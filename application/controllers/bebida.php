<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bebida extends Private_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model(array('restaurant','bebidas'));
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
			base_url()."static/js/library/files.js",
			base_url()."static/js/users/bebida.js",
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
		//consultando tipo de producto
		$user_data['tipos'] = $this->restaurant->selectSQLMultiple("SELECT * FROM tipo_producto",null);
		$user_data['categorias'] = $this->restaurant->selectSQLMultiple("SELECT * FROM categoria_bebida",null);
		$this->load->view('user/bebidas',$user_data);
		$this->load->view('templates/footer', $data);
	}
	
	/*
	 * -------------------------------------------------------------------
	 * UPLOAD FILES
	 * -------------------------------------------------------------------
	 */
	function do_upload($ci,$name,$edt=false)
	{
	    $files = $_FILES;
	    $cpt = count($_FILES[$name]['name']);
	    if($edt)
		{	
			$dir = opendir('./uploads/drink/');
			while ($file = readdir($dir)) 
			{ 
				if($file != '.' && $file != '..' && $file!=".DS_Store")
				{
					if (strpos($file, $ci)!==false){
						unlink('./uploads/drink/'.$file);
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
			$tipo=explode('/',$files[$name]['type'][$i]);
			$new_name = $ci.'.'.$tipo[1];
			//move_uploaded_file($_FILES[$name]['tmp_name'], './uploads/drink/'.$new_name);
		    //codigo redimensionar imagen
			$ruta_imagen = $files[$name]['tmp_name'][$i];

			$miniatura_ancho_maximo = 800;
			$miniatura_alto_maximo = 800;

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


			imagejpeg($lienzo, './uploads/drink/'.$ci.'.jpg', 80);
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
	 * BEBIDAS
	 * -------------------------------------------------------------------
	 */
	public function delete_drink()
	{
		if(!@$this->user) redirect ('main');
		if ($this->input->is_ajax_request()) 
    	{
    		$data = array('prd_id' => $this->input->post('id'));
			$response = $this->bebidas->delete($data);
			echo json_encode($response);
		}
		else
		{
			exit('No direct script access allowed');
			show_404();
		}
		return FALSE;
	}
	public function save_drink()
	{
		if(!@$this->user) redirect ('main');
		if ($this->input->is_ajax_request()) 
    	{
			$user_data = (array)$this->session->userdata('logged_user');
			$this->load->helper('security');
			$path = "uploads/drink/";
			$filesUrl = $_FILES;
			$tipo=explode('/',$filesUrl['images']['type'][0]);
			$data = array(
    			$this->input->post('txtNombre'),
				$this->input->post('txtInfo'),
				$this->input->post('txtPrec'),
				$this->input->post('slcCatg'),
				$this->input->post('slcTipo'),
				'jpg',
				$user_data['rst_id']
    		);
			$response = $this->restaurant->selectSQL("SELECT insert_bebida(?,?,?,?,?,?,?)",$data);
			
			if($response->insert_bebida!='0')
			{
				$new_name = $response->insert_bebida.'_'.do_hash($response->insert_bebida, 'md5');
				
				$this->do_upload($new_name, 'images');
			}
			
			echo json_encode($response->insert_bebida);
		}
		else
		{
			exit('No direct script access allowed');
			show_404();
		}
		return FALSE;
	}
	
	public function get_drink_all()
	{
		if(!@$this->user) redirect ('main');
		if ($this->input->is_ajax_request()) 
    	{
			$user_data = (array)$this->session->userdata('logged_user');
    		$data = $this->restaurant->selectSQLMultiple('Select * from drink_view where rst_id='.$user_data["rst_id"],null);
			echo json_encode(array("data"=>$data));
		}
		else
		{
			exit('No direct script access allowed');
			show_404();
		}
		return FALSE;
	}
	
	public function find_drink()
	{
		if(!@$this->user) redirect ('main');
		if ($this->input->is_ajax_request()) 
    	{
    		$data = $this->restaurant->selectSQLMultiple('Select * from drink_view where prd_id=?',array($this->input->post('id')));
			echo json_encode(array("data"=>$data));
		}
		else
		{
			exit('No direct script access allowed');
			show_404();
		}
		return FALSE;
	}
	
	public function edit_drink()
	{
		if(!@$this->user) redirect ('main');
		if ($this->input->is_ajax_request()) 
    	{
			$user_data = (array)$this->session->userdata('logged_user');
			$this->load->helper('security');
			$path = "uploads/drink/";
			$filesUrl = $_FILES;
			$new_name = $this->input->get('trId').'_'.do_hash($this->input->get('trId'), 'md5');
			$data = array();
			if($_FILES['imagesMd']['name'][0])
			{
				$this->do_upload($new_name, 'imagesMd',true);
				$tipo=explode('/',$filesUrl['imagesMd']['type'][0]);
				$data = array(
					'prd_nom'=>$this->input->post('txtNombreEdit'),
					'prd_dsc'=>$this->input->post('txtInfoEdit'),
					'prd_prc'=>$this->input->post('txtPrecEdit'),
					'ctp_id'=>$this->input->post('slcCatgEdit'),
					'tpr_id'=>$this->input->post('slcTipoEdit'),
					'prd_url'=>$path.$new_name.'.jpg',
				);
			}
			else
			{
				$data = array(
					'prd_nom'=>$this->input->post('txtNombreEdit'),
					'prd_dsc'=>$this->input->post('txtInfoEdit'),
					'prd_prc'=>$this->input->post('txtPrecEdit'),
					'ctb_id'=>$this->input->post('slcCatgEdit'),
					'tpr_id'=>$this->input->post('slcTipoEdit')
				);
			}
			$response = $this->bebidas->update($this->input->get('trId'),$data);
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