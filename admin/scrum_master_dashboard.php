<?php
session_start();
include '../includes/db.php'; // Connexion à la base de données

if ($_SESSION['role'] !== 'scrum_master') {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord Scrum Master</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="max-w-7xl mx-auto p-8">
        <h1 class="text-3xl font-bold mb-6 text-center text-gray-800">Tableau de bord Scrum Master</h1>
        
        <div class="flex justify-center space-x-4">
            <a href="sprint_backlog.php" class="bg-blue-500 text-white px-4 py-2 rounded-lg shadow-lg hover:bg-blue-600 transition duration-200">Créer le Sprint Backlog</a>
            <a href="affecter_taches.php" class="bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg hover:bg-green-600 transition duration-200">Affecter des Tâches</a>
        </div>
        
        <div class="mt-10">
            <!-- Ajouter d'autres sections de votre tableau de bord ici -->
        </div>
    </div>
</body>
</html>
