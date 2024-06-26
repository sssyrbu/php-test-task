FROM php:8.0-apache

RUN apt-get update 

RUN docker-php-ext-install gd mysqli pdo pdo_mysql zip

RUN a2enmod rewrite

COPY . /var/www/html/

EXPOSE 80
