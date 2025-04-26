<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<body>  
<section id="connexion_et_inscription" class="connexion_et_inscription">
            <h2><i class="fas fa-bullhorn"></i> Les Annonces de la Criée de Poulgoazec</h2>
            
            <?php
                include "application/config/database.php";

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
                        // Si les informations sont trouvées, continuer la logique existante
                        if ($row['typeCompte'] === 'acheteur') {
                            // Si l'utilisateur est un acheteur, afficher les annonces
                            $selectAnnonces = "SELECT DISTINCT a.idImage, a.idBateau, a.datePeche, a.idLot, a.prixEnchere, 
                                             a.DateEnchere, a.titreAnnonce, a.idCompteV, a.idCompteA, 
                                             a.dateDerniereEnchere, a.dateFinEnchere,
                                             l.prixPlancher, l.prixEncheresMax
                                             FROM ANNONCE a
                                             JOIN LOT l ON a.idLot = l.idLot AND a.idBateau = l.idBateau AND a.datePeche = l.datePeche
                                             GROUP BY a.idBateau, a.datePeche, a.idLot
                                             ORDER BY a.dateFinEnchere DESC";
                            $stmt = $pdo->prepare($selectAnnonces);
                            $stmt->execute();
                            $rows = $stmt->fetchAll();
                            
                            if(count($rows) > 0) {
                                echo '<table>
                                    <thead>
                                        <tr>
                                            <th scope="col">Image</th>                                        
                                            <th scope="col">Bateau</th>
                                            <th scope="col">Date de pêche</th>
                                            <th scope="col">Lot n°</th>
                                            <th scope="col">Prix actuel</th>
                                            <th scope="col">Prix plancher</th>
                                            <th scope="col">Prix max</th>
                                            <th scope="col">Date Enchère</th>
                                            <th scope="col">Titre</th>
                                            <th scope="col">Vendeur</th>
                                            <th scope="col">Dernier enchérisseur</th>
                                            <th scope="col">Date dernière enchère</th>
                                            <th scope="col">Date limite</th>
                                        </tr>
                                    </thead>
                                    <tbody>';

                                foreach ($rows as $row) {
                                    echo '<tr>';
                                    echo '<td><img src="../../assets/'.$row['idImage'].'"></td>';
                                    echo '<td>'.$row['idBateau'].'</td>';
                                    echo '<td>'.$row['datePeche'].'</td>';
                                    echo '<td>'.$row['idLot'].'</td>';
                                    echo '<td>'.$row['prixEnchere'].' €</td>';
                                    echo '<td>'.$row['prixPlancher'].' €</td>';
                                    echo '<td>'.($row['prixEncheresMax'] ?? 'Non défini').' €</td>';
                                    echo '<td>'.$row['DateEnchere'].'</td>';
                                    echo '<td>'.$row['titreAnnonce'].'</td>';
                                    echo '<td>'.$row['idCompteV'].'</td>';
                                    echo '<td>'.($row['idCompteA'] ?? 'Aucun').'</td>';
                                    echo '<td>'.($row['dateDerniereEnchere'] ?? 'Aucune').'</td>';
                                    echo '<td>'.($row['dateFinEnchere'] ?? 'Non définie').'</td>';
                                    echo '</tr>';
                                }
                                echo '</tbody></table>';

                                echo '<p>Vous allez être rediriger pour enchérir
                                      <br><br>
                                        <form method="POST" action="' . site_url('welcome/contenu/Annonces_Encherir') . '">
                                            <button type="submit" class="btn">Enchérir</button>
                                        </form>
                                    </p>';

                            } else {
                                echo "Aucune annonce n'est disponible pour le moment.";
                            }
                        } elseif ($row['typeCompte'] === 'vendeur') {
                            // Si l'utilisateur est un vendeur, afficher les annonces
                            $selectAnnonces = "SELECT DISTINCT a.idImage, a.idBateau, a.datePeche, a.idLot, a.prixEnchere, 
                                             a.DateEnchere, a.titreAnnonce, a.idCompteV, a.idCompteA, 
                                             a.dateDerniereEnchere, a.dateFinEnchere,
                                             l.prixPlancher, l.prixEncheresMax
                                             FROM ANNONCE a
                                             JOIN LOT l ON a.idLot = l.idLot AND a.idBateau = l.idBateau AND a.datePeche = l.datePeche
                                             WHERE a.idCompteV = :idCompte
                                             GROUP BY a.idBateau, a.datePeche, a.idLot
                                             ORDER BY a.dateFinEnchere DESC";
                            $stmt = $pdo->prepare($selectAnnonces);
                            $stmt->bindParam(':idCompte', $idCompte, PDO::PARAM_STR);
                            $stmt->execute();
                            $rows = $stmt->fetchAll();
                            
                            if(count($rows) > 0) {
                                echo '<br>
                                <form method="POST" action="' . site_url('welcome/contenu/Annonces_Creation') . '">
                                <button type="submit" class="btn">Créer une annonce</button>
                                </form>';

                                echo '<table>
                                    <thead>
                                        <tr>
                                            <th scope="col">Image</th>                                        
                                            <th scope="col">Bateau</th>
                                            <th scope="col">Date de pêche</th>
                                            <th scope="col">Lot n°</th>
                                            <th scope="col">Prix actuel</th>
                                            <th scope="col">Prix plancher</th>
                                            <th scope="col">Prix max</th>
                                            <th scope="col">Date Enchère</th>
                                            <th scope="col">Titre</th>
                                            <th scope="col">Vendeur</th>
                                            <th scope="col">Dernier enchérisseur</th>
                                            <th scope="col">Date dernière enchère</th>
                                            <th scope="col">Date limite</th>
                                        </tr>
                                    </thead>
                                    <tbody>';

                                foreach ($rows as $row) {
                                    echo '<tr>';
                                    echo '<td><img src="../../assets/'.$row['idImage'].'"></td>';
                                    echo '<td>'.$row['idBateau'].'</td>';
                                    echo '<td>'.$row['datePeche'].'</td>';
                                    echo '<td>'.$row['idLot'].'</td>';
                                    echo '<td>'.$row['prixEnchere'].' €</td>';
                                    echo '<td>'.$row['prixPlancher'].' €</td>';
                                    echo '<td>'.($row['prixEncheresMax'] ?? 'Non défini').' €</td>';
                                    echo '<td>'.$row['DateEnchere'].'</td>';
                                    echo '<td>'.$row['titreAnnonce'].'</td>';
                                    echo '<td>'.$row['idCompteV'].'</td>';
                                    echo '<td>'.($row['idCompteA'] ?? 'Aucun').'</td>';
                                    echo '<td>'.($row['dateDerniereEnchere'] ?? 'Aucune').'</td>';
                                    echo '<td>'.($row['dateFinEnchere'] ?? 'Non définie').'</td>';
                                    echo '</tr>';
                                }
                                echo '</tbody></table>';
                            } else {
                                echo "Aucune annonce n'est disponible pour le moment.";
                            }
                        }
                    }
                } else {
                    echo '<br>
                    Veuillez vous connecter pour voir les annonces ou créer une annonce.
                    <form method="POST" action="' . site_url('welcome/contenu/Connexion') . '">
                        <br><button type="submit" class="btn">Connexion</button>
                    </form>';
                }
                $pdo=null;
            ?>
    </section> 
</body>