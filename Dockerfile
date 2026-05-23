# ── Stage 1: PHP dependencies ─────────────────────────────────────────────────
FROM composer:2 AS vendor

WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install \
        --no-dev \
        --no-scripts \
        --no-autoloader \
        --prefer-dist \
        --ignore-platform-reqs

# ── Stage 2: Frontend assets ───────────────────────────────────────────────────
FROM node:20-alpine AS assets

ARG VITE_APP_NAME="Usta panel"
ARG VITE_REVERB_APP_KEY
ARG VITE_REVERB_HOST=localhost
ARG VITE_REVERB_PORT=8080
ARG VITE_REVERB_SCHEME=http

WORKDIR /app
COPY package*.json ./
RUN npm ci --prefer-offline
COPY . .
COPY --from=vendor /app/vendor ./vendor
RUN npm run build

# ── Stage 3: PHP runtime ───────────────────────────────────────────────────────
FROM php:8.3-fpm-alpine

# Runtime libs (stay in image)
RUN apk add --no-cache \
        freetype-dev \
        libjpeg-turbo-dev \
        libpng-dev \
        libwebp-dev \
        libzip-dev \
        oniguruma-dev \
        sqlite-dev \
        curl \
        unzip

# Build tools only needed for pecl — removed after compilation
RUN apk add --no-cache --virtual .build-deps \
        autoconf g++ make \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j$(nproc) \
        gd bcmath pcntl pdo_sqlite zip opcache \
    && pecl install igbinary redis \
    && docker-php-ext-enable igbinary redis \
    && apk del .build-deps \
    && rm -rf /tmp/pear

COPY docker/php/local.ini /usr/local/etc/php/conf.d/local.ini
COPY docker/php/zz-fpm.conf /usr/local/etc/php-fpm.d/zz-fpm.conf

# Composer from official image (not from vendor stage)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .
COPY --from=vendor /app/vendor ./vendor
COPY --from=assets /app/public/build ./public/build

RUN composer dump-autoload --optimize \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 9000
ENTRYPOINT ["/entrypoint.sh"]
CMD ["php-fpm"]
