<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include "application/config/database.php";

// Définir le fuseau horaire
$pdo->exec("SET time_zone = 'Europe/Paris'");


// Récupérer les enchères gagnées par l'utilisateur connecté
$query = "SELECT DISTINCT a.idImage, a.idBateau, a.datePeche, a.idLot, a.prixEnchere, 
          a.DateEnchere, a.titreAnnonce, a.idCompteV, a.idCompteA, 
          a.dateDerniereEnchere, a.dateFinEnchere,
          l.prixPlancher, l.prixEncheresMax
          FROM ANNONCE a
          JOIN LOT l ON a.idLot = l.idLot AND a.idBateau = l.idBateau AND a.datePeche = l.datePeche
          WHERE CURRENT_TIMESTAMP >= a.dateFinEnchere
          AND a.idCompteV != :idCompte 
          AND a.idCompteA = :idCompte
          GROUP BY a.idBateau, a.datePeche, a.idLot
          ORDER BY a.dateFinEnchere DESC";

$stmt = $pdo->prepare($query);
$stmt->bindParam(':idCompte', $_SESSION['identifiant'], PDO::PARAM_STR);
$stmt->execute();
$annonces = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<body>
    <section id="connexion_et_inscription" class="connexion_et_inscription">
        <h2><i class="fas fa-credit-card"></i> Paiement</h2>
        
        <?php if (empty($annonces)): ?>
            <div class="no-auctions">
                <p>Vous n'avez pas d'enchères gagnées à payer.</p>
            </div>
        <?php else: ?>
            <form method="POST" action="<?php echo site_url('welcome/traitement_paiement'); ?>" class="paiement-form">
                <div class="form-group">
                    <label for="idAnnonce">Sélectionnez l'enchère à payer :</label>
                    <select id="idAnnonce" name="idAnnonce" required onchange="updateAnnonceDetails(this.value)">
                        <option value="">Sélectionnez une enchère</option>
                        <?php foreach ($annonces as $annonce): 
                            $annonceId = $annonce['idBateau'] . '_' . $annonce['datePeche'] . '_' . $annonce['idLot'];
                        ?>
                            <option value="<?php echo $annonceId; ?>" 
                                    data-prix="<?php echo $annonce['prixEnchere']; ?>"
                                    data-titre="<?php echo $annonce['titreAnnonce']; ?>">
                                Lot <?php echo $annonce['idLot']; ?> - Bateau <?php echo $annonce['idBateau']; ?> 
                                (<?php echo number_format($annonce['prixEnchere'], 2); ?> €)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div id="annonceDetails" class="annonce-details" style="display: none;">
                    <h3>Détails de l'enchère gagnante</h3>
                    <p><strong>Titre :</strong> <span id="titreAnnonce"></span></p>
                    <p><strong>Lot :</strong> <span id="lotId"></span></p>
                    <p><strong>Bateau :</strong> <span id="bateauId"></span></p>
                    <p><strong>Date de pêche :</strong> <span id="datePeche"></span></p>
                    <p><strong>Prix final :</strong> <span id="prixFinal"></span> €</p>
                </div>

                <div class="form-group">
                    <label for="cardNumber">Numéro de carte :</label>
                    <input type="text" id="cardNumber" name="cardNumber" pattern="[0-9]{16}" maxlength="16" required placeholder="1234 5678 9012 3456">
                </div>

                <div class="form-group">
                    <label for="expiryDate">Date d'expiration :</label>
                    <input type="text" id="expiryDate" name="expiryDate" pattern="(0[1-9]|1[0-2])\/[0-9]{2}" maxlength="5" required placeholder="MM/AA">
                </div>

                <div class="form-group">
                    <label for="cvv">CVV :</label>
                    <input type="text" id="cvv" name="cvv" pattern="[0-9]{3}" maxlength="3" required placeholder="123">
                </div>

                <div class="form-group">
                    <label for="cardName">Nom sur la carte :</label>
                    <input type="text" id="cardName" name="cardName" required>
                </div>

                <button type="submit" class="btn" id="payButton" disabled>Payer</button>
            </form>

            <script>
            function updateAnnonceDetails(annonceId) {
                const select = document.getElementById('idAnnonce');
                const option = select.options[select.selectedIndex];
                const details = document.getElementById('annonceDetails');
                const payButton = document.getElementById('payButton');

                if (annonceId) {
                    details.style.display = 'block';
                    document.getElementById('titreAnnonce').textContent = option.dataset.titre;
                    document.getElementById('lotId').textContent = annonceId.split('_')[2];
                    document.getElementById('bateauId').textContent = annonceId.split('_')[0];
                    document.getElementById('datePeche').textContent = new Date(annonceId.split('_')[1]).toLocaleDateString('fr-FR');
                    document.getElementById('prixFinal').textContent = parseFloat(option.dataset.prix).toFixed(2);
                    payButton.textContent = 'Payer ' + parseFloat(option.dataset.prix).toFixed(2) + ' €';
                    payButton.disabled = false;
                } else {
                    details.style.display = 'none';
                    payButton.disabled = true;
                }
            }
            </script>
        <?php endif; ?>
    </section>
</body>
