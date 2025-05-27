# ⚙️ EasyPortal – Partie Opérative

EasyPortal est un système IoT de contrôle de portail intelligent basé sur la détection automatique de plaques d'immatriculation.  
Cette partie du projet gère les opérations embarquées : détection via caméra, traitement OCR, communication réseau, journalisation et commande du portail via Arduino.

---

## 🎯 Objectif

Assurer le contrôle automatique du portail en analysant les plaques d’immatriculation et en interagissant avec une base de données distante pour autoriser ou refuser l’ouverture.  
Cette logique est embarquée sur un **Raspberry Pi**, qui communique avec un **Arduino** pour commander le moteur.

---

## 🔍 Fonctionnalités

### 📸 Détection de plaques
- Analyse d’un flux vidéo en temps réel via caméra Pi ou USB  
- Utilisation de `EasyOCR` pour détecter les caractères  
- Nettoyage et formatage du texte détecté

### 🧠 Vérification des autorisations
- Envoi de la plaque au serveur via une requête API REST  
- Réception de la décision (autorisé, refusé, blacklist)  
- Log de chaque tentative (plaque, date, heure, statut)

### 🔌 Contrôle matériel du portail
- Commande d’un moteur DC via un **L298N** et un **Arduino UNO**  
- Ouverture/fermeture du portail sur signal validé via socket TCP/IP  
- Clignotement d’un signal lumineux (LED) lors des mouvements du portail

### 📡 Communication réseau
- Serveur TCP/IP pour recevoir les ordres de l’app Android  
- Client REST pour interroger la base de données distante  
- Possibilité d’utiliser un tunnel (ex. `ngrok`) pour usage hors LAN

---

## 🔗 Technologies utilisées

- 🧠 **Raspberry Pi 3 B+** – Système embarqué  
- 🐍 **Python 3** – Langage principal  
- 🔍 **EasyOCR** – Détection de texte sur les plaques  
- 📷 **OpenCV** – Capture vidéo et traitement d’image  
- 🌐 **Flask / Socket** – API REST et serveur TCP  
- ⚙️ **Arduino UNO + L298N** – Contrôle moteur à courant continu  
- 🎥 **Caméra Pi ou USB** – Entrée vidéo

---

## 🧪 Tests & Déploiement

- Testé sur **Raspberry Pi OS 64 bits**  
- Communication testée en **réseau local** et **à distance**  
- Intégration avec **base de données MySQL distante**  
- Arduino programmé via **IDE Arduino (C++)**

---

## 🔒 Sécurité

- Communication API via **HTTP** (HTTPS recommandé en production)  
- Authentification gérée côté serveur  
- Possibilité de filtrer les **IP autorisées** sur le réseau local

---

## 📈 À venir

- Ajout d’**alertes** en cas de tentative non autorisée  
- Système de reconnaissance améliorée (**Deep Learning**)  
- Intégration d’une **batterie de secours** pour la motorisation

---

## 👨‍💻 Auteur

**Ivan Surnin**  
Développement de la partie opérationnelle (Raspberry Pi, Arduino, détection OCR, contrôle du portail)
