<?php

/* --------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
  --------------------------------------------------------------------------------------------------------------------------- */

if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/sql.class.php");

class sql_formHistoria extends sql {

    function cadena_sql($configuracion, $conexion, $opcion, $variable) {

        switch ($opcion) {

            case "insertarHistoria":
                $cadena_sql = " INSERT INTO cuotas_partes.cuotas_hlaboral ( ";
                $cadena_sql.=" hlab_nro_ingreso, ";
                $cadena_sql.=" hlab_nro_identificacion, ";
                $cadena_sql.=" hlab_nitenti, ";
                $cadena_sql.=" hlab_nitprev, ";
                $cadena_sql.=" hlab_fingreso, ";
                $cadena_sql.=" hlab_fretiro, ";
                $cadena_sql.=" hlab_horas, ";
                $cadena_sql.=" hlab_periodicidad, ";
                $cadena_sql.=" hlab_estado, ";
                $cadena_sql.=" hlab_registro) VALUES ( ";
                $cadena_sql.=" '" . $variable['nro_ingreso'] . "' ,";
                $cadena_sql.=" '" . $variable['cedula'] . "' ,";
                $cadena_sql.=" '" . $variable['nit_entidad'] . "' ,";
                $cadena_sql.=" '" . $variable['nit_previsora'] . "' ,";
                $cadena_sql.=" '" . $variable['fecha_ingreso'] . "' ,";
                $cadena_sql.=" '" . $variable['fecha_salida'] . "' ,";
                $cadena_sql.=" '" . $variable['horas_labor'] . "' ,";
                $cadena_sql.=" '" . $variable['periodo_labor'] . "' ,";
                $cadena_sql.=" '" . $variable['estado'] . "' ,";
                $cadena_sql.=" '" . $variable['registro'] . "' )";
                break;

            case "insertarEntidad":
                $cadena_sql = " INSERT INTO cuotas_partes.cuotas_entidad ( ";
                $cadena_sql.=" ent_nit_entidad, ";
                $cadena_sql.=" ent_ciudad, ";
                $cadena_sql.=" ent_direccion, ";
                $cadena_sql.=" ent_telefono, ";
                $cadena_sql.=" ent_contacto, ";
                $cadena_sql.=" ent_estado, ";
                $cadena_sql.=" ent_registro) VALUES ( ";
                $cadena_sql.=" '" . $variable['nit_entidad'] . "' ,";
                $cadena_sql.=" '" . $variable['ciudad_entidad'] . "' ,";
                $cadena_sql.=" '" . $variable['direccion_entidad'] . "' ,";
                $cadena_sql.=" '" . $variable['telefono_entidad'] . "' ,";
                $cadena_sql.=" '" . $variable['contacto_entidad'] . "' ,";
                $cadena_sql.=" '" . $variable['estado'] . "' ,";
                $cadena_sql.=" '" . $variable['registro'] . "')";
                break;

            case "insertarInterrupcion":
                $cadena_sql = " INSERT INTO cuotas_partes.cuotas_interrupciones( ";
                $cadena_sql.=" int_nro_interrupcion, ";
                $cadena_sql.=" int_nro_ingreso,  ";
                $cadena_sql.=" int_nro_identificacion, ";
                $cadena_sql.=" int_nitent, int_nitprev, ";
                $cadena_sql.=" int_dias,   int_fdesde, int_fhasta, ";
                $cadena_sql.=" int_num_certificado, int_fecha_cert, ";
                $cadena_sql.=" int_estado, int_registro) VALUES ( ";
                $cadena_sql.=" '" . $variable['nro_interrupcion'] . "', ";
                $cadena_sql.=" '" . $variable['nro_ingreso'] . "', ";
                $cadena_sql.=" '" . $variable['cedula'] . "', ";
                $cadena_sql.=" '" . $variable['nit_entidad'] . "', ";
                $cadena_sql.=" '" . $variable['entidad_previsora'] . "', ";
                $cadena_sql.=" '" . $variable['total_dias'] . "', ";
                $cadena_sql.=" '" . $variable['dias_nor_desde'] . "', ";
                $cadena_sql.=" '" . $variable['dias_nor_hasta'] . "', ";
                $cadena_sql.=" '" . $variable['num_certificado'] . "', ";
                $cadena_sql.=" '" . $variable['fecha_certificado'] . "', ";
                $cadena_sql.=" '" . $variable['estado'] . "', ";
                $cadena_sql.=" '" . $variable['registro'] . "' ) ";
                break;

            case "consultarPrevisora":
                $cadena_sql = " SELECT ";
                $cadena_sql.=" prev_nit, ";
                $cadena_sql.=" prev_nombre ";
                $cadena_sql.=" FROM cuotas_partes.cuotas_previsora ";
                $cadena_sql.=" ORDER BY prev_habilitado_pago ASC ";
                break;

            case "consultarHistoria":
                $cadena_sql = " SELECT ";
                $cadena_sql.=" hlab_nro_ingreso, ";
                $cadena_sql.=" hlab_nro_identificacion, ";
                $cadena_sql.=" hlab_nitenti, ";
                $cadena_sql.=" hlab_nitprev, ";
                $cadena_sql.=" hlab_fingreso, ";
                $cadena_sql.=" hlab_fretiro, ";
                $cadena_sql.=" hlab_horas, ";
                $cadena_sql.=" hlab_periodicidad, ";
                $cadena_sql.=" hlab_estado, ";
                $cadena_sql.=" hlab_registro ";
                $cadena_sql.=" FROM cuotas_partes.cuotas_hlaboral ";
                $cadena_sql.=" WHERE hlab_nro_identificacion = '" . $variable . "' ";
                break;

            default:
                $cadena_sql = "";
                break;
        }//fin switch

        return $cadena_sql;
    }

}

?>
