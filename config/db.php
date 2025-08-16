
<?php



// db.php

$host = 'localhost'; // Adresse du serveur
$dbname = 'esportify'; // Nom de la base de données
$username = 'root'; // Nom d'utilisateur par défaut de XAMPP
$password = ''; // Mot de passe par défaut de XAMPP (souvent vide)

// Création de la connexion
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Définir le mode d'erreur de PDO sur Exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
}
?>
