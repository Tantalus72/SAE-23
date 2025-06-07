<?php
session_start();
require_once 'functions/functions.php';

$error = '';
$success = '';

// Connexion à la base de données
$pdo = new PDO('sqlite:bdd/db.sqlite');

// Récupération des marques et modèles
$marques = $pdo->query("SELECT * FROM marques")->fetchAll(PDO::FETCH_ASSOC);
$modeles = $pdo->query("SELECT * FROM modeles")->fetchAll(PDO::FETCH_ASSOC);

// Récupérer annonces
$annonces = listerAnnonces();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modification d'annonces</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <?php
    if (empty($_SESSION)) {
        include 'partial/connect_access.php';
        include 'footer.php';
        redirect('connexion.php', 10);
        exit();

    } else if (!isAdmin($_SESSION['email'] ?? '')) {
        include 'partial/admin_access.php';
        include 'footer.php';
        redirect('connexion.php', 10);
        exit();

    } else {
        if (empty($_GET) || !isset($_GET['idVoiture'])) {
    ?>

    <main class="container mt-5">
        <h2 class="mb-4">Gestion des annonces</h2>

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
                        <form method="POST" action="modification.php?idVoiture=<?= $annonce['idAnnonce'] ?>" class="form-inline">
                            <input type="hidden" name="id" value="<?= $annonce['idAnnonce'] ?>">
                            <div class="input-group">
                                <button type="submit" class="btn btn-primary ms-2">Modification</button>
                            </div>
                        </form>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </main>

    <?php 
        } else if (!empty($_GET) && isset($_GET['idVoiture'])) { 
            $VOITURE = getVoiture($_GET['idVoiture']);
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
                        min="1770" max="<?= date('Y') + 1 ?>" value="<?= $VOITURE['annee'] ?>" required>
                </div>

                <div class="col-md-4">
                    <label for="kilometrage" class="form-label">Kilométrage</label>
                    <input type="number" class="form-control" id="kilometrage" name="kilometrage" 
                        min="0" value="<?= $VOITURE['kilometrage'] ?>" required>
                </div>

                <div class="col-md-4">
                    <label for="prix" class="form-label">Prix</label>
                    <input type="number" class="form-control" id="prix" name="prix" 
                        min="0" value="<?= $VOITURE['prix'] ?>" required>
                </div>

                <div class="col-md-4">
                    <label for="img">Modifier l'image</label>
                    <input type="file" class="form-control" id="img" name="img" accept="image/jpeg, image/png, image/gif">
                    <div class="form-text">
                        Formats acceptés : JPG, PNG, GIF.
                    </div>
                    <p> <strong>Image actuelle :</strong></p>
                </div>
                
                <div class="mt-2">
                    <img src="ressources/image/annonces/<?php echo ($VOITURE['path_img']) ?>" alt="Image actuelle" style="max-width: 100%; max-height: 150px;">
                </div>

                <div class="col-12 mt-4">
                    <button type="submit" class="btn btn-primary btn-lg">Modifier l'annonce</button>
                </div>
            </div>
        </form>
    </main>
    <script>
        document.getElementById('form-modif').addEventListener('submit', function(e) {
            e.preventDefault();
            const xhr  = new XMLHttpRequest();
            const data = new FormData(e.target);

            xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                let json;
                try {
                json = JSON.parse(xhr.responseText);
                } catch {
                return document.getElementById('message').innerHTML =
                    '<div class="alert alert-danger">Réponse invalide du serveur.</div>';
                }
                const msgDiv = document.getElementById('message');
                if (xhr.status === 200 && !json.error) {
                msgDiv.innerHTML = `<div class="alert alert-success">${json.success}</div>`;
                e.target.reset();
                // éventuellement: setTimeout(() => window.location.reload(), 800);
                } else {
                msgDiv.innerHTML = `<div class="alert alert-danger">${json.error||'Erreur serveur'}</div>`;
                }
            }
            };

            xhr.open('POST', 'modification_ajax.php', true);
            xhr.send(data);
        });
    </script>
    <?php 
        } 
        include 'footer.php';
    }
    ?>
</body>
</html>
