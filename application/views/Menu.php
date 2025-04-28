<?php
defined('BASEPATH') OR exit('No direct script access allowed');
session_start();
?>
<!doctype html>
<html lang=fr>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta property="og:site_name" content="Criée de Poulgoazec">
        <meta property="og:title" content="&#x26CA; Criée de Poulgoazec">
        <meta property="og:description" content="Criée de Poulgoazec">
        <meta property="og:url" content="https://www.lyceecassin-strasbourg.eu/">
        <meta property="og:image:url" href="<?php echo base_url('assets/img/Accueil.webp');?>">
        <link rel="icon" href="<?php echo base_url('assets/img/Accueil.webp');?>">
        <title><?php echo isset($page_title) ? $page_title : 'Criée de Poulgoazec'; ?></title>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/Criee9.css');?>">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
        <script src="<?php echo base_url('assets/js/Criee.js');?>"></script>
    </head>
    <body>
        <div class="overlay"></div>
        <!-- Menu latéral -->
        <div class="sidebar">
            <div class="sidebar-header">
                <i class="fas fa-bars"></i><h2>Menu</h2>
                <button class="close-btn">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <nav class="sidebar-nav">

                <!-- Bouton Accueil -->
                <a href="<?php echo site_url('welcome/contenu/Accueil');?>">
                    <button class="sidebar-item deco-button">
                        <i class="fas fa-home"></i> <span>Accueil</span>
                    </button>
                </a>

                <!-- Bouton Connexion -->
                <a href="<?php echo site_url('welcome/contenu/Connexion');?>">
                        <button class="sidebar-item deco-button">
                            <i class="fas fa-user"></i> <span>Compte</span>
                    </button>
                </a>

                <!-- Bouton Déconnexion -->
                <?php if (isset($_SESSION) && isset($_SESSION["identifiant"])): ?>
                <a href="<?php echo site_url('welcome/contenu/Deconnexion');?>">
                    <button class="sidebar-item deco-button" style="margin-bottom: 50px;">
                        <i class="fas fa-sign-out-alt"></i> <span>Deconnexion</span>
                    </button>
                </a>
                <?php endif; ?>

                <!-- Bouton Lots -->
                <a href="<?php echo site_url('welcome/contenu/Lots');?>">
                    <button class="sidebar-item deco-button" style=" margin-top: 50px; margin-bottom: 50px;">
                        <i class="fas fa-box"></i> <span>Lots</span>
                    </button>
                </a>

                <!-- Bouton Annonces -->
                <a href="<?php echo site_url('welcome/contenu/Annonces');?>">
                    <button class="sidebar-item deco-button" style="margin-top: 50px; margin-bottom: 50px;">
                        <i class="fas fa-bullhorn"></i> <span>Annonces</span>
                    </button>
                </a>

                <!-- Bouton Mentions -->
                <a href="<?php echo site_url('welcome/contenu/Mentions');?>">
                    <button class="sidebar-item deco-button">
                        <i class="fas fa-file-alt"></i> <span>Mentions</span>
                    </button>
                </a>

                <!-- Bouton Contact -->
                <a href="<?php echo site_url('welcome/contenu/Contact');?>">
                    <button class="sidebar-item deco-button">
                        <i class="fas fa-envelope"></i> <span>Contact</span>
                    </button>
                </a>

                <!-- Bouton Horaires -->
                <a href="<?php echo site_url('welcome/contenu/Horaires');?>">
                    <button class="sidebar-item deco-button">
                        <i class="fas fa-clock"></i> <span>Horaires</span>
                    </button>
                </a>
            </nav>
            <div class="sidebar-footer">
            <p><i class="fas fa-fish"></i>
                Merci de visiter la Criée de Poulgoazec</p>
            </div>
        </div>
        <header>
            <nav id="navbar">
                <form id="navForm" style="display: flex; align-items: center; justify-content: space-between;">  
                    <button type="button" class="nav-link menu-btn">
                        <i class="fas fa-bars"></i>
                        Menu
                    </button>
                    <a href="<?php echo site_url('welcome/contenu/Accueil'); ?>">
                        <img src="<?php echo base_url('assets/img/Accueil.webp'); ?>" alt="Logo" />
                    </a>
                </form>
            </nav>   
        </header>
    </body>
</html>