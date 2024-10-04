<?php
session_start();
include '../includes/db.php'; // Chemin correct vers le fichier de base de données

// // Vérification de la connexion et du rôle de l'utilisateur
// if (!isset($_SESSION['utilisateur_id']) || $_SESSION['role'] !== 'admin') {
//     header('Location: login.php');
//     exit();
// }

// Récupérer tous les sprints
$stmt_sprints = $pdo->query("SELECT * FROM taches");
$sprints = $stmt_sprints->fetchAll(PDO::FETCH_ASSOC);

// Récupérer tous les éléments du product backlog
$stmt_product_backlog = $pdo->query("SELECT * FROM product_backlog");
$product_backlog = $stmt_product_backlog->fetchAll(PDO::FETCH_ASSOC);

// Traitement de l'ajout d'un élément au sprint backlog
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_backlog_id = $_POST['product_backlog_id'];
    $sprint_id = $_POST['sprint_id'];
    $statut = $_POST['statut'];
    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'];

    $stmt = $pdo->prepare("INSERT INTO sprint_backlog (product_backlog_id, sprint_id, statut, date_debut, date_fin) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$product_backlog_id, $sprint_id, $statut, $date_debut, $date_fin]);

    header('Location: sprint_backlog.php'); // Rediriger après l'ajout
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion du Sprint Backlog</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="max-w-7xl mx-auto p-8">
        <h1 class="text-3xl font-bold mb-6"> Sprint </h1>

        <form action="" method="POST" class="mb-6">
            <div class="mb-4">
                <label for="product_backlog_id" class="block text-sm font-bold mb-2">Élément du Product Backlog :</label>
                <select id="product_backlog_id" name="product_backlog_id" class="border rounded w-full p-2" required>
                    <option value="">Sélectionnez un élément</option>
                    <?php foreach ($product_backlog as $item): ?>
                        <option value="<?php echo $item['id']; ?>"><?php echo $item['fonctionnalite']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-4">
                <label for="sprint_id" class="block text-sm font-bold mb-2">Sprint :</label>
                <select id="sprint_id" name="sprint_id" class="border rounded w-full p-2" required>
                    <option value="">Sélectionnez un sprint</option>
                    <?php foreach ($sprints as $sprint): ?>
                        <option value="<?php echo $sprint['id']; ?>"><?php echo htmlspecialchars($sprint['nom_tache']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-4">
                <label for="statut" class="block text-sm font-bold mb-2">Statut :</label>
                <select id="statut" name="statut" class="border rounded w-full p-2" required>
                    <option value="à faire">À faire</option>
                    <option value="en cours">En cours</option>
                    <option value="terminé">Terminé</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="date_debut" class="block text-sm font-bold mb-2">Date de début :</label>
                <input type="date" id="date_debut" name="date_debut" class="border rounded w-full p-2" required>
            </div>
            <div class="mb-4">
                <label for="date_fin" class="block text-sm font-bold mb-2">Date de fin :</label>
                <input type="date" id="date_fin" name="date_fin" class="border rounded w-full p-2" required>
            </div>
            <button type="submit" class="bg-blue-500 text-white p-2 rounded hover:bg-blue-600 w-full">Ajouter au Sprint Backlog</button>
        </form>

        <h2 class="text-2xl font-bold mb-4">Éléments du Sprint </h2>
        <table class="min-w-full bg-white border rounded">
            <thead>
                <tr class="bg-gray-200">
                    <th class="py-2 px-4 border">Nom de l'Élément</th>
                    <th class="py-2 px-4 border">Sprint</th>
                    <th class="py-2 px-4 border">Statut</th>
                    <th class="py-2 px-4 border">Date de Début</th>
                    <th class="py-2 px-4 border">Date de Fin</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Récupérer les éléments du sprint backlog
                $stmt_sprint_backlog = $pdo->query("SELECT sb.*, pb.nom_fonctionnalite, s.nom_sprint FROM sprint_backlog sb JOIN product_backlog pb ON sb.product_backlog_id = pb.id JOIN sprints s ON sb.sprint_id = s.id");
                $sprint_backlogs = $stmt_sprint_backlog->fetchAll(PDO::FETCH_ASSOC);
                
                foreach ($sprint_backlogs as $sprint_backlog): ?>
                    <tr>
                        <td class="py-2 px-4 border"><?php echo htmlspecialchars($sprint_backlog['nom_fonctionnalite']); ?></td>
                        <td class="py-2 px-4 border"><?php echo htmlspecialchars($sprint_backlog['nom_sprint']); ?></td>
                        <td class="py-2 px-4 border"><?php echo htmlspecialchars($sprint_backlog['statut']); ?></td>
                        <td class="py-2 px-4 border"><?php echo htmlspecialchars($sprint_backlog['date_debut']); ?></td>
                        <td class="py-2 px-4 border"><?php echo htmlspecialchars($sprint_backlog['date_fin']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
