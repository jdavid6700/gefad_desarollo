<?php 
function fu_envia_clave($nom, $email,$email_ins, $user, $clave, $tipo)
{
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

	if($tipo == 4)
	{
		$tip = "Coordinador";
	}
	elseif($tipo == 16)
	{
		$tip = "Decano";
	}
	elseif($tipo == 24)
	{
		$tip = "Funcionario";
	}
	elseif($tipo == 30)
	{
		$tip = "Docente";
	}
	elseif($tipo == 51)
	{
		$tip = "Estudiante";
	}
	   
	$fecha = date("d-M-Y  h:i:s A");
	$comen = "Mensaje generado autom&aacute;ticamente por el servidor de la Oficina Asesora de Sistemas.\n";
	$comen.= "Este es su usuario y clave para ingresar al Sistema de Informaci&oacute;n C&oacute;ndor.\n\n";
	$comen.= "Por seguridad cambie la clave.\n\n";
	$sujeto= "Clave";
	$cuerpo="Fecha de envio: " .$fecha."\n\n";
	$cuerpo.="Se&ntilde;or(a)      : " .$nom."\n\n";
	$cuerpo.=$comen. "\n\n";
	$cuerpo.="Tipo:           " .$tip. "\n";
	$cuerpo.="Usuario:        " .$user. "\n";
	$cuerpo.="Clave Acceso:   " .$clave."\n";
 
	$mail->Body    = $cuerpo;
	$mail->Subject = $sujeto;
	$mail->AddAddress($email);
	$mail->AddCC($email_ins);
		 
	if(!$mail->Send())
	{
		header("Location: $redir?error_login=16");
	}
	else
	{
		header("Location: $redir?error_login=18");
	}
	$mail->ClearAllRecipients();
}
?>