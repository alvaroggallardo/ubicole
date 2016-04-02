<?php

//llamada a la clase que hace la conexión a la base de datos
//En este archivo es donde configuramos la conexión a la bbdd
require'../class/config.php';

//llamada a la clase que ejecutará los queries 
require'../class/dbactions.php';

//creación o instanciamiento de un objeto de la Clase Connection
$objConn = new Connection();
$objDb = new Database();

//llamamos la funcion que nos conecta a la base de datos
//Conexión con la base de datos
$conexion = $objConn->get_connected();

//Recuperamos los valores introducidos por el usuario
$usuario = $_POST["altaNombreUsuario"];
$email = $_POST["altaEmail"];
$password = $_POST["altaPassword"];
$passwordConfirm = $_POST["altaPassword_confirm"];

//Confirmamos las contraseñas
if($password!=$passwordConfirm){
	die ("Las contraseñas no coinciden");
}

//Encriptamos la contraseña
$passwordUsuario = md5($password);

//Comprobamos que el usuario no esté ya en la base de datos
$query = "SELECT * FROM users WHERE usuario='".$usuario."'";
$consultaUsuario = $objDb->select($query);

if(mysql_num_rows($consultaUsuario)==0){ //Está disponible

	//Encriptamos la contraseña
	$passwordUsuario = md5($password);

	$query = "INSERT INTO users (usuario,email,password,idprofile)VALUES('".$usuario."','".$email."','".$passwordUsuario."','1')";

	$result = $objDb->select($query);

	header('Location: ../../index.php?login=1'); 
} else {
	header('Location: ../../index.php?login=5'); 
}


?>