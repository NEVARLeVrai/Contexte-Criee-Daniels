<html>
	<body>
		<section id="connexion_et_inscription" class="connexion_et_inscription">
			<h2>Inscription</h2>
			<p>Remplissez le formulaire ci-dessous pour créer votre compte :</p>
			<?php echo validation_errors(); ?>
			<form method='POST' action="<?php echo site_url('welcome/traitement_inscription'); ?>">
				<label for="idCompte">Identifiant :</label>
				<input type="text" id="idCompte" name="idCompte" required><br> 
				
				<label for="mdpCompte">Mot de passe :</label>
				<input type="password" id="mdpCompte" name="mdpCompte" required><br>
				<br>
				<button type='submit' class='btn'>Valider</button> 
				<button type='reset' class='btn'>Effacer</button> 
				<br>
			</form>
			<form  method="POST" action="<?php echo site_url('welcome/contenu/connexion'); ?>">
				<p>Vous avez un compte ?</p>
				<button type="submit" class="btn">Se connecter</button>
			</form>
		</section>
	</body>
</html>