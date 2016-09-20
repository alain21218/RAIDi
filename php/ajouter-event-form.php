<?php
    if(isset($_POST['ajouter'])){
        $date = $_POST['date'];

        $query = 'INSERT INTO event(date) VALUE("'.$date.'")';

        $rowCount = $bdd->exec($query);

        if($rowCount > 0) {
            $success = 'Date & heure ajoutées';

            $historique = $_SESSION['ndc']." a ajouter un évènement pour le ".$date;
            ajouterHistorique($historique, $bdd);
        }else $error = 'Erreur lors de l\'ajout';
    }
?>

<?php if(isset($error)){?>
    <div class="col-xs-12 alert alert-danger">
        <?php echo $error; ?>
    </div>
<?php } ?>
<?php if(isset($success)){?>
    <div class="col-xs-12 alert alert-success">
        <?php echo $success; ?>
    </div>
<?php } ?>
<form style="text-align: center" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
    <label>Date & heure de votre prochain raid :</label><br/>
    <input name="date" id="datetimepicker" type="text" placeholder="Ajouter une date" required><br/>
    <input style="width:309px;" class="btn btn-primary" name="ajouter" type="submit" value="Ajouter">
    <?php if(isset($message)) echo $message; ?>
</form>
