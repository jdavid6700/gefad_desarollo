<?php

/* --------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
  --------------------------------------------------------------------------------------------------------------------------- */

if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/sql.class.php");

class sql_adminVinculacion extends sql {

    function cadena_sql($opcion, $variable = "") {

        switch ($opcion) {
            case "NO IMPLEMENTADO":
                //En ORACLE: Caso no implementado, se deja para referencia
                $cadena_sql = "SELECT ";
                $cadena_sql.="doc_apellido DOC_APEL, ";
                $cadena_sql.="doc_nombre DOC_NOM, ";
                $cadena_sql.="doc_tip_iden DOC_TIP_IDEN, ";
                $cadena_sql.="doc_nro_iden DOC_NRO_IDEN, ";
                $cadena_sql.="doc_email DOC_EMAIL ";
                $cadena_sql.="FROM ";
                $cadena_sql.="acdocente ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="doc_nro_iden=" . $variable['identificacion'] . " ";
                $cadena_sql.="AND ";
                $cadena_sql.="doc_estado = 'A' ";
                break;

            case "datosUsuario":
                //En ORACLE
                $cadena_sql = "SELECT ";
                $cadena_sql.="emp_nombre1 PLA_NOMBRE1, ";
                $cadena_sql.="emp_nombre2 PLA_NOMBRE2, ";
                $cadena_sql.="emp_apellido1 PLA_APELLIDO1, ";
                $cadena_sql.="emp_apellido2 PLA_APELLIDO2, ";
                $cadena_sql.="emp_nro_iden PLA_NRO_IDEN, ";
                $cadena_sql.="emp_tipo_iden PLA_TIPO_IDEN, ";
                $cadena_sql.="emp_email PLA_EMAIL, ";
                $cadena_sql.="emp_nro_hoja PLA_RES, ";
                $cadena_sql.="car_estado PLA_ESTADO, ";
                $cadena_sql.="emp_direccion PLA_DIRECC, ";
                $cadena_sql.="emp_telefono PLA_TELE, ";
                $cadena_sql.="decode(CAR_TC_COD,'DP','DOCENTE PLANTA','DC','DOCENTE PLANTA','DH','DOCENTE PLANTA','FUNCIONARIO PLANTA') VINCULACION ";
                $cadena_sql.="FROM ";
                $cadena_sql.="peemp, pecargo ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="CAR_TC_COD not in ('PA','PD') ";
                $cadena_sql.="and CAR_COD = EMP_CAR_COD ";
                $cadena_sql.=" and EMP_ESTADO_E <> 'R' ";
                $cadena_sql.="AND emp_nro_iden=" . $variable['identificacion'] . " ";
                $cadena_sql.=" AND emp_estado = 'A' ";
                break;

            case "datosUsuarioSDH":
                //En ORACLE
                $cadena_sql = "SELECT ";
                $cadena_sql.="ib_primer_nombre PLA_NOMBRE1, ";
                $cadena_sql.="ib_segundo_nombre PLA_NOMBRE2, ";
                $cadena_sql.="ib_primer_apellido PLA_APELLIDO1, ";
                $cadena_sql.="ib_segundo_apellido PLA_APELLIDO2, ";
                $cadena_sql.="ib_codigo_identificacion PLA_NRO_IDEN, ";
                $cadena_sql.="ib_tipo_identificacion PLA_TIPO_IDEN, ";
                $cadena_sql.="' ' PLA_EMAIL, ";
                $cadena_sql.="' ' PLA_RES, ";
                $cadena_sql.="' ' PLA_ESTADO ";
                $cadena_sql.="FROM ";
                $cadena_sql.="SHD_INFORMACION_BASICA ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="ib_codigo_identificacion=" . $variable['identificacion'] . " ";

                break;

            case "vinculaciones":
                $cadena_sql = "SELECT DISTINCT ";
                $cadena_sql.="dvin.dtv_ape_ano VIN_ANIO, ";
                //$cadena_sql.="dvin.dtv_ape_per VIN_PER, ";
                $cadena_sql.="dvin.dtv_cra_cod VIN_CRA_COD , ";
                $cadena_sql.="cra.cra_nombre VIN_CRA_NOM , ";
                $cadena_sql.="dvin.dtv_tvi_cod VIN_COD, ";
                $cadena_sql.="tvin.tvi_nombre VIN_NOMBRE, ";
                $cadena_sql.="dvin.dtv_estado VIN_ESTADO, ";
                $cadena_sql.="dvin.dtv_resolucion VIN_RESOLUCION, ";
                $cadena_sql.="dvin.dtv_interno_res VIN_INT_RES ";
                $cadena_sql.=" FROM ";
                $cadena_sql.="acdoctipvin dvin ,actipvin tvin ,accra cra ";
                $cadena_sql.=" WHERE ";
                $cadena_sql.="dvin.dtv_tvi_cod=tvin.tvi_cod";
                $cadena_sql.=" AND ";
                $cadena_sql.="dvin.dtv_cra_cod=cra.cra_cod ";
                if (isset($variable['anio'])) {
                    $cadena_sql.=" AND ";
                    $cadena_sql.="dvin.dtv_ape_ano=" . $variable['anio'] . " ";
                    //  $cadena_sql.=" AND ";
                    // $cadena_sql.="dvin.dtv_ape_per=" . $variable['periodo'] . " ";
                    $cadena_sql.=" AND ";
                    $cadena_sql.="dvin.dtv_estado='A' ";
                }
                $cadena_sql.=" AND ";
                $cadena_sql.=" dvin.dtv_doc_nro_iden=" . $variable['identificacion'] . " ";

                $cadena_sql.=" ORDER BY tvin.tvi_nombre DESC, dvin.dtv_ape_ano DESC,dvin.dtv_cra_cod ";

                break;

            case "vigenciaVinculacion":
                //En ORACLE
                $cadena_sql = " SELECT DISTINCT";
                $cadena_sql.=" dtv_ape_ano COD_ANIO,";
                $cadena_sql.=" dtv_ape_ano ANIO";
                $cadena_sql.=" FROM acdoctipvin";
                $cadena_sql.=" where dtv_doc_nro_iden=" . $variable['identificacion'] . " ";
                $cadena_sql.=" ORDER BY dtv_ape_ano DESC";
                break;

            case "mes":
                //En ORACLE
                $cadena_sql = " SELECT DISTINCT";
                $cadena_sql.=" LPAD(mes_cod,2,'0') COD_MES,";
                $cadena_sql.=" mes_nombre MES ";
                $cadena_sql.=" FROM gemes";
                $cadena_sql.=" WHERE mes_cod<13";
                $cadena_sql.=" ORDER BY COD_MES DESC";
                break;

            case "datos_contrato":

                $cadena_sql = " SELECT DISTINCT";
                $cadena_sql.=" PROV.INTERNO_PROVEEDOR INT_PROV,";
                $cadena_sql.=" PROV.TIPO_IDENTIFICACION as TIPO_ID, ";
                $cadena_sql.=" PROV.NUM_IDENTIFICACION NUM_ID, ";
                $cadena_sql.=" PROV.RAZON_SOCIAL NOMBRE, ";
                $cadena_sql.=" MINUTA.INTERNO_MC, ";
                $cadena_sql.=" MINUTA.VIGENCIA VIGENCIA, ";
                $cadena_sql.=" MINUTA.CUANTIA, ";
                $cadena_sql.=" minuta.tipo_contratacion tipo_contrato, ";
                $cadena_sql.=" MINUTA.CODIGO_UNIDAD_EJECUTORA COD_UNI_EJE, ";
                $cadena_sql.=" MINUTA.DEPENDENCIA_DESTINO DEP_DES, ";
                $cadena_sql.=" MINUTA.PLAZO_EJECUCION, ";
                $cadena_sql.=" MINUTA.NUM_SOL_ADQ, ";
                $cadena_sql.=" (CASE WHEN MINUTA.CONSECUTIVO_CONTRATO IS NOT NULL THEN MINUTA.CONSECUTIVO_CONTRATO  ELSE ORDEN.NUM_CONTRATO END) AS NUM_CONTRATO, ";
                $cadena_sql.=" TO_CHAR(LEG.FECHA_DE_INICIACION,'YYYY-MM-DD')  AS FECHA_INICIO, ";
                $cadena_sql.=" TO_CHAR(LEG.FECHA_FINAL,'YYYY-MM-DD')    AS FECHA_FINAL, ";
                $cadena_sql.=" TO_CHAR(LEG.FECHA_ELABORACION_ACTA_INICIO,'YYYY-MM-DD')  AS FECHA_ELABORACION_ACTA_INICIO, ";
                $cadena_sql.=" SOL.FUNCIONARIO ";
                $cadena_sql.=" FROM CO.CO_MINUTA_CONTRATO MINUTA ";
                $cadena_sql.=" INNER JOIN CO.CO_SOLICITUD_ADQ SOL ON SOL.VIGENCIA=MINUTA.VIGENCIA AND SOL.NUM_SOL_ADQ= MINUTA.NUM_SOL_ADQ AND SOL.AUTORIZADA='S' ";
                $cadena_sql.=" INNER JOIN CO.CO_ORDEN_CONTRATO ORDEN ON MINUTA.INTERNO_MC=ORDEN.INTERNO_MC AND MINUTA.VIGENCIA = ORDEN.VIGENCIA AND MINUTA.INTERNO_PROVEEDOR = ORDEN.INTERNO_PROVEEDOR ";
                $cadena_sql.=" LEFT OUTER JOIN CO.CO_PROVEEDOR PROV ON PROV.INTERNO_PROVEEDOR=MINUTA.INTERNO_PROVEEDOR ";
                $cadena_sql.=" LEFT OUTER JOIN CO.CO_MINUTA_LEGALIZACION LEG ON LEG.VIGENCIA= MINUTA.VIGENCIA AND LEG.INTERNO_OC=ORDEN.INTERNO_OC ";
                $cadena_sql.=" WHERE PROV.NUM_IDENTIFICACION=" . $variable['identificacion'] . " ";
                $cadena_sql.=" order by VIGENCIA DESC ";
                break;

            case "insertar_respuestas":
                $cadena_sql = " INSERT INTO tributario.tributario_respuestas_enc VALUES(";
                $cadena_sql.= "'" . $variable['id_preg'] . "',";
                $cadena_sql.= "'" . $variable['id_enc'] . "',";
                $cadena_sql.= "'" . $variable['id_num'] . "',";
                $cadena_sql.= "'" . $variable['id_tipo'] . "',";
                $cadena_sql.= "'" . $variable['vigencia'] . "',";
                $cadena_sql.= "'" . $variable['contrato'] . "',";
                $cadena_sql.= "'" . $variable['fecha_reg'] . "',";
                $cadena_sql.= "'" . $variable['resp'] . "');";
                break;

            case "consultar_respuestas":
                $cadena_sql = " SELECT distinct resp_enc_id, resp_funcionario_documento, resp_annio, resp_contrato, resp_fec_registro ";
                $cadena_sql.=" FROM ";
                $cadena_sql.=" tributario.tributario_respuestas_enc ";
                $cadena_sql.=" WHERE resp_funcionario_documento=" . $variable['identificacion'] . " ";
                break;   

            case "consultar_direccion_SHD":
                $cadena_sql = " SELECT ";
                $cadena_sql.= " ib_primer_nombre PLA_NOMBRE1, ";
                $cadena_sql.= " ib_segundo_nombre PLA_NOMBRE2, ";
                $cadena_sql.= " ib_primer_apellido PLA_APELLIDO1, ";
                $cadena_sql.= " ib_segundo_apellido PLA_APELLIDO2, ";
                $cadena_sql.= " ib_codigo_identificacion PLA_NRO_IDEN,  ";
                $cadena_sql.= " ib_tipo_identificacion PLA_TIPO_IDEN,  ";
                $cadena_sql.= " ' ' PLA_EMAIL, ";
                $cadena_sql.= " ' ' PLA_RES,  ";
                $cadena_sql.= " ' ' PLA_ESTADO, ";
                $cadena_sql.= " co_tipo_contacto DATO_B_DIR, ";
                $cadena_sql.= " co_VALOR DATO_B_V_DIR ";
                $cadena_sql.= " FROM ";
                $cadena_sql.= " shd_contactos,shd_informacion_basica ";
                $cadena_sql.= " where shd_contactos.id=shd_informacion_basica.id ";
                $cadena_sql.= " and ib_codigo_identificacion=" . $variable['identificacion'] . " ";
                break;

            case "consultar_telefono_SHD":
                $cadena_sql = "SELECT ";
                $cadena_sql.= "ib_primer_nombre PLA_NOMBRE1, ";
                $cadena_sql.= "ib_segundo_nombre PLA_NOMBRE2, ";
                $cadena_sql.= "ib_primer_apellido PLA_APELLIDO1, ";
                $cadena_sql.= "ib_segundo_apellido PLA_APELLIDO2, ";
                $cadena_sql.= "ib_codigo_identificacion PLA_NRO_IDEN, ";
                $cadena_sql.= "ib_tipo_identificacion PLA_TIPO_IDEN, ";
                $cadena_sql.= "' ' PLA_EMAIL, ";
                $cadena_sql.= "' ' PLA_RES, ";
                $cadena_sql.= "' ' PLA_ESTADO, ";
                $cadena_sql.= "co_tipo_contacto DATO_B_DIR, ";
                $cadena_sql.= "co_VALOR DATO_B_V_DIR ";
                $cadena_sql.= "FROM ";
                $cadena_sql.= " shd_contactos,shd_informacion_basica ";
                $cadena_sql.= " where shd_contactos.id=shd_informacion_basica.id ";
                $cadena_sql.= " and co_tipo_contacto='TEL' ";
                $cadena_sql.=" and ib_codigo_identificacion=" . $variable['identificacion'] . " ";
                break;
        }
        //echo $cadena_sql."<br>";
        return $cadena_sql;
    }

}

?>