<?
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/
/*--------------------------------------------------------------------------------------------------------------------------
* @name          bloque.php 
* @author        Paulo Cesar Coronado
* @revision      Última revisión 12 de enero de 2009
/*--------------------------------------------------------------------------------------------------------------------------
* @subpackage		bloqueAdminNominaOrdenador
* @package		bloques
* @copyright    	Universidad Distrital Francisco Jose de Caldas
* @version      	0.0.0.1 - abril 17 de 2013
* @author		Maritza Callejas Cely
* @author		Oficina Asesora de Sistemas
* @link			N/D
* @description  	Bloque para gestionar la nomina del sistema de contratación. Implementa los casos
*			de uso: 
*			Reporte de nomina
*			
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
class bloqueAdminNominaOrdenador extends bloque
{

	 public function __construct($configuracion)
	{
 		$this->sql = new sql_adminNominaOrdenador();
 		$this->funcion = new funciones_adminNominaOrdenador($configuracion, $this->sql);
 		
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

                        case "consultarNomina":
                                $this->funcion->consultar();
                                break;
			
                        case "consultarDetalles":
                                $this->funcion->consultarListadoDetalles();
                                break;
			
                        case "revisarSolicitudNomina":
                                $this->funcion->revisarSolicitudPagoNomina();
                                break;
			
                        case 'aprobar_nomina':
		  		$this->funcion->revisarAprobacionPagoNomina();
				break;
                        
                        case "revisarNomina":
                                $this->funcion->revisarPagoNomina();
                                break;
			
                        case 'generar_nomina':
		  		$this->funcion->generarNomina();
				break;
                        
                        
                        default:
		  		$this->funcion->consultar();
                                break;	
				
		}//fin switch
		
	}// fin funcion html
	
	
	function action($configuracion)
	{
		switch($_REQUEST['opcion'])
		{	
                  
                           
                      default: 
				//recupera los datos para realizar la busqueda de usuario				
				$pagina = $configuracion["host"].$configuracion["site"]."/index.php?";
				$variable = "pagina=nom_adminNominaOrdenador";
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

$esteBloque = new bloqueAdminNominaOrdenador($configuracion);


if(isset($_REQUEST['cancelar']))
{   unset($_REQUEST['action']);		
	$pagina = $configuracion["host"].$configuracion["site"]."/index.php?";
	$variable = "pagina=nom_adminNominaOrdenador";
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


