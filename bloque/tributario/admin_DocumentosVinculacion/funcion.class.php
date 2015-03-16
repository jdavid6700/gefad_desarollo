<?php
/* --------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
  --------------------------------------------------------------------------------------------------------------------------- */

if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}


include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/funcionGeneral.class.php");

class funciones_adminVinculacion extends funcionGeneral {

    //Crea un objeto tema y un objeto SQL.
    private $pagina;
    private $opcion;
    private $configuracion;

    function __construct($configuracion, $sql) {

        include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
        include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/html.class.php");
        include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/log.class.php");


        $indice = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
        $this->indice = $configuracion["host"] . $configuracion["site"] . "/index.php?";
        $this->html = new html();
        $this->cripto = new encriptar();
        $this->sql = $sql;

        //Conexion General
        $this->acceso_db = $this->conectarDB($configuracion, "");
        //Conexion SICAPITAL
        $this->accesoSICAPITAL = $this->conectarDB($configuracion, "oracleSIC");
        //Conexion Oracle
        $this->accesoOracle = $this->conectarDB($configuracion, "tributario_planta");
        //Conexion postgres
        $this->acceso_pg = $this->conectarDB($configuracion, "tributario");

        $this->usuario = $this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion = $this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");

        $this->pagina = "adminDocumentosVinculacion";
        $this->configuracion = $configuracion;
        $this->log_us = new log();
        ?> <link href = "<? echo $this->configuracion["host"] . $this->configuracion["site"] . $this->configuracion["bloques"] ?>/tributario/admin_DocumentosVinculacion/form_estilo.css" rel = "stylesheet" type = "text/css" />

        <script language="javascript">
            <!--
            var nav4 = window.Event ? true : false;
            function acceptNum(evt)
            {
                // NOTE: Backspace = 8, Enter = 13, '0' = 48, '9' = 57
                var key = nav4 ? evt.which : evt.keyCode;
                return (key <= 13 || (key >= 48 && key <= 57));
            }
            //-->
        </script>
        <?
    }

    /**
     * Utiliza los metodos insertarDatos, mostrarInicio, historialVinculacion
     */
    function insertarDatos($resp_encuesta) {


        if (isset($resp_encuesta['func_documento']) && $resp_encuesta['func_documento'] == $this->identificacion) {

            $id_num = $resp_encuesta['func_documento'];
            $id_tipo = $resp_encuesta['tipo_documento'];
            $vigencia = $resp_encuesta['annio'];
            $contrato = $resp_encuesta['contrato'];
            $fec_registro = $resp_encuesta['fecha_registro'];
            $id_enc = $resp_encuesta['id_encuesta'];
            $cont_ini = $resp_encuesta['cont_ini'];    

            $parametros = array(
                'id_enc' => $id_enc,
                'id_num' => $id_num,
                'id_tipo' => $id_tipo,
                'vigencia' => $vigencia,
                'contrato' => $contrato,
                'fecha_reg' => $fec_registro);

            $cont = $cont_ini;

            foreach ($resp_encuesta as $key => $values) {
                if ($key == 'id_pregunta' . $cont && ($resp_encuesta['respuesta_' . $cont] != 'SI' && $resp_encuesta['respuesta_' . $cont] != 'NO')) {
                    echo "<script type=\"text/javascript\">" .
                    "alert('Formulario NO diligenciado correctamente');" .
                    "</script> ";
                    unset($resp_encuesta);
                    $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                    $variable = 'pagina=encuestaTributario';
                    $variable.='&opcion=';
                    $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                    echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                    exit;
                } elseif ($key == 'id_pregunta' . $cont) {
                    $cont++;
                }
            }
            $cont = $cont_ini;
            foreach ($resp_encuesta as $key => $values) {
                
                if ($key == 'id_pregunta' . $cont) {

                    $parametros['id_preg'] = $resp_encuesta['id_pregunta' . $cont];
                    $parametros['resp'] = $resp_encuesta['respuesta_' . $cont];

                    $cadena_sql = $this->sql->cadena_sql("insertar_respuestas", $parametros);
                    $insertar = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");

                    $registro[0] = "GUARDAR";
                    $registro[1] = $parametros['id_num'] . '|' . $parametros['id_enc'] . '|' . $parametros['id_preg']; //
                    $registro[2] = "TRIBUTARIO";
                    $registro[3] = $parametros['id_enc'] . '|' . $parametros['id_preg'] . '|' . $parametros['resp']; //
                    $registro[4] = time();
                    $registro[5] = "Registra datos insertados a la base de datos para el usuario con  ";
                    $registro[5] .= " identificacion =" . $parametros['id_num'];
                    $this->log_us->log_usuario($registro, $this->configuracion);
                    $cont++;
                }
            }


            $variable = 'pagina=asistenteTributario';
            $variable.='&opcion=';
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            ?>
            <script language="javascript">    window.location.href = "<? echo $this->indice . $variable; ?>"</script>
            <?
        } else {

            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = "pagina=asistenteTributario";
            $variable .= "&opcion=";
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
        }
    }

    function actualizarDatosBasicos($datos_actualizar) {


        if (isset($datos_actualizar['id_usuario']) && $datos_actualizar['id_usuario'] == $this->identificacion) {

            $tipo_BD = $datos_actualizar['tipo_bd'];

            if (!isset($datos_actualizar['datos_tel']) || !isset($datos_actualizar['datos_e']) || !isset($datos_actualizar['datos_dir'])) {
                echo "<script type=\"text/javascript\">" .
                "alert('Formulario NO diligenciado correctamente');" .
                "</script> ";
                $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                $variable = 'pagina=encuestaTributario';
                $variable.='&opcion=';
                $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                exit;
            }

            foreach ($datos_actualizar as $key => $values) {
                if ($datos_actualizar[$key] == "") {
                    echo "<script type=\"text/javascript\">" .
                    "alert('Formulario NO diligenciado correctamente');" .
                    "</script> ";
                    $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                    $variable = 'pagina=encuestaTributario';
                    $variable.='&opcion=';
                    $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                    echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                    exit;
                }
            }

            $parametros2 = array(
                'email' => $datos_actualizar['datos_e'],
                'direccion' => $datos_actualizar['datos_dir'],
                'telefono' => $datos_actualizar['datos_tel'],
                'identificacion' => $datos_actualizar['id_usuario'],
                'co_fecha_inicial' => $datos_actualizar['co_fecha_inicial'],
                'ID' => $datos_actualizar['id_bd']
            );

            if ($tipo_BD == 'SHD') {

                $cadena_sql_tel = $this->sql->cadena_sql("existenciaDatosTEL_SHD", $parametros2);
                $e_datosTEL = $this->ejecutarSQL($this->configuracion, $this->accesoSICAPITAL, $cadena_sql_tel, "busqueda");

                $cadena_sql_email = $this->sql->cadena_sql("existenciaDatosEMAIL_SHD", $parametros2);
                $e_datosEMAIL = $this->ejecutarSQL($this->configuracion, $this->accesoSICAPITAL, $cadena_sql_email, "busqueda");

                $cadena_sql_dir = $this->sql->cadena_sql("existenciaDatosDIR_SHD", $parametros2);
                $e_datosDIR = $this->ejecutarSQL($this->configuracion, $this->accesoSICAPITAL, $cadena_sql_dir, "busqueda");

                //ACTUALIZACIÓN DIRECCION
                if ($e_datosDIR == "") {
                    $cadena_sql_1 = $this->sql->cadena_sql("insertaDatosDIR", $parametros2);
                    $inserta_dir = $this->ejecutarSQL($this->configuracion, $this->accesoSICAPITAL, $cadena_sql_1, "busqueda");

                    $registro[0] = "REGISTRAR DATOS BASICOS";
                    $registro[1] = $datos_actualizar['id_usuario'];
                    $registro[2] = "TRIBUTARIO";
                    $registro[3] = 'DIR | '. $datos_actualizar['datos_dir']; //
                    $registro[4] = time();
                    $registro[5] = "Registra datos básicos del usuario en la base de datos para el usuario con  ";
                    $registro[5] .= " identificacion =" . $datos_actualizar['id_usuario'];
                    $this->log_us->log_usuario($registro, $this->configuracion);
                } else {
                    $cadena_sql_2 = $this->sql->cadena_sql("actualizaDatosDIR", $parametros2);
                    $this->ejecutarSQL($this->configuracion, $this->accesoSICAPITAL, $cadena_sql_2, "busqueda");

                    $registro[0] = "ACTUALIZAR DATOS BASICOS";
                    $registro[1] = $datos_actualizar['id_usuario'];
                    $registro[2] = "TRIBUTARIO";
                    $registro[3] = 'DIR | '. $datos_actualizar['datos_dir'];
                    $registro[4] = time();
                    $registro[5] = "Actualiza datos básicos del usuario en la base de datos para el usuario con  ";
                    $registro[5] .= " identificacion =" . $datos_actualizar['id_usuario'];
                    $this->log_us->log_usuario($registro, $this->configuracion);
                }
                echo '<br>';
                //ACTUALIZACIÓN TELEFONO

                if ($e_datosTEL == "") {
                    $cadena_sql_3 = $this->sql->cadena_sql("insertaDatosTEL", $parametros2);
                    $inserta_dir = $this->ejecutarSQL($this->configuracion, $this->accesoSICAPITAL, $cadena_sql_3, "busqueda");

                    $registro[0] = "REGISTRAR DATOS BASICOS";
                    $registro[1] = $datos_actualizar['id_usuario'];
                    $registro[2] = "TRIBUTARIO";
                    $registro[3] = 'TEL | '. $datos_actualizar['datos_tel']; //
                    $registro[4] = time();
                    $registro[5] = "Registra datos básicos del usuario en la base de datos para el usuario con  ";
                    $registro[5] .= " identificacion =" . $datos_actualizar['id_usuario'];
                    $this->log_us->log_usuario($registro, $this->configuracion);
                } else {
                    $cadena_sql_4 = $this->sql->cadena_sql("actualizaDatosTEL", $parametros2);
                    $this->ejecutarSQL($this->configuracion, $this->accesoSICAPITAL, $cadena_sql_4, "busqueda");

                    $registro[0] = "ACTUALIZAR DATOS BASICOS";
                    $registro[1] = $datos_actualizar['id_usuario'];
                    $registro[2] = "TRIBUTARIO";
                    $registro[3] = 'TEL | '. $datos_actualizar['datos_tel']; //
                    $registro[4] = time();
                    $registro[5] = "Actualiza datos básicos del usuario en la base de datos para el usuario con  ";
                    $registro[5] .= " identificacion =" . $datos_actualizar['id_usuario'];
                    $this->log_us->log_usuario($registro, $this->configuracion);
                }
                echo '<br>';

                //ACTUALIZACIÓN EMAIL
                if ($e_datosEMAIL == "") {
                    $cadena_sql_5 = $this->sql->cadena_sql("insertaDatosEMAIL", $parametros2);
                    $inserta_dir = $this->ejecutarSQL($this->configuracion, $this->accesoSICAPITAL, $cadena_sql_5, "busqueda");

                    $registro[0] = "REGISTRAR DATOS BASICOS";
                    $registro[1] = $datos_actualizar['id_usuario'];
                    $registro[2] = "TRIBUTARIO";
                    $registro[3] = 'EMAIL | '. $datos_actualizar['datos_e']; //
                    $registro[4] = time();
                    $registro[5] = "Registra datos básicos del usuario en la base de datos para el usuario con  ";
                    $registro[5] .= " identificacion =" . $datos_actualizar['id_usuario'];
                    $this->log_us->log_usuario($registro, $this->configuracion);
                } else {
                    $cadena_sql_6 = $this->sql->cadena_sql("actualizaDatosEMAIL", $parametros2);
                    $this->ejecutarSQL($this->configuracion, $this->accesoSICAPITAL, $cadena_sql_6, "busqueda");

                    $registro[0] = "ACTUALIZAR DATOS BASICOS";
                    $registro[1] = $datos_actualizar['id_usuario'];
                    $registro[2] = "TRIBUTARIO";
                    $registro[3] = 'EMAIL | '. $datos_actualizar['datos_e']; //
                    $registro[4] = time();
                    $registro[5] = "Actualiza datos básicos del usuario en la base de datos para el usuario con  ";
                    $registro[5] .= " identificacion =" . $datos_actualizar['id_usuario'];
                    $this->log_us->log_usuario($registro, $this->configuracion);
                }


                $variable = 'pagina=asistenteTributario';
                $variable.='&opcion=';
                $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                ?>
                <script language="javascript">    window.location.href = "<? echo $this->indice . $variable; ?>"</script>
                <?
            }


            if ($tipo_BD == 'PEEMP') {

                //ACTUALIZACION DIRECCION
                $cadena_sql = $this->sql->cadena_sql("actualizaDatosDIR_PEEMP", $parametros2);
                $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");

                //ACTUALIZACION TELEFONO
                $cadena_sql2 = $this->sql->cadena_sql("actualizaDatosTEL_PEEMP", $parametros2);
                $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql2, "busqueda");

                //ACTUALIZACION EMAIL
                $cadena_sql3 = $this->sql->cadena_sql("actualizaDatosEMAIL_PEEMP", $parametros2);
                $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql3, "busqueda");


                $registro[0] = "ACTUALIZAR DATOS BASICOS";
                $registro[1] = $datos_actualizar['id_usuario'];
                $registro[2] = "TRIBUTARIO";
                $registro[3] = 'DIR | TEL | EMAIL | '. $datos_actualizar['datos_dir'].'|'.$datos_actualizar['datos_tel'].'|'.$datos_actualizar['datos_e']; //
                $registro[4] = time();
                $registro[5] = "Actualiza datos básicos del usuario en la base de datos para el usuario con  ";
                $registro[5] .= " identificacion =" . $datos_actualizar['id_usuario'];
                $this->log_us->log_usuario($registro, $this->configuracion);


                $variable = 'pagina=asistenteTributario';
                $variable.='&opcion=';
                $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                ?>
                <script language="javascript">    window.location.href = "<? echo $this->indice . $variable; ?>"</script>
                <?
            }
        } else {

            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = "pagina=asistenteTributario";
            $variable .= "&opcion=";
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
        }
    }

    function actualizarRespuestas($respuestas_actualizar) {

        if (isset($respuestas_actualizar['func_documento']) && $respuestas_actualizar['func_documento'] == $this->identificacion) {

            $id_num = $respuestas_actualizar['func_documento'];
            $id_tipo = $respuestas_actualizar['tipo_documento'];
            $vigencia = $respuestas_actualizar['annio'];
            $contrato = $respuestas_actualizar['contrato'];
            $fec_registro = $respuestas_actualizar['fecha_registro'];
            $id_enc = $respuestas_actualizar['id_encuesta'];
            $cont_ini = $respuestas_actualizar['cont_ini'];

            $parametros = array(
                'id_enc' => $id_enc,
                'id_num' => $id_num,
                'id_tipo' => $id_tipo,
                'vigencia' => $vigencia,
                'contrato' => $contrato,
                'fecha_reg' => $fec_registro);

            $cont = $cont_ini;

            foreach ($respuestas_actualizar as $key => $values) {
                if ($key == 'id_pregunta' . $cont && ($respuestas_actualizar['respuesta_' . $cont] != 'SI' && $respuestas_actualizar['respuesta_' . $cont] != 'NO')) {
                    echo "<script type=\"text/javascript\">" .
                    "alert('Formulario NO diligenciado correctamente');" .
                    "</script> ";
                    unset($respuestas_actualizar);
                    $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                    $variable = 'pagina=encuestaTributario';
                    $variable.='&opcion=';
                    $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                    echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                    exit;
                } elseif ($key == 'id_pregunta' . $cont) {
                    $cont++;
                }
            }

            $cont = $cont_ini;
            foreach ($respuestas_actualizar as $key => $values) {

                if ($key == 'id_pregunta' . $cont) {

                    $parametros['id_preg'] = $respuestas_actualizar['id_pregunta' . $cont];
                    $parametros['resp'] = $respuestas_actualizar['respuesta_' . $cont];

                    $cadena_sql = $this->sql->cadena_sql("actualizar_respuestas", $parametros);
                    $actualizar = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");

                    $registro[0] = "ACTUALIZAR";
                    $registro[1] = $parametros['id_num'] . '|' . $parametros['id_enc'] . '|' . $parametros['id_preg']; //
                    $registro[2] = "TRIBUTARIO";
                    $registro[3] = $parametros['id_enc'] . '|' . $parametros['id_preg'] . '|' . $parametros['resp']; //
                    $registro[4] = time();
                    $registro[5] = "Actualiza datos insertados a la base de datos para el usuario con  ";
                    $registro[5] .= " identificacion =" . $parametros['id_num'];
                    $this->log_us->log_usuario($registro, $this->configuracion);
                    $cont++;
                }
            }

            $variable = 'pagina=asistenteTributario';
            $variable.='&opcion=';
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            ?>
            <script language="javascript">    window.location.href = "<? echo $this->indice . $variable; ?>"</script>
            <?
        } else {

            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = "pagina=asistenteTributario";
            $variable .= "&opcion=";
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
        }
    }

    function mostrarInicio() {

        $this->formulario = "admin_DocumentosVinculacion";

        if (date('m') <= '07') {
            $per = '1';
        } else {
            $per = '3';
        }

        $funcionario = array('identificacion' => $this->identificacion,
            'anio' => date('Y'),
            'periodo' => $per);

        $cadena_sql = $this->sql->cadena_sql("datosUsuarioSDH", $funcionario);
        $cadena_sql2 = $this->sql->cadena_sql("datosUsuario", $funcionario);
        $cadena_sql3 = $this->sql->cadena_sql("datos_contrato", $funcionario);

        $cadena_sql9 = $this->sql->cadena_sql("consultar_telefono_SHD", $funcionario);
        $cadena_sql8 = $this->sql->cadena_sql("consultar_direccion_SHD", $funcionario);
        $cadena_sql7 = $this->sql->cadena_sql("consultar_email_SHD", $funcionario);

        $datosSHD = $this->ejecutarSQL($this->configuracion, $this->accesoSICAPITAL, $cadena_sql, "busqueda");
        $datosUser = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql2, "busqueda");

        $datos_email = $this->ejecutarSQL($this->configuracion, $this->accesoSICAPITAL, $cadena_sql7, "busqueda");
        $datos_dir = $this->ejecutarSQL($this->configuracion, $this->accesoSICAPITAL, $cadena_sql8, "busqueda");
        $datos_tel = $this->ejecutarSQL($this->configuracion, $this->accesoSICAPITAL, $cadena_sql9, "busqueda");


        if ($datosUser == "") {
            $datosusuario = $datosSHD;
            $tipoBD = 'SHD';
        } else {
            $datosusuario = $datosUser;
            $tipoBD = 'PEEMP';
        }
        ?>
        <script src="<? echo $this->configuracion["host"] . $this->configuracion["site"] . $this->configuracion["javascript"] ?>/jquery.js" type="text/javascript" language="javascript"></script>

        <table class="bordered" width="100%" border="0" align="center" cellpadding="4 px" cellspacing="1px" >

            <th class='titulo_th' colspan ="5" >FUNCIONARIO </th>    <tbody>
                <tr>
                    <td align="center"  class='cuadro_plano '>

                        <form method="post" action="index.php" name='<? echo $this->formulario; ?>' onSubmit="return checkrequired(this)">
                            <table class="bordered"  width="100%">
                                <tr>
                                    <td class='texto_elegante estilo_td2' width='20%' height="15">
                                        Tipo de documento:
                                    </td>
                                    <td class='texto_elegante estilo_td2' width='20%' align="left">
                                        <?
                                        if ($datosusuario[0]['PLA_TIPO_IDEN'] = 1)
                                            echo 'CC';
                                        ?>
                                    </td>
                                    <td class='texto_elegante estilo_td2' width='20%'>
                                        Identificaci&oacute;n:
                                    </td>
                                    <td class='texto_elegante estilo_td2' width='20%' align="left">
                                        <? echo $datosusuario[0]['PLA_NRO_IDEN']; ?>
                                    </td>

                                </tr>

                                <tr>
                                    <td class='texto_elegante estilo_td2' width='15%' height="15">
                                        Nombre:
                                    </td>
                                    <td class='texto_elegante estilo_td2' width='15%' align="left">
                                        <? echo $datosusuario[0]['PLA_NOMBRE1'] . ' ' . $datosusuario[0]['PLA_NOMBRE2']; ?>
                                    </td>
                                    <td class='texto_elegante estilo_td2'  width=15%' height="15">
                                        Apellido:
                                    </td>
                                    <td class='texto_elegante estilo_td2' width='15%' align="left">
                                        <? echo $datosusuario[0]['PLA_APELLIDO1'] . ' ' . $datosusuario[0]['PLA_APELLIDO2']; ?>
                                    </td> </tr>

                                <tr><th colspan="4" class = 'subtitulo_th centrar' > Datos Básicos</th></tr>


                                <tr>
                                    <td class='texto_elegante estilo_td2' width='10%' height="10">
                                        Email:
                                    </td>

                                    <td class='texto_elegante estilo_td2' width='10%' align="left">

                                        <?
                                        if ($datosusuario[0]['PLA_EMAIL'] == " ") {
                                            if ($datos_email[0]['MAIL'] == "") {
                                                echo "<input type='text' name='datos_e' size='25' required='required' value=''>";
                                            } else {

                                                echo "<input type='text' name='datos_e' size='30' required='required' style='font-color:red' value='" . $datos_email[0]['MAIL'] . "'>";
                                            }
                                        } else {

                                            echo "<input type='text' name='datos_e' size='30' required='required' value='" . $datosusuario[0]['PLA_EMAIL'] . "'>";
                                        }
                                        ?>

                                    </td>

                                </tr>
                                <tr>
                                    <td class='texto_elegante estilo_td2' width='10%' height="10">
                                        Dirección:
                                    </td>
                                    <td class='texto_elegante estilo_td2' width='15%' align="left">


                                        <?
                                        if ($datosusuario[0]['PLA_DIRECC'] == "") {
                                            if ($datos_dir[0]['DATO_B_V_DIR'] == "") {
                                                echo "<input type='text' name='datos_dir' size='25' required='required' value=''>";
                                            } else {
                                                echo "<input type='text' name='datos_dir' size='25' required='required' value='" . $datos_dir[0]['DATO_B_V_DIR'] . "'>";
                                            }
                                        } else {

                                            echo "<input type='text' name='datos_dir' size='25' required='required' value='" . $datosusuario[0]['PLA_DIRECC'] . "'>";
                                        }
                                        ?>


                                    </td>
                                    <td class='texto_elegante estilo_td2' width='15%' height="10">
                                        Teléfono:
                                    </td>


                                    <td class='texto_elegante estilo_td2' width='15%' align="left">
                                        <?
                                        if ($datosusuario[0]['PLA_TELE'] == "") {
                                            if ($datos_tel[0]['DATO_B_V_TEL'] == "") {

                                                echo "<input type='text' name='datos_tel' size='25' required='required' onKeyPress='return acceptNum(event)' value=''>";
                                            } else {
                                                echo "<input type='text' name='datos_tel' size='25' required='required' onKeyPress='return acceptNum(event)' style='font-color:red' value='" . $datos_tel[0]['DATO_B_V_TEL'] . "'>";
                                            }
                                        } else {
                                            echo "<input type='text' name='datos_tel' size='25' required='required' onKeyPress='return acceptNum(event)' value='" . $datosusuario[0]['PLA_TELE'] . "'>";
                                        }
                                        ?>
                                    </td>
                                </tr>

                                <tr align="center" valign="middle">
                                    <td colspan="4" class='texto_elegante estilo_td2' style="vertical-align: middle; text-align:center; ">
                                        <br><br>
                                <center>
                                    <input type="submit" class="navbtn"  value="Actualizar Datos">

                                    <? //onClick="document.getElementById('datos').disabled = true;                                           ?>

                                    <input type='hidden' name='opcion' value='actualizar'>
                                    <input type='hidden' name='action' value='<? echo $this->formulario; ?>'>

                                    <input type='hidden' name='id_usuario' value='<? echo $datosusuario[0]['PLA_NRO_IDEN']; ?>'>
                                    <input type='hidden' name='id_bd' value='<? echo $datosusuario[0]['ID']; ?>'>
                                    <input type='hidden' name='tipo_bd' value='<? echo $tipoBD; ?>'>
                                    <input type='hidden' name='co_fecha_inicial' value='<? echo $datosusuario[0]['PLA_FECHA_IN']; ?>'>

                                </center>
                                <br>
                                </td>
                                </tr>

                            </table>
                        </form>
                    </td>
                </tr>
            </tbody>

        </tr>
        </table>
        <div id="div_mensaje1" align="center" class="ab_name">
        </div>
        <?
    }

    function historialVinculacion() {

        $funcionario = array('identificacion' => $this->identificacion,
                              'anio'=>date('Y') );

        $cadena_sql = $this->sql->cadena_sql("datosUsuarioSDH", $funcionario);
        $cadena_sql2 = $this->sql->cadena_sql("datosUsuario", $funcionario);
        $cadena_sql3 = $this->sql->cadena_sql("datos_contrato", $funcionario);
        $cadena_sql4 = $this->sql->cadena_sql("consultar_respuestas", $funcionario);
        $cadena_sql5 = $this->sql->cadena_sql("vinculaciones", $funcionario);

        $datosSHD = $this->ejecutarSQL($this->configuracion, $this->accesoSICAPITAL, $cadena_sql, "busqueda");
        $datosUser = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql2, "busqueda");
        $datosContrato = $this->ejecutarSQL($this->configuracion, $this->accesoSICAPITAL, $cadena_sql3, "busqueda");
        $datos_consulta = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql4, "busqueda");
        $datosVinculacion = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql5, "busqueda");

        if ($datosUser == "") {
            $datosusuario = $datosSHD;
            $tipoBD = 'SHD';
        } else {
            $datosusuario = $datosUser;
            $tipoBD = 'PEEMP';
        }
        ?>
        <table class="bordered" width="100%" >

            <th class='espacios_proyecto' colspan ="5"><? echo "<br>HISTORIAL DE VINCULACIONES - " . $datosusuario[0]['PLA_APELLIDO1'] . ' ' . $datosusuario[0]['PLA_APELLIDO2'] . ' ' . $datosusuario[0]['PLA_NOMBRE1'] . ' ' . $datosusuario[0]['PLA_NOMBRE2'] . '<br>    <br>'; ?></th>
            <tr>    <th class = 'subtitulo_th centrar' > Periodo </th>
                <th class = 'subtitulo_th centrar' > Tipo Vinculación</th>
                <th class = 'subtitulo_th centrar' > Estado</th>
                <th class = 'subtitulo_th centrar' > Resoluci&oacute;n/Contrato</th>
                <th class = 'subtitulo_th centrar' > Registrar Información Tributaria</th>
            </tr>
            <?
            //Impresión de vinculaciones como Funcionario de Planta

            if ($datosusuario[0]['PLA_ESTADO'] != " ") {
                ?>

                <tr >
                    <td width="10%" class='texto_elegante estilo_td' style="text-align:center;">
                        <? echo $anio = date('Y'); ?>
                    </td>

                    <td width="20%" class='texto_elegante estilo_td'>
                        <? echo $datosusuario[0]['VINCULACION']; ?>
                    </td>

                    <td width="15%" class='texto_elegante estilo_td' style="text-align:center;">
                        <?
                        echo $datosusuario[0]['PLA_ESTADO'];
                        ?>
                    </td>

                    <td width="20%" class='texto_elegante estilo_td'  style="text-align:center;">
                        <? echo $datosusuario[0]['PLA_RES']; ?>
                    </td>

                    <td width="20%" class='texto_elegante estilo_td' style="text-align:center;">
                        <?
                        if ($anio == date('Y')) {

                            if ($datos_consulta[0]['resp_annio'] == $anio) {
                                ?>   
                                <a href="
                                <?
                                $variable = 'pagina=encuestaTributario';
                                $variable.='&opcion=consultar';

                                $variable.='&vigencia=' . $anio;
                                $variable.='&vinculacion=' . $datosusuario[0]['VINCULACION'];
                                $variable.='&estado=' . $datosusuario[0]['PLA_ESTADO'];
                                $variable.='&contrato=' . $datosusuario[0]['PLA_RES'];

                                $variable.='&nombre=' . $datosusuario[0]['PLA_NOMBRE1'];
                                $variable.='&nombre2=' . $datosusuario[0]['PLA_NOMBRE2'];
                                $variable.='&apellido=' . $datosusuario[0]['PLA_APELLIDO1'];
                                $variable.='&ap2=' . $datosusuario[0]['PLA_APELLIDO2'];
                                $variable.='&identificacion=' . $datosusuario[0]['PLA_NRO_IDEN'];
                                $variable.='&id_tipo=' . $datosusuario[0]['PLA_TIPO_IDEN'];

                                $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                                echo $this->indice . $variable;
                                ?>" >
                                    <img alt="Ir a Formulario" src="<? echo $this->configuracion["host"] . $this->configuracion["site"] . $this->configuracion["bloques"] ?>/tributario/magi_form.jpg" /></a> 
                            <? } else {
                                ?>   
                                <a href="
                                <?
                                $variable = 'pagina=encuestaTributario';
                                $variable.='&opcion=';

                                $variable.='&vigencia=' . $anio;
                                $variable.='&vinculacion=' . $datosusuario[0]['VINCULACION'];
                                $variable.='&estado=' . $datosusuario[0]['PLA_ESTADO'];
                                $variable.='&contrato=' . $datosusuario[0]['PLA_RES'];

                                $variable.='&nombre=' . $datosusuario[0]['PLA_NOMBRE1'];
                                $variable.='&nombre2=' . $datosusuario[0]['PLA_NOMBRE2'];
                                $variable.='&apellido=' . $datosusuario[0]['PLA_APELLIDO1'];
                                $variable.='&ap2=' . $datosusuario[0]['PLA_APELLIDO2'];
                                $variable.='&identificacion=' . $datosusuario[0]['PLA_NRO_IDEN'];
                                $variable.='&id_tipo=' . $datosusuario[0]['PLA_TIPO_IDEN'];

                                $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                                echo $this->indice . $variable;
                                ?>" >
                                    <img alt="Ir a Formulario" src="<? echo $this->configuracion["host"] . $this->configuracion["site"] . $this->configuracion["bloques"] ?>/tributario/icon_form_1.jpg" /></a> 
                                <?
                            }
                            ?> 
                        </td>

                    </tr>

                    <?
                }
            }

            //Impresión de vinculaciones como Contratista
            if (is_array($datosContrato)) {
                foreach ($datosContrato as $key => $value) {
                    ?> <tr >
                        <td width="10%" class='texto_elegante estilo_td' style="text-align:center;">
                            <? echo $datosContrato[$key]['VIGENCIA'];
                            ?>
                        </td>

                        <td width="20%" class='texto_elegante estilo_td'>
                            <? echo $datosContrato[$key]['TIPO_CONTRATO']; ?>
                        </td>

                        <td width="15%" class='texto_elegante estilo_td'>
                            <?
                            echo '';
                            ?>
                        </td>

                        <td width="20%" class='texto_elegante estilo_td' style="text-align:center;">
                            <? echo $datosContrato[$key]['NUM_CONTRATO'] ?>
                        </td>

                        <td width="20%" height="26" class='texto_elegante estilo_td' style="text-align:center;">
                            <?
                            if ($datosContrato[$key]['VIGENCIA'] == date('Y')) {
                                if ($datos_consulta[$key]['resp_annio'] == $datosContrato[$key]['VIGENCIA']) {
                                    ?>
                                    <a href="
                                    <?
                                    $variable = 'pagina=encuestaTributario';
                                    $variable.='&opcion=consultar';

                                    $variable.='&vigencia=' . $datosContrato[$key]['VIGENCIA'];
                                    $variable.='&vinculacion=' . $datosContrato[$key]['TIPO_CONTRATO'];
                                    //$variable.='$estadoC=' .
                                    $variable.='&contrato=' . $datosContrato[$key]['NUM_CONTRATO'];

                                    $variable.='&nombre=' . $datosusuario[0]['PLA_NOMBRE1'];
                                    $variable.='&nombre2=' . $datosusuario[0]['PLA_NOMBRE2'];
                                    $variable.='&apellido=' . $datosusuario[0]['PLA_APELLIDO1'];
                                    $variable.='&ap2=' . $datosusuario[0]['PLA_APELLIDO2'];
                                    $variable.='&identificacion=' . $datosusuario[0]['PLA_NRO_IDEN'];
                                    $variable.='&id_tipo=' . $datosusuario[0]['PLA_TIPO_IDEN'];
                                    $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                                    echo $this->indice . $variable;
                                    ?>"> 
                                        <img alt="Ir a Formulario" src="<? echo $this->configuracion["host"] . $this->configuracion["site"] . $this->configuracion["bloques"] ?>/tributario/magi_form.jpg" /></a> 
                                    <?
                                } else {
                                    ?>
                                    <a href="
                                    <?
                                    $variable = 'pagina=encuestaTributario';
                                    $variable.='&opcion=';

                                    $variable.='&vigencia=' . $datosContrato[$key]['VIGENCIA'];
                                    $variable.='&vinculacion=' . $datosContrato[$key]['TIPO_CONTRATO'];
                                    //$variable.='$estadoC=' .
                                    $variable.='&contrato=' . $datosContrato[$key]['NUM_CONTRATO'];

                                    $variable.='&nombre=' . $datosusuario[0]['PLA_NOMBRE1'];
                                    $variable.='&nombre2=' . $datosusuario[0]['PLA_NOMBRE2'];
                                    $variable.='&apellido=' . $datosusuario[0]['PLA_APELLIDO1'];
                                    $variable.='&ap2=' . $datosusuario[0]['PLA_APELLIDO2'];
                                    $variable.='&identificacion=' . $datosusuario[0]['PLA_NRO_IDEN'];
                                    $variable.='&id_tipo=' . $datosusuario[0]['PLA_TIPO_IDEN'];
                                    $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                                    echo $this->indice . $variable;
                                    ?>"> 
                                        <img alt="Ir a Formulario" src="<? echo $this->configuracion["host"] . $this->configuracion["site"] . $this->configuracion["bloques"] ?>/tributario/icon_form_1.jpg" /></a> 
                                    <?
                                }
                                ?> 
                            </td>

                        </tr>

                        <?
                    }
                }
            }

            //Impresión de vinculaciones como Docente
            if (is_array($datosVinculacion)) {

                foreach ($datosVinculacion as $key => $value) {
                    ?> <tr >
                        <td width="10%" class='texto_elegante estilo_td' style="text-align:center;">
                            <? echo $datosVinculacion[$key]['VIN_ANIO']; ?>
                        </td>

                        <td width="20%" class='texto_elegante estilo_td'>
                            <? echo $datosVinculacion[$key]['VIN_NOMBRE']; ?>
                        </td>

                        <td width="5%" class='texto_elegante estilo_td' style="text-align:center;">
                            <? echo $datosVinculacion[$key]['VIN_ESTADO']; ?>
                        </td>

                        <td width="15%" class='texto_elegante estilo_td'  style="text-align:center;">
                            <?
                            if ($datosVinculacion[$key]['VIN_COD'] == 1 || $datosVinculacion[$key]['VIN_COD'] == 8 || $datosVinculacion[$key]['VIN_COD'] == 6)
                                echo $datosusuario[0]['PLA_RES'];
                            ?>
                        </td>

                        <td width="20%" height="26" class='texto_elegante estilo_td' style="text-align:center;">
                            <?
                            if ($datosVinculacion[$key]['VIN_ANIO'] == date('Y')) {
                                if ($datos_consulta[0]['resp_annio'] == $datosVinculacion[$key]['VIN_ANIO']) {
                                    ?>   
                                    <a href="<?
                        $variable = 'pagina=encuestaTributario';
                        $variable.='&opcion=consultar';

                        $variable.='&vigencia=' . $datosVinculacion[$key]['VIN_ANIO'];
                        $variable.='&vinculacion=' . $datosVinculacion[$key]['VIN_NOMBRE'];
                        $variable.='&estado=' . $datosVinculacion[$key]['VIN_ESTADO'];
                        $variable.='&contrato=' . $datosusuario[0]['PLA_RES'];

                        $variable.='&nombre=' . $datosusuario[0]['PLA_NOMBRE1'];
                        $variable.='&nombre2=' . $datosusuario[0]['PLA_NOMBRE2'];
                        $variable.='&apellido=' . $datosusuario[0]['PLA_APELLIDO1'];
                        $variable.='&ap2=' . $datosusuario[0]['PLA_APELLIDO2'];
                        $variable.='&identificacion=' . $datosusuario[0]['PLA_NRO_IDEN'];
                        $variable.='&id_tipo=' . $datosusuario[0]['PLA_TIPO_IDEN'];

                        $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                        echo $this->indice . $variable;
                                    ?>" >    
                                        <img alt="Ir a Formulario" src="<? echo $this->configuracion["host"] . $this->configuracion["site"] . $this->configuracion["bloques"] ?>/tributario/magi_form.jpg" /></a> 
                                </td>

                            </tr>

                            <?
                        } else {
                            ?>   
                            <a href="<?
                        $variable = 'pagina=encuestaTributario';
                        $variable.='&opcion=';

                        $variable.='&vigencia=' . $datosVinculacion[$key]['VIN_ANIO'];
                        $variable.='&vinculacion=' . $datosVinculacion[$key]['VIN_NOMBRE'];
                        $variable.='&estado=' . $datosVinculacion[$key]['VIN_ESTADO'];
                        $variable.='&contrato=' . $datosusuario[0]['PLA_RES'];

                        $variable.='&nombre=' . $datosusuario[0]['PLA_NOMBRE1'];
                        $variable.='&nombre2=' . $datosusuario[0]['PLA_NOMBRE2'];
                        $variable.='&apellido=' . $datosusuario[0]['PLA_APELLIDO1'];
                        $variable.='&ap2=' . $datosusuario[0]['PLA_APELLIDO2'];
                        $variable.='&identificacion=' . $datosusuario[0]['PLA_NRO_IDEN'];
                        $variable.='&id_tipo=' . $datosusuario[0]['PLA_TIPO_IDEN'];

                        $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                        echo $this->indice . $variable;
                            ?>" >    
                                <img alt="Ir a Formulario" src="<? echo $this->configuracion["host"] . $this->configuracion["site"] . $this->configuracion["bloques"] ?>/tributario/icon_form_1.jpg" /></a> 
                        </td>

                        </tr>

                        <?
                    }
                }
            }
        }
        echo '<br>';
        ?>

        </table>
        <?
    }

    function noData($mensaje) {

        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/alerta.class.php");
        $cadena = ".::" . $mensaje . "::.";
        $cadena = htmlentities($cadena, ENT_COMPAT, "UTF-8");
        alerta::sin_registro($this->configuracion, $cadena);
    }

}
?>
