<html>
	<body>
		<section id="accueil" class="accueil">
			<h2>Connectez vous à votre compte :</h2>
			<?php echo validation_errors(); ?>
			<form method="POST" action="<?php echo site_url('welcome/traitement_connexion'); ?>">
				<label for="id">Identifiant :</label>
				<input type="text" id="idCompte" name="idCompte" required><br> 

				<label for="mdp">Mot de passe :</label>
				<input type="password" id="mdpCompte" name="mdpCompte" required><br>
				<br>
				<button type='submit' name='page' class='btn' value='traitement_connexion'>Valider</button> 
				<button type='reset' class='btn'>Effacer</button> 
			</form>
			<form method="POST" action="<?php echo site_url('welcome/contenu/Inscription'); ?>">
				<p>Vous n'avez pas de compte ?</p>	
				<button type="submit" class="btn">Créer un compte</button>
			</form>
		</section>
	</body>
</html>