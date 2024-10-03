<?php
session_start();
include '../includes/db.php'; // Connexion à la base de données

// // Vérification si l'utilisateur est déjà connecté
// if (isset($_SESSION['utilisateur_id'])) {
//     // Redirection en fonction du rôle déjà stocké dans la session
//     switch ($_SESSION['role']) {
//         case 'admin':
//             header('Location: admin_dashboard.php');
//             exit();
//         case 'gestionnaire_projet':
//             header('Location: gestionnaire_dashboard.php');
//             exit();
//         case 'scrum_master':
//             header('Location: scrum_master_dashboard.php');
//             exit();
//         case 'membre_equipe':
//             header('Location: membre_dashboard.php');
//             exit();
//         case 'product_owner':
//             header('Location: product_owner_dashboard.php');
//             exit();
//         default:
//             header('Location: login.php');
//             exit();
//     }
// }

// Code de connexion ci-dessous...
$error_message = ''; // Initialiser la variable d'erreur

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validation des champs
    if (empty($email) || empty($password)) {
        $error_message = 'Veuillez remplir tous les champs.';
    } else {
        // Préparer la requête pour vérifier les identifiants
        $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
        $stmt->execute([$email]);
        $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

        // Vérifier si l'utilisateur existe et si le mot de passe est correct
        if ($utilisateur && password_verify($password, $utilisateur['mot_de_passe'])) {
            // Créer une session pour l'utilisateur
            $_SESSION['utilisateur_id'] = $utilisateur['id'];
            $_SESSION['role'] = $utilisateur['role'];

            header('Location: ' . $_SESSION['role'] . '_dashboard.php'); // Redirection vers le tableau de bord correspondant
            exit();
        } else {
            $error_message = 'Identifiant ou mot de passe incorrect.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded shadow-md w-96">
        <h1 class="text-2xl font-bold mb-4">Connexion</h1>

        <?php if ($error_message): ?>
            <div class="bg-red-200 text-red-800 p-2 rounded mb-4">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="mb-4">
                <label for="email" class="block text-sm font-bold mb-2">Email :</label>
                <input type="email" id="email" name="email" class="border rounded w-full p-2" required>
            </div>
            <div class="mb-4">
                <label for="password" class="block text-sm font-bold mb-2">Mot de passe :</label>
                <input type="password" id="password" name="password" class="border rounded w-full p-2" required>
            </div>
            <button type="submit" class="bg-blue-500 text-white p-2 rounded hover:bg-blue-600 w-full">Se connecter</button>
            <p>Vous n'avez pas de compte?<a href='register.php' class='text-purple-600'> Creer un compte</a></p>
        </form>
    </div>
</body>
</html>
