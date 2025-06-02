# Utiliser l'image officielle PHP avec FPM et Alpine
FROM php:8.3-fpm-alpine

# Mettre à jour les dépôts obsolètes si nécessaire
RUN sed -i 's/dl-cdn.alpinelinux.org/old.alpinelinux.org/g' /etc/apk/repositories

# Installer les paquets requis pour compiler les extensions PHP
RUN apk update && apk add --no-cache \
    ca-certificates \
    libxml2-dev \
    oniguruma-dev \
    autoconf \
    g++ \
    gcc \
    make \
    libc-dev \
    pkgconf \
    re2c \
    file \
    && update-ca-certificates

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
