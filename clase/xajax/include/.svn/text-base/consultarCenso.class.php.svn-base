<?php

function consultarCenso($valor)
{
	require_once("clase/config.class.php");
	$esta_configuracion=new config();
	$configuracion=$esta_configuracion->variable(); 
	//Buscar un registro que coincida con el valor
	
	
	$cadena_html="<table width='50%'>\n";
	$cadena_html.="<tr class='bloquecentralcuerpo'>\n";
	$cadena_html.="<td class='centrar'>\n";
	$cadena_html.="<span class='texto_gris'>No Aplica.</span>";
	$cadena_html.="</td>\n";
	$cadena_html.="</tr>\n";
	$cadena_html.="</table>\n";
	
	
	$respuesta = new xajaxResponse();
	$respuesta->addAssign("divRegistro","innerHTML",$cadena_html);
	return $respuesta;
	
}
?>
