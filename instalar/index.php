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
* términos de la Licencia Pública General GNU tal como la publica la       *
* Free Software Foundation en la versión 2 de la Licencia ó, a su elección,*
* cualquier versión posterior.                                             *
*                                                                          *
* SIAE se distribuye con la esperanza de que sea útil, pero SIN NINGUNA    *
* GARANTÍA. Incluso sin garantía implícita de COMERCIALIZACIÓN o ADECUACIÓN*
* PARA UN PROPÓSITO PARTICULAR. Vea la Licencia Pública General GNU para   *
* más detalles.                                                            *
*                                                                          *
* Debería haber recibido una copia de la Licencia pública General GNU junto*
* con SIAE; si esto no ocurrió, escriba a la Free Software Foundation, Inc,*
* 59 Temple Place, Suite 330, Boston, MA 02111-1307, Estados Unidos de     *
* América                                                                  *
*                                                                          *
*                                                                          *
***************************************************************************/
?><?
/************************************************************************************************************
     * @name          index.php 
* @author        Paulo Cesar Coronado
* @revision      Última revisión 6 de septiembre de 2006
*************************************************************************************************************
* @subpackage   
* @package	SIAE
* @copyright    
* @version      0.2
* @author      	Paulo Cesar Coronado
* @link		N/D
* @description  Pagina principal del aplicativo
*
************************************************************************************************************/

$tema->celda         ="#F5F5F5";

?><html>
<head>
<style>
.bloquecentralcuerpo {
    font-size: 12;
    font-family: "Arial", Verdana, Trebuchet MS, Helvetica, sans-serif;
    text-align: justify;
    }
</style>
</head>
<body>
<form enctype='multipart/form-data' method='POST' action='action.php' name='instalar'>
<table class='bloquelateral' align='center' width='100%' cellpadding='0' cellspacing='0'>
<tr>
<td>
<table align='center' width='100%' cellpadding='7' cellspacing='1'>
	<tr class='bloquecentralcuerpo'>
		<td bgcolor='<? echo $tema->celda ?>'>
			Directorio ra&iacute;z de los documentos en el servidor:<br>(No es la URL)
		</td>
		<td bgcolor='<? echo $tema->celda ?>'>
			<input type='text' name='raiz_documento' size='40' maxlength='255' tabindex='<? echo $tab++ ?>' >
		</td>
	</tr>
	<tr class='bloquecentralcuerpo'>
		<td bgcolor='<? echo $tema->celda ?>'>
			Direcci&oacute;n (URL) ra&iacute;z del servidor:<br>(Ej: http://mi_servidor)
		</td>
		<td bgcolor='<? echo $tema->celda ?>'>
			<input type='text' name='host' size='40' maxlength='255' tabindex='<? echo $tab++ ?>' >
		</td>
	</tr>
	<tr class='bloquecentralcuerpo'>
		<td bgcolor='<? echo $tema->celda ?>'>
			Carpeta del sitio:<br>(Ej: /mi_sitio)
		</td>
		<td bgcolor='<? echo $tema->celda ?>'>
			<input type='text' name='site' size='40' maxlength='255' tabindex='<? echo $tab++ ?>' >
		</td>
	</tr>
	<tr class='bloquecentralcuerpo'>
		<td bgcolor='<? echo $tema->celda ?>'>
			Sistema de Base de Datos:
		</td>
		<td bgcolor='<? echo $tema->celda ?>'>
			<input type='text' name='db_sys' size='40' maxlength='255' tabindex='<? echo $tab++ ?>' >
		</td>
	</tr>
	<tr class='bloquecentralcuerpo'>
		<td bgcolor='<? echo $tema->celda ?>'>
			DNS del servidor de base de datos:
		</td>
		<td bgcolor='<? echo $tema->celda ?>'>
			<input type='text' name='db_dns' size='40' maxlength='255' tabindex='<? echo $tab++ ?>' >
		</td>
	</tr>
	<tr class='bloquecentralcuerpo'>
		<td bgcolor='<? echo $tema->celda ?>'>
			Tiempo de expiraci&oacute;n de las sesiones:<br>(En minutos)
		</td>
		<td bgcolor='<? echo $tema->celda ?>'>
			<input type='text' name='expiracion' size='40' maxlength='255' tabindex='<? echo $tab++ ?>' >
		</td>
	</tr>
	<tr class='bloquecentralcuerpo'>
		<td bgcolor='<? echo $tema->celda ?>'>
			N&uacute;mero de registros que se mostrar&aacute;n en los resultados:
		</td>
		<td bgcolor='<? echo $tema->celda ?>'>
			<input type='text' name='registro' size='40' maxlength='255' tabindex='<? echo $tab++ ?>' >
		</td>
	</tr>
	<tr class='bloquecentralcuerpo'>
		<td bgcolor='<? echo $tema->celda ?>'>
			Prefijo para las tablas en la base de datos:
		</td>
		<td bgcolor='<? echo $tema->celda ?>'>
			<input type='text' name='prefijo' size='40' maxlength='255' tabindex='<? echo $tab++ ?>' >
		</td>
	</tr>
	<tr class='bloquecentralcuerpo'>
		<td bgcolor='<? echo $tema->celda ?>'>
			Nombre del Sitio:<br>(M&aacute;ximo 255 letras)
		</td>
		<td bgcolor='<? echo $tema->celda ?>'>
			<input type='text' name='titulo' size='40' maxlength='255' tabindex='<? echo $tab++ ?>' >
		</td>
	</tr>
	<tr class='bloquecentralcuerpo'>
		<td bgcolor='<? echo $tema->celda ?>'>
			Nombre de la variable con que encripta:
		</td>
		<td bgcolor='<? echo $tema->celda ?>'>
			<input type='text' name='enlace' size='40' maxlength='255' tabindex='<? echo $tab++ ?>' >
		</td>
	</tr>
	<tr class='bloquecentralcuerpo'>
		<td bgcolor='<? echo $tema->celda ?>'>
			Clases:
		</td>
		<td bgcolor='<? echo $tema->celda ?>'>
			<input type='text' name='clase' size='40' maxlength='255' tabindex='<? echo $tab++ ?>' >
		</td>
	</tr>
	<tr class='bloquecentralcuerpo'>
		<td bgcolor='<? echo $tema->celda ?>'>
			Gr&aacute;ficos:
		</td>
		<td bgcolor='<? echo $tema->celda ?>'>
			<input type='text' name='grafico' size='40' maxlength='255' tabindex='<? echo $tab++ ?>' >
		</td>
	</tr>
	<tr class='bloquecentralcuerpo'>
		<td bgcolor='<? echo $tema->celda ?>'>
			Bloques (M&oacute;dulos):
		</td>
		<td bgcolor='<? echo $tema->celda ?>'>
			<input type='text' name='bloque' size='40' maxlength='255' tabindex='<? echo $tab++ ?>' >
		</td>
	</tr>
	<tr class='bloquecentralcuerpo'>
		<td bgcolor='<? echo $tema->celda ?>'>
			Archivos de configuraci&oacute;n:
		</td>
		<td bgcolor='<? echo $tema->celda ?>'>
			<input type='text' name='configuracion' size='40' maxlength='255' tabindex='<? echo $tab++ ?>' >
		</td>
	</tr>
	<tr class='bloquecentralcuerpo'>
		<td bgcolor='<? echo $tema->celda ?>'>
			Funciones Javascript:
		</td>
		<td bgcolor='<? echo $tema->celda ?>'>
			<input type='text' name='javascript' size='40' maxlength='255' tabindex='<? echo $tab++ ?>' >
		</td>
	</tr>
	<tr class='bloquecentralcuerpo'>
		<td bgcolor='<? echo $tema->celda ?>'>
			Documentos:<br>(Con permisos de lectura y escritura)
		</td>
		<td bgcolor='<? echo $tema->celda ?>'>
			<input type='text' name='documento' size='40' maxlength='255' tabindex='<? echo $tab++ ?>' >
		</td>
	</tr>
	<tr class='bloquecentralcuerpo'>
		<td bgcolor='<? echo $tema->celda ?>'>
			Estilo
		</td>
		<td bgcolor='<? echo $tema->celda ?>'>
			<input type='text' name='estilo' size='40' maxlength='255' tabindex='<? echo $tab++ ?>' >
		</td>
	</tr>
	<tr align='center'>
		<td colspan='2' rowspan='1'>
			<input name='aceptar' value='Aceptar' type='submit'><br>
		</td>
	</tr>
</table>
</td>
</tr>
</table>
</form>
</body>
</html>