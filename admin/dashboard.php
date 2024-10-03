<?php
session_start();
include '../includes/db.php'; // Chemin correct vers le fichier de base de données

// // Vérification de la connexion et du rôle de l'utilisateur
// if (!isset($_SESSION['utilisateur_id']) || $_SESSION['role'] !== 'admin') {
//     header('Location: login.php');
//     exit();
// }

// Récupérer les informations sur les Sprints
$stmt = $pdo->query("SELECT s.*, COUNT(sb.id) AS total_elements, 
                      SUM(CASE WHEN sb.statut = 'terminé' THEN 1 ELSE 0 END) AS elements_termines 
                      FROM sprints s 
                      LEFT JOIN sprint_backlog sb ON s.id = sb.sprint_id 
                      GROUP BY s.id");
$sprints = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les informations sur le Product Backlog
$stmt_pb = $pdo->query("SELECT * FROM product_backlog");
$product_backlog = $stmt_pb->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les utilisateurs
$stmt_users = $pdo->query("SELECT * FROM utilisateurs");
$utilisateurs = $stmt_users->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

</head>
<body class="bg-gray-100">
    <div class="max-w-7xl mx-auto p-8">
        <h1 class="text-3xl font-bold mb-6">Tableau de Bord des Projets</h1>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">


        <h2 class="text-2xl font-semibold mb-4">Sprints</h2>
        <table class="min-w-full bg-white border rounded mb-8">
            <thead>
                <tr class="bg-gray-200">
                    <th class="py-2 px-4 border">Nom du Sprint</th>
                    <th class="py-2 px-4 border">Date de Début</th>
                    <th class="py-2 px-4 border">Date de Fin</th>
                    <th class="py-2 px-4 border">Total Éléments</th>
                    <th class="py-2 px-4 border">Éléments Terminés</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sprints as $sprint): ?>
                    <tr>
                        <td class="py-2 px-4 border"><?php echo htmlspecialchars($sprint['nom']); ?></td>
                        <td class="py-2 px-4 border"><?php echo htmlspecialchars($sprint['date_debut']); ?></td>
                        <td class="py-2 px-4 border"><?php echo htmlspecialchars($sprint['date_fin']); ?></td>
                        <td class="py-2 px-4 border"><?php echo htmlspecialchars($sprint['total_elements']); ?></td>
                        <td class="py-2 px-4 border"><?php echo htmlspecialchars($sprint['elements_termines']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h2 class="text-2xl font-semibold mb-4">Product Backlog</h2>
        <table class="min-w-full bg-white border rounded mb-8">
            <thead>
                <tr class="bg-gray-200">
                    <th class="py-2 px-4 border">ID</th>
                    <th class="py-2 px-4 border">Fonctionnalité</th>
                    <th class="py-2 px-4 border">Description</th>
                    <th class="py-2 px-4 border">Priorité</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($product_backlog as $item): ?>
                    <tr>
                        <td class="py-2 px-4 border"><?php echo htmlspecialchars($item['id']); ?></td>
                        <td class="py-2 px-4 border"><?php echo htmlspecialchars($item['fonctionnalite']); ?></td>
                        <td class="py-2 px-4 border"><?php echo htmlspecialchars($item['description']); ?></td>
                        <td class="py-2 px-4 border"><?php echo htmlspecialchars($item['priorite']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h2 class="text-2xl font-semibold mb-4">Utilisateurs</h2>
        <table class="min-w-full bg-white border rounded">
            <thead>
                <tr class="bg-gray-200">
                    <th class="py-2 px-4 border">ID</th>
                    <th class="py-2 px-4 border">Nom</th>
                    <th class="py-2 px-4 border">Email</th>
                    <th class="py-2 px-4 border">Rôle</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($utilisateurs as $user): ?>
                    <tr>
                        <td class="py-2 px-4 border"><?php echo htmlspecialchars($user['id']); ?></td>
                        <td class="py-2 px-4 border"><?php echo htmlspecialchars($user['nom']); ?></td>
                        <td class="py-2 px-4 border"><?php echo htmlspecialchars($user['email']); ?></td>
                        <td class="py-2 px-4 border"><?php echo htmlspecialchars($user['role']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
