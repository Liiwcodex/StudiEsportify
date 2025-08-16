
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
    <title>Recherche d'Événements</title>
    <meta name="description" content="Esportify - plateforme de gestion d'événements e-sport.">
    <meta name="keywords" content="esport, jeu vidéo, tournois, compétitions gaming, événements">
    <link rel="stylesheet" href="/StudiProjetEsport/css/events_research.css">
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
            <a href="#">Événements</a>
            <a href="/StudiProjetEsport/contact">Contact</a>
   
            <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
                <a href="/StudiProjetEsport/deconnexion">Déconnexion</a>
            <?php else: ?>
                <a href="/StudiProjetEsport/connexion">Connexion</a>
            <?php endif; ?>
        </div>
    </div>

    <div id="events-title">
        <h1>Événements</h1>
    </div>

    <form id="research">
        <input type="text" id="search" name="search" placeholder="Tapez quelque chose..." />

        <div id="suggestions" class="suggestions"></div>
   
        <input type="submit" value="Recherche"  id="screen-pc" style="z-index: 1;" />
    </form>

    <!-- Div pour afficher les détails de l'événement -->
    <div id="event-details" class="event-details"></div>

    <footer id="site-footer">
        <div class="footer-icons">
            <a href="/StudiProjetEsport"> <img src="/StudiProjetEsport/images/home.png" alt="Accueil"></a>
            <a href="/StudiProjetEsport/contact"> <img src="/StudiProjetEsport/images/phone-call.png" alt="Téléphone"></a>
            <a href="/StudiProjetEsport/favoris"><img src="/StudiProjetEsport/images/favorite.png" alt="Favoris"></a>
            <a href="/StudiProjetEsport/condition-user"><img src="/StudiProjetEsport/images/contrat.png" alt="Contrat"></a>
        </div>
    </footer>

    <script src="/StudiProjetEsport/js/index.js"></script>
    <script src="/StudiProjetEsport/js/research.js"></script>
    <script>
        document.getElementById('research').addEventListener('submit', function(event) {
            event.preventDefault(); // Empêche le rechargement de la page
            const searchTerm = document.getElementById('search').value;

            // Effectuer une requête AJAX
            fetch(`/StudiProjetEsport/php/search.php?search=${encodeURIComponent(searchTerm)}`)
                .then(response => response.json())
                .then(data => {
                    const eventDetails = document.getElementById('event-details');
                    eventDetails.innerHTML = ''; // Efface les résultats précédents

                    if (data.length > 0) {

     
                        data.forEach(event => {
                    eventDetails.innerHTML += `
                         <div class="event-image-container">
       
                         <img src="${event.photo}" alt="${event.titre}" class="event-image" />
       
                         <h2 class="event-title">${event.titre}</h2>
   
                         </div>
   
                         <div class="event-text">
       
                         <p>${event.description}</p>
       
                         <p>Date: ${event.date}</p>
       
                         <p>Lieu: ${event.Adresse}</p>
    
                         </div>
                    `;
                });
                    } else {
                        eventDetails.innerHTML = '<p>Aucun événement trouvé.</p>';
                    }
                })
                .catch(error => console.error('Erreur:', error));
        });
    </script>
</body>

</html>
