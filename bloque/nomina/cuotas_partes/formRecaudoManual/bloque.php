<?

/* --------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
  --------------------------------------------------------------------------------------------------------------------------- */
/* --------------------------------------------------------------------------------------------------------------------------
 * @name          bloque.php 
 * @author        Violeta Sosa
 * @revision      Última revisión 01 mayo 2014
  /*--------------------------------------------------------------------------------------------------------------------------
 * @subpackage		bloqueformRecaudoManual
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
class bloque_formRecaudoManual extends bloque {

    private $configuracion;

    public function __construct($configuracion) {
        $this->configuracion = $configuracion;
        $this->sql = new sql_formRecaudoManual();
        $this->funcion = new funciones_formRecaudoManual($configuracion, $this->sql);
        $this->cripto = new encriptar();
    }

    function html() {
        if (isset($_REQUEST['opcion'])) {
            $accion = $_REQUEST['opcion'];

            switch ($accion) {

                case "validarCedula":
                    $cedula = $_REQUEST['cedula_emp'];
                    if (!preg_match("^\d+$^", $cedula)) {
                        echo "<script type=\"text/javascript\">" .
                        "alert('La cédula no posee un formato válido');" .
                        "</script> ";
                        $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                        $variable = 'pagina=formularioRManual';
                        $variable.='&opcion=';
                        $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                        echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                        exit;
                    } else {
                        $this->funcion->entidadFormulario($cedula);
                    }
                    break;

                case "consultar":
                    $consultar_previsora = array();
                    $saldo_cuenta = 0;
                    foreach ($_REQUEST as $key => $value) {
                        if ($key != 'action' && $key != 'opcion') {
                            $consultar_recaudos[$key] = $_REQUEST[$key];
                        }
                    }

                    $this->funcion->historiaRecaudos($consultar_recaudos, $saldo_cuenta);
                    break;

                case "pasoFormulario":
                    foreach ($_REQUEST as $key => $value) {
                        if ($key != 'action' && $key != 'opcion') {
                            $recaudo_manual[$key] = $_REQUEST[$key];
                        }
                    }
                    $this->funcion->mostrarFormulario($recaudo_manual);
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

            case "guardarRecaudo":
                $registro_recaudo = array();

                foreach ($_REQUEST as $key => $value) {
                    if ($key != 'action' && $key != 'opcion') {
                        $registro_recaudo[$key] = $_REQUEST[$key];
                    }
                }

                $this->funcion->procesarFormulario($registro_recaudo);
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
$esteBloque = new bloque_formRecaudoManual($configuracion);
//echo var_dump($_REQUEST);exit;
//"blouqe ".$_REQUEST['action'];exit;


if (!isset($_REQUEST['action'])) {
    $esteBloque->html();
} else {

    $esteBloque->action();
}
?>