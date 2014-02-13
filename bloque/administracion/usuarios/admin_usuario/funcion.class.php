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

class funciones_adminUsuario extends funcionGeneral {

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
        $this->acceso_db = $this->conectarDB($configuracion, "mysqlFrame");

        //Conexion SICAPITAL
        $this->acceso_sic = $this->conectarDB($configuracion, "cuotasP");

        //Datos de sesion

        $this->usuario = $this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion = $this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
    }

    function nuevoRegistro($configuracion, $tema, $acceso_db) {
        $registro = (isset($registro) ? $registro : '');
        $this->form_usuario($configuracion, $registro, $this->tema, "");
    }

    function editarRegistro($configuracion, $tema, $id, $acceso_db, $formulario) {
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
        switch ($opcion) {
            case "multiplesUsuarios":
                $this->multiplesUsuarios($configuracion, $registro, $totalRegistros, $variable);
                break;
        }
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

        $html = new html();
        $tab = 1;
        $this->formulario = "admin_usuario";
        $this->verificar .= "control_vacio(" . $this->formulario . ",'nombres')";
        $this->verificar .= "&& control_vacio(" . $this->formulario . ",'apellidos')";
        $this->verificar .= "&& control_vacio(" . $this->formulario . ",'identificacion')";
        $this->verificar .= "&& control_vacio(" . $this->formulario . ",'telefono')";
        $this->verificar .= "&& control_vacio(" . $this->formulario . ",'correo')";
        $this->verificar .= "&& verificar_correo(" . $this->formulario . ",'correo')";
        $this->verificar .= "&& seleccion_valida(" . $this->formulario . ",'id_dep')";
        //$this->verificar .= "&& seleccion_valida(".$this->formulario.",'id_area')";
        $this->verificar .= "&& seleccion_valida(" . $this->formulario . ",'id_rol')";
        $this->verificar .= "&& control_vacio(" . $this->formulario . ",'fecha_inactiva')";
        //$this->verificar .= "&& fecha(".$this->formulario.",'fecha_inactiva','".date("d/m/Y",time())."')";
        //$this->verificar .= "&& longitud_cadena(".$this->formulario.",'contrasena',6)";
        //$this->verificar .= "&& comparar_contenido(".$this->formulario.",'contrasena','reescribir_contrasena')";
        $nombres = (isset($registro[0][1]) ? $registro[0][1] : '');
        $apellidos = (isset($registro[0][2]) ? $registro[0][2] : '');
        $identificacion = (isset($registro[0][7]) ? $registro[0][7] : '');
        $telefono = (isset($registro[0][4]) ? $registro[0][4] : '');
        $correo = (isset($registro[0][3]) ? $registro[0][3] : '');
        $celular = (isset($registro[0][6]) ? $registro[0][6] : '');
        ?>
        <script src="<? echo $configuracion["host"] . $configuracion["site"] . $configuracion["javascript"] ?>/funciones.js" type="text/javascript" language="javascript"></script>
        <script src="<? echo $configuracion["host"] . $configuracion["site"] . $configuracion["javascript"] ?>/md5.js" type="text/javascript" language="javascript"></script>		
        <script type="text/javascript" src="<? echo $this->configuracion["host"] . $this->configuracion["site"] . $this->configuracion["plugins"]; ?>/datepicker/js/datepicker.js"></script>
        <link href = "http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel = "stylesheet" type = "text/css"/>
        <script type = "text/javascript" src = "http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
        <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
        <link	href="<? echo $configuracion["host"] . $configuracion["site"] . $configuracion["bloques"] ?>/administracion/css/form_estilo.css"	rel="stylesheet" type="text/css" />
        <script>
            $(document).ready(function() {
                $("#fecha_inactiva").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    yearRange: '2000:c',
                    dateFormat: 'dd/mm/yy',
                    showButtonPanel: true,
                    beforeShow: function() {
                        $(".ui-datepicker").css('font-size', 12)
                    }
                });
            });</script>


        <form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario; ?>'>
            <hr>

            <table width='80%' valign="top" >		
                <tr>
                    <td><font color="red" size="-2"  ><br>Todos los campos marcados con ( * ) son obligatorios. <br></font></td>
                </tr>
            </table>

            <table width='55%'  class='bordered'  align='center' >
                <tr>
                    <td class='texto_elegante2 estilo_td' colspan="2"><hr class='hr_subtitulo'/>
                        <?
                        if (!$registro)
                            echo "DATOS DEL USUARIO";
                        else
                            echo "EDITAR USUARIO";
                        ?>
                        <hr class='hr_subtitulo'/>
                    </td>
                </tr>	

                <tr>
                    <td width='30%' class='texto_elegante2 estilo_td'><?
                        $texto_ayuda = "<b>Nombres del usuario.</b><br> ";
                        ?><font color="red" >*</font><span onmouseover="return escape('<? echo $texto_ayuda ?>')">Nombres:</span>
                    </td>
                    <td class='texto_elegante2 estilo_td'>
                        <input type='text' name='nombres' value='<? echo $nombres ?>' size='40' maxlength='255' onKeyPress="return solo_texto_sin_esp(event)" >
                    </td>
                </tr>	

                <tr>
                    <td width='30%' class='texto_elegante2 estilo_td'><?
                        $texto_ayuda = "<b>Apellidos del usuario.</b><br> ";
                        ?><font color="red" >*</font><span onmouseover="return escape('<? echo $texto_ayuda ?>')">Apellidos:</span>
                    </td>
                    <td class='texto_elegante2 estilo_td'>
                        <input type='text' name='apellidos' value='<? echo $apellidos ?>' size='40' maxlength='255'  onKeyPress="return solo_texto_sin_esp(event)" >
                    </td>
                </tr>		

                <tr>
                    <td width='30%' class='texto_elegante2 estilo_td'><?
                        $texto_ayuda = "<b>Número de identificación del usuario.</b><br> ";
                        ?><font color="red" >*</font><span onmouseover="return escape('<? echo $texto_ayuda ?>')">Identificación:</span>
                    </td >
                    <td class='texto_elegante2 estilo_td'>
                        <input type='text' name='identificacion' value='<? echo $identificacion ?>' size='40' maxlength='15' onKeyPress="return solo_numero_sin_slash(event)">
                    </td>
                </tr>		
                <tr>
                    <td width='30%' class='texto_elegante2 estilo_td'><?
                        $texto_ayuda = "<b>Teléfono fijo del usuario.</b><br> ";
                        ?><font color="red" >*</font><span onmouseover="return escape('<? echo $texto_ayuda ?>')">Teléfono:</span>
                    </td>
                    <td class='texto_elegante2 estilo_td'>
                        <input type='text' name='telefono' value='<? echo $telefono ?>' size='40' maxlength='50' onKeyPress="return solo_numero(event)">
                    </td>
                </tr>	
                <tr>
                    <td width='30%' class='texto_elegante2 estilo_td'><?
                        $texto_ayuda = "<b>Estencion del teléfono del usuario.</b><br> ";
                        ?><font color="red" >*</font><span onmouseover="return escape('<? echo $texto_ayuda ?>')">Extension:</span>
                    </td>
                    <td class='texto_elegante2 estilo_td'>
                        <input type='text' name='ext' value='<? echo $telefono ?>' size='40' maxlength='50' onKeyPress="return solo_numero(event)">
                    </td>
                </tr>	
                <tr>
                    <td width='30%' class='texto_elegante2 estilo_td'><?
                        $texto_ayuda = "<b>Direccion de la cuenta de correo electrónico del usuario.</b><br> ";
                        ?><font color="red" >*</font><span onmouseover="return escape('<? echo $texto_ayuda ?>')">Correo electrónico:</span>
                    </td>
                    <td class='texto_elegante2 estilo_td'>
                        <input type='text' name='correo' value='<? echo $correo ?>' size='40' maxlength='100' >
                    </td>
                </tr>		
                <tr>
                    <td width='30%' class='texto_elegante2 estilo_td'><?
                        $texto_ayuda = "<b>Número de celular del usuario.</b><br> ";
                        ?><span onmouseover="return escape('<? echo $texto_ayuda ?>')">  No. Celular:</span>
                    </td>
                    <td class='texto_elegante2 estilo_td'>
                        <input type='text' name='celular' value='<? echo $celular ?>' size='40' maxlength='15' onKeyPress="return solo_numero_sin_slash(event)"/>
                    </td>
                </tr>		

                <tr>
                    <td width='30%' class='texto_elegante2 estilo_td'><?
                        $texto_ayuda = "<b>Rol que se asignara al usuario.</b><br> ";
                        ?><font color="red" >*</font><span onmouseover="return escape('<? echo $texto_ayuda ?>')">Rol:</span>
                    </td>
                    <td class='texto_elegante2 estilo_td'>
                        <?
                        //Evaluamos el rol del usuario actual en el sistema, si es administrador general no tiene 
                        //restricciones en roles; si NO es administrador general, no puede crear usuarios con rol Administrador 
                        //general
                        $busquedaRol = "SELECT DISTINCT id_subsistema , etiqueta FROM " . $configuracion["prefijo"] . "subsistema ORDER BY etiqueta";
                        $metodo = $this->ejecutarSQL($configuracion, $this->acceso_db, $busquedaRol, "busqueda");
                        ?>
                        <select name='id_rol'  title="*Campo Obligatorio" required='required'>
                            <?php
                            $var = "";
                            foreach ($metodo as $key => $value) {
                                $var.= "<option value='" . $metodo[$key]['id_subsistema'] . "'>" . $metodo[$key]['etiqueta'] . "</option>";
                            }
                            echo $var;
                            ?>   

                        </select>

                    </td>
                </tr>		
                <tr>
                    <td width='30%' class='texto_elegante2 estilo_td'><?
                        $texto_ayuda = "<b>Fecha de inactivaci&oacute;n del rol.</b><br> ";
                        $texto_ayuda .= "Fecha en formato: dd/mm/aaaa.<br>";
                        ?><font color="red" >*</font><span onmouseover="return escape('<? echo $texto_ayuda ?>')">Fecha Inactivaci&oacute;n:</span>
                    </td>
                    <td class='texto_elegante2 estilo_td'>
                        <input type='text' size='12' maxlength='25' readonly="readonly" id="fecha_inactiva" name='fecha_inactiva' value='<?
                        if (!$registro) {
                            echo "31/12/" . date("Y");
                        } else {
                            list($d, $m, $a) = explode("-", $registro[0]['F_FIN']);
                            echo $a . "/" . $m . "/" . $d;
                        }
                        ?>'
                               />
                    </td>
                </tr>	
            </table>
            <br>
            <table align='center'>
                <tr align='center'>
                    <td colspan='2' rowspan='1' >
                        <input type='hidden' name='action' value='admin_usuario'>
                        <input type='hidden' name='opcion' value='nuevo'>
                        <input type='hidden' name='usuario' value='<? echo $id_usuario ?>'>
                                                                                                              <!--<input value="Guardar" name="aceptar" tabindex='?= $tab++ ?>' type="button" onclick="if(?= $this->verificar; ?>){?echo $this->formulario?>.contrasena.value = hex_md5(?echo $this->formulario?>.contrasena.value);?echo $this->formulario?>.reescribir_contrasena.value = hex_md5(?echo $this->formulario?>.reescribir_contrasena.value);document.forms['? echo $this->formulario?>'].submit()}else{false}"/>-->
                        <input value="Guardar" name="Guardar" class="navbtn" type="submit">

                        <br>
                    </td>
                </tr> 
            </table>

        </form>		
        <?php
    }

    function guardarUsuario($configuracion) {

        //rescata los valores para ingresar los datos del  nuevo usuario//----------------------------------------------------


        list($d, $m, $a) = explode("/", $_REQUEST["fecha_inactiva"]);

        $nvo_usuario = array(
            'cod_admin' => $_REQUEST['usuario'],
            'cod_usuario' => '',
            'nombres' => strtoupper($_REQUEST['nombres']),
            'apellidos' => strtoupper($_REQUEST['apellidos']),
            'num_id' => $_REQUEST['identificacion'],
            'telefono' => $_REQUEST['telefono'],
            'ext' => $_REQUEST['ext'],
            'correo' => $_REQUEST['correo'],
            'celular' => $_REQUEST['celular'],
            'id_rol' => $_REQUEST['id_rol'],
            'cod_area' => 0,
            'cod_rol' => $_REQUEST['id_rol'],
            'id_area' => 0,
            'fecha_registro' => date('Y-m-d'),
            'fecha_inactiva' => $a . "-" . $m . "-" . $d,
            'nick' => $_REQUEST['identificacion'],
            'contrasena' => md5($_REQUEST['identificacion']),
            'estado' => '1');
        //var_dump($nvo_usuario);exit;
        //evaluamos si el usuario existe
        $existe_usuario_sql = $this->sql->cadena_sql($configuracion, $this->acceso_db, "busqueda_usuario_xnombre", $nvo_usuario['nick']);
        $rs_existe_usuario = $this->ejecutarSQL($configuracion, $this->acceso_db, $existe_usuario_sql, "busqueda");

        if (!$rs_existe_usuario) {
            //ejecuta el ingreso del usuario

            $usuario_sql = $this->sql->cadena_sql($configuracion, $this->acceso_db, "inserta_usuario", $nvo_usuario);
            $usu = $this->ejecutarSQL($configuracion, $this->acceso_db, $usuario_sql, "");

            //consultamos el identificador del nuevo usuario			
            $nvo_usuario_sql = $this->sql->cadena_sql($configuracion, $this->acceso_db, "busqueda_usuario_xnombre", $nvo_usuario['nick']);
            $rs_nvo_usuario = $this->ejecutarSQL($configuracion, $this->acceso_db, $nvo_usuario_sql, "busqueda");
            $nvo_usuario['cod_usuario'] = $rs_nvo_usuario[0]['ID_US']; //asignamos al arreglo el id del nuevo usuario
            //var_dump($nvo_usuario);


            $cadena_sql = $this->sql->cadena_sql($configuracion, $this->acceso_db, "inserta_registrado_subsistema", $nvo_usuario);
            $rs_usuario_subsistema = $this->ejecutarSQL($configuracion, $this->acceso_db, $cadena_sql, "registro");

            //se inserta registrado_subsistema




            $variable = "pagina=adminUsuario";
            $variable .= "&opcion=consultar";
            $variable .= "&cod_usuario=" . $rs_nvo_usuario[0]['ID_US'];

            if ($rs_nvo_usuario == true) {

                $parametros = array(
                    'id_usuario' => $nvo_usuario['cod_usuario'],
                    'id_subsistema' => $_REQUEST['id_rol'],
                    'fecha_registro' => date('Y-m-d'),
                    'fecha_fin' => $a . "-" . $m . "-" . $d,
                    'cod_area' => 0,
                    'estado' => '1');

                $rol_sql = $this->sql->cadena_sql($configuracion, $this->acceso_db, "insertar_rol", $parametros);
                $rol = $this->ejecutarSQL($configuracion, $this->acceso_db, $rol_sql, "");

                //VARIABLES PARA EL LOG
                $registro[0] = "CREAR";
                $registro[1] = $nvo_usuario['cod_admin'];
                $registro[2] = "USUARIO";
                $registro[3] = $nvo_usuario['nick'];
                $registro[4] = time();
                $registro[5] = "Registra usuario " . $nvo_usuario['nick'];
                $registro[5] .= " al Area " . $nvo_usuario['id_area'];
                $registro[5] .= " con rol " . $nvo_usuario['id_rol'];
                $this->log_us->log_usuario($registro, $configuracion);
                echo "<script>alert('Usuario registrado con exito!')</script>";
            } else {
                echo "<script>alert('Imposible Crear Usuario')</script>";
            }
        }//fin if NO existencia de usuario	
        else {
            $variable = "pagina=adminUsuario";
            $variable .= "&opcion=nuevo";
            echo "<script>alert('YA EXISTE EL USUARIO')</script>";
        }
        $pagina = $configuracion["host"] . $configuracion["site"] . "/index.php?";
        $variable = $this->cripto->codificar_url($variable, $configuracion);
        echo "<script>location.replace('" . $pagina . $variable . "')</script>";
    }

    function consultarUsuario($configuracion, $cod_usuario) {

        $busquedaUsuario = "";
        $this->formulario = "admin_usuario";
        $indice = $configuracion["host"] . $configuracion["site"] . "/index.php?";
        $tab = 0;

        if ($cod_usuario == "") {

            $id_usuario = $this->usuario;
            $clave = (isset($_REQUEST['clave']) ? $_REQUEST['clave'] : '');
            if ($clave) {
                $busquedaUsuario = array(
                    'id_admin' => $this->usuario,
                    'criterio_busqueda' => $_REQUEST['criterio_busqueda'],
                    'valor' => $_REQUEST['clave']);
                //$busqueda[1] = $_REQUEST['criterio_busqueda'];//tipo de consulta
                //$busqueda[2] = $_REQUEST['clave'];//cadena a buscar
                $cadena = "No Existen Usuarios para la consulta.";
            } else {
                $cadena = "No Existen Usuarios Registrados.";
            }

            //Rescatar Usuarios, todos si es administrador general 
            if (is_array($busquedaUsuario)) {
                $cadena_sql = $this->sql->cadena_sql($configuracion, $this->acceso_db, "usuarios_todos", $busquedaUsuario);
            } else {
                $cadena_sql = $this->sql->cadena_sql($configuracion, $this->acceso_db, "usuarios_todos", "");
            }

            $registro_completo = $this->ejecutarSQL($configuracion, $this->acceso_db, $cadena_sql, "busqueda");

            //Obtener el total de registros
            $totalRegistros = $this->totalRegistros($configuracion, $this->acceso_db);

            $this->cadena_hoja = $cadena_sql;

            //Si no se viene de una hoja anterior
            if (!isset($_REQUEST["hoja"])) {
                $_REQUEST["hoja"] = 1;
            }

            $this->cadena_hoja .= " LIMIT " . (($_REQUEST["hoja"] - 1) * $configuracion['registro']) . "," . $configuracion['registro'];
            $registro = $this->ejecutarSQL($configuracion, $this->acceso_db, $this->cadena_hoja, "busqueda");
            // var_dump($registro);
            if ($totalRegistros) {
                $hojas = ceil($totalRegistros / $configuracion['registro']);
            } else {
                $hojas = 1;
            }

            if (is_array($registro)) {
                //evaluamos si existe mas de un usuario
                if ($totalRegistros > 1) {
                    $variable["pagina"] = "adminUsuario";
                    $variable["opcion"] = $_REQUEST["opcion"];
                    $variable["hoja"] = $_REQUEST["hoja"];

                    $menu = new navegacion();
                    if ($hojas > 1) {
                        $menu->menu_navegacion($configuracion, $_REQUEST["hoja"], $hojas, $variable);
                    }

                    $this->mostrarRegistro($configuracion, $registro, $configuracion['registro'], "multiplesUsuarios", "");
                    $menu->menu_navegacion($configuracion, $_REQUEST["hoja"], $hojas, $variable);
                } else {
                    //Consultar un usuario especifico
                    $this->consultarUsuario($configuracion, $registro[0][0]);
                }
            } else {
                include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/alerta.class.php");

                alerta::sin_registro($configuracion, $cadena);
            }
        } else {
            //busca si existen registro de datos de usuarios en la base de datos 
            $cadena_sql = $this->sql->cadena_sql($configuracion, $this->acceso_db, "usuario", $cod_usuario);

            $datos_usuario = $this->ejecutarSQL($configuracion, $this->acceso_db, $cadena_sql, "busqueda");
            //busca el estado del usuario

            $estado_sql = $this->sql->cadena_sql($configuracion, $this->acceso_db, "busqueda_estado", $cod_usuario);
            $estado = $this->ejecutarSQL($configuracion, $this->acceso_db, $estado_sql, "busqueda");

            //busca los roles del usuario en la base de datos 
            $roles_sql = $this->sql->cadena_sql($configuracion, $this->acceso_db, "roles", $cod_usuario);
            $registro_completo = $this->ejecutarSQL($configuracion, $this->acceso_db, $roles_sql, "busqueda");


            //Obtener el total de registros
            $totalRegistros = $this->totalRegistros($configuracion, $this->acceso_db);

            $this->cadena_hoja = $roles_sql;

            //Si no se viene de una hoja anterior
            if (!isset($_REQUEST["hoja"])) {
                $_REQUEST["hoja"] = 1;
            }
            $this->cadena_hoja .= " LIMIT " . (($_REQUEST["hoja"] - 1) * $configuracion['registro']) . "," . $configuracion['registro'];
            $roles = $this->ejecutarSQL($configuracion, $this->acceso_db, $this->cadena_hoja, "busqueda");

            if ($totalRegistros) {
                $hojas = ceil($totalRegistros / $configuracion['registro']);
            } else {
                $hojas = 1;
            }

            //Obtener el total de registros
            $totalRoles = $this->totalRegistros($configuracion, $this->acceso_db);
            $datos_usuario[0]['IDENT'] = (isset($datos_usuario[0]['IDENT']) ? $datos_usuario[0]['IDENT'] : '');
            $datos_usuario[0]['NOMBRE'] = (isset($datos_usuario[0]['NOMBRE']) ? $datos_usuario[0]['NOMBRE'] : '');
            $datos_usuario[0]['APELLIDO'] = (isset($datos_usuario[0]['APELLIDO']) ? $datos_usuario[0]['APELLIDO'] : '');
            $datos_usuario[0]['NICK'] = (isset($datos_usuario[0]['NICK']) ? $datos_usuario[0]['NICK'] : '');
            $datos_usuario[0]['MAIL'] = (isset($datos_usuario[0]['MAIL']) ? $datos_usuario[0]['MAIL'] : '');
            $datos_usuario[0]['TEL'] = (isset($datos_usuario[0]['TEL']) ? $datos_usuario[0]['TEL'] : '');
            $datos_usuario[0]['EXT'] = (isset($datos_usuario[0]['EXT']) ? $datos_usuario[0]['EXT'] : '');
            $datos_usuario[0]['CEL'] = (isset($datos_usuario[0]['CEL']) ? $datos_usuario[0]['CEL'] : '');
            ?>	

            <link	href="<? echo $configuracion["host"] . $configuracion["site"] . $configuracion["bloques"] ?>/nomina/cuotas_partes/formHistoria/form_estilo.css"	rel="stylesheet" type="text/css" />
            <table width="90%" align="center" border="0" cellpadding="10" cellspacing="0" >
                <tbody>
                    <tr>
                        <td>
                            <table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
                                <tr class="texto_subtitulo">
                                    <td>
                                        Consulta de Usuario<br>
                                        <hr class="hr_subtitulo">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <table class='contenidotabla'>
                                            <tr >
                                                <td class='cuadro_plano centrar ancho10' >Identificaci&oacute;n:</td>
                                                <td class='cuadro_plano '><? echo $datos_usuario[0]['IDENT'] ?></td>
                                            </tr>	
                                            <tr >
                                                <td class='cuadro_plano centrar ancho10' >Nombre(s):</td>
                                                <td class='cuadro_plano '><? echo $datos_usuario[0]['NOMBRE'] ?></td>
                                            </tr>			
                                            <tr >
                                                <td class='cuadro_plano centrar ancho10' >Apellido(s):</td>
                                                <td class='cuadro_plano '><? echo $datos_usuario[0]['APELLIDO'] ?></td>
                                            </tr>
                                            <tr >
                                                <td class='cuadro_plano centrar ancho10' >Usuario:</td>
                                                <td class='cuadro_plano '><? echo $datos_usuario[0]['NICK'] ?></td>
                                            </tr>
                                            <tr >
                                                <td class='cuadro_plano centrar ancho10' >Correo:</td>
                                                <td class='cuadro_plano '><? echo $datos_usuario[0]['MAIL'] ?></td>
                                            </tr>			
                                            <tr >
                                                <td class='cuadro_plano centrar ancho10' >Tel&eacute;fono:</td>
                                                <td class='cuadro_plano '><? echo $datos_usuario[0]['TEL'] ?></td>
                                            </tr>			
                                            <tr >
                                                <td class='cuadro_plano centrar ancho10' >Extenci&oacute;n:</td>
                                                <td class='cuadro_plano '><? echo $datos_usuario[0]['EXT'] ?></td>
                                            </tr>			
                                            <tr >
                                                <td class='cuadro_plano centrar ancho10' >Celular:</td>
                                                <td class='cuadro_plano '><? echo $datos_usuario[0]['CEL'] ?></td>
                                            </tr>			

                                            <tr >
                                                <td class='cuadro_plano centrar ancho10' >Estado:</td>
                                                <td class='cuadro_plano '>
                                                    <?
                                                    //Mostramos el estado actual del usuario, y el botón con la 
                                                    //opcion de carbiar dicho estado.
                                                    $datos[0] = $cod_usuario;
                                                    if ($estado[0][0] == 1) {
                                                        echo "Activo";
                                                        $dato = 0;
                                                    } else {
                                                        echo "Inactivo";
                                                        $dato = 1;
                                                    }
                                                    ?>



                                                </td>
                                            </tr>			

                                        </table>
                                    </td>
                                </tr>
                            </table>
                            <form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario; ?>'>
                                <center><input id="registrarBoton" type="submit" class="navbtn"  onClick='return confirmarEnvio();' value="Cambiar Estado"></center>
                                <input type='hidden' name='action' value='admin_usuario'>
                                <input type='hidden' name='usuario' value='<? echo (isset($_REQUEST["usuario"]) ? $_REQUEST["usuario"] : '') ?>'>
                                <input type='hidden' name='cod_usuario' value='<? echo $cod_usuario ?>'>
                                <input type='hidden' name='dato' value='<? echo $dato ?>'>
                                <input type='hidden' name='action' value='admin_usuario'>
                                <input type='hidden' name='opcion' value='cambiar_estado'>
                                &nbsp;&nbsp;

                            </form>	
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?
                            //Mostramos el menu de la paginación
                            if ($totalRoles > 0) {
                                $variable["pagina"] = "adminUsuario";
                                $variable["opcion"] = $_REQUEST["opcion"];
                                $variable["hoja"] = $_REQUEST["hoja"];
                                $variable["cod_usuario"] = (isset($_REQUEST["cod_usuario"]) ? $_REQUEST["cod_usuario"] : '');
                                $menu = new navegacion();
                                ?>
                            </td>
                        </tr>	
                        <tr>
                            <td>
                                <table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
                                    <tr class="texto_subtitulo">
                                        <td>
                                            Rol(es) del Usuario<br>
                                            <hr class="hr_subtitulo">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <table class='contenidotabla'>
                                                <tr class='cuadro_color'>

                                                    <td class='cuadro_plano centrar'>Rol </td>
                                                    <td class='cuadro_plano centrar'>Fecha Registro </td>
                                                    <td class='cuadro_plano centrar'>Fecha Suspender </td>
                                                    <td colspan='2' class='cuadro_plano centrar'>Opciones</td>
                                                    Opciones	</tr>			

                                                <?
                                                if (is_array($roles)) {
                                                    foreach ($roles as $key => $value) {
                                                        ?><tr> 
                                                            <td class='cuadro_plano'><?php echo $roles[$key]['ROL']; ?></td>    
                                                            <td class='cuadro_plano'><?php echo $roles[$key]['F_INI']; ?></td>    
                                                            <td class='cuadro_plano'><?php echo $roles[$key]['F_FIN']; ?></td>
                                                            <td align='center' width='10%' class='cuadro_plano' colspan='2'>
                                                                <?php if ($roles[$key]['ESTADO'] == 1) {
                                                                    ?>
                                                                    <a href='<?
                                                                    $enlace = "pagina=adminUsuario";
                                                                    $enlace.="&opcion=editarRol";
                                                                    $enlace.="&cod_usuario=" . $roles[$key]['ID_US'];
                                                                    $enlace.="&cod_rol=" . $roles[$key]['ID_ROL'];

                                                                    $enlace = $this->cripto->codificar_url($enlace, $configuracion);
                                                                    echo $indice . $enlace;
                                                                    ?>'><img width='24' heigth='24' src='<?php echo $configuracion["host"] . $configuracion["site"] . $configuracion["grafico"] . "/editar.png"; ?>' alt='Editar este registro' title='Editar este registro' border='0' />	
                                                                        <a href='<?
                                                                        $enlace = "pagina=adminUsuario";
                                                                        $enlace.="&opcion=inactivarRol";
                                                                        $enlace.="&cod_usuario=" . $roles[$key]['ID_US'];
                                                                        $enlace.="&cod_rol=" . $roles[$key]['ID_ROL'];

                                                                        $enlace = $this->cripto->codificar_url($enlace, $configuracion);
                                                                        echo $indice . $enlace;
                                                                        ?>'>
                                                                            <img width='24' heigth='24' src='<? echo $configuracion["host"] . $configuracion["site"] . $configuracion["grafico"] . "/boton_borrar.png" ?>' alt='Desactivar el registro' title='Desactivar el registro' border='0' /></a>	
                                                                    <?php } else {
                                                                        ?>
                                                                        <img width='24' heigth='24' src='<?php echo $configuracion["host"] . $configuracion["site"] . $configuracion["grafico"] . "/button_cancel.png"; ?>' alt='Registro Inactivo' title='Registro Inactivo' border='0' />
                                                                    <?php } ?>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?
                                $menu->menu_navegacion($configuracion, $_REQUEST["hoja"], $hojas, $variable);
                                ?>
                            </td>
                        </tr>

                        <?
                    }//fin if roles >0
                    ?>
                </tbody>
            </table>
            <?
        }//fin else existe cod_usuario
    }

    function inactivarRol($configuracion) {

        $usuario_rol = array(
            'cod_usuario' => $_REQUEST['cod_usuario'],
            'cod_rol' => $_REQUEST['cod_rol']
        );

        $cadena_sql = $this->sql->cadena_sql($configuracion, $this->acceso_db, "inactivar_rol", $usuario_rol);
        $inactivar_rol = $this->ejecutarSQL($configuracion, $this->acceso_db, $cadena_sql, "registrar");

        if ($inactivar_rol) {

            //VARIABLES PARA EL LOG
            $registro[0] = "INACTIVAR";
            $registro[1] = "el ";
            $registro[2] = "ROL";
            $registro[3] = $usuario_rol['cod_usuario'];
            $registro[4] = time();
            $registro[5] = "Inactiva el rol del usuario " . $usuario_rol['cod_usuario'];
            $registro[5] = " con el rol " . $usuario_rol['cod_rol'];
            $this->log_us->log_usuario($registro, $configuracion);
            echo "<script>alert('Se ha actualizado el rol con exito!')</script>";
        } else {
            echo "<script>alert('Imposible actualizar el rol')</script>";
        }
        $pagina = $configuracion["host"] . $configuracion["site"] . "/index.php?";
        $variable = "pagina=adminUsuario";
        $variable.= "&cod_usuario=" . $usuario_rol['cod_usuario'];
        $variable.= "&cod_rol=" . $usuario_rol['cod_rol'];
        $variable.= "&opcion=consultar";
        $variable = $this->cripto->codificar_url($variable, $configuracion);
        echo "<script>location.replace('" . $pagina . $variable . "')</script>";
    }

    function editarRol($configuracion, $buscaRol) {

        $usuario_sql = $this->sql->cadena_sql($configuracion, $this->acceso_db, "usuarios_todos", $buscaRol);
        $basicoUsuario = $this->ejecutarSQL($configuracion, $this->acceso_db, $usuario_sql, "busqueda");
        $this->mostrarRegistro($configuracion, $basicoUsuario, $configuracion['registro'], "multiplesUsuarios", "");
        $rol_sql = $this->sql->cadena_sql($configuracion, $this->acceso_db, "busqueda_rol", $buscaRol);
        $basicoRol = $this->ejecutarSQL($configuracion, $this->acceso_db, $rol_sql, "busqueda");
        $this->form_rol($configuracion, $basicoRol, $this->tema, "");
    }

    function nuevoRol($configuracion, $cod_usuario) {
        $busquedaUsuario = array(
            'criterio_busqueda' => "COD_US",
            'valor' => $cod_usuario);

        $registro = 0;

        $cadena_sql = $this->sql->cadena_sql($configuracion, $this->acceso_db, "usuarios_todos", $busquedaUsuario);
        $basicoUsuario = $this->ejecutarSQL($configuracion, $this->acceso_db, $cadena_sql, "busqueda");
        $this->mostrarRegistro($configuracion, $basicoUsuario, $configuracion['registro'], "multiplesUsuarios", "");
        $this->form_rol($configuracion, $registro, $this->tema, "");
    }

    function form_rol($configuracion, $registro, $tema, $estilo) {

        $indice = $configuracion["host"] . $configuracion["site"] . "/index.php?";

        /*         * ************************************************************************************************** */
        include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/html.class.php");

        $enlace = $this->acceso_db;
        $id_admin = $this->usuario;
        //usuario administrador echo $id_admin;				
        $html = new html();
        $tab = 1;

        $this->formulario = "admin_usuario";
        $this->verificar .= "&& seleccion_valida(" . $this->formulario . ",'id_rol')";
        ?>


        <script src="<? echo $configuracion["host"] . $configuracion["site"] . $configuracion["javascript"] ?>/funciones.js" type="text/javascript" language="javascript"></script>
        <script src="<? echo $configuracion["host"] . $configuracion["site"] . $configuracion["javascript"] ?>/md5.js" type="text/javascript" language="javascript"></script>		
        <script type="text/javascript" src="<? echo $this->configuracion["host"] . $this->configuracion["site"] . $this->configuracion["plugins"]; ?>/datepicker/js/datepicker.js"></script>
        <link href = "http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel = "stylesheet" type = "text/css"/>
        <script type = "text/javascript" src = "http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
        <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
        <link	href="<? echo $configuracion["host"] . $configuracion["site"] . $configuracion["bloques"] ?>/administracion/css/form_estilo.css"	rel="stylesheet" type="text/css" />
        <script>
                                    $(document).ready(function() {
                                        $("#fecha_inactiva").datepicker({
                                            changeMonth: true,
                                            changeYear: true,
                                            yearRange: '2000:c',
                                            dateFormat: 'dd/mm/yy',
                                            showButtonPanel: true,
                                            beforeShow: function() {
                                                $(".ui-datepicker").css('font-size', 12)
                                            }
                                        });
                                    });</script>

        <form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario; ?>'>
            <hr>

            <table width='80%' height="45" valign="top" >		
                <tr>
                    <td colspan="5"><font color="red" size="-2"  ><br>Todos los campos marcados con ( * ) son obligatorios. <br></font></td>
                </tr>
            </table>
            <?
            if ($registro == 0) {
                ?>
                <table width='80%'  class='formulario'  align='center'>
                    <tr class='bloquecentralcuerpobeige'><td  colspan='3'><hr class='hr_subtitulo'/>
                            <? echo "NUEVO ROL"; ?>
                            <hr class = 'hr_subtitulo'/></td></tr>

                    <td width='30%'><?
                        $texto_ayuda = "<b>Rol que se asignara al usuario.</b><br> ";
                        ?><font color="red" >*</font><span onmouseover="return escape('<? echo $texto_ayuda ?>')">Rol:</span>
                    </td>
                    <td>
                        <?
                        //Evaluamos el rol del usuario actual en el sistema, si es administrador general no tiene 
                        //restricciones en roles; si NO es administrador general, no puede crear usuarios con rol Administrador 
                        //general

                        $busquedaRol = "SELECT DISTINCT id_subsistema , etiqueta FROM " . $configuracion["prefijo"] . "subsistema ORDER BY etiqueta";
                        $metodo = $this->ejecutarSQL($configuracion, $this->acceso_db, $busquedaRol, "busqueda");
                        ?>

                        <select name='id_rol_nuevo'  title="*Campo Obligatorio" required='required' class="dropdown">
                            <?php
                            $var = "";
                            foreach ($metodo as $key => $value) {
                                $var.= "<option value='" . $metodo[$key]['id_subsistema'] . "'>" . $metodo[$key]['etiqueta'] . "</option>";
                            }
                            echo $var;
                            ?>   

                        </select>

                    </td>
                    </tr>		
                    <tr>
                        <td width='30%'><?
                            $texto_ayuda = "<b>Fecha de inactivaci&oacute;n del rol.</b><br> ";
                            $texto_ayuda .= "Fecha en formato: dd/mm/aaaa.<br>";
                            ?><font color="red" >*</font><span onmouseover="return escape('<? echo $texto_ayuda ?>')">Fecha Inactivaci&oacute;n:</span>
                        </td>
                        <td class='texto_elegante2 estilo_td'>
                            <input type='text' size='12' maxlength='25' readonly="readonly" id="fecha_inactiva" name='fecha_inactiva' value='<?
                            if (!$registro) {
                                echo "31/12/" . date("Y");
                            } else {
                                list($d, $m, $a) = explode("-", $registro[0]['F_FIN']);
                                echo $a . "/" . $m . "/" . $d;
                            }
                            ?>'
                                   />
                        </td>

                    </tr>	
                </table> 

                <table align='center'>
                    <tr align='center'>
                        <td colspan='2' rowspan='1' >
                            <input type='hidden' name='usuario' value='<? echo $id_admin; ?>'>
                            <input type='hidden' name='cod_usuario' value='<? echo $_REQUEST["cod_usuario"]; ?>' />
                            <input type='hidden' name='action' value='admin_usuario'/>

                            <input type='hidden' name='opcion' value='nuevoRol'/>

                            <input value="Guardar" name="Guardar" class="navbtn" type="submit">

                            <br>
                        </td>
                    </tr> 
                </table><?
            } else {

                $rol_actual = $registro[0]['ID_ROL'];
                $cadena_sql = $this->sql->cadena_sql($configuracion, $this->acceso_db, "busqueda_etiqueta", $rol_actual);
                $etiqueta = $this->ejecutarSQL($configuracion, $this->acceso_db, $cadena_sql, "busqueda");
                ?>
                <table width='80%'  class='formulario'  align='center'>
                    <tr class='bloquecentralcuerpobeige'><td  colspan='3'><hr class='hr_subtitulo'/>
                            <? echo "ACTUALIZAR FECHA DE INACTIVACIÓN DEL ROL"; ?>
                            <hr class = 'hr_subtitulo'/></td></tr>

                    <td width='30%'><?
                        $texto_ayuda = "<b>Rol que se asignara al usuario.</b><br> ";
                        ?><font color="red" >*</font><span onmouseover="return escape('<? echo $texto_ayuda ?>')">Rol:</span>
                    </td>
                    <td class='texto_elegante2 estilo_td'>
                        <? ECHO $etiqueta[0]['etiqueta'] ?>
                    </td>
                    </tr>		
                    <tr>
                        <td width='30%'><?
                            $texto_ayuda = "<b>Fecha de inactivaci&oacute;n del rol.</b><br> ";
                            $texto_ayuda .= "Fecha en formato: dd/mm/aaaa.<br>";
                            ?><font color="red" >*</font><span onmouseover="return escape('<? echo $texto_ayuda ?>')">Fecha Inactivaci&oacute;n:</span>
                        </td>
                        <td class='texto_elegante2 estilo_td'>
                            <input type='text' size='12' maxlength='25'  id="fecha_inactiva" name='fecha_inactiva' value='<?
                            if (!$registro) {
                                echo "31/12/" . date("Y");
                            } else {
                                list($d, $m, $a) = explode("-", $registro[0]['F_FIN']);
                                echo $a . "/" . $m . "/" . $d;
                            }
                            ?>'
                                   />
                        </td>
                    </tr>	
                </table> 

                <table align='center'>
                    <tr align='center'>
                        <td colspan='2' rowspan='1' >
                            <input type='hidden' name='usuario' value='<? echo $id_admin; ?>'>
                            <input type='hidden' name='cod_usuario' value='<? echo $_REQUEST["cod_usuario"]; ?>' />
                            <input type='hidden' name='action' value='admin_usuario'/>


                            <input type='hidden' name='id_rol_anterior' value='<? echo $registro[0]['ID_ROL']; ?>'/>
                            <input type='hidden' name='opcion' value='editarRol'/>

                            <input value="Guardar" name="Guardar" class="navbtn" type="submit">

                            <br>
                        </td>
                    </tr> 
                </table><?
            }
            ?>



        </form>		
        <?php
    }

    function guardarRol($configuracion) {

        //rescata los valores para ingresar los datos del usuario
        //----------------------------------------------------
        //cambia el fromato de la fecha a aaaa-mm-dd

        list($d, $m, $a) = explode("/", $_REQUEST["fecha_inactiva"]);

        $GuardaRol = array(
            'id_admin' => $_REQUEST["usuario"],
            'cod_usuario' => $_REQUEST["cod_usuario"],
            'cod_rol' => $_REQUEST["id_rol_nuevo"],
            'id_dependencia' => 0,
            'fecha_registro' => date('Y-m-d'),
            'fecha_inactiva' => $a . "-" . $m . "-" . $d,
            'estado' => '1');

        //evaluamos si el rol existe
        $existe_rol_sql = $this->sql->cadena_sql($configuracion, $this->acceso_db, "busqueda_rol", $GuardaRol);

        $rs_existe_rol = $this->ejecutarSQL($configuracion, $this->acceso_db, $existe_rol_sql, "busqueda");


        if (!is_array($rs_existe_rol)) {
            //ejecuta el ingreso del usuario

            $rol_sql = $this->sql->cadena_sql($configuracion, $this->acceso_db, "insertar_rol", $GuardaRol);

            $nvo_rol = $this->ejecutarSQL($configuracion, $this->acceso_db, $rol_sql, "registrar");

            $variable = "pagina=adminUsuario";
            $variable .= "&opcion=consultar";
            $variable .= "&cod_usuario=" . $GuardaRol['cod_usuario'];

            if ($nvo_rol) {

                //VARIABLES PARA EL LOG
                $registro[0] = "REGISTRAR";
                $registro[1]= $GuardaRol['id_admin'];
                $registro[2]= "ROL";
                $registro[3]= $GuardaRol['cod_usuario'];
                $registro[4]= time();
                $registro[5]= "Registra al usuario " . $GuardaRol['cod_usuario'];
                $registro[5]= " con el rol " . $GuardaRol['cod_rol'];
                $this->log_us->log_usuario($registro, $configuracion);
                echo "<script>alert('Se ha asignado el nuevo rol al Usuario con exito!')</script>";
            } else {
                echo "<script>alert('Imposible Asiganr el Rol')</script>";
            }
        }//fin if NO existencia de usuario	
        else {
            $variable = "pagina=adminUsuario";
            $variable .= "&opcion=nuevoRol";
            $variable .= "&cod_usuario=" . $GuardaRol['cod_usuario'];
            echo "<script>alert('YA EXISTE UN ROL ACTIVO, PARA EL USUARIO EN EL AREA SELECCIONADA')</script>";
        }
        $pagina = $configuracion["host"] . $configuracion["site"] . "/index.php?";
        $variable = $this->cripto->codificar_url($variable, $configuracion);
        echo "<script>location.replace('" . $pagina . $variable . "')</script>";
    }

    function actualizarRol($configuracion) {

        //rescata los valores para ingresar los datos del usuario
        //----------------------------------------------------
        //cambia el fromato de la fecha a aaaa-mm-dd

        list($d, $m, $a) = explode("/", $_REQUEST["fecha_inactiva"]);

        $EditaRol = array
            (
            'id_admin' => $_REQUEST["usuario"],
            'cod_usuario' => $_REQUEST["cod_usuario"],
            'cod_rol' => $_REQUEST["id_rol_anterior"],
            'fecha_inactiva' => $a . "-" . $m . "-" . $d,
            'estado' => '1');


        //evaluamos si el rol existe
        $existe_rol_sql = $this->sql->cadena_sql($configuracion, $this->acceso_db, "busqueda_rol", $EditaRol);

        $rs_existe_rol = $this->ejecutarSQL($configuracion, $this->acceso_db, $existe_rol_sql, "busqueda");


        if ($rs_existe_rol) {
            //ejecuta el ingreso del usuario
            $rol_sql = $this->sql->cadena_sql($configuracion, $this->acceso_db, "actualizar_rol", $EditaRol);
            $edit_rol = $this->ejecutarSQL($configuracion, $this->acceso_db, $rol_sql, "");


            $variable = "pagina=adminUsuario";
            $variable .= "&opcion=consultar";
            $variable .= "&cod_usuario=" . $EditaRol['cod_usuario'];

            if ($edit_rol) {

                //VARIABLES PARA EL LOG
                $registro[0] = "ACTUALIZAR";
                $registro[1] = $EditaRol['id_admin'];
                $registro[2] = "ROL";
                $registro[3] = $EditaRol['cod_usuario'];
                $registro[4] = time();
                $registro[5] = "Actualiza la fecha de inactivacion a " . $EditaRol['fecha_inactiva'];
                $registro[6] = "para el usuario " . $EditaRol['cod_usuario'];
                $registro[7] = " y el rol " . $EditaRol['cod_rol'];
                $this->log_us->log_usuario($registro, $configuracion);
                echo "<script>alert('Se ha actualizado el Rol con exito!')</script>";
            } else {
                echo "false";
                exit;
                echo "<script>alert('Imposible Actualizar el Rol')</script>";
            }
        }//fin if NO existencia de usuario	
        else {
            $variable = "pagina=adminUsuario";
            $variable .= "&opcion=editarRol";
            $variable .= "&cod_usuario=" . $EditaRol['cod_usuario'];
            echo "<script>alert('NO EXISTE EL ROL ACTIVO, PARA EL USUARIO EN EL AREA SELECCIONADA')</script>";
        }
        $pagina = $configuracion["host"] . $configuracion["site"] . "/index.php?";
        $variable = $this->cripto->codificar_url($variable, $configuracion);
        echo "<script>location.replace('" . $pagina . $variable . "')</script>";
    }

    function multiplesUsuarios($configuracion, $registro, $total, $variable) {
        include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
        $indice = $configuracion["host"] . $configuracion["site"] . "/index.php?";
        $cripto = new encriptar();
        ?><table width="80%" align="center" border="0" cellpadding="10" cellspacing="0" >
            <tbody>
                <tr>
                    <td >
                        <table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
                            <tr class="texto_subtitulo">
                                <td>
                                    Usuarios<br>
                                    <hr class="hr_subtitulo">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <table class='contenidotabla'>
                                        <tr class='cuadro_color'>
                                            <td class='cuadro_plano centrar ancho10' >Identificaci&oacute;n</td>
                                            <td class='cuadro_plano centrar'>Nombre </td>
                                            <td class='cuadro_plano centrar'>Usuario </td>
                                        </tr><?
                                        foreach ($registro as $key => $value) {
                                            $parametroSolicitud[0] = $registro[$key]['ID_US'];

                                            //Con enlace a la busqueda
                                            $parametro = "pagina=adminUsuario";
                                            $parametro .= "&hoja=1";
                                            $parametro .= "&opcion=consultar";
                                            $parametro .= "&accion=consultar";
                                            $parametro .= "&cod_usuario=" . $registro[$key]['ID_US'];
                                            $parametro = $cripto->codificar_url($parametro, $configuracion);
                                            echo "	<tr> 
										 	<td class='cuadro_plano centrar'>" . $registro[$key]['IDENT'] . "</td>
                                                                                        <td class='cuadro_plano'>" . $registro[$key]['NOMBRE'] . "</td>    
											<td class='cuadro_plano'><a href='" . $indice . $parametro . "'>" . $registro[$key]['NICK'] . "</a></td>
										</tr>";
                                        }//fin for 
                                        ?>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td class='cuadro_plano cuadro_brown'>
                        <p class="textoNivel0">Click sobre el USUARIO a Consultar.</p>
                    </td>
                </tr>
            </tbody>
        </table>
        <?
    }

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
        $variable = "pagina=adminUsuario";
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

    function formContrasena($configuracion, $registro, $tema, $estilo) {
        $indice = $configuracion["host"] . $configuracion["site"] . "/index.php?";

        /*         * ************************************************************************************************** */
        include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/html.class.php");

        $enlace = $this->acceso_db;
        $id_usuario = $this->usuario;
        $html = new html();
        $tab = 1;
        $this->formulario = "admin_usuario";
        $this->verificar .= "control_vacio(" . $this->formulario . ",'contrasena')";
        $this->verificar .= " && longitud_cadena(" . $this->formulario . ",'contrasena',6)";
        $this->verificar .= "&& control_vacio(" . $this->formulario . ",'reescribir_contrasena')";
        $this->verificar .= "&& longitud_cadena(" . $this->formulario . ",'reescribir_contrasena',6)";
        $this->verificar .= "&& comparar_contenido(" . $this->formulario . ",'contrasena','reescribir_contrasena')";
        ?>		
        <script src="<? echo $configuracion["host"] . $configuracion["site"] . $configuracion["javascript"] ?>/funciones.js" type="text/javascript" language="javascript"></script>
        <script src="<? echo $configuracion["host"] . $configuracion["site"] . $configuracion["javascript"] ?>/md5.js" type="text/javascript" language="javascript"></script>		
        <form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario; ?>'>
            <hr>
            <?
            //Mostramos los datos del usuario 
            ?>
            <table width='80%'  class='formulario'  align='center'>
                <tr class='bloquecentralcuerpobeige'><td  colspan='3'><hr class='hr_subtitulo'/>DATOS DE USUARIO

                        <hr class='hr_subtitulo'/></td></tr>		
            </table>
            <table class='contenidotabla'>
                <tr >
                    <td class='cuadro_plano centrar ancho10' >Código:</td>
                    <td class='cuadro_plano '><? echo $registro[0][0] ?></td>
                </tr>	
                <tr >
                    <td class='cuadro_plano centrar ancho10' >Nombre(s):</td>
                    <td class='cuadro_plano '><? echo $registro[0][1] ?></td>
                </tr>			
                <tr >
                    <td class='cuadro_plano centrar ancho10' >Apellido(s):</td>
                    <td class='cuadro_plano '><? echo $registro[0][2] ?></td>
                </tr>			
                <tr >
                    <td class='cuadro_plano centrar ancho10' >Correo</td>
                    <td class='cuadro_plano '><? echo $registro[0][3] ?></td>
                </tr>			
                <tr >
                    <td class='cuadro_plano centrar ancho10' >Teléfono</td>
                    <td class='cuadro_plano '><? echo $registro[0][4] ?></td>
                </tr>			
                <tr >
                    <td class='cuadro_plano centrar ancho10' >Celular</td>
                    <td class='cuadro_plano '><? echo $registro[0][6] ?></td>
                </tr>			
                <tr >
                    <td class='cuadro_plano centrar ancho10' >Identificación</td>
                    <td class='cuadro_plano '><? echo $registro[0][7] ?></td>
                </tr>			
            </table>

            <table width='80%'  class='formulario'  align='center'>
                <tr class='bloquecentralcuerpobeige'><td  colspan='3'><hr class='hr_subtitulo'/>CAMBIO DE CONTRASEÑA

                        <hr class='hr_subtitulo'/></td></tr>		
                <tr>
                    <td width='30%'><?
                        $texto_ayuda = "<b>Nueva contraseña para el usuario.</b><br> ";
                        ?><font color="red" >*</font><span onmouseover="return escape('<? echo $texto_ayuda ?>')">Nueva Contraseña:</span>
                    </td>
                    <td>
                        <input type='password' name='contrasena' size='40' maxlength='50' tabindex='<? echo $tab++ ?>'   >
                    </td>
                </tr>		
                <tr>
                    <td width='30%'><?
                        $texto_ayuda = "<b>Reescriba la nueva contraseña.</b><br> ";
                        ?><font color="red" >*</font><span onmouseover="return escape('<? echo $texto_ayuda ?>')">Confirmar contraseña:</span>
                    </td>
                    <td>
                        <input type='password' name='reescribir_contrasena' size='40' maxlength='50' tabindex='<? echo $tab++ ?>'  >
                    </td>
                </tr>		
            </table>
            <table align='center'>
                <tr align='center'>
                    <td colspan='2' rowspan='1'>
                        <input type='hidden' name='usuario' value='<? echo (isset($_REQUEST["usuario"]) ? $_REQUEST["usuario"] : ''); ?>'>
                        <input type='hidden' name='action' value='admin_usuario'>
                        <input type='hidden' name='opcion' value='cambiar_clave'>
                        <input value="Guardar" name="aceptar" tabindex='<?= $tab++ ?>' type="button" 	
                               onclick="if (<?= $this->verificar; ?>) {<? echo $this->formulario ?>.contrasena.value = hex_md5(<? echo $this->formulario ?>.contrasena.value);<? echo $this->formulario ?>.reescribir_contrasena.value = hex_md5(<? echo $this->formulario ?>.reescribir_contrasena.value);
                                                   document.forms['<? echo $this->formulario ?>'].submit()
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

        <?
    }

    function editarContrasena($configuracion) {

        //rescata los valores para editar la contrasena
        //----------------------------------------------------
        $datos_usuario[0] = $this->usuario;
        $datos_usuario[1] = $_REQUEST['contrasena'];

        //ejecuta la modificacion de la contraseña de usuario

        $usuario_sql = $this->sql->cadena_sql($configuracion, $this->acceso_db, "editar_contrasena", $datos_usuario);
        //echo "<br>sql mod pw ".$usuario_sql;
        @$usu = $this->ejecutarSQL($configuracion, $this->acceso_db, $usuario_sql, "");
        $pagina = $configuracion["host"] . $configuracion["site"] . "/index.php?";
        $variable = "pagina=adminUsuario";
        $variable.= "&opcion=cambiar_clave";
        $variable = $this->cripto->codificar_url($variable, $configuracion);

        //verificamos que se halla ejecutado la consulta con exito.		
        if (@$usu) {
            //VARIABLES PARA EL LOG
            $registro[0] = "EDITAR";
            $registro[1] = $nvo_usuario[0];
            $registro[2] = "CONTRASEÑA USUARIO";
            $registro[3] = $nvo_usuario[0];
            $registro[4] = time();
            $registro[5] = "Modifica la contraseña del usuario " . $nvo_usuario[0];
            $this->log_us->log_usuario($registro, $configuracion);
            unset($_REQUEST['action']);

            echo "<script>alert('Contraseña de Usuario modificada con exito!')</script>";
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
        } else {
            echo "<script>alert('Imposible Modificar la contraseña')</script>";
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
        }
    }

    function cambiarEstado($configuracion) {

        //ejecuta la modificacion del estado del usuario
        $datos_usuario[0] = $_REQUEST['cod_usuario'];
        $datos_usuario[1] = $_REQUEST['dato'];
        $usuario_sql = $this->sql->cadena_sql($configuracion, $this->acceso_db, "cambiar_estado", $datos_usuario);
        $usu = $this->ejecutarSQL($configuracion, $this->acceso_db, $usuario_sql, "");
        $pagina = $configuracion["host"] . $configuracion["site"] . "/index.php?";
        $variable = "pagina=adminUsuario";
        $variable .= "&opcion=consultar";
        $variable .= "&cod_usuario=" . $datos_usuario[0];
        $variable = $this->cripto->codificar_url($variable, $configuracion);

        //verificamos que se halla ejecutado la consulta con exito.		
        if ($usu) {
            //VARIABLES PARA EL LOG
            $registro[0] = "EDITAR";
            $registro[1] = $datos_usuario[0];
            $registro[2] = "USUARIO";
            $registro[3] = $datos_usuario[0];
            $registro[4] = time();
            $registro[5] = "Modifica Estado del usuario " . $datos_usuario[0];
            if ($datos_usuario[1] == 1) {
                $registro[5] .= " a ACTIVO";
            } else {
                $registro[5] .= " a INACTIVO ";
            }

            $this->log_us->log_usuario($registro, $configuracion);
            unset($_REQUEST['action']);

            echo "<script>alert('Estado de Usuario modificado con exito!')</script>";
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
        } else {
            echo "<script>alert('Imposible Modificar el estado')</script>";
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
        }
    }

}

// fin de la clase
?>



