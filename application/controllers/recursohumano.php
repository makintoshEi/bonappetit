<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//require_once($_SERVER["DOCUMENT_ROOT"].'/application/libraries/metodos.php');

class RecursoHumano extends Private_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model(array('rrhh'));
	}
	/*
	 * -------------------------------------------------------------------
	 *  RRHH
	 * -------------------------------------------------------------------
	 */
	public function start()
	{
		if(!@$this->user) redirect ('main');
		$user_data = (array)$this->session->userdata('logged_user');
		$user_data['title'] = 'Recursos Humanos';
		$data['js'] = array(
			base_url()."static/js/library/alls.js",
			base_url()."static/js/users/rrhh.js",
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
		$this->load->view('user/recursohumano',$user_data);
		$this->load->view('templates/footer', $data);
	}
	
	public function start_2()
	{
		if(!@$this->user) redirect ('main');
		$user_data = (array)$this->session->userdata('logged_user');
		$user_data['title'] = 'Recursos Humanos';
		$data['js'] = array(
			base_url()."static/js/library/alls.js",
			base_url()."static/js/users/rrhh.js",
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
		
		$roles = $this->rrhh->selectSQLMultiple("SELECT * FROM rol ORDER BY rol_id",null);
		$user_data['roles'] = $roles;
		$modulos = $this->rrhh->selectSQLMultiple("SELECT * FROM modulo ORDER BY mod_id",null);
		$data['funcion']="<script type='text/javascript'> var modulos_acceso={}; modulos= new Array(); roles=new Array();";
		foreach ($modulos as $keyA => $valueA) 
		{
			$data['funcion'].="modulos_acceso={}; modulos_acceso.id='".$valueA["mod_id"]."'; modulos_acceso.nombre='".$valueA["mod_nom"]."'; modulos_acceso.icono='".base_url().$valueA["mod_img"]."'; modulos_acceso.nivel='".$valueA["mod_niv"]."'; modulos.push(modulos_acceso); ";
		}
		foreach ($roles as $keyA => $valueA) 
		{
			$data['funcion'].="roles.push(['".$valueA["rol_id"]."','".$valueA["rol_niv"]."']); ";
		}
		$data['funcion'].="$('#slcRol').change();</script>";
		$this->load->view('templates/header', $user_data);
		$this->load->view('user/recursohumano_v2',$user_data);
		$this->load->view('templates/footer', $data);
	}
	
	/*
	 * -------------------------------------------------------------------
	 * FUNCTIONS CLIENTS
	 * -------------------------------------------------------------------
	 */
	public function delete_rrhh()
	{
		if(!@$this->user) redirect ('main');
		if ($this->input->is_ajax_request()) 
    	{
			$data = array('emp_id' => $this->input->post('id'));
			$response = $this->rrhh->delete($data);
			echo json_encode($response);
		}
		else
		{
			exit('No direct script access allowed');
			show_404();
		}
		return FALSE;
	}
	
	public function insert_rrhh()
	{
		if(!@$this->user) redirect ('main');
		if ($this->input->is_ajax_request()) 
    	{
			$this->load->helper('security');
			$this->load->library('metodos');
			$codigo=$this->metodos->generateCode(6);
			$user_data = (array)$this->session->userdata('logged_user');
			$data = array(
    			$this->input->post('txtCedula'),
				$this->input->post('txtNombre'),
				$this->input->post('txtApellido'),
				$this->input->post('txtEmail'),
				do_hash($codigo, 'md5'),
				$user_data["rst_id"],
				$this->input->post('slcRol'),
				$this->input->post('txtSalario')
    		);
			$response = $this->rrhh->selectSQL("SELECT insert_rrhh(?,?,?,?,?,?,?,?)",$data);
			$cuerpo='<div align="center"><h2><strong>BON APPÉTIT</strong></h2></div></br><h3>Te damos la bienvenida a Bon Appétit, tu guía gastronómica.</h3></br><h3><strong>'.$user_data["rst_nom"].'</strong> te ha añadido a su equipo de Recursos Humanos, ahora podras iniciar sesión en <a href="http://bonappetit.encoding-ideas.com">Bon Appétit</a>.<br> Usuario: <strong>'.$this->input->post('txtEmail').'</strong><br>Contraseña: <strong>'.$codigo.'</strong><br/>Recuerda esta información es de caracter privado y no debe ser proporcionada a terceros.</h3>';
			if($response->insert_rrhh>0)
			{
				$this->metodos->sendMail('Bienvenido', $cuerpo, $this->input->post('txtEmail'),'encid_6822');
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
	
	public function get_rrhh_all()
	{
		if(!@$this->user) redirect ('main');
		if ($this->input->is_ajax_request()) 
    	{
			$user_data = (array)$this->session->userdata('logged_user');
			$data = $this->rrhh->selectSQLMultiple("SELECT e.*, r.rol_id, r.rol_dsc from empleado e, rol r where rst_id=? and e.rol_id=r.rol_id",array($user_data["rst_id"]));
    		echo json_encode(array("data"=>$data));
		}
		else
		{
			exit('No direct script access allowed');
			show_404();
		}
		return FALSE;
	}
	
	public function search_rrhh_by_id()
	{
		if(!@$this->user) redirect ('main');
		if ($this->input->is_ajax_request()) 
    	{
    		if($this->input->post('id'))
    		{
				$user_data = (array)$this->session->userdata('logged_user');
    			$data= array('emp_id' => $this->input->post('id'));
    		}
			
			
			$response = $this->rrhh->get($data);
			if($response->suc_num!=null)
				$response = $this->rrhh->selectSQL("SELECT * FROM (select * from empleado where emp_id=?) a, (select suc_num, suc_nom, suc_dir, ciu_nom, rst_id  from sucursal s, ciudad c where s.ciu_id=c.ciu_id) b WHERE a.rst_id=b.rst_id AND a.suc_num=b.suc_num",array($this->input->post('id')));
			echo json_encode($response);
		}
		else
		{
			exit('No direct script access allowed');
			show_404();
		}
		return FALSE;
	}
	
	public function edit_rrhh()
	{
		if(!@$this->user) redirect ('main');
		if ($this->input->is_ajax_request()) 
    	{
			$data = array(
				$this->input->get('trId'),
				$this->input->post('txtNombreMd'),
				$this->input->post('txtApellidoMd'),
				$this->input->post('txtEmailMd'),
				$this->input->post('slcRolMd'),
				$this->input->post('txtSalarioMd')
			);
			
			$response = $this->rrhh->selectSQL("SELECT update_rrhh(?,?,?,?,?,?)",$data);
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