<?php
session_start();
include '../includes/db.php'; // Connexion à la base de données

// Vérification si l'utilisateur est connecté et s'il est admin
if (!isset($_SESSION['utilisateur_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Récupérer les projets depuis la base de données
$stmt_projets = $pdo->query("SELECT * FROM projets");
$projets = $stmt_projets->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les utilisateurs depuis la base de données
$stmt_utilisateurs = $pdo->query("SELECT * FROM utilisateurs");
$utilisateurs = $stmt_utilisateurs->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord Administrateur</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="max-w-7xl mx-auto p-8">
        <h1 class="text-3xl font-bold mb-6 text-center">Tableau de bord Administrateur</h1>

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
                <?php foreach ($projets as $projet): ?>
                    <tr class="hover:bg-gray-100">
                        <td class="py-2 px-4 border"><?php echo htmlspecialchars($projet['nom_projet']); ?></td>
                        <td class="py-2 px-4 border"><?php echo htmlspecialchars($projet['description']); ?></td>
                        <td class="py-2 px-4 border"><?php echo htmlspecialchars($projet['budget']); ?></td>
                        <td class="py-2 px-4 border">
                            <?php
                            // Récupérer le nom du responsable
                            $stmt_responsable = $pdo->prepare("SELECT nom FROM utilisateurs WHERE id = ?");
                            $stmt_responsable->execute([$projet['responsable_id']]);
                            $responsable = $stmt_responsable->fetch(PDO::FETCH_ASSOC);
                            echo htmlspecialchars($responsable['nom']);
                            ?>
                        </td>
                        <td class="py-2 px-4 border">
                            <a href="modifier_projet.php?id=<?php echo $projet['id']; ?>" class="text-blue-500 hover:underline">Modifier</a>
                            <a href="supprimer_projet.php?id=<?php echo $projet['id']; ?>" class="text-red-500 hover:underline">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="mt-6 text-center">
            <a href="ajouter_projet.php" class="bg-blue-500 text-white px-4 py-2 rounded-lg shadow-lg hover:bg-blue-600 transition duration-200">Ajouter un Projet</a>
        </div>

        <!-- Section pour la gestion des utilisateurs -->
        <h2 class="text-2xl font-bold mb-4 mt-8">Gestion des Utilisateurs</h2>
        <table class="min-w-full bg-white border rounded-lg shadow-lg mb-8">
            <thead>
                <tr class="bg-gray-200">
                    <th class="py-3 px-4 border">Nom</th>
                    <th class="py-3 px-4 border">Email</th>
                    <th class="py-3 px-4 border">Rôle</th>
                    <th class="py-3 px-4 border">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($utilisateurs as $utilisateur): ?>
                    <tr class="hover:bg-gray-100">
                        <td class="py-2 px-4 border"><?php echo htmlspecialchars($utilisateur['nom']); ?></td>
                        <td class="py-2 px-4 border"><?php echo htmlspecialchars($utilisateur['email']); ?></td>
                        <td class="py-2 px-4 border"><?php echo htmlspecialchars($utilisateur['role']); ?></td>
                        <td class="py-2 px-4 border">
                            <a href="modifier_utilisateur.php?id=<?php echo $utilisateur['id']; ?>" class="text-blue-500 hover:underline">Modifier</a>
                            <a href="supprimer_utilisateur.php?id=<?php echo $utilisateur['id']; ?>" class="text-red-500 hover:underline">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="mt-6 text-center">
            <a href="gestion_utilisateurs.php" class="bg-blue-500 text-white px-4 py-2 rounded-lg shadow-lg hover:bg-blue-600 transition duration-200">Ajouter un Utilisateur</a>
        </div>
    </div>
    
</body>
</html>
