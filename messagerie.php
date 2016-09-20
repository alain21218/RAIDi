<?php
session_start();
include('php/pdo.php');

if(!isset($_SESSION['id'])){
    header('Location: connexion.php');
}
?>

<!DOCTYPE html>
<html>
<?php include('includes/head.php'); ?>
<body>
<?php include('includes/menu.php'); ?>
<div class="container corps-page chat">
    <div class="row">
        <!-- Colonne de gauche -->
        <div class="col-md-3">
            <div class="row">
                <div class="col-xs-12 inlines">
                    <?php include('php/dropdown-joueurs.php'); ?>
                    <button class="btn-primary" id="new-discussion">Nouveau</button>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <h4>Mes discussions</h4>
                    <ul class="discussion-list">
                    </ul>
                </div>
            </div>
        </div>

        <!-- Colonne de droite -->
        <div class="col-md-9">
            <table class="current-discussion chat-view">
                
            </table>
        </div>

        <div class="col-md-9 col-md-offset-3">
            <input type="hidden" id="hdnSession" value=<?php echo $_SESSION['id']; ?> />
            <textarea id="content-to-send"></textarea>
            <span class="chat-content-option"><input checked type="checkbox" id="enter-to-send" name="enter-to-send"><label for="enter-to-send">Entrée pour envoyer</label></span>
            <button class="pull-right btn-primary" id="send-message">Envoyer</button>
        </div>
    </div>
</div>
</body>
<?php include('includes/footer.php'); ?>
</html>
