<div class="well optionBar" align="center" id="headerTittle" style="background:#f5f5f5 url(<?php echo base_url()?>static/img/fg-facturacion.png) no-repeat 10% center; background-size: auto 105%;">
	<h3>FACTURACIÓN</h3>
</div>
<div class="well panel panel-default" style="margin-top:1%;">
	<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
	  <!-- CREAR FACTURA -->
	  <div class="panel panel-primary">
		<div class="panel-heading" role="tab" id="headingOne">
		  <h4 class="panel-title">
			<a data-toggle="collapse" data-parent="#accordion" id="ltNew" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
			  CREAR FACTURA
			</a>
		  </h4>
		</div>
		<div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
		  <div class="panel-body">
			
			<div class="row">
				<div id="divFrmInvoice" class="col-xs-12 col-sm-10 col-sm-offset-1" style="border: 1px solid #ccc; padding:10px 35px 40px 35px;background-color:#FFF;">	
					<form id="frmNewInvoice">
						<fieldset class="scheduler-border">
						  <legend class="scheduler-border">Datos del Cliente</legend>
						  <div class="form-group col-xs-12">
							<label for="txtCedula">C.I./R.U.C.:</label>
							<input type="text" required="true" class="form-control" id="txtCedula" name="txtCedula" placeholder="Ingrese C.I./R.U.C." maxlength="13"/>
						  </div>
						  <div class="form-group col-sm-6">
							<label for="txtNombre">Nombre:</label>
							<input type="text" required="true" class="form-control" id="txtNombre" name="txtNombre" placeholder="Ingrese Nombre"/>
						  </div>
						  <div class="form-group col-sm-6">
							<label for="txtApellido">Apellido:</label>
							<input type="text" required="true" class="form-control" id="txtApellido" name="txtApellido" placeholder="Ingrese Apellido"/>
						  </div>
						  <div class="form-group col-xs-12">
							<label for="txtEmail">E-mail:</label>
							<input type="email"  class="form-control" id="txtEmail" name="txtEmail" placeholder="Ingrese Email"/>
						  </div>
						  <div class="form-group col-xs-12">
							<label for="txtDireccion">Dirección:</label>
							<input type="text" required="true" class="form-control" id="txtDireccion" name="txtDireccion" placeholder="Ingrese Dirección"/>
						  </div>
						  <div class="form-group col-xs-12">
							<label for="txtTelefono">Teléfono:</label>
							<input type="text" class="form-control" id="txtTelefono" name="txtTelefono" placeholder="Ingrese Dirección"/>
						  </div>
						</fieldset>
						<fieldset class="scheduler-border">
						  <legend class="scheduler-border">Datos de Factura</legend>
						  <div class="form-group col-xs-12 col-sm-4">
							<label for="txtEstablecimiento">Numeración:</label>
							<?php if($tipo=="E") {?>
								<input type="number" value="<?php echo str_pad($numeracion[0]["suc_num"], 3, "0", STR_PAD_LEFT);?>" readonly="true" required="true"  min="1" max="999" step="1" class="form-control" id="txtEstablecimiento" name="txtEstablecimiento" placeholder="N° de establecimiento"/>
							<?php } 
							else
							{
								if($tipo=="A")
								{
									if ( ! empty($numeracion))
									{
										echo "<select id='txtEstablecimiento' name='txtEstablecimiento' required='true' class='form-control'>";
										foreach ($numeracion as $key => $value) 
										{
											
											echo '<option value="'.$value['suc_num'].'">'.str_pad($value["suc_num"], 3, "0", STR_PAD_LEFT).'</option>';
										}
										echo "</select>";
									}
								}
							}
							?>
						  </div>
						  <div class="form-group col-xs-12 col-sm-4">
							<label for="txtFacturero"></label>
							<input type="number" required="true" min="1" max="999" step="1" class="form-control" id="txtFacturero" name="txtFacturero" placeholder="Punto de Emisión"/>
						  </div>
						  <div class="form-group col-xs-12 col-sm-4">
							<label for="txtNumero"></label>
							<input type="number" min="1" max="999999999" step="1" class="form-control" id="txtNumero" name="txtNumero" placeholder="N° de factura"/>
						  </div>
						  <div class="form-group col-xs-12 col-sm-6">
							<label for="txtFecha">Fecha:</label>
							<input type="date" required="true" class="form-control" id="txtFecha" name="txtFecha"/>
						  </div>
						  <div class="form-group col-xs-12">
							<label for="txtProductos">Detalles:</label>
							<div id="divProduct">
								<input type="text" class="form-control" id="txtProductos" name="txtProductos" placeholder="Ingrese Productos"/>
							</div>
						    <br>
							<div style="overflow-x:hidden; overflow-y:auto;" id="divTbDetails">
								<table class="table-hovered table-bordered" cellspacing="0" width="100%" id="details">
									
									<thead>
										<tr>
											<th class="text-center" width="40%">Producto</th>
											<th class="text-center">Precio</th>
											<th class="text-center">Cant.</th>
											<th class="text-center">Total</th>
											<th class="text-center">Acción</th>
										</tr>
									</thead>
									<tbody id="tbodyDetails">
										
									</tbody>
								</table>
							</div>
						  </div>
						  
						  <div class="form-group col-xs-12">
							<label for="txtSubtotal">Subtotal:</label>
							<input type="number" readonly="true" class="form-control" id="txtSubtotal" name="txtSubtotal" value="0"/>
						  </div>
						  <div class="form-group col-xs-12">
							<label for="txtDesc">Descuento:</label><br/>
								<input type="number" readonly="true" class="form-control" id="txtCantDesc" name="txtCantDesc" value="0" style="width:65%; display:inline;"/>
								<input type="number" step="0.1" min="0" max="100" class="form-control" id="txtDesc" name="txtDesc" value="0" style="width:30%; display:inline;" onchange="$.calculateDesc('')"/>
						  </div>
						  <div class="form-group col-xs-12">
							<label for="chkIva">I.V.A:</label>
							<input type="checkbox" class="form-control" id="chkIva" name="chkIva" checked="true" onchange="$.calculateIva('')" style="width:15px; height:15px; display:inline;"/>
							<br/>
							<input type="number" readonly="true" class="form-control" id="txtIva" name="txtIva" value="0"/>
						  </div>
						  <div class="form-group col-xs-12">
							<label for="txtPropina">Propina:</label>
							<input type="number" step="0.01" class="form-control" id="txtPropina" name="txtPropina" onchange="$.subTotal('')" value="0" min="0"/>
						  </div>
						  <div class="form-group col-xs-12">
							<label for="txtTotal">Total:</label>
							<input type="number" readonly="true" class="form-control" id="txtTotal" name="txtTotal" value="0"/>
						  </div>
						</fieldset>
					  <div class="row">
						  <div align="center" id="actionButtonsNew">
							<button type="reset" onclick="$.vaciarDetalles('')" class="button button-3d button-rounded">Borrar</button>
							<button type="submit" class="button button-3d-primary button-rounded">Guardar</button>
						  </div>
					  </div>
					</form>
				</div>
			</div>
			
		  </div>
		</div>
	  </div>
	  <!--END CREAR FACTURA-->
	  <!-- LISTAR FACTURA -->
	  <div class="panel panel-primary">
		<div class="panel-heading" role="tab" id="headingThree">
		  <h4 class="panel-title">
			<a class="collapsed" id="ltFood" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
			  LISTAR FACTURAS
			</a>
		  </h4>
		</div>
		<div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
		  <div class="panel-body">
			
			<div class="row">
				<div class="col-sm-12">
					<table data-order='[[ 0, "asc" ],[ 0, "asc" ],[ 0, "asc" ],[ 0, "asc" ]]' class="table table-hovered table-bordered" cellspacing="0" width="100%" id="tbFacturas">
						<thead>
							<tr>
								<th class="text-center">Fecha</th>
								<th class="text-center">N°</th>
								<th class="text-center">Cliente</th>
								<th class="text-center">Total</th>
								<th class="text-center">Entregado</th>
								<th class="text-center">Autorizado</th>
								<th class="text-center" width="17%">Acción</th>
							</tr>
						</thead>
						
					</table>
				</div>
			</div>

		  </div>
		</div>
	  </div>
	  <!-- END LISTAR FACTURAS -->
	  <!-- ORDENES DE MESAS -->
	  <div class="panel panel-primary">
		<div class="panel-heading" role="tab" id="headingTwo">
		  <h4 class="panel-title">
			<a class="collapsed" id="ltOrdenes" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
			  ORDENES DE MESA
			</a>
		  </h4>
		</div>
		<div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
		  <div class="panel-body">
			
			<fieldset class="scheduler-border">
			  <legend class="scheduler-border">Datos de Mesa</legend>
			  <form role="form" id='frmMesas'>
				  <div class="form-group col-xs-12 col-sm-7">
					<label for="txtEstablecimientoMesas">Sucursal:</label>
					<?php if($tipo=="E") {
					echo "<select id='txtEstablecimientoMesas' name='txtEstablecimientoMesas' required='true' class='form-control'>
							<option value='".$numeracion[0]["suc_num"]."'>".str_pad($numeracion[0]["suc_num"], 3, "0", STR_PAD_LEFT)." : ".$numeracion[0]["suc_nom"]."</option>
						</select>";
						
					} 
					else
					{
						if($tipo=="A")
						{
							if ( ! empty($numeracion))
							{
								echo "<select id='txtEstablecimientoMesas' name='txtEstablecimientoMesas' required='true' class='form-control'>";
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
				  <div class="form-group col-xs-12">
					<label for="txtProductosMd">Mesas:</label>
					<br>
					<div style="overflow-x:hidden; overflow-y:auto;" id="divTbDetailsMd">
						<table class="table-hovered table-bordered" cellspacing="0" width="100%" id="detailsMesas">
							
							<thead>
								<tr>
									<th class="text-center">N°</th>
									<th class="text-center">Capacidad</th>
									<th class="text-center">Estado</th>
									<th class="text-center">Acción</th>
								</tr>
							</thead>
							<tbody id="tbodyMesas">
								
							</tbody>
						</table>
					</div>
				  </div>
			  </form>
			</fieldset>
		  </div>
		</div>
	  </div>
	  <!-- END ORDENES MESAS -->
	  <!-- CREAR REPORTE -->
	  <div class="panel panel-primary">
		<div class="panel-heading" role="tab" id="headingFour">
		  <h4 class="panel-title">
			<a data-toggle="collapse" data-parent="#accordion" id="ltNew" href="#collapseFour" aria-expanded="true" aria-controls="collapseFour">
			  REPORTES
			</a>
		  </h4>
		</div>
		<div id="collapseFour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFour">
		  <div class="panel-body">
			
			<div class="row">
				<div id="divFrmReports" class="col-xs-12 col-sm-10 col-sm-offset-1" style="border: 1px solid #ccc; padding:10px 35px 40px 35px;background-color:#FFF;">	
					<form id="frmReportVentas">
						<fieldset class="scheduler-border">
						  <legend class="scheduler-border">Reporte de Ventas(Ingresos)</legend>
						  <div class="form-group col-xs-12 col-sm-7">
							<label for="txtEstablecimientoReportVenta">Sucursal:</label>
							<?php if($tipo=="E") {?>
								<input type="number" value="<?php echo str_pad($numeracion[0]["suc_num"], 3, "0", STR_PAD_LEFT);?>" readonly="true" required="true"  min="1" max="999" step="1" class="form-control" id="txtEstablecimientoReportVenta" name="txtEstablecimientoReportVenta" placeholder="N° de establecimiento"/>
							<?php } 
							else
							{
								if($tipo=="A")
								{
									if ( ! empty($numeracion))
									{
										echo "<select id='txtEstablecimientoReportVenta' name='txtEstablecimientoReportVenta' required='true' class='form-control'>";
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
							<label for="txtFechaInicioReportVenta">Fecha Inicio:</label>
							<input type="date" required="true" class="form-control" id="txtFechaInicioReportVenta" name="txtFechaInicioReportVenta"/>
						  </div>
						  <div class="form-group col-xs-12 col-sm-6">
							<label for="txtFechaFinReportVenta">Fecha Fin:</label>
							<input type="date" required="true" class="form-control" id="txtFechaFinReportVenta" name="txtFechaFinReportVenta"/>
						  </div>
						  <div class="row col-xs-12">
							  <div align="center" id="actionButtonsNew">
								<button type="submit" class="button button-3d-primary button-rounded">Generar</button>
							  </div>
						  </div>
						</fieldset>
					</form>
					<br>
					<form id="frmReportTop">
						<fieldset class="scheduler-border">
						  <legend class="scheduler-border">Reporte de productos mas vendidos</legend>
						  <div class="form-group col-xs-12 col-sm-7">
							<label for="txtEstablecimientoReportTop">Sucursal:</label>
							<?php if($tipo=="E") {?>
								<input type="number" value="<?php echo str_pad($numeracion[0]["suc_num"], 3, "0", STR_PAD_LEFT);?>" readonly="true" required="true"  min="1" max="999" step="1" class="form-control" id="txtEstablecimientoReportTop" name="txtEstablecimientoReportTop" placeholder="N° de establecimiento"/>
							<?php } 
							else
							{
								if($tipo=="A")
								{
									if ( ! empty($numeracion))
									{
										echo "<select id='txtEstablecimientoReportTop' name='txtEstablecimientoReportTop' required='true' class='form-control'>";
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
							<label for="txtRangoReportTop">Rango:</label>
							<select id='txtRangoReportTop' name='txtRangoReportTop' required='true' class='form-control'>
								<option value="5"> 5 mas vendidos </option>
								<option value="10"> 10 mas vendidos </option>
								<option value="15"> 15 mas vendidos </option>
							</select>
						  </div>
						  <div class="form-group col-xs-12 col-sm-6">
							<label for="txtTipoProdReportTop">Tipo/Producto:</label>
							<select id='txtTipoProdReportTop' name='txtTipoProdReportTop' required='true' class='form-control'>
								<option value="1"> Bebidas </option>
								<option value="2"> Comidas </option>
								<option value="3"> Todos </option>
							</select>
						  </div>
						  <div class="form-group col-xs-12 col-sm-6">
							<label for="txtFechaInicioReportTop">Fecha Inicio:</label>
							<input type="date" required="true" class="form-control" id="txtFechaInicioReportTop" name="txtFechaInicioReportTop"/>
						  </div>
						  <div class="form-group col-xs-12 col-sm-6">
							<label for="txtFechaFinReportTop">Fecha Fin:</label>
							<input type="date" required="true" class="form-control" id="txtFechaFinReportTop" name="txtFechaFinReportTop"/>
						  </div>
						  <div class="row col-xs-12">
							  <div align="center" id="actionButtonsNew">
								<button type="submit" class="button button-3d-primary button-rounded">Generar</button>
							  </div>
						  </div>
						</fieldset>
					</form>
				</div>
			</div>
			
		  </div>
		</div>
	  </div>
	  <!--END REPORTES-->
	</div>
</div>
<!-- Modal HTML -->
<div id="mdInvoice" class="modal fade">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title">Revisar Factura</h4>
			</div>
			<form role="form" id='frmMdInvoice'>
				<span id="spId"></span>
				<div class="modal-body">		
					<fieldset class="scheduler-border">
						  <legend class="scheduler-border">Datos del Cliente</legend>
						  <div class="form-group col-xs-12">
							<label for="txtCedulaMd">C.I./R.U.C.:</label>
							<input type="text" required="true" class="form-control" id="txtCedulaMd" name="txtCedulaMd" placeholder="Ingrese C.I./R.U.C." maxlength="13"/>
						  </div>
						  <div class="form-group col-sm-6">
							<label for="txtNombreMd">Nombre:</label>
							<input type="text" required="true" class="form-control" id="txtNombreMd" name="txtNombreMd" placeholder="Ingrese Nombre"/>
						  </div>
						  <div class="form-group col-sm-6">
							<label for="txtApellidoMd">Apellido:</label>
							<input type="text" required="true" class="form-control" id="txtApellidoMd" name="txtApellidoMd" placeholder="Ingrese Apellido"/>
						  </div>
						  <div class="form-group col-xs-12">
							<label for="txtEmailMd">E-mail:</label>
							<input type="email"  class="form-control" id="txtEmailMd" name="txtEmailMd" placeholder="Ingrese Email"/>
						  </div>
						  <div class="form-group col-xs-12">
							<label for="txtDireccionMd">Dirección:</label>
							<input type="text" required="true" class="form-control" id="txtDireccionMd" name="txtDireccionMd" placeholder="Ingrese Dirección"/>
						  </div>
						  <div class="form-group col-xs-12">
							<label for="txtTelefonoMd">Teléfono:</label>
							<input type="text" class="form-control" id="txtTelefonoMd" name="txtTelefonoMd" placeholder="Ingrese Dirección"/>
						  </div>
						</fieldset>
						<fieldset class="scheduler-border">
						  <legend class="scheduler-border">Datos de Factura</legend>
						  <div class="form-group col-xs-12 col-sm-6">
							<label for="txtNumeroMd">Número:</label>
							<input type="text" required="true" class="form-control" id="txtNumeroMd" name="txtNumeroMd" placeholder="Ingrese número de factura"/>
						  </div>
						  <div class="form-group col-xs-12 col-sm-6">
							<label for="txtFechaMd">Fecha:</label>
							<input type="date" required="true" class="form-control" id="txtFechaMd" name="txtFechaMd"/>
						  </div>
						  <div class="form-group col-xs-12">
							<label for="txtProductosMd">Detalles:</label>
							<br>
							<div style="overflow-x:hidden; overflow-y:auto; max-height:110px;" id="divTbDetailsMd">
								<table class="table-hovered table-bordered" cellspacing="0" width="100%" id="detailsMd">
									
									<thead>
										<tr>
											<th class="text-center" width="40%">Producto</th>
											<th class="text-center">Precio</th>
											<th class="text-center">Cant.</th>
											<th class="text-center">Total</th>
										</tr>
									</thead>
									<tbody id="tbodyDetailsMd">
										
									</tbody>
								</table>
							</div>
						  </div>
						  
						  <div class="form-group col-xs-12">
							<label for="txtSubtotalMd">Subtotal:</label>
							<input type="number" readonly="true" class="form-control" id="txtSubtotalMd" name="txtSubtotalMd" value="0"/>
						  </div>
						  <div class="form-group col-xs-12">
							<label for="txtCantDescMd">Descuento:</label><br/>
								<input type="number" readonly="true" class="form-control" id="txtCantDescMd" name="txtCantDescMd" value="0"/>
						  </div>
						  <div class="form-group col-xs-12">
							<label for="txtIva">I.V.A:</label>
							<input type="number" readonly="true" class="form-control" id="txtIvaMd" name="txtIvaMd" value="0"/>
						  </div>
						  <div class="form-group col-xs-12">
							<label for="txtPropinaMd">Propina:</label>
							<input type="number" readonly="true" class="form-control" id="txtPropinaMd" name="txtPropinaMd" onchange="$.subTotal('Orden')" value="0"/>
						  </div>
						  <div class="form-group col-xs-12">
							<label for="txtTotalMd">Total:</label>
							<input type="number" readonly="true" class="form-control" id="txtTotalMd" name="txtTotalMd" value="0"/>
						  </div>
						</fieldset>
				</div>		
				<div class="modal-footer">		
					<div class="row">
						  <div align="center" id="actionButtonsEdit">
							<button type="button"  data-dismiss="modal" class="button button-3d button-rounded">Cerrar</button>
						  </div>
					</div>
			    </div>
			</form>
		</div>
	</div>
</div>
<!-- End Modal HTML -->

<!-- Modal MESAS ORDENES -->
<div id="mdOrdenMesa" class="modal fade">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title">Orden de Mesa</h4>
			</div>
			<form role="form" id='frmOrdenMesa'>
				<span id="spIdMesa"></span>
				<div class="modal-body">		
					<fieldset class="scheduler-border">
					  <legend class="scheduler-border">Datos de Orden</legend>
					  <div class="form-group col-xs-12 col-sm-6">
						<label for="txtNumeroMesa">Mesa N°:</label>
						<input type="number" readonly="true" class="form-control" id="txtNumeroMesa" name="txtNumeroMesa"/>
					  </div>
					  <div class="form-group col-xs-12 col-sm-6">
						<label for="txtSucursalMesa">Sucursal:</label>
						<input type="text" readonly="true" class="form-control" id="txtSucursalMesa" name="txtSucursalMesa"/>
					  </div>
					  <div class="form-group col-xs-12">
						<label for="txtProductosOrden">Detalles:</label>
						<div id="divProductOrden">
							<input type="text" class="form-control" id="txtProductosOrden" name="txtProductosOrden" placeholder="Ingrese Productos"/>
						</div>
						<br>
						<div style="overflow-x:hidden; overflow-y:auto;" id="divTbDetailsOrden">
							<table class="table-hovered table-bordered" cellspacing="0" width="100%" id="detailsOrden">
								<thead>
									<tr>
										<th class="text-center" width="40%">Producto</th>
										<th class="text-center">Precio</th>
										<th class="text-center">Cant.</th>
										<th class="text-center">Total</th>
									</tr>
								</thead>
								<tbody id="tbodyDetailsOrden">
									
								</tbody>
							</table>
						</div>
					  </div>
					  
					  <div class="form-group col-xs-12">
							<label for="txtSubtotalOrden">Subtotal:</label>
							<input type="number" readonly="true" class="form-control" id="txtSubtotalOrden" name="txtSubtotalOrden" value="0"/>
						  </div>
						  <div class="form-group col-xs-12">
							<label for="txtDescOrden">Descuento:</label><br/>
								<input type="number" readonly="true" class="form-control" id="txtCantDescOrden" name="txtCantDescOrden" value="0" style="width:65%; display:inline;"/>
								<input type="number" step="0.1" min="0" max="100" class="form-control" id="txtDescOrden" name="txtDescOrden" value="0" style="width:30%; display:inline;" onchange="$.calculateDesc('Orden')"/>
						  </div>
						  <div class="form-group col-xs-12">
							<label for="chkIvaOrden">I.V.A:</label>
							<input type="checkbox" class="form-control" id="chkIvaOrden" name="chkIvaOrden" checked="true" onchange="$.calculateIva('Orden')" style="width:15px; height:15px; display:inline;"/>
							<br/>
							<input type="number" readonly="true" class="form-control" id="txtIvaOrden" name="txtIvaOrden" value="0"/>
						  </div>
						  <div class="form-group col-xs-12">
							<label for="txtPropinaOrden">Propina:</label>
							<input type="number" step="0.01" class="form-control" id="txtPropinaOrden" name="txtPropinaOrden" onchange="$.subTotal('Orden')" value="0" min="0"/>
						  </div>
						  <div class="form-group col-xs-12">
							<label for="txtTotalOrden">Total:</label>
							<input type="number" readonly="true" class="form-control" id="txtTotalOrden" name="txtTotalOrden" value="0"/>
						  </div>
						</fieldset>
				</div>		
				<div class="modal-footer">		
					<div class="row">
						  <div align="center" id="actionButtonsOrden">
							<button type="reset" onclick="$.vaciarDetalles()" class="button button-3d button-rounded">Borrar</button>
							<button type="submit" class="button button-3d-primary button-rounded">Guardar</button>
						  </div>
					</div>
			    </div>
			</form>
		</div>
	</div>
</div>
<!-- End Modal HTML -->