<div>
<div class="well optionBar" align="center" id="headerTittle" style="background:#f5f5f5 url(<?php echo base_url()?>static/img/fg-perfil.png) no-repeat 10% center; background-size: auto 120%;">
	<h3>PERFIL</h3>
</div>
<div class="well panel panel-default" style="margin-top:1%;">
	<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
	  <!-- DATOS DE RESTAURANT -->
	  <div class="panel panel-primary">
		<div class="panel-heading" role="tab" id="headingTwo">
		  <h4 class="panel-title">
			<a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
				LOGO
			</a>
		  </h4>
		</div>
		<div id="collapseTwo" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingTwo">
		  <div class="panel-body">
			<div class="row">
				<div class="col-xs-12 col-sm-8 col-sm-offset-2" style="border: 1px solid #ccc; padding:10px 35px 40px 35px;background-color:#FFF;">	
					<form id="frmLogo">
						<div class="form-group" align="left">
							<label for="images">Foto/Logo:</label>
							<div class="col-xs-12">
								<img src="<?php echo base_url().$rst_url;?>" class="img-thumbnail img-responsive" style="border: 1px solid #ccc; padding:10px;background-color:#FFF;"/>
							</div>
							<input name="images[]" id="images" type="file" required = "required"/>
						</div>
						<div class="row">
							<div align="center">
								<button type="submit" class="button button-3d-primary button-rounded">CAMBIAR</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		  </div>
		</div>
	</div>
	<div class="panel panel-primary">
		<div class="panel-heading" role="tab" id="headingOne">
		  <h4 class="panel-title">
			<a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
				DATOS DE PERFIL
			</a>
		  </h4>
		</div>
		<div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
		  <div class="panel-body">
			
			<div class="row">
				<div class="col-xs-12 col-sm-8 col-sm-offset-2" style="border: 1px solid #ccc; padding:10px 35px 40px 35px;background-color:#FFF;">	
					<form id="frmDataProfile">
						<fieldset class="scheduler-border">
							<legend class="scheduler-border">Datos Generales</legend>
							<div class="form-group" align="left">
								<label for="txtNombre">Nombre/Razon Social:</label>
								<input required="true" class="form-control" id="txtNombre" name="txtNombre" placeholder="Ingrese Nombre/Razon Social" value="<?php echo $rst_nom;?>" />
							</div>
							<div class="form-group" align="left">
								<label for="txtInfo">Información:</label>
								<TextArea required="true" class="form-control" id="txtInfo" name="txtInfo" placeholder="Cuentanos un poco sobre tus servicios..." ><?php echo $rst_inf;?></TextArea>
							</div>
							<div class="form-group" align="left">
								<label for="txtDmc" style="display:inline-block;">Pedidos a domicilio:</label>
								<input type="checkbox" class="form-control" id="txtDmc" name="txtDmc" style="display:inline-block; width:15px; height:auto; margin:0px; padding:0px;" <?php echo $rst_ent_dom=='t'?"checked":"";?>>
							</div>
						</fieldset>
						<fieldset class="scheduler-border">
							<legend class="scheduler-border">Datos Tributarios</legend>
							<div class="form-group" align="left">
								<label for="txtRUC">R.U.C.:</label>
								<input required="true" class="form-control" id="txtRUC" name="txtRUC" maxlength="13" placeholder="Ingrese R.U.C." value="<?php echo $tributario?$tributario['trb_ruc']:"";?>"/>
							</div>
							<div class="form-group" align="left">
								<label for="txtNombreCom">Nombre Comercial:</label>
								<input required="true" class="form-control" id="txtNombreCom" name="txtNombreCom" maxlength="300" placeholder="Ingrese Nombre Comercial" value="<?php echo $tributario?$tributario['trb_nom_com']:"";?>"/>
							</div>
							<div class="form-group" align="left">
								<label for="txtContEsp">N° de Contribuyente Especial:</label>
								<input required="true" minlength="3" maxlength="5" class="form-control" id="txtContEsp" name="txtContEsp" maxlength="13" placeholder="Ingrese N° de Contribuyente Especial" value="<?php echo $tributario?$tributario['trb_con_esp']:"";?>"/>
							</div>
							<div class="form-group" align="left">
								<label for="txtObg" style="display:inline-block;">Obligado a llevar contabilidad:</label>
								<input type="checkbox" class="form-control" id="txtObg" name="txtObg" style="display:inline-block; width:15px; height:auto; margin:0px; padding:0px;" <?php echo $tributario['trb_obl_con']=='t'?"checked":""?>>
							</div>
							<div class="form-group" align="left">
								<label for="firma">Firma Electrónica:</label>
								<input name="firma[]" id="firma" type="file"/>
							</div>
							<div class="form-group" align="left">
								<label for="txtPssFirma">Contraseña de Firma Electrónica:</label>
								<input type="password" class="form-control" id="txtPssFirma" name="txtPssFirma" placeholder="Ingrese Contraseña de Firma Electrónica" value="<?php echo $tributario?$tributario['trb_pss_frm']:"";?>"/>
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
	</div>
</div>
</div>