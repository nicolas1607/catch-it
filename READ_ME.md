        
            // Bienvenue sur Catch'it ! //


L'application permet de gérer vos collections parmis celle proposées sur le site.
En ajoutant les items que vous possedez déjà, vous augmentez leur popularité auprès
des autres utilisateurs. Il vous est également possible de donner votre avis sur un item.



    1. Installation

        1.1 Installer les prérequis

> composer install


        1.2 Initialiser la base de donnée

> symfony console doc:data:create
> symfony console doc:schema:update --force
> symfony console doc:fix:load
> symfony console doc:data:import db.sql



    2. Compte administrateur

En tant que formateur du CEFIM, il vous est fournit un compte 
administrateur qui vous donnera accès à la liste des utilisateurs, ainsi 
qu'à la gestion des collections, des items et des commentaires :

> Adresse email : mauger@cefim.eu
> Mot de passe : mauger


        2.1 Gestion administrateur

La liste des collections vous permet de :

    - consulter une collection
    - ajouter un nouvel item
    - modifier une collection
    - supprimer une collection

La liste des items vous permet de :

    - consulter un item
    - modifier un item
    - supprimer un item

La liste des commentaires vous permet de :

    - valider un commentaire
    - refuser un commentaire
    


    3. Compte utilisateur

Un utilisateur ne pourra ajouter aucune collection ni aucun item. Il pourra
en revanche ajouter à ses collections, une des celles proposées par les
administrateur et ajouter les items qu'il possède déjà.

Il lui est également possible de noter un item /5 ou encore de laisser un 
commentaire, qui sera valider ou refuser par l'admin s'il ne respecte pas la charte
du site.



    4. Compte visiteur

En tant que visiteur du site, vous n'avez pas la possibilité d'intéragir avec les
collections ou les items. Vous pouvez cependant consulter la totalité des collections, 
des items, de leur note globale ainsi que de leurs commentaires.