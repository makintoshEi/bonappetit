<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Restaurant extends CI_Model {

 
	/*
		Constructor del modelo
	*/
	function __construct() {
		parent::__construct();
		$this->table = 'restaurant';
		$this->id = 'rst_id';
	}
 
	public function selectSQL($sql,$data)
	{
		$query = $this->db->query($sql,$data);
		if ($query->num_rows() > 0)
		{	
			return $query->row();
		}
	}
	
	public function selectSQLMultiple($sql,$data) {
		$query=null;
		if($data)
		{
			$query = $this->db->query($sql,$data);
		}
		else
		{
			$query = $this->db->query($sql);
		}
		Return $query->result_array();
	}

	public function getUser($username='', $password='') {
		return $this->db->get_where(
			$this->table, array(
				'rst_eml' => $username,
				'rst_pss' => $password
			)
		)->row();
	}
	
	public function update($id, $data)
	{
		if($id){
			return $this->db->update($this->table, $data, array($this->id => $id));
		}
		else{
			return FALSE;
		}
	}
}