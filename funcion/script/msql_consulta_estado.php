<?php
//Llamado de est_pag_principal.php
$consulta = OCIParse($oci_conecta, "SELECT est_estado_est,estado_nombre
  									  FROM acest, acestado
 									 WHERE est_cod = ".$_SESSION['usuario_login']."
   									   AND estado_cod = est_estado_est
   									   AND est_estado_est NOT IN('A','B','H','J','L','T','V')");
OCIExecute($consulta) or die(Ora_ErrorCode());
$row = OCIFetch($consulta);
if($row != 0){
   $estado = OCIResult($consulta, 2);
   $msg = 'A los estudiantes que aún no han oficializado matricula, no se les desplegará ninguna información.';
} else { 
		$msg = 'A los estudiantes que aún no han oficializado matricula, no se les desplegará ninguna información.'; }
?>