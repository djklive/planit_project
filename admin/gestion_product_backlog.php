<?php
session_start();
include '../includes/db.php'; // Chemin vers le fichier de base de données

// // Vérification de la connexion et du rôle de l'utilisateur
// if (!isset($_SESSION['utilisateur_id']) || $_SESSION['role'] !== 'admin') {
//     header('Location: login.php');
//     exit();
// }

// Récupérer les éléments du product backlog
$stmt = $pdo->query("SELECT * FROM product_backlog");
$product_backlog = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Traitement de la soumission du formulaire pour ajouter un nouvel élément au Product Backlog
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajouter_backlog'])) {
    $titre = trim($_POST['titre']);
    $description = trim($_POST['description']);
    $priorite = trim($_POST['priorite']);

    // Ajouter l'élément au product backlog
    $stmt = $pdo->prepare("INSERT INTO product_backlog (titre, description, priorite) VALUES (?, ?, ?)");
    $stmt->execute([$titre, $description, $priorite]);
    header('Location: gestion_product_backlog.php'); // Redirection vers la même page pour éviter la soumission multiple
    exit();
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

        <form action="" method="POST" class="mb-8 bg-white p-4 rounded shadow">
            <h2 class="text-2xl font-bold mb-4">Ajouter un Élement au Product Backlog</h2>
            <div class="mb-4">
                <label for="titre" class="block text-sm font-bold mb-2">Titre :</label>
                <input type="text" id="titre" name="titre" class="border rounded w-full p-2" required>
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
            <button type="submit" name="ajouter_backlog" class="bg-blue-500 text-white p-2 rounded hover:bg-blue-600 w-full">Ajouter Élement</button>
        </form>

        <h2 class="text-2xl font-bold mb-4">Liste du Product Backlog</h2>
        <table class="min-w-full bg-white border rounded">
            <thead>
                <tr class="bg-gray-200">
                    <th class="py-2 px-4 border">Titre</th>
                    <th class="py-2 px-4 border">Description</th>
                    <th class="py-2 px-4 border">Priorité</th>
                    <th class="py-2 px-4 border">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($product_backlog as $item): ?>
                    <tr>
                        <td class="py-2 px-4 border"><?php echo htmlspecialchars($item['titre']); ?></td>
                        <td class="py-2 px-4 border"><?php echo htmlspecialchars($item['description']); ?></td>
                        <td class="py-2 px-4 border"><?php echo htmlspecialchars($item['priorite']); ?></td>
                        <td class="py-2 px-4 border">
                            <a href="modifier_backlog.php?id=<?php echo $item['id']; ?>" class="text-blue-500">Modifier</a>
                            <a href="supprimer_backlog.php?id=<?php echo $item['id']; ?>" class="text-red-500">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
