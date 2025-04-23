<body>
	<?php
           // Inclure le fichier de connexion à la base de données
		include "application/config/database.php";
		// Inclure les fonctions de hash
		function crypterMotDePasse($motDePasse)
		{
			$options = ['cost' => 12]; // Plus le coût est élevé, plus le hachage est sécurisé
			return password_hash($motDePasse, PASSWORD_BCRYPT, $options);
		}
		
		// verifier si le mot de passe correspond avec le hash : retourne true ou false
		function veriferMotDePasse($motDePasse, $motDePasseCrypte)
		{
			return password_verify($motDePasse, $motDePasseCrypte);   
		}
		
		// Récupérer les valeurs des champs du formulaire
		if ($_SERVER["REQUEST_METHOD"] == "POST") 
		{
			// Récupérer les valeurs des champs du formulaire
			$idCompte = $_POST["idCompte"]; 
			$mdpCompte = $_POST["mdpCompte"]; 
			// Vérifier si les champs sont vides
			if (empty($idCompte) || empty($mdpCompte)) // si id OU mdp est vide (rempli par des espaces)
			{ 
				echo "<section id='connexion_et_inscription' class='connexion_et_inscription'>
					Veuillez remplir tous les champs.
					<form action='<?php echo site_url('welcome/contenu/Connexion'); ?>' method='POST'>
						<button type='submit' class='btn'>Retour</button>
					</form>
				</section>";						
			} 
			else
			{
				$selectInfosCompte = "SELECT idCompte, mdpCompte FROM COMPTE WHERE idCompte = :idCompte";
				$stmt = $pdo->prepare($selectInfosCompte);
				$stmt->bindParam(':idCompte', $idCompte, PDO::PARAM_STR);
				$stmt->execute();
				$row = $stmt->fetch(PDO::FETCH_ASSOC);
				if (empty($row))
				{
					echo "<section id='connexion_et_inscription' class='connexion_et_inscription'>
						<p>Aucun utilisateur trouvé avec cet identifiant</p>
						<form action='".site_url('welcome/contenu/Connexion')."' method='POST'>
							<button type='submit' class='btn'>Réessayer la connexion</button>
						</form>
					</section>";
				}
				else
				{
					if (veriferMotDePasse($mdpCompte, $row['mdpCompte']))
					{
						$_SESSION["identifiant"] = $idCompte; // l'identifiant reçoit la valeur de la bdd
						
						echo "
						<section id='connexion_et_inscription' class='connexion_et_inscription'>
							<h2>Connexion réussie.</h2><br>	
							<br><p>									
							Bienvenue ".htmlspecialchars($idCompte)."<br></p><br>
							
							<form action='" . site_url('welcome/contenu/Accueil') . "' method='POST'>
								<input type='hidden' id='idCompte' name='idCompte' value=".htmlspecialchars($idCompte).">
								<button type='submit' class='btn'>Accueil</button>
								<br>
							</form>	
							<br>

							<form action='" . site_url('welcome/contenu/Annonces') . "' method='POST'>
								<input type='hidden' id='idCompte' name='idCompte' value=".htmlspecialchars($idCompte).">
								<button type='submit' class='btn'>Annonces</button>
								
							</form>	
							<br>
							<form action='" . site_url('welcome/contenu/Lots') . "' method='POST'>
								<input type='hidden' id='idCompte' name='idCompte' value=".htmlspecialchars($idCompte).">
								<button type='submit' class='btn'>Lots</button>
							</form>	
						</section>";
					}
					else 
					{
						echo "
						<section id='connexion_et_inscription' class='connexion_et_inscription'>
							<p><h2>Mot de passe incorrect.</p></h2>
							<br>
							<form action='".site_url('welcome/contenu/Connexion')."' method='POST'>
								<button type='submit' class='btn'>Réessayer la connexion</button>
							</form>	
						</section>";
					}
				}
			}
		}
		
		$pdo=null; 
	?>
</body>
