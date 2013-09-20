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

class sql_reporteFinanciero extends sql
{
	function cadena_sql($configuracion,$conexion, $opcion,$variable="")
	{

		switch($opcion)
		{

			case "buscarParametrosReporte":
                                
                                $cadena_sql=" SELECT DISTINCT";
                                $cadena_sql.=" rep.id_reporte id_rep, ";
                                $cadena_sql.=" rep.nombre rep_nom, ";
                                $cadena_sql.=" rep.titulo rep_titulo, ";
                                $cadena_sql.=" rep.parametros usa_par, ";
                                $cadena_sql.=" p_rep.id_parametro id_par , ";
                                $cadena_sql.=" p_rep.nombre nombre_par, ";
                                $cadena_sql.=" p_rep.conexion conexion_par , ";
                                $cadena_sql.=" p_rep.consulta sql_par , ";
                                $cadena_sql.=" p_rep.carga_parametro alimenta_par, ";
                                $cadena_sql.=" p_rep.control_parametro control_busqueda, ";
                                $cadena_sql.=" p_rep.tipo_caja caja_html ";
                                $cadena_sql.=" FROM gestion_reportes rep";
                                $cadena_sql.=" LEFT OUTER JOIN gestion_parametros_reporte p_rep";
                                $cadena_sql.=" ON p_rep.id_reporte=rep.id_reporte AND p_rep.estado='1'";
                                $cadena_sql.=" WHERE rep.nombre='".$variable['nombre']."'";
                                $cadena_sql.=" ORDER BY p_rep.id_parametro ";
				
				break;                            
                            
			case "buscarReporte":
                                
                                $cadena_sql=" SELECT ";
                                $cadena_sql.=" id_reporte rep_id,";
                                $cadena_sql.=" nombre rep_nom, ";
                                $cadena_sql.=" titulo rep_titulo, ";
                                $cadena_sql.=" descripcion rep_desc, ";
                                $cadena_sql.=" tipo_usuario rep_perfil, ";
                                $cadena_sql.=" conexion rep_conect, ";
                                $cadena_sql.=" consulta rep_sql,";
                                $cadena_sql.=" parametros rep_parametros,";
                                $cadena_sql.=" estado rep_est";
                                $cadena_sql.=" FROM gestion_reportes ";
                                $cadena_sql.=" WHERE nombre='".$variable['nombre']."'";
				
				break;
                            
     

			default:
				$cadena_sql="";
				break;
		}//fin switch
		return $cadena_sql;
	}// fin funcion cadena_sql


}//fin clase sql_adminNovedad
?>

