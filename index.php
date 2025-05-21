<?php
session_start();
require_once 'functions/functions.php';
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
    <?php include 'navbar.php'; ?>

    <header class="hero-section">
        <div class="video-container">
            <div id="videoCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <?php foreach(glob('ressources/videos/*.mp4') as $key => $video) { ?>
                    <div class="carousel-item <?= $key === 0 ? 'active' : '' ?>">
                        <video class="video-background" autoplay muted loop>
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
                                <?php if(isset($_SESSION['email'])) { ?>
                                    Bienvenue <br><span class="text-primary"><?= htmlspecialchars($_SESSION['email']) ?></span>
                                <?php } else { ?>
                                    Trouvez la voiture<br><span class="text-primary">de vos rêves</span>
                                <?php }; ?>
                            </h1>
                            <p class="lead mb-5 text-white-75 text-white">Découvrez notre collection exclusive</p>
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
            
            if($annonces !== false) {
                foreach($annonces as $annonce) {
                    if($count++ >= 5) break;
            ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow">
                    <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="Voiture">
                    <div class="card-body">
                        <h5 class="card-title"><?= $annonce['titre'] ?></h5>
                        <p class="card-text"><?= substr($annonce['description'], 0, 100) ?>...</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge bg-primary"><?= $annonce['prix'] ?> €</span>
                            <a href="#" class="btn btn-outline-primary">Voir plus</a>
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

    <?php include 'footer.php'; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>