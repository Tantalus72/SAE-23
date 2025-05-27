<?php
session_start();
require_once 'functions/functions.php';
// if (!isset($_SESSION['email'])) {
//     header('Location: connexion.php');
//     exit();
// }

?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TrouveTaCaisse</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>


    <?php
        include 'navbar.php';

        if (empty($_SESSION)) {

    ?>

    <div class="container my-5">
        <div class="card shadow-lg p-4 p-md-5 border-0 text-center">
            <div class="card-body">
                <!-- Icône de bienvenue -->
                <div class="mb-4 text-center">
                    <img src="ressources/image/TROUVETACAISSELOGO.png" class="img-fluid" alt="Logo TrouveTaCaisse" style="filter: brightness(0) saturate(100%); width: 200px; height: auto">
                </div>

                <!-- Titre principal -->
                <h1 class="fw-bold mb-3">Bienvenue sur TrouveTaCaisse</h1>

                <!-- Sous-titre -->
                <p class="lead text-muted mb-4">
                    Trouvez la voiture de vos rêves parmi notre sélection exclusive<br>
                    ou vendez votre véhicule en quelques clics
                </p>

                <!-- Boutons d'action -->
                <div class="d-flex flex-column flex-md-row gap-3 justify-content-center">
                    <a href="login.php" class="btn btn-primary btn-lg px-4 py-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-box-arrow-in-right me-2" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M6 3.5a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-2a.5.5 0 0 0-1 0v2A1.5 1.5 0 0 0 6.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-8A1.5 1.5 0 0 0 5 3.5v2a.5.5 0 0 0 1 0v-2z"/>
                            <path fill-rule="evenodd" d="M11.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5H1.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z"/>
                        </svg>
                        Connexion
                    </a>

                    <a href="register.php" class="btn btn-outline-primary btn-lg px-4 py-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-person-plus me-2" viewBox="0 0 16 16">
                            <path d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H1s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C9.516 10.68 8.289 10 6 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/>
                            <path fill-rule="evenodd" d="M13.5 5a.5.5 0 0 1 .5.5V7h1.5a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0V8h-1.5a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5z"/>
                        </svg>
                        Créer un compte
                    </a>
                </div>
            </div>
        </div>
    </div>

    <?php } else { ?>
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
            var_dump($annonces);
            if($annonces !== []) {
                foreach($annonces as $annonce) {
                    if($count++ >= 5) break;
            ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow">
                    <img src="ressources/image/annonces/<?php echo $annonce['path_img'] ?>" class="card-img-top" alt="Voiture d'occasion">
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
    
    
    <?php } ?>


    <?php include 'footer.php'; ?>


    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>