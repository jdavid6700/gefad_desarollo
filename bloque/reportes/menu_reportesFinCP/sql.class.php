<?php
/*--------------------------------------------------------------------------------------------------------------------------
 @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sql.class.php");

class sql_menuReporteFin extends sql
{
	function cadena_sql($configuracion,$conexion, $opcion,$variable="")
	{

		switch($opcion)
		{
                        case "vigencias_reservas":
				$cadena_sql=" SELECT DISTINCT";
				$cadena_sql.=" RESERVA.VIGENCIA VIGENCIA ";
				$cadena_sql.=" FROM PR_COMUN.PR_RESERVAS RESERVA ";
				$cadena_sql.=" WHERE RESERVA.CODIGO_COMPANIA='230' ";
				$cadena_sql.=" ORDER BY RESERVA.VIGENCIA ";
				//echo $cadena_sql;
				break;
                       
			default:
				$cadena_sql="";
				break;
		}//fin switch
		return $cadena_sql;
	}// fin funcion cadena_sql


}//fin clase sql_adminNovedad
?>

