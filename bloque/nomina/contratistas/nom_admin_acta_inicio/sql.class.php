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

class sql_adminActaInicio extends sql
{
	function cadena_sql($configuracion,$conexion, $opcion,$variable="")
	{
		
		switch($opcion)
		{	
                    		
			 case "conceptos_nomina":
                                $cadena_sql=" SELECT cno_codigo,";
                                $cadena_sql.=" cno_nombre ";
                                $cadena_sql.=" FROM fn_nom_conceptos_nomina";
                                $cadena_sql.=" WHERE cno_codigo !='0'";
                                $cadena_sql.=" ORDER BY cno_tipo_nomina";
                                break;		

                         case "insertar_acta_inicio":
                                $cadena_sql=" INSERT INTO fn_nom_acta_inicio (";
                                $cadena_sql.=" aci_id,";
                                $cadena_sql.=" aci_cto_num,";
                                $cadena_sql.=" aci_cto_vigencia ,";
                                $cadena_sql.=" aci_fecha_inicio,";
                                $cadena_sql.=" aci_fecha_finalizacion,";
                                $cadena_sql.=" aci_cno_codigo ,";
                                $cadena_sql.=" aci_fecha_reg ,";
                                $cadena_sql.=" aci_fecha_firma ,";
                                $cadena_sql.=" aci_estado_reg )"; 
                                $cadena_sql.=" VALUES(";
                                $cadena_sql.="'".$variable['id']."',";
                                $cadena_sql.="'".$variable['cod_contrato']."',";
                                $cadena_sql.="'".$variable['vigencia_contrato']."',";
                                $cadena_sql.="'".$variable['fecha_ini']."',";
                                $cadena_sql.="'".$variable['fecha_fin']."',";
                                $cadena_sql.="'".$variable['tipo_nomina']."',";
                                $cadena_sql.="'".$variable['fecha']."',";
                                $cadena_sql.="'".$variable['fecha_firma']."',";
                                $cadena_sql.="'".$variable['estado']."'";
                                $cadena_sql.=" )";
                                break;
                        
                        case "ultimo_numero_acta_inicio":
                                $cadena_sql=" SELECT MAX(aci_id) AS NUM ";
                                $cadena_sql.=" FROM fn_nom_acta_inicio";
                                break;

                        case "acta_inicio":
				$cadena_sql=" SELECT ";
                                $cadena_sql.=" aci_id,";
                                $cadena_sql.=" aci_cto_num,";
                                $cadena_sql.=" aci_cto_vigencia ,";
                                $cadena_sql.=" aci_fecha_inicio,";
                                $cadena_sql.=" aci_fecha_finalizacion,";
                                $cadena_sql.=" aci_cno_codigo ,";
                                $cadena_sql.=" aci_fecha_reg, ";
                                $cadena_sql.=" aci_fecha_firma ,";
                                $cadena_sql.=" cno_nombre ";
                                
                                $cadena_sql.=" FROM  fn_nom_acta_inicio ";
                                $cadena_sql.=" INNER JOIN fn_nom_conceptos_nomina ON aci_cno_codigo=cno_codigo ";
                                $cadena_sql.=" WHERE aci_cto_num= ".$variable['cod_contrato'];
                                $cadena_sql.=" AND aci_cto_vigencia=".$variable['vigencia_contrato'];
                                $cadena_sql.=" AND aci_estado_reg='A'";
                                break;		

                        case "inactivar_acta_inicio":
                                $cadena_sql=" UPDATE  fn_nom_acta_inicio ";
                                $cadena_sql.=" SET aci_estado_reg ='I'";
                                $cadena_sql.=" WHERE";
                                $cadena_sql.=" aci_id='".$variable."'";
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

                        case "existe_datos_acta":
                                $cadena_sql=" SELECT   ";
                                $cadena_sql.=" aci_id,";
                                $cadena_sql.=" aci_cto_num,";
                                $cadena_sql.=" aci_cto_vigencia ,";
                                $cadena_sql.=" aci_fecha_inicio,";
                                $cadena_sql.=" aci_fecha_finalizacion,";
                                $cadena_sql.=" aci_cno_codigo ,";
                                $cadena_sql.=" aci_fecha_reg ";
                                $cadena_sql.=" FROM fn_nom_acta_inicio";
                                $cadena_sql.=" WHERE ";
                                $cadena_sql.=" aci_estado_reg ='A' ";
                                $cadena_sql.=" AND aci_cto_num='".$variable['num_contrato']."' ";
                                $cadena_sql.=" AND aci_cto_vigencia='".$variable['vigencia']."'";
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
                      
                        case "actualizar_fechas_minuta_legalizacion":
                                $cadena_sql=" UPDATE  co.co_minuta_legalizacion ";
                                $cadena_sql.=" SET fecha_de_iniciacion ='".$variable['fecha_ini']."'";
                                $cadena_sql.=" fecha_final ='".$variable['fecha_fin']."'";
                                $cadena_sql.=" WHERE";
                                $cadena_sql.=" vigencia='".$variable['vigencia']."'";
                                $cadena_sql.=" AND interno_oc='".$variable['interno_oc']."'";
                                break;

                        case "actualizar_aspectos_contratista":
                                $cadena_sql=" UPDATE fn_nom_datos_contratista ";
                                $cadena_sql.=" SET con_regimen_comun ='".$variable['regimen']."',";
                                $cadena_sql.=" con_declarante ='".$variable['declarante']."',";
                                $cadena_sql.=" con_pensionado ='".$variable['pensionado']."',";
                                $cadena_sql.=" con_pasante ='".$variable['pasante']."'";
                                $cadena_sql.=" WHERE";
                                $cadena_sql.=" con_num_id='".$variable['identificacion']."'";
                                $cadena_sql.=" AND con_tipo_id='".$variable['tipo_id']."'";
                                break;
 
                        case "aspectos_contratista":
				$cadena_sql=" SELECT ";
                                $cadena_sql.=" con_tipo_id,";
                                $cadena_sql.=" con_num_id,";
                                $cadena_sql.=" con_interno_proveedor ,";
                                $cadena_sql.=" con_regimen_comun,";
                                $cadena_sql.=" con_declarante,";
                                $cadena_sql.=" con_pensionado ,";
                                $cadena_sql.=" con_pasante ";
                                $cadena_sql.=" FROM  fn_nom_datos_contratista ";
                                $cadena_sql.=" WHERE con_tipo_id= '".$variable['tipo_id']."'";
                                $cadena_sql.=" AND con_num_id='".$variable['identificacion']."'";
                                break;		

                        case "existe_datos_contratista":
                                $cadena_sql=" SELECT   ";
                                $cadena_sql.=" con_tipo_id, ";
                                $cadena_sql.=" con_num_id ";
                                $cadena_sql.=" FROM fn_nom_datos_contratista";
                                $cadena_sql.=" WHERE ";
                                $cadena_sql.=" con_tipo_id='".$variable['tipo_id']."' ";
                                $cadena_sql.=" AND con_num_id='".$variable['cod_contratista']."'";
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

                        case "insertar_datos_contrato":
                                $cadena_sql=" INSERT INTO  fn_nom_datos_contrato (";
                                $cadena_sql.=" cto_vigencia, ";
                                $cadena_sql.=" cto_num, ";
                                $cadena_sql.=" cto_con_tipo_id, ";
                                $cadena_sql.=" cto_con_num_id, ";
                                $cadena_sql.=" cto_interno_co, ";
                                $cadena_sql.=" cto_uni_ejecutora)";
                                $cadena_sql.=" VALUES(";
                                $cadena_sql.="'".$variable['vigencia']."',";
                                $cadena_sql.="'".$variable['cod_contrato']."',";
                                $cadena_sql.="'".$variable['tipo_id']."',";
                                $cadena_sql.="'".$variable['cod_contratista']."',";
                                $cadena_sql.="'".$variable['interno_oc']."',";
                                $cadena_sql.="'".$variable['unidad_ejec']."'";
                                $cadena_sql.=" )";
                                break;

			default:
				$cadena_sql="";
				break;
		}//fin switch
		return $cadena_sql;
	}// fin funcion cadena_sql
	
	
}//fin clase sql_adminActaInicio
?>

