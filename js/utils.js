 /**
* @fileoverview Función que borra resultados anteriores al volver a pulsar el botón
*
* @author alvaroggallardo
* @version 1.0
*/
/**
*/  
 
 function limpiaResultados(){
	//Limpiamos los arrays de resultados
	nombreCole = [];
	provinciaCole = [];
	municipioCole = [];
	tiempoCole = [];
	distanciaCole = [];
	//Si existe algún resultado en la capa de polígonos lo borramos
	if(layerVector){
		layerVector.removeAllFeatures();
	};
	//Deseleccionamos todos los elementos de la capa para que no interfieran en los nuevos resultados
	selectFeature.unselectAll();
	//Borramos los resultados de la tabla
	$("#tablaResultados tr").remove(); 

	//Refrescamos las capas para que se limpien los estilos seleccionados
	centrosEducativosSecundariaPublicos.refresh();
	centrosEducativosSecundariaPrivados.refresh();
	centrosEducativosInfantilPublicos.refresh();
	centrosEducativosInfantilPrivados.refresh();

 }


  /**
* @fileoverview Función que maneja la visibilidad de las diferentes capas en función de lo que el usuario elija en el comboBox
*
* @author alvaroggallardo
* @version 1.0
*/
/**
* @returns {String} Devuelve el valor seleccionado en el combo
*/  

function changeTipoEnse() {

    var selectBox = document.getElementById("comboTipoEnse");
	selectedValue = selectBox.options[selectBox.selectedIndex].value;

    switch(selectedValue) {
	    case "centrosEducativosInfantilPublicos":

	        centrosEducativosInfantilPrivados.setVisibility (false);
	        centrosEducativosInfantilPrivadosInfo.setVisibility (false);
	        centrosEducativosInfantilPublicos.setVisibility (true);
	        centrosEducativosInfantilPublicosInfo.setVisibility (true);
	        centrosEducativosSecundariaPublicos.setVisibility (false);
	        centrosEducativosSecundariaPublicosInfo.setVisibility (false);
	        centrosEducativosSecundariaPrivados.setVisibility (false);
	        centrosEducativosSecundariaPrivadosInfo.setVisibility (false);
	        break;
	    case "centrosEducativosInfantilPrivados":

	        centrosEducativosInfantilPrivados.setVisibility (true);
	        centrosEducativosInfantilPrivadosInfo.setVisibility (true);
	        centrosEducativosInfantilPublicos.setVisibility (false);
	        centrosEducativosInfantilPublicosInfo.setVisibility (false);
	        centrosEducativosSecundariaPublicos.setVisibility (false);
	        centrosEducativosSecundariaPublicosInfo.setVisibility (false);
	        centrosEducativosSecundariaPrivados.setVisibility (false);
	        centrosEducativosSecundariaPrivadosInfo.setVisibility (false);
	        break;
	    case "centrosEducativosSecundariaPublicos":

	        centrosEducativosInfantilPrivados.setVisibility (false);
	        centrosEducativosInfantilPrivadosInfo.setVisibility (false);
	        centrosEducativosInfantilPublicos.setVisibility (false);
	        centrosEducativosInfantilPublicosInfo.setVisibility (false);
	        centrosEducativosSecundariaPublicos.setVisibility (true);
	        centrosEducativosSecundariaPublicosInfo.setVisibility (true);
	        centrosEducativosSecundariaPrivados.setVisibility (false);
	        centrosEducativosSecundariaPrivadosInfo.setVisibility (false);
	        break;
	    case "centrosEducativosSecundariaPrivados":

	        centrosEducativosInfantilPrivados.setVisibility (false);
	        centrosEducativosInfantilPrivadosInfo.setVisibility (false);
	        centrosEducativosInfantilPublicos.setVisibility (false);
	        centrosEducativosInfantilPublicosInfo.setVisibility (false);
	        centrosEducativosSecundariaPublicos.setVisibility (false);
	        centrosEducativosSecundariaPublicosInfo.setVisibility (false);
	        centrosEducativosSecundariaPrivados.setVisibility (true);
	        centrosEducativosSecundariaPrivadosInfo.setVisibility (true);
	        break;

	} 
    
	return  selectedValue;
}

  /**
* @fileoverview Función que inicializa el mapa
*
* @author alvaroggallardo
* @version 1.0
*/
/**
* @returns 
*/  
function init(){

	//Debido a errores con los eventos debemos hacer las redirecciones así
	$("#index").click(function(event){
          event.preventDefault();
          window.location.href = "index.php";
	});

	//Debido a errores con los eventos debemos hacer las redirecciones así
	$("#registrate").click(function(event){
          event.preventDefault();
          window.location.href = "index.php";
	});

	//Debido a errores con los eventos debemos hacer las redirecciones así
	$("#ayuda").click(function(event){
          event.preventDefault();
          window.location.href = "ayuda.html";
	});

	//Debido a errores con los eventos debemos hacer las redirecciones así
	$("#contacto").click(function(event){
          event.preventDefault();
          window.location.href = "contacto.html";
	});

	    		//Debido a errores con los eventos debemos hacer las redirecciones así
	$("#comoLlegar").click(function(event){
          event.preventDefault();
          //window.location.href = "contacto.html";
	});


	//Función que carga los estilos
	cargaEstilos();


	//Creamos el mapa
	map = new OpenLayers.Map('mapOL');

	//La capa base
	layer = new OpenLayers.Layer.OSM( "Simple OSM Map");
	//Añadimos la capa
	map.addLayer(layer);
	
	//Establecemos los parámetros iniciales del mapa
	map.setCenter(
		//Como la capa OSM está en 900913, esa será la proyección del mapa
		new OpenLayers.LonLat(-5, 42).transform(
			new OpenLayers.Projection("EPSG:4326"), //source
			map.getProjectionObject() //dest
		), 8
	);  
	//Añadimos las coordenadas del ratón
	var ctrl = new OpenLayers.Control.MousePosition()
	//Las añadimos al mapa
	map.addControl(ctrl);

	//Añadimos la capa al mapa
	cargaCapaInfantilPrivados();
	cargaCapaInfantilPublicos();
	cargaCapaSecundariaPublicos();
	cargaCapaSecundariaPrivados();
	

	var input = document.getElementById('address');
	//Cuadro de texto autocompletado de google
	var autocomplete = new google.maps.places.Autocomplete(input);

	//Iniclización de variables para procesos sobre google
	geocoder = new google.maps.Geocoder();
	directionsService = new google.maps.DirectionsService();

	//Para que la primera carga sea correcta debemos de elegir nada más cargar una de las capas
	changeTipoEnse("centrosEducativosInfantilPublicos");

}

