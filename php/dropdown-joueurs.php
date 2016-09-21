<?php
    $query = "SELECT id, ndc FROM joueur ORDER BY ndc";
    $donnees = $bdd->query($query)->fetchAll();

    if(!empty($donnees)){
        echo '<select id="joueurs-dropdown">';

        foreach ($donnees as $ligne){
            if($_SESSION['id'] != $ligne['id']) {
                echo '<option value="' . $ligne['id'] . '">' . $ligne['ndc'] . '</option>';
            }
        }

        echo '</select>';
    }
?>


