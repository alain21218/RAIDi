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
    <div class="row">
        <?php include('php/tableau-historique.php'); ?>
    </div>
</div>
</body>
<?php include('includes/footer.php'); ?>
</html>
