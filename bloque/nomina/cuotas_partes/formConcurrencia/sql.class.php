<?php

/* --------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
  --------------------------------------------------------------------------------------------------------------------------- */

if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/sql.class.php");

class sql_formConcurrencia extends sql {

    function cadena_sql($configuracion, $conexion, $opcion, $variable) {

        switch ($opcion) {

            case "insertarDescripcionCP":
                $cadena_sql = " INSERT INTO cuotas_partes.cuotas_descripcion_cuotaparte ( ";
                $cadena_sql.=" dcp_nro_identificacion, ";
                $cadena_sql.=" dcp_nitent, ";
                $cadena_sql.=" dcp_nitprev, ";
                $cadena_sql.=" dcp_fecha_concurrencia, ";
                $cadena_sql.=" dcp_resol_pension, ";
                $cadena_sql.=" dcp_resol_pension_fecha, ";
                $cadena_sql.=" dcp_fecha_pension, ";
                $cadena_sql.=" dcp_valor_mesada, ";
                $cadena_sql.=" dcp_valor_cuota, ";
                $cadena_sql.=" dcp_porcen_cuota, ";
                $cadena_sql.=" dcp_tipo_actoadmin, ";
                $cadena_sql.=" dcp_actoadmin, ";
                $cadena_sql.=" dcp_factoadmin, ";
                $cadena_sql.=" dcp_estado, ";
                $cadena_sql.=" dcp_registro) VALUES ( ";
                $cadena_sql.=" '" . $variable['cedula'] . "' ,";
                $cadena_sql.=" '" . $variable['nit_entidad'] . "' ,";
                $cadena_sql.=" '" . $variable['nit_previsora'] . "' ,";
                $cadena_sql.=" '" . $variable['fecha_concurrencia'] . "' ,";
                $cadena_sql.=" '" . $variable['resolucion_pension']."', ";
                $cadena_sql.=" '" . $variable['fecha_res_pension']."', ";
                $cadena_sql.=" '" . $variable['fecha_pension']."', ";
                $cadena_sql.=" '" . $variable['valor_mesada'] . "' ,";
                $cadena_sql.=" '" . $variable['valor_cuota'] . "' ,";
                $cadena_sql.=" '" . $variable['porcen_cuota'] . "' ,";
                $cadena_sql.=" '" . $variable['tipo_actoadmin'] . "' ,";
                $cadena_sql.=" '" . $variable['actoadmin'] . "' ,";
                $cadena_sql.=" '" . $variable['factoadmin'] . "' ,";
                $cadena_sql.=" '" . $variable['estado'] . "' ,";
                $cadena_sql.=" '" . $variable['registro'] . "');";
                break;

            default:
                $cadena_sql = "";
                break;
        }//fin switch

        return $cadena_sql;
    }

}

?>
