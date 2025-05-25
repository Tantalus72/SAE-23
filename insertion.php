<?php
session_start();
require_once 'functions/functions.php';

// Vérification admin
if(!isAdmin($_SESSION['email'] ?? '')) {
    header('Location: index.php');
    exit();
}

$error = '';
$success = '';

try {
    // Connexion à la base de données
    $pdo = new PDO('sqlite:bdd/db.sqlite');

    // Récupération des marques et modèles
    $marques = $pdo->query("SELECT * FROM marques")->fetchAll(PDO::FETCH_ASSOC);
    $modeles = $pdo->query("SELECT * FROM modeles")->fetchAll(PDO::FETCH_ASSOC);

    // Traitement du formulaire
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $idMarque = $_POST['idMarque'];
        $idModele = $_POST['idModele'];
        $designation = htmlspecialchars($_POST['designation']);
        $annee = (int)$_POST['annee'];
        $kilometrage = (int)$_POST['kilometrage'];
        $prix = (int)$_POST['prix'];

        // Validation des données
        if (empty($idMarque) || empty($idModele) || empty($designation)) {
            throw new Exception("Tous les champs obligatoires doivent être remplis");
        }

        // Insertion
        $stmt = $pdo->prepare("INSERT INTO annonces 
            (idMarque, idModele, designation, annee, kilometrage, prix)
            VALUES (?, ?, ?, ?, ?, ?)");
        
        $stmt->execute([
            $idMarque,
            $idModele,
            $designation,
            $annee,
            $kilometrage,
            $prix
        ]);

        $success = "Annonce créée avec succès !";
    }

} catch (Exception $e) {
    $error = "Erreur : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insertion d'annonce</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <main class="container mt-5">
        <h2 class="mb-4">Créer une nouvelle annonce</h2>

        <?php if($error) { ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php }; ?>

        <?php if($success) { ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php }; ?>

        <form method="POST">
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="idMarque" class="form-label">Marque</label>
                    <select class="form-select" id="idMarque" name="idMarque" required>
                        <option value="">Sélectionnez une marque</option>
                        <?php foreach($marques as $marque) { ?>
                            <option value="<?= $marque['idMarque'] ?>">
                                <?= $marque['nom'] ?>
                            </option>
                        <?php }; ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="idModele" class="form-label">Modèle</label>
                    <select class="form-select" id="idModele" name="idModele" required>
                        <option value="">Sélectionnez un modèle</option>
                        <?php foreach($modeles as $modele) { ?>
                            <option value="<?= $modele['idModele'] ?>" 
                                data-marque="<?= $modele['idMarque'] ?>">
                                <?= htmlspecialchars($modele['nom']) ?>
                            </option>
                        <?php }; ?>
                    </select>
                </div>

                <div class="col-12">
                    <label for="designation" class="form-label">Désignation</label>
                    <input type="text" class="form-control" id="designation" name="designation" 
                           maxlength="100" required>
                </div>

                <div class="col-md-4">
                    <label for="annee" class="form-label">Année</label>
                    <input type="number" class="form-control" id="annee" name="annee" 
                           min="1900" max="<?= date('Y') + 1 ?>" required>
                </div>

                <div class="col-md-4">
                    <label for="kilometrage" class="form-label">Kilométrage</label>
                    <input type="number" class="form-control" id="kilometrage" name="kilometrage" 
                           min="0" required>
                </div>

                <div class="col-md-4">
                    <label for="prix" class="form-label">Prix (€)</label>
                    <input type="number" class="form-control" id="prix" name="prix" 
                           min="0" required>
                </div>

                <div class="col-12 mt-4">
                    <button type="submit" class="btn btn-primary btn-lg">Créer l'annonce</button>
                </div>
            </div>
        </form>
    </main>

    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Filtrage des modèles selon la marque sélectionnée
        document.getElementById('idMarque').addEventListener('change', function() {
            const selectedMarque = this.value;
            const modelOptions = document.querySelectorAll('#idModele option');

            modelOptions.forEach(option => {
                const show = option.dataset.marque === selectedMarque || option.value === "";
                option.style.display = show ? 'block' : 'none';
            });

            document.getElementById('idModele').value = "";
        });
    </script>
</body>
</html>