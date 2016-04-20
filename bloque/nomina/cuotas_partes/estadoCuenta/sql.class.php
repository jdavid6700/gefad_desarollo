<?php

/* --------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
  --------------------------------------------------------------------------------------------------------------------------- */

if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/sql.class.php");

class sql_estadoCuenta extends sql {

    function cadena_sql($configuracion, $conexion, $opcion, $variable) {

        switch ($opcion) {

            case "insertarPrevisora":
                $cadena_sql = " INSERT INTO cuotas_partes.cuotas_previsora  (prev_nit,   prev_nombre,   prev_habilitado_pago,   ";
                $cadena_sql.=" prev_observacion,   prev_direccion,   prev_departamento,   prev_ciudad, prev_telefono,   prev_responsable, ";
                $cadena_sql.="prev_cargo,   prev_otroc,   prev_cargooc,   prev_correo1,   prev_correo2,prev_estado_registro, prev_fecha_registro)  VALUES (";
                $cadena_sql.=" '" . $variable['nit_previsora'] . "' ,";
                $cadena_sql.=" '" . $variable['nombre_previsora'] . "' ,";
                $cadena_sql.=" '" . $variable['estado'] . "' ,";
                $cadena_sql.=" '" . $variable['observacion'] . "' ,";
                $cadena_sql.=" '" . $variable['direccion'] . "' ,";
                $cadena_sql.=" '" . $variable['departamento'] . "' ,";
                $cadena_sql.=" '" . $variable['ciudad'] . "' ,";
                $cadena_sql.=" '" . $variable['telefono'] . "' ,";
                $cadena_sql.=" '" . $variable['responsable'] . "' ,";
                $cadena_sql.=" '" . $variable['cargo'] . "' ,";
                $cadena_sql.=" '" . $variable['otro_contacto'] . "' ,";
                $cadena_sql.=" '" . $variable['otro_cargo'] . "' ,";
                $cadena_sql.=" '" . $variable['correo1'] . "' ,";
                $cadena_sql.=" '" . $variable['correo2'] . "' ,";
                $cadena_sql.=" '" . $variable['estado_registro'] . "' ,";
                $cadena_sql.=" '" . $variable['fecha_registro'] . "' )";
                break;

            case "consultarPrevisora":
                $cadena_sql = " SELECT ";
                $cadena_sql.=" prev_nit,";
                $cadena_sql.=" prev_nombre, ";
                $cadena_sql.=" prev_habilitado_pago, ";
                $cadena_sql.=" prev_observacion, ";
                $cadena_sql.=" prev_direccion, ";
                $cadena_sql.=" prev_departamento, ";
                $cadena_sql.=" prev_ciudad, ";
                $cadena_sql.=" prev_telefono, ";
                $cadena_sql.=" prev_responsable, ";
                $cadena_sql.=" prev_cargo, ";
                $cadena_sql.=" prev_otroc, ";
                $cadena_sql.=" prev_cargooc, ";
                $cadena_sql.=" prev_correo1, ";
                $cadena_sql.=" prev_correo2, ";
                $cadena_sql.=" prev_fecha_registrO ";
                $cadena_sql.=" FROM cuotas_partes.cuotas_previsora ";
                $cadena_sql.=" ORDER BY prev_fecha_registro DESC ";
                break;

            default:
                $cadena_sql = "";
                break;
        }//fin switch

        return $cadena_sql;
    }

}

?>
