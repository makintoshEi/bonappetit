$(function(){
	$( document ).ready(function() {
	  $("#slcProv" ).change();
	});
	
	
	/*
	 * -------------------------------------------------------------------
	 *  Signup restaurant submit(Ajax)
	 * -------------------------------------------------------------------
	 */
	$("#registerFrm").on("submit",function(event){
		event.preventDefault();
		$("#buttonsActionRegister").html('<h4 class="text-primary">Procesando...</h4>')
		if($("#txtRePass").val()==$("#txtPass").val())
		{
			$.ajax({
				type: "POST",
				url: "/main/signup/",
				dataType: 'json',
				data: $(this).serialize(),
				success: function(response) {
					if(response.signup=="-1")
					{
						new PNotify({
								title: 'Aviso',
								text: 'El e-mail que ha ingresado ya está siendo usado por otro usuario.',
								type: 'error'
							});
					}
					else
					{
						if(response.signup!="0"){
							$("#buttonsActionRegister").html('<button type="submit" class="button button-3d-primary button-rounded">Registrarme</button>')
							$('#mdMsj').modal('show');
							$("#registerFrm input").val('');
						}
					}	
				},
				error: function(){
					$.errorMessage();
				}
			});
		}
		else
		{
			new PNotify({
				title: 'Aviso!',
				text: 'La nueva contraseña ingresada no coincide con su confirmación.',
				type: 'error'
			});
			$("#buttonsActionRegister").html('<button type="submit" class="button button-3d-primary button-rounded">Registrarme</button>')
		}
	});
	
	/*
	 * -------------------------------------------------------------------
	 *  Signup restaurant submit(Ajax)
	 * -------------------------------------------------------------------
	 */
	$("#login_Frm").on("submit",function(event){
		event.preventDefault();
		$("#buttonsActionRegister").html('<h4 class="text-primary">Verificando...</h4>')
		if($("#txtRePass").val()==$("#txtPass").val())
		{
			$.ajax({
				type: "POST",
				url: "/main/signin/",
				dataType: 'json',
				data: $(this).serialize(),
				success: function(response) {
					if(response.signup=="-1")
					{
						new PNotify({
								title: 'Aviso',
								text: 'El e-mail que ha ingresado ya está siendo usado por otro usuario.',
								type: 'error'
							});
					}
					else
					{
						if(response.signup!="0"){
							$("#buttonsActionRegister").html('<button type="submit" class="button button-3d-primary button-rounded">Entrar</button>')
							$('#mdMsj').modal('show');
							$("#registerFrm input").val('');
						}
					}	
				},
				error: function(){
					$.errorMessage();
				}
			});
		}
		else
		{
			new PNotify({
				title: 'Aviso!',
				text: 'La nueva contraseña ingresada no coincide con su confirmación.',
				type: 'error'
			});
			$("#buttonsActionRegister").html('<button type="submit" class="button button-3d-primary button-rounded">Registrarme</button>')
		}
	});
	
	/*
	 * -------------------------------------------------------------------
	 *  Pronvicia select change(Ajax)
	 * -------------------------------------------------------------------
	 */
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
	
	$("#frmMdPass").on("submit",function(event){
		event.preventDefault();
			$.ajax({
				type: "POST",
				url: "/main/generate_pass/",
				dataType: 'json',
				data: $(this).serialize(),
				success: function(response) {
					if(response.update_pass!="0")
					{
						$.successMessage();
					}
					else
					{
						$.errorMessage();
					}	
				},
				error: function(){
					$.errorMessage();
				}
			});
	});
});