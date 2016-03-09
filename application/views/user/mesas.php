<div class="well optionBar" align="center" id="headerTittle" style="background:#f5f5f5 url(<?php echo base_url()?>static/img/fg-mesas.png) no-repeat 10% center; background-size: auto 105%;">
	<h3>MESAS</h3>
</div>
<div class="well panel panel-default" style="margin-top:1%;">
	<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
	  <!-- CREAR BEBIDA -->
	  <div class="panel panel-primary">
		<div class="panel-heading" role="tab" id="headingOne">
		  <h4 class="panel-title">
			<a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
			  CREAR MESA
			</a>
		  </h4>
		</div>
		<div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
		  <div class="panel-body">
			
			<div class="row">
				<div id="divFrmTable" class="col-xs-12 col-sm-8 col-sm-offset-2" style="border: 1px solid #ccc; padding:10px 35px 40px 35px;background-color:#FFF;">	
					<form id="frmNewTable">
						<fieldset class="scheduler-border">
						  <legend class="scheduler-border">Datos de Mesa</legend>
						  <div class="form-group col-xs-12 col-sm-7">
							<label for="txtEstablecimiento">Sucursal:</label>
							<?php if($tipo=="E") {
							echo "<select id='txtEstablecimiento' name='txtEstablecimiento' required='true' class='form-control'>
									<option value='".$numeracion[0]["suc_num"]."'>".str_pad($numeracion[0]["suc_num"], 3, "0", STR_PAD_LEFT)." : ".$numeracion[0]["suc_nom"]."</option>
								</select>";
								
							} 
							else
							{
								if($tipo=="A")
								{
									if ( ! empty($numeracion))
									{
										echo "<select id='txtEstablecimiento' name='txtEstablecimiento' required='true' class='form-control'>";
										foreach ($numeracion as $key => $value) 
										{
											
											echo '<option value="'.$value['suc_num'].'">'.str_pad($value["suc_num"], 3, "0", STR_PAD_LEFT).' : '.$value["suc_nom"].'</option>';
										}
										echo "</select>";
									}
								}
							}
							?>
						  </div>
						  <div class="form-group col-xs-12 col-sm-6">
							<label for="txtNumero">Número:</label>
							<input type="number" step="1" class="form-control" id="txtNumero" name="txtNumero" value="1" min="1" placeholder="Ingrese número de mesa"/>
						  </div>
						  <div class="form-group col-xs-12 col-sm-6">
							<label for="txtCapacidad">Capacidad:</label>
							<input type="number" step="1" class="form-control" id="txtCapacidad" name="txtCapacidad" value="1" min="1" placeholder="Ingrese capacidad de mesa"/>
						  </div>
						</fieldset>
					  <div class="row">
						  <div align="center" id="actionButtons">
							<button type="reset" class="button button-3d button-rounded">Borrar</button>
							<button type="submit" class="button button-3d-primary button-rounded">Guardar</button>
						  </div>
					  </div>
					</form>
				</div>
			</div>
			
		  </div>
		</div>
	  </div>
	  <!--END CREAR BEBIDA-->
	  <!-- LISTAR BEBIDAS -->
	  <div class="panel panel-primary">
		<div class="panel-heading" role="tab" id="headingThree">
		  <h4 class="panel-title">
			<a class="collapsed" id="ltMesas" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
			  LISTAR MESAS
			</a>
		  </h4>
		</div>
		<div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
		  <div class="panel-body">
			
			<div class="row">
				<div class="col-md-10 col-md-offset-1">
					<table data-order='[[ 0, "asc" ],[ 0, "asc" ],[ 0, "asc" ],[ 0, "asc" ]]' class="table table-hovered table-bordered" cellspacing="0" width="100%" id="tbMesas">
						<thead>
							<tr>
								<th class="text-center">N°</th>
								<th class="text-center">Capacidad</th>
								<th class="text-center">Estado</th>
								<th class="text-center">Sucursal</th>
								<th class="text-center">Acción</th>
							</tr>
						</thead>
						
					</table>
				</div>
			</div>

		  </div>
		</div>
	  </div>
	  <!-- END LISTAR FACTURAS -->
	</div>
</div>
<!-- Modal HTML -->
<div id="mdTable" class="modal fade">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title">Editar Mesa</h4>
			</div>
			<form role="form" id='frmMdTable'>
				<span id="spId"></span>
				<div class="modal-body">		
					<fieldset class="scheduler-border">
					  <legend class="scheduler-border">Datos de Mesa</legend>
					  <div class="form-group col-xs-12 col-sm-8">
						<label for="txtSucursalMd">Sucursal:</label>
						<input readonly="true" class="form-control" id="txtSucursalMd" name="txtSucursalMd"/>
					  </div>
					  <div class="form-group col-xs-12 col-sm-6">
						<label for="txtNumeroMd">Número:</label>
						<input type="number" readonly="true" class="form-control" id="txtNumeroMd" name="txtNumeroMd" placeholder="Ingrese número de mesa"/>
					  </div>
					  <div class="form-group col-xs-12 col-sm-6">
						<label for="txtCapacidadMd">Capacidad:</label>
						<input type="number" step="1" min="1" required="true" class="form-control" id="txtCapacidadMd" name="txtCapacidadMd" placeholder="Ingrese capacidad de mesa"/>
					  </div>
					</fieldset>
				</div>		
				<div class="modal-footer">		
					<div class="row">
						  <div align="center" id="actionButtonsEdit">
							<button type="button"  data-dismiss="modal" class="button button-3d button-rounded">Cerrar</button>
							<button type="submit" class="button button-3d-primary button-rounded">Guardar</button>
						  </div>
					</div>
			    </div>
			</form>
		</div>
	</div>
</div>


<!-- End Modal HTML -->