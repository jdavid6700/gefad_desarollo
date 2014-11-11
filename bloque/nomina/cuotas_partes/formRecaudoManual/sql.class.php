<?php

/* --------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
  --------------------------------------------------------------------------------------------------------------------------- */

if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/sql.class.php");

class sql_formRecaudoManual extends sql {

    function cadena_sql($configuracion, $conexion, $opcion, $variable) {

        switch ($opcion) {


            case "consultarPrevisora":
                $cadena_sql = " SELECT ";
                $cadena_sql.=" prev_nit, ";
                $cadena_sql.=" prev_nombre ";
                $cadena_sql.=" FROM cuotas_partes.cuotas_previsora ";
                $cadena_sql.=" WHERE prev_habilitado_pago = 'ACTIVA' ";
                $cadena_sql.=" ORDER BY prev_nombre ASC ";
                break;

            case "consultarEntidadesRecaudo":
                $cadena_sql = " SELECT prev_nombre, hlab_nitprev, prev_nit  ";
                $cadena_sql.=" FROM cuotas_partes.cuotas_previsora, cuotas_partes.cuotas_hlaboral  ";
                $cadena_sql.=" WHERE prev_nit= hlab_nitprev and hlab_nro_identificacion = '" . $variable['cedula'] . "' ";
                $cadena_sql.=" GROUP BY prev_nombre, hlab_nitprev, prev_nit ";
                break;

            case "consultaPagoConsecutivo":
                $cadena_sql = " SELECT rec_id ";
                $cadena_sql.= " FROM cuotas_partes.cuotas_recaudos ";
                $cadena_sql.= " ORDER BY rec_id DESC ";
                $cadena_sql.= " LIMIT 1 ";
                break;

            case "registrarPago":
                $cadena_sql = " INSERT INTO cuotas_partes.cuotas_recaudos ";
                $cadena_sql.=" (rec_id,  ";
                $cadena_sql.=" rec_consecu_rec,  ";
                $cadena_sql.=" rec_nitprev,  ";
                $cadena_sql.=" rec_identificacion,  ";
                $cadena_sql.=" rec_resolucionop,  ";
                $cadena_sql.=" rec_fecha_resolucion,  ";
                $cadena_sql.=" rec_fecha_pago,  ";
                $cadena_sql.=" rec_medio_pago,  ";
                $cadena_sql.=" rec_pago_capital,  ";
                $cadena_sql.=" rec_pago_interes,  ";
                $cadena_sql.=" rec_total_recaudo,  ";
                $cadena_sql.=" rec_estado,  ";
                $cadena_sql.=" rec_fecha_registro) VALUES ( ";
                $cadena_sql.=" '" . $variable['rec_id'] . "', ";
                $cadena_sql.=" '" . $variable['consecutivo_rec'] . "', ";
                $cadena_sql.=" '" . $variable['nit_previsional'] . "', ";
                $cadena_sql.=" '" . $variable['cedula_emp'] . "', ";
                $cadena_sql.=" '" . $variable['resolucion_OP'] . "', ";
                $cadena_sql.=" '" . $variable['fecha_resolucion'] . "', ";
                $cadena_sql.=" '" . $variable['fecha_pago_cuenta'] . "', ";
                $cadena_sql.=" '" . $variable['medio_pago'] . "', ";
                $cadena_sql.=" '" . $variable['valor_pagado_capital'] . "', ";
                $cadena_sql.=" '" . $variable['valor_pagado_interes'] . "', ";
                $cadena_sql.=" '" . $variable['total_recaudo'] . "', ";
                $cadena_sql.=" 'ACTIVO', ";
                $cadena_sql.=" '" . $variable['fecha_registro'] . "'); ";
                break;


            default:
                $cadena_sql = "";
                break;
        }//fin switch

        return $cadena_sql;
    }

}

?>
