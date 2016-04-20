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

class sql_adminNominaOrdenador extends sql
{
	function cadena_sql($configuracion,$conexion, $opcion,$variable="")
	{
		
		switch($opcion)
		{	
                    		
                        case "nomina":
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
                                $cadena_sql.=" nom_estado_reg";
                                $cadena_sql.=" FROM fn_nom_nomina";
                                $cadena_sql.=" WHERE ";
                                $cadena_sql.=" nom_estado_reg='A'";
                                $cadena_sql.=" AND nom_num_id_ordenador='".$variable['id_ordenador']."'";
                                $cadena_sql.=" ORDER BY nom_fecha_registro DESC,nom_id DESC  ";
                                
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
                                $cadena_sql.=" dtn_pasante_monitoria,";
                                $cadena_sql.=" nom_rubro_interno,";
                                $cadena_sql.=" nom_anio,";
                                $cadena_sql.=" nom_num_id_ordenador,";
                                $cadena_sql.=" nom_cod_ordenador,";
                                $cadena_sql.=" nom_cod_dep_ordenador,";
                                $cadena_sql.=" nom_cod_dep_supervisor";
                                $cadena_sql.=" FROM fn_nom_dtlle_nomina";
                                $cadena_sql.=" INNER JOIN fn_nom_cumplido ON cum_cto_vigencia=dtn_cum_cto_vigencia AND cum_id= dtn_cum_id ";
                                $cadena_sql.=" INNER JOIN fn_nom_datos_contrato ON cum_cto_vigencia=dtn_cum_cto_vigencia AND cum_cto_num= cto_num";
                                $cadena_sql.=" INNER JOIN fn_nom_cuenta_banco ON cum_cta_id=cta_id";
                                $cadena_sql.=" INNER JOIN fn_nom_banco ON cta_id_banco=ban_id";
                                $cadena_sql.=" INNER JOIN fn_nom_nomina ON dtn_nom_id=nom_id ";
                                $cadena_sql.=" WHERE dtn_nom_id=".$variable;
                                break;
                          
                            case "datos_contratista":
                                $cadena_sql=" SELECT TER.TIPO_DOCUMENTO,";
                                $cadena_sql.=" TER.NUMERO_DOCUMENTO,";
                                $cadena_sql.=" TER.PRIMER_NOMBRE,";
                                $cadena_sql.=" TER.SEGUNDO_NOMBRE,";
                                $cadena_sql.=" TER.PRIMER_APELLIDO,";
                                $cadena_sql.=" TER.SEGUNDO_APELLIDO,";
                                $cadena_sql.=" TER.DIRECCION,";
                                $cadena_sql.=" TER.TELEFONO ";
                                $cadena_sql.=" FROM PR.PR_TERCEROS TER";
                                $cadena_sql.=" WHERE TER.NUMERO_DOCUMENTO='".$variable."'";
                                break;		
                        
                          case "datos_contrato":
				$cadena_sql=" SELECT ";
                                $cadena_sql.=" PROV.INTERNO_PROVEEDOR,";
                                $cadena_sql.=" PROV.TIPO_IDENTIFICACION,";
                                $cadena_sql.=" PROV.NUM_IDENTIFICACION,";
                                $cadena_sql.=" PROV.RAZON_SOCIAL,";
                                $cadena_sql.=" MINUTA.INTERNO_MC,";
                                $cadena_sql.=" MINUTA.VIGENCIA,";
                                $cadena_sql.=" MINUTA.CUANTIA,";
                                $cadena_sql.=" MINUTA.CODIGO_UNIDAD_EJECUTORA,";
                                $cadena_sql.=" MINUTA.DEPENDENCIA_DESTINO,";
                                $cadena_sql.=" MINUTA.PLAZO_EJECUCION,";
                                $cadena_sql.=" MINUTA.NUM_SOL_ADQ ,";
                                $cadena_sql.=" MINUTA.OBJETO,";
                                $cadena_sql.=" ORDEN.INTERNO_OC,";
                                $cadena_sql.=" (CASE WHEN MINUTA.CONSECUTIVO_CONTRATO IS NOT NULL THEN MINUTA.CONSECUTIVO_CONTRATO  ELSE ORDEN.NUM_CONTRATO END) AS NUM_CONTRATO,";
                                $cadena_sql.=" TO_CHAR(LEG.FECHA_DE_INICIACION,'YYYY-MM-DD')  AS FECHA_INICIO,";
                                $cadena_sql.=" TO_CHAR(LEG.FECHA_FINAL,'YYYY-MM-DD')    AS FECHA_FINAL,";
                                $cadena_sql.=" TO_CHAR(LEG.FECHA_ELABORACION_ACTA_INICIO,'YYYY-MM-DD')  AS FECHA_ELABORACION_ACTA_INICIO,";
                                $cadena_sql.=" SOL.FUNCIONARIO";
                                $cadena_sql.=" FROM CO.CO_MINUTA_CONTRATO MINUTA";
                                $cadena_sql.=" INNER JOIN CO.CO_SOLICITUD_ADQ SOL ON SOL.VIGENCIA=MINUTA.VIGENCIA AND SOL.NUM_SOL_ADQ= MINUTA.NUM_SOL_ADQ AND SOL.AUTORIZADA='S'";
                                $cadena_sql.=" INNER JOIN CO.CO_ORDEN_CONTRATO ORDEN ON MINUTA.INTERNO_MC=ORDEN.INTERNO_MC AND MINUTA.VIGENCIA = ORDEN.VIGENCIA AND MINUTA.INTERNO_PROVEEDOR = ORDEN.INTERNO_PROVEEDOR";
                                $cadena_sql.=" LEFT OUTER JOIN CO.CO_PROVEEDOR PROV ON PROV.INTERNO_PROVEEDOR=MINUTA.INTERNO_PROVEEDOR";
                                $cadena_sql.=" LEFT OUTER JOIN CO.CO_MINUTA_LEGALIZACION LEG ON LEG.VIGENCIA= MINUTA.VIGENCIA AND LEG.INTERNO_OC=ORDEN.INTERNO_OC";
                                $cadena_sql.=" WHERE MINUTA.VIGENCIA= ".$variable['vigencia'];
                                $cadena_sql.=" AND ORDEN.INTERNO_OC=".$variable['interno_oc'];
                                                                                             
                                break;		
 
                        case "datos_disponibilidad":
				$cadena_sql=" SELECT CDP.NUMERO_DISPONIBILIDAD,";
                                $cadena_sql.=" CDP.FECHA_DISPONIBILIDAD,";
                                $cadena_sql.=" CDP.VALOR,";
                                $cadena_sql.=" CDPRUBRO.INTERNO_RUBRO";
                                $cadena_sql.=" FROM CO.CO_MINUTA_CDP CDP";
                                $cadena_sql.=" INNER JOIN CO.CO_SOL_CDP_RUBRO CDPRUBRO ON CDP.VIGENCIA=CDPRUBRO.VIGENCIA AND CDPRUBRO.NUMERO_CDP=CDP.NUMERO_DISPONIBILIDAD AND TRIM(CDPRUBRO.ESTADO_CDP)='APROBADO'";
                                $cadena_sql.=" WHERE CDP.INTERNO_MC= ".$variable['cod_minuta_contrato'];
                                $cadena_sql.=" AND CDP.VIGENCIA=".$variable['vigencia'];
                                $cadena_sql.=" AND CDP.CODIGO_UNIDAD_EJECUTORA=".$variable['cod_unidad_ejecutora'];
                                break;		
                            
			case "datos_registro":
                                $cadena_sql=" SELECT CRP.NUMERO_REGISTRO,";
                                $cadena_sql.=" TO_CHAR(CRP.FECHA_REGISTRO,'YYYY-MM-DD') AS FECHA_REGISTRO,";
                                $cadena_sql.=" REG.VALOR";
                                $cadena_sql.=" FROM PR.PR_REGISTRO_PRESUPUESTAL CRP";
                                $cadena_sql.=" INNER JOIN PR.PR_REGISTRO_DISPONIBILIDAD REG ON CRP.NUMERO_DISPONIBILIDAD=REG.NUMERO_DISPONIBILIDAD AND CRP.CODIGO_UNIDAD_EJECUTORA=REG.CODIGO_UNIDAD_EJECUTORA AND CRP.VIGENCIA=REG.VIGENCIA";
                                $cadena_sql.=" WHERE CRP.VIGENCIA=".$variable['vigencia'];
                                $cadena_sql.=" AND CRP.CODIGO_UNIDAD_EJECUTORA=".$variable['cod_unidad_ejecutora'];
                                $cadena_sql.=" AND CRP.NUMERO_DISPONIBILIDAD=".$variable['nro_cdp'];
                                break;		
                        
                        case "solicitudes_nomina":
				$cadena_sql=" SELECT ";
                                $cadena_sql.=" dtn_id                       AS detalle_id,";
                                $cadena_sql.=" dtn_cum_cto_vigencia         AS vigencia,";
                                $cadena_sql.=" cum_cto_num                  AS num_contrato,";
                                $cadena_sql.=" cto_con_tipo_id              AS tipo_id,";
                                $cadena_sql.=" cto_con_num_id               AS num_id,";
                                $cadena_sql.=" dtn_fecha_inicio_per         AS fecha_inicio_per,";
                                $cadena_sql.=" dtn_fecha_final_per          AS fecha_final_per,";
                                $cadena_sql.=" dtn_num_dias_pagados         AS num_dias,";
                                $cadena_sql.=" dtn_valor_liq_antes_iva      AS valor_antes_iva,";
                                $cadena_sql.=" dtn_afc                      AS afc,";
                                $cadena_sql.=" dtn_cooperativas_depositos   AS coop_depositos,";
                                $cadena_sql.=" dtn_salud                    AS salud,";
                                $cadena_sql.=" dtn_pension                  AS pension,";
                                $cadena_sql.=" dtn_arp                      AS arp,";
                                $cadena_sql.=" dtn_saldo_antes_pago         AS saldo_antes_pago,";
                                $cadena_sql.=" sol_cod_dependencia          AS cod_dependencia,";
                                $cadena_sql.=" sol_cod_ordenador            AS cod_ordenador,";
                                $cadena_sql.=" sol_id_ordenador             AS id_ordenador,";
                                $cadena_sql.=" con_regimen_comun            AS regimen_comun,";
                                $cadena_sql.=" con_declarante               AS declarante,";
                                $cadena_sql.=" con_pensionado               AS pensionado,";
                                $cadena_sql.=" con_pasante                  AS pasante";
                                $cadena_sql.=" FROM fn_nom_tmp_dtlle_nomina";
                                $cadena_sql.=" INNER JOIN fn_nom_cumplido ON cum_cto_vigencia=dtn_cum_cto_vigencia AND cum_id= dtn_cum_id ";
                                $cadena_sql.=" INNER JOIN fn_nom_datos_contrato ON cum_cto_vigencia=dtn_cum_cto_vigencia AND cum_cto_num= cto_num";
                                $cadena_sql.=" INNER JOIN fn_nom_datos_contratista ON cto_con_tipo_id=con_tipo_id  AND  cto_con_num_id=con_num_id ";
                                $cadena_sql.=" INNER JOIN fn_nom_solicitud_pago ON sol_id=dtn_num_solicitud_pago";
                                $cadena_sql.=" WHERE";
                                $cadena_sql.=" dtn_num_solicitud_pago >0";
                                $cadena_sql.=" AND (dtn_estado ='SOLICITADO' or dtn_estado is null)";
                                $cadena_sql.=" AND sol_id_ordenador='".$variable['id_ordenador']."'";
                                break;

			case "aprobar_solicitud_pago":
				$cadena_sql=" UPDATE ";
                                $cadena_sql.=" fn_nom_tmp_dtlle_nomina";
                                $cadena_sql.=" SET";
                                $cadena_sql.=" dtn_estado='".$variable['estado']."',";
                                $cadena_sql.=" dtn_base_retefuente_renta='".$variable['valor_base_retefuente']."',";
                                $cadena_sql.=" dtn_valor_retefuente_renta='".$variable['valor_retefuente']."',";
                                $cadena_sql.=" dtn_valor_iva ='".$variable['valor_iva']."',";
                                $cadena_sql.=" dtn_valor_reteiva ='".$variable['valor_reteiva']."',";
                                $cadena_sql.=" dtn_base_ica_estampillas ='".$variable['valor_base_ica_estampillas']."',";
                                $cadena_sql.=" dtn_valor_ica ='".$variable['valor_ica']."',";
                                $cadena_sql.=" dtn_estampilla_ud ='".$variable['valor_estampilla_ud']."',";
                                $cadena_sql.=" dtn_estampilla_procultura ='".$variable['valor_estampilla_procultura']."',";
                                $cadena_sql.=" dtn_estampilla_proadultomayor ='".$variable['valor_estampilla_proadultomayor']."',";
                                $cadena_sql.=" dtn_retefuente_099 ='".$variable['valor_retefuente_099']."',";
                                $cadena_sql.=" dtn_retefuente_iman ='".$variable['valor_retefuente_iman']."'";
                                //$cadena_sql.=" dtn_cooperativas_depositos ='".$variable['dtn_cooperativas_depositos']."'";

                                $cadena_sql.=" WHERE dtn_id='".$variable['id']."'";
                                break;		
                            
                        case "solicitudes_aprobadas_nomina":
				$cadena_sql=" SELECT ";
                                $cadena_sql.=" cto_con_tipo_id              AS tipo_id,";
                                $cadena_sql.=" cto_con_num_id               AS num_id,";
                                $cadena_sql.=" ban_id                       AS ban_id,";
                                $cadena_sql.=" ban_nombre                   AS ban_nombre,";
                                $cadena_sql.=" cta_tipo                     AS cta_tipo,";
                                $cadena_sql.=" cta_num                      AS cta_num,";
                                $cadena_sql.=" dtn_id                       AS detalle_id,";
                                $cadena_sql.=" dtn_cum_cto_vigencia         AS vigencia,";
                                $cadena_sql.=" dtn_cum_id                   AS cum_id,";
                                $cadena_sql.=" cum_cto_num                  AS num_contrato,";
                                $cadena_sql.=" dtn_saldo_antes_pago         AS saldo_antes_pago ,";
                                $cadena_sql.=" dtn_fecha_inicio_per         AS fecha_inicio_per,";
                                $cadena_sql.=" dtn_fecha_final_per          AS fecha_final_per,";
                                $cadena_sql.=" dtn_num_dias_pagados         AS num_dias_pagados,";
                                $cadena_sql.=" dtn_regimen_comun            AS regimen_comun,";
                                $cadena_sql.=" dtn_declarante               AS declarante,";
                                $cadena_sql.=" dtn_valor_liq_antes_iva      AS valor_liq_antes_iva,";
                                $cadena_sql.=" dtn_valor_iva                AS valor_iva,";
                                $cadena_sql.=" dtn_valor_total              AS valor_total,";
                                $cadena_sql.=" dtn_porc_retefuente          AS porc_retefuente,";
                                $cadena_sql.=" dtn_base_retefuente_renta    AS base_retefuente_renta,";
                                $cadena_sql.=" dtn_valor_retefuente_renta   AS valor_retefuente_renta,";
                                $cadena_sql.=" dtn_valor_reteiva            AS valor_reteiva,";
                                $cadena_sql.=" dtn_base_ica_estampillas     AS base_ica_estampillas,";
                                $cadena_sql.=" dtn_valor_ica                AS valor_ica,";
                                $cadena_sql.=" dtn_estampilla_ud            AS estampilla_ud,";
                                $cadena_sql.=" dtn_estampilla_procultura    AS estampilla_procultura,";
                                $cadena_sql.=" dtn_estampilla_proadultomayor    AS estampilla_proadultomayor ,";
                                $cadena_sql.=" dtn_arp                      AS arp,";
                                $cadena_sql.=" dtn_cooperativas_depositos   AS cooperativas_depositos,";
                                $cadena_sql.=" dtn_afc                      AS afc,";
                                $cadena_sql.=" dtn_salud                    AS salud,";
                                $cadena_sql.=" dtn_pension                  AS pension,";
                                $cadena_sql.=" dtn_pensionado               AS pensionado,";
                                $cadena_sql.=" dtn_pago_saldo_menores       AS pago_saldo_menores,";
                                $cadena_sql.=" dtn_pasante_monitoria        AS pasante_monitoria,";
                                $cadena_sql.=" dtn_num_solicitud_pago       AS num_solicitud_pago,";
                                $cadena_sql.=" cto_interno_co               AS interno_co,";
                                $cadena_sql.=" cto_uni_ejecutora            AS unidad_ejecutora,";
                                $cadena_sql.=" cto_tipo_contrato            AS tipo_contrato,";
                                $cadena_sql.=" sol_cod_dependencia          AS cod_dependencia,";
                                $cadena_sql.=" sol_cod_ordenador            AS cod_ordenador,";
                                $cadena_sql.=" sol_rubro                    AS interno_rubro,";
                                $cadena_sql.=" sol_cno_codigo               AS id_tipo_nomina,";
                                $cadena_sql.=" sol_id_ordenador             AS id_ordenador";
                                $cadena_sql.=" FROM fn_nom_tmp_dtlle_nomina";
                                $cadena_sql.=" INNER JOIN fn_nom_cumplido ON cum_cto_vigencia=dtn_cum_cto_vigencia AND cum_id= dtn_cum_id ";
                                $cadena_sql.=" INNER JOIN fn_nom_datos_contrato ON cum_cto_vigencia=dtn_cum_cto_vigencia AND cum_cto_num= cto_num";
                                $cadena_sql.=" INNER JOIN fn_nom_cuenta_banco ON cum_cta_id=cta_id";
                                $cadena_sql.=" INNER JOIN fn_nom_banco ON cta_id_banco=ban_id";
                                $cadena_sql.=" INNER JOIN fn_nom_solicitud_pago ON sol_id=dtn_num_solicitud_pago";
                                $cadena_sql.=" WHERE";
                                $cadena_sql.=" dtn_num_solicitud_pago >0";
                                $cadena_sql.=" AND dtn_estado ='APROBADO'";
                                if($variable['id_detalle']){
                                    $cadena_sql.=" AND dtn_id ='".$variable['id_detalle']."'";
                                }
                                if($variable['id_ordenador']){
                                    $cadena_sql.=" AND sol_id_ordenador ='".$variable['id_ordenador']."'";
                                }
                                break;

                        case "ultimo_numero_nomina":
                                $cadena_sql=" SELECT MAX(nom_id) AS NUM ";
                                $cadena_sql.=" FROM fn_nom_nomina";
                                break;

                        case "insertar_nomina":
                                $cadena_sql=" INSERT INTO fn_nom_nomina(";
                                $cadena_sql.=" nom_id,";
                                $cadena_sql.=" nom_rubro_interno,";
                                $cadena_sql.=" nom_cod_dep_supervisor,";
                                $cadena_sql.=" nom_cod_ordenador,";
                                $cadena_sql.=" nom_num_id_ordenador,";
                                $cadena_sql.=" nom_anio,";
                                $cadena_sql.=" nom_mes,";
                                $cadena_sql.=" nom_fecha_registro,";
                                $cadena_sql.=" nom_estado_reg,";
                                $cadena_sql.=" nom_estado,";
                                $cadena_sql.=" nom_cod_dep_ordenador)";
                                $cadena_sql.=" VALUES(";
                                $cadena_sql.="'".$variable['id_nomina']."',";
                                $cadena_sql.="'".$variable['rubro_interno']."',";
                                $cadena_sql.="'".$variable['cod_dependencia']."',";
                                $cadena_sql.="'".$variable['cod_ordenador']."',";
                                $cadena_sql.="'".$variable['id_ordenador']."',";
                                $cadena_sql.="'".$variable['anio']."',";
                                $cadena_sql.="'".$variable['mes']."',";
                                $cadena_sql.="'".$variable['fecha_registro']."',";
                                $cadena_sql.="'".$variable['estado']."',";
                                $cadena_sql.="'".$variable['estado_registro']."',";
                                $cadena_sql.="'".$variable['cod_dependencia_ord']."'";
                                $cadena_sql.=" )";
                                break;

                        case "insertar_detalle_nomina":
                                $cadena_sql=" INSERT INTO fn_nom_dtlle_nomina(";
                                $cadena_sql.=" dtn_id,";
                                $cadena_sql.=" dtn_nom_id,";
                                $cadena_sql.=" dtn_cum_id,";
                                $cadena_sql.=" dtn_cum_cto_vigencia,";
                                $cadena_sql.=" dtn_porc_retefuente ,";
                                $cadena_sql.=" dtn_neto_abonar_cta_bancaria ,";
                                $cadena_sql.=" dtn_neto_aplicar_sic ,";
                                $cadena_sql.=" dtn_saldo_antes_pago,";
                                $cadena_sql.=" dtn_fecha_inicio_per,";
                                $cadena_sql.=" dtn_fecha_final_per ,";
                                $cadena_sql.=" dtn_num_dias_pagados,";
                                $cadena_sql.=" dtn_regimen_comun , ";
                                $cadena_sql.=" dtn_valor_liq_antes_iva,";
                                $cadena_sql.=" dtn_valor_iva ,";
                                $cadena_sql.=" dtn_valor_total,";
                                $cadena_sql.=" dtn_base_retefuente_renta,";
                                $cadena_sql.=" dtn_valor_retefuente_renta,";
                                $cadena_sql.=" dtn_valor_reteiva ,";
                                $cadena_sql.=" dtn_base_ica_estampillas,";
                                $cadena_sql.=" dtn_valor_ica ,";
                                $cadena_sql.=" dtn_estampilla_ud,";
                                $cadena_sql.=" dtn_estampilla_procultura,";
                                $cadena_sql.=" dtn_estampilla_proadultomayor,";
                                $cadena_sql.=" dtn_arp ,";
                                $cadena_sql.=" dtn_cooperativas_depositos ,";
                                $cadena_sql.=" dtn_afc ,";
                                $cadena_sql.=" dtn_total_dctos_sin_retenciones,";
                                $cadena_sql.=" dtn_neto_pagar_sin_retenciones,";
                                $cadena_sql.=" dtn_saldo_contrato_al_corte,";
                                $cadena_sql.=" dtn_salud,";
                                $cadena_sql.=" dtn_pension,";
                                $cadena_sql.=" dtn_pensionado,";
                                $cadena_sql.=" dtn_pago_saldo_menores,";
                                $cadena_sql.=" dtn_pasante_monitoria ,";
                                $cadena_sql.=" dtn_declarante,";
                                $cadena_sql.=" dtn_num_solicitud_pago) ";
                                $cadena_sql.=" VALUES(";
                                $cadena_sql.="'".$variable['id_detalle']."',";
                                $cadena_sql.="'".$variable['id_nomina']."',";
                                $cadena_sql.="'".$variable['id_cumplido']."',";
                                $cadena_sql.="'".$variable['vigencia']."',";
                                $cadena_sql.="'".$variable['porc_retefuente']."',";
                                $cadena_sql.="'".$variable['neto_abonar_cta_bancaria']."',";
                                $cadena_sql.="'".$variable['neto_aplicar_sic']."',";
                                $cadena_sql.="'".$variable['saldo_antes_pago']."',";
                                $cadena_sql.="'".$variable['fecha_inicio_per']."',";
                                $cadena_sql.="'".$variable['fecha_final_per']."',";
                                $cadena_sql.="'".$variable['num_dias_pagados']."',";
                                $cadena_sql.="'".$variable['regimen_comun']."',";
                                $cadena_sql.="'".$variable['valor_liq_antes_iva']."',";
                                $cadena_sql.="'".$variable['valor_iva']."',";
                                $cadena_sql.="'".$variable['valor_total']."',";
                                $cadena_sql.="'".$variable['base_retefuente_renta']."',";
                                $cadena_sql.="'".$variable['valor_retefuente_renta']."',";
                                $cadena_sql.="'".$variable['valor_reteiva']."',";
                                $cadena_sql.="'".$variable['base_ica_estampillas']."',";
                                $cadena_sql.="'".$variable['valor_ica']."',";
                                $cadena_sql.="'".$variable['estampilla_ud']."',";  
                                $cadena_sql.="'".$variable['estampilla_procultura']."',";
                                $cadena_sql.="'".$variable['estampilla_proadultomayor']."',";
                                $cadena_sql.="'".$variable['arp']."',";
                                $cadena_sql.="'".$variable['cooperativas_depositos']."',";
                                $cadena_sql.="'".$variable['afc']."',";
                                $cadena_sql.="'".$variable['total_dctos_sin_retenciones']."',";
                                $cadena_sql.="'".$variable['neto_pagar_sin_retenciones']."',";
                                $cadena_sql.="'".$variable['saldo_contrato_al_corte']."',";
                                $cadena_sql.="'".$variable['salud']."',";
                                $cadena_sql.="'".$variable['pension']."',";
                                $cadena_sql.="'".$variable['pensionado']."',";
                                $cadena_sql.="'".$variable['pago_saldo_menores']."',";
                                $cadena_sql.="'".$variable['pasante_monitoria']."',";
                                $cadena_sql.="'".$variable['declarante']."',";
                                $cadena_sql.="'".$variable['num_solicitud_pago']."'";
                                $cadena_sql.=" )";
                                break;
                           
                        case "eliminar_tmp_detalle_nomina":
				$cadena_sql=" DELETE ";
                                $cadena_sql.=" FROM fn_nom_tmp_dtlle_nomina";
                                $cadena_sql.=" WHERE dtn_id='".$variable."'";
                                break;		
                        
                        case "datos_rubro":
                                $cadena_sql=" SELECT ";
                                $cadena_sql.=" INTERNO_RUBRO,";
                                $cadena_sql.=" VIGENCIA VIG_RUBRO,";
                                $cadena_sql.=" (CODIGO_NIVEL1||'-'||CODIGO_NIVEL2||'-'||CODIGO_NIVEL3||'-'||CODIGO_NIVEL4||'-'||CODIGO_NIVEL5||'-'||CODIGO_NIVEL6||'-'||CODIGO_NIVEL7||'-'||CODIGO_NIVEL8) COD_RUBRO,";
                                $cadena_sql.=" DESCRIPCION NOM_RUBRO,";
                                $cadena_sql.=" TIPO_PLAN";
                                $cadena_sql.=" FROM PR_V_RUBROS";
                                $cadena_sql.=" WHERE VIGENCIA='".$variable['vigencia']."'";
                                $cadena_sql.=" AND INTERNO_RUBRO='".$variable['interno']."'";
                                $cadena_sql.=" ORDER BY COD_RUBRO ";
                                break;                            
                            
                        case "datos_ordenador":
                                $cadena_sql=" SELECT COD_JEFE,";
                                $cadena_sql.=" COD_DEPENDENCIA,";
                                $cadena_sql.=" TIPO_DOCUMENTO,";
                                $cadena_sql.=" NUMERO_DOCUMENTO,";
                                $cadena_sql.=" NOMBRES_JEFE,";
                                $cadena_sql.=" PRIMER_APELLIDO,";
                                $cadena_sql.=" SEGUNDO_APELLIDO";
                                $cadena_sql.=" FROM CO.CO_JEFES";
                                $cadena_sql.=" WHERE NUMERO_DOCUMENTO='".$variable."'";
                                break;

                       case "nombre_dependencia":
                                $cadena_sql=" SELECT NOMBRE_DEPENDENCIA ";
                                $cadena_sql.=" FROM CO.CO_DEPENDENCIAS DEP ";
                                $cadena_sql.=" WHERE DEP.COD_DEPENDENCIA=".$variable;  
                                break;
                            
                        case "actualizar_estado_detalle_solicitud":
                                $cadena_sql=" UPDATE ";
                                $cadena_sql.=" fn_nom_tmp_dtlle_nomina";
                                $cadena_sql.=" SET";
                                $cadena_sql.=" dtn_estado='".$variable['estado']."' ";
                                $cadena_sql.=" WHERE dtn_id='".$variable['id_detalle']."'";
                                
                                break;
                         
                        case "detalle_nomina_xsolicitud":
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
                                $cadena_sql.=" WHERE dtn_num_solicitud_pago=".$variable['id_solicitud'];
                                $cadena_sql.=" AND dtn_cum_id=".$variable['id_cumplido'];
                                $cadena_sql.=" AND dtn_cum_cto_vigencia=".$variable['vigencia_contrato'];
                                break;
                          
                        case "solicitudes_nomina_xdetalle":
				$cadena_sql=" SELECT ";
                                $cadena_sql.=" cto_con_tipo_id              AS tipo_id,";
                                $cadena_sql.=" cto_con_num_id               AS num_id,";
                                $cadena_sql.=" dtn_id                       AS detalle_id,";
                                $cadena_sql.=" dtn_cum_cto_vigencia         AS vigencia,";
                                $cadena_sql.=" dtn_cum_id                   AS cum_id,";
                                $cadena_sql.=" cum_cto_num                  AS num_contrato,";
                                $cadena_sql.=" dtn_saldo_antes_pago         AS saldo_antes_pago ,";
                                $cadena_sql.=" dtn_fecha_inicio_per         AS fecha_inicio_per,";
                                $cadena_sql.=" dtn_fecha_final_per          AS fecha_final_per,";
                                $cadena_sql.=" dtn_num_dias_pagados         AS num_dias_pagados,";
                                $cadena_sql.=" dtn_regimen_comun            AS regimen_comun,";
                                $cadena_sql.=" dtn_valor_liq_antes_iva      AS valor_liq_antes_iva,";
                                $cadena_sql.=" dtn_valor_iva                AS valor_iva,";
                                $cadena_sql.=" dtn_valor_total              AS valor_total,";
                                $cadena_sql.=" dtn_porc_retefuente          AS porc_retefuente,";
                                $cadena_sql.=" dtn_base_retefuente_renta    AS base_retefuente_renta,";
                                $cadena_sql.=" dtn_valor_retefuente_renta   AS valor_retefuente_renta,";
                                $cadena_sql.=" dtn_valor_reteiva            AS valor_reteiva,";
                                $cadena_sql.=" dtn_base_ica_estampillas     AS base_ica_estampillas,";
                                $cadena_sql.=" dtn_valor_ica                AS valor_ica,";
                                $cadena_sql.=" dtn_estampilla_ud            AS estampilla_ud,";
                                $cadena_sql.=" dtn_estampilla_procultura    AS estampilla_procultura,";
                                $cadena_sql.=" dtn_estampilla_proadultomayor    AS estampilla_proadultomayor ,";
                                $cadena_sql.=" dtn_arp                      AS arp,";
                                $cadena_sql.=" dtn_cooperativas_depositos   AS cooperativas_depositos,";
                                $cadena_sql.=" dtn_afc                      AS afc,";
                                $cadena_sql.=" dtn_salud                    AS salud,";
                                $cadena_sql.=" dtn_pension                  AS pension,";
                                $cadena_sql.=" dtn_pensionado               AS pensionado,";
                                $cadena_sql.=" dtn_pago_saldo_menores       AS pago_saldo_menores,";
                                $cadena_sql.=" dtn_pasante_monitoria        AS pasante_monitoria,";
                                $cadena_sql.=" dtn_num_solicitud_pago       AS num_solicitud_pago,";
                                $cadena_sql.=" dtn_declarante               AS declarante,";
                                $cadena_sql.=" cto_interno_co               AS interno_co,";
                                $cadena_sql.=" cto_uni_ejecutora            AS unidad_ejecutora,";
                                $cadena_sql.=" cto_tipo_contrato            AS tipo_contrato,";
                                $cadena_sql.=" sol_cod_dependencia          AS cod_dependencia,";
                                $cadena_sql.=" sol_cod_ordenador            AS cod_ordenador,";
                                $cadena_sql.=" sol_rubro                    AS interno_rubro,";
                                $cadena_sql.=" sol_id_ordenador             AS id_ordenador";
                                $cadena_sql.=" FROM fn_nom_tmp_dtlle_nomina";
                                $cadena_sql.=" INNER JOIN fn_nom_cumplido ON cum_cto_vigencia=dtn_cum_cto_vigencia AND cum_id= dtn_cum_id ";
                                $cadena_sql.=" INNER JOIN fn_nom_datos_contrato ON cum_cto_vigencia=dtn_cum_cto_vigencia AND cum_cto_num= cto_num";
                                $cadena_sql.=" INNER JOIN fn_nom_solicitud_pago ON sol_id=dtn_num_solicitud_pago";
                                $cadena_sql.=" WHERE";
                                $cadena_sql.=" dtn_id ='".$variable."'";
                                break;

			default:
				$cadena_sql="";
				break;
		}//fin switch
		return $cadena_sql;
	}// fin funcion cadena_sql
	
	
}//fin clase sql_adminNominaOrdenador
?>

