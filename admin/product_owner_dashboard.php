<?php
session_start();
include '../includes/db.php'; // Ce chemin doit être correct

// // Vérification de la connexion et du rôle de l'utilisateur
// if (!isset($_SESSION['utilisateur_id']) || $_SESSION['role'] !== 'product_owner') {
//     header('Location: login.php'); // Redirection vers la page de connexion si non autorisé
//     exit();
// }

// Récupérer les projets ou tâches liés au Product Owner connecté
$product_owner_id = $_SESSION['utilisateur_id'];
$stmt = $pdo->prepare("SELECT * FROM projets WHERE responsable_id = ?");
$stmt->execute([$product_owner_id]);
$projets = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les projets depuis la base de données
$stmt_projets = $pdo->query("SELECT * FROM projets");
$projets_Admin = $stmt_projets->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord Product Owner</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="max-w-7xl mx-auto p-8">
        <h1 class="text-3xl font-bold mb-6">Tableau de bord Product Owner</h1>

        <!-- Lien vers la création du Product Backlog -->
        <a href="product_backlog.php" class="mb-4 inline-block bg-blue-500 text-white p-2 rounded hover:bg-blue-600">Créer un Product Backlog</a>

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

           
