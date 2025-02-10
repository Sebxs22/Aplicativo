# Usa una imagen de PHP con Nginx preconfigurado
FROM php:8.1-fpm

# Instala extensiones necesarias
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Copia los archivos de tu aplicación PHP al contenedor
WORKDIR /var/www/html
COPY . .

# Instala y configura Nginx
RUN apt-get update && apt-get install -y nginx
COPY default.conf /etc/nginx/sites-available/default

# Expone el puerto 80 para el tráfico web
EXPOSE 80

# Comando para ejecutar Nginx y PHP-FPM
CMD service nginx start && php-fpm
