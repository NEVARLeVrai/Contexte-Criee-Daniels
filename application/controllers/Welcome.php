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
		$this->load->view('Menu'); // créer un fichier menu.php dans le répertoire views
		$this->load->view('Accueil'); // créer affichage.php dans le répertoire views
		$data['clients']= $this->requetes->getClients(); 
		$this->load->view('piedPage',$data); // Vue piedPage à créer dans le dossier VIEWS
		//$this->load->view('piedPage',NULL); // Vue piedPage à créer dans le dossier VIEWS 
	}
    public function contenu($id) 
    {
        $this->load->view('Menu'); // Chargement de la vue du menu
    
        switch($id) {
            case "Accueil":
                $this->load->view('Accueil');
                break;
            case "Mentions":
                $this->load->view('Mentions');
                break;
            case "Pains":
                $this->load->view('Pains');
                break;
            case "Viennoiseries":
                $this->load->view('Viennoiseries');
                break;
            case "Specialites":
                $this->load->view('Specialites');
                break;
            case "Contact":
                $this->load->view('Contact');
                break;
            case "Commandes":
                $this->load->view('Commandes');
                break;
            case "Connexion":
                $this->load->view('Connexion');
                break;
            case "Inscription":
                $this->load->view('Inscription');
                break;
    
        }
    
        $this->load->view('piedPage', NULL); // Chargement de la vue pied de page
    }
    
    public function traitement_inscription() {
        // Récupérer les données du formulaire
        $this->load->view('Menu'); // Chargement de la vue du menu
        $this->load->view('piedPage');
        $idCompte = $this->input->post('idCompte');
        $mdpCompte = $this->input->post('mdpCompte');
    
        // Préparer les données à passer à la vue
        $data['idCompte'] = $idCompte;
        $data['mdpCompte'] = $mdpCompte;
    
        // Charger la vue Inscription_Traitement avec les données
        $this->load->view('Inscription_Traitement', $data);
    }
    
    public function traitement_connexion() {
        // Récupérer les données du formulaire
        $this->load->view('Menu'); // Chargement de la vue du menu
        $this->load->view('piedPage');
        $idCompte = $this->input->post('idCompte');
        $mdpCompte = $this->input->post('mdpCompte');
    
        // Préparer les données à passer à la vue
        $data['idCompte'] = $idCompte;
        $data['mdpCompte'] = $mdpCompte;
    
        
        $this->load->view('Connexion_Traitement', $data);
    }

    public function commandes() {
        // Récupérer les données du formulaire
        $this->load->view('Menu'); // Chargement de la vue du menu
        $this->load->view('piedPage'); // Chargement de la vue du pied de page
        
        // Récupérer l'ID du compte depuis la requête POST
        $idCompte = $this->input->post('idCompte');
        
        // Préparer les données à passer à la vue
        $data['idCompte'] = $idCompte;
        
        // Charger la vue Commandes avec les données
        $this->load->view('Commandes', $data);
    }
    
    public function traitement() {
        // Récupérer les données du formulaire
        $this->load->view('Menu'); // Chargement de la vue du menu
        $this->load->view('piedPage'); // Chargement de la vue du pied de page
        
        // Récupérer l'ID du compte depuis la requête POST
        $idCompte = $this->input->post('idCompte');
        
        // Préparer les données à passer à la vue
        $data['idCompte'] = $idCompte;
        
        // Charger la vue Commandes avec les données
        $this->load->view('Traitement', $data);
    }
    

}
