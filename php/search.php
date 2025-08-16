
<?php
session_start();
include '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['search'])) {
    $searchTerm = htmlspecialchars($_GET['search']); // Échapper les entrées utilisateur

    // Logique pour rechercher des événements dans la base de données
    $stmt = $pdo->prepare("SELECT * FROM events WHERE titre LIKE :titre");
    $stmt->execute(['titre' => "%$searchTerm%"]);
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Retourner les résultats au format JSON
    echo json_encode($events);
}
?>
