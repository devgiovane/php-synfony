ARG PHP_VERSION=8.1
FROM php:${PHP_VERSION}-fpm-alpine
WORKDIR /var/www/code
RUN rm -rf /var/www/html
# Install Basic
RUN apk update && apk add --no-cache \
    bash \
    curl \
    make \
    libzip-dev \
    libressl-dev \
    pcre-dev $PHPIZE_DEPS
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
