FROM php:8.4-apache

# --- CAMBIO CLAVE: Ejecutamos todo como root para evitar problemas de permisos ---
USER root

# Activamos el módulo de reescritura de Apache
RUN a2enmod rewrite

# Install system dependencies (dejamos esto por si la app lo necesita)
RUN apt-get update && \
    apt-get install -y libzip-dev && \
    docker-php-ext-install zip

# Establecemos el directorio y copiamos los archivos
WORKDIR /var/www/html
COPY . .
