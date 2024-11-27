<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!doctype html>
<html lang="fr">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>Formulaire</title>
	</head>
<?php
    echo form_open('Welcome/ajout_utilisateur',array('method'=>'post'));

	$nom= array('name'=>'nom','id'=>'nom','placeholder'=>'Nom','value'=>set_value('nom'));
    echo form_input($nom);
    
	$ville= array('name'=>'ville','id'=>'ville','placeholder'=>'Ville','value'=>set_value('ville'));
    echo form_input($ville);
    
	$mail= array('name'=>'mail','id'=>'mail','placeholder'=>'Email','value'=>set_value('mail'));
    echo form_input($mail);
    
	echo form_submit('envoi', 'Valider');
 
    echo form_close();
?>
</html>