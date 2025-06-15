<?php
session_start();
require_once 'functions/functions.php';


// Connexion et sélection des marques / modèles

$pdo = getPDO();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$marques  = $pdo->query("SELECT * FROM marques")->fetchAll(PDO::FETCH_ASSOC);
$modeles  = $pdo->query("SELECT * FROM modeles")->fetchAll(PDO::FETCH_ASSOC);
?>

    <?php include 'partial/navbar.php'; ?>

    <?php
    // redirection 
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
        if (empty($_GET) || !isset($_GET['idVoiture'])) {
    ?>

  <main class="container mt-5">
    <h2>Créer une nouvelle annonce</h2>
    <div id="message"></div>

    <form id="formAnnonce" enctype="multipart/form-data">
      <div class="row g-3">
        <!-- Marque -->
        <div class="col-md-6">
          <label for="idMarque" class="form-label">Marque</label>
          <select id="idMarque" name="idMarque" class="form-select" required>
            <option value="">Sélectionnez une marque</option>
            <?php foreach($marques as $m) { ?>
              <option value="<?= $m['idMarque'] ?>"><?= $m['nom'] ?></option>
            <?php }; ?>
          </select>
        </div>

        <!-- Modèle -->
        <div class="col-md-6">
          <label for="idModele" class="form-label">Modèle</label>
          <select id="idModele" name="idModele" class="form-select" required>
            <option value="">Sélectionnez un modèle</option>
            <?php foreach($modeles as $m) { ?>
              <option value="<?= $m['idModele'] ?>"
                      data-marque="<?= $m['idMarque'] ?>">
                <?= htmlspecialchars($m['nom']) ?>
              </option>
            <?php }; ?>
          </select>
        </div>

        <!-- Désignation -->
        <div class="col-12">
          <label for="designation" class="form-label">Désignation</label>
          <input type="text" id="designation" name="designation"
                 class="form-control" maxlength="100" required>
        </div>

        <!-- Année / Kilométrage / Prix -->
        <div class="col-md-4">
          <label for="annee" class="form-label">Année</label>
          <input type="number" id="annee" name="annee"
                 class="form-control" min="1770" max="<?= date('Y')+1 ?>" required>
        </div>
        <div class="col-md-4">
          <label for="kilometrage" class="form-label">Kilométrage</label>
          <input type="number" id="kilometrage" name="kilometrage"
                 class="form-control" min="0" required>
        </div>
        <div class="col-md-4">
          <label for="prix" class="form-label">Prix (€)</label>
          <input type="number" id="prix" name="prix"
                 class="form-control" min="0" required>
        </div>

        <!-- Image -->
        <div class="col-md-4">
          <label for="img" class="form-label">Ajouter une image</label>
          <input type="file" id="img" name="img"
                 class="form-control"
                 accept=".jpg,.jpeg,.png,.gif">
          <div class="form-text">JPG, PNG, GIF (max 2 Mo).</div>
        </div>

        <!-- Bouton -->
        <div class="col-12 mt-4">
          <button type="submit" class="btn btn-primary btn-lg">
            Créer l'annonce
          </button>
        </div>
      </div>
    </form>
  </main>

  <?php }
     }
     include 'partial/footer.php'; 
  ?>

  <script>

    const msgDiv = document.getElementById('message'); // message d'erreur



    // Requete Ajax
    document.getElementById('formAnnonce').addEventListener('submit', function(e) {
      e.preventDefault(); //pour ne pas recharger la page
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
          if (xhr.status === 200 && !json.error) {
            msgDiv.innerHTML = `<div class="alert alert-success">${json.success}</div>`;
            e.target.reset();
          } else {
            msgDiv.innerHTML = `<div class="alert alert-danger">${json.error||'Erreur serveur'}</div>`;
          }
        }
      };

      xhr.open('POST', 'insertion_ajax.php', true);
      xhr.send(data);
    });

    // Filtrer dynamiquement les modèles à partir des marques
    document.getElementById('idMarque').addEventListener('change', function() {
      const marque = this.value;
      document.querySelectorAll('#idModele option').forEach(opt => {
        opt.style.display = (opt.value === '' || opt.dataset.marque === marque)
                          ? 'block' : 'none';
      });
      document.getElementById('idModele').value = '';
    });
  </script>
</body>
</html>
