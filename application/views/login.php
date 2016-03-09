<!DOCTYPE html>
<html>
<head>
<link rel="shortcut icon" href="<?php echo base_url()?>static/img/logo.png">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta charset="utf-8">
<script src="<?php echo base_url()?>static/js/jquery-1.11.3.min.js"></script>
<script src="<?php echo base_url()?>static/js/header.js"></script>
<script src="<?php echo base_url()?>static/js/login.js"></script>
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
<link rel="stylesheet" href="<?php echo base_url()?>static/css/pnotify.custom.min.css" type="text/css">
<link rel="stylesheet" href="<?= base_url() ?>static/css/bootstrap.min.css">
<script src="<?php echo base_url()?>static/js/pnotify.custom.min.js"></script>
<script src="<?php echo base_url()?>static/js/library/alls.js"></script>
<title> Bon Appétit - inicio</title>
</head>
<body class="bodyLogin">
<!--<div class="menu_templ" style="width:100%;max-width:612px;background-color:#d3c2bd;">-->
<div>

<!--header-->
<div class="header" id="home">
		<div class="container">
			<div class="navigation">
				<div class="mainOptions">
					<a href="#" onclick="$('#mdInfo').modal('show');"><img src="<?php echo base_url()?>static/img/logo.png" alt="" width="35"> <span class="hidden-xs">Bon Appétit</span></a>
				</div>
			</div>
		</div>
	</div>
<!--end header-->
<div class="container-fluid" style="padding:0px; margin-left:-5px; ">
  <div class="row-fluid">
    <div  style="/*overflow-x:hidden; overflow-y:scroll; max-height:800px;height:85vh; width:100%;*/">
		
		 
		<br />
		<div class="well mobileHide" style="left: 1%; top: 25%; position: absolute;">
			<!--iframe width="640" height="401" src="http://www.powtoon.com/embed/d80hd4CRBT4/?autoPlay=true" autostart="1" frameborder="0"></iframe-->
			<iframe src="https://www.youtube.com/embed/x_w-8tSw8Ps?rel=0&autoplay=1"
			allowtransparency="true" frameborder="0" scrolling="no" class="wistia_embed"
			name="wistia_embed" allowfullscreen mozallowfullscreen webkitallowfullscreen
			oallowfullscreen msallowfullscreen width="640" height="401"></iframe>
		</div>
		<div class="centrado">
			<div id="divErrorData" style='display:block; width:40vw; bottom:50px; position:absolute;'>
				<?php if(@$error_login): ?>
					<div class="alert alert-danger alert-dismissable">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<h5><strong>Aviso: </strong>El email o contraseña ingresadas son incorrectas.</h5>
					</div>
				<?php endif; ?>
			</div>
			<div id="errorValidation" style='display:none;'>
				<?php echo @validation_errors(); ?>
			</div>
			<div class=" bgCabeceraLogin" >
				<h3 style="">Bon Appétit</h3>
			</div>
			<div class="divMetro contenedorLogin" align="center">
				<div class="buttons">
					<a href="#" class="active" onclick="tabChange(this)" name="registro">Registrate</a>
					<a href="#" onclick="tabChange(this)" name="login">Inicia Sesión</a>
				</div>
				<div class="tabsContainer">
				<div id="registro" class="col-xs-12 active">
					<form id="registerFrm" method='post'>
						<div class="alert alert-info" style="padding:0px; ">
							<h5>Registrate y obtén una cuenta <strong>Starter</strong> totalmente <strong>¡Gratis!</strong></h5>
						</div>
						<div class="form-group" align="left">
							<label for="txtName">Restaurant:</label>
							<input type="text" required="true" class="form-control" id="txtName" name="txtName" placeholder="Ingrese Nombre de Restaurant">
						</div>
						<div class="form-group" align="left">
							<label for="txtEmail">Email:</label>
							<input type="email" required="true" class="form-control" id="txtEmail" name="txtEmail" placeholder="Ingrese dirección de correo electrónico">
						</div>
						<div class="form-group" align="left">
							<label for="txtPass">Contraseña:</label>
							<input type="password" required="true" class="form-control" id="txtPass" name="txtPass" placeholder="Ingrese una Contraseña">
						</div>
						<div class="form-group" align="left">
							<label for="txtRePass">Reingrese Contraseña:</label>
							<input type="password" required="true" class="form-control" id="txtRePass" name="txtRePass" placeholder="Reingrese su Contraseña">
						</div>
						<div class="form-group" align="left">
							<a href="https://youtu.be/J1N-x02YzSA" target="_blank">¿No sabes como registrarte?</a>
						</div>
						<div class="row">
						  <div align="center" id="buttonsActionRegister">
							<button type="submit" class="button button-3d-primary button-rounded">Registrarme</button>
						  </div>
						</div>
						</br>
					</form>
				</div>
				<div id="login" class="col-xs-12 tab">
					<form id="loginFrm" method='post' action="<?php echo base_url()?>main/signin/">
						<div class="form-group" align="left">
							<label for="txtEmail">Email:</label>
							<input type="email" required="true" class="form-control" id="txtEmail" name="txtEmail" placeholder="Ingrese dirección de correo electrónico">
						</div>
						<div class="form-group" align="left">
							<label for="txtPass">Contraseña:</label>
							<input type="password" required="true" class="form-control" id="txtPass" name="txtPass" placeholder="Ingrese su Contraseña">
						</div>
						<div class="form-group" align="left">
							<a href="#" onclick="$('#mdClave').modal('show');">¿Ha olvidado la constraseña?</a>
						</div>
						<div class="row">
						  <div align="center" id="buttonsActionLogin">
							<button type="submit" class="button button-3d-primary button-rounded">Entrar</button>
						  </div>
						</div>
						</br>
					</form>
				</div>
			</div>
		</div>
		</div>
  </div>
  
<!--footer-->
<div class="navbar-inverse navbar-fixed-bottom" style="background-color:rgba(15,15,15,0.9); margin-top:10px;" role="navigation">
	<div class="container" style="margin:0px;" >
		<a href="http://www.encoding-ideas.com" style="color:white; font-size:2vmin ;">
			<img src="<?php echo base_url()?>static/img/mini_logo.png" style="height:30px;" alt="">
			Desarrollado por <b>Encoding Ideas</b>
		</a>
	</div>
</div>
<!--modal mensaje-->
<div id="mdMsj" class="modal fade">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header" style="background: rgba(255, 209, 63, 0.88) url(<?php echo base_url()?>static/img/logo.png) no-repeat 99% center ; background-size:auto 120%;">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h3 class="modal-title">Bienvenido</h3>
			</div>
			<form role="form" id='frmMdClient'>
				<div class="modal-body">		
					<p>El registro ha sido existoso, ahora ya puedes iniciar sesión!</br></p>
				</div>
			
				<div class="modal-footer">
					<div class="row">
						<div align="center">
							<button type="button" class="button button-3d-primary button-rounded" data-dismiss="modal">Listo</button>
						</div>
					</div>
				</div>
			</form>
		</div>
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
						<p><strong>Bon Appétit</strong> es una Guía Gastronómica que te permitirá anunciar tus servicios y captar nuevos clientes a traves del <strong>Mobile Marketing</strong>.<br/> Ademas te brindará las herramientas necesarias para la <strong>Administración</strong> de tu <strong>PYME</strong>.</br></p>
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
<!--MODAL NUEVA CONTRASEÑA-->
	<div id="mdClave" class="modal fade">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header" style="background: rgba(255, 209, 63, 0.88) url(<?php echo base_url()?>static/img/logo.png) no-repeat right center ; background-size:auto 120%;">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h3 class="modal-title">Nueva Contraseña</h3>
				</div>
				<form role="form" id='frmMdPass'>
					<div class="modal-body col-xs-12 col-sm-10 col-sm-offset-1">		
						<p><strong>Aviso:</strong> Te proporcionaremos una nueva contraseña y la enviaremos a tu direccion de e-mail. Completa el siguiente formulario:</br></p>
						<div class="form-group" align="left">
							<label for="txtEmailClv">Email:</label>
							<input type="email" required="true" class="form-control" id="txtEmailClv" name="txtEmailClv" placeholder="Ingrese dirección de correo electrónico">
						</div>
					</div>
				
					<div class="modal-footer">
						<div class="row col-xs-12">
							<div align="center" id="buttonsActionRegister">
								<button type="submit" class="button button-3d-primary button-rounded">Enviar</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
<!--END MODAL NUEVA CONTRASEÑA-->
</body>
</html>
