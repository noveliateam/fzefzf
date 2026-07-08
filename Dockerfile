# Utilise une image PHP stable avec Apache
FROM php:8.2-apache

# Installation des dépendances nécessaires
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    && docker-php-ext-install zip \
    && a2enmod rewrite

# Configuration d'Apache pour forcer index.php comme fichier par défaut
RUN echo "DirectoryIndex index.php" >> /etc/apache2/apache2.conf

# Définition du répertoire de travail
WORKDIR /var/www/html

# Copie de tout le code source dans le conteneur
COPY . .

# Configuration des permissions pour que PHP puisse lire/écrire
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html

# Exposition du port 80
EXPOSE 80

# Lancement d'Apache
CMD ["apache2-foreground"]
