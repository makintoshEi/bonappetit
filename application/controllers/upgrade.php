<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Upgrade extends Private_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model(array('restaurant','invoice'));
	}
	/*
	 * -------------------------------------------------------------------
	 *  BEBIDA
	 * -------------------------------------------------------------------
	 */
	public function start()
	{
		if(!@$this->user) redirect ('main');
		$user_data = (array)$this->session->userdata('logged_user');
		$user_data['title'] = 'upgrade';
		$user_data['modulos'] = $this->restaurant->selectSQLMultiple("SELECT * FROM modulos_permisos_view WHERE rst_id=".$user_data['rst_id'],null);
		$promo = $this->restaurant->selectSQL("SELECT * FROM promocion WHERE pro_fch_ini<=current_date and pro_fch_fin>=current_date",null);
		$data['js'] = array(
			"http://code.jquery.com/ui/1.10.2/jquery-ui.js",
			base_url()."static/js/library/alls.js",
			base_url()."static/js/library/files.js",
			base_url()."static/js/users/upgrade.js",
			base_url()."static/js/jquery.dataTables.min.js",
			base_url()."static/js/dataTables.bootstrap.js",
			base_url()."static/js/jquery.ci.validator.js",
			base_url()."static/js/pnotify.custom.min.js"
		);
		$data['funcion']="<script> window.promo=".($promo!=null?$promo->pro_val:0).";</script>";
		
		$user_data['css'] = array(
			"http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css",
			base_url()."static/css/pnotify.custom.min.css",
			base_url()."static/css/dataTables.bootstrap.css"
		);
		
		//$clientes['clientes']  = $this->clients->get_all();
		$this->load->view('templates/header', $user_data);
		$this->load->view('user/upgrade',$user_data);
		$this->load->view('templates/footer', $data);
	}
	
	/*
	 * -------------------------------------------------------------------
	 * productos
	 * -------------------------------------------------------------------
	 */
	 
	 public function get_all_packs()
	{
		$row_set=array();
		$data = $this->restaurant->selectSQLMultiple("SELECT * FROM pack_view ORDER BY pck_id",null);
		echo json_encode(array("data"=>$data));
	}
	 
	 
	 /*
	 * -------------------------------------------------------------------
	 *  fin productos
	 * -------------------------------------------------------------------
	 */
	
	/*
	 * -------------------------------------------------------------------
	 * INVOICE
	 * -------------------------------------------------------------------
	 */
	public function save_invoice()
	{
		if(!@$this->user) redirect ('main');
		if ($this->input->is_ajax_request()) 
    	{
			$productos = explode(',', $this->input->get('prods'));
			
			$user_data = (array)$this->session->userdata('logged_user');
			$data = array(
				"{".implode(",",$productos)."}",
				$user_data["rst_id"]
    		);
			$response = $this->invoice->selectSQL("SELECT insert_invoice_ei(?,?)",$data);
			echo json_encode($response);
		}
		else
		{
			exit('No direct script access allowed');
			show_404();
		}
		return FALSE;
	}
	
	public function save_voucher()
	{
		if(!@$this->user) redirect ('main');
		if ($this->input->is_ajax_request()) 
    	{
			$filesUrl = $_FILES;
			$tipo=explode('/',$filesUrl['images']['type'][0]);
			$data = array(
				$this->input->post("txtNumeroCash"),
				$this->input->get("id"),
				'voucher/uploads/img/'.$this->input->post("txtNumeroCash").'.'.$tipo[1]
    		);
			$response = $this->invoice->selectSQL("SELECT insert_voucher(?,?,?)",$data);
			if($response->insert_voucher=="1")
			{
				$this->do_upload($this->input->post("txtNumeroCash"), 'images');
			}
			echo json_encode($response);
		}
		else
		{
			exit('No direct script access allowed');
			show_404();
		}
		return FALSE;
	}
	
	function do_upload($ci,$name,$edt=false)
	{
	    $files = $_FILES;
	    $cpt = count($_FILES[$name]['name']);
	    if($edt)
		{	
			$dir = opendir('./voucher/uploads/img/');
			while ($file = readdir($dir)) 
			{ 
				if($file != '.' && $file != '..' && $file!=".DS_Store")
				{
					if (strpos($file, $ci)!==false){
						unlink('./voucher/uploads/img/'.$file);
					}
				}
			} 
			closedir($dir);
		}
		
		for($i=0; $i<$cpt; $i++)
	    {
	        $_FILES[$name]['name']		= $files[$name]['name'][$i];
	        $_FILES[$name]['type']		= $files[$name]['type'][$i];
	        $_FILES[$name]['tmp_name']	= $files[$name]['tmp_name'][$i];
	        $_FILES[$name]['error']		= $files[$name]['error'][$i];
	        $_FILES[$name]['size']		= $files[$name]['size'][$i];
			//$new_name = $ci."_".$_FILES[$name]['name'];
			$tipo=explode('/',$files[$name]['type'][$i]);
			$new_name = $ci.'.'.$tipo[1];
			move_uploaded_file($_FILES[$name]['tmp_name'], './voucher/uploads/img/'.$new_name);
		    
	    }
		if ($cpt>1)
		{
			return true;
		}
		else{
			return false;
		}
	}
	
	public function search_invoice_by_id()
	{
		if(!@$this->user) redirect ('main');
		if ($this->input->is_ajax_request()) 
    	{
			$user_data = (array)$this->session->userdata('logged_user');
    		$data = $this->restaurant->selectSQLMultiple('Select * from view_complete_invoice_ei where fei_id=?',array($this->input->post("id")));
			echo json_encode(array("data"=>$data));
		}
		else
		{
			exit('No direct script access allowed');
			show_404();
		}
		return FALSE;
	}
	
	public function get_invoice_all()
	{
		if(!@$this->user) redirect ('main');
		if ($this->input->is_ajax_request()) 
    	{
			$user_data = (array)$this->session->userdata('logged_user');
    		$data = $this->restaurant->selectSQLMultiple('Select * from view_basic_invoice_ei where rst_id=?',array($user_data["rst_id"]));
			echo json_encode(array("data"=>$data));
		}
		else
		{
			exit('No direct script access allowed');
			show_404();
		}
		return FALSE;
	}
}
/* End of file main.php */
/* Location: ./application/controllers/car.php */