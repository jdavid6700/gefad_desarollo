<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'conexion.php');
require_once(dir_conect.'cierra_bd.php');

$estcod = $_SESSION['usuario_login'];

$diferido = OCIParse($oci_conecta, "SELECT est_diferido FROM acest WHERE est_cod=$estcod");
OCIExecute($diferido) or die(Ora_ErrorCode());
$row_min = OCIFetch($diferido);
	
$dat_diferido = OCIResult($diferido, 1);
	
cierra_bd($diferido,$oci_conecta);
?>