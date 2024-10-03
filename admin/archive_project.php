<?php
session_start();
include '../includes/db.php'; // Chemin vers le fichier de base de données

if (isset($_GET['project_id'])) {
    $project_id = $_GET['project_id'];

    // Récupérer les informations du projet à archiver
    $stmt = $pdo->prepare("SELECT * FROM projects WHERE id = ?");
    $stmt->execute([$project_id]);
    $project = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($project) {
        // Insérer les données dans la table d'archivage
        $stmt = $pdo->prepare("INSERT INTO archived_projects (original_project_id, nom, description, date_fin) VALUES (?, ?, ?, ?)");
        $stmt->execute([
            $project['id'],
            $project['nom'],
            $project['description'],
            $project['date_fin']
        ]);

        // Supprimer le projet de la table originale
        $stmt = $pdo->prepare("DELETE FROM projects WHERE id = ?");
        $stmt->execute([$project_id]);

        $_SESSION['success_message'] = 'Le projet a été archivé avec succès.';
    } else {
        $_SESSION['error_message'] = 'Le projet n\'existe pas.';
    }
}
header('Location: projects.php'); // Redirection vers la page des projets
exit();
?>
