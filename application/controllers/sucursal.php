<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sucursal extends Private_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model(array('store','rrhh'));
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
		$user_data['title'] = 'sucursales';
		$data['js'] = array(
			"http://code.jquery.com/ui/1.10.2/jquery-ui.js",
			"http://maps.googleapis.com/maps/api/js?v3&key=AIzaSyDYMmVNvltSy7O_BCGOr8-qihtna4JaC-A",
			base_url()."static/js/library/alls.js",
			base_url()."static/js/users/sucursal.js",
			base_url()."static/js/jquery.dataTables.min.js",
			base_url()."static/js/dataTables.bootstrap.js",
			base_url()."static/js/pnotify.custom.min.js"
		);
		
		
		$user_data['css'] = array(
			"http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css",
			base_url()."static/css/pnotify.custom.min.css",
			base_url()."static/css/style_2.css",
			base_url()."static/css/dataTables.bootstrap.css"
		);
		
		$info['provincias'] = $this->rrhh->selectSQLMultiple("SELECT * FROM provincia",null);
		
		$this->load->view('templates/header', $user_data);
		$this->load->view('user/sucursal',$info);
		$this->load->view('templates/footer', $data);
	}
	
	public function start_2()
	{
		if(!@$this->user) redirect ('main');
		$user_data = (array)$this->session->userdata('logged_user');
		$user_data['title'] = 'sucursales';
		$data['js'] = array(
			"http://code.jquery.com/ui/1.10.2/jquery-ui.js",
			"http://maps.googleapis.com/maps/api/js?v3&key=AIzaSyDYMmVNvltSy7O_BCGOr8-qihtna4JaC-A",
			base_url()."static/js/library/alls.js",
			base_url()."static/js/users/sucursal.js",
			base_url()."static/js/jquery.dataTables.min.js",
			base_url()."static/js/dataTables.bootstrap.js",
			base_url()."static/js/pnotify.custom.min.js"
		);
		
		
		$user_data['css'] = array(
			"http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css",
			base_url()."static/css/pnotify.custom.min.css",
			base_url()."static/css/style_2.css",
			base_url()."static/css/dataTables.bootstrap.css"
		);
		
		$info['provincias'] = $this->rrhh->selectSQLMultiple("SELECT * FROM provincia",null);
		
		$this->load->view('templates/header', $user_data);
		$this->load->view('user/sucursal_2',$info);
		$this->load->view('templates/footer', $data);
	}
	
	/*
	 * -------------------------------------------------------------------
	 * FUNCTIONS SUCURSAL
	 * -------------------------------------------------------------------
	 */
	public function delete_sucursal()
	{
		if(!@$this->user) redirect ('main');
		if ($this->input->is_ajax_request()) 
    	{
			$user_data = (array)$this->session->userdata('logged_user');
    		$data = array($this->input->post('id'), $user_data["rst_id"]);
			$response = $this->store->selectSQL("SELECT delete_sucursal(?,?)",$data);
			echo json_encode($response->delete_sucursal=="1"?true:false);
		}
		else
		{
			exit('No direct script access allowed');
			show_404();
		}
		return FALSE;
	}
	public function save_sucursal()
	{
		if(!@$this->user) redirect ('main');
		if ($this->input->is_ajax_request()) 
    	{
			$rrhh = explode(',', $this->input->get('rrhh'));
			$user_data = (array)$this->session->userdata('logged_user');
			$data = array(
    			$this->input->post('txtNumero'),
				$this->input->post('txtNombre'),
				$this->input->post('txtDir'),
				$this->input->post('txtTlf'),
				false,
				$this->input->get('lat'),
				$this->input->get('lng'),
				$user_data["rst_id"],
				$this->input->post('slcCiu'),
				$this->input->post('txtLunes'),
				$this->input->post('txtMartes'),
				$this->input->post('txtMiercoles'),
				$this->input->post('txtJueves'),
				$this->input->post('txtViernes'),
				$this->input->post('txtSabado'),
				$this->input->post('txtDomingo'),
				"{".implode(",",$rrhh)."}"
    		);
			$response = $this->store->selectSQL("SELECT insert_sucursal(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)",$data);
			echo json_encode($response);
		}
		else
		{
			exit('No direct script access allowed');
			show_404();
		}
		return FALSE;
	}
	
	public function get_sucursal_all()
	{
		if(!@$this->user) redirect ('main');
		if ($this->input->is_ajax_request()) 
    	{
    		$user_data = (array)$this->session->userdata('logged_user');
			$data = $this->store->selectSQLMultiple("select s.*, p.prv_nom||' / '||c.ciu_nom as place from sucursal s, ciudad c, provincia p where s.ciu_id=c.ciu_id and c.prv_id=p.prv_id and s.rst_id=?",array($user_data["rst_id"]));
			echo json_encode(array("data"=>$data));
		}
		else
		{
			exit('No direct script access allowed');
			show_404();
		}
		return FALSE;
	}
	
	public function search_sucursal_by_id()
	{
		if(!@$this->user) redirect ('main');
		if ($this->input->is_ajax_request()) 
    	{
			$response=null;
    		if($this->input->post('id'))
    		{
				$user_data = (array)$this->session->userdata('logged_user');
    			$response['general'] = $this->store->selectSQL("select s.*, p.prv_nom||' / '||c.ciu_nom, p.prv_id, c.ciu_id as place from sucursal s, ciudad c, provincia p where s.ciu_id=c.ciu_id and c.prv_id=p.prv_id and s.rst_id=? and suc_num=?",array($user_data["rst_id"],$this->input->post('id')));
				$response['horario'] = $this->store->selectSQL("select * from horario where rst_id=? and suc_num=?",array($user_data["rst_id"],$this->input->post('id')));
				$response['empleados'] = $this->store->selectSQLMultiple("select emp_id, emp_ced, emp_nom||' '||emp_ape as nombre, emp_eml from empleado where rst_id=? and suc_num=?",array($user_data["rst_id"],$this->input->post('id')));
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
	
	public function edit_sucursal()
	{
		if(!@$this->user) redirect ('main');
		if ($this->input->is_ajax_request()) 
    	{
			$rrhh = explode(',', $this->input->get('rrhh'));
			$user_data = (array)$this->session->userdata('logged_user');
			$data = array(
    			$this->input->get('id'),
				$this->input->post('txtNombreMd'),
				$this->input->post('txtDirMd'),
				$this->input->post('txtTlfMd'),
				false,
				$this->input->get('lat'),
				$this->input->get('lng'),
				$user_data["rst_id"],
				$this->input->post('slcCiuMd'),
				$this->input->post('txtLunesMd'),
				$this->input->post('txtMartesMd'),
				$this->input->post('txtMiercolesMd'),
				$this->input->post('txtJuevesMd'),
				$this->input->post('txtViernesMd'),
				$this->input->post('txtSabadoMd'),
				$this->input->post('txtDomingoMd'),
				"{".implode(",",$rrhh)."}"
    		);
			$response = $this->store->selectSQL("SELECT update_sucursal(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)",$data);
			echo json_encode($response);
		}
		else
		{
			exit('No direct script access allowed');
			show_404();
		}
		return FALSE;
	}
	
	 public function search_autocomplete_products()
	{
		$row_set=array();
		$user_data = (array)$this->session->userdata('logged_user');
		$valor = $this->input->get('term');
		$data = $this->rrhh->selectSQLMultiple("SELECT * FROM empleado where (emp_nom ||' '|| emp_ape ilike '%'||'".$valor."'||'%' OR emp_ced ilike '%'||'".$valor."'||'%') and rst_id=".$user_data['rst_id'],null);
		foreach ($data as $valor)
		{
				$row['value']=$valor["emp_ced"]." - ".$valor["emp_nom"]." ".$valor["emp_ape"];
				$row['id']=$valor["emp_id"].";e-i;".$valor["emp_nom"].' '.$valor["emp_ape"].";e-i;".$valor["emp_ced"].";e-i;".$valor["emp_eml"];
				$row_set[] = $row;//build an array
		}
		echo json_encode($row_set);
	}
	
}
/* End of file main.php */
/* Location: ./application/controllers/car.php */