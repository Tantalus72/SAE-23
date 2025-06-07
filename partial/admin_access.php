<div class="container my-5">
  <div class="alert alert-danger shadow-lg py-5 px-4 text-center mx-auto" style="max-width: 700px;">
    <!-- Icône de sécurité -->
    <div class="mb-4">
      <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor" class="bi bi-shield-lock-fill text-danger" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M8 0c-.69 0-1.843.265-2.928.56-1.11.3-2.229.655-2.887.87a1.54 1.54 0 0 0-1.044 1.262c-.596 4.477.787 7.795 2.465 9.99a11.777 11.777 0 0 0 2.517 2.453c.386.273.744.482 1.048.625.28.132.581.24.829.24s.548-.108.829-.24a7.159 7.159 0 0 0 1.048-.625 11.775 11.775 0 0 0 2.517-2.453c1.678-2.195 3.061-5.513 2.465-9.99a1.541 1.541 0 0 0-1.044-1.263 62.467 62.467 0 0 0-2.887-.87C9.843.266 8.69 0 8 0zm0 5a1.5 1.5 0 0 1 .5 2.915l.385 1.99a.5.5 0 0 1-.491.595h-.788a.5.5 0 0 1-.49-.595l.384-1.99A1.5 1.5 0 0 1 8 5z"/>
      </svg>
    </div>

    <!-- Titre -->
    <h2 class="fw-bold mb-3">Accès administratif requis</h2>

    <!-- Description -->
    <p class="lead mb-4">
      Vous devez être administrateur pour accéder à cette page.<br>
      Veuillez contacter le support si vous pensez qu'il s'agit d'une erreur.
    </p>

    <!-- Boutons d'action -->
    <div class="d-flex flex-column flex-md-row justify-content-center align-items-center gap-3 mb-4">
      <a href="index.php" class="btn btn-primary btn-lg px-4">
        <i class="bi bi-house-door me-2"></i> Retour à l'accueil
      </a>

      <a href="logout.php" class="btn btn-outline-danger btn-lg px-4">
        <i class="bi bi-box-arrow-right me-2"></i> Se déconnecter
      </a>
    </div>

    <!-- Compte à rebours + chargement -->
    <div class="mb-3">
      <p class="lead text-muted fw-bold">
        Redirection vers la page de connexion dans <span id="countdown">10</span> secondes...
      </p>
      <div class="d-flex justify-content-center">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
          <span class="visually-hidden">Chargement...</span>
        </div>
      </div>
    </div>
  </div>
</div>



<script>
    // Compte à rebours
    let seconds = 10;
    const countdownEl = document.getElementById('countdown');
    if(countdownEl) {
        setInterval(() => {
            countdownEl.textContent = --seconds > 0 ? seconds : '';
        }, 1000);
    }
</script>