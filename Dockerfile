ARG PHP_VERSION=8.1
FROM php:${PHP_VERSION}-fpm-alpine
WORKDIR /var/www/code
RUN rm -rf /var/www/html
# Install Basic
RUN apk update && apk add --no-cache \
    bash \
    curl \
    libzip-dev \
    libressl-dev \
    pcre-dev $PHPIZE_DEPS
# Install Dockerize
ENV DOCKERIZE_VERSION v0.6.1
RUN wget https://github.com/jwilder/dockerize/releases/download/$DOCKERIZE_VERSION/dockerize-linux-amd64-$DOCKERIZE_VERSION.tar.gz && \
    tar -C /usr/local/bin -xzvf dockerize-linux-amd64-$DOCKERIZE_VERSION.tar.gz && \
    rm dockerize-linux-amd64-$DOCKERIZE_VERSION.tar.gz
# Install extensions
RUN docker-php-ext-install pdo && \
    docker-php-ext-install pdo_mysql && \
    docker-php-ext-install zip
# Install Redis e Mongo
RUN pecl install redis && \
    docker-php-ext-enable redis && \
    pecl install mongodb && \
    docker-php-ext-enable mongodb
# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

