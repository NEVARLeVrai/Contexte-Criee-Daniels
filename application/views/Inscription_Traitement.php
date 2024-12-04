<html>
	<body>
		<?php
			include "application/config/database.php";

			// Inclure les fonctions de hash
			function crypterMotDePasse($motDePasse)
			{
				$options = ['cost' => 12]; // Plus le coût est élevé, plus le hachage est sécurisé
				return password_hash($motDePasse, PASSWORD_BCRYPT, $options);
			}

			// Vérifier si le formulaire d'inscription a été soumis
			if ($_SERVER["REQUEST_METHOD"] == "POST") { 
            	// Récupérer les valeurs des champs du formulaire
				$idCompte = $_POST["idCompte"]; 
				$mdpCompte = $_POST["mdpCompte"]; 

				if (empty($idCompte) || empty($mdpCompte)) // si id OU mdp est vide (rempli par des espaces)
				{ 
					echo "<section id='connexion' class='connexion'>
						Veuillez remplir tous les champs.
						<form>
							<a href='" . site_url('welcome/contenu/Inscription') . "'>
								<button type='submit' class='btn'>Retour</button>
							</a>
						</form>
					</section>";
				} 
				else 
				{
					//Vérifier si l'identifiant est déjà utilisé
					$selectIdVerif = "SELECT idCompte FROM COMPTE WHERE idCompte = :idCompte";
					$stmt = $pdo->prepare($selectIdVerif);
					$stmt->bindParam(':idCompte', $idCompte, PDO::PARAM_STR);
					$stmt->execute();
					$rows = $stmt->fetchAll();
					//Vérifier si l'identifiant est déjà utilisé
					if (count($rows) > 0) 
					{
						echo "<section id='connexion_et_inscription' class='connexion_et_inscription'>
							L'identifiant est déjà utilisé. 
							<br>
							Veuillez en choisir un autre.
							<form>
								<a href='" . site_url('welcome/contenu/Inscription') . "'>
									<button type='button' class='btn'>Retour</button>
								</a>
							</form>
						</section>";
					}
					else
					{
						// Hash le mot de passe avant de l'insérer dans la base de données
						$mdpCrypte = crypterMotDePasse($mdpCompte);
						// Insérer le nouvel utilisateur dans la base de données
						$ajouteUtilisateur = "INSERT INTO COMPTE (idCompte, mdpCompte) VALUES (:idCompte, :mdpCompte)";
						$stmt = $pdo->prepare($ajouteUtilisateur);
						$stmt->bindParam(':idCompte', $idCompte, PDO::PARAM_STR);
						$stmt->bindParam(':mdpCompte', $mdpCrypte, PDO::PARAM_STR);
					
						if ($stmt->execute())
						{
							echo "<section id='connexion' class='connexion'>
								<h2>Inscription réussie avec l'id ".htmlspecialchars($idCompte)."</h2>
								<br>
								<form>
									<a href='" . site_url('welcome/contenu/Connexion') . "'>
										<button type='button' class='btn'>Se connecter</button>
									</a>
								</form>
							</section>";
						} 
						else 
						{
							echo "<section id='connexion_et_inscription' class='connexion_et_inscription'>
								Une erreur s'est produite lors de l'inscription. 
								<br>
								Veuillez réessayer.
								<form>
									<a href='" . site_url('welcome/contenu/Inscription') . "'>
										<button type='submit' class='btn'>Retour</button>
									</a>
								</form>
							</section>";
						}
					}	
				}
			}
			$pdo=null; 
		?>
	</body>
</html>
