<?php
session_start();
include '../includes/db.php'; // Chemin correct vers le fichier de base de données

// // Vérification de la connexion
// if (!isset($_SESSION['utilisateur_id']) || $_SESSION['role'] !== 'admin') {
//     header('Location: login.php');
//     exit();
// }

// Récupérer le projet à supprimer
if (!isset($_GET['id'])) {
    header('Location: admin_dashboard.php'); // Rediriger si l'ID n'est pas fourni
    exit();
}

// Supprimer le projet de la base de données
$stmt = $pdo->prepare("DELETE FROM projets WHERE id = ?");
if ($stmt->execute([$_GET['id']])) {
    header('Location: admin_dashboard.php'); // Rediriger vers le tableau de bord après suppression
    exit();
} else {
    // Gérer l'erreur si la suppression échoue
    echo "Erreur lors de la suppression du projet.";
}
?>
