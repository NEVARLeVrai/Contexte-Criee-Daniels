<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<?php
    // Inclure le fichier de connexion à la base de données
    include "application/config/database.php";

    // Inclure les fonctions de hachage
    function crypterMotDePasse($motDePasse)
    {
        $options = ['cost' => 12]; // Plus le coût est élevé, plus le hachage est sécurisé
        return password_hash($motDePasse, PASSWORD_BCRYPT, $options);
    }

    // Vérifier si le formulaire d'inscription a été soumis
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Récupérer les valeurs des champs du formulaire
        $idCompte = $_POST["idCompte"];
        $mdpCompte = $_POST["mdpCompte"];

        // Vérifier si les champs sont vides
        if (empty($idCompte) || empty($mdpCompte)) {
            echo "<section id='connexion_et_inscription' class='connexion_et_inscription'>
            Veuillez remplir tous les champs.
            </section>";
        } else {
            // Vérifier si l'identifiant est déjà utilisé
            $sql_check = "SELECT idCompte FROM COMPTE WHERE idCompte = :idCompte";
            $stmt_check = $pdo->prepare($sql_check);
            $stmt_check->bindParam(':idCompte', $idCompte, PDO::PARAM_STR);
            $stmt_check->execute();

            // Récupérer tous les résultats de la première requête avant d'exécuter la suivante
            $rows = $stmt_check->fetchAll();

            // Vérifier si l'identifiant est déjà utilisé
            if (count($rows) > 0) {
                echo "<section id='connexion_et_inscription' class='connexion_et_inscription'>
                L'identifiant est déjà utilisé. 
                <br>
                Veuillez en choisir un autre.
                </section>";
            } else {
                // Hacher le mot de passe avant de l'insérer dans la base de données
                $motDePasseCrypte = crypterMotDePasse($mdpCompte);

                // Insérer le nouvel utilisateur dans la base de données
                $sql_insert = "INSERT INTO COMPTE (idCompte, mdpCompte) VALUES (:idCompte, :mdpCompte)";
                $stmt_insert = $pdo->prepare($sql_insert);
                $stmt_insert->bindParam(':idCompte', $idCompte, PDO::PARAM_STR);
                $stmt_insert->bindParam(':mdpCompte', $motDePasseCrypte, PDO::PARAM_STR);

                if ($stmt_insert->execute()) {
                    echo "<section id='connexion_et_inscription' class='connexion_et_inscription'>
                            <h2>Inscription réussie avec l'id " . htmlspecialchars($idCompte) . "</h2>
                            <br>
                            <br>
                            <form>
                                <a href='" . site_url('welcome/contenu/Inscription') . "'>
                                    <button type='button' class='btn'>Inscription</button>
                                </a>
                                <a href='" . site_url('welcome/contenu/Connexion') . "'>
                                    <button type='button' class='btn'>Connexion</button>
                                </a>
                            </form>
                        </section>";
                } else {
                    echo "<section id='connexion_et_inscription' class='connexion_et_inscription'>
                            Une erreur s'est produite lors de l'inscription. 
                            <br>
                            Veuillez réessayer.
                        </section>";
                }
                
            }
        }
    }
?>
