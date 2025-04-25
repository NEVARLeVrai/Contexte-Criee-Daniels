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
            <?php	
                include "application/config/database.php";    

                $selectAnnonces = "SELECT a.idAnnonce, a.titreAnnonce, a.prixEnchere, a.idCompteA, 
                                 l.prixPlancher, l.prixEncheresMax
                                 FROM ANNONCE a
                                 JOIN LOT l ON a.idLot = l.idLot
                                 ORDER BY a.idAnnonce";                
                $stmt = $pdo->prepare($selectAnnonces);
                $stmt->execute();
                $rows = $stmt->fetchAll();        

                foreach ($rows as $row) {
                    echo '<option value="'.$row['idAnnonce'].'" 
                            data-prix-actuel="'.$row['prixEnchere'].'"
                            data-prix-plancher="'.$row['prixPlancher'].'"
                            data-prix-max="'.$row['prixEncheresMax'].'">'.
                            $row['idAnnonce'].' : '.$row['titreAnnonce'].
                         '</option>';
                }
                $pdo=null;
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
            <button type='reset' class='btn'>Effacer</button> 
        </form>      

        <script>
        function updateLotInfo(annonceId) {
            const select = document.getElementById('idAnnonce');
            const option = select.options[select.selectedIndex];
            
            document.getElementById('prixActuel').textContent = option.dataset.prixActuel;
            document.getElementById('prixPlancher').textContent = option.dataset.prixPlancher;
            document.getElementById('prixMax').textContent = option.dataset.prixMax || 'Non défini';
            
            // Mettre à jour les limites du champ prixEnchere
            const prixEnchereInput = document.getElementById('prixEnchere');
            prixEnchereInput.min = parseFloat(option.dataset.prixActuel) + 0.01;
            if (option.dataset.prixMax) {
                prixEnchereInput.max = parseFloat(option.dataset.prixMax);
            }
        }

        // Vérifier le prix lors de la soumission du formulaire
        document.querySelector('form').addEventListener('submit', function(e) {
            const select = document.getElementById('idAnnonce');
            const option = select.options[select.selectedIndex];
            const prixEnchere = parseFloat(document.getElementById('prixEnchere').value);
            const prixMax = parseFloat(option.dataset.prixMax);

            if (prixMax && prixEnchere > prixMax) {
                e.preventDefault();
                alert('Le prix de votre enchère ne peut pas dépasser le prix maximum de ' + prixMax + ' €');
            }
        });
        </script>
    </section>
</body>
