<?php
/**
 * Esta clase maneja las sesiones en la aplicaciones
 *
 * @author alvaroggallardo
 * @copyright 2015
 * @license ruta/a/license.txt GPL
 */
class Sessions{
	//A partir de PHP5 las clases se inicializan definiendo un constructor de esta manera
	public function __construct(){ }

	public function init(){
		//Iniciar una nueva sesión o reanudar la existente
		@session_start();
	}
	//
	public function set($varname, $value){
		//Establecemos el nombre del usuario como parámetro de la sesión
		$_SESSION[$varname] = $value;
		
	}
	//Este método elimina las variables de sesión
	public function destroy(){
		//Libera todas las variables de sesión
		session_unset();
		//Destruye toda la información registrada de una sesión
		session_destroy();
		
	}
	
}

?>