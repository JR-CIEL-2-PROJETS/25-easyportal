# Utiliser l'image officielle PHP avec FPM et Alpine
FROM php:8.3-fpm-alpine

# Installer les certificats SSL avant de faire quoi que ce soit d'autre
RUN apk add --no-cache ca-certificates && update-ca-certificates

# Mettre à jour les paquets et installer les dépendances
RUN apk update && apk add --no-cache \
    libxml2-dev \
    oniguruma-dev \
    autoconf \
    g++ \
    gcc \
    make \
    libc-dev \
    pkgconf \
    re2c \
    file

# Installer les extensions PHP nécessaires
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Définir le dossier de travail
WORKDIR /var/www/html

# Copier les fichiers du projet dans le conteneur
COPY ./html /var/www/html

# Donner les bons droits
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

# Exposer le port utilisé par PHP-FPM
EXPOSE 9000

# Démarrer PHP-FPM
CMD ["php-fpm"]
