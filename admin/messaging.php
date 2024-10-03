<?php
session_start();
include '../includes/db.php'; // Assurez-vous que le chemin est correct

// // Vérifiez si l'utilisateur est connecté
// if (!isset($_SESSION['utilisateur_id'])) {
//     header('Location: login.php'); // Redirection vers la page de connexion
//     exit();
// }

// Récupérer les messages de l'utilisateur
$utilisateur_id = $_SESSION['utilisateur_id'];
$stmt = $pdo->prepare("SELECT m.id, m.contenu, m.date_envoi, u.nom AS expediteur
                        FROM messages m
                        JOIN utilisateurs u ON m.expediteur_id = u.id
                        WHERE m.destinataire_id = :utilisateur_id
                        ORDER BY m.date_envoi DESC");
$stmt->execute(['utilisateur_id' => $utilisateur_id]);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messagerie</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="max-w-7xl mx-auto p-8">
        <h1 class="text-3xl font-bold mb-6">Messagerie</h1>
        
        <h2 class="text-2xl font-bold mb-4">Messages reçus</h2>
        <table class="min-w-full bg-white border rounded">
            <thead>
                <tr class="bg-gray-200">
                    <th class="py-2 px-4 border">Expéditeur</th>
                    <th class="py-2 px-4 border">Contenu</th>
                    <th class="py-2 px-4 border">Date d'envoi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($messages as $message): ?>
                    <tr>
                        <td class="py-2 px-4 border"><?php echo htmlspecialchars($message['expediteur']); ?></td>
                        <td class="py-2 px-4 border"><?php echo htmlspecialchars($message['contenu']); ?></td>
                        <td class="py-2 px-4 border"><?php echo htmlspecialchars($message['date_envoi']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
