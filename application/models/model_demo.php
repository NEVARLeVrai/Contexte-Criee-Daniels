<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Model extends CI_Model
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
 return $query_result = $result->fetchAll(PDO::FETCH_ASSOC);
 }
}
?>