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

            case "actualizarHistoria":
                $cadena_sql = " UPDATE cuotas_partes.cuotas_hlaboral SET";
                $cadena_sql.=" hlab_nitenti='" . $variable['nit_entidad'] . "', ";
                $cadena_sql.=" hlab_nitprev='" . $variable['nit_previsora'] . "' ,";
                $cadena_sql.=" hlab_fingreso='" . $variable['fecha_ingreso'] . "' ,";
                $cadena_sql.=" hlab_fretiro='" . $variable['fecha_salida'] . "' ,";
                $cadena_sql.=" hlab_horas='" . $variable['horas_labor'] . "' ,";
                $cadena_sql.=" hlab_periodicidad='" . $variable['periodo_labor'] . "' ,";
                $cadena_sql.=" hlab_numcertificado='" . $variable['num_certificado'] . "' ,";
                $cadena_sql.=" hlab_fcertificado='" . $variable['fecha_certificado'] . "' ,";
                $cadena_sql.=" hlab_estado= '" . $variable['estado'] . "' ,";
                $cadena_sql.=" hlab_registro='" . $variable['registro'] . "' ";
                $cadena_sql.=" WHERE ";
                $cadena_sql.=" hlab_serial='" . $variable['serial'] . "' ";
                $cadena_sql.=" AND hlab_nro_ingreso='" . $variable['nro_ingreso'] . "' ";
                $cadena_sql.=" AND hlab_nro_identificacion='" . $variable['cedula'] . "' ";
                break;

            case "actualizarNitInterrupcion":
                $cadena_sql = " UPDATE cuotas_partes.cuotas_interrupciones SET";
                $cadena_sql.=" int_nitent='" . $variable['nit_entidad'] . "', ";
                $cadena_sql.=" int_nitprev='" . $variable['nit_previsora'] . "',";
                //$cadena_sql.=" int_estado= '" . $variable['estado'] . "',";
                $cadena_sql.=" int_registro='" . $variable['registro'] . "' ";
                $cadena_sql.=" WHERE ";
                $cadena_sql.=" int_nro_ingreso='" . $variable['nro_ingreso'] . "' ";
                $cadena_sql.=" AND int_nro_identificacion='" . $variable['cedula'] . "' ";
                break;

            case "inactivarInterrupcion":
                $cadena_sql = " UPDATE cuotas_partes.cuotas_interrupciones SET";
                $cadena_sql.=" int_estado='0',";
                $cadena_sql.=" int_registro='" . $variable['registro'] . "'";
                $cadena_sql.=" WHERE ";
                $cadena_sql.=" int_nro_ingreso='" . $variable['nro_ingreso'] . "' ";
                $cadena_sql.=" AND int_nro_identificacion='" . $variable['cedula'] . "' ";
                $cadena_sql.=" AND int_nitent='" . $variable['nit_entidad'] . "' ";
                $cadena_sql.=" AND int_nitprev='" . $variable['nit_previsora'] . "' ";
                break;

            case "actualizarInterrupcion":
                $cadena_sql = " UPDATE cuotas_partes.cuotas_interrupciones SET ";
                $cadena_sql.=" int_dias='" . $variable['total_dias'] . "', ";
                $cadena_sql.=" int_fdesde=" . (isset($variable['dias_nor_desde']) ? "'" . $variable['dias_nor_desde'] . "'" : 'NULL') . ", ";
                $cadena_sql.=" int_fhasta=" . (isset($variable['dias_nor_hasta']) ? "'" . $variable['dias_nor_hasta'] . "'" : 'NULL') . ", ";
                $cadena_sql.=" int_estado='" . $variable['estado'] . "', ";
                $cadena_sql.=" int_registro='" . $variable['registro'] . "' ";
                $cadena_sql.=" WHERE int_nitent='" . $variable['nit_entidad'] . "' ";
                $cadena_sql.=" AND int_nitprev='" . $variable['entidad_previsora'] . "' ";
                $cadena_sql.=" AND int_nro_interrupcion='" . $variable['nro_interrupcion'] . "' ";
                $cadena_sql.=" AND int_nro_ingreso='" . $variable['nro_ingreso'] . "' ";
                break;

            case "consultarSustituto":
                $cadena_sql = " SELECT sus_cedulasus, sus_nombresus, sus_fnac_sustituto, sus_fresol_sustitucion ";
                $cadena_sql.=" FROM cuotas_partes.cuotas_sustituto ";
                $cadena_sql.=" WHERE sus_cedulapen = '" . $variable . "' ";
                break;

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
                $cadena_sql.=" hlab_numcertificado, ";
                $cadena_sql.=" hlab_fcertificado, ";
                $cadena_sql.=" hlab_estado, ";
                $cadena_sql.=" hlab_registro) VALUES ( ";
                $cadena_sql.=" '" . $variable['nro_ingreso'] . "', ";
                $cadena_sql.=" '" . $variable['cedula'] . "', ";
                $cadena_sql.=" '" . $variable['nit_entidad'] . "', ";
                $cadena_sql.=" '" . $variable['nit_previsora'] . "', ";
                $cadena_sql.=" '" . $variable['fecha_ingreso'] . "', ";
                $cadena_sql.=" '" . $variable['fecha_salida'] . "', ";
                $cadena_sql.=" '" . $variable['horas_labor'] . "', ";
                $cadena_sql.=" '" . $variable['periodo_labor'] . "', ";
                $cadena_sql.=" '" . $variable['num_certificado'] . "', ";
                $cadena_sql.=" '" . $variable['fecha_certificado'] . "', ";
                $cadena_sql.=" '" . $variable['estado'] . "', ";
                $cadena_sql.=" '" . $variable['registro'] . "' )";
                break;

            case "insertarInterrupcion":
                $cadena_sql = " INSERT INTO cuotas_partes.cuotas_interrupciones( ";
                $cadena_sql.=" int_nro_interrupcion, ";
                $cadena_sql.=" int_nro_ingreso, ";
                $cadena_sql.=" int_nro_identificacion, ";
                $cadena_sql.=" int_nitent, int_nitprev, ";
                $cadena_sql.=" int_dias, int_fdesde, int_fhasta, ";
                // $cadena_sql.=" int_num_certificado, int_fecha_cert, ";
                $cadena_sql.=" int_estado, int_registro) VALUES ( ";
                $cadena_sql.=" '" . $variable['nro_interrupcion'] . "', ";
                $cadena_sql.=" '" . $variable['nro_ingreso'] . "', ";
                $cadena_sql.=" '" . $variable['cedula'] . "', ";
                $cadena_sql.=" '" . $variable['nit_entidad'] . "', ";
                $cadena_sql.=" '" . $variable['entidad_previsora'] . "', ";
                $cadena_sql.=" '" . $variable['total_dias'] . "', ";
                $cadena_sql.=" " . (isset($variable['dias_nor_desde']) ? "'" . $variable['dias_nor_desde'] . "'" : 'NULL') . ", ";
                $cadena_sql.=" " . (isset($variable['dias_nor_hasta']) ? "'" . $variable['dias_nor_hasta'] . "'" : 'NULL') . ", ";
                //$cadena_sql.=" '" . $variable['num_certificado'] . "', ";
                //$cadena_sql.=" '" . $variable['fecha_certificado'] . "', ";
                $cadena_sql.=" '" . $variable['estado'] . "', ";
                $cadena_sql.=" '" . $variable['registro'] . "' ) ";
                break;

            case "consultarPrevisora":
                $cadena_sql = " SELECT ";
                $cadena_sql.=" prev_nit, ";
                $cadena_sql.=" prev_nombre ";
                $cadena_sql.=" FROM cuotas_partes.cuotas_previsora ";
                //$cadena_sql.=" WHERE prev_habilitado_pago = 'ACTIVA' ";
                $cadena_sql.=" ORDER BY prev_nombre ASC ";
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
                $cadena_sql.=" hlab_numcertificado, ";
                $cadena_sql.=" hlab_fcertificado, ";
                $cadena_sql.=" hlab_estado, ";
                $cadena_sql.=" hlab_registro ";
                $cadena_sql.=" FROM cuotas_partes.cuotas_hlaboral ";
                $cadena_sql.=" WHERE hlab_nro_identificacion = '" . $variable . "' ";
                $cadena_sql.=" ORDER BY hlab_fingreso DESC ";
                break;

            case "reporteHistoria":
                $cadena_sql = " SELECT ";
                $cadena_sql.=" hlab_serial, ";
                $cadena_sql.=" hlab_nro_ingreso, ";
                $cadena_sql.=" hlab_nro_identificacion, ";
                $cadena_sql.=" hlab_nitenti, ";
                $cadena_sql.=" hlab_nitprev, ";
                $cadena_sql.=" prev_nombre, ";
                $cadena_sql.=" hlab_fingreso, ";
                $cadena_sql.=" hlab_fretiro, ";
                $cadena_sql.=" hlab_horas, ";
                $cadena_sql.=" hlab_periodicidad, ";
                $cadena_sql.=" hlab_numcertificado, ";
                $cadena_sql.=" hlab_fcertificado ";
                $cadena_sql.=" FROM cuotas_partes.cuotas_previsora, cuotas_partes.cuotas_hlaboral ";
                $cadena_sql.=" WHERE hlab_nro_identificacion = '" . $variable . "' ";
                $cadena_sql.=" AND prev_nit = hlab_nitprev ";
                $cadena_sql.=" ORDER BY hlab_fingreso ASC ";
                break;

            case "reporteHistoria2":
                $cadena_sql = " SELECT ";
                $cadena_sql.=" hlab_serial, ";
                $cadena_sql.=" hlab_nro_ingreso, ";
                $cadena_sql.=" hlab_nro_identificacion, ";
                $cadena_sql.=" hlab_nitenti, ";
                $cadena_sql.=" hlab_nitprev, ";
                $cadena_sql.=" prev_nombre as empleador, ";
                $cadena_sql.=" hlab_fingreso, ";
                $cadena_sql.=" hlab_fretiro, ";
                $cadena_sql.=" hlab_horas, ";
                $cadena_sql.=" hlab_periodicidad, ";
                $cadena_sql.=" hlab_numcertificado, ";
                $cadena_sql.=" hlab_fcertificado ";
                $cadena_sql.=" FROM cuotas_partes.cuotas_previsora, cuotas_partes.cuotas_hlaboral ";
                $cadena_sql.=" WHERE hlab_nro_identificacion = '" . $variable . "' ";
                $cadena_sql.=" AND prev_nit = hlab_nitenti ";
                $cadena_sql.=" ORDER BY hlab_fingreso ASC ";
                break;

            case "nombre_empleador":
                $cadena_sql.=" SELECT prev_nombre ";
                $cadena_sql.=" FROM cuotas_partes.cuotas_previsora ";
                $cadena_sql.=" WHERE prev_nit = '" . $variable . "' ";
                break;

            case "reporteInterrupcion":
                $cadena_sql = " SELECT ";
                $cadena_sql.=" int_nro_ingreso, ";
                $cadena_sql.=" int_nro_interrupcion, ";
                $cadena_sql.=" int_nitent, ";
                $cadena_sql.=" int_nitprev, ";
                $cadena_sql.=" int_fdesde, ";
                $cadena_sql.=" int_fhasta, ";
                $cadena_sql.=" int_dias, ";
                $cadena_sql.=" int_num_certificado, ";
                $cadena_sql.=" int_fecha_cert, ";
                $cadena_sql.=" int_nro_identificacion, ";
                $cadena_sql.=" int_serial ";
                $cadena_sql.=" FROM cuotas_partes.cuotas_interrupciones ";
                $cadena_sql.=" WHERE int_nro_identificacion = '" . $variable . "' ";
                $cadena_sql.=" AND int_estado='1'";
                $cadena_sql.=" ORDER BY int_fdesde ASC ";
                break;

            case "reporteInterrupcionEntidad":
                $cadena_sql = " SELECT ";
                $cadena_sql.=" int_nro_ingreso, ";
                $cadena_sql.=" int_nro_interrupcion, ";
                $cadena_sql.=" int_nitent, ";
                $cadena_sql.=" int_nitprev, ";
                $cadena_sql.=" int_fdesde, ";
                $cadena_sql.=" int_fhasta, ";
                $cadena_sql.=" int_dias, ";
                $cadena_sql.=" int_num_certificado, ";
                $cadena_sql.=" int_fecha_cert, ";
                $cadena_sql.=" int_nro_identificacion, ";
                $cadena_sql.=" int_serial ";
                $cadena_sql.=" FROM cuotas_partes.cuotas_interrupciones ";
                $cadena_sql.=" WHERE int_nro_identificacion = '" . $variable['cedula'] . "' ";
                $cadena_sql.=" AND int_nitent = '" . $variable['empleador'] . "' ";
                $cadena_sql.=" AND int_nitprev = '" . $variable['previsora'] . "' ";
                $cadena_sql.=" AND int_estado='1' ";
                $cadena_sql.=" ORDER BY int_fdesde ASC ";
                break;

            case "reporteInterrupcionEntidad_Modificar":
                $cadena_sql = " SELECT ";
                $cadena_sql.=" int_nro_ingreso, ";
                $cadena_sql.=" hlab_nro_ingreso, ";
                $cadena_sql.=" int_nro_interrupcion, ";
                $cadena_sql.=" hlab_fingreso, ";
                $cadena_sql.=" hlab_fretiro, ";
                $cadena_sql.=" int_nitent, ";
                $cadena_sql.=" int_nitprev, ";
                $cadena_sql.=" int_nro_identificacion, ";
                $cadena_sql.=" int_serial, ";
                $cadena_sql.=" hlab_serial ";
                $cadena_sql.=" FROM cuotas_partes.cuotas_interrupciones, cuotas_partes.cuotas_hlaboral ";
                $cadena_sql.=" WHERE hlab_nro_identificacion = int_nro_identificacion ";
                $cadena_sql.=" AND cast(int_nro_ingreso as character varying) = hlab_nro_ingreso ";
                $cadena_sql.=" AND hlab_nitprev = int_nitprev ";
                $cadena_sql.=" AND hlab_nitenti = int_nitent ";
                $cadena_sql.=" AND int_nro_identificacion = '" . $variable['cedula'] . "' ";
                $cadena_sql.=" AND int_nitent = '" . $variable['empleador'] . "' ";
                $cadena_sql.=" AND int_nitprev = '" . $variable['previsora'] . "' ";
                $cadena_sql.=" AND int_nro_ingreso = '" . $variable['h_ingreso'] . "' ";
                $cadena_sql.=" AND int_estado='1' ";
                $cadena_sql.=" ORDER BY int_fdesde ASC ";
                break;

            case "reporteDescripcion":
                $cadena_sql = " SELECT dcp_nro_identificacion, ";
                $cadena_sql.= " dcp_nitprev, ";
                $cadena_sql.= " dcp_resol_pension_fecha, ";
                $cadena_sql.= " dcp_resol_pension, ";
                $cadena_sql.= " dcp_fecha_pension, ";
                $cadena_sql.= " dcp_factoadmin, ";
                $cadena_sql.= " dcp_actoadmin, ";
                $cadena_sql.= " dcp_fecha_concurrencia, ";
                $cadena_sql.= " dcp_valor_mesada, ";
                $cadena_sql.= " dcp_porcen_cuota, ";
                $cadena_sql.= " dcp_valor_cuota, ";
                $cadena_sql.= " dcp_observacion ";
                $cadena_sql.= " from cuotas_partes.cuotas_descripcion_cuotaparte ";
                $cadena_sql.= " where dcp_nro_identificacion = '" . $variable . "' ";
                break;

            case "consultarConsecutivo":
                $cadena_sql = " SELECT ";
                $cadena_sql.=" hlab_nro_ingreso, ";
                $cadena_sql.=" hlab_nro_identificacion, ";
                $cadena_sql.=" hlab_nitenti, ";
                $cadena_sql.=" prev_nombre, ";
                $cadena_sql.=" hlab_fingreso, ";
                $cadena_sql.=" hlab_fretiro, ";
                $cadena_sql.=" hlab_horas, ";
                $cadena_sql.=" hlab_periodicidad ";
                $cadena_sql.=" FROM cuotas_partes.cuotas_previsora, cuotas_partes.cuotas_hlaboral ";
                $cadena_sql.=" WHERE hlab_nro_identificacion = '" . $variable['cedula'] . "' ";
                $cadena_sql.=" AND hlab_nitenti = '" . $variable['nit_entidad'] . "' ";
                $cadena_sql.=" AND hlab_nitprev = '" . $variable['nit_previsora'] . "' ";
                $cadena_sql.=" AND prev_nit = hlab_nitprev ";
                $cadena_sql.=" ORDER by hlab_nro_ingreso DESC ";
                $cadena_sql.=" LIMIT 1 ";
                break;

            case "consultarConsecutivoI":
                $cadena_sql = " select int_nro_interrupcion, int_nro_ingreso, int_nro_identificacion ";
                $cadena_sql.= " from cuotas_partes.cuotas_interrupciones ";
                $cadena_sql.= " where int_nro_identificacion = '" . $variable['cedula'] . "'";
                $cadena_sql.=" AND int_estado='1' ";
                $cadena_sql.= " order by int_nro_interrupcion DESC ";
                break;

            case "consultarDatosBasicos":
                $cadena_sql = " SELECT ";
                $cadena_sql.=" emp_nro_iden, emp_nombre AS NOMBRE ";
                $cadena_sql.=" FROM peemp ";
                $cadena_sql.=" WHERE emp_nro_iden = " . $variable . " ";
                //$cadena_sql.=" AND emp_estado = 'A' ";
                $cadena_sql.=" ORDER BY emp_nro_iden ASC ";
                break;


            default:
                $cadena_sql = "";
                break;
        }//fin switch

        return $cadena_sql;
    }

}

?>
