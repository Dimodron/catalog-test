FROM php:8.3-fpm-alpine

RUN apk add --no-cache \
    bash git unzip icu-dev oniguruma-dev libzip-dev \
    && docker-php-ext-install intl pdo_mysql mbstring zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
