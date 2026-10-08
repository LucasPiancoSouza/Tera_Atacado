FROM php:8.4-apache
WORKDIR /var/www/html
# Extensões necessárias para o projeto
RUN docker-php-ext-install pdo pdo_mysql mysqli
# Ativa o mod_rewrite
RUN a2enmod rewrite
# Permite o uso de .htaccess
RUN printf '<Directory /var/www/html>\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>\n' \
    > /etc/apache2/conf-available/tera-atacado.conf \
    && a2enconf tera-atacado
EXPOSE 80