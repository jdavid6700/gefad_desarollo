<?PHP
include_once("../clase/multiConexion.class.php");

	$esta_configuracion=new config();
	$configuracion=$esta_configuracion->variable("../"); 

	$usuario = $_SESSION['usuario_login'];
	$carrera = $_SESSION['carrera'];



	$conexion=new multiConexion();
	$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);

	//FECHA ACTUAL
	$QryHoy = "SELECT TO_NUMBER(to_char(SYSDATE,'YYYYMMDD')) FROM dual";
	$registro=$conexion->ejecutarSQL($configuracion,$accesoOracle,$QryHoy,"busqueda");
	$Hoy = $registro[0][0];

	//ULTIMA MODIFICACION DE LA CLAVE
	$QryMod = "SELECT DISTINCT(MAX(TO_NUMBER(TO_CHAR(cla_fecha,'YYYYMMDD'))))
		   FROM mntge.geclaveslog
		   WHERE cla_codigo = ".$_SESSION['usuario_login'];
		   //echo "<br>".$QryMod;

	$registro=$conexion->ejecutarSQL($configuracion,$accesoOracle,$QryMod,"busqueda");
	$Mod = $registro[0][0];
	$Dia = $Hoy-$Mod;

	//NUMERO DE VISITANTES A CONDOR
	$QryTot = "SELECT count(CNX_USUARIO) FROM geconexlog";
	$registro=$conexion->ejecutarSQL($configuracion,$accesoOracle,$QryTot,"busqueda");
	$Tot = $registro[0][0];

	//NUMERO DE VISITAS A CONDOR POR USUARIO
	$QryNro ="SELECT NVL(count(CNX_USUARIO ),1) 
		  FROM geconexlog 
		  WHERE CNX_USUARIO = ".$_SESSION['usuario_login'];

	$registro=$conexion->ejecutarSQL($configuracion,$accesoOracle,$QryNro,"busqueda");
	$Nro =  $registro[0][0];
	$Res = ($Nro%10);


?>
