<?

/* --------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
  --------------------------------------------------------------------------------------------------------------------------- */
/* --------------------------------------------------------------------------------------------------------------------------
 * @name          bloque.php 
 * @author        Violeta Sosa
 * @revision      Última revisión 02 de agosto de 2013
  /*--------------------------------------------------------------------------------------------------------------------------
 * @subpackage		bloqueAdminVinculacion
 * @package		bloques
 * @copyright    	Universidad Distrital Francisco Jose de Caldas
 * @version      	0.0.0.1 - Julio 30 de 2013
 * @author	
 * @author		Oficina Asesora de Sistemas
 * @link		N/D
 * @description  	Bloque para gestionar formularios de cuotas partes.
 *                      Implementa:

  Despliegue formulario cuotas partes registro historia laboral
 *                      Registro información de formularios
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
class bloque_formHistoria extends bloque {

    private $configuracion;

    public function __construct($configuracion) {
        $this->configuracion = $configuracion;
        $this->sql = new sql_formHistoria();
        $this->funcion = new funciones_formHistoria($configuracion, $this->sql);
        $this->cripto = new encriptar();
    }

    function html() {
        if (isset($_REQUEST['opcion'])) {
            $accion = $_REQUEST['opcion'];

            switch ($accion) {

                case "interrupcion":

                    $datos_interrupcion = array();
                    foreach ($_REQUEST as $key => $value) {
                        $datos_interrupcion[$key] = $_REQUEST[$key];
                    }

                    $this->funcion->nuevaInterrupcion($datos_interrupcion);
                    break;

                case "modificarInterrupcion":
                    $interrupcion = unserialize($_REQUEST['interrupcion']);
                    $this->funcion->modificarInterrupcion($interrupcion);
                    break;

                case "dbasicoHistoria":
                    $this->funcion->dbasicoHistoria();
                    break;

                case "mostrarHistoria":

                    $cedula = $_REQUEST['cedula_emp'];
                    if (!preg_match("^\d+$^", $cedula)) {
                        echo "<script type=\"text/javascript\">" .
                        "alert('La cédula posee un formato inválido');" .
                        "</script> ";
                        error_log('\n');
                        $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                        $variable = 'pagina=formHistoria';
                        $variable.='&opcion=';
                        $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                        echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                        exit;
                    } else {
                        $this->funcion->mostrarHistoria($cedula);
                    }
                    break;

                case "registrarHistoria":

                    $cedula = $_REQUEST['cedula_emp'];
                    if (!preg_match("^\d+$^", $cedula)) {
                        echo "<script type=\"text/javascript\">" .
                        "alert('La cédula posee un formato inválido');" .
                        "</script> ";
                        error_log('\n');
                        $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                        $variable = 'pagina=formHistoria';
                        $variable.='&opcion=';
                        $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                        echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                        exit;
                    } else {
                        $this->funcion->mostrarFormulario($cedula);
                    }
                    break;

                case "modificar":
                    $historia_laboral = unserialize($_REQUEST['historia']);
                    $this->funcion->modificarHistoria($historia_laboral);
                    break;

                default :
                    $this->funcion->dbasicoHistoriaR();
                    break;
            }
        } else {
            $accion = "inicio";
            $this->funcion->mostrarFormulario();
        }
    }

    function action() {

        switch ($_REQUEST['opcion']) {
            case "registrarHistoria":
                $registro_historia = array();
                foreach ($_REQUEST as $key => $value) {
                    if ($key != 'action' && $key != 'opcion') {
                        $registro_historia[$key] = $_REQUEST[$key];
                    }
                }

                $this->funcion->procesarFormulario($registro_historia);
                break;

            case "actualizarHistoria":
                $historia_antigua = unserialize($_REQUEST['historia_completa']);

                $actualizar_historia = array();
                foreach ($_REQUEST as $key => $value) {
                    if ($key != 'action' && $key != 'opcion') {
                        $actualizar_historia[$key] = $_REQUEST[$key];
                    }
                }

                $this->funcion->actualizarFormulario($actualizar_historia, $historia_antigua);
                break;

            case "registrarInterrupcion":

                $registro_interrupcion = array();
                foreach ($_REQUEST as $key => $value) {
                    if ($key != 'action' && $key != 'opcion') {
                        $registro_interrupcion[$key] = $_REQUEST[$key];
                    }
                }
                $this->funcion->procesarFormularioInterrupcion($registro_interrupcion);
                break;

            case "actualizarInterrupcion":
                
                $actualizar_interrupcion = array();
                foreach ($_REQUEST as $key => $value) {
                    if ($key != 'action' && $key != 'opcion') {
                        $actualizar_interrupcion[$key] = $_REQUEST[$key];
                    }
                }

                $this->funcion->procesarFormularioInterrupcion_Modificar($actualizar_interrupcion);
                break;

            default :
                $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                $variable = "pagina=reportesCuotas";
                $variable .= "&opcion=";
                $variable = $this->funcion->cripto->codificar_url($variable, $this->configuracion);
                echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                break;
        }
    }

}

// @ Crear un objeto bloque especifico
$esteBloque = new bloque_formHistoria($configuracion);
//echo var_dump($_REQUEST);exit;
//"blouqe ".$_REQUEST['action'];exit;


if (!isset($_REQUEST['action'])) {
    $esteBloque->html();
} else {

    $esteBloque->action();
}
?>