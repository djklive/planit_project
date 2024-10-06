<?php
session_start();
include '../includes/db.php'; // Connexion à la base de données

if ($_SESSION['role'] !== 'scrum_master') {
    header('Location: login.php');
    exit();
}

// Récupérer les fonctionnalités existantes
$stmt = $pdo->query("SELECT * FROM product_backlog");
$fonctionnalites = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SESSION['role'] == 'developpeur') {
    $lien = "developpeur_dashboard.php";
 }
 elseif ($_SESSION['role'] == 'scrum_master') {
    $lien = "scrum_master_dashboard.php";
 }
 elseif ($_SESSION['role'] == 'product_owner') {
    $lien = "product_owner_dashboard.php";
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

    <nav class="bg-gray-800 p-4">
        <div class="max-w-7xl mx-auto">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-2">
                    <img src="../images/vision pub logo.jpg" alt="logo" class="w-15 h-10">
                    <h1 class="text-white text-lg font-semibold">Scrum Master</h1>
                    <a href="<?php echo $lien; ?>" class="text-white text-lg font-semibold">Accueil</a>
                </div>
                <div class="space-x-4">
                    <a href="logout.php" class="text-white">Déconnexion</a>
                </div>
            </div>
        </div>
    </nav> 

    <div class="max-w-7xl mx-auto p-8">
        <h1 class="text-3xl font-bold mb-6 text-center text-gray-800">Tableau de bord Scrum Master</h1>
        
        <div class="flex justify-center space-x-4">
            <a href="#" class="bg-blue-500 text-white px-4 py-2 rounded-lg shadow-lg hover:bg-blue-600 transition duration-200" id="appear">Créer le Sprint Backlog</a>
            <a href="affecter_taches.php" class="bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg hover:bg-green-600 transition duration-200">Affecter des Tâches</a>
        </div>
        
        <div class="bg-white rounded-lg shadow-md overflow-hidden mt-8 hidden" id="btn">
            <h2 class="text-xl font-semibold p-4 bg-gray-50 border-b">Backlog</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product Backlog</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priorité</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($fonctionnalites as $fonctionnalite): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="product_backlog_fonctionnalite.php?id=<?php echo $fonctionnalite['id']; ?>" class="text-blue-600 hover:text-blue-900 font-medium">
                                        <?php echo htmlspecialchars($fonctionnalite['fonctionnalite']); ?>
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php echo htmlspecialchars($fonctionnalite['description']); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        <?php 
                                        if ($fonctionnalite['priorite'] == 'haute') {
                                            echo 'bg-red-100 text-red-800';
                                        } elseif ($fonctionnalite['priorite'] == 'moyenne') {
                                            echo 'bg-yellow-100 text-yellow-800';
                                        } elseif ($fonctionnalite['priorite'] == 'basse') {
                                            echo 'bg-green-100 text-green-800';
                                        } else {
                                            echo 'bg-gray-100 text-gray-800';
                                        }
                                        ?>">
                                        <?php echo htmlspecialchars($fonctionnalite['priorite']); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="modifier_fonctionnalite.php?id=<?php echo $fonctionnalite['id']; ?>" class="text-indigo-600 hover:text-indigo-900 mr-3">Modifier</a>
                                    <a href="supprimer_fonctionnalite.php?id=<?php echo $fonctionnalite['id']; ?>" class="text-red-600 hover:text-red-900">Supprimer</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        const appear = document.getElementById("appear");
        appear.addEventListener("click", () =>{
            let btn = document.getElementById("btn")
            btn.classList.toggle("hidden")
        });
    </script>
</body>
</html>
