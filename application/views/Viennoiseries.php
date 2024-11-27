<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<body>
    <?php
    include "application/config/database.php";

    $affiche = "SELECT PRODUIT.photoP, PRODUIT.refP, PRODUIT.designationP, PRODUIT.prixP, PRODUIT.poidsP, PRODUIT.descriptifP, MAX(EXISTER.presence) AS presence, GROUP_CONCAT(ALLERGENE.denominationA SEPARATOR ', ') AS allergenes, EXISTER.trace 
                FROM PRODUIT 
                INNER JOIN EXISTER ON EXISTER.refP = PRODUIT.refP 
                INNER JOIN ALLERGENE ON EXISTER.idA = ALLERGENE.idA 
                WHERE PRODUIT.codeCat = 2
                GROUP BY PRODUIT.photoP, PRODUIT.refP, PRODUIT.designationP, PRODUIT.prixP, PRODUIT.poidsP, PRODUIT.descriptifP, EXISTER.trace
                ORDER BY PRODUIT.refP";

    $resultat = $pdo->query($affiche);
    $rows = $resultat->fetchAll();

    if(count($rows) > 0) {
        echo '
        <section id="viennoiseries" class="viennoiseries">
            <h2>Viennoiseries :</h2>
            <table>
                <thead>
                    <tr>
                        <th scope="col">Photo</th>
                        <th scope="col">Référence</th>
                        <th scope="col">Désignation</th>
                        <th scope="col">Prix</th>
                        <th scope="col">Poids</th>
                        <th scope="col">Descriptif</th>
                        <th scope="col">Allergènes</th>
                    </tr>
                </thead>
                <tbody>';

        foreach ($rows as $row) {
            echo "<tr>";
            echo '<td><img src="../../assets/'.$row['photoP'].'"></td>';
            echo "<td>".$row['refP']."</td>";
            echo "<td>".$row['designationP']."</td>";
            echo "<td>".$row['prixP']."</td>";
            echo "<td>".$row['poidsP']."</td>";
            echo "<td>".$row['descriptifP']."</td>";
            echo "<td>".$row['allergenes']."</td>";
            echo "</tr>";
        }

        echo '</tbody>
            </table>
            </section>';
    } else {
        echo "Aucun produit trouvé dans la base de données.";
    }

    $pdo=null;
    ?> 
</body>