FROM php:7.4-apache

RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

RUN pecl install -o -f redis \
&&  rm -rf /tmp/pear \
&&  docker-php-ext-enable redis

# COPY frontend/ /var/www/html/