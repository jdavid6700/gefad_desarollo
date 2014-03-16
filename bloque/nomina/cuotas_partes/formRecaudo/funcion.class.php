
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
        $parametros = array('cedula' => $datos_consulta['cedula_emp'], 'entidad' => $datos_consulta['hlab_nitprev']);

        $saldo_cc = $saldo_cuenta;
        $datos_recaudos = $this->consultarRecaudos($parametros);
        $datos_cobros = $this->consultarCobros($parametros);

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

    function historiaRecaudos_cp($datos_consulta, $saldo_cuenta) {
        $parametros = array('cedula' => $datos_consulta['cedula_emp'], 'entidad' => $datos_consulta['hlab_nitprev']);

        $saldo_cc = $saldo_cuenta;
        $datos_recaudos = $this->consultarRecaudos($parametros);
        $datos_cobros = $this->consultarCobros($parametros);

        if (is_array($datos_cobros)) {
            $this->html_formRecaudo->reporteCobrosPagos($datos_recaudos, $datos_cobros, $saldo_cc);
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
            $fecha_cuenta[$key] =date('d/m/Y', strtotime(str_replace('/', '-', $value['fecha_cuenta'])));
        }

        rsort($fecha_cuenta);

        $fecha_minima_datepicker = $fecha_cuenta[0];

        $this->html_formRecaudo->formularioRecaudos($cuentas_pago, $fecha_minima_datepicker);
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

    function consultarRecaudos($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "consultarRecaudos", $parametros);
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

    function actualizarEstadoCobro($parametro) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "actualizarCobro", $parametro);
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

    function revisarSaldo($parametros) {

        $consultar_ccobro = $this->consultar_cuentac($parametros);

        //Revisión Datos de la Cuenta de Cobro
        $deuda_capital = $consultar_ccobro[0]['cob_ts_interes'];
        $deuda_interes = $consultar_ccobro[0]['cob_interes'];
        $total_deuda_calculado = $deuda_capital + $deuda_interes;
        $total_deuda_bd = $consultar_ccobro[0]['cob_total'];

        //Revisión datos del pago registrado
        $pago_capital = $parametros['valor_pagado_capital'];
        $pago_interes = $parametros['valor_pagado_interes'];
        $total_pago_calc = $pago_capital + $pago_interes;
        $total_pago_bd = $parametros['total_recaudo'];

        //Cálculos de la deuda
        $saldo_capital = $deuda_capital - $pago_capital;
        $saldo_interes = $deuda_interes - $deuda_interes;
        $saldo_total = $saldo_capital + $saldo_interes;


        if ($saldo_total == 0) {
            //Si el saldo es 0, actualizar saldo e inactivar cuenta de cobro para cobros y registro de pago
            $inactivar_cobro = $this->actualizarEstadoCobro($parametros['consecutivo_cc']);

            $para_saldo = array(
                'cob_fgenerado' => $consultar_ccobro[0]['cob_fgenerado'],
                'cob_nitemp' => $consultar_ccobro[0]['cob_nitemp'],
                'cob_nitprev' => $consultar_ccobro[0]['cob_nitprev'],
                'cob_consecu_cta' => $consultar_ccobro[0]['cob_consecu_cta'],
                'cob_finicial' => $consultar_ccobro[0]['cob_finicial'],
                'cob_ffinal' => $consultar_ccobro[0]['cob_ffinal'],
                'cob_ts_interes' => $consultar_ccobro[0]['cob_ts_interes'],
                'cob_interes' => $consultar_ccobro[0]['cob_interes'],
                'cob_tc_interes' => $total_deuda_calculado,
                'cob_ie_correspondencia' => $consultar_ccobro[0]['cob_ie_correspondencia'],
                'cob_cedula' => $consultar_ccobro[0]['cob_cedula'],
                'cob_saldo' => $saldo_total,
                'cob_estado_cuenta' => 'INACTIVA',
                'cob_total' => $consultar_ccobro[0]['cob_total'],
                'cob_fecha_registro' => date("Y-m-d"),
                'cob_mesada' => $consultar_ccobro[0]['cob_mesada'],
                'cob_mesada_ad' => $consultar_ccobro[0]['cob_mesada_ad'],
                'cob_subtotal' => $consultar_ccobro[0]['cob_subtotal'],
                'cob_incremento' => $consultar_ccobro[0]['cob_incremento'],
                'cob_estado' => 'ACTIVO',
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
                "alert('No se realizó el cambio de estado de la cuenta " . $parametros['consecutivo_cc'] . " con éxito.');" .
                "</script> ";
                $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                $variable = 'pagina=formularioRecaudo';
                $variable.='&opcion=';
                $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                exit;
            }
        } else {
            //Si el saldo es diferente de 0, inactivar registro anterior, ingresar nuevo registro de cuenta de cobro con valor actualizado
            $inactivar_cobro = $this->actualizarEstadoCobro($parametros['consecutivo_cc']);

            $para_saldo = array(
                'cob_fgenerado' => $consultar_ccobro[0]['cob_fgenerado'],
                'cob_nitemp' => $consultar_ccobro[0]['cob_nitemp'],
                'cob_nitprev' => $consultar_ccobro[0]['cob_nitprev'],
                'cob_consecu_cta' => $consultar_ccobro[0]['cob_consecu_cta'],
                'cob_finicial' => $consultar_ccobro[0]['cob_finicial'],
                'cob_ffinal' => $consultar_ccobro[0]['cob_ffinal'],
                'cob_ts_interes' => $consultar_ccobro[0]['cob_ts_interes'],
                'cob_interes' => $consultar_ccobro[0]['cob_interes'],
                'cob_tc_interes' => $total_deuda_calculado,
                'cob_ie_correspondencia' => $consultar_ccobro[0]['cob_ie_correspondencia'],
                'cob_cedula' => $consultar_ccobro[0]['cob_cedula'],
                'cob_saldo' => $saldo_total,
                'cob_estado_cuenta' => 'ACTIVA',
                'cob_total' => $consultar_ccobro[0]['cob_total'],
                'cob_fecha_registro' => date("Y-m-d"),
                'cob_mesada' => $consultar_ccobro[0]['cob_mesada'],
                'cob_mesada_ad' => $consultar_ccobro[0]['cob_mesada_ad'],
                'cob_subtotal' => $consultar_ccobro[0]['cob_subtotal'],
                'cob_incremento' => $consultar_ccobro[0]['cob_incremento'],
                'cob_estado' => 'ACTIVO',
            );

            $registro_actualizado = $this->registrarSaldo($para_saldo);

            if ($registro_actualizado) {
                echo "<script type=\"text/javascript\">" .
                "alert('Cuenta de Cobro " . $parametros['consecutivo_cc'] . " con saldo igual a " . $saldo_total . ".');" .
                "</script> ";
                $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                $variable = 'pagina=formularioRecaudo';
                $variable.='&opcion=consulta_cp';
                $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                exit;
            } else {
                echo "<script type=\"text/javascript\">" .
                "alert('No se realizó el cambio de estado de la cuenta " . $parametros['consecutivo_cc'] . " con éxito.');" .
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
            'cedula' => $datos['cedula_emp'],
            'entidad' => $datos['nit_previsional']
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


        foreach ($rango as $key => $values) {
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
            'nit_empleador' => (isset($datos['nit_empleador']) ? $datos['nit_empleador'] : ''),
            'nit_previsional' => (isset($datos['nit_previsional']) ? $datos['nit_previsional'] : ''),
            'cedula_emp' => (isset($datos['cedula_emp']) ? $datos['cedula_emp'] : ''),
            'resolucion_OP' => (isset($datos['resolucion_OP']) ? $datos['resolucion_OP'] : ''),
            'fecha_resolucion' => (isset($datos['fecha_resolucion']) ? $datos['fecha_resolucion'] : ''),
            'fecha_pago_cuenta' => (isset($datos['fecha_pago_cuenta']) ? $datos['fecha_pago_cuenta'] : ''),
            'medio_pago' => (isset($datos['medio_pago']) ? $datos['medio_pago'] : ''),
            'valor_pagado_capital' => (isset($total_capital) ? $total_capital : ''),
            'valor_pagado_interes' => (isset($total_interes) ? $total_interes : ''),
            'total_recaudo' => (isset($datos['total_recaudo']) ? $datos['total_recaudo'] : ''));

        $datos_recaudo = $this->registrarPago($parametros_recaudo);

        if ($datos_recaudo == true) {

            foreach ($datos as $key => $value) {

                if (strstr($key, 'valor_pagado_capital')) {
                    $valor = substr($key, strlen('valor_pagado_capital'));
                    $total_capital = intval($datos['valor_pagado_capital' . $valor]) + $total_capital;
                }


                if (strstr($key, 'consec_cc')) {
                    $valor = substr($key, strlen('consec_cc'));

                    $total_Recaudo = intval($datos['valor_pagado_interes' . $valor]) + intval($datos['valor_pagado_capital' . $valor]);
                    $parametros = array(
                        'consecutivo_cc' => $datos['consec_cc' . $valor],
                        'consecutivo_rec' => $cons_recaudo,
                        'cedula_emp' => $datos['cedula_emp'],
                        'nit_empleador' => $datos['nit_empleador'],
                        'nit_previsional' => $datos['nit_previsional'],
                        'valor_pagado_capital' => $datos['valor_pagado_capital' . $valor],
                        'valor_pagado_interes' => $datos['valor_pagado_interes' . $valor],
                        'total_recaudo' => $total_Recaudo,
                        'fecha_pago' => $datos['fecha_pago_cuenta'],
                        'fecha_pdesde' => $datos['fecha_pinicio' . $valor],
                        'fecha_phasta' => $datos['fecha_pfin' . $valor]);

                    $revisar_saldo = $this->revisarSaldo($parametros);
                    exit;

                    $datos_recaudo_cobro = $this->registrarPagoCobro($parametros);

                    if ($datos_recaudo_cobro == false) {
                        echo "<script type=\"text/javascript\">" .
                        "alert('Datos de Recaudo-Cobro NO Registrados Correctamente. ERROR en el REGISTRO');" .
                        "</script> ";

                        $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                        $variable = 'pagina=formularioRecaudo';
                        $variable .= "&opcion=";
                        $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                        echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                        break;
                    } else {
                        $registroL[0] = "REGISTRAR";
                        $registroL[1] = $parametros['cedula_emp'] . '|' . $parametros['nit_empleador'] . '|' . $parametros['nit_previsional']; //
                        $registroL[2] = "CUOTAS_PARTES";
                        $registroL[3] = $parametros['consecutivo_cc'] . '|' . $parametros['consecutivo_rec'] . '|' . $parametros['total_recaudo'] . '|' . $parametros['fecha_pdesde'] . '|' . $parametros['fecha_phasta']; //
                        $registroL[4] = time();
                        $registroL[5] = "Registra el pago_cobro para el pensionado con ";
                        $registroL[5] .= " identificacion =" . $parametros['cedula_emp'];
                        $this->log_us->log_usuario($registroL, $this->configuracion);
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
                        $registroL[1] = $parametros['cedula_emp'] . '|' . $parametros['nit_empleador'] . '|' . $parametros['nit_previsional']; //
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
            $registroL[1] = $parametros['cedula_emp'] . '|' . $parametros['nit_empleador'] . '|' . $parametros['nit_previsional']; //
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
