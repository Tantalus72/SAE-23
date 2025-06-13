<?php
session_start();
require_once 'functions/functions.php';

$error = '';
$success = '';

// Traitement suppression avec reCAPTCHA
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['g-recaptcha-response'])) {
    $recaptchaSecret = '6LfDnFkrAAAAAFnL0qNUwxDmNmVpC0FeA_a25oFu'; // Clé secrète

    $recaptchaResponse = $_POST['g-recaptcha-response'];
    $verifyResponse = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$recaptchaSecret}&response={$recaptchaResponse}");
    $responseData = json_decode($verifyResponse);

    if ($responseData->success) {
        // Supprimer annonce
        if (delete_annonce($_POST['id'])) {
            $success = "Annonce supprimée avec succès !";
        } else {
            $error = "Erreur lors de la suppression";
        }
    } else {
        $error = "Vérification reCAPTCHA échouée !";
    }
}

// Récupérer annonces
$annonces = listerAnnonces();
?>

<?php include 'partial/navbar.php'; ?>

<?php
if (empty($_SESSION)) {
    include 'partial/connect_access.php';
    include 'partial/footer.php';
    redirect('connexion.php', 10);
    exit();
} elseif (!isAdmin($_SESSION['email'] ?? '')) {
    include 'partial/admin_access.php';
    include 'partial/footer.php';
    redirect('connexion.php', 10);
    exit();
} else {
?>

<div class="container mt-5">
    <h2>Gestion des annonces</h2>

    <?php if ($error) { ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php } ?>
    <?php if ($success) { ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php } ?>

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
                            <!-- reCAPTCHA -->
                            <div class="g-recaptcha" data-sitekey="6LfDnFkrAAAAAKky5Q-OnQbXBRcgfA7tk3ZaOrZd"></div>
                            <button type="submit" class="btn btn-danger ms-2">Supprimer</button>
                        </div>
                    </form>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<?php } include 'partial/footer.php'; ?>
</body>
</html>
