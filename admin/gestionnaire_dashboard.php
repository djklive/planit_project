<?php
session_start();
include '../includes/db.php'; // Connexion à la base de données

if ($_SESSION['role'] !== 'gestionnaire') {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord Gestionnaire de Projet</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <h1 class="text-3xl font-bold mb-6">Tableau de bord Gestionnaire de Projet</h1>
    <a href="ajouter_projet.php" class="btn btn-blue">Créer un Projet</a>
    <a href="creer_equipe.php" class="btn btn-blue">Créer une Équipe</a>
</body>
</html>
