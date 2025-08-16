
<?php

// Inclure le fichier de configuration où session_start() est appelé
include './config/db.php';

// Vérifier si une session est déjà active

// Capture l'URL demandée
$request = $_SERVER['REQUEST_URI'];

// Extraire le chemin et les paramètres
$urlParts = parse_url($request);
$path = $urlParts['path'];
$query = isset($urlParts['query']) ? $urlParts['query'] : '';

// Liste des routes disponibles
switch ($path) {
    case '/StudiProjetEsport':
        require __DIR__ . '/index.html';  // Page d'accueil
        break;
    case '/StudiProjetEsport/connexion':
        require __DIR__ . '/php/Connexion.php'; // Page connexion
        break;
    case '/StudiProjetEsport/deconnexion':
        require __DIR__ . '/php/Deconnexion.php'; // Page déconnexion
        break;
    case '/StudiProjetEsport/contact':
        require __DIR__ . '/php/Contact.php'; // Page contact
        break;
    case '/StudiProjetEsport/condition-user':
        require __DIR__ . '/php/Condition-user.php'; // Page condition utilisateur
        break;
    case '/StudiProjetEsport/recherche-evenement':
        require __DIR__ . '/php/EventsResearch.php'; // Page recherche d'événements
        break;
    case '/StudiProjetEsport/inscription':
        require __DIR__ . '/php/inscription-profil.php'; // Page inscription
        break;
    case '/StudiProjetEsport/inscription-profil':
        require __DIR__ . '/php/Inscription-choice.php'; // Page choix d'inscription
        break;
    case '/StudiProjetEsport/participants':
        // Gérer les paramètres d'URL
        parse_str($query, $params);
        if (isset($params['event_id'])) {
            // Logique pour afficher les participants pour l'événement spécifique
            require __DIR__ . '/php/Participants.php'; // Page des participants
        } else {
            echo "Aucun événement spécifié."; // Message d'erreur
        }
        break;

    case '/StudiProjetEsport/favoris':

        require __DIR__ . '/php/Favoris.php'; // Page connexion

        break;
    
    default:
        require __DIR__ . '/php/404.php'; // Page 404 si la route n'existe pas
        break;
}

// Traitement de la recherche d'événements
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['search'])) {
    $searchTerm = htmlspecialchars($_GET['search']); // Échapper les entrées utilisateur

    // Logique pour rechercher des événements dans la base de données
    $stmt = $pdo->prepare("SELECT * FROM events WHERE titre LIKE :title");
    $stmt->execute(['title' => "%$searchTerm%"]);
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Afficher les résultats
    if ($events) {
        foreach ($events as $event) {
            echo "<h2>{$event['titre']}</h2>";
            echo "<p>{$event['description']}</p>";
            echo "<p>Date: {$event['date']}</p>";
            echo "<p>Lieu: {$event['Adresse']}</p>";
        }
    } else {
        echo "<p>Aucun événement trouvé.</p>";
    }
}  
?>
