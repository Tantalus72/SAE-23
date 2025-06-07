<?php
session_start();
header('Content-Type: application/json; charset=UTF-8');

require_once 'functions/functions.php';

$response = ['error' => null, 'success' => null];

try {
    // Vérification de l'accès admin
    if (empty($_SESSION) || !isAdmin($_SESSION['email'] ?? '')) {
        throw new Exception("Accès non autorisé");
    }

    

    // Connexion BDD
    $pdo = getPDO();

    // Données reçues
    $idMarque    = $_POST['idMarque'] ?? null;
    $idModele    = $_POST['idModele'] ?? null;
    $designation = htmlspecialchars(trim($_POST['designation'] ?? ''));
    $annee       = (int) ($_POST['annee'] ?? 0);
    $kilometrage = (int) ($_POST['kilometrage'] ?? 0);
    $prix        = (int) ($_POST['prix'] ?? 0);

    // Vérification des champs obligatoires
    if (!$idMarque || !$idModele || !$designation || !$annee || !$kilometrage || !$prix) {
        throw new Exception("Tous les champs sont obligatoires.");
    }

    // Répertoire cible pour l'image
    $upload_dir = realpath(__DIR__ . '/ressources/image/annonces');
    if (!$upload_dir) {
        throw new Exception("Dossier d’upload introuvable.");
    }

    $imageName = 'default_img.png'; // par défaut

    if (isset($_FILES['img']) && $_FILES['img']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['img']['tmp_name'];
        $fileName = basename($_FILES['img']['name']);
        $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($extension, $allowedTypes)) {
            throw new Exception("Type de fichier non autorisé.");
        }

        if ($_FILES['img']['size'] > 2 * 1024 * 1024) {
            throw new Exception("Image trop volumineuse (>2 Mo).");
        }

        // Nom unique pour éviter les collisions
        $uniqueName = uniqid('annonce_', true) . '.' . $extension;
        $destPath = $upload_dir . '/' . $uniqueName;

        if (!move_uploaded_file($fileTmpPath, $destPath)) {
            throw new Exception("Erreur lors du téléchargement de l'image.");
        }

        $imageName = $uniqueName;
    }

    // Insertion en base
    $stmt = $pdo->prepare("
        INSERT INTO annonces 
            (idMarque, idModele, designation, annee, kilometrage, prix, path_img)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $idMarque,
        $idModele,
        $designation,
        $annee,
        $kilometrage,
        $prix,
        $imageName
    ]);

    $response['success'] = "Annonce créée avec succès !";

} catch (Exception $e) {
    $response['error'] = $e->getMessage();
}

echo json_encode($response);
