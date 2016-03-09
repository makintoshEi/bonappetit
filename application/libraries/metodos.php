<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class metodos{
function __construct() {
}

public function generateCode($size)
	{
		$str = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
		$cad = "";
		for($i=0;$i<$size;$i++) 
		{
			$cad .= substr($str,rand(0,62),1);
		}
		return $cad;
	}
public function sendMail($asunto, $cuerpo, $para,$pass, $files=array(),$img_cab="static/img/logo.png")
	{
		if($pass=="encid_6822")
		{
			$html='<div style=" font-family: Arial, Helvetica, sans-serif;" align="center">
			<img src="http://bonappetit.encoding-ideas.com/'.$img_cab.'" height="60"/>
			</div>'.$cuerpo.'</br><div align="right"><a href="http://www.encoding-ideas.com">
			<img align="right" src="http://bonappetit.encoding-ideas.com/static/img/logo_completo.png" height="50"/></a>
			</div>';
			//cargamos la libreria email de ci
			$CI =& get_instance();
			$CI->load->library("email");
			//configuracion para gmail
			$configGmail = array(
				'protocol' => 'smtp',
				'smtp_host' => 'smtp.encoding-ideas.com',
				'smtp_port' => 587,
				'smtp_user' => 'info@encoding-ideas.com',
				'smtp_pass' => 'encid_2015',
				'mailtype' => 'html',
				'charset' => 'ISO-8859-1',
				'newline' => "\r\n"
			);   
			//cargamos la configuración para enviar con gmail
			$CI->email->initialize($configGmail);
			$CI->email->from('info@encoding-ideas.com');
			$CI->email->to($para);
			$CI->email->subject($asunto);
			$CI->email->message(utf8_decode (utf8_encode ($html)));
			if(sizeof($files)>0)
			{
				foreach ($files as $key => $val) 
				{
					$CI->email->attach($val);
				}
			}
			return $CI->email->send();
		}
	}
public function dateToLongString($date)
	{
		$fecha=explode("-",$date);
		$meses=array(
			"01"=>"Enero",
			"02"=>"Febrero",
			"03"=>"Marzo",
			"04"=>"Abril",
			"05"=>"Mayo",
			"06"=>"Junio",
			"07"=>"Julio",
			"08"=>"Agosto",
			"09"=>"Septiembre",
			"10"=>"Octubre",
			"11"=>"Noviembre",
			"12"=>"Diciembre");
		return $fecha[2]." de ".$meses[$fecha[1]]." de ".$fecha[0];
	}
	
function obj2array($obj) 
	{
	  $out = array();
	  foreach ($obj as $key => $val) {
	  //echo "<br>salto";
		switch(true) {
			case is_object($val):
			 $out[$key] = obj2array($val);
			 //echo "<br>obj::".$key;
			 break;
		  case is_array($val):
			 $out[$key] = obj2array($val);
			 //echo "<br>arr::".$key;
			 break;
		  default:
			$out[$key] = $val;
			 //echo "<br>def::".$key;
		}
	  }
	  return $out;
	}
}
?>