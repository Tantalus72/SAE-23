<?php
session_start();
require_once 'functions/functions.php';

// Redirection si déjà connecté
if(isset($_SESSION['email'])) {
    header('Location: index.php');
    exit();
}

$error = '';
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(!empty($_POST['email']) && !empty($_POST['password'])) {
        if(login($_POST['email'], $_POST['password'])) {
            $_SESSION['email'] = $_POST['email'];
            header('Location: index.php');
            exit();
        } else {
            $error = 'Identifiants incorrects';
        }
    } else {
        $error = 'Veuillez remplir tous les champs';
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Auto-Annonces</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <?php include 'navbar.php'; ?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card shadow">
                    <div class="card-body p-4">
                        <h2 class="text-center mb-4">Connexion</h2>
                        
                        <?php if($error) {?>
                        <div class="alert alert-danger"><?= $error ?></div>
                        <?php }; ?>

                        <form method="POST">
                            <div class="mb-3">
                                <label for="email" class="form-label">Adresse email</label>
                                <input type="email" class="form-control" 
                                       id="email" name="email" 
                                       value="<?= $_POST['email'] ?? '' ?>" 
                                       required autofocus>
                            </div>
                            
                            <div class="mb-4">
                                <label for="password" class="form-label">Mot de passe</label>
                                <input type="password" class="form-control" 
                                       id="password" name="password" required>
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100">Se connecter</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>