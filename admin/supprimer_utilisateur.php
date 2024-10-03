<?php
    require ('../includes/db.php');
    
    if (isset($_GET['id']) AND !empty($_GET['id'])) {
        $id_value=$_GET['id'];
        $supprimer_utilisateur=$pdo->prepare('DELETE FROM utilisateurs WHERE id=?');
        $supprimer_utilisateur->execute(array($id_value));
        header('Location:admin_dashboard.php');
        
    }

?>