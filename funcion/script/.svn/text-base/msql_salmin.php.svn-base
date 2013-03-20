<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'conexion.php');
require_once(dir_conect.'cierra_bd.php');

$salmin = OCIParse($oci_conecta, "SELECT smi_valor FROM acsalmin WHERE smi_estado='A'");
OCIExecute($salmin) or die(Ora_ErrorCode());
$row_min = OCIFetch($salmin);
	
$vlr_salmin = OCIResult($salmin, 1);
$med_salmin = OCIResult($salmin, 1)/2;

cierra_bd($salmin,$oci_conecta);
?>