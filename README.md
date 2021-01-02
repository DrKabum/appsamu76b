Projet historique, gardé pour l'émotion qu'il procure :') Il s'agit de mon tout premier projet en programmation un peu sérieux !


samu76b
=======

Ce projet rassemble le travail pour les applications du SAMU 76B. A ce jour, elle sont composées de :

- GestionVM : une application de gestion et traçabilité des problèmes de véhicules et de matériel du SMUR.

## Récupérer le répository pour les nuls : 
- Installer une nouvelle instance de Symfony dans un dossier à part. Il sert de base pour récupérer certains fichiers ignorés dans le repository
- Dans le dossier où vous souhaitez travailler, clonez le repository
<code>
git clone https://github.com/drkabum/appsamu76b
</code>
- Cela va créer un sous dossier appsamu76b/ avec le contenu de ce dépot.
- A partir du dossier de l'installation de base de Symfony, copier les dossiers bin/, vendor/ et le fichier app/config/parameter.yml dans le dossier où vous avez cloné le répository.
- renseignez le fichier parameter.yml selon vos paramètres locaux de base de donnée.
- Installez composer pour finir l'installation avec "php composer.phar update"
- Travailler sur MAMP sur certains postes oblige à faire la manipulation suivante, dans le fichier app/config/parameters.yml, ajoutez la ligne suivante:

<code> 
parameters:  
    database_unix_socket:
</code>

En dehors de MAMP il n'est pas nécessaire d'ajouter une valeur. Si vous travaillez sur MAMP, ajoutez :

<code> 
parameters:  
    database_unix_socket: /Applications/MAMP/tmp/mysql/mysql.sock
</code>

- N'oubliez pas de mettre à jour le schéma BDD ou le créer avec     

<code>
php app/console doctrine:schema:update --dump-sql
</code>

<code>
php app/console doctrine:schema:update --force
</code>
- Voila !
