<?
/*--------------------------------------------------------------------------------------------------------------------------
 @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/
/*--------------------------------------------------------------------------------------------------------------------------
 * @name          bloque.php
* @author        Paulo Cesar Coronado
* @revision      Última revisión 12 de enero de 2009
/*--------------------------------------------------------------------------------------------------------------------------
* @subpackage		bloqueAdminNovedad
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
class bloquemenuReporteFin extends bloque
{

	public function __construct($configuracion)
	{
		$this->sql = new sql_menuReporteFin();
		$this->funcion = new funciones_menuReporteFin($configuracion, $this->sql);
			
	}


	function html($configuracion)
	{
		//Rescatar datos de sesion
		//$usuario = $this->funcion->rescatarValorSesion($configuracion, $this->funcion->acceso_db, "usuario");
		//$id_usuario = $this->funcion->rescatarValorSesion($configuracion, $this->funcion->acceso_db, "id_usuario");
		
		$tema=(isset($tema)?$tema:'');
                
		switch ($_REQUEST['opcion'])
		{
			case 'consultar':
				//Consultar usuario
				break;

			case 'crearNovedad':
				//Consultar usuario
				//var_dump($_REQUEST);
				break;


			default:
				//Consultar novedad
				$this->funcion->htmlMenuReporte->menu();
				break;

		}//fin switch

	}// fin funcion html


	function action($configuracion)
	{
		
	}//fin funcion action


}// fin clase bloquenom_adminNovedad


// @ Crear un objeto bloque especifico

$esteBloque = new bloquemenuReporteFin($configuracion);
//var_dump($_REQUEST);//exit;

if(isset($_REQUEST['cancelar']))
{
	unset($_REQUEST['action']);
	$pagina = $configuracion["host"].$configuracion["site"]."/index.php?";
	$variable = "pagina=reportesFinanciero";
        $variable .= "&opcion=".$_REQUEST["opcion"];
        $variable .= "&reporte=".$_REQUEST["reporte"];
        $variable .= "&vigencia=".$_REQUEST["vigencia"];
        $variable .= "&unidad=".$_REQUEST["unidad"];
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
	$this->cripto = new encriptar();
	$variable = $this->cripto->codificar_url($variable,$configuracion);

	echo "<script>location.replace('".$pagina.$variable."')</script>";
}
//var_dump($_REQUEST);//exit;
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


