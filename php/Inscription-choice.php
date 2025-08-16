
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
    <title>Inscription</title>
    <meta name="description" content="Esportify - plateforme de gestion d'événements e-sport, inscrivez-vous à des tournois,
    de jeux vidéo, et connectez-vous avec d'autres passionnés de gaming.">
    <meta name="keywords" content="esport, jeu vidéo, tournois, compétitions gaming, événements">
    <link rel="stylesheet" href="/StudiProjetEsport/css/index.css">
    <link rel="stylesheet" href="/StudiProjetEsport/css/inscription-choice.css">
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

    <div id="choice-profil">
        <h1 style="text-decoration: underline;">Choisir son profil</h1>
        <table>
            <tr>
                <td>
                    <img src="/StudiProjetEsport/images/user.png" alt="Image 1" title="utilisateur"
                        id="userProfileImage">
                </td>
            </tr>
            <tr>
                <td><img src="/StudiProjetEsport/images/admin.png" alt="Image 2" title="administrateur"></td>
            </tr>
        </table>
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
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Sélectionner l'image de l'utilisateur
            var userImage = document.getElementById('userProfileImage');

            // Ajouter un écouteur d'événements pour le clic
            userImage.addEventListener('click', function () {
                // Rediriger vers la page d'inscription du profil
                window.location.href = '/StudiProjetEsport/inscription';
            });

            // Changer le curseur en pointeur lorsqu'on survole l'image
            userImage.style.cursor = 'pointer';
        });
    </script>
</body>

</html>
