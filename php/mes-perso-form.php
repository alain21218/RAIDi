<?php
    $classes = [
        "Elémentaliste",
        "Nécromant",
        "Envouteur",
        "Gardien",
        "Guerrier",
        "Revenant",
        "Rodeur",
        "Voleur",
        "Ingénieur"
    ];

    $spes = [
        "DPS",
        "Altérations",
        "Tank",
        "Soin"
    ];

    //On vérifie si le compte possède au moins un personnage
    $ndc = $_SESSION['ndc'];
    $id = $_SESSION['id'];
    $query = 'SELECT * FROM perso WHERE id_compte='.$id.' AND main=1';
    $main = $bdd->query($query)->fetch();
    $query = 'SELECT * FROM perso WHERE id_compte='.$id.' AND main=0';
    $seconds = $bdd->query($query)->fetchAll();

    if(isset($_POST['envoyer'])){
        $main_class = $_POST['main-class'];
        $main_spe = $_POST['main-spe'];
        $exp = $_POST['exp'];
        $guild = $_POST['guild'];

        $second_char = [];

        //On retire les persos existants
        //Si il y a au moins un perso
        if(!empty($main)) {
            $query = 'DELETE FROM perso WHERE id_compte=' . $id;
            $bdd->exec($query);
        }

        //Ajouter le nouveau main & modifier les LI
        $query = 'INSERT INTO perso(classe, spe, id_compte, main) VALUES("'.$main_class.'", "'.$main_spe.'", '.$id.', 1)';
        $rowCount = $bdd->exec($query);
        $query = 'UPDATE joueur SET exp = '.$exp.', guild = UPPER("'.$guild.'") WHERE id='.$id;
        $bdd->exec($query);
        $_SESSION['li'] = $exp;
        $_SESSION['guild'] = $guild;

        //Ajout des seconds perso
        for($cpt=0; $cpt<7; $cpt++){
            if(isset($_POST['second-class-'.$cpt]))
                $second_char[$_POST['second-class-'.$cpt]] = $_POST['second-spe-'.$cpt];
        }

        foreach($second_char as $char => $spe){
            $query = 'SELECT * FROM perso WHERE classe="'.$char.'" AND spe="'.$spe.'" AND id_compte='.$id;
            $exist = $bdd->query($query)->fetch();

            if(empty($exist)){
                $query = 'INSERT INTO perso(classe, spe, id_compte, main) VALUES("'.$char.'", "'.$spe.'", "'.$id.'", 0)';
                $rowCount += $bdd->exec($query);
            }
        }

        if ($rowCount >= 1) {
            $raid_success = 'Personnage(s) ajouté(s), la page va etre actualisée';
            
            $historique = $ndc." a modifié sa fiche de personnages";
            ajouterHistorique($historique, $bdd);
            
            header("Refresh:3");
        }else
            $raid_error = 'Une erreur est survenue';
    }
?>

<fieldset class="raid-form">
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <div class="row">
            <?php if(isset($raid_error)){?>
                <div class="col-xs-12">
                    <div class="alert alert-danger">
                        <?php echo $raid_error; ?>
                    </div>
                </div>
            <?php } ?>
            <?php if(isset($raid_success)){?>
                <div class="col-xs-12">
                    <div class="alert alert-success">
                        <?php echo $raid_success; ?>
                    </div>
                </div>
            <?php } ?>
            <div class="col-md-6 col-xs-12">
                <label for="classe-main">Je préfère jouer :</label>
            </div>
            <div class="col-md-6 col-xs-12">
                <div class="row">
                    <div class="col-xs-6">
                        <select name="main-class"">
                            <?php foreach ($classes as $classe){
                                if(!strcmp($classe, $main['classe']))
                                    echo '<option value="'.$classe.'" selected>'.$classe.'</option>';
                                else echo '<option value="'.$classe.'">'.$classe.'</option>';
                            } ?>
                        </select>
                    </div>
                    <div class="col-xs-6">
                        <select name="main-spe"">
                            <?php foreach ($spes as $spe){
                                if(!strcmp($spe, $main['spe']))
                                    echo '<option value="'.$spe.'" selected>'.$spe.'</option>';
                                else echo '<option value="'.$spe.'">'.$spe.'</option>';
                            } ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xs-12">
                <label>Sinon, je peux jouer :</label>
            </div>
            <div class="col-md-6 col-xs-12">
                <?php $i = 0; ?>
                <?php foreach($seconds as $second){ ?>
                    <div class="row">
                        <div class="col-xs-6 seconds-char">
                            <select name="second-class-<?php echo $i;?>"">
                                <?php foreach ($classes as $classe){
                                    if(!strcmp($classe, $second['classe']))
                                        echo '<option value="'.$classe.'" selected>'.$classe.'</option>';
                                    else echo '<option value="'.$classe.'">'.$classe.'</option>';
                                } ?>
                            </select>
                        </div>
                        <div class="col-xs-6">
                            <select name="second-spe-<?php echo $i;?>"">
                                <?php foreach ($spes as $spe){
                                    if(!strcmp($spe, $second['spe']))
                                        echo '<option value="'.$spe.'" selected>'.$spe.'</option>';
                                    else echo '<option value="'.$spe.'">'.$spe.'</option>';
                                } ?>
                            </select>
                        </div>
                    </div>
                <?php
                $i++;
                } ?>
                <a class="pull-right btn-link <?php if($i==0) echo 'hidden'; ?>" id="rem-char">Retirer un personnage</a><br/>
                <a class="pull-right btn-link" id="add-char">Ajouter un personnage</a><br/>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 col-xs-12">
                <label>Nombre de connaissances légendaires en ma possession : <a href="http://db.gw2.fr/item/77302" target="_blank">(qu'est-ce ?)</a></label>
            </div>
            <div class="col-md-6 col-xs-12">
                <input style="margin-top: 10px;" name="exp" id="exp-oui" name="exp" type="number" value=<?php echo $_SESSION['li']; ?> required>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 col-xs-12">
                <label>Tag de guilde</label>
            </div>
            <div class="col-md-6 col-xs-12">
                <input name="guild" type="text" maxlength="4" placeholder="VTV" value="<?php echo $_SESSION['guild']; ?>">
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <input name="envoyer" class="btn btn-primary pull-right" type="submit">
            </div>
        </div>
    </form>
</fieldset>