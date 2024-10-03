<?php
session_start();
include '../includes/db.php';

// Vérifiez si l'utilisateur est connecté et a les droits nécessaires
if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: login.php');
    exit();
}

// Récupérer tous les projets
$stmt = $pdo->query("SELECT * FROM projects");
$projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Archivage d'un projet
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['archive_project_id'])) {
    $project_id = intval($_POST['archive_project_id']);
    
    // Récupérer les détails du projet à archiver
    $stmt = $pdo->prepare("SELECT * FROM projects WHERE id = ?");
    $stmt->execute([$project_id]);
    $project = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($project) {
        // Archiver le projet dans la table archived_projects
        $stmt = $pdo->prepare("INSERT INTO archived_projects (original_project_id, nom, description, date_fin) VALUES (?, ?, ?, ?)");
        $stmt->execute([$project['id'], $project['nom'], $project['description'], $project['date_fin']]);
        
        // Supprimer le projet de la table projects
        $stmt = $pdo->prepare("DELETE FROM projects WHERE id = ?");
        $stmt->execute([$project_id]);
        
        // Redirection avec message de succès
        header('Location: archive_projects.php?success=1');
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Archiver des Projets</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-5">
    <div class="container mx-auto">
        <h1 class="text-2xl font-bold mb-4">Projets à Archiver</h1>

        <?php if (isset($_GET['success'])): ?>
            <div class="bg-green-200 text-green-800 p-2 rounded mb-4">
                Projet archivé avec succès !
            </div>
        <?php endif; ?>

        <table class="min-w-full bg-white border border-gray-300">
            <thead>
                <tr>
                    <th class="border px-4 py-2">ID</th>
                    <th class="border px-4 py-2">Nom</th>
                    <th class="border px-4 py-2">Description</th>
                    <th class="border px-4 py-2">Date de Fin</th>
                    <th class="border px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($projects as $project): ?>
                    <tr>
                        <td class="border px-4 py-2"><?= htmlspecialchars($project['id']) ?></td>
                        <td class="border px-4 py-2"><?= htmlspecialchars($project['nom']) ?></td>
                        <td class="border px-4 py-2"><?= htmlspecialchars($project['description']) ?></td>
                        <td class="border px-4 py-2"><?= htmlspecialchars($project['date_fin']) ?></td>
                        <td class="border px-4 py-2">
                            <form action="" method="POST" class="inline">
                                <input type="hidden" name="archive_project_id" value="<?= $project['id'] ?>">
                                <button type="submit" class="bg-red-500 text-white p-1 rounded hover:bg-red-600">Archiver</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
