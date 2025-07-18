# Usar una imagen oficial de PHP 8.4 con FPM (FastCGI Process Manager)
FROM php:8.4-fpm-alpine

# Instalar dependencias del sistema y extensiones de PHP necesarias para Laravel
RUN apk add --no-cache \
    libzip-dev \
    zip \
    && docker-php-ext-install pdo pdo_mysql zip

# Instalar Composer para gestionar las dependencias de PHP
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Establecer el directorio de trabajo dentro del contenedor
WORKDIR /var/www/html