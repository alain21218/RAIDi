<?php
session_start();
include('../php/pdo.php');

$query = 'UPDATE inscription SET equipe = "'.$_POST['team'].'", id_perso = "'.$_POST["char"].'" WHERE id_compte = (SELECT id FROM joueur WHERE ndc = "'.$_POST["player"].'") AND id_event = (SELECT id FROM event WHERE DATE_FORMAT(date, \'%d/%m/%Y %H:%i\') = "'.$_POST["date"].'")';
$rowCount = $bdd->exec($query);

if($rowCount >= 1)
    echo true;
else echo false;
?>