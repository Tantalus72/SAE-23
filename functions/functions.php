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
        
        // Création Objet PDO (@param->chaine de co bdd)
        $madb = new PDO('sqlite:bdd/db.sqlite');

        // Ecriture de la req
        $sql = "SELECT * FROM annonces";

        // Execution de la req
        $res = $madb -> query($sql);
        
        // Récupération de la réponse sous forme de tablea
        $tab_assoc = $res->fetchAll(PDO::FETCH_ASSOC);
        // Traitement des info s'il y en a

        if (sizeof($tab_assoc) != 0) {
            $retour = $tab_assoc;
        } 

    }// fin try
    catch (Exception $e) {		
        echo "Erreur BDD" . $e->getMessage();		
    }	// fin catch
    
    return $retour;
}	

?>