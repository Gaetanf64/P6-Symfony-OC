# Projet Blog OpenClassRooms

[![Codacy Badge](https://app.codacy.com/project/badge/Grade/4e12fe2bde7b42a8b2e54488ee64f326)](https://www.codacy.com/gh/Gaetanf64/P6-Symfony-OC/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=Gaetanf64/P6-Symfony-OC&amp;utm_campaign=Badge_Grade)

## Descriptif du projet

Vous êtes chargé de développer le site répondant aux besoins de Jimmy. Vous devez ainsi implémenter les fonctionnalités suivantes : 

* un annuaire des figures de snowboard. Vous pouvez vous inspirer de la liste des figures sur Wikipédia. Contentez-vous d'intégrer 10 figures, le reste sera saisi par les internautes ;
* la gestion des figures (création, modification, consultation) ;
* un espace de discussion commun à toutes les figures.

Pour implémenter ces fonctionnalités, vous devez créer les pages suivantes :

* la page d’accueil où figurera la liste des figures ; 
* la page de création d'une nouvelle figure ;
* la page de modification d'une figure ;
* la page de présentation d’une figure (contenant l’espace de discussion commun autour d’une figure).

## Contraintes

Il faut que les URL de page permettent une compréhension rapide de ce que la page représente et que le référencement naturel soit facilité.

L’utilisation de bundles tiers est interdite sauf pour les données initiales. Vous utiliserez les compétences acquises jusqu’ici ainsi que la documentation officielle afin de remplir les objectifs donnés.

Le design du site web est laissé complètement libre, attention cependant à respecter les wireframes fournis pour le gabarit de vos pages. Néanmoins, il faudra que le site soit consultable aussi bien sur un ordinateur que sur mobile (téléphone mobile, tablette, phablette…).

En premier lieu, il vous faudra écrire l’ensemble des issues/tickets afin de découper votre travail méthodiquement et de vous assurer que l’ensemble du besoin client soit bien compris avec votre mentor. Les tickets/issues seront écrits dans un repository GitHub que vous aurez créé au préalable.

L’ensemble des figures de snowboard doivent être présentes à l’initialisation de l’application web. Vous utiliserez un bundle externe pour charger ces données. 

## Installation du projet 

* Cloner le projet avec gitclone 
```https://github.com/Gaetanf64/P6-Symfony-OC.git```
* Installer les dépendances 
``composer install``
* Copier le fichier .env_sample et le renommer en .env
* Mettre à jour la base de données en entrant votre nom d'utilisateur et le mot de passe dans le .env_sample:
```DATABASE_URL=mysql://user:password@127.0.0.1:3306/snowtricks```
* Pour les mails, changer l'adresse mail de récpetion et le mot de passe dans le .env.sample (si vous utlisez un autre host que gmail, entrez aussi les valeurs correspondants à votre host)
```MAILER_DSN=smtp://ADRESS:PASSWORD@smtp.gmail.com?verify_peer=0```
* Créer la base de données si elle n'existe pas déjà en entrant cette commande à la racine du projet : 
```php bin/console doctrine:database:create```
* Créer les tables du projet en appliquant les migrations : 
```php bin/console make:migration```
```php bin/console doctrine:migrations:migrate```
* Installer les DataFixtures (données initiales) : 
```php bin/console doctrine:fixtures:load```
* Pour créer un nouvel administrateur : créer vous un compte utilisateur depuis l'interface, puis mettre le champ 'is_admin' à 1 dans votre application de gestion de base de données