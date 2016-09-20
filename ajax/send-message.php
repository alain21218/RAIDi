<?php
    session_start();
    include('../php/pdo.php');

    $query = 'INSERT INTO message(id_source, id_cible, content, date, lu) VALUES('.$_SESSION['id'].', (SELECT id FROM joueur WHERE ndc="'.$_POST['cible'].'"), "'.htmlspecialchars($_POST['contenu']).'", NOW(), 0)';
    $rowCount = $bdd->exec($query);

    if($rowCount >= 1)
        echo "Envoyé";
    else echo "Erreur lors de l'envoi";
?>