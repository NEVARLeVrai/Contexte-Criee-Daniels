<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html lang="fr">
	<head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta property="og:site_name" content="Le fournil alsacien - Boulangerie">
        <meta property="og:title" content="&#x26CA; Le fournil alsacien - Boulangerie">
        <meta property="og:description" content="Le Fournil Alsacien est une boulangerie traditionnelle implantée en Alsace. Reconnue pour la qualité de ses produits artisanaux, cette entreprise a su se faire une place de choix sur le marché local">
        <meta property="og:url" content="https://www.lyceecassin-strasbourg.eu/">
        <meta property="og:image:url" href="<?php echo base_url('assets/img/Accueil.png');?>">
        <link rel="icon" href="<?php echo base_url('assets/img/Accueil.png');?>">
        <title>Le fournil alsacien</title>
		<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/Accueil1.css');?>">
    </head>



	<header>
        <nav id="navbar">
            <img src="<?php echo base_url('assets/img/Accueil.png');?>">
            <form id="navForm">  
                <a href="<?php echo site_url('welcome/contenu/Accueil');?>"> <button type="button" class="nav-link">Accueil</button></a>
                <a href="<?php echo site_url('welcome/contenu/Pains');?>"> <button type="button" class="nav-link">Pains</button></a>      
                <a href="<?php echo site_url('welcome/contenu/Viennoiseries');?>"> <button type="button" class="nav-link">Viennoiseries</button></a>
                <a href="<?php echo site_url('welcome/contenu/Specialites');?>"> <button type="button" class="nav-link">Specialites</button></a>    
                <a href="<?php echo site_url('welcome/contenu/Connexion');?>"> <button type="button" class="nav-link">Commandes</button></a>   

            </form>
        </nav>   
        
    </header>
    
<html>
