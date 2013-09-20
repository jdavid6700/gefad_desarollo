<?

/* --------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
  --------------------------------------------------------------------------------------------------------------------------- */
/* --------------------------------------------------------------------------------------------------------------------------
 * @name          bloque.php 
 * @author        Paulo Cesar Coronado
 * @revision      Última revisión 12 de enero de 2009
  /*--------------------------------------------------------------------------------------------------------------------------
 * @subpackage		bloqueAdminVinculacion
 * @package		bloques
 * @copyright    	Universidad Distrital Francisco Jose de Caldas
 * @version      	0.0.0.1 - Junio 18 de 2013
 * @author	
 * @author		Oficina Asesora de Sistemas
 * @link		N/D
 * @description  	Bloque para gestionar información tributaria.
 *                      Implementa:

 *                      Recuperación información básica empleados
 */
/* -------------------------------------------------------------------------------------------------------------------------- */


if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}
//echo "<br>action ".$_REQUEST['action'];
//echo "<br>opcion ".$_REQUEST['opcion'];
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/bloque.class.php");
include_once("sql.class.php");
include_once("funcion.class.php");

//Clase
class bloque_adminVinculacion extends bloque {

    private $configuracion;

    public function __construct($configuracion) {
        $this->configuracion = $configuracion;
        $this->sql = new sql_adminVinculacion();
        $this->funcion = new funciones_adminVinculacion($configuracion, $this->sql);
    }

    function html() {
        if (isset($_REQUEST['opcion'])) {
            $accion = $_REQUEST['opcion'];

            switch ($accion) {
                case "verificar":
                    $this->funcion->mostrarInicio();
                    $this->funcion->historialVinculacion();
                    break;

                default :
                    $this->funcion->mostrarInicio();
                    $this->funcion->historialVinculacion();
                    break;
            }
        } else {
            $accion = "inicio";
            $this->funcion->mostrarInicio();
        }
    }

    function action() {

        switch ($_REQUEST['opcion']) {
            case "guardarDatos":

                $respuestas_encuesta = array();

                foreach ($_REQUEST as $key => $value) {
                    if ($key != 'action' && $key != 'opcion') {
                        $respuestas_encuesta[$key] = $_REQUEST[$key];
                    }
                }

                $this->funcion->insertarDatos($respuestas_encuesta);
                break;

            case "actualizar":

                $datos_actualizar = array();

                foreach ($_REQUEST as $key => $value) {
                    if ($key != 'action' && $key != 'opcion') {
                        $datos_actualizar[$key] = $_REQUEST[$key];
                    }
                }

                $this->funcion->actualizarDatosBasicos($datos_actualizar);
                break;

            case "actualizarRespuestas":

                $respuestas_encuesta_a = array();

                foreach ($_REQUEST as $key => $value) {
                    if ($key != 'action' && $key != 'opcion') {
                        $respuestas_encuesta_a[$key] = $_REQUEST[$key];
                    }
                }

                $this->funcion->actualizarRespuestas($respuestas_encuesta_a);
                break;



            default :
                $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                $variable = "pagina=asistenteTributario";
                $variable .= "&opcion=";
                $variable = $this->funcion->cripto->codificar_url($variable, $this->configuracion);
                echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                break;
        }
    }

}

// @ Crear un objeto bloque especifico
$esteBloque = new bloque_adminVinculacion($configuracion);
//echo var_dump($_REQUEST);exit;
//"blouqe ".$_REQUEST['action'];exit;


if (!isset($_REQUEST['action'])) {
    $esteBloque->html();
} else {

    $esteBloque->action();
}
?>