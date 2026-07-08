FROM php:8.2-apache

# Installation des dépendances et activation du rewrite
RUN apt-get update && apt-get install -y libzip-dev zip && \
    docker-php-ext-install zip && \
    a2enmod rewrite

WORKDIR /var/www/html
COPY . .

# RÉGLAGE DES DROITS : Permet à PHP d'écrire partout sans erreur 403 ou Permission Denied
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 777 /var/www/html

EXPOSE 80
CMD ["apache2-foreground"]
