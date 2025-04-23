<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<body>
    <section id="connexion_et_inscription" class="connexion_et_inscription">
        <h2>Annonces</h2>
		<br>
        <p>Créer mon annonce :</p>
		<br>
        
        <form method="POST" action="<?php echo site_url('welcome/annonces_traitement'); ?>">
            <?php	
                include "application/config/database.php";
            
                $selectBateaux = "SELECT idBateau, immatriculation FROM BATEAU";                
                $stmt = $pdo->prepare($selectBateaux);
                $stmt->execute();
                $rows = $stmt->fetchAll();
            
                if(count($rows) > 0) // on vérifie que le nombre d'éléments dans $rows est supérieur à 0, soit que $rows ne soit pas vide
                {
                    echo '
                    <label for="idBateau">Bateau :</label><br>
                    <select id="idBateau" name="idBateau" required>';
                        foreach ($rows as $row) // boucle pour tous les éléments dans $rows
                        { 
                            echo '<option value='.$row['idBateau'].'>'.$row['idBateau'].' - '.$row['immatriculation'].'</option>';
                        }
                    echo '</select><br><br>';
                    
                } else {
                    echo "Erreur : aucun enregistrement de bateau trouvé";
                    // si $rows est vide, cela signifie qu'aucun bateau n'existe dans la base
                }
                $pdo=null; // on ferme la connexion à la base de données en donnant une valeur vide à $pdo
            ?>

            <label for="datePeche">Date :</label><br>
            <input type="date" id="datePeche" name="datePeche" required><br>

            <label for="prixEnchere">Prix :</label><br>
            <input type="number" min="1" step="any" id="prixEnchere" name="prixEnchere" required><br>

            <label for="heureEnchere">Heure :</label><br>
            <input type="time" id="heureEnchere" name="heureEnchere" required><br>

            <label for="nomAnnonce">Titre :</label><br>
            <input type="text" id="nomAnnonce" name="nomAnnonce" required><br>

			<br>
            <button type="submit" class="btn">Valider</button>
			<button type='reset' class='btn'>Effacer</button> 
        </form>      
    </section>
</body>
