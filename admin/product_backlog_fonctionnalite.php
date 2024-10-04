<?php
session_start();
include '../includes/db.php'; // Chemin correct vers le fichier de base de données

// // Vérification de la connexion et du rôle de l'utilisateur
// if (!isset($_SESSION['utilisateur_id']) || $_SESSION['role'] !== 'admin') {
//     header('Location: login.php');
//     exit();
// }

// Récupérer le projet ou l'on va ajouter la tache 
if (!isset($_GET['id'])) {
    header('Location: product_backlog.php'); // Rediriger si l'ID n'est pas fourni
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM product_backlog WHERE id = ?");
$stmt->execute([$_GET['id']]);
$projet = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$projet) {
    header('Location: product_backlog.php'); // Rediriger si le projet n'existe pas
    exit();
}

// Récupérer les taches existantes
$stmt = $pdo->prepare("SELECT * FROM taches WHERE id_fonctionnalite = ?");
$stmt->execute([$_GET['id']]);
$taches = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Initialiser la variable d'erreur
$error_message = '';

// Handle form submission for status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['Statut']) && isset($_POST['tache_id'])) {
    $statut = trim($_POST['Statut']);
    $tache_id = $_POST['tache_id'];
    
    $update_avancement = $pdo->prepare("UPDATE taches SET statut = ? WHERE id = ?");
    $update_avancement->execute([$statut, $tache_id]);
    
    // Redirect to prevent form resubmission
    header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $_GET['id']);
    exit();
}






?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Taches Fonctionnalité</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <div class="max-w-7xl mx-auto p-8">
        
        
        <?php
        
            if ($_SESSION['role']=='product_owner') {
                
            
        ?>
        <h1 class="text-3xl font-bold mb-6">Product Owner</h1>
            <?php if ($error_message): ?>
                <div class="bg-red-200 text-red-800 p-2 rounded mb-4">
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>
            <form action="" method="POST" class="mb-4">
                <div class="mb-4">
                    <label for="tache" class="block text-sm font-bold mb-2">Sprint Backlog:</label>
                    <input type="text" id="tache" name="taches" class="border rounded w-full p-2"  required>
                </div>
                
                <button type="submit" class="bg-blue-500 text-white p-2 rounded hover:bg-blue-800 w-full">Ajouter</button>
            </form>
        <?php
            }else {
        ?>
            <h1 class="text-3xl font-bold mb-6">Membre Equipe Developpement</h1>
               
        <?php
            }
        ?>

        <h2 class="text-2xl font-bold mb-4">Sprint Backlog</h2>
        <table class="min-w-full bg-white border rounded">
            <thead>
                <tr class="bg-gray-200">
                    <th class="py-2 px-4 border" ><?php echo htmlspecialchars($projet['fonctionnalite']); ?></th>
                    <th class="py-2 px-4 border" >Actions</th>
                    <th class="py-2 px-4 border" >Avancement</th>
                </tr>
            </thead>
            <tbody>
                <?php

                
                
                foreach ($taches as $tache): ?>
                    <tr>
                        <td class="py-2 px-4 border"><a href="#"><?php echo htmlspecialchars($tache['nom_tache']); ?></a></td>
                        <td class="py-2 px-4 border">

                        <?php
                            
                            if ($_SESSION['role']=='product_owner') {
                                
                            
                        ?>
                            <a href="modifier_tache.php?id=<?php echo $tache['id']; ?>" class="text-blue-500">Modifier</a>
                            <a href="supprimer_tache.php?id=<?php echo $tache['id']; ?>" class="text-red-500">Supprimer</a>
                        <?php
                            }else {
                               
                        ?>
                                <p class='text-red-600'>Aucune</p>
                        <?php
                            }
                        ?>
                        </td>
                        <td class="py-2 px-4 border "><a href="#">
                            <?php
                                if ($_SESSION['role']=='product_owner') {
                                    
                                
                            ?>
                               <p><?php echo htmlspecialchars($tache['statut'] ?? 'Non défini'); ?></p>
                            <?php
                                }else {
                                
                            ?>

                                <form action="" method='POST' class='flex items-center justify-between'>
                                    <select id="statut_<?php echo $tache['id']; ?>" name="Statut" class="border rounded w-2/4 p-2" required>
                                        <option value="à faire" <?php echo $tache['statut'] == 'à faire' ? 'selected' : ''; ?>>À faire</option>
                                        <option value="en cours" <?php echo $tache['statut'] == 'en cours' ? 'selected' : ''; ?>>En cours</option>
                                        <option value="terminé" <?php echo $tache['statut'] == 'terminé' ? 'selected' : ''; ?>>Terminé</option>
                                    </select>
                                    <input type="hidden" name="tache_id" value="<?php echo $tache['id']; ?>">
                                    <button type="submit" class="bg-blue-500 text-white p-2 rounded hover:bg-blue-600">Mettre à jour</button>
                                    <p><?php echo htmlspecialchars($tache['statut'] ?? 'Non défini'); ?></p>
                                </form>
                                
                            <?php
                                }
                            ?>
                            
                           
                           
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>


    </div>
    
</body>
</html>