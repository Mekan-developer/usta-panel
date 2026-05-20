# ── Stage 1: build frontend assets ────────────────────────────────────────────
FROM node:20-alpine AS assets

# VITE_ vars are baked into the bundle at build time
ARG VITE_APP_NAME="Usta panel"
ARG VITE_REVERB_APP_KEY
ARG VITE_REVERB_HOST=localhost
ARG VITE_REVERB_PORT=8080
ARG VITE_REVERB_SCHEME=http

WORKDIR /app
COPY package*.json ./
RUN npm ci --prefer-offline
COPY . .
RUN npm run build

# ── Stage 2: PHP runtime ───────────────────────────────────────────────────────
FROM php:8.3-fpm-alpine

RUN apk add --no-cache \
        freetype-dev \
        libjpeg-turbo-dev \
        libpng-dev \
        libwebp-dev \
        libzip-dev \
        oniguruma-dev \
        sqlite-dev \
        curl \
        unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j$(nproc) \
        gd \
        bcmath \
        pcntl \
        pdo_sqlite \
        zip \
        opcache \
    && pecl install igbinary redis \
    && docker-php-ext-enable igbinary redis \
    && rm -rf /tmp/pear

COPY docker/php/local.ini /usr/local/etc/php/conf.d/local.ini
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json composer.lock ./
RUN composer install \
        --no-dev \
        --no-scripts \
        --no-autoloader \
        --prefer-dist

COPY . .
COPY --from=assets /app/public/build ./public/build

RUN composer dump-autoload --optimize \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 9000
ENTRYPOINT ["/entrypoint.sh"]
CMD ["php-fpm"]
