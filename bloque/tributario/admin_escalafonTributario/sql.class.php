<?php

/* --------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
  --------------------------------------------------------------------------------------------------------------------------- */

if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/sql.class.php");

class sql_adminTributario extends sql {
    
    
    function cadena_sql($configuracion, $conexion, $opcion, $variable) {

        switch ($opcion) {
            
            case "invocar_vigencias":
                $cadena_sql =" SELECT DISTINCT vig.resp_annio vigencia ";
                $cadena_sql.=" FROM ";
                $cadena_sql.=" tributario.tributario_respuestas_enc vig ";
                $cadena_sql.=" ORDER BY vig.resp_annio DESC";
                break; 
            
           case "consultar_respuestas":
               
               $cadena_sql ="SELECT distinct resp.resp_preg_id,";
               $cadena_sql.="resp.resp_enc_id, ";
               $cadena_sql.="resp.resp_funcionario_documento, ";
               $cadena_sql.="resp.resp_annio, resp_contrato, ";
               $cadena_sql.="resp.resp_fec_registro, ";
               $cadena_sql.="resp.resp_respuesta,";
               $cadena_sql.="preg.form_posicion ";
               $cadena_sql.=" FROM ";
               $cadena_sql.="tributario.tributario_respuestas_enc resp ";
               $cadena_sql.="INNER JOIN tributario.tributario_formulario_enc preg ";
               $cadena_sql.="ON resp.resp_preg_id=preg.form_preg_id ";
               $cadena_sql.="AND resp.resp_enc_id=preg.form_enc_id";
               $cadena_sql.=" WHERE resp.resp_funcionario_documento=" . $variable['identificacion'] . " ";
               $cadena_sql.=" AND resp.resp_annio=" . $variable['vigencia'] . " ";
               $cadena_sql.=" ORDER BY resp.resp_preg_id";
                break;

            
            case "lista_funcionarios":
                    $cadena_sql=" SELECT DISTINCT";
                    $cadena_sql.=" resp.resp_annio vigencia, ";
                    $cadena_sql.=" resp.resp_tipo_documento tipo_doc , ";
                    $cadena_sql.=" resp.resp_funcionario_documento identificacion , ";
                    $cadena_sql.=" resp.resp_enc_id encuesta, ";
                    $cadena_sql.=" (CASE WHEN clasifica.nom_escalafon IS NULL ";
                    $cadena_sql.=" THEN 'SIN CLASIFICAR' ";
                    $cadena_sql.=" ELSE clasifica.nom_escalafon ";
                    $cadena_sql.=" END ) escalafon, ";
                    $cadena_sql.=" (CASE WHEN clasifica.id_escalafon IS NULL ";
                    $cadena_sql.=" THEN 0 ";
                    $cadena_sql.=" ELSE clasifica.id_escalafon ";
                    $cadena_sql.=" END ) id_escalafon ";
                    $cadena_sql.=" FROM ";
                    $cadena_sql.=" tributario.tributario_respuestas_enc resp";
                    $cadena_sql.=" LEFT OUTER JOIN ";
                        $cadena_sql.=" (SELECT ";
                        $cadena_sql.=" escal.esc_annio vigencia, ";
                        $cadena_sql.=" escal.esc_tipo_documento tipo_ident, ";
                        $cadena_sql.=" escal.esc_funcionario_documento identificacion, ";
                        $cadena_sql.=" clas.clas_id id_escalafon, ";
                        $cadena_sql.=" clas.clas_nombre nom_escalafon";
                        $cadena_sql.=" FROM tributario.tributario_escalafon_funcionario escal ";
                        $cadena_sql.=" INNER JOIN tributario.tributario_clasificacion clas";
                        $cadena_sql.=" ON escal.esc_clas_id = clas.clas_id AND clas.clas_estado=1) clasifica ";
                    $cadena_sql.=" ON clasifica.vigencia=resp.resp_annio   ";
                    $cadena_sql.=" AND clasifica.tipo_ident=resp.resp_tipo_documento ";
                    $cadena_sql.=" AND clasifica.identificacion=resp.resp_funcionario_documento ";
                    
                    
                    $cadena_sql.=" WHERE resp.resp_annio=".$variable['vigencia']." ";
                    if($variable['identificacion']!='')
                        { $cadena_sql.=" AND trim(to_char(resp.resp_funcionario_documento, '999999999999999'))  LIKE '".$variable['identificacion']."%' ";}
                    if($variable['busqueda']=='clas')
                        { $cadena_sql.=" AND clasifica.nom_escalafon IS NOT NULL ";}    
                    elseif($variable['busqueda']=='noClass')
                        { $cadena_sql.=" AND clasifica.nom_escalafon IS NULL ";}        
                    $cadena_sql.=" ORDER BY resp.resp_funcionario_documento ";    
                        
                break;
                
            case "datosUsuario":
                //En ORACLE SUDD
                $cadena_sql = "SELECT ";
                $cadena_sql.="emp_nombre1 FUN_NOMBRE1, ";
                $cadena_sql.="emp_nombre2 FUN_NOMBRE2, ";
                $cadena_sql.="emp_apellido1 FUN_APELLIDO1, ";
                $cadena_sql.="emp_apellido2 FUN_APELLIDO2, ";
                $cadena_sql.="emp_nro_iden FUN_NRO_IDEN, ";
                $cadena_sql.="emp_tipo_iden FUN_TIPO_IDEN, ";
                $cadena_sql.="emp_email FUN_EMAIL, ";
                $cadena_sql.="emp_nro_hoja FUN_RES, ";
                $cadena_sql.="car_estado FUN_ESTADO, ";
                $cadena_sql.="emp_direccion FUN_DIRECC, ";
                $cadena_sql.="emp_telefono FUN_TELE, ";
                $cadena_sql.="emp_nro_iden FUN_ID, ";
                $cadena_sql.="emp_desde FUN_FECHA_IN, ";
                $cadena_sql.="decode(CAR_TC_COD,'DP','DOCENTE PLANTA','DC','DOCENTE PLANTA','DH','DOCENTE PLANTA','FUNCIONARIO PLANTA') FUN_VINCULACION ";
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
                //En ORACLE UD
                $cadena_sql = "SELECT ";
                $cadena_sql.="ib_primer_nombre FUN_NOMBRE1, ";
                $cadena_sql.="ib_segundo_nombre FUN_NOMBRE2, ";
                $cadena_sql.="ib_primer_apellido FUN_APELLIDO1, ";
                $cadena_sql.="ib_segundo_apellido FUN_APELLIDO2, ";
                $cadena_sql.="ib_codigo_identificacion FUN_NRO_IDEN, ";
                $cadena_sql.="ib_tipo_identificacion FUN_TIPO_IDEN, ";
                $cadena_sql.="' ' FUN_EMAIL, ";
                $cadena_sql.="' ' FUN_RES, ";
                $cadena_sql.="' ' FUN_ESTADO, ";
                $cadena_sql.="id FUN_ID, ";
                $cadena_sql.="ib_fecha_inicial FUN_FECHA_IN, ";
                $cadena_sql.="'CONTRATISTA' FUN_VINCULACION ";
                $cadena_sql.="FROM ";
                $cadena_sql.="SHD_INFORMACION_BASICA ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="ib_codigo_identificacion='" . $variable['identificacion'] . "' ";
                break;    
                
            
            case "insertar_escalafon":
                $cadena_sql = " INSERT INTO tributario.tributario_escalafon_funcionario (   ";
                $cadena_sql.=" esc_funcionario_documento, ";
                $cadena_sql.=" esc_clas_id, ";
                $cadena_sql.=" esc_annio, ";
                $cadena_sql.=" esc_tipo_documento, ";
                $cadena_sql.=" esc_fecha_reg, ";
                $cadena_sql.=" esc_estado) ";
                $cadena_sql.= "  VALUES (   ";
                $cadena_sql.= " '" . $variable['identificacion'] . "', ";
                $cadena_sql.= " '" . $variable['clasificacion'] . "',   ";
                $cadena_sql.= " '" . $variable['vigencia'] . "',   ";
                $cadena_sql.= " '" . $variable['tipo_ident'] . "',   ";
                $cadena_sql.= " '" . $variable['fecha'] . "',   ";
                $cadena_sql.= " '" . $variable['estado'] . "');  ";
                break;
            
            
           case "actualizar_escalafon":
                $cadena_sql = " UPDATE tributario.tributario_escalafon_funcionario ";
                $cadena_sql.= " SET    ";
                $cadena_sql.=" esc_clas_id= '" . $variable['clasificacion'] . "',   ";
                $cadena_sql.=" esc_fecha_reg= '" . $variable['fecha'] . "',   ";
                $cadena_sql.=" esc_estado='" . $variable['estado'] . "'  ";
                $cadena_sql.=" WHERE ";
                $cadena_sql.=" esc_funcionario_documento='" . $variable['identificacion'] . "' ";
                $cadena_sql.=" AND esc_annio= '" . $variable['vigencia'] . "' ";
                break;
            
            case "invocar_respuestas":
                $cadena_sql=" SELECT DISTINCT clas.clas_id id_clasifica, ";
                $cadena_sql.=" clas.clas_descripcion desc_clasifica, ";
                $cadena_sql.=" clas.clas_nombre nom_clasifica, ";
                $cadena_sql.=" var_clas.vclas_form_preg_id id_pregunta, ";
                $cadena_sql.=" var_clas.vclas_form_enc_id id_encuesta, ";
                $cadena_sql.=" var_clas.vclas_respuesta respuesta_clas, ";
                $cadena_sql.=" resp.resp_funcionario_documento doc_us,";
                $cadena_sql.=" resp.resp_preg_id preg_us,";
                $cadena_sql.=" resp.resp_respuesta respuesta_us";
                $cadena_sql.=" FROM tributario.tributario_clasificacion clas ";
                $cadena_sql.="  LEFT OUTER JOIN tributario.tributario_variables_clasificacion var_clas ";
                $cadena_sql.="      ON clas.clas_id = var_clas.vclas_clas_id ";
                $cadena_sql.="      AND clas.clas_enc_id=var_clas.vclas_form_enc_id AND var_clas.vclas_estado='1'";
                $cadena_sql.="  LEFT OUTER JOIN tributario.tributario_respuestas_enc resp";
                $cadena_sql.="      ON var_clas.vclas_form_preg_id=resp.resp_preg_id ";
                $cadena_sql.="      AND var_clas.vclas_form_enc_id=resp.resp_enc_id";
                $cadena_sql.="      AND var_clas.vclas_respuesta=resp.resp_respuesta";
                $cadena_sql.="      AND resp.resp_funcionario_documento=" . $variable['identificacion'] . " ";
                $cadena_sql.="      AND resp.resp_annio=" . $variable['vigencia'] . "";
                /*
                $cadena_sql.=" WHERE clas.clas_enc_id='1' ";
                $cadena_sql.=" AND clas.clas_estado='1' ";
                 */
                $cadena_sql.=" WHERE ";
                $cadena_sql.=" clas.clas_estado='1' ";
                $cadena_sql.=" ORDER BY clas.clas_id DESC, var_clas.vclas_form_preg_id ASC ";
                
                break;            
            
     
            case "invocar_preguntas":
                $cadena_sql = " select ";
                $cadena_sql.=" tributario.tributario_pregunta.preg_id, ";
                $cadena_sql.=" tributario.tributario_pregunta.preg_nombre, ";
                $cadena_sql.=" tributario.tributario_formulario_enc.form_preg_id, ";
                $cadena_sql.=" tributario.tributario_formulario_enc.form_enc_id, ";
                $cadena_sql.=" tributario.tributario_formulario_enc.form_posicion ";
                $cadena_sql.=" from tributario.tributario_pregunta,tributario.tributario_formulario_enc ";
                $cadena_sql.=" where ";
                $cadena_sql.=" tributario.tributario_formulario_enc.form_preg_id=tributario.tributario_pregunta.preg_id ";
                $cadena_sql.=" and ";
                $cadena_sql.=" tributario.tributario_pregunta.preg_estado=1 ";
                $cadena_sql.=" and ";
                $cadena_sql.=" tributario.tributario_formulario_enc.form_estado=1 and ";
                $cadena_sql.=" tributario.tributario_formulario_enc.form_enc_id=1 ";
                break;

            default:
                $cadena_sql = "";
                break;
        }//fin switch
        return $cadena_sql;
    }

// fin funcion cadena_sql
}

//fin clase sql_infoTributario
?>

