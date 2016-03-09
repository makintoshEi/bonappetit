<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Perfil extends Private_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model(array('restaurant'));
	}
	/*
	 * -------------------------------------------------------------------
	 *  PROFILE
	 * -------------------------------------------------------------------
	 */
	public function start()
	{
		if(!@$this->user) redirect ('main');
		$user_data = (array)$this->session->userdata('logged_user');
		$data['js'] = array(
			base_url()."static/js/library/alls.js",
			base_url()."static/js/library/files.js",
			base_url()."static/js/pnotify.custom.min.js",
			base_url()."static/js/login.js",
			base_url()."static/js/users/perfil.js"
		);
		
		$user_data['css'] = array(
			base_url()."static/css/pnotify.custom.min.css",
			base_url()."static/css/dataTables.bootstrap.css"
		);
		
		$user_data['title'] = utf8_encode ('perfíl');
		$this->load->view('templates/header', $user_data);
		$user_data['provincias'] = $this->restaurant->selectSQLMultiple("SELECT * FROM provincia",null);
		$this->load->view('user/perfil',$user_data);
		$this->load->view('templates/footer', $data);
	}
	
	/* =========================>>> UPDATE PROFILE DATA <<<========================= */
	public function update_logo()
	{
		if(!@$this->user) redirect ('main');
		if ($this->input->is_ajax_request()) 
    	{	
			//$path = "uploads/logos/";
			$filesUrl = $_FILES;
			$user_data = (array)$this->session->userdata('logged_user');
			$new_name = $user_data["rst_id"].'_logo';
			$this->do_upload($new_name, 'images',true);
			$tipo=explode('/',$filesUrl['images']['type'][0]);
			//$new_name = 'uploads/logos/'.$new_name.'.'.$tipo[1];
			$new_name = 'uploads/logos/'.$new_name.'.jpg';
			$data = array(
				'rst_url'=>$new_name
			);
			$response = $this->restaurant->update($user_data['rst_id'],$data);
			if($response)
			{
				$logged_user = $this->restaurant->selectSQL("SELECT rst_id, rst_nom, rst_hab, rst_url, 'A' AS tipo, 0::bigint AS rrhh_id, '' AS rrhh_nom, rst_inf, rst_ent_dom from profile_view where rst_id=?",array($user_data["rst_id"]));
				$logged_user->modulos = (array)$this->restaurant->selectSQLMultiple("SELECT * FROM modulos_permisos_view WHERE rst_id=".$logged_user->rst_id,null);
				$logged_user->tributario = (array)$this->restaurant->selectSQL("SELECT * FROM tributario WHERE rst_id=".$logged_user->rst_id,null);
				$this->session->set_userdata('logged_user', $logged_user);
			}
			echo json_encode($response);
		}
		else
		{
			exit('No direct script access allowed');
		}
		return FALSE;
	}
	
	function do_upload($ci,$name,$edt=false)
	{
	    $files = $_FILES;
	    $cpt = count($_FILES[$name]['name']);
	    if($edt)
		{	
			$dir = opendir('./uploads/logos/');
			while ($file = readdir($dir)) 
			{ 
				if($file != '.' && $file != '..' && $file!=".DS_Store")
				{
					if (strpos($file, $ci)!==false){
						unlink('./uploads/logos/'.$file);
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
			//move_uploaded_file($_FILES[$name]['tmp_name'], './uploads/logos/'.$new_name);
		    //codigo de prueba para redimensionar imagen
			$ruta_imagen = $files[$name]['tmp_name'][$i];

			$miniatura_ancho_maximo = 100;
			$miniatura_alto_maximo = 100;

			$info_imagen = getimagesize($ruta_imagen);
			$imagen_ancho = $info_imagen[0];
			$imagen_alto = $info_imagen[1];
			$imagen_tipo = $info_imagen['mime'];


			$proporcion_imagen = $imagen_ancho / $imagen_alto;
			$proporcion_miniatura = $miniatura_ancho_maximo / $miniatura_alto_maximo;

			if ( $proporcion_imagen > $proporcion_miniatura ){
				$miniatura_ancho = $miniatura_ancho_maximo;
				$miniatura_alto = $miniatura_ancho_maximo / $proporcion_imagen;
			} else if ( $proporcion_imagen < $proporcion_miniatura ){
				$miniatura_ancho = $miniatura_ancho_maximo * $proporcion_imagen;
				$miniatura_alto = $miniatura_alto_maximo;
			} else {
				$miniatura_ancho = $miniatura_ancho_maximo;
				$miniatura_alto = $miniatura_alto_maximo;
			}
			
			switch ( $imagen_tipo ){
				case "image/jpg":
				case "image/jpeg":
					$imagen = imagecreatefromjpeg( $ruta_imagen );
					break;
				case "image/png":
					$imagen = imagecreatefrompng( $ruta_imagen );
					break;
				case "image/gif":
					$imagen = imagecreatefromgif( $ruta_imagen );
					break;
			}
			
			$lienzo = imagecreatetruecolor( $miniatura_ancho, $miniatura_alto );

			imagecopyresampled($lienzo, $imagen, 0, 0, 0, 0, $miniatura_ancho, $miniatura_alto, $imagen_ancho, $imagen_alto);


			imagejpeg($lienzo, './uploads/logos/'.$ci.'.jpg', 80);
	    }
		if ($cpt>1)
		{
			return true;
		}
		else{
			return false;
		}
	}
	//--------------------------------//
	
	function do_upload_sign($ci,$name,$edt=false)
	{
	    $files = $_FILES;
	    $cpt = count($_FILES[$name]['name']);
	    if($edt)
		{	
			$dir = opendir('./uploads/signs/');
			while ($file = readdir($dir)) 
			{ 
				if($file != '.' && $file != '..' && $file!=".DS_Store")
				{
					if (strpos($file, $ci)!==false){
						unlink('./uploads/signs/'.$file);
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
			$datos=explode('.',$_FILES[$name]['name']);
			$new_name = $ci.'.'.($datos[sizeof($datos)-1]);
			move_uploaded_file($_FILES[$name]['tmp_name'], './uploads/signs/'.$new_name);
		    
	    }
		if ($cpt>1)
		{
			return true;
		}
		else{
			return false;
		}
	}
	
	/*
	 * -------------------------------------------------------------------
	 * PROFILE
	 * -------------------------------------------------------------------
	 */
	
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
	
	public function edit_profile()
	{
		if(!@$this->user) redirect ('main');
		if ($this->input->is_ajax_request()) 
    	{
			$user_data = (array)$this->session->userdata('logged_user');
			$new_name='';
			if(!empty($_FILES['firma']['name'][0]))
			{
				$filesUrl = $_FILES;
				$new_name = $user_data["rst_id"].'_sign';
				$this->do_upload_sign($new_name, 'firma',true);
				$tipo=explode('/',$filesUrl['firma']['type'][0]);
				$datos=explode('.',$_FILES['firma']['name']);
				$new_name = $new_name.'.'.($datos[sizeof($datos)-1]);
				//$new_name = 'uploads/signs/'.$new_name.'.'.$tipo[1];
			}
			$data = array(
					$user_data["rst_id"],
					$this->input->post('txtRUC'),
					$this->input->post('txtContEsp'),
					$this->input->post('txtObg')?true:false,
					$this->input->post('txtNombreCom'),
					$_FILES?$new_name:'',
					$this->input->post('txtPssFirma'),
					$this->input->post('txtNombre'),
					$this->input->post('txtInfo'),
					$this->input->post('txtDmc')?true:false
				);
			$response = $this->restaurant->selectSQL("SELECT editar_perfil(?,?,?,?,?,?,?,?,?,?)",$data);
			if($response->editar_perfil=='1')
			{
				$logged_user = $this->restaurant->selectSQL("SELECT rst_id, rst_nom, rst_hab, rst_url, 'A' AS tipo, 0::bigint AS rrhh_id, '' AS rrhh_nom, rst_inf, rst_ent_dom from profile_view where rst_id=?",array($user_data["rst_id"]));
				$logged_user->modulos = (array)$this->restaurant->selectSQLMultiple("SELECT * FROM modulos_permisos_view WHERE rst_id=".$logged_user->rst_id,null);
				$logged_user->tributario = (array)$this->restaurant->selectSQL("SELECT * FROM tributario WHERE rst_id=".$logged_user->rst_id,null);
				$this->session->set_userdata('logged_user', $logged_user);
			}
			echo json_encode($response->editar_perfil);
		}
		else
		{
			exit('No direct script access allowed');
			show_404();
		}
		return FALSE;
	}
	
	public function update_profile_data()
	{
		if(!@$this->user) redirect ('main');
		if ($this->input->is_ajax_request()) 
    	{	
			$user_data = (array)$this->session->userdata('logged_user');
			$domicilio=false;
			if($this->input->post('txtDmc'))
				$domicilio=true;
			$data = array(
				$this->input->post('txtInfo'),
				$this->input->post('txtTlf'),
				$domicilio,
				$this->input->post('slcCiu'),
				$this->input->post('txtDir'),
				$this->input->post('txtLunes'),
				$this->input->post('txtMartes'),
				$this->input->post('txtMiercoles'),
				$this->input->post('txtJueves'),
				$this->input->post('txtViernes'),
				$this->input->post('txtSabado'),
				$this->input->post('txtDomingo'),
				$user_data["rst_id"],
				$user_data["rst_act"],
				$user_data["rst_url"]
			);
			$response = $this->restaurant->selectSQL("SELECT update_profile(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)",$data);
			if($response->update_profile=='1')
			{
				$logged_user = $this->restaurant->selectSQL("SELECT * from perfil where rst_id=?",array($user_data["rst_id"]));
				$this->session->set_userdata('logged_user', $logged_user);
			}
			echo json_encode($response);
		}
		else
		{
			exit('No direct script access allowed');
		}
		return FALSE;
	}
}
/* End of file main.php */
/* Location: ./application/controllers/car.php */