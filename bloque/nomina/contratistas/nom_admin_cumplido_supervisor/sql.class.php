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

class sql_adminCumplidoSupervisor extends sql
{
	function cadena_sql($configuracion,$conexion, $opcion,$variable="")
	{
		
		switch($opcion)
		{	
                    		
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

			case "datos_cuenta":
				$cadena_sql=" SELECT  COM.IC_BANCO  AS CODIGO,";
                                $cadena_sql.=" BAS.IB_PRIMER_NOMBRE AS BANCO,";
                                $cadena_sql.=" COM.IC_TIPO_CUENTA   AS TIPO_CTA,";
                                $cadena_sql.=" COM.IC_CUENTA        AS NRO_CTA ";
                                $cadena_sql.=" FROM SHD.SHD_INFORMACION_COMERCIAL COM";
                                $cadena_sql.=" INNER JOIN SHD.SHD_INFORMACION_BASICA BAS ON COM.IC_BANCO=BAS.ID";
                                $cadena_sql.=" WHERE COM.ID= ".$variable;                                              
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
                            
                        case "datos_orden_pago":
                                $cadena_sql=" SELECT DISTINCT";
                                $cadena_sql.=" DET_OP.VIGENCIA AS VIGENCIA, ";
                                $cadena_sql.=" DET_OP.RUBRO_INTERNO AS RUBRO,";
                                $cadena_sql.=" DET_OP.CODIGO_COMPANIA AS CODIGO_COMPANIA,";
                                $cadena_sql.=" DET_OP.CODIGO_UNIDAD_EJECUTORA AS COD_UNIDAD_EJEC, ";
                                $cadena_sql.=" DET_OP.CONSECUTIVO_ORDEN AS NUMERO_ORDEN, ";
                                $cadena_sql.=" OP.TER_ID AS ID_TERCERO,";
                                $cadena_sql.=" DECODE(OP.FECHA_APROBACION,'',TO_DATE(EGR.FECHA_REGISTRO,'DD-MM-YY'),TO_DATE(OP.FECHA_APROBACION,";
                                $cadena_sql.=" 'DD-MM-YY')) AS FECHA_ORDEN,";
                                $cadena_sql.=" DET_OP.NUMERO_DISPONIBILIDAD AS NUMERO_DISPONIBILIDAD,";
                                $cadena_sql.=" DET_OP.VALOR AS VALOR_OP, ";
                                $cadena_sql.=" DECODE(SUBSTR(OP.ESTADO,9,1),'1','ANULADO',SUBSTR(OP.ESTADO,4,1),'1','VIGENTE') AS ESTADO,";
                                $cadena_sql.=" TO_DATE(EGR.FECHA_REGISTRO,'DD-MM-YY') AS FECHA_PAGO";
                                $cadena_sql.=" FROM OGT.OGT_V_PREDIS_DETALLE DET_OP";
                                $cadena_sql.=" INNER JOIN PR.PR_COMPROMISOS COMP ";
                                $cadena_sql.=" ON DET_OP.VIGENCIA = COMP.VIGENCIA ";
                                $cadena_sql.=" AND DET_OP.CODIGO_COMPANIA = COMP.CODIGO_COMPANIA ";
                                $cadena_sql.=" AND DET_OP.CODIGO_UNIDAD_EJECUTORA = COMP.CODIGO_UNIDAD_EJECUTORA ";
                                $cadena_sql.=" AND DET_OP.NUMERO_REGISTRO = COMP.NUMERO_REGISTRO";
                                $cadena_sql.=" LEFT OUTER JOIN OGT.OGT_ORDEN_PAGO OP ";
                                $cadena_sql.=" ON DET_OP.VIGENCIA = OP.VIGENCIA ";
                                $cadena_sql.=" AND DET_OP.CODIGO_COMPANIA = OP.ENTIDAD ";
                                $cadena_sql.=" AND DET_OP.CODIGO_UNIDAD_EJECUTORA = OP.UNIDAD_EJECUTORA ";
                                $cadena_sql.=" AND DET_OP.CONSECUTIVO_ORDEN = OP.CONSECUTIVO";
                                $cadena_sql.=" AND OP.TIPO_DOCUMENTO = 'OP'";
                                $cadena_sql.=" LEFT OUTER JOIN OGT.OGT_DETALLE_EGRESO EGR ";
                                $cadena_sql.=" ON OP.CONSECUTIVO = EGR.CONSECUTIVO ";
                                $cadena_sql.=" AND OP.TER_ID = EGR.TER_ID";
                                $cadena_sql.=" AND OP.VIGENCIA = EGR.VIGENCIA ";
                                $cadena_sql.=" AND OP.UNIDAD_EJECUTORA = EGR.UNIDAD_EJECUTORA ";
                                $cadena_sql.=" WHERE ";
                                $cadena_sql.=" DET_OP.VIGENCIA =".$variable['vigencia'];
                                $cadena_sql.=" AND OP.IND_APROBADO = 1 ";
                                $cadena_sql.=" AND OP.TIPO_OP != 2";
                                $cadena_sql.=" AND COMP.NUMERO_DOCUMENTO=".$variable['identificacion'];
                                $cadena_sql.=" AND DET_OP.NUMERO_DISPONIBILIDAD=".$variable['num_disponibilidad'];
                                $cadena_sql.=" GROUP BY ";
                                $cadena_sql.=" DET_OP.VIGENCIA, ";
                                $cadena_sql.=" DET_OP.RUBRO_INTERNO,";
                                $cadena_sql.=" DET_OP.CODIGO_COMPANIA,";
                                $cadena_sql.=" DET_OP.CODIGO_UNIDAD_EJECUTORA, ";
                                $cadena_sql.=" DET_OP.CONSECUTIVO_ORDEN, ";
                                $cadena_sql.=" DET_OP.NUMERO_REGISTRO, ";
                                $cadena_sql.=" OP.TER_ID,";
                                $cadena_sql.=" OP.FECHA_APROBACION,";
                                $cadena_sql.=" DET_OP.NUMERO_DISPONIBILIDAD,";
                                $cadena_sql.=" OP.DETALLE,";
                                $cadena_sql.=" DET_OP.VALOR, ";
                                $cadena_sql.=" DECODE(SUBSTR(OP.ESTADO,9,1),'1','ANULADO',SUBSTR(OP.ESTADO,4,1),'1','VIGENTE'),";
                                $cadena_sql.=" EGR.FECHA_REGISTRO,";
                                $cadena_sql.=" OP.FECHA_ANULACION,";
                                $cadena_sql.=" OP.DESCRIPCION_ANULACION,";
                                $cadena_sql.=" OP.NUMERO_OFICIO_ANULACION,";
                                $cadena_sql.=" OP.FECHA_OFICIO_ANULACION,";
                                $cadena_sql.=" OP.ID_LM";
                                $cadena_sql.=" ORDER BY DET_OP.VIGENCIA,DET_OP.CODIGO_UNIDAD_EJECUTORA DESC,FECHA_ORDEN DESC";
                                break;		

                            
                       case "datos_novedades":
                                $cadena_sql=" SELECT ";
                                $cadena_sql.=" nov_id           AS id_nov,";
                                $cadena_sql.=" nov_id_tipo      AS cod_tipo_nov, ";
                                $cadena_sql.=" tpn_nombre       AS tipo_nov,";
                                $cadena_sql.=" tpn_descripcion  AS descripcion_tipo_nov,";
                                $cadena_sql.=" nov_cto_num   AS INTERNO_CO,";
                                $cadena_sql.=" nov_fecha        AS fecha_nov,";
                                $cadena_sql.=" nov_fecha_ini    AS fecha_inicio, ";
                                $cadena_sql.=" nov_fecha_fin    AS fecha_fin,";
                                $cadena_sql.=" nov_valor        AS valor_nov,";
                                $cadena_sql.=" nov_descripcion  AS descripcion_nov,";
                                $cadena_sql.=" nov_estado       AS estado_nov,";
                                $cadena_sql.=" nov_cta_id       AS cod_cta,";
                                $cadena_sql.=" cta_id_banco     AS cod_banco,";
                                $cadena_sql.=" ban_nombre       AS banco,";
                                $cadena_sql.=" cta_num          AS num_cta,";
                                $cadena_sql.=" cta_tipo         AS tipo_cta,";
                                $cadena_sql.=" cta_estado       AS estado_cta";
                                $cadena_sql.=" FROM fn_nom_novedad";
                                $cadena_sql.=" INNER JOIN fn_nom_tipo_novedad ON nov_id_tipo=tpn_id";
                                $cadena_sql.=" LEFT OUTER JOIN fn_nom_cuenta_banco ON nov_cta_id=cta_id";
                                $cadena_sql.=" LEFT OUTER JOIN fn_nom_banco ON cta_id_banco=ban_id";
                                $cadena_sql.=" WHERE nov_cto_vigencia=".$variable['vigencia'];
                                $cadena_sql.=" AND nov_cto_num=".$variable['cod_contrato'];
                                $cadena_sql.=" ORDER BY nov_id DESC";
                                break;		
               
                      
                       case "lista_cuentas":
                                $cadena_sql=" SELECT ";
                                $cadena_sql.=" cta_id       AS ID,";
                                $cadena_sql.=" concat('No.',cta_num,' - ',ban_nombre,' (',cta_tipo,')')   AS NOMBRE ";
                                $cadena_sql.=" FROM fn_nom_cuenta_banco";
                                $cadena_sql.=" INNER JOIN fn_nom_banco ON cta_id_banco=ban_id";
                                $cadena_sql.=" WHERE cta_con_tipo_id='".$variable['tipo_id']."'";
                                $cadena_sql.=" AND cta_con_num_id=".$variable['num_id'];
                                $cadena_sql.=" AND cta_estado='A'";
                                $cadena_sql.=" AND cta_id>0";
                                $cadena_sql.=" ORDER BY ban_nombre ";
                                break;		

         
                        case "codigo_contratista":
				$cadena_sql=" SELECT ";
                                $cadena_sql.=" cto_con_tipo_id  AS TIPO_ID,";
                                $cadena_sql.=" cto_con_num_id   AS NUM_ID";
                                $cadena_sql.=" FROM fn_nom_datos_contrato";
                                $cadena_sql.=" WHERE cto_num= ".$variable['cod_contrato'];
                                $cadena_sql.=" AND cto_vigencia=".$variable['vigencia'];
                                break;		

                        case "lista_vigencias":
                                $cadena_sql=" SELECT DISTINCT ";
                                $cadena_sql.=" MINUTA.VIGENCIA  AS ID_VIG,";
                                $cadena_sql.=" MINUTA.VIGENCIA  AS NOMBRE";
                                $cadena_sql.=" FROM CO.CO_MINUTA_CONTRATO MINUTA";
                                $cadena_sql.=" ORDER BY ID_VIG DESC";
                                break;		

                        case "codigo_cuenta_banco":
				$cadena_sql=" SELECT ";
                                $cadena_sql.=" cta_id";
                                $cadena_sql.=" FROM fn_nom_cuenta_banco";
                                $cadena_sql.=" WHERE cta_con_num_id = ".$variable['cod_contratista'];
                                $cadena_sql.=" AND cta_con_tipo_id = '".$variable['tipo_id']."'";
                                $cadena_sql.=" AND cta_id_banco=".$variable['id_banco'];
                                $cadena_sql.=" AND cta_num='".$variable['num_cta']."'";
                                $cadena_sql.=" AND cta_tipo='".$variable['tipo']."'";
                                break;		

                       case "insertar_datos_contratista":
                                $cadena_sql=" INSERT INTO  fn_nom_datos_contratista (";
                                $cadena_sql.=" con_tipo_id, ";
                                $cadena_sql.=" con_num_id, ";
                                $cadena_sql.=" con_interno_proveedor)";
                                $cadena_sql.=" VALUES(";
                                $cadena_sql.="'".$variable['tipo_id']."',";
                                $cadena_sql.="'".$variable['cod_contratista']."',";
                                $cadena_sql.="'".$variable['interno_prov']."'";
                                $cadena_sql.=" )";
                                break;

			case "existe_cumplido":
                                $cadena_sql=" SELECT   ";
                                $cadena_sql.=" cum_id ";
                                $cadena_sql.=" FROM fn_nom_cumplido";
                                $cadena_sql.=" WHERE ";
                                $cadena_sql.=" cum_cto_vigencia='".$variable['vigencia']."' ";
                                $cadena_sql.=" AND cum_cto_num='".$variable['cod_contrato']."'";
                                $cadena_sql.=" AND cum_anio='".$variable['anio']."'";
                                $cadena_sql.=" AND cum_mes='".$variable['mes']."'";
                                break;


                        case "codigo_banco":
                                $cadena_sql=" SELECT   ";
                                $cadena_sql.=" ban_id ";
                                $cadena_sql.=" FROM fn_nom_banco";
                                $cadena_sql.=" WHERE ";
                                $cadena_sql.=" ban_codigo_sic='".$variable."' ";
                                $cadena_sql.=" AND ban_estado='A'";
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
                            
                case "contratos":
				$cadena_sql=" SELECT ";
                                $cadena_sql.=" PROV.INTERNO_PROVEEDOR,";
                                $cadena_sql.=" PROV.TIPO_IDENTIFICACION,";
                                $cadena_sql.=" PROV.NUM_IDENTIFICACION,";
                                $cadena_sql.=" MINUTA.INTERNO_MC,";
                                $cadena_sql.=" MINUTA.VIGENCIA,";
                                $cadena_sql.=" TO_CHAR(LEG.FECHA_DE_INICIACION,'YYYY-MM-DD')  AS FECHA_INICIO,";
                                $cadena_sql.=" TO_CHAR(LEG.FECHA_FINAL,'YYYY-MM-DD') AS FECHA_FINAL,";
                                $cadena_sql.=" ORDEN.INTERNO_OC,";
                                $cadena_sql.=" (CASE WHEN MINUTA.CONSECUTIVO_CONTRATO IS NOT NULL THEN MINUTA.CONSECUTIVO_CONTRATO  ELSE ORDEN.NUM_CONTRATO END) AS NUM_CONTRATO";
                                $cadena_sql.=" FROM CO.CO_MINUTA_CONTRATO MINUTA";
                                $cadena_sql.=" INNER JOIN CO.CO_SOLICITUD_ADQ SOL ON SOL.VIGENCIA=MINUTA.VIGENCIA AND SOL.NUM_SOL_ADQ= MINUTA.NUM_SOL_ADQ AND SOL.AUTORIZADA='S'";
                                $cadena_sql.=" INNER JOIN CO.CO_ORDEN_CONTRATO ORDEN ON MINUTA.INTERNO_MC=ORDEN.INTERNO_MC AND MINUTA.VIGENCIA = ORDEN.VIGENCIA AND MINUTA.INTERNO_PROVEEDOR = ORDEN.INTERNO_PROVEEDOR";
                                $cadena_sql.=" LEFT OUTER JOIN CO.CO_PROVEEDOR PROV ON PROV.INTERNO_PROVEEDOR=MINUTA.INTERNO_PROVEEDOR";
                                $cadena_sql.=" LEFT OUTER JOIN CO.CO_MINUTA_LEGALIZACION LEG ON LEG.VIGENCIA= MINUTA.VIGENCIA AND LEG.INTERNO_OC=ORDEN.INTERNO_OC";
                                $cadena_sql.=" WHERE PROV.NUM_IDENTIFICACION= ".$variable;
                                $cadena_sql.=" ORDER BY MINUTA.VIGENCIA DESC, ORDEN.NUM_CONTRATO DESC";
                                break;		
                            
                        case "solicitudes_cumplido":
				$cadena_sql=" SELECT cum_id         AS id,";
                                $cadena_sql.=" cum_cto_vigencia     AS vigencia,";
                                $cadena_sql.=" cum_cto_num          AS num_contrato,";
                                $cadena_sql.=" cum_anio             AS anio,";
                                $cadena_sql.=" cum_mes              AS mes,";
                                $cadena_sql.=" cum_procesado        AS procesado,";
                                $cadena_sql.=" cum_estado           AS estado,";
                                $cadena_sql.=" cum_estado_reg       AS estado_reg,";
                                $cadena_sql.=" cum_fecha            AS fecha,";
                                $cadena_sql.=" cum_num_impresiones  AS num_impresiones,";
                                $cadena_sql.=" cum_valor            AS valor,";
                                $cadena_sql.=" cum_cta_id           AS id_cta,";
                                $cadena_sql.=" cum_finicio_per_pago         AS finicio_per_pago,";
                                $cadena_sql.=" cum_ffinal_per_pago          AS ffinal_per_pago,";
                                $cadena_sql.=" cum_acumulado_valor_pagos    AS acumulado_valor_pagos,";
                                $cadena_sql.=" cum_acumulado_dias_pagos     AS acumulado_dias_pagos,";
                                $cadena_sql.=" cto_interno_co               AS interno_co,";
                                $cadena_sql.=" cto_con_num_id               AS num_id_contratista";
                                $cadena_sql.=" FROM fn_nom_cumplido";
                                $cadena_sql.=" INNER JOIN fn_nom_datos_contrato ON cum_cto_vigencia=cto_vigencia AND cum_cto_num=cto_num ";
                                $cadena_sql.=" WHERE cum_estado_reg='A'";
                                if($variable['id_solicitud']){
                                   $cadena_sql.=" AND cum_id='".$variable['id_solicitud']."' ";
                                     
                                }elseif($variable['vigencia'] && $variable['cod_contrato']){
                                    $cadena_sql.=" AND cum_cto_vigencia='".$variable['vigencia']."' ";
                                    $cadena_sql.=" AND cum_cto_num='".$variable['cod_contrato']."' ";
                                }
                                if($variable['codigos_internos']){
                                    $cadena_sql.=" AND cto_interno_co in (".$variable['codigos_internos'].")";
                                }
                                $cadena_sql.=" ORDER BY vigencia DESC,num_contrato DESC,anio DESC,mes DESC";
                                break;		
                            
			case "todas_solicitudes_cumplido":
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
                                $cadena_sql.=" con_tipo_id              AS tipo_id_contratista,";
                                $cadena_sql.=" con_num_id               AS num_id_contratista,";
                                $cadena_sql.=" con_interno_proveedor    AS interno_proveedor,";  
                                $cadena_sql.=" dtn_salud                AS salud,";  
                                $cadena_sql.=" dtn_pension              AS pension,";  
                                $cadena_sql.=" dtn_arp                  AS arp,";  
                                $cadena_sql.=" dtn_afc                  AS afc,";  
                                $cadena_sql.=" dtn_cooperativas_depositos   AS cooperativas_depositos,";  
                                $cadena_sql.=" dtn_num_dias_pagados     AS dias_cumplido,";  
                                $cadena_sql.=" dtn_saldo_antes_pago     AS saldo_antes_pago";  
                                $cadena_sql.=" FROM fn_nom_cumplido";
                                $cadena_sql.=" INNER JOIN fn_nom_datos_contrato  ON cto_vigencia=cum_cto_vigencia AND cto_num=cum_cto_num";
                                $cadena_sql.=" INNER JOIN fn_nom_datos_contratista  ON cto_con_tipo_id=con_tipo_id AND cto_con_num_id=con_num_id";
                                $cadena_sql.=" INNER JOIN fn_nom_tmp_dtlle_nomina  ON dtn_cum_id=cum_id AND dtn_cum_cto_vigencia=cum_cto_vigencia";
                                $cadena_sql.=" WHERE cum_estado_reg='A'";
                                $cadena_sql.=" AND cum_estado='SOLICITADO'";
                                if($variable){
                                    $cadena_sql.=" AND cto_interno_co in (".$variable.")";
                                }
                                $cadena_sql.=" ORDER BY vigencia DESC,num_contrato DESC,anio DESC,mes DESC";
                                break;		
                            
			case "aprobar_solicitud":
				$cadena_sql=" UPDATE ";
                                $cadena_sql.=" fn_nom_cumplido";
                                $cadena_sql.=" SET";
                                $cadena_sql.=" cum_estado='".$variable['estado']."',";
                                $cadena_sql.=" cum_valor='".$variable['valor']."',";
                                $cadena_sql.=" cum_finicio_per_pago='".$variable['finicial']."',";
                                $cadena_sql.=" cum_ffinal_per_pago='".$variable['ffinal']."',";
                                $cadena_sql.=" cum_num_dias='".$variable['dias']."',";
                                $cadena_sql.=" cum_acumulado_valor_pagos='".$variable['valor_acumulado']."',";
                                $cadena_sql.=" cum_acumulado_dias_pagos='".$variable['dias_acumulados']."',";
                                $cadena_sql.=" cum_num_id_supervisor='".$variable['id_supervisor']."'";
                                $cadena_sql.=" WHERE cum_estado_reg='A'";
                                $cadena_sql.=" AND cum_id='".$variable['id']."'";
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
                        
                        case "contratos_supervisor":
				$cadena_sql=" SELECT ";
                                $cadena_sql.=" MINUTA.INTERNO_MC,";
                                $cadena_sql.=" MINUTA.VIGENCIA,";
                                $cadena_sql.=" MINUTA.CUANTIA,";
                                $cadena_sql.=" MINUTA.CODIGO_UNIDAD_EJECUTORA,";
                                $cadena_sql.=" MINUTA.DEPENDENCIA_DESTINO,";
                                $cadena_sql.=" MINUTA.PLAZO_EJECUCION,";
                                $cadena_sql.=" MINUTA.NUM_SOL_ADQ ,";
                                $cadena_sql.=" ORDEN.INTERNO_OC,";
                                $cadena_sql.=" (CASE WHEN MINUTA.CONSECUTIVO_CONTRATO IS NOT NULL THEN MINUTA.CONSECUTIVO_CONTRATO  ELSE ORDEN.NUM_CONTRATO END) AS NUM_CONTRATO,";
                                $cadena_sql.=" LEG.FECHA_DE_INICIACION  AS FECHA_INICIO,";
                                $cadena_sql.=" LEG.FECHA_FINAL,";
                                $cadena_sql.=" LEG.FECHA_ELABORACION_ACTA_INICIO,";
                                $cadena_sql.=" SOL.FUNCIONARIO";
                                $cadena_sql.=" FROM CO.CO_MINUTA_CONTRATO MINUTA";
                                $cadena_sql.=" INNER JOIN CO.CO_SOLICITUD_ADQ SOL ON SOL.VIGENCIA=MINUTA.VIGENCIA AND SOL.NUM_SOL_ADQ= MINUTA.NUM_SOL_ADQ AND SOL.AUTORIZADA='S'";
                                $cadena_sql.=" INNER JOIN CO.CO_ORDEN_CONTRATO ORDEN ON MINUTA.INTERNO_MC=ORDEN.INTERNO_MC AND MINUTA.VIGENCIA = ORDEN.VIGENCIA AND MINUTA.INTERNO_PROVEEDOR = ORDEN.INTERNO_PROVEEDOR";
                                $cadena_sql.=" LEFT OUTER JOIN CO.CO_PROVEEDOR PROV ON PROV.INTERNO_PROVEEDOR=MINUTA.INTERNO_PROVEEDOR";
                                $cadena_sql.=" LEFT OUTER JOIN CO.CO_MINUTA_LEGALIZACION LEG ON LEG.VIGENCIA= MINUTA.VIGENCIA AND LEG.INTERNO_OC=ORDEN.INTERNO_OC";
                                $cadena_sql.=" WHERE  SOL.FUNCIONARIO=".$variable;
                                break;
                        
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

                            
                        case "insertar_novedad_cumplido":
                                 $cadena_sql=" INSERT INTO fn_nom_cumplido_novedad (";
                                $cadena_sql.=" cnov_cum_id ,";
                                $cadena_sql.=" cnov_cum_cto_vigencia, ";
                                $cadena_sql.=" cnov_nov_id, ";
                                $cadena_sql.=" cnov_estado)";
                                $cadena_sql.=" VALUES(";
                                $cadena_sql.="'".$variable['id_cumplido']."',";
                                $cadena_sql.="'".$variable['vigencia']."',";
                                $cadena_sql.="'".$variable['id_novedad']."',";
                                $cadena_sql.="'".$variable['estado']."'";
                                $cadena_sql.=" )";
                                
                                break;
                            
                        case "id_novedad_afc":
                        
                                $cadena_sql=" select cnov_nov_id";
                                $cadena_sql.=" FROM fn_nom_cumplido_novedad";
                                $cadena_sql.=" INNER JOIN fn_nom_novedad ON cnov_nov_id=nov_id";
                                $cadena_sql.=" WHERE nov_id_tipo=1";
                                $cadena_sql.=" AND cnov_cum_id=".$variable['id_cumplido'];
                                $cadena_sql.=" AND cnov_cum_cto_vigencia=".$variable['vigencia'];
                                break;
                            
			default:
				$cadena_sql="";
				break;
		}//fin switch
		return $cadena_sql;
	}// fin funcion cadena_sql
	
	
}//fin clase sql_adminCumplidoSupervisor
?>

