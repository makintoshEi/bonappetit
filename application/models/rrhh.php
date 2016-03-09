<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class rrhh extends CI_Model {

 
	/*
		Constructor del modelo
	*/
	function __construct() {
		parent::__construct();
	}
 
	
	public function get_all()
	{
		return $this->db->get('empleado')->result_array();
	}
	
	public function get($data) {
		return $this->db->get_where('empleado', $data)->row();
	}
	
	public function save($data)
	{
		return $this->db->insert('empleado', $data);
	}
	
	public function delete($data)
	{
		return $this->db->delete('empleado', $data); 
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
	public function update($id, $data)
	{
		if($id){
			return $this->db->update('empleado', $data, array('emp_id' => $id));
		}
		else{
			return FALSE;
		}
	}
}