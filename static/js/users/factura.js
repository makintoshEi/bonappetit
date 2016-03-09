/* global $ */
/* global PNotify */
$(function(){

	var idOrdMesa ="";
	$.ordenMesa = function(obj){
		event.preventDefault();
		$('#detailsOrden tbody').html('');
		$.subTotal('Orden');
		idOrdMesa = $.trim($($($(obj).parent()).parent()).attr('id'));
		var datos=idOrdMesa.split("-");
		$('#txtSucursalMesa').val($.getSelectedText('txtEstablecimientoMesas'));
		$('#txtNumeroMesa').val(datos[1]);
		$("#mdOrdenMesa").modal('show');
		$.ajax({
			type: "POST",
			url: "/mesas/find_order/",
			dataType: 'json',
			data: {id: idOrdMesa, bill:"false"},
			success: function(response) {
				if(response)
				{
					var general = response.general;
					var detalles = response.detalles;
					contenido="";
					var postfijo="Orden";
					detalles.forEach(function(producto) {
						$('#details'+postfijo+' tbody').append('<tr id="'+(producto.prd_id)+postfijo+'"><td style="padding:3px;">'+producto.prd_nom+'</td><td align="center" id="prc_'+(producto.prd_id)+postfijo+'">'+(parseFloat(producto.dem_prc).toFixed(2))+'</td><td><input type="number" step="1" min="1" class="form-control" onchange="$.calculateTotal('+(producto.prd_id)+',\''+(postfijo)+'\')" id="cant_'+(producto.prd_id)+postfijo+'" value="'+producto.dem_cnt+'" style="width:90%; height:30px; margin-left:5%;"/></td><td><input type="number" readonly="true" class="form-control" id="ttl_'+(producto.prd_id)+postfijo+'" style="width:90%; height:30px; margin-left:5%;" value="'+(parseFloat(producto.dem_cnt) * parseFloat(producto.dem_prc)).toFixed(2)+'"/></td><td align="center"> <button onclick="$.removeItem('+(producto.prd_id)+',\''+postfijo+'\')"  type="button" class="btn btn-default">  <span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button> </td></tr>');
					});
					$("#chkIvaOrden").prop("checked",general.orm_iva=="t"?true:false)
					$("#txtDescOrden").val(general.orm_dsc)
					$("#txtPropinaOrden").val(general.orm_prp)
					$.subTotal(postfijo);
				}	
			},
			error: function(){
				$.errorMessage();
			}
	});
	}
	
	
	$.facturar = function(obj){
		event.preventDefault();
		$.infoMressage('Cargando orden...');
		$('#details tbody').html('');
		idOrdMesaFac = $.trim($($($(obj).parent()).parent()).attr('id'));
		$.ajax({
			type: "POST",
			url: "/mesas/find_order/",
			dataType: 'json',
			data: {id: idOrdMesaFac, bill:"true"},
			success: function(response) {
				if(response)
				{
					var general = response.general;
					var detalles = response.detalles;
					contenido="";
					var postfijo="";
					detalles.forEach(function(producto) {
						$('#details'+postfijo+' tbody').append('<tr id="'+(producto.prd_id)+postfijo+'"><td style="padding:3px;">'+producto.prd_nom+'</td><td align="center" id="prc_'+(producto.prd_id)+postfijo+'">'+(parseFloat(producto.dem_prc).toFixed(2))+'</td><td><input type="number" step="1" min="1" class="form-control" onchange="$.calculateTotal('+(producto.prd_id)+',\''+(postfijo)+'\')" id="cant_'+(producto.prd_id)+postfijo+'" value="'+producto.dem_cnt+'" style="width:90%; height:30px; margin-left:5%;"/></td><td><input type="number" readonly="true" class="form-control" id="ttl_'+(producto.prd_id)+postfijo+'" style="width:90%; height:30px; margin-left:5%;" value="'+(parseFloat(producto.dem_cnt) * parseFloat(producto.dem_prc)).toFixed(2)+'"/></td><td align="center"> <button onclick="$.removeItem('+(producto.prd_id)+',\''+postfijo+'\')"  type="button" class="btn btn-default">  <span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button> </td></tr>');
					});
					$("#chkIva").prop("checked",general.orm_iva=="t"?true:false)
					$("#txtDesc").val(general.orm_dsc)
					$("#txtPropina").val(general.orm_prp)
					$.subTotal(postfijo);
					$("#ltNew").click();
					$('#txtFacturero').focus();
					$.successMessage('Orden cargada!');
				}	
			},
			error: function(){
				$.errorMessage();
			}
	});
	}

	$("#txtEstablecimientoMesas" ).change(function() {
		event.preventDefault();
		$.ajax({
			type: "POST",
			url: "/mesas/get_tables_by_store/",
			dataType: 'json',
			data: {id: $("#txtEstablecimientoMesas").val()},
			success: function(response) {
				if(response)
				{
					var mesas = response.data;
					contenido="";
					if(mesas.length==0)
					{
						$('#detailsMesas tbody').html('<tr><td colspan="4" style="padding:3px;">No existen mesas registradas. <a href="http://bonappetit.encoding-ideas.com/mesas/start" class="btn btn-primary" role="button">Añade una mesa</a></td></tr>');
					}
					else
					{
						$('#detailsMesas tbody').html('');
					}
					mesas.forEach(function(mesa) {
						$('#detailsMesas tbody').append('<tr id="'+mesa.suc_num+'-'+mesa.mss_num+'" style="background-color:'+(mesa.mss_est=="t"?"#fff":"#FFD9D9")+';"><td style="padding:4px;" align="center">'+mesa.mss_num+'</td><td align="center">'+mesa.mss_cap+' personas</td><td align="center">'+(mesa.mss_est=="t"?"Disponible":"Ocupado")+'</td><td align="center"><button style="border: 0; background: transparent" onclick="$.ordenMesa(this);"><img src="/static/img/orden.png" height="28" title="Orden"></button>'+(mesa.mss_est=="t"?'':'<button style="border: 0; background: transparent" onclick="$.facturar(this);"><img src="/static/img/facturar.png" height="28" title="Facturar"></button>')+'</td></tr>');
						
					});
				}	
			},
			error: function(){
				$.errorMessage();
			}
		});
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
	
	 $(document).ready(function () {
	 $("#txtEstablecimiento" ).change();
	 var f = new Date();
	$('#txtFecha').val(f.getFullYear() + "-" + ((f.getMonth() +1)<10?"0"+(f.getMonth() +1):(f.getMonth() +1)) + "-" + ((f.getDate())<10?"0"+(f.getDate()):(f.getDate())) );
	$("#txtCedula").autocomplete({
	source: "/cliente/search_autocomplete/",
	minLength: 3,//search after two characters
	select: function(event,ui){
			$.post("/cliente/search_client_by_id", {id:ui.item.id}, function( response ){
				if( response!=null ){
					$('#txtNombre').val(response.cli_nom);
					$('#txtApellido').val(response.cli_ape);
					$('#txtDireccion').val(response.cli_dir);
					$('#txtEmail').val(response.cli_ema);
					$('#txtTelefono').val(response.cli_tlf)
					$('#txtNumero').focus();
				}else{
					$.errorMessage();
				}
			},'json');
		}
	});
	$.autoCompleteProduct("");
	$.autoCompleteProduct("Orden");
	});
	
	$.autoCompleteProduct=function(postfijo){
		$("#txtProductos"+postfijo).autocomplete({
		source: "/factura/search_autocomplete_products/",
		minLength: 3,//search after two characters
		select: function(event,ui){
				var datos = (ui.item.id+"").split(";e-i;");
				if ( $("#"+datos[0]+postfijo).length == 0 )
				{
					//$('#details tr:last').after('<tr id="'+datos[0]+'"><td style="padding:3px;">'+datos[1]+'</td><td align="center" id="prc_'+datos[0]+'">'+datos[2]+'</td><td><input type="number" step="1" class="form-control" onchange="$.calculateTotal('+datos[0]+')" id="cant_'+datos[0]+'" value="1" style="width:90%; height:30px; margin-left:5%;"/></td><td><input type="number" readonly="true" class="form-control" id="ttl_'+datos[0]+'" style="width:90%; height:30px; margin-left:5%;" value="'+datos[2]+'"/></td><td align="center"> <button onclick="$.removeItem('+datos[0]+')"  type="button" class="btn btn-default">  <span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button> </td></tr>');
					$('#details'+postfijo+' tbody').append('<tr id="'+datos[0]+postfijo+'"><td style="padding:3px;">'+datos[1]+'</td><td align="center" id="prc_'+datos[0]+postfijo+'">'+(parseFloat(datos[2]).toFixed(2))+'</td><td><input type="number" step="1" min="1" class="form-control" onchange="$.calculateTotal('+datos[0]+',\''+(postfijo)+'\')" id="cant_'+datos[0]+postfijo+'" value="1" style="width:90%; height:30px; margin-left:5%;"/></td><td><input type="number" readonly="true" class="form-control" id="ttl_'+datos[0]+postfijo+'" style="width:90%; height:30px; margin-left:5%;" value="'+datos[2]+'"/></td><td align="center"> <button onclick="$.removeItem('+datos[0]+',\''+postfijo+'\')"  type="button" class="btn btn-default">  <span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button> </td></tr>');
					$.subTotal(postfijo);
				}
				$('#txtProductos'+postfijo).val("");
				$('#divProduct'+postfijo).html('<input type="text" class="form-control" id="txtProductos'+postfijo+'" name="txtProductos'+postfijo+'" placeholder="Ingrese Productos"/>');
				$.autoCompleteProduct(postfijo);
				$('#txtProductos'+postfijo).focus();
			}
		});
	}
	
	$.calculateTotal = function(id, postfijo){
		//var tr=$($($('#'+id).parent()).parent());
		//$("#"+tr.id+" td:nth-child(2)")
		$("#ttl_"+id+postfijo).val((parseInt($("#cant_"+id+postfijo).val())*parseFloat($("#prc_"+id+postfijo).html())).toFixed(2));
		$.subTotal(postfijo);
	}
	$.removeItem = function(id,postfijo){
		$("#"+id+postfijo).remove();
		$.subTotal(postfijo);
	}
	$.subTotal = function(postfijo){
		var subtotal=0;
		$('#tbodyDetails'+postfijo+' tr').each(function() {
			subtotal+=parseFloat($("#ttl_"+this.id).val());
		});
		$("#txtSubtotal"+postfijo).val(subtotal.toFixed(2));
		$.calculateDesc(postfijo);
	}
	$.calculateIva = function(postfijo){
		if($("#chkIva"+postfijo).prop("checked"))
		{
			$("#txtIva"+postfijo).val(((parseFloat($("#txtSubtotal"+postfijo).val())-parseFloat($("#txtCantDesc"+postfijo).val()))*0.12).toFixed(2));
		}
		else{
			$("#txtIva"+postfijo).val(0);
		}
		$.calculateTotalPrice(postfijo);
	}
	$.calculateDesc = function(postfijo){
		$("#txtDesc"+postfijo).val(parseFloat($("#txtDesc"+postfijo).val())>100?100:$("#txtDesc"+postfijo).val());
		var desc=(parseFloat($("#txtDesc"+postfijo).val())/100)*(parseFloat($("#txtSubtotal"+postfijo).val()));
		$("#txtCantDesc"+postfijo).val(desc.toFixed(2));
		$.calculateIva(postfijo);
	}
	$.calculateTotalPrice = function(postfijo){
		var total=parseFloat($("#txtSubtotal"+postfijo).val())+parseFloat($("#txtIva"+postfijo).val())-parseFloat($("#txtCantDesc"+postfijo).val())+parseFloat($("#txtPropina"+postfijo).val());
		$("#txtTotal"+postfijo).val(total.toFixed(2));
	}
	$.vaciarDetalles = function(postfijo){
		$('#tbodyDetails'+postfijo).html("");
	}
	/*
	 * -------------------------------------------------------------------
	 *  Create invoice submit(Ajax)
	 * -------------------------------------------------------------------
	 */
	var create = false;
	$("#frmNewInvoice").on("submit",function(event){
		event.preventDefault();
		
		var prods = [];
		var cant = [];
		var prec = [];
		var nombres = [];
		$("#tbodyDetails tr").each(function(){
			var id =$(this).attr('id');
			prods.push(id);
			cant.push($("#cant_"+id).val());
			prec.push($("#prc_"+id).html());
			nombres.push($($(this).children('td')[0]).html());
		});
		$("#txtCedula").change();
		if(!invalidCi){
			if(prods.length>0)
			{
				$("#actionButtonsNew").html('<h4 class="text-primary">Procesando...</h4>')
				if(true){
						$.ajax({
							type: "POST",
							url: "/factura/save_invoice/?prods="+prods+"&cant="+cant+"&prec="+prec+"&dets="+nombres,
							dataType: 'json',
							cache: false,
							async:true,
							data: $(this).serialize(),
							success: function(response) {
								if(response.insert_invoice=="-1")
								{
									$.errorMessage('El número de factura ingresado ya se encuentra registrado.');
								}
								else
								{
									if(response.insert_invoice!="0"){
										$('#tbFacturas').DataTable().ajax.reload();
										$.successMessage('Factura N°'+("00" + $("#txtEstablecimiento").val()).slice (-3)+'-'+("00" + $("#txtFacturero").val()).slice (-3)+'-'+("00000000" + response.insert_invoice).slice (-9)+' se ha guardado correctamente.');
										$("#frmNewInvoice input[type='text']").val('');
										$("#frmNewInvoice input[type='email']").val('');
										$("#frmNewInvoice input[type='number']").val('0');
										$.vaciarDetalles('');
										create = true;
									}else{		
										$.errorMessage('Error en el Registro');
									}
								}
								$("#actionButtonsNew").html('<button type="reset" onclick="$.vaciarDetalles(\'\')" class="button button-3d button-rounded">Borrar</button> <button type="submit" class="button button-3d-primary button-rounded">Guardar</button>')
							},
						error: function(){
							$.errorMessage();
							$("#actionButtonsNew").html('<button type="reset" onclick="$.vaciarDetalles(\'\')" class="button button-3d button-rounded">Borrar</button> <button type="submit" class="button button-3d-primary button-rounded">Guardar</button>')
						}
						});
				}else{
					$.errorMessage("Algunos campos del formulario están mal llenados revise e intente nuevamente..!!");
				}
			}
			else
			{
				$.errorMessage("Esta factura aun no posee productos.");
			}
		}else{
			$.errorMessage("La Cédula Es Incorrecta!!");
		}
	});
	
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
			url: "/factura/delete_invoice/",
			dataType: 'json',
			data: {id:trIdClt},
			success: function(response) {
				if(response){
					$.successMessage();
					$('#tbFacturas').DataTable().ajax.reload();
					//$('#tbFacturas').DataTable().row( $("#"+trIdClt) ).remove().draw();
				}else{		
					$.errorMessage();
				}
			},
			error: function(){
				$.errorMessage();
			}
		});
	 };
	 
	 var trIdSRI="";
	 /*FUNCION PARA COMPLETAR EL PROCESO DE AUTORIZACION DE COMPROBANTE ELECTRONICO*/
	  $.callProcessSRI = function(btn){
		$.confirmMessage($.processSRI, "¿Está seguro de enviar factura al SRI?");
		trIdSRI = $.trim($($($(btn).parent()).parent()).attr('id'));
	  }
	  
	 $.processSRI = function(){
		$.infoMressage('Procesando, por favor espere...');
		$.ajax({
			type: "POST",
			url: "/factura/process_sri/",
			dataType: 'json',
			data: {id:trIdSRI},
			success: function(response) {
				if(response){
					switch(response)
					{
						case "COMPLETO":
							$.successMessage();
							break;
						case "INCOMPLETO":
							$.successMessage("El proceso no se completó correctamente, por favor intentelo de nuevo en unos minutos.");
							$.confirmMessage($.processSRI, "¿Desea enviar una copia de la factura electronica al cliente?");
							break;
						default:
							$.errorMessage("Error: "+response);
					}
					$('#tbFacturas').DataTable().ajax.reload();
				}else{		
					$.errorMessage();
				}
			},
			error: function(){
				$.errorMessage();
			}
		});
	 };
	 
	 /*FUNCION PARA EL ENVIO DE EMAIL CON COMPROBANTE ELECTRÓNICO*/
	 $.callEmailInvoice = function(btn){
		$.confirmMessage($.emailInvoice, "¿Está seguro de enviar factura al cliente?");
		trIdSRI = $.trim($($($(btn).parent()).parent()).attr('id'));
	  }
	  
	 $.emailInvoice = function(){
		$.infoMressage('Procesando, por favor espere...');
		$.ajax({
			type: "POST",
			url: "/factura/send_email_invoice/",
			dataType: 'json',
			data: {id:trIdSRI},
			success: function(response) {
				if(response){
					$.successMessage();
				}else{
					if(response=="NoEmail")
					{
						$.errorMessage("El cliente no registra una dirección de correo electrónico.");
					}
					else
					{
						$.errorMessage();
					}
				}
			},
			error: function(){
				$.errorMessage();
			}
		});
	 };
	 
	 /*DOWNLOAD FILE XML AUTHORIZED*/
	 $.downloadInvoiceXML = function(btn){
		$.infoMressage('Procesando, por favor espere...');
		trIdSRI = $.trim($($($(btn).parent()).parent()).attr('id'));
		var win = window.open('http://bonappetit.encoding-ideas.com/factura/download_xml_invoice?id='+trIdSRI, '_blank');
		if(win){
			//Browser has allowed it to be opened
			win.focus();
		}else{
			//Broswer has blocked it
			$.errorMessage('Para descargar el archivo debe peritir los "Popups" para este sitio.');
		}
		/*$.ajax({
			type: "POST",
			url: "/factura/download_xml_invoice/",
			dataType: 'json',
			data: {id:trIdSRI},
			success: function(response) {
				if(response){
					//$.successMessage();
				}else{
					//$.errorMessage();
				}
			},
			error: function(){
				$.errorMessage();
			}
		});*/
	 };
	 
	 $('#mdInvoice').on('shown.bs.modal', function () {
		
	 });
	 
	 $('#mdInvoice').on('hidden.bs.modal', function () {
		
	 });
	 
	 
	 var trIdClt;
	 $.editDeleteModel = function(btn, edt){
	 	trIdClt = $.trim($($($(btn).parent()).parent()).attr('id'));
	 	if(edt){
			$("#spIdCliMd").attr('data-toggle',trIdClt);
			$.post("/factura/search_invoice_id", {id:trIdClt}, function( response ){
				$("#mdInvoice").modal('show');
				if( response!=null ){
					var response=response.data;
					var general = response[0];
					$($('#txtCedulaMd').val(general.cli_ced)).attr('disabled',true);
					$('#txtNombreMd').val(general.cli_nom);
					$('#txtApellidoMd').val(general.cli_ape);
					$('#txtDireccionMd').val(general.cli_dir);
					$('#txtEmailMd').val(general.cli_ema);
					$('#txtTelefonoMd').val(general.cli_tlf);
					$('#txtNumeroMd').val(trIdClt);
					$('#txtFechaMd').val(general.fac_fec);
					$('#txtSubtotalMd').val(general.fac_sub);
					$('#txtIvaMd').val(general.fac_iva);
					$('#txtCantDescMd').val(general.fac_des);
					$('#txtTotalMd').val(general.fac_tot);
					$('#txtPropinaMd').val(general.fac_prp);
					$('#detailsMd tbody').html('');
					$.each( response, function( key, value ) {
						//console.log(value);
					  $('#detailsMd tbody').append('<tr><td style="padding:3px;">'+value.prd_nom+'</td><td align="center">'+value.dfa_prc+'</td><td align="center">'+value.dfa_cnt+'</td><td align="center">'+(value.dfa_prc*value.dfa_cnt)+'</td></tr>');
				
					});
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
	
	$("#frmReportVentas").on("submit",function(event){
		var win = window.open('http://bonappetit.encoding-ideas.com/report/ingresos_pdf?suc='+$('#txtEstablecimientoReportVenta').val()+'&name='+$.getSelectedText('txtEstablecimientoReportVenta')+'&fec_in='+$('#txtFechaInicioReportVenta').val()+'&fec_fn='+$('#txtFechaFinReportVenta').val(), '_blank');
		if(win){
			//Browser has allowed it to be opened
			win.focus();
		}else{
			//Broswer has blocked it
			$.errorMessage('Para visualizar la factura debe peritir los "Popups" para este sitio.');
		}
		
		/*$.post('http://bonappetit.encoding-ideas.com/report/factura_pdf', {id:trIdClt}, function (data) {
			
		});*/

	});
	
	$("#frmReportTop").on("submit",function(event){
		var type=$('#txtTipoProdReportTop').val()
		type=type=='1'?'b':type=='2'?'p':'';
		var win = window.open('http://bonappetit.encoding-ideas.com/report/top_pdf?suc='+$('#txtEstablecimientoReportVenta').val()+'&name='+$.getSelectedText('txtEstablecimientoReportTop')+'&fec_in='+$('#txtFechaInicioReportTop').val()+'&fec_fn='+$('#txtFechaFinReportTop').val()+'&range='+$('#txtRangoReportTop').val()+'&type='+type, '_blank');
		if(win){
			//Browser has allowed it to be opened
			win.focus();
		}else{
			//Broswer has blocked it
			$.errorMessage('Para visualizar la factura debe peritir los "Popups" para este sitio.');
		}
		
		/*$.post('http://bonappetit.encoding-ideas.com/report/factura_pdf', {id:trIdClt}, function (data) {
			
		});*/

	});
	
	$.print = function(btn)
	{
		trIdClt = $.trim($($($(btn).parent()).parent()).attr('id'));
		var win = window.open('http://bonappetit.encoding-ideas.com/report/factura_pdf?id='+trIdClt, '_blank');
		if(win){
			//Browser has allowed it to be opened
			win.focus();
		}else{
			//Broswer has blocked it
			$.errorMessage('Para visualizar la factura debe peritir los "Popups" para este sitio.');
		}
		
		/*$.post('http://bonappetit.encoding-ideas.com/report/factura_pdf', {id:trIdClt}, function (data) {
			
		});*/

	}
	
	$.getSelectedText = function(elementId) {
		var elt = document.getElementById(elementId);

		if (elt.selectedIndex == -1)
			return null;

		return elt.options[elt.selectedIndex].text;
	}
	
	var btnsOpTblModels = "<button style='border: 0; background: transparent' onclick='$.editDeleteModel(this, true);'>"+
							"<img src='/static/img/revisar.png' height='24' title='Revisar'></button>"+
						  "<button style='border: 0; background: transparent' onclick='$.print(this);'>"+
							"<img src='/static/img/imprimir.png' height='24' title='Imprimir'></button>"+
							(window.firma?"<button style='border: 0; background: transparent' onclick='$.callProcessSRI(this);'><img height='24' src='/static/img/sri.png' title='Enviar a SRI'></button>":"");
	
					  
	$.renderizeRow = function( nRow, aData, iDataIndex ) {
	   $(nRow).append("<td class='text-center'>"+btnsOpTblModels+
	   ((aData['fac_est_aut']=="t" && aData['fac_est_rec']=="t")?"<button style='border: 0; background: transparent' onclick='$.callEmailInvoice(this);'><img height='24' src='/static/img/fg-mensaje.png' title='Enviar e-mail'></button>":"")+
	   ((aData['fac_est_aut']=="t" && aData['fac_est_rec']=="t")?"<button style='border: 0; background: transparent' onclick='$.downloadInvoiceXML(this);'><img height='24' src='/static/img/xml-file.png' title='Descargar XML'></button>":"")+
	   "<button style='border: 0; background: transparent' onclick='$.editDeleteModel(this, false);'><img src='/static/img/delete.png' title='Eliminar'></button></td>");
	   $(nRow).attr('id',aData['fac_num']);
	   $($(nRow).children('td')[4]).html("<input type='checkbox' onclick='return false;' disable='true' "+(aData['fac_est_aut']=="t"?"checked":"")+"/>");
	   $($(nRow).children('td')[5]).html("<input type='checkbox' onclick='return false;' disable='true' "+(aData['fac_est_rec']=="t"?"checked":"")+"/>");
	   $($(nRow).children('td')[4]).attr('align', 'center');
	   $($(nRow).children('td')[5]).attr('align', 'center');
	};
	
	$.fnTbl('#tbFacturas',"/factura/get_invoice_all/",[{ "data": "fac_fec"},{"data":"fac_num"},{"data":"nombre"},{"data":"fac_tot"},{"data":"fac_est_rec"},{"data":"fac_est_aut"}],$.renderizeRow);
	
	$("#ltClient").click(function(event){
		if(create){
			$('#tbFacturas').DataTable().ajax.reload();
			create = false;
		}
	});
	
	$("#ltOrdenes").click(function(event){
		$("#txtEstablecimientoMesas" ).change();
	});
    
	//************ GUARDAR ORDEN*/
	$("#frmOrdenMesa").on("submit",function(event){
		event.preventDefault();
		var prods = [];
		var cant = [];
		$("#tbodyDetailsOrden tr").each(function(){
			var id =$(this).attr('id');
			prods.push(id.replace("Orden",""));
			cant.push($("#cant_"+id).val());
		});
		if(prods.length>0)
		{
		$("#actionButtonsOrden").html('<h4 class="text-primary">Procesando...</h4>')
		$.ajax({
			type: "POST",
			url: "/mesas/save_order/?prods="+prods+"&cant="+cant+"&id="+idOrdMesa,
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
						$('#detailsOrden tbody').html('');
						$.subTotal('Orden');
						$("#mdOrdenMesa").modal('hide');
						$("#txtEstablecimientoMesas" ).change();
						idOrdMesa="";
					}else{		
						$.errorMessage('Error en el Registro');
					}
				}
				$("#actionButtonsOrden").html('<button type="reset" onclick="$.vaciarDetalles(\'Orden\')" class="button button-3d button-rounded">Borrar</button> <button type="submit" class="button button-3d-primary button-rounded">Guardar</button>')
			},
		error: function(){
			$.errorMessage();
			$("#actionButtonsOrden").html('<button type="reset" onclick="$.vaciarDetalles(\'Orden\')" class="button button-3d button-rounded">Borrar</button> <button type="submit" class="button button-3d-primary button-rounded">Guardar</button>')
		}
		});
		}
		else
		{
			$.errorMessage('Esta orden aún no contiene productos.');
		}
	});
	
});