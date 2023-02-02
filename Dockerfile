FROM php:7.4-apache

WORKDIR /watch_store_backend

COPY . .

RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    && docker-php-ext-install zip \
    && docker-php-ext-install pdo_mysql \
    && a2enmod rewrite

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN composer install

COPY docker/vhost.conf /etc/apache2/sites-available/000-default.conf

EXPOSE 8000