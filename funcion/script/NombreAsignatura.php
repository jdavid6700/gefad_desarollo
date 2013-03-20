<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_script.'fu_cabezote.php');
require_once(dir_conect.'fu_tipo_user.php');
require_once(dir_script.'msql_ano_per.php');
include_once("../clase/multiConexion.class.php");


fu_tipo_user(51);


	$conexion=new multiConexion();
	$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);

	
	$NombreAsignatura= "SELECT trim(asi_nombre) 
		FROM acasi 
		WHERE asi_cod = $asicod
		AND asi_estado = 'A'";
	
	$registro=$conexion->ejecutarSQL($configuracion,$accesoOracle,$NombreAsignatura,"busqueda");
	$Asignatura = $registro[0][0];	


?>
