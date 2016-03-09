<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Invoice extends CI_Model {

 
	/*
		Constructor del modelo
	*/
	function __construct() {
		parent::__construct();
	}
 
	
	public function get_all()
	{
		/*
			SELECT per_id, per_ced, per_nom, per_ape, cli_id, cli_dir, cli_eml, cli_tel
			FROM cliente
		*/
		return $this->db->get('factura')->result_array();
		
	}
	
	public function get($data) {
		return $this->db->get_where('factura', $data)->row();
	}
	public function delete($data)
	{
		return $this->db->delete('factura', $data); 
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