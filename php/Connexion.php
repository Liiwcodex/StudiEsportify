
<?php

session_start(); 

include './config/db.php';
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <meta name="description" content="Esportify - plateforme de gestion d'événements e-sport, inscrivez-vous à des tournois,
    de jeux vidéo, et connectez-vous avec d'autres passionnés de gaming.">
    <meta name="keywords" content="esport, jeu vidéo, tournois, compétitions gaming, événements">
    <link rel="stylesheet" href="/StudiProjetEsport/css/Connexion.css">
</head>

<body class="scrollable-content">

    <?php include './config/db.php'; ?>
    <?php 

    $msg = ""; // Message d'erreur ou de succès

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Préparer la requête pour récupérer l'utilisateur
    $sql = "SELECT * FROM users WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    // Vérifier si l'utilisateur existe
    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Vérifier le mot de passe
        if (password_verify($password, $user['password'])) {
            // Stocker les informations de l'utilisateur dans la session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['pseudo'];
            $_SESSION['logged_in'] = true; // Indiquer que l'utilisateur est connecté

            // Rediriger vers la page d'accueil ou une autre page
            header("Location:/StudiProjetEsport");
            exit();
        } else {
            $msg = "Mot de passe incorrect.";
        }
    } else {
        $msg = "Aucun utilisateur trouvé avec cet email.";
    }
}
?>



    <!-- Menu Hamburger -->
    <div class="menu-hamburger">
        <div class="hamburger-icon" onclick="toggleMenu()">
            ☰ <!-- Icône hamburger -->
        </div>
        <div id="menu" class="menu-links">
            <a href="/StudiProjetEsport">Accueil</a>
            <a href="/StudiProjetEsport/recherche-evenement">Événements</a>
            <a href="/StudiProjetEsport/contact">Contact</a>
            <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>


                <a href="/StudiProjetEsport/deconnexion">Déconnexion</a>


                <?php else: ?>

    
                    <a href="/StudiProjetEsport/connexion">Connexion</a>


                    <?php endif; ?>
        </div>
    </div>

    <div id="connexion-title">
        <h1 style="padding-top: 25%;">Connexion</h1>
    </div>

    <div class="form-container">
        <form   action="/StudiProjetEsport/connexion" method="POST">
         

            <label for="email">Email :</label>
            <input type="email" id="email" name="email" style="margin-left: 40px;"><br><br><br>
            <label for="password">Mot de passe :</label>
            <input type="password" id="password" name="password" style="margin-left: 40px;"><br><br><br><br>
            <input type="submit" value="Connectez">
        </form>

        <p>
            <a href="/StudiProjetEsport/inscription" style="color:white"> Pas de compte ? Créez-en un
                ici </a>
        </p>
    </div>

    <footer id="site-footer">
        <div class="footer-icons">
            <a href="/StudiProjetEsport"> <img src="/StudiProjetEsport/images/home.png" alt="Accueil"></a>
            <a href="/StudiProjetEsport/contact"> <img src="/StudiProjetEsport/images/phone-call.png"
                    alt="Téléphone"></a>
            <a href="/StudiProjetEsport/favoris"><img src="/StudiProjetEsport/images/favorite.png" alt="Favoris"></a>
            <a href="/StudiProjetEsport/condition-user"><img src="/StudiProjetEsport/images/contrat.png"
                    alt="Contrat"></a>
        </div>
    </footer>

    <script src="/StudiProjetEsport/js/index.js"></script>
    <script src="/StudiProjetEsport/js/connexion.js"></script>
    <script>
        window.onload = function () {
            console.log("Largeur de l'écran : " + window.innerWidth + "px");
            console.log("Hauteur de l'écran : " + window.innerHeight + "px");
        };
    </script>
</body>

</html>
