<?php

//llamada a la clase que hace la conexión a la base de datos
require'class/config.php';
//llamada a la clase Usuarios para realizar el inicio de sesion
require'class/users.php';
//llamada a la clase que ejecutará los queries de Consulta, Adición y Eliminación
require'class/dbactions.php';
//llamada a la clase encargada de las sesiones
require'class/sessions.php';

//creación o instanciamiento de un objeto de la Clase Connection
$objConn = new Connection();
//objeto de la clase users
$objUser = new Users();

//llamamos la funcion que nos conecta a la base de datos
$objConn->get_connected();
//function que realiza la verificación de usuarios e inicio de sesion
$objUser->login_in();



?>