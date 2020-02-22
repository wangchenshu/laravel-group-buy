FROM php:alpine

# RUN apk update
# RUN apk add libzip-dev zip
# RUN docker-php-ext-install zip
RUN docker-php-ext-install -j$(nproc) pdo_mysql
