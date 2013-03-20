<?php
//Se invoca cuando se cambia de pais
function pais($valor)
{
	require_once("clase/config.class.php");
	setlocale(LC_MONETARY, 'en_US');
	
	$esta_configuracion=new config();
	$configuracion=$esta_configuracion->variable(); 
	//Buscar un registro que coincida con el valor
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
	
	$acceso_db=new dbms($configuracion);
	$enlace=$acceso_db->conectar_db();	
	$valor=$acceso_db->verificar_variables($valor);
	$html=new html();
	

	if (is_resource($enlace) && $valor==170)
	{
		
		
		$busqueda="SELECT ";
		$busqueda.="id_localidad, ";
		$busqueda.="nombre ";
		$busqueda.="FROM ";
		$busqueda.=$configuracion["prefijo"]."localidad ";
		$busqueda.="WHERE ";
		$busqueda.="id_pais=170 ";
		$busqueda.="AND ";
		$busqueda.="tipo=1 ";
		$busqueda.="ORDER BY nombre";
		$configuracion["ajax_function"]="xajax_region";
		$configuracion["ajax_control"]="region";
		$mi_cuadro=$html->cuadro_lista($busqueda,"region",$configuracion,5,2,FALSE,0,"region");
		
		$respuesta = new xajaxResponse();
		$respuesta->addAssign("divRegion","innerHTML",$mi_cuadro);
		
		$busqueda="SELECT ";
		$busqueda.="id_localidad, ";
		$busqueda.="nombre ";
		$busqueda.="FROM ";
		$busqueda.=$configuracion["prefijo"]."localidad ";
		$busqueda.="WHERE ";
		$busqueda.="id_pais=170 ";
		$busqueda.="AND ";
		$busqueda.="id_padre=5 ";						
		$busqueda.="AND ";
		$busqueda.="tipo=2 ";
		$busqueda.="ORDER BY nombre";						
		$mi_cuadro=$html->cuadro_lista($busqueda,"ciudad",$configuracion,1,0,FALSE,0,"ciudad");
		$respuesta->addAssign("divCiudad","innerHTML",$mi_cuadro);
		
		
	}
	else
	{
		
		$cadena_html="<table width='50%'>\n";
		$cadena_html.="<tr class='bloquecentralcuerpo'>\n";
		$cadena_html.="<td class='centrar'>\n";
		$cadena_html.="<span class='texto_gris'>No Aplica.</span>";
		$cadena_html.="</td>\n";
		$cadena_html.="</tr>\n";
		$cadena_html.="</table>\n";
		$respuesta = new xajaxResponse();
		$respuesta->addAssign("divRegion","innerHTML",$cadena_html);
		
		$mi_cuadro=$html->cuadro_texto("ciudad",$configuracion,"Ingrese nombre de Ciudad",0,0,"",25,100,"texto_cursiva");
		$respuesta->addAssign("divCiudad","innerHTML",$mi_cuadro);
	
	
	}
	return $respuesta;
		
	
}


function region($valor)
{
	require_once("clase/config.class.php");
	setlocale(LC_MONETARY, 'en_US');
	
	$esta_configuracion=new config();
	$configuracion=$esta_configuracion->variable(); 
	//Buscar un registro que coincida con el valor
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
	
	$acceso_db=new dbms($configuracion);
	$enlace=$acceso_db->conectar_db();	
	$valor=$acceso_db->verificar_variables($valor);
	$html=new html();
	

	if (is_resource($enlace))
	{
		
		
		$busqueda="SELECT ";
		$busqueda.="id_localidad, ";
		$busqueda.="nombre ";
		$busqueda.="FROM ";
		$busqueda.=$configuracion["prefijo"]."localidad ";
		$busqueda.="WHERE ";
		$busqueda.="id_pais=170 ";
		$busqueda.="AND ";
		$busqueda.="id_padre=".$valor." ";						
		$busqueda.="AND ";
		$busqueda.="tipo=2 ";
		$busqueda.="ORDER BY nombre";						
		$mi_cuadro=$html->cuadro_lista($busqueda,"ciudad",$configuracion,1,0,FALSE,0,"ciudad");
		$respuesta = new xajaxResponse();
		$respuesta->addAssign("divCiudad","innerHTML",$mi_cuadro);
		
		
	}
	else
	{
		
		$cadena_html="<table width='50%'>\n";
		$cadena_html.="<tr class='bloquecentralcuerpo'>\n";
		$cadena_html.="<td class='centrar'>\n";
		$cadena_html.="<span class='texto_gris'>No Aplica.</span>";
		$cadena_html.="</td>\n";
		$cadena_html.="</tr>\n";
		$cadena_html.="</table>\n";
		$respuesta = new xajaxResponse();
		$respuesta->addAssign("divRegion","innerHTML",$cadena_html);
		
		$mi_cuadro=$html->cuadro_texto("ciudad",$configuracion,"Ingrese nombre de Ciudad",0,0,"",25,100,"texto_cursiva");
		$respuesta->addAssign("divCiudad","innerHTML",$mi_cuadro);
	
	
	}
	return $respuesta;
	
}

function paisFormacion($valor)
{
	require_once("clase/config.class.php");
	setlocale(LC_MONETARY, 'en_US');
	
	$esta_configuracion=new config();
	$configuracion=$esta_configuracion->variable(); 
	//Buscar un registro que coincida con el valor
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
	
	$acceso_db=new dbms($configuracion);
	$enlace=$acceso_db->conectar_db();	
	$valor=$acceso_db->verificar_variables($valor);
	$html=new html();
	

	if (is_resource($enlace) && $valor==170)
	{
		
		
		$busqueda="SELECT ";
		$busqueda.="id_localidad, ";
		$busqueda.="nombre ";
		$busqueda.="FROM ";
		$busqueda.=$configuracion["prefijo"]."localidad ";
		$busqueda.="WHERE ";
		$busqueda.="id_pais=170 ";
		$busqueda.="AND ";
		$busqueda.="tipo=1 ";
		$busqueda.="ORDER BY nombre";
		$configuracion["ajax_function"]="xajax_regionFormacion";
		$configuracion["ajax_control"]="regionFormacion";
		$mi_cuadro=$html->cuadro_lista($busqueda,"regionFormacion",$configuracion,5,2,FALSE,0,"regionFormacion");
		
		$respuesta = new xajaxResponse();
		$respuesta->addAssign("divRegionFormacion","innerHTML",$mi_cuadro);
		
		
		$busqueda="SELECT ";
		$busqueda.="id_localidad, ";
		$busqueda.="nombre ";
		$busqueda.="FROM ";
		$busqueda.=$configuracion["prefijo"]."localidad ";
		$busqueda.="WHERE ";
		$busqueda.="id_pais=170 ";
		$busqueda.="AND ";
		$busqueda.="id_padre=5 ";						
		$busqueda.="AND ";
		$busqueda.="tipo=2 ";
		$busqueda.="ORDER BY nombre";						
		$mi_cuadro=$html->cuadro_lista($busqueda,"ciudadFormacion",$configuracion,1,0,FALSE,0,"ciudadFormacion");
		$respuesta->addAssign("divCiudadFormacion","innerHTML",$mi_cuadro);
		
		
	}
	else
	{
		
		$cadena_html="<table width='50%'>\n";
		$cadena_html.="<tr class='bloquecentralcuerpo'>\n";
		$cadena_html.="<td class='centrar'>\n";
		$cadena_html.="<span class='texto_gris'>No Aplica.</span>";
		$cadena_html.="</td>\n";
		$cadena_html.="</tr>\n";
		$cadena_html.="</table>\n";
		$respuesta = new xajaxResponse();
		$respuesta->addAssign("divRegionFormacion","innerHTML",$cadena_html);
		
		$mi_cuadro=$html->cuadro_texto("ciudadFormacion",$configuracion,"Ingrese nombre de Ciudad",0,0,"",25,100,"texto_cursiva");
		$respuesta->addAssign("divCiudadFormacion","innerHTML",$mi_cuadro);
	
	
	}
	return $respuesta;
		
	
}


function regionFormacion($valor)
{
	require_once("clase/config.class.php");
	setlocale(LC_MONETARY, 'en_US');
	
	$esta_configuracion=new config();
	$configuracion=$esta_configuracion->variable(); 
	//Buscar un registro que coincida con el valor
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
	
	$acceso_db=new dbms($configuracion);
	$enlace=$acceso_db->conectar_db();	
	$valor=$acceso_db->verificar_variables($valor);
	$html=new html();
	

	if (is_resource($enlace))
	{
		
		
		$busqueda="SELECT ";
		$busqueda.="id_localidad, ";
		$busqueda.="nombre ";
		$busqueda.="FROM ";
		$busqueda.=$configuracion["prefijo"]."localidad ";
		$busqueda.="WHERE ";
		$busqueda.="id_pais=170 ";
		$busqueda.="AND ";
		$busqueda.="id_padre=".$valor." ";						
		$busqueda.="AND ";
		$busqueda.="tipo=2 ";
		$busqueda.="ORDER BY nombre";						
		$mi_cuadro=$html->cuadro_lista($busqueda,"ciudadFormacion",$configuracion,1,0,FALSE,0,"ciudadFormacion");
		$respuesta = new xajaxResponse();
		$respuesta->addAssign("divCiudadFormacion","innerHTML",$mi_cuadro);
		
		
	}
	else
	{
		
		$cadena_html="<table width='50%'>\n";
		$cadena_html.="<tr class='bloquecentralcuerpo'>\n";
		$cadena_html.="<td class='centrar'>\n";
		$cadena_html.="<span class='texto_gris'>No Aplica.</span>";
		$cadena_html.="</td>\n";
		$cadena_html.="</tr>\n";
		$cadena_html.="</table>\n";
		$respuesta = new xajaxResponse();
		$respuesta->addAssign("divRegionFormacion","innerHTML",$cadena_html);
		
		$mi_cuadro=$html->cuadro_texto("ciudadFormacion",$configuracion,"Ingrese nombre de Ciudad",0,0,"",25,100,"texto_cursiva");
		$respuesta->addAssign("divCiudadFormacion","innerHTML",$mi_cuadro);
	
	
	}
	return $respuesta;
		
	
}



?>
