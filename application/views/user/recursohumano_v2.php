<div class="well optionBar" align="center" id="headerTittle" style="background:#f5f5f5 url(<?php echo base_url()?>static/img/fg-empleado.png) no-repeat 10% center; background-size: auto 105%;">
	<h3>RECURSOS HUMANOS</h3>
</div>
<div class="well panel panel-default" style="margin-top:1%;">
	<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
	  <!-- CREAR RECURSO HUMANO -->
	  <div class="panel panel-primary">
		<div class="panel-heading" role="tab" id="headingOne">
		  <h4 class="panel-title">
			<a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
			  CREAR RECURSO HUMANO
			</a>
		  </h4>
		</div>
		<div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
		  <div class="panel-body">
			
			<div class="row">
				<div id="divFrmClient" class="col-md-6 col-md-offset-3" style="border: 1px solid #ccc; padding:10px 35px 10px 35px;background-color:#FFF;">	
					<form id="frmNewRRHH">
						<fieldset class="scheduler-border">
						  <legend class="scheduler-border">Datos General</legend>		  
						  <div class="form-group">
							<label for="txtCedula" title="Campo Obligatorio">*C.I.:</label>
							<input type="text" required="true" class="form-control" id="txtCedula" name="txtCedula" placeholder="Ingrese Cédula de Identidad" maxlength="13"/>
						  </div>
						  <div class="form-group">
							<label for="txtNombre" title="Campo Obligatorio">*Nombre:</label>
							<input type="text" required="true" class="form-control" id="txtNombre" name="txtNombre" placeholder="Ingrese Nombre"/>
						  </div>
						  <div class="form-group">
							<label for="txtApellido" title="Campo Obligatorio">*Apellido:</label>
							<input type="text" required="true" class="form-control" id="txtApellido" name="txtApellido" placeholder="Ingrese Apellido"/>
						  </div>
						  <div class="form-group">
							<label for="txtEmail" title="Campo Obligatorio">*E-mail:</label>
							<input type="email" required="true" class="form-control" id="txtEmail" name="txtEmail" placeholder="Ingrese Email"/>
						  </div>
						  <div class="form-group">
							<label for="txtSalario">Salario:</label>
							<input type="number" step="0.01" min="0" required="true" class="form-control" id="txtSalario" name="txtSalario" placeholder="Ingrese Salario"/>
						  </div>
						  <div class="form-group">
							<label for="slcRol" title="Campo Obligatorio">*Rol:</label>
							<select class="form-control" id="slcRol" name="slcRol" required="true">
								<?php
									if ( ! empty($roles))
									{
										foreach ($roles as $key => $value) 
										{
											echo '<option value="'.$value['rol_id'].'">'.$value['rol_dsc'].'</option>';
										}
									}
								?>
							</select>
						  </div>
						  <div class="form-group">
							<label>Acceso a Modulos:</label>
							<div id="modulos" style="padding:5px;" class="well">
							
							</div>
						  </div>
						</fieldset>
					  <div class="row">
						  <div align="center" id="actionButtons">
							<button type="submit" class="button button-3d-primary button-rounded">Guardar</button>
						  </div>
					  </div>
					  <br/>
					  <div class="alert alert-warning alert-dismissable">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<h5><strong>(*) </strong>Campo de carácter obligatorio.</h5>
					  </div>
					</form>
					<br/>
				</div>
			</div>
			
		  </div>
		</div>
	  </div>
	  <!--END CREAR CLIENTE-->
	  <!-- LISTAR MODELOS -->
	  <div class="panel panel-primary">
		<div class="panel-heading" role="tab" id="headingThree">
		  <h4 class="panel-title">
			<a class="collapsed" id="ltRRHH" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
			  LISTAR RECURSOS HUMANOS
			</a>
		  </h4>
		</div>
		<div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
		  <div class="panel-body">
			
			<div class="row">
				<div class="col-md-10 col-md-offset-1">
					<table data-order='[[ 0, "asc" ]]' class="table table-hovered table-bordered" cellspacing="0" width="100%" id="tbRRHH">
						<thead>
							<tr>
								<th class="text-center"> Cédula </th>
								<th class="text-center"> Nombres </th>
								<th class="text-center"> Apellidos </th>
								<th class="text-center"> E-mail </th>
								<th class="text-center">Acción</th>
							</tr>
						</thead>
						
					</table>
				</div>
			</div>

		  </div>
		</div>
	  </div>
	  <!-- END LISTAR MODELOS -->
	</div>
</div>
<!-- Modal HTML -->
<div id="mdRRHH" class="modal fade">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title">Editar Recurso Humano</h4>
			</div>
			<form id="frmMdRRHH" role="form">
				<div class="modal-body">		
				
					<span id="spIdRRHHMd"></span>
					
					<fieldset class="scheduler-border">
						  <legend class="scheduler-border">Datos Generales</legend>		  
						  <div class="form-group">
							<label for="txtCedulaMd">*C.I.:</label>
							<input type="text" required="true" class="form-control" id="txtCedulaMd" name="txtCedulaMd" placeholder="Ingrese Cédula de Identidad"/>
						  </div>
						  <div class="form-group">
							<label for="txtNombreMd">*Nombre:</label>
							<input type="text" required="true" class="form-control" id="txtNombreMd" name="txtNombreMd" placeholder="Ingrese Nombre"/>
						  </div>
						  <div class="form-group">
							<label for="txtApellidoMd">*Apellido:</label>
							<input type="text" required="true" class="form-control" id="txtApellidoMd" name="txtApellidoMd" placeholder="Ingrese Apellido"/>
						  </div>
						  <div class="form-group">
							<label for="txtEmailMd">*E-mail:</label>
							<input type="email" required="true" class="form-control" id="txtEmailMd" name="txtEmailMd" placeholder="Ingrese Email"/>
						  </div>
						  <div class="form-group">
							<label for="txtSalarioMd">Salario:</label>
							<input type="number" step="0.01" min="0" required="true" class="form-control" id="txtSalarioMd" name="txtSalarioMd" placeholder="Ingrese Salario"/>
						  </div>
						  <div class="form-group">
							<label for="slcRolMd" title="Campo Obligatorio">*Rol:</label>
							<select class="form-control" id="slcRolMd" name="slcRolMd" required="true">
								<?php
									if ( ! empty($roles))
									{
										foreach ($roles as $key => $value) 
										{
											echo '<option value="'.$value['rol_id'].'">'.$value['rol_dsc'].'</option>';
										}
									}
								?>
							</select>
						  </div>
						  <div class="form-group">
							<label>Acceso a Modulos:</label>
							<div id="modulosMd" style="padding:5px;" class="well">
							
							</div>
						  </div>
					</fieldset>
					<fieldset class="scheduler-border" id="storeData" style="display:none;">
						  <legend class="scheduler-border">Datos Sucursal</legend>		  
						  <div class="form-group">
							<label for="txtNumero">N° Sucursal:</label>
							<input type="text" readonly="true" class="form-control" id="txtNumero" name="txtNumero" placeholder="N° de Sucursal"/>
						  </div>
						  <div class="form-group">
							<label for="txtNombreSuc">Nombre:</label>
							<input type="text" readonly="true" class="form-control" id="txtNombreSuc" name="txtNombreSuc" placeholder="Nombre de Sucursal"/>
						  </div>
						  <div class="form-group">
							<label for="txtCiudadSuc">Ciudad:</label>
							<input type="text" readonly="true" class="form-control" id="txtCiudadSuc" name="txtCiudadSuc" placeholder="Nombre de Ciudad"/>
						  </div>
						  <div class="form-group">
							<label for="txtDireccionSuc">Dirección:</label>
							<input type="text" readonly="true" class="form-control" id="txtDireccionSuc" name="txtDireccionSuc" placeholder="Dirección de la Sucursal"/>
						  </div>
					</fieldset>
				</div>
			
				<div class="modal-footer">
					<div class="row">
						<div align="center" id="actionButtonsMd">
							<button type="button" class="button button-3d button-rounded" data-dismiss="modal">Cancelar</button>
							<button type="button" class="button button-3d-primary button-rounded" id="updateClient">Guardar</button>
						</div>
					</div>
				</div>
				<br/>
					<div class="alert alert-warning alert-dismissable">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<h5><strong>(*) </strong>Campo de carácter obligatorio.</h5>
					</div>
			</form>
		</div>
	</div>
</div>


<!-- End Modal HTML -->