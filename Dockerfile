ARG PHP_VERSION=8.1
FROM php:${PHP_VERSION}-fpm-alpine
WORKDIR /var/www/code
RUN rm -rf /var/www/html
# Install Basic
RUN apk update && apk add --no-cache \
    wget \
    bash \
    curl \
    make \
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
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php && \
    php -r "unlink('composer-setup.php');" && \
    mv composer.phar /usr/local/bin/composer
