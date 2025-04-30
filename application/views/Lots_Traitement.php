<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>



<html>
    <body>
        <?php
            include "application/config/database.php";

            // Définir le fuseau horaire
            $pdo->exec("SET time_zone = 'Europe/Paris'");

            // Vérifier si l'utilisateur est connecté
            if (!isset($_SESSION['identifiant'])) {
                echo "<section id='connexion_et_inscription' class='connexion_et_inscription'>
                    <h2>Accès non autorisé</h2>
                    <p>Cette page est réservée aux utilisateurs connectés. Veuillez vous connecter pour créer un lot.</p>
                    <br>
                    <form>
                        <a href='" . site_url('welcome/contenu/Connexion') . "'>
                            <button type='button' class='btn'>Se connecter</button>
                        </a>
                    </form>
                </section>";
                exit;
            }

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // Traitement de la création de lot
                $idBateau = $_POST['idBateau'];
                $datePeche = $_POST['datePeche'];
                $idEspece = $_POST['idEspece'];
                $idTaille = $_POST['idTaille'];
                $idPresentation = $_POST['idPresentation'];
                $idBac = $_POST['idBac'];
                $idQualite = $_POST['idQualite'];
                $poidsBrutLot = $_POST['poidsBrutLot'];
                $prixPlancher = $_POST['prixPlancher'];
                $prixDepart = $_POST['prixDepart'];
                $prixEncheresMax = isset($_POST['prixEncheresMax']) ? $_POST['prixEncheresMax'] : null;
                $codeEtat = isset($_POST['codeEtat']) ? $_POST['codeEtat'] : 'ok';
                $idFacture = $_POST['idFacture'];
                $idCompte = $_SESSION['identifiant'];

                try {
                    // Vérifier si la ligne existe déjà dans la table PECHE
                    $checkPeche = "SELECT COUNT(*) FROM PECHE WHERE idBateau = :idBateau AND datePeche = :datePeche";
                    $stmt = $pdo->prepare($checkPeche);
                    $stmt->bindParam(':idBateau', $idBateau, PDO::PARAM_STR);
                    $stmt->bindParam(':datePeche', $datePeche, PDO::PARAM_STR);
                    $stmt->execute();
                    $pecheExists = $stmt->fetchColumn();

                    // Si la pêche n'existe pas, l'insérer
                    if ($pecheExists == 0) {
                        $insertPeche = "INSERT INTO PECHE (idBateau, datePeche) VALUES (:idBateau, :datePeche)";
                        $stmt = $pdo->prepare($insertPeche);
                        $stmt->bindParam(':idBateau', $idBateau, PDO::PARAM_STR);
                        $stmt->bindParam(':datePeche', $datePeche, PDO::PARAM_STR);
                        $stmt->execute();
                    }

                    // Récupérer le prochain ID de lot disponible globalement
                    $selectMaxIdLot = "SELECT COALESCE(MAX(idLot), 0) + 1 FROM LOT FOR UPDATE";
                    $stmt = $pdo->prepare($selectMaxIdLot);
                    $stmt->execute();
                    $idLot = $stmt->fetchColumn();

                    // Insérer le nouveau lot
                    $insertLot = "INSERT INTO LOT (idBateau, datePeche, idLot, idEspece, idTaille, idPresentation, idBac, idCompte, idQualite, poidsBrutLot, prixPlancher, prixDepart, prixEncheresMax, codeEtat, idFacture, idCompteV) 
                               VALUES (:idBateau, :datePeche, :idLot, :idEspece, :idTaille, :idPresentation, :idBac, :idCompte, :idQualite, :poidsBrutLot, :prixPlancher, :prixDepart, :prixEncheresMax, :codeEtat, :idFacture, :idCompteV)";
                    
                    $stmt = $pdo->prepare($insertLot);
                    $stmt->bindParam(':idBateau', $idBateau, PDO::PARAM_STR);
                    $stmt->bindParam(':datePeche', $datePeche, PDO::PARAM_STR);
                    $stmt->bindParam(':idLot', $idLot, PDO::PARAM_INT);
                    $stmt->bindParam(':idEspece', $idEspece, PDO::PARAM_STR);
                    $stmt->bindParam(':idTaille', $idTaille, PDO::PARAM_INT);
                    $stmt->bindParam(':idPresentation', $idPresentation, PDO::PARAM_STR);
                    $stmt->bindParam(':idBac', $idBac, PDO::PARAM_STR);
                    $stmt->bindParam(':idCompte', $idCompte, PDO::PARAM_STR);
                    $stmt->bindParam(':idQualite', $idQualite, PDO::PARAM_STR);
                    $stmt->bindParam(':poidsBrutLot', $poidsBrutLot, PDO::PARAM_STR);
                    $stmt->bindParam(':prixPlancher', $prixPlancher, PDO::PARAM_STR);
                    $stmt->bindParam(':prixDepart', $prixDepart, PDO::PARAM_STR);
                    $stmt->bindParam(':prixEncheresMax', $prixEncheresMax, PDO::PARAM_STR);
                    $stmt->bindParam(':codeEtat', $codeEtat, PDO::PARAM_STR);
                    $stmt->bindParam(':idFacture', $idFacture, PDO::PARAM_STR);
                    $stmt->bindParam(':idCompteV', $idCompte, PDO::PARAM_STR);

                    if ($stmt->execute()) {
                        echo "<section id='connexion_et_inscription' class='connexion_et_inscription'>
                            <h2>Lot créé avec succès !</h2>
                            <p>Le lot #" . $idLot . " pour le bateau " . $idBateau . " a été créé.</p>
                            <br>
                            <form>
                                <a href='" . site_url('welcome/contenu/Lots') . "'>
                                    <button type='button' class='btn'>Voir les lots</button>
                                </a>
                                                        <br>                            <br>
                                <a href='" . site_url('welcome/contenu/Lots_Creation') . "'>
                                    <button type='button' class='btn'>Créer un autre lot</button>
                                </a>
                                                        <br>                            <br>
                                <a href='" . site_url('welcome/contenu/Accueil') . "'>
                                    <button type='button' class='btn'>Retour à l'accueil</button>
                                </a>
                            </form>
                        </section>";
                    } else {
                        echo "<section id='connexion_et_inscription' class='connexion_et_inscription'>
                            <h2>Erreur lors de la création du lot</h2>
                            <p>Erreur: " . implode(", ", $stmt->errorInfo()) . "</p>
                            <br>
                            <form>
                                <a href='" . site_url('welcome/contenu/Lots') . "'>
                                    <button type='button' class='btn'>Réessayer</button>
                                </a>
                            </form>
                        </section>";
                    }
                } catch (PDOException $e) {
                    echo "<section id='connexion_et_inscription' class='connexion_et_inscription'>
                        <h2>Erreur lors de la création du lot</h2>
                        <p>Erreur: " . $e->getMessage() . "</p>
                        <br>
                        <form>
                            <a href='" . site_url('welcome/contenu/Lots') . "'>
                                <button type='button' class='btn'>Réessayer</button>
                            </a>
                        </form>
                    </section>";
                }
            }
            $pdo = null;
        ?>
    </body>
</html> 