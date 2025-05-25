<?php
session_start();
require_once 'functions/functions.php';

// Vérifier si admin
if(!isAdmin($_SESSION['email'] ?? '')) {
    header('Location: index.php');
    exit();
}

$error = '';
$success = '';

// Traitement suppression
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['captcha'])) {
    // Vérifier CAPTCHA
    if (!isset($_SESSION['code']) || strtolower($_POST['captcha']) !== strtolower($_SESSION['code'])) {
        $error = "CAPTCHA incorrect !";
    } else {
        // Supprimer annonce
        if (delete_annonce($_POST['id'])) {
            $success = "Annonce supprimée avec succès !";
        } else {
            $error = "Erreur lors de la suppression";
        }
    }
}

// Récupérer annonces
$annonces = listerAnnonces();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Suppression d'annonces</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        function refreshCaptcha(img) {
            img.src = 'image.php?' + Date.now();
        }
    </script>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container mt-5">
        <h2>Gestion des annonces</h2>
        
        <?php if($error) { ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php }; ?>
        <?php if($success) { ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php };  ?>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Marque</th>
                    <th>Modèle</th>
                    <th>Désignation</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($annonces as $annonce) { ?>
                <tr>
                    <td><?= $annonce['idAnnonce'] ?></td>
                    <td><?= htmlspecialchars($annonce['nom_marque']) ?></td>
                    <td><?= htmlspecialchars($annonce['nom_modele']) ?></td>
                    <td><?= htmlspecialchars($annonce['designation']) ?></td>
                    <td>
                        <form method="POST" class="form-inline">
                            <input type="hidden" name="id" value="<?= $annonce['idAnnonce'] ?>">
                            <div class="input-group">
                                <input type="text" name="captcha" class="form-control" placeholder="CAPTCHA" required>
                                <img src="image.php" 
                                    onclick="this.src='image.php?rand='+Math.random()" 
                                    alt="CAPTCHA" 
                                    style="height: 40px; margin-left: 10px;">
                                <button type="submit" class="btn btn-danger ms-2">Supprimer</button>
                            </div>
                        </form>
                    </td>
                </tr>
                <?php }; ?>
            </tbody>
        </table>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>