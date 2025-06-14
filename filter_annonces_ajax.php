<?php
// =======================
// AJAX - Récupère les annonces filtrées
// =======================

session_start();
header('Content-Type: application/json; charset=UTF-8');
require_once 'functions/functions.php';

try {
    $pdo = getPDO();

    $clauses = []; // Nos condition du 'WHERE' dans la requete sql
    $params  = []; // param à mettre dans la requête préparée

    // Si chaque filtre est défini et non vide, on l'ajoute
    if (!empty($_POST['idMarque'])) {
        $clauses[] = 'a.idMarque = ?';
        $params[]  = (int)$_POST['idMarque'];
    }
    if (!empty($_POST['idModele'])) {
        $clauses[] = 'a.idModele = ?';
        $params[]  = (int)$_POST['idModele'];
    }
    if (!empty($_POST['anneeMin'])) {
        $clauses[] = 'a.annee >= ?';
        $params[]  = (int)$_POST['anneeMin'];
    }
    if (!empty($_POST['anneeMax'])) {
        $clauses[] = 'a.annee <= ?';
        $params[]  = (int)$_POST['anneeMax'];
    }
    if (!empty($_POST['kmMin'])) {
        $clauses[] = 'a.kilometrage >= ?';
        $params[]  = (int)$_POST['kmMin'];
    }
    if (!empty($_POST['kmMax'])) {
        $clauses[] = 'a.kilometrage <= ?';
        $params[]  = (int)$_POST['kmMax'];
    }
    if (!empty($_POST['prixMin'])) {
        $clauses[] = 'a.prix >= ?';
        $params[]  = (int)$_POST['prixMin'];
    }
    if (!empty($_POST['prixMax'])) {
        $clauses[] = 'a.prix <= ?';
        $params[]  = (int)$_POST['prixMax'];
    }


    // requete sql
    $sql = "
        SELECT 
            a.*, 
            m.nom  AS nom_marque, 
            mo.nom AS nom_modele
        FROM annonces a
        LEFT JOIN marques  m  ON a.idMarque = m.idMarque
        LEFT JOIN modeles mo ON a.idModele  = mo.idModele
    ";

    // si on a des conditions on les ajoute à la requête sql
    if ($clauses) {
        $sql .= ' WHERE ' . implode(' AND ', $clauses); // implode sert à rassembler les éléments d'un tableau en une chaine de caractères
    }

    // tri par date 
    $sql .= ' ORDER BY a.idAnnonce DESC'; 

    // execution de la requête
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $annonces = $stmt->fetchAll(PDO::FETCH_ASSOC);

    
    // réponse d'un JSON en cas de succès
    echo json_encode([
        'success' => true,
        'data'    => $annonces
    ]);

} catch (Exception $e) {
    // si erreur on retourne l'erreur dans le JSON
    echo json_encode([
        'success' => false,
        'error'   => $e->getMessage()
    ]);
}
