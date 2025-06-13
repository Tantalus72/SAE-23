<?php
session_start();
require_once 'functions/functions.php';

// Connexion
$pdo = getPDO();

// Marques et modèles pour les filtres
$marquesList = $pdo
  ->query("SELECT idMarque, nom FROM marques ORDER BY nom")
  ->fetchAll(PDO::FETCH_ASSOC);

$modelesList = $pdo
  ->query("SELECT idModele, nom, idMarque FROM modeles ORDER BY nom")
  ->fetchAll(PDO::FETCH_ASSOC);

// Années existantes
$anneesList = $pdo
  ->query("SELECT DISTINCT annee FROM annonces ORDER BY annee DESC")
  ->fetchAll(PDO::FETCH_COLUMN); // Retourne un simple tableau au lieu d'un tableau associatif

// KM min/max
list($kmMin, $kmMax) = $pdo
  ->query("SELECT MIN(kilometrage), MAX(kilometrage) FROM annonces")
  ->fetch(PDO::FETCH_NUM); // Pour ne récupérer qu'un nombre

// Prix min/max
list($prixMin, $prixMax) = $pdo
  ->query("SELECT MIN(prix), MAX(prix) FROM annonces")
  ->fetch(PDO::FETCH_NUM);

?>

    <?php
    include 'partial/navbar.php';

    if (empty($_SESSION)) {

        include 'partial/page_no_connect.php';
        redirect('connexion.php', 10);
        exit();

    } else { ?>
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
                            <a href="index.php#annonce" class="btn btn-light btn-lg px-5 me-3">Explorer</a>
                            <?php if(!isset($_SESSION['email'])) { ?>
                                <a href="connexion.php" class="btn btn-outline-light btn-lg px-5">Connexion</a>
                            <?php }; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>



    <main class="container py-5">
        <h2 id="annonce" class="text-center mb-5">Nos dernières annonces</h2>

        <!-- FILTRES  -->
        <div class="row mb-4">
            <!-- Filtre Marque -->
            <div class="col-md-2">
                <label for="filterMarque" class="form-label">Marque</label>
                <select id="filterMarque" class="form-select" onchange="applyFilters()">
                <option value="">Toutes</option>
                <?php foreach($marquesList as $m): ?>
                    <option value="<?= $m['idMarque'] ?>">
                    <?= htmlspecialchars($m['nom']) ?>
                    </option>
                <?php endforeach; ?>
                </select>
            </div>

            <!-- Filtre Modèle -->
            <div class="col-md-2">
                <label for="filterModele" class="form-label">Modèle</label>
                <select id="filterModele" class="form-select" onchange="applyFilters()">
                <option value="">Tous</option>
                <?php foreach($modelesList as $mo): ?>
                    <option value="<?= $mo['idModele'] ?>"
                            data-marque="<?= $mo['idMarque'] ?>">
                    <?= htmlspecialchars($mo['nom']) ?>
                    </option>
                <?php endforeach; ?>
                </select>
            </div>

            <!-- Filtre Année Min -->
            <div class="col-md-2">
                <label for="filterAnneeMin" class="form-label">Année min</label>
                <select id="filterAnneeMin" class="form-select" onchange="applyFilters()">
                <option value="">Min</option>
                <?php foreach($anneesList as $a): ?>
                    <option value="<?= $a ?>"><?= $a ?></option>
                <?php endforeach; ?>
                </select>
            </div>

            <!-- Filtre Année Max -->
            <div class="col-md-2">
                <label for="filterAnneeMax" class="form-label">Année max</label>
                <select id="filterAnneeMax" class="form-select" onchange="applyFilters()">
                <option value="">Max</option>
                <?php foreach($anneesList as $a): ?>
                    <option value="<?= $a ?>"><?= $a ?></option>
                <?php endforeach; ?>
                </select>
            </div>

            <!-- Filtre Kilométrage max -->
            <div class="col-md-2">
                <label for="filterKmMax" class="form-label">Km max</label>
                <input type="number" id="filterKmMax" class="form-control"
                    min="<?= $kmMin ?>" max="<?= $kmMax ?>"
                    placeholder="jusqu’à <?= number_format($kmMax,0,',',' ') ?>"
                    oninput="debounceApplyFilters()">
            </div>

            <!-- Filtre Prix max -->
            <div class="col-md-2">
                <label for="filterPrixMax" class="form-label">Prix max</label>
                <input type="number" id="filterPrixMax" class="form-control"
                    min="<?= $prixMin ?>" max="<?= $prixMax ?>"
                    placeholder="jusqu’à <?= number_format($prixMax,0,',',' ') ?>"
                    oninput="debounceApplyFilters()">
            </div>
        </div>



        <div class="row" id="annonces-container">
        </div>

    </main>
    
    <script>

    // Timer global pour le debounce
    let timer;

    // Dès que l'utilisateur tape un caractère, on annule le timer précédent et on relance après 500ms
    function debounceApplyFilters() {
        clearTimeout(timer);
        timer = setTimeout(applyFilters, 500);  
    }

    // Filtrage dynamique des modèles en fonction de la marque
    document.addEventListener('change', function(e) {
        if (e.target.id === 'filterMarque') {
            const marque = e.target.value;

            // On affiche uniquement les modèles qui appartiennent à la marque sélectionnée
            document.querySelectorAll('#filterModele option').forEach(opt => {
                opt.style.display = (!marque || opt.dataset.marque === marque || opt.value === '') ? 'block' : 'none';
            });

            // On réinitialise la sélection du modèle
            document.getElementById('filterModele').value = '';
        }
    });


    // envoie les filtres au serveur et met à jour les cartes
    function applyFilters() {
        const data = new FormData();

        // Correspondance entre les IDs des filtres HTML et les noms de champs côté PHP
        const map = {
            filterMarque: 'idMarque',
            filterModele: 'idModele',
            filterAnneeMin: 'anneeMin',
            filterAnneeMax: 'anneeMax',
            filterKmMax: 'kmMax',
            filterPrixMax: 'prixMax'
        };

        // On ajoute uniquement les champs qui sont remplis au 'FormData'
        for (let id in map) {
            const el = document.getElementById(id);
            if (el && el.value) data.append(map[id], el.value);
        }

        // Création de la requête AJAX
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'filter_annonces_ajax.php', true);

        xhr.onload = function() {
            if (xhr.status === 200) {
                const resp = JSON.parse(xhr.responseText);
                if (resp.success) {
                    const container = document.getElementById('annonces-container');
                    container.innerHTML = ''; // On vide le contenu actuel

                    // Si aucune annonce ne correspond
                    if (resp.data.length === 0) {
                        container.innerHTML = `
                            <div class="col-12 mt-4">
                                <p class="text-center">Aucune annonce disponible avec ces filtres de recherche.</p>
                            </div>`;
                    } else {

                        console.log(resp.data)
                        // On génère les cards 
                        resp.data.forEach(a => {
                            console.log(a)
                            const d = document.createElement('div');
                            d.className = 'col-md-4 mb-4';
                            d.innerHTML = `
                                <div class="card h-100 shadow">
                                    <img src="ressources/image/annonces/${a.path_img}"
                                        class="card-img-top"
                                        alt="Voiture d'occasion"
                                        style="height: 200px; object-fit: cover;">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <div>
                                                <h5 class="card-title mb-1">${a.nom_marque}</h5>
                                                <h6 class="card-subtitle text-muted">${a.nom_modele}</h6>
                                            </div>
                                            <span class="badge bg-dark">${a.annee}</span>
                                        </div>

                                        <p class="card-text">${a.designation}</p>

                                        <div class="d-flex justify-content-between align-items-center mt-auto">
                                            <div>
                                                <span class="text-muted d-block small">Kilométrage</span>
                                                <span class="fw-bold">${Number(a.kilometrage).toLocaleString('fr-FR')} km</span>
                                            </div>
                                            <div class="text-end">
                                                <span class="text-primary fs-4 fw-bold">${Number(a.prix).toLocaleString('fr-FR')} €</span>
                                            <?php if (isAdmin($_SESSION['email']))  { ?>
                                                 <a href="modification.php?idVoiture=${a.idAnnonce}" class="btn btn-primary btn-sm d-block mt-1">Modifier</a>
                                                
                                            <?php }; ?>
                                            </div>                                            

                                        </div>
                                    </div>
                                </div>`;
                            container.appendChild(d);
                        });
                    }
                }
            }
        };

        xhr.send(data); // Envoie de la requête
    }


    // On lance la fonction au chargement de la page
    document.addEventListener('DOMContentLoaded', applyFilters);
    </script>


    
    <?php } ?>


    <?php include 'partial/footer.php'; ?>


    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>