<?php
// Evitar los errores de variables no definidas!!!
//Necesitamos esta variable para manejar los errores de registro en la aplicación
$err = isset($_GET['error']) ? $_GET['error'] : null ;
$login = isset($_GET['login']) ? $_GET['login'] : null ;

// Cargamos la clase que maneja las sesiones en la aplicación
require'users/class/sessions.php';
//ESTOS DOS ARCHIVOS LOS NECESITAMOS PARA CARGAR LOS CENTRO MÁS COTIZADOS
//En este archivo es donde configuramos la conexión a la bbdd
require'users/class/config.php';
//llamada a la clase que ejecutará los queries 
require'users/class/dbactions.php';
//Creamos un objeto de la clase sessions
$objses = new Sessions();
//creación o instanciamiento de un objeto de la Clase Connection
$objConn = new Connection();
$objDb = new Database();

//Lo inicializamos
$objses->init();
//Operador ternario que define la sesión de usuario con el nombre que se registró si está definido, y si no está definido lo registra como nulo
$user = isset($_SESSION['user']) ? $_SESSION['user'] : null ;


?>

<!DOCTYPE html>
<!--[if lt IE 7 ]> <html lang="en" class="no-js ie6 lt8"> <![endif]-->
<!--[if IE 7 ]>    <html lang="en" class="no-js ie7 lt8"> <![endif]-->
<!--[if IE 8 ]>    <html lang="en" class="no-js ie8 lt8"> <![endif]-->
<!--[if IE 9 ]>    <html lang="en" class="no-js ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html lang="en" class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="UTF-8" />
        <!-- <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">  -->
        <title>UbiCole</title>
        <!-- METAINFORMACIÓN PARA LA APLICACIÓN  -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
        <meta name="description" content="Portada de Ubicole" />
        <meta name="keywords" content="colegio,interinos,oposición,gis,búsqueda,ubicole,alvaroggallardo" />
        <meta name="author" content="@alvareto5" />
        <!-- CARGAMOS LOS ARCHIVOS EXTERNOS  -->
        <link rel="icon" type="image/png" sizes="32x32" href="images/favicon.png">
        <link rel="stylesheet" type="text/css" href="css/style.css" />
        <link rel="stylesheet" type="text/css" href="css/mapa.css" />
		<link rel="stylesheet" type="text/css" href="css/animate-custom.css" />
        <link href='http://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet' type='text/css'>

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
    <body>
        <div class="container">
            <!-- DEFINIMOS LA CABECERA DE LA PORTADA  -->
            <header>
                <h1><span>Ubicole</span> te ayudara a encontrar los colegios mas cercanos a tu ubicacion <span>Registrate y pruebalo</span></h1>
                <div class="imagenCabecera">
                    <img src="images/imagenPortada.bmp" alt="logo">
                    <?php 
                        if($user == ''){
                            echo "<div class='infoUsuario'>Registrate para poder usar la aplicación</div>";
                        }else{
                            echo "<div class='infoUsuario'>Bienvenido, ".$_SESSION['user']."</div>";
                        }  
                    ?>
                </div>
				<nav class="codrops-demos">
					<a href="index.php" class="current-demo">Inicio</a>
					<a href="buscador.php">Buscador de colegios</a>
					<a href="ayuda.html">Ayuda</a>
                    <a href="contacto.html">Contacto</a>
				</nav>
            </header>

            <section>				
                <div id="container_demo" >
                    <a class="hiddenanchor" id="toregister"></a>
                    <a class="hiddenanchor" id="tologin"></a>
                    <div id="wrapper">
                        <div id="login" class="animate form">

                            <!-- Formulario para loguearte en la aplicación  -->
                            <form  action="users/session_init.php" method="POST" autocomplete="on"> 
                                <!-- Validamos si el usuario está dado de alta en la plataforma  -->
                                <!-- Si no es posible encontrar el usuario alertamos al usuario  -->
                                <?php 
                                    if($err==1){
                                        echo '<script type="text/javascript">alert("Usuario o contraseña erróneos");</script>';
                                    }
                                    if($login==1){
                                        echo '<script type="text/javascript">alert("Ha sido dado de alta en el sistema");</script>';
                                    }
                                    if($login==5){
                                        echo '<script type="text/javascript">alert("No se ha podido dar de alta, el usuario ya existe");</script>';
                                    }
                                ?>

                                <!--<h1>Entrar</h1> -->
                                <p> 
                                    <label for="username" class="uname" data-icon="u" >Usuario</label>
                                    <input id="username" name="loginUsuario" required="required" type="text" placeholder="usuario"/>
                                </p>
                                <p> 
                                    <label for="password" class="youpasswd" data-icon="p">Contraseña</label>
                                    <input id="password" name="loginPassword" required="required" type="password" placeholder="contraseña" /> 
                                </p>
                                <p class="login button"> 
                                     <a id="salir" href='users/user/log_out.php'>Salir</a>
                                     <input type="submit" value="Entrar" />
								</p>
                                <p class="change_link">
									Si no eres miembro, date de alta.
									<a href="#toregister" class="to_register">Únete</a>
								</p>
                            </form>
                        </div>

                        <div id="register" class="animate form">

                            <!-- Formulario para registrarte en la aplicación  -->
                            <form  action="users/user/registro.php" method="POST" autocomplete="on"> 
                                <!--<h1>Entrar</h1> -->
                                <p> 
                                    <label for="usernamesignup" class="uname" data-icon="u">Usuario</label>
                                    <input id="usernamesignup" name="altaNombreUsuario" required="required" type="text" placeholder="usuario" />
                                </p>
                                <p> 
                                    <label for="emailsignup" class="youmail" data-icon="e" >Correo electrónico</label>
                                    <input id="emailsignup" name="altaEmail" required="required" type="email" placeholder="correo electrónico"/> 
                                </p>
                                <p> 
                                    <label for="passwordsignup" class="youpasswd" data-icon="p">Contraseña</label>
                                    <input id="passwordsignup" name="altaPassword" required="required" type="password" placeholder="contraseña"/>
                                </p>
                                <p> 
                                    <label for="passwordsignup_confirm" class="youpasswd" data-icon="p">Confirma tu contraseña</label>
                                    <input id="passwordsignup_confirm" name="altaPassword_confirm" required="required" type="password" placeholder="contraseña"/>
                                </p>
                                <p class="signin button"> 
									<input type="submit" value="Regístrate"/> 
								</p>
                                <p class="change_link">  
									¿Ya eres miembro?
									<a href="#tologin" class="to_register">Entra en la aplicación</a>
								</p>
                            </form>
                        </div>
						
                    </div>
                    <!-- Texto de saludo de la aplicación  -->  
                    <div class="textoPortada">
                        Te preguntarás, ¿Qué es Ubicole? Es una aplicación que te ayudará a encontrar los centros educativos más cercanos a tu ubicación.</br></br>
                        Sólo tienes que indicarnos desde donde empezar a buscar y el tiempo de viaje y la aplicación te indicará que colegios están en tu radio de búsqueda.
                        </br>
                        </br>
                        <div id="tituloCotiza"><b>Centros más buscados:</b></br></br></div>

                        <?php 

                            $conexion = $objConn->get_connected();

                            $query = "SELECT * FROM centro ORDER BY Count DESC LIMIT 5";

                            $resEmp = $objDb->select($query);

                            echo "<div class='coleCotiza'>";
                            
                            while ($rowEmp = mysql_fetch_assoc($resEmp)) {
                                echo "<div class='coleCotizaImgTxt'>";
                                $nombre = $rowEmp['DEspecifica'];
                                echo "<img src='images/star.png' style='padding-right:10px; float:left;'><div class='coleCotizaText'>".utf8_encode($nombre)."</div></br>";
                                echo "</div>";
                            }
                            echo "</div>";
                            
                        ?>

                        </br>
                        </br>
                        <a href="https://twitter.com/ubicole" class="twitter-follow-button" data-show-count="false">Follow @ubicole</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
                    </div>
                </div>

                <!-- DEFINIMOS EL PIE DE LA APLICACIÓN  -->  
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
            </section>

        </div>
    </body>
</html>

