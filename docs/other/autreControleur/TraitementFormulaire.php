<?php
class traitementFormulaire extends CI_Controller
{  
    public function __construct()
    {
        parent::__construct();
    }
     
    public function index()
    {
        //$this->accueil();
    }
 
    public function ajout_utilisateur()//$nom,$prenom,$mail) 
	{
		/*
		
	   $this->form_validation->set_rules('nom', '"Le Nom"', 'trim|required|xss_clean');
	   $this->form_validation->set_rules('prenom', '"Le prיnom"', 'trim|required|xss_clean');
	   $this->form_validation->set_rules('mail', '"Le mail"', 'trim|required|valid_email|is_unique[users.email]|xss_clean');
	  
	  	if ($this->form_validation->run() == false)
		{
	      $this->load->view('formulaire');
	    }else {
	      $this->load->view('resultat');
	 	}*/
	}     
}
?>