<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<html>
	<body>
		<?php
			include "application/config/database.php";

			// Définir le fuseau horaire à Paris
			date_default_timezone_set('Europe/Paris');

			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				// Vérifier si c'est une création d'annonce ou une enchère
				if (isset($_POST['idBateau'])) {
					// Traitement de la création d'annonce
					$idBateau = $_POST['idBateau'];
					$datePeche = $_POST['datePeche'];
					$prixEnchere = $_POST['prixEnchere'];
					$heureEnchere = $_POST['heureEnchere'];
					$titreAnnonce = $_POST['nomAnnonce'];
					$idCompteV = $_SESSION['identifiant'];

					// Insérer la nouvelle annonce
					$insertAnnonce = "INSERT INTO ANNONCE (idBateau, datePeche, prixEnchere, heureEnchere, titreAnnonce, idCompteV) 
									VALUES (:idBateau, :datePeche, :prixEnchere, :heureEnchere, :titreAnnonce, :idCompteV)";
					
					$stmt = $pdo->prepare($insertAnnonce);
					$stmt->bindParam(':idBateau', $idBateau, PDO::PARAM_STR);
					$stmt->bindParam(':datePeche', $datePeche, PDO::PARAM_STR);
					$stmt->bindParam(':prixEnchere', $prixEnchere, PDO::PARAM_STR);
					$stmt->bindParam(':heureEnchere', $heureEnchere, PDO::PARAM_STR);
					$stmt->bindParam(':titreAnnonce', $titreAnnonce, PDO::PARAM_STR);
					$stmt->bindParam(':idCompteV', $idCompteV, PDO::PARAM_STR);

					if ($stmt->execute()) {
						echo "<section id='connexion_et_inscription' class='connexion_et_inscription'>
							<h2>Annonce créée avec succès !</h2>
							<br>
							<form>
								<a href='" . site_url('welcome/contenu/Annonces') . "'>
									<button type='button' class='btn'>Retour aux annonces</button>
								</a>
							</form>
						</section>";
					} else {
						echo "<section id='connexion_et_inscription' class='connexion_et_inscription'>
							<h2>Erreur lors de la création de l'annonce</h2>
							<br>
							<form>
								<a href='" . site_url('welcome/contenu/Annonces_Creation') . "'>
									<button type='button' class='btn'>Réessayer</button>
								</a>
							</form>
						</section>";
					}
				} else {
					// Traitement de l'enchère
					$idLot = $_POST['idLot'];
					$nouveauPrix = $_POST['nouveauPrix'];
					$idCompteA = $_SESSION['identifiant'];
					$dateActuelle = date('Y-m-d H:i:s'); // Format datetime pour MySQL

					// Debug
					echo "Debug - idLot: " . $idLot . "<br>";
					echo "Debug - nouveauPrix: " . $nouveauPrix . "<br>";
					echo "Debug - idCompteA: " . $idCompteA . "<br>";
					echo "Debug - dateActuelle: " . $dateActuelle . "<br>";
					echo "Debug - Heure actuelle: " . date('H:i:s') . "<br>";

					// Vérifier si le nouveau prix est supérieur au prix actuel
					$selectPrix = "SELECT prixEnchere, dateDerniereEnchere FROM ANNONCE WHERE idLot = :idLot";
					$stmt = $pdo->prepare($selectPrix);
					$stmt->bindParam(':idLot', $idLot, PDO::PARAM_STR);
					$stmt->execute();
					$result = $stmt->fetch(PDO::FETCH_ASSOC);
					$prixActuel = $result['prixEnchere'];
					$dateDerniereEnchere = $result['dateDerniereEnchere'];

					echo "Debug - prixActuel: " . $prixActuel . "<br>";
					echo "Debug - dateDerniereEnchere actuelle: " . $dateDerniereEnchere . "<br>";

					if ($nouveauPrix > $prixActuel) {
						try {
							// Mettre à jour le prix de l'enchère et les informations de l'enchérisseur
							$updateEnchere = "UPDATE ANNONCE SET prixEnchere = :nouveauPrix, idCompteA = :idCompteA, dateDerniereEnchere = :dateActuelle WHERE idLot = :idLot";
							$stmt = $pdo->prepare($updateEnchere);
							$stmt->bindParam(':nouveauPrix', $nouveauPrix, PDO::PARAM_STR);
							$stmt->bindParam(':idCompteA', $idCompteA, PDO::PARAM_STR);
							$stmt->bindParam(':dateActuelle', $dateActuelle, PDO::PARAM_STR);
							$stmt->bindParam(':idLot', $idLot, PDO::PARAM_STR);

							if ($stmt->execute()) {
								// Vérifier la mise à jour
								$selectVerif = "SELECT dateDerniereEnchere FROM ANNONCE WHERE idLot = :idLot";
								$stmt = $pdo->prepare($selectVerif);
								$stmt->bindParam(':idLot', $idLot, PDO::PARAM_STR);
								$stmt->execute();
								$nouvelleDate = $stmt->fetchColumn();
								
								echo "Debug - Nouvelle dateDerniereEnchere: " . $nouvelleDate . "<br>";
								
								echo "<section id='connexion_et_inscription' class='connexion_et_inscription'>
									<h2>Enchère validée avec succès !</h2>
									<br>
									<form>
										<a href='" . site_url('welcome/contenu/Annonces') . "'>
											<button type='button' class='btn'>Retour aux annonces</button>
										</a>
									</form>
								</section>";
							} else {
								echo "<section id='connexion_et_inscription' class='connexion_et_inscription'>
									<h2>Erreur lors de la validation de l'enchère</h2>
									<p>Erreur SQL: " . implode(", ", $stmt->errorInfo()) . "</p>
									<br>
									<form>
										<a href='" . site_url('welcome/contenu/Annonces_Encherir') . "'>
											<button type='button' class='btn'>Réessayer</button>
										</a>
									</form>
								</section>";
							}
						} catch (PDOException $e) {
							echo "<section id='connexion_et_inscription' class='connexion_et_inscription'>
								<h2>Erreur lors de la validation de l'enchère</h2>
								<p>Exception: " . $e->getMessage() . "</p>
								<br>
								<form>
									<a href='" . site_url('welcome/contenu/Annonces_Encherir') . "'>
										<button type='button' class='btn'>Réessayer</button>
									</a>
								</form>
							</section>";
						}
					} else {
						echo "<section id='connexion_et_inscription' class='connexion_et_inscription'>
							<h2>Le prix proposé doit être supérieur au prix actuel</h2>
							<p>Prix actuel: " . $prixActuel . "€</p>
							<p>Prix proposé: " . $nouveauPrix . "€</p>
							<br>
							<form>
								<a href='" . site_url('welcome/contenu/Annonces_Encherir') . "'>
									<button type='button' class='btn'>Réessayer</button>
								</a>
							</form>
						</section>";
					}
				}
			}
			$pdo = null;
		?>
	</body>
</html>
