<?
/***************************************************************************
*    Copyright (c) 2004 - 2006 :                                           *
*    UNIVERSIDAD DISTRITAL Francisco Jose de Caldas                        *
*    Paulo Cesar Coronado                                                  *
*    paulo_cesar@udistrital.edu.co                                         *
*                                                                          *
****************************************************************************
*                                                                          *
*                                                                          *
* SITEM es software libre. Puede redistribuirlo y/o modificarlo bajo los   *
* terminos de la Licencia Publica General GNU tal como la publica la       *
* Free Software Foundation en la versión 2 de la Licencia o, a su eleccion,*
* cualquier version posterior.                                             *
*                                                                          *
* SITEM se distribuye con la esperanza de que sea util, pero SIN NINGUNA   *
* GARANTIA. Incluso sin garantia implicita de COMERCIALIZACION o ADECUACION*
* PARA UN PROPOSITO PARTICULAR. Vea la Licencia Publica General GNU para   *
* mas detalles.                                                            *
*                                                                          *
* Deberia haber recibido una copia de la Licencia publica General GNU junto*
* con SITEM;si esto no ocurrio, escriba a la Free Software Foundation, Inc,*
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
* @revision      Ultima revision 6 de septiembre de 2006
****************************************************************************
* @subpackage   
* @package	SIAE
* @copyright    
* @version      0.2
* @author      	Paulo Cesar Coronado
* @link		N/D
* @description  Pagina principal del aplicativo
*
*****************************************************************************/
?><html>
<head>
<style>
.bloquecentralcuerpo {
    font-size: 12;
    font-family: "Arial", Verdana, Trebuchet MS, Helvetica, sans-serif;
    text-align: justify;
    color:#2A2A2A;
    }

.bloquelateral {
    border-width: 1px;
    border-color: <?PHP echo $tema->bordes?>;
    border-style: solid;
    -moz-border-radius-bottomleft: 10px;
    -moz-border-radius-bottomright: 10px;
    background-color: <?PHP echo $tema->cuerpotabla?>;
}
</style>
<title>
Sistema Informaci&oacute;n en Telemedicina
</title>
</head>
<body leftMargin="0" topMargin="0">
<script src="funciones.js" type="text/javascript" language="javascript"></script>
<table width="795px" class='bloquelateral' align='center' width='100%' cellpadding='0' cellspacing='0' bgcolor="#a8c9eb">
	<tr>
		<td>
<?

if (substr(phpversion(),0,3)>(4.2))
{
  	mensaje("OK: Versi&oacute;n actual de PHP: ".phpversion().".",1);
	
}
else
{
	mensaje("Error: Versi&oacute;n actual de PHP: ".phpversion().".",0);
	$error=FALSE;
}


if (extension_loaded("mysql"))
{
  	mensaje("OK: Soporte para MySQL ",1);
	
}
else
{
	mensaje("Error: No existe soporte para MySQL ",0);
	$error=FALSE;
}

if (extension_loaded("gd"))
{
  	mensaje("OK: Soporte para GD ",1);
	
}
else
{
	mensaje("Advertencia: No existe soporte para imagenes - librer&iacute;a GD.<br>El aplicativo no podr&aacute; generar gr&aacute;ficos.",2);	
	$error_ligero=FALSE;
}

if(!getenv("safe_mode"))
{
	mensaje("OK: Soporte para carga de archivo con SAFE_MODE=off",1);

}
else
{
	mensaje("Advertencia: El servidor est&aacute; en modo seguro.<br>Posiblemente el manejo de archivos estar&aacute; deshabilitado.",2);
	$error_ligero=FALSE;
}


if(isset($error))
{
	mensaje("<b>Por favor revisar la configuraci&oacute;n del servidor para poder continuar con la instalaci&oacute;n.</b>",0);

}
else
{
	if(isset($error_ligero))
	{
		mensaje("Aunque algunos par&aacute;metros de configuraci&oacute;n no son los esperados, la instalaci&oacute;n puede continuar con algunas restricciones en la funcionalidad del aplicativo.",2);
	
	}
	else
	{
		mensaje("Configuraci&oacute;n del servidor: CORRECTA.",1);
	
	}
	?><br>
	<table align="center" style="text-align: left;" border="0"  cellspacing="0"  width="80%">
	<tr>
		<td >
			<table align="center" cellpadding="10" cellspacing="0" >
				<tr class="bloquecentralcuerpo">
					<td valign="middle" align="right" >
						<a href="html.php">Continuar</a>
					</td>
				</tr>
			</table> 
		</td>
	</tr>  
	</table>
	<br><?


}?>		</td>
	</tr>
</table>

</body>
</html>

<?

function mensaje($mensaje="",$tipo=NULL)
{
?><table align="center" style="text-align: left;" border="0"  cellspacing="0"  width="80%">
	<tr>
		<td >
			<table cellpadding="10" cellspacing="0" >
				<tr class="bloquecentralcuerpo">
					<td valign="middle" align="right" >
						<?
						switch($tipo)
						{
							case 0:	?>
						<img src="boton_rojo.png" border="0" /><?
							break;
							
						   	case 1:
						?>	
						<img src="boton_verde.png" border="0" /><?
							break;
							
						   	case 2:
						?>	
						<img src="boton_gris.png" border="0" /><?
							break;
						  }
						?>	
					</td>
					<td align="left">
						<?echo $mensaje?>
					</td>
				</tr>
			</table> 
		</td>
	</tr>  
</table><?
}


?>