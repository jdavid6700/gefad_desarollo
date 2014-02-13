<?php
/* --------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
  --------------------------------------------------------------------------------------------------------------------------- */
/* ---------------------------------------------------------------------------------------
  |				Control Versiones				    	|
  ----------------------------------------------------------------------------------------
  | fecha      |        Autor            | version     |              Detalle            |
  ----------------------------------------------------------------------------------------
  | 14/08/2009 | Maritza Callejas C.  	| 0.0.0.1     |                                 |
  ----------------------------------------------------------------------------------------
 */


if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/funcionGeneral.class.php");
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/navegacion.class.php");

class funciones_adminGeneral extends funcionGeneral {

    function __construct($configuracion, $sql) {
        //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo		
        //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
        include ($configuracion["raiz_documento"] . $configuracion["estilo"] . "/basico/tema.php");
        include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
        include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/log.class.php");

        $this->cripto = new encriptar();
              $this->log_us = new log();
        $this->tema = $tema;
        $this->sql = $sql;

        //Conexion General
        $this->acceso_db = $this->conectarDB($configuracion, "");

        //Datos de sesion

        $this->usuario = $this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion = $this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
    }

    function nuevoRegistro($configuracion, $tema, $acceso_db) {
        
    }

    function editarRegistro($configuracion, $id) {

        $this->cadena_sql = $this->sql->cadena_sql($configuracion, $this->acceso_db, "usuario", $id);
        $registro = $this->acceso_db->ejecutarAcceso($this->cadena_sql, "busqueda");


        if ($_REQUEST['opcion'] == 'cambiar_clave') {
            $this->formContrasena($configuracion, $registro, $this->tema, '');
        } else {
            $this->form_usuario($configuracion, $registro, $this->tema, '');
        }
    }

    function corregirRegistro() {
        
    }

    function listaRegistro($configuracion, $id_registro) {
        
    }

    function mostrarRegistro($configuracion, $registro, $totalRegistros, $opcion, $variable) {
        
    }

    /* __________________________________________________________________________________________________

      Metodos especificos
      __________________________________________________________________________________________________ */

    function form_usuario($configuracion, $registro, $tema, $estilo) {
        $indice = $configuracion["host"] . $configuracion["site"] . "/index.php?";

        /*         * ************************************************************************************************** */
        include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/html.class.php");

        $enlace = $this->acceso_db;
        $id_usuario = $this->usuario;
        $sql_rol_usuario = "	SELECT id_rol 
					FROM " . $configuracion["prefijo"] . "integrante_proyecto
					WHERE id_usuario = " . $id_usuario;
        $rol_usuario = $this->ejecutarSQL($configuracion, $this->acceso_db, $sql_rol_usuario, "busqueda");

        $html = new html();
        $tab = 1;
        $this->formulario = "admin_general";
        $this->verificar .= "control_vacio(" . $this->formulario . ",'nombres')";
        $this->verificar .= "&& control_vacio(" . $this->formulario . ",'apellidos')";
        $this->verificar .= "&& control_vacio(" . $this->formulario . ",'telefono')";
        $this->verificar .= "&& control_vacio(" . $this->formulario . ",'correo')";
        $this->verificar .= "&& verificar_correo(" . $this->formulario . ",'correo')";
        ?>
        <script src="<? echo $configuracion["host"] . $configuracion["site"] . $configuracion["javascript"] ?>/funciones.js" type="text/javascript" language="javascript"></script>

        <form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario; ?>'>
            <hr>

            <table width='80%' height="45" valign="top" >		
                <tr>
                    <td colspan="5"><font color="red" size="-2"  ><br>Todos los campos marcados con ( * ) son obligatorios. <br></font></td>
                </tr>
            </table>

            <table width='80%'  class='formulario'  align='center'>
                <tr class='bloquecentralcuerpobeige'><td  colspan='3'><hr class='hr_subtitulo'/>
                        <?
                        echo "EDITAR USUARIO";
                        ?>
                        <hr class='hr_subtitulo'/></td></tr>		
                <tr>
                    <td width='30%'><?
                        $texto_ayuda = "<b>Nombres del usuario.</b><br> ";
                        ?><font color="red" >*</font><span onmouseover="return escape('<? echo $texto_ayuda ?>')">Nombres:</span>
                    </td>
                    <td>
                        <input type='text' name='nombres' value='<? echo $registro[0][1] ?>' size='40' maxlength='255' tabindex='<? echo $tab++ ?>'  onKeyPress="return solo_texto_sin_esp(event)" >
                    </td>
                </tr>		
                <tr>
                    <td width='30%'><?
                        $texto_ayuda = "<b>Apellidos del usuario.</b><br> ";
                        ?><font color="red" >*</font><span onmouseover="return escape('<? echo $texto_ayuda ?>')">Apellidos:</span>
                    </td>
                    <td>
                        <input type='text' name='apellidos' value='<? echo $registro[0][2] ?>' size='40' maxlength='255' tabindex='<? echo $tab++ ?>' onKeyPress="return solo_texto_sin_esp(event)" >
                    </td>
                </tr>		
                <tr>
                    <td width='30%'><?
                        $texto_ayuda = "<b>Número de identificación del usuario.</b><br> ";
                        ?><font color="red" >*</font><span onmouseover="return escape('<? echo $texto_ayuda ?>')">Identificación:</span>
                    </td>
                    <td>
                        <input type='text' name='identificacion' value='<? echo $registro[0][7] ?>' size='40' maxlength='15' tabindex='<? echo $tab++ ?>' onKeyPress="return solo_numero_sin_slash(event)">
                    </td>
                </tr>		
                <tr>
                    <td width='30%'><?
                        $texto_ayuda = "<b>Teléfono del usuario.</b><br> ";
                        ?><font color="red" >*</font><span onmouseover="return escape('<? echo $texto_ayuda ?>')">Teléfono:</span>
                    </td>
                    <td>
                        <input type='text' name='telefono' value='<? echo $registro[0][4] ?>' size='40' maxlength='50' tabindex='<? echo $tab++ ?>' onKeyPress="return solo_numero(event)">
                    </td>
                </tr>		
                <tr>
                    <td width='30%'><?
                        $texto_ayuda = "<b>Nombre de la cuenta de correo electrónico del usuario.</b><br> ";
                        ?><font color="red" >*</font><span onmouseover="return escape('<? echo $texto_ayuda ?>')">Correo electrónico:</span>
                    </td>
                    <td>
                        <input type='text' name='correo' value='<? echo $registro[0][3] ?>' size='40' maxlength='100' tabindex='<? echo $tab++ ?>' >
                    </td>
                </tr>		
                <tr>
                    <td width='30%'><?
                        $texto_ayuda = "<b>Número de celular del usuario.</b><br> ";
                        ?><span onmouseover="return escape('<? echo $texto_ayuda ?>')">No. Celular:</span>
                    </td>
                    <td>
                        <input type='text' name='celular' value='<? echo $registro[0][6] ?>' size='40' maxlength='15' tabindex='<? echo $tab++ ?>' onKeyPress="return solo_numero_sin_slash(event)" >
                    </td>
                </tr>		
            </table>
            <table align='center'>
                <tr align='center'>
                    <td colspan='2' rowspan='1'>
                        <input type='hidden' name='usuario' value='<? echo $_REQUEST["usuario"] ?>'>
                        <input type='hidden' name='action' value='admin_general'>

                        <input type='hidden' name='opcion' value='editar'>
                        <input value="Aceptar" name="aceptar" tabindex='<?= $tab++ ?>' type="button" 	onclick="if (<?= $this->verificar; ?>) {
                                    document.forms['<?= $this->formulario ?>'].submit()
                                } else {
                                    false
                                }"
                               >			
                        <input name='cancelar' value='Cancelar' type='submit'>
                        <br>
                    </td>
                </tr> 
            </table>

        </form>		
        <?php
    }

// fin function form_usuario
    // funcion que edita los datos del usuario

    function editarUsuario($configuracion) {

        //rescata los valores para editar los datos del usuario
        //----------------------------------------------------
        $datos_usuario[0] = $this->usuario;
        $datos_usuario[1] = $_REQUEST['nombres'];
        $datos_usuario[2] = $_REQUEST['apellidos'];
        $datos_usuario[3] = $_REQUEST['identificacion'];
        $datos_usuario[4] = $_REQUEST['telefono'];
        $datos_usuario[5] = $_REQUEST['correo'];
        $datos_usuario[6] = $_REQUEST['celular'];

        //ejecuta la modificacion del usuario

        $usuario_sql = $this->sql->cadena_sql($configuracion, $this->acceso_db, "editar_usuario", $datos_usuario);
        @$usu = $this->ejecutarSQL($configuracion, $this->acceso_db, $usuario_sql, "");
        $pagina = $configuracion["host"] . $configuracion["site"] . "/index.php?";
        $variable = "pagina=adminGeneral";
        $variable .= "&opcion=editar";
        $variable = $this->cripto->codificar_url($variable, $configuracion);

        //verificamos que se halla ejecutado la consulta con exito.		
        if (@$usu) {
            //VARIABLES PARA EL LOG
            $registro[0] = "EDITAR";
            $registro[1] = $datos_usuario[0];
            $registro[2] = "USUARIO";
            $registro[3] = $datos_usuario[0];
            $registro[4] = time();
            $registro[5] = "Modifica datos de usuario " . $datos_usuario[0];
            $registro[5] .= " - nombre =" . $datos_usuario[1];
            $registro[5] .= " - apellidos =" . $datos_usuario[2];
            $registro[5] .= " - identificación =" . $datos_usuario[3];
            $registro[5] .= " - telefono =" . $datos_usuario[4];
            $registro[5] .= " - correo =" . $datos_usuario[5];
            $registro[5] .= " - celular =" . $datos_usuario[6];
            $this->log_us->log_usuario($registro, $configuracion);

            unset($_REQUEST['action']);

            echo "<script>alert('Registro de Usuario modificado con exito!')</script>";
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
        } else {
            echo "<script>alert('Imposible Modificar Usuario')</script>";
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
        }
    }

    function formContrasena($configuracion, $registro) {
        $indice = $configuracion["host"] . $configuracion["site"] . "/index.php?";


        $datos_basicos = array(
            'ID_US' => (isset($registro[0]['0']) ? $registro[0]['0'] : ''),
            'NOMBRE' => (isset($registro[0]['1']) ? $registro[0]['1'] : ''),
            'APELLIDO' => (isset($registro[0]['2']) ? $registro[0]['2'] : ''),
            'MAIL' => (isset($registro[0]['3']) ? $registro[0]['3'] : ''),
            'TEL' => (isset($registro[0]['4']) ? $registro[0]['4'] : ''),
            'EXT' => (isset($registro[0]['5']) ? $registro[0]['5'] : ''),
            'NICK' => (isset($registro[0]['6']) ? $registro[0]['6'] : ''),
            'CEL' => (isset($registro[0]['7']) ? $registro[0]['7'] : ''),
            'IDENT' => (isset($registro[0]['8']) ? $registro[0]['8'] : ''),
            'PASS' => (isset($registro[0]['9']) ? $registro[0]['9'] : '')
        );


        /*         * ************************************************************************************************** */
        include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/html.class.php");

        $enlace = $this->acceso_db;
        $id_usuario = $this->usuario;
        $html = new html();
        $tab = 1;
        $this->formulario = "admin_general";
        $this->verificar .= "control_vacio(" . $this->formulario . ", 'contrasena')";
        $this->verificar .= " && longitud_cadena(" . $this->formulario . ", 'contrasena', 6)";
        $this->verificar .= "&& control_vacio(" . $this->formulario . ", 'reescribir_contrasena')";
        $this->verificar .= "&& longitud_cadena(" . $this->formulario . ", 'reescribir_contrasena', 6)";
        $this->verificar .= "&& comparar_contenido(" . $this->formulario . ", 'contrasena', 'reescribir_contrasena')";
        ?>		
        <script src="<? echo $configuracion["host"] . $configuracion["site"] . $configuracion["javascript"]
        ?>/funciones.js" type="text/javascript" language="javascript"></script>
        <script src="<? echo $configuracion["host"] . $configuracion["site"] . $configuracion["javascript"] ?>/md5.js" type="text/javascript" language="javascript"></script>		
        <link	href="<? echo $configuracion["host"] . $configuracion["site"] . $configuracion["bloques"] ?>/nomina/cuotas_partes/formIPC/form_estilo.css"	rel="stylesheet" type="text/css" />
        <form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario; ?>'>
            <hr>
            <?
            //Mostramos los datos del usuario 
            ?>
            <center> <table width='60%'  class='bordered'  align='center'>
                    <tr>
                        <td  colspan='2' width='60%'>
                            <hr class='hr_subtitulo'/>DATOS DE USUARIO
                        </td></tr>		
                </table>
                <table class='bordered' width='60%' >
                    <tr >
                        <td class='cuadro_plano centrar ancho10' >Identificaci&oacute;n:</td>
                        <td width="70%" class='cuadro_plano '><? echo $datos_basicos['ID_US'] ?></td>
                    </tr>	
                    <tr >
                        <td class='cuadro_plano centrar ancho10' >Nombre(s):</td>
                        <td width="70%" class='cuadro_plano '><? echo $datos_basicos['NOMBRE'] ?></td>
                    </tr>			
                    <tr >
                        <td class='cuadro_plano centrar ancho10' >Apellido(s):</td>
                        <td width="70%" class='cuadro_plano '><? echo $datos_basicos['APELLIDO'] ?></td>
                    </tr>
                    <tr >
                        <td class='cuadro_plano centrar ancho10' >Usuario:</td>
                        <td width="70%" class='cuadro_plano '><? echo $datos_basicos['NICK'] ?></td>
                    </tr>
                    <tr >
                        <td class='cuadro_plano centrar ancho10' >Correo:</td>
                        <td width="70%" class='cuadro_plano '><? echo $datos_basicos['MAIL'] ?></td>
                    </tr>			
                    <tr >
                        <td class='cuadro_plano centrar ancho10' >Tel&eacute;fono:</td>
                        <td width="70%" class='cuadro_plano '><? echo $datos_basicos['TEL'] ?></td>
                    </tr>			
                    <tr >
                        <td class='cuadro_plano centrar ancho10' >Extenci&oacute;n:</td>
                        <td width="70%" class='cuadro_plano '><? echo $datos_basicos['EXT'] ?></td>
                    </tr>			
                    <tr >
                        <td class='cuadro_plano centrar ancho10' >Celular:</td>
                        <td width="70%" class='cuadro_plano '><? echo $datos_basicos['CEL'] ?></td>
                    </tr>                            
                </table>

                <table width='60%'  class='bordered'  align='center'>
                    <tr><td  colspan='2'><hr class='encabezado_registro'/>CAMBIO DE CONTRASEÑA

                            <hr class='hr_subtitulo'/></td></tr>		
                    <tr>
                        <td width='30%'><?
                            $texto_ayuda = "<b>Nueva contraseña para el usuario.</b><br> ";
                            ?><font color="red" >*</font><span onmouseover="return escape('<? echo $texto_ayuda ?>')">Nueva Contraseña:</span>
                        </td>
                        <td>
                            <input class="fieldcontent" type='password' name='contrasena' size='40' maxlength='50'>
                        </td>
                    </tr>		
                    <tr>
                        <td width='30%'><?
                            $texto_ayuda = "<b>Reescriba la nueva contraseña.</b><br> ";
                            ?><font color="red" >*</font><span onmouseover="return escape('<? echo $texto_ayuda ?>')">Confirmar contraseña:</span>
                        </td>
                        <td>
                            <input class="fieldcontent" type='password' name='reescribir_contrasena' size='40' maxlength='50' >
                        </td>
                    </tr>		
                </table>
                <br>
                <table align='center' width="60%"  class="bordered">
                    <tr align='center'>
                        <td colspan='2' rowspan='1'>
                            <input class="navbtn" value='Cambiar Clave' name='aceptar' type='submit'>	
                            <input type='hidden' name='usuario' value='<? echo $datos_basicos['NICK'] ?>'>
                            <input type='hidden' name='action' value='admin_general'>
                            <input type='hidden' name='opcion' value='cambiar_clave'>

                            <input class="navbtn" name='cancelar' value='Cancelar' type='submit'>
                            <br>
                        </td>
                    </tr> 
                </table>
            </center>
        </form>		

        <?
    }

    function editarContrasena($configuracion) {

        //rescata los valores para editar la contrasena
        //----------------------------------------------------
        $datos_usuario[0] = $this->usuario;
        $datos_usuario[1] = $this->cripto->codificar_md5($_REQUEST['contrasena']);

        //ejecuta la modificacion de la contraseña de usuario

        $usuario_sql = $this->sql->cadena_sql($configuracion, $this->acceso_db, "editar_contrasena", $datos_usuario);
        //echo "<br>sql mod pw ".$usuario_sql;exit;
        @$usu = $this->ejecutarSQL($configuracion, $this->acceso_db, $usuario_sql, "");
        $pagina = $configuracion["host"] . $configuracion["site"] . "/index.php?";
        $variable = "pagina=adminGeneral";
        $variable .= "&opcion=cambiar_clave";
        $variable = $this->cripto->codificar_url($variable, $configuracion);

        //verificamos que se haya ejecutado la consulta con exito.		
        if (@$usu) {
            //VARIABLES PARA EL LOG
            $registro[0] = "EDITAR";
            $registro[1] = $datos_usuario[0];
            $registro[2] = "CONTRASEÑA USUARIO";
            $registro[3] = $datos_usuario[1];
            $registro[4] = time();
            $registro[5] = "Modifica la contraseña del usuario " . $datos_usuario[0];
            $this->log_us->log_usuario($registro, $configuracion);
            unset($_REQUEST['action']);

            echo "<script>alert('Contraseña de Usuario modificada con exito!')</script>";
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
        } else {
            echo "<script>alert('Imposible Modificar la contraseña')</script>";
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
        }
    }

}
?>

