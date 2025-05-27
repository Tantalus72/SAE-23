<?php 
session_start();
require_once 'functions/functions.php';
?>

<?php
    include 'navbar.php';

    if (empty($_SESSION)) {
        include 'page_no_connect.php';  
    } else { ?>

    <main class="container py-5">
        <h2 class="text-center mb-5">Toutes nos annonces</h2>
        <div class="row">
            <?php 
            $annonces = listerAnnonces();
            // var_dump($annonces);
            if($annonces !== []) {
                foreach($annonces as $annonce) {
            ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow">
                    <img src="ressources/image/annonces/<?php echo $annonce['path_img'] ?>" class="card-img-top" alt="Voiture d'occasion" style="height: 200px; object-fit: cover;">
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

    
    
    
    
    
    
<?php
    include 'footer.php';
?>