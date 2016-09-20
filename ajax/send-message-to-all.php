<?php
session_start();
include('../php/pdo.php');

$query = "insert into message(id_cible, id_source, date, content) SELECT j.id as source, j.id as cible, NOW(), '".$_POST['content']."' FROM joueur j";
$rowCount = $bdd->exec($query);

if($rowCount >= 1)
    echo true;
else echo false;
?>