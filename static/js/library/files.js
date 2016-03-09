$(function(){
	$(':file').change(function () {
		var tam = this.files.length;
		var fileupload = $(this);
		$($(fileupload).parent().children('div')[0]).html("");
		console.log(tam);
		if(tam ==1){
	   
			var cont = true;
			$.each(this.files, function(i, val){
				var nombre = val.name;
				var tamano = val.size;
				var tipo   = val.type.replace("image/",".");
				console.log(tipo);
				//2.5MB
				if (tipo !== ".png" && tipo !== ".jpg"  && tipo !== ".jpeg" || tamano > 2097152){
					cont= false;
					i = tam;
					$($(fileupload).parent().children('div')[0]).html("");
				}
				
				if(cont){
					var reader = new FileReader();
					reader.onload = function (e) {
						$($($(fileupload).parent().children('div')[0]).append("<img src='"+e.target.result+"' class='img-thumbnail img-responsive'>")).attr("style","border: 1px solid #ccc; padding:10px;background-color:#FFF;");
					};
					reader.readAsDataURL(val);
					
				}else{
					$($($(fileupload).parent().children('div')[0]).html($.errorDiv(" El tipo de imagen permitido es png y jpg. Máximo 2.5MB!"))).removeAttr("style");
					fileupload.val('');
				}
			});
		}else{
			$($($(fileupload).parent().children('div')[0]).html($.errorDiv(" se puede seleccionar máximo 2 fotos!"))).removeAttr("style");
			fileupload.val('');
		}
	});
});