<?php
    require ('../includes/db.php');
    
    if (isset($_GET['id']) AND !empty($_GET['id'])) {
        $id_value=$_GET['id'];
        $supprimer_utilisateur=$pdo->prepare('DELETE FROM product_backlog WHERE id=?');
        $supprimer_utilisateur->execute(array($id_value));
        if ($_SESSION['role'] == 'product_owner') {
            header('Location: product_backlog.php'); // Rediriger vers le tableau de bord après modification
        } else if ($_SESSION['role'] == 'scrum_master'){
            header('Location: scrum_master_dashboard.php'); // Rediriger vers le tableau de bord après modification
        }
        
    }

?>