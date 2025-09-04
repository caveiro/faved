# Usamos la imagen oficial de PHP
FROM php:8.4-apache

# Activamos el módulo de reescritura de Apache para las URLs limpias
RUN a2enmod rewrite

# Instalamos dependencias del sistema que necesita la app
RUN apt-get update && \
    apt-get install -y libzip-dev && \
    docker-php-ext-install zip

# Establecemos el directorio de trabajo
WORKDIR /var/www/html

# Copiamos todos los archivos del repositorio
COPY . .

# --- LA SOLUCIÓN DEL GITHUB ISSUE ---
# Cambiamos los permisos de la carpeta storage para que sea escribible por todos
RUN chmod 777 /var/www/html/storage
