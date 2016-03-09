<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cliente extends Private_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model(array('clients'));
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
		$user_data['title'] = 'bebidas';
		
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
		
		//$clientes['clientes']  = $this->clients->get_all();
		$this->load->view('templates/header', $user_data);
		$this->load->view('user/client',$user_data);
		$this->load->view('templates/footer', $data);
	}
	
	/*
	 * -------------------------------------------------------------------
	 * FUNCTIONS CLIENTS
	 * -------------------------------------------------------------------
	 */
	public function delete_client()
	{
		if(!@$this->user) redirect ('main');
		if ($this->input->is_ajax_request()) 
    	{
			$user_data = (array)$this->session->userdata('logged_user');
    		$data = array('cli_ced' => $this->input->post('id'),'rst_id'=>$user_data["rst_id"]);
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
			$user_data = (array)$this->session->userdata('logged_user');
			$data = array(
    			$this->input->post('txtCedula'),
				$this->input->post('txtNombre'),
				$this->input->post('txtApellido'),
				$this->input->post('txtDireccion'),
				$this->input->post('txtEmail'),
				$this->input->post('txtTelefono'),
				$user_data["rst_id"]
    		);
			$response = $this->clients->selectSQL("SELECT insert_client(?,?,?,?,?,?,?)",$data);
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
			$user_data = (array)$this->session->userdata('logged_user');
    		$data = $this->clients->selectSQLMultiple("SELECT * from cliente where rst_id=?",array($user_data["rst_id"]));
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
				$user_data = (array)$this->session->userdata('logged_user');
    			$data= array('cli_ced' => $this->input->post('id'),'rst_id'=>$user_data["rst_id"]);
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
			$user_data = (array)$this->session->userdata('logged_user');
			$data = array(
				$this->input->get('trId'),
				$this->input->post('txtNombreMd'),
				$this->input->post('txtApellidoMd'),
				$this->input->post('txtDireccionMd'),
				$this->input->post('txtEmailMd'),
				$this->input->post('txtTelefonoMd'),
				$user_data["rst_id"]
    		);
			
			$response = $this->clients->selectSQL("SELECT update_client(?,?,?,?,?,?,?)",$data);
			echo json_encode($response);
		}
		else
		{
			exit('No direct script access allowed');
			show_404();
		}
		return FALSE;
	}
	
	public function search_autocomplete()
	{
		$row_set=array();
		$user_data = (array)$this->session->userdata('logged_user');
    	$data = $this->clients->selectSQLMultiple("SELECT * from cliente where rst_id=? and cli_ced ilike ?||'%'",array($user_data["rst_id"], $this->input->get('term')));;
		foreach ($data as $valor)
		{
				$row['value']=$valor["cli_ced"];
				$row['id']=$valor["cli_ced"];
				$row_set[] = $row;//build an array
		}
		echo json_encode($row_set);
	}
	
}
/* End of file main.php */
/* Location: ./application/controllers/car.php */