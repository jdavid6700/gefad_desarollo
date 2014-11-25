<?php

/* --------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
  --------------------------------------------------------------------------------------------------------------------------- */

if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/sql.class.php");

class sql_formPrevisora extends sql {

    function cadena_sql($configuracion, $conexion, $opcion, $variable) {

        switch ($opcion) {

            case "actualizarPrevisora":
                $cadena_sql = " UPDATE cuotas_partes.cuotas_previsora ";
                $cadena_sql.=" SET ";
                $cadena_sql.=" prev_nit='" . $variable['nit_previsora'] . "' , ";
                $cadena_sql.=" prev_nombre='" . $variable['nombre_previsora'] . "',";
                $cadena_sql.=" prev_habilitado_pago='" . $variable['estado'] . "',";
                $cadena_sql.=" prev_observacion='" . $variable['observacion'] . "' , ";
                $cadena_sql.=" prev_direccion='" . $variable['direccion'] . "', ";
                $cadena_sql.=" prev_departamento='" . $variable['departamento'] . "' ,  ";
                $cadena_sql.=" prev_ciudad='" . $variable['ciudad'] . "', ";
                $cadena_sql.=" prev_telefono='" . $variable['telefono'] . "', ";
                $cadena_sql.=" prev_responsable='" . $variable['responsable'] . "', ";
                $cadena_sql.=" prev_cargo='" . $variable['cargo'] . "', ";
                $cadena_sql.=" prev_otroc='" . $variable['otro_contacto'] . "', ";
                $cadena_sql.=" prev_cargooc='" . $variable['otro_cargo'] . "',   ";
                $cadena_sql.=" prev_correo1='" . $variable['correo1'] . "',  ";
                $cadena_sql.=" prev_correo2='" . $variable['correo2'] . "', ";
                $cadena_sql.=" prev_estado_registro='" . $variable['estado_registro'] . "', ";
                $cadena_sql.=" prev_fecha_registro='" . $variable['fecha_registro'] . "'  ";
                $cadena_sql.=" WHERE prev_serial='" . $variable['serial'] . "'";
                break;

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
                $cadena_sql.=" prev_fecha_registro, ";
                $cadena_sql.=" prev_serial ";
                $cadena_sql.=" FROM cuotas_partes.cuotas_previsora ";
                $cadena_sql.=" ORDER BY prev_nombre ASC ";
                break;

            case "consultarGeografia":
                $cadena_sql = " SELECT dep_nombre, mun_nombre ";
                $cadena_sql.=" FROM MNTGE.gemunicipio, MNTGE.gedepartamento ";
                $cadena_sql.=" WHERE mun_dep_cod = dep_cod";
                $cadena_sql.=" ORDER BY mun_nombre";
                break;

            case "consultarGeografiaDEP":
                $cadena_sql = "SELECT dep_nombre";
                $cadena_sql.=" FROM MNTGE.gedepartamento ";
                $cadena_sql.=" ORDER BY dep_nombre";
                break;

            case "consultarGeografiaMUN":
                $cadena_sql = " SELECT dep_nombre, mun_nombre ";
                $cadena_sql.=" FROM MNTGE.gemunicipio, MNTGE.gedepartamento ";
                $cadena_sql.=" WHERE mun_dep_cod = dep_cod";
                $cadena_sql.=" ORDER BY mun_nombre";
                break;

            default:
                $cadena_sql = "";
                break;
        }//fin switch

        return $cadena_sql;
    }

}

?>
