<?php
    session_start();
    include('../php/pdo.php');

    $cible = $_POST['cible'];
    $id = $_SESSION['id'];
    $contenu = htmlspecialchars($_POST['contenu']);

    $query = "INSERT INTO message(id_source, id_cible, content, date, lu) VALUES($id, $cible, '$contenu', NOW(), 0)";
    $rowCount = $bdd->exec($query);

    if($rowCount >= 1)
        echo true;
    else echo false;
?>