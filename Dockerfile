FROM php:8.2-apache

# Installation de PDO
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Activation du module Apache rewrite
RUN a2enmod rewrite

# Configuration des permissions pour le répertoire /var/www/html
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html