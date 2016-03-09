<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// Include the main TCPDF library (search for installation path).
require_once($_SERVER["DOCUMENT_ROOT"].'/application/libraries/tcpdf.php');

// extend TCPF with custom functions
class MYPDF extends TCPDF {

    // Colored table
    public function ColoredTable($w, $header,$data, $orientation) {
      
        // Colors, line width and bold font
        $this->SetFillColor(30, 30, 30);
        $this->SetTextColor(255);
        $this->SetDrawColor(30, 30, 30);
        $this->SetLineWidth(0.3);
        $this->SetFont('', 'B');
        // Header
  
        $num_headers = count($header);
        for($i = 0; $i < $num_headers; ++$i) {
			$this->Cell($w[$i], 7, utf8_encode($header[$i]), 1, 0, 'C', 1);
        }
        $this->Ln();
        // Color and font restoration
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        // Data
         
        $fill = 0;
        $cont = 0;
        foreach($data as $row) {
             
            $cellcount = array();
            //write text first
            $startX = $this->GetX();
            $startY = $this->GetY();
            //draw cells and record maximum cellcount
            //cell height is 6 and width is 80
     
            foreach ($row as $key => $column):
                 $cellcount[] = $this->MultiCell($w[$key],6,$column,0,$orientation[$key],$fill,0);
            endforeach;
         
            $this->SetXY($startX,$startY);
  
            //now do borders and fill
            //cell height is 6 times the max number of cells
         
            $maxnocells = max($cellcount);
         
            foreach ($row as $key => $column):
                 $this->MultiCell($w[$key],$maxnocells * 6,'','LR',$orientation[$key],$fill,0);
            endforeach;
		
        $this->Ln();
			$cont += $maxnocells;
			if ($cont > 23) {
				$this->AddPage('P', 'A5');
				$cont = 0;
			}
            
            // fill equals not fill (flip/flop)
            $fill=!$fill;
             
        }
		$this->Cell(array_sum($w), 0, '', 'T');
	}
	// Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, utf8_encode('Página '.$this->getAliasNumPage().'/'.$this->getAliasNbPages()), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}

class Report extends Private_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model(array('reports','orders','users'));
		//$this->load->library('tcpdf');
	}
	/*
	 * -------------------------------------------------------------------
	 *  reportes
	 * -------------------------------------------------------------------
	 */
	public function start()
	{
		if(!@$this->user) redirect ('main');
		$title['title'] = 'reporte';

		$data['js'] = array(
			base_url()."static/js/library/alls.js",
			base_url()."static/js/users/reports.js",
			base_url()."static/js/jquery.dataTables.min.js",
			base_url()."static/js/dataTables.bootstrap.js",
			base_url()."static/js/pnotify.custom.min.js",
		);
		
		$data['funcion']="<script type='text/javascript'> seleccionar('mn_rpt') </script>"; //al hacer click queda seleccionado en el menu
		
		$title['css'] = array(
			base_url()."static/css/pnotify.custom.min.css",
			base_url()."static/css/dataTables.bootstrap.css"
		);
		
		
		$this->load->view('templates/header', $title); //necesario
		$this->load->view('user/report'); // el que carga el template
		$this->load->view('templates/footer', $data); // necesario
	}
	
	/*
	 * -------------------------------------------------------------------
	 * REPORTS
	 * -------------------------------------------------------------------
	 */
	 
	public function ventas_pdf()
	{
		if(!@$this->user) redirect ('main');
		$desde=$this->input->get("desde");
		$hasta=$this->input->get("hasta");
		$estado=$this->input->get("est");
		$tabla=array();
		$total=0;
		$facturas=0;
		$response;
		$docs="";
		switch($estado)
		{
		case "1":
			$docs="Reservas y ventas.";
			$response = $this->orders->selectSQLMultiple("select otb.ord_num, otb.ord_fch, nombre, servs, ord_cst from orden_trabajo_revision otr, orden_trabajo_basico otb where otb.ord_id=otr.ord_id and otr.ord_fch between ? and ?",array($desde,$hasta));
			break;
		case "2":
			$docs="Solo reservas";
			$response = $this->orders->selectSQLMultiple("select otb.ord_num, otb.ord_fch, nombre, servs, ord_cst from orden_trabajo_revision otr, orden_trabajo_basico otb where otb.ord_id=otr.ord_id and otb.ord_rsv=true and otr.ord_fch between ? and ?",array($desde,$hasta));
			break;
		case "3":
			$docs="Solo ventas";
			$response = $this->orders->selectSQLMultiple("select otb.ord_num, otb.ord_fch, nombre, servs, ord_cst from orden_trabajo_revision otr, orden_trabajo_basico otb where otb.ord_id=otr.ord_id and otb.ord_rsv=false and otr.ord_fch between ? and ?",array($desde,$hasta));
			break;
		}
		foreach ($response as $keyA => $valueA) 
		{
			array_push($tabla,array($valueA["ord_num"],$valueA["ord_fch"],$valueA["nombre"],$valueA["servs"],$valueA["ord_cst"]));
			$total=$total+$valueA["ord_cst"];
			$facturas++;
		}
		
		// create new PDF document
		$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$pdf->setPrintHeader(false);
		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		// set font
		$pdf->SetFont('helvetica', '', 12);

		// add a page
		$pdf->AddPage();
		
		// create some HTML content
		$html = '<br/><br/><img src="/sich/static/img/cabecera.jpg" height="80"/>
		<div align="center">
			<h4><strong>REPORTE DE INGRESOS</strong></h4>
		</div>
		<table cellpadding="0" cellspacing="0" border="0" style="text-align:left; vertical-align: middle; margin-top:20px;">
			<tr><td><h5><strong>Total ingresos: </strong>'.$total.'</h5></td></tr>
			<tr><td><h5><strong>Documentos: </strong>'.$docs.'</h5></td></tr>
			<tr><td><h5><strong>Total documentos: </strong>'.$facturas.'</h5></td></tr>
			<tr><td><h5><strong>Periodo: </strong>'.(($desde==$hasta)?$desde:($desde." al ".$hasta)).'</h5></td></tr>
		</table>
		<br/>
		';
		$html = utf8_encode ($html);
		
		// output the HTML content
		$pdf->writeHTML($this->especiales($html), true, false, true, false, '');
		
		// column titles
		$header = array('N° Ord', 'Fecha', 'Cliente', 'Servicios', 'Total');

		// print colored table
		$pdf->ColoredTable(array(30,30,50,52,30),$header, $tabla);
		
		// reset pointer to the last page
		$pdf->lastPage();

		// ---------------------------------------------------------

		//Close and output PDF document
		$pdf->Output('reporte_ingresos_'.(($desde==$hasta)?$desde:($desde."_al_".$hasta)).'.pdf', 'F');
	}
	
	public function ingresos_pdf()
	 {
		if(!@$this->user) redirect ('main');
		$sucursal=$this->input->get("suc");
		$sucursal_name=$this->input->get("name");
		$fecha_inicio=$this->input->get("fec_in");//fecha de incio
		$fecha_fin=$this->input->get("fec_fn");//fecha de fin
		$user_data = (array)$this->session->userdata('logged_user');
		$total=0;
		$propinas=0;
		$descuentos=0;
		$iva=0;
		$docs="";
		$pixel=37.795275591;
		$pixel=0;
		$general = (array)$this->orders->selectSQLMultiple("select * from factura where rst_id=? AND suc_num=? AND fac_fec>=? AND fac_fec<=? ORDER BY fac_fec, fac_fct, fac_num",array($user_data['rst_id'],(int)$sucursal,$fecha_inicio,$fecha_fin));
		$perfil = (array)$this->orders->selectSQL("select rst_dir from restaurant where rst_id=?",array($user_data['rst_id']));
		$margen=1;
		$tabla=array();
		// create new PDF document
		$pdf = new MYPDF('P', PDF_UNIT, 'A4', true, 'UTF-8', false);
		$pdf->setPrintHeader(false);
		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		// set font
		$pdf->SetFont('helvetica', '', 9);

		// add a page
		$pdf->AddPage();
		//Load library 'metodos'
		$this->load->library('metodos');
			
		// create some HTML content
		$html='<table><tr><td style="height:'.($pixel*$margen).'px;"></td></tr></table>
		<table>
		<tr><td rowspan="3" align="rigth" style="width:170px"><img src="'.$user_data["rst_url"].'" width="50"/></td><td style="text-align:center; width:'.$pdf->getPageWidth().'; font-size:14pt;"><strong>'.utf8_decode($user_data["rst_nom"]).'</strong></td><td style="width:90px;"></td></tr>
		<tr><td style="text-align:center;"><strong>Matríz: </strong>'.utf8_decode($perfil['rst_dir']).'</td></tr>
		<tr><td style="text-align:center;"></td></tr>
		</table>
		<hr>
		<table><tr><td style="height:7px;"></td></tr></table>		
		<table border="0">
			<tr><td align="center"><strong>REPORTE DE INGRESOS</strong></td></tr>
		</table>
		<table><tr><td style="height:5px;"></td></tr></table>		
		<table>
			<tr><td><strong>Periodo: </strong>'.($fecha_inicio==$fecha_fin?$this->metodos->dateToLongString($fecha_fin):$this->metodos->dateToLongString($fecha_inicio).' hasta '. $this->metodos->dateToLongString($fecha_fin)) .'</td></tr>
			<tr><td><strong>Sucursal: </strong>'.utf8_decode($sucursal_name).'</td></tr>
		</table>';
		$contador=0;
		foreach ($general as $keyA => $valueA) 
		{
			$contador++;
			$propinas+=(float)$valueA["fac_prp"];
			$total+=(float)$valueA["fac_tot"];
			$descuentos+=(float)$valueA["fac_des"];
			$iva+=(float)$valueA["fac_iva"];
			array_push($tabla,array($valueA["fac_fec"],str_pad($valueA["fac_fct"], 3, "0", STR_PAD_LEFT),str_pad($valueA['fac_num'], 9, "0", STR_PAD_LEFT),number_format($valueA["fac_sub"],2),number_format($valueA["fac_des"],2),number_format($valueA["fac_iva"],2),number_format($valueA["fac_prp"],2),number_format($valueA["fac_tot"],2)));
		}
		
		$html = utf8_encode ($html);
		
		// output the HTML content
		$pdf->writeHTML($this->especiales($html), true, false, false, false, '');
		
		// column titles
		$header = array('Fecha', 'Pto. Emisión', 'N°/Factura', 'Subtotal','Descuento','I.V.A.', 'Propina','Total');
		
		//orientation of columns
		$orientation = array('L','C','C','R','R','R','R','R');
		
		// print colored table
		$pdf->ColoredTable(array(25,25,30,20,20,20,20,25),$header, $tabla, $orientation);
		
		$html='<table><tr><td style="height:10px;"></td></tr></table>
		<br/><table>
		<tr><td style="width:80px"><strong>Facturas emitidas:</strong></td><td align="rigth" style="text-align:right; width:50px;">'.$contador.'</td></tr>
		<tr><td style="width:80px"><strong>Total Descuentos:</strong></td><td align="rigth" style="text-align:right; width:50px;">'.number_format($descuentos,2).'</td></tr>
		<tr><td style="width:80px"><strong>Total I.V.A.:</strong></td><td align="rigth" style="text-align:right; width:50px;">'.number_format($iva,2).'</td></tr>
		<tr><td style="width:80px"><strong>Total Propinas:</strong></td><td align="rigth" style="text-align:right; width:50px;">'.number_format($propinas,2).'</td></tr>
		<tr><td style="width:80px"><strong>Total Neto:</strong></td><td align="rigth" style="text-align:right; width:50px;">'.number_format($total,2).'</td></tr>
		</table>';
		
		$pdf->writeHTML($this->especiales($html), true, false, false, false, '');
		
		// reset pointer to the last page
		$pdf->lastPage();

		// ---------------------------------------------------------

		//Close and output PDF document
		$pdf->Output('ingresos_'.$sucursal.'.pdf', 'I');
	 }
	 
	public function top_pdf()
	 {
		if(!@$this->user) redirect ('main');
		$sucursal=$this->input->get("suc");
		$sucursal_name=$this->input->get("name");
		$fecha_inicio=$this->input->get("fec_in");//fecha de incio
		$fecha_fin=$this->input->get("fec_fn");//fecha de fin
		$tipo=$this->input->get("type");//tipo de producto a buscar
		$rango=$this->input->get("range");//rango de top
		$user_data = (array)$this->session->userdata('logged_user');
		$docs="";
		$pixel=37.795275591;
		$pixel=0;
		$general = (array)$this->orders->selectSQLMultiple("select * from get_top(?,?,?,?,?,?)",array($user_data['rst_id'],(int)$sucursal,$fecha_inicio,$fecha_fin,$tipo,$rango));
		$perfil = (array)$this->orders->selectSQL("select rst_dir from restaurant where rst_id=?",array($user_data['rst_id']));
		$margen=1;
		$tabla=array();
		// create new PDF document
		$pdf = new MYPDF('P', PDF_UNIT, 'A4', true, 'UTF-8', false);
		$pdf->setPrintHeader(false);
		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		// set font
		$pdf->SetFont('helvetica', '', 9);

		// add a page
		$pdf->AddPage();
		//Load library 'metodos'
		$this->load->library('metodos');
			
		// create some HTML content
		$html='<table><tr><td style="height:'.($pixel*$margen).'px;"></td></tr></table>
		<table>
		<tr><td rowspan="3" align="rigth" style="width:170px"><img src="'.$user_data["rst_url"].'" width="50"/></td><td style="text-align:center; width:'.$pdf->getPageWidth().'; font-size:14pt;"><strong>'.utf8_decode($user_data["rst_nom"]).'</strong></td><td style="width:90px;"></td></tr>
		<tr><td style="text-align:center;"><strong>Matríz: </strong>'.utf8_decode($perfil['rst_dir']).'</td></tr>
		<tr><td style="text-align:center;"></td></tr>
		</table>
		<hr>
		<table><tr><td style="height:7px;"></td></tr></table>		
		<table border="0">
			<tr><td align="center"><strong>REPORTE DE PRODUCTOS MÁS VENDIDOS</strong></td></tr>
		</table>
		<table><tr><td style="height:5px;"></td></tr></table>		
		<table>
			<tr><td><strong>Periodo: </strong>'.($fecha_inicio==$fecha_fin?$this->metodos->dateToLongString($fecha_fin):$this->metodos->dateToLongString($fecha_inicio).' hasta '. $this->metodos->dateToLongString($fecha_fin)) .'</td></tr>
			<tr><td><strong>Sucursal: </strong>'.utf8_decode($sucursal_name).'</td></tr>
		</table>';
		$contador=0;
		foreach ($general as $keyA => $valueA) 
		{
			array_push($tabla,array($valueA["prd_nom"],$valueA["tipo"]=="b"?"Bebida":"Comida",$valueA["vendidos"],number_format($valueA["ingresos"],2),));
		}
		
		$html = utf8_encode ($html);
		
		// output the HTML content
		$pdf->writeHTML($this->especiales($html), true, false, false, false, '');
		
		// column titles
		$header = array('Producto', 'Tipo/Producto', 'Total Vendidos', 'Ingresos Producidos');
		
		//orientation of columns
		$orientation = array('L','C','C','R');
		
		// print colored table
		$pdf->ColoredTable(array(60,25,30,35),$header, $tabla, $orientation);
		
		// reset pointer to the last page
		$pdf->lastPage();

		// ---------------------------------------------------------

		//Close and output PDF document
		$pdf->Output('top_'.$sucursal.'.pdf', 'I');
	 }
	
	public function factura_pdf()
	 {
		if(!@$this->user) redirect ('main');
		$idOrd=$this->input->get("id");
		$numeracion=explode('-',$idOrd);
		$user_data = (array)$this->session->userdata('logged_user');
		$total=0;
		$facturas=0;
		$response;
		$docs="";
		$pixel=37.795275591;
		$pixel=0;
		$general = (array)$this->orders->selectSQL("select * from factura where rst_id=? AND suc_num=? AND fac_fct=? AND fac_num=?",array($user_data['rst_id'],(int)$numeracion[0],(int)$numeracion[1],(int)$numeracion[2]));
		$sucursal = (array)$this->orders->selectSQL("select s.*, p.prv_nom||' - '||c.ciu_nom as city, p.prv_id, c.ciu_id as place from sucursal s, ciudad c, provincia p where s.ciu_id=c.ciu_id and c.prv_id=p.prv_id and s.rst_id=? and suc_num=?",array($user_data["rst_id"],$general["suc_num"]));
		$details = $this->orders->selectSQLMultiple("select * from detalles_factura df,  producto p where df.prd_id=p.prd_id and df.rst_id=? AND df.suc_num=? AND df.fac_fct=? AND df.fac_num=?",array($user_data['rst_id'],(int)$numeracion[0],(int)$numeracion[1],(int)$numeracion[2]));
		$cliente = (array)$this->orders->selectSQL("select cli_nom||' '||cli_ape as nombre from cliente  WHERE cli_ced=?",array($general['cli_ced']));
		$margen=1;
		$tabla=array();
		// create new PDF document
		$pdf = new MYPDF('P', PDF_UNIT, 'A5', true, 'UTF-8', false);
		$pdf->setPrintHeader(false);
		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		// set font
		$pdf->SetFont('helvetica', '', 9);

		// add a page
		$pdf->AddPage();
		//Load library 'metodos'
		$this->load->library('metodos');
			
		// create some HTML content
		$html='<table><tr><td style="height:'.($pixel*$margen).'px;"></td></tr></table>
		<table>
		<tr><td rowspan="3" align="center"><img src="'.$user_data["rst_url"].'" width="50"/></td><td style="text-align:center; width:'.$pdf->getPageWidth()*(1.4).'; font-size:14pt;"><strong>'.utf8_decode($user_data["rst_nom"]).'</strong></td><td style="width:90px;"></td></tr>
		<tr><td style="text-align:center;"><strong>Dirección: </strong>'.utf8_decode($sucursal['suc_dir']).'</td></tr>
		<tr><td style="text-align:center;">'.utf8_decode($sucursal['city']).'</td></tr>
		</table>
		<hr>
		<table><tr><td style="height:5px;"></td></tr></table>
		<table>
			<tr>
				<td style="width:'.($pdf->getPageWidth()*(0.65)*(2.4)).';">
					<table>
						<tr><td><strong>Fecha: </strong>'.$this->metodos->dateToLongString($general["fac_fec"]).'</td></tr>
						<tr><td><strong>Cliente: </strong>'.utf8_decode($cliente["nombre"]).'</td></tr>
						<tr><td><strong>R.U.C./C.I.: </strong>'.$general["cli_ced"].'</td></tr>
					</table>
				</td>
				<td style="width:'.($pdf->getPageWidth()*(0.35)*(2.4)).';">
					<table border="1">
						<tr><td align="center"><strong>FACTURA</strong></td></tr>
						<tr><td align="center"><strong>'.$idOrd.'</strong></td></tr>
					</table>
				</td>
			</tr>
		</table>';
		
		foreach ($details as $keyA => $valueA) 
		{
			array_push($tabla,array($valueA["dfa_cnt"],$valueA["prd_nom"],number_format($valueA["dfa_prc"],2),number_format($valueA["dfa_prc"]*$valueA["dfa_cnt"],2)));
		}
		
		$html = utf8_encode ($html);
		
		// output the HTML content
		$pdf->writeHTML($this->especiales($html), true, false, false, false, '');
		
		// column titles
		$header = array('Cant.', 'Descripcion', 'P. Unit.', 'Total');

		//orientation of columns
		$orientation = array('C','L','R','R');
		
		// print colored table
		$pdf->ColoredTable(array(15,70,20,20),$header, $tabla,$orientation);
		
		$html='<br/>
		<table>
		<tr><td style="text-align:right; width:'.($pdf->getPageWidth()*(0.8)*(2.4)).'px;"><strong>Subtotal:</strong></td><td align="rigth" style="text-align:right; width:'.($pdf->getPageWidth()*(0.2)*(2.4)).'px;">'.number_format($general['fac_sub'],2).'</td></tr>
		<tr><td style="text-align:right; width:'.($pdf->getPageWidth()*(0.8)*(2.4)).'px;"><strong>Descuento:</strong></td><td align="rigth" style="text-align:right; width:'.($pdf->getPageWidth()*(0.2)*(2.4)).'px;">'.number_format($general['fac_des'],2).'</td></tr>
		<tr><td style="text-align:right; width:'.($pdf->getPageWidth()*(0.8)*(2.4)).'px;"><strong>I.V.A.(12%):</strong></td><td align="rigth" style="text-align:right; width:'.($pdf->getPageWidth()*(0.2)*(2.4)).'px;">'.number_format($general['fac_iva'],2).'</td></tr>
		<tr><td style="text-align:right; width:'.($pdf->getPageWidth()*(0.8)*(2.4)).'px;"><strong>Total:</strong></td><td align="rigth" style="text-align:right; width:'.($pdf->getPageWidth()*(0.2)*(2.4)).'px;">'.number_format($general['fac_tot'],2).'</td></tr>
		</table>';
		$html = utf8_encode ($html);
		$pdf->writeHTML($this->especiales($html), true, false, false, false, '');
		// reset pointer to the last page
		$pdf->lastPage();

		// ---------------------------------------------------------

		//Close and output PDF document
		$pdf->Output('factura_'.$idOrd.'.pdf', 'I');
	 }
	 
	 public function especiales($str)
	{
		$find=array("á","é","í","ó","ú");
		$remp=array("&aacute;","&eacute;","&iacute;","&oacute;","&uacute;");
		return str_replace($find, $remp, $str);
	}
	 
	 public function orden_pdf()
	{
		if(!@$this->user) redirect ('main');
		$data= array($this->input->get("id"));
		//echo $this->input->get("id");
		$cliente = $this->orders->selectSQLMultiple("SELECT cli.* from orden_trabajo ot, vehiculo veh, cliente cli where ord_id=? and ot.id_veh=veh.veh_id and cli.cli_id=veh.id_cli",$data);
		$vehiculo = $this->orders->selectSQLMultiple("SELECT veh.* , mod.mod_nom, mar.mar_nom from orden_trabajo ot, vehiculo veh, modelo mod, marca mar where ord_id=? and ot.id_veh=veh.veh_id and mod.mod_id=veh.id_modelo and mar.mar_id=mod.id_marca",$data);
		$ordentrb = $this->orders->selectSQLMultiple("SELECT * from orden_trabajo, forma_pago where ord_id=? and fpg_id=id_fpg",$data);
		$servs = $this->orders->selectSQLMultiple("select * from detalle_servicio_orden dso,  servicio srv where dso.srv_id=srv.srv_id and ord_id=?",$data);
		$detallesTrabajo = $this->orders->selectSQLMultiple("SELECT * from detalle_orden_area doa, area_trabajo art where doa.art_id=art.art_id and art.art_est=true and ord_id=? ",$data);
		$invent = $this->orders->selectSQLMultiple("select dipa.*, pie.pie_nom from inventario inv, detalle_inventario_piezas_auto dipa, piezas_auto pie where inv.ord_id=? and dipa.inv_id=inv.inv_id and pie.pie_id=dipa.pie_id",$data);
		$inventarioGeneral = $this->orders->selectSQLMultiple("select * from inventario where ord_id=? ",$data);
		$cliente=$cliente[0];
		$vehiculo=$vehiculo[0];
		$ordentrb=$ordentrb[0];
		$inventarioGeneral=$inventarioGeneral[0];
		$servicios="";
		$inventario="";
		$contadorDetalle=1;
		$coorComb=explode("-",$inventarioGeneral["inv_com"]);
		$detalles="";
		foreach ($servs as $keyA => $valueA) 
		{
			$servicios.=$valueA["srv_nom"].'&nbsp;&nbsp;<strong>:</strong>&nbsp;&nbsp;$'.$valueA["dso_prc"].'&nbsp;&nbsp;<strong>|</strong>&nbsp;&nbsp;&nbsp;&nbsp;';
		}
		foreach ($detallesTrabajo as $key => $value) 
		{
			if($contadorDetalle==1)
			{
				$detalles.="<tr>";
			}
			$detalles.='<td style="border-left: 1px solid '.($contadorDetalle==1?"white":"gray").';">'.$value["art_nom"].'</td>';
			if($contadorDetalle==3)
			{
				$detalles.="</tr>";
				$contadorDetalle=0;
			}
			$contadorDetalle++;
		}
		if($contadorDetalle>1)
		{
		$detalles.=str_repeat("<td></td>",4-$contadorDetalle).'</tr>';
		}
		$contadorDetalle=1;
		foreach ($invent as $key => $value) 
		{
			if($contadorDetalle==1)
			{
				$inventario.="<tr>";
			}
			$inventario.='<td style="border-left: 1px solid '.($contadorDetalle==1?"white":"gray").';">'.$value["pie_nom"].'</td>';
			if($contadorDetalle==3)
			{
				$inventario.="</tr>";
				$contadorDetalle=0;
			}
			$contadorDetalle++;
		}
		if($contadorDetalle>1)
		{
		$inventario.=str_repeat("<td></td>",3-$contadorDetalle).'</tr>';
		}
		/*$response = $this->orders->selectSQLMultiple("select otb.ord_num, otb.ord_fch, nombre, servs, ord_cst from orden_trabajo_revision otr, orden_trabajo_basico otb where otb.ord_id=otr.ord_id and otr.ord_fch between ? and ?",array();
		foreach ($response as $keyA => $valueA) 
		{
			array_push($tabla,array($valueA["ord_num"],$valueA["ord_fch"],$valueA["nombre"],$valueA["servs"],$valueA["ord_cst"]));
			$total=$total+$valueA["ord_cst"];
			$facturas++;
		}*/
		
		// create new PDF document
		$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$pdf->setPrintHeader(false);
		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		// set font
		$pdf->SetFont('helvetica', '', 12);

		// add a page
		$pdf->AddPage();
		
		// create some HTML content
		$html = '<br/><br/>
		<img src="/sich/static/img/cabecera.jpg" height="80"/><img src="/sich/static/img/ford.jpg" height="70"/>
		<div align="center">
			<strong>ORDEN DE TRABAJO</strong>
		</div>
		<table cellpadding="3" cellspacing="0" rules="none" border="0" style="text-align:right; font-size:11pt;">
			<tr><td><span style="width:100px;"><strong>Serie </strong>001-001-</span><span style="color:red; width:100px;"><strong>'.(str_repeat("0",9-strlen($ordentrb["ord_num"]))).$ordentrb["ord_num"].'</strong></span></td></tr>
		</table>
		<table cellpadding="0" cellspacing="0" style="border: 1px solid gray;">
			<table cellpadding="3" cellspacing="0" rules="none" border="0" style="font-size:11pt;">
				<tr><td style="width:300px;"><strong>Cliente:</strong> '.utf8_decode($cliente["per_nom"].' '.$cliente["per_ape"]).'</td><td style="width:200px;"><strong>E-mail:</strong> '.$cliente["cli_eml"].'</td></tr>
				<tr><td style="width:300px;"><strong>Télefono:</strong> '.str_replace(array("{","}"),"",$cliente["cli_tel"]).'</td><td style="width:200px;"><strong>C.I./R.U.C.:</strong> '.$cliente["per_ced"].'</td></tr>
				<tr><td colspan="4"><strong>Dirección:</strong> '.utf8_decode($cliente["cli_dir"]).'</td></tr>
				<tr><td style="width:300px;"><strong>¿Quién entrega?:</strong> '.utf8_decode($ordentrb["ord_per_ent"]).'</td><td style="width:200px;"><strong>Teléfono:</strong> '.$ordentrb["ord_per_tel"].'</td></tr>
				<tr><td style="width:300px;"><strong>Asesor:</strong> '.utf8_decode($ordentrb["ord_asr"]).'</td><td style="width:200px;"><strong>Fecha:</strong> '.$ordentrb["ord_fch"].'</td></tr>
			</table>
		</table>
		<br/>
		<br/>
		<table cellpadding="0" cellspacing="0" style="border: 1px solid gray;">
			<table cellpadding="3" cellspacing="0" rules="none" border="0" style="font-size:11pt;">
				<tr><td style="width:300px;"><strong>Vehiculo Marca:</strong> '.utf8_decode($vehiculo["mar_nom"]).'</td><td style="width:240px;"><strong>Modelo:</strong> '.utf8_decode($vehiculo["mod_nom"]).'</td></tr>
				<tr><td><strong>Chasís:</strong> '.$vehiculo["veh_cha"].'</td><td ><strong>Placa:</strong> '.$vehiculo["veh_pla"].'</td></tr>
				<tr><td><strong>Año:</strong> '.$vehiculo["veh_yar"].'</td><td ><strong>Motor:</strong> '.$vehiculo["veh_mot"].'</td></tr>
				<tr><td><strong>Color:</strong> <span style="background-color:'.$vehiculo["veh_col"].';">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td><td><strong>Código:</strong> '.$vehiculo["veh_cla"].'</td></tr>
			</table>
		</table>
		<br/>
		<br/>
		<table cellpadding="0" cellspacing="0" style="border: 1px solid gray;">
			<table cellpadding="3" cellspacing="3" style="font-size:11pt; ">
				<tr><td colspan="2"><strong>Combustible:</strong><img src="/sich/static/img/gasometro.png" height="70"/></td></tr>
			</table>
			<table cellpadding="3" cellspacing="3" style="font-size:11pt; ">
				<tr><td colspan="3" align="center"><strong>INVENTARIO DEL VEHICULO</strong></td></tr>
				'.utf8_decode($inventario).'
				<tr><td colspan="3"><strong>Kilometraje:</strong>'.utf8_decode($inventarioGeneral["inv_kil"]).' Km</td></tr>
				<tr><td colspan="3"><strong>Observaciones:</strong>'.utf8_decode($inventarioGeneral["inv_obs"]).'</td></tr>
			</table>
		</table>
		';
		$html = utf8_encode ($html);
		$pdf->writeHTML($this->especiales($html), true, false, true, false, '');
		$style2 = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(255, 0, 0));
		$pdf->SetLineStyle($style2);
		$pdf->Line(49.25, 149, 49.25+(($coorComb[0]-50)*0.175), 149+(($coorComb[1]-70)*0.16), $style2);
		
		$html='
		<br/>
		<table cellpadding="0" cellspacing="0" style="border: 1px solid gray;">
			<table cellpadding="3" cellspacing="0" rules="none" border="0" style="font-size:11pt;">
				<tr><td style="width:270px;"><strong>Fecha ingreso:</strong> '.$ordentrb["ord_fch_ing"].'</td><td style="width:270px;"><strong>Fecha estimada de entrega:</strong> '.$ordentrb["ord_fch_ent"].'</td></tr>
				<tr><td colspan="4" align="center"><strong> </strong></td></tr>
				<tr><td colspan="4" align="center"><strong>SERVICIO : COSTO</strong></td></tr>
				<tr><td colspan="4">'.utf8_decode($servicios).'</td></tr>
				<tr><td colspan="4" align="center"><strong> </strong></td></tr>
				<tr><td colspan="4" align="center"><strong>DETALLE DEL TRABAJO</strong></td></tr>
			</table>
			<table cellpadding="3" cellspacing="3" style="font-size:11pt; ">
				'.utf8_decode($detalles).'
			</table>
		</table>
		<br/>
		<br/>
		<table cellpadding="0" cellspacing="0">
			<table cellpadding="3" cellspacing="0" rules="none" border="0" style="font-size:11pt;">
				<tr><td style="width:270px;"><strong>Costo Total:</strong> '.$ordentrb["ord_cst"].'</td><td style="width:270px;"><strong>Pago con tarjeta:</strong> '.$ordentrb["ord_trj"].'</td></tr>';
		$abonos=str_replace(array('{','}'),array('',''),$ordentrb["ord_abn"]);
		$totalAbonos=0;
		if($abonos!='')
		{
			$abonos=explode (',',$abonos);
			foreach ($abonos as $valor) {
				$abono = explode (':',$valor);
				$html.='<tr><td colspan="4"><strong>Abono ('.$abono[0].'):</strong> '.$abono[1].'</td></tr>';
				$totalAbonos+=$abono[1];
			}
		}
		else
		{
			$html.='<tr><td colspan="4"><strong>Abonos:</strong> 0.00</td></tr>';
		}
		$html.='
				<tr><td colspan="4"><strong>Saldo:</strong> '.round($ordentrb["ord_cst"]-$totalAbonos,2).'</td></tr>
				<tr><td colspan="4"><strong>Observaciones:</strong>'.utf8_decode($ordentrb["ord_obs"]).'</td></tr>
			</table>
		</table>
		<br/>
		<br/>
		<br/>
		<br/>
		<br/>
		<br/>
		<table cellpadding="0" cellspacing="0" border="0">
			<tr><td align="center">______________________________</td><td align="center">______________________________</td></tr>
			<tr><td align="center">RECIBÍ CONFORME</td><td align="center">ENTREGUÉ CONFORME</td></tr>
			<tr><td align="center" colspan="2"><small><strong>IMPORTANTE:</strong> No nos responsabilizamos por objetos de valor en su vehiculo, que no estén relacionados con la orden de trabajo.</small></td></tr>
		</table>
		';
		
		// output the HTML content
		$html = utf8_encode ($html);
		$pdf->writeHTML($this->especiales($html), true, false, true, false, '');
		
		// reset pointer to the last page
		$pdf->lastPage();

		// ---------------------------------------------------------

		//Close and output PDF document
		$pdf->Output('orden_trabajo_.pdf', 'I');
	}
}
/* End of file main.php */
/* Location: ./application/controllers/car.php */