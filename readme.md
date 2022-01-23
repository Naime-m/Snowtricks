Pour installer le projet il faudra :

1 ) Créer un fichier .env en se basant sur le fichier .env.example :

Ce fichier est strictement confidentiel puisqu'il contiendra des données sensibles
tels que des mots de passe
Placez le fichier dans le même dossier que le fichier .env.example

---------------------------------------
APP_ENV=dev
APP_SECRET=example
MAILER_DSN=smtp://user:pass@smtp.example.com:port
---------------------------------------

Il faudra remplacer 'user' par votre adresse mail et 'pass' par votre mot de passe
Pour le smtp : https://symfony.com/doc/5.4/mailer.html#using-a-3rd-party-transport
Choisissez le fournisseur de votre email et remplacez selon les instructions

---------------------------------------
MAILER_SENDER=adressemail@mail.fr
DATABASE_URL="mysql://test:@127.0.0.1:3306/mydatabase?serverVersion=5.7"
---------------------------------------

Ici après MAILER_SENDER il faudra indiquer l'adresse email depuis laquelle vous souhaitez envoyer les mails provenant du site

Après DATABASE_URL, il faudra indiquer l'adresse de votre base de données : 
https://symfony.com/doc/current/doctrine.html#configuring-the-database
Vous aurez les instructions à suivre selon votre base de données ici

Il faudra ensuite lancer la commande : php bin/console doctrine:database:create
Pour cela il faudra ouvrir votre invite de commande :
http://codeur-pro.fr/invite-de-commande-et-terminal/



2) Il faudra mettre la base de données à jour en utilisant à nouveau l'invite de commandes et lancer :
doctrine:schema:update 

Ceci mettra à jour la base de données

3) Il ne restera plus qu'à installer les données fournies avec la commande :
php bin/console doctrine:fixtures:load

https://symfony.com/bundles/DoctrineFixturesBundle/current/index.html#loading-fixtures

