<?php
session_start();
include '../includes/db.php'; // Chemin correct vers le fichier de base de données

// Vérification de la connexion
// if (!isset($_SESSION['utilisateur_id']) || $_SESSION['role'] !== 'admin') {
//     header('Location: login.php');
//     exit();
// }

// Récupérer le projet à modifier
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

// Initialiser la variable d'erreur
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fonctionnalite = trim($_POST['fonctionnalite']);
    $description = trim($_POST['description']);
    $priorite = trim($_POST['priorite']);
   

    // Validation des champs
    if (empty($fonctionnalite) || empty($description) || empty($priorite)) {
        $error_message = 'Veuillez remplir tous les champs.';
    } else {
        // Mettre à jour le projet dans la base de données
        $stmt = $pdo->prepare("UPDATE product_backlog SET fonctionnalite = ?, description = ?, priorite = ? WHERE id = ?");
        if ($stmt->execute([$fonctionnalite, $description, $priorite, $_GET['id']])) {
            if ($_SESSION['role'] == 'product_owner') {
                header('Location: product_backlog.php'); // Rediriger vers le tableau de bord après modification
            } else if ($_SESSION['role'] == 'scrum_master'){
                header('Location: scrum_master_dashboard.php'); // Rediriger vers le tableau de bord après modification
            }
            exit();
        } else {
            $error_message = 'Erreur lors de la mise à jour du projet.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Fonctionnalité</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="max-w-3xl mx-auto p-6 sm:p-8">
        <h1 class="text-3xl font-bold mb-8 text-gray-800">Modifier la Fonctionnalité</h1>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <?php if ($error_message): ?>
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                    <p><?php echo htmlspecialchars($error_message); ?></p>
                </div>
            <?php endif; ?>
            
            <form action="" method="POST" class="space-y-6">
                <div>
                    <label for="fonctionnalite" class="block text-sm font-medium text-gray-700 mb-2">Fonctionnalité :</label>
                    <input type="text" id="fonctionnalite" name="fonctionnalite" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" value="<?php echo htmlspecialchars($projet['fonctionnalite']); ?>" required>
                </div>
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description :</label>
                    <textarea id="description" name="description" rows="3" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" required><?php echo htmlspecialchars($projet['description']); ?></textarea>
                </div>
                <div>
                    <label for="priorite" class="block text-sm font-medium text-gray-700 mb-2">Priorité :</label>
                    <select id="priorite" name="priorite" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md" required>
                        <option value="haute" <?php echo $projet['priorite'] == 'haute' ? 'selected' : ''; ?>>Haute</option>
                        <option value="moyenne" <?php echo $projet['priorite'] == 'moyenne' ? 'selected' : ''; ?>>Moyenne</option>
                        <option value="basse" <?php echo $projet['priorite'] == 'basse' ? 'selected' : ''; ?>>Basse</option>
                    </select>
                </div>
                <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Modifier
                </button>
            </form>
        </div>
    </div>
</body>
</html>
