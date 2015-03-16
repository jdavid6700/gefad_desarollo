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
class bloqueadminTributario extends bloque {

    public function __construct($configuracion) {
        $this->sql = new sql_adminTributario();
        $this->funcion = new funciones_adminTributario($configuracion, $this->sql);
    }

    function html($configuracion) {
        //Rescatar datos de sesion        
       // var_dump($_REQUEST);exit;
        switch ($_REQUEST['opcion']) 
            {   case "buscar":
                    $this->funcion->nuevoRegistro($configuracion, '', '');
                    $datos=array('vigencia'=>$_REQUEST['vigencia'],'identificacion'=>$_REQUEST['identificacion'],'busqueda'=>$_REQUEST['opBuscar'] );
                    $this->funcion->listaRegistro($configuracion, $datos);
                    break;
                
                case "consultar":
                    $this->funcion->consultaForm();
                    break;

                default:
                    $this->funcion->nuevoRegistro($configuracion, '', '');
            }
    }

// fin funcion html

    function action($configuracion) {
        switch ($_REQUEST['opcion']) 
            {   case 'otro': echo "otro";
                 break;
                
              case "clasificar":
                    $datosClasificar=array();
                    $cont=0;
                    $aux=0;
                    $vig=$_REQUEST['vigencia'];
                    foreach ($_REQUEST as $class=>$valor)
                           {  if($class == 'per_esc_'.$aux )
                                    {
                                     $datosClasificar[$cont]['identificacion_'.$cont]=$valor;
                                     $datosClasificar[$cont]['tipo_ident_'.$cont]=$_REQUEST['tipo_ident_'.$aux];
                                     $datosClasificar[$cont]['escalafon_'.$cont]=$_REQUEST['escal_'.$aux];
                                     $cont++;   
                                     $aux++; 
                                    }
                              elseif($class == 'tipo_ident_'.$aux)
                                    { $aux++;}      
                            }
                    $this->funcion->generarEscalafon($datosClasificar,$vig);
                    $pagina = $configuracion["host"] . $configuracion["site"] . "/index.php?";
                    $variable = "pagina=administraTributario";
                    $variable .= "&opcion=buscar";
                    $variable .= "&vigencia=".$_REQUEST['vigencia'];
                    $variable .= "&identificacion=".$_REQUEST['identificacion'];
                    $variable .= "&opBuscar=".$_REQUEST['opBuscar'];
                   // echo $variable;exit;
                    include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
                    $this->cripto = new encriptar();
                    $variable = $this->cripto->codificar_url($variable, $configuracion);
                    echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                    break; 
             
             
                default:
                     //recupera los datos para realizar la busqueda de usuario
                    $pagina = $configuracion["host"] . $configuracion["site"] . "/index.php?";
                    $variable = "pagina=administraTributario";
                    foreach ($_REQUEST as $key => $value) {
                        if (!isset($_REQUEST[$configuracion['enlace']]) && $key != 'action') {
                            $variable .= "&$key=" . $_REQUEST[$key];
                        }
                    }
                    include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
                    $this->cripto = new encriptar();
                    $variable = $this->cripto->codificar_url($variable, $configuracion);
                    echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                 break;
            }
    }

//fin funcion action
}

// fin clase bloquenom_adminNovedad
// @ Crear un objeto bloque especifico
//echo "bloque";
//var_dump($configuracion);
$esteBloque = new bloqueadminTributario($configuracion);

if (isset($_REQUEST['cancelar'])) {
    unset($_REQUEST['action']);
    $pagina = $configuracion["host"] . $configuracion["site"] . "/index.php?";
    $variable = "pagina=administraTributario";
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


