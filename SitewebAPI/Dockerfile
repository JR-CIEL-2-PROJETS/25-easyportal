# Utiliser l'image officielle PHP avec FPM et extensions nécessaires
FROM php:8.3-fpm-alpine

# Installer les extensions PHP nécessaires (ajuste selon ton besoin)
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Définir le dossier de travail
WORKDIR /var/www/html

# Copier les fichiers HTML, CSS, JS et PHP dans le conteneur
COPY ./html /var/www/html

# Donner les bons droits (évite les problèmes de permissions)
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

# Exposer le port utilisé par PHP-FPM
EXPOSE 9000

# Démarrer PHP-FPM
CMD ["php-fpm"]
