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
                    $cadena_sql.=" '" . $variable['Suma_Fijas'] . "',  ";
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

            case "VeriAnio":
                $cadena_sql = "SELECT ipc_fecha ";
                $cadena_sql.="  FROM cuotas_partes.cuotas_indc_ipc; ";
                break;

            case "Consultar":
                $cadena_sql = "SELECT ipc_fecha, to_char(ipc_indiceipc,'0.99999999'), ipc_sumas_fijas as ipc_indiceipc  ";
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
