# syntax=docker/dockerfile:1
FROM php:8.2-fpm-bullseye AS base

# Packages for PHP extensions
RUN apt-get update && apt-get install -y \
    git unzip libicu-dev libzip-dev libpng-dev libjpeg-dev libfreetype6-dev \
    libonig-dev libxml2-dev libssl-dev \
 && docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install -j"$(nproc)" \
    pdo_mysql bcmath intl zip gd opcache \
 && pecl install redis \
 && docker-php-ext-enable redis \
 && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

# Composer (from official image)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy app source
COPY . /var/www/html

# Optimize permissions for runtime dirs
RUN mkdir -p storage bootstrap/cache \
 && chown -R www-data:www-data storage bootstrap/cache \
 && find storage -type d -exec chmod 775 {} \; \
 && find storage -type f -exec chmod 664 {} \; \
 && chmod -R 775 bootstrap/cache

# Install PHP deps
RUN composer install --no-dev --prefer-dist --optimize-autoloader || true

# Optional: Build assets with Node (uncomment if nécessaire)
# FROM node:20-alpine AS assets
# WORKDIR /app
# COPY package*.json vite.config.* /app/
# RUN npm ci
# COPY . /app
# RUN npm run build
#
# FROM base AS app
# COPY --from=assets /app/public/build /var/www/html/public/build

CMD ["php-fpm"]
