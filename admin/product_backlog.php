<?php
session_start();
include '../includes/db.php'; // Chemin correct vers le fichier de base de données

// Récupérer les fonctionnalités existantes
$stmt = $pdo->query("SELECT * FROM product_backlog");
$fonctionnalites = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Ajouter une fonctionnalité
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fonctionnalite = trim($_POST['fonctionnalite']);
    $description = trim($_POST['description']);
    $priorite = trim($_POST['priorite']);

    // Validation des champs
    if (empty($fonctionnalite) || empty($priorite)) {
        $error_message = 'Veuillez remplir tous les champs.';
    } else {
        // Préparer la requête pour ajouter une fonctionnalité
        $stmt = $pdo->prepare("INSERT INTO product_backlog (fonctionnalite, description, priorite) VALUES (?, ?, ?)");
        $stmt->execute([$fonctionnalite, $description, $priorite]);
        header('Location: product_backlog.php');
        exit();
    }
}

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
    <title>Gestion du Product Backlog</title>
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
                    <img src="../images/vision pub logo.jpg" alt="logo" class="w-15 h-10">
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
        <h1 class="text-4xl font-bold mb-8 text-gray-800">Gestion du Product Backlog</h1>

        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-2xl font-semibold mb-6 text-blue-600">Ajouter une fonctionnalité</h2>
            <?php if (isset($error_message)): ?>
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                    <p><?php echo htmlspecialchars($error_message); ?></p>
                </div>
            <?php endif; ?>
            <form action="" method="POST" class="space-y-4">
                <div>
                    <label for="fonctionnalite" class="block text-sm font-medium text-gray-700 mb-2">Product Backlog :</label>
                    <input type="text" id="fonctionnalite" name="fonctionnalite" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" required>
                </div>
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description :</label>
                    <textarea id="description" name="description" rows="3" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" required></textarea>
                </div>
                <div>
                    <label for="priorite" class="block text-sm font-medium text-gray-700 mb-2">Priorité :</label>
                    <select id="priorite" name="priorite" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md" required>
                        <option value="haute">Haute</option>
                        <option value="moyenne">Moyenne</option>
                        <option value="basse">Basse</option>
                    </select>
                </div>
                <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Ajouter
                </button>
            </form>
        </div>

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
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
</body>
</html>