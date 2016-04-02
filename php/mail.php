<?php
if(isset($_POST['emailContact'])) {

	$email_to = "agallardotsyl@gmail.com";
	$email_subject = "Contacto desde UBICOLE";


	$email_message = "Detalles del formulario de contacto:\n\n";
	$email_message .= "Nombre: " . $_POST['username'] . "\n";
	$email_message .= "E-mail: " . $_POST['emailContact'] . "\n";
	$email_message .= "Comentarios: " . $_POST['comentario'] . "\n\n";


	// Ahora se envía el e-mail usando la función mail() de PHP
	$headers = 'From: '."Ubicole"."\r\n".
	'Reply-To: '.$_POST['emailContact']."\r\n" .
	'X-Mailer: PHP/' . phpversion();

	//@mail($email_to, $email_subject, $email_message, $headers);
	mail($email_to,$email_subject,$email_message);

	header('Location: ../contacto.html'); 
}
?>