<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include "application/config/database.php";
?>

<body>
    <section id="connexion_et_inscription" class="connexion_et_inscription">
        <h2><i class="fas fa-box"></i> Les Lots de la Criée de Poulgoazec</h2>
        <br>
            
        <?php
            // Vérifier si l'utilisateur est connecté
            if (isset($_SESSION['identifiant'])) {
                // Récupérer le type de compte de l'utilisateur
                $idCompte = $_SESSION['identifiant'];
                $selectTypeCompte = "SELECT typeCompte FROM COMPTE WHERE idCompte = :idCompte";
                $stmt = $pdo->prepare($selectTypeCompte);
                $stmt->bindParam(':idCompte', $idCompte, PDO::PARAM_STR);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                // Vérifier si les informations du compte ont été trouvées
                if ($row === false) {
                    echo "<p>Erreur : Impossible de récupérer les informations du compte. L'utilisateur n'existe peut-être plus. Veuillez vous reconnecter.</p>";
                    echo '<form method="POST" action="' . site_url('welcome/contenu/Connexion') . '">
                            <br><button type="submit" class="btn">Se reconnecter</button>
                          </form>';
                } else {
                    // Si les informations sont trouvées, vérifier le type de compte
                    if ($row['typeCompte'] === 'vendeur') {
                        // Afficher le bouton de création de lot pour les vendeurs
                        echo '<form method="POST" action="' . site_url('welcome/contenu/LotsCreation') . '">
                            <button type="submit" class="btn">Créer un lot</button>
                          </form><br>';
                    }

                    // Afficher les lots
                    $selectLots = "SELECT l.idLot, l.idBateau, l.datePeche, e.nomEspece, t.specification, p.libelle as presentation, 
                                 b.tare, q.libelle as qualite, l.poidsBrutLot, l.prixPlancher, l.prixDepart, l.prixEncheresMax, 
                                 l.DateEnchere, l.codeEtat, l.idFacture
                                 FROM LOT l
                                 JOIN ESPECE e ON l.idEspece = e.idEspece
                                 JOIN TAILLE t ON l.idTaille = t.idTaille
                                 JOIN PRESENTATION p ON l.idPresentation = p.idPresentation
                                 JOIN BAC b ON l.idBac = b.idBac
                                 JOIN QUALITE q ON l.idQualite = q.idQualite
                                 ORDER BY l.idLot";
                    
                    $stmt = $pdo->prepare($selectLots);
                    $stmt->execute();
                    $rows = $stmt->fetchAll();
                    
                    if(count($rows) > 0) {
                        echo '<table>
                            <thead>
                                <tr>
                                    <th scope="col">Lot n°</th>
                                    <th scope="col">Bateau</th>
                                    <th scope="col">Date de pêche</th>
                                    <th scope="col">Espèce</th>
                                    <th scope="col">Taille</th>
                                    <th scope="col">Présentation</th>
                                    <th scope="col">Bac</th>
                                    <th scope="col">Qualité</th>
                                    <th scope="col">Poids brut (kg)</th>
                                    <th scope="col">Prix plancher (€)</th>
                                    <th scope="col">Prix départ (€)</th>
                                    <th scope="col">Prix max (€)</th>
                                    <th scope="col">Date enchère</th>
                                    <th scope="col">État</th>
                                    <th scope="col">Facture</th>
                                </tr>
                            </thead>
                            <tbody>';

                        foreach ($rows as $row) {
                            echo '<tr>';
                            echo '<td>'.$row['idLot'].'</td>';
                            echo '<td>'.$row['idBateau'].'</td>';
                            echo '<td>'.$row['datePeche'].'</td>';
                            echo '<td>'.$row['nomEspece'].'</td>';
                            echo '<td>'.$row['specification'].'</td>';
                            echo '<td>'.$row['presentation'].'</td>';
                            echo '<td>'.$row['tare'].' kg</td>';
                            echo '<td>'.$row['qualite'].'</td>';
                            echo '<td>'.$row['poidsBrutLot'].'</td>';
                            echo '<td>'.$row['prixPlancher'].'</td>';
                            echo '<td>'.$row['prixDepart'].'</td>';
                            echo '<td>'.($row['prixEncheresMax'] ?? 'Non défini').'</td>';
                            echo '<td>'.$row['DateEnchere'].'</td>';
                            echo '<td>'.$row['codeEtat'].'</td>';
                            echo '<td>'.$row['idFacture'].'</td>';
                            echo '</tr>';
                        }
                        echo '</tbody></table>';
                    } else {
                        echo "Aucun lot n'est disponible pour le moment.";
                    }
                }
            } else {
                echo 'Veuillez vous connecter pour voir les lots.
                <form method="POST" action="' . site_url('welcome/contenu/Connexion') . '">
                    <br><button type="submit" class="btn">Connexion</button>
                </form>';
            }
            $pdo=null;
        ?>
    </section> 
</body> 