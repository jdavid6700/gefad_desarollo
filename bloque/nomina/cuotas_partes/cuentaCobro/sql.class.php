<?php

/* --------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
  --------------------------------------------------------------------------------------------------------------------------- */

if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/sql.class.php");

class sql_adminCuentaCobro extends sql {

    function cadena_sql($configuracion, $conexion, $opcion, $variable) {

        switch ($opcion) {

            /*             * * Consultas SQL para el registro de cuentas de cobro manuales */
            case "consecutivoCC":
                $cadena_sql = " SELECT cob_idcob ";
                $cadena_sql.= " FROM cuotas_partes.cuotas_cobros ";
                $cadena_sql.= " ORDER BY cob_idcob DESC ";
                $cadena_sql.= " LIMIT 1 ";
                break;

            case "consultarPrevisora":
                $cadena_sql = " SELECT prev_nombre, hlab_nitprev, prev_nit ";
                $cadena_sql.=" from cuotas_partes.cuotas_previsora, cuotas_partes.cuotas_hlaboral ";
                $cadena_sql.=" where prev_nit= hlab_nitprev and hlab_nro_identificacion = '" . $variable . "' ";
                $cadena_sql.=" and prev_habilitado_pago = 'ACTIVA' ";
                break;

            case "consultarPrevFormulario":
                $cadena_sql = " SELECT prev_nombre, hlab_nitprev, prev_nit ";
                $cadena_sql.=" from cuotas_partes.cuotas_previsora, cuotas_partes.cuotas_hlaboral ";
                $cadena_sql.=" where prev_nit= hlab_nitprev and hlab_nro_identificacion = '" . $variable['cedula'] . "' ";
                $cadena_sql.=" AND hlab_nitprev='" . $variable['previsor'] . "' ";
                $cadena_sql.=" and prev_habilitado_pago = 'ACTIVA' ";
                $cadena_sql.=" GROUP BY prev_nombre, hlab_nitprev, prev_nit ";
                break;

            case "consultarEmpleador":
                $cadena_sql = " SELECT prev_nombre, hlab_nro_identificacion, hlab_nitprev,hlab_nitenti, hlab_fingreso, hlab_fretiro ";
                $cadena_sql.=" FROM cuotas_partes.cuotas_hlaboral, cuotas_partes.cuotas_previsora ";
                $cadena_sql.=" WHERE hlab_nro_identificacion='" . $variable['cedula'] . "' ";
                $cadena_sql.=" AND prev_nit=hlab_nitenti ";
                $cadena_sql.=" AND hlab_nitprev='" . $variable['previsor'] . "' ";
                break;

            case "consultarHistoria2":
                $cadena_sql = " SELECT DISTINCT hlab_nro_identificacion, hlab_nitenti, hlab_nitprev, hlab_fingreso, hlab_fretiro  ";
                $cadena_sql.=" FROM cuotas_partes.cuotas_hlaboral ";
                $cadena_sql.=" WHERE hlab_nro_identificacion='" . $variable['cedula'] . "' ";
                $cadena_sql.=" and hlab_nitprev='" . $variable['previsor'] . "' ";
                $cadena_sql.= "ORDER BY hlab_fretiro DESC ";
                break;

            case "consecutivoRecta":
                $cadena_sql = " SELECT recta_id ";
                $cadena_sql.=" FROM cuotas_partes.cuotas_recaudo_cuenta ";
                $cadena_sql.=" ORDER BY recta_id DESC ";
                $cadena_sql.=" LIMIT 1 ";
                break;

            case "consultarHistoria":
                $cadena_sql = " SELECT ";
                $cadena_sql.=" hlab_nro_ingreso, ";
                $cadena_sql.=" hlab_nro_identificacion, ";
                $cadena_sql.=" hlab_nitenti, ";
                $cadena_sql.=" hlab_nitprev, ";
                $cadena_sql.=" hlab_fingreso, ";
                $cadena_sql.=" hlab_fretiro, ";
                $cadena_sql.=" hlab_horas, ";
                $cadena_sql.=" hlab_periodicidad, ";
                $cadena_sql.=" hlab_estado, ";
                $cadena_sql.=" hlab_registro ";
                $cadena_sql.=" FROM cuotas_partes.cuotas_hlaboral ";
                $cadena_sql.=" WHERE hlab_nro_identificacion = '" . $variable['cedula'] . "' ";
                $cadena_sql.=" ORDER BY hlab_fingreso DESC ";
                break;

            case "insertarCManual":
                $cadena_sql = " INSERT INTO cuotas_partes.cuotas_cobros (";
                $cadena_sql.= " cob_fgenerado, ";
                $cadena_sql.= " cob_cedula, ";
                $cadena_sql.= " cob_nitprev, ";
                $cadena_sql.= " cob_consecu_cta, ";
                $cadena_sql.= " cob_saldo, ";
                $cadena_sql.= " cob_finicial, ";
                $cadena_sql.= " cob_ffinal, ";
                $cadena_sql.= " cob_mesada_ordinaria, ";
                $cadena_sql.= " cob_mesada_ad, ";
                $cadena_sql.= " cob_subtotal, ";
                $cadena_sql.= " cob_incremento, ";
                $cadena_sql.= " cob_ts_interes, ";
                $cadena_sql.= " cob_interes, ";
                $cadena_sql.= " cob_tc_interes, ";
                $cadena_sql.= " cob_total, ";
                $cadena_sql.= " cob_ie_correspondencia, ";
                $cadena_sql.= " cob_estado_cuenta, ";
                $cadena_sql.= " cob_estado, ";
                $cadena_sql.= " cob_fecha_registro ) VALUES ( ";
                $cadena_sql.= " '" . $variable['fecha_generacion'] . "', ";
                $cadena_sql.= " '" . $variable['cedula'] . "', ";
                $cadena_sql.= " '" . $variable['previsor'] . "', ";
                $cadena_sql.= " '" . $variable['consecutivo_cc'] . "', ";
                $cadena_sql.= " '" . $variable['saldo_fecha'] . "', ";
                $cadena_sql.= " '" . $variable['fecha_inicial'] . "', ";
                $cadena_sql.= " '" . $variable['fecha_final'] . "', ";
                $cadena_sql.= " '" . $variable['mesada'] . "', ";
                $cadena_sql.= " '" . $variable['mesada_adc'] . "', ";
                $cadena_sql.= " '" . $variable['subtotal'] . "', ";
                $cadena_sql.= " '" . $variable['incremento'] . "', ";
                $cadena_sql.= " '" . $variable['t_sin_interes'] . "', ";
                $cadena_sql.= " '" . $variable['interes'] . "', ";
                $cadena_sql.= " '" . $variable['t_con_interes'] . "', ";
                $cadena_sql.= " '" . $variable['t_con_interes'] . "', ";
                $cadena_sql.= " '" . $variable['fecha_recibido'] . "', ";
                $cadena_sql.= " '" . $variable['estado_cuenta'] . "', ";
                $cadena_sql.= " '" . $variable['estado'] . "', ";
                $cadena_sql.= " '" . $variable['fecha_registro'] . "'); ";
                break;

            case "insertarCManualSaldo":
                $cadena_sql = " INSERT INTO cuotas_partes.cuotas_recaudo_cuenta ";
                $cadena_sql.=" (recta_consecu_cta, ";
                $cadena_sql.=" recta_consecu_rec, ";
                $cadena_sql.=" recta_id, ";
                $cadena_sql.=" recta_cedula, ";
                $cadena_sql.=" recta_nitprev, ";
                $cadena_sql.=" recta_valor_recaudo, ";
                $cadena_sql.=" recta_valor_cobro, ";
                $cadena_sql.=" recta_saldocapital, ";
                $cadena_sql.=" recta_saldointeres, ";
                $cadena_sql.=" recta_saldototal, ";
                $cadena_sql.=" recta_estado, ";
                $cadena_sql.=" recta_fecha_registro) VALUES (";
                $cadena_sql.=" '" . $variable['consecutivo_cc'] . "', ";
                $cadena_sql.=" '" . $variable['consecu_rec'] . "', ";
                $cadena_sql.=" '" . $variable['id_registro'] . "', ";
                $cadena_sql.=" '" . $variable['cedula'] . "', ";
                $cadena_sql.=" '" . $variable['previsor'] . "', ";
                $cadena_sql.=" '" . $variable['recaudo'] . "', ";
                $cadena_sql.=" '" . $variable['t_con_interes'] . "', ";
                $cadena_sql.=" '" . $variable['capital'] . "', ";
                $cadena_sql.=" '" . $variable['interes'] . "', ";
                $cadena_sql.=" '" . $variable['saldo_fecha'] . "', ";
                $cadena_sql.=" 'ACTIVO', ";
                $cadena_sql.=" '" . date("d/m/Y") . "' ) ; ";
                break;

            case "consultarPrevisoraUnica":
                $cadena_sql = " SELECT prev_nombre, hlab_nitprev, prev_nit  ";
                $cadena_sql.=" FROM cuotas_partes.cuotas_previsora, cuotas_partes.cuotas_hlaboral  ";
                $cadena_sql.=" WHERE prev_nit= hlab_nitprev and hlab_nro_identificacion = '" . $variable . "' ";
                $cadena_sql.=" and prev_habilitado_pago = 'ACTIVA' ";
                $cadena_sql.=" GROUP BY prev_nombre, hlab_nitprev, prev_nit ";
                break;

            case "consultarEmpleadorUnico":
                $cadena_sql = " SELECT prev_nombre,hlab_nitenti";
                $cadena_sql.=" FROM cuotas_partes.cuotas_hlaboral, cuotas_partes.cuotas_previsora ";
                $cadena_sql.=" WHERE hlab_nro_identificacion='" . $variable['cedula'] . "' ";
                $cadena_sql.=" AND prev_nit=hlab_nitenti ";
                $cadena_sql.=" AND hlab_nitprev='" . $variable['previsor'] . "' ";
                $cadena_sql.=" GROUP BY prev_nombre, hlab_nitenti";
                break;

            case "consultarCobros":
                $cadena_sql = "  SELECT cob_fgenerado, cob_nitprev, cob_consecu_cta, cob_finicial, cob_ffinal, ";
                $cadena_sql.=" cob_ts_interes, cob_interes, cob_tc_interes, cob_ie_correspondencia, cob_cedula, cob_saldo";
                $cadena_sql.=" from cuotas_partes.cuotas_cobros ";
                $cadena_sql.=" where cob_cedula = '" . $variable['cedula'] . "' ";
                // $cadena_sql.=" and cob_estado_cuenta = 'ACTIVA' ";
                $cadena_sql.=" and cob_nitprev='" . $variable['previsor'] . "' ";
                $cadena_sql.=" order by cob_fgenerado ASC ";
                break;


            default:
                $cadena_sql = "";
                break;
        }//fin switch
        return $cadena_sql;
    }

// fin funcion cadena_sql
}

//fin clase sql_adminCuentaCobro
?>

