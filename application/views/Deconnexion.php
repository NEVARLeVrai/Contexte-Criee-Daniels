<body>
	<section id="connexion_et_inscription" class="connexion_et_inscription"> 
		<h2>Déconnexion</h2>
		<br>
		<?php echo validation_errors(); 
		// Déconnecter la session existante
		unset($_SESSION["identifiant"]);
		unset($_SESSION["typeCompte"]);  // S'assurer que le type de compte est également supprimé
		// Alternativement, on peut utiliser session_destroy() pour effacer toutes les variables de session
		// session_destroy();
		?>	
		<p>Compte déconnecté avec succès.</p> 
		<p>Merci de votre visite</p><br>
		<form  method="POST" action="<?php echo site_url('welcome/contenu/Connexion'); ?>">
			<button type="submit" class="btn">Se reconnecter</button>
		</form>
	</section>
</body>
