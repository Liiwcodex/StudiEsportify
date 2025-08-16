
<?php

session_start(); 
// Inclure avant toute sortie HTML
include './config/db.php';
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription Profil</title>
    <meta name="description" content="Esportify - plateforme de gestion d'événements e-sport, inscrivez-vous à des tournois,
    de jeux vidéo, et connectez-vous avec d'autres passionnés de gaming.">
    <meta name="keywords" content="esport, jeu vidéo, tournois, compétitions gaming, événements">
    <link rel="stylesheet" href="/StudiProjetEsport/css/inscription-profil.css">
</head>

<body class="scrollable-content">





<?php


$msg="";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $pseudo = $_POST['pseudo'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Hachage du mot de passe pour la sécurité
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Préparer la requête d'insertion
    $sql = "INSERT INTO users (pseudo, email, password) VALUES (:pseudo, :email, :password)";
    $stmt = $pdo->prepare($sql);

    // Lier les paramètres
    $stmt->bindParam(':pseudo', $pseudo);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashedPassword);

    // Exécuter la requête
    if ($stmt->execute()) {

        $msg="Inscription réussie !";
      
    } else {
        echo "Erreur lors de l'inscription.";
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

    <div id="inscription-title">
        <h1 style="padding-top: 25%;">Inscription Profil</h1>
    </div>

    <!-- Nouveau formulaire d'inscription -->
    <form id="inscription-form" action="/StudiProjetEsport/inscription" method="POST">


        <div class="form-group">
            <label for="pseudo">Pseudo :</label>
            <input type="text" id="pseudo" name="pseudo" required>
        </div>
        <div class="form-group">
            <label for="email">Email :</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="password">Mot de passe :</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit">Enregistrer</button>
    </form>

   
<?php if($msg!="") echo $msg ; ?>

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
</body>

</html>
