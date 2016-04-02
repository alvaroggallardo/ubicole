<?php
/**
 * Este archivo será llamado para añadir un token más a la búsqueda
 *
 * @author alvaroggallardo
 * @copyright 2015
 * @license ruta/a/license.txt GPL
 */

//llamada a la clase que hace la conexión a la base de datos
//En este archivo es donde configuramos la conexión a la bbdd
require'../users/class/config.php';

//llamada a la clase que ejecutará los queries 
require'../users/class/dbactions.php';

// Cargamos la clase que maneja las sesiones en la aplicación
require'../users/class/sessions.php';
//Creamos un objeto de la clase sessions
$objses = new Sessions();
//Lo inicializamos
$objses->init();

//Guardamos el código de colegio
$codigoCol = $_GET["data"];

//creación o instanciamiento de un objeto de la Clase Connection
$objConn = new Connection();
$objDb = new Database();

//Conexión con la base de datos
$conexion = $objConn->get_connected();
//Esta consulta actualiza en 1 el campo count de la tabla centros
$query1 = "UPDATE centro SET Count = Count + 1 WHERE Codigo = ".(int)$codigoCol;

$result = $objDb->select($query1);
//Guardamos el usuario, en string y como usuario, ej: alvareto5, aido,...
$usuario = $_SESSION["user"];

//Miramos que id tiene el usuario que hemos guardado
$query3 = mysql_query("SELECT idUsuario FROM users WHERE usuario = '$usuario'");
//Debemos hacerlo con fetch xk nos devuelve un puntero en forma de tabla al resultado, no el resultado en si mismo
$row = mysql_fetch_array($query3);
//Aislamos el resultado de la consulta que deseamos
$result2 = $row['idUsuario'];

echo $result2;
//Añadimos a la tabla busquedaUsuarios el resultado obtenido por el usuario en cada búsqueda
$query2 = "INSERT INTO busquedausuarios VALUES(".(int)$result2.",".(int)$codigoCol.")";

$objDb->select($query2);



?>