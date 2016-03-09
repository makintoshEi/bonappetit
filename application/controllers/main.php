<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends Private_Controller {

	function __construct() {
		parent::__construct();
		//$this->removeCache();
		
		// Se carga el modelo de usuarios.
		$this->load->model(array('restaurant','rrhh'));
	}
	
	public function index()
	{	
		$this->load->helper('form');
		$this->load->view('login');
	}
	
	public function news()
	{
		if(!@$this->user) redirect ('main');
		$data['js'] = array(
			base_url()."static/js/library/alls.js",
			base_url()."static/js/users/user.js",
			base_url()."static/js/bootstrap-select.min.js",
			base_url()."static/js/i18n/defaults-es_CL.min.js",
			base_url()."static/js/pnotify.custom.min.js"
			);
		$user_data = (array)$this->session->userdata('logged_user');
		$user_data['title'] = 'novedades';
		$user_data['css'] = array(base_url()."static/css/pnotify.custom.min.css");
		$info['news'] = $this->restaurant->selectSQLMultiple("SELECT * FROM novedades",null);
		$info['promos'] = $this->restaurant->selectSQLMultiple("SELECT * FROM promocion WHERE pro_fch_ini<=current_date and pro_fch_fin>=current_date",null);
		
		$this->load->view('templates/header', $user_data);
		$this->load->view('user/novedades',$info);
		$this->load->view('templates/footer',$data);
	}
	
	private function verificar_registro()
	{
		$user_data = (array)$this->session->userdata('logged_user');
		// Si existe el usuario creamos la sesion y redirigimos al index.
		if($user_data["rst_hab"]=='f')
		{	
			redirect('main/register/');
		}
	}
	
	private function generateCode($size)
	{
		$str = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
		$cad = "";
		for($i=0;$i<$size;$i++) 
		{
			$cad .= substr($str,rand(0,62),1);
		}
		return $cad;
	}
	
	public function signup()
	{
		$codigo=$this->generateCode(6);
		$this->load->helper('security');
		if ($this->input->is_ajax_request()) 
    	{
			$data = array(
				$this->input->post('txtName'),
				$this->input->post('txtEmail'),
				do_hash($this->input->post('txtPass'), 'md5'),
				$codigo
    		);
			$response = $this->restaurant->selectSQL("SELECT signup(?,?,?,?)",$data);
			$cuerpo='<div align="center"><h2><strong>BON APPÉTIT</strong></h2></div></br><h3>Te damos la bienvenida a Bon Appétit, tu guía gastronómica.</h3></br><h3>El código de activación de tu cuenta es:<strong>'.$codigo.'</strong></h3>';
			if($response->signup>0)
			{
				$this->sendMail('Bienvenido', $cuerpo, $this->input->post('txtEmail'));
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
	
	public function generate_pass()
	{
		$codigo=$this->generateCode(9);
		$this->load->helper('security');
		if ($this->input->is_ajax_request()) 
    	{
			$data = array(
				do_hash($codigo, 'md5'),
				$this->input->post('txtEmailClv')
    		);
			$response = $this->restaurant->selectSQL("SELECT update_pass(?,?)",$data);
			$cuerpo='<div align="center"><h2><strong>BON APPETIT</strong></h2></div></br><h3>Se ha generado una nueva contraseña para tu cuenta. Tu nueva contraseña es: <strong>'.$codigo.'</strong><br/>Recuerda esta información es de carácter privado y no debe ser proporcionada a terceros.</h3><br/>';
			if($response->update_pass>0)
			{
				$this->sendMail('Nueva de contraseña', $cuerpo, $this->input->post('txtEmailClv'));
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
	
	private function sendMail($asunto, $cuerpo, $para)
	{
		//if(!@$this->user) redirect ('main');
		//contenido html del mensaje a enviar
		$html='<div style=" font-family: Arial, Helvetica, sans-serif;" align="center">
		<img src="http://bonappetit.encoding-ideas.com/static/img/logo.png" height="60"/>
		</div>'.$cuerpo.'</br><div align="right">
		<img align="right" src="http://bonappetit.encoding-ideas.com/static/img/logo_completo.png" height="50"/>
		</div>';
		//cargamos la libreria email de ci
		$this->load->library("email");
		//configuracion para gmail
		$configGmail = array(
			'protocol' => 'smtp',
			'smtp_host' => 'ssl://smtp.gmail.com',
			'smtp_port' => 465,
			'smtp_user' => 'alex.nb.92@gmail.com',
			'smtp_pass' => '21792Alexander',
			'mailtype' => 'html',
			'charset' => 'utf-8',
			'newline' => "\r\n"
		);    
		$configGmail = array(
			'protocol' => 'smtp',
			'smtp_host' => 'smtp.encoding-ideas.com',
			'smtp_port' => 587,
			'smtp_user' => 'info@encoding-ideas.com',
			'smtp_pass' => 'encid_2015',
			'mailtype' => 'html',
			'charset' => 'utf-8',
			'newline' => "\r\n"
		);   
		//cargamos la configuración para enviar con gmail
		$this->email->initialize($configGmail);
		$this->email->from('info@encoding-ideas.com');
		$this->email->to($para);
		$this->email->subject($asunto);
		$this->email->message(utf8_decode (utf8_encode ($html)));
		$this->email->send();
		//con esto podemos ver el resultado
		/*if(strlen($this->email->print_debugger())<20){
			echo json_encode(true);
		}
		else
		{
			echo $this->email->print_debugger();
			echo json_encode(false);
		}*/
	}
	
	public function signin() {
		$data=null;
		// Se carga el helper form y security.
		$this->load->helper(array('form', 'security'));

 		$username = $this->input->post('txtEmail');
 		$passwd   = do_hash($this->input->post('txtPass'), 'md5'); 
 		
		// Si username y password existen en post
		if($username && $passwd) {
			// Obtenemos la informacion del usuario desde el modelo users.
			//$logged_user = $this->restaurant->selectSQL("SELECT * from perfil where rst_eml=? and rst_pss=?",array($username, $passwd));
			$logged_user = $this->restaurant->selectSQL("SELECT * from signin(?,?)",array($username, $passwd));
			//$modulos = $this->restaurant->selectSQLMultiple("SELECT * FROM modulos_permisos_view WHERE rst_id=".$logged_user->rst_id,null);
				
			// Si existe el usuario creamos la sesion y redirigimos al index.
			if($logged_user->rst_id) {
				$logged_user->modulos = (array)$this->restaurant->selectSQLMultiple("SELECT * FROM modulos_permisos_view WHERE rst_id=".$logged_user->rst_id,null);
				$logged_user->tributario = (array)$this->restaurant->selectSQL("SELECT * FROM tributario WHERE rst_id=".$logged_user->rst_id,null);
				$this->session->set_userdata('logged_user', $logged_user);
				//$this->session->set_userdata('modulos', $modulos);
				if($logged_user->rst_hab=='t')
				{	
					redirect('main/home/');
				}
				else
				{
					redirect('main/register/');
				}
				
			} else {
				// De lo contrario se activa el error_login.
				$data['error_login'] = TRUE;
			}
		}
		if($data)
		{
			$this->load->view('login', $data);
		}
		else
		{
			$this->load->view('login');
		}
	}
	
	public function home()
	{
		if(!@$this->user) redirect ('main');
		$this->verificar_registro();
		$user_data = (array)$this->session->userdata('logged_user');
		$user_data['title'] = 'home';
		//$user_data['modulos'] = (array)$this->session->userdata('modulos');
		$data['funcion']="<script type='text/javascript'> seleccionar('mn_home') </script>";
		$user_data['mensaje'] = "La información del <strong>Perfíl</strong> está incompleta, es necesario que complete los datos del perfíl para el correcto funcionamiento del sistema.";
		$this->load->view('templates/header', $user_data);
		$data['funcion'] = $user_data["tributario"]['trb_ruc']==''||$user_data["tributario"]['trb_frm']==''?"<script type='text/javascript'> 
										$('#mdAviso').modal('show');
							</script>":"";
		$this->load->view('user/inicio');
		$this->load->view('templates/footer',$data);
	}
	
	public function register()
	{
		if(!@$this->user) redirect ('main');
		$user_data = (array)$this->session->userdata('logged_user');
		$data['name']=$user_data["rst_nom"];
		$data['provincias'] = $this->restaurant->selectSQLMultiple("SELECT * FROM provincia",null);
		$this->load->view('user/registro',$data);
	}
	
	public function ciudades()
	{
		if(!@$this->user) redirect ('main');
		if ($this->input->is_ajax_request()) 
    	{
			$data = array(
				$this->input->post('slcProv')?$this->input->post('slcProv'):$this->input->post('slcProvMd')
			);
			$response=$this->restaurant->selectSQLMultiple("SELECT * FROM ciudad where prv_id=?",$data);
			echo json_encode(array("data"=>$response));
		}
	}
	/* =========================>>> REGISTER DATA <<<========================= */
	public function register_data()
	{
		if(!@$this->user) redirect ('main');
		if ($this->input->is_ajax_request()) 
    	{	
			$path = "uploads/logos/";
			$filesUrl = $_FILES;
			$user_data = (array)$this->session->userdata('logged_user');
			$new_name = $user_data["rst_id"].'_logo';
			$domicilio=false;
			if($this->input->post('txtDmc'))
				$domicilio=true;
			$this->do_upload($new_name, 'images');
			$tipo=explode('/',$filesUrl['images']['type'][0]);
			$new_name = 'uploads/logos/'.$new_name.'.jpg';
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
				$this->input->post('txtCode'),
				$new_name,
				$this->input->get('lat'),
				$this->input->get('lng')
			);
			$response = $this->restaurant->selectSQL("SELECT update_profile(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)",$data);
			if($response->update_profile=='1')
			{
				$logged_user = $this->restaurant->selectSQL("SELECT rst_id, rst_nom, rst_hab, rst_url, 'A' AS tipo, 0::bigint AS rrhh_id, '' AS rrhh_nom, rst_inf, rst_ent_dom from profile_view where rst_id=?",array($user_data["rst_id"]));
				if($logged_user->rst_id) {
					$logged_user->modulos = (array)$this->restaurant->selectSQLMultiple("SELECT * FROM modulos_permisos_view WHERE rst_id=".$logged_user->rst_id,null);
					$logged_user->tributario = (array)$this->restaurant->selectSQL("SELECT * FROM tributario WHERE rst_id=".$logged_user->rst_id,null);
					$this->session->set_userdata('logged_user', $logged_user);
				}
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
	
	public function help()
	{
		if(!@$this->user) redirect ('main');
		$data['js'] = array(
			base_url()."static/js/library/alls.js",
			base_url()."static/js/users/user.js",
			base_url()."static/js/bootstrap-select.min.js",
			base_url()."static/js/i18n/defaults-es_CL.min.js",
			base_url()."static/js/pnotify.custom.min.js"
			);
		$user_data = (array)$this->session->userdata('logged_user');
		$user_data['title'] = 'Ayuda';
		$user_data['css'] = array(base_url()."static/css/pnotify.custom.min.css");
		$this->load->view('templates/header', $user_data);
		$this->load->view('user/help');
		$this->load->view('templates/footer',$data);
	}
	
	public function conf()
	{
		if(!@$this->user) redirect ('main');
		$data['js'] = array(
			base_url()."static/js/library/alls.js",
			base_url()."static/js/users/user.js",
			base_url()."static/js/bootstrap-select.min.js",
			base_url()."static/js/i18n/defaults-es_CL.min.js",
			base_url()."static/js/pnotify.custom.min.js"
			);
		$user_data = (array)$this->session->userdata('logged_user');
		$user_data['title'] = 'configuraciones';
		$user_data['css'] = array(base_url()."static/css/pnotify.custom.min.css");
		$this->load->view('templates/header', $user_data);
		$this->load->view('user/setting');
		$this->load->view('templates/footer',$data);
	}
	
	public function logout() {
		$this->session->unset_userdata('logged_user');
		$this->session->sess_destroy();
		redirect('main');
	}
	
	public function updatePass()
	{
		$this->load->helper('security');
		if(!@$this->user) redirect ('main');
		if ($this->input->is_ajax_request()) 
    	{
			$user_data = (array)$this->session->userdata('logged_user');
			if($user_data["tipo"]=="A")
			{
				$info=(array)$this->restaurant->selectSQL("SELECT * FROM restaurant where rst_id=?",($user_data["rst_id"]));
				$data = array(
					'rst_pss'  => do_hash($this->input->post('txtPassConfirm'), 'md5')
				);
				if($info["rst_pss"] == do_hash($this->input->post('txtActualPass'), 'md5'))
				{	
					$response = $this->restaurant->update($user_data["rst_id"],$data);
					echo json_encode($response);
				}
				else
				{
					$response="noPass";
					echo json_encode($response);
				}
			}
			else
			{
				$info=(array)$this->rrhh->selectSQL("SELECT * FROM empleado where emp_id=?",($user_data["rrhh_id"]));
				$data = array(
					'emp_pss'  => do_hash($this->input->post('txtPassConfirm'), 'md5')
				);
				if($info["emp_pss"] == do_hash($this->input->post('txtActualPass'), 'md5'))
				{	
					$response = $this->rrhh->update($user_data["rrhh_id"],$data);
					echo json_encode($response);
				}
				else
				{
					$response="noPass";
					echo json_encode($response);
				}
			}
		}
		else
		{
			exit('No direct script access allowed');
			show_404();
		}
		return FALSE;
	}
	
	//--------------------------------//
	
	public function login() {


		// Se carga el helper form y security.
		$this->load->helper(array('form', 'security'));

		// Se carga la libreria form_validation.
		$this->load->library('form_validation');
		
		$data = array();
 
		// Añadimos las reglas necesarias.
		$this->form_validation->set_rules('username', 'Usuario', 'required|trim|min_length[10]|max_length[10]|xss_clean|callback_validar_ci');
		$this->form_validation->set_rules('password', 'Contraseña', 'required|trim|md5|min_length[5]|max_length[32]|xss_clean');
 		
        
		// Generamos el mensaje de error personalizado para la accion 'required', 'min_lenght', 'max_lenght'
		$this->form_validation->set_message('required', ' * El campo %s es requerido.');
		$this->form_validation->set_message('min_length', ' * El %s debe tener al menos %s carácteres');
        $this->form_validation->set_message('max_length', ' * El %s debe tener al menos %s carácteres');
        $this->form_validation->set_message('validar_ci', ' * La Cédula es Incorrecta..!');
 		
 		$username = $this->input->post('username');
 		$passwd   = do_hash($this->input->post('password'), 'md5'); 
 		
		// Si username y password existen en post
		if($username && $passwd) {
			// Si las reglas se cumplen, entramos a la condicion.
			if ($this->form_validation->run()) {
 
				// Obtenemos la informacion del usuario desde el modelo users.
				$logged_user = $this->users->get($username, $passwd);

				// Si existe el usuario creamos la sesion y redirigimos al index.
				if($logged_user) {
					$this->session->set_userdata('logged_user', $logged_user);
					redirect('main/home/');
				} else {
					// De lo contrario se activa el error_login.
					$data['error_login'] = TRUE;
				}
			}
		}
 
		$this->load->view('login', $data);
	}
	
}

/* End of file main.php */
/* Location: ./application/controllers/main.php */