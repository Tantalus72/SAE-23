<?php
session_start();
require_once 'functions/functions.php';

$error = '';
$success = '';

// Connexion à la base de données
$pdo = getPDO();

// Récupération des marques et modèles
$marques = $pdo->query("SELECT * FROM marques")->fetchAll(PDO::FETCH_ASSOC);
$modeles = $pdo->query("SELECT * FROM modeles")->fetchAll(PDO::FETCH_ASSOC);

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

    } else if (!isAdmin($_SESSION['email'] ?? '')) {
        include 'partial/admin_access.php';
        include 'partial/footer.php';
        redirect('connexion.php', 10);
        exit();

    } else {
    
        if (empty($_GET) || !isset($_GET['idVoiture'])) { //  Si l'utilisateur accède simplement à la page
    ?>

    <main class="container mt-5">
        <h2 class="mb-4">Gestion des annonces</h2>

        <?php if ($error) { ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php } ?>
        <?php if ($success) { ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php } ?>
    
    <div class="table-responsive">
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
                        <form method="POST" action="modification.php?idVoiture=<?= $annonce['idAnnonce'] ?>" class="form-inline">
                            <input type="hidden" name="id" value="<?= $annonce['idAnnonce'] ?>">
                            <div class="input-group">
                                <button type="submit" class="btn btn-primary w-100">Modification</button>
                            </div>
                        </form>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        </div>
    </main>

    <?php 
    
        } else if (!empty($_GET) && isset($_GET['idVoiture'])) { // Si le paramètre idVoiture est présent dans l'URL
            $VOITURE = getVoiture($_GET['idVoiture']); // On récupère les informations de la voiture
    ?>

    <main class="container mt-5">
        <div id="message"></div>

        <h2 class="mb-4">Modifier une annonce</h2>
        <form enctype="multipart/form-data" id="form-modif">
        <input type="hidden" name="idAnnonce" value="<?= $VOITURE['idAnnonce'] ?>">
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="idMarque" class="form-label">Marque</label>
                    <select class="form-select" id="idMarque" name="idMarque" required>
                        <?php foreach ($marques as $marque) { ?>
                            <option value="<?= $marque['idMarque'] ?>" <?php if ($marque['idMarque'] == $VOITURE['idMarque']) echo 'selected' ?> ><?= $marque['nom'] ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="idModele" class="form-label">Modèle</label>
                    <select class="form-select" id="idModele" name="idModele" required>
                        <option value="">Sélectionnez un modèle</option>
                        <?php foreach ($modeles as $modele) { ?>
                            <option value="<?= $modele['idModele'] ?>" data-marque="<?= $modele['idMarque'] ?>" <?php if ($modele['idModele'] == $VOITURE['idModele']) echo 'selected' ?> >
                                <?= htmlspecialchars($modele['nom']) ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="col-12">
                    <label for="designation" class="form-label">Désignation</label>
                    <input type="text" class="form-control" id="designation" name="designation" 
                        maxlength="100" value="<?= $VOITURE['designation'] ?>" required>
                </div>

                <div class="col-md-4">
                    <label for="annee" class="form-label">Année</label>
                    <input type="number" class="form-control" id="annee" name="annee" 
                        value="<?= $VOITURE['annee'] ?>" required>
                </div>

                <div class="col-md-4">
                    <label for="kilometrage" class="form-label">Kilométrage</label>
                    <input type="number" class="form-control" id="kilometrage" name="kilometrage" 
                        value="<?= $VOITURE['kilometrage'] ?>" required>
                </div>

                <div class="col-md-4">
                    <label for="prix" class="form-label">Prix</label>
                    <input type="number" class="form-control" id="prix" name="prix" 
                        value="<?= $VOITURE['prix'] ?>" required>
                </div>

                <div class="col-md-4">
                    <label for="img">Modifier l'image</label>
                    <input type="file" class="form-control" id="img" name="img" accept="image/jpeg, image/png, image/gif">
                    <div class="form-text">
                        Formats acceptés : JPG, PNG, GIF.
                    </div>
                    <p class="mt-4"> <strong>Image actuelle :</strong></p>
                </div>
                
                <div class="mt-1">
                    <img src="ressources/image/annonces/<?php echo ($VOITURE['path_img']) ?>" alt="Image actuelle" style="max-width: 100%; max-height: 150px;">
                </div>
                
                <!-- CAPTCHA emojis -->
                <div class="mt-5">
                <h5 class="mb-2">Vérification CAPTCHA</h5>
                <p class="text-muted mb-3">Parmi ces icônes, trouvez et cliquez sur l’intru.</p>

                <div id="captcha-container" class="d-flex flex-wrap gap-2 mb-3"></div>

                <div class="d-flex align-items-center gap-2 mb-2">
                    <button type="button" id="refresh-captcha" class="btn btn-sm btn-outline-secondary">
                        ↻ Regénérer
                    </button>
                </div>

                <input type="hidden" name="captcha_key" id="captcha_key" value="">
                <input type="hidden" id="captcha_valid" value="0">
                </div>


                <div class="col-12 mt-4">
                    <button type="submit" class="btn btn-primary btn-lg">Modifier l'annonce</button>
                </div>
            </div>
        </form>
    </main>
    
    <script>
        // Liste des emojis possibles
        const ICONS = [
            { group: 'car',   emoji: '🚗' },
            { group: 'bike',  emoji: '🏍' },
            { group: 'bus',   emoji: '🚌' },
            { group: 'train', emoji: '🚆' },
        ];


        // Message d'erreur/succès
        const msgDiv = document.getElementById('message');

        // Récupérer la valeur de l'input année à l'envoi du formulaire

        function generateCaptcha() {
            const container = document.getElementById('captcha-container');
            container.innerHTML = '';

            // On reset la valeur de validation
            document.getElementById('captcha_valid').value = '0';

            // Choix aléatoire : 
            // On mélange le tableau ICONS de manière aléatoire (grâce à sort + random)
            // Puis on sélectionne les deux premiers éléments du tableau (.slice) :
            // - 'main' servira à représenter la majorité des icônes
            // - 'intrus' sera l'emoji différent que l'utilisateur devra repérer

            const [main, intrus] = ICONS.sort(() => 0.5 - Math.random()).slice(0, 2);

            // - On crée un tableau contenant 4 fois l'emoji principal ('main')
            // - On y ajoute une fois l'intrus (.concat)
            // - On mélange l'ensemble pour que l'intrus soit à une position aléatoire (.sort)
            const pool = Array(4).fill(main.emoji).concat(intrus.emoji).sort(() => 0.5 - Math.random());

            // On stock l'emoji valide (pour valider le CAPTCHA)
            document.getElementById('captcha_key').value = intrus.emoji;

            // Pour chaque emojis de notre pool
            pool.forEach(emoji => { 
                const btn = document.createElement('button'); // On créé un bouton
                btn.type = 'button';
                btn.textContent = emoji;
                btn.className = 'btn btn-light';
                btn.style.fontSize = '2rem';

                // Si un emoji est cliqué (si le bouton associé est cliqué)
                btn.addEventListener('click', () => {
                    // On retire la classe selected de tous les éléments
                    container.querySelectorAll('button').forEach(b => b.classList.remove('selected'));
                    // On ajoute selected à celui qui a été cliqué
                    btn.classList.add('selected');

                    // Si l'utilisateur a bon, la valeur de l'input CAPTCHA prend '1'
                    if (emoji === intrus.emoji) {
                        document.getElementById('captcha_valid').value = '1';
                    } else { // Sinon '0'
                        document.getElementById('captcha_valid').value = '0';
                    }

                });

                // On affiche tous ces boutons avec leurs propriétés
                container.appendChild(btn);
            });
        }

        // Si le bouton de reset est pressé, on regénère un CAPTCHA
        document.getElementById('refresh-captcha').addEventListener('click', generateCaptcha);
        
        // On génère le captcha
        generateCaptcha();

        // submit + AJAX
        document.getElementById('form-modif').addEventListener('submit', function(e) {
            e.preventDefault();

            // Cette constante est vraie uniquement si l'utilisateur a selectionné le bon emoji
            const isValid = document.getElementById('captcha_valid').value === '1';
            
            // On récupère les valeurs des inputs
            const anneeInput = document.getElementById('annee');
            const kilometrageInput = document.getElementById('kilometrage');
            const prixInput = document.getElementById('prix');
            

            // On vérifie que l'année est valide
            if (anneeInput.value < 1770 || anneeInput.value > new Date().getFullYear() + 1) {
                msgDiv.innerHTML = `<div class="alert alert-danger">Année invalide</div>`;
                window.scrollTo(0, 0);
                return;
            }

            // On vérifie que le kilométrage est positif
            if (kilometrageInput.value < 0) {
                msgDiv.innerHTML = `<div class="alert alert-danger">Kilométrage invalide</div>`;
                window.scrollTo(0, 0);
                return;
            }

            // On vérifie que le prix est positif
            if (prixInput.value < 0) {
                msgDiv.innerHTML = `<div class="alert alert-danger">Prix invalide</div>`;
                window.scrollTo(0, 0);
                return;
            }


            if (!isValid) { // Si l'utilisateur s'est trompé d'emoji
                msgDiv.innerHTML = `<div class="alert alert-danger">Mauvais CAPTCHA</div>`;

                // On scroll vers le haut pour que l'utilisateur voit bien le message  
                window.scrollTo(0, 0);

                // On regénère un CAPTCHA
                generateCaptcha();
                return;
            }

            const xhr  = new XMLHttpRequest();
            const data = new FormData(this);

            xhr.onreadystatechange = function() {
                if (xhr.readyState !== 4) return;
                let json;
                try {
                    json = JSON.parse(xhr.responseText);
                // Si un souci survient (erreur qui bloque la génération de la réponse JSON)
                } catch {
                    return document.getElementById('message').innerHTML =
                    '<div class="alert alert-danger">Réponse invalide du serveur.</div>';
                }

                // Si tout est bon
                if (xhr.status === 200 && !json.error) {
                    msgDiv.innerHTML = `<div class="alert alert-success">${json.success}</div>`;

                    // On met à jour les inputs du formulaire
                    const u = json.updated;
                    document.getElementById('designation').value  = u.designation;
                    document.getElementById('annee').value       = u.annee;
                    document.getElementById('kilometrage').value = u.kilometrage;
                    document.getElementById('prix').value        = u.prix;

                    // On met à jour les selects de marque et modèle
                    document.getElementById('idMarque').value = u.idMarque;
                    document.getElementById('idModele').value = u.idModele;

                    // On met à jour l’aperçu de l’image
                    const imgElem = document.querySelector('#form-modif img');
                    imgElem.src = `ressources/image/annonces/${u.path_img}`;

                    // On réinitialise le CAPTCHA
                    generateCaptcha();
                // Si on a une erreur
                } else {
                    // On affiche l'erreur dans le message en haut de la page
                    msgDiv.innerHTML = `<div class="alert alert-danger">${json.error||'Erreur serveur'}</div>`;
                }
            }

            xhr.open('POST', 'modification_ajax.php', true);
            xhr.send(data);

            // On scroll vers le haut pour que l'utilisateur voit bien le message
            window.scrollTo(0, 0);

        });
          document.getElementById('idMarque').addEventListener('change', function() {
            const marque = this.value;
            document.querySelectorAll('#idModele option').forEach(opt => {
            opt.style.display = (opt.value === '' || opt.dataset.marque === marque)
                                ? 'block' : 'none';
            });
            document.getElementById('idModele').value = '';
        });
    </script>




    <?php 
        } 
        include 'partial/footer.php';
    }
    ?>
</body>
</html>
