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
            $status = "user";
            if (isAdmin($_SESSION["email"])){$status="admin";}
            $fichier = fopen('./logs/access.log','a+');
            fputs($fichier,$_POST['email']." de ".$_SERVER['REMOTE_ADDR']." à ".date('l jS\of F Y h:i:s A')." sous le status :".$status." s'est connecté");
            fputs($fichier,"\n");
            fclose($fichier);
            
            header('Location: index.php');
            exit();
        } else {
            $error = 'Identifiants incorrects';
            $fichier = fopen('./logs/access.log','a+');
            fputs($fichier,$_POST['email']." de ".$_SERVER['REMOTE_ADDR']." à ".date('l jS\of F Y h:i:s A')." ne s'est connecté pas");
            fputs($fichier,"\n");
            fclose($fichier);
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
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
   

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">
            <img src="ressources/image/TROUVETACAISSELOGO.png" alt="Logo" class="img-fluid">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="index.php">Accueil</a></li>
                <li class="nav-item"><a class="nav-link" href="annonces.php">Annonces</a></li>
                <li class="nav-item"><a class="nav-link" href="rechercher.php">Rechercher</a></li>
                <?php if(isAdmin($_SESSION['email'] ?? '')) { ?>
                <li class="nav-item"><a class="nav-link" href="insertion.php">Insertion</a></li>
                <li class="nav-item"><a class="nav-link" href="suppression.php">Suppression</a></li>
                <?php }; ?>
            </ul>
            <ul class="navbar-nav">
                <?php if(isset($_SESSION['email'])) { ?>
                <li class="nav-item"><a class="nav-link" href="logout.php">Déconnexion</a></li>
                <?php } else { ?>
                <li class="nav-item"><a class="nav-link" href="login.php">Connexion</a></li>
                <?php }; ?>
            </ul>
        </div>
    </div>
</nav>


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

    <footer class="bg-dark text-white mt-5">
    <div class="container py-4">
        <div class="row">
            <div class="col-md-6">
                <h5>À propos</h5>
                <p>Site d'annonces de voitures d'occasion - Projet PHP <br>
                    Réalisé par GIRAULT Erwann et SENES Tiziano</p>
            </div>
            <div class="col-md-6">
                <h5>Contact</h5>
                <ul class="list-unstyled">
                    <li>Email : contact@auto-annonces.com</li>
                    <li>Tél : 01 23 45 67 89</li>
                </ul>
            </div>
        </div>
        <div class="text-center mt-3">
            <p>&copy; 2025 Auto-Annonces - Tous droits réservés</p>
        </div>
    </div>
</footer>';


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>