<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);

$QryNivelCra = "SELECT cra_abrev,tra_nivel
		FROM actipcra, accra
		WHERE cra_cod = $carrera
		AND cra_tip_cra = tra_cod";
$RowNombre = $conexion->ejecutarSQL($configuracion,$accesoOracle,$QryNivelCra,"busqueda");
$Carrera = $RowNombre[0][0];
$Nivel = $RowNombre[0][1];
?>