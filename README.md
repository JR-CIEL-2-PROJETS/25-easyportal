Voici le repos de l'étudiant 3 ! 

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


[![Open in Visual Studio Code](https://classroom.github.com/assets/open-in-vscode-2e0aaae1b6195c2367325f4f02e2d04e9abb55f0b24a779b69b11b9e10269abc.svg)](https://classroom.github.com/online_ide?assignment_repo_id=17624743&assignment_repo_type=AssignmentRepo)

À faire pour l'application Android :
Écran de connexion :

1-

Créer une interface permettant à l'utilisateur d'entrer son nom d'utilisateur et son mot de passe.
Intégrer une requête API (GET /connexion/utilisateur) pour vérifier les identifiants.
Demande d'accès :

2-

Ajouter un formulaire permettant à un visiteur de demander :
Un accès temporaire (limité dans le temps).
Un accès illimité, soumis à validation par un administrateur.
Ouverture du portail :

3-

Implémenter un bouton/action pour envoyer une requête au serveur permettant d'ouvrir le portail.
Gestion des utilisateurs :

4-

Ajouter une fonctionnalité pour afficher la liste des utilisateurs via l'API (GET /liste_user).
Permettre à l'administrateur de modifier les rôles via l'API (POST /role_user).
Intégration des APIs :

5-

Connecter l'application aux endpoints du serveur Mock et gérer les réponses JSON.
Test de l'application :

-6

Tester chaque fonctionnalité avec Postman et l'application (connexion, demande d’accès, ouverture du portail).
Documentation utilisateur :

-7

Ajouter des instructions claires dans l'application (par exemple : si l'accès est refusé, ou en cas d'erreur réseau).
