<!DOCTYPE html>
<html>
<head>
<link rel="shortcut icon" href="<?php echo base_url()?>static/img/logo.png">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta charset="utf-8">
<script src="http://maps.googleapis.com/maps/api/js?v3&key=AIzaSyDYMmVNvltSy7O_BCGOr8-qihtna4JaC-A"></script>
<script src="<?php echo base_url()?>static/js/jquery-1.11.3.min.js"></script>
<script src="<?php echo base_url()?>static/js/header.js"></script>
<script src="<?php echo base_url()?>static/js/register.js"></script>
<script src="<?php echo base_url()?>static/js/users/perfil.js"></script>
<script src="<?php echo base_url()?>static/js/login.js"></script>
<script src="<?php echo base_url()?>static/js/library/files.js"></script>
<script src="<?php echo base_url()?>static/js/pnotify.custom.min.js"></script>
<script src="<?php echo base_url()?>static/js/library/alls.js"></script>
	<!-- Latest compiled and minified CSS -->


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
<link rel="stylesheet" href="<?= base_url() ?>static/css/bootstrap.min.css">
<link rel="stylesheet" href="<?= base_url() ?>static/css/pnotify.custom.min.css">
<title> Bon Appétit - register</title>
</head>
<body onload="bgBody()" class="bodyLogin">
<!--<div class="menu_templ" style="width:100%;max-width:612px;background-color:#d3c2bd;">-->

<!--header-->
<div class="header" id="home">
		<div class="container">
			<div class="navigation">
				<div class="mainOptions">
					<a href="#" onclick="$('#mdInfo').modal('show');"><img src="<?php echo base_url()?>static/img/logo.png" alt="" width="35"> <span class="hidden-xs">Bon Appétit</span></a>
					| <a href="<?php echo base_url()?>main/logout"><img src="<?php echo base_url()?>static/img/salir.png" title="Cerrar Sesión" width="20"></a>
				</div>
			</div>
		</div>
	</div>
<!--end header-->
<div class="container-fluid" style="padding:0px; margin-left:-5px; ">
  <div class="row-fluid">
    <div >
		<div class="centrado">
			<div class=" bgCabeceraLogin" >
				<h3 style="">Bon Appétit</h3>
			</div>
			<div class="divMetro contenedorLogin" align="center" style="height:400px; overflow-x:hidden; overflow-y:auto;">
				<div id="registroData" class="col-xs-12">
					<h4><strong>¡Bienvenido!</strong></h4>
					<p><strong><?php echo @$name;?></strong>  ahora formas parte de <strong>Bon Appétit</strong><p>
					</br>
					<div align="left"><p>Por favor llena el siguiente formulario para registrar tu establecimiento Matríz<p></div>
					</br>
					<form id="registerData">
						<div align="left">
							<fieldset class="scheduler-border">
								<legend class="scheduler-border">Datos Generales</legend>
								<div class="form-group" align="left">
									<label for="images">Foto/Logo:</label>
									<div class="col-xs-12"></div>
									<input name="images[]" id="images" type="file" required = "required"/>
								</div>
								<div class="form-group" align="left">
									<label for="txtInfo">Información:</label>
									<TextArea required="true" class="form-control" id="txtInfo" name="txtInfo" placeholder="Cuentanos un poco sobre tus servicios..." ></TextArea>
								</div>
								<div class="form-group" align="left">
									<label for="txtTlf">Teléfono(s):</label>
									<input type="text" required="true" class="form-control" id="txtTlf" name="txtTlf" placeholder="Ingrese Número(s) de Teléfono">
								</div>
								<div class="form-group" align="left">
									<label for="txtDmc" style="display:inline-block;">Pedidos a domicilio:</label>
									<input type="checkbox" class="form-control" id="txtDmc" name="txtDmc" style="display:inline-block; width:15px; height:auto; margin:0px; padding:0px;">
								</div>
							</fieldset>
							<fieldset class="scheduler-border">
								<legend class="scheduler-border">Ubicación</legend>
								<div class="form-group" align="left">
									<label for="slcProv">Provincia:</label>
									<select class="form-control" id="slcProv" name="slcProv" required="true">
									<?php
										if ( ! empty($provincias))
										{
											foreach ($provincias as $key => $value) 
											{
												echo '<option value="'.$value['prv_id'].'">'.$value['prv_nom'].'</option>';
											}
										}
									?>
									</select>
								</div>
								<div class="form-group" align="left">
									<label for="slcCiu">Ciudad:</label>
									<select class="form-control" id="slcCiu" name="slcCiu" required="true">
									</select>									
								</div>
								<div class="form-group" align="left">
									<label for="txtDir">Dirección:</label>
									<input type="text" required="true" class="form-control" id="txtDir" name="txtDir" placeholder="Ingrese Dirección de Restaurant">
								</div>
								<div class="form-group" align="left">
								<label for="txtDir">Geolocalización:</label>
								<div class="embed-responsive embed-responsive-16by9" align="center">
									<div id="googleMap" style="width:425px; height:250px;"></div>
								</div>
							</div>
							</fieldset>
							<fieldset class="scheduler-border">
								<legend class="scheduler-border">Horarios de Atención</legend>
								<div class="form-group" align="left">
									<label for="txtLunes">Lunes:</label>
									<input type="text" required="true" class="form-control" id="txtLunes" name="txtLunes" placeholder="Ingrese horario de atención en dias Lunes">
								</div>
								<div class="form-group" align="left">
									<label for="txtMartes">Martes:</label>
									<input type="text" required="true" class="form-control" id="txtMartes" name="txtMartes" placeholder="Ingrese horario de atención en dias Martes">
								</div>
								<div class="form-group" align="left">
									<label for="txtMiercoles">Miércoles:</label>
									<input type="text" required="true" class="form-control" id="txtMiercoles" name="txtMiercoles" placeholder="Ingrese horario de atención en dias Miércoles">
								</div>
								<div class="form-group" align="left">
									<label for="txtJueves">Jueves:</label>
									<input type="text" required="true" class="form-control" id="txtJueves" name="txtJueves" placeholder="Ingrese horario de atención en dias Jueves">
								</div>
								<div class="form-group" align="left">
									<label for="txtViernes">Viernes:</label>
									<input type="text" required="true" class="form-control" id="txtViernes" name="txtViernes" placeholder="Ingrese horario de atención en dias Viernes">
								</div>
								<div class="form-group" align="left">
									<label for="txtSabado">Sábado:</label>
									<input type="text" required="true" class="form-control" id="txtSabado" name="txtSabado" placeholder="Ingrese horario de atención en dias Sábado">
								</div>
								<div class="form-group" align="left">
									<label for="txtDomingo">Domingo:</label>
									<input type="text" required="true" class="form-control" id="txtDomingo" name="txtDomingo" placeholder="Ingrese horario de atención en dias Domingo">
								</div>
								<div class="alert alert-info alert-dismissable">
									<button type="button" class="close" data-dismiss="alert">&times;</button>
									<h5><strong>Ejemplo: </strong>Lunes: Desde 9:00 hasta 19:00 / Domingo: No hay atención.</h5>
								</div>
							</fieldset>
							<fieldset class="scheduler-border">
								<legend class="scheduler-border">Activación</legend>
								<div class="form-group" align="left">
									<label for="txtCode">Código:</label>
									<input type="password" required="true" class="form-control" id="txtCode" name="txtCode" placeholder="Ingrese su código de activación">
								</div>
								<div class="alert alert-info">
									<h5><strong>Aviso: </strong>Por motivos de seguridad hemos enviado un código de activación a tu dirección de correo electrónico.</h5>
								</div>
							</fieldset>
						</div>
						<div class="row">
						  <div align="center" id="buttonsAction">
							<button type="submit" class="button button-3d-primary button-rounded">Enviar</button>
						  </div>
						</div>
						</br>
					</form>
				</div>
			</div>
		</div>
    </div>
  </div>
</div>
<!--footer-->
<div class="navbar-inverse navbar-fixed-bottom" style="background-color:rgba(15,15,15,0.9); margin-top:10px;" role="navigation">
	<div class="container" style="margin:0px;" >
		<a href="http://www.alexnb92.wix.com/encoding-ideas" style="color:white; font-size:2vmin ;">
			<img src="<?php echo base_url()?>static/img/mini_logo.png" style="height:30px;" alt="">
			Desarrollado por <b>Encoding Ideas</b>
		</a>
	</div>
</div>
<!--MODAL INFO APP-->
	<div id="mdInfo" class="modal fade">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header" style="background: rgba(255, 209, 63, 0.88) url(<?php echo base_url()?>static/img/logo.png) no-repeat right center ; background-size:auto 120%;">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h3 class="modal-title">BON APPÉTIT v.1.0 Beta</h3>
				</div>
				<form role="form" id='frmMdClient'>
					<div class="modal-body col-xs-12 col-sm-10 col-sm-offset-1">		
						<p><strong>Bon Appétit</strong> esa una Guía Gastronómica que te permitirá anunciar tus servicios y captar nuevos clientes a traves del <strong>Mobile Marketing</strong>.</br></p>
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
</body>
</html>
