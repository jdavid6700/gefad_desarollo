<?php

/* --------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
  --------------------------------------------------------------------------------------------------------------------------- */

if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/sql.class.php");

class sql_formSalario extends sql {

    function cadena_sql($configuracion, $conexion, $opcion, $variable) {

        switch ($opcion) {

            case "insertarSalario":
                $cadena_sql = "INSERT INTO cuotas_partes.cuotas_salario ( ";
                $cadena_sql.= " salario_norma, ";
                $cadena_sql.= " salario_numero, ";
                $cadena_sql.= " salario_anio, ";
                $cadena_sql.= " salario_vdesde, ";
                $cadena_sql.= " salario_vhasta, ";
                $cadena_sql.= " salario_monto, ";
                $cadena_sql.= " salario_estado, ";
                $cadena_sql.= " salario_registro) VALUES ";
                $cadena_sql.= " ('" . $variable['salario_norma'] . "', ";
                $cadena_sql.= " '" . $variable['salario_numero'] . "', ";
                $cadena_sql.= " '" . $variable['salario_anio'] . "', ";
                $cadena_sql.= " '" . $variable['salario_vdesde'] . "', ";
                $cadena_sql.= " '" . $variable['salario_vhasta'] . "', ";
                $cadena_sql.= " '" . $variable['salario_monto'] . "', ";
                $cadena_sql.= " '" . $variable['salario_estado'] . "', ";
                $cadena_sql.= " '" . $variable['salario_registro'] . "') ";
                break;

            case "VeriAnio":
                
                
                $cadena_sql = "SELECT salario_anio ";
                $cadena_sql.="  FROM cuotas_partes.cuotas_salario; ";
                break;

            case "Consultar":
                $cadena_sql = "SELECT *  ";
                $cadena_sql.="FROM cuotas_partes.cuotas_salario ";
                $cadena_sql.="ORDER BY salario_anio DESC;  ";
                break;

            default:
                $cadena_sql = "";
                break;
        }//fin switch

        return $cadena_sql;
    }

}

?>
