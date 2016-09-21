<?php
    session_start();
    include_once('../php/pdo.php');

    $id = $_SESSION["id"];
    $event = $_POST["event"];

    $query = "DELETE FROM inscription WHERE id_compte=$id AND id_event=(SELECT id FROM event WHERE DATE_FORMAT(date, '%d/%m/%Y %H:%i') = '$event')";
    $rowCount = $bdd->exec($query);

    if($rowCount > 0)
        echo true;
    else echo false;
?>