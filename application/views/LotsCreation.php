<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include "application/config/database.php";
?>

<body>
    <section id="connexion_et_inscription" class="connexion_et_inscription">
        <h2>Création d'un lot</h2>
        <br>
        
        <?php
        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['identifiant'])) {
            echo '<p>Cette page est réservée aux utilisateurs connectés. Veuillez vous connecter pour créer un lot.</p>
                <br>
                <form>
                    <a href="' . site_url('welcome/contenu/Connexion') . '">
                        <button type="button" class="btn">Se connecter</button>
                    </a>
                </form>';
        } else {
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
                    // L'utilisateur est un vendeur, permettre l'accès à la création de lots
                    echo '<p>Créer mon lot :</p>
                    <br>
                    
                    <form method="POST" action="' . site_url('welcome/traitement_lots') . '">'; 
                        include "application/config/database.php";
                    
                        $selectBateaux = "SELECT idBateau, immatriculation FROM BATEAU";                
                        $stmt = $pdo->prepare($selectBateaux);
                        $stmt->execute();
                        $rows = $stmt->fetchAll();
                        
                        if(count($rows) > 0) // on vérifie que le nombre d'éléments dans $rows est supérieur à 0, soit que $rows ne soit pas vide
                        {
                            echo '
                            <label for="idBateau">Bateau :</label><br>
                            <select id="idBateau" name="idBateau" required>';
                                foreach ($rows as $row) // boucle pour tous les éléments dans $rows
                                { 
                                    echo '<option value='.$row['idBateau'].'>'.$row['idBateau'].' - '.$row['immatriculation'].'</option>';
                                }
                            echo '</select><br><br>';
                            
                        } else {
                            echo "Erreur : aucun enregistrement de bateau trouvé";
                            // si $rows est vide, cela signifie qu'aucun bateau n'existe dans la base
                        }
                    ?>

                    <label for="datePeche">Date de pêche :</label><br>
                    <input type="date" id="datePeche" name="datePeche" required><br><br>

                    <label for="idEspece">Espèce :</label><br>
                    <select id="idEspece" name="idEspece" required>
                        <?php
                        $selectEspeces = "SELECT idEspece, nomEspece FROM ESPECE";
                        $stmt = $pdo->prepare($selectEspeces);
                        $stmt->execute();
                        $especes = $stmt->fetchAll();
                        foreach ($especes as $espece) {
                            echo '<option value="'.$espece['idEspece'].'">'.$espece['nomEspece'].'</option>';
                        }
                        ?>
                    </select><br><br>

                    <label for="idTaille">Taille :</label><br>
                    <select id="idTaille" name="idTaille" required>
                        <?php
                        $selectTailles = "SELECT idTaille, specification FROM TAILLE";
                        $stmt = $pdo->prepare($selectTailles);
                        $stmt->execute();
                        $tailles = $stmt->fetchAll();
                        foreach ($tailles as $taille) {
                            echo '<option value="'.$taille['idTaille'].'">'.$taille['specification'].'</option>';
                        }
                        ?>
                    </select><br><br>

                    <label for="idPresentation">Présentation :</label><br>
                    <select id="idPresentation" name="idPresentation" required>
                        <?php
                        $selectPresentations = "SELECT idPresentation, libelle FROM PRESENTATION";
                        $stmt = $pdo->prepare($selectPresentations);
                        $stmt->execute();
                        $presentations = $stmt->fetchAll();
                        foreach ($presentations as $presentation) {
                            echo '<option value="'.$presentation['idPresentation'].'">'.$presentation['libelle'].'</option>';
                        }
                        ?>
                    </select><br><br>

                    <label for="idBac">Bac :</label><br>
                    <select id="idBac" name="idBac" required>
                        <?php
                        $selectBacs = "SELECT idBac, tare FROM BAC";
                        $stmt = $pdo->prepare($selectBacs);
                        $stmt->execute();
                        $bacs = $stmt->fetchAll();
                        foreach ($bacs as $bac) {
                            echo '<option value="'.$bac['idBac'].'">'.$bac['idBac'].' - '.$bac['tare'].' kg</option>';
                        }
                        ?>
                    </select><br><br>

                    <label for="idQualite">Qualité :</label><br>
                    <select id="idQualite" name="idQualite" required>
                        <?php
                        $selectQualites = "SELECT idQualite, libelle FROM QUALITE";
                        $stmt = $pdo->prepare($selectQualites);
                        $stmt->execute();
                        $qualites = $stmt->fetchAll();
                        foreach ($qualites as $qualite) {
                            echo '<option value="'.$qualite['idQualite'].'">'.$qualite['libelle'].'</option>';
                        }
                        ?>
                    </select><br><br>

                    <label for="poidsBrutLot">Poids brut du lot (kg) :</label><br>
                    <input type="number" min="0" step="any" id="poidsBrutLot" name="poidsBrutLot" required><br><br>

                    <label for="prixPlancher">Prix plancher (€) :</label><br>
                    <input type="number" min="0" step="any" id="prixPlancher" name="prixPlancher" required><br><br>

                    <label for="prixDepart">Prix de départ (€) :</label><br>
                    <input type="number" min="0" step="any" id="prixDepart" name="prixDepart" required><br><br>

                    <label for="prixEncheresMax">Prix d'enchères maximum (€) :</label><br>
                    <input type="number" min="0" step="any" id="prixEncheresMax" name="prixEncheresMax"><br><br>

                    <label for="DateEnchere">Date et heure d'enchère :</label><br>
                    <input type="datetime-local" id="DateEnchere" name="DateEnchere" required><br><br>

                    <label for="codeEtat">État :</label><br>
                    <input type="text" id="codeEtat" name="codeEtat" placeholder="ex: ok" value="ok"><br><br>

                    <label for="idFacture">Facture :</label><br>
                    <select id="idFacture" name="idFacture" required>
                        <?php
                        $selectFactures = "SELECT idFacture FROM FACTURE";
                        $stmt = $pdo->prepare($selectFactures);
                        $stmt->execute();
                        $factures = $stmt->fetchAll();
                        foreach ($factures as $facture) {
                            echo '<option value="'.$facture['idFacture'].'">'.$facture['idFacture'].'</option>';
                        }
                        ?>
                    </select><br><br>

                    <br>
                    <button type="submit" class="btn">Valider</button>
                    <button type="reset" class="btn">Effacer</button> 
                </form>
                <?php
                } else {
                    echo '<p>Cette page est réservée aux vendeurs. Vous n\'avez pas les droits nécessaires pour créer un lot.</p>
                        <br>
                        <form>
                                <a>Se connecter en tant que Vendeur
                                <br>
                                                                <br>
                            <a href="' . site_url('welcome/contenu/Connexion') . '">
                                <button type="button" class="btn">Connexion</button>
                            </a>
                        </form>';
                }
            }
        }
        ?>
    </section> 
</body>
