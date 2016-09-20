<?php
session_start();
include('../php/pdo.php');

$query = "SELECT m.content, m.id as id_message FROM message m JOIN joueur j ON j.id = m.id_cible WHERE (m.id_cible = ".$_SESSION["id"]." AND m.id_source = (SELECT id FROM joueur WHERE ndc = '".$_GET["cible"]."')) AND m.lu = 0 ORDER BY m.date ASC";
$data = $bdd->query($query)->fetchAll();

if(!empty($data)) {
    echo json_encode($data);
}
?>