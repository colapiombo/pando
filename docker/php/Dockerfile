FROM php:7.3-fpm
ARG TIMEZONE

RUN chgrp -R 0 /var/www/html/
RUN chmod -R g=u /var/www/html/

ARG DEBIAN_FRONTEND=noninteractive


RUN apt-get update && apt-get install -y --fix-missing \
    apt-utils \
    nano\
    gnupg

RUN echo "deb http://packages.dotdeb.org jessie all" >> /etc/apt/sources.list
RUN echo "deb-src http://packages.dotdeb.org jessie all" >> /etc/apt/sources.list
RUN curl -sS --insecure https://www.dotdeb.org/dotdeb.gpg | apt-key add -


# Install intl
RUN apt-get update && apt-get install -y \
        libicu-dev \
    zlib1g-dev \
    libzip-dev\
        g++ \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl

# Install mbstring
RUN docker-php-ext-install mbstring

# Install curl
RUN apt-get install -y libcurl4-openssl-dev \
    && docker-php-ext-install curl

# Install zip
RUN docker-php-ext-install zip

# Install json
RUN docker-php-ext-install json

# Install extensions through the scripts the container provides
# Here we install the pdo_mysql extensions to access MySQL.
RUN docker-php-ext-install pdo pdo_mysql opcache

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



#opcache.validate_timestamps - production environments: 0
RUN usermod -u 1000 www-data
RUN echo 'date.timezone="Europa/Helsinki"' >> /usr/local/etc/php/conf.d/date.ini
RUN echo 'opcache.enable=1' >> /usr/local/etc/php/conf.d/opcache.conf
RUN echo 'opcache.validate_timestamps=1' >> /usr/local/etc/php/conf.d/opcache.conf
RUN echo 'opcache.fast_shutdown=1' >> /usr/local/etc/php/conf.d/opcache.conf

# Set timezone
RUN ln -snf /usr/share/zoneinfo/${TIMEZONE} /etc/localtime && echo ${TIMEZONE} > /etc/timezone \
&& printf '[PHP]\ndate.timezone = "%s"\n', ${TIMEZONE} > /usr/local/etc/php/conf.d/tzone.ini \
&& "date"

RUN chown -R www-data:www-data /var/www

RUN chgrp -R 0 /var/www/html/
RUN chmod -R g=u /var/www/html/

COPY ./www.conf /usr/local/etc/php-fpm.d/
COPY ./php.ini /usr/local/etc/php/
COPY ./php.conf /usr/local/etc/



