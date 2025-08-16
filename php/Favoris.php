
<?php
session_start(); 
// Inclure avant toute sortie HTML
include './config/db.php'; // Assurez-vous que ce chemin est correct

// Vérifier si la variable $pdo est définie
if (!isset($pdo)) {
    die("Erreur de connexion à la base de données.");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Favoris</title>
    <meta name="description" content="Esportify - plateforme de gestion d'événements e-sport, inscrivez-vous à des tournois, de jeux vidéo, et connectez-vous avec d'autres passionnés de gaming.">
    <meta name="keywords" content="esport, jeu vidéo, tournois, compétitions gaming, événements">
    <link rel="stylesheet" href="./css/index.css">
    <style>
        .message {
            color: white;
            text-align: center;
            margin-top: 20px;
            font-size: 18px;
        }
        .favorite-events {
            list-style-type: none;
            padding: 0;
        }
        .favorite-events li {
            margin: 20px 0;
            border: 1px solid #C65D3A;
            padding: 10px;
            border-radius: 5px;
        }
    </style>
</head>
<body>

    <!-- DEBUT MENU GRAND ECRAN -->
    <nav class="horizontal-menu">
        <a href="#">Accueil</a>
        <a href="./html/EventsResearch.html">Événements</a>
        <a href="./html/Contact.html">Contact</a>
        <a href="/StudiProjetEsport/favoris"></a>
        <a href="#">Chat</a>
        
        <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
            <a href="/StudiProjetEsport/deconnexion">Déconnexion</a>
        <?php else: ?>
            <a href="/StudiProjetEsport/connexion">Connexion</a>
        <?php endif; ?>
    </nav>
  
    <!-- DEBUT HTML PETIT ECRAN -->
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

    <h1 id="title-mini"> Vos favoris ! </h1>

    <div class="container">
        <?php if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']): ?>
            <div class="message">
                Vous devez être connecté pour voir vos favoris. <br>
                <a href="/StudiProjetEsport/connexion">Cliquez ici pour vous connecter.</a>
            </div>
        <?php else: ?>
          
            
            <?php
            // Récupérer les événements favoris de l'utilisateur
            $user_id = $_SESSION['user_id']; // Assurez-vous que l'ID de l'utilisateur est stocké dans la session
            
            $query = "SELECT e.id, e.titre, e.description, e.date, e.heure, e.adresse, e.photo 
                      FROM favorites f 
                      JOIN events e ON f.event_id = e.id 
                      WHERE f.user_id = :user_id";
            
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($events): ?>
                <ul class="favorite-events">
                    <?php foreach ($events as $event): ?>
                        <li>
                            <h2><?= htmlspecialchars($event['titre']) ?></h2>
                            <center><img src="<?= htmlspecialchars($event['photo']) ?>" alt="<?= htmlspecialchars($event['titre']) ?>" style="max-width: 200px;"></center>
                            <p><?= htmlspecialchars($event['description']) ?></p>
                            <p>Date: <?= htmlspecialchars($event['date']) ?> à <?= htmlspecialchars($event['heure']) ?></p>
                            <p>Adresse: <?= htmlspecialchars($event['adresse']) ?></p>
                            
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <div class="message">Aucun favori trouvé.</div>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <footer id="site-footer">
        <div class="footer-icons">
            <a href="/StudiProjetEsport"><img src="/StudiProjetEsport/images/home.png" alt="Accueil"></a>
            <a href="/StudiProjetEsport/contact"><img src="/StudiProjetEsport/images/phone-call.png" alt="Téléphone"></a>
            <a href="/StudiProjetEsport/favoris"><img src="/StudiProjetEsport/images/favorite.png" alt="Favoris"></a>
            <a href="/StudiProjetEsport/condition-user"><img src="/StudiProjetEsport/images/contrat.png" alt="Contrat"></a>
        </div>
    </footer>

    <script src="./js/index.js"></script>

</body>
</html>
