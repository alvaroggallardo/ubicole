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

$codigoCol = $_GET["data"];
echo "Esto es codigo col ".$codigoCol;

//creación o instanciamiento de un objeto de la Clase Connection
$objConn = new Connection();
$objDb = new Database();

//Conexión con la base de datos
$conexion = $objConn->get_connected();

$query = "UPDATE centro SET Count = Count + 1 WHERE Codigo = ".(int)$codigoCol;

$result = $objDb->select($query);





?>