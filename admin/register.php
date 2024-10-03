<?php
// Connexion à la base de données
include '../includes/db.php';

$erreurs = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $mot_de_passe = $_POST['mot_de_passe'];
    $mot_de_passe_confirme = $_POST['mot_de_passe_confirme'];
    $role = $_POST['role']; // Nouveau champ pour le rôle

    // Validation des champs
    if (empty($nom) || empty($email) || empty($mot_de_passe) || empty($mot_de_passe_confirme) || empty($role)) {
        $erreurs[] = "Tous les champs doivent être remplis.";
    }

    if ($mot_de_passe !== $mot_de_passe_confirme) {
        $erreurs[] = "Les mots de passe ne correspondent pas.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreurs[] = "L'email est invalide.";
    }

    // Vérification si le rôle est valide
    $roles_valides = ['gestionnaire_projet', 'scrum_master', 'admin','membre_equipe', 'product_owner'];
    if (!in_array($role, $roles_valides)) {
        $erreurs[] = "Le rôle sélectionné est invalide.";
    }

    if (empty($erreurs)) {
        // Hachage du mot de passe
        $mot_de_passe_hash = password_hash($mot_de_passe, PASSWORD_DEFAULT);

        // Insertion de l'utilisateur dans la base de données avec son rôle
        $stmt = $pdo->prepare("INSERT INTO utilisateurs (nom, email, mot_de_passe, role) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nom, $email, $mot_de_passe_hash, $role]);

        // Redirection après l'inscription
        header("Location: login.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="max-w-lg mx-auto mt-10 p-8 bg-white shadow-lg rounded-lg">
        <h2 class="text-2xl font-bold text-center mb-8">Inscription</h2>

        <!-- Affichage des erreurs -->
        <?php if (!empty($erreurs)): ?>
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                <?php foreach ($erreurs as $erreur): ?>
                    <p><?php echo $erreur; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Formulaire d'inscription -->
        <form action="register.php" method="POST" class="space-y-4">
            <div>
                <label for="nom" class="block text-sm font-medium">Nom :</label>
                <input type="text" id="nom" name="nom" class="w-full p-2 border rounded" required>
            </div>
            <div>
                <label for="email" class="block text-sm font-medium">Email :</label>
                <input type="email" id="email" name="email" class="w-full p-2 border rounded" required>
            </div>
            <div>
                <label for="mot_de_passe" class="block text-sm font-medium">Mot de passe :</label>
                <input type="password" id="mot_de_passe" name="mot_de_passe" class="w-full p-2 border rounded" required>
            </div>
            <div>
                <label for="mot_de_passe_confirme" class="block text-sm font-medium">Confirmer mot de passe :</label>
                <input type="password" id="mot_de_passe_confirme" name="mot_de_passe_confirme" class="w-full p-2 border rounded" required>
            </div>
            <div>
                <label for="role" class="block text-sm font-medium">Rôle :</label>
                <select id="role" name="role" class="w-full p-2 border rounded" required>
                    <option value="">Sélectionnez un rôle</option>
                    <option value="gestionnaire_projet">Gestionnaire de Projet</option>
                    <option value="scrum_master">Scrum Master</option>
                    <option value="membre_equipe">Membre Équipe de Développement</option>
                    <option value="product_owner">Product Owner</option>
                </select>
            </div>
            <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded font-bold hover:bg-blue-600">S'inscrire</button>
            <p>Vous avez un compte?<a href='login.php' class='text-purple-600'> connectez-vous</a></p>
        </form>
    </div>
</body>
</html>
