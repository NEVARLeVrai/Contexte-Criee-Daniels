<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// Définir le fuseau horaire à Paris
date_default_timezone_set('Europe/Paris');
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
					$idLot = $_POST["idLot"];
					$prixEnchere = $_POST['prixEnchere'];
					$DateEnchere = $_POST['DateEnchere'];
					$titreAnnonce = $_POST['titreAnnonce'];
					$dateFinEnchere = $_POST['dateFinEnchere'];
					$idCompte = $_SESSION['identifiant'];

					// Vérifier si une annonce existe déjà pour ce lot
					$checkAnnonce = "SELECT COUNT(*) FROM ANNONCE WHERE idLot = :idLot";
					$stmt = $pdo->prepare($checkAnnonce);
					$stmt->bindParam(':idLot', $idLot, PDO::PARAM_INT);
					$stmt->execute();
					$annonceExists = $stmt->fetchColumn();

					if ($annonceExists > 0) {
						echo "<section id='connexion_et_inscription' class='connexion_et_inscription'>
							<h2>Erreur lors de la création de l'annonce</h2>
							<p>Une annonce existe déjà pour ce lot.</p>
							<br>
							<form>
								<a href='" . site_url('welcome/contenu/Annonces_Creation') . "'>
									<button type='button' class='btn'>Réessayer</button>
								</a>
							</form>
						</section>";
						exit;
					}

					// Vérifier si le lot existe dans la table PECHE
					$checkPeche = "SELECT COUNT(*) FROM PECHE WHERE idBateau = :idBateau AND datePeche = :datePeche";
					$stmt = $pdo->prepare($checkPeche);
					$stmt->bindParam(':idBateau', $idBateau, PDO::PARAM_STR);
					$stmt->bindParam(':datePeche', $datePeche, PDO::PARAM_STR);
					$stmt->execute();
					$pecheExists = $stmt->fetchColumn();

					if ($pecheExists == 0) {
						echo "<section id='connexion_et_inscription' class='connexion_et_inscription'>
							<h2>Erreur lors de la création de l'annonce</h2>
							<p>Le lot n'existe pas dans la table PECHE.</p>
							<br>
							<form>
								<a href='" . site_url('welcome/contenu/Annonces_Creation') . "'>
									<button type='button' class='btn'>Réessayer</button>
								</a>
							</form>
						</section>";
						exit;
					}

					// Traitement de l'image
					$target_dir = "assets/imgE/";
					$imageFileType = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
					$newFileName = uniqid() . '.' . $imageFileType;
					$target_file = $target_dir . $newFileName;
					$uploadOk = 1;

					// Vérifier si le fichier est une image
					$check = getimagesize($_FILES["image"]["tmp_name"]);
					if($check === false) {
						echo "Le fichier n'est pas une image.";
						$uploadOk = 0;
					}

					// Vérifier la taille du fichier
					if ($_FILES["image"]["size"] > 5000000) {
						echo "Désolé, votre fichier est trop volumineux.";
						$uploadOk = 0;
					}

					// Autoriser certains formats de fichiers
					if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
						echo "Désolé, seuls les fichiers JPG, JPEG, PNG & GIF sont autorisés.";
						$uploadOk = 0;
					}

					if ($uploadOk == 1) {
						if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
							$idImage = "imgE/" . $newFileName;

							// Insérer la nouvelle annonce
							$insertAnnonce = "INSERT INTO ANNONCE (idImage, idBateau, datePeche, idLot, prixEnchere, dateEnchere, titreAnnonce, idCompteV, dateFinEnchere) 
							VALUES (:idImage, :idBateau, :datePeche, :idLot, :prixEnchere, :dateEnchere, :titreAnnonce, :idCompteV, :dateFinEnchere)";
						
							$stmt = $pdo->prepare($insertAnnonce);
							$stmt->bindParam(':idImage', $idImage, PDO::PARAM_STR);
							$stmt->bindParam(':idBateau', $idBateau, PDO::PARAM_STR);
							$stmt->bindParam(':datePeche', $datePeche, PDO::PARAM_STR);
							$stmt->bindParam(':idLot', $idLot, PDO::PARAM_INT);
							$stmt->bindParam(':prixEnchere', $prixEnchere, PDO::PARAM_STR);
							$stmt->bindParam(':dateEnchere', $DateEnchere, PDO::PARAM_STR);
							$stmt->bindParam(':titreAnnonce', $titreAnnonce, PDO::PARAM_STR);
							$stmt->bindParam(':idCompteV', $idCompte, PDO::PARAM_STR);
							$stmt->bindParam(':dateFinEnchere', $dateFinEnchere, PDO::PARAM_STR);
							
							try { 
								$stmt->execute();
								echo "<section id='connexion_et_inscription' class='connexion_et_inscription'>
									<h2>Annonce créée avec succès !</h2>
									<br>
									<form>
										<a href='" . site_url('welcome/contenu/Annonces') . "'>
											<button type='button' class='btn'>Retour aux annonces</button>
										</a>
									</form>
								</section>";
							} catch (PDOException $e) {
								echo "<section id='connexion_et_inscription' class='connexion_et_inscription'>
									<h2>Erreur lors de la création de l'annonce</h2>
									<p>Erreur SQL: " . $e->getMessage() . "</p>
									<br>
									<form>
										<a href='" . site_url('welcome/contenu/Annonces_Creation') . "'>
											<button type='button' class='btn'>Réessayer</button>
										</a>
									</form>
								</section>";
							}
						} else {
							echo "<section id='connexion_et_inscription' class='connexion_et_inscription'>
								<h2>Erreur lors de l'upload de l'image</h2>
								<br>
								<form>
									<a href='" . site_url('welcome/contenu/Annonces_Creation') . "'>
										<button type='button' class='btn'>Réessayer</button>
									</a>
								</form>
							</section>";
						}
					}
				} else {
					// Traitement de l'enchère
					$annonceId = $_POST['idAnnonce'];
					
					// Extraire les informations de l'identifiant composé
					list($idBateau, $datePeche, $idLot) = explode('_', $annonceId);
					
					$prixEnchere = $_POST['prixEnchere'];
					$idCompteA = $_SESSION['identifiant'];
					$dateActuelle = date('Y-m-d H:i:s'); // Format datetime pour MySQL

					// Vérifier si le nouveau prix est supérieur au prix actuel et si la date limite n'est pas dépassée
					$selectAnnonce = "SELECT prixEnchere, dateDerniereEnchere, dateFinEnchere FROM ANNONCE 
									WHERE idBateau = :idBateau 
									AND datePeche = :datePeche 
									AND idLot = :idLot";
					$stmt = $pdo->prepare($selectAnnonce);
					$stmt->bindParam(':idBateau', $idBateau, PDO::PARAM_STR);
					$stmt->bindParam(':datePeche', $datePeche, PDO::PARAM_STR);
					$stmt->bindParam(':idLot', $idLot, PDO::PARAM_INT);
					$stmt->execute();
					$result = $stmt->fetch(PDO::FETCH_ASSOC);

					// Vérifier si la date limite est dépassée
					if (strtotime($dateActuelle) > strtotime($result['dateFinEnchere'])) {
						echo "<section id='connexion_et_inscription' class='connexion_et_inscription'>
							<h2>Enchère impossible</h2>
							<p>La date limite pour enchérir est dépassée.</p>
							<p>Date limite : " . $result['dateFinEnchere'] . "</p>
							<p>Date actuelle : " . $dateActuelle . "</p>
							<br>
							<form>
								<a href='" . site_url('welcome/contenu/Annonces') . "'>
									<button type='button' class='btn'>Retour aux annonces</button>
								</a>
							</form>
						</section>";
					} elseif ($prixEnchere > $result['prixEnchere']) {
						try {
							// Mettre à jour le prix de l'enchère et les informations de l'enchérisseur
							$updateEnchere = "UPDATE ANNONCE 
										   SET prixEnchere = :prixEnchere, 
											   idCompteA = :idCompteA, 
											   dateDerniereEnchere = :dateActuelle 
										   WHERE idBateau = :idBateau 
										   AND datePeche = :datePeche 
										   AND idLot = :idLot";
							$stmt = $pdo->prepare($updateEnchere);
							$stmt->bindParam(':prixEnchere', $prixEnchere, PDO::PARAM_STR);
							$stmt->bindParam(':idCompteA', $idCompteA, PDO::PARAM_STR);
							$stmt->bindParam(':dateActuelle', $dateActuelle, PDO::PARAM_STR);
							$stmt->bindParam(':idBateau', $idBateau, PDO::PARAM_STR);
							$stmt->bindParam(':datePeche', $datePeche, PDO::PARAM_STR);
							$stmt->bindParam(':idLot', $idLot, PDO::PARAM_INT);

							if ($stmt->execute()) {
								// Vérifier la mise à jour
								$selectVerif = "SELECT dateDerniereEnchere FROM ANNONCE WHERE idLot = :idLot";
								$stmt = $pdo->prepare($selectVerif);
								$stmt->bindParam(':idLot', $idLot, PDO::PARAM_STR);
								$stmt->execute();
								$nouvelleDate = $stmt->fetchColumn();
							
								
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
							<p>Prix actuel: " . $result['prixEnchere'] . "€</p>
							<p>Prix proposé: " . $prixEnchere . "€</p>
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
