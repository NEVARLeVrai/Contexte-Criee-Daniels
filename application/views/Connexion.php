<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<body>
    <section id="connexion_et_inscription" class="connexion_et_inscription">
        <?php
            if(isset($_SESSION) && isset($_SESSION["identifiant"]))
			{
				$id = $_SESSION["identifiant"]; 
				echo '
				<h2>Connecté avec le compte '.$id.'</h2><br>
				<form method="POST" action="' . site_url('welcome/contenu/Deconnexion') . '">
					<button class="btn">Déconnexion</button>
				</form>';
            }
            else
            {
                echo '
                <h2>Connectez-vous</h2>
                <br>
                <p>Connectez vous à votre compte :</p>
                <br>
                
                <form method="POST" action="' . site_url('welcome/traitement_connexion') . '">
        
                    <label for="idCompte">Identifiant :</label><br>
                    <input type="text" id="idCompte" name="idCompte" required><br>
        
                    <label for="mdpCompte">Mot de passe :</label><br>
                    <input type="password" id="mdpCompte" name="mdpCompte" required><br>
        
                    <br>
                    <button type="submit" class="btn">Valider</button>
                    <button type="reset" class="btn">Effacer</button> 
        
                </form>
        
                <br>
                <br>
                <form method="POST" action="' . site_url('welcome/contenu/Inscription') . '">
                    <p>Vous n'."'".'avez pas de compte ?</p>
                    <br>
                    <button type="submit" class="btn">Inscription</button>
                </form>';
            }
        ?> 
    </section>
</body>