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

Seuls les clients référencés peuvent accéder aux API. Les clients de l’API doivent être authentifiés via JWT.
