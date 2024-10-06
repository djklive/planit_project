<?php
session_start();
include '../includes/db.php'; // Ce chemin doit être correct

// // Vérification de la connexion et du rôle de l'utilisateur
// if (!isset($_SESSION['utilisateur_id']) || $_SESSION['role'] !== 'product_owner') {
//     header('Location: login.php'); // Redirection vers la page de connexion si non autorisé
//     exit();
// }

// Récupérer les projets ou tâches liés au Product Owner connecté
$product_owner_id = $_SESSION['utilisateur_id'];
$stmt = $pdo->prepare("SELECT * FROM projets WHERE responsable_id = ?");
$stmt->execute([$product_owner_id]);
$projets = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les projets depuis la base de données
$stmt_projets = $pdo->query("SELECT * FROM projets");
$projets_Admin = $stmt_projets->fetchAll(PDO::FETCH_ASSOC);

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
    <title>Tableau de bord Product Owner</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">

    <nav class="bg-gray-800 p-4">
        
        <div class="max-w-7xl mx-auto">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-2">
                    <img src="../images/logo.png" alt="logo" class="w-10 h-10">
                    <h1 class="text-white text-lg font-semibold">Product Owner</h1>
                    <a href="<?php echo $lien; ?>" class="text-white text-lg font-semibold">Accueil</a>
                </div>
                <div class="space-x-4">
                    <a href="logout.php" class="text-white">Déconnexion</a>
                </div>
            </div>
        </div>
        
    </nav>

    <div class="max-w-7xl mx-auto p-6 sm:p-8">
        <h1 class="text-4xl font-bold mb-8 text-gray-800">Tableau de bord Product Owner</h1>

        <div class="mb-8">
            <a href="product_backlog.php" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Créer un Product Backlog
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <h2 class="text-xl font-semibold p-4 bg-gray-50 border-b">Liste des Projets</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom du Projet</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Budget</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Responsable</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($projets_Admin as $projet): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    <?php echo htmlspecialchars($projet['nom_projet']); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php echo htmlspecialchars($projet['description']); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php echo htmlspecialchars($projet['budget']); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php
                                    $stmt_responsable = $pdo->prepare("SELECT nom FROM utilisateurs WHERE id = ?");
                                    $stmt_responsable->execute([$projet['responsable_id']]);
                                    $responsable = $stmt_responsable->fetch(PDO::FETCH_ASSOC);
                                    echo htmlspecialchars($responsable['nom']);
                                    ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <span class="text-red-600">Aucune</span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>

