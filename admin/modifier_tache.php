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
    header('Location: product_backlog_fonctionnalite.php'); // Rediriger si l'ID n'est pas fourni
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM taches WHERE id = ?");
$stmt->execute([$_GET['id']]);
$projet = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$projet) {
    header('Location: product_backlog_fonctionnalite.php'); // Rediriger si le projet n'existe pas
    exit();
}

// Initialiser la variable d'erreur
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tache = trim($_POST['tache']);
   

    // Validation des champs
    if (empty($tache)) {
        $error_message = 'Veuillez remplir tous les champs.';
    } else {
        // Mettre à jour le projet dans la base de données
        $stmt = $pdo->prepare("UPDATE taches SET nom_tache = ? WHERE id = ?");
        if ($stmt->execute([$tache, $_GET['id']])) {
            header('Location: product_backlog_fonctionnalite.php'); // Rediriger vers le tableau de bord après modification
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
    <title>Modifier Taches</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <div class="max-w-7xl mx-auto p-8">
        <h1 class="text-3xl font-bold mb-6">Gestion du Product Backlog</h1>
        <?php if ($error_message): ?>
            <div class="bg-red-200 text-red-800 p-2 rounded mb-4">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>
        <form action="" method="POST" class="mb-4">
            <div class="mb-4">
                <label for="tache" class="block text-sm font-bold mb-2">Tache :</label>
                <input type="text" id="tache" name="tache" class="border rounded w-full p-2" value="<?php echo htmlspecialchars($projet['nom_tache']); ?>"  required>
            </div>
            
            <button type="submit" class="bg-red-500 text-white p-2 rounded hover:bg-blue-600 w-full">Modifier</button>
        </form>


    </div>
    
</body>
</html>