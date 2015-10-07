<?php

/* --------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
  --------------------------------------------------------------------------------------------------------------------------- */

if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/sql.class.php");

class sql_formDTF extends sql {

    function cadena_sql($configuracion, $conexion, $opcion, $variable) {

        switch ($opcion) {

            case "insertarDTF":
                $cadena_sql = " INSERT INTO cuotas_partes.cuotas_indc_dtf (
                    dtf_norma, 
                    dtf_n_reso, 
                    dtf_fe_resolucion, 
                    dtf_fe_desde, 
                    dtf_fe_hasta, 
                    dtf_indi_ce,
                    dtf_estado,
                    dtf_fecha_registro) VALUES ( ";
                $cadena_sql.=" '" . $variable['Norma'] . "' ,";
                $cadena_sql.=" '" . $variable['Numero_resolucion'] . "',  ";
                $cadena_sql.=" '" . $variable['Fecha_resolucion'] . "',  ";
                $cadena_sql.=" '" . $variable['Fecha_vigencia_inicio'] . "',  ";
                $cadena_sql.=" '" . $variable['Fecha_vigencia_final'] . "',  ";
                $cadena_sql.=" " . $variable['Interes_DTF'] . ",";
                $cadena_sql.=" '" . $variable['estado_registro'] . "', ";
                $cadena_sql.=" '" . $variable['fecha_registro'] . "');";
                break;

            case "actualizarDTF":
                $cadena_sql = " UPDATE cuotas_partes.cuotas_indc_dtf SET ";
                $cadena_sql.=" dtf_norma = '" . $variable['Norma'] . "', ";
                $cadena_sql.=" dtf_n_reso = '" . $variable['Numero_resolucion'] . "', ";
                $cadena_sql.=" dtf_fe_resolucion = '" . $variable['Fecha_resolucion'] . "', ";
                $cadena_sql.=" dtf_fe_desde='" . $variable['Fecha_vigencia_inicio'] . "', ";
                $cadena_sql.=" dtf_fe_hasta='" . $variable['Fecha_vigencia_final'] . "', ";
                $cadena_sql.=" dtf_indi_ce=" . $variable['Interes_DTF'] . ", ";
                $cadena_sql.=" dtf_estado='" . $variable['estado_registro'] . "', ";
                $cadena_sql.=" dtf_fecha_registro='" . $variable['fecha_registro'] . "' ";
                $cadena_sql.=" WHERE dtf_serial='" . $variable['serial'] . "' ";
                break;

            case "Veriperiodo":
                $cadena_sql = "SELECT dtf_periodo ";
                $cadena_sql.=" FROM cuotas_partes.cuotas_indc_dtf;
            ";
                break;

            case "Consultar":
                $cadena_sql = "SELECT * ";
                $cadena_sql.=" FROM cuotas_partes.cuotas_indc_dtf ";
                $cadena_sql.=" ORDER BY dtf_fe_hasta DESC;
            ";
                break;

            case "periodo_ante":
                $cadena_sql = " INSERT INTO cuotas_partes.cuotas_indc_dtf
                        (dtf_norma,
                        dtf_n_reso,
                        dtf_fe_resolucion,
                        dtf_indi_ce,
                        dtf_estado,
                        dtf_fecha_registro) VALUES (";
                $cadena_sql.=" '" . $variable['Norma'] . "', ";
                $cadena_sql.=" '" . $variable['Numero_resolucion'] . "', ";
                $cadena_sql.=" '" . $variable['Fecha_resolucion'] . "', ";
                $cadena_sql.=" '" . $variable['Interes_DTF'] . "', ";
                $cadena_sql.=" '" . $variable['estado_registro'] . "', ";
                $cadena_sql.=" '" . $variable['fecha_registro'] . "' );
                        ";
                break;

            default:
                $cadena_sql = "";
                break;
        }//fin switch

        return $cadena_sql;
    }

}

?>
