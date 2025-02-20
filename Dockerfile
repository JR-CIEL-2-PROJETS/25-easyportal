FROM php:8.3-fpm-alpine

# Installe les dépendances système nécessaires (Pour les Requetes en SQL)
RUN apk add --no-cache \
    mysql-client \
    php8-pdo \
    php8-pdo_mysql \
    php8-pdo_sqlite

RUN docker-php-ext-install pdo pdo_mysql pdo_sqlite

# Démarrage de PHP-FPM lorsque le conteneur démarre
CMD ["php-fpm"]

# Utiliser l'image officielle Nginx
FROM nginx:latest

# Copier la configuration Nginx personnalisée (optionnel)
COPY ./nginx.conf /etc/nginx/nginx.conf

# Copier tes fichiers (HTML/CSS/JS) dans le répertoire racine d'Nginx
COPY ./code/html /usr/share/nginx/html

