<?php

if(isset($_POST['deconnexion'])){
    session_destroy();
    header("Refresh:0");
}else if(isset($_POST['connexion'])) {
    $id = htmlspecialchars($_POST['id']);
    $mdp = htmlspecialchars($_POST['mdp']);

    $query = 'SELECT * FROM joueur WHERE ndc="' . $id . '" AND mdp="' . sha1($mdp) . '";';
    $compte = $bdd->query($query)->fetch();

    if (empty($compte)) {
        $erreur = 'Nom de compte inexistant ou mot de passe incorrect';
    } else {
        $_SESSION['id'] = $compte['id'];
        $_SESSION['ndc'] = $compte['ndc'];
        $_SESSION['li'] = $compte['exp'];
        $_SESSION['droits'] = $compte['droits'];
        $_SESSION['guild'] = $compte['guild'];
        header("Refresh:0");
    }
}

if(!isset($_SESSION['id'])) {?>
    <form class="form-inline" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <div class="form-group">
            <input name="id" type="text" placeholder="Nom de compte" required>
        </div>
        <div class="form-group">
            <input name="mdp" type="password" placeholder="Mot de passe" required>
        </div>
        <input class="btn btn-primary" name="connexion" type="submit" value="Connexion">
    </form>
    <?php if(isset($erreur)){ echo $erreur; } ?>
<?php }else {?>
    <form class="form-inline" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <div class="form-group"><label class="pseudo"><?php echo $_SESSION['ndc']; ?></label></div>
        <div class="form-group"><input style="margin-left: -5px;" type="submit" name="deconnexion" class="btn btn-primary deco" value="Me déconnecter"/></div>
    </form>
<?php } ?>
