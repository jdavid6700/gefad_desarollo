<?

/* --------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
  --------------------------------------------------------------------------------------------------------------------------- */
/* --------------------------------------------------------------------------------------------------------------------------
 * @name          bloque.php 
 * @author        Violeta Sosa
 * @revision      Última revisión 30 de diciembre de 2013
  /*--------------------------------------------------------------------------------------------------------------------------
 * @subpackage		bloqueestadoCuenta
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
class bloque_estadoCuenta extends bloque {

    private $configuracion;

    public function __construct($configuracion) {
        $this->configuracion = $configuracion;
        $this->sql = new sql_estadoCuenta();
        $this->funcion = new funciones_estadoCuenta($configuracion, $this->sql);
    }

    function html() {
        if (isset($_REQUEST['opcion'])) {
            $accion = $_REQUEST['opcion'];

            switch ($accion) {

                case "saldo_cuenta":
                    $this->funcion->entregarSaldo();
                    break;
                
                default :
                    $this->funcion->consultarRegistros();
            }
        } else {
            $accion = "inicio";
            $this->funcion->mostrarFormulario();
        }
    }

    function action() {

        switch ($_REQUEST['opcion']) {

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
$esteBloque = new bloque_estadoCuenta($configuracion);
//echo var_dump($_REQUEST);exit;
//"blouqe ".$_REQUEST['action'];exit;


if (!isset($_REQUEST['action'])) {
    $esteBloque->html();
} else {

    $esteBloque->action();
}
?>