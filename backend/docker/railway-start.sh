#!/bin/sh
set -eu

PORT="${PORT:-8080}"

echo "Preparing Laravel..."

mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache
chmod -R 775 storage bootstrap/cache

php artisan config:clear --no-interaction || true
php artisan route:clear --no-interaction || true
php artisan view:clear --no-interaction || true

if [ "${RUN_MIGRATIONS:-true}" = "true" ]; then
    php artisan migrate --force --no-interaction
fi

if [ "${RUN_DEFAULT_USERS:-true}" = "true" ]; then
    php artisan db:seed --class=UserSeeder --force --no-interaction
fi

php artisan storage:link --force --no-interaction || true

echo "Starting Laravel on 0.0.0.0:${PORT}..."
exec php \
    -d upload_max_filesize=100M \
    -d post_max_size=110M \
    artisan serve --host=0.0.0.0 --port="${PORT}"
