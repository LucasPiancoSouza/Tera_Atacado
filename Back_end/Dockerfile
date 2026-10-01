FROM php:8.4-apache
WORKDIR /var/www/html
RUN docker-php-ext-install pdo pdo_mysql mysqli
COPY public/ /var/www/html/
COPY config/ /var/www/config/
EXPOSE 80