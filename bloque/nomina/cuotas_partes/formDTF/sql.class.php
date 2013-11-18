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
                    dtf_periodo, 
                    dtf_n_reso, 
                    dtf_fe_resolucion, 
                    dtf_fe_desde, 
                    dtf_fe_hasta, 
                    dtf_indi_ce,
                    dtf_estado,
                    dtf_fecha_registro) VALUES ( ";
                $cadena_sql.=" '" . $variable['Anio_registrado'] . "' ,";
                $cadena_sql.=" " . $variable['Numero_resolucion'] . ",  ";
                $cadena_sql.=" '" . $variable['Fecha_resolucion'] . "',  ";
                $cadena_sql.=" '" . $variable['Fecha_vigencia_inicio'] . "',  ";
                $cadena_sql.=" '" . $variable['Fecha_vigencia_final'] . "',  ";
                $cadena_sql.=" " . $variable['Interes_DTF'] . ",";
                $cadena_sql.=" '" . $variable['estado_registro'] . "', ";
                $cadena_sql.=" '" . $variable['fecha_registro'] . "');";
                break;

            case "Veriperiodo":
                $cadena_sql = "SELECT dtf_periodo ";
                $cadena_sql.=" FROM cuotas_partes.cuotas_indc_dtf;";
                break;

            case "Consultar":
                $cadena_sql = "SELECT *  ";
                $cadena_sql.=" FROM cuotas_partes.cuotas_indc_dtf ";
                $cadena_sql.=" ORDER BY dtf_periodo ASC;  ";
                break;

            case "periodo_ante":
                $cadena_sql = " INSERT INTO cuotas_partes.cuotas_indc_dtf 
                               (dtf_periodo,
                                dtf_n_reso,
                                dtf_fe_resolucion, 
                                dtf_indi_ce,
                                dtf_estado,
                                dtf_fecha_registro) VALUES (";
                $cadena_sql.=" '" . $variable['Anio_registrado'] . "' ,";
                $cadena_sql.=" '" . $variable['Numero_resolucion'] . "',";
                $cadena_sql.=" '" . $variable['Fecha_resolucion'] . "',  ";
                $cadena_sql.=" '" . $variable['Interes_DTF'] . "', ";
                $cadena_sql.=" '" . $variable['estado_registro'] . "', ";
                $cadena_sql.=" '" . $variable['fecha_registro'] . "');";
                break;

            default:
                $cadena_sql = "";
                break;
        }//fin switch

        return $cadena_sql;
    }

}

?>
