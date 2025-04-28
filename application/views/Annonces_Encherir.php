<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<body>
    <section class="connexion_et_inscription">
        <h2>Enchérir</h2>
        <br>
        <p>Choisir une annonce :</p>
        <br>
        
        <form method="POST" action="<?php echo site_url('welcome/traitement_annonces'); ?>">
            <label for="idAnnonce">Annonce :</label><br>
            <select id="idAnnonce" name="idAnnonce" required onchange="updateLotInfo(this.value)">
                <option value="">Sélectionnez une annonce</option>
            <?php	
                include "application/config/database.php";    

                $selectAnnonces = "SELECT DISTINCT a.idImage, a.idBateau, a.datePeche, a.idLot, a.prixEnchere, 
                                             a.DateEnchere, a.titreAnnonce, a.idCompteV, a.idCompteA, 
                                             a.dateDerniereEnchere, a.dateFinEnchere,
                                             l.prixPlancher, l.prixEncheresMax
                                             FROM ANNONCE a
                                             JOIN LOT l ON a.idLot = l.idLot AND a.idBateau = l.idBateau AND a.datePeche = l.datePeche
                                             WHERE a.dateFinEnchere > NOW() OR a.DateEnchere < NOW()
                                             AND a.idCompteV != :idCompte
                                             AND (a.idCompteA IS NULL OR a.idCompteA != :idCompte)
                                             GROUP BY a.idBateau, a.datePeche, a.idLot
                                             ORDER BY a.dateFinEnchere DESC";

                
                $stmt = $pdo->prepare($selectAnnonces);
                $stmt->bindParam(':idCompte', $_SESSION['identifiant'], PDO::PARAM_STR);
                $stmt->execute();
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                
                foreach ($rows as $row) {
                    // On utilise la combinaison idBateau + datePeche + idLot comme identifiant unique
                    $annonceId = $row['idBateau'] . '_' . $row['datePeche'] . '_' . $row['idLot'];
                    echo '<option value="'.$annonceId.'" 
                            data-prix-actuel="'.$row['prixEnchere'].'"
                            data-prix-plancher="'.$row['prixPlancher'].'"
                            data-prix-max="'.$row['prixEncheresMax'].'">
                            Lot '.$row['idLot'].' - Bateau '.$row['idBateau'].' - '.$row['titreAnnonce'].' 
                            (Prix actuel: '.$row['prixEnchere'].'€)
                          </option>';
                }
            ?>
            </select><br><br>

            <div id="lotInfo">
                <p>Prix actuel : <span id="prixActuel">-</span> €</p>
                <p>Prix plancher : <span id="prixPlancher">-</span> €</p>
                <p>Prix maximum : <span id="prixMax">-</span> €</p>
            </div><br>

            <label for="prixEnchere">Votre enchère :</label><br>
            <input type="number" min="1" step="any" id="prixEnchere" name="prixEnchere" required><br>

            <br>
            <button type="submit" class="btn">Valider</button>
            <button type="reset" class="btn">Effacer</button> 
        </form>      

        <script>
        function updateLotInfo(annonceId) {
            const select = document.getElementById('idAnnonce');
            const option = select.options[select.selectedIndex];
            
            document.getElementById('prixActuel').textContent = option.dataset.prixActuel || '-';
            document.getElementById('prixPlancher').textContent = option.dataset.prixPlancher || '-';
            document.getElementById('prixMax').textContent = option.dataset.prixMax || 'Non défini';
            
            // Mettre à jour les limites du champ prixEnchere
            const prixEnchereInput = document.getElementById('prixEnchere');
            if (option.dataset.prixActuel) {
                prixEnchereInput.min = parseFloat(option.dataset.prixActuel) + 0.01;
                prixEnchereInput.value = parseFloat(option.dataset.prixActuel) + 0.01;
            }
            if (option.dataset.prixMax) {
                prixEnchereInput.max = parseFloat(option.dataset.prixMax);
            }
        }

        // Initialiser les informations au chargement
        window.onload = function() {
            const select = document.getElementById('idAnnonce');
            if (select.value) {
                updateLotInfo(select.value);
            }
        }
        </script>
    </section>
</body>