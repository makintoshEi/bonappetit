/* global $ */
/* global PNotify */
$(function(){
	
	/*===VALIDACIONES===*/
	$.ValidaSoloNumeros = function() {
	 if ((event.keyCode < 48) || (event.keyCode > 57)) 
	  event.returnValue = false;
	};
	
	$.ValidaSoloLetras = function() {
	 if ((event.keyCode != 32) && (event.keyCode < 65) || (event.keyCode > 90) && (event.keyCode < 97) || (event.keyCode > 122))
	  event.returnValue = false;
	};
	
    $("#frmNewClient").validate({
        rules: {
            txtCedula:{required:true,minlength:10,maxlength:13,digits:true},
            txtNombre: {minlength: 4,required: true},
            txtApellido: {minlength: 4,required: true},
            txtEmail:{minlength: 8,required: true, email:true},
			txtDireccion:{minlength: 8,required: true}
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
	
	$("#frmMdClient").validate({
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
	$("#frmNewClient").on("submit",function(event){
		event.preventDefault();
		if(!invalidCi){
			if($("#frmNewClient").validate().numberOfInvalids()==0){
					$.ajax({
						type: "POST",
						url: "/cliente/save_client/",
						dataType: 'json',
						data: $(this).serialize(),
						success: function(response) {
							if(response.insert_client=="-1")
							{
								$.errorMessage('La C.I./R.U.C. ingresado ya se encuentra registrado.');
							}
							else
							{
								if(response.insert_client=="1"){
									$.successMessage('Registro Exitoso');
									$("#frmNewClient input[type='text']").val('');
									$("#frmNewClient input[type='email']").val('');
									create = true;
								}else{		
									$.errorMessage('Error en el Registro');
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
		}else{
			$.errorMessage("La Cédula Es Incorrecta!!");
		}
	});
	
	/*
	 * -------------------------------------------------------------------
	 *  Edit client submit(Ajax) --- modal form
	 * -------------------------------------------------------------------
	 */
	 $( "#updateClient" ).click(function() {
		console.log("invalidos:"+$('#frmNewClient').validate().numberOfInvalids());
	  $( "#frmMdClient" ).submit();
	});
	 var telsMd = [];
	 $("#frmMdClient").on("submit",function(event){
	 //$.updateClient = function(event){
	 	event.preventDefault();
		console.log('paso por aquí');
		var formData = new FormData($("#frmMdClient")[0]);
		//if(!invalidCi){
		
			if($('#txtNombreMd').val().trim()!="" && $('#txtApellidoMd').val().trim()!=""){
					$.ajax({
						type: "POST",
						url: "/cliente/edit_client/?trId="+$("#spIdCliMd").attr('data-toggle'),
						dataType: 'json',
						data: {txtNombreMd:$("#txtNombreMd").val(),
								txtApellidoMd:$("#txtApellidoMd").val(),
								txtEmailMd:$("#txtEmailMd").val(),
								txtDireccionMd:$("#txtDireccionMd").val(),
								txtTelefonoMd:$("#txtTelefonoMd").val()},
						success: function(response) {
							if(response.update_client == '1'){
								$("#frmMdClient input[type='text']").val('');
								$("#frmMdClient input[type='email']").val('');
								$("#mdClient").modal('hide');
								$.successMessage();
								$('#tbClients').DataTable().ajax.reload();
							}else{		
								$.errorMessage();
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
	 
	 
	 $.deleteClient = function(){
	 	$.ajax({
			type: "POST",
			url: "/cliente/delete_client/",
			dataType: 'json',
			data: {id:trIdClt},
			success: function(response) {
				if(response){
					$.successMessage();
					$('#tbClients').DataTable().row( $("#"+trIdClt) ).remove().draw();
				}else{		
					$.errorMessage();
				}
			},
			error: function(){
				$.errorMessage();
			}
		});
	 };
	 
	 $('#mdClient').on('shown.bs.modal', function () {
		$("#txtNombreMd").focus();
	 });
	 
	 $('#mdClient').on('hidden.bs.modal', function () {
		telsMd.length = 0;
	 });
	 
	 var trIdClt;
	 $.editDeleteModel = function(btn, edt){
	 	trIdClt = $.trim($($($(btn).parent()).parent()).attr('id'));
	 	if(edt){
			$("#spIdCliMd").attr('data-toggle',trIdClt);
			$.post("/cliente/search_client_by_id", {id:trIdClt}, function( response ){
				$("#mdClient").modal('show');
				if( response!=null ){
					$($('#txtCedulaMd').val(response.cli_ced)).attr('disabled',true);
					$('#txtNombreMd').val(response.cli_nom);
					$('#txtApellidoMd').val(response.cli_ape);
					$('#txtDireccionMd').val(response.cli_dir);
					$('#txtEmailMd').val(response.cli_ema);
					$('#txtTelefonoMd').val(response.cli_tlf)
					/*if( response.cli_tel !== null){
						var telefonos = response.cli_tel.replace("{","").replace("}","").split(',');
						if ( $.trim(telefonos[0]) != ''){
							$("#tbodyTelsMd").html("");
							$.each(telefonos, function( i, val) {	
								$("#tbodyTelsMd").append("<tr><td class='text-center'>"+val+"</td><td class='text-center'>"+btnsOpTblTels+"</td></tr>");
								$("#divTbTelsMd").fadeIn('fast');
							});
						}
					}*/
				}else{
					$.errorMessage();
				}
			},'json');
	 	}
	 	else
	 	{
	 		$.confirmMessage($.deleteClient);
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
	   $(nRow).attr('id',aData['cli_ced']);
	};
	
	$.fnTbl('#tbClients',"/cliente/get_clients_all/",[{ "data": "cli_ced"},{"data":"cli_nom"},{"data":"cli_ape"},{"data":"cli_ema"}],$.renderizeRow);
	
	$("#ltClient").click(function(event){
		if(create){
			$('#tbClients').DataTable().ajax.reload();
			create = false;
		}
	});
    
});