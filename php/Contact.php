
<?php

session_start(); 
include './config/db.php';
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact</title>
    <meta name="description" content="Esportify - plateforme de gestion d'événements e-sport, inscrivez-vous à des tournois,
    de jeux vidéo, et connectez-vous avec d'autres passionnés de gaming.">
    <meta name="keywords" content="esport, jeu vidéo, tournois, compétitions gaming, événements">
    <link rel="stylesheet" href="/StudiProjetEsport/css/index.css">
    <link rel="stylesheet" href="/StudiProjetEsport/css/Contact.css">
</head>

<body>


   <!--  DEBUT  MENU  GRAND  ECRAN  -->
   <nav class="horizontal-menu">
        <a href="#">Accueil</a>
        <a href="./html/EventsResearch.html">Événements</a>
        <a href="./html/Contact.html">Contact</a>
        <a href="#">Favoris</a>
        <a href="#">Chat</a>
        
        <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
            <a href="/StudiProjetEsport/deconnexion">Déconnexion</a>
        <?php else: ?>
            <a href="/StudiProjetEsport/connexion">Connexion</a>
        <?php endif; ?>
    </nav>












    <!-- Menu Hamburger -->
    <div class="menu-hamburger">
        <div class="hamburger-icon" onclick="toggleMenu()">
            ☰ <!-- Icône hamburger -->
        </div>
        <div id="menu" class="menu-links">
            <a href="/StudiProjetEsport">Accueil</a>
            <a href="/StudiProjetEsport/recherche-evenement">Événements</a>
                <a href="#">Contact</a>
               
                <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>


                    <a href="/StudiProjetEsport/deconnexion">Déconnexion</a>


                    <?php else: ?>

    
                        <a href="/StudiProjetEsport/connexion">Connexion</a>


                        <?php endif; ?>
        </div>
    </div>

    <h1 id="contact-title">Contact  <br></h1>

     <center>
        
     
        <div id="Goooglemap">


             <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2603.1522373043986!2d2.4641493763310347!3d49.27351247139136!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e64a3e98857b4b%3A0x6d294a199e4c17f0!2sPl.%20des%203%20Rois%2C%2060180%20Nogent-sur-Oise!5e0!3m2!1sfr!2sfr!4v1731400762530!5m2!1sfr!2sfr" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            
        
        </div>
    
    </center> 

    <p style="text-decoration: underline; color:white; margin-top: 90%;"> 06 51 94 22 71 </p>

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
    <script>
        window.onload = function () {
            console.log("Largeur de l'écran : " + window.innerWidth + "px");
            console.log("Hauteur de l'écran : " + window.innerHeight + "px");
        };
    </script>
</body>

</html>
