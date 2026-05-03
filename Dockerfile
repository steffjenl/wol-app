FROM php:8.4-apache

RUN docker-php-ext-install sockets

RUN a2enmod rewrite

RUN sed -i 's/Listen 80/Listen 4021/' /etc/apache2/ports.conf && \
    sed -i 's/<VirtualHost \*:80>/<VirtualHost *:4021>/' /etc/apache2/sites-enabled/000-default.conf

COPY app/ /var/www/html/

RUN chown -R www-data:www-data /var/www/html

EXPOSE 4021
