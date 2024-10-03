<?php
session_start();
include '../includes/db.php';

// Vérifier si l'utilisateur est connecté et a les droits nécessaires
if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: login.php');
    exit();
}

// Récupérer tous les projets archivés
$stmt = $pdo->query("SELECT * FROM archived_projects");
$archived_projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Restauration d'un projet archivé
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['restore_project_id'])) {
    $archived_project_id = intval($_POST['restore_project_id']);
    
    // Récupérer les détails du projet archivé à restaurer
    $stmt = $pdo->prepare("SELECT * FROM archived_projects WHERE id = ?");
    $stmt->execute([$archived_project_id]);
    $archived_project = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($archived_project) {
        // Restaurer le projet dans la table projects
        $stmt = $pdo->prepare("INSERT INTO projects (nom, description, date_fin) VALUES (?, ?, ?)");
        $stmt->execute([$archived_project['nom'], $archived_project['description'], $archived_project['date_fin']]);
        
        // Supprimer le projet de la table archived_projects
        $stmt = $pdo->prepare("DELETE FROM archived_projects WHERE id = ?");
        $stmt->execute([$archived_project_id]);
        
        // Redirection avec message de succès
        header('Location: view_archived_projects.php?success=1');
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Projets Archivés</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 p-6">
    <div class="container mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Projets Archivés</h1>

        <p class="mb-4 text-gray-700">Cette section permet de consulter les projets archivés et de restaurer un projet si nécessaire. Une fois restauré, le projet sera réintégré dans la gestion des projets actifs.</p>

        <?php if (isset($_GET['success'])): ?>
            <div class="bg-green-200 text-green-800 p-2 rounded mb-4">
                Le projet a été restauré avec succès.
            </div>
        <?php endif; ?>

        <?php if (count($archived_projects) === 0): ?>
            <div class="bg-yellow-100 text-yellow-700 p-2 rounded">
                Aucun projet archivé n'est disponible pour le moment.
            </div>
        <?php else: ?>
            <table class="min-w-full bg-white border border-gray-300 rounded">
                <thead>
                    <tr>
                        <th class="border px-4 py-2">ID</th>
                        <th class="border px-4 py-2">Nom du projet</th>
                        <th class="border px-4 py-2">Description</th>
                        <th class="border px-4 py-2">Date de fin</th>
                        <th class="border px-4 py-2">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($archived_projects as $project): ?>
                        <tr>
                            <td class="border px-4 py-2"><?= htmlspecialchars($project['id']) ?></td>
                            <td class="border px-4 py-2"><?= htmlspecialchars($project['nom']) ?></td>
                            <td class="border px-4 py-2"><?= htmlspecialchars($project['description']) ?></td>
                            <td class="border px-4 py-2"><?= htmlspecialchars($project['date_fin']) ?></td>
                            <td class="border px-4 py-2">
                                <form action="" method="POST">
                                    <input type="hidden" name="restore_project_id" value="<?= $project['id'] ?>">
                                    <button type="submit" class="bg-blue-500 text-white py-1 px-3 rounded hover:bg-blue-600">Restaurer</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>
