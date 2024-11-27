<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<?php
    // Inclure le fichier de connexion à la base de données
    include "application/config/database.php";

    // Inclure les fonctions de hachage et de vérification de mot de passe
    function crypterMotDePasse($motDePasse)
    {
        $options = ['cost' => 12]; // Plus le coût est élevé, plus le hachage est sécurisé
        $motDePasseCrypte = password_hash($motDePasse, PASSWORD_BCRYPT, $options);
        return $motDePasseCrypte;
    }

    function veriferMotDePasse($motDePasse, $motDePasseCrypte)
    {
        return password_verify($motDePasse, $motDePasseCrypte);   
    }

    // Vérifier si le formulaire a été soumis
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
            // Préparer la requête SQL pour récupérer l'utilisateur en fonction de son identifiant
            $sql = "SELECT idCompte, mdpCompte FROM COMPTE WHERE idCompte = :idCompte";

            // Préparer la requête pour exécution
            $stmt = $pdo->prepare($sql);

            // Liaison des paramètres
            $stmt->bindParam(':idCompte', $idCompte, PDO::PARAM_STR);

            // Exécuter la requête
            $stmt->execute();

            // Récupérer le résultat de la requête
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // Vérifier si un utilisateur correspondant à l'identifiant a été trouvé
            if ($row) {
                // Vérifier si le mot de passe est correct avec bcrypt
                if (veriferMotDePasse($mdpCompte, $row['mdpCompte'])) {

                    // Stocker l'identifiant de l'utilisateur dans la session
                    $_SESSION["idCompte"] = $idCompte;

                    // Afficher le message de connexion après avoir démarré la session
                    echo "<section id='connexion_et_inscription' class='connexion_et_inscription'>
                    <p>Vous êtes connecté.</p> <br>
                    <h2>Bienvenu(e) " . htmlspecialchars($idCompte) . "</h2>
                    <br>
                    <form method='POST' action='" . site_url('welcome/commandes') . "'>
                        <input type='hidden' id='idCompte' name='idCompte' value='" . htmlspecialchars($idCompte) . "'>
                        <button type='submit' class='btn'>Allez vers commandes</button>
                    </form>
                    </section>";
                } else {
                    echo "<section id='connexion_et_inscription' class='connexion_et_inscription'>
                    Identifiant ou mot de passe incorrect.
                    </section>";
                }
            } else {
                echo "<section id='connexion_et_inscription' class='connexion_et_inscription'>
                Identifiant ou mot de passe incorrect.
                </section>";
            }
        }
    }
?>
