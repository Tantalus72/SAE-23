<?php function login($email, $password) {
    $retour = false;
    $madb = new PDO('sqlite:bdd/comptes.sqlite');
    $mail = $madb->quote($email);
    $pass = $madb->quote($password);
    $requete = "SELECT EMAIL, PASS FROM utilisateurs WHERE EMAIL = $mail AND PASS = $pass";

    $resultats = $madb->query($requete);
    $tableau = $resultats->fetchAll(PDO::FETCH_ASSOC);

    if (sizeof($tableau) != 0) {
        $retour = true; 
    }

    return $retour;
}


function isAdmin($mail){
    $retour = false ;
    // 1. Création d'un objet PDO
    $madb = new PDO('sqlite:bdd/comptes.sqlite'); 

    // 2. Ecriture de la requête
    $mail= $madb->quote($mail);
    // $pass = $madb->quote($pass);
    $requete = "SELECT STATUT FROM utilisateurs WHERE EMAIL = $mail" ;
    //var_dump($requete);echo "<br/>";  	

    // 3. Execution de la requête
    $resultat = $madb->query($requete);

    // 4. Récupération des résultats
    $tableau_assoc = $resultat->fetchAll(PDO::FETCH_ASSOC);

    // 5. Traitement des résultats s'il y en a
    if (!empty($tableau_assoc)) {
        if ($tableau_assoc[0]["STATUT"] == 'admin') $retour = true;	
    } 
    return $retour;
}


function listerAnnonces()	{
    $retour = false;
    try {
        $pdo = new PDO('sqlite:bdd/db.sqlite');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $sql = "SELECT 
                    a.*, 
                    m.nom as nom_marque, 
                    mo.nom as nom_modele 
                FROM annonces a
                JOIN marques m ON a.idMarque = m.idMarque
                JOIN modeles mo ON a.idModele = mo.idModele
                ORDER BY a.idAnnonce DESC";

        $stmt = $pdo->query($sql);
        $retour = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
    } catch (PDOException $e) {
        error_log("Erreur BDD: " . $e->getMessage());
        $retour = false;
    }
    
    return $retour;
}	



// Fonction de suppression d'une annonce
function delete_annonce($id) {
    try {
        $pdo = getPDO();
        $temp = $pdo->prepare("SELECT path_img FROM annonces WHERE idAnnonce = ?");
        $temp->execute([$id]);
        $temp = $temp->fetch(PDO::FETCH_ASSOC);
        var_dump($temp);
        unlink("../ressources/images/annonces/".$temp["path_img"]);

        $stmt = $pdo->prepare("DELETE FROM annonces WHERE idAnnonce = ?");
        return $stmt->execute([$id]);
    } catch (PDOException $e) {
        error_log("Erreur suppression: " . $e->getMessage());
        return false;
    }
}


function redirect($url, $tps) {
    $temps = $tps * 1000;
    ?>
    <script type="text/javascript">
        setTimeout(function() {
            window.location.href = '<?= $url ?>';
        }, <?= $temps ?>);
    </script>
    <?php
}


// Fonction de connexion PDO (si non existante)
function getPDO() {
    try {
        return new PDO('sqlite:bdd/db.sqlite');
    } catch (PDOException $e) {
        die("Erreur connexion BDD: " . $e->getMessage());
    }
}

?>