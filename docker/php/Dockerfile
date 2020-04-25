FROM php:7.3-fpm
ARG TIMEZONE
ARG DEBIAN_FRONTEND=noninteractive


RUN apt-get update && apt-get install -y --fix-missing \
    apt-utils \
    nano\
    gnupg

# Install json
RUN docker-php-ext-install json

#MySQLi
RUN docker-php-ext-install mysqli
RUN docker-php-ext-enable mysqli


#Xdebug
RUN pecl install xdebug redis \
    && docker-php-ext-enable xdebug redis \
    && echo "xdebug.remote_enable=on" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.remote_autostart=off" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.remote_port=9001" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.remote_handler=dbgp" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.remote_connect_back=0" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.idekey=mertblog.net" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.remote_host=docker.for.mac.localhost" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

RUN docker-php-ext-enable xdebug;

RUN chown -R www-data:www-data /var/www
RUN chgrp -R 0 /var/www/html/
RUN chmod -R g=u /var/www/html/

COPY ./www.conf /usr/local/etc/php-fpm.d/
COPY ./php.ini /usr/local/etc/php/
COPY ./php.conf /usr/local/etc/



