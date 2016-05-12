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
class bloque_formConcurrencia extends bloque {

    private $configuracion;

    public function __construct($configuracion) {
        $this->configuracion = $configuracion;
        $this->sql = new sql_formConcurrencia();
        $this->funcion = new funciones_formConcurrencia($configuracion, $this->sql);
        $this->cripto = new encriptar();
    }

    function html() {
        if (isset($_REQUEST['opcion'])) {
            $accion = $_REQUEST['opcion'];

            switch ($accion) {

                case "historiaConcurrencia":

                    $cedula = $_REQUEST['cedula_emp'];

                    if (!preg_match("^\d+$^", $cedula)) {
                        echo "<script type=\"text/javascript\">" .
                        "alert('La cédula no posee un formato válido');" .
                        "</script> ";
                        $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                        $variable = 'pagina=reportesCuotas';
                        $variable.='&opcion=';
                        $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                        echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                        exit;
                    } else {
                        $this->funcion->mostrarPrevisoras($cedula);
                    }
                    break;

                case "formulario":
                    $this->funcion->mostrarFormulario();
                    break;

                case "modificarConcurrencia":
                    $concurrencia_registrada = unserialize($_REQUEST['concurrencia']);
                    $this->funcion->modificarConcurrencia($concurrencia_registrada);
                    break;

                default :
                    $this->funcion->inicio();
            }
        } else {
            $accion = "inicio";
            $this->funcion->inicio();
        }
    }

    function action() {

        switch ($_REQUEST['opcion']) {

            case "registrarConcurrencia":
                $registro_previsora = array();

                foreach ($_REQUEST as $key => $value) {
                    if ($key != 'action' && $key != 'opcion') {
                        $registro_previsora[$key] = $_REQUEST[$key];
                    }
                }

                $this->funcion->procesarFormulario($registro_previsora);
                break;

            case "actualizarConcurrencia":
                $registro_previsora = array();

                foreach ($_REQUEST as $key => $value) {
                    if ($key != 'action' && $key != 'opcion') {
                        $registro_previsora[$key] = $_REQUEST[$key];
                    }
                }

                $this->funcion->actualizarConcurrencia($registro_previsora);
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
$esteBloque = new bloque_formConcurrencia($configuracion);
//echo var_dump($_REQUEST);exit;
//"blouqe ".$_REQUEST['action'];exit;


if (!isset($_REQUEST['action'])) {
    $esteBloque->html();
} else {

    $esteBloque->action();
}
?>