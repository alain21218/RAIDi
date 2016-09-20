<?php
    function ajouterHistorique($contenu, $bdd){
        $query = 'INSERT INTO historique(libelle, date) VALUES ("'.$contenu.'", NOW())';
        $bdd->exec($query);
    }
?>