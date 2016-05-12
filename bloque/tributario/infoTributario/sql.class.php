<?php

/* --------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
  --------------------------------------------------------------------------------------------------------------------------- */

if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/sql.class.php");

class sql_infoTributario extends sql {

    function cadena_sql($configuracion, $conexion, $opcion, $variable) {

        switch ($opcion) {
            case "insertar_respuestas":
                $cadena_sql = " INSERT INTO tributario.tributario_respuestas_enc VALUES (   ";
                $cadena_sql.= " " . $variable['id_preg'] . ", ";
                $cadena_sql.= " " . $variable['id_enc'] . ",   ";
                $cadena_sql.= " " . $variable['id_num'] . ",   ";
                $cadena_sql.= " " . $variable['id_tipo'] . ",   ";
                $cadena_sql.= " " . $variable['vigencia'] . ",   ";
                $cadena_sql.= " " . $variable['contrato'] . ",   ";
                $cadena_sql.= " " . $variable['fecha_reg'] . ",   ";
                $cadena_sql.= " " . $variable['resp'] . ");  ";
                break;

            case "invocar_respuestas":
                $cadena_sql =" SELECT   ";
                $cadena_sql.=" resp.resp_preg_id, ";
                $cadena_sql.=" resp.resp_enc_id, ";
                $cadena_sql.=" resp.resp_funcionario_documento, ";
                $cadena_sql.=" resp.resp_annio, ";
                $cadena_sql.=" resp.resp_fec_registro, ";
                $cadena_sql.=" resp.resp_respuesta  ";
                $cadena_sql.=" FROM ";
                $cadena_sql.=" tributario.tributario_respuestas_enc resp  ";
                $cadena_sql.=" WHERE resp.resp_funcionario_documento=" . $variable['identificacion'] . " ";
                $cadena_sql.=" AND resp.resp_annio=" . $variable['vigencia'] . " ";
                $cadena_sql.=" ORDER BY resp_preg_id ASC";
                break;

            case "invocar_preguntas":
                $cadena_sql = " select distinct ";
                $cadena_sql.=" preg.preg_id, ";
                $cadena_sql.=" preg.preg_nombre, ";
                $cadena_sql.=" form.form_preg_id, ";
                $cadena_sql.=" form.form_enc_id, ";
                $cadena_sql.=" form.form_posicion, ";
                $cadena_sql.=" form.form_soportar, ";
                $cadena_sql.=" enc.enc_nombre, ";
                $cadena_sql.=" enc.enc_descripcion, ";
                $cadena_sql.=" enc.enc_fec_soportes ";
                $cadena_sql.=" from tributario.tributario_pregunta preg,tributario.tributario_formulario_enc form,tributario.tributario_encuesta enc";
                $cadena_sql.=" where ";
                $cadena_sql.=" form.form_preg_id=preg.preg_id ";
                $cadena_sql.=" and ";
                $cadena_sql.=" enc.enc_id=form.form_enc_id ";
                $cadena_sql.=" and ";
                $cadena_sql.=" preg.preg_estado=1 ";
                $cadena_sql.=" and ";
                $cadena_sql.=" form.form_estado=1 ";
                // $cadena_sql.=" and ";
                //$cadena_sql.=" tributario.tributario_formulario_enc.form_enc_id=1 ";
                $cadena_sql.=" order by form.form_posicion ";
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

