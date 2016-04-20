<?php

/* --------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
  --------------------------------------------------------------------------------------------------------------------------- */

if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/sql.class.php");

class sql_formSustituto extends sql {

    function cadena_sql($configuracion, $conexion, $opcion, $variable) {

        switch ($opcion) {

            case "insertarSustituto":
                $cadena_sql = " INSERT INTO cuotas_partes.cuotas_sustituto ";
                $cadena_sql.=" (sus_cedulapen, ";
                $cadena_sql.=" sus_cedulasus,  ";
                $cadena_sql.=" sus_nombresus,  ";
                $cadena_sql.=" sus_fdefuncion , ";
                $cadena_sql.=" sus_certificado_defuncion, ";
                $cadena_sql.=" sus_fcertificado_defuncion, ";
                $cadena_sql.=" sus_fnac_sustituto, ";
                $cadena_sql.=" sus_resol_sustitucion, ";
                $cadena_sql.=" sus_fresol_sustitucion, ";
                $cadena_sql.=" sus_parentezcosus, ";
                $cadena_sql.=" sus_generosus, ";
                $cadena_sql.=" sus_tutor, ";
                $cadena_sql.=" sus_cedula_tercero, ";
                $cadena_sql.=" sus_nombre_tercero, ";
                $cadena_sql.=" sus_tercero_sentencia, ";
                $cadena_sql.=" sus_fecha_tersentencia, ";
                $cadena_sql.=" sus_observacion, ";
                $cadena_sql.=" sus_estado, ";
                $cadena_sql.=" sus_fecha_registro) ";
                $cadena_sql.=" VALUES ( ";
                $cadena_sql.=" '" . $variable['cedula_pen'] . "', ";
                $cadena_sql.=" '" . $variable['cedula_sustituto'] . "', ";
                $cadena_sql.=" '" . $variable['nombre_sustituto'] . "', ";
                $cadena_sql.=" '" . $variable['fecha_muerte'] . "', ";
                $cadena_sql.=" '" . $variable['certificado_defuncion'] . "', ";
                $cadena_sql.=" '" . $variable['fecha_certificadod'] . "', ";
                $cadena_sql.=" '" . $variable['fecha_nacsustituto'] . "', ";
                $cadena_sql.=" '" . $variable['res_sustitucion'] . "', ";
                $cadena_sql.=" '" . $variable['fecha_res_sustitucion'] . "', ";
                $cadena_sql.=" '" . $variable['parentezco'] . "', ";
                $cadena_sql.=" '" . $variable['genero'] . "', ";
                $cadena_sql.=" '" . $variable['tutor'] . "', ";
                $cadena_sql.=" '" . $variable['cedula_tercero'] . "', ";
                $cadena_sql.=" '" . $variable['nombre_tercero'] . "', ";
                $cadena_sql.=" '" . $variable['tercero_sentencia'] . "', ";
                $cadena_sql.=" '" . $variable['fecha_tersentencia'] . "', ";
                $cadena_sql.=" '" . $variable['observacion'] . "', ";
                $cadena_sql.=" '" . $variable['estado'] . "', ";
                $cadena_sql.=" '" . $variable['registro'] . "') ";
                break;

            case "reporteSustituto":
                $cadena_sql = " SELECT sus_cedulapen,sus_cedulasus,sus_nombresus, ";
                $cadena_sql.=" sus_fdefuncion, sus_certificado_defuncion, ";
                $cadena_sql.=" sus_fcertificado_defuncion,sus_fnac_sustituto, ";
                $cadena_sql.=" sus_resol_sustitucion,sus_fresol_sustitucion, sus_parentezcosus, ";
                $cadena_sql.=" sus_generosus, ";
                $cadena_sql.=" sus_tutor, ";
                $cadena_sql.=" sus_cedula_tercero, ";
                $cadena_sql.=" sus_nombre_tercero, ";
                $cadena_sql.=" sus_tercero_sentencia, ";
                $cadena_sql.=" sus_fecha_tersentencia, ";
                $cadena_sql.=" sus_observacion ";
                $cadena_sql.=" FROM cuotas_partes.cuotas_sustituto ";
                $cadena_sql.=" WHERE sus_cedulapen='" . $variable['cedula'] . "' ";
                break;

            case "listadoSustitutos":
                $cadena_sql = " SELECT sus_cedulapen,sus_cedulasus,sus_nombresus, ";
                $cadena_sql.=" sus_fdefuncion, sus_certificado_defuncion, ";
                $cadena_sql.=" sus_fcertificado_defuncion,sus_fnac_sustituto, ";
                $cadena_sql.=" sus_resol_sustitucion,sus_fresol_sustitucion,sus_parentezcosus, ";
                $cadena_sql.=" sus_generosus, ";
                $cadena_sql.=" sus_tutor, ";
                $cadena_sql.=" sus_cedula_tercero, ";
                $cadena_sql.=" sus_nombre_tercero, ";
                $cadena_sql.=" sus_tercero_sentencia, ";
                $cadena_sql.=" sus_fecha_tersentencia, ";
                $cadena_sql.=" sus_observacion ";
                $cadena_sql.=" FROM cuotas_partes.cuotas_sustituto ";
                $cadena_sql.=" ORDER BY sus_cedulapen ASC ";
                break;

            case "datos_pensionado":
                $cadena_sql = " SELECT ";
                $cadena_sql.=" emp_nro_iden AS Cedula, ";
                $cadena_sql.=" to_char(emp_fecha_pen,'DD/MM/YYYY') AS FECHA_PENSION,";
                $cadena_sql.=" emp_nombre AS NOMBRE, ";
                $cadena_sql.=" to_char(EMP_FECHA_NAC,'DD/MM/YYYY') as FECHA_NAC, ";
                $cadena_sql.=" EMP_FALLECIDO as fallecido ";
                $cadena_sql.=" from peemp ";
                $cadena_sql.=" where emp_nro_iden='" . $variable['cedula'] . "' and emp_estado='A' ";
                break;

            case "datos_pensionado_pg":
                $cadena_sql = " SELECT ";
                $cadena_sql.=" dcp_nro_identificacion, ";
                $cadena_sql.=" dcp_fecha_pension as FECHA_PENSION";
                $cadena_sql.=" FROM cuotas_partes.cuotas_descripcion_cuotaparte ";
                $cadena_sql.=" WHERE dcp_nro_identificacion='" . $variable['cedula'] . "' ";
                break;


            default:
                $cadena_sql = "";
                break;
        }//fin switch

        return $cadena_sql;
    }

}

?>
