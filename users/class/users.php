<?php

/**
 * Esta clase realiza el login de usuario en el sistema comparando lo que introduce el usuario y lo que hay en la base de datos
 *
 * @author alvaroggallardo
 * @copyright 2015
 */ 

class Users{
	
	public $objDb;
	public $objSe;
	public $result;
	public $rows;
	public $useropc;
	
	public function __construct(){
		//Creamos el objeto para conectarnos a la base de datos
		$this->objDb = new Database();
		//Creamos el objeto que maneja las sesiones
		$this->objSe = new Sessions();
		
	}
	
	public function login_in(){
		//Encriptamos la clave de usuario, ya que así se guarda en la base de datos
		$passwordDecode = md5($_POST["loginPassword"]);

		//Consultamos si existe ese usuario y contraseña
		$query = "SELECT * FROM users, profiles WHERE users.usuario = '".$_POST["loginUsuario"]."' 
			AND users.password = '$passwordDecode' AND users.idprofile = profiles.idProfile ";

		//Este objeto devuelve el resultado de la consulta
		$this->result = $this->objDb->select($query);
		//Consultamos el número de resultados
		$this->rows = mysql_num_rows($this->result);
		//Si la consulta devuelve algún resultado
		if($this->rows > 0){
			//Extraemos los resultados
			if($row=mysql_fetch_array($this->result)){
				//Creamos las variables de sesion que correspondan
				$this->objSe->init();
				$this->objSe->set('user', $row["usuario"]);
				$this->objSe->set('iduser', $row["idUsuario"]);
				$this->objSe->set('idprofile', $row["idprofile"]);
				
				$this->useropc = $row["usuario"];

				//Redirigimos a la página de inicio
				header('Location: ../index.php');

			}
			
		}else{
			//Cargamos de nuevo la página de incio pero alertándo mediante la variable de que se produjo un error de logueo
			header('Location: ../index.php?error=1');
		}
		
	}
	
}

?>