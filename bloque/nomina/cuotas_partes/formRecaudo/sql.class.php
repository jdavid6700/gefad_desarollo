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

            case "insertarHistoria":
                $cadena_sql = " INSERT INTO cuotas_partes.recaudos_cp VALUES (";
                $cadena_sql.=" '" . $variable['cedula'] . "' ,";
                $cadena_sql.=" '" . $variable['nit_entidad'] . "' ,";
                $cadena_sql.=" '" . $variable['resolucion'] . "' ,";
                $cadena_sql.=" '" . $variable['fecha_resolucion'] . "' ,";
                $cadena_sql.=" '" . $variable['fecha_desde'] . "' ,";
                $cadena_sql.=" '" . $variable['fecha_hasta'] . "' ,";
                $cadena_sql.=" '" . $variable['fecha_pago'] . "' ,";
                $cadena_sql.="  " . $variable['valor_pagado'] . " ,";
                $cadena_sql.=" '" . $variable['medio_pago'] . "' )";
                break;

            case "consultarEntidades":
                $cadena_sql = " SELECT prev_nombre, hlab_nitprev, prev_nit ";
                $cadena_sql.=" from cuotas_partes.cuotas_previsora, cuotas_partes.cuotas_hlaboral ";
                $cadena_sql.=" where prev_nit= hlab_nitprev and hlab_nro_identificacion = '" . $variable['cedula'] . "' ";
                break;

            case "consultarRecaudos":
                $cadena_sql = " SELECT ";
                $cadena_sql.=" recta_nitprev, ";
                $cadena_sql.=" prev_nombre, ";
                $cadena_sql.=" recta_consecu_cta, ";
                $cadena_sql.=" cob_ie_correspondencia, ";
                $cadena_sql.=" rec_resolucionop, ";
                $cadena_sql.=" rec_fecha_resolucion, ";
                $cadena_sql.=" recta_fechapago, ";
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
                $cadena_sql.=" cob_ts_interes, cob_interes, cob_tc_interes, cob_ie_correspondencia, cob_cedula";
                $cadena_sql.=" from cuotas_partes.cuotas_cobros ";
                $cadena_sql.=" where cob_cedula = '" . $variable['cedula'] . "' ";
                $cadena_sql.=" and cob_estado_cuenta = 'ACTIVA' ";
                $cadena_sql.=" order by cob_fgenerado ASC ";
                break;

            case "actualizarCobro":
                $cadena_sql = " UPDATE cuotas_partes.cuotas_cobros ";
                $cadena_sql.= " SET cob_estado_cuenta='INACTIVA' ";
                $cadena_sql.= " where cob_consecu_cta='" . $variable . "' ";
                break;

            case "registrarPago":
                $cadena_sql = " INSERT INTO cuotas_partes.cuotas_recaudos ";
                $cadena_sql.=" (rec_consecu_rec,  ";
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
                $cadena_sql.=" 'RC-0001-2013', ";
                $cadena_sql.=" '" . $variable['nit_empleador'] . "', ";
                $cadena_sql.=" '" . $variable['nit_previsional'] . "', ";
                $cadena_sql.=" '" . $variable['cedula_emp'] . "', ";
                $cadena_sql.=" '" . $variable['resolucion_OP'] . "', ";
                $cadena_sql.=" '" . $variable['fecha_resolucion'] . "', ";
                $cadena_sql.=" '" . $variable['fecha_pago'] . "', ";
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
                $cadena_sql.=" recta_estado, ";
                $cadena_sql.=" recta_fecha_registro) VALUES (";
                $cadena_sql.=" '" . $variable['consecutivo'] . "', ";
                $cadena_sql.=" 'RC-0001-2013', ";
                $cadena_sql.=" '" . $variable['cedula_emp'] . "', ";
                $cadena_sql.=" '" . $variable['nit_empleador'] . "', ";
                $cadena_sql.=" '" . $variable['nit_previsional'] . "', ";
                $cadena_sql.=" '" . $variable['total_recaudo'] . "', ";
                $cadena_sql.=" '" . $variable['fecha_pago'] . "', ";
                $cadena_sql.=" 'ACTIVO', ";
                $cadena_sql.=" '" . date("d/m/Y") . "' ) ; ";
                break;

            default:
                $cadena_sql = "";
                break;
        }//fin switch

        return $cadena_sql;
    }

}

?>
