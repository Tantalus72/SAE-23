<?php
session_start();
require_once 'functions/functions.php';
if (!isset($_SESSION['email'])) {
    header('Location: connexion.php');
    exit();
}

?>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auto-Annonces</title>
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
            
                <li class="nav-item"><a class="nav-link" href="logout.php">Déconnexion</a></li>
                
            </ul>
        </div>
    </div>
</nav>


    <header class="hero-section">
        <div class="video-container">
            <div id="videoCarousel" class="carousel slide carousel-fade w-100 h-100" data-bs-ride="carousel">
                <div class="carousel-inner w-100 h-100">
                    <?php foreach(glob('ressources/videos/*.mp4') as $key => $video) { ?>
                    <div class="carousel-item w-100 h-100 <?= $key === 0 ? 'active' : '' ?>">
                        <video class="video-background w-100 h-100" autoplay muted loop>
                            <source src="<?= $video ?>" type="video/mp4">
                        </video>
                    </div>
                    <?php }; ?>
                </div>
            </div>
        </div>

        <div class="hero-content">
            <div class="container h-100">
                <div class="row h-100 align-items-center">
                    <div class="col-lg-7 col-xl-6">
                        <div class="welcome-text">
                            <h1 class="display-3 fw-bold mb-4 text-white">
                                
                                    Bienvenue <br><span class="text-primary"><?= $_SESSION['email']?> </span>
                            </h1>
                            <p class="lead mb-5 text-white-75 text-white">Découvrez notre incroyable collection</p>
                            <a href="annonces.php" class="btn btn-light btn-lg px-5 me-3">Explorer</a>
                            <?php if(!isset($_SESSION['email'])) { ?>
                                <a href="login.php" class="btn btn-outline-light btn-lg px-5">Connexion</a>
                            <?php }; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>



    <main class="container py-5">
        <h2 class="text-center mb-5">Nos dernières annonces</h2>
        <div class="row">
            <?php 
            $annonces = listerAnnonces();
            $count = 0;
            
            if($annonces !== []) {
                foreach($annonces as $annonce) {
                    if($count++ >= 5) break;
            ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow">
                    <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="Voiture d'occasion">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h5 class="card-title mb-1"><?= $annonce['nom_marque']?></h5>
                                <h6 class="card-subtitle text-muted"><?= $annonce['nom_modele'] ?></h6>
                            </div>
                            <span class="badge bg-dark"><?= $annonce['annee'] ?></span>
                        </div>
                        
                        <p class="card-text"><?= $annonce['designation'] ?></p>
                        
                        <div class="d-flex justify-content-between align-items-center mt-auto">
                            <div>
                                <span class="text-muted d-block small">Kilométrage</span>
                                <span class="fw-bold"><?= number_format($annonce['kilometrage'], 0, ',', ' ') ?> km</span>
                            </div>
                            <div class="text-end">
                                <span class="text-primary fs-4 fw-bold"><?= number_format($annonce['prix'], 0, ',', ' ') ?> €</span>
                                <a href="annonce.php?id=<?= $annonce['idAnnonce'] ?>" class="btn btn-primary btn-sm d-block mt-1">Voir plus</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php 
                } 
                } else {
            ?>
            <p class="text-center">Aucune annonce disponible pour le moment.</p>
            <?php }; ?>
        </div>
    </main>

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
                    <li>Email : contact@trouvetacaisse.com</li>
                    <li>Tél : 01 23 45 67 89</li>
                </ul>
            </div>
        </div>
        <div class="text-center mt-3">
            <p>&copy; 2025 trouvetacaisse - Tous droits réservés</p>
        </div>
    </div>
</footer>';

    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>