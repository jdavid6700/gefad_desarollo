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
            case "historia_empleado":
                $cadena_sql = " select cuotas_partes.entidades_cp.cedula_emp as cedula, entidades_cp.mesada, ";
                $cadena_sql.=" entidades_cp.nombre_entidad as nombre_entidad, ";
                $cadena_sql.=" entidades_cp.fecha_ingreso, fecha_salida, dias, ";
                $cadena_sql.=" substr(cast(((dias/total)*100) as text), 1, position('.' in cast(((dias/total)*100) as text))) || ";
                $cadena_sql.=" substr(cast(((dias/total)*100) as text), position('.' in cast((dias/total)*100 as text)) + 1, 5) as porcentaje_cuota ";
                $cadena_sql.=" from ";
                $cadena_sql.=" (select ((extract(year from age(fecha_salida::date, fecha_ingreso::date))*360 + ";
                $cadena_sql.=" extract(month from age(fecha_salida::date, fecha_ingreso::date))*30 + ";
                $cadena_sql.=" extract(day from age(fecha_salida::date, fecha_ingreso::date)))) as dias, ";
                $cadena_sql.=" nit_entidad, cedula_emp, fecha_ingreso, ";
                $cadena_sql.=" (select sum((extract(year from age(fecha_salida::date, fecha_ingreso::date))*360 + ";
                $cadena_sql.=" extract(month from age(fecha_salida::date, fecha_ingreso::date))*30 + ";
                $cadena_sql.=" extract(day from age(fecha_salida::date, fecha_ingreso::date)))) as total ";
                $cadena_sql.=" from cuotas_partes.entidades_cp where entidades_cp.cedula_emp = '9906') as total ";
                $cadena_sql.=" from cuotas_partes.entidades_cp ";
                $cadena_sql.=" where entidades_cp.cedula_emp = '" . $variable['cedula'] . "') as totales, ";
                $cadena_sql.=" cuotas_partes.entidades_cp ";
                $cadena_sql.=" where totales.cedula_emp = entidades_cp.cedula_emp ";
                $cadena_sql.=" and totales.nit_entidad = entidades_cp.nit_entidad ";
                $cadena_sql.=" and totales.fecha_ingreso = entidades_cp.fecha_ingreso ";
                $cadena_sql.=" and entidades_cp.cedula_emp = '" . $variable['cedula'] . "' ";
                $cadena_sql.=" order by fecha_ingreso ";
                break;

            case "registro_entidades":
                $cadena_sql = "select cuotas_partes.entidades_cp.cedula_emp as cedula, entidades_cp.mesada, ";
                $cadena_sql.=" entidades_cp.nombre_entidad as nombre_entidad, ";
                $cadena_sql.=" entidades_cp.fecha_ingreso,fecha_salida, dias,  ";
                $cadena_sql.=" cuotas_partes.entidades_cp.nit_entidad as nit_entidad, ";
                $cadena_sql.=" substr(cast(((dias/total)*100) as text),1,position('.' in cast(((dias/total)*100) as text))) ||  ";
                $cadena_sql.=" substr(cast(((dias/total)*100) as text),position('.' in cast((dias/total)*100 as text)) + 1,5) as porcentaje_cuota  ";
                $cadena_sql.=" from  ";
                $cadena_sql.=" (select ((extract(year from age(fecha_salida::date ,fecha_ingreso::date))*360 +  ";
                $cadena_sql.=" extract(month from age(fecha_salida::date ,fecha_ingreso::date))*30 +  ";
                $cadena_sql.=" extract(day from age(fecha_salida::date ,fecha_ingreso::date)))) as dias,  ";
                $cadena_sql.=" nit_entidad, cedula_emp, fecha_ingreso, ";
                $cadena_sql.=" (select sum((extract(year from age(fecha_salida::date ,fecha_ingreso::date))*360 +  ";
                $cadena_sql.=" extract(month from age(fecha_salida::date ,fecha_ingreso::date))*30 +  ";
                $cadena_sql.=" extract(day from age(fecha_salida::date ,fecha_ingreso::date)))) as total  ";
                $cadena_sql.=" from cuotas_partes.entidades_cp where entidades_cp.cedula_emp ='" . $variable['cedula'] . "') as total ";
                $cadena_sql.=" from cuotas_partes.entidades_cp  ";
                $cadena_sql.=" where entidades_cp.cedula_emp ='" . $variable['cedula'] . "') as totales, ";
                $cadena_sql.=" cuotas_partes.entidades_cp  ";
                $cadena_sql.=" where totales.cedula_emp=entidades_cp.cedula_emp  ";
                $cadena_sql.=" and totales.nit_entidad=entidades_cp.nit_entidad  ";
                $cadena_sql.=" and totales.fecha_ingreso=entidades_cp.fecha_ingreso ";
                if ($variable['entidad'] != '') {
                    $cadena_sql.=" and entidades_cp.nombre_entidad='" . $variable['entidad'] . "'";
                }
                $cadena_sql.=" and entidades_cp.cedula_emp ='" . $variable['cedula'] . "' ";
                $cadena_sql.=" order by fecha_ingreso ";
                break;

            case "registro_empleados":
                $cadena_sql = " select ";
                $cadena_sql.=" emp_nro_iden, ";
                $cadena_sql.=" emp_nombre, ";
                $cadena_sql.=" emp_fecha_pen ";
                $cadena_sql.=" from peemp ";
                $cadena_sql.=" where emp_nro_iden='" . $variable['cedula'] . "' and emp_estado='A'";
                break;

            case "recaudos":
                $cadena_sql = " SELECT ";
                $cadena_sql.=" cuotas_partes.recaudos_cp.cedula_emp, ";
                $cadena_sql.=" cuotas_partes.recaudos_cp.nit_entidad, ";
                $cadena_sql.=" cuotas_partes.recaudos_cp.resolucion, ";
                $cadena_sql.=" cuotas_partes.recaudos_cp.fecha_resolucion, ";
                $cadena_sql.=" cuotas_partes.recaudos_cp.fecha_desde, ";
                $cadena_sql.=" cuotas_partes.recaudos_cp.fecha_hasta, ";
                $cadena_sql.=" cuotas_partes.recaudos_cp.valor_pagado, ";
                $cadena_sql.=" cuotas_partes.recaudos_cp.fecha_pago, ";
                $cadena_sql.=" cuotas_partes.recaudos_cp.medio_pago, ";
                $cadena_sql.=" cuotas_partes.entidades_cp.nombre_entidad ";
                $cadena_sql.=" from cuotas_partes.recaudos_cp, cuotas_partes.entidades_cp ";
                $cadena_sql.=" where cuotas_partes.recaudos_cp.cedula_emp = '" . $variable['cedula'] . "' ";
                $cadena_sql.=" and cuotas_partes.entidades_cp.nombre_entidad = '" . $variable['entidad'] . "' ";
                $cadena_sql.=" and cuotas_partes.recaudos_cp.cedula_emp = cuotas_partes.entidades_cp.cedula_emp ";
                $cadena_sql.=" and cuotas_partes.recaudos_cp.nit_entidad = cuotas_partes.entidades_cp.nit_entidad ";
                $cadena_sql.=" order by fecha_pago DESC ";
                break;

            case "datos_entidad_liquidar":
                $cadena_sql = "select cuotas_partes.entidades_cp.cedula_emp as cedula, ";
                $cadena_sql.=" entidades_cp.nombre_entidad as nombre_entidad, ";
                $cadena_sql.=" entidades_cp.fecha_ingreso,fecha_salida, dias,  ";
                $cadena_sql.=" substr(cast(((dias/total)*100) as text),1,position('.' in cast(((dias/total)*100) as text))) ||  ";
                $cadena_sql.=" substr(cast(((dias/total)*100) as text),position('.' in cast((dias/total)*100 as text)) + 1,5) as porcentaje_cuota  ";
                $cadena_sql.=" from  ";
                $cadena_sql.=" (select ((extract(year from age(fecha_salida::date ,fecha_ingreso::date))*360 +  ";
                $cadena_sql.=" extract(month from age(fecha_salida::date ,fecha_ingreso::date))*30 +  ";
                $cadena_sql.=" extract(day from age(fecha_salida::date ,fecha_ingreso::date)))) as dias,  ";
                $cadena_sql.=" nit_entidad, cedula_emp, fecha_ingreso, ";
                $cadena_sql.=" (select sum((extract(year from age(fecha_salida::date ,fecha_ingreso::date))*360 +  ";
                $cadena_sql.=" extract(month from age(fecha_salida::date ,fecha_ingreso::date))*30 +  ";
                $cadena_sql.=" extract(day from age(fecha_salida::date ,fecha_ingreso::date)))) as total  ";
                $cadena_sql.=" from cuotas_partes.entidades_cp where entidades_cp.cedula_emp ='" . $variable['cedula'] . "') as total ";
                $cadena_sql.=" from cuotas_partes.entidades_cp  ";
                $cadena_sql.=" where entidades_cp.cedula_emp ='" . $variable['cedula'] . "') as totales, ";
                $cadena_sql.=" cuotas_partes.entidades_cp  ";
                $cadena_sql.=" where totales.cedula_emp=entidades_cp.cedula_emp  ";
                $cadena_sql.=" and totales.nit_entidad=entidades_cp.nit_entidad  ";
                $cadena_sql.=" and totales.fecha_ingreso=entidades_cp.fecha_ingreso ";
                if ($variable['entidad'] != '') {
                    $cadena_sql.=" and entidades_cp.nombre_entidad='" . $variable['entidad'] . "'";
                }
                $cadena_sql.=" and entidades_cp.cedula_emp ='" . $variable['cedula'] . "' ";
                $cadena_sql.=" and nombre_entidad='" . $variable['entidad'] . "'";
                $cadena_sql.=" order by fecha_ingreso ";
                break;



            case "valor_ipc":
                $cadena_sql = " SELECT valor_ipc_cp from cuotas_partes.ipc_cp ";
                $cadena_sql.= " where annio_ipc_cp = '" . $variable . "' ";
                break;

            case "valor_sumafija":
                $cadena_sql = " SELECT valor_sumasfijas_cp from cuotas_partes.sumasfijas_cp ";
                $cadena_sql.="  where annio_sumasfijas_cp='" . $variable . "'";
                break;

            case "valor_mesada_inicial":
                $cadena_sql = " SELECT ";
                $cadena_sql.=" cedula_emp, mesada from cuotas_partes.entidades_cp ";
                $cadena_sql.=" where cedula_emp = '" . $variable['cedula'] . "' ";
                $cadena_sql.=" limit 1 ";
                break;

            case "valor_dtf":
                $cadena_sql = " SELECT ";
                $cadena_sql.=" valor_dtf_cp from cuotas_partes.dtf_cp ";
                $cadena_sql.=" where annio_dtf_cp = '" . $variable . "' ";
                break;

            case "guardarCuentaC":
                $cadena_sql = " INSERT INTO cuotas_partes.cuentac_registro_cp VALUES (   ";
                $cadena_sql.= " '" . $variable['consecutivo'] . "', ";
                $cadena_sql.= " '" . $variable['cc_pensionado'] . "', ";
                $cadena_sql.= " '" . $variable['nit_entidad'] . "',";
                $cadena_sql.= " " . $variable['saldo'] . ", ";
                $cadena_sql.= " '" . $variable['liq_fechain'] . "',";
                $cadena_sql.= " '" . $variable['liq_fechafin'] . "', ";
                $cadena_sql.= " " . $variable['liq_mesada'] . ",";
                $cadena_sql.= " " . $variable['liq_mesada_ad'] . ", ";
                $cadena_sql.= " " . $variable['liq_subtotal'] . ",";
                $cadena_sql.= " " . $variable['liq_incremento_salud'] . ", ";
                $cadena_sql.= " " . $variable['liq_total_sinteres'] . ",";
                $cadena_sql.= " " . $variable['liq_interes'] . ", ";
                $cadena_sql.= " " . $variable['liq_total_cinteres'] . ",";
                $cadena_sql.= " " . $variable['liq_total'] . ", ";
                $cadena_sql.= " '" . $variable['fecha_cc'] . "', ";
                $cadena_sql.= " " . $variable['consecutivo_contador'] . ");";
                break;

            case "consecutivo":
                $cadena_sql = " SELECT contador_consec_cc_cp FROM cuotas_partes.cuentac_registro_cp ";
                $cadena_sql.=" ORDER BY contador_consec_cc_cp DESC ";
                $cadena_sql.=" LIMIT 1";
                break;

            /*             * * Consultas SQL para el registro de cuentas de cobro manuales */


            case "consultarPrevisora":
                $cadena_sql = " SELECT prev_nombre, hlab_nitprev, prev_nit ";
                $cadena_sql.=" from cuotas_partes.cuotas_previsora, cuotas_partes.cuotas_hlaboral ";
                $cadena_sql.=" where prev_nit= hlab_nitprev and hlab_nro_identificacion = '" . $variable . "' ";
                break;

            case "consultarPrevFormulario":
                $cadena_sql = " SELECT prev_nombre, hlab_nitprev, prev_nit ";
                $cadena_sql.=" from cuotas_partes.cuotas_previsora, cuotas_partes.cuotas_hlaboral ";
                $cadena_sql.=" where prev_nit= hlab_nitprev and hlab_nro_identificacion = '" . $variable['cedula'] . "' ";
                $cadena_sql.=" AND hlab_nitprev='" . $variable['previsor'] . "' ";
                $cadena_sql.=" GROUP BY prev_nombre, hlab_nitprev, prev_nit ";
                break;

            case "consultarEmpleador":
                $cadena_sql = " SELECT prev_nombre, hlab_nro_identificacion, hlab_nitprev,hlab_nitenti, hlab_fingreso, hlab_fretiro ";
                $cadena_sql.=" FROM cuotas_partes.cuotas_hlaboral, cuotas_partes.cuotas_previsora ";
                $cadena_sql.=" WHERE hlab_nro_identificacion='" . $variable['cedula'] . "' ";
                $cadena_sql.=" AND prev_nit=hlab_nitenti ";
                $cadena_sql.=" AND hlab_nitprev='" . $variable['previsor'] . "' ";
                break;

            case "consultarHistoria":
                $cadena_sql = " SELECT DISTINCT hlab_nro_identificacion, hlab_nitenti, hlab_nitprev, hlab_fingreso, hlab_fretiro  ";
                $cadena_sql.=" FROM cuotas_partes.cuotas_hlaboral ";
                $cadena_sql.=" WHERE hlab_nro_identificacion='" . $variable['cedula'] . "' ";
                $cadena_sql.=" and hlab_nitprev='" . $variable['previsor'] . "' ";
                $cadena_sql.= "ORDER BY hlab_fretiro DESC ";
                break;


            case "insertarCManual":
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
                $cadena_sql.= " '" . $variable['fecha_generacion'] . "', ";
                $cadena_sql.= " '" . $variable['cedula'] . "', ";
                $cadena_sql.= " '" . $variable['empleador'] . "', ";
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

            case "consultarPrevisoraUnica":
                $cadena_sql = " SELECT prev_nombre, hlab_nitprev, prev_nit  ";
                $cadena_sql.=" FROM cuotas_partes.cuotas_previsora, cuotas_partes.cuotas_hlaboral  ";
                $cadena_sql.=" WHERE prev_nit= hlab_nitprev and hlab_nro_identificacion = '" . $variable . "' ";
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

