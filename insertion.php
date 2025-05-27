<?php
session_start();
require_once 'functions/functions.php';


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


        // Traitement de l'image
        $target_dir = "ressources/image/annonces/";
        $target_file = $target_dir . basename($_FILES["img"]["name"]);
        var_dump($_FILES["img"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        // Check if image file is a actual image or fake image
        if(isset($_POST["submit"])) {
            $check = getimagesize($_FILES["img"]["name"]);
            if($check !== false) {
                // echo "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                // echo "File is not an image.";
                $uploadOk = 0;
            }
        }


        // Validation des données
        if (empty($idMarque) || empty($idModele) || empty($designation)) {
            throw new Exception("Tous les champs obligatoires doivent être remplis");
        }

        // Insertion
        $stmt = $pdo->prepare("INSERT INTO annonces 
            (idMarque, idModele, designation, annee, kilometrage, prix, path_img)
            VALUES (?, ?, ?, ?, ?, ?, ?)");
        
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

    <?php
    if (empty($_SESSION)) {
    ?>
        <div class="container my-5">
        <div class="alert alert-warning text-center shadow-lg p-5">
            <div class="d-flex flex-column align-items-center gap-4">
                <!-- Icône d'avertissement -->
                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor" class="bi bi-exclamation-triangle-fill text-danger" viewBox="0 0 16 16">
                    <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                </svg>
                
                <!-- Message principal -->
                <h2 class="fw-bold mb-3">Accès restreint</h2>
                
                <!-- Description -->
                <p class="lead mb-4">
                    Vous devez être connecté pour accéder à cette page.<br>
                    Merci de vous identifier.
                </p>

                <!-- Bouton de retour -->
                <a href="index.php" class="btn btn-primary btn-lg px-5">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-house-door me-2" viewBox="0 0 16 16">
                        <path d="M8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4.5a.5.5 0 0 0 .5-.5v-4h2v4a.5.5 0 0 0 .5.5H14a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146zM2.5 14V7.707l5.5-5.5 5.5 5.5V14H10v-4a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5v4H2.5z"/>
                    </svg>
                    Retour à l'accueil
                </a>
            </div>
        </div>
    </div>
    
   <?php } else if(!isAdmin($_SESSION['email'] ?? '')) { ?>



    <div class="container my-5">
        <div class="alert alert-danger text-center shadow-lg p-5">
            <div class="d-flex flex-column align-items-center gap-4">
                <!-- Icône de sécurité -->
                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor" class="bi bi-shield-lock-fill" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M8 0c-.69 0-1.843.265-2.928.56-1.11.3-2.229.655-2.887.87a1.54 1.54 0 0 0-1.044 1.262c-.596 4.477.787 7.795 2.465 9.99a11.777 11.777 0 0 0 2.517 2.453c.386.273.744.482 1.048.625.28.132.581.24.829.24s.548-.108.829-.24a7.159 7.159 0 0 0 1.048-.625 11.775 11.775 0 0 0 2.517-2.453c1.678-2.195 3.061-5.513 2.465-9.99a1.541 1.541 0 0 0-1.044-1.263 62.467 62.467 0 0 0-2.887-.87C9.843.266 8.69 0 8 0zm0 5a1.5 1.5 0 0 1 .5 2.915l.385 1.99a.5.5 0 0 1-.491.595h-.788a.5.5 0 0 1-.49-.595l.384-1.99A1.5 1.5 0 0 1 8 5z"/>
                </svg>

                <!-- Message principal -->
                <h2 class="fw-bold mb-3">Accès administratif requis</h2>
                
                <!-- Description -->
                <p class="lead mb-4">
                    Vous devez être administrateur pour accéder à cette page.<br>
                    Veuillez contacter le support si vous pensez qu'il s'agit d'une erreur.
                </p>

                <!-- Boutons d'action -->
                <div class="d-flex flex-column flex-md-row gap-3 w-100 justify-content-center">
                    <a href="index.php" class="btn btn-primary btn-lg px-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-house-door me-2" viewBox="0 0 16 16">
                            <path d="M8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4.5a.5.5 0 0 0 .5-.5v-4h2v4a.5.5 0 0 0 .5.5H14a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146zM2.5 14V7.707l5.5-5.5 5.5 5.5V14H10v-4a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5v4H2.5z"/>
                        </svg>
                        Retour à l'accueil
                    </a>

                    <a href="logout.php" class="btn btn-outline-danger btn-lg px-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-box-arrow-right me-2" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0v2z"/>
                            <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z"/>
                        </svg>
                        Se déconnecter
                    </a>
                </div>
            </div>
        </div>
    </div>






    <?php } else { ?>

    <main class="container mt-5">
        <h2 class="mb-4">Créer une nouvelle annonce</h2>

        <?php if($error) { ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php }; ?>

        <?php if($success) { ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php }; ?>

        <form method="POST" action="insertion.php" enctype="multipart/form-data">
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
                           min="1770" max="<?= date('Y') + 1 ?>" required>
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
                
                <div class="col-md-4">
                    <label for="img">Ajouter une image</label>
                    <input type="file" class="form-control" id="img" name="img" accept="image/jpg,image/png,image/gif">
                    <div class="form-text">Formats acceptés : JPG, PNG, GIF.</div>
                </div>
                
                <div class="col-12 mt-4">
                    <button type="submit" class="btn btn-primary btn-lg">Créer l'annonce</button>
                </div>
            </div>
        </form>
    </main>

    <?php } include 'footer.php'; ?>


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