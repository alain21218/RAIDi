<?php
session_start();
include('php/pdo.php');
?>

<!DOCTYPE html>
<html>
<?php include('includes/head.php'); ?>
<body>
<?php include('includes/menu.php'); ?>
<div class="container corps-page">
    <div class="row result">
        <?php include('php/tableau-repertoire.php'); ?>
    </div>
</div>
</body>
<?php include('includes/footer.php'); ?>
</html>
