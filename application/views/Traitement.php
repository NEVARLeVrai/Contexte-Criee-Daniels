<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<body>
    <section id='traitement' class='traitement'>
        <h2>Votre commande</h2>
    </section>
    <?php
        include "application/config/database.php";
        
        $sql = "SELECT refP, designationP, prixP FROM PRODUIT;";
        $res = $pdo->query($sql);
        $rows = $res->fetchAll();
        $idCompte = $_POST["idCompte"];

        echo"<h2>Connecté avec le compte ".$idCompte."</h2>
        <br>";

        if($rows > 0)
        {   
            $prixTotal = 0;

            foreach($rows as $row){

                $refProduit = $row['refP'];
                $quantite = floatval($_POST['quantite'.$refProduit]);
                $prixTotal += floatval($row['prixP']) * $quantite;

            }

            $sql2 = "INSERT INTO COMMANDE (prixTotal, idCompte) VALUES (?,?)";
            $stmt = $pdo->prepare($sql2);
            $stmt->execute([$prixTotal, $idCompte]);
            $numCommande = $pdo->lastInsertId();


            foreach($rows as $row){

                $refProduit = $row['refP'];
                $quantite = intval($_POST['quantite'.$refProduit]);

                if($quantite>0)
                {          
                    $sql4 = "INSERT INTO QUANTITE_COMMANDE VALUES (?,?,?)";
                    $stmt = $pdo->prepare($sql4);
                    $marche = $stmt->execute([$numCommande, $refProduit, $quantite]);

                    echo "<section id='traitement2' class='traitement2'>
                    <p>".$quantite." ".$row['designationP']."</p>";
                }


            }

            if($marche){
                echo "<br>
                    <h4>Prix total : $prixTotal euros</h4>
                </section>
                    ";
                    
            }
        }
    ?>
</body>