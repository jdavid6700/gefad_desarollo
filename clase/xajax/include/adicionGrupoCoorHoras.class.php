<?

//======= Revisar si no hay acceso ilegal ==============
if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;
}
//======================================================
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/multiConexion.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");

    

    function nombreEstudiante($codEstudiante, $posicion) {

  if (!isset($codEstudiante) || is_null($codEstudiante) || $codEstudiante == "") {
    echo 'Por favor ingrese el código del estudiante';
    exit;
  }

  if (!is_numeric($codEstudiante)) {
    echo 'El valor ingresado debe ser numérico';
    exit;
  }
  require_once("clase/config.class.php");
  $esta_configuracion = new config();
  $configuracion = $esta_configuracion->variable();
  $funcion = new funcionGeneral();
  //Conectarse a la base de datos
  $acceso_db = new dbms($configuracion);
  $enlace = $acceso_db->conectar_db();
  //$valor = $acceso_db->verificar_variables($valor);

  $html = new html();
  $conexion = new multiConexion();
  $accesoOracle = $conexion->estableceConexion(75, $configuracion);

  if (is_resource($enlace)) {
    $busqueda = "SELECT DISTINCT est_nombre NOMBRE, cra_nombre PROYECTO, est_estado_est ESTADO, est_ind_cred MODALIDAD";
    $busqueda.=" FROM acest";
    $busqueda.=" INNER JOIN accra ON acest.est_cra_cod=accra.cra_cod";
    $busqueda.=" INNER JOIN acestado ON estado_cod=est_estado_est";
    $busqueda.=" WHERE est_cod=" . $codEstudiante;
    $busqueda.=" AND estado_activo like '%S%'";

    $resultado = $funcion->ejecutarSQL($configuracion, $accesoOracle, $busqueda, "busqueda");

    if (is_array($resultado)) {
      switch (trim($resultado[0]['MODALIDAD'])) {
        case 'N':
          $modalidad = 'HORAS';
          break;

        case 'S':
          $modalidad = 'CRÉDITOS';
          break;

        default :
          break;
      }
      $mi_cuadro = $resultado[0]['NOMBRE'];
      $mi_cuadro.="&nbsp;&nbsp;&nbsp; <b>Estado: " . $resultado[0]['ESTADO'] . "</b><br>";
      $mi_cuadro.=htmlentities(utf8_decode($resultado[0]['PROYECTO']));
      $mi_cuadro.="&nbsp;&nbsp;&nbsp; <b>Modalidad: " . $modalidad . "</b><br>";
    } else if ($codEstudiante == '') {
      $mi_cuadro = "<font color=red>POR FAVOR DIGITE UN C&Oacute;DIGO QUE CORRESPONDA A UN ESTUDIANTE ACTIVO.</font>";
    } else {
      $mi_cuadro = "<font color=red>EL C&Oacute;DIGO INGRESADO NO CORRESPONDE A UN ESTUDIANTE ACTIVO.</font>";
    }


    $respuesta = new xajaxResponse();
    $respuesta->addAssign("div_nombreEstudiante" . $posicion, "innerHTML", $mi_cuadro);
    return $respuesta;
  }
}

?>