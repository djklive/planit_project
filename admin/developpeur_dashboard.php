<?php
session_start();
include '../includes/db.php'; // Connexion à la base de données

if ($_SESSION['role'] !== 'developpeur') {
    header('Location: login.php');
    exit();
}

// Récupérer les projets depuis la base de données
$stmt_projets = $pdo->query("SELECT * FROM projets");
$projets_Admin = $stmt_projets->fetchAll(PDO::FETCH_ASSOC);

$stmt_fonctionnalite = $pdo->query("SELECT * FROM product_backlog");
$fonctionnalites = $stmt_fonctionnalite->fetchAll(PDO::FETCH_ASSOC);


?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord Développeur</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="max-w-7xl mx-auto p-8">
        <h1 class="text-3xl font-bold mb-6 text-center text-gray-800">Membre Équipe Développement</h1>
        
        <div class="flex justify-center space-x-4">
            <a href="developpeur_dashboard.php" class="bg-blue-500 text-white px-4 py-2 rounded-lg shadow-lg hover:bg-blue-600 transition duration-200">Daily Sprint</a>

            <a href="consulter_planning.php" class="bg-blue-500 text-white px-4 py-2 rounded-lg shadow-lg hover:bg-blue-600 transition duration-200">Consulter le Planning</a>
            <a href="messaging.php" class="bg-blue-500 text-white px-4 py-2 rounded-lg shadow-lg hover:bg-blue-600 transition duration-200">Envoyer un Message</a>
            <a href="marquer_avancement.php" class="bg-blue-500 text-white px-4 py-2 rounded-lg shadow-lg hover:bg-blue-600 transition duration-200">Marquer l'Avancement</a>
        </div>

        <h2 class="text-2xl font-bold mb-4">Liste des Projets</h2>
        <table class="min-w-full bg-white border rounded-lg shadow-lg mb-8">
            <thead>
                <tr class="bg-gray-200">
                    <th class="py-3 px-4 border">Nom du Projet</th>
                    <th class="py-3 px-4 border">Description</th>
                    <th class="py-3 px-4 border">Budget</th>
                    <th class="py-3 px-4 border">Responsable</th>
                    <th class="py-3 px-4 border">Actions</th>
                </tr>
            </thead>
           
            <tbody>
                <?php foreach ($projets_Admin as $projet): ?>
                    <tr class="hover:bg-gray-100">
                      
                        <td class="py-2 px-4 border"><?php echo htmlspecialchars($projet ['nom_projet']); ?></td>
                        <td class="py-2 px-4 border"><?php echo htmlspecialchars($projet ['description']); ?></td>
                        <td class="py-2 px-4 border"><?php echo htmlspecialchars($projet ['budget']); ?></td>
                        <td class="py-2 px-4 border">
                            <?php
                            // Récupérer le nom du responsable
                            $stmt_responsable = $pdo->prepare("SELECT nom FROM utilisateurs WHERE id = ?");
                            $stmt_responsable->execute([$projet ['responsable_id']]);
                            $responsable = $stmt_responsable->fetch(PDO::FETCH_ASSOC);
                            echo htmlspecialchars($responsable['nom']);
                            ?>
                        </td>
                        <td class="py-2 px-4 border">
                            <p class='text-red-600'>Aucune</p>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <!-- liste des fonctionnalités -->
        <h2 class="text-2xl font-bold mb-4">Backlog</h2>
        <table class="min-w-full bg-white border rounded">
            <thead>
                <tr class="bg-gray-200">
                    <th class="py-2 px-4 border">Product Backlog</th>
                    <th class="py-2 px-4 border">Description</th>
                    <th class="py-2 px-4 border">Priorité</th>
                    <th class="py-2 px-4 border">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($fonctionnalites as $fonctionnalite): ?>
                    <tr>
                        <td class="py-2 px-4 border"><a href="product_backlog_fonctionnalite.php?id=<?php echo $fonctionnalite['id']; ?>" class="text-blue-400 font-bold hover:text-blue-600"><?php echo htmlspecialchars($fonctionnalite['fonctionnalite']); ?></a></td>
                        <td class="py-2 px-4 border"><?php echo htmlspecialchars($fonctionnalite['description']); ?></td>
                        <td class="py-2 px-4 border"><?php echo htmlspecialchars($fonctionnalite['priorite']); ?></td>
                        <td class="py-2 px-4 border">
                            <p class='text-red-600'>Aucune</p>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    </div>
</body>
</html>
