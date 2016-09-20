<?php
    $jours = [
        "Dimanche",
        "Lundi",
        "Mardi",
        "Mercredi",
        "Jeudi",
        "Vendredi",
        "Samedi",
    ];

    $query = 'SELECT DAYOFWEEK(date) as jour, DATE_FORMAT(date, \'%d/%m/%Y %H:%i\') AS datef FROM event WHERE date > NOW() ORDER BY date DESC';
    $donnees = $bdd->query($query)->fetchAll();

    $query = 'SELECT DAYOFWEEK(date) as jour, DATE_FORMAT(date, \'%d/%m/%Y %H:%i\') AS datef FROM event WHERE date > NOW() ORDER BY date ASC LIMIT 1';
    $now = $bdd->query($query)->fetch();

    $query = 'SELECT DAYOFWEEK(date) as jour, DATE_FORMAT(date, \'%d/%m/%Y %H:%i\') AS datef FROM event WHERE date < NOW() ORDER BY date DESC';
    $past = $bdd->query($query)->fetchAll();

    if(isset($donnees)) { ?>
        <select name="date-select" id="date-select">
            <?php foreach ($donnees as $ligne) {
                if($now['datef'] == $ligne['datef']){?>
                    <option selected value="<?php echo $ligne['datef']; ?>"><?php echo $jours[$ligne['jour']-1].' '.$ligne['datef']; ?></option>
                <?php }else{ ?>
                    <option value="<?php echo $ligne['datef']; ?>"><?php echo $jours[$ligne['jour']-1].' '.$ligne['datef']; ?></option>
            <?php }}

            foreach ($past as $ligne) {?>
                <option class="past-option" value="<?php echo $ligne['datef']; ?>"><?php echo $jours[$ligne['jour']-1].' '.$ligne['datef']; ?></option>
            <?php } ?>
        </select>
    <?php } ?>