<?php
    if(isset($_POST['envoyer'])){
        if(mail (
            "alain21218@gmail.com" ,
            htmlspecialchars("raid.vtvw2.fr - ".$_POST['sender']) ,
            htmlspecialchars($_POST['comment'])
        )){
            $success_contact = 'E-Mail envoyé';
        }else{
            $error_contact = "Une erreur est survenue";
        }
    }
?>

<footer>
    <div class="container">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <legend>Signaler un bug</legend>
            <?php if(isset($error_contact)){?>
                <div class="col-xs-12 alert alert-danger">
                    <?php echo $error_contact; ?>
                </div>
            <?php } ?>
            <?php if(isset($succes_contacts)){?>
                <div class="col-xs-12 alert alert-success">
                    <?php echo $success_contact; ?>
                </div>
            <?php } ?>
            <input name='sender' type="text" placeholder="Nom de compte" id="bug-form-sender" value="<?php if(isset($_SESSION['ndc'])) echo $_SESSION['ndc']; ?>" required><br/>
            <textarea type="text" name="comment" placeholder="Votre message" id="bug-form-content" required></textarea><br/>
            <input class="btn btn-primary" name="envoyer" type="submit" value="Envoyer">
        </form>
    </div>
</footer>