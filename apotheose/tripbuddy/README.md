# Projet Symfony "Tripbuddy"

Ce dépôt contient le code source de l' application "TripBuddy" qui permet de gérer des utilisateurs et des itinéraires de voyage.

## Installation

Suivez ces étapes pour installer et exécuter le projet sur votre machine locale.

### Prérequis

Avant de commencer, assurez-vous d'avoir installé les éléments suivants sur votre machine :

- [Composer](https://getcomposer.org/download/)
- [Symfony CLI](https://symfony.com/download)
  
### Instructions

1. Clonez ce dépôt vers votre machine locale : git@github.com:O-clock-X-Ray/projet-5-carnet-voyageur-back.git OU BIEN
2. Créez le projet Symfony: composer create-project symfony/skeleton Tripbuddy
3. Accédez au répertoire du projet : cd Tripbuddy
4. Faites les commandes : composer require --dev symfony/maker-bundle et composer require symfony/webapp-pack
5. Installez les dépendances avec Composer : composer install
6. Configurez votre base de données en éditant le fichier .env : vous pouvez consulter la documentation Symfony ici-> https://symfony.com/doc/current/configuration.html#configuring-environment-variables
7. Créez la base de données: - php bin/console doctrine:database:create
8. Créer les Entités: php bin/console make:entity
9. Appliquez les migrations :- php bin/console make:migration
10. Exécutez ensuite les migrations => php bin/console doctrine:migrations:migrate
    
#### Utilisation

    - Vous pouvez utiliser un outil tel qu'Insomnia ou Postman pour tester les différentes routes de l'API.


Merci d'avoir choisi "TripBuddy" !
