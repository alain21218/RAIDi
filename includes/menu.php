<div class="container">
    <div class="row">
        <nav class="navbar navbar-default">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <div class="alert alert-success top-right-fixed msg-notif hidden">
                    Nouveau(x) message(s) de <span id="new-msg-name"></span>
                </div>
                <ul class="nav navbar-nav menu">
                    <li><a href="index.php">Accueil</a></li>
                    <li><a href="repertoire.php">Répertoire</a></li>
                    <?php if(isset($_SESSION['id'])){ ?>
                    <li><a href="mes-perso.php">Mes personnages</a></li>
                    <li><a href="messagerie.php">Messagerie <sup>Bêta</sup></a></li>
                    <li><a class="visible-xs" href="#" id="deconnexion">Me déconnecter</a></li>
                        <?php if($_SESSION['droits'] > 0){?>
                            <li><a href="nouveau-raid.php">Nouveau raid</a></li>
                            <li><a href="historique.php">Historique</a></li>
                    <?php }}else{ ?>
                    <li><a href="creation-compte.php">Créer un compte</a></li>
                        <li class="visible-xs"><a href="connexion.php">Connexion</a></li>
                    <?php } ?>
                </ul>
                <ul class="nav navbar-nav">
                    <li class="connexion-menu hidden-xs"><?php include('php/connexion-form.php'); ?></li>
                </ul>
            </div>
        </nav>
    </div>
</div>
