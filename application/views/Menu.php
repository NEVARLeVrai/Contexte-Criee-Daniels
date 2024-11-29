<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html lang="fr">
	<head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta property="og:site_name" content="Criée de Poulgoazec">
        <meta property="og:title" content="&#x26CA; Criée de Poulgoazec">
        <meta property="og:description" content="Criée de Poulgoazec">
        <meta property="og:url" content="https://www.lyceecassin-strasbourg.eu/">
        <meta property="og:image:url" href="<?php echo base_url('assets/img/Accueil.png');?>">
        <link rel="icon" href="<?php echo base_url('assets/img/Accueil.png');?>">
        <title>Criée de Poulgoazec</title>

		<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/Criee copy 3.css');?>">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
        <script src="<?php echo base_url('assets/js/Criee.js');?>"></script>
    </head>
    <body>
        <div class="overlay"></div>

        <!-- Menu latéral -->
        <div class="sidebar">
            <div class="sidebar-header">
                <h2>Menu</h2>
                <button class="close-btn">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <nav class="sidebar-nav">
                <a href="<?php echo site_url('welcome/contenu/Commandes');?>">
                    <button class="sidebar-item">
                        <i class="fas fa-shopping-cart"></i> Commandes
                    </button>
                </a>
            </nav>
        </div>

        <header>
            <nav id="navbar">
                <form id="navForm">  
                    <button type="button" class="nav-link menu-btn">
                        <i class="fas fa-bars"></i>
                        Menu
                    </button>


                    <a href="<?php echo site_url('welcome/contenu/Accueil');?>">
                        <button type="button" class="nav-link">
                            <i class="fas fa-home"></i> Accueil
                    </button>
     
                


                    <a href="<?php echo site_url('welcome/contenu/Connexion');?>">
                        <button type="button" class="nav-link">
                            <i class="fas fa-user"></i> Compte
                    </button>
                    </a>
              
                      
                </form>
            </nav>   
        </header>
    </body>
</html>
