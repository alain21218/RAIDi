<?php
session_start();
include('../php/pdo.php');

$current = $_SESSION["id"];
$contact = $_GET["target"];

$query = "SELECT m.content, m.id_source, m.id as id_message FROM message m JOIN joueur j ON j.id = m.id_cible WHERE (m.id_source = $current AND m.id_cible = $contact) OR (m.id_source = $contact AND m.id_cible = $current)  ORDER BY m.date ASC LIMIT 100";
$data = $bdd->query($query)->fetchAll();

if(!empty($data)) {
    echo json_encode($data);
}
?>