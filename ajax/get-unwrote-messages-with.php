<?php
session_start();
include('../php/pdo.php');

$id = $_SESSION['id'];
$event = $_POST['event'];

$query = "DELETE FROM inscription WHERE id_compte= $id AND id_event= $event";
$rowCount = $bdd->exec($query)->fetchAll();

if($rowCount != 0)
    echo true;
else echo false;

?>