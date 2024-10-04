<?php 
session_start();
include '../includes/db.php'; // Chemin correct vers le fichier de base de données

// // Vérification de la connexion et du rôle de l'utilisateur
// if (!isset($_SESSION['utilisateur_id']) || $_SESSION['role'] !== 'admin') {
//     header('Location: login.php');
//     exit();
// }

// Récupérer les fonctionnalités existantes
$stmt = $pdo->query("SELECT * FROM product_backlog");
$fonctionnalites = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer tous les développeurs
$stmt_utilisateurs = $pdo->prepare("SELECT * FROM utilisateurs where role = 'developpeur'");
$stmt_utilisateurs->execute();
$utilisateurs = $stmt_utilisateurs->fetchAll(PDO::FETCH_ASSOC);

// Traitement de l'ajout et la mise à jour du développeur 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tache_id = $_POST['tache_id'];
    $utilisateur_id = $_POST['utilisateur_id'];
    
    $stmt = $pdo->prepare("UPDATE taches SET id_developpeur = ? WHERE id = ?");
    $stmt->execute([$utilisateur_id, $tache_id]);

    // Rediriger vers la même page pour éviter la soumission multiple du formulaire
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Affecter Taches</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <div class="max-w-7xl mx-auto p-8">

        <h2 class="text-3xl font-bold mb-4">Sprint Backlog</h2>
        <?php foreach ($fonctionnalites as $fonctionnalite): ?>
            <?php
            // Récupérer les taches par fonctionnalités 
            $stmt = $pdo->prepare("SELECT t.*, u.nom as nom_developpeur FROM taches t LEFT JOIN utilisateurs u ON t.id_developpeur = u.id WHERE t.id_fonctionnalite = ?");
            $stmt->execute([$fonctionnalite['id']]);
            $taches = $stmt->fetchAll(PDO::FETCH_ASSOC);
            ?>
            <h2 class="text-2xl font-bold mb-4"><?php echo htmlspecialchars($fonctionnalite['fonctionnalite']); ?></h2>
            <table class="min-w-full bg-white border rounded mb-6">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="py-2 px-4 border">Taches</th>
                        <th class="py-2 px-4 border">Action</th>
                        <th class="py-2 px-4 border">Développeur</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($taches as $tache): ?>
                        <tr>
                            <td class="py-2 px-4 border"><?php echo htmlspecialchars($tache['nom_tache']); ?></td>
                            <td class="py-2 px-4 border">Aucune</td>
                            <td class="py-2 px-4 border">
                                <form action="" method="POST" class="flex justify-between items-center">
                                    <input type="hidden" name="tache_id" value="<?php echo $tache['id']; ?>">
                                    <select name="utilisateur_id" class="border rounded w-2/4 p-2" onchange="this.form.submit()">
                                        <option value="">Sélectionnez un développeur</option>
                                        <?php foreach ($utilisateurs as $utilisateur): ?>
                                            <option value="<?php echo $utilisateur['id']; ?>" <?php echo ($tache['id_developpeur'] == $utilisateur['id']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($utilisateur['nom']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <p><?php echo $tache['nom_developpeur'] ? htmlspecialchars($tache['nom_developpeur']) : 'Aucun'; ?></p>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        
        <?php endforeach; ?>

    </div>
    
</body>
</html>