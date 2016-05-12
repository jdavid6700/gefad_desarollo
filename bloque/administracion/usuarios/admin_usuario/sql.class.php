<?php

/* --------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
  --------------------------------------------------------------------------------------------------------------------------- */

if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/sql.class.php");

class sql_adminUsuario extends sql {

    function cadena_sql($configuracion, $conexion, $opcion, $variable = "") {

        switch ($opcion) {
            case "areaUsuario":
                $cadena_sql = "SELECT DISTINCT";
                $cadena_sql.=" id_usuario ID_ADMIN,";
                $cadena_sql.=" id_area AREA";
                $cadena_sql.=" FROM ";
                $cadena_sql.= $configuracion["prefijo"] . "registrado_subsistema ";
                $cadena_sql.=" WHERE ";
                $cadena_sql.=" id_usuario=" . $variable['id_us'];
                break;

            case "inserta_usuario":
                $cadena_sql = "INSERT INTO ";
                $cadena_sql.= $configuracion["prefijo"] . "registrado ";
                $cadena_sql.="(";
                $cadena_sql.="nombre, ";
                $cadena_sql.="apellido, ";
                $cadena_sql.="correo, ";
                $cadena_sql.="telefono1, ";
                $cadena_sql.="extensiones1, ";
                $cadena_sql.="usuario, ";
                $cadena_sql.="clave, ";
                $cadena_sql.="celular, ";
                $cadena_sql.="identificacion,";
                $cadena_sql.="fecha_registro,";
                $cadena_sql.="estado";
                $cadena_sql.=") ";
                $cadena_sql.="VALUES (";
                $cadena_sql.="'" . $variable['nombres'] . "', ";
                $cadena_sql.="'" . $variable['apellidos'] . "', ";
                $cadena_sql.="'" . $variable['correo'] . "', ";
                $cadena_sql.="'" . $variable['telefono'] . "', ";
                $cadena_sql.="'" . $variable['ext'] . "', ";
                $cadena_sql.="'" . $variable['nick'] . "', ";
                $cadena_sql.="'" . $variable['contrasena'] . "', ";
                $cadena_sql.="'" . $variable['celular'] . "',";
                $cadena_sql.="'" . $variable['num_id'] . "',";
                $cadena_sql.="'" . $variable['fecha_registro'] . "',";
                $cadena_sql.="'" . $variable['estado'] . "'";
                $cadena_sql.=")";
                break;

            case "inserta_integrante_proyecto":

                $cadena_sql = "INSERT INTO ";
                $cadena_sql.= $configuracion["prefijo"] . "integrante_proyecto ";
                $cadena_sql.="(";
                $cadena_sql.="id_proyecto, ";
                $cadena_sql.="id_usuario, ";
                $cadena_sql.="id_rol, ";
                $cadena_sql.="fecha_ingreso, ";
                $cadena_sql.="estado";
                $cadena_sql.=") ";
                $cadena_sql.="VALUES (";
                $cadena_sql.="'" . $variable[3] . "', ";
                $cadena_sql.="'" . $variable[11] . "', ";
                $cadena_sql.="'" . $variable[8] . "', ";
                $cadena_sql.="'" . $variable[4] . "', ";
                $cadena_sql.="'1'";
                $cadena_sql.=")";
                break;

            case "inserta_registrado_subsistema":

                $cadena_sql = "INSERT INTO ";
                $cadena_sql.= $configuracion["prefijo"] . "registrado_subsistema ";
                $cadena_sql.="(";
                $cadena_sql.=" id_usuario, ";
                $cadena_sql.=" id_subsistema, ";
                $cadena_sql.=" estado, ";
                $cadena_sql.=" id_dependencia, ";
                $cadena_sql.=" fecha_registro, ";
                $cadena_sql.=" fecha_fin";
                $cadena_sql.=") ";
                $cadena_sql.="VALUES (";
                $cadena_sql.="'" . $variable['cod_usuario'] . "', ";
                $cadena_sql.="'" . $variable['cod_rol'] . "', ";
                $cadena_sql.="'1',";
                $cadena_sql.="'" . $variable['cod_area'] . "', ";
                $cadena_sql.="'" . $variable['fecha_registro'] . "', ";
                $cadena_sql.="'" . $variable['fecha_inactiva'] . "'";
                $cadena_sql.=")";
                break;

            case "busqueda_usuario_xnombre":

                $cadena_sql = "SELECT ";
                $cadena_sql.= "id_usuario ID_US, ";
                $cadena_sql.= "nombre NOM_USU, ";
                $cadena_sql.= "apellido APEL_USU, ";
                $cadena_sql.= "correo CORREO, ";
                $cadena_sql.= "telefono1 TEL, ";
                $cadena_sql.= "extensiones1 EXT, ";
                $cadena_sql.= "usuario NICK, ";
                $cadena_sql.= "celular CEL, ";
                $cadena_sql.= "identificacion DOC_USU ";
                $cadena_sql.= "FROM ";
                $cadena_sql.= $configuracion["prefijo"] . "registrado ";
                $cadena_sql.= "WHERE usuario = '" . $variable . "'";
                break;

            case "usuario":
                $cadena_sql = "SELECT ";
                $cadena_sql.= "id_usuario ID_US, ";
                $cadena_sql.= "nombre NOMBRE, ";
                $cadena_sql.= "apellido APELLIDO, ";
                $cadena_sql.= "correo MAIL, ";
                $cadena_sql.= "telefono1 TEL, ";
                $cadena_sql.= "extensiones1 EXT, ";
                $cadena_sql.= "usuario NICK, ";
                $cadena_sql.= "celular CEL, ";
                $cadena_sql.= "identificacion IDENT, ";
                $cadena_sql.= "clave PASS ";
                $cadena_sql.= "FROM ";
                $cadena_sql.= $configuracion["prefijo"] . "registrado ";
                $cadena_sql.= "WHERE ";
                $cadena_sql.= "id_usuario = ";
                $cadena_sql.= $variable;
                break;

            case "usuarios_todos":
                $variable['criterio_busqueda'] = (isset($variable['criterio_busqueda']) ? $variable['criterio_busqueda'] : '');
                $cadena_sql = "SELECT DISTINCT ";
                $cadena_sql.= "reg.id_usuario ID_US, ";
                $cadena_sql.= "reg.usuario NICK, ";
                $cadena_sql.= "reg.identificacion IDENT, ";
                $cadena_sql.= "concat(reg.nombre,' ',reg.apellido) NOMBRE ";
                $cadena_sql.= "FROM ";
                $cadena_sql.= $configuracion["prefijo"] . "registrado reg ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="reg.id_usuario > 0 ";
                //  var_dump($variable);

                if ($variable['criterio_busqueda'] == 'NOMBRE') {
                    $cadena_sql.="AND ";
                    $cadena_sql.=" concat(reg.nombre,reg.apellido) LIKE '%" . $variable['valor'] . "%'";
                } elseif ($variable['criterio_busqueda'] == 'ID') {
                    $cadena_sql.="AND ";
                    $cadena_sql.=" reg.identificacion LIKE '%" . $variable['valor'] . "%'";
                } elseif ($variable['criterio_busqueda'] == 'COD_US') {
                    $cadena_sql.="AND ";
                    $cadena_sql.=" reg.id_usuario =" . $variable['valor'];
                }
                break;


            case "roles":
                $cadena_sql = "SELECT ";
                $cadena_sql.="reg.id_usuario ID_US, ";
                $cadena_sql.="reg.fecha_registro F_INI, ";
                $cadena_sql.="reg.fecha_fin F_FIN, ";
                $cadena_sql.= "reg.estado ESTADO, ";
                $cadena_sql.= "sub.id_subsistema ID_ROL, ";
                $cadena_sql.= "sub.etiqueta ROL ";
                $cadena_sql.= "FROM ";
                $cadena_sql.= $configuracion["prefijo"] . "registrado_subsistema reg ";
                $cadena_sql.= "INNER JOIN ";
                $cadena_sql.= $configuracion["prefijo"] . "subsistema sub on reg.id_subsistema=sub.id_subsistema ";
                $cadena_sql.= "WHERE ";
                $cadena_sql.= "reg.id_usuario = $variable ";
                break;

            case "busqueda_rol":
                $cadena_sql = "SELECT ";
                $cadena_sql.="reg.id_usuario ID_US, ";
                $cadena_sql.="reg.id_subsistema ID_ROL, ";
                $cadena_sql.="reg.fecha_registro F_INI, ";
                $cadena_sql.="reg.fecha_fin F_FIN, ";
                $cadena_sql.="reg.estado EST ";
                $cadena_sql.="FROM ";
                $cadena_sql.=$configuracion["prefijo"] . "registrado_subsistema reg ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="reg.id_usuario='" . $variable['cod_usuario'] . "' ";
                $cadena_sql.="AND reg.id_subsistema='" . $variable['cod_rol'] . "' ";
                $cadena_sql.="AND reg.estado='1' ";
                break;

            case "listar_dep":

                $cadena_sql = "SELECT COD_DEPENDENCIA,";
                $cadena_sql.="NOMBRE_DEPENDENCIA ";
                $cadena_sql.="FROM ";
                $cadena_sql.="CO.CO_DEPENDENCIASSELECT ";
                break;

            case "buscar_dep":

                $cadena_sql = "SELECT DISTINCT ";
                $cadena_sql.="dep.id_dependencia ID_DEP, ";
                $cadena_sql.="dep.nombre DEPE ";
                $cadena_sql.="FROM ";
                $cadena_sql.=$configuracion["prefijo"] . "dependencia dep ";
                $cadena_sql.= "INNER JOIN ";
                $cadena_sql.= $configuracion["prefijo"] . "area area on dep.id_dependencia=area.id_dependencia ";
                $cadena_sql.= "AND area.id_area='" . $variable[0]['ID_AREA'] . "'";
                break;

            case "lista_area_dep":

                $cadena_sql = "SELECT DISTINCT ";
                $cadena_sql.="area.id_area ID_AREA, ";
                $cadena_sql.="area.nombre AREA ";
                $cadena_sql.="FROM ";
                $cadena_sql.= $configuracion["prefijo"] . "area area ";
                $cadena_sql.= "WHERE area.id_dependencia='" . $variable[0]['ID_DEP'] . "'";
                break;

            case "buscar_area":

                $cadena_sql = "SELECT DISTINCT ";
                $cadena_sql.="area.id_area ID_AREA, ";
                $cadena_sql.="area.nombre AREA ";
                $cadena_sql.="FROM ";
                $cadena_sql.= $configuracion["prefijo"] . "area area ";
                $cadena_sql.= "WHERE area.id_area='" . $variable[0]['ID_AREA'] . "'";
                break;

            case "insertar_rol":
                $cadena_sql = "INSERT INTO ";
                $cadena_sql.=$configuracion["prefijo"] . "registrado_subsistema ";
                $cadena_sql.="(id_usuario, ";
                $cadena_sql.="id_subsistema, ";
                $cadena_sql.="id_dependencia, ";
                $cadena_sql.="fecha_registro, ";
                $cadena_sql.="fecha_fin, ";
                $cadena_sql.="estado) ";
                $cadena_sql.=" VALUES ";
                $cadena_sql.="('" . $variable['cod_usuario'] . "',";
                $cadena_sql.="'" . $variable['cod_rol'] . "',";
                $cadena_sql.="'" . $variable['id_dependencia'] . "',";
                $cadena_sql.="'" . $variable['fecha_registro'] . "',";
                $cadena_sql.="'" . $variable['fecha_inactiva'] . "',";
                $cadena_sql.="'" . $variable['estado'] . "')";
                break;

            case "busqueda_etiqueta":
                $cadena_sql = "SELECT etiqueta ";
                $cadena_sql.="FROM ";
                $cadena_sql.=$configuracion["prefijo"] . "subsistema sub ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="sub.id_subsistema='" . $variable . "' ";
                break;

            case "actualizar_rol":
                $cadena_sql = "UPDATE ";
                $cadena_sql.=$configuracion["prefijo"] . "registrado_subsistema reg ";
                $cadena_sql.="SET ";
                $cadena_sql.="reg.fecha_fin='" . $variable['fecha_inactiva'] . "' ";
                $cadena_sql.=" WHERE ";
                $cadena_sql.="reg.id_usuario='" . $variable['cod_usuario'] . "' ";
                $cadena_sql.="AND reg.id_subsistema='" . $variable['cod_rol'] . "' ";
                $cadena_sql.="AND reg.estado='1'";
                break;

            case "inactivar_rol":
                $cadena_sql = "UPDATE ";
                $cadena_sql.=$configuracion["prefijo"] . "registrado_subsistema reg ";
                $cadena_sql.="SET ";
                $cadena_sql.="reg.estado='0' ";
                $cadena_sql.=" WHERE ";
                $cadena_sql.="reg.id_usuario='" . $variable['cod_usuario'] . "' ";
                $cadena_sql.="AND reg.id_subsistema='" . $variable['cod_rol'] . "' ";
                $cadena_sql.="AND reg.estado='1'";
                break;

            case "editar_usuario":
                $cadena_sql = "UPDATE ";
                $cadena_sql .= $configuracion["prefijo"] . "registrado ";
                $cadena_sql .= "SET ";
                $cadena_sql .= "`nombre`='" . $variable[1] . "', ";
                $cadena_sql .= "`apellido`='" . $variable[2] . "', ";
                $cadena_sql .= "`correo`='" . $variable[5] . "', ";
                $cadena_sql .= "`telefono`='" . $variable[4] . "', ";
                $cadena_sql .= "`celular`='" . $variable[6] . "', ";
                $cadena_sql .= "`identificacion`='" . $variable[3] . "'";
                $cadena_sql .= " WHERE ";
                $cadena_sql .= "`id_usuario`= ";
                $cadena_sql .= $variable[0];
                break;

            case "editar_contrasena":
                $cadena_sql = "UPDATE ";
                $cadena_sql .= $configuracion["prefijo"] . "registrado ";
                $cadena_sql .= "SET ";
                $cadena_sql .= "`clave`='" . $variable[1] . "' ";
                $cadena_sql .= " WHERE ";
                $cadena_sql .= "`id_usuario`= ";
                $cadena_sql .= $variable[0];
                break;

            case "busqueda_estado":
                $cadena_sql = "SELECT ";
                $cadena_sql .= "estado ";
                $cadena_sql .= "FROM ";
                $cadena_sql .= $configuracion["prefijo"] . "registrado_subsistema ";
                $cadena_sql .= "WHERE ";
                $cadena_sql .= "id_usuario = " . $variable;
                break;

            case "cambiar_estado":
                $cadena_sql = "UPDATE ";
                $cadena_sql .= $configuracion["prefijo"] . "registrado_subsistema ";
                $cadena_sql .= "SET ";
                $cadena_sql .= "`estado`='" . $variable[1] . "' ";
                $cadena_sql .= " WHERE ";
                $cadena_sql .= "`id_usuario`= ";
                $cadena_sql .= $variable[0];
                break;

            default:
                $cadena_sql = "";
                break;
        }//fin switch
        return $cadena_sql;
    }

// fin funcion cadena_sql
}

//fin clase sql_adminUsuario
?>

