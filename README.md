# Easy Portal  

## Tâches pour l'Application Android  

### 1. **Écran de connexion**  
- Créer une interface permettant à l'utilisateur de saisir son nom d'utilisateur et son mot de passe.  
- Intégrer une requête API (`GET /connexion/utilisateur`) pour vérifier les identifiants.  

### 2. **Demande d'accès**  
- Ajouter un formulaire pour qu'un visiteur puisse demander :  
  - Un accès temporaire (limité dans le temps).  
  - Un accès illimité (validé par un administrateur).  

### 3. **Ouverture du portail**  
- Implémenter un bouton ou une action pour envoyer une requête au serveur permettant d'ouvrir le portail.  

### 4. **Gestion des utilisateurs**  
- Afficher la liste des utilisateurs via l'API (`GET /Dashboard/GestionUser/liste_user`).  
- Permettre à l'administrateur de modifier les rôles via l'API (`POST /role_user`).  

### 5. **Intégration des APIs**  
- Connecter l'application aux endpoints du serveur Mock et gérer les réponses JSON.  

### 6. **Tests de l'application**  
- Tester toutes les fonctionnalités (connexion, demande d'accès, ouverture du portail) avec Postman et l'application Android.  

### 7. **Documentation utilisateur**  
- Ajouter des instructions claires dans l'application :  
  - Que faire en cas d'accès refusé.  
  - Comment gérer une erreur réseau.  

---

## Liens Utiles  
- [Trello](https://trello.com/b/0rJY5Std/easyportal-projet)  
- [Cahier des charges](https://drive.google.com/file/d/1jTqu-DFb7wzD0Egm1XIkNRtG2iQ96qns/view)  
- [Ressources UML/Étudiants](https://drive.google.com/drive/folders/113utU2sftZKCHBuI6aiLEg9IuT4zaUXk)  
- [Figma](https://www.figma.com/proto/nLCbqOJC40aEosKBdooski/Untitled?node-id=1-2&p=f&t=kWOY56ctfhEp92Rq-0&scaling=min-zoom&content-scaling=fixed&page-id=0%3A1&starting-point-node-id=1%3A2)  
