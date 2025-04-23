<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<body>  
    <section class="Annonces">
        <div class="Annonces-container">
            <div class="Annonces-section">
                <h2><i class="fas fa-bullhorn"></i> Les Annonces de la Criée de Poulgoazec</h2>
            </div>  
            
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

                    if ($row['typeCompte'] === 'acheteur') {
                        // Si l'utilisateur est un acheteur, afficher les annonces
                        $selectAnnonces = "SELECT idImage, idBateau, datePeche, idLot, prixEnchere, heureEnchere, titreAnnonce, idCompteV FROM ANNONCE ORDER BY idLot";
                        $stmt = $pdo->prepare($selectAnnonces);
                        $stmt->execute();
                        $rows = $stmt->fetchAll();
                        
                        if(count($rows) > 0) // on vérifie que le nombre d'éléments dans $rows est supérieur à 0, soit que $rows ne soit pas vide
                        {
                            echo '<table> <!-- table est un tableau -->
                                <thead> <!-- thead est le haut du tableau -->
                                    <tr> <!-- tr est une ligne dans le tableau -->
                                        <!-- th est l en-tete du tableau, le nom des colonnes -->
                                        <th scope="col">Image</th>                                        
                                        <th scope="col">Bateau</th>
                                        <th scope="col">Date de pêche</th>
                                        <th scope="col">Lot n°</th>
                                        <th scope="col">Prix</th> 	
                                        <th scope="col">Heure</th>
                                        <th scope="col">Titre</th>
                                        <th scope="col">Vendeur</th>
                                    </tr>
                                </thead>
                                <tbody> <!-- tbody est le reste du tableau -->';

                            foreach ($rows as $row) 
                            {
                                echo '<td><img src="../../assets/'.$row['idImage'].'"></td>';
                                echo '
                                    <td>'.$row['idBateau'].'</td>
                                    <td>'.$row['datePeche'].'</td>
                                    <td>'.$row['idLot'].'</td>
                                    <td>'.$row['prixEnchere'].'</td>
                                    <td>'.$row['heureEnchere'].'</td>
                                    <td>'.$row['titreAnnonce'].'</td>
                                    <td>'.$row['idCompteV'].'</td>
                                    </tr>';
                            }
                            echo '
                                </tbody>
                            </table>';


                            echo "<p>Vous allez être rediriger pour enchérir
                                  <br><br>
                                  <button type='button' class='btn'>Enchérir</button>";

                        } else {
                            echo "Aucune annonce n'est disponible pour le moment.";
                            // si $rows est vide, cela signifie qu'aucune annonce n'a été trouvée ou n'existe dans la base
                        }
                    } elseif ($row['typeCompte'] === 'vendeur') {
                        // Si l'utilisateur est un vendeur, afficher le bouton pour créer une annonce
                        echo '<form method="POST" action="' . site_url('welcome/contenu/Annonces_Creation') . '">
                            <button class="btn">Créer mon annonce</button>
                        </form>';
                    }
                    
                } else {
                    echo 'Veuillez vous connecter pour voir les annonces ou créer une annonce.
                    <form method="POST" action="' . site_url('welcome/contenu/Connexion') . '">
                        <br><button type="submit" class="btn">Connexion</button>
                    </form>';
                }
                $pdo=null; // on ferme la connexion à la base de données en donnant une valeur vide à $pdo
            ?>
        </div>
    </section> 
</body>