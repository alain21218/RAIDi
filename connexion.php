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
        <div class="row connexion">
            <div class="col-xs-12">
                <?php
                    if(!isset($_SESSION['id'])) {
                        include('php/connexion-form.php');
                    }else{
                        header("Location:index.php");
                    }
                ?>
            </div>
        </div>
    </div>
</body>
<?php include('includes/footer.php'); ?>
</html>
