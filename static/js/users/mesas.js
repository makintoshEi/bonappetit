$(function(){
var create = false;
$("#frmNewTable").on('submit', function(event){
		event.preventDefault();
		$("#actionButtons").html('<h4 class="text-primary">Procesando...</h4>')
		$.ajax({
			type: "POST",
			url: "/mesas/save_table/",
			dataType: 'json',
			data: $(this).serialize(),
			success: function(response) {
				if(response=="-1")
				{
					$.errorMessage('El número de mesa ingresado ya se encuentra registrado en esta sucursal.');
				}
				else
				{
					if(response!="0"){
						$.successMessage('Registro Exitoso');
						$("#frmNewTable input[type='text']").val('');
						$("#frmNewTable input[type='number']").val('1');
						create = true;
					}else{		
						$.errorMessage('Error en el Registro');
					}
				}
				$("#actionButtons").html('<button type="reset" class="button button-3d button-rounded">Borrar</button> <button type="submit" class="button button-3d-primary button-rounded">Guardar</button>')
			},
		error: function(){
			$.errorMessage();
			$("#actionButtons").html('<button type="reset" class="button button-3d button-rounded">Borrar</button> <button type="submit" class="button button-3d-primary button-rounded">Guardar</button>')
		}
		});
	});
/*
 * -------------------------------------------------------------------
 *  Edit model submit(Ajax) --- modal form
 * -------------------------------------------------------------------
 */
 $("#frmMdTable").on("submit",function(event){
	event.preventDefault();
		$("#actionButtonsEdit").html('<h4 class="text-primary">Procesando...</h4>')
		$.ajax({
			type: "POST",
			url: "/mesas/edit_table/?trId="+$("#spId").attr('data-toggle'),
			dataType: 'json',
			data: $(this).serialize(),
			success: function(response) {
				if(response=="-1")
				{
					$.errorMessage();
				}
				else
				{
					if(response!="0"){
						$.successMessage('Registro Exitoso');
						$("#frmMdTable input[type='text']").val('');
						$("#frmMdTable input[type='number']").val('1');
						$("#mdTable").modal('hide');
						$('#tbMesas').DataTable().ajax.reload();
					}else{		
						$.errorMessage('Error en el Registro');
					}
				}
				$("#actionButtonsEdit").html('<button type="reset" class="button button-3d button-rounded">Borrar</button> <button type="submit" class="button button-3d-primary button-rounded">Guardar</button>')
			},
		error: function(){
			$.errorMessage();
			$("#actionButtonsEdit").html('<button type="reset" class="button button-3d button-rounded">Borrar</button> <button type="submit" class="button button-3d-primary button-rounded">Guardar</button>')
		}
		});
});
/*
	*CARGA DE DATOS DESDE LA BD A MODAL EDITION
*/
 $.chargeTable = function(){
		event.preventDefault();
		$("#actionButtonsEdit").html('<h4 class="text-primary">Procesando...</h4>');
		$.ajax({
			url: "/mesas/find_table/",  
			type: 'POST',
			data: {id:trIdMd},
			dataType:'json',
			//si finalizo correctamente
			success: function(response){
				table=response.data[0]
				$('#txtSucursalMd').val(table.suc_num + ' : ' + table.suc_nom);	
				$('#txtNumeroMd').val(table.mss_num);	
				$('#txtCapacidadMd').val(table.mss_cap);	
				$("#actionButtonsEdit").html('<button type="button"  data-dismiss="modal" class="button button-3d button-rounded">Cerrar</button> <button type="submit" class="button button-3d-primary button-rounded">Guardar</button>')
			},
			//si ocurrido un error
			error: function(){
				$.errorMessage("Error al establecer conexión con el servidor");
				$("#actionButtonsEdit").html('<button type="button"  data-dismiss="modal" class="button button-3d button-rounded">Cerrar</button> <button type="submit" class="button button-3d-primary button-rounded">Guardar</button>')
			}
		});
	}
/*
 * -------------------------------------------------------------------
 *  function editDeleteModel(btn) -> load modal form edit or delete
 *	@param : btn => parameter this btn(editModel) onclick
 *	@param : edt => edit o delete param(true=>edit, false=>delete)
 * -------------------------------------------------------------------
 */
 $.deleteFood = function(){
	$.ajax({
		type: "POST",
		url: "/mesas/delete_table/",
		dataType: 'json',
		data: {id:trIdMd},
		success: function(response) {
			if(response=="1"){
				$.successMessage();
				$('#tbMesas').DataTable().row( $("#"+trIdMd) ).remove().draw();
			}else{		
				$.errorMessage();
			}
		},
		error: function(){
			$.errorMessage();
		}
	});
 };
 
 var trIdMd;
 $.editDeleteFood = function(btn, edt){
	trIdMd = $($($(btn).parent()).parent()).attr('id');
	if(edt){
		$.chargeTable();
		$("#mdTable").modal('show');
		$("#spId").attr('data-toggle', trIdMd);
	}
	else
	{
		$.confirmMessage($.deleteFood);
	} 	
 };
//--------TABLA DE PLATOS
var btnsOpTblFoods = "<button style='border: 0; background: transparent' onclick='$.editDeleteFood(this, true);'>"+
							"<img src='/static/img/edit.png' title='Editar'>"+
						  "</button>"+
						  "<button style='border: 0; background: transparent' onclick='$.editDeleteFood(this, false);'>"+
							"<img src='/static/img/delete.png' title='Eliminar'>"+
						  "</button>";
						  
	$.renderizeRow = function( nRow, aData, iDataIndex ) {
	   $(nRow).attr('id',aData['suc_num']+"-"+aData['mss_num']);
	   $($(nRow).children('td')[2]).html(aData['mss_est']=="t"?"Disponible":"Ocupado");
	   $($(nRow).children('td')[3]).html(aData['suc_num']+" : "+aData['suc_nom']);
	   $($(nRow).children('td')[1]).html(aData['mss_cap']+" personas");
	   $(nRow).append("<td class='text-center'>"+btnsOpTblFoods+"</td>");
	};
						  
	var flagMd = true;
	$("#ltMesas").click(function(event){
		//$("#tbModels").ajax.reload();
		if (flagMd){
			$.fnTbl('#tbMesas',"/mesas/get_tables_all/",[{ "data": "mss_num"},{"data":"mss_cap"},{"data":"mss_est"},{"data":"suc_num"}],$.renderizeRow);
			flagMd = false;		
		}
		else if(create){
			$('#tbMesas').DataTable().ajax.reload();
			create = false;
		}
	});
});