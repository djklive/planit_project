<?php
session_start();
require ('../includes/db.php');

// Vérifier si l'utilisateur est connecté et s'il a le rôle d'administrateur
if (!isset($_SESSION['utilisateur_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Récupérer l'ID de l'utilisateur à modifier
$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE id = ?");
$stmt->execute([$id]);
$utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

// Vérifier si l'utilisateur existe
if (!$utilisateur) {
    header('Location: gerer_ressources.php'); // Rediriger si l'utilisateur n'est pas trouvé
    exit();
}

// Traitement du formulaire lors de la soumission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    // Mettre à jour l'utilisateur dans la base de données
    $stmt = $pdo->prepare("UPDATE utilisateurs SET nom = ?, email = ?, role = ? WHERE id = ?");
    $stmt->execute([$nom, $email, $role, $id]);

    header('Location: admin_dashboard.php'); // Rediriger après modification
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Utilisateur</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="max-w-7xl mx-auto p-8">
        <h1 class="text-3xl font-bold mb-6">Modifier Utilisateur</h1>
        <form action="" method="POST" class="bg-white p-6 rounded shadow-md">
            <div class="mb-4">
                <label for="nom" class="block text-gray-700">Nom</label>
                <input type="text" name="nom" id="nom" value="<?php echo htmlspecialchars($utilisateur['nom']); ?>" required class="border rounded w-full py-2 px-3" />
            </div>
            <div class="mb-4">
                <label for="email" class="block text-gray-700">Email</label>
                <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($utilisateur['email']); ?>" required class="border rounded w-full py-2 px-3" />
            </div>
            <div class="mb-4">
                <label for="role" class="block text-gray-700">Rôle</label>
                <select name="role" id="role" class="border rounded w-full py-2 px-3">
                    <option value="admin" <?php if ($utilisateur['role'] === 'admin') echo 'selected'; ?>>Administrateur</option>
                    <option value="ressource" <?php if ($utilisateur['role'] === 'ressource') echo 'selected'; ?>>Ressource</option>
                </select>
            </div>
            <button type="submit" class="bg-blue-500 text-white p-2 rounded hover:bg-blue-600">Modifier</button>
        </form>
    </div>
</body>
</html>
