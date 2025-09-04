FROM php:8.4-apache

# Install system dependencies
RUN apt-get update && \
    apt-get install -y libzip-dev && \
    docker-php-ext-install zip

WORKDIR /var/www/html
COPY . .
