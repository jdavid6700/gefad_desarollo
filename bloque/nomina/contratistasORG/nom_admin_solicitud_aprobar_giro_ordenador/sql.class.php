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

class sql_adminSolicitudAprobarGiro extends sql
{
	function cadena_sql($configuracion,$conexion, $opcion,$variable="")
	{
		
		switch($opcion)
		{	
                    	case "nominas_generadas_sin_solicitud_giro":
				$cadena_sql=" SELECT ";
                                $cadena_sql.=" nom_id,";
                                $cadena_sql.=" nom_rubro_interno,";
                                $cadena_sql.=" nom_cod_dep_supervisor ,";
                                $cadena_sql.=" nom_cod_ordenador ,";
                                $cadena_sql.=" nom_num_id_ordenador ,";
                                $cadena_sql.=" nom_anio ,";
                                $cadena_sql.=" nom_mes ,";
                                $cadena_sql.=" nom_fecha_registro ,";
                                $cadena_sql.=" nom_estado,";
                                $cadena_sql.=" nom_estado_reg,";
                                $cadena_sql.=" (SELECT count(dtn_id) cantidad FROM fn_nom_dtlle_nomina WHERE dtn_nom_id=nom_id) cantidad_registros";
                                $cadena_sql.=" FROM fn_nom_nomina";
                                $cadena_sql.=" WHERE ";
                                $cadena_sql.=" nom_estado_reg='A'";
                                $cadena_sql.=" AND nom_num_id_ordenador='".$variable."'";
                                $cadena_sql.=" AND nom_id NOT IN (SELECT sgi_dtn_nom_id AS CODIGO FROM fn_nom_solicitud_giro)";
                                
                                break;
      		
                        case "detalle_nomina":
		            
                                $cadena_sql=" SELECT ";
                                $cadena_sql.=" cto_num,";
                                $cadena_sql.=" cto_con_tipo_id,";
                                $cadena_sql.=" cto_con_num_id,";
                                $cadena_sql.=" cto_interno_co,";
                                $cadena_sql.=" cto_uni_ejecutora,";
                                $cadena_sql.=" cto_tipo_contrato,";
                                $cadena_sql.=" ban_id,";
                                $cadena_sql.=" ban_nombre,";
                                $cadena_sql.=" cta_tipo,";
                                $cadena_sql.=" cta_num,";
                                $cadena_sql.=" dtn_id,";
                                $cadena_sql.=" dtn_nom_id ,";
                                $cadena_sql.=" dtn_cum_cto_vigencia ,";
                                $cadena_sql.=" dtn_cum_id ,";
                                $cadena_sql.=" dtn_porc_retefuente ,";
                                $cadena_sql.=" dtn_neto_abonar_cta_bancaria ,";
                                $cadena_sql.=" dtn_neto_aplicar_sic ,";
                                $cadena_sql.=" dtn_saldo_antes_pago ,";
                                $cadena_sql.=" dtn_fecha_inicio_per ,";
                                $cadena_sql.=" dtn_fecha_final_per ,";
                                $cadena_sql.=" dtn_num_dias_pagados ,";
                                $cadena_sql.=" dtn_regimen_comun ,";
                                $cadena_sql.=" dtn_valor_liq_antes_iva,";
                                $cadena_sql.=" dtn_valor_iva ,";
                                $cadena_sql.=" dtn_valor_total ,";
                                $cadena_sql.=" dtn_base_retefuente_renta ,";
                                $cadena_sql.=" dtn_valor_retefuente_renta ,";
                                $cadena_sql.=" dtn_valor_reteiva ,";
                                $cadena_sql.=" dtn_base_ica_estampillas ,";
                                $cadena_sql.=" dtn_valor_ica ,";
                                $cadena_sql.=" dtn_estampilla_ud ,";
                                $cadena_sql.=" dtn_estampilla_procultura ,";
                                $cadena_sql.=" dtn_estampilla_proadultomayor ,";
                                $cadena_sql.=" dtn_arp ,";
                                $cadena_sql.=" dtn_cooperativas_depositos ,";
                                $cadena_sql.=" dtn_afc ,";
                                $cadena_sql.=" dtn_total_dctos_sin_retenciones ,";
                                $cadena_sql.=" dtn_neto_pagar_sin_retenciones ,";
                                $cadena_sql.=" dtn_saldo_contrato_al_corte ,";
                                $cadena_sql.=" dtn_salud ,";
                                $cadena_sql.=" dtn_pension ,";
                                $cadena_sql.=" dtn_pensionado ,";
                                $cadena_sql.=" dtn_pago_saldo_menores ,";
                                $cadena_sql.=" dtn_pasante_monitoria";
                                $cadena_sql.=" FROM fn_nom_dtlle_nomina";
                                $cadena_sql.=" INNER JOIN fn_nom_cumplido ON cum_cto_vigencia=dtn_cum_cto_vigencia AND cum_id= dtn_cum_id ";
                                $cadena_sql.=" INNER JOIN fn_nom_datos_contrato ON cum_cto_vigencia=dtn_cum_cto_vigencia AND cum_cto_num= cto_num";
                                $cadena_sql.=" INNER JOIN fn_nom_cuenta_banco ON cum_cta_id=cta_id";
                                $cadena_sql.=" INNER JOIN fn_nom_banco ON cta_id_banco=ban_id";
                                $cadena_sql.=" WHERE dtn_nom_id=".$variable;
                                break;
                            
                         case "dependencia":
                                $cadena_sql=" SELECT ";
                                $cadena_sql.=" DEP.COD_DEPENDENCIA,";
                                $cadena_sql.=" DEP.NOMBRE_DEPENDENCIA";
                                $cadena_sql.=" FROM CO.CO_DEPENDENCIAS DEP";
                                break;
 	
                         case "rubro":
                                $cadena_sql=" SELECT DESCRIPCION NOMBRE_RUBRO ";
                                $cadena_sql.=" FROM PR.PR_RUBRO ";
                                $cadena_sql.=" WHERE VIGENCIA= ".$variable['vigencia'];
                                $cadena_sql.=" AND INTERNO=".$variable['cod_rubro'];   
                                break;
 	
                        case "ultimo_numero_solicitud_giro":
                                $cadena_sql=" SELECT MAX(sgi_id) AS NUM ";
                                $cadena_sql.=" FROM fn_nom_solicitud_giro";
                                break;

                        case "detalle_nomina":
		            
                                $cadena_sql=" SELECT ";
                                $cadena_sql.=" dtn_id,";
                                $cadena_sql.=" dtn_nom_id ,";
                                $cadena_sql.=" dtn_cum_cto_vigencia ,";
                                $cadena_sql.=" dtn_cum_id ,";
                                $cadena_sql.=" dtn_porc_retefuente ,";
                                $cadena_sql.=" dtn_neto_abonar_cta_bancaria ,";
                                $cadena_sql.=" dtn_neto_aplicar_sic ,";
                                $cadena_sql.=" dtn_saldo_antes_pago ,";
                                $cadena_sql.=" dtn_fecha_inicio_per ,";
                                $cadena_sql.=" dtn_fecha_final_per ,";
                                $cadena_sql.=" dtn_num_dias_pagados ,";
                                $cadena_sql.=" dtn_regimen_comun ,";
                                $cadena_sql.=" dtn_valor_liq_antes_iva,";
                                $cadena_sql.=" dtn_valor_iva ,";
                                $cadena_sql.=" dtn_valor_total ,";
                                $cadena_sql.=" dtn_base_retefuente_renta ,";
                                $cadena_sql.=" dtn_valor_retefuente_renta ,";
                                $cadena_sql.=" dtn_valor_reteiva ,";
                                $cadena_sql.=" dtn_base_ica_estampillas ,";
                                $cadena_sql.=" dtn_valor_ica ,";
                                $cadena_sql.=" dtn_estampilla_ud ,";
                                $cadena_sql.=" dtn_estampilla_procultura ,";
                                $cadena_sql.=" dtn_estampilla_proadultomayor ,";
                                $cadena_sql.=" dtn_arp ,";
                                $cadena_sql.=" dtn_cooperativas_depositos ,";
                                $cadena_sql.=" dtn_afc ,";
                                $cadena_sql.=" dtn_total_dctos_sin_retenciones ,";
                                $cadena_sql.=" dtn_neto_pagar_sin_retenciones ,";
                                $cadena_sql.=" dtn_saldo_contrato_al_corte ,";
                                $cadena_sql.=" dtn_salud ,";
                                $cadena_sql.=" dtn_pension ,";
                                $cadena_sql.=" dtn_pensionado ,";
                                $cadena_sql.=" dtn_pago_saldo_menores ,";
                                $cadena_sql.=" dtn_pasante_monitoria";
                                $cadena_sql.=" FROM fn_nom_dtlle_nomina";
                                $cadena_sql.=" WHERE dtn_nom_id=".$variable;
                                break;
                          
                        case "insertar_solicitud_giro":
                                $cadena_sql=" INSERT INTO  fn_nom_solicitud_giro (";
                                $cadena_sql.=" sgi_id,";
                                $cadena_sql.=" sgi_dtn_id,";
                                $cadena_sql.=" sgi_dtn_nom_id,";
                                $cadena_sql.=" sgi_fecha ) ";
                                $cadena_sql.=" VALUES(";
                                $cadena_sql.="'".$variable['id_solicitud']."',";
                                $cadena_sql.="'".$variable['id_detalle']."',";
                                $cadena_sql.="'".$variable['id_nomina']."',";
                                $cadena_sql.="'".$variable['fecha']."'";
                                $cadena_sql.=" )";
                                break;

                        case "datos_solicitud_giro":
                                $cadena_sql=" SELECT  ";
                                $cadena_sql.=" sgi_id,";
                                $cadena_sql.=" sgi_dtn_id,";
                                $cadena_sql.=" sgi_dtn_nom_id,";
                                $cadena_sql.=" sgi_fecha ,";
                                $cadena_sql.=" sgi_fecha_pago,";
                                $cadena_sql.=" sgi_num_op ";
                                $cadena_sql.=" FROM fn_nom_solicitud_giro";
                                $cadena_sql.=" WHERE ";
                                $cadena_sql.=" sgi_dtn_id='".$variable['id_detalle']."'";
                                $cadena_sql.=" AND sgi_dtn_nom_id='".$variable['id_nomina']."'";
                                if($variable['id_solicitud']){
                                    $cadena_sql.=" AND sgi_id='".$variable['id_solicitud']."'";
                                }
                                break;
                            
                            
                        default:
				$cadena_sql="";
				break;
		}//fin switch
		return $cadena_sql;
	}// fin funcion cadena_sql
	
	
}//fin clase sql_adminSolicitudAprobarGiro
?>

