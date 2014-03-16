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

            case "datos_pensionado":
                $cadena_sql = " SELECT ";
                $cadena_sql.=" emp_nro_iden AS Cedula, ";
                $cadena_sql.=" emp_nombre AS NOMBRE, ";
                $cadena_sql.=" emp_fecha_pen AS FECHA_PENSION ";
                $cadena_sql.=" from peemp ";
                $cadena_sql.=" where emp_nro_iden='" . $variable['cedula_emp'] . "' and emp_estado='A' ";
                break;

            case "datos_concurrencia":
                $cadena_sql = " SELECT dcp_nro_identificacion, ";
                $cadena_sql.=" dcp_nitent, ";
                $cadena_sql.=" dcp_nitprev, ";
                $cadena_sql.=" dcp_fecha_concurrencia, ";
                $cadena_sql.=" dcp_resol_pension_fecha, ";
                $cadena_sql.=" dcp_fecha_pension, ";
                $cadena_sql.=" dcp_valor_mesada, ";
                $cadena_sql.=" dcp_valor_cuota, ";
                $cadena_sql.=" dcp_porcen_cuota ";
                $cadena_sql.=" FROM cuotas_partes.cuotas_descripcion_cuotaparte ";
                $cadena_sql.=" WHERE dcp_nro_identificacion='" . $variable['cedula'] . "' ";
                $cadena_sql.=" AND dcp_nitprev='" . $variable['entidad'] . "' ";
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
                break;

            case "valor_sumafija":
                $cadena_sql = " SELECT ipc_sumas_fijas as suma_fija ";
                $cadena_sql.=" FROM cuotas_partes.cuotas_indc_ipc ";
                $cadena_sql.=" WHERE ipc_fecha='1999' ";
                $cadena_sql.=" AND ipc_estado_registro='1' ";
                break;

            case "valor_ipc":
                $cadena_sql = " SELECT ipc_indiceipc as valor_ipc ";
                $cadena_sql.=" FROM cuotas_partes.cuotas_indc_ipc ";
                $cadena_sql.=" WHERE ipc_fecha='1999' ";
                $cadena_sql.=" AND ipc_estado_registro='1' ";
                break;

            case "valor_dtf":
                $cadena_sql = " SELECT ipc_indiceipc as valor_ipc ";
                $cadena_sql.=" FROM cuotas_partes.cuotas_indc_ipc ";
                $cadena_sql.=" WHERE ipc_fecha='1999' ";
                $cadena_sql.=" AND ipc_estado_registro='1' ";
                break;

            case "consecutivo":
                $cadena_sql = " SELECT liq_consecutivo ";
                $cadena_sql.=" FROM cuotas_partes.cuotas_liquidacion ";
                $cadena_sql.=" ORDER BY liq_consecutivo DESC ";
                $cadena_sql.=" LIMIT 1 ";
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
                $cadena_sql.=" WHERE liq_cedula='" . $variable['cedula_emp'] . "' ";
                $cadena_sql.=" AND liq_nitprev='" . $variable['entidad_nit'] . "' ";
                $cadena_sql.=" AND liq_estado='ACTIVO' ";
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
                $cadena_sql.=" WHERE liq_cedula='" . $variable['cedula_emp'] . "' ";
                $cadena_sql.=" AND liq_nitprev='" . $variable['entidad_nit'] . "' ";
                $cadena_sql.=" AND liq_consecutivo='" . $variable['liq_consecutivo'] . "' ";
                $cadena_sql.=" AND liq_estado='ACTIVO' ";
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

