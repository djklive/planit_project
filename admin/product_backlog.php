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
        header('Location: product_backlog.php'); // Redirection pour éviter la soumission multiple
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion du Product Backlog</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="max-w-7xl mx-auto p-8">
        <h1 class="text-3xl font-bold mb-6">Gestion du Product Backlog</h1>

        <form action="" method="POST" class="mb-4">
            <div class="mb-4">
                <label for="fonctionnalite" class="block text-sm font-bold mb-2">Fonctionnalité :</label>
                <input type="text" id="fonctionnalite" name="fonctionnalite" class="border rounded w-full p-2" required>
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-bold mb-2">Description :</label>
                <textarea id="description" name="description" class="border rounded w-full p-2" required></textarea>
            </div>
            <div class="mb-4">
                <label for="priorite" class="block text-sm font-bold mb-2">Priorité :</label>
                <select id="priorite" name="priorite" class="border rounded w-full p-2" required>
                    <option value="haute">Haute</option>
                    <option value="moyenne">Moyenne</option>
                    <option value="basse">Basse</option>
                </select>
            </div>
            <button type="submit" class="bg-blue-500 text-white p-2 rounded hover:bg-blue-600 w-full">Ajouter</button>
        </form>

        <h2 class="text-2xl font-bold mb-4">Fonctionnalités Existantes</h2>
        <table class="min-w-full bg-white border rounded">
            <thead>
                <tr class="bg-gray-200">
                    <th class="py-2 px-4 border">Fonctionnalité</th>
                    <th class="py-2 px-4 border">Description</th>
                    <th class="py-2 px-4 border">Priorité</th>
                    <th class="py-2 px-4 border">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($fonctionnalites as $fonctionnalite): ?>
                    <tr>
                        <td class="py-2 px-4 border"><?php echo htmlspecialchars($fonctionnalite['fonctionnalite']); ?></td>
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
</body>
</html>
