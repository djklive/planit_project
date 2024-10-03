<?php
session_start();
include '../includes/db.php'; // Chemin vers le fichier de base de données

// // Vérification de la connexion et du rôle de l'utilisateur
// if (!isset($_SESSION['utilisateur_id']) || $_SESSION['role'] !== 'admin') {
//     header('Location: login.php');
//     exit();
// }

// Récupérer tous les utilisateurs
$stmt = $pdo->query("SELECT * FROM utilisateurs");
$utilisateurs = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Traitement de la soumission du formulaire pour ajouter un nouvel utilisateur
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajouter_utilisateur'])) {
    $nom = trim($_POST['nom']);
    $email = trim($_POST['email']);
    $mot_de_passe = password_hash(trim($_POST['mot_de_passe']), PASSWORD_BCRYPT);
    $role = trim($_POST['role']);

    // Ajouter l'utilisateur à la base de données
    $stmt = $pdo->prepare("INSERT INTO utilisateurs (nom, email, mot_de_passe, role) VALUES (?, ?, ?, ?)");
    $stmt->execute([$nom, $email, $mot_de_passe, $role]);
    header('Location: gestion_utilisateurs.php'); // Redirection vers la même page pour éviter la soumission multiple
    exit();
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Utilisateurs</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="max-w-7xl mx-auto p-8">
        <h1 class="text-3xl font-bold mb-6">Gestion des Utilisateurs</h1>

        <form action="" method="POST" class="mb-8 bg-white p-4 rounded shadow">
            <h2 class="text-2xl font-bold mb-4">Ajouter un Utilisateur</h2>
            <div class="mb-4">
                <label for="nom" class="block text-sm font-bold mb-2">Nom :</label>
                <input type="text" id="nom" name="nom" class="border rounded w-full p-2" required>
            </div>
            <div class="mb-4">
                <label for="email" class="block text-sm font-bold mb-2">Email :</label>
                <input type="email" id="email" name="email" class="border rounded w-full p-2" required>
            </div>
            <div class="mb-4">
                <label for="mot_de_passe" class="block text-sm font-bold mb-2">Mot de passe :</label>
                <input type="password" id="mot_de_passe" name="mot_de_passe" class="border rounded w-full p-2" required>
            </div>
            <div class="mb-4">
                <label for="role" class="block text-sm font-bold mb-2">Rôle :</label>
                <select id="role" name="role" class="border rounded w-full p-2" required>
                    <option value="product_owner">Product Owner</option>
                    <option value="scrum_master">Scrum Master</option>
                    <option value="developpeur">Développeur</option>
                </select>
            </div>
            <button type="submit" name="ajouter_utilisateur" class="bg-blue-500 text-white p-2 rounded hover:bg-blue-600 w-full">Ajouter Utilisateur</button>
        </form>

        <h2 class="text-2xl font-bold mb-4">Liste des Utilisateurs</h2>
        <table class="min-w-full bg-white border rounded">
            <thead>
                <tr class="bg-gray-200">
                    <th class="py-2 px-4 border">Nom</th>
                    <th class="py-2 px-4 border">Email</th>
                    <th class="py-2 px-4 border">Rôle</th>
                    <th class="py-2 px-4 border">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($utilisateurs as $utilisateur): ?>
                    <tr>
                        <td class="py-2 px-4 border"><?php echo htmlspecialchars($utilisateur['nom']); ?></td>
                        <td class="py-2 px-4 border"><?php echo htmlspecialchars($utilisateur['email']); ?></td>
                        <td class="py-2 px-4 border"><?php echo htmlspecialchars($utilisateur['role']); ?></td>
                        <td class="py-2 px-4 border">
                            <a href="modifier_utilisateur.php?id=<?php echo $utilisateur['id']; ?>" class="text-blue-500">Modifier</a>
                            <a href="supprimer_utilisateur.php?id=<?php echo $utilisateur['id']; ?>" class="text-red-500">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
