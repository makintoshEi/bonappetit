$(function(){
	
	/*
	 * -------------------------------------------------------------------
	 *  Update pass submit(Ajax)
	 * -------------------------------------------------------------------
	 */
	$("#frmChangePass").on("submit",function(event){
		event.preventDefault();
		if($("#txtPassConfirm").val()==$("#txtNewPass").val())
		{
			$.ajax({
				type: "POST",
				url: "/main/updatePass/",
				dataType: 'json',
				data: $(this).serialize(),
				success: function(response) {
					if(response=="noPass")
					{
						new PNotify({
								title: 'Aviso',
								text: 'La constrase�a ingresada no es la correcta',
								type: 'error'
							});
					}
					else
					{
						if(response){
						new PNotify({
								title: 'Aviso',
								text: 'Contrase�a actulizada.',
								type: 'success'
							});
						new PNotify({
								title: 'Sesion Terminada',
								text: 'Cerrando sesi�n...',
								type: 'notice'
							});
							window.location.replace("/main/logout/");
							//salir de sesion
						}else{		
							new PNotify({
								title: 'Oh No!',
								text: 'Error en el registro.',
								type: 'error'
							});
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
				text: 'La nueva contrase�a ingresada no coincide con su confirmaci�n.',
				type: 'error'
			});
		}
	});
});