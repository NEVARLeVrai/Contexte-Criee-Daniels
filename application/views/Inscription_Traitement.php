<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Récupérer les valeurs des champs du formulaire
$idCompte = $_POST["idCompte"];
$typeCompte = $_POST["typeCompte"];
?>

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

			// Déclarer la fonction ajouteUtilisateur en dehors des blocs conditionnels
			function ajouteUtilisateur($idCompte, $typeCompte, $pdo)
			{
				if ($typeCompte === 'acheteur') {
					// Insérer dans la table ACHETEUR (seulement l'idCompte)
					$insertAcheteur = "INSERT INTO ACHETEUR (idCompte) VALUES (:idCompte)";
					$stmtAcheteur = $pdo->prepare($insertAcheteur);
					$stmtAcheteur->bindParam(':idCompte', $idCompte, PDO::PARAM_STR);
					$stmtAcheteur->execute();
					
					return true;
				} elseif ($typeCompte === 'vendeur') {
					// Insérer dans la table VENDEUR (seulement l'idCompte)
					$insertVendeur = "INSERT INTO VENDEUR (idCompte) VALUES (:idCompte)";
					$stmtVendeur = $pdo->prepare($insertVendeur);
					$stmtVendeur->bindParam(':idCompte', $idCompte, PDO::PARAM_STR);
					$stmtVendeur->execute();
					
					return true;
				}
				return false;
			}

			// Vérifier si le formulaire d'inscription a été soumis
			if ($_SERVER["REQUEST_METHOD"] == "POST") { 
            	// Récupérer les valeurs des champs du formulaire
				$idCompte = $_POST["idCompte"]; 
				$mdpCompte = $_POST["mdpCompte"]; 
				$typeCompte = $_POST["typeCompte"];

				if (empty($idCompte) || empty($mdpCompte) || empty($typeCompte)) // si id, mdp ou type de compte est vide
				{
					echo "<section id='connexion_et_inscription' class='connexion_et_inscription'>
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
							<h2>L'identifiant est déjà utilisé.</h2>
							<br>
							Veuillez en choisir un autre.
							<br>
							<br>
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
						$ajouteUtilisateur = "INSERT INTO COMPTE (idCompte, mdpCompte, typeCompte) VALUES (:idCompte, :mdpCompte, :typeCompte)";
						$stmt = $pdo->prepare($ajouteUtilisateur);
						$stmt->bindParam(':idCompte', $idCompte, PDO::PARAM_STR);
						$stmt->bindParam(':mdpCompte', $mdpCrypte, PDO::PARAM_STR);
						$stmt->bindParam(':typeCompte', $typeCompte, PDO::PARAM_STR);

						if ($stmt->execute())
						{
							// Insérer l'utilisateur dans la table ACHETEUR ou VENDEUR
							if (ajouteUtilisateur($idCompte, $typeCompte, $pdo)) {
								echo "<section id='connexion_et_inscription' class='connexion_et_inscription'>
									<h2>Inscription réussie avec l'id ".htmlspecialchars($idCompte)."</h2>
									<br>
									<form method='POST' action='" . site_url('welcome/traitement_Compte') . "'>
										<input type='hidden' name='idCompte' value='" . htmlspecialchars($idCompte) . "'>
										<input type='hidden' name='typeCompte' value='" . htmlspecialchars($typeCompte) . "'>";

								if ($typeCompte === 'acheteur') {
									echo "<label for='raisonSocialeEntreprise'>Raison Sociale :</label><br>
										<input type='text' id='raisonSocialeEntreprise' name='raisonSocialeEntreprise' required><br>

										<label for='locRue'>Localisation Rue :</label><br>
										<input type='text' id='locRue' name='locRue' required><br>

										<label for='rue'>Rue :</label><br>
										<input type='text' id='rue' name='rue' required><br>

										<label for='ville'>Ville :</label><br>
										<input type='text' id='ville' name='ville' required><br>

										<label for='codePostal'>Code Postal :</label><br>
										<input type='text' id='codePostal' name='codePostal' required><br>

										<label for='numHabilitation'>Numéro d'Habilitation :</label><br>
										<input type='text' id='numHabilitation' name='numHabilitation' required><br>";
								} elseif ($typeCompte === 'vendeur') {
									echo "<label for='raisonSocialeEntreprise'>Raison Sociale :</label><br>
										<input type='text' id='raisonSocialeEntreprise' name='raisonSocialeEntreprise' required><br>
									
										<label for='nom'>Nom :</label><br>
										<input type='text' id='nom' name='nom' required><br>

										<label for='prenom'>Prénom :</label><br>
										<input type='text' id='prenom' name='prenom' required><br>

										<label for='locRue'>Localisation Rue :</label><br>
										<input type='text' id='locRue' name='locRue' required><br>

										<label for='rue'>Rue :</label><br>
										<input type='text' id='rue' name='rue' required><br>

										<label for='ville'>Ville :</label><br>
										<input type='text' id='ville' name='ville' required><br>

										<label for='codePostal'>Code Postal :</label><br>
										<input type='text' id='codePostal' name='codePostal' required><br>
										
										<label for='numHabilitation'>Numéro d'Habilitation :</label><br>
										<input type='text' id='numHabilitation' name='numHabilitation' required><br>";
								}

								echo "<br>
										<button type='submit' class='btn'>Valider</button>
										<button type='reset' class='btn'>Effacer</button> 
									</form>
								</section>";
							} else {
								echo "<section id='connexion_et_inscription' class='connexion_et_inscription'>
									Une erreur s'est produite lors de l'inscription dans la table " . htmlspecialchars($typeCompte) . ".
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
