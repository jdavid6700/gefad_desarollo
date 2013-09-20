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

class sqlregistroParticipante extends sql {
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


            case "buscarConductor":
                $cadena_sql="SELECT ";
                $cadena_sql.="`idConductor` ";
                $cadena_sql.="FROM ";
                $cadena_sql.="".$configuracion["prefijo"]."conductor ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="identificacion='".$variable."' ";
                $cadena_sql.="AND ";
                $cadena_sql.="estado='0' ";
                $cadena_sql.="LIMIT 1";
                break;


            case "eliminarTemp":

                $cadena_sql="DELETE ";
                $cadena_sql.="FROM ";
                $cadena_sql.=$configuracion["prefijo"]."tempFormulario ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="`id_sesion` = '".$variable."' ";
                break;

            case "insertarTemp":
                $cadena_sql="INSERT INTO ";
                $cadena_sql.=$configuracion["prefijo"]."tempFormulario ";
                $cadena_sql.="( ";
                $cadena_sql.="`id_sesion`, ";
                $cadena_sql.="`formulario`, ";
                $cadena_sql.="`campo`, ";
                $cadena_sql.="`valor`, ";
                $cadena_sql.="`fecha` ";
                $cadena_sql.=") ";
                $cadena_sql.="VALUES ";

                foreach($_REQUEST as $clave => $valor) {
                    $cadena_sql.="( ";
                    $cadena_sql.="'".$configuracion['id_sesion']."', ";
                    $cadena_sql.="'".$variable['formulario']."', ";
                    $cadena_sql.="'".$clave."', ";
                    $cadena_sql.="'".$valor."', ";
                    $cadena_sql.="'".$variable['fecha']."' ";
                    $cadena_sql.="),";
                }

                $cadena_sql=substr($cadena_sql,0,(strlen($cadena_sql)-1));
                break;

            case "rescatarTemp":
                $cadena_sql="SELECT ";
                $cadena_sql.="`id_sesion`, ";
                $cadena_sql.="`formulario`, ";
                $cadena_sql.="`campo`, ";
                $cadena_sql.="`valor`, ";
                $cadena_sql.="`fecha` ";
                $cadena_sql.="FROM ";
                $cadena_sql.=$configuracion["prefijo"]."tempFormulario ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="id_sesion='".$configuracion["id_sesion"]."'";
                break;

            case "rescatarParticipante":
                $cadena_sql="SELECT ";
                $cadena_sql.="`idRegistrado`, ";
                $cadena_sql.="`nombre`, ";
                $cadena_sql.="`apellido`, ";
                $cadena_sql.="`identificacion`, ";
                $cadena_sql.="`codigo`, ";
                $cadena_sql.="`correo`, ";
                $cadena_sql.="`tipo`, ";
                $cadena_sql.="`fecha` ";
                
                $cadena_sql.="FROM ";
                $cadena_sql.=$configuracion["prefijo"]."registradoConferencia ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="`idRegistrado` =".$variable." ";
                break;

            case "insertarRegistro":
                $cadena_sql="INSERT INTO ";
                $cadena_sql.=$configuracion["prefijo"]."registradoConferencia ";
                $cadena_sql.="( ";
                $cadena_sql.="`idRegistrado`, ";
                $cadena_sql.="`nombre`, ";
                $cadena_sql.="`apellido`, ";
                $cadena_sql.="`identificacion`, ";
                $cadena_sql.="`codigo`, ";
                $cadena_sql.="`correo`, ";
                $cadena_sql.="`tipo`, ";
                $cadena_sql.="`fecha` ";
                $cadena_sql.=") ";
                $cadena_sql.="VALUES ";
                $cadena_sql.="( ";
                $cadena_sql.="NULL, ";
                $cadena_sql.="'".$variable['nombre']."', ";
                $cadena_sql.="'".$variable['apellido']."', ";
                $cadena_sql.="'".$variable['identificacion']."', ";
                $cadena_sql.="'".$variable['codigo']."', ";
                $cadena_sql.="'".$variable['correo']."', ";
                $cadena_sql.="'0', ";
                $cadena_sql.="'".time()."' ";
                $cadena_sql.=")";
                break;


            case "actualizarRegistro":
                $cadena_sql="UPDATE ";
                $cadena_sql.=$configuracion["prefijo"]."conductor ";
                $cadena_sql.="SET ";
                $cadena_sql.="`nombre` = '".$variable["nombre"]."', ";
                $cadena_sql.="`apellido` = '".$variable["apellido"]."', ";
                $cadena_sql.="`identificacion` = '".$variable["identificacion"]."', ";
                $cadena_sql.="`telefono` = '".$variable["telefono"]."' ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="`idConductor` =".$_REQUEST["registro"]." ";
                break;


        }
        //echo $cadena_sql."<hr>";
        return $cadena_sql;

    }
}
?>
