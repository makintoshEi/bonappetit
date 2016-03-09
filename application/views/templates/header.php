<!DOCTYPE html>
<html>
<head>
<link rel="shortcut icon" href="<?php echo base_url()?>static/img/logo.png">
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta charset="utf-8">
<script src="<?php echo base_url()?>static/js/jquery-1.11.3.min.js"></script>
<script src="<?php echo base_url()?>static/js/header.js"></script>
<script src="<?php echo base_url()?>static/js/register.js"></script>
	<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="<?= base_url() ?>static/css/bootstrap.min.css">

<!--link rel="stylesheet" href="<?php echo base_url()?>static/css/boostrap.css" type="text/css"-->
<script src="<?= base_url() ?>static/js/bootstrap.min.js"></script>

<link rel="stylesheet" href="<?= base_url() ?>static/css/buttons.css">
<script type="text/javascript" src="<?= base_url() ?>static/js/buttons.js"></script>

<?php  
	if ( ! empty($css))
	{
		foreach ($css as $key => $value) 
		{
			echo "<link rel='stylesheet' href='$value'>";
		}
	}
?>
<link rel="stylesheet" href="<?php echo base_url()?>static/css/style.css" type="text/css">
<link rel="stylesheet" href="<?php echo base_url()?>static/css/style_2.css" type="text/css">
<title> Bon Appétit - <?php echo $title ?></title>
</head>
<body onload="bgBody()">
<!--<div class="menu_templ" style="width:100%;max-width:612px;background-color:#d3c2bd;">-->
<div>

<!--header-->
<div class="header" id="home">
		<div class="container">
			<div class="logo">
				<a href="#"><img src="<?php echo (base_url()).$rst_url;?>" alt="" width="80" style="z-index:1000;"></a><span><?php echo ($tipo=='A'?$tributario['trb_nom_com']:$rrhh_nom);?></span>
			</div>
			<div class="navigation">
				<div class="mainOptions">
					<a href="#" onclick="$('#mdMsj').modal('show');"><img src="<?php echo base_url()?>static/img/logo.png" alt="" width="35"> <span class="hidden-xs">Bon Appétit</span></a>
					 | <a href="<?php echo base_url()?>main/logout"><img src="<?php echo base_url()?>static/img/salir.png" title="Cerrar Sesión" width="20"></a>
				</div>
			</div>
		</div>
	</div>
<!--end header-->
<div class="container-fluid" style="padding:0px; margin-left:-5px; margin-top:20px;">
  <div class="row-fluid">
    <div class="col-xs-3 col-sm-2 col-md-2 col-lg-2" style="overflow-x:hidden; overflow-y:auto; max-height:85vh;">
		<div class="col-xs-12 col-sm-6 opcion">
			<a  href="<?php echo base_url()?>main/conf/" align="center"><img src="<?php echo base_url()?>static/img/conf.png" alt="" width="50">
			<div><p>Config.</p></div></a>
		</div>
		<?php if($tipo=='A'){?>
		<div class="col-xs-12 col-sm-6 opcion">
			<a href="<?php echo base_url()?>perfil/start/"><img src="<?php echo base_url()?>static/img/perfil.png" alt="" width="50">
			<div><p>Perfíl</p></div></a>
		</div>
		<?php 
			}
			if ( ! empty($modulos))
			{
				$nivel=$tipo=='A'?1:2;
				foreach ($modulos as $key => $value) 
				{
					if($value['mod_niv']>=$nivel)
					{
						echo '<div class="col-xs-12 col-sm-6 opcion">
								<a href="'.base_url().$value['mod_url'].'"><img src="'.base_url().$value['mod_img'].'" title="'.$value['mod_nom'].'" width="50">
								<div align="center"><p>'.$value['mod_nom'].'</p></div></a>
							</div>';
					}
				}
			}?>
		<div class="col-xs-12 col-sm-6 opcion">
			<a href="<?php echo base_url()?>main/news/"><img src="<?php echo base_url()?>static/img/novedades.png" alt="" width="50">
			<div><p>Novedades</p></div></a>
		</div>
		<div class="col-xs-12 col-sm-6 opcion">
			<a href="<?php echo base_url()?>main/help/"><img src="<?php echo base_url()?>static/img/help.png" alt="" width="50">
			<div><p>Ayuda</p></div></a>
		</div>
		<?php if($tipo=='A'){ ?>
		<div class="col-xs-12 col-sm-6 opcion">
			<a href="<?php echo base_url()?>upgrade/start/"><img src="<?php echo base_url()?>static/img/upgrade.png" alt="" width="50">
			<div><p>Upgrade</p></div></a>
		</div>
		<?php } ?>
		<!--MODAL INFO APP-->
			<div id="mdMsj" class="modal fade">
				<div class="modal-dialog modal-md">
					<div class="modal-content">
						<div class="modal-header" style="background: rgba(255, 209, 63, 0.88) url(<?php echo base_url()?>static/img/logo.png) no-repeat right center ; background-size:auto 120%;">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
							<h3 class="modal-title">BON APPÉTIT v.1.0 Beta</h3>
						</div>
						<form role="form" id='frmMdClient'>
							<div class="modal-body col-xs-12 col-sm-10 col-sm-offset-1">		
								<p><strong>Bon Appétit</strong> es una Guía Gastronómica que te permitirá anunciar tus servicios y captar nuevos clientes a traves del <strong>Mobile Marketing</strong>.</br></p>
							</div>
						
							<div class="modal-footer">
								<div class="row col-xs-12">
									<div align="right">
										<img src="<?php echo base_url()?>static/img/logo_completo.png" height="55"/>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		<!--END MODAL INFO APP-->
		<!--MODAL AVISOS APP-->
			<div id="mdAviso" class="modal fade">
				<div class="modal-dialog modal-md">
					<div class="modal-content">
						<div class="modal-header" style="background: rgba(255, 209, 63, 0.88) url(<?php echo base_url()?>static/img/logo.png) no-repeat right center ; background-size:auto 120%;">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
							<h3 class="modal-title">Aviso</h3>
						</div>
						<form role="form" id='frmMdClient'>
							<div class="modal-body col-xs-12 col-sm-10 col-sm-offset-1">		
								<p><?php echo $mensaje;?></p>
							</div>
						
							<div class="modal-footer">
								<div class="row col-xs-12">
									<div align="right">
										<img src="<?php echo base_url()?>static/img/logo_completo.png" height="55"/>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		<!--END MODAL AVISOS APP-->
	</div>
    <div class="col-xs-9 col-md-10" style="overflow-x:hidden; overflow-y:auto; max-height:85vh;" id="bodyPage">
    <!--Body content-->