<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<title>Messagerie PHP</title>
	<link rel="stylesheet" href="style/style.css">
</head>
<body>

	<div class="background-color"></div> 
	<div class="background-img"></div>

	<!--Formulaire d'inscritpion à la messagerie-->
	<div id="inscription-page">
		<div id="inscription">
			<div class="background-inscription"></div>
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
				<div class="pseudo-box"><div><label for="pseudo">Pseudo: </label></div>
				<div><input type="text" id="pseudo" name="pseudo" placeholder="Votre pseudo"></div></div>
				<div class="pass-box"><div><label for="password">Mot de passe: </label></div>
				<div><input type="text" id="password" name="password" placeholder="Votre mot de passe"></div></div>
				<div class="pass-box"><div><label for="passwordConfirm">Confirmer le mot de passe: </label></div>
				<div><input type="text" id="passwordConfirm" name="passwordConfirm" placeholder="Confirmer votre mot de passe"></div></div>
				<div class="button-box"><button type="submit" name="submit_ins" id="submit_ins">S'inscrire</button></div>
			</form>
			<div id="message-inscription">
				<span>
				<!--Message d'erreur-->
				</span>
			</div>
		</div>
	</div>
	
	<div id="connexion">
		<div class="background-connexion"></div>
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
			<div class="pseudo-box"><div><label for="pseudo_con">Pseudo</label></div><div><input type="text" id="pseudo_con" name="pseudo_con" placeholder="Votre pseudo"></div></div>
			<div class="pass-box"><div><label for="password_con">Mot de passe</label></div><div><input type="text" id="password_con" name="password_con" placeholder="Votre mot de passe"></div></div>
			<div class="button-box"><button type="submit" name="submit_con" id="submit_con">Connexion</button></div>
		</form>
		<div class="inscritpion-start">
			<span id="go-inscription">Inscrivez-vous!</span>
		</div>
		<div id="message-retour" class="message-good">
			<span></span>
		</div>
	</div>

	<script src="script/js/jQuery3.1.js"></script>
	<script src="script/js/script.js"></script>
</body>
</html>