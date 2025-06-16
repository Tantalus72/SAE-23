<?php
session_start();
require_once 'functions/functions.php';

// Redirection si déjà connecté
if(isset($_SESSION['email'])) {
    header('Location: index.php');
    exit();
}

$error = ''; // On définir une variable en cas d'erreur

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(!empty($_POST['email']) && !empty($_POST['password'])) {
        if(login($_POST['email'], $_POST['password'])) {
            
            // On créer la session
            $_SESSION['email'] = $_POST['email'];
            $status = "user"; // Statut par défaut

            // Si l'utilisateur est admin, on change le statut
            if (isAdmin($_SESSION["email"])){
                $status="admin";
            } 

            // Gestion des logs
            $fichier = fopen('./logs/access.log','a+'); // fichier de logs

            // On écrit dans le fichier de logs
            fputs($fichier,$_POST['email']." de ".$_SERVER['REMOTE_ADDR']." à ".date('l jS\of F Y h:i:s A')." sous le status :".$status." s'est connecté en");
            fputs($fichier,"\n");
            fclose($fichier);
            
            // On redirige vers la page d'accueil
            header('Location: index.php');
            exit();
        } else { // en cas d'erreur de connexion
            $error = 'Identifiants incorrects';
            $fichier = fopen('./logs/access.log','a+');
            fputs($fichier,$_POST['email']." de ".$_SERVER['REMOTE_ADDR']." à ".date('l jS\of F Y h:i:s A')." ne s'est pas connecté");
            fputs($fichier,"\n");
            fclose($fichier);
        }
    } else {
        $error = 'Veuillez remplir tous les champs';
    }
}
?>
   
    <?php include 'partial/navbar.php'; ?>


    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card shadow">
                    <div class="card-body p-4">
                        <h2 class="text-center mb-4">Connexion</h2>
                        
                        <?php if($error) {?>
                            <div class="alert alert-danger"><?= $error ?></div>
                        <?php }; ?>

                        <form method="POST" onsubmit="return verif_login()">
                            <div class="mb-3">
                                <label for="email" class="form-label">Adresse email</label>
                                <input type="email" class="form-control" 
                                       id="email" name="email" 
                                       value="<?= $_POST['email'] ?? '' ?>" 
                                       required autofocus>
                            </div>
                            
                            <div class="mb-4">
                                <label for="password" class="form-label">Mot de passe</label>
                                <input type="password" class="form-control" 
                                       id="password" name="password" required>
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100" onclick="return verif_login()">Se connecter</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'partial/footer.php'; ?>

    <script>
        function verif_login() {
            const mdp = document.getElementById("password").value;
            // Le mot de passe doit contenir au moins une majuscule et un caractère spécial
            const regex = /^(?=.*[A-Z])(?=.*[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?])/;

            if (!regex.test(mdp)) {
                alert("Le mot de passe doit contenir au moins une majuscule et un caractère spécial.");
                return false; // bloque la soumission du formulaire
            }

            return true; // autorise l'envoi
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>