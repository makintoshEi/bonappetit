<div class="well optionBar" align="center" id="headerTittle" style="background:#f5f5f5 url(<?php echo base_url()?>static/img/fg-clientes.png) no-repeat 10% center; background-size: auto 105%;">
	<h3>CLIENTES</h3>
</div>
<div class="well panel panel-default" style="margin-top:1%;">
	<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
	  <!-- CREAR CLIENTE -->
	  <div class="panel panel-primary">
		<div class="panel-heading" role="tab" id="headingOne">
		  <h4 class="panel-title">
			<a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
			  CREAR CLIENTE
			</a>
		  </h4>
		</div>
		<div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
		  <div class="panel-body">
			
			<div class="row">
				<div id="divFrmClient" class="col-md-6 col-md-offset-3" style="border: 1px solid #ccc; padding:10px 35px 40px 35px;background-color:#FFF;">	
					<form id="frmNewClient">
						<fieldset class="scheduler-border">
						  <legend class="scheduler-border">Datos</legend>		  
						  <div class="form-group">
							<label for="txtName">C.I./R.U.C.:</label>
							<input type="text" required="true" class="form-control" id="txtCedula" name="txtCedula" placeholder="Ingrese C.I./R.U.C." maxlength="13"/>
						  </div>
						  <div class="form-group">
							<label for="txtName">Nombre:</label>
							<input type="text" required="true" class="form-control" id="txtNombre" name="txtNombre" placeholder="Ingrese Nombre"/>
						  </div>
						  <div class="form-group">
							<label for="txtName">Apellido:</label>
							<input type="text" required="true" class="form-control" id="txtApellido" name="txtApellido" placeholder="Ingrese Apellido"/>
						  </div>
						  <div class="form-group">
							<label for="txtName">E-mail:</label>
							<input type="email" required="true"  class="form-control" id="txtEmail" name="txtEmail" placeholder="Ingrese Email"/>
						  </div>
						  <div class="form-group">
							<label for="txtName">Dirección:</label>
							<input type="text" class="form-control" id="txtDireccion" name="txtDireccion" placeholder="Ingrese Dirección"/>
						  </div>
						  <div class="form-group">
							<label for="txtName">Teléfono:</label>
							<input type="text" class="form-control" id="txtTelefono" name="txtTelefono" placeholder="Ingrese Dirección"/>
						  </div>
						</fieldset>
					  <div class="row">
						  <div align="center">
							<button type="submit" class="button button-3d-primary button-rounded">Guardar</button>
						  </div>
					  </div>
					</form>
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
			<a class="collapsed" id="ltClient" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
			  LISTAR CLIENTES
			</a>
		  </h4>
		</div>
		<div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
		  <div class="panel-body">
			
			<div class="row">
				<div class="col-md-10 col-md-offset-1">
					<table data-order='[[ 0, "asc" ]]' class="table table-hovered table-bordered" cellspacing="0" width="100%" id="tbClients">
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
<div id="mdClient" class="modal fade">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title">Editar Cliente</h4>
			</div>
			<form id="frmMdClient" role="form">
				<div class="modal-body">		
				
					<span id="spIdCliMd"></span>
					
					<fieldset class="scheduler-border">
						  <legend class="scheduler-border">Datos</legend>		  
						  <div class="form-group">
							<label for="txtCedulaMd">C.I./R.U.C.:</label>
							<input type="text" required="true" class="form-control" id="txtCedulaMd" name="txtCedulaMd" placeholder="Ingrese C.I./R.U.C."/>
						  </div>
						  <div class="form-group">
							<label for="txtNombreMd">Nombre:</label>
							<input type="text" required="true" class="form-control" id="txtNombreMd" name="txtNombreMd" placeholder="Ingrese Nombre"/>
						  </div>
						  <div class="form-group">
							<label for="txtApellidoMd">Apellido:</label>
							<input type="text" required="true" class="form-control" id="txtApellidoMd" name="txtApellidoMd" placeholder="Ingrese Apellido"/>
						  </div>
						  <div class="form-group">
							<label for="txtEmailMd">E-mail:</label>
							<input type="email" required="true" class="form-control" id="txtEmailMd" name="txtEmailMd" placeholder="Ingrese Email"/>
						  </div>
						  <div class="form-group">
							<label for="txtDireccionMd">Dirección:</label>
							<input type="text" class="form-control" id="txtDireccionMd" name="txtDireccionMd" placeholder="Ingrese Dirección"/>
						  </div>
						  <div class="form-group">
							<label for="txtTelefonoMd">Teléfono:</label>
							<input type="text" class="form-control" id="txtTelefonoMd" name="txtTelefonoMd" placeholder="Ingrese Teléfono"/>
						  </div>
						</fieldset>
				</div>
			
				<div class="modal-footer">
					<div class="row">
						<div align="center">
							<button type="button" class="button button-3d button-rounded" data-dismiss="modal">Cancelar</button>
							<button type="button" class="button button-3d-primary button-rounded" id="updateClient">Guardar</button>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>


<!-- End Modal HTML -->