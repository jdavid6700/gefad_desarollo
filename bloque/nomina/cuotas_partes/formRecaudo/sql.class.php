<?php

/* --------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
  --------------------------------------------------------------------------------------------------------------------------- */

if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/sql.class.php");

class sql_formRecaudo extends sql {

    function cadena_sql($configuracion, $conexion, $opcion, $variable) {

        switch ($opcion) {

            case "actualizarCobro":
                $cadena_sql = " UPDATE cuotas_partes.cuotas_cobros ";
                $cadena_sql.= " SET cob_estado_cuenta='INACTIVA' ";
                $cadena_sql.= " WHERE cob_consecu_cta='" . $variable . "' ";
                break;

            case "consultarEntidades":
                $cadena_sql = " SELECT prev_nombre, hlab_nro_ingreso, hlab_nitprev, prev_nit ";
                $cadena_sql.=" from cuotas_partes.cuotas_previsora, cuotas_partes.cuotas_hlaboral ";
                $cadena_sql.=" where prev_nit= hlab_nitprev and hlab_nro_identificacion = '" . $variable['cedula'] . "' ";
                $cadena_sql.=" and prev_habilitado_pago = 'ACTIVA' ";
                break;

            case "consultarEntidadesRecaudo":
                $cadena_sql = " SELECT prev_nombre, hlab_nitprev, prev_nit  ";
                $cadena_sql.=" FROM cuotas_partes.cuotas_previsora, cuotas_partes.cuotas_hlaboral  ";
                $cadena_sql.=" WHERE prev_nit= hlab_nitprev and hlab_nro_identificacion = '" . $variable['cedula'] . "' ";
                $cadena_sql.=" GROUP BY prev_nombre, hlab_nitprev, prev_nit ";
                break;

            case "consultarRecaudos":
                $cadena_sql = " SELECT ";
                $cadena_sql.=" recta_nitprev, ";
                $cadena_sql.=" prev_nombre, ";
                $cadena_sql.=" recta_consecu_cta, recta_consecu_rec, ";
                $cadena_sql.=" cob_ie_correspondencia, ";
                $cadena_sql.=" rec_resolucionop, ";
                $cadena_sql.=" rec_fecha_resolucion, ";
                $cadena_sql.=" recta_fechapago, ";
                $cadena_sql.=" recta_fechadesde, ";
                $cadena_sql.=" recta_fechahasta, ";
                $cadena_sql.=" rec_pago_capital, ";
                $cadena_sql.=" rec_pago_interes, ";
                $cadena_sql.=" rec_medio_pago ";
                $cadena_sql.=" FROM  ";
                $cadena_sql.=" cuotas_partes.cuotas_recaudo_cuenta, cuotas_partes.cuotas_cobros, cuotas_partes.cuotas_recaudos, cuotas_partes.cuotas_previsora ";
                $cadena_sql.=" WHERE ";
                $cadena_sql.=" recta_cedula='" . $variable['cedula'] . "' AND ";
                $cadena_sql.=" recta_nitprev='" . $variable['entidad'] . "' AND ";
                $cadena_sql.=" cob_consecu_cta=recta_consecu_cta AND ";
                $cadena_sql.=" rec_consecu_rec=recta_consecu_rec AND ";
                $cadena_sql.=" prev_nit=recta_nitprev ";
                $cadena_sql.=" ORDER BY recta_fechapago ASC ";
                break;

            case "consultarCobros":
                $cadena_sql = "  SELECT cob_fgenerado, cob_nitemp, cob_nitprev, cob_consecu_cta, cob_finicial, cob_ffinal, ";
                $cadena_sql.=" cob_ts_interes, cob_interes, cob_tc_interes, cob_ie_correspondencia, cob_cedula, cob_saldo";
                $cadena_sql.=" from cuotas_partes.cuotas_cobros ";
                $cadena_sql.=" where cob_cedula = '" . $variable['cedula'] . "' ";
                $cadena_sql.=" and cob_estado_cuenta = 'ACTIVA' ";
                $cadena_sql.=" and cob_nitprev='" . $variable['entidad'] . "' ";
                $cadena_sql.=" order by cob_fgenerado ASC ";
                break;

            case "consultaPagoConsecutivo":
                $cadena_sql = " SELECT rec_id ";
                $cadena_sql.= " FROM cuotas_partes.cuotas_recaudos ";
                $cadena_sql.= " ORDER BY rec_id DESC ";
                $cadena_sql.= " LIMIT 1 ";
                break;

            case "consultar_cc":
                $cadena_sql = " SELECT cob_fgenerado, cob_nitemp, cob_nitprev, cob_consecu_cta, cob_finicial, cob_ffinal, ";
                $cadena_sql.=" cob_ts_interes, cob_interes, cob_tc_interes, cob_ie_correspondencia, cob_cedula, cob_saldo, ";
                $cadena_sql.=" cob_estado_cuenta, cob_total, cob_mesada, cob_mesada_ad, cob_subtotal, cob_incremento, cob_estado, cob_fecha_registro ";
                $cadena_sql.=" FROM cuotas_partes.cuotas_cobros ";
                $cadena_sql.=" WHERE cob_consecu_cta = '" . $variable['consecutivo_cc'] . "' ";
                break;

            case "registrarPago":
                $cadena_sql = " INSERT INTO cuotas_partes.cuotas_recaudos ";
                $cadena_sql.=" (rec_id,  ";
                $cadena_sql.=" rec_consecu_rec,  ";
                $cadena_sql.=" rec_nitemp,  ";
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
                $cadena_sql.=" '" . $variable['nit_empleador'] . "', ";
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
                $cadena_sql.=" '" . date("d/m/Y") . "'); ";
                break;

            case "registrarPagoCobro":
                $cadena_sql = " INSERT INTO cuotas_partes.cuotas_recaudo_cuenta ";
                $cadena_sql.=" (recta_consecu_cta, ";
                $cadena_sql.=" recta_consecu_rec, ";
                $cadena_sql.=" recta_cedula, ";
                $cadena_sql.=" recta_nitemp, ";
                $cadena_sql.=" recta_nitprev, ";
                $cadena_sql.=" recta_valor_recaudo, ";
                $cadena_sql.=" recta_fechapago, ";
                $cadena_sql.=" recta_fechadesde, ";
                $cadena_sql.=" recta_fechahasta, ";
                $cadena_sql.=" recta_estado, ";
                $cadena_sql.=" recta_fecha_registro) VALUES (";
                $cadena_sql.=" '" . $variable['consecutivo_cc'] . "', ";
                $cadena_sql.=" '" . $variable['consecutivo_rec'] . "', ";
                $cadena_sql.=" '" . $variable['cedula_emp'] . "', ";
                $cadena_sql.=" '" . $variable['nit_empleador'] . "', ";
                $cadena_sql.=" '" . $variable['nit_previsional'] . "', ";
                $cadena_sql.=" '" . $variable['total_recaudo'] . "', ";
                $cadena_sql.=" '" . $variable['fecha_pago'] . "', ";
                $cadena_sql.=" '" . $variable['fecha_pdesde'] . "', ";
                $cadena_sql.=" '" . $variable['fecha_phasta'] . "', ";
                $cadena_sql.=" 'ACTIVO', ";
                $cadena_sql.=" '" . date("d/m/Y") . "' ) ; ";
                break;

            case "registrarSaldo":
                $cadena_sql = " INSERT INTO cuotas_partes.cuotas_cobros (";
                $cadena_sql.= " cob_fgenerado, ";
                $cadena_sql.= " cob_cedula, ";
                $cadena_sql.= " cob_nitemp, ";
                $cadena_sql.= " cob_nitprev, ";
                $cadena_sql.= " cob_consecu_cta, ";
                $cadena_sql.= " cob_saldo, ";
                $cadena_sql.= " cob_finicial, ";
                $cadena_sql.= " cob_ffinal, ";
                $cadena_sql.= " cob_mesada, ";
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
                $cadena_sql.= " '" . $variable['cob_fgenerado'] . "', ";
                $cadena_sql.= " '" . $variable['cob_cedula'] . "', ";
                $cadena_sql.= " '" . $variable['cob_nitemp'] . "', ";
                $cadena_sql.= " '" . $variable['cob_nitprev'] . "', ";
                $cadena_sql.= " '" . $variable['cob_consecu_cta'] . "', ";
                $cadena_sql.= " '" . $variable['cob_saldo'] . "', ";
                $cadena_sql.= " '" . $variable['cob_finicial'] . "', ";
                $cadena_sql.= " '" . $variable['cob_ffinal'] . "', ";
                $cadena_sql.= " '" . $variable['cob_mesada'] . "', ";
                $cadena_sql.= " '" . $variable['cob_mesada_ad'] . "', ";
                $cadena_sql.= " '" . $variable['cob_subtotal'] . "', ";
                $cadena_sql.= " '" . $variable['cob_incremento'] . "', ";
                $cadena_sql.= " '" . $variable['cob_ts_interes'] . "', ";
                $cadena_sql.= " '" . $variable['cob_interes'] . "', ";
                $cadena_sql.= " '" . $variable['cob_tc_interes'] . "', ";
                $cadena_sql.= " '" . $variable['cob_total'] . "', ";
                $cadena_sql.= " '" . $variable['cob_ie_correspondencia'] . "', ";
                $cadena_sql.= " '" . $variable['cob_estado_cuenta'] . "', ";
                $cadena_sql.= " '" . $variable['cob_estado'] . "', ";
                $cadena_sql.= " '" . $variable['cob_fecha_registro'] . "'); ";
                break;

            default:
                $cadena_sql = "";
                break;
        }//fin switch

        return $cadena_sql;
    }

}

?>
