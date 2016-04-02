var isocrona;
var geocoder;



/**
* @fileoverview esta función calcula el perímetro de la isocrona
*
* @author alvaroggallardo
* @version 1.0
*/
/**
* @param {Int} latitud de inicio del cálculo
* @param {Int} longitud de inicio del cálculo
* @param {Int} tiempo de viaje en minutos
*/  
function calculaIsolinea(latCalc,longCalc,timeTravel,capa){
	//Creamos las coordenadas
	var coordenadas = [latCalc,longCalc];
	
	var marker = L.marker(coordenadas);
	// set the service key, this is a demo key
    // please contact us and request your own key
    r360.config.serviceKey = 'YD9VT5Q9NMSERALNJXN4';
    r360.config.serviceUrl = 'https://service.route360.net/iberia/';

	//Establecemos los parámetros para el cálculo de la isocrona
	var travelOptions = r360.travelOptions();
    // we want to have polygons for 5 to 30 minutes
    travelOptions.setTravelTimes([timeTravel*60]);
    // vamos en coche
    travelOptions.setTravelType('car');
    // we only have one source which is the marker we just added
    travelOptions.addSource(marker);

	
	// Llamamos al servicio route360
    r360.PolygonService.getTravelTimePolygons(travelOptions, function(polygons){

    layer.setOpacity(0.3); //Mientras busca establecemos una transparencia para que se vea el gift de cargando
    });
		
	 setTimeout(
                function(){ 
					//Dibujamos la isocrona en OpenLayers
					drawPoligon(isocrona,capa,timeTravel);
					layer.setOpacity(1); //Una vez que ha terminado todos los cálculos ponemos la capa OSM sin transparencia
		}, 5000
    );
}


/**
* @fileoverview Dibuja sobre openlayers el polígono que representa la isocrona
*
* @author alvaroggallardo
* @version 1.0
*/
/**
* @param {String Array} conjunto de strings que contienen coordenadas separadas por comar para cada vértice
*/  
function drawPoligon(isocrona,capa,timeTravel){

	var points = [];

    isocrona.polygons[0].outerBoundary.forEach(function(coords) {

       
        //Guardamos la latitud y la longitud
        var lat = coords[1];
        var lon = coords[0];
        //Creamos un vértice del poígono por cada par de coordenadas
        var point = new OpenLayers.Geometry.Point(lon,lat);
        //Añadimos el punto al array de vértices de la isocrona
        points.push(point);

    })

	//////////////////////////////////////OPENLAYERS//////////////////////////////////////////////
	
	//Creamos un linear Ring
	var OriginalRing = new OpenLayers.Geometry.LinearRing(points);

	//Simplifamos la isocrona én función al tiempo de búsqueda para que no tenga que cargar tantos vértices openlayers
	var ring = simplificaIsocrona(OriginalRing,timeTravel);

	//Creamos el polígono a partor del linear ring
	var polygon = new OpenLayers.Geometry.Polygon([ring]);
	//Añadimos las features
	var feature = new OpenLayers.Feature.Vector(polygon);
	//Creamos la capa vectorial
	layerVector = new OpenLayers.Layer.Vector("Isocrona");
	//Añadimos las features a la capa
	layerVector.addFeatures([feature]);
	//Añadimos la capa al mapa
	map.addLayer(layerVector);
	//Establecemos el zoom del mapa al área de cálculo
	map.zoomToExtent(layerVector.getDataExtent());
	
	//Creamos un bucle para saber que elemento quedan dentro de nuestra isocrona
	for (var a = 0; a < capa.features.length; a++) {
		//Si la isocrona intersecta con el centro educativo
		if (polygon.intersects(capa.features[a].geometry)) {
			//Entonces seleccionamos el centro educativo en el mapa
			selectFeature.select(capa.features[a]);

			//Metemos en el array de colegios los seleccionados
			nombreCole.push(capa.features[a].attributes['DENOMINA_2']);
			provinciaCole.push(capa.features[a].attributes['PROVINCIA']);
			municipioCole.push(capa.features[a].attributes['MUNICIPIO']);

			//Añadimos una búsqueda más para ese colegio
			jQuery.ajax({
		       type: "GET",
		       //Por cada colegio seleccionado añadimos su codigo como una búsqueda más
		       data:{"data":parseInt(capa.features[a].attributes['CODIGO_COL'])},
		       url: "php/addBusqueda.php",
		       cache: false,
		       success: function(response)
		       {
		         
		       }
		    });

			var latitudProceso = capa.features[a].attributes['COORD__LAT'];
			var longitudProceso = capa.features[a].attributes['COORD__LON'];

			var origen = address;

			//Utilizamos la función que hemos creado de geocodificación inversa para saber la calle del colegio
			latlon2direction(String(latitudProceso+","+longitudProceso));
			  
		}//Fin del if
    }
		 

}


/**
* @fileoverview Función para geocodificación inversa. Le pasamos un par de coordenadas separadas por coma y te devuelve como se llama la calle
*
* @author alvaroggallardo
* @version 1.0
*/
/**
* Muestra un mensaje de texto
* @param {String} Un par de coordenadas en EPSG:900913 separadas por coma
* @returns {String} El nombre de la calle donde están las coordenadas
*/       
 function latlon2direction(latlon) {

    var calle;

     //Si está relleno el argumento de la función
     if(latlon) {
        //Llamamos al servicio de google para geocodificación
        geocoder.geocode({'address': latlon}, function(results, status) {
           //Si hay calle para nuestras coordenadas
           if (status == google.maps.GeocoderStatus.OK) {

              calle = latlon= results[0].formatted_address;

              //Si hay calle depuramos el resultado y devolvemos el texto
              if (calle) {
                 var calleCiudad = calle.split(',', 2);
                 calle = calleCiudad[0].trim() + "\n" + calleCiudad[1].trim() + "\n";

                 //Devolvemos el valor de la calle
                 //alert(address);

                 tiempoDistancia(address,calle)
                 
              }
              //txt += "lat: " + fldLat.value + "\nlng: " + fldLng.value + "\n";

           } else {
              //Si GeocoderStatus da un error
              console.log("Problema con coordenada en latlon2direction");
           }
        });


     }

     return(calle);
  }

 /**
* @fileoverview Función que calcula el tiempo y la distancia entre dos puntos
*
* @author alvaroggallardo
* @version 1.0
*/
/**
* 
* @param {Object} Tanto un objeto lonlat de google, como un string con coords o calle
* @param {Object} Tanto un objeto lonlat de google, como un string con coords o calle
* @returns {String} El nombre de la calle donde están las coordenadas
*/  

  function tiempoDistancia(origen,destino){

    var request = {
       origin: origen, 
       destination: destino,
       travelMode: google.maps.DirectionsTravelMode.DRIVING
      };

      directionsService.route(request, function(response, status) {
        if (status == google.maps.DirectionsStatus.OK) {
            // Display the distance:
            //alert(response.routes[0].legs[0].distance.value + " metros");
            distanciaCole.push(response.routes[0].legs[0].distance.value);

            // Display the duration:
            //alert(response.routes[0].legs[0].duration.value + " segundos");
            var minutosViaje = response.routes[0].legs[0].duration.value/60;
            tiempoCole.push(minutosViaje);
        }
      });
      
  }


  //Imprime los resultados en el panel derecho
  function imprimir(){
  		$('#tablaResultados').append("<tr>"+
				    "<td class='headTabla'>Colegio</td>"+
				    "<td class='headTabla'>Municipio</td>"+
				    "<td class='headTabla'>Provincia</td>"+
					"</tr>");
      //Rellenamos los resultados
      for (i=0;i<nombreCole.length;i++){ 
          //alert(nombreCole[i]);
            //$('#tablaResultados').append('<tr><td class="bodyTabla">'+nombreCole[i]+'</td>'+'<td class="bodyTabla">'+distanciaCole[i]+'</td>'+'<td class="bodyTabla">'+Math.floor(tiempoCole[i])+'</td></tr>');
            $('#tablaResultados').append('<tr><td class="bodyTabla">'+nombreCole[i]+'</td>'+'<td class="bodyTabla">'+municipioCole[i]+'</td>'+'<td class="bodyTabla">'+provinciaCole[i]+'</td></tr>');
      }


  }


 /**
* @fileoverview Función que calcula el tiempo y la distancia entre dos puntos
*
* @author alvaroggallardo
* @version 1.0
*/
/**
* 
* @param {Object} Capa sobre la que debemos hacer la búsqueda
*/  
  function buscaColegio(capa){

  			//Recogemos el valor de la caja de texto donde el usuario introduce la dirección de búsqueda
			address = $('#address').val();
			//Abrimos el panel derecho para mostrar el resultado
			layout.open('east');
			//Recogemos el del tiempo en minutos
			var timeTravel = $('#timeTravel').val();
			//Creamos una instancia del objeto para geocodificar
			var geocoder = new google.maps.Geocoder();
			//Utilizamos la dirección y el método geocode para sacar las coordenadas
			geocoder.geocode({ 'address': address}, geocodeResult);
			//Procesamos el resultado
			function geocodeResult(results, status) {
				//Si hay una respuesta válida
				if (status == 'OK') {
					//Guardamos en una variable la latitud
					var latCalc = results[0].geometry.location.lat();
					//La escribimos en el cuadro de texto
					$('#latitude').text(latCalc);
					//Hacemos lo mismo con la longitud
					var longCalc = results[0].geometry.location.lng();
					$('#longitude').text(longCalc);
					//Una vez tenemos las coordenadas y el tiempo y lanzamos la función para calcular la isocrona
					calculaIsolinea(latCalc,longCalc,timeTravel,capa);

				} else {
					//Si se produce un error en la geocodificación lanzamos una alerta
					alert("Geocoding no tuvo éxito debido a: " + status);
				}
			}

			//Esto es necesatrio xk no se cuando acaba google exactamente de procesar
			setTimeout(function(){imprimir();}, 6000);
  }


 /**
* @fileoverview Simplifica la isocrona en función del tiempo para que no tenga tantos vértices
*
* @author alvaroggallardo
* @version 1.0
*/
/**
* 
* @param {Object} LinearRing de entrada
* @param {Float} Grado de simplificación, a más número, más simplicado. Simplification is based on the Douglas-Peucker algorithm.
* @returns {Object} LinearRing de salida
*/  

  function simplificaIsocrona(linearRing,timeTravel){


	//Convertimos este LinearRing en Linear String
	var OriginalLineString = new OpenLayers.Geometry.LineString(linearRing.components);

	var newLineString;

	timeTravelInt = parseInt(timeTravel);

	//Simplificamos el Linear String
	    if(timeTravelInt<=40){
	    	newLineString = OriginalLineString.simplify(250);
	    }
	    if(timeTravelInt>40 && timeTravelInt<=60){
	    	newLineString = OriginalLineString.simplify(600);
	    }
	    if(timeTravelInt>60 && timeTravelInt<=90){
	    	newLineString = OriginalLineString.simplify(2500);
	    }
	    if(timeTravelInt>90){
	    	newLineString = OriginalLineString.simplify(5000);
	    }

		
	//Convertimos de nuevo el linear-string en linearRing
	var ring = new OpenLayers.Geometry.LinearRing(newLineString.getVertices());

	return ring;
      
  }