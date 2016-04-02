function cargaEstilos(){

	//Estilo para los colegios por defecto
	styleNormal = new OpenLayers.Style({
		strokeColor: "#2e6784",
        strokeOpacity: 1,
        strokeWidth: 1,
        pointRadius: 12,
  		externalGraphic: 'images/icon/schoolNormal.png',
  		label: "${getName}",
  		fontSize: "12px",
  		fontWeight: "bold",
  		labelXOffset: 5,
        labelYOffset: 5,
        labelOutlineColor: "white",
        labelOutlineWidth: 3,
        fontColor: "#2e6784",
        graphicTitle: "${getLabel}"
	},
	{
		context: {
			getName: function(feature) {
							var zoom = map.getZoom();
							
							if(zoom>14){
								var name = feature.attributes['DENOMINA_2'];
							}else{
								var name = "";
							}	
							return name;
		            },
            getLabel: function(feature) {

							var name = feature.attributes['DENOMINA_2'];
	
							return name;
		            }
		}
	});

	//Estilo para cuando los colegios son seleccionados
	styleSeleccionado = new OpenLayers.Style({
		strokeColor: "#2e6784",
        strokeOpacity: 1,
        strokeWidth: 1,
        pointRadius: 15,
  		externalGraphic: 'images/icon/schoolSelected.png'
	});

	//Este estilo recoge los dos anteriores y es para las capas visibles
	styleVisible = new OpenLayers.StyleMap({'default': styleNormal,
                         'select': styleSeleccionado}
                         );

	//Este estilo hace transparente la capa y es para las capas que nos proporcionana la información
	styleInfo = new OpenLayers.Style({
		strokeColor: "#2e6784",
        strokeOpacity: 0,
        strokeWidth: 1,
        pointRadius: 10,
        fillOpacity: 0
	});

}


function cargaCapaInfantilPrivados(){


  	//Creamos la capas con los centro educativos
	centrosEducativosInfantilPrivados = new OpenLayers.Layer.Vector("centrosEducativosInfantilPrivados", {
		strategies: [new OpenLayers.Strategy.Fixed()],
		protocol: new OpenLayers.Protocol.HTTP({
			url: "data/geoJSON/infantilPrivados.geojson",
			format: new OpenLayers.Format.GeoJSON()
		}),
		styleMap: styleVisible
	});


	centrosEducativosInfantilPrivadosInfo = new OpenLayers.Layer.Vector("centrosEducativosInfantilPrivadosInfo", {
		strategies: [new OpenLayers.Strategy.Fixed()],
		protocol: new OpenLayers.Protocol.HTTP({
			url: "data/geoJSON/infantilPrivados.geojson",
			format: new OpenLayers.Format.GeoJSON()
		}),
		styleMap: styleInfo
	});


	map.addLayer(centrosEducativosInfantilPrivados);
    map.addLayer(centrosEducativosInfantilPrivadosInfo);


    //Creamos un control de mapa para poder seleccionar elementos de una capa
	selectFeature = new OpenLayers.Control.SelectFeature(centrosEducativosInfantilPrivados);

	map.addControl(selectFeature);
	
	//Activamos la herramienta
	selectFeature.activate();


	function onPopupClose(evt) {
	    // 'this' is the popup.
	    selectControl.unselectAll();
	}

	function onFeatureSelect(selectedFeature) {

        $.ajax({
        	data:{"data":parseInt(selectedFeature.attributes['CODIGO_COL'])},
     		url:'php/service.php', 
     		type: "GET",
     		beforeSend: function() {
     			$('#map').fadeTo("slow",0.5);
            },
            complete: function() {
            	$('#map').fadeTo("slow",1);
            },
     		success: function(response){

 				var html = response;
				
					popup = new OpenLayers.Popup.FramedCloud("chicken", 
                     selectedFeature.geometry.getBounds().getCenterLonLat(),
                     null,
                     "<div class='popupCustom'><img src='images/popup/cabecera.jpg' alt='cabaceraPopUp'></br></br>"+html+"</div>"+
                     "<div class='footerPopup'><img src='images/popup/footerPopup.jpg' alt='piePopUp'>",
                     null, 
                     true, 
                     onPopupClose
                );
	            	
	                popup.maxSize = new OpenLayers.Size(420, 400);
	                popup.minSize = new OpenLayers.Size(200, 300);
					
					selectedFeature.popup = popup;
	                map.addPopup(popup);
     			}
     		});//Fin llamada AJAX	
    };

    function onFeatureUnselect(selectedFeature) {
        map.removePopup(selectedFeature.popup);
        selectedFeature.popup.destroy();
        selectedFeature.popup = null;
    };  

    var selectControl = new OpenLayers.Control.SelectFeature(centrosEducativosInfantilPrivadosInfo,
        {onSelect: onFeatureSelect, onUnselect: onFeatureUnselect});

	map.addControl(selectControl);

	selectControl.activate();

  }


function cargaCapaInfantilPublicos(){


	centrosEducativosInfantilPublicos = new OpenLayers.Layer.Vector("centrosEducativosInfantilPublicos", {
		strategies: [new OpenLayers.Strategy.Fixed()],
		protocol: new OpenLayers.Protocol.HTTP({
			url: "data/geoJSON/infantilPublicos.geojson",
			format: new OpenLayers.Format.GeoJSON()
		}),
		styleMap: styleVisible
	});


	centrosEducativosInfantilPublicosInfo = new OpenLayers.Layer.Vector("centrosEducativosInfantilPublicosInfo", {
		strategies: [new OpenLayers.Strategy.Fixed()],
		protocol: new OpenLayers.Protocol.HTTP({
			url: "data/geoJSON/infantilPublicos.geojson",
			format: new OpenLayers.Format.GeoJSON()
		}),
		styleMap: styleInfo
	});


	map.addLayer(centrosEducativosInfantilPublicos);
    map.addLayer(centrosEducativosInfantilPublicosInfo);

    //Creamos un control de mapa para poder seleccionar elementos de una capa
	selectFeature = new OpenLayers.Control.SelectFeature(centrosEducativosInfantilPublicos);

	map.addControl(selectFeature);
	
	//Activamos la herramienta
	selectFeature.activate();


	function onPopupClose(evt) {
	    // 'this' is the popup.
	    selectControl.unselectAll();
	}

	function onFeatureSelect(selectedFeature) {

        $.ajax({
        	data:{"data":parseInt(selectedFeature.attributes['CODIGO_COL'])},
     		url:'php/service.php', 
     		type: "GET",
     		beforeSend: function() {
     			$('#map').fadeTo("slow",0.5);
            },
            complete: function() {
            	$('#map').fadeTo("slow",1);
            },
     		success: function(response){

 				var html = response;
				
					popup = new OpenLayers.Popup.FramedCloud("chicken", 
                     selectedFeature.geometry.getBounds().getCenterLonLat(),
                     null,
                      "<div class='popupCustom'><img src='images/popup/cabecera.jpg' alt='cabaceraPopUp'></br></br>"+html+"</div>"+
                     "<div class='footerPopup'><img src='images/popup/footerPopup.jpg' alt='piePopUp'>",
                     null, 
                     true, 
                     onPopupClose
                );
	            	
	                popup.maxSize = new OpenLayers.Size(620, 500);
	                popup.minSize = new OpenLayers.Size(200, 300);
					
					selectedFeature.popup = popup;
	                map.addPopup(popup);
     			}
     		});//Fin llamada AJAX	
    };

    function onFeatureUnselect(selectedFeature) {
        map.removePopup(selectedFeature.popup);
        selectedFeature.popup.destroy();
        selectedFeature.popup = null;
    };  

    var selectControl = new OpenLayers.Control.SelectFeature(centrosEducativosInfantilPublicosInfo,
        {onSelect: onFeatureSelect, onUnselect: onFeatureUnselect});

	map.addControl(selectControl);

	selectControl.activate();

  }


  function cargaCapaSecundariaPublicos(){

	centrosEducativosSecundariaPublicos = new OpenLayers.Layer.Vector("centrosEducativosSecundariaPublicos", {
		strategies: [new OpenLayers.Strategy.Fixed()],
		protocol: new OpenLayers.Protocol.HTTP({
			url: "data/geoJSON/secundariaPublicos.geojson",
			format: new OpenLayers.Format.GeoJSON()
		}),
		styleMap: styleVisible
	});


	centrosEducativosSecundariaPublicosInfo = new OpenLayers.Layer.Vector("centrosEducativosSecundariaPublicosInfo", {
		strategies: [new OpenLayers.Strategy.Fixed()],
		protocol: new OpenLayers.Protocol.HTTP({
			url: "data/geoJSON/secundariaPublicos.geojson",
			format: new OpenLayers.Format.GeoJSON()
		}),
		styleMap: styleInfo
	});


	map.addLayer(centrosEducativosSecundariaPublicos);
    map.addLayer(centrosEducativosSecundariaPublicosInfo);

    //Creamos un control de mapa para poder seleccionar elementos de una capa
	selectFeature = new OpenLayers.Control.SelectFeature(centrosEducativosSecundariaPublicos);

	map.addControl(selectFeature);
	
	//Activamos la herramienta
	selectFeature.activate();


	function onPopupClose(evt) {
	    // 'this' is the popup.
	    selectControl.unselectAll();
	}

	function onFeatureSelect(selectedFeature) {

        $.ajax({
        	data:{"data":parseInt(selectedFeature.attributes['CODIGO_COL'])},
     		url:'php/service.php', 
     		type: "GET",
     		beforeSend: function() {
     			$('#map').fadeTo("slow",0.5);
            },
            complete: function() {
            	$('#map').fadeTo("slow",1);
            },
     		success: function(response){

 				var html = response;
				
					popup = new OpenLayers.Popup.FramedCloud("chicken", 
                     selectedFeature.geometry.getBounds().getCenterLonLat(),
                     null,
                      "<div class='popupCustom'><img src='images/popup/cabecera.jpg' alt='cabaceraPopUp'></br></br>"+html+"</div>"+
                     "<div class='footerPopup'><img src='images/popup/footerPopup.jpg' alt='piePopUp'>",
                     null, 
                     true, 
                     onPopupClose
                );
	            	
	                popup.maxSize = new OpenLayers.Size(620, 500);
	                popup.minSize = new OpenLayers.Size(200, 300);
					
					selectedFeature.popup = popup;
	                map.addPopup(popup);
     			}
     		});//Fin llamada AJAX	
    };

    function onFeatureUnselect(selectedFeature) {
        map.removePopup(selectedFeature.popup);
        selectedFeature.popup.destroy();
        selectedFeature.popup = null;
    };  

    var selectControl = new OpenLayers.Control.SelectFeature(centrosEducativosSecundariaPublicosInfo,
        {onSelect: onFeatureSelect, onUnselect: onFeatureUnselect});

	map.addControl(selectControl);

	selectControl.activate();

  }




  function cargaCapaSecundariaPrivados(){

	centrosEducativosSecundariaPrivados = new OpenLayers.Layer.Vector("centrosEducativosSecundariaPrivados", {
		strategies: [new OpenLayers.Strategy.Fixed()],
		protocol: new OpenLayers.Protocol.HTTP({
			url: "data/geoJSON/secundariaPrivados.geojson",
			format: new OpenLayers.Format.GeoJSON()
		}),
		styleMap: styleVisible
	});


	centrosEducativosSecundariaPrivadosInfo = new OpenLayers.Layer.Vector("centrosEducativosSecundariaPrivadosInfo", {
		strategies: [new OpenLayers.Strategy.Fixed()],
		protocol: new OpenLayers.Protocol.HTTP({
			url: "data/geoJSON/secundariaPrivados.geojson",
			format: new OpenLayers.Format.GeoJSON()
		}),
		styleMap: styleInfo
	});


	map.addLayer(centrosEducativosSecundariaPrivados);
    map.addLayer(centrosEducativosSecundariaPrivadosInfo);

    //Creamos un control de mapa para poder seleccionar elementos de una capa
	selectFeature = new OpenLayers.Control.SelectFeature(centrosEducativosSecundariaPrivados);

	map.addControl(selectFeature);
	
	//Activamos la herramienta
	selectFeature.activate();


	function onPopupClose(evt) {
	    // 'this' is the popup.
	    selectControl.unselectAll();
	}

	function onFeatureSelect(selectedFeature) {

        $.ajax({
        	data:{"data":parseInt(selectedFeature.attributes['CODIGO_COL'])},
     		url:'php/service.php', 
     		type: "GET",
     		beforeSend: function() {
     			$('#map').fadeTo("slow",0.5);
            },
            complete: function() {
            	$('#map').fadeTo("slow",1);
            },
     		success: function(response){

 				var html = response;
				
					popup = new OpenLayers.Popup.FramedCloud("chicken", 
                     selectedFeature.geometry.getBounds().getCenterLonLat(),
                     null,
                      "<div class='popupCustom'><img src='images/popup/cabecera.jpg' alt='cabaceraPopUp'></br></br>"+html+"</div>"+
                     "<div class='footerPopup'><img src='images/popup/footerPopup.jpg' alt='piePopUp'>",
                     null, 
                     true, 
                     onPopupClose
                );
	            	
	                popup.maxSize = new OpenLayers.Size(620, 500);
	                popup.minSize = new OpenLayers.Size(200, 300);
					
					selectedFeature.popup = popup;
	                map.addPopup(popup);
     			}
     		});//Fin llamada AJAX	
    };

    function onFeatureUnselect(selectedFeature) {
        map.removePopup(selectedFeature.popup);
        selectedFeature.popup.destroy();
        selectedFeature.popup = null;
    };  

    var selectControl = new OpenLayers.Control.SelectFeature(centrosEducativosSecundariaPrivadosInfo,
        {onSelect: onFeatureSelect, onUnselect: onFeatureUnselect});

	map.addControl(selectControl);

	selectControl.activate();

  }