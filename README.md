samu76b
=======

Ce projet rassemble le travail pour les applications du SAMU 76B. A ce jour, elle sont composées de :

- GestionVM : une application de gestion et traçabilité des problèmes de véhicules et de matériel du SMUR.

## Récupérer le répository pour les nuls : 
- Installer une nouvelle instance de Symfony dans un dossier.
- créer un dossier pour le développement et y cloner le répository.
- A partir du dossier de la nouvelle installation de Symfony, copier les dossiers bin/, vendor/ et le fichier app/config/parameter.yml dans le dossier où vous avez cloné le répository.
- renseignez le fichier parameter.yml selon vos paramètres locaux
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

- Installez composer pour finir l'installation avec "php composer.phar update"
- Voila !
