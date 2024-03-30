# Образ php + fpm + alpine из внешнего репозитория
FROM php:7.4.23-fpm-alpine3.13 as base
 
# Задаем расположение рабочей директории
WORKDIR /var/www/application

# Copy the composer.json and composer.lock files to the container
COPY composer.json composer.lock ./

# Install specific version of Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer --version=2.3.5

# Install dependencies
RUN composer install

ENV JWT_SECRET=secret-for-jwt1212

RUN set -xe \
    && docker-php-ext-install -j$(nproc) pdo \
    && docker-php-ext-install -j$(nproc) pdo_mysql
  
FROM base

# https://docs.docker.com/engine/reference/builder/#copy
COPY . ./

# Give access to upload folder
RUN chmod 777 ./public/upload

# Expose port 9000 and start php-fpm server
EXPOSE 9000
