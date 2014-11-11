<?php

/* --------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
  --------------------------------------------------------------------------------------------------------------------------- */

if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/sql.class.php");

class sql_formIPC extends sql {

    function cadena_sql($configuracion, $conexion, $opcion, $variable) {

        switch ($opcion) {

            case "insertarIPC":
                if ($variable['Suma_fijas'] != "") {

                    $cadena_sql = " INSERT INTO cuotas_partes.cuotas_indc_ipc(ipc_fecha, ipc_indiceipc, ipc_sumas_Fijas,  ipc_estado_registro , ipc_fecha_registro) VALUES ( ";
                    $cadena_sql.=" '" . $variable['Fecha'] . "' ,";
                    $cadena_sql.=" '" . $variable['Indice_IPC'] . "',  ";
                    $cadena_sql.=" '" . $variable['Suma_fijas'] . "',  ";
                    $cadena_sql.=" '" . $variable['estado_registro'] . "',  ";
                    $cadena_sql.=" '" . $variable['fecha_registro'] . "' );";
                } else {
                    $cadena_sql = " INSERT INTO cuotas_partes.cuotas_indc_ipc(ipc_fecha, ipc_indiceipc, ipc_estado_registro , ipc_fecha_registro) VALUES ( ";
                    $cadena_sql.=" '" . $variable['Fecha'] . "' ,";
                    $cadena_sql.=" '" . $variable['Indice_IPC'] . "' ,";
                    $cadena_sql.=" '" . $variable['estado_registro'] . "' ,";
                    $cadena_sql.=" '" . $variable['fecha_registro'] . "' );";
                }
                break;

            case "actualizarIPC":
                       if ($variable['Suma_fijas'] != "") {
                    $cadena_sql = " UPDATE cuotas_partes.cuotas_indc_ipc SET ";
                    $cadena_sql.=" ipc_fecha='" . $variable['Fecha'] . "' ,";
                    $cadena_sql.=" ipc_indiceipc= '" . $variable['Indice_IPC'] . "', ";
                    $cadena_sql.=" ipc_sumas_Fijas ='" . $variable['Suma_fijas'] . "',  ";
                    $cadena_sql.=" ipc_estado_registro='" . $variable['estado_registro'] . "', ";
                    $cadena_sql.=" ipc_fecha_registro = '" . $variable['fecha_registro'] . "' ";
                    $cadena_sql.=" WHERE ipc_fecha= '" . $variable['Fecha'] . "' ";
                    $cadena_sql.=" AND ipc_serial= '" . $variable['serial'] . "' ";
                } else {
                    $cadena_sql = " UPDATE cuotas_partes.cuotas_indc_ipc SET ";
                    $cadena_sql.=" ipc_fecha='" . $variable['Fecha'] . "' ,";
                    $cadena_sql.=" ipc_indiceipc= '" . $variable['Indice_IPC'] . "', ";
                    $cadena_sql.=" ipc_estado_registro='" . $variable['estado_registro'] . "', ";
                    $cadena_sql.=" ipc_fecha_registro = '" . $variable['fecha_registro'] . "' ";
                    $cadena_sql.=" WHERE ipc_fecha= '" . $variable['Fecha'] . "' ";
                    $cadena_sql.=" AND ipc_serial= '" . $variable['serial'] . "' ";
                }
                break;

            case "VeriAnio":
                $cadena_sql = "SELECT ipc_fecha ";
                $cadena_sql.="  FROM cuotas_partes.cuotas_indc_ipc; ";
                break;

            case "Consultar":
                $cadena_sql = "SELECT ipc_fecha, ipc_indiceipc as ipc_indiceipc, ipc_sumas_fijas,ipc_serial   ";
                $cadena_sql.="FROM cuotas_partes.cuotas_indc_ipc ";
                $cadena_sql.="ORDER BY ipc_fecha DESC;  ";
                break;

            default:
                $cadena_sql = "";
                break;
        }//fin switch

        return $cadena_sql;
    }

}

?>
