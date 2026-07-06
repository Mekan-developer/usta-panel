#!/bin/bash
set -e

echo "Waiting for MySQL..."
until php -r "new PDO('mysql:host=${DB_HOST:-mysql};port=${DB_PORT:-3306}', '${DB_USERNAME:-admin}', '${DB_PASSWORD:-secret}');" 2>/dev/null; do
    sleep 2
done
echo "MySQL ready."

# ---- ДОБАВЛЕНО: Кэширование роутов и вьюшек (Для Production) ----
# На локалке (local) кэшировать роуты и конфиги НЕ НАДО, иначе не увидишь изменений в коде.
# Проверяем среду, и если это production — кэшируем всё для максимальной скорости.
if [ "${APP_ENV}" = "production" ]; then
    echo "Caching config and routes for production..."
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
else
    echo "Clearing cache for development..."
    php artisan config:clear
    php artisan route:clear
    php artisan view:clear
fi

# ---- ИСПРАВЛЕНО: Генерация ключа (если забыли) ----
# Если APP_KEY пустой, генерируем его, чтобы приложение не падало
if [ -z "${APP_KEY}" ]; then
    echo "APP_KEY is missing. Generating one..."
    php artisan key:generate --force
fi

# ---- ДОБАВЛЕНО: Создание линка на хранилище ----
# Без этого загруженные картинки/файлы (storage/app/public) не будут видны из интернета
if [ ! -L "public/storage" ]; then
    echo "Creating storage link..."
    php artisan storage:link
fi

# Выполнение миграций
echo "Running migrations..."
php artisan migrate --force

echo "Application is ready!"

# Запуск основного процесса контейнера
exec "$@"
