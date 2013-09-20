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

class sql_adminSolicitudPagoSupervisor extends sql
{
	function cadena_sql($configuracion,$conexion, $opcion,$variable="")
	{
		
		switch($opcion)
		{	
                    		
                        case "codigo_dependencia":
                                $cadena_sql=" SELECT regsub.id_dependencia";
                                $cadena_sql.=" FROM gestion_registrado_subsistema regsub";
                                $cadena_sql.=" INNER JOIN gestion_registrado reg ON reg.id_usuario = regsub.id_usuario";
                                $cadena_sql.=" WHERE reg.identificacion =".$variable;
                                
                                break;
                        
                        case "codigo_supervisor":
                                $cadena_sql=" SELECT COD_JEFE,";
                                $cadena_sql.=" COD_DEPENDENCIA,";
                                $cadena_sql.=" NOMBRES_JEFE,";
                                $cadena_sql.=" PRIMER_APELLIDO,";
                                $cadena_sql.=" SEGUNDO_APELLIDO";
                                $cadena_sql.=" FROM CO.CO_JEFES";
                                $cadena_sql.=" WHERE COD_DEPENDENCIA=".$variable;
                                break;

                      case "solicitudes_pago":
				$cadena_sql=" SELECT ";
                                $cadena_sql.=" sol_id,";
                                $cadena_sql.=" sol_fecha_reg ,";
                                $cadena_sql.=" sol_rubro ,";
                                $cadena_sql.=" sol_estado,";
                                $cadena_sql.=" sol_cordis,";
                                $cadena_sql.=" sol_observacion ,";
                                $cadena_sql.=" sol_pago_anio ,";
                                $cadena_sql.=" sol_pago_mes ,";
                                $cadena_sql.=" sol_cod_supervisor ,";
                                $cadena_sql.=" sol_id_supervisor ,";
                                $cadena_sql.=" sol_cod_ordenador ,";
                                $cadena_sql.=" sol_id_ordenador ,";
                                $cadena_sql.=" sol_estado_reg,";
                                $cadena_sql.=" sol_cod_dependencia  ";
                                $cadena_sql.=" FROM fn_nom_solicitud_pago";
                                $cadena_sql.=" WHERE sol_id_supervisor='".$variable."'";
                                $cadena_sql.=" ORDER BY sol_id DESC";
                                break;

                      case "detalle_solicitud_pago":
				$cadena_sql=" SELECT ";
                                $cadena_sql.=" cto_con_tipo_id              ,";
                                $cadena_sql.=" cto_con_num_id               ,";
                                $cadena_sql.=" ban_id                       ,";
                                $cadena_sql.=" ban_nombre                   ,";
                                $cadena_sql.=" cta_tipo                     ,";
                                $cadena_sql.=" cta_num                      ,";
                                $cadena_sql.=" dtn_id                       ,";
                                $cadena_sql.=" dtn_cum_cto_vigencia         ,";
                                $cadena_sql.=" dtn_cum_id                   ,";
                                $cadena_sql.=" cto_num                      ,";
                                $cadena_sql.=" cto_vigencia                 ,";
                                $cadena_sql.=" dtn_saldo_antes_pago         ,";
                                $cadena_sql.=" dtn_fecha_inicio_per         ,";
                                $cadena_sql.=" dtn_fecha_final_per          ,";
                                $cadena_sql.=" dtn_num_dias_pagados         ,";
                                $cadena_sql.=" dtn_regimen_comun            ,";
                                $cadena_sql.=" dtn_valor_liq_antes_iva      ,";
                                $cadena_sql.=" dtn_valor_iva                ,";
                                $cadena_sql.=" dtn_valor_total              ,";
                                $cadena_sql.=" dtn_porc_retefuente          ,";
                                $cadena_sql.=" dtn_base_retefuente_renta    ,";
                                $cadena_sql.=" dtn_valor_retefuente_renta   ,";
                                $cadena_sql.=" dtn_valor_reteiva            ,";
                                $cadena_sql.=" dtn_base_ica_estampillas     ,";
                                $cadena_sql.=" dtn_valor_ica                ,";
                                $cadena_sql.=" dtn_estampilla_ud            ,";
                                $cadena_sql.=" dtn_estampilla_procultura    ,";
                                $cadena_sql.=" dtn_estampilla_proadultomayor    ,";
                                $cadena_sql.=" dtn_arp                      ,";
                                $cadena_sql.=" dtn_cooperativas_depositos   ,";
                                $cadena_sql.=" dtn_afc                      ,";
                                $cadena_sql.=" dtn_salud                    ,";
                                $cadena_sql.=" dtn_pension                  ,";
                                $cadena_sql.=" dtn_pensionado               ,";
                                $cadena_sql.=" dtn_pago_saldo_menores       ,";
                                $cadena_sql.=" dtn_pasante_monitoria        ,";
                                $cadena_sql.=" dtn_num_solicitud_pago       ,";
                                $cadena_sql.=" cto_interno_co               ,";
                                $cadena_sql.=" cto_uni_ejecutora            ,";
                                $cadena_sql.=" cto_tipo_contrato            ";
                                $cadena_sql.=" FROM fn_nom_tmp_dtlle_nomina";
                                $cadena_sql.=" INNER JOIN fn_nom_cumplido ON cum_cto_vigencia=dtn_cum_cto_vigencia AND cum_id= dtn_cum_id ";
                                $cadena_sql.=" INNER JOIN fn_nom_datos_contrato ON cum_cto_vigencia=dtn_cum_cto_vigencia AND cum_cto_num= cto_num";
                                $cadena_sql.=" INNER JOIN fn_nom_cuenta_banco ON cum_cta_id=cta_id";
                                $cadena_sql.=" INNER JOIN fn_nom_banco ON cta_id_banco=ban_id";
                                $cadena_sql.=" WHERE";
                                $cadena_sql.=" dtn_num_solicitud_pago ='".$variable."'";
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
                                $cadena_sql.=" CDPRUBRO.INTERNO_RUBRO,";
                                $cadena_sql.=" CDPRUBRO.ID_SOL_CDP,";
                                $cadena_sql.=" SOLCDP.INTERNO_DE AS COD_ORDENADOR,";
                                $cadena_sql.=" SOLCDP.CARGO_DE AS DEPENDENCIA_ORDENADOR";
                                $cadena_sql.=" FROM CO.CO_MINUTA_CDP CDP";
                                $cadena_sql.=" INNER JOIN CO.CO_SOL_CDP_RUBRO CDPRUBRO ON CDP.VIGENCIA=CDPRUBRO.VIGENCIA AND CDPRUBRO.NUMERO_CDP=CDP.NUMERO_DISPONIBILIDAD AND TRIM(CDPRUBRO.ESTADO_CDP)='APROBADO'";
                                $cadena_sql.=" INNER JOIN CO.CO_SOL_CDP SOLCDP ON CDP.VIGENCIA=SOLCDP.VIGENCIA AND CDPRUBRO.ID_SOL_CDP=SOLCDP.ID_SOL_CDP";
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
                        
                        case "cumplidos_aprobados":
				
				$cadena_sql=" SELECT cum_id             AS id,";
                                $cadena_sql.=" cum_cto_vigencia         AS vigencia,";
                                $cadena_sql.=" cum_cto_num              AS num_contrato,";
                                $cadena_sql.=" cum_anio                 AS anio,";
                                $cadena_sql.=" cum_mes                  AS mes,";
                                $cadena_sql.=" cum_procesado            AS procesado,";
                                $cadena_sql.=" cum_estado               AS estado,";
                                $cadena_sql.=" cum_estado_reg           AS estado_reg,";
                                $cadena_sql.=" cum_fecha                AS fecha,";
                                $cadena_sql.=" cum_num_impresiones      AS num_impresiones,";
                                $cadena_sql.=" cum_valor                AS valor,";
                                $cadena_sql.=" cum_cta_id               AS id_cta,";
                                $cadena_sql.=" cum_finicio_per_pago     AS finicio_cumplido,";
                                $cadena_sql.=" cum_ffinal_per_pago      AS ffinal_cumplido,";
                                $cadena_sql.=" cum_acumulado_valor_pagos AS acumulado_valor_pagos,";
                                $cadena_sql.=" cum_acumulado_dias_pagos  AS acumulado_dias_pagos,";
                                $cadena_sql.=" cto_interno_co           AS interno_oc,";
                                $cadena_sql.=" cto_uni_ejecutora        AS unidad_ejecutora,";
                                $cadena_sql.=" con_tipo_id              AS tipo_id_contratista,";
                                $cadena_sql.=" con_num_id               AS num_id_contratista,";
                                $cadena_sql.=" con_interno_proveedor    AS interno_proveedor,";  
                                $cadena_sql.=" dtn_salud                AS salud,";  
                                $cadena_sql.=" dtn_pension              AS pension,";  
                                $cadena_sql.=" dtn_arp                  AS arp,";  
                                $cadena_sql.=" dtn_afc                  AS afc,";  
                                $cadena_sql.=" dtn_cooperativas_depositos    AS cooperativas_depositos,";  
                                $cadena_sql.=" dtn_num_dias_pagados     AS dias_cumplido,";  
                                $cadena_sql.=" dtn_saldo_antes_pago     AS saldo_antes_pago";  
                                $cadena_sql.=" FROM fn_nom_cumplido";
                                $cadena_sql.=" INNER JOIN fn_nom_datos_contrato  ON cto_vigencia=cum_cto_vigencia AND cto_num=cum_cto_num";
                                $cadena_sql.=" INNER JOIN fn_nom_datos_contratista  ON cto_con_tipo_id=con_tipo_id AND cto_con_num_id=con_num_id";
                                $cadena_sql.=" INNER JOIN fn_nom_tmp_dtlle_nomina  ON dtn_cum_id=cum_id AND dtn_cum_cto_vigencia=cum_cto_vigencia";
                                $cadena_sql.=" WHERE cum_estado_reg='A'";
                                $cadena_sql.=" AND cum_estado='APROBADO'";
                                $cadena_sql.=" AND cum_num_id_supervisor ='".$variable."'";
                                $cadena_sql.=" ORDER BY vigencia DESC,num_contrato DESC,anio DESC,mes DESC";
                                break;		
                            
                        case "ultimo_numero_solicitud_pago":
                                $cadena_sql=" SELECT MAX(sol_id) AS NUM ";
                                $cadena_sql.=" FROM fn_nom_solicitud_pago";
                                break;

                        case "insertar_solicitud_pago":
                                $cadena_sql=" INSERT INTO fn_nom_solicitud_pago(";
                                $cadena_sql.=" sol_id ,";
                                $cadena_sql.=" sol_fecha_reg ,";
                                $cadena_sql.=" sol_rubro ,";
                                $cadena_sql.=" sol_estado,";
                                $cadena_sql.=" sol_cordis,";
                                $cadena_sql.=" sol_observacion ,";
                                $cadena_sql.=" sol_pago_anio ,";
                                $cadena_sql.=" sol_pago_mes ,";
                                $cadena_sql.=" sol_cod_supervisor ,";
                                $cadena_sql.=" sol_id_supervisor ,";
                                $cadena_sql.=" sol_cod_ordenador ,";
                                $cadena_sql.=" sol_id_ordenador ,";
                                $cadena_sql.=" sol_estado_reg ,";
                                $cadena_sql.=" sol_cod_dependencia ) ";
                                $cadena_sql.=" VALUES(";
                                $cadena_sql.="'".$variable['id_solicitud']."',";
                                $cadena_sql.="'".$variable['fecha_registro']."',";
                                $cadena_sql.="'".$variable['rubro_interno']."',";
                                $cadena_sql.="'".$variable['estado']."',";
                                $cadena_sql.="'".$variable['cordis']."',";
                                $cadena_sql.="'".$variable['observacion']."',";
                                $cadena_sql.="'".$variable['anio_pago']."',";
                                $cadena_sql.="'".$variable['mes_pago']."',";
                                $cadena_sql.="'".$variable['cod_supervisor']."',";
                                $cadena_sql.="'".$variable['id_supervisor']."',";
                                $cadena_sql.="'".$variable['cod_ordenador']."',";
                                $cadena_sql.="'".$variable['id_ordenador']."',";
                                $cadena_sql.="'".$variable['estado_registro']."',";
                                $cadena_sql.="'".$variable['cod_dependencia']."'";
                                $cadena_sql.=" )";

                                
                                break;

                         case "actualizar_detalle_solicitud_pago":
                                $cadena_sql=" UPDATE ";
                                $cadena_sql.=" fn_nom_tmp_dtlle_nomina";
                                $cadena_sql.=" SET";
                                $cadena_sql.=" dtn_num_solicitud_pago='".$variable['numero_solicitud']."',";
                                $cadena_sql.=" dtn_id='".$variable['id_detalle']."'";
                                $cadena_sql.=" WHERE dtn_cum_id='".$variable['id_cumplido']."'";
                                $cadena_sql.=" AND dtn_cum_cto_vigencia='".$variable['vigencia']."'";
                                
                                break;
                        
                        case "ultimo_numero_detalle_solicitud_pago":
                                $cadena_sql=" SELECT MAX(dtn_id) AS NUM ";
                                $cadena_sql.=" FROM fn_nom_tmp_dtlle_nomina";
                                break;

                        case "solicitudes_sin_cordis":
				$cadena_sql=" SELECT ";
                                $cadena_sql.=" sol_id,";
                                $cadena_sql.=" sol_fecha_reg ,";
                                $cadena_sql.=" sol_rubro ,";
                                $cadena_sql.=" sol_estado,";
                                $cadena_sql.=" sol_cordis,";
                                $cadena_sql.=" sol_observacion ,";
                                $cadena_sql.=" sol_pago_anio ,";
                                $cadena_sql.=" sol_pago_mes ,";
                                $cadena_sql.=" sol_cod_supervisor ,";
                                $cadena_sql.=" sol_id_supervisor ,";
                                $cadena_sql.=" sol_cod_ordenador ,";
                                $cadena_sql.=" sol_id_ordenador ,";
                                $cadena_sql.=" sol_estado_reg,";
                                $cadena_sql.=" sol_cod_dependencia  ";
                                $cadena_sql.=" FROM fn_nom_solicitud_pago";
                                $cadena_sql.=" WHERE sol_id_supervisor='".$variable."'";
                                $cadena_sql.=" AND (sol_cordis = '' OR sol_cordis = ' ')";
                                $cadena_sql.=" ORDER BY sol_id DESC";
                                break;

                        case "actualizar_cordis":
                                $cadena_sql=" UPDATE ";
                                $cadena_sql.=" fn_nom_solicitud_pago";
                                $cadena_sql.=" SET";
                                $cadena_sql.=" sol_cordis='".$variable['cordis']."' ";
                                $cadena_sql.=" WHERE sol_id='".$variable['id_solicitud']."'";
                                
                                break;
                            
                        case "solicitud_pago":
				$cadena_sql=" SELECT ";
                                $cadena_sql.=" sol_id,";
                                $cadena_sql.=" sol_fecha_reg ,";
                                $cadena_sql.=" sol_rubro ,";
                                $cadena_sql.=" sol_estado,";
                                $cadena_sql.=" sol_cordis,";
                                $cadena_sql.=" sol_observacion ,";
                                $cadena_sql.=" sol_pago_anio ,";
                                $cadena_sql.=" sol_pago_mes ,";
                                $cadena_sql.=" sol_cod_supervisor ,";
                                $cadena_sql.=" sol_id_supervisor ,";
                                $cadena_sql.=" sol_cod_ordenador ,";
                                $cadena_sql.=" sol_id_ordenador ,";
                                $cadena_sql.=" sol_estado_reg,";
                                $cadena_sql.=" sol_cod_dependencia  ";
                                $cadena_sql.=" FROM fn_nom_solicitud_pago";
                                $cadena_sql.=" WHERE sol_id='".$variable."'";
                                $cadena_sql.=" ORDER BY sol_id DESC";
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
                                $cadena_sql.=" WHERE COD_JEFE=".$variable;
                                break;

                       case "dependencia":
                                $cadena_sql=" SELECT ";
                                $cadena_sql.=" DEP.COD_DEPENDENCIA,";
                                $cadena_sql.=" DEP.NOMBRE_DEPENDENCIA";
                                $cadena_sql.=" FROM CO.CO_DEPENDENCIAS DEP";
                                break;

                       case "actualizar_estado_cumplido":
                                $cadena_sql=" UPDATE ";
                                $cadena_sql.=" fn_nom_cumplido";
                                $cadena_sql.=" SET";
                                $cadena_sql.=" cum_estado='".$variable['estado']."' ";
                                $cadena_sql.=" WHERE cum_id='".$variable['id_cumplido']."'";
                                $cadena_sql.=" AND cum_cto_vigencia='".$variable['vigencia']."'";
                                
                                break;
                            
                        case "documento":
                                $cadena_sql=" SELECT ";
                                $cadena_sql.=" docu_nombre          AS nombre,";
                                $cadena_sql.=" docu_descripcion     AS descripcion, ";
                                $cadena_sql.=" docu_ubicacion_pdf   AS ubicacion, ";
                                $cadena_sql.=" docu_nombre_pdf      AS nombre_pdf ";
                                $cadena_sql.=" FROM fn_nom_documento ";
                                $cadena_sql.=" WHERE docu_id=".$variable;
                                $cadena_sql.=" AND docu_estado='A'";
                                break;

                        case "detalle_solicitud_pago_xcumplido":
				$cadena_sql=" SELECT dtn_id , ";
                                $cadena_sql.=" dtn_cum_cto_vigencia , ";
                                $cadena_sql.=" dtn_cum_id , ";
                                $cadena_sql.=" dtn_saldo_antes_pago , ";
                                $cadena_sql.=" dtn_fecha_inicio_per , ";
                                $cadena_sql.=" dtn_fecha_final_per , ";
                                $cadena_sql.=" dtn_num_dias_pagados , ";
                                $cadena_sql.=" dtn_regimen_comun , ";
                                $cadena_sql.=" dtn_valor_liq_antes_iva , ";
                                $cadena_sql.=" dtn_valor_iva , ";
                                $cadena_sql.=" dtn_valor_total , ";
                                $cadena_sql.=" dtn_porc_retefuente , ";
                                $cadena_sql.=" dtn_base_retefuente_renta , ";
                                $cadena_sql.=" dtn_valor_retefuente_renta , ";
                                $cadena_sql.=" dtn_valor_reteiva , ";
                                $cadena_sql.=" dtn_base_ica_estampillas , ";
                                $cadena_sql.=" dtn_valor_ica , ";
                                $cadena_sql.=" dtn_estampilla_ud , ";
                                $cadena_sql.=" dtn_estampilla_procultura , ";
                                $cadena_sql.=" dtn_estampilla_proadultomayor , ";
                                $cadena_sql.=" dtn_arp , ";
                                $cadena_sql.=" dtn_cooperativas_depositos , ";
                                $cadena_sql.=" dtn_afc , ";
                                $cadena_sql.=" dtn_salud , ";
                                $cadena_sql.=" dtn_pension , ";
                                $cadena_sql.=" dtn_pensionado , ";
                                $cadena_sql.=" dtn_pago_saldo_menores , ";
                                $cadena_sql.=" dtn_pasante_monitoria , ";
                                $cadena_sql.=" dtn_num_solicitud_pago ";
                                $cadena_sql.=" FROM fn_nom_tmp_dtlle_nomina ";
                                $cadena_sql.=" WHERE dtn_cum_id ='".$variable['id_cumplido']."'";
                                $cadena_sql.=" AND dtn_cum_cto_vigencia ='".$variable['vigencia_contrato']."'";
                                break;
        
                        default:
				$cadena_sql="";
				break;
		}//fin switch
		return $cadena_sql;
	}// fin funcion cadena_sql
	
        
	
}//fin clase sql_adminSolicitudPagoSupervisor
?>

