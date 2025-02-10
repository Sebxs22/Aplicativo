FROM php:8.1-fpm

# Instalar dependencias necesarias
RUN apt-get update && apt-get install -y nginx

# Instalar extensiones de PHP necesarias
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Configurar Nginx
COPY default.conf /etc/nginx/sites-available/default

# Copiar código PHP al contenedor
WORKDIR /var/www/html
COPY . .

# Permisos correctos para PHP y Nginx
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html

# Exponer puerto 80
EXPOSE 80

# Iniciar PHP-FPM y Nginx correctamente
CMD service nginx start && php-fpm -F
