<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Menu extends Private_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model(array('restaurant'));
	}
	/*
	 * -------------------------------------------------------------------
	 *  MENU
	 * -------------------------------------------------------------------
	 */
	public function start()
	{
		if(!@$this->user) redirect ('main');
		$user_data = (array)$this->session->userdata('logged_user');
		$user_data['title'] = 'men&uacute;';
		
		$data['js'] = array(
			base_url()."static/js/library/alls.js",
			base_url()."static/js/users/clients.js",
			base_url()."static/js/jquery.dataTables.min.js",
			base_url()."static/js/dataTables.bootstrap.js",
			base_url()."static/js/pnotify.custom.min.js",
			base_url()."static/js/jquery.ci.validator.js",
			base_url()."static/js/library/validateForms/jquery.validate.min.js",
			base_url()."static/js/library/validateForms/localization/messages_es.min.js"
		);
		
		
		$user_data['css'] = array(
			base_url()."static/css/pnotify.custom.min.css",
			base_url()."static/css/dataTables.bootstrap.css"
		);
		$data['funcion']="<script type='text/javascript'> $('#divTbPlatos').fadeIn('fast'); $('#divTbBebidas').fadeIn('fast');</script>";
		//$clientes['clientes']  = $this->clients->get_all();
		$this->load->view('templates/header', $user_data);
		$this->load->view('user/menu');
		$this->load->view('templates/footer', $data);
	}
	
	/*
	 * -------------------------------------------------------------------
	 * MODELS
	 * -------------------------------------------------------------------
	 */
	public function delete_client()
	{
		if(!@$this->user) redirect ('main');
		if ($this->input->is_ajax_request()) 
    	{
    		$data = array('cli_id' => $this->input->post('id'));
			$response = $this->clients->delete($data);
			echo json_encode($response);
		}
		else
		{
			exit('No direct script access allowed');
			show_404();
		}
		return FALSE;
	}
	public function save_client()
	{
		if(!@$this->user) redirect ('main');
		if ($this->input->is_ajax_request()) 
    	{
			$telefonos = explode(',', $this->input->get('tels'));
			
			$data = array(
    			$this->input->post('txtCedula'),
				$this->input->post('txtNombre'),
				$this->input->post('txtApellido'),
				$this->input->post('txtDireccion'),
				$this->input->post('txtEmail'),
				"{".implode(",",$telefonos)."}"
    		);
			
			$response = $this->clients->selectSQL("SELECT insert_client(?,?,?,?,?,?)",$data);
			echo json_encode($response);
		}
		else
		{
			exit('No direct script access allowed');
			show_404();
		}
		return FALSE;
	}
	
	public function get_clients_all()
	{
		if(!@$this->user) redirect ('main');
		if ($this->input->is_ajax_request()) 
    	{
    		$data = $this->clients->get_all();
			echo json_encode(array("data"=>$data));
		}
		else
		{
			exit('No direct script access allowed');
			show_404();
		}
		return FALSE;
	}
	
	public function search_client_by_id()
	{
		if(!@$this->user) redirect ('main');
		if ($this->input->is_ajax_request()) 
    	{
    		if($this->input->post('id'))
    		{
    			$data= array('cli_id' => $this->input->post('id'));
    		}
			elseif($this->input->post('ci')){
				$data= array('per_ced' => $this->input->post('ci'));
			}
			
    		$response = $this->clients->get($data);
			echo json_encode($response);
		}
		else
		{
			exit('No direct script access allowed');
			show_404();
		}
		return FALSE;
	}
	
	public function edit_client()
	{
		if(!@$this->user) redirect ('main');
		if ($this->input->is_ajax_request()) 
    	{
			$telefonos = explode(',', $this->input->get('tels'));
			
			$data = array(
				$this->input->post('txtNombreMd'),
				$this->input->post('txtApellidoMd'),
				$this->input->post('txtDireccionMd'),
				$this->input->post('txtEmailMd'),
				$this->input->get('trId'),
				"{".implode(",",$telefonos)."}"
    		);
			
			$response = $this->clients->selectSQL("SELECT update_client(?,?,?,?,?,?)",$data);
			echo json_encode($response);
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