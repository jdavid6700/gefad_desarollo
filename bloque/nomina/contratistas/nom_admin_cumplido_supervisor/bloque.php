<?
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/
/*--------------------------------------------------------------------------------------------------------------------------
* @name          bloque.php 
* @author        Paulo Cesar Coronado
* @revision      Última revisión 12 de enero de 2009
/*--------------------------------------------------------------------------------------------------------------------------
* @subpackage		bloqueAdminCumplidoSupervisor
* @package		bloques
* @copyright    	Universidad Distrital Francisco Jose de Caldas
* @version      	0.0.0.1 - Febrero 14 de 2013
* @author		Maritza Callejas Cely
* @author			Oficina Asesora de Sistemas
* @link			N/D
* @description  	Bloque para gestionar las novedades del sistema de contratación. Implementa los casos
*			de uso: 
*			Consultar novedades de contratista
*			Registrar novedad de contratista
/*--------------------------------------------------------------------------------------------------------------------------*/
if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;		
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/bloque.class.php");
include_once("funcion.class.php");
include_once("sql.class.php");



//Clase
class bloqueAdminCumplidoSupervisor extends bloque
{

	 public function __construct($configuracion)
	{
 		$this->sql = new sql_adminCumplidoSupervisor();
 		$this->funcion = new funciones_adminCumplidoSupervisor($configuracion, $this->sql);
 		
	}
	
	
	function html($configuracion)
	{		
		//Rescatar datos de sesion
		$usuario = $this->funcion->rescatarValorSesion($configuracion, $this->funcion->acceso_db, "usuario");
		$id_usuario = $this->funcion->rescatarValorSesion($configuracion, $this->funcion->acceso_db, "id_usuario");
		$_REQUEST['opcion']=(isset($_REQUEST['opcion'])?$_REQUEST['opcion']:'');
                //$vigencia=(isset($_REQUEST['vigencia'])?$_REQUEST['vigencia']:date('Y'));
                $tema=(isset($tema)?$tema:'');
                switch ($_REQUEST['opcion'])
		{ 

                        case 'revisar_solicitud':
		  		//solicitar cumplido
				$this->funcion->revisarSolicitudCumplido();
				break;
                        
                        case 'aprobar':
		  		$this->funcion->revisarAprobacion();
				break;
                        
                        case "generarCumplido":
                                $this->funcion->generarCumplido($configuracion);
                                break;
					
                        case "consultarCumplidos":
                                $this->funcion->consultar();
                                break;
			
                        default:
		  		//solicitar cumplido
				$this->funcion->solicitarCumplido();
				break;	
				
		}//fin switch
		
	}// fin funcion html
	
	
	function action($configuracion)
	{
		switch($_REQUEST['opcion'])
		{	
                  
                    case 'nuevo':
		  		//Consultar usuario
				$this->funcion->registrarSolicitud();
				break;
                           
                      default: 
				//recupera los datos para realizar la busqueda de usuario				
				$pagina = $configuracion["host"].$configuracion["site"]."/index.php?";
				$variable = "pagina=nom_adminNovedad";
				$variable .= "&opcion=".$_REQUEST["opcion"];
				$variable .= "&vigencia=".$_REQUEST["vigencia"];
				if(isset($_REQUEST['clave']))
					{
					$variable .= "&clave=".$_REQUEST["clave"];
					}
				if(isset($_REQUEST['criterio_busqueda'])){
                                        $variable .= "&criterio_busqueda=".$_REQUEST["criterio_busqueda"];
                                }
				include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto = new encriptar();
				$variable = $this->cripto->codificar_url($variable,$configuracion);
				echo "<script>location.replace('".$pagina.$variable."')</script>";

				break;
		}//fin switch
	}//fin funcion action
	
	
}// fin clase bloquenom_adminNovedad


// @ Crear un objeto bloque especifico

$esteBloque = new bloqueAdminCumplidoSupervisor($configuracion);


if(isset($_REQUEST['cancelar']))
{   unset($_REQUEST['action']);		
	$pagina = $configuracion["host"].$configuracion["site"]."/index.php?";
	$variable = "pagina=nom_adminNovedad";
	$variable .= "&opcion=consultar";
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
	$this->cripto = new encriptar();
	$variable = $this->cripto->codificar_url($variable,$configuracion);
	
	echo "<script>location.replace('".$pagina.$variable."')</script>";
}
//var_dump($_REQUEST);exit;
//echo "action".$_REQUEST['action'];exit;
if(!isset($_REQUEST['action']))
{
	$esteBloque->html($configuracion);
}
else
{
	$esteBloque->action($configuracion);

}


?>


