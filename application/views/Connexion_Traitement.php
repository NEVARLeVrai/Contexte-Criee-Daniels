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
						
						// Récupérer le type de compte pour la session
						$selectTypeCompte = "SELECT typeCompte FROM COMPTE WHERE idCompte = :idCompte";
						$stmtType = $pdo->prepare($selectTypeCompte);
						$stmtType->bindParam(':idCompte', $idCompte, PDO::PARAM_STR);
						$stmtType->execute();
						$typeCompte = $stmtType->fetchColumn();
						
						// Vérifier le type d'utilisateur dans les tables spécifiques sans afficher de débogage
						$tables = ['VENDEUR', 'ACHETEUR', 'ADMIN'];
						foreach ($tables as $table) {
							$checkSql = "SELECT COUNT(*) FROM $table WHERE idCompte = :idCompte";
							$checkStmt = $pdo->prepare($checkSql);
							$checkStmt->bindParam(':idCompte', $idCompte, PDO::PARAM_STR);
							$checkStmt->execute();
							$exists = $checkStmt->fetchColumn();
							
							if ($exists > 0 && empty($typeCompte)) {
								// Si le type n'est pas défini dans COMPTE mais existe dans une table spécifique
								if ($table == 'VENDEUR') $typeCompte = 'vendeur';
								elseif ($table == 'ACHETEUR') $typeCompte = 'acheteur';
								elseif ($table == 'ADMIN') $typeCompte = 'admin';
								
								// Mettre à jour le type de compte dans la base de données
								$updateTypeSql = "UPDATE COMPTE SET typeCompte = :typeCompte WHERE idCompte = :idCompte";
								$updateStmt = $pdo->prepare($updateTypeSql);
								$updateStmt->bindParam(':typeCompte', $typeCompte, PDO::PARAM_STR);
								$updateStmt->bindParam(':idCompte', $idCompte, PDO::PARAM_STR);
								$updateStmt->execute();
							}
						}
						
						// Si le type de compte est toujours vide, essayer de le déterminer
						if (empty($typeCompte)) {
							// Vérifier si l'utilisateur existe dans VENDEUR
							$checkVendeur = "SELECT COUNT(*) FROM VENDEUR WHERE idCompte = :idCompte";
							$stmt = $pdo->prepare($checkVendeur);
							$stmt->bindParam(':idCompte', $idCompte, PDO::PARAM_STR);
							$stmt->execute();
							if ($stmt->fetchColumn() > 0) {
								$typeCompte = 'vendeur';
							} else {
								// Vérifier si l'utilisateur existe dans ACHETEUR
								$checkAcheteur = "SELECT COUNT(*) FROM ACHETEUR WHERE idCompte = :idCompte";
								$stmt = $pdo->prepare($checkAcheteur);
								$stmt->bindParam(':idCompte', $idCompte, PDO::PARAM_STR);
								$stmt->execute();
								if ($stmt->fetchColumn() > 0) {
									$typeCompte = 'acheteur';
								} else {
									// Vérifier si l'utilisateur existe dans ADMIN
									$checkAdmin = "SELECT COUNT(*) FROM ADMIN WHERE idCompte = :idCompte";
									$stmt = $pdo->prepare($checkAdmin);
									$stmt->bindParam(':idCompte', $idCompte, PDO::PARAM_STR);
									$stmt->execute();
									if ($stmt->fetchColumn() > 0) {
										$typeCompte = 'admin';
									} else {
										// Type par défaut si on ne trouve pas l'utilisateur dans les tables spécifiques
										$typeCompte = 'inconnu';
									}
								}
							}
						}
						
						// Stocker le type de compte dans la session
						$_SESSION["typeCompte"] = $typeCompte;
						
						echo "
						<section id='connexion_et_inscription' class='connexion_et_inscription'>
							<h2>Connexion réussie.</h2><br>	
							<br><p>									
							Bienvenue ".htmlspecialchars($idCompte)."<br></p><br>
							
							<form action='" . site_url('welcome/contenu/Accueil') . "' method='POST'>
								<input type='hidden' id='idCompte' name='idCompte' value=".htmlspecialchars($idCompte).">
								<button type='submit' class='btn'>Accueil</button>
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
