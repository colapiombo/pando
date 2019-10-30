FROM php:7.3-fpm
ARG TIMEZONE

RUN chgrp -R 0 /var/www/html/
RUN chmod -R g=u /var/www/html/

RUN apt-get update

# Install iconv, mcryot, gd
RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libmcrypt-dev \
        libpng-dev \
    && docker-php-ext-install -j$(nproc) iconv \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd \
    && pecl install mcrypt-1.0.1 \
    && docker-php-ext-enable mcrypt


# Install intl
RUN apt-get install -y \
        libicu-dev \
        zlib1g-dev \
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


#opcache.validate_timestamps - production environments: 0
RUN usermod -u 1000 www-data
RUN echo 'date.timezone="America/Sao_Paulo"' >> /usr/local/etc/php/conf.d/date.ini
RUN echo 'opcache.enable=1' >> /usr/local/etc/php/conf.d/opcache.conf
RUN echo 'opcache.validate_timestamps=1' >> /usr/local/etc/php/conf.d/opcache.conf
RUN echo 'opcache.fast_shutdown=1' >> /usr/local/etc/php/conf.d/opcache.conf


# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
&& composer --version

# Set timezone
RUN ln -snf /usr/share/zoneinfo/${TIMEZONE} /etc/localtime && echo ${TIMEZONE} > /etc/timezone \
&& printf '[PHP]\ndate.timezone = "%s"\n', ${TIMEZONE} > /usr/local/etc/php/conf.d/tzone.ini \
&& "date"

RUN chgrp -R 0 /var/www/html/
RUN chmod -R g=u /var/www/html/

COPY ./www.conf /usr/local/etc/php-fpm.d/
COPY ./php.ini /usr/local/etc/php/
COPY ./php-fpm.conf /usr/local/etc/

WORKDIR /var/www/html

# Set host user as www-data user in container,
# allowing webroot files (Drupal) to be edited from host
RUN echo "Setting host user ID ${HOST_UID} for www-data..."
RUN usermod -o -u ${HOST_UID} www-data
RUN groupmod -o -g ${HOST_GID} www-data

RUN chown -R www-data:www-data /var/www
