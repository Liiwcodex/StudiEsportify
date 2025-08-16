
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
    <title>Condition user</title>
    <meta name="description" content="Esportify - plateforme de gestion d'événements e-sport, inscrivez-vous à des tournois,
    de jeux vidéo, et connectez-vous avec d'autres passionnés de gaming.">
    <meta name="keywords" content="esport, jeu vidéo, tournois, compétitions gaming, événements">
    <link rel="stylesheet" href="/StudiProjetEsport/css/index.css">
    <link rel="stylesheet" href="/StudiProjetEsport/css/Condition-user.css">
</head>

<body>



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

    <h1 id="condition-user-title">Conditions d'utilisation</h1>

    <p id="rules-of-app">
        En utilisant Esportify, vous acceptez les règles suivantes :<br /><br />

        <strong>1. Compte :</strong> Vous êtes responsable de la sécurité de votre compte. Comportement malveillant ou
        tricherie entraînera l'exclusion.<br /><br />

        <strong>2. Données personnelles :</strong> Vos données sont collectées pour les services fournis et ne seront
        pas partagées sans votre consentement.<br /><br />

        <strong>3. Respect des règles :</strong> Les participants doivent respecter les règles des tournois. Toute
        violation sera sanctionnée.<br /><br />

        <strong>4. Responsabilité :</strong> Nous ne sommes pas responsables des interruptions de service ou problèmes
        techniques pendant les événements.<br /><br />

        <strong>5. Modifications :</strong> Les conditions peuvent être mises à jour. Il est de votre responsabilité de
        consulter cette page régulièrement.<br /><br />

        En cas de questions, contactez-nous via notre page dédiée.

    <form action="/StudiProjetEsport/inscription" method="post">
        <input type="checkbox" required>
        J'accepte les conditions d'utilisation.
        <button type="submit">S'inscrire</button>
    </form>
    </p>

    <footer id="site-footer">
        <div class="footer-icons">
            <a href="/StudiProjetEsport"> <img src="/StudiProjetEsport/images/home.png" alt="Accueil"> </a>
            <a href="//StudiProjetEsport/contact"> <img src="/StudiProjetEsport/images/phone-call.png" alt="Téléphone">
            </a>
            <a href="/StudiProjetEsport/favoris"><img src="/StudiProjetEsport/images/favorite.png" alt="Favoris"> </a>
            <a href="#"><img src="/StudiProjetEsport/images/contrat.png" alt="Contrat"> </a>
        </div>
    </footer>

    <script src="/StudiProjetEsport/js/index.js"></script>
    <script>
        window.onload = function () {
            console.log("Largeur de l'écran : " + window.innerWidth + "px");
            console.log("Hauteur de l'écran : " + window.innerHeight + "px");
        };
    </script>
</body>

</html>
