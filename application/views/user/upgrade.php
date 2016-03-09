<div class="well optionBar" align="center" id="headerTittle" style="background:#4D4D4D url(<?php echo base_url()?>static/img/fg-upgrade.png) no-repeat 10% center; background-size: auto 105%; color:white;">
	<h3>UPGRADE</h3>
</div>
<div class="well panel panel-default" style="margin-top:1%;">
	<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
	  <!-- CREAR BEBIDA -->
	  <div class="panel panel-primary">
		<div class="panel-heading" role="tab" id="headingOne">
		  <h4 class="panel-title">
			<a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
			  MÓDULOS VIGENTES
			</a>
		  </h4>
		</div>
		<div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
		  <div class="panel-body">
			
			<div class="row">
				<div id="divFrmInvoice" class="col-xs-12 col-sm-8 col-sm-offset-2" style="border: 1px solid #ccc; padding:10px 35px 10px 35px;background-color:#FFF;">	
						<fieldset class="scheduler-border">
						  <legend class="scheduler-border">Datos de Módulos</legend>
						  <div class="form-group col-xs-12">
							<div style="overflow-x:hidden; overflow-y:auto;" id="divTbDetails">
								<table class="table-hovered table-striped table-bordered" cellspacing="0" width="100%" id="details">
									<thead>
										<tr>
											<th class="text-center">N°</th>
											<th class="text-center" width="40%">Modulo</th>
											<th class="text-center">Fecha/Exp.</th>
										</tr>
									</thead>
									<tbody id="tbodyDetails">
										<?php 
											$CI =& get_instance();
											$CI->load->library('metodos');
											if ( ! empty($modulos))
											{
												$contador=1;
												foreach ($modulos as $key => $value) 
												{
													echo '<tr>
														<td align="center">'.$contador++.'</td>
														<td><img src="'.base_url().$value['mod_img'].'" title="'.$value['mod_nom'].'" width="40" style="padding:3px;"> '.$value['mod_nom'].'</td>
														<td align="center">'.$CI->metodos->dateToLongString($value['per_fch_exp']).'</td>
														</tr>';
												}
											}
										?>
									</tbody>
								</table>
							</div>
						  </div>
						</fieldset>
				</div>
			</div>
			
		  </div>
		</div>
	  </div>
	  <!--END MODULOS VIGENTES-->
	  <!-- COMPRAR -->
	  <div class="panel panel-primary">
		<div class="panel-heading" role="tab" id="headingThree">
		  <h4 class="panel-title">
			<a class="collapsed" id="ltPacks" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
			  COMPRAR
			</a>
		  </h4>
		</div>
		<div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
		  <div class="panel-body">
			
			<div class="row">
				<div class="form-group col-xs-12">
					<fieldset class="scheduler-border">
						  <legend class="scheduler-border">Datos de Paquetes</legend>
						  <div class="col-md-10 col-md-offset-1">
								<table data-order='[[ 0, "asc" ],[ 0, "asc" ],[ 0, "asc" ],[ 0, "asc" ]]' class="table table-striped table-hovered table-bordered" cellspacing="0" width="100%" id="tbPacks">
									<thead>
										<tr>
											<th class="text-center"> Nombre </th>
											<th class="text-center"> Módulos </th>
											<th class="text-center"> Precio </th>
											<th class="text-center"> Duración</th>
											<th class="text-center">Seleccionar</th>
										</tr>
									</thead>
								</table>
						  </div>
					</fieldset>
				</div>
				<div class="form-group col-xs-12">
					<fieldset class="scheduler-border">
						<legend class="scheduler-border">Datos de Factura</legend>
						  <div class="form-group col-sm-6">
							<label for="txtFch">Fecha:</label>
							<input type="text" readonly="true" class="form-control" id="txtFch" name="txtFch"/>
						  </div>
						  <div class="form-group col-sm-7">
							<label for="txtSub">Subtotal:</label>
							<input type="text" readonly="true" class="form-control" id="txtSub" name="txtSub"/>
						  </div>
						  <div class="form-group col-sm-7">
							<label for="txtDesc">Descuento:</label>
							<input type="text" readonly="true" class="form-control" id="txtDesc" name="txtDesc"/>
						  </div>
						  <div class="form-group col-sm-7">
							<label for="txtIva">I.V.A(12%):</label>
							<input type="text" readonly="true" class="form-control" id="txtIva" name="txtIva"/>
						  </div>
						  <div class="form-group col-sm-7">
							<label for="txtTotal">Total:</label>
							<input type="text" readonly="true" class="form-control" id="txtTotal" name="txtTotal"/>
						  </div>
					</fieldset>
				</div>
				<form id="frmNewInvoice">
					<div class="row">
						  <div align="center" id="actionButtonsEdit">
							<button type="submit" class="button button-3d-primary button-rounded">Comprar</button>
						  </div>
					</div>
				</form>
			</div>
			<br>
			<div class="alert alert-info alert-dismissable">
				<button type="button" class="close" data-dismiss="alert">&times;</button>
				<table>
				<tr>
					<td>
						<img src="<?php echo base_url() ?>static/img/anuncio.png" class="img-responsive" style="width:250px;"/>
					</td>
					<td>
						<h4><strong>Aviso: </strong>Recuerda que puedes hacer el pago de tus facturas mediante deposito o transferencia bancaria.</h4>
						<h5><br>&nbsp;&nbsp;&nbsp;<strong>Banco Pichincha</strong><br>
						&nbsp;&nbsp;&nbsp;Cta. # 5813046000<br>
						&nbsp;&nbsp;&nbsp;Wilson Núñez Barrera<br>
						&nbsp;&nbsp;&nbsp;C.I. 070602894-1</h5>
					</td>
				</tr>
				</table>
			</div>
		  </div>
		</div>
	  </div>
	  <!-- END COMPRAR -->
	  <!-- MIS FACTURAS -->
	  <div class="panel panel-primary">
		<div class="panel-heading" role="tab" id="headingTwo">
		  <h4 class="panel-title">
			<a class="collapsed" id="ltInvoice" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
			  MIS FACTURAS
			</a>
		  </h4>
		</div>
		<div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
		  <div class="panel-body">
			
			<div class="row">
				<div class="form-group col-xs-12">
				<div class="alert alert-info alert-dismissable">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<table>
						<tr>
							<td>
								<img src="<?php echo base_url() ?>static/img/anuncio.png" class="img-responsive" style="width:250px;"/>
							</td>
							<td>
								<h4><strong>Aviso: </strong>Recuerda que puedes hacer el pago de tus facturas mediante deposito o transferencia bancaria.</h4>
								<h5><br>&nbsp;&nbsp;&nbsp;<strong>Banco Pichincha</strong><br>
								&nbsp;&nbsp;&nbsp;Cta. # 5813046000<br>
								&nbsp;&nbsp;&nbsp;Wilson Núñez Barrera<br>
								&nbsp;&nbsp;&nbsp;C.I. 070602894-1</h5>
							</td>
						</tr>
						</table>
						
					</div>
					<fieldset class="scheduler-border">
						  <legend class="scheduler-border">Datos de Facturas</legend>
						  <div class="col-md-10 col-md-offset-1">
								<table data-order='[[ 0, "asc" ],[ 0, "asc" ],[ 0, "asc" ],[ 0, "asc" ]]' class="table table-striped table-hovered table-bordered" cellspacing="0" width="100%" id="tbInvoices">
									<thead>
										<tr>
											<th class="text-center"> N°</th>
											<th class="text-center"> Fecha Emisión </th>
											<th class="text-center"> Fecha Expiración </th>
											<th class="text-center"> Estado</th>
											<th class="text-center"> Total</th>
											<th class="text-center"> Acción </th>
										</tr>
									</thead>
								</table>
						  </div>
						  
					</fieldset>
					
				</div>
			</div>

		  </div>
		</div>
	  </div>
	  <!-- END LISTAR FACTURAS -->
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
						  <legend class="scheduler-border">Datos de Factura</legend>
						  <div class="form-group col-xs-12 col-sm-6">
							<label for="txtNumeroMd">Número:</label>
							<input type="number" readonly="true" class="form-control" id="txtNumeroMd" name="txtNumeroMd" placeholder="Ingrese número de factura"/>
						  </div>
						  <div class="form-group col-xs-12 col-sm-6">
							<label for="txtFechaEmisionMd">Fecha Emisión:</label>
							<input type="date" readonly="true" class="form-control" id="txtFechaEmisionMd" name="txtFechaEmisionMd"/>
						  </div>
						  <div class="form-group col-xs-12 col-sm-6">
							<label for="txtFechaExpMd">Fecha Expiración:</label>
							<input type="date" readonly="true" class="form-control" id="txtFechaExpMd" name="txtFechaExpMd"/>
						  </div>
						  <div class="form-group col-xs-12">
							<label for="txtProductosMd">Datos de Paquetes:</label>
							<br>
							<div style="overflow-x:hidden; overflow-y:auto;" id="divTbDetailsMd">
								<table class="table-hovered table-striped table-bordered" cellspacing="0" width="100%" id="detailsMd">
									
									<thead>
										<tr>
											<th class="text-center" width="40%">Nombre</th>
											<th class="text-center">Módulos</th>
											<th class="text-center">Duración</th>
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

<!--MODAL CASH-->
<div id="mdCash" class="modal fade">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title">Pagar Factura</h4>
			</div>
			<form role="form" id='frmMdCash'>
				<span id="spId"></span>
				<div class="modal-body">		
						<fieldset class="scheduler-border">
						  <legend class="scheduler-border">Datos de Transacción</legend>
						  <div class="form-group col-xs-12">
							<label for="txtNumeroCash">Número de Documento:</label>
							<input type="text" required="true" class="form-control" id="txtNumeroCash" name="txtNumeroCash" placeholder="Ingrese número de documento de pago"/>
						  </div>
						  <div class="form-group col-xs-12" align="left">
							<label for="images">Foto de Comprobante:</label>
							<div class="col-xs-12" id="img_cash" align="center">
								<img class="img-thumbnail img-responsive" style="border: 1px solid #ccc; padding:10px;background-color:#FFF;"/>
							</div>
							<input name="images[]" id="images" type="file" required = "required"/>
						</div>
						</fieldset>
				</div>		
				<div class="modal-footer">		
					<div class="row">
						  <div align="center" id="actionButtonsCash">
							<button type="submit" class="button button-3d-primary button-rounded">Enviar</button>
						  </div>
					</div>
			    </div>
			</form>
		</div>
	</div>
</div>
<!--END MODAL CASH-->
<!-- End Modal HTML -->