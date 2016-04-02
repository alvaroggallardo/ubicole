<?php
//Este archivo es el utilizado para terminar la sesión de usuario.

require'../class/sessions.php';

//Creamos un objeto de sesión
$objses = new Sessions();
$objses->init();

//Llamamos al método que destruye la sesión
$objses->destroy();

//Redirigimos al usuario a la página de inicio
header('Location: ../../index.php');

?>