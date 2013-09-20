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
                $cadena_sql.= " " . $variable['id_preg'] . ",";
                $cadena_sql.= " " . $variable['id_enc'] . ",";
                $cadena_sql.= " " . $variable['id_num'] . ",";
                $cadena_sql.= " " . $variable['id_tipo'] . ",";
                $cadena_sql.= " " . $variable['vigencia'] . ",";
                $cadena_sql.= " " . $variable['contrato'] . ",";
                $cadena_sql.= " " . $variable['fecha_reg'] . ",";
                $cadena_sql.= " " . $variable['resp'] . ");  ";
                break;

            case "invocar_respuestas":
                $cadena_sql =" SELECT resp_preg_id, resp_enc_id, resp_funcionario_documento, resp_annio, resp_fec_registro, resp_respuesta  ";
                $cadena_sql.=" FROM ";
                $cadena_sql.=" tributario.tributario_respuestas_enc ";
                $cadena_sql.=" WHERE resp_funcionario_documento=" . $variable['identificacion'] . " ";
                $cadena_sql.=" ORDER BY resp_preg_id ASC";
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

