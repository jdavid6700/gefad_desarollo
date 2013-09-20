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
                                $cadena_sql.=" ban_codigo_sic,";
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
                                $cadena_sql.=" nom_cod_dep_supervisor ,";
                                $cadena_sql.=" nom_cod_ordenador ,";
                                $cadena_sql.=" nom_num_id_ordenador ,";
                                $cadena_sql.=" nom_rubro_interno,";
                                $cadena_sql.=" nom_anio ,";
                                $cadena_sql.=" nom_mes, ";
                                $cadena_sql.=" aci_cno_codigo,";
                                $cadena_sql.=" cum_anio,";
                                $cadena_sql.=" cum_mes";
                                $cadena_sql.=" FROM fn_nom_dtlle_nomina";
                                $cadena_sql.=" INNER JOIN fn_nom_nomina ON dtn_nom_id=nom_id";
                                $cadena_sql.=" INNER JOIN fn_nom_cumplido ON cum_cto_vigencia=dtn_cum_cto_vigencia AND cum_id= dtn_cum_id ";
                                $cadena_sql.=" INNER JOIN fn_nom_datos_contrato ON cum_cto_vigencia=dtn_cum_cto_vigencia AND cum_cto_num= cto_num";
                                $cadena_sql.=" INNER JOIN fn_nom_cuenta_banco ON cum_cta_id=cta_id";
                                $cadena_sql.=" INNER JOIN fn_nom_banco ON cta_id_banco=ban_id";
                                $cadena_sql.=" INNER JOIN fn_nom_acta_inicio ON cum_cto_vigencia=aci_cto_vigencia AND cum_cto_num=aci_cto_num AND aci_estado_reg='A'";
                                $cadena_sql.=" WHERE dtn_nom_id=".$variable;
                                break;
                            
                         case "dependencia":
                                $cadena_sql=" SELECT ";
                                $cadena_sql.=" DEP.COD_DEPENDENCIA,";
                                $cadena_sql.=" DEP.NOMBRE_DEPENDENCIA";
                                $cadena_sql.=" FROM CO.CO_DEPENDENCIAS DEP";
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
                        
                        case "nuevo_numero_orden_pago":
                                $cadena_sql=" SELECT (MAX(TO_NUMBER(CONSECUTIVO))+1) NUMERO";
                                $cadena_sql.=" FROM OGT.OGT_DOCUMENTO_PAGO";
                                $cadena_sql.=" WHERE VIGENCIA=TO_CHAR(SYSDATE,'RRRR')";
                                $cadena_sql.=" AND TIPO_DOCUMENTO='OP'";
                                $cadena_sql.=" AND UNIDAD_EJECUTORA='".$variable."'";
                                break;
                        
                         case "insertar_tmp_orden_pago":
                                $cadena_sql=" INSERT INTO OGT.ogt_orden_pago_tmp";
                                $cadena_sql.=" (VIGENCIA, ";
                                $cadena_sql.=" ENTIDAD, ";
                                $cadena_sql.=" UNIDAD_EJECUTORA,";
                                $cadena_sql.=" TIPO_DOCUMENTO, ";
                                $cadena_sql.=" CONSECUTIVO, ";
                                $cadena_sql.=" NUM_CONVENIO, ";
                                $cadena_sql.=" NUM_DOC_SOPORTE, ";
                                $cadena_sql.=" CONCEPTO, ";
                                $cadena_sql.=" TER_ID, ";
                                $cadena_sql.=" CODIGO_CONTABLE_NETO, ";
                                $cadena_sql.=" CODIGO_CONTABLE_BRUTO, ";
                                $cadena_sql.=" FORMA_PAGO, ";
                                $cadena_sql.=" NUMERO_CUENTA, ";
                                $cadena_sql.=" BANCO, ";
                                $cadena_sql.=" CLASE, ";
                                $cadena_sql.=" REGIMEN, ";
                                $cadena_sql.=" TIPO_DOC_IDENT, ";
                                $cadena_sql.=" NUM_DOC_IDENT, ";
                                $cadena_sql.=" DETALLE, ";
                                $cadena_sql.=" VALOR_BRUTO, ";
                                $cadena_sql.=" VALOR_NETO,";
                                $cadena_sql.=" CODIGO_CONTABLE_ORDEN1, ";
                                $cadena_sql.=" CODIGO_CONTABLE_ORDEN2, ";
                                $cadena_sql.=" VIGENCIA_PRESUPUESTO) ";
                                $cadena_sql.=" VALUES ";
                                $cadena_sql.=" ('".$variable['vigencia']."',";
                                $cadena_sql.=" '".$variable['codigo_entidad']."',";
                                $cadena_sql.=" '".$variable['unidad_ejecutora']."',";
                                $cadena_sql.=" '".$variable['tipo_documento_obligacion']."',";
                                $cadena_sql.=" '".$variable['consecutivo_op']."',";
                                $cadena_sql.=" '".$variable['numero_convenio']."',";
                                $cadena_sql.=" '".$variable['num_documento_soporte']."',";
                                $cadena_sql.=" '".$variable['concepto_tesoreria']."',";
                                $cadena_sql.=" '".$variable['codigo_interno_tercero']."',";
                                $cadena_sql.=" '".$variable['codigo_contable_neto']."',";
                                $cadena_sql.=" '".$variable['codigo_contable_bruto']."',";
                                $cadena_sql.=" '".$variable['forma_pago']."',";
                                $cadena_sql.=" '".$variable['cuenta_dueno_obligacion']."',";
                                $cadena_sql.=" '".$variable['codigo_interno_banco']."',";
                                $cadena_sql.=" '".$variable['tipo_cta_deposito']."',";
                                $cadena_sql.=" nvl('".$variable['tipo_regimen']."','NO REPORTADO'),";
                                $cadena_sql.=" '".$variable['tipo_id']."',";
                                $cadena_sql.=" '".$variable['num_id']."',";
                                $cadena_sql.=" '".$variable['detalle_obligacion']."',";
                                $cadena_sql.=" '".$variable['valor_bruto']."',";
                                $cadena_sql.=" '".$variable['valor_neto']."',";
                                $cadena_sql.=" '".$variable['codigo_contable_op_debito']."',";
                                $cadena_sql.=" '".$variable['codigo_contable_op_credito']."',";
                                $cadena_sql.=" '".$variable['vigencia_presupuestal']."'";
                                $cadena_sql.=" )";
                                break;

                        case "insertar_tmp_imputacion":
                                $cadena_sql=" INSERT INTO OGT.ogt_imputacion_tmp";
                                $cadena_sql.=" (VIGENCIA, ";
                                $cadena_sql.=" ENTIDAD, ";
                                $cadena_sql.=" UNIDAD_EJECUTORA, ";
                                $cadena_sql.=" TIPO_DOCUMENTO, ";
                                $cadena_sql.=" CONSECUTIVO, ";
                                $cadena_sql.=" CODIGO_RUBRO,";
                                $cadena_sql.=" RUBRO_INTERNO, ";
                                $cadena_sql.=" DISPONIBILIDAD, ";
                                $cadena_sql.=" REGISTRO, ";
                                $cadena_sql.=" TIPO_COMPROMISO, ";
                                $cadena_sql.=" NUMERO_COMPROMISO, ";
                                $cadena_sql.=" VALOR_BRUTO, ";
                                $cadena_sql.=" ANO_PAC, ";
                                $cadena_sql.=" MES_PAC, ";
                                $cadena_sql.=" TIPO_DOCUMENTO_IE, ";
                                $cadena_sql.=" NUMERO_DOCUMENTO";
                                $cadena_sql.=" ) ";
                                $cadena_sql.=" VALUES(";
                                $cadena_sql.=" '".$variable['vigencia']."',";
                                $cadena_sql.=" '".$variable['codigo_entidad']."',";
                                $cadena_sql.=" '".$variable['unidad_ejecutora']."',";
                                $cadena_sql.=" '".$variable['tipo_documento_obligacion']."',";
                                $cadena_sql.=" '".$variable['consecutivo_op']."',";
                                $cadena_sql.=" '".$variable['codigo_rubro']."',";
                                $cadena_sql.=" '".$variable['codigo_rubro_interno']."',";
                                $cadena_sql.=" '".$variable['num_cdp']."',";
                                $cadena_sql.=" '".$variable['num_rp']."',";
                                $cadena_sql.=" '".$variable['tipo_compromiso']."',";
                                $cadena_sql.=" '".$variable['num_compromiso']."',";
                                $cadena_sql.=" '".$variable['valor_bruto']."',";
                                $cadena_sql.=" null,";
                                $cadena_sql.=" null,";
                                $cadena_sql.=" null,";
                                $cadena_sql.=" null";
                                
                                $cadena_sql.=" )";
                                break;

                        case "insertar_tmp_descuentos":
                                $cadena_sql=" INSERT INTO OGT.ogt_detalle_dscto_tmp";
                                $cadena_sql.=" (VIGENCIA, ";
                                $cadena_sql.=" ENTIDAD, ";
                                $cadena_sql.=" UNIDAD_EJECUTORA, ";
                                $cadena_sql.=" TIPO_DOCUMENTO, ";
                                $cadena_sql.=" CONSECUTIVO, ";
                                $cadena_sql.=" RUBRO_INTERNO, ";
                                $cadena_sql.=" DISPONIBILIDAD,";
                                $cadena_sql.=" REGISTRO, ";
                                $cadena_sql.=" NUMERO_DOCUMENTO, ";
                                $cadena_sql.=" CODIGO_INTERNO, ";
                                $cadena_sql.=" VALOR_BASE_RETENCION, ";
                                $cadena_sql.=" VALOR_DESCUENTO, ";
                                $cadena_sql.=" CODIGO_RUBRO, ";
                                $cadena_sql.=" CODIGO_CTA";
                                $cadena_sql.=" ) ";
                                $cadena_sql.=" VALUES(";
                                $cadena_sql.=" '".$variable['vigencia']."',";
                                $cadena_sql.=" '".$variable['codigo_entidad']."',";
                                $cadena_sql.=" '".$variable['unidad_ejecutora']."',";
                                $cadena_sql.=" '".$variable['tipo_documento_obligacion']."',";
                                $cadena_sql.=" '".$variable['consecutivo_op']."',";
                                $cadena_sql.=" '".$variable['codigo_rubro_interno']."',";
                                $cadena_sql.=" '".$variable['num_cdp']."',";
                                $cadena_sql.=" '".$variable['num_rp']."',";
                                $cadena_sql.=" '".$variable['num_compromiso']."',";
                                $cadena_sql.=" '".$variable['codigo_interno_descuento']."',";
                                $cadena_sql.=" '".$variable['valor_base_retencion']."',";
                                $cadena_sql.=" '".$variable['valor_descuento']."',";
                                $cadena_sql.=" '".$variable['codigo_rubro']."',";
                                $cadena_sql.=" '".$variable['codigo_cuenta_descuento']."'";
                                $cadena_sql.=" )";
                                break;

                        case "conceptos_cuentas_nomina":
                                $cadena_sql=" SELECT ";
                                $cadena_sql.=" cno_codigo,";
                                $cadena_sql.=" cno_nombre,";
                                $cadena_sql.=" cno_tipo_nomina,";
                                $cadena_sql.=" cno_cuenta_gasto_debito,";
                                $cadena_sql.=" cno_cuenta_retefuente_credito,";
                                $cadena_sql.=" cno_cuenta_orden_debito,";
                                $cadena_sql.=" cno_cuenta_orden_credito,";
                                $cadena_sql.=" cno_cuenta_cxpagar_credito,";
                                $cadena_sql.=" cno_cod_unidad_ejec,";
                                $cadena_sql.=" cno_tipo_convenio";
                                $cadena_sql.=" FROM fn_nom_conceptos_nomina";
                                $cadena_sql.=" WHERE cno_estado='A'";
                                break;
                       
                        case "tipo_contrato":
                                $cadena_sql=" SELECT TIPO_COMPROMISO,";
                                $cadena_sql.=" RESULTADO";
                                $cadena_sql.=" FROM PR.PR_COMPROMISOS";
                                $cadena_sql.=" INNER JOIN shd.bintablas ON grupo='PREDIS' AND ARGUMENTO=TIPO_COMPROMISO AND NOMBRE='TIPO_COMPROMISO' ";
                                $cadena_sql.=" WHERE VIGENCIA ='".$variable['vigencia']."' ";
                                $cadena_sql.=" AND CODIGO_UNIDAD_EJECUTORA ='".$variable['unidad_ejec']."' ";
                                $cadena_sql.=" AND NUMERO_REGISTRO = '".$variable['num_registro']."' ";
                            
                                break;
                            
                        case "datos_disponibilidad":
				$cadena_sql=" SELECT CDP.NUMERO_DISPONIBILIDAD,";
                                $cadena_sql.=" CDP.FECHA_DISPONIBILIDAD,";
                                $cadena_sql.=" CDP.VALOR,";
                                $cadena_sql.=" CDPRUBRO.RUBRO_INTERNO";
                                $cadena_sql.=" FROM CO.CO_MINUTA_CDP CDP";
                                $cadena_sql.=" INNER JOIN PR.PR_DISPONIBILIDAD_RUBRO CDPRUBRO ON CDP.VIGENCIA=CDPRUBRO.VIGENCIA AND CDP.CODIGO_UNIDAD_EJECUTORA=CDPRUBRO.CODIGO_UNIDAD_EJECUTORA AND CDPRUBRO.NUMERO_DISPONIBILIDAD=CDP.NUMERO_DISPONIBILIDAD ";
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
                            
			case "datos_contrato":
				$variable['criterio_busqueda']=(isset($variable['criterio_busqueda'])?$variable['criterio_busqueda']:'');
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
                            
                         case "datos_tercero":
                                $cadena_sql=" SELECT ";
                                $cadena_sql.=" ID,";
                                $cadena_sql.=" IB_FECHA_INICIAL,";
                                $cadena_sql.=" IB_PRIMER_NOMBRE,";
                                $cadena_sql.=" IB_SEGUNDO_NOMBRE,";
                                $cadena_sql.=" IB_PRIMER_APELLIDO,";
                                $cadena_sql.=" IB_SEGUNDO_APELLIDO";
                                $cadena_sql.=" FROM shd.shd_informacion_basica ";
                                $cadena_sql.=" WHERE IB_TIPO_IDENTIFICACION= '".$variable['tipo_id']."'";
                                $cadena_sql.=" AND IB_CODIGO_IDENTIFICACION='".$variable['identificacion']."'";
                                break;		
                       
                        case "valor_pagado":
                                $cadena_sql=" SELECT NVL(SUM(C.VALOR_BRUTO),0) MI_TOTAL ";
                                $cadena_sql.=" FROM OGT.OGT_ORDEN_PAGO A";
                                $cadena_sql.=" ,OGT.OGT_REGISTRO_PRESUPUESTAL B";
                                $cadena_sql.=" ,OGT.OGT_INFORMACION_EXOGENA C";
                                $cadena_sql.=" WHERE A.CONSECUTIVO = B.CONSECUTIVO";
                                $cadena_sql.=" AND A.ENTIDAD = B.ENTIDAD";
                                $cadena_sql.=" AND A.TIPO_DOCUMENTO = B.TIPO_DOCUMENTO";
                                $cadena_sql.=" AND A.UNIDAD_EJECUTORA = B.UNIDAD_EJECUTORA";
                                $cadena_sql.=" AND A.VIGENCIA = B.VIGENCIA";
                                $cadena_sql.=" AND B.REGISTRO = C.REGISTRO";
                                $cadena_sql.=" AND B.TIPO_DOCUMENTO = C.TIPO_DOCUMENTO";
                                $cadena_sql.=" AND B.DISPONIBILIDAD = C.DISPONIBILIDAD";
                                $cadena_sql.=" AND B.CONSECUTIVO = C.CONSECUTIVO";
                                $cadena_sql.=" AND B.ENTIDAD = C.ENTIDAD";
                                $cadena_sql.=" AND B.UNIDAD_EJECUTORA = C.UNIDAD_EJECUTORA";
                                $cadena_sql.=" AND B.VIGENCIA = C.VIGENCIA";
                                $cadena_sql.=" AND B.VIGENCIA_PRESUPUESTO = C.VIGENCIA_PRESUPUESTO";
                                $cadena_sql.=" AND B.RUBRO_INTERNO = C.RUBRO_INTERNO";
                                $cadena_sql.=" AND SUBSTR(A.ESTADO,9,1) = '0'";
                                $cadena_sql.=" AND B.REGISTRO = ".$variable['num_rp'];
                                $cadena_sql.=" AND B.TIPO_DOCUMENTO ='OP'";
                                $cadena_sql.=" AND B.DISPONIBILIDAD =".$variable['num_cdp'];
                                $cadena_sql.=" AND B.CONSECUTIVO !='".$variable['consecutivo_op']."'";
                                $cadena_sql.=" AND B.ENTIDAD ='".$variable['codigo_compania']."'";
                                $cadena_sql.=" AND B.UNIDAD_EJECUTORA ='".$variable['unidad_ejec']."'";
                                $cadena_sql.=" AND B.VIGENCIA = '".$variable['vigencia']."'";
                                $cadena_sql.=" AND B.VIGENCIA_PRESUPUESTO = '".$variable['vigencia']."'";
                                $cadena_sql.=" AND B.RUBRO_INTERNO ='".$variable['rubro_interno']."'";
                                $cadena_sql.=" AND B.ENTIDAD_PRESUPUESTO ='".$variable['codigo_compania']."'";
                                break;
                            
                        case "valor_reintegro":
                                $cadena_sql=" SELECT nvl(sum(da.valor), 0) MI_REINTEGRO";
                                $cadena_sql.=" FROM ogt.ogt_actas a , ogt.OGT_DETALLE_ACTAS da";
                                $cadena_sql.=" WHERE a.VIGENCIA = da.VIGENCIA";
                                $cadena_sql.=" AND a.ENTIDAD = da.entidad";
                                $cadena_sql.=" AND a.UNIDAD_EJECUTORA = da.UNIDAD_EJECUTORA";
                                $cadena_sql.=" AND a.TIPO_DOCUMENTO = da.TIPO_DOCUMENTO";
                                $cadena_sql.=" AND a.CONSECUTIVO = da.CONSECUTIVO";
                                $cadena_sql.=" AND a.tipo_documento = 'AR'";
                                $cadena_sql.=" AND a.estado = 'AP'";
                                $cadena_sql.=" AND RUBRO_INTERNO ='".$variable['rubro_interno']."'";
                                $cadena_sql.=" AND registro = ".$variable['num_rp'];
                                $cadena_sql.=" AND nvl(VIGENCIA_PRESUPUESTO, a.VIGENCIA) ='".$variable['vigencia']."'";
                                $cadena_sql.=" AND DA.UNIDAD_EJECUTORA='".$variable['unidad_ejec']."'";
                                $cadena_sql.=" AND DISPONIBILIDAD = ".$variable['num_cdp'];
                                break;
                            
                        case "valor_rp_anulados":
                                $cadena_sql=" SELECT NVL(SUM(NVL(pr_registro_disponibilidad.valor,0)),0) mi_valor_rp_anulados";
                                $cadena_sql.=" FROM PR.pr_registro_disponibilidad, PR.pr_registro_presupuestal";
                                $cadena_sql.=" WHERE (pr_registro_disponibilidad.numero_registro=".$variable['num_rp']." AND";
                                $cadena_sql.=" pr_registro_disponibilidad.numero_disponibilidad=pr_registro_presupuestal.numero_disponibilidad AND";
                                $cadena_sql.=" pr_registro_disponibilidad.numero_registro=pr_registro_presupuestal.numero_registro AND";
                                $cadena_sql.=" pr_registro_disponibilidad.codigo_unidad_ejecutora=pr_registro_presupuestal.codigo_unidad_ejecutora AND";
                                $cadena_sql.=" pr_registro_disponibilidad.codigo_compania=pr_registro_presupuestal.codigo_compania AND";
                                $cadena_sql.=" pr_registro_disponibilidad.vigencia=pr_registro_presupuestal.vigencia) AND";
                                $cadena_sql.=" pr_registro_disponibilidad.codigo_compania = '".$variable['codigo_compania']."' AND";
                                $cadena_sql.=" pr_registro_disponibilidad.codigo_unidad_ejecutora = '".$variable['unidad_ejec']."' AND";
                                $cadena_sql.=" pr_registro_disponibilidad.vigencia = '".$variable['vigencia']."' AND";
                                $cadena_sql.=" pr_registro_disponibilidad.rubro_interno = '".$variable['rubro_interno']."' AND";
                                $cadena_sql.=" pr_registro_presupuestal.fecha_registro <= SYSDATE AND";
                                $cadena_sql.=" EXISTS";
                                $cadena_sql.=" ( SELECT pr_anulaciones.numero_documento_anulado";
                                $cadena_sql.=" FROM PR.pr_anulaciones";
                                $cadena_sql.=" WHERE";
                                $cadena_sql.=" pr_anulaciones.vigencia = '".$variable['vigencia']."' AND";
                                $cadena_sql.=" pr_anulaciones.codigo_compania = '".$variable['codigo_compania']."' AND";
                                $cadena_sql.=" pr_anulaciones.codigo_unidad_ejecutora = '".$variable['unidad_ejec']."' AND";
                                $cadena_sql.=" pr_anulaciones.documento_anulado = 'REGISTRO' and";
                                $cadena_sql.=" pr_anulaciones.numero_documento_anulado = pr_registro_presupuestal.numero_registro and";
                                $cadena_sql.=" pr_anulaciones.fecha_registro <= SYSDATE)";
                                break;
                        
                        case "valor_rp_anulados_parciales":
                                $cadena_sql=" SELECT NVL(SUM(NVL(pr_rp_anulados.valor_anulado,0)),0) mi_valor_rp_parciales";
                                $cadena_sql.=" FROM pr.pr_rp_anulados";
                                $cadena_sql.=" WHERE vigencia = '".$variable['vigencia']."' AND";
                                $cadena_sql.=" codigo_compania = '".$variable['codigo_compania']."' AND";
                                $cadena_sql.=" codigo_unidad_ejecutora = '".$variable['unidad_ejec']."' AND";
                                $cadena_sql.=" rubro_interno = '".$variable['rubro_interno']."' AND";
                                $cadena_sql.=" fecha_anulacion <= SYSDATE AND";
                                $cadena_sql.=" numero_registro=".$variable['num_rp']." AND";
                                $cadena_sql.=" EXISTS";
                                $cadena_sql.=" (SELECT pr_registro_presupuestal.numero_registro";
                                $cadena_sql.=" FROM pr.pr_registro_presupuestal";
                                $cadena_sql.=" WHERE pr_registro_presupuestal.vigencia = '".$variable['vigencia']."' AND";
                                $cadena_sql.=" pr_registro_presupuestal.codigo_compania = '".$variable['codigo_compania']."' AND";
                                $cadena_sql.=" pr_registro_presupuestal.codigo_unidad_ejecutora = '".$variable['unidad_ejec']."' AND";
                                $cadena_sql.=" pr_registro_presupuestal.numero_registro = pr_rp_anulados.numero_registro AND";
                                $cadena_sql.=" pr_registro_presupuestal.fecha_registro <= SYSDATE)";

                                break;
                            
                        case "valor_rp":
                                $cadena_sql=" SELECT SUM(rd.valor) mi_total ";
                                $cadena_sql.=" FROM pr.pr_registro_disponibilidad rd ";
                                $cadena_sql.=" WHERE rd.vigencia = '".$variable['vigencia']."' AND ";
                                $cadena_sql.=" rd.codigo_compania = '".$variable['codigo_compania']."' AND ";
                                $cadena_sql.=" rd.codigo_unidad_ejecutora = '".$variable['unidad_ejec']."' AND ";
                                $cadena_sql.=" rd.numero_registro = ".$variable['num_rp']." ";
                                $cadena_sql.=" AND rd.numero_disponibilidad = ".$variable['num_cdp'];
                                $cadena_sql.=" GROUP BY rd.vigencia, rd.codigo_compania,rd.codigo_unidad_ejecutora, rd.numero_registro, rd.numero_disponibilidad";

                                break;
                            
                        case "codigo_rubro":
                                $cadena_sql=" SELECT (CODIGO_NIVEL1||'-'||CODIGO_NIVEL2||'-'||CODIGO_NIVEL3||'-'||CODIGO_NIVEL4||'-'||CODIGO_NIVEL5||'-'||CODIGO_NIVEL6||'-'||CODIGO_NIVEL7||'-'||CODIGO_NIVEL8) AS CODIGO_RUBRO ,";
                                $cadena_sql.=" DESCRIPCION NOMBRE_RUBRO";
                                $cadena_sql.=" FROM PR.PR_V_RUBROS";
                                $cadena_sql.=" WHERE VIGENCIA='".$variable['vigencia']."' ";
                                $cadena_sql.=" AND INTERNO_RUBRO='".$variable['rubro_interno']."' ";
                                break;
                            
                        case "conceptos_conceptos":
                                $cadena_sql=" SELECT ";
                                $cadena_sql.=" cno_codigo,";
                                $cadena_sql.=" cno_nombre,";
                                $cadena_sql.=" cno_tipo_nomina,";
                                $cadena_sql.=" cno_cuenta_gasto_debito,";
                                $cadena_sql.=" cno_cuenta_retefuente_credito,";
                                $cadena_sql.=" cno_cuenta_orden_debito,";
                                $cadena_sql.=" cno_cuenta_orden_credito,";
                                $cadena_sql.=" cno_cuenta_cxpagar_credito,";
                                $cadena_sql.=" cno_cod_unidad_ejec,";
                                $cadena_sql.=" cno_tipo_convenio";
                                $cadena_sql.=" FROM fn_nom_conceptos_nomina";
                                $cadena_sql.=" WHERE cno_estado='A'";
                                break;
                       
                        case "actualizar_numero_documento_imputacion":
				$cadena_sql=" UPDATE OGT.ogt_imputacion_tmp A ";
                                $cadena_sql.=" SET NUMERO_DOCUMENTO = (SELECT B.NUM_DOC_SOPORTE ";
                                $cadena_sql.=" FROM OGT.ogt_orden_pago_tmp B";
                                $cadena_sql.=" where B.vigencia = A.vigencia ";
                                $cadena_sql.=" AND B.entidad = A.entidad ";
                                $cadena_sql.=" AND B.unidad_ejecutora = A.unidad_ejecutora ";
                                $cadena_sql.=" AND B.consecutivo = A.consecutivo";
                                $cadena_sql.=" AND rownum=1 )";
                                
                                break;		

                        case "actualizar_tipo_documento_imputacion":
				$cadena_sql=" UPDATE OGT.ogt_imputacion_tmp A ";
                                $cadena_sql.=" SET ANO_PAC=TO_NUMBER(to_char(sysdate,'YYYY')) ,";
                                $cadena_sql.=" MES_PAC=TO_NUMBER(TO_CHAR(SYSDATE,'MM')),";
                                $cadena_sql.=" TIPO_DOCUMENTO_IE = 'C',";
                                $cadena_sql.=" NUMERO_DOCUMENTO = NVL(NUMERO_DOCUMENTO,NUMERO_COMPROMISO)";
                                break;  		

                        case "cantidad_registros_descuentos":
                                $cadena_sql=" SELECT COUNT(*) cantidad";
                                $cadena_sql.=" FROM OGT.ogt_detalle_dscto_tmp ";
                                break;

                        case "actualizar_numero_documento_descuento":
                                $cadena_sql=" UPDATE OGT.ogt_detalle_dscto_tmp A ";
                                $cadena_sql.=" SET numero_documento = (SELECT B.numero_documento ";
                                $cadena_sql.=" from OGT.ogt_imputacion_tmp B ";
                                $cadena_sql.=" where B.vigencia = A.vigencia ";
                                $cadena_sql.=" AND B.entidad = A.entidad ";
                                $cadena_sql.=" AND B.registro = A.registro ";
                                $cadena_sql.=" AND B.disponibilidad = A.disponibilidad ";
                                $cadena_sql.=" AND B.consecutivo = A.consecutivo";
                                $cadena_sql.=" AND rownum=1 )";
                                break;
                            
                        case "actualizar_codigo_interno_descuentos":
                                $cadena_sql=" UPDATE OGT.ogt_detalle_dscto_tmp A ";
                                $cadena_sql.=" SET A.codigo_interno = ( Select B.codigo_interno ";
                                $cadena_sql.=" from OGT.ogt_descuento B";
                                $cadena_sql.=" where B.cuenta_contable = A.codigo_cta ";
                                $cadena_sql.=" AND rownum = 1";
                                $cadena_sql.=" AND b.fecha_final is null)";
                                $cadena_sql.=" WHERE A.codigo_interno is null ";
                                break;
                            
                        case "insertar_documento_pago_de_oden_pago_tmp":
                                $cadena_sql=" INSERT INTO OGT.OGT_DOCUMENTO_PAGO ";
                                $cadena_sql.=" (VIGENCIA,";
                                $cadena_sql.=" ENTIDAD,";
                                $cadena_sql.=" UNIDAD_EJECUTORA,";
                                $cadena_sql.=" TIPO_DOCUMENTO,";
                                $cadena_sql.=" CONSECUTIVO,";
                                $cadena_sql.=" FECHA_DILIGENCIAMIENTO)";
                                $cadena_sql.=" SELECT VIGENCIA,";
                                $cadena_sql.=" ENTIDAD,";
                                $cadena_sql.=" UNIDAD_EJECUTORA,";
                                $cadena_sql.=" TIPO_DOCUMENTO,";
                                $cadena_sql.=" CONSECUTIVO,";
                                $cadena_sql.=" TO_DATE(TO_CHAR(SYSDATE,'DDMMYYHH24MISS'),'DDMMYYHH24MISS')";
                                $cadena_sql.=" FROM OGT.ogt_orden_pago_tmp";
                                $cadena_sql.=" WHERE ";
                                $cadena_sql.=" (VIGENCIA,";
                                $cadena_sql.=" ENTIDAD,";
                                $cadena_sql.=" UNIDAD_EJECUTORA,";
                                $cadena_sql.=" TIPO_DOCUMENTO,";
                                $cadena_sql.=" CONSECUTIVO) IN ";
                                $cadena_sql.=" (SELECT VIGENCIA,";
                                $cadena_sql.=" ENTIDAD,";
                                $cadena_sql.=" UNIDAD_EJECUTORA,";
                                $cadena_sql.=" TIPO_DOCUMENTO,";
                                $cadena_sql.=" CONSECUTIVO ";
                                $cadena_sql.=" FROM OGT.ogt_orden_pago_tmp)";
                                break;
                         
                        case "insertar_orden_pago_de_tmp":
                                $cadena_sql=" INSERT INTO OGT.OGT_ORDEN_PAGO ";
                                $cadena_sql.=" (VIGENCIA,";
                                $cadena_sql.=" ENTIDAD,";
                                $cadena_sql.=" UNIDAD_EJECUTORA,";
                                $cadena_sql.=" TIPO_DOCUMENTO,";
                                $cadena_sql.=" CONSECUTIVO,";
                                $cadena_sql.=" TIPO_OP,";
                                $cadena_sql.=" TER_ID,";
                                $cadena_sql.=" CODIGO_COMPROMISO,";
                                $cadena_sql.=" NUMERO_DE_COMPROMISO,";
                                $cadena_sql.=" TIPO_VIGENCIA,";
                                $cadena_sql.=" ESTADO,";
                                $cadena_sql.=" SITUACION_FONDOS, ";
                                $cadena_sql.=" DETALLE,";
                                $cadena_sql.=" DESCRIPCION_EJECUTADO,";
                                $cadena_sql.=" ACTA_DE_RECIBO,";
                                $cadena_sql.=" CODIGO_CONTABLE_NETO,";
                                $cadena_sql.=" CODIGO_CONTABLE_BRUTO,";
                                $cadena_sql.=" FORMA_PAGO,";
                                $cadena_sql.=" NUMERO_CUENTA,";
                                $cadena_sql.=" BANCO,";
                                $cadena_sql.=" CLASE,";
                                $cadena_sql.=" REGIMEN,";
                                $cadena_sql.=" ENTIDAD_PRESUPUESTO,";
                                $cadena_sql.=" VIGENCIA_OP,";
                                $cadena_sql.=" CONVENIO)";
                                $cadena_sql.=" SELECT O.VIGENCIA,";
                                $cadena_sql.=" O.ENTIDAD,";
                                $cadena_sql.=" O.UNIDAD_EJECUTORA,";
                                $cadena_sql.=" O.TIPO_DOCUMENTO,";
                                $cadena_sql.=" O.CONSECUTIVO,";
                                $cadena_sql.=" 1,";
                                $cadena_sql.=" O.TER_ID,";
                                $cadena_sql.=" MAX(I.TIPO_COMPROMISO),";
                                $cadena_sql.=" MAX(I.NUMERO_COMPROMISO),";
                                $cadena_sql.=" 'V',";
                                $cadena_sql.=" '000000000' ESTADO,";
                                $cadena_sql.=" 'S' SITUACION_FONDOS,";
                                $cadena_sql.=" O.DETALLE,";
                                $cadena_sql.=" 'P' DESCRIPCION_EJECUTADO, ";
                                $cadena_sql.=" O.NUM_CONVENIO,";
                                $cadena_sql.=" O.CODIGO_CONTABLE_NETO,";
                                $cadena_sql.=" O.CODIGO_CONTABLE_BRUTO,";
                                $cadena_sql.=" O.FORMA_PAGO,";
                                $cadena_sql.=" O.NUMERO_CUENTA,";
                                $cadena_sql.=" O.BANCO,";
                                $cadena_sql.=" O.CLASE,";
                                $cadena_sql.=" O.REGIMEN,";
                                $cadena_sql.=" O.ENTIDAD,";
                                $cadena_sql.=" O.VIGENCIA ,";
                                $cadena_sql.=" TO_CHAR(O.NUM_CONVENIO)";
                                $cadena_sql.=" FROM OGT.ogt_orden_pago_tmp O , OGT.ogt_imputacion_tmp I ";
                                $cadena_sql.=" where O.vigencia = I.vigencia ";
                                $cadena_sql.=" AND O.entidad = I.entidad ";
                                $cadena_sql.=" AND O.unidad_ejecutora = I.unidad_ejecutora ";
                                $cadena_sql.=" AND O.consecutivo = I.consecutivo ";
                                $cadena_sql.=" and (O.VIGENCIA,O.ENTIDAD,O.UNIDAD_EJECUTORA,O.TIPO_DOCUMENTO,O.CONSECUTIVO) ";
                                $cadena_sql.=" IN (SELECT VIGENCIA,ENTIDAD,UNIDAD_EJECUTORA,TIPO_DOCUMENTO,CONSECUTIVO FROM OGT.ogt_orden_pago_tmp)";
                                $cadena_sql.=" GROUP BY ";
                                $cadena_sql.=" O.VIGENCIA,";
                                $cadena_sql.=" O.ENTIDAD,";
                                $cadena_sql.=" O.UNIDAD_EJECUTORA,";
                                $cadena_sql.=" O.TIPO_DOCUMENTO,";
                                $cadena_sql.=" O.CONSECUTIVO,";
                                $cadena_sql.=" 1,";
                                $cadena_sql.=" O.TER_ID, ";
                                $cadena_sql.=" O.DETALLE, ";
                                $cadena_sql.=" O.NUM_CONVENIO,";
                                $cadena_sql.=" O.CODIGO_CONTABLE_NETO,";
                                $cadena_sql.=" O.CODIGO_CONTABLE_BRUTO,";
                                $cadena_sql.=" O.FORMA_PAGO,";
                                $cadena_sql.=" O.NUMERO_CUENTA,";
                                $cadena_sql.=" O.BANCO,";
                                $cadena_sql.=" O.CLASE,";
                                $cadena_sql.=" O.REGIMEN,";
                                $cadena_sql.=" O.ENTIDAD,";
                                $cadena_sql.=" O.VIGENCIA ,";
                                $cadena_sql.=" TO_CHAR(O.NUM_CONVENIO)";
                                break;
                            
                        case "insertar_imputacion_de_tmp":
                                $cadena_sql=" INSERT INTO OGT.OGT_IMPUTACION ";
                                $cadena_sql.=" (VIGENCIA,";
                                $cadena_sql.=" ENTIDAD,";
                                $cadena_sql.=" UNIDAD_EJECUTORA,";
                                $cadena_sql.=" TIPO_DOCUMENTO,";
                                $cadena_sql.=" CONSECUTIVO,";
                                $cadena_sql.=" RUBRO_INTERNO,";
                                $cadena_sql.=" DISPONIBILIDAD,";
                                $cadena_sql.=" VIGENCIA_PRESUPUESTO,";
                                $cadena_sql.=" ENTIDAD_PRESUPUESTO,";
                                $cadena_sql.=" UNIDAD_EJECUTORA_PRESUPUESTO,";
                                $cadena_sql.=" VALOR_BRUTO,";
                                $cadena_sql.=" ANO_PAC,";
                                $cadena_sql.=" MES_PAC,";
                                $cadena_sql.=" REGISTRO)";
                                $cadena_sql.=" SELECT VIGENCIA,";
                                $cadena_sql.=" ENTIDAD,";
                                $cadena_sql.=" UNIDAD_EJECUTORA,";
                                $cadena_sql.=" TIPO_DOCUMENTO,";
                                $cadena_sql.=" CONSECUTIVO,";
                                $cadena_sql.=" RUBRO_INTERNO,";
                                $cadena_sql.=" DISPONIBILIDAD,";
                                $cadena_sql.=" VIGENCIA,";
                                $cadena_sql.=" ENTIDAD,";
                                $cadena_sql.=" UNIDAD_EJECUTORA,";
                                $cadena_sql.=" VALOR_BRUTO,";
                                $cadena_sql.=" ANO_PAC,";
                                $cadena_sql.=" MES_PAC,";
                                $cadena_sql.=" REGISTRO";
                                $cadena_sql.=" FROM OGT.ogt_imputacion_tmp";
                                break;
                            
                        case "insertar_ogt_registro_presupuestal":
                                $cadena_sql=" INSERT INTO OGT.OGT_REGISTRO_PRESUPUESTAL ";
                                $cadena_sql.=" (VIGENCIA,";
                                $cadena_sql.=" ENTIDAD,";
                                $cadena_sql.=" UNIDAD_EJECUTORA,";
                                $cadena_sql.=" TIPO_DOCUMENTO,";
                                $cadena_sql.=" CONSECUTIVO,";
                                $cadena_sql.=" RUBRO_INTERNO,";
                                $cadena_sql.=" DISPONIBILIDAD,";
                                $cadena_sql.=" REGISTRO,";
                                $cadena_sql.=" VIGENCIA_PRESUPUESTO,";
                                $cadena_sql.=" ENTIDAD_PRESUPUESTO,";
                                $cadena_sql.=" UNIDAD_EJECUTORA_PRESUPUESTO,";
                                $cadena_sql.=" VALOR_REGISTRO) ";
                                $cadena_sql.=" SELECT VIGENCIA,";
                                $cadena_sql.=" ENTIDAD,";
                                $cadena_sql.=" UNIDAD_EJECUTORA,";
                                $cadena_sql.=" TIPO_DOCUMENTO,";
                                $cadena_sql.=" CONSECUTIVO,";
                                $cadena_sql.=" RUBRO_INTERNO,";
                                $cadena_sql.=" DISPONIBILIDAD,";
                                $cadena_sql.=" REGISTRO,";
                                $cadena_sql.=" VIGENCIA,";
                                $cadena_sql.=" ENTIDAD,";
                                $cadena_sql.=" UNIDAD_EJECUTORA,";
                                $cadena_sql.=" VALOR_BRUTO";
                                $cadena_sql.=" FROM OGT.ogt_imputacion_tmp";
                                break;
                        
                        case "insertar_informacion_exogena":
                                $cadena_sql=" INSERT INTO OGT.OGT_INFORMACION_EXOGENA ";
                                $cadena_sql.=" (VIGENCIA,";
                                $cadena_sql.=" VIGENCIA_PRESUPUESTO,";
                                $cadena_sql.=" ENTIDAD,";
                                $cadena_sql.=" UNIDAD_EJECUTORA,";
                                $cadena_sql.=" TIPO_DOCUMENTO,";
                                $cadena_sql.=" CONSECUTIVO,";
                                $cadena_sql.=" TIPO_DOCUMENTO_IE,";
                                $cadena_sql.=" NUMERO_DOCUMENTO,";
                                $cadena_sql.=" ITEM,";
                                $cadena_sql.=" RUBRO_INTERNO,";
                                $cadena_sql.=" DISPONIBILIDAD,";
                                $cadena_sql.=" REGISTRO,";
                                $cadena_sql.=" TER_ID,";
                                $cadena_sql.=" CODIGO_ACTIVIDAD, ";
                                $cadena_sql.=" FECHA,";
                                $cadena_sql.=" FORMA_PAGO,";
                                $cadena_sql.=" NUMERO_CUENTA,";
                                $cadena_sql.=" BANCO,";
                                $cadena_sql.=" CLASE,";
                                $cadena_sql.=" VALOR_BRUTO,";
                                $cadena_sql.=" VALOR_EMERGENCIA,";
                                $cadena_sql.=" VALOR_BASE,";
                                $cadena_sql.=" IVA_PESOS) ";
                                $cadena_sql.=" SELECT IP.VIGENCIA,";
                                $cadena_sql.=" IP.VIGENCIA,";
                                $cadena_sql.=" IP.ENTIDAD,";
                                $cadena_sql.=" IP.UNIDAD_EJECUTORA,";
                                $cadena_sql.=" IP.TIPO_DOCUMENTO,";
                                $cadena_sql.=" IP.CONSECUTIVO,";
                                $cadena_sql.=" IP.TIPO_DOCUMENTO_IE,";
                                $cadena_sql.=" IP.NUMERO_DOCUMENTO,";
                                $cadena_sql.=" 1,";
                                $cadena_sql.=" IP.RUBRO_INTERNO,";
                                $cadena_sql.=" max(IP.DISPONIBILIDAD),";
                                $cadena_sql.=" max(IP.REGISTRO),";
                                $cadena_sql.=" OP.TER_ID,";
                                $cadena_sql.=" '',";
                                $cadena_sql.=" SYSDATE,";
                                $cadena_sql.=" OP.FORMA_PAGO,";
                                $cadena_sql.=" OP.NUMERO_CUENTA,";
                                $cadena_sql.=" OP.BANCO,";
                                $cadena_sql.=" OP.CLASE,";
                                $cadena_sql.=" IP.VALOR_BRUTO,";
                                $cadena_sql.=" (NVL(IP.VALOR_BRUTO,0)*4/1000),";
                                $cadena_sql.=" IP.VALOR_BRUTO,";
                                $cadena_sql.=" 0";
                                $cadena_sql.=" FROM OGT.ogt_imputacion_tmp IP , OGT.ogt_orden_pago_tmp OP ";
                                $cadena_sql.=" WHERE IP.CONSECUTIVO=OP.CONSECUTIVO ";
                                $cadena_sql.=" AND IP.UNIDAD_EJECUTORA=OP.UNIDAD_EJECUTORA";
                                $cadena_sql.=" AND IP.TIPO_DOCUMENTO=OP.TIPO_DOCUMENTO";
                                $cadena_sql.=" AND IP.VIGENCIA=OP.VIGENCIA";
                                $cadena_sql.=" GROUP BY IP.VIGENCIA,";
                                $cadena_sql.=" IP.VIGENCIA,";
                                $cadena_sql.=" IP.ENTIDAD,";
                                $cadena_sql.=" IP.UNIDAD_EJECUTORA,";
                                $cadena_sql.=" IP.TIPO_DOCUMENTO,";
                                $cadena_sql.=" IP.CONSECUTIVO,";
                                $cadena_sql.=" IP.TIPO_DOCUMENTO_IE,";
                                $cadena_sql.=" IP.NUMERO_DOCUMENTO,";
                                $cadena_sql.=" 1,";
                                $cadena_sql.=" IP.RUBRO_INTERNO,";
                                $cadena_sql.=" OP.TER_ID,";
                                $cadena_sql.=" SYSDATE,";
                                $cadena_sql.=" OP.FORMA_PAGO,";
                                $cadena_sql.=" OP.NUMERO_CUENTA,";
                                $cadena_sql.=" OP.BANCO,";
                                $cadena_sql.=" OP.CLASE,";
                                $cadena_sql.=" IP.VALOR_BRUTO,";
                                $cadena_sql.=" (NVL(IP.VALOR_BRUTO,0)*4/1000),";
                                $cadena_sql.=" IP.VALOR_BRUTO,";
                                $cadena_sql.=" 0";
                                break;
                            
                        case "insertar_detalle_descuento_de_tmp":
                                $cadena_sql=" INSERT INTO OGT.OGT_DETALLE_DESCUENTO";
                                $cadena_sql.=" (VIGENCIA,";
                                $cadena_sql.=" ENTIDAD,";
                                $cadena_sql.=" UNIDAD_EJECUTORA,";
                                $cadena_sql.=" TIPO_DOCUMENTO,";
                                $cadena_sql.=" CONSECUTIVO,";
                                $cadena_sql.=" RUBRO_INTERNO,";
                                $cadena_sql.=" VIGENCIA_PRESUPUESTO,";
                                $cadena_sql.=" DISPONIBILIDAD,";
                                $cadena_sql.=" REGISTRO, ";
                                $cadena_sql.=" NUMERO_DOCUMENTO,";
                                $cadena_sql.=" ITEM,";
                                $cadena_sql.=" CODIGO_INTERNO,";
                                $cadena_sql.=" FECHA_GRABACION,";
                                $cadena_sql.=" VALOR_BASE_RETENCION,";
                                $cadena_sql.=" VALOR_DESCUENTO) ";
                                $cadena_sql.=" SELECT IP.VIGENCIA,";
                                $cadena_sql.=" IP.ENTIDAD,";
                                $cadena_sql.=" IP.UNIDAD_EJECUTORA,";
                                $cadena_sql.=" IP.TIPO_DOCUMENTO,";
                                $cadena_sql.=" IP.CONSECUTIVO,";
                                $cadena_sql.=" IP.RUBRO_INTERNO,";
                                $cadena_sql.=" IP.VIGENCIA,";
                                $cadena_sql.=" max(IP.DISPONIBILIDAD),";
                                $cadena_sql.=" max(IP.REGISTRO),";
                                $cadena_sql.=" IP.NUMERO_DOCUMENTO,";
                                $cadena_sql.=" 1,";
                                $cadena_sql.=" DS.CODIGO_INTERNO,";
                                $cadena_sql.=" SYSDATE ,";
                                $cadena_sql.=" VALOR_BASE_RETENCION,";
                                $cadena_sql.=" ROUND(VALOR_DESCUENTO,0)";
                                $cadena_sql.=" FROM OGT.ogt_imputacion_tmp IP , OGT.ogt_detalle_dscto_tmp DS ";
                                $cadena_sql.=" where IP.CONSECUTIVO=DS.CONSECUTIVO ";
                                $cadena_sql.=" and IP.VIGENCIA = DS.VIGENCIA ";
                                $cadena_sql.=" AND IP.UNIDAD_EJECUTORA=DS.UNIDAD_EJECUTORA ";
                                $cadena_sql.=" AND IP.TIPO_DOCUMENTO=DS.TIPO_DOCUMENTO";
                                $cadena_sql.=" GROUP BY IP.VIGENCIA,";
                                $cadena_sql.=" IP.ENTIDAD,";
                                $cadena_sql.=" IP.UNIDAD_EJECUTORA,";
                                $cadena_sql.=" IP.TIPO_DOCUMENTO,";
                                $cadena_sql.=" IP.CONSECUTIVO,";
                                $cadena_sql.=" IP.RUBRO_INTERNO,";
                                $cadena_sql.=" IP.VIGENCIA,";
                                $cadena_sql.=" IP.NUMERO_DOCUMENTO,";
                                $cadena_sql.=" 1,";
                                $cadena_sql.=" DS.CODIGO_INTERNO,";
                                $cadena_sql.=" SYSDATE,";
                                $cadena_sql.=" VALOR_BASE_RETENCION,";
                                $cadena_sql.=" VALOR_DESCUENTO";
                                break;
                            
                        case "existe_cuentas_orden":
                                $cadena_sql=" SELECT COUNT(*) CONTADOR ";
                                $cadena_sql.=" FROM ogt.ogt_orden_pago_tmp";
                                $cadena_sql.=" WHERE CODIGO_CONTABLE_ORDEN1 IS NULL";
                                $cadena_sql.=" AND CODIGO_CONTABLE_ORDEN2 IS NULL";
                                break;
                            
                        case "insertar_cuentas_orden_pago2":
                                $cadena_sql=" insert into OGT.OGT_CUENTAS_ORDEN_PAGO";
                                $cadena_sql.=" (CONSECUTIVO, ";
                                $cadena_sql.=" ENTIDAD,";
                                $cadena_sql.=" TIPO_DOCUMENTO, ";
                                $cadena_sql.=" UNIDAD_EJECUTORA, ";
                                $cadena_sql.=" VIGENCIA, ";
                                $cadena_sql.=" CUENTA_CONTABLE, ";
                                $cadena_sql.=" NATURALEZA, ";
                                $cadena_sql.=" consecutivo_cuenta,";
                                $cadena_sql.=" VALOR_CAUSADO, ";
                                $cadena_sql.=" ES_DESCUENTO) ";
                                $cadena_sql.=" select consecutivo,";
                                $cadena_sql.=" entidad,";
                                $cadena_sql.=" tipo_documento,";
                                $cadena_sql.=" unidad_ejecutora,";
                                $cadena_sql.=" vigencia, ";
                                $cadena_sql.=" codigo_contable_orden2 CUENTA_CONTABLE,";
                                $cadena_sql.=" 'C' naturaleza,";
                                $cadena_sql.=" 1 consecutivo_cuenta, ";
                                $cadena_sql.=" valor_bruto,";
                                $cadena_sql.=" 'N' ";
                                $cadena_sql.=" from OGT.ogt_orden_pago_tmp";
                                break;
                         
                        case "insertar_cuentas_orden_pago1":
                                $cadena_sql=" insert into OGT.OGT_CUENTAS_ORDEN_PAGO";
                                $cadena_sql.=" (CONSECUTIVO, ";
                                $cadena_sql.=" ENTIDAD, ";
                                $cadena_sql.=" TIPO_DOCUMENTO, ";
                                $cadena_sql.=" UNIDAD_EJECUTORA, ";
                                $cadena_sql.=" VIGENCIA, ";
                                $cadena_sql.=" CUENTA_CONTABLE, ";
                                $cadena_sql.=" NATURALEZA, ";
                                $cadena_sql.=" consecutivo_cuenta,";
                                $cadena_sql.=" VALOR_CAUSADO, ";
                                $cadena_sql.=" ES_DESCUENTO)";
                                $cadena_sql.=" select consecutivo,";
                                $cadena_sql.=" entidad,";
                                $cadena_sql.=" tipo_documento,";
                                $cadena_sql.=" unidad_ejecutora,";
                                $cadena_sql.=" vigencia,";
                                $cadena_sql.=" codigo_contable_orden1 CUENTA_CONTABLE,";
                                $cadena_sql.=" 'D' naturaleza,";
                                $cadena_sql.=" 1 consecutivo_cuenta, ";
                                $cadena_sql.=" valor_bruto,";
                                $cadena_sql.=" 'N' ";
                                $cadena_sql.=" from OGT.ogt_orden_pago_tmp";
                                break;
                           
                        case "insertar_cuentas_orden_codigo_contable_bruto":
                                $cadena_sql=" insert into";
                                $cadena_sql.=" OGT.OGT_CUENTAS_ORDEN_PAGO";
                                $cadena_sql.=" (CONSECUTIVO, ";
                                $cadena_sql.=" ENTIDAD,";
                                $cadena_sql.=" TIPO_DOCUMENTO, ";
                                $cadena_sql.=" UNIDAD_EJECUTORA,";
                                $cadena_sql.=" VIGENCIA, ";
                                $cadena_sql.=" CUENTA_CONTABLE, ";
                                $cadena_sql.=" NATURALEZA, ";
                                $cadena_sql.=" consecutivo_cuenta,";
                                $cadena_sql.=" VALOR_CAUSADO, ";
                                $cadena_sql.=" ES_DESCUENTO)";
                                $cadena_sql.=" select consecutivo,";
                                $cadena_sql.=" entidad,";
                                $cadena_sql.=" tipo_documento,";
                                $cadena_sql.=" unidad_ejecutora,";
                                $cadena_sql.=" vigencia,";
                                $cadena_sql.=" codigo_contable_bruto,";
                                $cadena_sql.=" 'D' naturaleza,";
                                $cadena_sql.=" 1 consecutivo_cuenta, ";
                                $cadena_sql.=" valor_bruto,";
                                $cadena_sql.=" 'B'";
                                $cadena_sql.=" from OGT.ogt_orden_pago_tmp";
                                break;
                            
                        case "insertar_cuentas_orden_codigo_contable_neto":
                                $cadena_sql=" insert into";
                                $cadena_sql.=" OGT.OGT_CUENTAS_ORDEN_PAGO ";
                                $cadena_sql.=" (CONSECUTIVO, ";
                                $cadena_sql.=" ENTIDAD, ";
                                $cadena_sql.=" TIPO_DOCUMENTO, ";
                                $cadena_sql.=" UNIDAD_EJECUTORA, ";
                                $cadena_sql.=" VIGENCIA, ";
                                $cadena_sql.=" CUENTA_CONTABLE, ";
                                $cadena_sql.=" NATURALEZA, ";
                                $cadena_sql.=" consecutivo_cuenta,";
                                $cadena_sql.=" VALOR_CAUSADO, ";
                                $cadena_sql.=" ES_DESCUENTO)";
                                $cadena_sql.=" select consecutivo,";
                                $cadena_sql.=" entidad,";
                                $cadena_sql.=" tipo_documento,";
                                $cadena_sql.=" unidad_ejecutora,";
                                $cadena_sql.=" vigencia, ";
                                $cadena_sql.=" codigo_contable_neto,";
                                $cadena_sql.=" 'C' naturaleza,";
                                $cadena_sql.=" 1 consecutivo_cuenta, ";
                                $cadena_sql.=" valor_neto,";
                                $cadena_sql.=" 'T'";
                                $cadena_sql.=" from OGT.ogt_orden_pago_tmp";
                                break;
                            
                        case "insertar_conceptos_orden_pago":
                                $cadena_sql=" insert into ";
                                $cadena_sql.=" OGT.OGT_CONCEPTOS_ORDEN_PAGO ";
                                $cadena_sql.=" (CONSECUTIVO, ";
                                $cadena_sql.=" ENTIDAD, ";
                                $cadena_sql.=" TIPO_DOCUMENTO, ";
                                $cadena_sql.=" UNIDAD_EJECUTORA, ";
                                $cadena_sql.=" VIGENCIA, ";
                                $cadena_sql.=" COTE_ID, ";
                                $cadena_sql.=" CONSECUTIVO_COTE,";
                                $cadena_sql.=" VALOR)";
                                $cadena_sql.=" select consecutivo,";
                                $cadena_sql.=" entidad,";
                                $cadena_sql.=" tipo_documento,";
                                $cadena_sql.=" unidad_ejecutora,";
                                $cadena_sql.=" vigencia, ";
                                $cadena_sql.=" concepto,";
                                $cadena_sql.=" 1, ";
                                $cadena_sql.=" valor_bruto";
                                $cadena_sql.=" from OGT.ogt_orden_pago_tmp";
                                break;
                            
                        case "vaciar_orden_pago_tmp":
                                $cadena_sql=" DELETE OGT.ogt_orden_pago_tmp";
                                break;

                        case "vaciar_imputacion_tmp":
                                $cadena_sql=" DELETE OGT.ogt_imputacion_tmp";
                                break;

                        case "vaciar_detalle_descuento_tmp":
                                $cadena_sql=" DELETE OGT.ogt_detalle_dscto_tmp";
                                break;

                            
                        case "orden_pago_tmp":
                                $cadena_sql=" SELECT ";
                                $cadena_sql.=" VIGENCIA,";
                                $cadena_sql.=" ENTIDAD,";
                                $cadena_sql.=" UNIDAD_EJECUTORA,";
                                $cadena_sql.=" TIPO_DOCUMENTO,";
                                $cadena_sql.=" CONSECUTIVO,";
                                $cadena_sql.=" NUM_CONVENIO,";
                                $cadena_sql.=" NUM_DOC_SOPORTE,";
                                $cadena_sql.=" CONCEPTO,";
                                $cadena_sql.=" TER_ID,";
                                $cadena_sql.=" CODIGO_CONTABLE_NETO,";
                                $cadena_sql.=" CODIGO_CONTABLE_BRUTO,";
                                $cadena_sql.=" FORMA_PAGO,";
                                $cadena_sql.=" NUMERO_CUENTA,";
                                $cadena_sql.=" BANCO,";
                                $cadena_sql.=" CLASE,";
                                $cadena_sql.=" REGIMEN,";
                                $cadena_sql.=" TIPO_DOC_IDENT,";
                                $cadena_sql.=" NUM_DOC_IDENT,";
                                $cadena_sql.=" DETALLE,";
                                $cadena_sql.=" VALOR_BRUTO,";
                                $cadena_sql.=" VALOR_NETO,";
                                $cadena_sql.=" CODIGO_CONTABLE_ORDEN1,";
                                $cadena_sql.=" CODIGO_CONTABLE_ORDEN2,";
                                $cadena_sql.=" VIGENCIA_PRESUPUESTO";
                                $cadena_sql.=" FROM OGT.OGT_ORDEN_PAGO_TMP";
                                break;
                         
                        case "eliminar_documento_pago":
                                $cadena_sql=" DELETE OGT.OGT_DOCUMENTO_PAGO";
                                $cadena_sql.=" WHERE ";
                                $cadena_sql.=" VIGENCIA= '".$variable['VIGENCIA']."' ";
                                $cadena_sql.=" AND ENTIDAD= '".$variable['ENTIDAD']."' ";
                                $cadena_sql.=" AND UNIDAD_EJECUTORA= '".$variable['UNIDAD_EJECUTORA']."' ";
                                $cadena_sql.=" AND TIPO_DOCUMENTO= '".$variable['TIPO_DOCUMENTO']."' ";
                                $cadena_sql.=" AND CONSECUTIVO= '".$variable['CONSECUTIVO']."' ";
                                $cadena_sql.=" AND TO_CHAR(fecha_diligenciamiento,'DD-MM-YYYY') = TO_CHAR(SYSDATE,'DD-MM-YYYY')";
                                break;
                          
                        case "eliminar_orden_pago":
                                $cadena_sql=" DELETE OGT.OGT_ORDEN_PAGO";
                                $cadena_sql.=" WHERE ";
                                $cadena_sql.=" VIGENCIA='".$variable['VIGENCIA']."'";
                                $cadena_sql.=" AND ENTIDAD='".$variable['ENTIDAD']."'";
                                $cadena_sql.=" AND UNIDAD_EJECUTORA='".$variable['UNIDAD_EJECUTORA']."'";
                                $cadena_sql.=" AND TIPO_DOCUMENTO='".$variable['TIPO_DOCUMENTO']."'";
                                $cadena_sql.=" AND CONSECUTIVO='".$variable['CONSECUTIVO']."'";
                                $cadena_sql.=" AND TER_ID='".$variable['TER_ID']."'";
                                $cadena_sql.=" AND DETALLE='".$variable['DETALLE']."'";
                                $cadena_sql.=" AND FORMA_PAGO='".$variable['FORMA_PAGO']."'";
                                $cadena_sql.=" AND NUMERO_CUENTA='".$variable['NUMERO_CUENTA']."'";
                                $cadena_sql.=" AND BANCO='".$variable['BANCO']."'";
                                $cadena_sql.=" AND CLASE='".$variable['CLASE']."'";
                                
                                break;
                            
                        case "imputacion_tmp":
                            
                                $cadena_sql=" SELECT VIGENCIA,";
                                $cadena_sql.=" ENTIDAD,";
                                $cadena_sql.=" UNIDAD_EJECUTORA,";
                                $cadena_sql.=" TIPO_DOCUMENTO,";
                                $cadena_sql.=" CONSECUTIVO,";
                                $cadena_sql.=" RUBRO_INTERNO,";
                                $cadena_sql.=" DISPONIBILIDAD,";
                                $cadena_sql.=" VIGENCIA,";
                                $cadena_sql.=" ENTIDAD,";
                                $cadena_sql.=" UNIDAD_EJECUTORA,";
                                $cadena_sql.=" VALOR_BRUTO,";
                                $cadena_sql.=" ANO_PAC,";
                                $cadena_sql.=" MES_PAC,";
                                $cadena_sql.=" REGISTRO";
                                $cadena_sql.=" FROM OGT.ogt_imputacion_tmp";
                                break;
                            
                        case "eliminar_imputacion":
                                $cadena_sql=" DELETE OGT.OGT_IMPUTACION";
                                $cadena_sql.=" WHERE ";
                                $cadena_sql.=" VIGENCIA='".$variable['VIGENCIA']."'";
                                $cadena_sql.=" AND ENTIDAD='".$variable['ENTIDAD']."'";
                                $cadena_sql.=" AND UNIDAD_EJECUTORA='".$variable['UNIDAD_EJECUTORA']."'";
                                $cadena_sql.=" AND TIPO_DOCUMENTO='".$variable['TIPO_DOCUMENTO']."'";
                                $cadena_sql.=" AND CONSECUTIVO='".$variable['CONSECUTIVO']."'";
                                $cadena_sql.=" AND RUBRO_INTERNO='".$variable['RUBRO_INTERNO']."'";
                                $cadena_sql.=" AND DISPONIBILIDAD='".$variable['DISPONIBILIDAD']."'";
                                $cadena_sql.=" AND VALOR_BRUTO='".$variable['VALOR_BRUTO']."'";
                                $cadena_sql.=" AND ANO_PAC='".$variable['ANO_PAC']."'";
                                $cadena_sql.=" AND MES_PAC='".$variable['MES_PAC']."'";
                                $cadena_sql.=" AND REGISTRO='".$variable['REGISTRO']."'";
                                break;
                         
                        case "eliminar_ogt_registro_presupuestal":
                                $cadena_sql=" DELETE OGT.OGT_REGISTRO_PRESUPUESTAL";
                                $cadena_sql.=" WHERE ";
                                $cadena_sql.=" VIGENCIA= '".$variable['VIGENCIA']."'";
                                $cadena_sql.=" AND ENTIDAD= '".$variable['ENTIDAD']."'";
                                $cadena_sql.=" AND UNIDAD_EJECUTORA= '".$variable['UNIDAD_EJECUTORA']."'";
                                $cadena_sql.=" AND TIPO_DOCUMENTO= '".$variable['TIPO_DOCUMENTO']."'";
                                $cadena_sql.=" AND CONSECUTIVO= '".$variable['CONSECUTIVO']."'";
                                $cadena_sql.=" AND RUBRO_INTERNO= '".$variable['RUBRO_INTERNO']."'";
                                $cadena_sql.=" AND DISPONIBILIDAD= '".$variable['DISPONIBILIDAD']."'";
                                $cadena_sql.=" AND REGISTRO= '".$variable['REGISTRO']."'";
                                $cadena_sql.=" AND VALOR_REGISTRO= '".$variable['VALOR_REGISTRO']."'";
                                break;
                        
                            case "exogena_de_tmp":
                                $cadena_sql=" SELECT ";
                                $cadena_sql.=" IP.VIGENCIA,";
                                $cadena_sql.=" IP.ENTIDAD,";
                                $cadena_sql.=" IP.UNIDAD_EJECUTORA,";
                                $cadena_sql.=" IP.TIPO_DOCUMENTO,";
                                $cadena_sql.=" IP.CONSECUTIVO,";
                                $cadena_sql.=" IP.TIPO_DOCUMENTO_IE,";
                                $cadena_sql.=" IP.NUMERO_DOCUMENTO,";
                                $cadena_sql.=" IP.RUBRO_INTERNO,";
                                $cadena_sql.=" max(IP.DISPONIBILIDAD) AS DISPONIBILIDAD,";
                                $cadena_sql.=" max(IP.REGISTRO)  AS REGISTRO,";
                                $cadena_sql.=" OP.TER_ID,";
                                $cadena_sql.=" OP.FORMA_PAGO,";
                                $cadena_sql.=" OP.NUMERO_CUENTA,";
                                $cadena_sql.=" OP.BANCO,";
                                $cadena_sql.=" OP.CLASE,";
                                $cadena_sql.=" IP.VALOR_BRUTO,";
                                $cadena_sql.=" (NVL(IP.VALOR_BRUTO,0)*4/1000),";
                                $cadena_sql.=" IP.VALOR_BRUTO";
                                $cadena_sql.=" FROM OGT.ogt_imputacion_tmp IP , OGT.ogt_orden_pago_tmp OP ";
                                $cadena_sql.=" WHERE IP.CONSECUTIVO=OP.CONSECUTIVO ";
                                $cadena_sql.=" AND IP.UNIDAD_EJECUTORA=OP.UNIDAD_EJECUTORA";
                                $cadena_sql.=" AND IP.TIPO_DOCUMENTO=OP.TIPO_DOCUMENTO";
                                $cadena_sql.=" AND IP.VIGENCIA=OP.VIGENCIA";
                                $cadena_sql.=" GROUP BY IP.VIGENCIA,";
                                $cadena_sql.=" IP.VIGENCIA,";
                                $cadena_sql.=" IP.ENTIDAD,";
                                $cadena_sql.=" IP.UNIDAD_EJECUTORA,";
                                $cadena_sql.=" IP.TIPO_DOCUMENTO,";
                                $cadena_sql.=" IP.CONSECUTIVO,";
                                $cadena_sql.=" IP.TIPO_DOCUMENTO_IE,";
                                $cadena_sql.=" IP.NUMERO_DOCUMENTO,";
                                $cadena_sql.=" IP.RUBRO_INTERNO,";
                                $cadena_sql.=" OP.TER_ID,";
                                $cadena_sql.=" SYSDATE,";
                                $cadena_sql.=" OP.FORMA_PAGO,";
                                $cadena_sql.=" OP.NUMERO_CUENTA,";
                                $cadena_sql.=" OP.BANCO,";
                                $cadena_sql.=" OP.CLASE,";
                                $cadena_sql.=" IP.VALOR_BRUTO,";
                                $cadena_sql.=" (NVL(IP.VALOR_BRUTO,0)*4/1000),";
                                $cadena_sql.=" IP.VALOR_BRUTO";
                                break;
                          
                        case "eliminar_informacion_exogena":
                                $cadena_sql=" DELETE OGT.OGT_INFORMACION_EXOGENA ";
                                $cadena_sql.=" WHERE ";
                                $cadena_sql.=" VIGENCIA='".$variable['VIGENCIA']."'";
                                $cadena_sql.=" AND VIGENCIA_PRESUPUESTO='".$variable['VIGENCIA']."'";
                                $cadena_sql.=" AND ENTIDAD='".$variable['ENTIDAD']."'";
                                $cadena_sql.=" AND UNIDAD_EJECUTORA='".$variable['UNIDAD_EJECUTORA']."'";
                                $cadena_sql.=" AND TIPO_DOCUMENTO='".$variable['TIPO_DOCUMENTO']."'";
                                $cadena_sql.=" AND CONSECUTIVO='".$variable['CONSECUTIVO']."'";
                                $cadena_sql.=" AND TIPO_DOCUMENTO_IE='".$variable['TIPO_DOCUMENTO_IE']."'";
                                $cadena_sql.=" AND NUMERO_DOCUMENTO='".$variable['NUMERO_DOCUMENTO']."'";
                                $cadena_sql.=" AND RUBRO_INTERNO='".$variable['RUBRO_INTERNO']."'";
                                $cadena_sql.=" AND DISPONIBILIDAD='".$variable['DISPONIBILIDAD']."'";
                                $cadena_sql.=" AND REGISTRO='".$variable['REGISTRO']."'";
                                $cadena_sql.=" AND TER_ID='".$variable['TER_ID']."'";
                                $cadena_sql.=" AND TO_CHAR(FECHA,'DD-MM-YYYY') = TO_CHAR(SYSDATE,'DD-MM-YYYY')";
                                $cadena_sql.=" AND FORMA_PAGO='".$variable['FORMA_PAGO']."'";
                                $cadena_sql.=" AND NUMERO_CUENTA='".$variable['NUMERO_CUENTA']."'";
                                $cadena_sql.=" AND BANCO='".$variable['BANCO']."'";
                                $cadena_sql.=" AND CLASE='".$variable['CLASE']."'";
                                $cadena_sql.=" AND VALOR_BRUTO='".$variable['VALOR_BRUTO']."'";
                                break;
                        
                         case "eliminar_detalle_descuento":
                                $cadena_sql=" DELETE OGT.OGT_DETALLE_DESCUENTO ";
                                $cadena_sql.=" WHERE ";
                                $cadena_sql.=" VIGENCIA='".$variable['VIGENCIA']."'";
                                $cadena_sql.=" AND ENTIDAD='".$variable['ENTIDAD']."'";
                                $cadena_sql.=" AND UNIDAD_EJECUTORA='".$variable['UNIDAD_EJECUTORA']."'";
                                $cadena_sql.=" AND TIPO_DOCUMENTO='".$variable['TIPO_DOCUMENTO']."'";
                                $cadena_sql.=" AND CONSECUTIVO='".$variable['CONSECUTIVO']."'";
                                $cadena_sql.=" AND RUBRO_INTERNO='".$variable['RUBRO_INTERNO']."'";
                                $cadena_sql.=" AND DISPONIBILIDAD='".$variable['DISPONIBILIDAD']."'";
                                $cadena_sql.=" AND REGISTRO='".$variable['REGISTRO']."'";
                                $cadena_sql.=" AND NUMERO_DOCUMENTO='".$variable['NUMERO_DOCUMENTO']."'";
                                $cadena_sql.=" AND CODIGO_INTERNO='".$variable['CODIGO_INTERNO']."'";
                                $cadena_sql.=" AND VALOR_BASE_RETENCION='".$variable['VALOR_BASE_RETENCION']."'";
                                $cadena_sql.=" AND VALOR_DESCUENTO='".$variable['VALOR_DESCUENTO']."'";
                                break;
                        
                        case "detalle_descuento_tmp":
                                $cadena_sql=" SELECT IP.VIGENCIA,";
                                $cadena_sql.=" IP.ENTIDAD,";
                                $cadena_sql.=" IP.UNIDAD_EJECUTORA,";
                                $cadena_sql.=" IP.TIPO_DOCUMENTO,";
                                $cadena_sql.=" IP.CONSECUTIVO,";
                                $cadena_sql.=" IP.RUBRO_INTERNO,";
                                $cadena_sql.=" IP.VIGENCIA,";
                                $cadena_sql.=" max(IP.DISPONIBILIDAD) AS DISPONIBILIDAD,";
                                $cadena_sql.=" max(IP.REGISTRO) AS REGISTRO,";
                                $cadena_sql.=" IP.NUMERO_DOCUMENTO,";
                                $cadena_sql.=" DS.CODIGO_INTERNO,";
                                $cadena_sql.=" VALOR_BASE_RETENCION,";
                                $cadena_sql.=" VALOR_DESCUENTO";
                                $cadena_sql.=" FROM OGT.ogt_imputacion_tmp IP , OGT.ogt_detalle_dscto_tmp DS ";
                                $cadena_sql.=" where IP.CONSECUTIVO=DS.CONSECUTIVO ";
                                $cadena_sql.=" and IP.VIGENCIA = DS.VIGENCIA ";
                                $cadena_sql.=" AND IP.UNIDAD_EJECUTORA=DS.UNIDAD_EJECUTORA ";
                                $cadena_sql.=" AND IP.TIPO_DOCUMENTO=DS.TIPO_DOCUMENTO";
                                $cadena_sql.=" GROUP BY IP.VIGENCIA,";
                                $cadena_sql.=" IP.ENTIDAD,";
                                $cadena_sql.=" IP.UNIDAD_EJECUTORA,";
                                $cadena_sql.=" IP.TIPO_DOCUMENTO,";
                                $cadena_sql.=" IP.CONSECUTIVO,";
                                $cadena_sql.=" IP.RUBRO_INTERNO,";
                                $cadena_sql.=" IP.VIGENCIA,";
                                $cadena_sql.=" IP.NUMERO_DOCUMENTO,";
                                $cadena_sql.=" DS.CODIGO_INTERNO,";
                                $cadena_sql.=" VALOR_BASE_RETENCION,";
                                $cadena_sql.=" VALOR_DESCUENTO";
                                
                                break;
                          
                        case "cuentas_orden_pago2_tmp":
                                $cadena_sql=" SELECT CONSECUTIVO,";
                                $cadena_sql.=" ENTIDAD,";
                                $cadena_sql.=" TIPO_DOCUMENTO,";
                                $cadena_sql.=" UNIDAD_EJECUTORA,";
                                $cadena_sql.=" VIGENCIA, ";
                                $cadena_sql.=" CODIGO_CONTABLE_ORDEN2 CUENTA_CONTABLE,";
                                $cadena_sql.=" 'C' NATURALEZA,";
                                $cadena_sql.=" 1 CONSECUTIVO_CUENTA, ";
                                $cadena_sql.=" VALOR_BRUTO,";
                                $cadena_sql.=" 'N' ES_DESCUENTO";
                                $cadena_sql.=" FROM OGT.ogt_orden_pago_tmp";
                                break;
                            
                        default:
				$cadena_sql="";
				break;
		}//fin switch
		return $cadena_sql;
	}// fin funcion cadena_sql
	
	
}//fin clase sql_adminSolicitudAprobarGiro
?>

