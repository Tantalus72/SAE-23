<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trouvetacaisse</title>
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
                <?php if(isAdmin($_SESSION['email'] ?? '')) { ?>
                <li class="nav-item"><a class="nav-link" href="rechercher.php">Modification</a></li>
                <li class="nav-item"><a class="nav-link" href="/insertion.php">Insertion</a></li>
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
