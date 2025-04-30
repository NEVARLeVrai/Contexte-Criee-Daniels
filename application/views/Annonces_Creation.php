<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
?>

<body>
    <section id="connexion_et_inscription" class="connexion_et_inscription">
        <h2>Annonces</h2>
		<br>
        <p>Créer mon annonce :</p>
		<br>
        
        <form method="POST" action="<?php echo site_url('welcome/traitement_annonces'); ?>" enctype="multipart/form-data">


            <label for="idLot">Lot n° :</label><br>
            <select id="idLot" name="idLot" required onchange="updateLotInfo(this.value)"><br>
            <?php	
            include "application/config/database.php";    
                $selectLots = "SELECT l.idLot, l.idBateau, l.datePeche, l.prixDepart, l.prixPlancher, l.prixEncheresMax, e.nomCommun 
                FROM LOT l 
                INNER JOIN ESPECE e 
                WHERE l.idEspece = e.idEspece 
                ORDER BY l.idLot";         

                $stmt = $pdo->prepare($selectLots);
                $stmt->execute();
                $rows = $stmt->fetchAll();        

                foreach ($rows as $row) {
                    echo '<option value="'.$row['idLot'].'" 
                            data-bateau="'.$row['idBateau'].'"
                            data-date-peche="'.$row['datePeche'].'"
                            data-prix-depart="'.$row['prixDepart'].'"
                            data-prix-plancher="'.$row['prixPlancher'].'"
                            data-prix-max="'.$row['prixEncheresMax'].'"
                            data-date-enchere="'.$row['DateEnchere'].'">'.
                            $row['idLot'].' : '.$row['idBateau'] .' - '.$row['nomCommun'].
                         '</option>';
                }                
            ?>
            </select><br><br>

            <label for="idBateau">Bateau :</label><br>
            <input id="idBateau" name="idBateau" required readonly>
            <label for="datePeche">Date de pêche:</label><br>
            <input type="date" id="datePeche" name="datePeche" readonly required><br>



            <div id="lotInfo">
                <p>Prix de départ du lot : <span id="prixDepart">-</span> €</p>
                <p>Prix plancher du lot : <span id="prixPlancher">-</span> €</p>
                <p>Prix maximum du lot : <span id="prixMax">-</span> €</p>
            </div><br>

            <label for="prixEnchere">Prix initial de l'enchère :</label><br>
            <input type="number" min="1" step="any" id="prixEnchere" name="prixEnchere" required><br>

            <label for="DateEnchere">Date de l'enchère :</label><br>
            <input type="datetime-local" id="DateEnchere" name="DateEnchere" required><br>

            <label for="titreAnnonce">Titre :</label><br>
            <input type="text" id="titreAnnonce" name="titreAnnonce" required><br>

            <label for="dateFinEnchere">Date limite d'enchère :</label><br>
            <input type="datetime-local" id="dateFinEnchere" name="dateFinEnchere" required><br>

            <label for="image">Ajouter une image :</label><br>
            <input type="file" accept="image/*" id="image" name="image" required><br>

			<br>
            <button type="submit" class="btn">Valider</button>
        </form>      

        <script>
        function updateLotInfo(lotId) {
            const select = document.getElementById('idLot');
            const option = select.options[select.selectedIndex];
            
            // Mettre à jour les informations du lot
            document.getElementById('prixDepart').textContent = option.dataset.prixDepart;
            document.getElementById('prixPlancher').textContent = option.dataset.prixPlancher;
            document.getElementById('prixMax').textContent = option.dataset.prixMax || 'Non défini';
            
            // Mettre à jour le bateau sélectionné
            document.getElementById('idBateau').value = option.dataset.bateau;
            
            // Mettre à jour la date de pêche
            document.getElementById('datePeche').value = option.dataset.datePeche;
            
            // Mettre à jour le champ prixEnchere avec les validations
            const prixEnchereInput = document.getElementById('prixEnchere');
            const prixDepart = parseFloat(option.dataset.prixDepart);
            const prixPlancher = parseFloat(option.dataset.prixPlancher);
            const prixMax = parseFloat(option.dataset.prixMax);

            // Le prix plancher est le minimum
            prixEnchereInput.min = prixPlancher;
            prixEnchereInput.value = prixPlancher;

            // Définir le prix maximum si disponible
            if (!isNaN(prixMax)) {
                prixEnchereInput.max = prixMax;
            }

            // Ajouter une validation lors de la saisie
            prixEnchereInput.oninput = function() {
                const valeur = parseFloat(this.value);
                if (valeur < prixPlancher) {
                    this.setCustomValidity(`Le prix initial doit être d'au moins ${prixPlancher}€ (prix plancher)`);
                } else if (!isNaN(prixMax) && valeur > prixMax) {
                    this.setCustomValidity(`Le prix initial ne peut pas dépasser ${prixMax}€`);
                } else {
                    this.setCustomValidity('');
                }
            };

            // Pré-remplir la date d'enchère avec celle du lot si elle existe
            if (option.dataset.dateEnchere) {
                document.getElementById('DateEnchere').value = option.dataset.dateEnchere.slice(0, 16); // Format pour datetime-local
            }
        }

        // Appeler updateLotInfo au chargement de la page pour initialiser les valeurs
        window.onload = function() {
            const select = document.getElementById('idLot');
            if (select.value) {
                updateLotInfo(select.value);
            }
        }
        </script>
    </section>
</body>
