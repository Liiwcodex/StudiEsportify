
<?php
session_start();
include './config/db.php';

if (isset($_GET['title'])) {
    $title = $_GET['title'];

    // Préparer et exécuter la requête
    $stmt = $pdo->prepare("SELECT * FROM events WHERE titre= :title");
    $stmt->execute(['title' => $title]);
    $event = $stmt->fetch(PDO::FETCH_ASSOC);

    // Retourner les données au format JSON
    echo json_encode($event);
} else {
    echo json_encode(null);
}
?>
