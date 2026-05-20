#!/bin/sh
set -e

if [ "${CONTAINER_ROLE}" = "app" ]; then
    # Ensure storage directory structure exists (matters when volume is fresh)
    mkdir -p \
        storage/framework/cache/data \
        storage/framework/sessions \
        storage/framework/views \
        storage/framework/testing \
        storage/app/public \
        storage/logs

    chown -R www-data:www-data storage bootstrap/cache
    chmod -R 775 storage bootstrap/cache

    # SQLite file lives inside the storage volume
    touch "${DB_DATABASE:-/var/www/html/storage/database.sqlite}"

    # Populate shared public volume for nginx (preserves symlinks)
    cp -rf /var/www/html/public/. /var/www/public/
    ln -sfn /var/www/html/storage/app/public /var/www/public/storage

    php artisan migrate --force --isolated
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
fi

exec docker-php-entrypoint "$@"
