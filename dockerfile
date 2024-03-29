# Образ php + fpm + alpine из внешнего репозитория
FROM php:7.4.23-fpm-alpine3.13 as base
 
# Задаем расположение рабочей директории
ENV WORK_DIR /var/www/application

ENV JWT_SECRET=secret-for-jwt1212

RUN set -xe \
    && docker-php-ext-install -j$(nproc) pdo \
    && docker-php-ext-install -j$(nproc) pdo_mysql
  
FROM base

# Copy the composer.json and composer.lock files
COPY composer.json composer.lock ./

# Install a specific version of Composer (e.g., Composer 2.1.9)
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer --version=2.3.5 \
    && php -r "unlink('composer-setup.php');"

# Install the project dependencies using the specific Composer version
RUN composer install --no-scripts --no-autoloader

# https://docs.docker.com/engine/reference/builder/#copy
COPY . ${WORK_DIR}
 
# Expose port 9000 and start php-fpm server
EXPOSE 9000
