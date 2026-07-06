FROM composer:2.2 AS composer
WORKDIR /build/
COPY composer.json composer.lock .
RUN composer install --no-autoloader
COPY . .
RUN composer dump-autoload

FROM node:26 AS node
WORKDIR /build/
COPY package.json package-lock.json .
RUN npm config set registry https://npm-mirror.gitverse.ru
RUN npm install
COPY --from=composer /build/ .
RUN npm run build

FROM php:8.3-apache

RUN apt update
RUN apt install -y libzip-dev libpq-dev
RUN docker-php-ext-install zip pdo_pgsql

ENV APACHE_LOG_DIR=/var/log/apache2
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN a2enmod rewrite

WORKDIR /var/www/html/
COPY --from=node /build/ .
COPY .env.example .env
RUN php artisan key:generate
RUN chown www-data:www-data storage/ -R