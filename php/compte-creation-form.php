<?php
    //Si c'est le formulaire d'inscription
    if(isset($_POST['valider'])) {
        $ndc = htmlspecialchars($_POST['identifiant']);
        $mdp1 = htmlspecialchars($_POST['mdp1']);
        $mdp2 = htmlspecialchars($_POST['mdp2']);


        if (preg_match("/[A-Za-z].[0-9]{4}/", $ndc)) {
            if(strlen($mdp1) < 8){
                $user_error = 'La taille du mot de passe doit être supérieure ou égale à 8 caractères';
            }else {
                if (strcmp($mdp1, $mdp2)) {
                    $user_error = 'Mot de passe différent de sa confirmation';
                } else {
                    $query = 'SELECT * FROM joueur WHERE ndc="' . $ndc . '";';
                    $arr = $bdd->query($query)->fetch();

                    if (!empty($arr)) {
                        $user_error = 'Ce nom de compte existe déjà';
                    } else {
                        $query = 'INSERT INTO joueur(ndc, mdp) VALUES("' . $ndc . '", "' . sha1($mdp1) . '");';
                        $rowCount = $bdd->exec($query);

                        if ($rowCount > 0) {
                            $user_success = 'Compte créé';
                            
                            $historique = $ndc." a créé un compte";
                            ajouterHistorique($historique, $bdd);
                        }else
                            $user_error = 'Erreur lors de la création du compte';
                    }
                }
            }
        } else {
            $user_error = 'Nom de compte invalide. Vous trouverez votre nom de compte en haut à gauche de la fenetre d\'amis de Guild wars 2';
        }
    }
?>

<div class="row inscription-page">
    <?php if(isset($user_error)){?>
        <div class="col-xs-12 alert alert-danger">
            <?php echo $user_error; ?>
        </div>
    <?php } ?>
    <?php if(isset($user_success)){?>
        <div class="col-xs-12 alert alert-success">
            <?php echo $user_success; ?>
        </div>
    <?php } ?>
    <form class="creation-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <div class="col-md-6 col-xs-12">
            <label>Nom de compte</label>
        </div>
        <div class="col-md-6 col-xs-12">
            <input name="identifiant" type="text" placeholder="Ex: Abyss.5903" value="<?php if(isset($_POST['identifiant'])) echo $_POST['identifiant']; ?>" required>
        </div>
        <div class="col-md-6 col-xs-12">
            <label>Mot de passe</label>
        </div>
        <div class="col-md-6 col-xs-12">
            <input name="mdp1" type="password" required>
        </div>
        <div class="col-md-6 col-xs-12">
            <label>Confirmation</label>
        </div>
        <div class="col-md-6 col-xs-12">
            <input name="mdp2" type="password" required>
        </div>
        <div class="col-xs-12">
            <input class="btn btn-primary pull-right" name="valider" type="submit" value="Créer">
        </div>
    </form>
</div>
