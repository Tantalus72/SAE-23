

# ToDo - Projet **TrouveTaCaisse**

## 1. Animation 3D des voitures -> tiz a dit non (no fun)

* [ ] Animation à partir d'une image ? ou modèle 3D (VRM utile ?)
* [ ] Choisir la meilleure option (image + CSS/JS ? ou modèle .glb / .vrm via Three.js ?)

---

## 2. Fiche "Voir plus"

* [ ] Lors du clic : effacer toute la page sauf navbar + footer
* [ ] Agrandir l’annonce sélectionnée
* [ ] Les autres annonces vont en bas
* [ ] Respecter le barème → fiche toujours affichée

---

## 3. Bouton "Explorer"

* [X] Ne fonctionne pas actuellement
* [X] Rediriger vers la section des annonces (via ancrage ? ou JS `scrollIntoView()` ?)

---

## 4. Remplacer les mentions "auto-annonces"

* [X] Tout remplacer par **TrouveTaCaisse**

---

## 5. `modification.php`

* [ ] Formulaire dynamique pour choisir l'élément à modifier
* [ ] Captcha
* [ ] Pré-remplissage des champs via Ajax
* [ ] Vérification/modification via Ajax
* [ ] Afficher le résultat (succès/échec)

---

## 6. Ajouter modèle perso

* [ ] Permettre d’ajouter un modèle non présent
* [ ] Insérer dans la BDD

---

## 7. Ajouter une marque perso

* [ ] Même logique que les modèles

---

## 8. Attribut `état` à rajouter

Valeurs possibles :

* Épave
* Mauvais état
* État moyen
* Presque neuf
* Neuf

---

## 9. Logs de connexion

* [X] Créer un fichier **log sécurisé** (inaccessible via HTTP)
* [X] Enregistrer :

  * login
  * IP
  * date/heure
  * statut (ok / échec)
  * statut utilisateur (admin / user)

---

## 10. Vérif JS login (formulaire)

* [X] Vérifier présence d’au moins :

  * 1 majuscule
  * 1 caractère spécial (`!`, `*`, etc.)
* [X] Bloquer l’envoi si invalide

---

## 11. Sécurité : redirection si droits insuffisants

* [ ] Rediriger vers `index.php` si pas les droits pour :

  * insertion
  * suppression
  * modification

---

## 12. Insertion avec formulaire dynamique

* [ ] Vérification avec JS (obligatoire)
* [ ] Validation back + front

---

## 13. `index.php` : système de filtres

* [ ] Formulaire dynamique (avec Ajax)
* [ ] Tableau de base = toutes les annonces
* [ ] Sinon : tableau filtré

---

## 14. Erreurs d’insertion

* [ ] Vérifier et corriger les Warnings

---

## 15. Suppression avec formulaire dynamique

* [ ] Choix via formulaire
* [ ] Sélection en Ajax ou PHP

---

## 16. Test suppression invalide

* [ ] Provoquer une erreur volontairement
* [ ] Vérifier gestion côté serveur

---

## 17. Erreur suppression image

Exemple :

```php
Warning: unlink(../ressources/images/annonces/Capture d'écran 2025-05-11 191752.png): No such file or directory
```

* [ ] Corriger la suppression si le fichier n'existe pas
* [ ] Éviter les warnings via `file_exists()`

---

## 18. `suppression.php`

* [ ] Choix via formulaire dynamique (Ajax ou non)
* [ ] Même logique que modification

---

## 19. Remplir la BDD automatiquement

* [ ] Créer un script PHP ou SQL pour :

  * peupler des annonces
  * marques
  * modèles
  * utilisateurs

---

##  20. Mise en ligne sur serveur IUT

* [ ] Transfert des fichiers
* [ ] Vérification des droits
* [ ] Tests finaux sur le serveur



##  21. redirection insertion,suppresion et modification

