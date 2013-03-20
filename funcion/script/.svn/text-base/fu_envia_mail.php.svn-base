<?php 
function fu_envia_mail($docnom, $email){
	require_once("class.phpmailer.php");
	$mail = new phpmailer();

	$mail->From     = "computo@udistrital.edu.co";
	$mail->FromName = "Oficina Asesora de Sistemas";
	$mail->Host     = "mail.udistrital.edu.co";
	$mail->Mailer   = "smtp";
	$mail->SMTPAuth = true;
	$mail->Username = "computo@udistrital.edu.co";
	$mail->Password = "oas20021";
	$mail->Timeout  = 120;
	$mail->Charset  = "utf-8";
	$mail->IsHTML(false);

	$fecha = date("d-M-Y  h:i:s A");
	$comen = "Mensaje enviado por la Oficina Asesora de Sistemas.\n";
	$comen .= "Ahora puede ingresar al Sistema de Información Académica CÓNDOR desde internet \n";
	$comen .= "Con el sistema CÓNDOR los docentes pueden ingresar sus notas, actualizar sus \n";
	$comen .= "datos personales y realizar su autoevaluación.\n\n";
	$comen .= "El ingreso debe hacerse a través del Portal de la Universidad:";
	$comen .= "http://www.udistrital.edu.co \n\n";
	$comen .= "Haciendo clic en el icono. <a href='http://condor.udistrital.edu.co:7777/oas/index.php'><img src='http://www.udistrital.edu.co/imagenes/condor.gif' border='0' width='43' height='32'></a> \n\n";

	$sujeto = "Mensaje OAS";
	$cuerpo .= "Fecha de envio: " .$fecha."\n\n";
	$cuerpo .= "Profesor(a):    " .$docnom."\n\n";
	$cuerpo .= $comen. "\n";
	 
	$mail->Body    = $cuerpo;
	$mail->Subject = $sujeto;
	$mail->AddAddress($email);
	 
	if(!$mail->Send()){
	   header("Location: $redir?error_login=16");
	}
	else{
		 header("Location: $redir?error_login=18");
	}
	$mail->ClearAllRecipients();
}
?>