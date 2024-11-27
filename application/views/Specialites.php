<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<body>
    <?php
    include "application/config/database.php";

    $afficheSpecialites = "SELECT PRODUIT.photoP, PRODUIT.refP, PRODUIT.designationP, PRODUIT.prixP, PRODUIT.poidsP, PRODUIT.descriptifP, 
                                GROUP_CONCAT(DISTINCT ALLERGENE.denominationA SEPARATOR ', ') AS allergenes,
                                MAX(EXISTER.presence) AS presence,
                                MAX(EXISTER.trace) AS trace
                        FROM PRODUIT 
                        LEFT JOIN EXISTER ON EXISTER.refP = PRODUIT.refP 
                        LEFT JOIN ALLERGENE ON EXISTER.idA = ALLERGENE.idA 
                        WHERE PRODUIT.codeCat = 3
                        GROUP BY PRODUIT.photoP, PRODUIT.refP, PRODUIT.designationP, PRODUIT.prixP, PRODUIT.poidsP, PRODUIT.descriptifP
                        ORDER BY PRODUIT.refP";

    $res = $pdo->query($afficheSpecialites);
    $rows = $res->fetchAll();

    if(count($rows) > 0) {
        echo '
        <section id="Specialites" class="specialites">
            <h2>Spécialités : </h2>
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
                        <th scope="col">Trace</th>
                    </tr>
                </thead>
                <tbody>';

        foreach($rows as $row) {
            echo "<tr>";
            echo '<td><img src="../../assets/'.$row['photoP'].'"></td>';
            echo "<td>".$row['refP']."</td>";
            echo "<td>".$row['designationP']."</td>";
            echo "<td>".$row['prixP']."</td>";
            echo "<td>".$row['poidsP']."</td>";
            echo "<td>".$row['descriptifP']."</td>";
            echo "<td>".$row['allergenes']."</td>";
            echo "<td>".$row['trace']."</td>";
            echo "</tr>";
        }

        echo '</tbody>
            </table>
            <br>
            <br>
            </section>';
    } else {
        echo "Aucune spécialité trouvée dans la base de données.";
    }

    $pdo=null;
    ?>

</body>