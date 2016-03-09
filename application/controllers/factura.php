<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Factura extends Private_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model(array('restaurant','invoice'));
		$this->load->library('metodos');
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
		$user_data['title'] = utf8_encode('facturación');
		
		$data['js'] = array(
			"http://code.jquery.com/ui/1.10.2/jquery-ui.js",
			base_url()."static/js/library/alls.js",
			base_url()."static/js/library/files.js",
			base_url()."static/js/users/factura.js",
			base_url()."static/js/jquery.dataTables.min.js",
			base_url()."static/js/dataTables.bootstrap.js",
			base_url()."static/js/jquery.ci.validator.js",
			base_url()."static/js/pnotify.custom.min.js"
		);
		
		
		$user_data['css'] = array(
			"http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css",
			base_url()."static/css/pnotify.custom.min.css",
			base_url()."static/css/dataTables.bootstrap.css"
		);
		
		//$clientes['clientes']  = $this->clients->get_all();
		$user_data['mensaje'] = utf8_encode ("La información del <strong>Perfíl</strong> está incompleta, es necesario que complete los datos del perfíl para el correcto funcionamiento del sistema.");
		$this->load->view('templates/header', $user_data);
		$data['funcion'] = $user_data["tributario"]['trb_ruc']==''||$user_data["tributario"]['trb_frm']==''?"<script type='text/javascript'> 
										$('#mdAviso').modal('show');
							</script>":"";
		$data['funcion'] .="<script type='text/javascript'> 
										window.firma=".(($user_data["tributario"]["trb_frm"]!=null && $user_data["tributario"]["trb_frm"]!="" && $user_data["tributario"]["trb_pss_frm"]!=null && $user_data["tributario"]["trb_pss_frm"]!="" && $user_data["tributario"]["trb_frm_est"]=="t")?"true":"false").";
							</script>";
		//consultando tipo de producto
		if($user_data['tipo']=='A')
		{
			$user_data['numeracion'] = $this->restaurant->selectSQLMultiple("SELECT * FROM sucursal WHERE rst_id=?",($user_data['rst_id']));
		}
		else
		{
			$empleado = (array)$this->restaurant->selectSQL("SELECT * FROM empleado WHERE emp_id=?",array($user_data['rrhh_id']));
			$user_data['numeracion'] = $this->restaurant->selectSQLMultiple("SELECT * FROM sucursal WHERE rst_id=? AND suc_num=?",array($user_data['rst_id'], $empleado['suc_num']));
		}
		$this->load->view('user/factura',$user_data);
		$this->load->view('templates/footer', $data);
	}
	
	/*
	 * -------------------------------------------------------------------
	 * productos
	 * -------------------------------------------------------------------
	 */
	 
	 public function search_autocomplete_products()
	{
		$row_set=array();
		$data = $this->restaurant->selectSQLMultiple("SELECT * FROM producto where prd_nom ilike '%'||'".$this->input->get('term')."'||'%'",null);
		foreach ($data as $valor)
		{
				$row['value']=$valor["prd_nom"];
				$row['id']=$valor["prd_id"].";e-i;".$valor["prd_nom"].";e-i;".$valor["prd_prc"];
				$row_set[] = $row;//build an array
		}
		echo json_encode($row_set);
	}
	 
	 
	 /*
	 * -------------------------------------------------------------------
	 *  fin productos
	 * -------------------------------------------------------------------
	 */
	 /*DESCARGAR ARCHIVO XML DE FACTURA*/
	 public function download_xml_invoice()
	 {
		if(!@$this->user) redirect ('main');
		//if ($this->input->is_ajax_request()) 
    	{
			$user_data = (array)$this->session->userdata('logged_user');
			$datos=explode('-',$this->input->get('id'));
			$enlace = "C:/facturacionElectronica/XML/".$user_data["rst_id"]."/factura/".trim($datos[0])."/autorizado/".trim($datos[0]).trim($datos[1]).trim($datos[2]).".xml"; 
			header ("Content-Disposition: attachment; filename=".trim($datos[0]).trim($datos[1]).trim($datos[2]).".xml"." "); 
			header ("Content-Type: application/octet-stream");
			header ("Content-Length: ".filesize($enlace));
			readfile($enlace);
		}
	 }
	 
	/*SEND EMAIL WITH E-INVOICE TO CLIENT*/
	public function send_email_invoice()
	{
		if(!@$this->user) redirect ('main');
		if ($this->input->is_ajax_request()) 
    	{
    		$user_data = (array)$this->session->userdata('logged_user');
			$datos=explode('-',$this->input->post('id'));
			$data = array('suc_num' => (int)trim($datos[0]),'fac_fct' => (int)trim($datos[1]),'fac_num' => (int)trim($datos[2]), 'rst_id'=> $user_data["rst_id"]);
			$response = (array)$this->restaurant->selectSQL("SELECT c.cli_nom ||' '|| c.cli_ape as nombre, c.cli_ema FROM factura f, cliente c WHERE suc_num=? and fac_fct=? and fac_num=? and f.rst_id=? and c.cli_ced=f.cli_ced",$data);
			
			$cuerpo='<div align="center"><h2><strong>'.utf8_decode($user_data["rst_nom"]).'</strong></h2></div></br><h3>Estimado(a) '.utf8_decode($response["nombre"]).' en este e-mail hemos anexado su comprobante electrónico. Agradecemos su preferencia.</h3></br><h3 align="rigth">Atentamente</h3><h3 align="rigth"><strong>'.utf8_decode($user_data["rst_nom"]).'</strong>';
			if($response["cli_ema"]!="" && $response["cli_ema"]!=null)
			{
				$response=$this->metodos->sendMail('COMPROBANTE ELECTRÓNICO', $cuerpo, $response["cli_ema"],'encid_6822',array("C:/facturacionElectronica/XML/".$user_data["rst_id"]."/factura/".trim($datos[0])."/autorizado/".trim($datos[0]).trim($datos[1]).trim($datos[2]).".xml", "C:/facturacionElectronica/XML/".$user_data["rst_id"]."/factura/".trim($datos[0])."/autorizado/pdf/".trim($datos[0]).trim($datos[1]).trim($datos[2]).".pdf"),$user_data["rst_url"]);
			}
			else
			{
				$response="NoEmail";
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
	/*
	 * -------------------------------------------------------------------
	 * INVOICE
	 * -------------------------------------------------------------------
	 */
	 /*DELETE INVOICE IN BD*/
	public function delete_invoice()
	{
		if(!@$this->user) redirect ('main');
		if ($this->input->is_ajax_request()) 
    	{
    		$user_data = (array)$this->session->userdata('logged_user');
			$datos=explode('-',$this->input->post('id'));
			$data = array('suc_num' => (int)trim($datos[0]),'fac_fct' => (int)trim($datos[1]),'fac_num' => (int)trim($datos[2]), 'rst_id'=> $user_data["rst_id"]);
			$response = $this->invoice->delete($data);
			echo json_encode($response);
		}
		else
		{
			exit('No direct script access allowed');
			show_404();
		}
		return FALSE;
	}
	/*COMPLETAR PROCESO SRI*/
	public function process_sri()
	{
		if(!@$this->user) redirect ('main');
		if ($this->input->is_ajax_request()) 
    	{
			$servicio="http://encoding-ideas.com:8090/ServiciosWeb/XMLwebService.asmx?WSDL"; //url del servicio web
			$client = new SoapClient($servicio, array('encoding'=>'UTF-8'));
			$user_data = (array)$this->session->userdata('logged_user');
			//$response = $this->invoice->selectSQL("SELECT insert_invoice(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)",$data);//se guarda factura en bd
			$datos=explode('-',$this->input->post('id'));
			if($user_data['tributario']['trb_frm']!=null and $user_data['tributario']['trb_frm']!="" and $user_data['tributario']['trb_pss_frm']!=null and $user_data['tributario']['trb_pss_frm']!="")
			{
				$parametros["tipo_Documento"]=1;
				$parametros["ctrsñ"]="encid_2015";
				$parametros["id"]=$user_data["rst_id"];
				$parametros['establecimiento']=str_pad(trim($datos[0]), 3, "0", STR_PAD_LEFT);
				$parametros['ptoEmision']= str_pad(trim($datos[1]), 3, "0", STR_PAD_LEFT);
				$parametros['secuencial']= str_pad(trim($datos[2]), 9, "0", STR_PAD_LEFT);
				$parametros['subdirectory']="/autorizado";
				$result = $client->searchFile($parametros);
				if($result->searchFileResult=="false")
				{
					$parametros['subdirectory']="";
					$result = $client->searchFile($parametros);
					if($result->searchFileResult=="true")
					{
						if($response->fac_est_rec=="t" && $response->fac_est_aut=="t")
						{
							$result="COMPLETO";
						}
						else
						{
							//enviar o autorizar
							$response = $this->invoice->selectSQL("SELECT * from view_basic_invoice where fac_num=?",array($this->input->post('id')));
							$result=null;
							$parametros['generado']=true;
							$parametros['recibido']=$response->fac_est_rec=="t"?true:false;
							$parametros['autorizado']=$response->fac_est_aut=="t"?true:false;
							$parametros['pathLogo']=$user_data['rst_url'];
							$result = $client->completeProcess($parametros);
						}
					}
					else
					{
						//crear archivo xml, firmar, enviar y autorizar
						$response = (array)$this->invoice->selectSQL ("SELECT * from view_complete_invoice where suc_num=? and fac_fct=? and fac_num=? and rst_id=?",array($datos[0]+0, $datos[1]+0, $datos[2]+0, $user_data['rst_id']));//consulta de datos de la factura
						$details = (array)$this->invoice->selectSQLMultiple ("select df.*, p.prd_nom from detalles_factura df, producto p where df.suc_num=? and df.fac_fct=? and df.fac_num=? and df.rst_id=? and df.prd_id=p.prd_id",array($datos[0]+0, $datos[1]+0, $datos[2]+0, $user_data['rst_id']));//consulta de datos de la factura
						$direcciones = (array)$this->invoice->selectSQL("select suc_dir, rst_dir from sucursal s, restaurant r where s.rst_id=? and s.suc_num=? and r.rst_id=s.rst_id",array($user_data["rst_id"],(int)$datos[0]));
						$detalles=array();
						for($i=0; $i<sizeof($details); $i++)
						{
							array_push($detalles, $details[$i]['prd_id']."<baei>".($details[$i]['prd_nom'])."<baei>".$details[$i]['dfa_cnt']."<baei>".$details[$i]['dfa_prc']);
						}
						//$this->load->library('metodos');//llamado a libreria "metodos"
						$servicio="http://encoding-ideas.com:8081/ServiciosWeb/XMLwebService.asmx?WSDL"; //url del servicio
						$parametros=array(); //parametros de la llamada
						$parametros['ambiente']="1";
						$parametros['tipoEmision']="1";
						$parametros['razonSocial']=$user_data['rst_nom'];
						$parametros['nombreComercial']=$user_data['tributario']['trb_nom_com'];
						$parametros['ruc']=$user_data['tributario']['trb_ruc'];
						$parametros['codDoc']="01";
						$parametros['establecimiento']=str_pad($response['suc_num'], 3, "0", STR_PAD_LEFT);
						$parametros['ptoEmision']= str_pad($response['fac_fct'], 3, "0", STR_PAD_LEFT);
						$parametros['secuencial']= str_pad($response['fac_num'], 9, "0", STR_PAD_LEFT);
						$parametros['dirMatriz']=$direcciones['rst_dir'];
						$parametros['fechaEmision']= date("d/m/Y", strtotime($response['fac_fec']));
						$parametros['direccionEstab']=$direcciones['suc_dir'];
						$parametros['codigoContrib']=$user_data['tributario']['trb_con_esp'];
						$parametros['obligadoContab']=$user_data['tributario']['trb_obl_con'];
						$parametros['tipoIdComprador']="05";
						$parametros['razonSocialComprador']=$response['cli_nom'].' '.$response['cli_ape'];
						$parametros['idComprador']=$response['cli_ced'];
						$parametros['totalDescuento']=(float)(($response['fac_des']*100)/$response['fac_sub'])/100;
						$parametros['detalles']=$detalles;
						$parametros['iva']=(float)$response['fac_iva']>0?true:false;
						$parametros['codigoNumerico']="531";
						$parametros['totalPropina']=$response['fac_prp'];
						$parametros['ctrsñ']="";
						$parametros['idEmpresa']=$user_data['rst_id'];
						$parametros['sign']=$user_data['tributario']['trb_frm'];
						$parametros['pssswd']=$user_data['tributario']['trb_pss_frm'];
						$parametros['generado']=false;
						$parametros['recibido']=false;
						$parametros['autorizado']=false;
						$parametros['pathLogo']=$user_data['rst_url'];
						$result = $client->completeProcess($parametros);
					}
					$data_sql=array();
					if($result->completeProcessResult=="AUTORIZADO")
					{
						$data_sql=array(true,true,$response['suc_num'],$response['fac_fct'],$response['fac_num'],$user_data['rst_id']);
						$result="COMPLETO";
					}
					else
					{
						if($result->completeProcessResult=="RECHAZADO")
						{
							$data_sql=array(true,false,$response['suc_num'],$response['fac_fct'],$response['fac_num'],$user_data['rst_id']);
							$result="INCOMPLETO";
						}
					}
					if(sizeof($data_sql)>0)
					{
						$response = $this->invoice->selectSQL("SELECT update_sri_invoice(?,?,?,?,?,?)",$data_sql);
					}
					echo json_encode($result);
				}
				else
				{
					echo json_encode("COMPLETO");
				}
			}
		}
	}
	/*SAVE INVOICE IN BD*/
	public function save_invoice()
	{
		if(!@$this->user) redirect ('main');
		if ($this->input->is_ajax_request()) 
    	{
			$productos = explode(',', $this->input->get('prods'));
			$productosNombre = explode(',', $this->input->get('dets'));
			$cantidad = explode(',', $this->input->get('cant'));
			$precio = explode(',', $this->input->get('prec'));
			
			$user_data = (array)$this->session->userdata('logged_user');
			$data = array(
				$this->input->post('txtFecha'),
    			$this->input->post('txtNumero')?$this->input->post('txtNumero'):"0",
				$this->input->post('txtSubtotal'),
				$this->input->post('txtIva'),
				$this->input->post('txtCantDesc'),
				$this->input->post('txtTotal'),
				"{".implode(",",$productos)."}",
				"{".implode(",",$cantidad)."}",
				"{".implode(",",$precio)."}",
				$user_data["rst_id"],
    			$this->input->post('txtCedula'),
				$this->input->post('txtNombre'),
				$this->input->post('txtApellido'),
				$this->input->post('txtDireccion'),
				$this->input->post('txtEmail'),
				$this->input->post('txtTelefono'),
				$this->input->post('txtFacturero'),
				$this->input->post('txtEstablecimiento'),
				($user_data['rrhh_id']==0)?null:$user_data['rrhh_id'],
				$this->input->post('txtPropina')
    		);
			$response = $this->invoice->selectSQL("SELECT insert_invoice(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)",$data);//se guarda factura en bd
			$direcciones = (array)$this->invoice->selectSQL("select suc_dir, rst_dir from sucursal s, restaurant r where s.rst_id=? and s.suc_num=? and r.rst_id=s.rst_id",array($user_data["rst_id"],(int)$this->input->post('txtEstablecimiento')));
			//comprobando si se guardó factura
			if($response->insert_invoice>0 and $user_data['tributario']['trb_frm']!=null and $user_data['tributario']['trb_frm']!="" and $user_data['tributario']['trb_pss_frm']!=null and $user_data['tributario']['trb_pss_frm']!="")
			{
				$detalles=array();
				for($i=0; $i<sizeof($productos); $i++)
				{
					array_push($detalles, $productos[$i]."<baei>".($productosNombre[$i])."<baei>".$cantidad[$i]."<baei>".$precio[$i]);
				}
				//$this->load->library('metodos');//llamado a libreria "metodos"
				$servicio="http://encoding-ideas.com:8090/ServiciosWeb/XMLwebService.asmx?WSDL"; //url del servicio
				$parametros=array(); //parametros de la llamada
				$parametros['ambiente']="1";
				$parametros['tipoEmision']="1";
				$parametros['razonSocial']=$user_data['rst_nom'];
				$parametros['nombreComercial']=$user_data['tributario']['trb_nom_com'];
				$parametros['ruc']=$user_data['tributario']['trb_ruc'];
				$parametros['codDoc']="01";
				$parametros['establecimiento']=str_pad($this->input->post('txtEstablecimiento'), 3, "0", STR_PAD_LEFT);
				$parametros['ptoEmision']= str_pad($this->input->post('txtFacturero'), 3, "0", STR_PAD_LEFT);
				$parametros['secuencial']= str_pad($response->insert_invoice, 9, "0", STR_PAD_LEFT);
				$parametros['dirMatriz']=$direcciones['rst_dir'];
				$parametros['fechaEmision']= date("d/m/Y", strtotime($this->input->post('txtFecha')));
				$parametros['direccionEstab']=$direcciones['suc_dir'];
				$parametros['codigoContrib']=$user_data['tributario']['trb_con_esp'];
				$parametros['obligadoContab']=$user_data['tributario']['trb_obl_con'];
				$parametros['tipoIdComprador']="05";
				$parametros['razonSocialComprador']=$this->input->post('txtNombre').' '.$this->input->post('txtApellido');
				$parametros['idComprador']=$this->input->post('txtCedula');
				$parametros['totalDescuento']=(float)$this->input->post('txtDesc')/100;
				$parametros['detalles']=$detalles;
				$parametros['iva']=(float)$this->input->post('txtIva')>0?true:false;
				$parametros['codigoNumerico']="531";
				$parametros['totalPropina']=$this->input->post('txtPropina');
				$parametros['ctrsñ']="";
				$parametros['idEmpresa']=$user_data['rst_id'];
				$parametros['sign']=$user_data['tributario']['trb_frm'];
				$parametros['pssswd']=$user_data['tributario']['trb_pss_frm'];
				$client = new SoapClient($servicio, array('encoding'=>'UTF-8'));
				$result = $client->crearFactura($parametros);
				$response=$result->crearFacturaResult=="firmado"?$response:$result;
				//$response["insert_invoice"]=$result->crearFacturaResult=="firmado"?"-1":$result;
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
	
	public function search_invoice_id()
	{
		if(!@$this->user) redirect ('main');
		if ($this->input->is_ajax_request()) 
    	{
			$numero=explode('-',$this->input->post("id"));
			$user_data = (array)$this->session->userdata('logged_user');
    		$data = $this->restaurant->selectSQLMultiple('Select * from view_complete_invoice where rst_id=? and suc_num=? and fac_fct=? and fac_num=?',array($user_data["rst_id"],(int)$numero[0],(int)$numero[1],(int)$numero[2]));
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
    		$data = $this->restaurant->selectSQLMultiple('Select * from view_basic_invoice where rst_id=?',array($user_data["rst_id"]));
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