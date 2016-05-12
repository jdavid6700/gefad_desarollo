<?
/*
############################################################################
#    UNIVERSIDAD DISTRITAL Francisco Jose de Caldas                        #
#    Desarrollo Por: Teleinformatics Technology Group                      #
#    Paulo Cesar Coronado 2004 - 2005                                      #
#    paulo_cesar@ttg.com                                                   #
#    Copyright: Vea el archivo EULA.txt que viene con la distribucion      #
############################################################################
*/
?>
<?
/****************************************************************************************************************
* @name          formulario.class.php 
* @editor        Paulo Cesar Coronado
* @revision      Última revisión 26 de junio de 2005
*******************************************************************************************************************
* @subpackage   admin_usuario
* @package	bloques
* @copyright    
* @version      0.2
* @author      	Paulo Cesar Coronado
* @link		N/D
* @description  Bloque principal para la administración de usuarios
*
*****************************************************************************************************************/
?><?
include_once("../clase/config.class.php");
$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../"); 
if(isset($_POST["aceptar"]))
{

	
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/dbms.class.php");
	$acceso_db=new dbms($configuracion);
	$enlace=$acceso_db->conectar_db();
	if (is_resource($enlace))
	{
		$identificador=mysql_list_fields($configuracion["db_nombre"],$_POST["tabla"],$enlace);
		if($identificador!=-1)
		{
			$cantidad_campos=mysql_num_fields($identificador);
			$insertar="";
			$valor_insertar="";
			$valor_update="";
			$controles="";
			$controles_llenos="";
			$controles_mostrar="";
			for($contador=0;$contador<$cantidad_campos;$contador++)
			{
				$tipo["control"]["nombre"]=mysql_field_name($identificador,$contador);
				$tipo["control"]["tipo"]=mysql_field_type($identificador,$contador);
				$tamanno=mysql_field_len($identificador,$contador);
				
				if($tamanno>40)
				{
					$tipo["control"]["tamanno"]=40;				
				}
				else
				{
					$tipo["control"]["tamanno"]=$tamanno;				
				}
				if($contador==($cantidad_campos-1))
				{
					$insertar.="\$cadena_sql.=\"`".$tipo["control"]["nombre"]."` \";\n";
					if($tipo["control"]["tipo"]!="int")
					{
						$valor_insertar.="\$cadena_sql.=\"'\".\$_POST['".$tipo["control"]["nombre"]."'].\"' \";\n";
						$valor_update.="\$cadena_sql.=\"`".$tipo["control"]["nombre"]."`='\".\$_POST['".$tipo["control"]["nombre"]."'].\"' \";\n";
					}
					else
					{
						$valor_insertar.="\$cadena_sql.=\"'\".\$_POST['".$tipo["control"]["nombre"]."'].\"' \";\n";
						$valor_update.="\$cadena_sql.=\"`".$tipo["control"]["nombre"]."`='\".\$_POST['".$tipo["control"]["nombre"]."'].\"' \";\n";
					}	
				}
				else
				{
					$insertar.="\$cadena_sql.=\"`".$tipo["control"]["nombre"]."`, \";\n";
					
					if($tipo["control"]["tipo"]!="int")
					{
						$valor_insertar.="\$cadena_sql.=\"'\".\$_POST['".$tipo["control"]["nombre"]."'].\"', \";\n";
						$valor_update.="\$cadena_sql.=\"`".$tipo["control"]["nombre"]."`='\".\$_POST['".$tipo["control"]["nombre"]."'].\"', \";\n";
					}
					else
					{
						$valor_insertar.="\$cadena_sql.=\"'\".\$_POST['".$tipo["control"]["nombre"]."'].\"', \";\n";
						$valor_update.="\$cadena_sql.=\"`".$tipo["control"]["nombre"]."`='\".\$_POST['".$tipo["control"]["nombre"]."'].\"', \";\n";
					}	
				}
				$tipo["control"]["max_tamanno"]=$tamanno;				
				//Crear una fila que contenga el control
				$controles.="	<tr class='bloquecentralcuerpo' onmouseover=\"setPointer(this, <? echo \$contador ?>, 'over', '<? echo \$tema->celda ?>', '<? echo \$tema->apuntado ?>', '<? echo \$tema->seleccionado ?>');\" onmouseout=\"setPointer(this, <? echo \$contador ?>, 'out', '<? echo \$tema->celda ?>', '<? echo \$tema->apuntado ?>', '<? echo \$tema->seleccionado ?>');\" onmousedown=\"setPointer(this, <? echo \$contador++ ?>, 'click', '<? echo \$tema->celda ?>', '<? echo \$tema->apuntado ?>', '<? echo \$tema->seleccionado ?>');\">\n";
				$controles.="		<td bgcolor='<? echo \$tema->celda ?>'>\n";
				$controles.="			".$tipo["control"]["nombre"]."\n";
				$controles.="		</td>\n";
				$controles.="		<td bgcolor='<? echo \$tema->celda ?>'>\n";
				$controles.="			".control_html($tipo)."\n";
				$controles.="		</td>\n";
				$controles.="	</tr>\n";
				
				$tipo["control"]["valor"]="<? echo \$registro[0][".$contador."] ?>";
				$controles_llenos.="	<tr class='bloquecentralcuerpo' onmouseover=\"setPointer(this, <? echo \$contador ?>, 'over', '<? echo \$tema->celda ?>', '<? echo \$tema->apuntado ?>', '<? echo \$tema->seleccionado ?>');\" onmouseout=\"setPointer(this, <? echo \$contador ?>, 'out', '<? echo \$tema->celda ?>', '<? echo \$tema->apuntado ?>', '<? echo \$tema->seleccionado ?>');\" onmousedown=\"setPointer(this, <? echo \$contador++ ?>, 'click', '<? echo \$tema->celda ?>', '<? echo \$tema->apuntado ?>', '<? echo \$tema->seleccionado ?>');\">\n";
				$controles_llenos.="		<td bgcolor='<? echo \$tema->celda ?>'>\n";
				$controles_llenos.="			".$tipo["control"]["nombre"]."\n";
				$controles_llenos.="		</td>\n";
				$controles_llenos.="		<td bgcolor='<? echo \$tema->celda ?>'>\n";
				$controles_llenos.="			".control_html($tipo)."\n";
				$controles_llenos.="		</td>\n";
				$controles_llenos.="	</tr>\n";
				
				
				$controles_mostrar.="	<tr class='bloquecentralcuerpo' onmouseover=\"setPointer(this, <? echo \$contador ?>, 'over', '<? echo \$tema->celda ?>', '<? echo \$tema->apuntado ?>', '<? echo \$tema->seleccionado ?>');\" onmouseout=\"setPointer(this, <? echo \$contador ?>, 'out', '<? echo \$tema->celda ?>', '<? echo \$tema->apuntado ?>', '<? echo \$tema->seleccionado ?>');\" onmousedown=\"setPointer(this, <? echo \$contador++ ?>, 'click', '<? echo \$tema->celda ?>', '<? echo \$tema->apuntado ?>', '<? echo \$tema->seleccionado ?>');\">\n";
				$controles_mostrar.="		<td bgcolor='<? echo \$tema->celda ?>'>\n";
				$controles_mostrar.="			".$tipo["control"]["nombre"].":\n";
				$controles_mostrar.="		</td>\n";
				$controles_mostrar.="		<td bgcolor='<? echo \$tema->celda ?>'>\n";
				$controles_mostrar.="			<? echo \$registro[0][".$contador."] ?>\n";
				$controles_mostrar.="		</td>\n";
				$controles_mostrar.="	</tr>\n";
				unset($tipo["control"]["valor"]);
				
				
			}
			
			
			$formulario="<form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='pagina_que_procesa' name='nombreformulario'>\n";
			$formulario.="<table class='bloquelateral' align='center' width='100%' cellpadding='0' cellspacing='0'>\n";
			$formulario.="<tr>\n";
			$formulario.="<td>\n";
			$formulario.="<table align='center' width='100%' cellpadding='7' cellspacing='1'>\n";
			$formulario.=$controles;
			//boton y campois hidden necesarios
			$formulario.="	<tr align='center'>\n";
        		$formulario.="		<td colspan='2' rowspan='1'>\n";
			$formulario.="			<input type='hidden' name='action' value='pagina_action'>\n";
			$formulario.="			<input name='aceptar' value='Aceptar' type='submit'><br>\n";
			$formulario.="		</td>\n";
			$formulario.="	</tr>\n";
			$formulario.="</table>\n";
			$formulario.="</td>\n";
			$formulario.="</tr>\n";
			$formulario.="</table>\n";
			$formulario.="</form>\n";
			
			$formulario_lleno="<form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='pagina_que_procesa' name='nombreformulario'>\n";
			$formulario_lleno.="<table class='bloquelateral' align='center' width='100%' cellpadding='0' cellspacing='0'>\n";
			$formulario_lleno.="<tr>\n";
			$formulario_lleno.="<td>\n";
			$formulario_lleno.="<table align='center' width='100%' cellpadding='7' cellspacing='1'>";
			$formulario_lleno.=$controles_llenos;
			//boton y campos hidden necesarios
			$formulario_lleno.="	<tr align='center'>\n";
			$formulario_lleno.="		<td colspan='2' rowspan='1' align='center'>\n";
			$formulario_lleno.="			<table align='center' width='50%'>\n";
			$formulario_lleno.="			<tr align='center'>\n";
			$formulario_lleno.="			<td>\n";
			$formulario_lleno.="				<input type='hidden' name='action' value='pagina_action'>\n";
			$formulario_lleno.="				<input name='aceptar' value='Aceptar' type='submit'><br>\n";
			$formulario_lleno.="			</td>\n";
			$formulario_lleno.="			<td>\n";
			$formulario_lleno.="				<input name='cancelar' value='Cancelar' type='submit'><br>\n";
			$formulario_lleno.="			</td>\n";
			$formulario_lleno.="			</tr>\n";
			$formulario_lleno.="			</table>\n";							
			$formulario_lleno.="		</td>\n";
			$formulario_lleno.="	</tr>\n";
			$formulario_lleno.="</table>\n";
			$formulario_lleno.="</td>\n";
			$formulario_lleno.="</tr>\n";
			$formulario_lleno.="</table>\n";
			$formulario_lleno.="</form>\n";
			
			$formulario_mostrar="<table class='bloquelateral' align='center' width='100%' cellpadding='0' cellspacing='0'>\n";
			$formulario_mostrar.="<tr>\n";
			$formulario_mostrar.="<td>\n";
			$formulario_mostrar.="<table align='center' width='100%' cellpadding='7' cellspacing='1'>";
			$formulario_mostrar.=$controles_mostrar;
			$formulario_mostrar.="</table>\n";
			$formulario_mostrar.="</td>\n";
			$formulario_mostrar.="</tr>\n";
			$formulario_mostrar.="</table>\n";
			
			
			
			$cadena_insertar="\$cadena_sql=\"INSERT INTO ".$_POST["tabla"]." \"; \n";
			$cadena_insertar.="\$cadena_sql.=\"( \";\n";
			$cadena_insertar.=$insertar;
			$cadena_insertar.="\$cadena_sql.=\") \";\n";
			$cadena_insertar.="\$cadena_sql.=\"VALUES \";\n";
			$cadena_insertar.="\$cadena_sql.=\"( \";\n";
			$cadena_insertar.=$valor_insertar;
			$cadena_insertar.="\$cadena_sql.=\")\";\n";
			
			$cadena_seleccionar="\$cadena_sql=\"SELECT \";\n";
			$cadena_seleccionar.=$insertar;
			$cadena_seleccionar.="\$cadena_sql.=\"FROM \";\n";
			$cadena_seleccionar.="\$cadena_sql.=\"".$_POST["tabla"]." \"; \n";
			
			$cadena_update="\$cadena_sql=\"UPDATE ".$_POST["tabla"]." \"; \n";
			$cadena_update.="\$cadena_sql=\"SET ; \n";
			$cadena_update.=$valor_update;
			
			$estado="CERRADO";
			$mi_nombre="../documento/".time().".txt";
			$mi_archivo=abrir_archivo($mi_nombre,$estado);
			echo "<a href='".$mi_nombre."'>Descargar archivo de c&oacute;digo</a>";
			$adorno_html="<br>----------------------------------------------------------------<br>";
			$adorno_html.="<h2>C&oacute;digo para insertar nuevos registros</h2>";
			$adorno_html.="----------------------------------------------------------------<br>";
			$adorno="\n----------------------------------------------------------------\n";
			$adorno.="Codigo para insertar nuevos registros";
			$adorno.="\n----------------------------------------------------------------\n";
			$mi_escritor=escribir_linea($adorno,$mi_archivo,$estado);
			echo $adorno_html;
			$mi_escritor=escribir_linea($cadena_insertar,$mi_archivo,$estado);
			echo $cadena_insertar;
			
			$adorno_html="<br>----------------------------------------------------------------<br>";
			$adorno_html.="<h2>C&oacute;digo para buscar registros</h2>";
			$adorno_html.="----------------------------------------------------------------<br>";
			$adorno="\n----------------------------------------------------------------\n";
			$adorno.="Codigo para buscar registros";
			$adorno.="\n----------------------------------------------------------------\n";
			$mi_escritor=escribir_linea($adorno,$mi_archivo,$estado);
			echo $adorno_html;
			$mi_escritor=escribir_linea($cadena_seleccionar,$mi_archivo,$estado);
			echo $cadena_seleccionar;
			
			$adorno_html="<br>----------------------------------------------------------------<br>";
			$adorno_html.="<h2>C&oacute;digo para UPDATE</h2>";
			$adorno_html.="----------------------------------------------------------------<br>";
			$adorno="\n----------------------------------------------------------------\n";
			$adorno.="Codigo para UPDATE ";
			$adorno.="\n----------------------------------------------------------------\n";
			$mi_escritor=escribir_linea($adorno,$mi_archivo,$estado);
			echo $adorno_html;
			$mi_escritor=escribir_linea($cadena_update,$mi_archivo,$estado);
			echo $cadena_update;
			
			$adorno_html="<br>----------------------------------------------------------------<br>";
			$adorno_html.="<h2>Formulario para insertar nuevos registros</h2>";
			$adorno_html.="----------------------------------------------------------------<br>";
			$adorno="\n----------------------------------------------------------------\n";
			$adorno.="Formulario para insertar nuevos registros";
			$adorno.="\n----------------------------------------------------------------\n";
			$mi_escritor=escribir_linea($adorno,$mi_archivo,$estado);
			echo $adorno_html;
			$mi_escritor=escribir_linea($formulario,$mi_archivo,$estado);
			echo $formulario;
			
			$adorno_html="<br>----------------------------------------------------------------<br>";
			$adorno_html.="<h2>Formulario para editar registros</h2>";
			$adorno_html.="----------------------------------------------------------------<br>";
			$adorno="\n----------------------------------------------------------------\n";
			$adorno.="Formulario para editar registros";
			$adorno.="\n----------------------------------------------------------------\n";
			$mi_escritor=escribir_linea($adorno,$mi_archivo,$estado);
			echo $adorno_html;
			$mi_escritor=escribir_linea($formulario_lleno,$mi_archivo,$estado);
			echo $formulario_lleno;
			
			$adorno_html="<br>----------------------------------------------------------------<br>";
			$adorno_html.="<h2>Tabla para mostrar registros</h2>";
			$adorno_html.="----------------------------------------------------------------<br>";
			$adorno="\n----------------------------------------------------------------\n";
			$adorno.="Tabla para mostrar registros";
			$adorno.="\n----------------------------------------------------------------\n";
			$mi_escritor=escribir_linea($adorno,$mi_archivo,$estado);
			echo $adorno_html;
			$mi_escritor=escribir_linea($formulario_mostrar,$mi_archivo,$estado);
			echo $formulario_mostrar;
			
			$mi_archivo=cerrar_archivo($estado,$mi_archivo);				
			
						
		}
		
		
	}
	
}
else
{
?><form method="post" action="formulario.php" name="crear_formulario" >
  <table class="bloquelateral" align="center" cellpadding="5" cellspacing="1" width="50%" border="1px">
    <tbody>
      <tr bgcolor="#F9F9A8">
        <td align="center" valign="middle">
        <input size="40" tabindex="1" name="tabla">
        </td>
      </tr>
      <tr>
        <td align="center" valign="middle">
        <input value="aceptar" name="aceptar" type="submit">
        </td>
      </tr>
    <tbody>
 </table>
<form>
<?
}
function control_html($tipo)
{
	switch($tipo["control"]["tipo"])
	{	
		
		case "int":
		
		case "string":
		
		case "varchar":
			
			$opcion="";
			
			if(isset($tipo["control"]["nombre"]))
			{
				$opcion.="name='".$tipo["control"]["nombre"]."' " ;
			}
			
			if(isset($tipo["control"]["valor"]))
			{
				$opcion.="value='".$tipo["control"]["valor"]."' " ;
			}
			
			if(isset($tipo["control"]["tamanno"]))
			{
				$opcion.="size='".$tipo["control"]["tamanno"]."' " ;
			}
			
			if(isset($tipo["control"]["max_tamanno"]))
			{
				$opcion.="maxlength='".$tipo["control"]["max_tamanno"]."' " ;
			}
			
			$opcion.="tabindex='<? echo \$tab++ ?>' " ;
			
			$cadena_control="<input type='text' ".$opcion.">";
			
			break;
			
		case "blob":
		case "text":
			
			$opcion="";
			
			if(isset($tipo["control"]["nombre"]))
			{
				$opcion.="name='".$tipo["control"]["nombre"]."' " ;
			}
			
			if(isset($tipo["control"]["columnas"]))
			{
				$opcion.="cols='".$tipo["control"]["columnas"]."' " ;
			}
			else
			{
				$opcion.="cols='20' " ;			
			}
			
			if(isset($tipo["control"]["filas"]))
			{
				$opcion.="rows='".$tipo["control"]["filas"]."' " ;
			}
			else
			{
				$opcion.="rows='2' " ;			
			}
			
			
			if(isset($tipo["control"]["wrap"]))
			{
				$opcion.="wrap='".$tipo["control"]["wrap"]."' " ;
			}
			
			$opcion.="tabindex='<? echo \$tab++ ?>' " ;
			
			$cadena_control="<textarea ".$opcion.">";
			
			if(isset($tipo["control"]["valor"]))
			{
				$cadena_control.=$tipo["control"]["valor"];
			}
			$cadena_control.="</textarea>";			
			break;	
				
			
	}
	return $cadena_control;


}

function abrir_archivo($archivo,&$estado)

{

	if($estado!="CERRADO")

	{

		$error="Error : El archivo est&aacute; en uso";

		return false;

	}	

	 
	if(!empty($archivo))

	{

		$fp=@fopen($archivo,"w+");

	}
	else

	{

		$error="Uso : new escritor( string nombre_archivo,[int tipo_archivo]')";

		return false;

	}	

	if($fp==false)

	{

		$error="Error: Imposible crear o abrir el archivo.";

		return false;

	}

	$estado="ABIERTO";

	return $fp;

}

function cerrar_archivo($estado,$fp)

{

	if($estado!="ABIERTO")

	{

		$error="Error : El archivo se encuentra cerrado..";

		return false;

	}	

	fclose($fp);

	$estado="CERRADO";

	return ;

}

function escribir_linea($linea,$fp,$estado)

{

	//echo $estado;
	if($estado!="ABIERTO")

	{

		$error="Error : No existe un archivo abierto.";

		return false;

	}	

	
	fwrite($fp,$linea);

	return TRUE;
	
}

function descargar($archivo)
{
	global $HTTP_ENV_VARS;
	if(isset($HTTP_ENV_VARS['HTTP_USER_AGENT']) and strpos($HTTP_ENV_VARS['HTTP_USER_AGENT'],'MSIE 5.5'))
	{

		Header('Content-Type: application/dummy');

	}	
	else
	{

		Header('Content-Type: application/octet-stream');

	}	
	if(headers_sent())
	{

		$Error('No se puede enviar datos');

	}
	Header('Content-Length: '.strlen($archivo));

	Header('Content-disposition: attachment; filename='.$archivo);


}
?>