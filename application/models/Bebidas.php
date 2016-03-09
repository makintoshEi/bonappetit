<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Bebidas extends CI_Model {

	/*
		Constructor del modelo
	*/
	function __construct() {
		parent::__construct();
	}
	
	/*
	 * -------------------------------------------------------------------
	 *  returns:
	 *			0 => name exists,
	 *			1 => insert correct,
	 *			2 => insert incorrect
	 * -------------------------------------------------------------------
	 */
 	
 	public function delete($data){ return $this->db->delete('bebida', $data); }
	
	public function update($id, $data)
	{
		if($id){
			return $this->db->update('bebida', $data, array('prd_id' => $id));
		}
		else{ return FALSE;}
	}
}