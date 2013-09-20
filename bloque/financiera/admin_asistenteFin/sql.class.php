<?php
/***************************************************************************
 *   PHP Application Framework Version 10                                  *
 *   Copyright (c) 2003 - 2009                                             *
 *   Teleinformatics Technology Group de Colombia                          *
 *   ttg@ttg.com.co                                                        *
 *                                                                         *
****************************************************************************/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sql.class.php");
//Para evitar redefiniciones de clases el nombre de la clase del archivo sqle debe corresponder al nombre del bloque
//en camel case precedida por la palabra sql

class sqladminAsistente extends sql {
    function cadena_sql($configuracion,$tipo,$variable="") {

        switch($tipo) {

            case "iniciarTransaccion":
                $cadena_sql="START TRANSACTION";
                break;

            case "finalizarTransaccion":
                $cadena_sql="COMMIT";
                break;

            case "cancelarTransaccion":
                $cadena_sql="ROLLBACK";
                break;

        }
        //echo $cadena_sql."<hr>";
        return $cadena_sql;

    }
}
?>
