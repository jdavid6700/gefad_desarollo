<?

/* --------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
  --------------------------------------------------------------------------------------------------------------------------- */
/* --------------------------------------------------------------------------------------------------------------------------
 * @name          bloque.php 
 * @author        Violeta Sosa
 * @revision      Última revisión 01 mayo 2014
  /*--------------------------------------------------------------------------------------------------------------------------
 * @subpackage		bloqueformSustituto
 * @package		bloques
 * @copyright    	Universidad Distrital Francisco Jose de Caldas
 * @version      	0.0.0.1 - 1 de mayo de 2014
 * @author	
 * @author		Oficina Asesora de Sistemas
 * @link		N/D
 * @description  	Bloque para gestionar formularios de cuotas partes.
 *                      Implementa:

  Despliegue formulario cuotas partes registro sustitutos
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
class bloque_formSustituto extends bloque {

    private $configuracion;

    public function __construct($configuracion) {
        $this->configuracion = $configuracion;
        $this->sql = new sql_formSustituto();
        $this->funcion = new funciones_formSustituto($configuracion, $this->sql);
        $this->cripto = new encriptar();
    }

    function html() {
        if (isset($_REQUEST['opcion'])) {
            $accion = $_REQUEST['opcion'];

            switch ($accion) {

                case "historiaSustituto":
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
                        $this->funcion->mostrarFormulario($cedula);
                    }
                    break;

                case "reporte":
                    $this->funcion->reporteSustituto();
                    break;

                case "pdf_reporte":
                    $datos_sustitutos = unserialize($_REQUEST['datos_sustitutos']);
                    $this->funcion->generarPDF_sustituto($datos_sustitutos);
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

            case "registrarSustituto":
                $registro_sustituto = array();

                foreach ($_REQUEST as $key => $value) {
                    if ($key != 'action' && $key != 'opcion') {
                        $registro_sustituto[$key] = $_REQUEST[$key];
                    }
                }

                $this->funcion->procesarFormulario($registro_sustituto);
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
$esteBloque = new bloque_formSustituto($configuracion);
//echo var_dump($_REQUEST);exit;
//"blouqe ".$_REQUEST['action'];exit;


if (!isset($_REQUEST['action'])) {
    $esteBloque->html();
} else {

    $esteBloque->action();
}
?>