

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">
            <img src="ressources/image/TROUVETACAISSELOGO.png" alt="Logo" class="img-fluid">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="index.php">Accueil</a></li>
                <li class="nav-item"><a class="nav-link" href="annonces.php">Annonces</a></li>
                <li class="nav-item"><a class="nav-link" href="rechercher.php">Rechercher</a></li>
                <?php if(isAdmin($_SESSION['email'] ?? '')) { ?>
                <li class="nav-item"><a class="nav-link" href="insertion.php">Insertion</a></li>
                <li class="nav-item"><a class="nav-link" href="suppression.php">Suppression</a></li>
                <?php }; ?>
            </ul>
            <ul class="navbar-nav">
                <?php if(isset($_SESSION['email'])) { ?>
                <li class="nav-item"><a class="nav-link" href="logout.php">Déconnexion</a></li>
                <?php } else { ?>
                <li class="nav-item"><a class="nav-link" href="login.php">Connexion</a></li>
                <?php }; ?>
            </ul>
        </div>
    </div>
</nav>
