<?php
session_start();
include '../includes/db.php'; // Connexion à la base de données

if ($_SESSION['role'] !== 'developpeur') {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord Développeur</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="max-w-7xl mx-auto p-8">
        <h1 class="text-3xl font-bold mb-6 text-center text-gray-800">Tableau de bord Développeur</h1>
        
        <div class="flex justify-center space-x-4">
            <a href="consulter_planning.php" class="bg-blue-500 text-white px-4 py-2 rounded-lg shadow-lg hover:bg-blue-600 transition duration-200">Consulter le Planning</a>
            <a href="messaging.php" class="bg-blue-500 text-white px-4 py-2 rounded-lg shadow-lg hover:bg-blue-600 transition duration-200">Envoyer un Message</a>
            <a href="marquer_avancement.php" class="bg-blue-500 text-white px-4 py-2 rounded-lg shadow-lg hover:bg-blue-600 transition duration-200">Marquer l'Avancement</a>
        </div>
    </div>
</body>
</html>
