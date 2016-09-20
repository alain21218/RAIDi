<?php
    session_start();
    include('../php/pdo.php');

    $query = 'SELECT DISTINCT ndc, id FROM (SELECT j.ndc, j.id, m.date from joueur j join message m on m.id_cible = j.id where id_source = '.$_SESSION["id"].' union SELECT j.ndc, j.id, m.date from joueur j join message m on m.id_source = j.id where id_cible = '.$_SESSION["id"].' ORDER BY date DESC) as result';
    $data = $bdd->query($query)->fetchAll();

    if(!empty($data)) {
        echo json_encode($data);
    }
?>