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
             
                $selectAnnonces = "SELECT idBateau, datePeche, idLot, idCompteA, prixEnchere, heureEnchere, nomAnnonce, idCompteV FROM ANNONCE ORDER BY idLot";

                // cette requête SQL va récupérer toutes les informations des annonces entrées dans la base de données			
                
                $stmt = $pdo->prepare($selectAnnonces);
                
                $stmt->execute();
                // prend la requête créée plus haut en paramètre et l'exécute dans la base de données. 

                $rows = $stmt->fetchAll();
            
                if(count($rows) > 0) // on vérifie que le nombre d'éléments dans $rows est supérieur à 0, soit que $rows ne soit pas vide
                {
                    echo '
                        <table> <!-- table est un tableau -->
                            <thead> <!-- thead est le haut du tableau -->
                                <tr> <!-- tr est une ligne dans le tableau -->
                                    <!-- th est l en-tete du tableau, le nom des colonnes -->
                                    <th scope="col">Bateau</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Lot n°</th>
                                    <th scope="col">Acheteur</th>
                                    <th scope="col">Prix</th> 	
                                    <th scope="col">Heure</th>
                                    <th scope="col">Titre</th>
                                    <th scope="col">Vendeur</th>
                                </tr>
                            </thead>
                            <tbody> <!-- tbody est le reste du tableau -->
                        ';

                        foreach ($rows as $row) 
                        {
                            // boucle pour tous les éléments dans $rows
                            echo'<tr>
                                <td>'.$row['idBateau'].'</td>
                                <td>'.$row['datePeche'].'</td>
                                <td>'.$row['idLot'].'</td>
                                <td>'.$row['idCompteA'].'</td>
                                <td>'.$row['prixEnchere'].'</td>
                                <td>'.$row['heureEnchere'].'</td>
                                <td>'.$row['nomAnnonce'].'</td>
                                <td>'.$row['idCompteV']."</td>
                                </tr>";
                        }

                } else {
                    echo "Aucune annonce n'est disponible pour le moment.";
                    // si $rows est vide, cela signifie qu'aucune annonce n'a été trouvée ou n'existe dans la base
                }
                
                echo '
                            </tbody>
                        </table>
                    </section>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    ';
                    
                $pdo=null; // on ferme la connexion à la base de données en donnant une valeur vide à $pdo
            ?>
        </div>
    </section> 
</body>