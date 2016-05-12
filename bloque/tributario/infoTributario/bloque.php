<?

/* --------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
  --------------------------------------------------------------------------------------------------------------------------- */
/* --------------------------------------------------------------------------------------------------------------------------
 * @name          bloque.php 
 * @author        Paulo Cesar Coronado
 * @revision      Última revisión 12 de enero de 2009
  /*--------------------------------------------------------------------------------------------------------------------------
 * @subpackage		bloqueAdminNovedad
 * @package		bloques
 * @copyright    	Universidad Distrital Francisco Jose de Caldas
 * @version      	0.0.0.1 - Mayo 24 de 2013
 * @author		
 * @author		Oficina Asesora de Sistemas
 * @link		N/D
 * @description  	Bloque para gestionar cuotas partes. Implementa los casos
 * 			de uso: 
 * 			Registro de historias laborales
 * 			Generación  cuenta de cobro
  /*-------------------------------------------------------------------------------------------------------------------------- */

if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/bloque.class.php");
include_once("funcion.class.php");
include_once("sql.class.php");

//Clase
class bloqueInfoTributario extends bloque {

    public function __construct($configuracion) {
        $this->sql = new sql_infoTributario();
        $this->funcion = new funciones_infoTributario($configuracion, $this->sql);
    }

    function html($configuracion) {
        //Rescatar datos de sesion        

        switch ($_REQUEST['opcion']) {


            case "verificar":
                $this->funcion->generarCuenta();
                break;

            case "consultar":
                $this->funcion->consultaForm();
                break;

            default:
                $this->funcion->envioConsulta();
        }
    }

// fin funcion html

    function action($configuracion) {
        switch ($_REQUEST['opcion']) {

            default:
                $this->funcion->consultaForm();
                break;
        }
    }

//fin funcion action
}

// fin clase bloquenom_adminNovedad
// @ Crear un objeto bloque especifico
//echo "bloque";
//var_dump($configuracion);
$esteBloque = new bloqueInfoTributario($configuracion);



if (isset($_REQUEST['cancelar'])) {
    unset($_REQUEST['action']);
    $pagina = $configuracion["host"] . $configuracion["site"] . "/index.php?";
    $variable = "pagina=adminTributario";
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


