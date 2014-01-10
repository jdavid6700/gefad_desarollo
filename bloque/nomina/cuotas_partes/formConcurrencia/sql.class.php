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
                $cadena_sql.=" '" . $variable['resolucion_pension'] . "', ";
                $cadena_sql.=" '" . $variable['fecha_res_pension'] . "', ";
                $cadena_sql.=" '" . $variable['fecha_pension'] . "', ";
                $cadena_sql.=" '" . $variable['valor_mesada'] . "' ,";
                $cadena_sql.=" '" . $variable['valor_cuota'] . "' ,";
                $cadena_sql.=" '" . $variable['porcen_cuota'] . "' ,";
                $cadena_sql.=" '" . $variable['tipo_actoadmin'] . "' ,";
                $cadena_sql.=" '" . $variable['actoadmin'] . "' ,";
                $cadena_sql.=" '" . $variable['factoadmin'] . "' ,";
                $cadena_sql.=" '" . $variable['estado'] . "' ,";
                $cadena_sql.=" '" . $variable['registro'] . "');";
                break;

            case "consultarPrevisora":
                $cadena_sql = " SELECT prev_nombre, hlab_nitprev, prev_nit ";
                $cadena_sql.=" from cuotas_partes.cuotas_previsora, cuotas_partes.cuotas_hlaboral ";
                $cadena_sql.=" where prev_nit= hlab_nitprev and hlab_nro_identificacion = '" . $variable['cedula'] . "' ";
                break;

            case "consultarPrevisoraU":
                $cadena_sql = " SELECT prev_nombre, hlab_nitprev, prev_nit  ";
                $cadena_sql.=" FROM cuotas_partes.cuotas_previsora, cuotas_partes.cuotas_hlaboral  ";
                $cadena_sql.=" WHERE prev_nit= hlab_nitprev and hlab_nro_identificacion = '" . $variable['cedula'] . "' ";
                $cadena_sql.=" GROUP BY prev_nombre, hlab_nitprev, prev_nit ";
                break;

            case "consultarPrevFormulario":
                $cadena_sql = " SELECT prev_nombre, hlab_nitprev, prev_nit ";
                $cadena_sql.=" from cuotas_partes.cuotas_previsora, cuotas_partes.cuotas_hlaboral ";
                $cadena_sql.=" where prev_nit= hlab_nitprev and hlab_nro_identificacion = '" . $variable['cedula'] . "' ";
                $cadena_sql.=" AND hlab_nitprev='" . $variable['previsor'] . "' ";
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

            default:
                $cadena_sql = "";
                break;
        }//fin switch

        return $cadena_sql;
    }

}

?>
