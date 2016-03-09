<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Platos extends CI_Model {

 
	/*
		Constructor del modelo
	*/
	function __construct() {
		parent::__construct();
	}
	
	
	public function get_all(){
		return $this->db->order_by('mar_nom asc')->get('marca')->result_array();
	}
	
	/*
	 * -------------------------------------------------------------------
	 *  returns:
	 *			0 => name exists,
	 *			1 => insert correct,
	 *			2 => insert incorrect
	 * -------------------------------------------------------------------
	 */
	public function save($data)
	{
		if($this->db->insert('plato', $data))
		{ 
			return TRUE; 
		}
		else
		{ 
			return FALSE; 
		}
	}
 	
 	public function delete($data){ return $this->db->delete('plato', $data); }
	
	public function update($id, $data)
	{
		if($id){
			return $this->db->update('plato', $data, array('prd_id' => $id));
		}
		else{ return FALSE;}
	}
}