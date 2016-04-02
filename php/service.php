<?php

/**
 * Resuelve las peticiones de información sobre centros educativos a la BD
 *
 * @author alvaroggallardo
 * @copyright 2015
 * @license ruta/a/license.txt GPL
 */

//En este archivo es donde configuramos la conexión a la bbdd
require'../users/class/config.php';

//llamada a la clase que ejecutará los queries 
require'../users/class/dbactions.php';


$idCentro = $_GET["data"];

class Colegio {

	public $objConn;
	public $objDb;
	public $conexion; 
	public $result;

	public $nombre;
	public $telefono;
	public $localidad;
	public $calle;
	public $web;
	public $internado;
	public $concierto;
	public $jornadaContinua;
	public $comedor;
	public $transporte;

	public function __construct(){

		//creación o instanciamiento de un objeto de la Clase Connection
		$this->objConn = new Connection();
		$this->objDb = new Database();

		$this->conexion = $this->objConn->get_connected();
		
	}


	public function getNombre($idCentro){

		$query = "SELECT DEspecifica FROM centro WHERE Codigo = ".(int)$idCentro;

		$this->result = $this->objDb->select($query);

		while ($rowEmp = mysql_fetch_assoc($this->result)) {
			$this->nombre = $rowEmp['DEspecifica'];
		}

		echo "<div class='paresPopup'><div class='textoPopup'><img src='images/icon/arrow.png'><b>Nombre:</div><div class='respuestaPopup'>".utf8_encode($this->nombre)."</b></div></div>";

	}

	public function getLocalidad($idCentro){

		$query = "SELECT Localidad FROM centro WHERE Codigo = ".(int)$idCentro;

		$this->result = $this->objDb->select($query);

		while ($rowEmp = mysql_fetch_assoc($this->result)) {
			$this->localidad = $rowEmp['Localidad'];
		}

		echo "<div class='paresPopup'><div class='textoPopup'><img src='images/icon/arrow.png'><b>Localidad:</div><div class='respuestaPopup'>".utf8_encode($this->localidad)."</b></div></div>";

	}

	public function getCalle($idCentro){

		$query = "SELECT NombreVia FROM centro WHERE Codigo = ".(int)$idCentro;

		$this->result = $this->objDb->select($query);

		while ($rowEmp = mysql_fetch_assoc($this->result)) {
			$this->calle = $rowEmp['NombreVia'];
		}

		echo "<div class='paresPopup'><div class='textoPopup'><img src='images/icon/arrow.png'><b>Nombre calle:</div><div class='respuestaPopup'>".utf8_encode($this->calle)."</b></div></div>";


	}


	public function getTelefono($idCentro){

		$query = "SELECT Telefono FROM centro WHERE Codigo = ".(int)$idCentro;

		$this->result = $this->objDb->select($query);

		while ($rowEmp = mysql_fetch_assoc($this->result)) {
			$this->telefono = $rowEmp['Telefono'];
		}

		echo "<div class='paresPopup'><div class='textoPopup'><img src='images/icon/arrow.png'><b>Teléfono:</div><div class='respuestaPopup'>".utf8_encode($this->telefono)."</b></div></div>";

	}

	public function getWeb($idCentro){

		$query = "SELECT Web FROM centro WHERE Codigo = ".(int)$idCentro;

		$this->result = $this->objDb->select($query);

		while ($rowEmp = mysql_fetch_assoc($this->result)) {
			$this->web = $rowEmp['Web'];
		}

		if($this->web!=""){
			echo "<div class='paresPopup'><div class='textoPopup'><img src='images/icon/arrow.png'><b>Web:</div><div class='respuestaPopup'><a href='".utf8_encode($this->web)."' target='_blank'>".utf8_encode($this->web)."</a></b></div></div>";
		}
		else{
			echo "<div class='paresPopup'><div class='textoPopup'><img src='images/icon/arrow.png'><b>Web:</div><div class='respuestaPopup'>No disponible</b></div></div>";
		}
	}

	public function getInternado($idCentro){

		$query = "SELECT Internado FROM centro WHERE Codigo = ".(int)$idCentro;

		$this->result = $this->objDb->select($query);

		while ($rowEmp = mysql_fetch_assoc($this->result)) {
			$this->internado = $rowEmp['Internado'];
		}

		echo "<div class='paresPopup'><div class='textoPopup'><img src='images/icon/arrow.png'><b>Internado:</div><div class='respuestaPopup'>".utf8_encode($this->internado)."</b></div></div>";

	}

	public function getConcierto($idCentro){

		$query = "SELECT Concierto FROM centro WHERE Codigo = ".(int)$idCentro;

		$this->result = $this->objDb->select($query);

		while ($rowEmp = mysql_fetch_assoc($this->result)) {
			$this->concierto = $rowEmp['Concierto'];
		}


		echo "<div class='paresPopup'><div class='textoPopup'><img src='images/icon/arrow.png'><b>Concierto:</div><div class='respuestaPopup'>".utf8_encode($this->concierto)."</b></div></div>";

	}

	public function getJornadaContinua($idCentro){

		$query = "SELECT jornadaContinua FROM centro WHERE Codigo = ".(int)$idCentro;

		$this->result = $this->objDb->select($query);

		while ($rowEmp = mysql_fetch_assoc($this->result)) {
			$this->jornadaContinua = $rowEmp['jornadaContinua'];
		}

		echo "<div class='paresPopup'><div class='textoPopup'><img src='images/icon/arrow.png'><b>Jornada contínua:</div><div class='respuestaPopup'>".utf8_encode($this->jornadaContinua)."</b></div></div>";

	}

	public function getComedor($idCentro){

		$query = "SELECT Comedor FROM centro WHERE Codigo = ".(int)$idCentro;

		$this->result = $this->objDb->select($query);

		while ($rowEmp = mysql_fetch_assoc($this->result)) {
			$this->comedor = $rowEmp['Comedor'];
		}

		echo "<div class='paresPopup'><div class='textoPopup'><img src='images/icon/arrow.png'><b>Comedor:</div><div class='respuestaPopup'>".utf8_encode($this->comedor)."</b></div></div>";

	}

	public function getTransporte($idCentro){

		$query = "SELECT Transporte FROM centro WHERE Codigo = ".(int)$idCentro;

		$this->result = $this->objDb->select($query);

		while ($rowEmp = mysql_fetch_assoc($this->result)) {
			$this->transporte = $rowEmp['Transporte'];
		}

		echo "<div class='paresPopup'><div class='textoPopup'><img src='images/icon/arrow.png'><b>Transporte:</div><div class='respuestaPopup'>".utf8_encode($this->transporte)."</b></div></div>";

	}

}


$colegio = new Colegio();

//echo utf8_encode($nombre);
echo $colegio->getNombre($idCentro);
echo $colegio->getLocalidad($idCentro);
echo $colegio->getCalle($idCentro);
echo $colegio->getTelefono($idCentro);
echo $colegio->getWeb($idCentro);
echo $colegio->getInternado($idCentro);
echo $colegio->getConcierto($idCentro);
echo $colegio->getJornadaContinua($idCentro);
echo $colegio->getComedor($idCentro);
echo $colegio->getTransporte($idCentro);


?>