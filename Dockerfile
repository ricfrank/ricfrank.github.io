FROM php:7.1-fpm-alpine

RUN apk update && \
    apk add zlib-dev mysql-client && \
    apk add bash libxml2-dev vim && \
    apk add freetype libpng libjpeg-turbo freetype-dev libpng-dev libjpeg-turbo-dev

RUN apk add --no-cache libmcrypt-dev libmcrypt

RUN docker-php-ext-configure pdo_mysql --with-pdo-mysql
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-configure zip
RUN docker-php-ext-install zip
RUN docker-php-ext-install soap
RUN docker-php-ext-configure soap
RUN docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/
RUN docker-php-ext-install gd
RUN docker-php-ext-configure mcrypt
RUN docker-php-ext-install mcrypt


RUN curl -sS https://getcomposer.org/installer | php
RUN mv composer.phar /usr/local/bin/composer

RUN adduser soisy -D
USER soisy

WORKDIR /var/www