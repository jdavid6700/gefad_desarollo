<?PHP
require(dir_conect.'conexion.php');
$QryFac = OCIParse($oci_conecta, "SELECT trim(dep_nombre)
									FROM gedep, accra
								   WHERE dep_cod = cra_dep_cod
									 AND cra_cod = ".$_SESSION['carrera']);
OCIExecute($QryFac) or die(Ora_ErrorCode());
$RowNombre = OCIFetch($QryFac);
$Facultad = OCIResult($QryFac, 1);
$_SESSION['fac'] = $Facultad;
cierra_bd($QryFac, $oci_conecta);
?>