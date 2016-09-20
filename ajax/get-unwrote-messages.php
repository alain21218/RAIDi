<?php
session_start();
include('../php/pdo.php');

$query = "SELECT j.ndc FROM message m JOIN joueur j ON j.id = m.id_source WHERE m.id_cible = ".$_SESSION["id"]." AND m.lu = 0 ORDER BY m.date ASC";
$data = $bdd->query($query)->fetchAll();
if(!empty($data)) {
    echo json_encode($data);
}
?>