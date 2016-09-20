<div class="col-xs-12">
    <table class="table table-responsive">
        <thead>
        <tr>
            <th>Libellé</th>
            <th>Date</th>
        </tr>
        </thead>
        <tbody>

        <?php
        //Tableau
        $query = 'SELECT DATE_FORMAT(date, \'%d/%m/%Y %H:%i\') as datef, libelle FROM historique ORDER BY date DESC LIMIT 0, 50';
        $donnees = $bdd->query($query)->fetchAll();

        if(isset($donnees)) {
            foreach ($donnees as $ligne) {?>
                <tr>
                    <td><?php echo $ligne['libelle']; ?></td>
                    <td><?php echo $ligne['datef']; ?></td>
                </tr>
            <?php }
        } ?>

        </tbody>
    </table>
</div>
