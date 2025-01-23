Voici le repos du groupe pour le projet Final EasyPortal !

Trello : https://trello.com/b/0rJY5Std/easyportal-projet

Cahier des charges : https://drive.google.com/file/d/1jTqu-DFb7wzD0Egm1XIkNRtG2iQ96qns/view

Drive Ressource du Groupe (Diagramme UML/Objectifs Etudiants) : https://drive.google.com/drive/folders/113utU2sftZKCHBuI6aiLEg9IuT4zaUXk

# Easy Portal

## Description du Projet
Easy Portal est un projet de gestion et de contrôle d’accès pour un portail automatisé. Il permet aux utilisateurs, visiteurs et administrateurs de gérer les accès de manière fluide et sécurisée via une application Android connectée à un serveur mock.

---

## Objectifs

1. **Gestion des utilisateurs** :
   - Ajouter, supprimer et modifier les informations des utilisateurs.
   - Différencier les rôles (administrateur, utilisateur, visiteur).

2. **Gestion des accès** :
   - Accorder des accès temporaires aux visiteurs.
   - Gérer des accès permanents pour les utilisateurs et administrateurs.

3. **Connexion sécurisée** :
   - Authentification via un serveur (Mock Server) avec gestion des identifiants.
   - Validation des rôles pour attribuer les permissions.

4. **Automatisation** :
   - Lecture automatique des plaques d’immatriculation pour l’ouverture du portail.
   - Demande d’ouverture via l’application Android.

---

## Fonctionnalités Principales

1. **Connexion Utilisateur** :
   - Authentification via identifiant et mot de passe.

2. **Gestion des utilisateurs** :
   - Consultation de la liste des utilisateurs via une API REST.
   - Ajout, suppression et modification des comptes.

3. **Ouverture du portail** :
   - Demande d’ouverture via l’application Android.
   - Lecture des plaques pour une ouverture intelligente.

4. **Gestion des accès visiteurs** :
   - Accès temporaire demandé par l’application.
   - Validation des accès illimités par un administrateur.

---

## Structure Technique

1. **Application Android** :
   - Interface utilisateur pour les accès et la gestion des utilisateurs.
   - Langages utilisés : Kotlin/Java.

2. **Mock Server** :
   - Serveur simulé pour tester les API REST.
   - Fonctionnalités :
     - Liste des utilisateurs.
     - Authentification.
     - Gestion des rôles.

3. **Exemples d’API REST** :
   - Connexion utilisateur :
     ```
     GET /connexion/utilisateur?username=medy&password=medy77230
     ```
   - Liste des utilisateurs :
     ```
     GET /Dashboard/GestionUser/liste_user
     ```

---

## Organisation Collaborative

- **Branches Git** :
  - Une branche par membre de l’équipe pour isoler les travaux.
  - Pull requests et revue de code pour valider les contributions.

- **Gestion des Tâches** :
  - Tableau Kanban pour suivre l’état des tâches.
  - Priorisation des développements.

---

## Membres de l’Équipe
- **Chef de projet** : Coordination et suivi du projet.
- **Développeur Android** : Création de l’application mobile.
- **Backend Engineer** : Gestion du serveur mock et des APIs.

---

## Comment Lancer le Projet

1. Clonez le dépôt :
   ```bash
   git clone <url-du-depot>
   ```

2. Installez les dépendances nécessaires (selon la stack technique).

3. Lancez le Mock Server pour tester les fonctionnalités API.

4. Exécutez l’application Android via Android Studio.

---

## Améliorations Futures

- Ajout de notifications pour les accès refusés.
- Intégration d’un système de logs pour suivre les demandes d’accès.
- Mise en production sur un serveur cloud.

---




