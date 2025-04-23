<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<body>
    <section id="connexion_et_inscription" class="connexion_et_inscription">
        <h2>Annonces</h2>
		<br>
        <p>Créer mon annonce :</p>
		<br>
        
        <form method="POST" action="<?php echo site_url('welcome/annonces_traitement'); ?>">

            <label for="typeCompte">Bateau :</label><br>
            <select id="typeCompte" name="typeCompte" required>
                <option value="acheteur">Acheteur</option>
                <option value="vendeur">Vendeur</option>
            </select><br>

			<br>
            <button type="submit" class="btn">Valider</button>
			<button type='reset' class='btn'>Effacer</button> 
        </form>      
    </section>
</body>
