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
	$query = 'SELECT DAYOFWEEK(date) as jour, DATE_FORMAT(date, \'%d/%m/%Y %H:%i\') AS datef FROM event WHERE date > NOW() ORDER BY date ASC';
	$donnees = $bdd->query($query)->fetch();
?>

<!DOCTYPE html>
<html>
	<?php include('includes/head.php'); ?>
	<body>
		<?php include('includes/menu.php'); ?>
		<div class="container corps-page">
			<div class="row info-message">
				<div class="col-xs-12">
					<div class="alert alert-info">
						Bonjour à tous,<br/><br/>

						Comme vous l'aurez remarqué, une mise à jour a eu lieu ce vendredi 16/09/2016.<br/>

						La fonction principale de cette mise à jour est la messagerie, disponible après s'être connecté, depuis le menu principal.
						Cette fonctionnalité étant en bêta, je vous serais extrêmement reconnaissant de bien vouloir me communiquer les éventuels bugs auxquels vous aurez fait face, via le formulaire prévu à cet effet en bas de la page.<br/><br/>

						Merci à toutes & à tous pour votre aide.<br/><br/>

						Leix.
					</div>
				</div>
			</div>
			<div class="row">
				<?php include('php/tableau-event.php'); ?>
			</div>
		</div>
	</body>
	<?php include('includes/footer.php'); ?>
</html>
