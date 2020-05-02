FROM php:7.3-fpm

RUN pecl install xdebug
RUN docker-php-ext-enable xdebug

RUN docker-php-ext-install pdo_mysql mysqli


COPY ./conf/php.ini /usr/local/etc/php/
COPY ./conf/php-fpm.conf /usr/local/etc/