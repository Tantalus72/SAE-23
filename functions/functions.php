<!-- FONCTION DE LOGIN -->
<?php
function login($email, $password) {
    $retour = false;
    
    $madb = getPDO(); // On récupère l'objet PDO
    
    // Requête préparée
    $requete = "SELECT EMAIL, PASS FROM utilisateurs WHERE EMAIL = ? AND PASS = ?";
    $stmt = $madb->prepare($requete);
    $stmt->execute([$email, $password]);

    $tableau = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (sizeof($tableau) != 0) {
        $retour = true; 
    }

    return $retour;
}

function isAdmin($mail){
    $retour = false ;
    
    $madb = getPDO(); // On récupère l'objet PDO

    $requete = "SELECT STATUT FROM utilisateurs WHERE EMAIL = ?" ;
    $stmt = $madb->prepare($requete);
    $stmt->execute([$mail]);

    $tableau_assoc = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($tableau_assoc)) {
        if ($tableau_assoc[0]["STATUT"] == 'admin') $retour = true;	
    } 
    return $retour;
}



// Fonction pour lister toutes les annonces
function listerAnnonces()	{
    $retour = false; // Retour par défaut en cas d'erreur

    try {

        $pdo = getPDO();
        
        // On écrit la requête pour récupérer toutes les annonces
        $sql = "SELECT 
                    a.*, 
                    m.nom as nom_marque, 
                    mo.nom as nom_modele 
                FROM annonces a
                JOIN marques m ON a.idMarque = m.idMarque
                JOIN modeles mo ON a.idModele = mo.idModele
                ORDER BY a.idAnnonce DESC";

        $stmt = $pdo->query($sql);
        $retour = $stmt->fetchAll(PDO::FETCH_ASSOC); // On stock les résultats dans $retour
        
        
    } catch (PDOException $e) {
        error_log("Erreur BDD: " . $e->getMessage());
    }
    
    return $retour;
}	


//Fonction pour récupérer UNE voiture précise dans la bdd
function getVoiture($id) {
    $retour = false ;
    try {

        $pdo = getPDO();
        $sql = $pdo->prepare("SELECT * FROM annonces WHERE idAnnonce = ?");
        $sql->execute([$id]);
        $voiture = $sql->fetch(PDO::FETCH_ASSOC);

        $retour = $voiture;

    } catch (PDOException $e) {
        error_log("Erreur modification: " . $e->getMessage());
    }

    return $retour;

}

// Fonction de suppression d'une annonce
function delete_annonce($id) {
    try {
        $pdo = getPDO();

        // Récupérer le chemin de l'image
        $stmtImg = $pdo->prepare("SELECT path_img FROM annonces WHERE idAnnonce = ?");
        $stmtImg->execute([$id]);
        $result = $stmtImg->fetch(PDO::FETCH_ASSOC); //on récupère le nom de l'image

        if ($result && !empty($result['path_img']) && $result['path_img'] !== 'default_img.png') {
            // Construction du chemin absolu correct
            $imagePath = __DIR__ . '/../ressources/images/annonces/' . $result['path_img'];

            // Vérifie si le fichier existe avant suppression
            if (file_exists($imagePath)) {
                unlink($imagePath);
            } else {
                error_log("Fichier image non trouvé : $imagePath");
            }
        }

        // Supprimer l'annonce de la BDD
        $stmtDelete = $pdo->prepare("DELETE FROM annonces WHERE idAnnonce = ?");
        return $stmtDelete->execute([$id]);

    } catch (PDOException $e) {
        error_log("Erreur suppression: " . $e->getMessage());
        return false;
    }
}

// Fonction de redirection
function redirect($url, $tps) {
    $temps = $tps * 1000; // On convertit en ms
    ?>
    <script>
        setTimeout(function() {
            window.location.href = '<?= $url ?>';
        }, <?= $temps ?>); // On éxecute cette redirection uniquement après le délai calculé
    </script>
    <?php
}

// Création de l'objet PDO
function getPDO() {
    try {

        $pdo = new PDO("sqlite:bdd/db.sqlite");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $pdo;
        
    } catch (PDOException $e) {
        die("Erreur connexion BDD: " . $e->getMessage());
    }
}

?>