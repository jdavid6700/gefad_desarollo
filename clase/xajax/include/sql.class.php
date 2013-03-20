<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/
/*---------------------------------------------------------------------------------------
 |				Control Versiones				    	|
 ----------------------------------------------------------------------------------------
 | fecha      |        Autor            | version     |              Detalle            |
 ----------------------------------------------------------------------------------------

 ----------------------------------------------------------------------------------------
*/

if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;		
}
require_once("clase/config.class.php");
	setlocale(LC_MONETARY, 'en_US');
	$esta_configuracion=new config();
	$configuracion=$esta_configuracion->variable();
	 
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sql.class.php");

class sql_xajax extends sql
{	//@ Método que crea las sentencias sql para el modulo admin_noticias
	function cadena_sql($configuracion,$tipo,$variable)
		{
		    switch($tipo)
			{
					
			case "buscar_area":

                                $this->cadena_sql="SELECT DISTINCT ";
                                $this->cadena_sql.="id_area ,";
                                $this->cadena_sql.="nombre ";
                                $this->cadena_sql.=" FROM ";
                                $this->cadena_sql.=$configuracion["prefijo"]."area";
                                $this->cadena_sql.=" WHERE ";
                                $this->cadena_sql.="id_dependencia=";
                                $this->cadena_sql.="'".$variable."' ";
                                $this->cadena_sql.="ORDER BY nombre ASC";
                              
                              
                           break;
                       
                       case "buscar_doc":

                                $this->cadena_sql="SELECT DISTINCT ";
                                $this->cadena_sql.="tconcep.id_tconcep TCONCEP,";
                                $this->cadena_sql.="tconcep.nombre CONCEP";
                                $this->cadena_sql.=" FROM ";
                                $this->cadena_sql.=$configuracion["prefijo"]."tipo_concepto tconcep";
                                $this->cadena_sql.=" INNER JOIN ";
                                $this->cadena_sql.=$configuracion["prefijo"]."tconcep_ttramite ttram";
                                $this->cadena_sql.=" ON tconcep.id_tconcep=ttram.id_tconcep AND ttram.estado=1 ";
                                $this->cadena_sql.=" WHERE ";
                                $this->cadena_sql.="ttram.id_Ttramite=";
                                $this->cadena_sql.="'".$variable[0]."' ";
                                $this->cadena_sql.="ORDER BY tconcep.nombre ASC";
                           break;
                       
                       
                       case "busqueda_radicado":
								
				$this->cadena_sql= "SELECT ";
				$this->cadena_sql.= "sol.id_sol ID_RAD, ";
				$this->cadena_sql.= "sol.id_Ttramite ID_TRAM, ";
                                $this->cadena_sql.= "area.id_area ID_AREA, ";
                                $this->cadena_sql.= "area.nombre AREA, ";
                                $this->cadena_sql.= "area_tram.posicion_tramite POS ";
				$this->cadena_sql.= "FROM ";
				$this->cadena_sql.= $configuracion["prefijo"]."tsolicitud sol ";
                                $this->cadena_sql.= " INNER JOIN ";
                                $this->cadena_sql.= $configuracion["prefijo"]."tramita_solicitud tram_sol ON tram_sol.id_sol=sol.id_sol ";
                                $this->cadena_sql.= " INNER JOIN ";
                                $this->cadena_sql.= $configuracion["prefijo"]."area area ON area.id_area=tram_sol.id_area ";
                                $this->cadena_sql.=" INNER JOIN ";
                                $this->cadena_sql.= $configuracion["prefijo"]."area_ttramite area_tram ON area_tram.id_area=area.id_area AND area_tram.id_Ttramite=sol.id_Ttramite ";
                                $this->cadena_sql.= "WHERE ";
                                $this->cadena_sql.= " sol.id_sol='".$variable."'"; 
                                $this->cadena_sql.= " ORDER BY area_tram.posicion_tramite DESC"; 
                                //echo $this->cadena_sql;
                              break;
                          
                        case "busqueda_usuario_area":
				
				$this->cadena_sql= "SELECT DISTINCT ";
				$this->cadena_sql.= "usu.id_usuario ID_USU, ";
                                $this->cadena_sql.= "concat(usu.nombre,' ',usu.apellido,' | ',area.nombre) USU ";
                                $this->cadena_sql.= "FROM ";
                                $this->cadena_sql.= $configuracion["prefijo"]."registrado usu";
                                $this->cadena_sql.=" INNER JOIN ";
                                $this->cadena_sql.= $configuracion["prefijo"]."registrado_subsistema sub ";
                                $this->cadena_sql.= " ON usu.id_usuario=sub.id_usuario AND sub.estado=1 ";
                                $this->cadena_sql.=" INNER JOIN ";
                                $this->cadena_sql.= $configuracion["prefijo"]."area_ttramite area_tram ";
                                $this->cadena_sql.= " ON area_tram.id_area=sub.id_area ";
                                $this->cadena_sql.=" INNER JOIN ";
                                $this->cadena_sql.= $configuracion["prefijo"]."area area ON area.id_area=area_tram.id_area ";
                                /*$this->cadena_sql.=" INNER JOIN ";
                                $this->cadena_sql.= $configuracion["prefijo"]."area_ttramite area_tram ON area_tram.id_area=area.id_area AND area_tram.id_Ttramite=sol.id_Ttramite ";*/
                                
                                $this->cadena_sql.="WHERE "; 
				$this->cadena_sql.="area_tram.id_Ttramite=".$variable['id_tramite'];
                                $this->cadena_sql.=" AND area_tram.posicion_tramite=".$variable['posicion'];
                                
							
                             //  echo $this->cadena_sql;exit;
				break;	
                            
                         case "areas_tramite":
				
				$this->cadena_sql= "SELECT MAX";
                                $this->cadena_sql.="(area_tram.posicion_tramite) areas ";
                                $this->cadena_sql.=" FROM ";
                                $this->cadena_sql.= $configuracion["prefijo"]."area_ttramite area_tram ";
                                $this->cadena_sql.= " WHERE ";
                                $this->cadena_sql.= " area_tram.id_Ttramite='".$variable['id_tramite']."' ";
                                break;
			}
		return $this->cadena_sql;
		
		}
}
?>
