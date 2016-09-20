<div class="col-xs-12">
    <a class="btn btn-primary" id="deplier">Déplier</a>
    <a class="btn btn-primary" id="replier">Replier</a>
    <table class="table table-hover">
        <thead>
        <tr>
            <th class="hidden-xs">N°</th>
            <th class="hidden-xs">Guilde</th>
            <th>Joueur</th>
            <th>Classe</th>
            <th>Spécialité</th>
            <th class="hidden-xs">Connaissances légendaires</th>
        </tr>
        </thead>
        <tbody>

        <?php
        //Tableau
        $query = 'SELECT DISTINCT j.ndc, j.exp, p.main, p.classe, p.spe, j.guild FROM joueur j JOIN perso p ON p.id_compte = j.id ORDER BY j.ndc ASC, p.main DESC';
        $donnees = $bdd->query($query)->fetchAll();

        if(isset($donnees)) {
            $i = 0;
            foreach ($donnees as $ligne) {
                if ($ligne['main'] == 1) {
                    $i++; ?>
                    <tr class="ligne cliquable first-char-<?php echo($i); ?>">
                        <td class="hidden"><?php echo $i; ?></td>
                        <td class="index-event hidden-xs"></td>
                        <td class="hidden-xs"><?php echo $ligne['guild']; ?></td>
                        <td><?php echo $ligne['ndc']; ?></td>
                        <td><?php echo $ligne['classe']; ?></td>
                        <td><?php echo $ligne['spe']; ?></td>
                        <td class="hidden-xs"><?php echo $ligne['exp']; ?></td>
                    </tr>
                <?php }else{ ?>
                    <tr class="second seconds-char-<?php echo($i); ?>">
                        <td class="hidden-xs"></td>
                        <td class="hidden-xs"></td>
                        <td></td>
                        <td><?php echo $ligne['classe']; ?></td>
                        <td><?php echo $ligne['spe']; ?></td>
                        <td class="hidden-xs"></td>
                    </tr>
                <?php }
            }
        } ?>

        </tbody>
    </table>
</div>
