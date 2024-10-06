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
                    <img src="../images/logo.png" alt="logo" class="w-10 h-10">
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
        
        <div class="mt-10 hidden" id="btn">
            <!-- Ajouter d'autres sections de votre tableau de bord ici -->
            <h2 class="text-2xl font-bold mb-4">Backlog</h2>
            <table class="min-w-full bg-white border rounded">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="py-2 px-4 border">Product Backlog</th>
                        <th class="py-2 px-4 border">Description</th>
                        <th class="py-2 px-4 border">Priorité</th>
                        <th class="py-2 px-4 border">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($fonctionnalites as $fonctionnalite): ?>
                        <tr>
                            <td class="py-2 px-4 border"><a href="product_backlog_fonctionnalite.php?id=<?php echo $fonctionnalite['id']; ?>" class="text-blue-400 font-bold hover:text-blue-600"><?php echo htmlspecialchars($fonctionnalite['fonctionnalite']); ?></a></td>
                            <td class="py-2 px-4 border"><?php echo htmlspecialchars($fonctionnalite['description']); ?></td>
                            <td class="py-2 px-4 border"><?php echo htmlspecialchars($fonctionnalite['priorite']); ?></td>
                            <td class="py-2 px-4 border">
                                <a href="modifier_fonctionnalite.php?id=<?php echo $fonctionnalite['id']; ?>" class="text-blue-500">Modifier</a>
                                <a href="supprimer_fonctionnalite.php?id=<?php echo $fonctionnalite['id']; ?>" class="text-red-500">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
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
