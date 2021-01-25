# ToDo & Co

<a href="https://codeclimate.com/github/WainlaiN/todoV2/maintainability"><img src="https://api.codeclimate.com/v1/badges/038d6d437a52dce7989e/maintainability" /></a>

[![Codacy Badge](https://app.codacy.com/project/badge/Grade/01a1a2b6771f4c3a8d5c8b970635b01f)](https://www.codacy.com/gh/WainlaiN/todoV2/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=WainlaiN/todoV2&amp;utm_campaign=Badge_Grade)


# P8-ToDo & Co

Améliorez une application existante de ToDo & Co.

## Description

Ainsi, pour ce dernier projet de spécialisation, vous êtes dans la peau d’un développeur expérimenté en charge des tâches suivantes :
```
Corrections d'anomalies. 
Implémentation de nouvelles fonctionnalités.
Implémentation de tests automatisés.
```

## Prérequis

Choisissez votre serveur en fonction de votre système d'exploitation:

    - Windows : WAMP (http://www.wampserver.com/)
    - MAC : MAMP (https://www.mamp.info/en/mamp/)
    - Linux : LAMP (https://doc.ubuntu-fr.org/lamp)
    - XAMP (https://www.apachefriends.org/fr/index.html)

## Installation
1. Clonez ou téléchargez le repository GitHub dans le dossier voulu :
```
    git clone https://github.com/WainlaiN/todoV2
```
2. Configurez vos variables d'environnement tel que la connexion à la base de données/serveur SMTP/adresse mail dans le fichier `.env.local` (faire une copie du .env.test) :
```
    DATABASE_URL=mysql://DB_LOGIN:DB_PASSWORD@127.0.0.1:3306/DB_NAME?serverVersion=5.7

    MAILER_DSN=gmail://email:password@default
```
3. Téléchargez et installez les dépendances back-end du projet avec [Composer](https://getcomposer.org/download/) :
```
    composer install
```
4. Téléchargez et installez les dépendances front-end du projet avec [Yarn](https://classic.yarnpkg.com/en/docs/install) :
```
    Yarn install
```
5. Créer un build d'assets (grâce à Webpack Encore) avec [Yarn](https://classic.yarnpkg.com/en/docs/install) :
```
    Yarn build (en prod)
    Yarn watch (en dev)
```
6. Créez la base de données si elle n'existe pas déjà, taper la commande ci-dessous en vous plaçant dans le répertoire du projet :
```
    php bin/console doctrine:database:create
```
7. Créez les tables de la base de données :
```
    php bin/console doctrine:migrations:migrate
```

8. (Optionnel) Installer les fixtures pour avoir une démo de données fictives :
```
    php bin/console doctrine:fixtures:load
```
9. Lancement du serveur :
```
    php bin/console server:run
```

10. Le projet est maintenant installé, vous pouvez tester l'application sur cette URL:

```
    http://127.0.0.1:8000
```

11. Pour contribuer au projet :

    Consultez [contributing.md](https://github.com/WainlaiN/todoV2/blob/master/docs/contributing.md).

12. Authentification :

    Consultez [authentication.md](https://github.com/WainlaiN/todoV2/blob/master/docs/Authentication.md)?


## Auteur

**Dupriez Nicolas** - Étudiant à Openclassrooms Parcours suivi Développeur d'application PHP/Symfony