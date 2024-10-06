<?php
session_start();
include '../includes/db.php'; // Chemin correct vers le fichier de base de données

// // Vérification de la connexion et du rôle de l'utilisateur
// if (!isset($_SESSION['utilisateur_id']) || $_SESSION['role'] !== 'admin') {
//     header('Location: login.php');
//     exit();
// }

// Récupérer le projet ou l'on va ajouter la tache 
if (!isset($_GET['id'])) {
    header('Location: product_backlog.php'); // Rediriger si l'ID n'est pas fourni
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM product_backlog WHERE id = ?");
$stmt->execute([$_GET['id']]);
$projet = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$projet) {
    header('Location: product_backlog.php'); // Rediriger si le projet n'existe pas
    exit();
}

// Récupérer les taches existantes avec les noms des développeurs
$stmt = $pdo->prepare("
    SELECT t.*, u.nom as nom_developpeur 
    FROM taches t 
    LEFT JOIN utilisateurs u ON t.id_developpeur = u.id 
    WHERE t.id_fonctionnalite = ?
");
$stmt->execute([$_GET['id']]);
$taches = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Initialiser la variable d'erreur
$error_message = '';

// Ajouter une tache
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tache = trim($_POST['tache']);
    

    // Validation des champs
    if (empty($tache)) {
        $error_message = 'Veuillez remplir tous les champs.';
    } else {
        // Préparer la requête pour ajouter une fonctionnalité
        $stmt = $pdo->prepare("INSERT INTO taches (id_fonctionnalite, nom_tache) VALUES (?, ?)");
        $stmt->execute([$_GET['id'], $tache]);
        header('Location: product_backlog_fonctionnalite.php'); // Redirection pour éviter la soumission multiple
        exit();
    }
}

// Handle form submission for status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['Statut']) && isset($_POST['tache_id'])) {
    $statut = trim($_POST['Statut']);
    $tache_id = $_POST['tache_id'];

    $update_avancement = $pdo->prepare("UPDATE taches SET statut = ? WHERE id = ?");
    $update_avancement->execute([$statut, $tache_id]);
    
    // Redirect to prevent form resubmission
    header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $_GET['id']);
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
    <title>Taches Fonctionnalité</title>
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
        <h1 class="text-4xl font-bold mb-8 text-gray-800">Sprint Backlog</h1>
        
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <?php if ($_SESSION['role'] == 'scrum_master'): ?>
                <h2 class="text-2xl font-semibold mb-6 text-blue-600">Scrum Master Dashboard</h2>
                <?php if ($error_message): ?>
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                        <p><?php echo htmlspecialchars($error_message); ?></p>
                    </div>
                <?php endif; ?>
                <form action="" method="POST" class="mb-6">
                    <div class="mb-4">
                        <label for="tache" class="block text-sm font-medium text-gray-700 mb-2">Nouvelle tâche du Sprint Backlog :</label>
                        <input type="text" id="tache" name="tache" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" required>
                    </div>
                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Ajouter la tâche
                    </button>
                </form>
            <?php elseif ($_SESSION['role'] == 'developpeur'): ?>
                <h2 class="text-2xl font-semibold mb-6 text-green-600">Membre Équipe Développement</h2>
            <?php endif; ?>
        </div>

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <h2 class="text-xl font-semibold p-4 bg-gray-50 border-b"><?php echo htmlspecialchars($projet['fonctionnalite']); ?> - Sprint Backlog</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tâche</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Développeur</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Avancement</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($taches as $tache): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    <?php echo htmlspecialchars($tache['nom_tache']); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php if ($_SESSION['role'] == 'scrum_master'): ?>
                                        <a href="modifier_tache.php?id=<?php echo $tache['id']; ?>" class="text-blue-600 hover:text-blue-900 mr-2">Modifier</a>
                                        <a href="supprimer_tache.php?id=<?php echo $tache['id']; ?>" class="text-red-600 hover:text-red-900">Supprimer</a>
                                    <?php else: ?>
                                        <span class="text-gray-400">Aucune action</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php echo $tache['nom_developpeur'] ? htmlspecialchars($tache['nom_developpeur']) : '<span class="text-yellow-600">Non assigné</span>'; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php if ($_SESSION['role'] == 'product_owner' || $_SESSION['role'] == 'scrum_master'): ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $tache['statut'] == 'terminé' ? 'bg-green-100 text-green-800' : ($tache['statut'] == 'en cours' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800'); ?>">
                                            <?php echo htmlspecialchars($tache['statut'] ?? 'Non défini'); ?>
                                        </span>
                                    <?php else: ?>
                                        <form action="" method='POST' class='flex items-center space-x-2'>
                                            <select id="statut_<?php echo $tache['id']; ?>" name="Statut" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md" required>
                                                <option value="à faire" <?php echo $tache['statut'] == 'à faire' ? 'selected' : ''; ?>>À faire</option>
                                                <option value="en cours" <?php echo $tache['statut'] == 'en cours' ? 'selected' : ''; ?>>En cours</option>
                                                <option value="terminé" <?php echo $tache['statut'] == 'terminé' ? 'selected' : ''; ?>>Terminé</option>
                                            </select>
                                            <input type="hidden" name="tache_id" value="<?php echo $tache['id']; ?>">
                                            <button type="submit" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                Mettre à jour
                                            </button>
                                        </form>
                                    <?php endif; ?>
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