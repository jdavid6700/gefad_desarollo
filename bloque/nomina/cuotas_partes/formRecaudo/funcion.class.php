
<?

/*
  ############################################################################
  #    UNIVERSIDAD DISTRITAL Francisco Jose de Caldas                        #
  #    Copyright: Vea el archivo EULA.txt que viene con la distribucion      #
  ############################################################################
 */
/* --------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
  --------------------------------------------------------------------------------------------------------------------------- */
/* ---------------------------------------------------------------------------------------
  |				Control Versiones				    	|
  ----------------------------------------------------------------------------------------
  | fecha      |        Autor            | version     |              Detalle            |
  ----------------------------------------------------------------------------------------
  | 18/05/2013 | Violet Sosa             | 0.0.0.1     |                                 |
  ----------------------------------------------------------------------------------------
  | 02/08/2013 | Violet Sosa             | 0.0.0.2     |                                 |
  ----------------------------------------------------------------------------------------
 */

/* --------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
  --------------------------------------------------------------------------------------------------------------------------- */


if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/funcionGeneral.class.php");
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/navegacion.class.php");
include_once("html.class.php");

class funciones_formRecaudo extends funcionGeneral {

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

        //Conexión a Postgres 
        $this->acceso_pg = $this->conectarDB($configuracion, "cuotas_partes");
        //Conexión Oracle        
        $this->acceso_Oracle = $this->conectarDB($configuracion, "cuotasP");

        //Datos de sesion
        $this->usuario = $this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion = $this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
        $this->configuracion = $configuracion;
        $this->html_formRecaudo = new html_formRecaudo($configuracion);
    }

    function inicio() {
        $this->html_formRecaudo->form_valor();
    }

    function inicio_cp() {
        $this->html_formRecaudo->form_valor_cp();
    }

    //Consultas del Sistema
    function actualizarEstadoCobro($parametro) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "actualizarCobro", $parametro);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "actualizar");
        return $datos;
    }

    function actualizarEstadoRecta($parametro) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "actualizarRecta", $parametro);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "actualizar");
        return $datos;
    }

    function consultarConsecPago() {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "consultaPagoConsecutivo", "");
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos;
    }

    function consultar_cuentac($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "consultar_cc", $parametros);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos;
    }

    function consultarConseRecta($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "consecutivoRecta", $parametros);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos;
    }

    function consultarEntidades($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "consultarEntidades", $parametros);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos;
    }

    function consultarEntidadesRecaudo($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "consultarEntidadesRecaudo", $parametros);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos;
    }

    function consultarCobroPago($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "consultarCobrosPagos", $parametros);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos;
    }

    function consultarRecaudos($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "consultarRecaudos", $parametros);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos;
    }

    function consultarRecaudoCompleto($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "consultarRecaudoCompleto", $parametros);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos;
    }

    function consultarRecaudoUnico($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "consultarRecaudoUnico", $parametros);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos;
    }

    function consultarSaldoAnterior($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "consultarSaldoAnterior", $parametros);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos;
    }

    function consultarConsecutivo($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "consultarConsecutivo", $parametros);
        $datos_historia = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos_historia;
    }

    function consultarCobros($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "consultarCobros", $parametros);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos;
    }

    function consultarCobrosEstado($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "consultarCobrosEstado", $parametros);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos;
    }

    function datosConcurrencia($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "datos_concurrencia", $parametros);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos;
    }

    function datosPensionado($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_Oracle, "datos_pensionado", $parametros);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_Oracle, $cadena_sql, "busqueda");
        return $datos;
    }

    function datosSaldos($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "datos_saldos", $parametros);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos;
    }
    
    function datosSaldosHistoria($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "datos_saldosHistoria", $parametros);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos;
    }

    function nombreEntidad($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "nombreEntidad", $parametros);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos;
    }

    function registrarPago($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "registrarPago", $parametros);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "registrar");
        return $datos;
    }

    function registrarPagoCobro($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "registrarPagoCobro", $parametros);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "registrar");
        return $datos;
    }

    function registrarSaldo($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "registrarSaldo", $parametros);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "registrar");
        return $datos;
    }

//Movimiento a Formularios
    function mostrarRecaudos() {
        $cedula = array('cedula' => (isset($_REQUEST['cedula_emp']) ? $_REQUEST['cedula_emp'] : ''));

        if (!preg_match("^\d+$^", $cedula['cedula'])) {
            echo "<script type=\"text/javascript\">" .
            "alert('La cédula posee un formato inválido');" .
            "</script> ";
            error_log('\n');
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = 'pagina=formularioRecaudo';
            $variable.='&opcion=';
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
            exit;
        } else {
            $datos_entidad = $this->consultarEntidadesRecaudo($cedula);

            if (is_array($datos_entidad)) {
                $this->html_formRecaudo->datosRecaudos($cedula, $datos_entidad);
            } else {
                echo "<script type=\"text/javascript\">" .
                "alert('No existen Cuentas de Cobro registradas con cédula " . $cedula['cedula'] . ". Por lo tanto, no hay pagos a registrar.');" .
                "</script> ";
                $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                $variable = 'pagina=cuentaCobro';
                $variable.='&opcion=manual';
                $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                exit;
            }
        }
    }

    function mostrarRecaudos_cp() {
        $cedula = array('cedula' => (isset($_REQUEST['cedula_emp']) ? $_REQUEST['cedula_emp'] : ''));

        if (!preg_match("^\d+$^", $cedula['cedula'])) {
            echo "<script type=\"text/javascript\">" .
            "alert('La cédula posee un formato inválido');" .
            "</script> ";
            error_log('\n');
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = 'pagina=formularioRecaudo';
            $variable.='&opcion=';
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
            exit;
        } else {

            $datos_entidad = $this->consultarEntidadesRecaudo($cedula);

            if (is_array($datos_entidad)) {
                $this->html_formRecaudo->datosRecaudos_cp($cedula, $datos_entidad);
            } else {
                echo "<script type=\"text/javascript\">" .
                "alert('No existen Cuentas de Cobro registradas con cédula " . $cedula['cedula'] . ". Por lo tanto, no hay datos a consultar.');" .
                "</script> ";
                $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                $variable = 'pagina=cuentaCobro';
                $variable.='&opcion=manual';
                $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                exit;
            }
        }
    }

    function historiaRecaudos($datos_consulta, $saldo_cuenta) {
        $parametros = array(
            'cedula_emp' => $datos_consulta['cedula_emp'],
            'nit_previsional' => $datos_consulta['hlab_nitprev']);
        
        $parametros2 = array(
            'cedula' => $datos_consulta['cedula_emp'],
            'entidad' => $datos_consulta['hlab_nitprev']);

        $saldo_cc = $saldo_cuenta;
        $datos_recaudos = $this->consultarRecaudos($parametros);
        $datos_cobros = $this->consultarCobros($parametros);
        $datos_saldo = $this->datosSaldos($parametros2);

        if (is_array($datos_cobros)) {
            $this->html_formRecaudo->historiaRecaudos($datos_recaudos, $datos_cobros, $saldo_cc);
        } else {
            echo "<script type=\"text/javascript\">" .
            "alert('No existen Cuentas de Cobro registradas con cédula " . $parametros['cedula'] . " para la Entidad " . $parametros['entidad'] . ". Por lo tanto, no hay pagos a registrar.');" .
            "</script> ";
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = 'pagina=reportesCuotas';
            $variable.='&opcion=';
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
            exit;
        }
    }

    function historiaRecaudos_cp($datos_consulta) {
        $parametros = array('cedula' => $datos_consulta['cedula_emp'], 'entidad' => $datos_consulta['hlab_nitprev']);
        $parametros2 = array('cedula_emp' => $datos_consulta['cedula_emp'], 'nit_previsional' => $datos_consulta['hlab_nitprev']);

        $datos_recaudos = $this->consultarRecaudoCompleto($parametros2);
        $datos_cobros = $this->consultarCobrosEstado($parametros);
        $datos_concurrencia = $this->datosConcurrencia($parametros);
        $datos_saldo = $this->datosSaldosHistoria($parametros);
 
        $nombre_entidad = $this->nombreEntidad($parametros);
        $nombre_empleado = $this->datosPensionado($parametros);

        $datos_basicos = array(
            'cedula' => $parametros['cedula'],
            'nombre_emp' => $nombre_empleado[0]['NOMBRE'],
            'entidad_nombre' => $nombre_entidad[0]['prev_nombre'],
            'entidad' => $parametros['entidad']);

        if (is_array($datos_cobros)) {
            $this->html_formRecaudo->estadoCuenta($datos_basicos, $datos_recaudos, $datos_cobros, $datos_concurrencia, $datos_saldo);
        } else {
            echo "<script type=\"text/javascript\">" .
            "alert('No existen Cuentas de Cobro registradas con cédula " . $parametros['cedula'] . " para la Entidad " . $parametros['entidad'] . ". Por lo tanto, no hay pagos a registrar.');" .
            "</script> ";
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = 'pagina=reportesCuotas';
            $variable.='&opcion=';
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
            exit;
        }
    }

    function mostrarFormulario($cuentas_pago) {

        foreach ($cuentas_pago as $key => $value) {
            $fecha_cuenta[$key] = date('d/m/Y', strtotime(str_replace('/', '-', $value['fecha_cuenta'])));
        }

        rsort($fecha_cuenta);

        $fecha_minima_datepicker = $fecha_cuenta[0];

        $this->html_formRecaudo->formularioRecaudos($cuentas_pago, $fecha_minima_datepicker);
    }

    function actualizarSaldo($parametros) {

        //Verificar la cuenta de cobro y recaudo
        $consultar_ccobro = $this->consultar_cuentac($parametros);
        $consultar_recaudo = $this->consultarRecaudoUnico($parametros);

        if ($consultar_ccobro == null) {
            echo "<script type=\"text/javascript\">" .
            "alert('No registra Cuenta de Cobro Válida');" .
            "</script> ";
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = 'pagina=formularioRecaudo';
            $variable.='&opcion=';
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
            exit;
        }

        if ($consultar_recaudo == null) {
            echo "<script type=\"text/javascript\">" .
            "alert('No registra Recaudo (Pago) Válido');" .
            "</script> ";
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = 'pagina=formularioRecaudo';
            $variable.='&opcion=';
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
            exit;
        }

        $consultar_saldo_anterior = $this->consultarSaldoAnterior($parametros);

        if ($consultar_saldo_anterior !== null) {
            //No existen pagos anteriores registrados
            //Revisión Datos de Recta
            $deuda_capital = $consultar_saldo_anterior[0]['recta_saldocapital'];
            $deuda_interes = $consultar_saldo_anterior[0]['recta_saldointeres'];
            $total_deuda = $consultar_saldo_anterior[0]['recta_saldototal'];

            //Revisión datos del pago registrado
            $deuda_cuentac = $parametros['total_cobro'];
            $pago_capital = $parametros['valor_pagado_capital'];
            $pago_interes = $parametros['valor_pagado_interes'];
            $total_pago_calc = $pago_capital + $pago_interes;
            $total_pago_bd = $parametros['total_recaudo'];

            //Cálculos de la deuda
            $saldo_capital = $deuda_capital - $pago_capital;
            $saldo_interes = $deuda_interes - $deuda_interes;
            $saldo_total = $saldo_capital + $saldo_interes;

            if ($saldo_total == 0) {
                //Si el saldo es 0, actualizar saldo e inactivar cuenta de cobro para cobros y actualizar registro de pago en recta
                $inactivar_cobro = $this->actualizarEstadoCobro($parametros['consecutivo_cc']);
                $inactivar_recta = $this->actualizarEstadoRecta($consultar_saldo_anterior[0]['recta_id']);

                $parametros_z = array();
                $consecutivo_recta = $this->consultarConseRecta($parametros_z);

                if ($consecutivo_recta == null) {
                    $rectaid = 1;
                } else {
                    $rectaid = $consecutivo_recta[0][0] + 1;
                }

                $para_saldo = array(
                    'recta_id' => $rectaid,
                    'recta_consecu_cta' => $parametros['consecutivo_cc'],
                    'recta_consecu_rec' => $parametros['consecutivo_rec'],
                    'recta_cedula' => $parametros['cedula_emp'],
                    'recta_nitprev' => $parametros['nit_previsional'],
                    'recta_valor_cobro' => $parametros['total_cobro'],
                    'recta_valor_recaudo' => $total_pago_bd,
                    'recta_saldocapital' => $saldo_capital,
                    'recta_saldointeres' => $saldo_interes,
                    'recta_saldototal' => $saldo_total,
                    'recta_fechapago' => $parametros['fecha_pago'],
                    'recta_fechadesde' => $parametros['fecha_pdesde'],
                    'recta_fechahasta' => $parametros['fecha_phasta'],
                    'recta_estado' => 'ACTIVO',
                    'recta_fecha_registro' => date('Y-m-d')
                );

                $registro_actualizado = $this->registrarSaldo($para_saldo);

                if ($registro_actualizado) {
                    echo "<script type=\"text/javascript\">" .
                    "alert('Cuenta de Cobro " . $parametros['consecutivo_cc'] . " con saldo igual a cero.');" .
                    "</script> ";
                    $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                    $variable = 'pagina=formularioRecaudo';
                    $variable.='&opcion=consulta_cp';
                    $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                    echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                    exit;
                } else {
                    echo "<script type=\"text/javascript\">" .
                    "alert('No se realizó el cambio de estado de la cuenta " . $parametros['consecutivo_cc'] . " con éxito3.');" .
                    "</script> ";
                    $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                    $variable = 'pagina=formularioRecaudo';
                    $variable.='&opcion=';
                    $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                    echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                    exit;
                }
            } else {
                //Si el saldo es diferente de 0, inactivar registro anterior, ingresar nuevo registro de recta con valor actualizado
                $inactivar_recta = $this->actualizarEstadoRecta($consultar_saldo_anterior[0]['recta_id']);

                $parametros_z = array();
                $consecutivo_recta = $this->consultarConseRecta($parametros_z);

                if ($consecutivo_recta == null) {
                    $rectaid = 1;
                } else {
                    $rectaid = $consecutivo_recta[0][0] + 1;
                }

                $para_saldo = array(
                    'recta_id' => $rectaid,
                    'recta_consecu_cta' => $parametros['consecutivo_cc'],
                    'recta_consecu_rec' => $parametros['consecutivo_rec'],
                    'recta_cedula' => $parametros['cedula_emp'],
                    'recta_nitprev' => $parametros['nit_previsional'],
                    'recta_valor_cobro' => $parametros['total_cobro'],
                    'recta_valor_recaudo' => $total_pago_bd,
                    'recta_saldocapital' => $saldo_capital,
                    'recta_saldointeres' => $saldo_interes,
                    'recta_saldototal' => $saldo_total,
                    'recta_fechapago' => $parametros['fecha_pago'],
                    'recta_fechadesde' => $parametros['fecha_pdesde'],
                    'recta_fechahasta' => $parametros['fecha_phasta'],
                    'recta_estado' => 'ACTIVO',
                    'recta_fecha_registro' => date('Y-m-d')
                );

                $registro_actualizado = $this->registrarSaldo($para_saldo);

                if ($registro_actualizado) {
                    echo "<script type=\"text/javascript\">" .
                    "alert('Cuenta de Cobro " . $parametros['consecutivo_cc'] . " con saldo igual a " . number_format($saldo_total) . ".');" .
                    "</script> ";
                    $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                    $variable = 'pagina=formularioRecaudo';
                    $variable.='&opcion=consulta_cp';
                    $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                    echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                    exit;
                } else {
                    echo "<script type=\"text/javascript\">" .
                    "alert('No se realizó el cambio de estado de la cuenta " . $parametros['consecutivo_cc'] . " con éxito3.');" .
                    "</script> ";
                    $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                    $variable = 'pagina=formularioRecaudo';
                    $variable.='&opcion=';
                    $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                    echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                    exit;
                }
            }
        } else {
            //NO existen registros de pagos anteriores, lo cual debe ser IMPOSIBLE
            echo "<script type=\"text/javascript\">" .
            "alert('Error Fatal. No se pudo recuperar los datos para actualizar el saldo.');" .
            "</script> ";
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = 'pagina=formularioRecaudo';
            $variable.='&opcion=consulta_cp';
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
            exit;
        }
    }

    function procesarFormulario($datos) {

        foreach ($datos as $key => $value) {

            if ($datos[$key] == "") {
                echo "<script type=\"text/javascript\">" .
                "alert('Formulario NO diligenciado correctamente');" .
                "</script> ";
                $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                $variable = 'pagina=formularioRecaudo';
                $variable.='&opcion=';
                $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                exit;
            }
        }

        if (!preg_match("/([0-9]{2})\/([0-9]{2})\/([0-9]{4})/", $datos['fecha_resolucion'])) {
            echo "<script type=\"text/javascript\">" .
            "alert('Formato fecha resolución diligenciado incorrectamente');" .
            "</script> ";
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = 'pagina=formularioRecaudo';
            $variable.='&opcion=';
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace(' " . $pagina . $variable . "')</script>";
            exit;
        }

        if (!preg_match("/([0-9]{2})\/([0-9]{2})\/([0-9]{4})/", $datos['fecha_pago_cuenta'])) {
            echo "<script type=\"text/javascript\">" .
            "alert('Formato fecha pago cuenta diligenciado incorrectamente');" .
            "</script> ";
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = 'pagina=formularioRecaudo';
            $variable.='&opcion=';
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace(' " . $pagina . $variable . "')</script>";
            exit;
        }

        foreach ($datos as $key => $value) {
            if (strstr($key, 'valor_pagado_capital')) {
                $valor = substr($key, strlen('valor_pagado_capital'));

                if ($datos['valor_pagado_capital' . $valor] == 0) {
                    echo "<script type=\"text/javascript\">" .
                    "alert('Valor Pagado NO Válido');" .
                    "</script> ";
                    $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                    $variable = 'pagina=formularioRecaudo';
                    $variable.='&opcion=';
                    $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                    echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                    exit;
                }
            }
        }

        $total_capital = 0;
        $total_interes = 0;

        foreach ($datos as $key => $value) {
            if (strstr($key, 'valor_pagado_capital')) {
                $valor = substr($key, strlen('valor_pagado_capital'));
                $total_capital = intval($datos['valor_pagado_capital' . $valor]) + $total_capital;
            }

            if (strstr($key, 'valor_pagado_interes')) {
                $valor = substr($key, strlen('valor_pagado_interes'));
                $total_interes = intval($datos['valor_pagado_interes' . $valor]) + $total_interes;
            }
        }

        $total_pagado = intval($total_capital) + intval($total_interes);

        if (intval($datos['total_recaudo']) !== intval($total_pagado)) {
            echo "<script type=\"text/javascript\">" .
            "alert('Valor Total Pagado no corresponde a la Suma de los valores correspondientes!');" .
            "</script> ";
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = 'pagina=formularioRecaudo';
            $variable.='&opcion=';
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
            exit;
        }

        /** Validar el traslape de las fechas de cobro y pago* */
        $parametros_rec = array(
            'cedula_emp' => $datos['cedula_emp'],
            'nit_previsional' => $datos['nit_previsional']
        );


        foreach ($datos as $key => $value) {
            if (strstr($key, 'fecha_cinicio')) {
                $valor = substr($key, strlen('fecha_cinicio'));
                $rango[$valor]['inicio'] = $datos['fecha_cinicio' . $valor];
            }

            if (strstr($key, 'fecha_cfin')) {
                $valor = substr($key, strlen('fecha_cfin'));
                $rango[$valor]['fin'] = $datos['fecha_cfin' . $valor];
            }

            if (strstr($key, 'fecha_pinicio')) {
                $valor = substr($key, strlen('fecha_pinicio'));
                $rango[$valor]['desde'] = $datos['fecha_pinicio' . $valor];
            }

            if (strstr($key, 'fecha_pfin')) {
                $valor = substr($key, strlen('fecha_pfin'));
                $rango[$valor]['hasta'] = $datos['fecha_pfin' . $valor];
            }
        }


     /*  foreach ($rango as $key => $values) {
            $antes = strtotime(str_replace('/', '-', $rango[$key]['desde']));
            $despues = strtotime(str_replace('/', '-', $rango[$key]['hasta']));

            foreach ($rango as $key => $values) {
                $inicio = strtotime(str_replace('/', '-', $rango[$key]['inicio']));
                $fin = strtotime(str_replace('/', '-', $rango[$key]['fin']));

                if ($antes > $inicio && $antes < $fin) {
                    echo "<script type=\"text/javascript\">" .
                    "alert('El intervalo de pago no es válido');" .
                    "</script> ";
                    error_log('\n');
                    $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                    $variable = 'pagina=formHistoria';
                    $variable.='&opcion=';
                    $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                    echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                    exit;
                }

                if ($despues > $inicio && $despues < $fin) {
                    echo "<script type=\"text/javascript\">" .
                    "alert('El intervalo de pago no es válido');" .
                    "</script> ";
                    error_log('\n');
                    $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                    $variable = 'pagina=formHistoria';
                    $variable.='&opcion=';
                    $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                    echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                    exit;
                }
            }
        }

        $recaudos = $this->consultarRecaudos($parametros_rec);

        if (is_array($recaudos)) {
            foreach ($rango as $key => $values) {
                $antes = strtotime(str_replace('/', '-', $rango[$key]['desde']));
                $despues = strtotime(str_replace('/', '-', $rango[$key]['hasta']));

                foreach ($recaudos as $key => $values) {
                    $inicio = strtotime(str_replace('/', '-', $recaudos[$key]['recta_fechadesde']));
                    $fin = strtotime(str_replace('/', '-', $recaudos[$key]['recta_fechahasta']));

                    if ($antes >= $inicio && $antes <= $fin) {
                        echo "<script type=\"text/javascript\">" .
                        "alert('El intervalo de pago no es válido');" .
                        "</script> ";
                        error_log('\n');
                        $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                        $variable = 'pagina=formHistoria';
                        $variable.='&opcion=';
                        $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                        echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                        exit;
                    }

                    if ($despues >= $inicio && $despues <= $fin) {
                        echo "<script type=\"text/javascript\">" .
                        "alert('El intervalo de pago no es válido');" .
                        "</script> ";
                        error_log('\n');
                        $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                        $variable = 'pagina=formHistoria';
                        $variable.='&opcion=';
                        $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                        echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                        exit;
                    }
                }
            }
        }
        /*         * Fin de las validaciones de los datos */

        $consecutivo = $this->consultarConsecPago();
        $cons = $consecutivo[0]['rec_id'] + 1;
        $annio = date("Y");

        if ($cons <= 9) {
            $cons_recaudo = "RC-000" . $cons . "-" . $annio;
        } elseif ($cons <= 99) {
            $cons_recaudo = "RC-00" . $cons . "-" . $annio;
        } elseif ($cons <= 999) {
            $cons_recaudo = "RC-0" . $cons . "-" . $annio;
        } else {
            $cons_recaudo = "RC-" . $cons . "-" . $annio;
        }

        $parametros_recaudo = array(
            'rec_id' => $cons,
            'consecutivo_rec' => $cons_recaudo,
            'nit_previsional' => (isset($datos['nit_previsional']) ? $datos['nit_previsional'] : ''),
            'cedula_emp' => (isset($datos['cedula_emp']) ? $datos['cedula_emp'] : ''),
            'actoadmin' => (isset($datos['acto_adm']) ? $datos['acto_adm'] : ''),
            'factoadmin' => (isset($datos['fecha_acto_adm']) ? $datos['fecha_acto_adm'] : ''),
            'resolucion_OP' => (isset($datos['resolucion_OP']) ? $datos['resolucion_OP'] : ''),
            'fecha_resolucion' => (isset($datos['fecha_resolucion']) ? $datos['fecha_resolucion'] : ''),
            'fecha_pago_cuenta' => (isset($datos['fecha_pago_cuenta']) ? $datos['fecha_pago_cuenta'] : ''),
            'medio_pago' => (isset($datos['medio_pago']) ? $datos['medio_pago'] : ''),
            'valor_pagado_capital' => (isset($total_capital) ? $total_capital : ''),
            'valor_pagado_interes' => (isset($total_interes) ? $total_interes : ''),
            'valor_pagado_interes' => (isset($total_interes) ? $total_interes : ''),
            'fecha_registro' => date('Y-m-d'),
            'total_recaudo' => (isset($datos['total_recaudo']) ? $datos['total_recaudo'] : ''));

        $datos_recaudo = $this->registrarPago($parametros_recaudo);

        if ($datos_recaudo == 1) {

            foreach ($datos as $key => $value) {

                if (strstr($key, 'consec')) {
                    $valor = substr($key, strlen('consec_cc'));

                    $total_Recaudo = intval($datos['valor_pagado_interes' . $valor]) + intval($datos['valor_pagado_capital' . $valor]);

                    $parametros = array(
                        'consecutivo_cc' => $datos['consec_cc' . $valor],
                        'consecutivo_rec' => $cons_recaudo,
                        'cedula_emp' => $datos['cedula_emp'],
                        'nit_previsional' => $datos['nit_previsional'],
                        'valor_pagado_capital' => $datos['valor_pagado_capital' . $valor],
                        'valor_pagado_interes' => $datos['valor_pagado_interes' . $valor],
                        'total_recaudo' => $total_Recaudo,
                        'total_cobro' => $datos['valor_cobro_' . $valor],
                        'fecha_pago' => $datos['fecha_pago_cuenta'],
                        'fecha_pdesde' => $datos['fecha_pinicio' . $valor],
                        'fecha_phasta' => $datos['fecha_pfin' . $valor]);

                    $revisar_saldo = $this->actualizarSaldo($parametros);

                    exit;
                    $datos_recaudo_cobro = $this->registrarPagoCobro($parametros);

                    if ($datos_recaudo_cobro == 1) {
                        $registroL[0] = "REGISTRAR";
                        $registroL[1] = $parametros['cedula_emp'] . '|' . $parametros['nit_previsional']; //
                        $registroL[2] = "CUOTAS_PARTES";
                        $registroL[3] = $parametros['consecutivo_cc'] . '|' . $parametros['consecutivo_rec'] . '|' . $parametros['total_recaudo'] . '|' . $parametros['fecha_pdesde'] . '|' . $parametros['fecha_phasta']; //
                        $registroL[4] = time();
                        $registroL[5] = "Registra el pago_cobro para el pensionado con ";
                        $registroL[5] .= " identificacion =" . $parametros['cedula_emp'];
                        $this->log_us->log_usuario($registroL, $this->configuracion);
                    } else {
                        echo "<script type=\"text/javascript\">" .
                        "alert('Datos de Recaudo-Cobro NO Registrados Correctamente. ERROR en el REGISTRO');" .
                        "</script> ";

                        $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                        $variable = 'pagina=formularioRecaudo';
                        $variable .= "&opcion=";
                        $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                        echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                        break;
                    }

                    $actualizar_cobro = $this->actualizarEstadoCobro($parametros['consecutivo_cc']);

                    if ($actualizar_cobro == false) {
                        echo "<script type=\"text/javascript\">" .
                        "alert('Datos de Actualización de Cobro NO Registrados Correctamente. ERROR en el REGISTRO');" .
                        "</script> ";

                        $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                        $variable = 'pagina=formularioRecaudo';
                        $variable .= "&opcion=";
                        $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                        echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                        break;
                    } else {
                        $registroL[0] = "ACTUALIZAR";
                        $registroL[1] = $parametros['cedula_emp'] . '|' . $parametros['nit_previsional']; //
                        $registroL[2] = "CUOTAS_PARTES";
                        $registroL[3] = $parametros['consecutivo_cc'] . '|' . $parametros['consecutivo_rec'] . '|' . $parametros['total_recaudo'] . '|' . $parametros['fecha_pdesde'] . '|' . $parametros['fecha_phasta']; //
                        $registroL[4] = time();
                        $registroL[5] = "Actualiza el cobro para el pensionado con ";
                        $registroL[5] .= " identificacion =" . $parametros['cedula_emp'];
                        $this->log_us->log_usuario($registroL, $this->configuracion);
                    }
                }
            }

            $registroL[0] = "REGISTRAR";
            $registroL[1] = $parametros['cedula_emp'] . '|' . $parametros['nit_previsional']; //
            $registroL[2] = "CUOTAS_PARTES";
            $registroL[3] = $parametros['consecutivo_cc'] . '|' . $parametros['consecutivo_rec'] . '|' . $parametros['total_recaudo'] . '|' . $parametros['fecha_pdesde'] . '|' . $parametros['fecha_phasta']; //
            $registroL[4] = time();
            $registroL[5] = "Registra el pago para el pensionado con ";
            $registroL[5] .= " identificacion =" . $parametros['cedula_emp'];
            $this->log_us->log_usuario($registroL, $this->configuracion);

            echo "<script type=\"text/javascript\">" .
            "alert('Datos Registrados');" .
            "</script> ";
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = "pagina=reportesCuotas";
            $variable .= "&opcion=";
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
            exit;
        } else {

            echo "<script type=\"text/javascript\">" .
            "alert('Datos de Recaudos NO Registrados Correctamente. ERROR en el REGISTRO');" .
            "</script> ";

            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = 'pagina=formularioRecaudo';
            $variable .= "&opcion=";
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
            exit;
        }
    }

}
