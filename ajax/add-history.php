<?php
    include_once('../php/pdo.php');

    $contenu = $_POST['content'];
    $query = "INSERT INTO historique(libelle, date) VALUES ('$contenu', NOW())";
    $rowCount = $bdd->exec($query);

    if($rowCount > 0)
        echo true;
    else echo false;
?>