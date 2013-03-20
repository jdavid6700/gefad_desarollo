<?
/*
 ############################################################################
#    UNIVERSIDAD DISTRITAL Francisco Jose de Caldas                        #
#    Desarrollo Por:                        #
#    Paulo Cesar Coronado 2004 - 2005                                      #
#    paulo_cesar@berosa.com                                                   #
#    Copyright: Vea el archivo EULA.txt que viene con la distribucion      #
############################################################################
*/
?>
<?
/****************************************************************************************************************

001.php

Paulo Cesar Coronado
Copyright (C) 2001-2005

Última revisión 6 de Marzo de 2006

*******************************************************************************************************************
* @subpackage
* @package	formulario
* @copyright
* @version      0.2
* @author      	Paulo Cesar Coronado
* @link		http://acreditacion.udistrital.edu.co
*
*
* Formulario de autenticacion de usuarios
*
*****************************************************************************************************************/
?><?
/**
 @TO DO: La posibilidad de definir la presentación del formulario (fondos, bordes) por medio de hojas
 de estilo y/o estilos inline


 */

if(!isset($_POST['action']))
{
	//echo 'HTML';
	include_once($configuracion["raiz_documento"].$configuracion["bloques"]."/login/html.php");

}else
{
	//echo 'ACTION';
	include_once($configuracion["raiz_documento"].$configuracion["bloques"]."/login/action.php");
}


?>
