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
					$idImage = $_POST["idImage"];
					$idBateau = $_POST['idBateau'];
					$datePeche = $_POST['datePeche'];
					$idLot = $_POST["idLot"]; //
					$prixEnchere = $_POST['prixEnchere'];
					$heureEnchere = $_POST['heureEnchere'];
					$titreAnnonce = $_POST['titreAnnonce'];
					$idCompte = $_POST['idCompte'];

					// Insérer la nouvelle annonce
					$insertAnnonce = "INSERT INTO ANNONCE (idImage, idBateau, datePeche, idLot, prixEnchere, heureEnchere, titreAnnonce, idCompteV) 
					VALUES (:idImage, :idBateau, :datePeche, :idLot, :prixEnchere, :heureEnchere, :titreAnnonce, :idCompteV)";
				
					$stmt = $pdo->prepare($insertAnnonce);
					$stmt->bindParam(':idImage', $idImage, PDO::PARAM_STR);
					$stmt->bindParam(':idBateau', $idBateau, PDO::PARAM_STR);
					$stmt->bindParam(':datePeche', $datePeche, PDO::PARAM_STR);
					$stmt->bindParam(':idLot', $idLot, PDO::PARAM_STR);
					$stmt->bindParam(':prixEnchere', $prixEnchere, PDO::PARAM_STR);
					$stmt->bindParam(':heureEnchere', $heureEnchere, PDO::PARAM_STR);
					$stmt->bindParam(':titreAnnonce', $titreAnnonce, PDO::PARAM_STR);
					$stmt->bindParam(':idCompte', $idCompte, PDO::PARAM_STR);

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
					$idCompteA = $_SESSION['idCompteA'];
					$dateActuelle = date('Y-m-d H:i:s'); // Format datetime pour MySQL

					// Debug
					echo "Debug - idLot: " . $idLot . "<br>";
					echo "Debug - nouveauPrix: " . $nouveauPrix . "<br>";
					echo "Debug - idCompteA: " . $idCompteA . "<br>";
					echo "Debug - dateActuelle: " . $dateActuelle . "<br>";
					echo "Debug - Heure actuelle: " . date('H:i:s') . "<br>";

					// Vérifier si le nouveau prix est supérieur au prix actuel et si la date limite n'est pas dépassée
					$selectAnnonce = "SELECT prixEnchere, dateDerniereEnchere, dateFinEnchere FROM ANNONCE WHERE idLot = :idLot";
					$stmt = $pdo->prepare($selectAnnonce);
					$stmt->bindParam(':idLot', $idLot, PDO::PARAM_STR);
					$stmt->execute();
					$result = $stmt->fetch(PDO::FETCH_ASSOC);
					$prixActuel = $result['prixEnchere'];
					$dateDerniereEnchere = $result['dateDerniereEnchere'];
					$dateFinEnchere = $result['dateFinEnchere'];

					echo "Debug - prixActuel: " . $prixActuel . "<br>";
					echo "Debug - dateDerniereEnchere actuelle: " . $dateDerniereEnchere . "<br>";
					echo "Debug - dateFinEnchere: " . $dateFinEnchere . "<br>";

					// Vérifier si la date limite est dépassée
					if (strtotime($dateActuelle) > strtotime($dateFinEnchere)) {
						echo "<section id='connexion_et_inscription' class='connexion_et_inscription'>
							<h2>Enchère impossible</h2>
							<p>La date limite pour enchérir est dépassée.</p>
							<p>Date limite : " . $dateFinEnchere . "</p>
							<p>Date actuelle : " . $dateActuelle . "</p>
							<br>
							<form>
								<a href='" . site_url('welcome/contenu/Annonces') . "'>
									<button type='button' class='btn'>Retour aux annonces</button>
								</a>
							</form>
						</section>";
					} elseif ($nouveauPrix > $prixActuel) {
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
