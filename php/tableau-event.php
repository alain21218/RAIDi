<?php
    //Vérifie qu'il existe au moins un raid dans la BD
    $query = 'SELECT id from event where  DATE_FORMAT(date, \'%d/%m/%Y %H:%i\') < NOW()';
    $players = $bdd->query($query)->fetch();

    if(isset($_POST['inscrire'])){
        $query = 'SELECT id from event where  DATE_FORMAT(date, \'%d/%m/%Y %H:%i\') = "'.$_POST['date-select'].'"';
        $players = $bdd->query($query)->fetch();

        $idCompte = $_SESSION['id'];
        $idEvent = $players['id'];

        $query = 'SELECT * FROM inscription WHERE id_compte = '.$idCompte.' AND id_event = '.$idEvent;
        $exist = $bdd->query($query)->fetch();

        $query = 'SELECT * FROM perso WHERE id_compte='.$idCompte;
        $perso = $bdd->query($query)->fetch();

        if(!empty($exist)) {
            $error = 'Vous etes déjà inscrit à ce raid';
        }else if(empty($perso)){
            $error = 'Il vous faut au moins un personnage pour pouvoir vous inscrire à un raid';
        }else {
            $query = 'INSERT INTO inscription(id_compte, id_event, date_inscription) VALUES(' . $idCompte . ', ' . $idEvent . ', NOW())';
            $rowCount = $bdd->exec($query);

            if ($rowCount > 0){
                $success = 'Inscription validée';

                $historique = $_SESSION['ndc']." s'est inscrit à l'événement du ".$_POST['date-select'];
                ajouterHistorique($historique, $bdd);
            }else $error = 'Une erreur est survenue';
        }
    }else if(isset($_POST['desinscrire'])) {
        $event = $_POST['event'];
        $id = $_SESSION['id'];

        $query = 'DELETE FROM inscription WHERE id_compte='.$id.' AND id_event='.$event;
        $rowCount = $bdd->exec($query);

        if($rowCount > 0){
            $success = "Désinscription effectuée";

            $query = "SELECT DATE_FORMAT(date, '%d/%m/%Y %H:%i') as datef FROM event WHERE id=".$_POST['event'];
            $event = $bdd->query($query)->fetch();

            $historique = $_SESSION['ndc']." s'est désinscrit de l'événement du ".$event['datef'];
            ajouterHistorique($historique, $bdd);
        }else $error = "Erreur lors de la désinscription";
    }
?>

<div class="col-xs-12">
    <?php if(!empty($players)){ ?>
        <form class="form-inline" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <div class="form-group dropdown-date"><?php include('php/dropdown-date.php'); ?></div>
            <?php if(isset($_SESSION['id'])){ ?><div class="form-group"><input class="btn btn-primary" name="inscrire" type="submit" id="inscription-raid" value="Participer !"></div> <?php } ?>
        </form>
    <?php } ?>
    <?php if(isset($error)){?>
        <div class="alert alert-danger">
            <?php echo $error; ?>
        </div>
    <?php } ?>
    <?php if(isset($success)){?>
        <div class="alert alert-success">
            <?php echo $success; ?>
        </div>
    <?php } ?>
    <table class="table" id="inscrits">
        <thead>
        <tr>
            <th>N°</th>
            <th class="hidden-xs">Guilde</th>
            <th>Joueur</th>
            <th class="hidden-xs">Personnages</th>
            <th class="hidden-xs">LI</th>
            <th>Equipe</th>
            <th></th>
        </tr>
        </thead>
        <tbody>

        <?php
        //Tableau
        $query = 'SELECT DATE_FORMAT(date, \'%d/%m/%Y %H:%i\') AS datef, i.id_event as event,j.id, j.ndc, j.exp, j.guild, i.equipe, i.id_perso FROM joueur j JOIN inscription i ON i.id_compte = j.id JOIN event e ON e.id = i.id_event GROUP by i.id_event, j.id ORDER BY i.equipe ASC, j.guild ASC';
        $players = $bdd->query($query)->fetchAll();

        if(isset($players)) {
            $i = 0;
            foreach ($players as $player) {
                    $i++; ?>
                    <tr class="ligne">
                        <td class="hidden"><?php echo $i; ?></td>
                        <td class="index-event"></td> <!--Généré en jquery-->
                        <td class="hidden-xs"><?php echo $player['guild']; ?></td>
                        <td class="player-name"><?php echo $player['ndc']; ?></td>

                        <td class="hidden-xs">
                            <?php
                                $query = 'SELECT p.id, p.classe, p.spe, p.main FROM perso p WHERE p.id_compte = '.$player["id"];
                                $chars = $bdd->query($query)->fetchAll();

                                if(isset($_SESSION['droits']) && $_SESSION['droits'] >= 1) {
                                    if (sizeof($chars) > 1) {?>
                                        <select>
                                            <?php
                                            //Vue utilisateur avancé => liste
                                            if ($player['id_perso'] <= 0) {
                                                //S'il n'y a pas de perso pour l'équipe
                                                foreach ($chars as $char) {
                                                    if ($char['main'])
                                                        echo '<option selected value=' . $char["id"] . ' >' . $char["classe"] . ' ' . $char["spe"] . '</option>';
                                                    else echo '<option value=' . $char["id"] . '>' . $char["classe"] . ' ' . $char["spe"] . '</option>';
                                                }
                                                //Si un perso a été sélectionné pour l'équipe
                                            } else {
                                                foreach ($chars as $char) {
                                                    if ($player['id_perso'] == $char['id'])
                                                        echo '<option class="choice" selected value=' . $char["id"] . ' >' . $char["classe"] . ' ' . $char["spe"] . '</option>';
                                                    else echo '<option value=' . $char["id"] . '>' . $char["classe"] . ' ' . $char["spe"] . '</option>';
                                                }
                                            } ?>
                                        </select>
                                    <?php } else {
                                        echo '
                                            <select disabled>
                                                <option value=' . $chars[0]["id"] . '>' . $chars[0]["classe"] . ' ' . $chars[0]["spe"] . '</option>
                                            </select>';
                                    }
                                }else{
                                    if ($player['id_perso'] <= 0) {
                                        foreach ($chars as $char) {
                                            if ($char['main'])
                                                echo $char["classe"] . ' ' . $char["spe"];
                                        }
                                    } else {
                                        if (sizeof($chars) > 1) {
                                            foreach ($chars as $char) {
                                                if ($player['id_perso'] == $char['id'])
                                                    echo $char["classe"] . ' ' . $char["spe"];
                                            }
                                        }else echo $chars[0]["classe"] . ' ' . $chars[0]["spe"];
                                    }
                                }?>
                        </td>

                        <td class="hidden-xs"><?php echo $player['exp']; ?></td>
                        <td class="hidden date-raid"><?php echo $player['datef']; ?></td>
                        <?php if(isset($_SESSION["droits"]) && $_SESSION["droits"] >= 1){ ?>
                            <td><input class="team-modify" type="text" value="<?php echo $player['equipe']; ?>"/></td>
                        <?php }else{ ?>
                            <td><?php echo $player['equipe']; ?></td>
                        <?php } ?>
                        <td>
                            <?php if(isset($_SESSION['id'])){
                                if($_SESSION['id'] == $player['id']){?>
                                    <a class="btn btn-primary unsubscribe"><span class="glyphicon glyphicon-remove"></span></a>
                                <?php }
                            } ?>
                        </td>
                    </tr>
                <?php }
        } ?>
        </tbody>
    </table>
    <div class="done msg-modif alert alert-success">Modification effectuée</div>
</div>
