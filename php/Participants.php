
<?php
session_start(); 
// Inclure avant toute sortie HTML
include './config/db.php';

// Gérer les paramètres d'URL
$eventId = $_GET['event_id'] ?? null;

if ($eventId) {
    // Préparer la requête pour récupérer le titre de l'événement
    $eventStmt = $pdo->prepare("SELECT titre FROM events WHERE id = :event_id");
    $eventStmt->execute(['event_id' => $eventId]);
    $event = $eventStmt->fetch(PDO::FETCH_ASSOC);

    // Préparer la requête pour récupérer les participants
    $participantsStmt = $pdo->prepare("SELECT u.pseudo FROM event_user eu JOIN users u ON eu.user_id = u.id WHERE eu.event_id = :event_id");
    $participantsStmt->execute(['event_id' => $eventId]);
    $participants = $participantsStmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $event = null;
    $participants = [];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Participants</title>
    <meta name="description" content="Esportify - plateforme de gestion d'évenements e-sport, inscrivez-vous à des tournois, de jeux vidéo, et connectez-vous avec d'autres passionnés de gaming.">
    <meta name="keywords" content="esport, jeu vidéo, tournois, compétitions gaming, événements">
    <link rel="stylesheet" href="./css/index.css">
    <link rel="stylesheet" href="./css/participants.css">
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

        <h1 id="title-mini">Les participants</h1>

        <?php if ($event && !empty($participants)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Participants</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td data-label="Titre"><?php echo htmlspecialchars($event['titre']); ?></td>
                        <td data-label="Participants">
                            <ul>
                                <?php foreach ($participants as $participant): ?>
                                    <li><?php echo htmlspecialchars($participant['pseudo']); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </td>
                    </tr>
                </tbody>
            </table>
        <?php else: ?>
            <p>Aucun participant inscrit à cet événement ou événement non trouvé.</p>
        <?php endif; ?>

        <footer id="site-footer">
            <div class="footer-icons">
                <a href="/StudiProjetEsport"><img src="/StudiProjetEsport/images/home.png" alt="Accueil"></a>
                <a href="/StudiProjetEsport/contact"><img src="/StudiProjetEsport/images/phone-call.png" alt="Téléphone"></a>
                <a href="/StudiProjetEsport/favoris"><img src="/StudiProjetEsport/images/favorite.png" alt="Favoris"></a>
                <a href="/StudiProjetEsport/condition-user"><img src="/StudiProjetEsport/images/contrat.png" alt="Contrat"></a>
            </div>
        </footer>
    </div>

    <script src="./js/index.js"></script>
</body>
</html>
