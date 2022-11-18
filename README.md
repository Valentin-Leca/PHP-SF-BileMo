# PHP-SF-BileMo

BileMo est une entreprise offrant toute une sélection de téléphones mobiles haut de gamme.

Le business modèle de BileMo n’est pas de vendre directement ses produits sur le site web, mais de fournir à toutes les plateformes qui le souhaitent l’accès au catalogue via une API (Application Programming Interface). Il s’agit donc de vente exclusivement en B2B (business to business).

Exposer un certain nombre d’API pour que les applications des autres plateformes web puissent effectuer des opérations.

# Besoin client

- Consulter la liste des produits BileMo ;
- Consulter les détails d’un produit BileMo ;
- Consulter la liste des utilisateurs inscrits liés à un client sur le site web ;
- Consulter le détail d’un utilisateur inscrit lié à un client ;
- Ajouter un nouvel utilisateur lié à un client ;
- Supprimer un utilisateur ajouté par un client.

Seuls les clients référencés peuvent accéder aux API. Les clients de l’API doivent 
être authentifiés via JWT.

**Outils Requis :**

PHP version >= 8.1

Composer version >= 2.4.1

Symfony CLI version >= 5.4.13

WampServer

MySQL version >= 8.0.29

PHPMyAdmin

**Installation :**

Ouvrez une interface de commande et cloner le repository dans un dossier ( "git clone
https://github.com/Valentin-Leca/PHP-SF-BileMo.git" )

Se placer à la racine du projet et faire un "composer install" pour installer tous
les bundles associés au projet présent dans le fichier composer.lock

Faites une copie de votre fichier .env que vous renommez en '.env.local' et modifiez
la partie "DATABASE_URL" avec vos informations de base de données (nom utilisateur,
mdp, nom de la bdd ...).

Faire la commande "php bin/console doctrine:schema:create" pour créer la base de
donnée.

Lancer la commande "symfony console doctrine:fixtures:load" pour créer les données de
test (Customers, Users, Phones)

Une fois ces étapes réalisées, lancer WampServer puis faites "symfony serve -d" en
ligne de commande à la racine du projet.

Rendez-vous sur Postman pour tester les différents endpoints !
