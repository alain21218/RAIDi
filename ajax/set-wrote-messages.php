<?php
session_start();
include('../php/pdo.php');

$query = "UPDATE message m SET m.lu = 1 WHERE (m.id_cible = ".$_SESSION["id"]." AND m.id_source = (SELECT id FROM joueur WHERE ndc = '".$_POST["cible"]."') AND m.id = ".$_POST['idmessage'].")";
$bdd->exec($query);

?>