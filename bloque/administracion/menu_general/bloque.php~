<?
/***************************************************************************
  
index.php 

Paulo Cesar Coronado
Copyright (C) 2001-2008

Última revisión 29 de Octubre de 2008

*****************************************************************************
* @subpackage   
* @package	bloques
* @copyright    
* @version      0.1
* @author      	Paulo Cesar Coronado
			jairo Lavado Hernandez
* @link		N/D
* @description  Menu principal del bloque noticias
* @usage        
*****************************************************************************/
if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;		
}


include_once($configuracion["raiz_documento"].$configuracion["clases"]."/bloque.class.php");
include_once("funcion.class.php");
//include_once("sql.class.php");
//Clase 
class bloqueMenuGeneral extends bloque
{
	//@Método constructor donde se crea un objeto funcion de la clase registro_menuusuario 
	 public function __construct($configuracion)
	{
		$this->funcion=new registro_menugeneral($configuracion);
		
	}
	
	//@ Método que crea una conexion a la base de datos e invoca el metodo nuevoRegistro para mostrar el menu de usuario
	function html($configuracion)
	{include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
		$this->tema=$tema;
		$this->acceso_db=$this->funcion->conectarDB($configuracion);
		// @ Crear un objeto de la clase funcion
		$this->funcion->nuevoRegistro($configuracion,$tema,$this->acceso_db);
		
	}
	
	
	function action($configuracion)
	{
		
	}
	
	
}


// @ Crear un objeto bloque especifico de la clase bloqueMenuusuario 

$esteBloque=new bloqueMenugeneral($configuracion);
// @ invoca el Método html de la clase bloqueMenuusuario
$esteBloque->html($configuracion);


?>
