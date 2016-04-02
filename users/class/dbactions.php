<?php

/**
 * Esta clase realizará las consultas a la bddd
 *
 * @author alvaroggallardo
 * @copyright 2015
 */ 

class Database{
	
	public $result;
	
	public function __construct(){ }

	/**
	* Realiza las consultas contra la bbdd
	*
	* @return Resultado de la consulta como un objeto
	* @param string $query consulta a la bbdd
	*/
	
	public function select($query){
		return $this->result = mysql_query($query);
	}
	
}

?>