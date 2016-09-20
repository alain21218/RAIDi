<?php
session_start();
include('../php/pdo.php');

$query = "SELECT m.content, m.id_source, m.id as id_message FROM message m JOIN joueur j ON j.id = m.id_cible WHERE (m.id_source = ".$_SESSION["id"]." AND m.id_cible = (SELECT id FROM joueur WHERE ndc = '".$_GET["target"]."')) OR (m.id_source = (SELECT id FROM joueur WHERE ndc = '".$_GET["target"]."') AND m.id_cible = ".$_SESSION["id"].")  ORDER BY m.date ASC LIMIT 100";
$data = $bdd->query($query)->fetchAll();

if(!empty($data)) {
    echo json_encode($data);
}
?>