<?php
session_start();
include '../includes/db.php'; // Chemin correct vers le fichier de base de données

// // Vérification de la connexion
// if (!isset($_SESSION['utilisateur_id']) || $_SESSION['role'] !== 'admin') {
//     header('Location: login.php');
//     exit();
// }

// Récupérer la liste des projets
$stmt = $pdo->query("SELECT p.*, u.nom AS responsable_nom FROM projets p JOIN utilisateurs u ON p.responsable_id = u.id");
$projets = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Projets</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-8">
        <h1 class="text-2xl font-bold mb-4">Liste des Projets</h1>
        <a href="ajouter_projet.php" class="bg-green-500 text-white p-2 rounded mb-4 inline-block">Ajouter un Projet</a>

        <table class="min-w-full bg-white">
            <thead>
                <tr>
                    <th class="py-2 px-4 border">Nom du Projet</th>
                    <th class="py-2 px-4 border">Description</th>
                    <th class="py-2 px-4 border">Budget</th>
                    <th class="py-2 px-4 border">Responsable</th>
                    <th class="py-2 px-4 border">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($projets as $projet): ?>
                    <tr>
                        <td class="py-2 px-4 border"><?php echo htmlspecialchars($projet['nom_projet']); ?></td>
                        <td class="py-2 px-4 border"><?php echo htmlspecialchars($projet['description']); ?></td>
                        <td class="py-2 px-4 border"><?php echo htmlspecialchars($projet['budget']); ?></td>
                        <td class="py-2 px-4 border"><?php echo htmlspecialchars($projet['responsable_nom']); ?></td>
                        <td class="py-2 px-4 border">
                            <a href="modifier_projet.php?id=<?php echo $projet['id']; ?>" class="text-blue-500">Modifier</a> | 
                            <a href="supprimer_projet.php?id=<?php echo $projet['id']; ?>" class="text-red-500">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
