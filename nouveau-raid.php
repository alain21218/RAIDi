<?php
session_start();
include('php/pdo.php');
$jours = [
    "Dimanche",
    "Lundi",
    "Mardi",
    "Mercredi",
    "Jeudi",
    "Vendredi",
    "Samedi",
];
$query = 'SELECT DAYOFWEEK(date) as jour, DATE_FORMAT(date, \'%d/%m/%Y %H:%i\') AS date FROM event WHERE date > NOW() ORDER BY date ASC';
$donnees = $bdd->query($query)->fetch();
?>

<!DOCTYPE html>
<html>
<?php include('includes/head.php'); ?>
<body>
<?php include('includes/menu.php'); ?>
<div class="container corps-page">
    <div class="row">
        <?php include('php/ajouter-event-form.php'); ?>
    </div>
</div>
</body>
<?php include('includes/footer.php'); ?>
</html>

