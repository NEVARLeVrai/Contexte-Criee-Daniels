<html>
	<body>
		<section id="connexion_et_inscription" class="connexion_et_inscription"> 
			<h2>Déconnexion</h2>
			<?php echo validation_errors(); 
			// Déconnecter la session existante
			unset($_SESSION["identifiant"]);
			?>	
			<p>Compte déconnecté avec succès. Merci de votre visite.</p><br>
			<form  method="POST" action="<?php echo site_url('welcome/contenu/Connexion'); ?>">
				<button type="submit" class="btn">Se reconnecter à mon compte</button>
			</form>
		</section>
	</body>
</html>