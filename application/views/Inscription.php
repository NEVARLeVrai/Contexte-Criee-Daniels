<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<body>
    <section id="connexion_et_inscription" class="connexion_et_inscription">
        <h2>Inscription</h2>
        <p>Remplissez le formulaire ci-dessous pour créer votre compte :</p>
        
        <form method="POST" action="<?php echo site_url('welcome/traitement_inscription'); ?>">

            <label for="idCompte">Id :</label><br>
            <input type="text" id="idCompte" name="idCompte" required><br>

            <label for="mdpCompte">Mot de passe :</label><br>
            <input type="password" id="mdpCompte" name="mdpCompte" required><br>

   
            <button type="submit" class="btn">Inscription</button>

        </form>

    

        <br>
        <form method="POST" action="<?php echo site_url('welcome/contenu/Connexion'); ?>">
            <p>Vous avez un compte ?</p>
            <button type="submit" class="btn">Connexion</button>
        </form>
        
    </section>
</body>
