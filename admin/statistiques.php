<?php
session_start();
include 'includes/db.php';

// Vérifier si l'utilisateur est connecté et s'il a le rôle d'administrateur
if (!isset($_SESSION['utilisateur_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Récupérer les statistiques des projets avec le nom du responsable
$stmt = $pdo->query("
    SELECT p.nom_projet, p.budget, p.date_debut, p.date_fin, u.nom AS responsable_nom 
    FROM projets p 
    LEFT JOIN utilisateurs u ON p.responsable_id = u.id
");
$projets = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calculer le budget total et le nombre de projets
$totalBudget = 0;
$nombreProjets = count($projets);
foreach ($projets as $projet) {
    $totalBudget += $projet['budget'];
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques des Projets</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="max-w-7xl mx-auto p-8">
        <h1 class="text-3xl font-bold mb-6">Statistiques des Projets</h1>
        <h2 class="text-2xl font-bold mb-4">Résumé</h2>
        <p class="mb-4">Nombre total de projets : <strong><?php echo $nombreProjets; ?></strong></p>
        <p class="mb-4">Budget total alloué : <strong><?php echo $totalBudget; ?> €</strong></p>

        <h2 class="text-2xl font-bold mb-4">Détails des Projets</h2>
        <table class="min-w-full bg-white border rounded">
            <thead>
                <tr class="bg-gray-200">
                    <th class="py-2 px-4 border">Nom du Projet</th>
                    <th class="py-2 px-4 border">Budget</th>
                    <th class="py-2 px-4 border">Date de Début</th>
                    <th class="py-2 px-4 border">Date de Fin</th>
                    <th class="py-2 px-4 border">Responsable</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($projets as $projet): ?>
                    <tr>
                        <td class="py-2 px-4 border"><?php echo htmlspecialchars($projet['nom_projet']); ?></td>
                        <td class="py-2 px-4 border"><?php echo htmlspecialchars($projet['budget']); ?> €</td>
                        <td class="py-2 px-4 border"><?php echo $projet['date_debut']; ?></td>
                        <td class="py-2 px-4 border"><?php echo $projet['date_fin']; ?></td>
                        <td class="py-2 px-4 border"><?php echo htmlspecialchars($projet['responsable_nom']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
