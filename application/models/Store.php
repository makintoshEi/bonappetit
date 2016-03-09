<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Store extends CI_Model {

 
	/*
		Constructor del modelo
	*/
	function __construct() {
		parent::__construct();
	}
 
	
	public function get($data) {
		return $this->db->get_where('sucursal', $data)->row();
	}
	
	public function delete($data)
	{
		return $this->db->delete('sucursal', $data); 
	}
	
	public function selectSQL($sql,$data)
	{
		$query = $this->db->query($sql,$data);
		Return $query->row();
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
}