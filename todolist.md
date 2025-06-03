1 - Animation 3D des voitures (à partir d'une image ??? ou modele 3d pur vrm utiles ?)
2 - Voir plus à finir -> effacer toute la page sauf la navbat et le footer + voir l'annonnce en grand (les autres annonces sont deplacé en bas (cf bareme -> tout le temps affiché))
3 - bouton exploer ne marche pas (redirige vers les annonces via un ancrage ?)
4 - Changer tous les auto-annonces par des TrouveTaCaisse
5 - Modification.php à faire -> choix de l'element via formulaire dynamyque, captcha, en reponse formulaire pre rempli à modifier, verification via ajax et le serv affichera les modification, indiquer si reussi ou pas
6 - Rajouté model perso dans les modéles à inserer(on rajoute un modele qui n'est pas present dans la liste pour ensuite l'ajouter dans la bdd) ?
7 - Meme chose avec les marques
8 - Rajouter un attribut etat ( épave, mauvais état, état moyen, presque neuf, neuf)
9 - Finir Log (consigne : Dans les deux cas, on enregistrera dans un fichier de log (inaccessible via HTTP) le login, l’adresse IP et l’heure de tentative de connexion avec le statut de connexion (connexion ok ou pas) et le statut de la personne en cas de réussite.)
10 - faire la verif du caractere special avec du js (consigne: Un script **Javascript** vérifiera que le login saisi contient un moins une majuscule et un caractère spécial ( !,*, etc.) , sinon l’envoi du formulaire sera bloqué.)
11 - l'utilisateur doit etre redirigé vers index.php si pas de droit (insetion,suppresion,modifcation)
12 - insertion a un formulaire dynamique ? le formulaire doit etre verifié avec du js
13 - index.php doit avoir avec un systeme de filtre avec formulaire dynamqiue (ajax) (de base tableau avec tout sinon tableau filtré)
14 - lors d'une erreur d'insertion il y a des warnings
15 - suppresion avec formulaire dynamique ?
16 - verifier si la suppresion invalide (provoquer une erreur) fonctionne si non -> corriger le probleme
17 - erreur dans la suppresion d'image 
exemple : 
array(1) { ["path_img"]=> string(38) "Capture d'écran 2025-05-11 191752.png" }
Warning: unlink(../ressources/images/annonces/Capture d'écran 2025-05-11 191752.png): No such file or directory in /home/trouvetacaisse/www/functions/functions.php on line 80
18 - suppression.php -> Le choix de l’élément à modifier se fera à l’aide d’un formulaire dynamique
19 - Remplir BDD (script pour automatiser ?)
20 - mettre sur le serv de l'iut 