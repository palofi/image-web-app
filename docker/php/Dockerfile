FROM php:7.3-fpm-alpine

# Install modules
RUN apk update && apk upgrade \
    && apk add git openssh libzip-dev icu-dev sqlite-dev libpng-dev libxml2-dev \
    && docker-php-ext-install bcmath mbstring calendar zip pdo_mysql pdo_sqlite mysqli intl gd xml sockets

RUN apk add --no-cache \
    $PHPIZE_DEPS

RUN pecl install redis-3.1.6 xdebug \
    && docker-php-ext-enable redis xdebug \
    && echo "xdebug.remote_enable=on" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.remote_connect_back=off" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.remote_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.idekey=PHPSTORM" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.remote_autostart=on" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# Install composer and global dependencies
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer global require --no-progress hirak/prestissimo

RUN rm /usr/local/etc/php-fpm.d/www.conf
COPY docker/php/www.conf /usr/local/etc/php-fpm.d
COPY docker/php/php.ini /usr/local/etc/php/php.ini

# HACK FOR iconv https://github.com/docker-library/php/issues/240#issuecomment-327992638
RUN apk add gnu-libiconv --update-cache --repository http://dl-cdn.alpinelinux.org/alpine/edge/community/ --allow-untrusted
ENV LD_PRELOAD /usr/lib/preloadable_libiconv.so php

EXPOSE 9001
WORKDIR /var/www
