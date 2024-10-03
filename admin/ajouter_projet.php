<?php
session_start();
include '../includes/db.php'; // Chemin correct vers le fichier de base de données

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
    } elseif (!is_numeric($budget) || $budget <= 0) {
        $error_message = 'Le budget doit être un nombre positif.';
    } else {
        // Vérification si le responsable existe
        $stmt_responsable = $pdo->prepare("SELECT COUNT(*) FROM utilisateurs WHERE id = ?");
        $stmt_responsable->execute([$responsable_id]);
        if ($stmt_responsable->fetchColumn() == 0) {
            $error_message = 'Le responsable sélectionné n\'existe pas.';
        } else {
            // Insertion du projet dans la base de données
            $stmt = $pdo->prepare("INSERT INTO projets (nom_projet, description, budget, responsable_id) VALUES (?, ?, ?, ?)");
            if ($stmt->execute([$nom_projet, $description, $budget, $responsable_id])) {
                $success_message = 'Projet créé avec succès.';
            } else {
                $error_message = 'Erreur lors de l\'ajout du projet.';
            }
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
    <title>Ajouter un Projet</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded shadow-md w-96">
        <h1 class="text-2xl font-bold mb-4">Ajouter un Projet</h1>

        <!-- Affichage des messages d'erreur et de succès -->
        <?php if ($error_message): ?>
            <div class="bg-red-200 text-red-800 p-2 rounded mb-4">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($success_message)): ?>
            <div class="bg-green-200 text-green-800 p-2 rounded mb-4">
                <?php echo htmlspecialchars($success_message); ?>
            </div>
            <script>
                setTimeout(() => {
                    window.location.href = 'admin_dashboard.php';
                }, 2000); // Redirection après 2 secondes
            </script>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="mb-4">
                <label for="nom_projet" class="block text-sm font-bold mb-2">Nom du Projet :</label>
                <input type="text" id="nom_projet" name="nom_projet" class="border rounded w-full p-2" required>
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-bold mb-2">Description :</label>
                <textarea id="description" name="description" class="border rounded w-full p-2" required></textarea>
            </div>
            <div class="mb-4">
                <label for="budget" class="block text-sm font-bold mb-2">Budget :</label>
                <input type="number" id="budget" name="budget" class="border rounded w-full p-2" min="0" required>
            </div>
            <div class="mb-4">
                <label for="responsable_id" class="block text-sm font-bold mb-2">Responsable :</label>
                <select id="responsable_id" name="responsable_id" class="border rounded w-full p-2" required>
                    <option value="">Choisir un responsable</option>
                    <?php foreach ($responsables as $responsable): ?>
                        <option value="<?php echo $responsable['id']; ?>"><?php echo htmlspecialchars($responsable['nom']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="bg-blue-500 text-white p-2 rounded hover:bg-blue-600 w-full">Ajouter</button>
        </form>
    </div>
</body>
</html>
