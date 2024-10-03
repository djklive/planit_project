<?php
session_start();
include '../includes/db.php'; // Chemin vers le fichier de base de données

// Récupérer les projets archivés
$stmt = $pdo->query("SELECT * FROM archived_projects");
$archived_projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projets Archivés</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Projets Archivés</h1>

        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="bg-green-200 text-green-800 p-2 rounded mb-4">
                <?= htmlspecialchars($_SESSION['success_message']); unset($_SESSION['success_message']); ?>
            </div>
        <?php endif; ?>

        <?php if (empty($archived_projects)): ?>
            <p>Aucun projet archivé.</p>
        <?php else: ?>
            <table class="min-w-full bg-white border border-gray-300">
                <thead>
                    <tr>
                        <th class="border px-4 py-2">Nom</th>
                        <th class="border px-4 py-2">Description</th>
                        <th class="border px-4 py-2">Date de Fin</th>
                        <th class="border px-4 py-2">Date d'Archivage</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($archived_projects as $project): ?>
                        <tr>
                            <td class="border px-4 py-2"><?= htmlspecialchars($project['nom']); ?></td>
                            <td class="border px-4 py-2"><?= htmlspecialchars($project['description']); ?></td>
                            <td class="border px-4 py-2"><?= htmlspecialchars($project['date_fin']); ?></td>
                            <td class="border px-4 py-2"><?= htmlspecialchars($project['archived_at']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>
