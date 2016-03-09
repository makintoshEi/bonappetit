/* global $ */
/* global PNotify */
$(function(){
	$(document).ready(function () {
	$("#slcRol").change();
	});
	$("#slcRol").change(function() {
		$("#modulos").html("");
		var rol=$("#slcRol" ).val(), mods_html="";
		$.each( roles, function( key, value ) {
		  if(rol==value[0])
		  {
			$.each( modulos, function( keyMod, valueMod ) {
				if(value[1]<=valueMod.nivel)
				{
					mods_html+="<img src='"+valueMod.icono+"' title='"+valueMod.nombre+"' width='30' height='30' style='margin:2px;'/>";
				}
			});
		  }
		});
		$("#modulos").html(mods_html);
	});
	
	$("#slcRolMd").change(function() {
		$("#modulosMd").html("");
		var rol=$("#slcRolMd" ).val(), mods_html="";
		$.each( roles, function( key, value ) {
		  if(rol==value[0])
		  {
			$.each( modulos, function( keyMod, valueMod ) {
				if(value[1]<=valueMod.nivel)
				{
					mods_html+="<img src='"+valueMod.icono+"' title='"+valueMod.nombre+"' width='30' height='30' style='margin:2px;'/>";
				}
			});
		  }
		});
		$("#modulosMd").html(mods_html);
	});
	
	/*===VALIDACIONES===*/
	$.ValidaSoloNumeros = function() {
	 if ((event.keyCode < 48) || (event.keyCode > 57)) 
	  event.returnValue = false;
	};
	
	$.ValidaSoloLetras = function() {
	 if ((event.keyCode != 32) && (event.keyCode < 65) || (event.keyCode > 90) && (event.keyCode < 97) || (event.keyCode > 122))
	  event.returnValue = false;
	};
	
    $("#frmNewRRHH").validate({
        rules: {
            txtCedula:{required:true,minlength:10,maxlength:13,digits:true},
            txtNombre: {minlength: 4,required: true},
            txtApellido: {minlength: 4,required: true},
            txtEmail:{minlength: 8,required: true, email:true},
		},
        highlight: function (element) {
            $(element).parent().removeClass('has-success has-feedback');
            $(element).parent().addClass("has-error has-feedback");
        },
        success: function (element) {
           $(element).parent().removeClass('has-error has-feedback');
           $(element).parent().addClass("has-success has-feedback");
        }
    });
	
	$("#frmMdRRHH").validate({
        rules: {
            txtCedulaMd:{required:true,minlength:10,maxlength:13,digits:true},
            txtNombreMd: {minlength: 4,required: true},
            txtApellidoMd: {minlength: 4,required: true},
            txtEmailMd:{minlength: 8,required: true, email:true},
			txtDireccionMd:{minlength: 8,required: true}
        },
        highlight: function (element) {
            $(element).parent().removeClass('has-success has-feedback');
            $(element).parent().addClass("has-error has-feedback");
        },
        success: function (element) {
           $(element).parent().removeClass('has-error has-feedback');
           $(element).parent().addClass("has-success has-feedback");
        }
    });
	
    /*=============================*/
    ///VALIDACION CEDULA
    var invalidCi = false;
	
	$.validateCi = function(id){
       $(id).validarCedulaEC({
            strict: true,
            events: "change",
            onValid: function () {
                invalidCi = false;
				var padre = $(id).parent();
                padre.removeClass('has-error has-feedback');
                padre.addClass("has-success has-feedback");
            },
            onInvalid: function () {
                invalidCi = true;
				var padre = $(id).parent();
                padre.removeClass('has-success has-feedback');
                padre.addClass("has-error has-feedback");
            }
        }); 
    };

    $.validateCi("#txtCedula");
    /*=============================*/
	
	/*
	 * -------------------------------------------------------------------
	 *  Create client submit(Ajax)
	 * -------------------------------------------------------------------
	 */
	var create = false;
	var tels = [];
	$("#frmNewRRHH").on("submit",function(event){
		event.preventDefault();
		$("#actionButtons").html('<h4 class="text-primary">Procesando...</h4>')
		if(!invalidCi){
			if($("#frmNewRRHH").validate().numberOfInvalids()==0){
					$.ajax({
						type: "POST",
						url: "/recursohumano/insert_rrhh/",
						dataType: 'json',
						data: $(this).serialize(),
						success: function(response) {
							if(response.insert_rrhh=="-2")
							{
								$.errorMessage('La dirección e-mail ingresada pertenece a una cuenta de administrador.');
							}
							else
							{
								if(response.insert_rrhh=="-1")
								{
									$.errorMessage('La C.I. o el e-mail ingresados ya se encuentran registrados.');
								}
								else
								{
									if(response.insert_rrhh=="1"){
										$.successMessage('Registro Exitoso');
										$("#frmNewRRHH input[type='text']").val('');
										$("#frmNewRRHH input[type='email']").val('');
										create = true;
									}else{		
										$.errorMessage('Error en el Registro');
									}
								}
							}
							$("#actionButtons").html('<button type="submit" class="button button-3d-primary button-rounded">Guardar</button>')
						},
					error: function(){
						$.errorMessage();
					}
					});
			}else{
				$.errorMessage("Algunos campos del formulario están mal llenados revise e intente nuevamente..!!");
				$("#actionButtons").html('<button type="submit" class="button button-3d-primary button-rounded">Guardar</button>')
			}
		}else{
			$.errorMessage("La Cédula Es Incorrecta!!");
			$("#actionButtons").html('<button type="submit" class="button button-3d-primary button-rounded">Guardar</button>')
		}
	});
	
	/*
	 * -------------------------------------------------------------------
	 *  Edit client submit(Ajax) --- modal form
	 * -------------------------------------------------------------------
	 */
	 $( "#updateClient" ).click(function() {
		console.log("invalidos:"+$('#frmNewRRHH').validate().numberOfInvalids());
	  $( "#frmMdRRHH" ).submit();
	});
	 var telsMd = [];
	 $("#frmMdRRHH").on("submit",function(event){
	 //$.updateClient = function(event){
	 	event.preventDefault();
		var formData = new FormData($("#frmMdRRHH")[0]);
		//if(!invalidCi){
		
			if($('#txtNombreMd').val().trim()!="" && $('#txtApellidoMd').val().trim()!="" && $('#txtEmailMd').val().trim()!=""){
					$.ajax({
						type: "POST",
						url: "/recursohumano/edit_rrhh/?trId="+$("#spIdRRHHMd").attr('data-toggle'),
						dataType: 'json',
						data: {txtNombreMd:$("#txtNombreMd").val(),
								txtApellidoMd:$("#txtApellidoMd").val(),
								txtEmailMd:$("#txtEmailMd").val()},
						success: function(response) {
							if(response.update_rrhh == '1'){
								$("#frmMdRRHH input[type='text']").val('');
								$("#frmMdRRHH input[type='email']").val('');
								$("#mdRRHH").modal('hide');
								$.successMessage();
								$('#tbRRHH').DataTable().ajax.reload();
							}else{		
								if(response.update_rrhh == '-1')
								{
									$.errorMessage("La dirección e-mail ingresada ya le pertenece a otro usuario.");
								}
								else
								{
									if(response.update_rrhh == '-2')
									{
										$.errorMessage("La dirección e-mail ingresada pertenece a una cuenta de administrador.");
									}
									else
									{
										$.errorMessage();
									}
								}
							}
						},
						error: function(){
							$.errorMessage();
						}
					});
			}else{
				$.errorMessage("Algunos campos del formulario están mal llenados revise e intente nuevamente..!!");
			}
		/*}else{
			$.errorMessage("La Cédula Es Incorrecta!!");
		}*/
	});
	//}
	
	
	/*
	 * -------------------------------------------------------------------
	 *  function editDeleteModel(btn) -> load modal form edit or delete
	 *	@param : btn => parameter this btn(editModel) onclick
	 *	@param : edt => edit o delete param(true=>edit, false=>delete)
	 * -------------------------------------------------------------------
	 */
	 
	 
	 $.deleteRRHH = function(){
	 	$.ajax({
			type: "POST",
			url: "/recursohumano/delete_rrhh/",
			dataType: 'json',
			data: {id:trIdClt},
			success: function(response) {
				if(response){
					$.successMessage();
					$('#tbRRHH').DataTable().row( $("#"+trIdClt) ).remove().draw();
				}else{		
					$.errorMessage();
				}
			},
			error: function(){
				$.errorMessage();
			}
		});
	 };
	 
	 $('#mdRRHH').on('shown.bs.modal', function () {
		$("#txtNombreMd").focus();
	 });
	 
	 $('#mdRRHH').on('hidden.bs.modal', function () {
		telsMd.length = 0;
	 });
	 
	 var trIdClt;
	 $.editDeleteModel = function(btn, edt){
		$('#storeData').hide();
	 	trIdClt = $.trim($($($(btn).parent()).parent()).attr('id'));
	 	if(edt){
			$("#spIdRRHHMd").attr('data-toggle',trIdClt);
			$.post("/recursohumano/search_rrhh_by_id", {id:trIdClt}, function( response ){
				$("#mdRRHH").modal('show');
				if( response!=null ){
					$($('#txtCedulaMd').val(response.emp_ced)).attr('disabled',true);
					$('#txtNombreMd').val(response.emp_nom);
					$('#txtApellidoMd').val(response.emp_ape);
					$('#txtEmailMd').val(response.emp_eml);
					$('#txtSalarioMd').val(response.emp_sal);
					$('#slcRolMd').val(response.rol_id);
					$('#slcRolMd').change();
					if(response.suc_num!=null)
					{
						$('#storeData').show();
						$('#txtNumero').val(response.suc_num);
						$('#txtNombreSuc').val(response.suc_nom);
						$('#txtCiudadSuc').val(response.ciu_nom);
						$('#txtDireccionSuc').val(response.suc_dir);
					}
				}else{
					$.errorMessage();
				}
			},'json');
	 	}
	 	else
	 	{
	 		$.confirmMessage($.deleteRRHH);
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
							"<img src='/static/img/edit.png' title='Editar'>"+
						  "</button>"+
						  "<button style='border: 0; background: transparent' onclick='$.editDeleteModel(this, false);'>"+
							"<img src='/static/img/delete.png' title='Eliminar'>"+
						  "</button>";
	
					  
	$.renderizeRow = function( nRow, aData, iDataIndex ) {
	   $(nRow).append("<td class='text-center'>"+btnsOpTblModels+"</td>");
	   $(nRow).attr('id',aData['emp_id']);
	};
	
	$.fnTbl('#tbRRHH',"/recursohumano/get_rrhh_all/",[{ "data": "emp_ced"},{"data":"emp_nom"},{"data":"emp_ape"},{"data":"emp_eml"},{"data":"rol_dsc"}],$.renderizeRow);
	
	$("#ltRRHH").click(function(event){
		if(create){
			$('#tbRRHH').DataTable().ajax.reload();
			create = false;
		}
	});
    
});