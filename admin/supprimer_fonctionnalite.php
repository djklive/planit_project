<?php
    require ('../includes/db.php');
    
    if (isset($_GET['id']) AND !empty($_GET['id'])) {
        $id_value=$_GET['id'];
        $supprimer_utilisateur=$pdo->prepare('DELETE FROM product_backlog WHERE id=?');
        $supprimer_utilisateur->execute(array($id_value));
        header('Location:product_backlog.php');
        
    }

?>