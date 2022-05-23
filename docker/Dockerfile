FROM php:8-fpm

RUN apt-get update \
  && apt-get install -y \
             curl \
             bash

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

WORKDIR /var/www