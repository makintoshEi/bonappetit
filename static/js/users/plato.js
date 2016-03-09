$(function(){
var create = false;
$("#frmNewFood").on('submit', function(event){
		var formData = new FormData($("#frmNewFood")[0]);
		event.preventDefault();
		$("#actionButtons").html('<h4 class="text-primary">Procesando...</h4>')
		$.ajax({
			url: "/plato/save_food/",  
			type: 'POST',
			data: formData,
			dataType:'json',
			//necesario para subir archivos via ajax
			cache: false,
			contentType: false,
			processData: false,
			// mientras se envia el archivo
			beforeSend: function(){               
			   $.infoMressage();
			},
			//si finalizo correctamente
			success: function(response){
				switch(response) {
					case '0':
						$.errorMessage('Error al actualizar datos.');
						$("#actionButtons").html('<button type="submit" class="button button-3d-primary button-rounded">Guardar</button>')
						break;
					default:
						$.successMessage();
						$("#actionButtons").html('<button type="submit" class="button button-3d-primary button-rounded">Guardar</button>')
						$("#frmNewFood input").val('');
						$("#frmNewFood textarea").val('');
						$("#txtNombre").focus();
						var fileupload = $('#images');
						$($(fileupload).parent().children('div')[0]).html("");
						create = true;
						break;
				}	
			},
			//si ocurrido un error
			error: function(){
				$.errorMessage("");
				$("#actionButtons").html('<button type="submit" class="button button-3d-primary button-rounded">Guardar</button>')
			}
		});
	});
/*
 * -------------------------------------------------------------------
 *  Edit model submit(Ajax) --- modal form
 * -------------------------------------------------------------------
 */
 $("#frmMdFood").on("submit",function(event){
	var formData = new FormData($("#frmMdFood")[0]);
	event.preventDefault();
	$.ajax({
		type: "POST",
		url: "/plato/edit_food/?trId="+$("#spId").attr('data-toggle'),
		data: formData,
		dataType: 'json',
		//necesario para subir archivos via ajax
		cache: false,
		contentType: false,
		processData: false,
		// mientras se envia el archivo
		beforeSend: function(){               
		   $.infoMressage();
		},
		//si finalizo correctamente
		success: function(response) {
			if(response){
				$('#tbPlatos').DataTable().ajax.reload();
				$("#frmMdFood input").val('');
				$("#frmMdFood textarea").val('');
				var fileupload = $('#imagesMd');
				$($(fileupload).parent().children('div')[0]).html("");
				$("#mdFood").modal('hide');
				$.successMessage();
			}else{		
				$.errorMessage();
			}
		},
		error: function(){
			$.errorMessage();
		}
	});
});
/*
	*CARGA DE DATOS DESDE LA BD A MODAL EDITION
*/
 $.chargeFood = function(){
		event.preventDefault();
		$("#actionButtonsEdit").html('<h4 class="text-primary">Procesando...</h4>');
		$.ajax({
			url: "/plato/find_food/",  
			type: 'POST',
			data: {id:trIdMd},
			dataType:'json',
			//si finalizo correctamente
			success: function(response){
				food=response.data[0]
				$('#txtNombreEdit').val(food.prd_nom);	
				$('#txtInfoEdit').val(food.prd_dsc);	
				$('#txtPrecEdit').val(food.prd_prc);	
				$('#slcCatgEdit').val(food.ctp_id);	
				$('#slcTipoEdit').val(food.tpr_id);
				$("#divImgsMd").html('');
				$($("#divImgsMd").append("<img src='/"+food.prd_url+"' class='img-thumbnail img-responsive'>")).attr("style","border: 1px solid #ccc; padding:10px;background-color:#FFF;");
				$("#actionButtonsEdit").html('<button type="submit" class="button button-3d-primary button-rounded">Guardar</button>')
			},
			//si ocurrido un error
			error: function(){
				$.errorMessage("Error al establecer conexión con el servidor");
				$("#actionButtonsEdit").html('<button type="submit" class="button button-3d-primary button-rounded">Guardar</button>')
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
		url: "/plato/delete_food/",
		dataType: 'json',
		data: {id:trIdMd},
		success: function(response) {
			if(response){
				$.successMessage();
				$('#tbPlatos').DataTable().row( $("#"+trIdMd) ).remove().draw();
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
		$.chargeFood();
		$("#mdFood").modal('show');
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
						  
	$.renderizeRowTbModels = function( nRow, aData, iDataIndex ) {
	   $(nRow).append("<td class='text-center'>"+btnsOpTblFoods+"</td>");
	   $(nRow).attr('id',aData['prd_id']);
	   $($(nRow).children('td')[1]).html(parseFloat(aData['prd_prc']).toFixed(2));
	};
						  
	var flagMd = true;
	$("#ltFood").click(function(event){
		//$("#tbModels").ajax.reload();
		if (flagMd){
			$.fnTbl('#tbPlatos',"/plato/get_food_all/",[{ "data": "prd_nom"},{"data":"prd_prc"},{"data":"ctp_nom"},{"data":"tpr_des"}],$.renderizeRowTbModels);
			flagMd = false;		
		}
		else if(create){
			$('#tbPlatos').DataTable().ajax.reload();
			create = false;
		}
	});
});