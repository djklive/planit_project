<?php
session_start();
include '../includes/db.php'; // Chemin correct vers le fichier de base de données

// Vérification de la connexion
if (!isset($_SESSION['utilisateur_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Récupérer le projet à modifier
if (!isset($_GET['id'])) {
    header('Location: admin_dashboard.php'); // Rediriger si l'ID n'est pas fourni
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM projets WHERE id = ?");
$stmt->execute([$_GET['id']]);
$projet = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$projet) {
    header('Location: admin_dashboard.php'); // Rediriger si le projet n'existe pas
    exit();
}

// Initialiser la variable d'erreur
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom_projet = trim($_POST['nom_projet']);
    $description = trim($_POST['description']);
    $budget = trim($_POST['budget']);
    $responsable_id = trim($_POST['responsable_id']);

    // Validation des champs
    if (empty($nom_projet) || empty($description) || empty($budget) || empty($responsable_id)) {
        $error_message = 'Veuillez remplir tous les champs.';
    } else {
        // Mettre à jour le projet dans la base de données
        $stmt = $pdo->prepare("UPDATE projets SET nom_projet = ?, description = ?, budget = ?, responsable_id = ? WHERE id = ?");
        if ($stmt->execute([$nom_projet, $description, $budget, $responsable_id, $_GET['id']])) {
            header('Location: admin_dashboard.php'); // Rediriger vers le tableau de bord après modification
            exit();
        } else {
            $error_message = 'Erreur lors de la mise à jour du projet.';
        }
    }
}

// Récupérer les utilisateurs pour le champ responsable
$stmt_responsables = $pdo->query("SELECT id, nom FROM utilisateurs");
$responsables = $stmt_responsables->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un Projet</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded shadow-md w-96">
        <h1 class="text-2xl font-bold mb-4">Modifier le Projet</h1>

        <?php if ($error_message): ?>
            <div class="bg-red-200 text-red-800 p-2 rounded mb-4">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="mb-4">
                <label for="nom_projet" class="block text-sm font-bold mb-2">Nom du Projet :</label>
                <input type="text" id="nom_projet" name="nom_projet" class="border rounded w-full p-2" value="<?php echo htmlspecialchars($projet['nom_projet']); ?>" required>
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-bold mb-2">Description :</label>
                <textarea id="description" name="description" class="border rounded w-full p-2" required><?php echo htmlspecialchars($projet['description']); ?></textarea>
            </div>
            <div class="mb-4">
                <label for="budget" class="block text-sm font-bold mb-2">Budget :</label>
                <input type="number" id="budget" name="budget" class="border rounded w-full p-2" value="<?php echo htmlspecialchars($projet['budget']); ?>" required>
            </div>
            <div class="mb-4">
                <label for="responsable_id" class="block text-sm font-bold mb-2">Responsable :</label>
                <select id="responsable_id" name="responsable_id" class="border rounded w-full p-2" required>
                    <option value="">Choisir un responsable</option>
                    <?php foreach ($responsables as $responsable): ?>
                        <option value="<?php echo $responsable['id']; ?>" <?php echo ($responsable['id'] == $projet['responsable_id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($responsable['nom']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="bg-blue-500 text-white p-2 rounded hover:bg-blue-600 w-full">Modifier</button>
        </form>
    </div>
</body>
</html>
