<?

/* --------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
  --------------------------------------------------------------------------------------------------------------------------- */
/* --------------------------------------------------------------------------------------------------------------------------
 * @name          bloque.php 
 * @author        Paulo Cesar Coronado
 * @revision      Última revisión 12 de enero de 2009
  /*--------------------------------------------------------------------------------------------------------------------------
 * @subpackage		bloqueAdminUsuario
 * @package		bloques
 * @copyright    	Universidad Distrital Francisco Jose de Caldas
 * @version      	0.0.0.1 - Agosto 14 de 2009
 * @author		Maritza Callejas Cely
 * @author			Oficina Asesora de Sistemas
 * @link			N/D
 * @description  	Bloque para gestionar los usuarios del sistema Portal DW. Implementa los casos
 * 			de uso: 
 * 			Registrar cuenta de Usuario
 * 			Editar Datos de Usuario
 * 			Consultar Usuario
 * 			Cambiar el estado del Usuario
 * 			Cambiar Contraseña
  /*-------------------------------------------------------------------------------------------------------------------------- */
if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/bloque.class.php");
include_once("funcion.class.php");
include_once("sql.class.php");

//Clase
class bloqueAdminUsuario extends bloque {

    public function __construct($configuracion) {
        $this->sql = new sql_adminUsuario();
        $this->funcion = new funciones_adminUsuario($configuracion, $this->sql);
    }

    function html($configuracion) {
        //Rescatar datos de sesion
        $usuario = $this->funcion->rescatarValorSesion($configuracion, $this->funcion->acceso_db, "usuario");
        $id_usuario = $this->funcion->rescatarValorSesion($configuracion, $this->funcion->acceso_db, "id_usuario");
        $_REQUEST['opcion'] = (isset($_REQUEST['opcion']) ? $_REQUEST['opcion'] : '');
        $tema = (isset($tema) ? $tema : '');

        switch ($_REQUEST['opcion']) {
            case 'consultar':
                //Consultar usuario
                $this->funcion->consultarUsuario($configuracion, $_REQUEST["cod_usuario"]);
                break;

            case 'nuevo':
                //Crear nuevo usuario
                $this->funcion->nuevoRegistro($configuracion, $tema, $this->funcion->acceso_db);
                break;

            case "editar":
                //Editar los datos básicos del usuario
                $this->funcion->editarRegistro($configuracion, $tema, $id_usuario, $this->acceso_db, "");
                break;

            case 'nuevoRol':
                //Consultar usuario
                $this->funcion->nuevoRol($configuracion, $_REQUEST["cod_usuario"]);
                break;

            case 'editarRol':
                //Consultar usuario

                $busquedaRol = array(
                    'criterio_busqueda' => "COD_US",
                    'valor' => $_REQUEST["cod_usuario"],
                    'cod_usuario' => $_REQUEST["cod_usuario"],
                    'cod_rol' => $_REQUEST["cod_rol"]);

                $this->funcion->editarRol($configuracion, $busquedaRol);
                break;

            case "cambiar_clave":

                //Cambiar clave o contraseña del usuario
                $this->funcion->editarRegistro($configuracion, $tema, $id_usuario, $this->acceso_db, "");
                break;

            case "cambiar_estado":

                //Cambiar estado del Usuario para el sistema
                $this->funcion->consultarUsuario($configuracion, $_REQUEST['cod_usuario']);
                break;

            case "inactivarRol":
                $this->funcion->inactivarRol($configuracion);
                break;

            default:
                //Consultar usuario
                $this->funcion->consultarUsuario($configuracion, "");
                break;
        }//fin switch
    }

// fin funcion html

    function action($configuracion) {
        switch ($_REQUEST['opcion']) {
            case "nuevo":
                $this->funcion->guardarUsuario($configuracion);
                break;
            case "editar":
                $this->funcion->editarUsuario($configuracion);
                break;
            case "nuevoRol":
        
                $this->funcion->guardarRol($configuracion);
                break;
            case "editarRol":
                $this->funcion->actualizarRol($configuracion);
                break;

            case "cambiar_clave":
                $this->funcion->editarContrasena($configuracion);
                break;
            case "cambiar_estado":
                $this->funcion->cambiarEstado($configuracion, $_REQUEST['cod_usuario']);
                break;

            default:

                //recupera los datos para realizar la busqueda de usuario				
                $pagina = $configuracion["host"] . $configuracion["site"] . "/index.php?";
                $variable = "pagina=adminUsuario";
                $variable .= "&opcion=" . $_REQUEST["opcion"];
                if (isset($_REQUEST['clave'])) {
                    $variable .= "&clave=" . $_REQUEST["clave"];
                }
                $variable .= "&criterio_busqueda=" . $_REQUEST["criterio_busqueda"];

                include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
                $this->cripto = new encriptar();
                $variable = $this->cripto->codificar_url($variable, $configuracion);

                echo "<script>location.replace('" . $pagina . $variable . "')</script>";

                break;
        }//fin switch
    }

//fin funcion action
}

// fin clase bloqueAdminUsuario
// @ Crear un objeto bloque especifico

$esteBloque = new bloqueAdminUsuario($configuracion);


if (isset($_REQUEST['cancelar'])) {
    unset($_REQUEST['action']);
    $pagina = $configuracion["host"] . $configuracion["site"] . "/index.php?";
    $variable = "pagina=adminUsuario";
    $variable .= "&opcion=consultar";
    include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
    $this->cripto = new encriptar();
    $variable = $this->cripto->codificar_url($variable, $configuracion);

    echo "<script>location.replace('" . $pagina . $variable . "')</script>";
}

//echo "action".$_REQUEST['action'];exit;
if (!isset($_REQUEST['action'])) {
    $esteBloque->html($configuracion);
} else {
    $esteBloque->action($configuracion);
}
?>


