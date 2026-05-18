#!/bin/sh
set -eu

echo "Waiting for PHP-FPM on 127.0.0.1:9000..."

for i in $(seq 1 60); do
    if php -r 'exit(@fsockopen("127.0.0.1", 9000) ? 0 : 1);' >/dev/null 2>&1; then
        echo "PHP-FPM is ready."
        break
    fi

    if [ "$i" -eq 60 ]; then
        echo "PHP-FPM did not become ready in time." >&2
        exit 1
    fi

    sleep 1
done

mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

php artisan config:clear --no-interaction || true
php artisan view:clear --no-interaction || true

if [ "${RUN_MIGRATIONS:-false}" = "true" ]; then
    php artisan migrate --force --no-interaction || true
fi

php artisan storage:link --no-interaction || true

echo "Laravel init completed."
