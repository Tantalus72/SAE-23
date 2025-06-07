<?php
session_start();
header('Content-Type: application/json; charset=UTF-8');

require_once 'functions/functions.php';

$response = ['error' => null, 'success' => null];

try {
    if (empty($_SESSION) || !isAdmin($_SESSION['email'] ?? '')) {
        throw new Exception("Accès non autorisé");
    }

    if (empty($_POST['idAnnonce'])) {
        throw new Exception("ID annonce manquant");
    }

    $pdo = getPDO();

    $idAnnonce   = (int) $_POST['idAnnonce'];
    $idMarque    = (int) $_POST['idMarque'];
    $idModele    = (int) $_POST['idModele'];
    $designation = htmlspecialchars(trim($_POST['designation'] ?? ''));
    $annee       = (int) $_POST['annee'];
    $kilometrage = (int) $_POST['kilometrage'];
    $prix        = (int) $_POST['prix'];

    // Vérifie les champs requis
    if (!$idMarque || !$idModele || !$designation || !$annee || !$kilometrage || !$prix) {
        throw new Exception("Tous les champs sont obligatoires.");
    }

    // Récupère l’image actuelle
    $stmt = $pdo->prepare("SELECT path_img FROM annonces WHERE idAnnonce = ?");
    $stmt->execute([$idAnnonce]);
    $annonce = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$annonce) {
        throw new Exception("Annonce non trouvée");
    }
    $current_img_path = $annonce['path_img'];

    $upload_dir = realpath(__DIR__ . '/ressources/image/annonces');
    if (!$upload_dir) {
        throw new Exception("Dossier d’upload introuvable.");
    }

    $imageName = $current_img_path; // par défaut, on conserve l'image actuelle

    // Si nouvelle image uploadée
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

        // Génère un nom unique
        $uniqueName = uniqid('annonce_', true) . '.' . $extension;
        $destPath = $upload_dir . '/' . $uniqueName;

        if (!move_uploaded_file($fileTmpPath, $destPath)) {
            throw new Exception("Erreur lors du téléchargement de l'image.");
        }

        $imageName = $uniqueName;
    }

    // Mise à jour de l'annonce
    $stmt = $pdo->prepare("UPDATE annonces 
        SET idMarque = ?, idModele = ?, designation = ?, annee = ?, kilometrage = ?, prix = ?, path_img = ? 
        WHERE idAnnonce = ?");

    $stmt->execute([
        $idMarque,
        $idModele,
        $designation,
        $annee,
        $kilometrage,
        $prix,
        $imageName,
        $idAnnonce
    ]);

    $response['success'] = "Annonce modifiée avec succès !";

} catch (Exception $e) {
    $response['error'] = $e->getMessage();
}

echo json_encode($response);
