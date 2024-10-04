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

// Récupérer les taches existantes
$stmt = $pdo->prepare("SELECT * FROM taches WHERE id_fonctionnalite = ?");
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


?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Taches Fonctionnalité</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <div class="max-w-7xl mx-auto p-8">
        <h1 class="text-3xl font-bold mb-6">Gestion du Sprint Backlog</h1>
        <?php if ($error_message): ?>
            <div class="bg-red-200 text-red-800 p-2 rounded mb-4">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>
        <form action="" method="POST" class="mb-4">
            <div class="mb-4">
                <label for="tache" class="block text-sm font-bold mb-2">Tache :</label>
                <input type="text" id="tache" name="tache" class="border rounded w-full p-2"  required>
            </div>
            
            <button type="submit" class="bg-blue-500 text-white p-2 rounded hover:bg-blue-600 w-full">Ajouter</button>
        </form>

        <h2 class="text-2xl font-bold mb-4">Taches Existantes</h2>
        <table class="min-w-full bg-white border rounded">
            <thead>
                <tr class="bg-gray-200">
                    <th class="py-2 px-4 border" colspan="2"><?php echo htmlspecialchars($projet['fonctionnalite']); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($taches as $tache): ?>
                    <tr>
                        <td class="py-2 px-4 border"><a href="#"><?php echo htmlspecialchars($tache['nom_tache']); ?></a></td>
                        <td class="py-2 px-4 border flex justify-end gap-4">
                            <a href="modifier_tache.php?id=<?php echo $tache['id']; ?>" class="text-blue-500">Modifier</a>
                            <a href="supprimer_tache.php?id=<?php echo $tache['id']; ?>" class="text-red-500">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>


    </div>
    
</body>
</html>