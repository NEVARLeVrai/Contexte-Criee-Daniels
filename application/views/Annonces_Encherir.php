<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<body>
    <section id="connexion_et_inscription" class="connexion_et_inscription">
        <h2>Enchérir sur une annonce</h2>
		<br>
        <p>Veuillez sélectionner l'annonce sur laquelle vous souhaitez enchérir :</p>
		<br>
        
        <form method="POST" action="<?php echo site_url('welcome/traitement_annonces'); ?>">
            <?php	
                include "application/config/database.php";
            
                $selectAnnonces = "SELECT idLot, titreAnnonce, prixEnchere FROM ANNONCE ORDER BY idLot";                
                $stmt = $pdo->prepare($selectAnnonces);
                $stmt->bindParam(':idCompte', $_SESSION['idCompteA'], PDO::PARAM_STR);
                $stmt->execute();
                $rows = $stmt->fetchAll();
            
                if(count($rows) > 0) {
                    echo '
                    <label for="idLot">Sélectionner l\'annonce :</label><br>
                    <select id="idLot" name="idLot" required>';
                        foreach ($rows as $row) {
                            echo '<option value='.$row['idLot'].'>Lot n°'.$row['idLot'].' - '.$row['titreAnnonce'].' (Prix actuel: '.$row['prixEnchere'].'€)</option>';
                        }
                    echo '</select><br><br>';
                    
                } else {
                    echo "Aucune annonce disponible pour enchérir.";
                }
            ?>

            <label for="nouveauPrix">Votre enchère (€) :</label><br>
            <input type="number" min="1" step="any" id="nouveauPrix" name="nouveauPrix" required><br>

			<br>
            <button type="submit" class="btn">Valider l'enchère</button>
			<button type='reset' class='btn'>Effacer</button> 
        </form>      
    </section>
</body>
