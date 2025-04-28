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
		
		// Vérifier si le formulaire a été soumis
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			// Vérifier si nous avons toutes les informations nécessaires
			if ($typeCompte === 'acheteur' && isset($_POST['raisonSocialeEntreprise']) && isset($_POST['locRue']) 
				&& isset($_POST['rue']) && isset($_POST['ville']) && isset($_POST['codePostal']) && isset($_POST['numHabilitation'])) {
				
				// Mettre à jour la table ACHETEUR avec les informations complémentaires
				$updateAcheteur = "UPDATE ACHETEUR SET 
					raisonSocialeEntreprise = :raisonSocialeEntreprise,
					locRue = :locRue,
					rue = :rue,
					ville = :ville,
					codePostal = :codePostal,
					numHabilitation = :numHabilitation
					WHERE idCompte = :idCompte";
				
				$stmtUpdateAcheteur = $pdo->prepare($updateAcheteur);
				$stmtUpdateAcheteur->bindParam(':raisonSocialeEntreprise', $_POST['raisonSocialeEntreprise'], PDO::PARAM_STR);
				$stmtUpdateAcheteur->bindParam(':locRue', $_POST['locRue'], PDO::PARAM_STR);
				$stmtUpdateAcheteur->bindParam(':rue', $_POST['rue'], PDO::PARAM_STR);
				$stmtUpdateAcheteur->bindParam(':ville', $_POST['ville'], PDO::PARAM_STR);
				$stmtUpdateAcheteur->bindParam(':codePostal', $_POST['codePostal'], PDO::PARAM_STR);
				$stmtUpdateAcheteur->bindParam(':numHabilitation', $_POST['numHabilitation'], PDO::PARAM_STR);
				$stmtUpdateAcheteur->bindParam(':idCompte', $idCompte, PDO::PARAM_STR);
				$stmtUpdateAcheteur->execute();
				
			} elseif ($typeCompte === 'vendeur' && isset($_POST['nom']) && isset($_POST['prenom']) 
				&& isset($_POST['locRue']) && isset($_POST['rue']) && isset($_POST['ville']) 
				&& isset($_POST['codePostal']) && isset($_POST['raisonSocialeEntreprise']) 
				&& isset($_POST['numHabilitation'])) {
				
				// Mettre à jour la table VENDEUR avec les informations complémentaires
				$updateVendeur = "UPDATE VENDEUR SET 
					nom = :nom,
					prenom = :prenom,
					locRue = :locRue,
					rue = :rue,
					ville = :ville,
					codePostal = :codePostal,
					raisonSocialeEntreprise = :raisonSocialeEntreprise,
					numHabilitation = :numHabilitation
					WHERE idCompte = :idCompte";
				
				$stmtUpdateVendeur = $pdo->prepare($updateVendeur);
				$stmtUpdateVendeur->bindParam(':nom', $_POST['nom'], PDO::PARAM_STR);
				$stmtUpdateVendeur->bindParam(':prenom', $_POST['prenom'], PDO::PARAM_STR);
				$stmtUpdateVendeur->bindParam(':locRue', $_POST['locRue'], PDO::PARAM_STR);
				$stmtUpdateVendeur->bindParam(':rue', $_POST['rue'], PDO::PARAM_STR);
				$stmtUpdateVendeur->bindParam(':ville', $_POST['ville'], PDO::PARAM_STR);
				$stmtUpdateVendeur->bindParam(':codePostal', $_POST['codePostal'], PDO::PARAM_STR);
				$stmtUpdateVendeur->bindParam(':raisonSocialeEntreprise', $_POST['raisonSocialeEntreprise'], PDO::PARAM_STR);
				$stmtUpdateVendeur->bindParam(':numHabilitation', $_POST['numHabilitation'], PDO::PARAM_STR);
				$stmtUpdateVendeur->bindParam(':idCompte', $idCompte, PDO::PARAM_STR);
				$stmtUpdateVendeur->execute();
			}
		}
		$pdo = null;
	?>
	<section id='connexion_et_inscription' class='connexion_et_inscription'>
		<h2>Inscription réussie</h2>
        <br>
		<p>ID du compte : <?php echo htmlspecialchars($idCompte); ?></p>
		<p>Type de compte : <?php echo htmlspecialchars($typeCompte); ?></p>


        <p>Vos informations complémentaires ont bien été enregistrées.</p>
		<p>Veuillez vous connecter ici</p>
        <br>
		<form>
			<a href='<?php echo site_url('welcome/contenu/Connexion'); ?>'>
				<button type='button' class='btn'>Connexion</button>
			</a>
		</form>
	</section>
</body>
</html>
