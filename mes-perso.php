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
                <div class="col-xs-12">
                    <?php
                        if(!isset($_SESSION['id'])){
                            header('Location: connexion.php');
                        }

                        include('php/mes-perso-form.php');
                    ?>
                </div>
            </div>
        </div>
    </body>
    <?php include('includes/footer.php'); ?>
</html>