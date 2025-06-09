FROM php:8.3-fpm-alpine

# ⚠️ Remplacer HTTPS par HTTP pour éviter les erreurs SSL
RUN sed -i 's|https://dl-cdn.alpinelinux.org|http://dl-cdn.alpinelinux.org|g' /etc/apk/repositories

# 🔐 Installer les certificats SSL et curl
RUN apk --no-cache add ca-certificates curl && update-ca-certificates

# 📦 Installer les dépendances système
RUN apk add --no-cache \
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

# 🧩 Installer les extensions PHP nécessaires
RUN docker-php-ext-install pdo pdo_mysql mysqli

# 📁 Définir le dossier de travail
WORKDIR /var/www/html

# 📂 Copier les fichiers de l'application
COPY ./html /var/www/html

# 🔐 Définir les permissions
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

# 🌐 Exposer le port FPM
EXPOSE 9000

# ▶️ Lancer PHP-FPM
CMD ["php-fpm"]
