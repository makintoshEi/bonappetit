/* global $ */
/* global PNotify */
$(function(){
	var map, mapEdit, lat, lng, latEdit, lngEdit;
	var markersArray = [];
	var markersArrayEdit = [];
	$.fn_mal = function()
	{}
	
	$.fn_ok = function(rta)
	{
		lat = rta.coords.latitude;
		lng = rta.coords.longitude;
		center = new google.maps.LatLng(lat, lng);
	var mapProp = {
		center:center,
		zoom:16,
		mapTypeId:google.maps.MapTypeId.ROADMAP
	  };
	  var mapPropEdit = {
		center:center,
		zoom:16,
		mapTypeId:google.maps.MapTypeId.ROADMAP
	  };
	  map=new google.maps.Map(document.getElementById("googleMap"), mapProp);
	  $.placeMarker(center, map,markersArray);
	  google.maps.event.addListener(map, 'click', function(event) {
		   $.placeMarker(event.latLng, map, markersArray);
		});
		
	  mapEdit=new google.maps.Map(document.getElementById("googleMapEdit"), mapPropEdit);
	  $.placeMarker(center, mapEdit,markersArrayEdit);
	  google.maps.event.addListener(mapEdit, 'click', function(event) {
		   $.placeMarker(event.latLng, mapEdit, markersArrayEdit);
		});
	}

	navigator.geolocation.getCurrentPosition($.fn_ok, $.fn_mal);
	
	//falta cargar el mapa de edicion y cargar la geolocalizacion 
	
	$.placeMarker = function(location, mapa, arrayMarker) {
            // first remove all markers if there are any
            $.deleteOverlays(arrayMarker);

            var marker = new google.maps.Marker({
                position: location, 
                map: mapa
            });
			
			console.log(marker.getPosition().lat());
			console.log(marker.getPosition().lng());

            // add marker in markers array
            arrayMarker.push(marker);

            //map.setCenter(location);
        }

        // Deletes all markers in the array by removing references to them
    $.deleteOverlays = function(markersArray) {
            if (markersArray) {
                for (i in markersArray) {
                    markersArray[i].setMap(null);
                }
            markersArray.length = 0;
            }
        }
		
	$("#slcProv" ).change(function() {
		event.preventDefault();
		$.ajax({
			type: "POST",
			url: "/main/ciudades/",
			dataType: 'json',
			data: $(this).serialize(),
			success: function(response) {
				if(response)
				{
					var ciudades = response.data;
					contenido="";
					ciudades.forEach(function(ciudad) {
						contenido+="<option value='"+ciudad.ciu_id+"'>"+ciudad.ciu_nom+"</option>";
					});
					$('#slcCiu').html(contenido);
				}	
			},
			error: function(){
				$.errorMessage();
			}
		});
	});
	
	$("#slcProvMd" ).change(function() {
		event.preventDefault();
		$.ajax({
			type: "POST",
			url: "/main/ciudades/",
			dataType: 'json',
			data: $(this).serialize(),
			success: function(response) {
				if(response)
				{
					var ciudades = response.data;
					contenido="";
					ciudades.forEach(function(ciudad) {
						contenido+="<option value='"+ciudad.ciu_id+"'>"+ciudad.ciu_nom+"</option>";
					});
					$('#slcCiuMd').html(contenido);
				}	
			},
			error: function(){
				$.errorMessage();
			}
		});
	});
	
	var rrhh=[];
	var rrhhMd=[];
	$(document).ready(function () {
	$("#slcProv" ).change();
	$("#txtPersonal").autocomplete({
	source: "/sucursal/search_autocomplete_products/",
	minLength: 3,//search after two characters
	select: function(event,ui){
			var datos = (ui.item.id+"").split(";e-i;");
			var paso=true
			for(i=0;i<rrhh.length;i++)
			{
				if(rrhh[i]==datos[0])
					paso=false;
			}
			if (paso)
			{
				$('#details tbody').append('<tr id="'+datos[0]+'"><td style="padding:3px;" align="center">'+datos[2]+'</td><td style="padding:3px;" align="center">'+datos[1]+'</td><td style="padding:3px;" align="center">'+datos[3]+'</td><td align="center"> <button onclick="$.removeItem('+datos[0]+')"  type="button" class="btn btn-default">  <span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button> </td></tr>');
				rrhh.push(datos[0]);
			}
			$('#txtPersonal').val('');
			$('#txtPersonal').focus();
		}
	});
	$("#txtPersonalMd").autocomplete({
	source: "/sucursal/search_autocomplete_products/",
	minLength: 3,//search after two characters
	select: function(event,ui){
			var datos = (ui.item.id+"").split(";e-i;");
			var paso=true
			for(i=0;i<rrhhMd.length;i++)
			{
				if(rrhhMd[i]==datos[0])
					paso=false;
			}
			if (paso)
			{
				$('#detailsMd tbody').append('<tr id="'+datos[0]+'"><td style="padding:3px;" align="center">'+datos[2]+'</td><td style="padding:3px;" align="center">'+datos[1]+'</td><td style="padding:3px;" align="center">'+datos[3]+'</td><td align="center"> <button onclick="$.removeItem('+datos[0]+')"  type="button" class="btn btn-default">  <span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button> </td></tr>');
				rrhhMd.push(datos[0]);
			}
			$('#txtPersonalMd').val('');
			$('#txtPersonalMd').focus();
		}
	});
	$( "#txtPersonalMd" ).autocomplete( "option", "appendTo", ".eventInsForm" );
	
	});
	$.removeItem = function(id){
		$("#"+id).remove();
		var aux=[]
		for(i=0;i<rrhh.length;i++)
		{
			if(rrhh[i]!=id)
				aux.push(rrhh[i]);
		}
		rrhh=aux;
	}
	$.removeItemMd = function(id){
		$("#"+id).remove();
		id=id.replace("Md","");
		var aux=[]
		for(i=0;i<rrhhMd.length;i++)
		{
			if(rrhhMd[i]!=id)
				aux.push(rrhhMd[i]);
		}
		rrhhMd=aux;
	}
	$.vaciarDetalles = function(){
		$('#tbodyDetails').html("");
		rrhh=[];
	}
	$.vaciarDetallesMd = function(){
		$('#tbodyDetailsMd').html("");
		rrhhMd=[];
	}
	/*
	 * -------------------------------------------------------------------
	 *  Create invoice submit(Ajax)
	 * -------------------------------------------------------------------
	 */
	var create = false;
	$("#frmNewStore").on("submit",function(event){
		event.preventDefault();
		$("#actionButtons").html('<h4 class="text-primary">Procesando...</h4>')
		marker=markersArray[markersArray.length-1];
		$.ajax({
			type: "POST",
			url: "/sucursal/save_sucursal/?rrhh="+rrhh+"&lat="+marker.getPosition().lat()+"&lng="+marker.getPosition().lng(),
			dataType: 'json',
			data: $(this).serialize(),
			success: function(response) {
				if(response.insert_sucursal=="-1")
				{
					$.errorMessage('El número de sucursal ingresado ya se encuentra registrado.');
				}
				else
				{
					if(response.insert_sucursal!="0"){
						$.successMessage('Registro Exitoso');
						$("#frmNewStore input[type='text']").val('');
						$("#frmNewStore input[type='number']").val('1');
						$.vaciarDetalles();
						create = true;
					}else{		
						$.errorMessage('Error en el Registro');
					}
				}
				$("#actionButtons").html('<button type="submit" class="button button-3d-primary button-rounded">Guardar</button>')
			},
		error: function(){
			$.errorMessage();
			$("#actionButtons").html('<button type="submit" class="button button-3d-primary button-rounded">Guardar</button>')
		}
		});
	});
	/*
	 * -------------------------------------------------------------------
	 *  EDIT STORE submit(Ajax)
	 * -------------------------------------------------------------------
	 */
	$("#frmMdStore").on("submit",function(event){
		event.preventDefault();
		console.log('entra edit');
		$("#actionButtonsMd").html('<h4 class="text-primary">Procesando...</h4>')
		marker=markersArrayEdit[markersArrayEdit.length-1];
		$.ajax({
			type: "POST",
			url: "/sucursal/edit_sucursal/?rrhh="+rrhhMd+"&id="+trIdClt+"&lat="+marker.getPosition().lat()+"&lng="+marker.getPosition().lng(),
			dataType: 'json',
			data: $(this).serialize(),
			success: function(response) {
				if(response.update_sucursal=="-1")
				{
					$.errorMessage('Registro no encontrado en la base de datos.');
				}
				else
				{
					if(response.update_sucursal!="0"){
						$('#tbStore').DataTable().ajax.reload();
						$("#mdStore").modal('hide');
						$.successMessage('Registro Exitoso');
						$("#frmMdStore input[type='text']").val('');
						$("#frmMdStore input[type='number']").val('1');
						$.vaciarDetallesMd();
					}else{		
						$.errorMessage('Error en el Registro');
					}
				}
				$("#actionButtonsMd").html('<button type="button" class="button button-3d button-rounded" data-dismiss="modal">Cancelar</button> <button type="submit" class="button button-3d-primary button-rounded">Guardar</button>')
			},
		error: function(){
			$.errorMessage();
			$("#actionButtonsMd").html('<button type="button" class="button button-3d button-rounded" data-dismiss="modal">Cancelar</button> <button type="submit" class="button button-3d-primary button-rounded">Guardar</button>')
		}
		});
	});
	/*
	 * -------------------------------------------------------------------
	 *  function editDeleteModel(btn) -> load modal form edit or delete
	 *	@param : btn => parameter this btn(editModel) onclick
	 *	@param : edt => edit o delete param(true=>edit, false=>delete)
	 * -------------------------------------------------------------------
	 */
	 
	 
	 $.deleteStore = function(){
	 	$.ajax({
			type: "POST",
			url: "/sucursal/delete_sucursal/",
			dataType: 'json',
			data: {id:trIdClt},
			success: function(response) {
				if(response){
					$.successMessage();
					$('#tbStore').DataTable().row( $("#"+trIdClt) ).remove().draw();
				}else{		
					$.errorMessage();
				}
			},
			error: function(){
				$.errorMessage();
			}
		});
	 };
	 $('#mdStore').on('shown', function () {
		google.maps.event.trigger(mapEdit, "resize");
	});
	 var trIdClt;
	 $.editDeleteModel = function(btn, edt){
	 	trIdClt = $.trim($($($(btn).parent()).parent()).attr('id'));
	 	if(edt){
			$("#spIdStoreMd").attr('data-toggle',trIdClt);
			$.post("/sucursal/search_sucursal_by_id", {id:trIdClt}, function( response ){
				$("#mdStore").modal('show');
				if( response!=null ){
					var general=response.general;
					var horario=response.horario;
					var empleados=response.empleados;
					//cargando geolocalizacion
					var centerEdit = new google.maps.LatLng(general.suc_lat, general.suc_lon);
					  /*mapEdit=new google.maps.Map(document.getElementById("googleMapEdit"), mapProp);
					  $.placeMarker(centerEdit, mapEdit, markersArrayEdit);
					  google.maps.event.addListener(mapEdit, 'click', function(event) {
						   $.placeMarker(event.latLng, mapEdit, markersArrayEdit);
						});*/
						$.placeMarker(centerEdit, mapEdit, markersArrayEdit);
					setTimeout(function(){
					  google.maps.event.trigger(mapEdit, "resize");
						mapEdit.setCenter(centerEdit);
					}, 1000);
					$($('#txtNumeroMd').val(general.suc_num)).attr('disabled',true);
					$('#txtNombreMd').val(general.suc_nom);
					$('#txtTlfMd').val(general.suc_tlf);
					$('#txtDirMd').val(general.suc_dir);
					$('#slcProvMd').val(general.prv_id);
					$("#slcProvMd" ).change();
					setTimeout(function(){
					  $('#slcCiuMd').val(general.ciu_id);
					}, 2000);
					$('#txtLunesMd').val(horario.hor_lun);
					$('#txtMartesMd').val(horario.hor_mar);
					$('#txtMiercolesMd').val(horario.hor_mie);
					$('#txtJuevesMd').val(horario.hor_jue);
					$('#txtViernesMd').val(horario.hor_vie);
					$('#txtSabadoMd').val(horario.hor_sab);
					$('#txtDomingoMd').val(horario.hor_dom);
					rrhhMd=[];
					$('#detailsMd tbody').html('');
					$.each( empleados, function( key, value ) {
						//console.log(value);
						$('#detailsMd tbody').append('<tr id="'+value.emp_id+'Md"><td style="padding:3px;" align="center">'+value.emp_ced+'</td><td style="padding:3px;" align="center">'+value.nombre+'</td><td style="padding:3px;" align="center">'+value.emp_eml+'</td><td align="center"> <button onclick="$.removeItemMd(\''+value.emp_id+'Md\')"  type="button" class="btn btn-default">  <span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button> </td></tr>');
						rrhhMd.push(value.emp_id);
					});
					
				}else{
					$.errorMessage();
				}
			},'json');
	 	}
	 	else
	 	{
	 		$.confirmMessage($.deleteStore);
	 	} 	
	 };
	
	/*
	 * -------------------------------------------------------------------
	 *  Generate Table models list
	 *	function renderizeRow renderize tr, td for table
	 *	@param : btnsOpTblModels => variable(string): buttons for dateTable
	 * -------------------------------------------------------------------
	 */
	
	var btnsOpTblModels = "<button style='border: 0; background: transparent' onclick='$.editDeleteModel(this, true);'>"+
							"<img src='/static/img/edit.png' height='24' title='Editar'>"+
						  "</button>"+
						  "<button style='border: 0; background: transparent' onclick='$.editDeleteModel(this, false);'>"+
							"<img src='/static/img/delete.png' title='Eliminar'>"+
						  "</button>";
	
					  
	$.renderizeRow = function( nRow, aData, iDataIndex ) {
	   $(nRow).append("<td class='text-center'>"+btnsOpTblModels+"</td>");
	   $(nRow).attr('id',aData['suc_num']);
	};
	
	$.fnTbl('#tbStore',"/sucursal/get_sucursal_all/",[{ "data": "suc_num"},{"data":"suc_nom"},{"data":"place"},{"data":"suc_dir"}],$.renderizeRow);
	
	$("#ltStore").click(function(event){
		if(create){
			$('#tbStore').DataTable().ajax.reload();
			create = false;
		}
	});
    
});