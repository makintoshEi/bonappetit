/* global $ */
/* global PNotify */
$(function(){

	 $(document).ready(function () {
	 var f = new Date();
	$('#txtFch').val(f.getFullYear() + "-" + ((f.getMonth() +1)<10?"0"+(f.getMonth() +1):(f.getMonth() +1)) + "-" + f.getDate());
	});
	/*=============================*/
	$.calculateTotal = function(id){
		//var tr=$($($('#'+id).parent()).parent());
		//$("#"+tr.id+" td:nth-child(2)")
		$("#ttl_"+id).val(parseInt($("#cant_"+id).val())*parseFloat($("#prc_"+id).html()));
		$.subTotal();
	}
	$.removeItem = function(id){
		$("#"+id).remove();
		$.subTotal();
	}
	$.subTotal = function(){
		var subtotal=0;
		$('#tbodyDetails tr').each(function() {
			subtotal+=parseFloat($("#ttl_"+this.id).val());
		});
		$("#txtSubtotal").val(Math.round(subtotal*100)/100);
		$("#txtSubtotal").val(parseFloat(subtotal).Fixed(2));
		
	}
	$.calculateIva = function(){
		if($("#txtSub").val()!="0")
		{
			$("#txtIva").val(((parseFloat($("#txtSub").val())-parseFloat($("#txtDesc").val()))*0.12).toFixed(2));
		}
		else{
			$("#txtIva").val(0);
		}
		$.calculateTotalPrice();
	}
	$.calculateDesc = function(){
		var desc=(parseFloat(window.promo)/100)*(parseFloat($("#txtSub").val()));
		$("#txtDesc").val(desc.toFixed(2));
		$.calculateIva();
	}
	$.calculateTotalPrice = function(){
		var total=parseFloat($("#txtSub").val())+parseFloat($("#txtIva").val())-parseFloat($("#txtDesc").val());
		$("#txtTotal").val(Math.round(total*100)/100);
	}
	$.vaciarDetalles = function(){
		$('#tbodyDetails').html("");
	}
	/*
	 * -------------------------------------------------------------------
	 *  Create invoice submit(Ajax)
	 * -------------------------------------------------------------------
	 */
	var create = false;
	$("#frmNewInvoice").on("submit",function(event){
		event.preventDefault();
			if(packs.length>0)
			{
				if(true){
						$.ajax({
							type: "POST",
							url: "/upgrade/save_invoice/?prods="+packs,
							dataType: 'json',
							data: $(this).serialize(),
							success: function(response) {
								if(response.insert_invoice_ei=="0")
								{
									$.errorMessage('Ha ocurrido un error, por favor intentalo de nuevo.');
								}
								else
								{
									if(response.insert_invoice_ei!="0"){
										$.successMessage('Registro Exitoso');
										/*$("#frmNewInvoice input[type='text']").val('');
										$("#frmNewInvoice input[type='email']").val('');
										$.vaciarDetalles();*/
										$("#frmNewInvoice input[type='number']").val('0');
										packs=[];
										$('#tbPacks').DataTable().ajax.reload();
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
				}
			}
			else
			{
				$.errorMessage("Esta factura aun no posee productos.");
			}
	});
	
	
	$("#frmMdCash").on('submit', function(event){
		var formData = new FormData($("#frmMdCash")[0]);
		event.preventDefault();
		$.ajax({
			url: "/upgrade/save_voucher/?id="+trIdClt,  
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
				switch(response.insert_voucher) {
					case '-1':
						$.errorMessage('El número de comprobante ya ha sido registrado previamente.');
						break;
					case '1':
						$.successMessage();
						$("#images").val("");
						$("#txtNumeroCash").val("");
						$("#img_cash").html("");
						$("#mdCash").modal('hide');
						$('#tbInvoices').DataTable().ajax.reload();
						break;
				}	
			},
			//si ocurrido un error
			error: function(){
				$.errorMessage("");
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
	 
	/*TABLA MIS FACTURAS*/ 
	var btnsOpTblInovice = ["<button style='border: 0; background: transparent' onclick='$.editDeleteModel(this, true);'>"+
							"<img src='/static/img/revisar.png' height='24' title='Editar'></button>",
						  "<button style='border: 0; background: transparent' onclick='$.editDeleteModel(this, false);'>"+
							"<img src='/static/img/fg-facturacion.png' height='24' title='Efectuar Pago'></button>"];
	
	var trIdClt;
	 $.editDeleteModel = function(btn, edt){
	 	trIdClt = $.trim($($($(btn).parent()).parent()).attr('id'));
	 	if(edt){
			$("#spIdStoreMd").attr('data-toggle',trIdClt);
			$.post("/upgrade/search_invoice_by_id", {id:trIdClt}, function( response ){
				$("#mdInvoice").modal('show');
				if( response!=null ){
					var general=response.data[0];
					$($('#txtNumeroMd').val(general.fei_id)).attr('disabled',true);
					$('#txtFechaEmisionMd').val(general.fei_fec_emi);
					$('#txtFechaExpMd').val(general.fei_fec_exp);
					$('#detailsMd tbody').html('');
					var subt=0;
					$.each( response.data, function( key, value ) {
						//console.log(value);
						$('#detailsMd tbody').append('<tr><td style="padding:3px;" align="left">'+value.pck_nom+'</td><td style="padding:3px;" align="center">'+value.list+'</td><td style="padding:3px;" align="center">'+value.dur_tmp+' meses</td></tr>');
						subt+=parseInt(value.pck_prc);
					});
					$('#txtSubtotalMd').val(subt.toFixed(2));
					$('#txtIvaMd').val(parseInt(general.fei_iva).toFixed(2));
					$('#txtCantDescMd').val(parseInt(general.fei_dsc).toFixed(2));
					$('#txtTotalMd').val((subt-parseInt(general.fei_dsc)+parseInt(general.fei_iva)).toFixed(2));
				}else{
					$.errorMessage();
				}
			},'json');
	 	}
	 	else
	 	{
			$("#images").val("");
			$("#txtNumeroCash").val("");
			$("#img_cash").html("");
	 		$("#mdCash").modal('show');
	 	} 	
	 };
	
	var packDB=[]				  
	$.renderizeRowInvoice = function( nRow, aData, iDataIndex ) {
	   $(nRow).append("<td align='center'>"+btnsOpTblInovice[0]+(aData['fei_est']=="E"? btnsOpTblInovice[1]:"")+"</td>");
	   $($(nRow).children('td')[3]).html(aData['fei_est']=="E"?"Pendiente":aData['fei_est']=="V"?"Verificando":"Pagada");
	   $(nRow).attr('id',aData['fei_id']);
	};
	
	$.fnTbl('#tbInvoices',"/upgrade/get_invoice_all/",[{ "data": "fei_id"},{"data":"fei_fec_emi"},{"data":"fei_fec_exp"},{"data":"fei_est"},{"data":"total"}],$.renderizeRowInvoice);
	
	$("#ltInvoice").click(function(event){
		if(create){
			$('#tbInvoices').DataTable().ajax.reload();
			create = false;
		}
	});
	
	$.pad = function(str, max) {
	  str = str.toString();
	  return str.length < max ? $.pad("0" + str, max) : str;
	}
	
	/*
	 * -------------------------------------------------------------------
	 *  Generate Table models list
	 *	function renderizeRow renderize tr, td for table
	 *	@param : btnsOpTblModels => variable(string): buttons for dateTable
	 * -------------------------------------------------------------------
	 */
	
	var packDB=[]				  
	$.renderizeRow = function( nRow, aData, iDataIndex ) {
	   $(nRow).append("<td align='center'><input type='checkbox' width='25' onchange='$.selectPack(this)'/></td>");
	   $($(nRow).children('td')[2]).html("$"+aData['pck_prc']);
	   $($(nRow).children('td')[3]).html(aData['dur_tmp']+" meses");
	   $(nRow).attr('id',aData['pck_id']);
	   packDB.push([aData['pck_id'],aData['pck_prc']])
	};
	
	$.fnTbl('#tbPacks',"/upgrade/get_all_packs/",[{ "data": "pck_nom"},{"data":"list"},{"data":"pck_prc"},{"data":"dur_tmp"}],$.renderizeRow);
	
	$("#ltPacks").click(function(event){
		if(create){
			$('#tbPacks').DataTable().ajax.reload();
			create = false;
		}
	});
	
    var packs = [];
	$.selectPack = function(chk) {
		trId=$($($(chk).parent()).parent()).attr("id");
		if($(chk).prop("checked"))
		{
			packs.push(trId);
		}
		else
		{
			var aux=[];
			
			for(var i=0; i<packs.length; i++)
			{
				if(packs[i]!=trId)
				{	
					aux.push(packs[i]);
				}
			}
			packs=aux;
		}
		var subtotal=0;
		for(var i=0; i<packs.length; i++)
			{
				for(var j=0; j<packDB.length; j++)
				{
					if(packs[i]==packDB[j][0])
					{	
						subtotal+=parseFloat(packDB[j][1]);
					}
				}
			}
		$("#txtSub").val(subtotal.toFixed(2))
		$.calculateDesc();
		console.log(packs)
	};
});