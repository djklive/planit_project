<?php 
session_start();
include '../includes/db.php'; // Chemin correct vers le fichier de base de données

// // Vérification de la connexion et du rôle de l'utilisateur
// if (!isset($_SESSION['utilisateur_id']) || $_SESSION['role'] !== 'admin') {
//     header('Location: login.php');
//     exit();
// }

// Récupérer les fonctionnalités existantes
$stmt = $pdo->query("SELECT * FROM product_backlog");
$fonctionnalites = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer tous les développeurs
$stmt_utilisateurs = $pdo->prepare("SELECT * FROM utilisateurs where role = 'developpeur'");
$stmt_utilisateurs->execute();
$utilisateurs = $stmt_utilisateurs->fetchAll(PDO::FETCH_ASSOC);

// Traitement de l'ajout et la mise à jour du développeur 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tache_id = $_POST['tache_id'];
    $utilisateur_id = $_POST['utilisateur_id'];
    
    $stmt = $pdo->prepare("UPDATE taches SET id_developpeur = ? WHERE id = ?");
    $stmt->execute([$utilisateur_id, $tache_id]);

    // Rediriger vers la même page pour éviter la soumission multiple du formulaire
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
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
    <title>Affecter Tâches</title>
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
                    <h1 class="text-white text-lg font-semibold">Scrum Master</h1>
                    <a href="<?php echo $lien; ?>" class="text-white text-lg font-semibold">Accueil</a>
                </div>
                <div class="space-x-4">
                    <a href="logout.php" class="text-white">Déconnexion</a>
                </div>
            </div>
        </div>
        
    </nav>

    <div class="max-w-7xl mx-auto p-6 sm:p-8">

        <h1 class="text-3xl font-bold mb-8 text-gray-800">Affecter des Tâches</h1>

        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-2xl font-semibold mb-4 text-blue-600">Sprint Backlog</h2>
            <?php foreach ($fonctionnalites as $fonctionnalite): ?>
                <?php
                // Récupérer les taches par fonctionnalités 
                $stmt = $pdo->prepare("SELECT t.*, u.nom as nom_developpeur FROM taches t LEFT JOIN utilisateurs u ON t.id_developpeur = u.id WHERE t.id_fonctionnalite = ?");
                $stmt->execute([$fonctionnalite['id']]);
                $taches = $stmt->fetchAll(PDO::FETCH_ASSOC);
                ?>
                <h3 class="text-xl font-semibold p-4 bg-gray-50 border-b rounded-t-xl"><?php echo htmlspecialchars($fonctionnalite['fonctionnalite']); ?> - Tache</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 rounded-xl shadow-md mb-8">
                        <thead class="bg-gray-50">
                            <tr class="bg-gray-200">
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tâches</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Développeur</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($taches as $tache): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo htmlspecialchars($tache['nom_tache']); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><span class="text-gray-400">Aucune action</span></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <form action="" method="POST" class="flex justify-between items-center space-x-2">
                                            <input type="hidden" name="tache_id" value="<?php echo $tache['id']; ?>">
                                            <select name="utilisateur_id" class="block w-2/4 pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md" onchange="this.form.submit()">
                                                <option value="">Sélectionnez un développeur</option>
                                                <?php foreach ($utilisateurs as $utilisateur): ?>
                                                    <option value="<?php echo $utilisateur['id']; ?>" <?php echo ($tache['id_developpeur'] == $utilisateur['id']) ? 'selected' : ''; ?>>
                                                        <?php echo htmlspecialchars($utilisateur['nom']); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <p><?php echo $tache['nom_developpeur'] ? htmlspecialchars($tache['nom_developpeur']) : 'Aucun'; ?></p>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    
</body>
</html>