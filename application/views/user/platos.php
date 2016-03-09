<div class="well optionBar" align="center" id="headerTittle" style="background:#f5f5f5 url(<?php echo base_url()?>static/img/fg-plato.png) no-repeat 10% bottom; background-size: auto 120%;">
	<h3>PLATOS</h3>
</div>
<div class="well panel panel-default" style="margin-top:1%;">
	<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
	  <!-- CREAR PLATO -->
	  <div class="panel panel-primary">
		<div class="panel-heading" role="tab" id="headingOne">
		  <h4 class="panel-title">
			<a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
			  CREAR PLATO
			</a>
		  </h4>
		</div>
		<div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
		  <div class="panel-body">
			
			<div class="row">
				<div id="divFrmClient" class="col-xs-12 col-sm-8 col-sm-offset-2" style="border: 1px solid #ccc; padding:10px 35px 40px 35px;background-color:#FFF;">	
					<form id="frmNewFood">
						<fieldset class="scheduler-border">
						  <legend class="scheduler-border">Datos Generales</legend>
						  <div class="form-group" align="left">
							<label for="images">Foto/Logo:</label>
							<div class="col-xs-12"></div>
							<input name="images[]" id="images" type="file" required = "required"/>
						  </div>
						  <div class="form-group">
							<label for="txtName">Nombre:</label>
							<input type="text" required="true" class="form-control" id="txtNombre" name="txtNombre" placeholder="Ingrese nombre del plato"/>
						  </div>
						 <div class="form-group" align="left">
							<label for="txtInfo">Descripción:</label>
							<TextArea required="true" class="form-control" id="txtInfo" name="txtInfo" placeholder="Describe brevemente este plato..." ></TextArea>
						</div>
						  <div class="form-group">
							<label for="txtPrec">Precio:</label>
							<input type="number" min="0" step="0.01"  class="form-control" id="txtPrec" name="txtPrec" placeholder="Ingrese el precio"/>
						  </div>
						<div class="form-group">
							<label for="slcCatg">Categoría:</label>
							<Select required="true" class="form-control" id="slcCatg" name="slcCatg" >
							<?php
								if ( ! empty($categorias))
								{
									foreach ($categorias as $key => $value) 
									{
										
										echo '<option value="'.$value['ctp_id'].'">'.$value['ctp_nom'].'</option>';
									}
								}
							?>
							</select>
						</div>
						<div class="form-group">
							<label for="slcTipo">Tipo:</label>
							<Select required="true" class="form-control" id="slcTipo" name="slcTipo">
							<?php
								if ( ! empty($tipos))
								{
									foreach ($tipos as $key => $value) 
									{
										
										echo '<option value="'.$value['tpr_id'].'" >'.$value['tpr_des'].'</option>';
									}
								}
							?>
							</select>
						</div>
						</fieldset>
					  <div class="row">
						  <div align="center" id="actionButtons">
							<button type="submit" class="button button-3d-primary button-rounded">Guardar</button>
						  </div>
					  </div>
					</form>
				</div>
			</div>
			
		  </div>
		</div>
	  </div>
	  <!--END CREAR PLATO-->
	  <!-- LISTAR MODELOS -->
	  <div class="panel panel-primary">
		<div class="panel-heading" role="tab" id="headingThree">
		  <h4 class="panel-title">
			<a class="collapsed" id="ltFood" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
			  LISTAR PLATOS
			</a>
		  </h4>
		</div>
		<div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
		  <div class="panel-body">
			
			<div class="row">
				<div class="col-md-10 col-md-offset-1">
					<table data-order='[[ 0, "asc" ],[ 0, "asc" ],[ 0, "asc" ],[ 0, "asc" ]]' class="table table-hovered table-bordered" cellspacing="0" width="100%" id="tbPlatos">
						<thead>
							<tr>
								<th class="text-center"> Nombre </th>
								<th class="text-center"> Precio </th>
								<th class="text-center"> Categoría </th>
								<th class="text-center"> Tipo</th>
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
<div id="mdFood" class="modal fade">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title">Editar Plato</h4>
			</div>
			<form role="form" id='frmMdFood'>
				<span id="spId"></span>
				<div class="modal-body">		
					<fieldset class="scheduler-border">
					  <legend class="scheduler-border">Datos Generales</legend>
					  <div class="form-group" align="left">
						<label for="imagesMd">Foto/Logo:</label>
						<div class="col-xs-12" id="divImgsMd"></div>
						<input name="imagesMd[]" id="imagesMd" type="file"/>
					  </div>
					  <div class="form-group">
						<label for="txtNombreEdit">Nombre:</label>
						<input type="text" required="true" class="form-control" id="txtNombreEdit" name="txtNombreEdit" placeholder="Ingrese nombre del plato"/>
					  </div>
					 <div class="form-group" align="left">
						<label for="txtInfoEdit">Descripción:</label>
						<TextArea required="true" class="form-control" id="txtInfoEdit" name="txtInfoEdit" placeholder="Describe brevemente este plato..." ></TextArea>
					</div>
					  <div class="form-group">
						<label for="txtPrecEdit">Precio:</label>
						<input type="number" min="0" step="0.01"  class="form-control" id="txtPrecEdit" name="txtPrecEdit" placeholder="Ingrese el precio"/>
					  </div>
					<div class="form-group">
						<label for="slcCatgEdit">Categoría:</label>
						<Select required="true" class="form-control" id="slcCatgEdit" name="slcCatgEdit" >
						<?php
							if ( ! empty($categorias))
							{
								foreach ($categorias as $key => $value) 
								{
									
									echo '<option value="'.$value['ctp_id'].'" >'.$value['ctp_nom'].'</option>';
								}
							}
						?>
						</select>
					</div>
					<div class="form-group">
						<label for="slcTipoEdit">Tipo:</label>
						<Select required="true" class="form-control" id="slcTipoEdit" name="slcTipoEdit">
						<?php
							if ( ! empty($tipos))
							{
								foreach ($tipos as $key => $value) 
								{
									
									echo '<option value="'.$value['tpr_id'].'" >'.$value['tpr_des'].'</option>';
								}
							}
						?>
						</select>
					</div>
					</fieldset>
				</div>		
				<div class="modal-footer">		
					<div class="row">
						  <div align="center" id="actionButtonsEdit">
							<button type="submit" class="button button-3d-primary button-rounded">Guardar</button>
						  </div>
					</div>
			    </div>
			</form>
		</div>
	</div>
</div>


<!-- End Modal HTML -->