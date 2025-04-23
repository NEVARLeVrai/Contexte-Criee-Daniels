<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Model_bddclient extends CI_Model
{
 public function __construct()
 {
	parent::__construct();
 }
 public function getClients()
 {
	 $search = "SELECT * FROM client";
	 $result = $this->db->conn_id->prepare($search);
	 $result->execute();
	 $query_result = $result->fetchAll(PDO::FETCH_ASSOC);
	// $this->db = null ;
	 return $query_result; 
 }
 public function setClients($nom,$ville)
 {
	 $search = "INSERT INTO client(nomC,villeC) VALUES(:nom,:ville);";
	 $result = $this->db->conn_id->prepare($search);
	 $result->bindParam(':nom', $nom, PDO::PARAM_STR);
	 $result->bindParam(':ville', $ville, PDO::PARAM_STR);
	 $result->execute();
	 $query_result = $result->fetchAll(PDO::FETCH_ASSOC);
	 //$this->db = null ;
	 return $query_result; 
 }
}
?>