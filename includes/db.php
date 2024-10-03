<?php
// Informations de connexion
$host = 'localhost'; // Hôte de la base de données
$dbname = 'planit'; // Nom de la base de données
$username = 'root'; // Nom d'utilisateur (par défaut pour XAMPP ou WAMP)
$password = ''; // Mot de passe (laisser vide si vous utilisez XAMPP ou WAMP)

try {
    // Création de la connexion PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    // Activer les erreurs PDO
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Afficher un message d'erreur si la connexion échoue
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>
