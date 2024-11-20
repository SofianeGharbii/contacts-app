# Application de Gestion de Contacts

## Vue d'ensemble
L'Application de Gestion de Contacts est un outil web conçu pour simplifier la gestion des informations.  
Elle permet d'effectuer des opérations **CRUD** (Créer, Lire, Modifier, Supprimer) avec des fonctionnalités avancées telles que :  
- **Recherche dynamique**.  
- **Validation côté serveur et client** pour garantir l'intégrité des données.  

---

## Fonctionnalités
- **Ajout, Modification et Suppression de Contacts** : Gestion complète des contacts via une interface conviviale et triable.  
- **Recherche Dynamique** : Filtrage instantané des contacts à l'aide d'une barre de recherche réactive.  
- **Validation côté Serveur et client** : Vérification des données saisies pour éviter les erreurs.  
- **API RESTful** : Points d'accès pour interagir avec le serveur.  
- **systeme utilisé** : **Windows**.  

Un serveur public est disponible pour tester l'application :  
**http://contacts-app.ct.ws/index.html**

---

## Prérequis
- **PHP 8**  
- **MySQL 5.7 ou supérieur**  
- Un serveur web (**Apache** ou **Nginx**)
- fonctionner sur Linux et Windows

---

## Installation

### Étape 1 : Cloner le projet
```bash
git clone https://github.com/SofianeGharbii/contacts-app.git
branch master

### Étape 2 : Créer la base de données et les tables
- Dans le dossier database, un script automatisé est inclus. Exécutez-le pour configurer la base de données et table contacts :

php database/create_db_and_table.php


### Étape 3 : Démarrer le serveur local

- Sous Windows (avec XAMPP) :
- Lancer XAMPP (Apache et MySQL activés).
- Accéder au projet via **http://localhost/contact-app/index.html**
