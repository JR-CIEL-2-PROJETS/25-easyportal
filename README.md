# 🔐 Mise en service du projet EasyPortal

## 🗂 Sommaire

* [🧠 Description des dossiers](#-description-des-dossiers)
* [🚀 Mise en service](#-mise-en-service)

  * [1. Clonage du projet](#1-clonage-du-projet)
  * [2. Lancer les services backend et frontend](#2-lancer-les-services-backend-et-frontend)
  * [3. Installer les dépendances](#3-installer-les-dépendances)
  * [4. Configurer le Raspberry Pi](#4-configurer-le-raspberry-pi)
  * [5. Installer et configurer l’application mobile](#5-installer-et-configurer-lapplication-mobile)


---

## 🧠 Description des dossiers

### 🔧 `SitewebAPI/`

* Interface administrateur : ajout/suppression plaques, gestion utilisateurs, supervision, etc.
* Code PHP des services API (gestion des utilisateurs, plaques, logs, etc.).
* Utilise Docker avec Nginx et PHP.

### 📱 `APPLI/`

* Application Android (Android Studio).
* Authentification, ouverture de portail, log, visualisation flux (admin).


---

## 🚀 Mise en service

### 1. Clonage du projet

```bash
git clone -b Déploiement https://github.com/JR-CIEL-2-PROJETS/25-easyportal.git
cd 25-easyportal
```

### 2. Lancer les services backend et frontend

Depuis la racine du projet, allez dans le dossier suivant et lancez la commande suivante :

```bash
cd SitewebAPI
sudo docker-compose up -d
```

Cette commande va démarrer les services du Site Web et de l'API

### ⚠️ Accès à l’interface web

Depuis un navigateur :

```url
http://{ip-machine}:8849/
```

**Note :** Une alerte peut apparaître si le certificat est auto-signé. Acceptez l’exception de sécurité.


### 4. Configurer le Raspberry Pi

#### 🧱 Raspberry Pi :

* Brancher la raspberry
* Le vehicule doit se presenter devant le capteur pour lancer la reconnaissance de plaques
  

---

### 5. Installer et configurer l’application mobile
⚠️ D'abord suivre [2. Lancer les services backend et frontend](#2-lancer-les-services-backend-et-frontend) pour lancer l'API et que les requêtes soit fonctionnel

Depuis un téléphone Android :

* Compiler et installer l'application via Android Studio **ou** APK
* Lors du premier lancement, cliquer sur l'engrenage pour configurer :

  * IP du backend (API)
  * IP du Raspberry Pi (serveur TCP)

---


---

### 6. Stopper la mise en service du Site web et de l'API

Depuis la racine du projet, allez dans le dossier suivant et lancez la commande suivante :

```bash
cd SitewebAPI
docker-compose down
```

