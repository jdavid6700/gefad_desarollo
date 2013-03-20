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
/************************************************************************************************************
     * @name          index.php 
* @author        Paulo Cesar Coronado
* @revision      Ultima revision 6 de septiembre de 2006
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
//Variables de instalacion:

include_once("variables.php");

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
Sistema Informaci&oacute;n en Telemedicina - Instalaci&oacute;n
</title>
</head>
<body leftMargin="0" topMargin="0">
<script src="funciones.js" type="text/javascript" language="javascript"></script>
<table width="795px" class='bloquelateral' align='center' width='100%' cellpadding='0' cellspacing='0' bgcolor="#a8c9eb">
	<tr>
		<td>
<?
$directorio=$_REQUEST["raiz_documento"];
$arquitectura=$_REQUEST["raiz_documento"].$_REQUEST["site"]."/instalar/arquitectura/arquitectura.sql";
$datos=$_REQUEST["raiz_documento"].$_REQUEST["site"]."/instalar/arquitectura/datos.sql";
$configuracion=$_REQUEST["raiz_documento"].$_REQUEST["site"]."/configuracion/config.inc.php";

if(!is_dir ($directorio))
{	
	mensaje(" Error: Directorio ra&iacute;z de los documentos en el servidor:<br><b>".$_REQUEST["raiz_documento"]."</b>.",0);
	mensaje(" Error: Carpeta del sitio:<br><b>".$_REQUEST["site"]."</b>",2);
	$error=FALSE;
}
else
{

	if(file_exists($arquitectura))
	{
		mensaje(" OK: Directorio ra&iacute;z de los documentos en el servidor:<br><b>".$_REQUEST["raiz_documento"]."</b>.",1);
		mensaje(" OK: Carpeta del sitio<br><b>".$_REQUEST["site"]."</b>",1);
		
	}
	else
	{
		mensaje(" Error: No existe la arquitectura de la base de datos</b>.",0);
		$error=FALSE;
		
	}
	
}

$url_1 = parse_url(getenv("HTTP_REFERER"));
$url_2 = parse_url($_REQUEST["host"]);

if($url_1["host"]==$url_2["host"])
{
	mensaje("  OK: Dirección (URL) ra&iacute;z del servidor:<br><b>".$_REQUEST["host"]."</b>",1);
}
else
{
	mensaje("  Error: Dirección (URL) ra&iacute;z del servidor:<br><b>".$_REQUEST["host"]."</b>",0);
	$error=FALSE;
}


$fp=@fopen($configuracion,"w+");


if($fp==FALSE)
{
	
	mensaje("Error: El archivo de configuraci&oacute;n ubicado en:<br><b>".$configuracion."</b><br>debe tener permisos de escritura.",0);
	$error=FALSE;

}
else
{
	mensaje("OK: El archivo de configuraci&oacute;n ubicado en:<br><b>".$configuracion."</b><br>puede ser editado.",1);

}

$conexion=@mysql_connect($_REQUEST["db_dns"],$_REQUEST["db_user"],$_REQUEST["db_password"]);

if(!$conexion)
{
	mensaje("Error: No se puede conectar al Servidor de bases de datos en:<br><b>".$_REQUEST["db_dns"]."</b>",0);
	mensaje("Error: No se puede conectar a la base de datos:<br><b>".$_REQUEST["db_name"]."</b>",0);
	$error=FALSE;
}
else
{
	mensaje("OK: Conexi&oacute;n exitosa al Servidor de bases de datos en:<br><b>".$_REQUEST["db_dns"]."</b>",1);
	
	$base=mysql_select_db($_REQUEST["db_name"],$conexion);
	if(!$base)
	{
		mensaje("Error: No se puede conectar a la base de datos:<br><b>".$_REQUEST["db_name"]."</b>",0);
		$error=FALSE;
	}
	else
	{
		mensaje("OK: Conexi&oacute;n exitosa a la base de datos:<br><b>".$_REQUEST["db_name"]."</b>",1);
	}

}

$arquitecturap=@fopen($arquitectura,"r");


if($arquitecturap==FALSE)
{
	
	mensaje("Error: El esquema de base de datos ubicado en:<br><b>".$arquitectura."</b><br>no puede ser leido.",0);
	$error=FALSE;

}
else
{
	mensaje("OK: El esquema de base de datos ubicado en:<br><b>".$arquitectura."</b><br>est&aacute; disponible.",1);

}


$datosp=@fopen($datos,"r");


if($datosp==FALSE)
{
	
	mensaje("Error: La informaci&oacute;n para poblar la base de datos ubicada en:<br><b>".$arquitectura."</b><br>no puede ser leida.",0);
	$error=FALSE;

}
else
{
	mensaje("OK: Informaci&oacute;n para poblar la base de datos:<br><b>".$arquitectura."</b><br>est&aacute; disponible.",1);

}



if(isset($error))
{
	mensaje("<b>Por favor revisar los datos de configuraci&oacute;n y/o la estructura de archivos<br>Existen errores que deben ser corregidos para poder continuar con la instalaci&oacute;n.</b>",0);

}
else
{
	$instrucciones=instruccion_sql($arquitecturap);
	$i=0;
	while (list ($clave, $cadena_sql) = each ($instrucciones)) 
	{
    		$resultado=mysql_query($cadena_sql,$conexion);
    		if($resultado==TRUE)
    		{
    			$i++;
    		}
	}
	
	if($instalar["total_tablas"]==$i)
	{
		mensaje("OK: Estructura de base de datos creada.",1);
		$resultado=TRUE;
		while(list($clave,$valor)=each($_REQUEST))
		{
			if(	($clave!="administrador")
				&&($clave!="db_sys")
				&&($clave!="db_dns")
				&&($clave!="db_name")
				&&($clave!="db_user")
				&&($clave!="db_password")
				)
			{
				if($clave=="raiz_documento")
				{
					$valor.=$_REQUEST["site"];
				}
				$cadena_sql="INSERT INTO ";
				$cadena_sql.=$_REQUEST["prefijo"]."configuracion ";
				$cadena_sql.="(";
				$cadena_sql.="`id_parametro` , ";
				$cadena_sql.="`parametro` , ";
				$cadena_sql.="`valor` ";
				$cadena_sql.=") ";
				$cadena_sql.="VALUES ";
				$cadena_sql.="( ";
				$cadena_sql.="'', ";
				$cadena_sql.="'".$clave."',";
				$cadena_sql.="'".$valor."' ";
				$cadena_sql.=")";
				//echo $cadena_sql."<br>";
				$resultado&=mysql_query($cadena_sql,$conexion);
			}			
		}		
		
		if($resultado!=TRUE)
		{
			mensaje("Error: Datos de configuraci&oacute;n no ingresados.",0);	
		}
		else
		{
			mensaje("OK: Datos de configuraci&oacute;n insertados.",1);	
			$instrucciones=instruccion_sql($datosp);
			
			while (list ($clave, $cadena_sql) = each ($instrucciones)) 
			{
				$resultado&=mysql_query($cadena_sql,$conexion);
				
			}
			
			if($resultado!=TRUE)
			{
				mensaje("Error: Datos del sitio no ingresados.",0);	
			}
			else
			{
				mensaje("OK: Datos del sitio ingresados.",1);
				
				$linea=codificar($_REQUEST["db_sys"]);
				$resultado&=fwrite($fp,$linea."\n");
				$linea=codificar($_REQUEST["db_dns"]);
				$resultado&=fwrite($fp,$linea."\n");
				$linea=codificar($_REQUEST["db_name"]);
				$resultado&=fwrite($fp,$linea."\n");
				$linea=codificar($_REQUEST["db_user"]);
				$resultado&=fwrite($fp,$linea."\n");
				$linea=codificar($_REQUEST["db_password"]);
				$resultado&=fwrite($fp,$linea."\n");	
				$linea=codificar($_REQUEST["prefijo"]);
				$resultado&=fwrite($fp,$linea."\n");	
				
				
				if($resultado!=TRUE)
				{
					mensaje("Error: Archivo de configuraci&oacute;n corrupto.",0);		
				}
				else
				{
					mensaje("OK: Archivo de configuraci&oacute;n escrito correctamente.",1);		
					mensaje("Instalaci&oacute;n Completa.<br>Por favor elimine la carpeta <b>/instalar</b> y todos sus componentes.",1);
					?><br>
						<table align="center" style="text-align: left;" border="0"  cellspacing="0"  width="80%">
						<tr>
							<td >
								<table align="center" cellpadding="10" cellspacing="0" >
									<tr class="bloquecentralcuerpo">
										<td valign="middle" align="right" >
											<a href="<? echo $_REQUEST["host"].$_REQUEST["site"]?>/index.php">Terminar</a>
										</td>
									</tr>
								</table> 
							</td>
						</tr>  
						</table>
						<br><?		
				}	
			}
		}
		
		if(isset($_REQUEST["administrador"])&&isset($_REQUEST["clave"]))
		{
			$cadena_sql="INSERT INTO ";
			$cadena_sql.=$_REQUEST["prefijo"]."registrado ";
			$cadena_sql.="(";
			$cadena_sql.="`id_usuario` , ";
			$cadena_sql.="`nombre` , ";
			$cadena_sql.="`apellido` , ";
			$cadena_sql.="`correo` , ";
			$cadena_sql.="`telefono` , ";
			$cadena_sql.="`usuario` , ";
			$cadena_sql.="`clave`  ";
			$cadena_sql.=") ";
			$cadena_sql.="VALUES ";
			$cadena_sql.="( ";
			$cadena_sql.="'', ";
			$cadena_sql.="'Administrador', ";
			$cadena_sql.="'General', ";
			$cadena_sql.="'".$_REQUEST["correo"]."', ";
			$cadena_sql.="'', ";
			$cadena_sql.="'".$_REQUEST["administrador"]."',";
			$cadena_sql.="'".md5($_REQUEST["clave"])."' ";
			$cadena_sql.=")";
			$resultado&=mysql_query($cadena_sql,$conexion);
		
			$id_registrado=mysql_insert_id($conexion);
			
			$cadena_sql="INSERT INTO ";
			$cadena_sql.=$_REQUEST["prefijo"]."registrado_subsistema ";
			$cadena_sql.="(";
			$cadena_sql.="`id_usuario` , ";
			$cadena_sql.="`id_subsistema` , ";
			$cadena_sql.="`estado`  ";
			$cadena_sql.=") ";
			$cadena_sql.="VALUES ";
			$cadena_sql.="( ";
			$cadena_sql.=$id_registrado.", ";
			$cadena_sql.=$instalar["subsistema_administrador"].", ";
			$cadena_sql.="1 ";
			$cadena_sql.=")";
			$resultado&=mysql_query($cadena_sql,$conexion);
			mensaje("OK: Informaci&oacute;n de administrador agregada correctamente.",1);
			
		
		}
		else
		{
			mensaje("Error: Informaci&oacute;n de administraci&oacute;n no puede ser leida.",0);
			$error=FALSE;
		}

		
		
	}
	else
	{
		mensaje("Imposible crear la estructura de la base de datos.",0);
	}
}


//Funcion: Toma un archivo *.sql y pone en  una matriz todas las instrucciones.

function instruccion_sql( $puntero ) 
{
	$instruccion = "";
	$listo = FALSE;
	$indice=0;
		
	while (!feof( $puntero )) 
	{
		$linea = trim(fgets( $puntero, 1024 ));
		$tamanno = strlen( $linea ) - 1;
		
		//Salta espacios en blanco
		if ( $tamanno < 0 ) 
		{ 
			continue; 
		}
		
		//Salta comentarios
		if ( ($linea{0}=='-') && ($linea{1}=="-") ) 
		{ 
			continue; 
		}

		if ( ($linea{$tamanno}==';') && ($linea{$tamanno - 1}!=';')) 
		{
			$listo = TRUE;
			$linea = substr( $linea, 0, $tamanno );
		}
		
		if ( $instruccion != ""  ) 
		{ 
			$instruccion .= " "; 
		}
		$instruccion .= $linea."\n";

		if ($listo) 
		{
			$instruccion = str_replace(";;", ";", $instruccion);
			$instruccion = str_replace("/*prefijo_db*/", $_REQUEST["prefijo"], $instruccion);
			$instrucciones[$indice++] = $instruccion;

			$instruccion= '';
			$listo = FALSE;
		}
	}
	fclose( $puntero );
	return $instrucciones;
}
	?>		
	</td>
	</tr>
</table>
</body>
</html>

<?

function mensaje($mensaje="",$tipo=NULL)
{
?><table align="center" style="text-align: left;" border="0"  cellspacing="0"  width="600px">
	<tr>
		<td >
			<table cellpadding="10" cellspacing="0" >
				<tr class="bloquecentralcuerpo">
					<td valign="middle">
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


function codificar($cadena)
{
	$cadena=base64_encode($cadena);
	$cadena=strrev($cadena);
	return $cadena;

}
?>
