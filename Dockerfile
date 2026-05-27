# Stage 1: frontend build
FROM node:20-alpine AS frontend-builder

WORKDIR /app

COPY frontend/package*.json ./frontend/
RUN cd frontend && if [ -f package-lock.json ]; then npm ci; else npm install; fi

COPY backend ./backend
COPY frontend ./frontend
RUN cd frontend && npm run build

# Stage 2: production server
FROM php:8.2-fpm-alpine

RUN apk add --no-cache \
    bash \
    curl \
    freetype-dev \
    git \
    libjpeg-turbo-dev \
    libpng-dev \
    libxml2-dev \
    libzip-dev \
    oniguruma-dev \
    unzip \
    zip

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd opcache zip

RUN { \
        echo 'upload_max_filesize=100M'; \
        echo 'post_max_size=110M'; \
        echo 'max_file_uploads=20'; \
    } > /usr/local/etc/php/conf.d/uploads.ini

RUN mkdir -p /run/nginx

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY backend .
COPY frontend/resources /var/frontend/resources
COPY --from=frontend-builder /app/backend/public/build ./public/build

RUN composer install --no-dev --optimize-autoloader --no-interaction \
    && mkdir -p /run/nginx storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache \
    && chown -R www-data:www-data /var/www \
    && chmod -R 775 storage bootstrap/cache

COPY backend/docker/railway-start.sh /usr/local/bin/railway-start

RUN chmod +x /usr/local/bin/railway-start

HEALTHCHECK --interval=30s --timeout=5s --start-period=20s --retries=3 \
    CMD curl -fsS "http://127.0.0.1:${PORT:-8080}/health" || exit 1

EXPOSE 8080

CMD ["/usr/local/bin/railway-start"]
