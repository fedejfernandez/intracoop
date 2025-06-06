# Etapa 1: Instalar dependencias de PHP con Composer
FROM composer:2.7 as vendor
WORKDIR /app
COPY database/ database/
COPY composer.json composer.lock ./
# Instala solo dependencias de producción, sin scripts y de forma optimizada
RUN composer install --no-interaction --no-dev --no-scripts --prefer-dist

# Etapa 2: Construir los assets de frontend con Node.js
FROM node:18-alpine as assets
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm install
COPY . .
RUN npm run build

# Etapa 3: Crear la imagen final de producción
FROM php:8.3-fpm-alpine

# Instalar extensiones de PHP esenciales para Laravel
RUN docker-php-ext-install pdo pdo_mysql bcmath

# Establecer el directorio de trabajo
WORKDIR /var/www/html

# Copiar el código fuente y las dependencias desde las etapas anteriores
COPY --from=vendor /app/vendor/ ./vendor/
COPY --from=assets /app/public/build/ ./public/build/
COPY . .

# Ajustar permisos para que Laravel pueda escribir en las carpetas de storage y cache
# El usuario 'www-data' es el que usa PHP-FPM dentro del contenedor
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache && \
    chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Exponer el puerto estándar de PHP-FPM
EXPOSE 9000

# El comando por defecto para iniciar el contenedor
CMD ["php-fpm"] 