<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mesas extends Private_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model(array('restaurant','platos'));
	}
	/*
	 * -------------------------------------------------------------------
	 *  MARKS
	 * -------------------------------------------------------------------
	 */
	public function start()
	{
		if(!@$this->user) redirect ('main');
		$user_data = (array)$this->session->userdata('logged_user');
		$user_data['title'] = 'mesas';
		
		$data['js'] = array(
			base_url()."static/js/library/alls.js",
			base_url()."static/js/users/mesas.js",
			base_url()."static/js/jquery.dataTables.min.js",
			base_url()."static/js/dataTables.bootstrap.js",
			base_url()."static/js/pnotify.custom.min.js"
		);
		
		
		$user_data['css'] = array(
			base_url()."static/css/pnotify.custom.min.css",
			base_url()."static/css/dataTables.bootstrap.css"
		);
		
		//$clientes['clientes']  = $this->clients->get_all();
		$this->load->view('templates/header', $user_data);
		//consultando sucursales
		if($user_data['tipo']=='A')
		{
			$user_data['numeracion'] = $this->restaurant->selectSQLMultiple("SELECT * FROM sucursal WHERE rst_id=?",($user_data['rst_id']));
		}
		else
		{
			$empleado = (array)$this->restaurant->selectSQL("SELECT * FROM empleado WHERE emp_id=?",array($user_data['rrhh_id']));
			$user_data['numeracion'] = $this->restaurant->selectSQLMultiple("SELECT * FROM sucursal WHERE rst_id=? AND suc_num=?",array($user_data['rst_id'], $empleado['suc_num']));
		}
		$this->load->view('user/mesas',$user_data);
		$this->load->view('templates/footer', $data);
	}
	
	/*
	 * -------------------------------------------------------------------
	 * MESAS
	 * -------------------------------------------------------------------
	 */
	public function delete_table()
	{
		if(!@$this->user) redirect ('main');
		if ($this->input->is_ajax_request()) 
    	{
			$ids=explode('-',$this->input->post('id'));
    		$user_data = (array)$this->session->userdata('logged_user');
			$data = array($user_data["rst_id"], $ids[0],  $ids[1]);
			$response = $this->restaurant->selectSQL("SELECT delete_table(?,?,?)",$data);
			echo json_encode($response->delete_table);
		}
		else
		{
			exit('No direct script access allowed');
			show_404();
		}
		return FALSE;
	}
	public function save_table()
	{
		if(!@$this->user) redirect ('main');
		if ($this->input->is_ajax_request()) 
    	{
			$user_data = (array)$this->session->userdata('logged_user');
			$data = array($user_data["rst_id"], $this->input->post('txtEstablecimiento'), $this->input->post('txtNumero')?$this->input->post('txtNumero'):"0", $this->input->post('txtCapacidad'));
			$response = $this->restaurant->selectSQL("SELECT insert_table(?,?,?,?)",$data);
			echo json_encode($response->insert_table);
		}
		else
		{
			exit('No direct script access allowed');
			show_404();
		}
		return FALSE;
	}
	
	public function get_tables_all()
	{
		$user_data = (array)$this->session->userdata('logged_user');
		if(!@$this->user) redirect ('main');
		if ($this->input->is_ajax_request()) 
    	{
    		$data = $this->restaurant->selectSQLMultiple('select m.*, s.suc_nom from mesas m, sucursal s where m.rst_id=s.rst_id and m.suc_num=s.suc_num and m.rst_id=?',array($user_data["rst_id"]));
			echo json_encode(array("data"=>$data));
		}
		else
		{
			exit('No direct script access allowed');
			show_404();
		}
		return FALSE;
	}
	
	public function get_tables_by_store()
	{
		$user_data = (array)$this->session->userdata('logged_user');
		if(!@$this->user) redirect ('main');
		if ($this->input->is_ajax_request()) 
    	{
    		$data = $this->restaurant->selectSQLMultiple('select m.*, s.suc_nom from mesas m, sucursal s where m.rst_id=s.rst_id and m.suc_num=s.suc_num and m.rst_id=? and m.suc_num=?',array($user_data["rst_id"],$this->input->post("id")));
			echo json_encode(array("data"=>$data));
		}
		else
		{
			exit('No direct script access allowed');
			show_404();
		}
		return FALSE;
	}
	
	public function find_table()
	{
		if(!@$this->user) redirect ('main');
		if ($this->input->is_ajax_request()) 
    	{
			$user_data = (array)$this->session->userdata('logged_user');
			$ids=explode('-',$this->input->post('id'));
    		$data = $this->restaurant->selectSQLMultiple('select m.*, s.suc_nom from mesas m, sucursal s where m.rst_id=s.rst_id and m.suc_num=s.suc_num and m.rst_id=? and m.suc_num=? and m.mss_num=?',array($user_data["rst_id"],$ids[0],$ids[1]));
			echo json_encode(array("data"=>$data));
		}
		else
		{
			exit('No direct script access allowed');
			show_404();
		}
		return FALSE;
	}
	
	public function edit_table()
	{
		if(!@$this->user) redirect ('main');
		if ($this->input->is_ajax_request()) 
    	{
			$user_data = (array)$this->session->userdata('logged_user');
			$ids=explode('-',$this->input->get('trId'));
    		$data = array($user_data["rst_id"], $ids[0], $ids[1], $this->input->post('txtCapacidadMd'));
			$response = $this->restaurant->selectSQL("SELECT update_table(?,?,?,?)",$data);
			echo json_encode($response->update_table);
		}
		else
		{
			exit('No direct script access allowed');
			show_404();
		}
		return FALSE;
	}
	
	public function save_order()
	{
		if(!@$this->user) redirect ('main');
		if ($this->input->is_ajax_request()) 
    	{
			$productos = explode(',', $this->input->get('prods'));
			$cantidad = explode(',', $this->input->get('cant'));
			$id = explode('-', $this->input->get('id'));
			
			$user_data = (array)$this->session->userdata('logged_user');
			$data = array(
				$user_data["rst_id"],
    			$id[0],
				$id[1],
				$this->input->post('chkIvaOrden')==true?true:false,
				$this->input->post('txtDescOrden'),
				"{".implode(",",$productos)."}",
				"{".implode(",",$cantidad)."}",
				$this->input->post('txtPropinaOrden')
    		);
			$response = $this->restaurant->selectSQL("SELECT insert_order_table(?,?,?,?,?,?,?,?)",$data);
			echo json_encode($response->insert_order_table);
		}
		else
		{
			exit('No direct script access allowed');
			show_404();
		}
		return FALSE;
	}
	
	public function find_order()
	{
		if(!@$this->user) redirect ('main');
		if ($this->input->is_ajax_request()) 
    	{
			$id = explode('-', $this->input->post('id'));
			
			$user_data = (array)$this->session->userdata('logged_user');
			$data = array(
				$user_data["rst_id"],
    			$id[0],
				$id[1]
    		);
			$response['general'] = $this->restaurant->selectSQL("select * from orden_mesa where rst_id=? and suc_num=? and mss_num=?",$data);
			$response['detalles'] = $this->restaurant->selectSQLMultiple("select d.prd_id, d.dem_cnt, d.dem_prc, p.prd_nom from detalles_orden_mesa d, producto p where d.rst_id=? and d.suc_num=? and d.mss_num=? and d.prd_id=p.prd_id",$data);
			if($this->input->post('bill')=="true" and sizeof($response['detalles'])>0)
			{
				$this->restaurant->selectSQL("SELECT delete_order_table(?,?,?)",$data);
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
}
/* End of file main.php */
/* Location: ./application/controllers/car.php */