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

            case "actualizarRecta":
                $cadena_sql = " UPDATE cuotas_partes.cuotas_recaudo_cuenta ";
                $cadena_sql.= " SET recta_estado='INACTIVO' ";
                $cadena_sql.= " WHERE recta_id='" . $variable . "' ";
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
                $cadena_sql.=" recta_cedula='" . $variable['cedula_emp'] . "' AND ";
                $cadena_sql.=" recta_nitprev='" . $variable['nit_previsional'] . "' AND ";
                $cadena_sql.=" cob_consecu_cta=recta_consecu_cta AND ";
                $cadena_sql.=" recta_consecu_rec=rec_consecu_rec AND  ";
                $cadena_sql.=" prev_nit=recta_nitprev ";
                $cadena_sql.=" ORDER BY recta_fechapago ASC ";
                break;

            case "consultarCobros":
                $cadena_sql = "  SELECT cob_fgenerado, cob_nitprev, cob_consecu_cta, cob_finicial, cob_ffinal, ";
                $cadena_sql.=" cob_ts_interes, cob_interes, cob_tc_interes, cob_ie_correspondencia, cob_cedula, recta_saldototal, recta_saldointeres, recta_saldocapital ";
                $cadena_sql.=" FROM cuotas_partes.cuotas_cobros, cuotas_partes.cuotas_recaudo_cuenta ";
                $cadena_sql.=" WHERE cob_cedula='" . $variable['cedula_emp'] . "' ";
                $cadena_sql.=" AND cob_estado_cuenta='ACTIVA' ";
                $cadena_sql.=" AND cob_nitprev='" . $variable['nit_previsional'] . "'  ";
                $cadena_sql.=" AND cob_nitprev=recta_nitprev ";
                $cadena_sql.=" AND recta_cedula=cob_cedula ";
                $cadena_sql.=" AND recta_estado='ACTIVO' ";
                $cadena_sql.=" ORDER by cob_fgenerado ASC ";
                break;

            case "consultarCobrosEstado":
                $cadena_sql = "  SELECT cob_fgenerado, cob_nitprev, cob_consecu_cta, cob_finicial, cob_ffinal, ";
                $cadena_sql.=" cob_ts_interes, cob_interes, cob_tc_interes, cob_ie_correspondencia, cob_cedula, cob_saldo";
                $cadena_sql.=" from cuotas_partes.cuotas_cobros ";
                $cadena_sql.=" where cob_cedula = '" . $variable['cedula'] . "' ";
                $cadena_sql.=" and cob_nitprev='" . $variable['entidad'] . "' ";
                $cadena_sql.=" order by cob_fgenerado ASC ";
                break;

            case "consultarCobrosPago":
                $cadena_sql = "  SELECT cob_fgenerado, cob_nitprev, cob_consecu_cta, cob_finicial, cob_ffinal, ";
                $cadena_sql.=" cob_ts_interes, cob_interes, cob_tc_interes, cob_ie_correspondencia, cob_cedula, cob_saldo";
                $cadena_sql.=" from cuotas_partes.cuotas_cobros ";
                $cadena_sql.=" where cob_cedula = '" . $variable['cedula'] . "' ";
                $cadena_sql.=" and cob_estado_cuenta = 'ACTIVA' ";
                $cadena_sql.=" and cob_nitprev='" . $variable['entidad'] . "' ";
                $cadena_sql.=" order by cob_fgenerado ASC ";
                break;

            case "consultarRecaudoCompleto":
                $cadena_sql = "  SELECT ";
                $cadena_sql.=" recta_consecu_rec,  ";
                $cadena_sql.=" rec_resolucionop, ";
                $cadena_sql.=" rec_fecha_resolucion, ";
                $cadena_sql.=" recta_fechapago, ";
                $cadena_sql.=" recta_fechadesde,  ";
                $cadena_sql.=" recta_fechahasta, ";
                $cadena_sql.=" rec_pago_capital, ";
                $cadena_sql.=" rec_pago_interes, ";
                $cadena_sql.=" rec_total_recaudo ";
                $cadena_sql.=" FROM cuotas_partes.cuotas_recaudos,cuotas_partes.cuotas_recaudo_cuenta ";
                $cadena_sql.=" WHERE rec_nitprev='" . $variable['nit_previsional'] . "' ";
                $cadena_sql.=" AND rec_identificacion='" . $variable['cedula_emp'] . "' ";
                $cadena_sql.=" AND rec_identificacion=recta_cedula ";
                $cadena_sql.=" AND recta_consecu_rec=rec_consecu_rec ";
                $cadena_sql.=" AND rec_nitprev=recta_nitprev ";
                //$cadena_sql.=" AND recta_estado='ACTIVO' ";
                $cadena_sql.=" AND rec_estado='ACTIVO' ";
                break;

            case "consultarRecaudoUnico":
                $cadena_sql = "  SELECT ";
                $cadena_sql.=" rec_consecu_rec,  ";
                $cadena_sql.=" rec_resolucionop, ";
                $cadena_sql.=" rec_fecha_resolucion, ";
                $cadena_sql.=" rec_pago_capital, ";
                $cadena_sql.=" rec_pago_interes, ";
                $cadena_sql.=" rec_total_recaudo ";
                $cadena_sql.=" FROM cuotas_partes.cuotas_recaudos ";
                $cadena_sql.=" WHERE rec_nitprev='" . $variable['nit_previsional'] . "' ";
                $cadena_sql.=" AND rec_identificacion='" . $variable['cedula_emp'] . "' ";
                $cadena_sql.=" AND rec_consecu_rec='" . $variable['consecutivo_rec'] . "' ";
                $cadena_sql.=" AND rec_estado='ACTIVO' ";
                break;

            case "consultaPagoConsecutivo":
                $cadena_sql = " SELECT rec_id ";
                $cadena_sql.= " FROM cuotas_partes.cuotas_recaudos ";
                $cadena_sql.= " ORDER BY rec_id DESC ";
                $cadena_sql.= " LIMIT 1 ";
                break;

            case "consultar_cc":
                $cadena_sql = " SELECT cob_fgenerado, cob_nitprev, cob_consecu_cta, cob_finicial, cob_ffinal, ";
                $cadena_sql.=" cob_ts_interes, cob_interes, cob_tc_interes, cob_ie_correspondencia, cob_cedula, cob_saldo, ";
                $cadena_sql.=" cob_estado_cuenta, cob_total, cob_mesada_ordinaria, cob_mesada_ad, cob_subtotal, cob_incremento, cob_estado, cob_fecha_registro ";
                $cadena_sql.=" FROM cuotas_partes.cuotas_cobros ";
                $cadena_sql.=" WHERE cob_consecu_cta = '" . $variable['consecutivo_cc'] . "' ";
                break;

            case "consecutivoRecta":
                $cadena_sql = " SELECT recta_id ";
                $cadena_sql.=" FROM cuotas_partes.cuotas_recaudo_cuenta ";
                $cadena_sql.=" ORDER BY recta_id DESC ";
                $cadena_sql.=" LIMIT 1 ";
                break;

            case "consultarSaldoAnterior":
                $cadena_sql = " SELECT recta_id,recta_consecu_cta, recta_consecu_rec, recta_valor_cobro, recta_valor_recaudo, recta_saldototal,recta_saldocapital,recta_saldointeres, recta_estado ";
                $cadena_sql.=" FROM cuotas_partes.cuotas_recaudo_cuenta ";
                $cadena_sql.=" WHERE recta_consecu_cta='" . $variable['consecutivo_cc'] . "' ";
                $cadena_sql.=" AND recta_cedula='" . $variable['cedula_emp'] . "' ";
                $cadena_sql.=" AND recta_nitprev='" . $variable['nit_previsional'] . "' ";
                $cadena_sql.=" AND recta_estado='ACTIVO' ";
                break;

            case "datos_pensionado":
                $cadena_sql = " SELECT ";
                $cadena_sql.=" emp_nro_iden AS Cedula, ";
                $cadena_sql.=" emp_nombre AS NOMBRE, ";
                $cadena_sql.=" emp_fecha_pen AS FECHA_PENSION,";
                $cadena_sql.=" EMP_FECHA_NAC as FECHA_NAC, ";
                $cadena_sql.=" EMP_FALLECIDO as fallecido ";
                $cadena_sql.=" from peemp ";
                $cadena_sql.=" where emp_nro_iden='" . $variable['cedula'] . "' and emp_estado='A' ";
                break;

            case "datos_concurrencia":
                $cadena_sql = " SELECT dcp_nro_identificacion, ";
                $cadena_sql.=" dcp_nitent, ";
                $cadena_sql.=" dcp_nitprev, ";
                $cadena_sql.=" dcp_fecha_concurrencia, ";
                $cadena_sql.=" dcp_resol_pension_fecha, ";
                $cadena_sql.=" dcp_actoadmin, ";
                $cadena_sql.=" dcp_factoadmin, ";
                $cadena_sql.=" dcp_fecha_pension, ";
                $cadena_sql.=" dcp_valor_mesada, ";
                $cadena_sql.=" dcp_valor_cuota, ";
                $cadena_sql.=" dcp_porcen_cuota ";
                $cadena_sql.=" FROM cuotas_partes.cuotas_descripcion_cuotaparte ";
                $cadena_sql.=" WHERE dcp_nro_identificacion='" . $variable['cedula'] . "' ";
                $cadena_sql.=" AND dcp_nitprev='" . $variable['entidad'] . "' ";
                break;

            case "datos_saldos":
                $cadena_sql = " SELECT recta_consecu_cta, recta_consecu_rec, ";
                $cadena_sql.=" recta_valor_cobro, recta_valor_recaudo,  ";
                $cadena_sql.=" recta_saldototal ";
                $cadena_sql.=" FROM cuotas_partes.cuotas_recaudo_cuenta  ";
                $cadena_sql.=" WHERE recta_cedula='" . $variable['cedula'] . "'  ";
                $cadena_sql.=" AND recta_nitprev='" . $variable['entidad'] . "'  ";
                $cadena_sql.=" AND recta_estado='ACTIVO'  ";
                break;

            case "datos_saldosHistoria":
                $cadena_sql = " SELECT recta_consecu_cta, recta_consecu_rec, ";
                $cadena_sql.=" recta_valor_cobro, recta_valor_recaudo,  ";
                $cadena_sql.=" recta_saldototal, recta_saldointeres, recta_saldocapital ";
                $cadena_sql.=" FROM cuotas_partes.cuotas_recaudo_cuenta  ";
                $cadena_sql.=" WHERE recta_cedula='" . $variable['cedula'] . "'  ";
                $cadena_sql.=" AND recta_nitprev='" . $variable['entidad'] . "'  ";
                break;

            case "jefeRecursosH":
                $cadena_sql = " SELECT emp_nombre ";
                $cadena_sql.= " FROM gedep, peemp ";
                $cadena_sql.= " WHERE emp_cod=dep_emp_cod ";
                $cadena_sql.= " AND dep_nombre='DIVISION DE RECURSOS HUMANOS' ";
                break;

            case "nombreEntidad":
                $cadena_sql = " SELECT prev_nombre";
                $cadena_sql.=" FROM cuotas_partes.cuotas_previsora";
                $cadena_sql.=" WHERE prev_nit= '" . $variable['entidad'] . "' ";
                $cadena_sql.=" and prev_habilitado_pago = 'ACTIVA' ";
                break;

            case "registrarPago":
                $cadena_sql = " INSERT INTO cuotas_partes.cuotas_recaudos ";
                $cadena_sql.=" (rec_id,  ";
                $cadena_sql.=" rec_consecu_rec,  ";
                $cadena_sql.=" rec_nitprev,  ";
                $cadena_sql.=" rec_identificacion,  ";
                $cadena_sql.=" rec_resolucionop,  ";
                $cadena_sql.=" rec_fecha_resolucion,  ";
                // $cadena_sql.=" rec_actoadmin,  ";
                // $cadena_sql.=" rec_factoadmin,  ";
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
                // $cadena_sql.=" '" . $variable['actoadmin'] . "', ";
                // $cadena_sql.=" '" . $variable['factoadmin'] . "', ";
                $cadena_sql.=" '" . $variable['fecha_pago_cuenta'] . "', ";
                $cadena_sql.=" '" . $variable['medio_pago'] . "', ";
                $cadena_sql.=" '" . $variable['valor_pagado_capital'] . "', ";
                $cadena_sql.=" '" . $variable['valor_pagado_interes'] . "', ";
                $cadena_sql.=" '" . $variable['total_recaudo'] . "', ";
                $cadena_sql.=" 'ACTIVO', ";
                $cadena_sql.=" '" . $variable['fecha_registro'] . "'); ";
                break;

            case "registrarPagoCobro":
                $cadena_sql = " INSERT INTO cuotas_partes.cuotas_recaudo_cuenta ";
                $cadena_sql.=" (recta_consecu_cta, ";
                $cadena_sql.=" recta_consecu_rec, ";
                $cadena_sql.=" recta_cedula, ";
                $cadena_sql.=" recta_nitprev, ";
                $cadena_sql.=" recta_valor_recaudo, ";
                $cadena_sql.=" recta_valor_cobro, ";
                $cadena_sql.=" recta_saldo, ";
                $cadena_sql.=" recta_fechapago, ";
                $cadena_sql.=" recta_fechadesde, ";
                $cadena_sql.=" recta_fechahasta, ";
                $cadena_sql.=" recta_estado, ";
                $cadena_sql.=" recta_fecha_registro) VALUES (";
                $cadena_sql.=" '" . $variable['consecutivo_cc'] . "', ";
                $cadena_sql.=" '" . $variable['consecutivo_rec'] . "', ";
                $cadena_sql.=" '" . $variable['cedula_emp'] . "', ";
                $cadena_sql.=" '" . $variable['nit_previsional'] . "', ";
                $cadena_sql.=" '" . $variable['total_recaudo'] . "', ";
                $cadena_sql.=" '" . $variable['total_cobro'] . "', ";
                $cadena_sql.=" '" . $variable['saldo'] . "', ";
                $cadena_sql.=" '" . $variable['fecha_pago'] . "', ";
                $cadena_sql.=" '" . $variable['fecha_pdesde'] . "', ";
                $cadena_sql.=" '" . $variable['fecha_phasta'] . "', ";
                $cadena_sql.=" 'ACTIVO', ";
                $cadena_sql.=" '" . date("d/m/Y") . "' ) ; ";
                break;

            case "registrarSaldo":
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
                $cadena_sql.=" recta_fechapago, ";
                $cadena_sql.=" recta_fechadesde,  ";
                $cadena_sql.=" recta_fechahasta,  ";
                $cadena_sql.=" recta_estado, ";
                $cadena_sql.=" recta_fecha_registro) VALUES (";
                $cadena_sql.=" '" . $variable['recta_consecu_cta'] . "', ";
                $cadena_sql.=" '" . $variable['recta_consecu_rec'] . "', ";
                $cadena_sql.=" '" . $variable['recta_id'] . "', ";
                $cadena_sql.=" '" . $variable['recta_cedula'] . "', ";
                $cadena_sql.=" '" . $variable['recta_nitprev'] . "', ";
                $cadena_sql.=" '" . $variable['recta_valor_recaudo'] . "', ";
                $cadena_sql.=" '" . $variable['recta_valor_cobro'] . "', ";
                $cadena_sql.=" '" . $variable['recta_saldocapital'] . "', ";
                $cadena_sql.=" '" . $variable['recta_saldointeres'] . "', ";
                $cadena_sql.=" '" . $variable['recta_saldototal'] . "', ";
                $cadena_sql.=" '" . $variable['recta_fechapago'] . "', ";
                $cadena_sql.=" '" . $variable['recta_fechadesde'] . "', ";
                $cadena_sql.=" '" . $variable['recta_fechahasta'] . "', ";
                $cadena_sql.=" 'ACTIVO', ";
                $cadena_sql.=" '" . date("d/m/Y") . "' ) ; ";
                break;

            /* Para cargues masivos */
            case "consultarCargue":
                $cadena_sql = " SELECT cuotas_pagomasivo.cedula, cuotas_pagomasivo.nit_previsora, cuotas_pagomasivo.resolucion_op, cuotas_pagomasivo.fecha_resoop, cuotas_pagomasivo.capital,  ";
                $cadena_sql.=" cuotas_pagomasivo.interes, cuotas_pagomasivo.total, cuotas_pagomasivo.fechapago, cuotas_pagomasivo.fpdesde, cuotas_pagomasivo.fphasta,cuotas_pagomasivo.observacion ";
                $cadena_sql.=" FROM cuotas_partes.cuotas_pagomasivo ";
                $cadena_sql.=" WHERE cuotas_pagomasivo.estado='1' ";
                break;
            
            default:
                $cadena_sql = "";
                break;
        }//fin switch

        return $cadena_sql;
    }

}

?>
