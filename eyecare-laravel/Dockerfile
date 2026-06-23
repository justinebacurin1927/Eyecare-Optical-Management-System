FROM node:22-alpine AS node-build
WORKDIR /app
COPY package.json ./
RUN npm install
COPY . .
RUN npm run build

FROM php:8.3-fpm-alpine AS base
RUN apk add --no-cache \
    libzip-dev \
    unzip \
    curl \
    && docker-php-ext-install pdo_mysql bcmath zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY composer.json ./
RUN COMPOSER_DISABLE_AUDIT=1 composer install --no-dev --no-scripts --no-autoloader

COPY . .
COPY --from=node-build /app/public/build /var/www/public/build

RUN COMPOSER_DISABLE_AUDIT=1 composer install --no-dev --optimize-autoloader \
    && php artisan storage:link \
    && php artisan route:cache || true

EXPOSE 9000

CMD ["php-fpm"]
