<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->database(); // charger les paramètres associés au fichier de configuration database.php
		$this->load->helper('url_helper');// Charger des fonctions de bases pour gérer les URL
		$this->load->model('model_bddclient','requetes');// Charger un modèle nommé model_bddclient.php
		$this->load->library('form_validation');
		$this->load->helper('form');
        
        // Session déjà démarrée dans Menu1.php
	}
	
	public function index()
	{
		$this->load->view('Menu1'); // créer un fichier menu.php dans le répertoire views
		$this->load->view('Accueil'); // créer affichage.php dans le répertoire views
		$data['clients']= $this->requetes->getClients(); 
	}
    public function contenu($id) 
    {
        $data = array();
        
        switch($id) {
            case "Accueil":
                $data['page_title'] = "Accueil - Criée de Poulgoazec";
                break;
            case "Mentions":
                $data['page_title'] = "Mentions légales - Criée de Poulgoazec";
                break;
            case "Contact":
                $data['page_title'] = "Contact - Criée de Poulgoazec";
                break;
            case "Connexion":
                $data['page_title'] = "Connexion - Criée de Poulgoazec";
                break;
            case "Inscription":
                $data['page_title'] = "Inscription - Criée de Poulgoazec";
                break;
            case "Deconnexion":
                $data['page_title'] = "Déconnexion - Criée de Poulgoazec";
                break;
            case "Horaires":
                $data['page_title'] = "Horaires - Criée de Poulgoazec";
                break;
            case "Annonces":
                $data['page_title'] = "Enchères - Criée de Poulgoazec";
                break;
            case "Lots":
                $data['page_title'] = "Lots - Criée de Poulgoazec";
                break;
            case "LotsCreation":
                $data['page_title'] = "Création de lots - Criée de Poulgoazec";
                break;
            case "Annonces_Creation":
                $data['page_title'] = "Création d'enchère - Criée de Poulgoazec";
                break;
            case "Annonces_Encherir":
                $data['page_title'] = "Enchérir - Criée de Poulgoazec";
                break;
        }

        $this->load->view('Menu1', $data); // Passer le titre à la vue Menu1
        $this->load->view($id); // Charger la vue correspondante
    }
    
    public function traitement_inscription() {
        // Récupérer les données du formulaire
        $this->load->view('Menu1'); // Chargement de la vue du menu
        $idCompte = $this->input->post('idCompte');
        $mdpCompte = $this->input->post('mdpCompte');
        $typeCompte = $this->input->post('typeCompte');
    
        // Préparer les données à passer à la vue
        $data['idCompte'] = $idCompte;
        $data['mdpCompte'] = $mdpCompte;
        $data['typeCompte'] = $typeCompte;
    
        // Charger la vue Inscription_Traitement avec les données
        $this->load->view('Inscription_Traitement', $data);
    }
    
    public function traitement_connexion() {
        // Récupérer les données du formulaire
        $this->load->view('Menu1'); // Chargement de la vue du menu
        $idCompte = $this->input->post('idCompte');
        $mdpCompte = $this->input->post('mdpCompte');
    
        // Préparer les données à passer à la vue
        $data['idCompte'] = $idCompte;
        $data['mdpCompte'] = $mdpCompte;
    
        
        $this->load->view('Connexion_Traitement', $data);
    }


    public function traitement_Compte() {
        // Récupérer les données du formulaire
        $this->load->view('Menu1'); // Chargement de la vue du menu
        $idCompte = $this->input->post('idCompte');
        $mdpCompte = $this->input->post('mdpCompte');
        $typeCompte = $this->input->post('typeCompte');
    
        // Préparer les données à passer à la vue
        $data['idCompte'] = $idCompte;
        $data['mdpCompte'] = $mdpCompte;
        $data['typeCompte'] = $typeCompte;
    
        
        $this->load->view('TraitementCompte', $data);
    }

    public function traitement_annonces() {
        // Récupérer les données du formulaire
        $this->load->view('Menu1'); // Chargement de la vue du menu
        
        // Récupérer les données de l'enchère
        $idLot = $this->input->post('idLot');
        $nouveauPrix = $this->input->post('nouveauPrix');
        
        // Préparer les données à passer à la vue
        $data['idLot'] = $idLot;
        $data['nouveauPrix'] = $nouveauPrix;
        
        $this->load->view('Annonces_Traitement', $data);
    }

    public function creation_lots() {
        $this->load->view('Menu1'); // Chargement de la vue du menu
        $this->load->view('Lots_Creation'); // Chargement de la vue pour la création de lots
    }

    public function traitement_lots() {
        $this->load->view('Menu1'); // Chargement de la vue du menu
        $this->load->view('Lots_Traitement'); // Chargement de la vue pour le traitement des lots
    }

}