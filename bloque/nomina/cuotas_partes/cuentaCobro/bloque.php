<?

/* --------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
  --------------------------------------------------------------------------------------------------------------------------- */
/* --------------------------------------------------------------------------------------------------------------------------
 * @name          bloque.php 
 * @author        Paulo Cesar Coronado
 * @revision      Última revisión 12 de enero de 2009
  /*--------------------------------------------------------------------------------------------------------------------------
 * @subpackage		bloqueAdminCuentaCobro
 * @package		bloques
 * @copyright    	Universidad Distrital Francisco Jose de Caldas
 * @version      	0.0.0.1 - Mayo 24 de 2013
 * @author		Violeta Sosa
 * @author		Oficina Asesora de Sistemas
 * @link		N/D
 * @description  	Bloque para gestionar cuotas partes. Implementa los casos
 * 			de uso: 
 * 			Registro de historias laborales
 * 			Generación  cuenta de cobro
 *  
 */
/* -------------------------------------------------------------------------------------------------------------------------- */

if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/bloque.class.php");
include_once("funcion.class.php");
include_once("sql.class.php");

//Clase
class bloqueAdminCuentaCobro extends bloque {

    public function __construct($configuracion) {
        $this->sql = new sql_adminCuentaCobro();
        $this->funcion = new funciones_adminCuentaCobro($configuracion, $this->sql);
    }

    function html() {
        //Rescatar datos de sesion        
        switch ($_REQUEST['opcion']) {

            case "manual":
                $this->funcion->registroManual();
                break;

            case "manual_consulta":
                $cedula = $_REQUEST['cedula_emp'];
                $this->funcion->consultaPManual($cedula);
                break;

            case "formulario_manual":

                $form_manual = array();

                foreach ($_REQUEST as $key => $value) {
                    if ($key != 'action' && $key != 'opcion') {
                        $form_manual[$key] = $_REQUEST[$key];
                    }
                }

                $this->funcion->formularioCManual($form_manual);
                break;

            default:
                $this->funcion->inicio();
                break;
        }
    }

// fin funcion html

    function action() {
        switch ($_REQUEST['opcion']) {
 
            case "cuentaManual":
           
                $cuentaManual_datos = array();

                foreach ($_REQUEST as $key => $value) {
                    if ($key != 'action' && $key != 'opcion') {
                        $cuentaManual_datos[$key] = $_REQUEST[$key];
                    }
                }
                $this->funcion->registrarManual($cuentaManual_datos);
                break;

            default:
                $this->funcion->consultar();
                break;
        }
    }

//fin funcion action
}

// fin clase bloquenom_adminNovedad
// @ Crear un objeto bloque especifico
//echo "bloque";
//var_dump($configuracion);
$esteBloque = new bloqueAdminCuentaCobro($configuracion);



if (isset($_REQUEST['cancelar'])) {
    unset($_REQUEST['action']);
    $pagina = $configuracion["host"] . $configuracion["site"] . "/index.php?";
    $variable = "pagina=reportesCuotas";
    $variable .= "&opcion=consultar";
    include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
    $this->cripto = new encriptar();
    $variable = $this->cripto->codificar_url($variable, $configuracion);

    echo "<script>location.replace('" . $pagina . $variable . "')</script>";
}

//echo "action".$_REQUEST['action'];exit;
//var_dump($_REQUEST);exit;
if (!isset($_REQUEST['action'])) {
    $esteBloque->html($configuracion);
} else {

    $esteBloque->action($configuracion);
}
?>


