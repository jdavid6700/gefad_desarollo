<?
/*
############################################################################
#    UNIVERSIDAD DISTRITAL Francisco Jose de Caldas                        #
#    GRUPO DE INVESTIGACION EN TELEMEDICINA                                #
#    Directora General:                                                    #
#    Dra LILIA EDITH APARICIO P.                                           #
#    Desarrollo Por:                                                       #
#    Paulo Cesar Coronado 2004 - 2007                                      #
#    paulo_cesar@etb.net.co                                                #
#    Copyright: Vea el archivo LICENCIA.txt que viene con la distribucion  #
############################################################################
*/
/***************************************************************************
  
bloque.php 

Paulo Cesar Coronado
Copyright (C) 2001-2005

Última revisión 6 de Marzo de 2006

*****************************************************************************
* @subpackage   registro_seleccionar_modelo
* @package	bloques
* @copyright    
* @version      0.2
* @author      	Paulo Cesar Coronado
* @link		N/D
* @description  Formulario para la seleccion de modelos dentro de la ponderacion
* @usage        
******************************************************************************/ 

if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;		
}
//@ invoca el archivo deonde se crea y ejecuta las sentencias sql, del subsistema o modulos especifico.
include_once ($configuracion["raiz_documento"].$configuracion["bloques"]."/borrar_registro/".$_REQUEST['opcion'].".php");
//@ Muestra una tabla con un mensaje para verificar el borrado del registro.
?><table width="100%" cellpadding="12" cellspacing="0"  align="center">
	<tbody>
		<tr>
			<td align="center" valign="middle">
				<table class="bloquelateral" cellpadding="0" cellspacing="0">
				<tbody>
					<tr class="bloquelateralencabezado">
						<td valign="middle" align="right" width="10%">
						<img src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["grafico"]?>/importante.png" border="0" />
						</td>
						<td valign="middle" align="left" >
						Eliminar un registro del sistema
						</td>
					</tr>
					<tr>
					<td colspan="2"> 
						<table border="0" cellpadding="10" cellsapcing="0">
						<tbody>
							<tr class="bloquecentralcuerpo">
							<td>
							Confirma la eliminaci&oacute;n de <b><? echo $borrar_nombre ?></b> del sistema. Tenga en cuenta que este cambio no podr&aacute;&nbsp; deshacerse.<br>
							</td>
							</tr>
							<tr class="bloquecentralcuerpo">
							<td align="center">
							<? echo $opciones ?>
							<hr style="width: 100%; height: 2px;">
							En algunas ocasiones borrar un registro puede implicar la eliminaci&oacute;n de todos sus datos asociados.</span></small></div><br> 
							</td>
							</tr>
						</tbody>
						</table>
					</td>
					</tr>
					</tbody>
				</table>
			</td>
		</tr>
	</tbody>
</table>
