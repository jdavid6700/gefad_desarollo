<?
/*
############################################################################
#    UNIVERSIDAD DISTRITAL Francisco Jose de Caldas                        #
#    Desarrollo Por:                                                       #
#    Paulo Cesar Coronado 2004 - 2005                                      #
#    paulo_cesar@berosa.com                                                #
#    Copyright: Vea el archivo EULA.txt que viene con la distribucion      #
############################################################################
*/
/***************************************************************************
  
borrar_registro.php 

Paulo Cesar Coronado
Copyright (C) 2001-2005

Última revisión 6 de Marzo de 2006

*****************************************************************************
* @subpackage   
* @package	formulario
* @copyright    
* @version      0.2
* @author      	Paulo Cesar Coronado
* @link		http://acreditacion.udistrital.edu.co
* 
*
* Borrar registros de la base de datos
*
*****************************************************************************/
//@ verifica si el usuario esta autorizado para ingresar ala modulo o lo envia a la pagina principal
if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;		
}
//@ verifica si existe una accion e invoca una pagina
if(!isset($_REQUEST['action']))
{
	include_once("html.php");	

}
else
{
	include_once("action.php");	
}
?>
