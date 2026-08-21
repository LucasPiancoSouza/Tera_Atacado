FROM php:8.4-apache
RUN docker-php-ext-install pdo pdo_mysql
WORKDIR /var/www/html
COPY public/ /var/www/html/
EXPOSE 80