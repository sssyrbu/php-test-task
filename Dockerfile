FROM php:8.0-apache

RUN apt-get update && apt-get install -y \
    libpng-dev \
    zlib1g-dev \
    libxml2-dev \
    libzip-dev \
    libonig-dev \
    zip \
    curl \
    unzip

RUN docker-php-ext-install gd mysqli pdo pdo_mysql zip

RUN a2enmod rewrite

COPY . /var/www/html/

EXPOSE 80
