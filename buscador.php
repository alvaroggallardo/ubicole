<?php
// Evitar los errores de variables no definidas!!!
//Necesitamos esta variable para manejar los errores de registro en la aplicación
$err = isset($_GET['error']) ? $_GET['error'] : null ;

// Cargamos la clase que maneja las sesiones en la aplicación
require'users/class/sessions.php';
//Creamos un objeto de la clase sessions
$objses = new Sessions();
//Lo inicializamos
$objses->init();
//Operador ternario que define la sesión de usuario con el nombre que se registró si está definido, y si no está definido lo registra como nulo
$user = isset($_SESSION['user']) ? $_SESSION['user'] : null ;

?>
<html>
<head>
	<meta name="viewport" content="initial-scale=1.0, width=device-width" />
	<meta http-equiv="Content-Type" content="text/html"; charset="utf-8"/> 
    <meta name="description" content="Buscador de Ubicole" />
    <meta name="keywords" content="colegio,interinos,oposición,gis,búsqueda,ubicole,alvaroggallardo" />
    <meta name="author" content="@alvareto5" />

	<title>Buscador de colegios UBICOLE</title>
	<!--CRAGA DE API 360 debemos de cargar leaftlet -->
	<script type="text/javascript" src="js/thirdparty/route360/leaflet.js"></script>
	<script type="text/javascript" src="js/thirdparty/route360/r360-core-src.js"></script>
	<script type="text/javascript" src="js/thirdparty/route360/r360-leaflet-src.js"></script>
	

	<!--CRAGA DE API EXTERNOS GOOGLE-->
	<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places"></script>


	<!--CARGA DE API EXTERNOS THIRD PARTY-->
	<script src="js/thirdparty/jquery.js"type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript" src="js/thirdparty/OpenLayers/OpenLayers.debug.js"></script>
	<script type="text/javascript" src="js/thirdparty/jqueryLayout.js"></script>
	<script type="text/javascript" src="js/thirdparty/jquery-ui/jquery-ui.min.js"></script>
	

	<!--Carga de archivos propios-->
	<script src="js/geoprocesos.js"type="text/javascript" charset="utf-8"></script>
	<script src="js/utils.js"type="text/javascript" charset="utf-8"></script>
	<script src="js/cargaCapas.js"type="text/javascript" charset="utf-8"></script>
	
	<!--Carga de hojas de estilo-->
	<link rel="icon" type="image/png" sizes="32x32" href="images/favicon.png">
	<link rel="stylesheet" href="js/thirdparty/OpenLayers/theme/default/style.css" type="text/css">
	<link rel="stylesheet" href="css/mapa.css" type="text/css">
	<link rel="stylesheet" href="css/jqueryLayout.css" type="text/css">
    <link rel="stylesheet" type="text/css" href="css/style.css" />
	<link rel="stylesheet" type="text/css" href="css/animate-custom.css" />
    <link href='http://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet' type='text/css'>
	
	
	<script type="text/javascript">
		//Variables genéricas de mapa
		var map, layer,layout,popup,selectedFeature;
		//Variables para procesos de google
		var geocoder,directionsService,address;
		//variables de resultados sobre el mapa
		var layerVector,selectFeature,isocrona;
		//variables para las distintas capas que contienen los centro educativos
		var centrosEducativosInfantilPrivados,centrosEducativosInfantilPrivadosInfo;
		var centrosEducativosInfantilPublicos,centrosEducativosInfantilPublicosInfo;
		var centrosEducativosSecundariaPublicos,centrosEducativosSecundariaPublicosInfo;
		var centrosEducativosSecundariaPrivados,centrosEducativosSecundariaPrivadosInfo;
		// variable de resultados sobre tabla
		var mostrarResultados;
		//Arrays para almacenar los resultados
		var nombreCole = new Array();
		var provinciaCole = new Array();
		var municipioCole = new Array();
		var tiempoCole = new Array();
		var distanciaCole = new Array();
		//Variables para los sistemas de coordenadas
		var epsg4326 = new OpenLayers.Projection("EPSG:4326"); //WGS84
		var epsg900913 = new OpenLayers.Projection("EPSG:900913"); //WebMercator
		//Esta variable guarda la selección del combo
		var selectedValue;
		//Variables que guardan los estilos
		var styleNormal,styleSeleccionado,styleVisible,styleInfo;


		//++++++++++++++INICIALIZACIÓN DE LOS OBJETOS DE JQUERY UI+++++++++++++++++++++
		//Botón jquery UI del buscador
		 $(function() {
			$( "input[type=submit], a, button" )
				.button()
				.click(function( event ) {
				event.preventDefault();
			});
		});

		 //Inicialización de JQuery Layout
		$(document).ready(function () {
        	layout = $('body').layout({ 
        		applyDefaultStyles: true,
        		east__size:300
        	});
        	layout.close('east');
    	});


			
		</script>

		        <!-- CODIGO GOOGLE ANALYTICS  -->
        <script>
          (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
          (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
          m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
          })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

          ga('create', 'UA-68458166-1', 'auto');
          ga('send', 'pageview');

        </script>

</head>
<!--Nada más cargar el body llama a la función init que está en utils-->
<body onload="init();">
	<!--La parte superior de carga del layout-->
	<div class="ui-layout-north">
		<div id="navegarBuscador">
			<nav class="codrops-demos">
				<a id="index" href="./index.php">Inicio</a>
				<a href="buscador.php" class="current-demo">Buscador de colegios</a>
				<a id="ayuda" href="ayuda.html">Ayuda</a>
                <a id="contacto" href="contacto.html">Contacto</a>
			</nav>
			<?php 
                if($user == ''){
                    echo "<div class='infoUsuarioBuscador'><a id='registrate' href='./index.php'>Regístrate</a> para poder usar la aplicación</div>";
                }else{
                    echo "<div class='infoUsuarioBuscador'><b>Usuario: </b>".$_SESSION['user']."</div>";
                }  
            ?>
		</div>

		<div class="cabecera">
			<!--Formulario de dirección de búsqueda-->
			<div id="wrapperBuscador">
				<div class="cuadroTexto">
					Dirección <input type="text" maxlength="400" id="address" > 
				</div>
				<div class="cuadroTexto">
					Tiempo de viaje en minutos <input type="text" maxlength="100" id="timeTravel">
				</div>
				<div class="combo">
					Tipo de enseñanza
				    <select class="comboDetail" id="comboTipoEnse" onchange="changeTipoEnse();">
					    <option value="centrosEducativosInfantilPublicos">Infantil y primaria públicos</option>
					    <option value="centrosEducativosInfantilPrivados">Infantil y primaria privados</option>
					    <option value="centrosEducativosSecundariaPublicos">Secundaria públicos</option>
					    <option value="centrosEducativosSecundariaPrivados">Secundaria privados</option>
				    </select>
				</div>

				<table class = "oculto">
					<tr><td class="unitx1"><strong>Latitud:</strong></td><td class="unitx2"><div id="latitude"></div></td></tr>
					<tr><td><strong>Longitud:</strong></td><td><div id="longitude"></div></td></tr>
				</table>

				<div class="boton">
					<label for="submit"><a href="javascript:void(0)" id="search">Buscar colegios</a></label>
				</div>
			</div>
		</div>
	</div>

	<!--La parte central de carga del layout-->
	<div class="ui-layout-center">
		<div id='cargandoMapa' style='display: none;'><img src='images/loading.gif' class='ajax-loader'/></div>
		<!--contenedor del mapa OL-->
		<div id="mapOL" style="background: url(images/loading.gif) 50% 50% no-repeat #ffffff;">
		</div>
	</div>

	<!--La parte derecha de carga del layout, aquí se cargarán los resultados-->
	<div class="ui-layout-east">
		<div id="resultados">
			<table id="tablaResultados" >
				<tr>
				    <td class="headTabla">Colegio</td>
				    <td class="headTabla">Municipio</td>
				    <td class="headTabla">Provincia</td>
				</tr>
			</table>
		</div>

	</div>

	<!--La parte inferior de carga del layout-->
	<div class="ui-layout-south">
		<div id="footer">
			<div id="textoFooter">
				<b>Realizado por:</b> <a href="https://es.linkedin.com/in/alvarogonzalezgallardo">Álvaro González Gallardo</a>
			</div>
			<div id="logosFooter">
				<b>Powered by: </b> 
				<a href="https://www.route360.net" target='_blank'><img src="images/footer/ROUTE360.png" alt="logo" style="padding-right:10px;"></a>
				<a href="https://www.google.es/maps" target='_blank'><img src="images/footer/Google.jpg" alt="logo" style="padding-right:10px;"></a>
				<a href="http://openlayers.org" target='_blank'><img src="images/footer/openlayers.jpg" alt="logo" style="padding-right:10px;"></a>
			</div>
		</div>
	</div>

	<?php 
		//Si no hay usuario avisamos de que no se puede buscar
        if($user == ''){
            echo "<div class='infoUsuarioBuscador'>Registrate para poder usar la aplicación</div>";
        //Si hay usuario le damos la bienvenida
        }else{
            echo "<div class='infoUsuarioBuscador'>Bienvenido, ".$_SESSION['user']."</div>";
        }  
    ?>
<script type='text/javascript'>

	var session = "<?php echo $user; ?>" ;

	//Añadimos evento click y manejador al enlace de búsqueda
	//Pasaremos a la función que busca el colegio la capa sobre la que actuar
	$('#search').on('click', function() {
		//Si estamos registrados nos deja buscar
		if(session != ''){

			//Si había algún resultado lo borramos
			limpiaResultados();
			//En función de la capa elegida buscamos los resultados
		    switch(selectedValue) {
			    case "centrosEducativosInfantilPublicos":
			    	buscaColegio(centrosEducativosInfantilPublicos);
			        break;
			    case "centrosEducativosInfantilPrivados":
			    	buscaColegio(centrosEducativosInfantilPrivados);
			        break;
			    case "centrosEducativosSecundariaPublicos":
			    	buscaColegio(centrosEducativosSecundariaPublicos);
			        break;
			    case "centrosEducativosSecundariaPrivados":
			    	buscaColegio(centrosEducativosSecundariaPrivados);
			        break;

			} 
		//Si no hay usuario no nos dejará buscar y nos pedirá que nos registremos
		}else{
			alert("DEBES REGISTRARTE PARA PODER BUSCAR");
		}
		
	});

</script>

</body>
</html>

