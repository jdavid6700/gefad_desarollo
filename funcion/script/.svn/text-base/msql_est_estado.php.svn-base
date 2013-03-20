<?PHP
$estado = OCIParse($oci_conecta, "SELECT est_estado_est,estado_nombre 
	  								FROM acest, acestado 
								   WHERE est_cod = $usuario 
									 AND estado_cod = est_estado_est ");
OCIExecute($estado) or die(Ora_ErrorCode());
$rowest = OCIFetch($estado);
if(OCIResult($estado,2) != 'ACTIVO'){
   $Estilo = 'Estilo10';
   $Estado = 'Estado: '.OCIResult($estado,2);
}
else{
	 $Estilo = 'Estilo5';
     $Estado = 'Estado: '.OCIResult($estado,2);
}
OCIFreeCursor($estado);
?>