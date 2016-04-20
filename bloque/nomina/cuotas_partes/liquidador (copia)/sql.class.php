<?php

/* --------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
  --------------------------------------------------------------------------------------------------------------------------- */

if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/sql.class.php");

class sql_liquidador extends sql {

    function cadena_sql($configuracion, $conexion, $opcion, $variable) {

        switch ($opcion) {

            //Reestructuración
            case "consultarEntidades":
                $cadena_sql = " SELECT prev_nombre, hlab_nro_ingreso, hlab_nitprev, prev_nit ";
                $cadena_sql.=" from cuotas_partes.cuotas_previsora, cuotas_partes.cuotas_hlaboral ";
                $cadena_sql.=" where prev_nit= hlab_nitprev and hlab_nro_identificacion = '" . $variable['cedula'] . "' ";
                $cadena_sql.=" and prev_habilitado_pago = 'ACTIVA' ";
                break;

            case "nombreEntidad":
                $cadena_sql = " SELECT prev_nombre";
                $cadena_sql.=" FROM cuotas_partes.cuotas_previsora";
                $cadena_sql.=" WHERE prev_nit= '" . $variable['entidad'] . "' ";
                $cadena_sql.=" and prev_habilitado_pago = 'ACTIVA' ";
                break;

            case "datosHistoria":
                $cadena_sql = " SELECT ";
                $cadena_sql.=" hlab_nro_identificacion,";
                $cadena_sql.=" hlab_nitenti, ";
                $cadena_sql.=" hlab_nitprev, ";
                $cadena_sql.=" hlab_fingreso, ";
                $cadena_sql.=" hlab_fretiro, ";
                $cadena_sql.=" hlab_horas, ";
                $cadena_sql.=" hlab_periodicidad ";
                $cadena_sql.=" FROM cuotas_partes.cuotas_hlaboral ";
                $cadena_sql.=" WHERE hlab_nitprev='" . $variable['entidad'] . "'  ";
                $cadena_sql.=" AND hlab_nro_identificacion='" . $variable['cedula'] . "' ";
                break;

            case "datosHistoriaTotales":
                $cadena_sql = " SELECT ";
                $cadena_sql.=" hlab_nro_identificacion,";
                $cadena_sql.=" hlab_nitenti, ";
                $cadena_sql.=" hlab_nitprev, ";
                $cadena_sql.=" hlab_fingreso, ";
                $cadena_sql.=" hlab_fretiro, ";
                $cadena_sql.=" hlab_horas, ";
                $cadena_sql.=" hlab_periodicidad ";
                $cadena_sql.=" FROM cuotas_partes.cuotas_hlaboral ";
                $cadena_sql.=" WHERE hlab_nro_identificacion='" . $variable['cedula'] . "' ";
                break;

            case "datos_pensionado":
                $cadena_sql = " SELECT ";
                $cadena_sql.=" emp_nro_iden AS Cedula, ";
                $cadena_sql.=" emp_nombre AS NOMBRE, ";
                $cadena_sql.=" to_char(emp_fecha_pen,'DD/MM/YYYY') AS FECHA_PENSION,";
                $cadena_sql.=" to_char(EMP_FECHA_NAC,'DD/MM/YYYY') as FECHA_NAC, ";
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

            case "consecutivoRecta":
                $cadena_sql = " SELECT recta_id ";
                $cadena_sql.=" FROM cuotas_partes.cuotas_recaudo_cuenta ";
                $cadena_sql.=" ORDER BY recta_id DESC ";
                $cadena_sql.=" LIMIT 1 ";
                break;

            case "insertarRecta":
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

            case "recaudos":
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

            case "recaudos_fechaliq":
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
                $cadena_sql.=" ORDER BY recta_fechahasta DESC ";
                break;

            case "valor_sumafija":
                $cadena_sql = " SELECT ipc_sumas_fijas as suma_fija ";
                $cadena_sql.=" FROM cuotas_partes.cuotas_indc_ipc ";
                $cadena_sql.=" WHERE ipc_fecha='" . $variable . "' ";
                $cadena_sql.=" AND ipc_estado_registro='1' ";
                $cadena_sql.=" ORDER BY ipc_sumas_fijas DESC ";
                $cadena_sql.=" LIMIT 1";
                break;

            case "valor_ipc":
                $cadena_sql = " SELECT ipc_indiceipc as valor_ipc ";
                $cadena_sql.=" FROM cuotas_partes.cuotas_indc_ipc ";
                $cadena_sql.=" WHERE ipc_fecha='" . $variable . "' ";
                $cadena_sql.=" AND ipc_estado_registro='1' ";
                $cadena_sql.=" ORDER BY ipc_sumas_fijas ASC ";
                break;
            
             case "detalle_indices":
                $cadena_sql = " SELECT ipc_fecha, ipc_indiceipc, ipc_sumas_fijas ";
                $cadena_sql.=" FROM cuotas_partes.cuotas_indc_ipc ";
                $cadena_sql.=" WHERE ipc_fecha='" . $variable . "' ";
                $cadena_sql.=" AND ipc_estado_registro='1' ";
                $cadena_sql.=" ORDER BY ipc_fecha ASC ";
                break;

            case "valor_dtf":
                $cadena_sql = " SELECT dtf_fe_desde, dtf_fe_hasta, dtf_indi_ce ";
                $cadena_sql.=" FROM cuotas_partes.cuotas_indc_dtf ";
                $cadena_sql.=" WHERE dtf_estado='1' ";
                $cadena_sql.=" ORDER BY dtf_fe_desde ASC ";
                break;

            case "consecutivo":
                $cadena_sql = " SELECT liq_consecutivo ";
                $cadena_sql.=" FROM cuotas_partes.cuotas_liquidacion ";
                $cadena_sql.=" ORDER BY liq_consecutivo DESC ";
                $cadena_sql.=" LIMIT 1 ";
                break;

            case "consecutivoCC":
                $cadena_sql = " SELECT cob_idcob ";
                $cadena_sql.= " FROM cuotas_partes.cuotas_cobros ";
                $cadena_sql.= " ORDER BY cob_idcob DESC ";
                $cadena_sql.= " LIMIT 1 ";
                break;

            case "guardarLiquidacion":
                $cadena_sql = " INSERT INTO ";
                $cadena_sql.=" cuotas_partes.cuotas_liquidacion ( ";
                $cadena_sql.=" liq_consecutivo, ";
                $cadena_sql.=" liq_fgenerado, ";
                $cadena_sql.=" liq_cedula, ";
                $cadena_sql.=" liq_nitprev, ";
                $cadena_sql.=" liq_fdesde, ";
                $cadena_sql.=" liq_fhasta, ";
                $cadena_sql.=" liq_mesada, ";
                $cadena_sql.=" liq_ajustepen, ";
                $cadena_sql.=" liq_mesada_ad, ";
                $cadena_sql.=" liq_incremento, ";
                $cadena_sql.=" liq_interes, ";
                $cadena_sql.=" liq_interes_a2006, ";
                $cadena_sql.=" liq_interes_d2006, ";
                $cadena_sql.=" liq_cuotap, ";
                $cadena_sql.=" liq_total, ";
                $cadena_sql.=" liq_estado_cc, ";
                $cadena_sql.=" liq_fecha_estado_cc, ";
                $cadena_sql.=" liq_estado_ccdetalle, ";
                $cadena_sql.=" liq_fecha_estado_ccdetalle, ";
                $cadena_sql.=" liq_estado_ccresumen, ";
                $cadena_sql.=" liq_fecha_estado_ccresumen, ";
                $cadena_sql.=" liq_estado, ";
                $cadena_sql.=" liq_fecha_registro ";
                $cadena_sql.=") VALUES ( ";
                $cadena_sql.=" '" . $variable['liq_consecutivo'] . "', ";
                $cadena_sql.=" '" . $variable['liq_fgenerado'] . "', ";
                $cadena_sql.=" '" . $variable['liq_cedula'] . "', ";
                $cadena_sql.=" '" . $variable['liq_nitprev'] . "', ";
                $cadena_sql.=" '" . $variable['liq_fdesde'] . "', ";
                $cadena_sql.=" '" . $variable['liq_fhasta'] . "', ";
                $cadena_sql.=" '" . $variable['liq_mesada'] . "', ";
                $cadena_sql.=" '" . $variable['liq_ajustepen'] . "', ";
                $cadena_sql.=" '" . $variable['liq_mesada_ad'] . "', ";
                $cadena_sql.=" '" . $variable['liq_incremento'] . "', ";
                $cadena_sql.=" '" . $variable['liq_interes'] . "', ";
                $cadena_sql.=" '" . $variable['liq_interes_a2006'] . "', ";
                $cadena_sql.=" '" . $variable['liq_interes_d2006'] . "', ";
                $cadena_sql.=" '" . $variable['liq_cuotap'] . "', ";
                $cadena_sql.=" '" . $variable['liq_total'] . "', ";
                $cadena_sql.=" '" . $variable['liq_estado_cc'] . "', ";
                $cadena_sql.=" '" . $variable['liq_fecha_estado_cc'] . "', ";
                $cadena_sql.=" '" . $variable['liq_estado_ccdetalle'] . "', ";
                $cadena_sql.=" '" . $variable['liq_fecha_estado_ccdetalle'] . "', ";
                $cadena_sql.=" '" . $variable['liq_estado_ccresumen'] . "', ";
                $cadena_sql.=" '" . $variable['liq_fecha_estado_ccresumen'] . "', ";
                $cadena_sql.=" '" . $variable['liq_estado'] . "', ";
                $cadena_sql.=" '" . $variable['liq_fecha_registro'] . "') ";
                break;

            case "consultarLiquidacion":
                $cadena_sql = " SELECT ";
                $cadena_sql.=" liq_consecutivo, ";
                $cadena_sql.=" liq_fgenerado, ";
                $cadena_sql.=" liq_cedula, ";
                $cadena_sql.=" liq_nitprev, ";
                $cadena_sql.=" liq_fdesde, ";
                $cadena_sql.=" liq_fhasta, ";
                $cadena_sql.=" liq_mesada, ";
                $cadena_sql.=" liq_ajustepen, ";
                $cadena_sql.=" liq_mesada_ad, ";
                $cadena_sql.=" liq_incremento, ";
                $cadena_sql.=" liq_interes, ";
                $cadena_sql.=" liq_interes_a2006, ";
                $cadena_sql.=" liq_interes_d2006, ";
                $cadena_sql.=" liq_cuotap, ";
                $cadena_sql.=" liq_total, ";
                $cadena_sql.=" liq_estado_cc, ";
                $cadena_sql.=" liq_fecha_estado_cc, ";
                $cadena_sql.=" liq_estado_ccdetalle, ";
                $cadena_sql.=" liq_fecha_estado_ccdetalle, ";
                $cadena_sql.=" liq_estado_ccresumen, ";
                $cadena_sql.=" liq_fecha_estado_ccresumen, ";
                $cadena_sql.=" liq_estado, ";
                $cadena_sql.=" liq_fecha_registro ";
                $cadena_sql.=" FROM cuotas_partes.cuotas_liquidacion ";
                $cadena_sql.=" WHERE liq_cedula='" . $variable['cedula'] . "' ";
                $cadena_sql.=" AND liq_nitprev='" . $variable['entidad'] . "' ";
                $cadena_sql.=" AND liq_estado='ACTIVO' ";
                $cadena_sql.=" ORDER BY liq_consecutivo ASC ";
                break;

            case "consultarLiquidacionConsecutivo":
                $cadena_sql = " SELECT ";
                $cadena_sql.=" liq_consecutivo, ";
                $cadena_sql.=" liq_fgenerado, ";
                $cadena_sql.=" liq_cedula, ";
                $cadena_sql.=" liq_nitprev, ";
                $cadena_sql.=" liq_fdesde, ";
                $cadena_sql.=" liq_fhasta, ";
                $cadena_sql.=" liq_mesada, ";
                $cadena_sql.=" liq_ajustepen, ";
                $cadena_sql.=" liq_mesada_ad, ";
                $cadena_sql.=" liq_incremento, ";
                $cadena_sql.=" liq_interes, ";
                $cadena_sql.=" liq_interes_a2006, ";
                $cadena_sql.=" liq_interes_d2006, ";
                $cadena_sql.=" liq_cuotap, ";
                $cadena_sql.=" liq_total, ";
                $cadena_sql.=" liq_estado_cc, ";
                $cadena_sql.=" liq_fecha_estado_cc, ";
                $cadena_sql.=" liq_estado_ccdetalle, ";
                $cadena_sql.=" liq_fecha_estado_ccdetalle, ";
                $cadena_sql.=" liq_estado_ccresumen, ";
                $cadena_sql.=" liq_fecha_estado_ccresumen, ";
                $cadena_sql.=" liq_estado, ";
                $cadena_sql.=" liq_fecha_registro ";
                $cadena_sql.=" FROM cuotas_partes.cuotas_liquidacion ";
                $cadena_sql.=" WHERE liq_cedula = '" . $variable['cedula'] . "' ";
                $cadena_sql.=" AND liq_nitprev = '" . $variable['entidad'] . "' ";
                $cadena_sql.=" AND liq_consecutivo = '" . $variable['liq_consecutivo'] . "' ";
                $cadena_sql.=" AND liq_estado = 'ACTIVO' ";
                break;

            case "guardar_cuentac":
                $cadena_sql = " INSERT INTO cuotas_partes.cuotas_cobros (";
                $cadena_sql.= " cob_idliq, ";
                $cadena_sql.= " cob_idcob, ";
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
                $cadena_sql.= " cob_ajustepen, ";
                $cadena_sql.= " cob_ts_interes, ";
                $cadena_sql.= " cob_interes, ";
                $cadena_sql.= " cob_tc_interes, ";
                $cadena_sql.= " cob_total, ";
                $cadena_sql.= " cob_ie_correspondencia, ";
                $cadena_sql.= " cob_estado_cuenta, ";
                $cadena_sql.= " cob_estado, ";
                $cadena_sql.= " cob_fecha_registro ) VALUES ( ";
                $cadena_sql.= " '" . $variable['id_liq'] . "', ";
                $cadena_sql.= " '" . $variable['id_cuentac'] . "', ";
                $cadena_sql.= " '" . $variable['fecha_generacion'] . "', ";
                $cadena_sql.= " '" . $variable['cedula'] . "', ";
                $cadena_sql.= " '" . $variable['previsor'] . "', ";
                $cadena_sql.= " '" . $variable['consecutivo_cc'] . "', ";
                $cadena_sql.= " '" . $variable['saldo_fecha'] . "', ";
                $cadena_sql.= " '" . $variable['fecha_inicial'] . "', ";
                $cadena_sql.= " '" . $variable['fecha_final'] . "', ";
                $cadena_sql.= " '" . $variable['mesada_ordinaria'] . "', ";
                $cadena_sql.= " '" . $variable['mesada_adc'] . "', ";
                $cadena_sql.= " '" . $variable['subtotal'] . "', ";
                $cadena_sql.= " '" . $variable['incremento'] . "', ";
                $cadena_sql.= " '" . $variable['ajuste_pension'] . "', ";
                $cadena_sql.= " '" . $variable['t_sin_interes'] . "', ";
                $cadena_sql.= " '" . $variable['interes'] . "', ";
                $cadena_sql.= " '" . $variable['t_con_interes'] . "', ";
                $cadena_sql.= " '" . $variable['total'] . "', ";
                $cadena_sql.= " '" . $variable['fecha_recibido'] . "', ";
                $cadena_sql.= " '" . $variable['estado_cuenta'] . "', ";
                $cadena_sql.= " '" . $variable['estado'] . "', ";
                $cadena_sql.= " '" . $variable['fecha_registro'] . "' ); ";
                break;

            case "consultarCC":
                $cadena_sql = " SELECT cob_idcob, cob_idliq, cob_cedula, cob_nitprev,cob_consecu_cta ";
                $cadena_sql.= " FROM cuotas_partes.cuotas_cobros ";
                $cadena_sql.= " WHERE cob_idliq='" . $variable['id_liq'] . "' ";
                $cadena_sql.= " AND cob_cedula='" . $variable['cedula'] . "' ";
                $cadena_sql.= " AND cob_nitprev='" . $variable['entidad'] . "' ";
                break;

            case "jefeRecursosH":
                $cadena_sql = " SELECT emp_nombre ";
                $cadena_sql.= " FROM gedep, peemp ";
                $cadena_sql.= " WHERE emp_cod=dep_emp_cod ";
                $cadena_sql.= " AND dep_nombre='DIVISION DE RECURSOS HUMANOS' ";
                break;

            case "jefeTesoreria":
                $cadena_sql = " SELECT emp_nombre ";
                $cadena_sql.= " FROM gedep, peemp ";
                $cadena_sql.= " WHERE emp_cod=dep_emp_cod ";
                $cadena_sql.= " AND dep_nombre='TESORERIA' ";
                break;



            default:
                $cadena_sql = "";
                break;
        }//fin switch
        return $cadena_sql;
    }

// fin funcion cadena_sql
}

//fin clase sql_liquidador
?>

