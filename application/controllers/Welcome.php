<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function __construct() {
		parent::__construct();
		$this->load->database(); // charger les paramètres associés au fichier de configuration database.php
		$this->load->helper('url_helper');// Charger des fonctions de bases pour gérer les URL
		$this->load->model('model_bddclient','requetes');// Charger un modèle nommé model_bddclient.php
		$this->load->library('form_validation');
		$this->load->helper('form');
        
	}
	
	public function index()
	{
		$this->load->view('Menu1'); // créer un fichier menu.php dans le répertoire views
		$this->load->view('Accueil'); // créer affichage.php dans le répertoire views
		$data['clients']= $this->requetes->getClients(); 
	}
    public function contenu($id) 
    {
        $this->load->view('Menu1'); // Chargement de la vue du menu
    
        switch($id) {
            case "Accueil":
                $this->load->view('Accueil');
                break;
            case "Mentions":
                $this->load->view('Mentions');
                break;
            case "Contact":
                $this->load->view('Contact');
                break;
            case "Connexion":
                $this->load->view('Connexion');
                break;
            case "Inscription":
                $this->load->view('Inscription');
                break;
            case "Deconnexion":
                $this->load->view('Deconnexion');
                break;
            case "Horaires":
                $this->load->view('Horaires');
                break;
            case "Annonces":
                $this->load->view('Annonces');
                break;
            case "Annonces_Creation":
                $this->load->view('Annonces_Creation');
                break;
            case "Annonces_Encherir":
                $this->load->view('Annonces_Encherir');
                break;     
        }
    
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

}
