FROM php:8.3-fpm-alpine
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Démarrage de PHP-FPM lorsque le conteneur démarre
CMD ["php-fpm"]

# Utiliser l'image officielle Nginx
FROM nginx:latest

# Copier la configuration Nginx personnalisée (optionnel)
COPY ./nginx.conf /etc/nginx/nginx.conf

# Copier tes fichiers (HTML/CSS/JS) dans le répertoire racine d'Nginx
COPY ./code/html /usr/share/nginx/html

