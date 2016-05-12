<?PHP
$QryCra = OCIParse($oci_conecta, "SELECT trim(cra_nombre)
									FROM accra
								   WHERE cra_cod = $carrera
									 AND cra_estado = 'A'");
OCIExecute($QryCra) or die(Ora_ErrorCode());
$RowNombre = OCIFetch($QryCra);
$Carrera = OCIResult($QryCra, 1);
cierra_bd($QryCra, $oci_conecta);
?>