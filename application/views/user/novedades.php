<div class="well optionBar" align="center" style="background:#f5f5f5 url(<?php echo base_url()?>static/img/fg-novedades.png) no-repeat 10% center; background-size: auto 120%;">
	<h3>NOVEDADES</h3>
</div>
<div class="well panel panel-default" style="margin-top:1%;">
	<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
	  <!-- PROMOCIONES -->
	  <div class="panel panel-primary">
		<div class="panel-heading" role="tab" id="headingOne">
		  <h4 class="panel-title">
			<a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" class="collapsed" aria-controls="collapseOne">
			  PROMOCIONES
			</a>
		  </h4>
		</div>
		<div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
		  <div class="panel-body">
			
			<div class="row">
				<div class="col-sm-10 col-sm-offset-1" style="border: 1px solid #ccc; padding:10px 35px 10px 35px;background-color:#FFF;">	
					<?php 
					//require_once($_SERVER["DOCUMENT_ROOT"].'/application/libraries/metodos.php');
					$CI =& get_instance();
					$CI->load->library('metodos');
					if ( ! empty($promos))
					{
						foreach ($promos as $key => $value) 
						{
							echo '<div class="well news col-xs-12">
									<div align="center" class="col-xs-4 col-sm-2"><img src="'.base_url().'static/img/descuento.png" width="80"></div>
									<div class="col-xs-8 col-sm-10"><div align="center"><p style="font-size:14pt;"><strong>¡SUPER PROMO!</strong></p><p>Obten el <strong>'.$value["pro_val"].'%</strong> de descuento en todas tus compras del '.$CI->metodos->dateToLongString($value["pro_fch_ini"]).' hasta '.$CI->metodos->dateToLongString($value["pro_fch_fin"]).'.</p></div></div>
								</div>';
						}
					}
					?>
				</div>
			</div>
		  </div>
		</div>
	  </div>
	  <!-- ACTUALIZACIONES -->
	  <div class="panel panel-primary">
		<div class="panel-heading" role="tab" id="headingTwo">
		  <h4 class="panel-title">
			<a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
			  ACTUALIZACIONES
			</a>
		  </h4>
		</div>
		<div id="collapseTwo" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingTwo">
		  <div class="panel-body">
			
			<div class="row">
				<div class="col-sm-10 col-sm-offset-1" style="border: 1px solid #ccc; padding:10px 35px 40px 35px;background-color:#FFF;">	
					<div class="well news col-xs-12">
						<div align="center" class="col-xs-4 col-sm-2"><img src="<?php echo base_url()?>static/img/novedades.png" width="80"></div>
						<div class="col-xs-8 col-sm-10"><div align="center"><p style="font-size:14pt;"><strong>¡Anuncialo!</strong></p><p>Que tus clientes se enteren que ya formas parte de <b>Bon Appétit</b>.<br/>¿Cómo? Sencillo, descarga e imprime el afiche de promoción y permite que los amantes del buen sabor estén al tanto de tus productos.</p><br/><a href="<?php echo base_url()?>static/img/promo-template.png" target="_blank"><img src="<?php echo base_url()?>static/img/descarga.png" height="35"/></a></div></div>
					</div>
					<?php 
					if ( ! empty($news))
					{
						foreach ($news as $key => $value) 
						{
							echo '<div class="well news col-xs-12">
									<div align="center" class="col-xs-4 col-sm-2"><img src="'.base_url().$value['nov_url'].'" width="80"></div>
									<div class="col-xs-8 col-sm-10"><div align="center"><p style="font-size:14pt;"><strong>'.$value['nov_nom'].'</strong></p><p>'.$value['nov_dsc'].'</p></div></div>
								</div>';
						}
					}
					?>
				</div>
			</div>
		  </div>
		</div>
	  </div>
	</div>
</div>
