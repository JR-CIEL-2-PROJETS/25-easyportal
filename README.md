# 🚪 EasyPortal Android App

EasyPortal est une application Android développée dans le cadre d’un projet IoT permettant le contrôle d’un portail motorisé à distance. L’application communique avec un système embarqué (Raspberry Pi) pour autoriser ou refuser l’ouverture du portail en fonction de l'utilisateur connecté, de la plaque détectée, et des autorisations définies.

---

## 📱 Fonctionnalités

### 🔐 Authentification
- Connexion sécurisée via identifiants
- Deux types d'utilisateurs :
  - **Administrateur**
  - **Utilisateur** (mode total ou réduit)

---

### 👤 Utilisateur
- Peut **ouvrir le portail** si autorisé
- En **mode total** : peut ouvrir même sans correspondance de plaque
- En **mode réduit** : ouverture uniquement si plaque reconnue
- Enregistrement automatique d’un **log** (date, heure, plaque, nom utilisateur)

---

### 🛠️ Administrateur
- Ouvre le portail à tout moment
- Visualise le **flux vidéo** d'une caméra IP
- Gère la **base de données des plaques** :
  - Ajouter/supprimer des plaques autorisées
  - Ajouter des plaques interdites (**blacklist**)

---

## 🔗 Technologies utilisées

- **Java / Kotlin** – Android Studio
- **MySQL** – Base de données distante
- **PHP / API REST** – Pour échanges avec le backend
- **TCP/IP Socket Client** – Communication avec le Raspberry Pi
- **Caméra IP** – Pour la visualisation en temps réel

---

## 🧪 Test & Déploiement

- Testé via **émulateur Android** ou **tablette réelle**
- Connecté au même réseau que le Raspberry Pi (ou via internet)
- Base de données hébergée sur un serveur distant ou local

---

## 📂 Architecture de l'application

