<div>
<div class="well optionBar" align="center" id="headerTittle" style="background:#f5f5f5 url(<?php echo base_url()?>static/img/fg-sucursal.png) no-repeat 10% center; background-size: auto 110%;">
	<h3>SUCURSALES</h3>
</div>
<div class="well panel panel-default" style="margin-top:1%;">
	<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
	<div class="panel panel-primary">
		<div class="panel-heading" role="tab" id="headingOne">
		  <h4 class="panel-title">
			<a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
				CREAR SUCURSAL
			</a>
		  </h4>
		</div>
		<div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
		  <div class="panel-body">
			
			<div class="row">
				<div class="col-xs-12 col-sm-8 col-sm-offset-2" style="border: 1px solid #ccc; padding:10px 35px 40px 35px;background-color:#FFF;">	
					<form id="frmNewStore">
						<fieldset class="scheduler-border">
							<legend class="scheduler-border">Datos Generales</legend>
							<div class="form-group" align="left">
								<label for="txtNumero">*N° de Establecimiento:</label>
								<input type="number" value="1" step="1" min="1" required="true" class="form-control" id="txtNumero" name="txtNumero" placeholder="Ingrese Número de Establecimiento">
							</div>
							<div class="form-group" align="left">
								<label for="txtNombre">*Nombre:</label>
								<input type="text" required="true" class="form-control" id="txtNombre" name="txtNombre" placeholder="Ingrese Nombre">
							</div>
							<div class="form-group" align="left">
								<label for="txtTlf">Teléfono(s):</label>
								<input type="text" class="form-control" id="txtTlf" name="txtTlf" placeholder="Ingrese Número(s) de Teléfono">
							</div>
							<div class="alert alert-info alert-dismissable">
								<button type="button" class="close" data-dismiss="alert">&times;</button>
								<h5><strong>Nota: </strong><i>'N° de Establecimiento'</i> es utilizada en la numeración de las facturas.</h5>
							</div>
						</fieldset>
						<fieldset class="scheduler-border">
							<legend class="scheduler-border">Ubicación</legend>
							<div class="form-group" align="left">
								<label for="txtDir">*Provincia:</label>
								<select class="form-control" id="slcProv" name="slcProv" required="true">
								<?php
									if ( ! empty($provincias))
									{
										foreach ($provincias as $key => $value) 
										{
											echo '<option value="'.$value['prv_id'].'" '.($prv_id==$value['prv_id']?'selected':'').'>'.$value['prv_nom'].'</option>';
										}
									}
								?>
								</select>
							</div>
							<div class="form-group" align="left">
								<label for="slcCiu">*Ciudad:</label>
								<select class="form-control" id="slcCiu" name="slcCiu" required="true">
								</select>									
							</div>
							<div class="form-group" align="left">
								<label for="txtDir">*Dirección:</label>
								<input type="text" required="true" class="form-control" id="txtDir" name="txtDir" placeholder="Ingrese Dirección de Restaurant">
							</div>
							<div class="form-group" align="left">
								<label for="txtDir">*Geolocalización:</label>
								<div class="embed-responsive embed-responsive-16by9" align="center">
									<div id="googleMap" style="width:500px; height:300px;"></div>
								</div>
							</div>
						</fieldset>
						<fieldset class="scheduler-border">
							<legend class="scheduler-border">Horarios de Atención</legend>
							<div class="form-group" align="left">
								<label for="txtLunes">*Lunes:</label>
								<input type="text" required="true" class="form-control" id="txtLunes" name="txtLunes" placeholder="Ingrese horario de atencion en dias Lunes">
							</div>
							<div class="form-group" align="left">
								<label for="txtMartes">*Martes:</label>
								<input type="text" required="true" class="form-control" id="txtMartes" name="txtMartes" placeholder="Ingrese horario de atencion en dias Martes">
							</div>
							<div class="form-group" align="left">
								<label for="txtMiercoles">*Miércoles:</label>
								<input type="text" required="true" class="form-control" id="txtMiercoles" name="txtMiercoles" placeholder="Ingrese horario de atencion en dias Miércoles">
							</div>
							<div class="form-group" align="left">
								<label for="txtJueves">*Jueves:</label>
								<input type="text" required="true" class="form-control" id="txtJueves" name="txtJueves" placeholder="Ingrese horario de atencion en dias Jueves">
							</div>
							<div class="form-group" align="left">
								<label for="txtViernes">*Viernes:</label>
								<input type="text" required="true" class="form-control" id="txtViernes" name="txtViernes" placeholder="Ingrese horario de atencion en dias Viernes">
							</div>
							<div class="form-group" align="left">
								<label for="txtSabado">*Sábado:</label>
								<input type="text" required="true" class="form-control" id="txtSabado" name="txtSabado" placeholder="Ingrese horario de atencion en dias Sábado">
							</div>
							<div class="form-group" align="left">
								<label for="txtDomingo">*Domingo:</label>
								<input type="text" required="true" class="form-control" id="txtDomingo" name="txtDomingo" placeholder="Ingrese horario de atencion en dias Domingo">
							</div>
							<div class="alert alert-info alert-dismissable">
								<button type="button" class="close" data-dismiss="alert">&times;</button>
								<h5><strong>Ejemplo: </strong>Lunes: Desde 9:00 hasta 19:00 / Domingo: No hay atención.</h5>
							</div>
						</fieldset>
						<fieldset class="scheduler-border">
							<legend class="scheduler-border">Recursos Humanos</legend>
							<div class="form-group" align="left">
								<label for="txtPersonal">Personal:</label>
								<input type="text" class="form-control" id="txtPersonal" name="txtPersonal" placeholder="Ingrese Cédula o Nombre del Recurso Humano"/>
								<br>
								<div style="overflow-x:hidden; overflow-y:auto; max-height:110px;" id="divTbDetails">
									<table class="table-hovered table-bordered" cellspacing="0" width="100%" id="details">
										
										<thead>
											<tr>
												<th class="text-center">Cédula</th>
												<th class="text-center" width="40%">Nombre</th>
												<th class="text-center">E-mail</th>
												<th class="text-center">Acción</th>
											</tr>
										</thead>
										<tbody id="tbodyDetails">
											
										</tbody>
									</table>
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
				</div>
			</div>
		  </div>
		</div>
	  </div>
	<!-- LISTAR SUCURSALES -->
	  <div class="panel panel-primary">
		<div class="panel-heading" role="tab" id="headingThree">
		  <h4 class="panel-title">
			<a class="collapsed" id="ltStore" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
			  LISTAR SUCURSALES
			</a>
		  </h4>
		</div>
		<div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
		  <div class="panel-body">
			
			<div class="row">
				<div class="col-md-10 col-md-offset-1">
					<table data-order='[[ 0, "asc" ]]' class="table table-hovered table-bordered" cellspacing="0" width="100%" id="tbStore">
						<thead>
							<tr>
								<th class="text-center">N° </th>
								<th class="text-center">Nombre </th>
								<th class="text-center">Provincia / Ciudad </th>
								<th class="text-center">Dirección</th>
								<th class="text-center">Acción</th>
							</tr>
						</thead>
						
					</table>
				</div>
			</div>

		  </div>
		</div>
	  </div>
	  <!-- END LISTAR SUCURSALES -->
	</div>
</div>
</div>
<!-- Modal HTML -->
<div id="mdStore" class="modal fade">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title">Editar Sucursal</h4>
			</div>
			<form id="frmMdStore" role="form">
				<div class="modal-body">		
					<span id="spIdStoreMd"></span>
					<fieldset class="scheduler-border">
						<legend class="scheduler-border">Datos Generales</legend>
						<div class="form-group" align="left">
							<label for="txtNumeroMd">*N° de Establecimiento:</label>
							<input type="number" value="1" step="1" min="1" required="true" class="form-control" id="txtNumeroMd" name="txtNumeroMd" placeholder="Ingrese Número de Establecimiento">
						</div>
						<div class="form-group" align="left">
							<label for="txtNombreMd">*Nombre:</label>
							<input type="text" required="true" class="form-control" id="txtNombreMd" name="txtNombreMd" placeholder="Ingrese Nombre">
						</div>
						<div class="form-group" align="left">
							<label for="txtTlfMd">Teléfono(s):</label>
							<input type="text" class="form-control" id="txtTlfMd" name="txtTlfMd" placeholder="Ingrese Número(s) de Teléfono">
						</div>
						<div class="alert alert-info alert-dismissable">
							<button type="button" class="close" data-dismiss="alert">&times;</button>
							<h5><strong>Nota: </strong><i>'N° de Establecimiento'</i> es utilizada en la numeración de las facturas.</h5>
						</div>
					</fieldset>
					<fieldset class="scheduler-border">
						<legend class="scheduler-border">Ubicación</legend>
						<div class="form-group" align="left">
							<label for="slcProvMd">*Provincia:</label>
							<select class="form-control" id="slcProvMd" name="slcProvMd" required="true">
							<?php
								if ( ! empty($provincias))
								{
									foreach ($provincias as $key => $value) 
									{
										echo '<option value="'.$value['prv_id'].'" '.($prv_id==$value['prv_id']?'selected':'').'>'.$value['prv_nom'].'</option>';
									}
								}
							?>
							</select>
						</div>
						<div class="form-group" align="left">
							<label for="slcCiuMd">*Ciudad:</label>
							<select class="form-control" id="slcCiuMd" name="slcCiuMd" required="true">
							</select>									
						</div>
						<div class="form-group" align="left">
							<label for="txtDirMd">*Dirección:</label>
							<input type="text" required="true" class="form-control" id="txtDirMd" name="txtDirMd" placeholder="Ingrese Dirección de Restaurant">
						</div>
						<div class="form-group" align="left">
							<label for="txtDir">*Geolocalización:</label>
							<div class="embed-responsive embed-responsive-16by9" align="center">
								<div id="googleMapEdit" style="width:500px; height:300px;"></div>
							</div>
						</div>
					</fieldset>
					<fieldset class="scheduler-border">
						<legend class="scheduler-border">Horarios de Atención</legend>
						<div class="form-group" align="left">
							<label for="txtLunesMd">*Lunes:</label>
							<input type="text" required="true" class="form-control" id="txtLunesMd" name="txtLunesMd" placeholder="Ingrese horario de atencion en dias Lunes">
						</div>
						<div class="form-group" align="left">
							<label for="txtMartesMd">*Martes:</label>
							<input type="text" required="true" class="form-control" id="txtMartesMd" name="txtMartesMd" placeholder="Ingrese horario de atencion en dias Martes">
						</div>
						<div class="form-group" align="left">
							<label for="txtMiercolesMd">*Miércoles:</label>
							<input type="text" required="true" class="form-control" id="txtMiercolesMd" name="txtMiercolesMd" placeholder="Ingrese horario de atencion en dias Miércoles">
						</div>
						<div class="form-group" align="left">
							<label for="txtJuevesMd">*Jueves:</label>
							<input type="text" required="true" class="form-control" id="txtJuevesMd" name="txtJuevesMd" placeholder="Ingrese horario de atencion en dias Jueves">
						</div>
						<div class="form-group" align="left">
							<label for="txtViernesMd">*Viernes:</label>
							<input type="text" required="true" class="form-control" id="txtViernesMd" name="txtViernesMd" placeholder="Ingrese horario de atencion en dias Viernes">
						</div>
						<div class="form-group" align="left">
							<label for="txtSabadoMd">*Sábado:</label>
							<input type="text" required="true" class="form-control" id="txtSabadoMd" name="txtSabadoMd" placeholder="Ingrese horario de atencion en dias Sábado">
						</div>
						<div class="form-group" align="left">
							<label for="txtDomingoMd">*Domingo:</label>
							<input type="text" required="true" class="form-control" id="txtDomingoMd" name="txtDomingoMd" placeholder="Ingrese horario de atencion en dias Domingo">
						</div>
						<div class="alert alert-info alert-dismissable">
							<button type="button" class="close" data-dismiss="alert">&times;</button>
							<h5><strong>Ejemplo: </strong>Lunes: Desde 9:00 hasta 19:00 / Domingo: No hay atención.</h5>
						</div>
					</fieldset>
					<fieldset class="scheduler-border">
						<legend class="scheduler-border">Recursos Humanos</legend>
						<div class="form-group" align="left">
							<label for="txtPersonalMd">Personal:</label>
							<input type="text" class="form-control" id="txtPersonalMd" name="txtPersonalMd" placeholder="Ingrese Cédula o Nombre del Recurso Humano"/>
							<br>
							<div style="overflow-x:hidden; overflow-y:auto; max-height:110px;" id="divTbDetailsMd">
								<table class="table-hovered table-bordered" cellspacing="0" width="100%" id="detailsMd">
									
									<thead>
										<tr>
											<th class="text-center">Cédula</th>
											<th class="text-center" width="40%">Nombre</th>
											<th class="text-center">E-mail</th>
											<th class="text-center">Acción</th>
										</tr>
									</thead>
									<tbody id="tbodyDetailsMd">
										
									</tbody>
								</table>
							</div>
						</div>
					</fieldset>
				</div>
				<div class="modal-footer">
					<div class="row">
						<div align="center" id="actionButtonsMd">
							<button type="button" class="button button-3d button-rounded" data-dismiss="modal">Cancelar</button>
							<button type="submit" class="button button-3d-primary button-rounded" >Guardar</button>
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