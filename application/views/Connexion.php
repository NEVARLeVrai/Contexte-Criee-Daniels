<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<body>
    <section id="connexion_et_inscription" class="connexion_et_inscription">
        <h2>Connectez-vous</h2>
        <p>Connectez vous à votre compte :</p>
        
        <form method="POST" action="<?php echo site_url('welcome/traitement_connexion'); ?>">

            <label for="idCompte">Id :</label><br>
            <input type="text" id="idCompte" name="idCompte" required><br>

            <label for="mdpCompte">Mot de passe :</label><br>
            <input type="password" id="mdpCompte" name="mdpCompte" required><br>

   
            <button type="submit" class="btn">Connexion</button>

        </form>

    

        <br>
        <form method="POST" action="<?php echo site_url('welcome/contenu/Inscription'); ?>">
            <p>Vous n'avez pas de compte ?</p>
            <button type="submit" class="btn">Inscription</button>
        </form>
        
    </section>
</body>
