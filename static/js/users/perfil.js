$(function(){
var host="bonappetit.encoding-ideas.com"
var map, lat, lng;
	var markersArray = [];
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
	  
	  map=new google.maps.Map(document.getElementById("googleMap"), mapProp);
	  $.placeMarker(center, map,markersArray);
	  google.maps.event.addListener(map, 'click', function(event) {
		   $.placeMarker(event.latLng, map, markersArray);
		});
	}

	navigator.geolocation.getCurrentPosition($.fn_ok, $.fn_mal);
	
	$.placeMarker = function(location, mapa, arrayMarker) {
            // first remove all markers if there are any
            $.deleteOverlays(arrayMarker);

            var marker = new google.maps.Marker({
                position: location, 
                map: mapa
            });
			
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
	
$("#frmDataProfile").on('submit', function(event){
		event.preventDefault();
		var formData = new FormData($("#frmDataProfile")[0]);
		$.ajax({
			url: "/perfil/edit_profile/",  
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
						break;
					case '1':
						$.successMessage();
						break;
				}	
			},
			//si ocurrido un error
			error: function(){
				$.errorMessage("");
			}
		});
	});
//-------------COMPLETAR REGISTRO
$("#registerData").on('submit', function(event){
		var formData = new FormData($("#registerData")[0]);
		event.preventDefault();
		marker=markersArray[markersArray.length-1];
		$.ajax({
			url: "/main/register_data/?lat="+marker.getPosition().lat()+"&lng="+marker.getPosition().lng(),  
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
				switch(response.update_profile) {
					case '0':
						$.errorMessage('El código ingresado es incorrecto.');
						break;
					case '1':
						$.successMessage();
						//redireccionar a home
						window.location = "http://"+host+"/main/home";
						break;
				}	
			},
			//si ocurrido un error
			error: function(){
				$.errorMessage("");
			}
		});
	});
//------CAMBIO DE IMAGEN
$("#frmLogo").on('submit', function(event){
		var formData = new FormData($("#frmLogo")[0]);
		event.preventDefault();
		$.ajax({
			url: "/perfil/update_logo/",  
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
				if(response) {
						$.successMessage();
						//redireccionar a home
						window.location = "http://"+host+"/perfil/start/";
				}
				else{
						$.errorMessage('Error al subir imagen.');
				}	
			},
			//si ocurrido un error
			error: function(){
				$.errorMessage("");
			}
		});
	});
});