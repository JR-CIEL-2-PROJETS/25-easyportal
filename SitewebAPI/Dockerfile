FROM php:8.3-fpm-alpine

# 🔐 Certificats SSL à jour
RUN apk --no-cache add ca-certificates curl \
 && curl -o /etc/ssl/certs/ca-certificates.crt https://curl.se/ca/cacert.pem \
 && update-ca-certificates

# 📦 Paquets nécessaires
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

# 📦 Extensions PHP
RUN docker-php-ext-install pdo pdo_mysql mysqli

WORKDIR /var/www/html
COPY ./html /var/www/html
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

EXPOSE 9000
CMD ["php-fpm"]
