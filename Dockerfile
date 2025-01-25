# Utiliser l'image officielle Nginx
FROM nginx:latest

# Copier la configuration Nginx personnalisée (optionnel)
COPY ./nginx.conf /etc/nginx/nginx.conf

# Copier tes fichiers (HTML/CSS/JS) dans le répertoire racine d'Nginx
COPY ./html /usr/share/nginx/html
