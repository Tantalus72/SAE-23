<?php
session_start();
session_unset();
session_destroy();

include 'functions/functions.php'
// Afficher la page de déconnexion
?>

<?php
include 'navbar.php';
?>

<div class="container my-5 text-center">
    <div class="card shadow p-5">
        <h1 class="text-success mb-4">À bientôt !</h1>
        <p class="lead">Vous avez été déconnecté avec succès.</p>
        <div class="text-center mt-3">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="visually-hidden">Chargement...</span>
            </div>
        </div>
        <p class="mt-3">Redirection vers l'accueil dans <span id="countdown">3</span> secondes...</p>
    </div>
</div>

<script>
    // Compte à rebours
    let seconds = 5;
    const countdownEl = document.getElementById('countdown');
    if(countdownEl) {
        setInterval(() => {
            countdownEl.textContent = --seconds > 0 ? seconds : '';
        }, 1000);
    }
</script>

<?php 
include 'footer.php';

// Redirection avec compte à rebours
redirect('index.php', 5);
exit();
?>
