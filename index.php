<?
/***************************************************************************
*    Copyright (c) 2004 - 2006 :                                           *
*    UNIVERSIDAD DISTRITAL Francisco Jose de Caldas                        *
*    Comite Institucional de Acreditacion                                  *
*    siae@udistrital.edu.co                                                *
*    Paulo Cesar Coronado                                                  *
*    paulo_cesar@udistrital.edu.co                                         *
*                                                                          *
****************************************************************************
*                                                                          *
*                                                                          *
* SIAE es software libre. Puede redistribuirlo y/o modificarlo bajo los    *
* terminos de la Licencia Publica General GNU tal como la publica la       *
* Free Software Foundation en la versión 2 de la Licencia o, a su eleccion,*
* cualquier version posterior.                                             *
*                                                                          *
* SIAE se distribuye con la esperanza de que sea util, pero SIN NINGUNA    *
* GARANTIA. Incluso sin garantia implicita de COMERCIALIZACION o ADECUACION*
* PARA UN PROPOSITO PARTICULAR. Vea la Licencia Publica General GNU para   *
* mas detalles.                                                            *
*                                                                          *
* Deberia haber recibido una copia de la Licencia publica General GNU junto*
* con SIAE; si esto no ocurrio, escriba a la Free Software Foundation, Inc,*
* 59 Temple Place, Suite 330, Boston, MA 02111-1307, Estados Unidos de     *
* America                                                                  *
*                                                                          *
*                                                                          *
***************************************************************************/
/*Los caracteres tipograficos especificos del Espannol se han omitido      *
* deliberadamente para mantener la compatibilidad con editores que no      *
* soporten la codificacion                                                 *
****************************************************************************/
?><?
/***************************************************************************
     * @name          index.php 
* @author        Paulo Cesar Coronado
* @revision      Última revisión 6 de septiembre de 2006
****************************************************************************
* @subpackage   
* @package	SIAE
* @copyright    
* @version      0.2
* @author      	Paulo Cesar Coronado
* @link		N/D
* @description  Pagina principal del aplicativo
*
****************************************************************************/


require_once("clase/config.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable(); 

if(!isset($configuracion["instalado"]))
{
	//echo "<script>location.replace('instalar/index.html')</script>";	
	echo "<h1>Por favor verifique las variables de configuraci&oacute;n</h1>";
	exit;
}



include_once($configuracion["raiz_documento"].$configuracion["clases"]."/pagina.class.php");	
$la_pagina=new pagina($configuracion);

?>
