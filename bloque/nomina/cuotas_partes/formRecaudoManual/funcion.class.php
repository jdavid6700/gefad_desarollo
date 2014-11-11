
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
  |				Control Versiones				       	 |
  ----------------------------------------------------------------------------------------
  | fecha      |        Autor            | version     |              Detalle            |
  ----------------------------------------------------------------------------------------
  | 23/07/2014 | Violet Sosa             | 0.0.0.1     |                                 |
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

class funciones_formRecaudoManual extends funcionGeneral {

    function __construct($configuracion, $sql) {
        //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo		
        //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
        include ($configuracion["raiz_documento"] . $configuracion["estilo"] . "/basico/tema.php");
        include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
        include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/log.class.php");
        include_once($configuracion["raiz_documento"] . $configuracion["plugins"] . "/html2pdf/html2pdf.class.php");

        $this->cripto = new encriptar();
        $this->log_us = new log();
        $this->sql = $sql;

        //Conexion General
        $this->acceso_db = $this->conectarDB($configuracion, "mysqlFrame");

        //Conexión a Postgres 
        $this->acceso_pg = $this->conectarDB($configuracion, "cuotas_partes");

        //Conexión a Oracle
        $this->acceso_Oracle = $this->conectarDB($configuracion, "cuotasP");

        //Datos de sesion

        $this->usuario = $this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion = $this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");

        $this->configuracion = $configuracion;

        $this->html_formRecaudoManual = new html_formRecaudoManual($configuracion);
    }

    function inicio() {
        $this->html_formRecaudoManual->form_valor();
    }

    function consultarEntidadesRecaudo($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "consultarEntidadesRecaudo", $parametros);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos;
    }

    function consultarConsecPago() {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "consultaPagoConsecutivo", "");
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos;
    }

    function mostrarFormulario($recaudo_manual) {

        $parametros = array(
            'cedula' => (isset($recaudo_manual['cedula_emp']) ? $recaudo_manual['cedula_emp'] : ''),
            'previsora' => (isset($recaudo_manual['hlab_nitprev']) ? $recaudo_manual['hlab_nitprev'] : '')
        );

        //no entiendo cómo sacar la deuda :(        
        $this->html_formRecaudoManual->formularioRegistro($parametros);
    }

    function entidadFormulario($cedula) {
        $cedula = array('cedula' => (isset($_REQUEST['cedula_emp']) ? $_REQUEST['cedula_emp'] : ''));

        if (!preg_match("^\d+$^", $cedula['cedula'])) {
            echo "<script type=\"text/javascript\">" .
            "alert('La cédula posee un formato inválido');" .
            "</script> ";
            error_log('\n');
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = 'pagina=formularioRManual';
            $variable.='&opcion=';
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
            exit;
        } else {

            $datos_entidad = $this->consultarEntidadesRecaudo($cedula);

            if (is_array($datos_entidad)) {
                $this->html_formRecaudoManual->datosRecaudos($cedula, $datos_entidad);
            } else {
                echo "<script type=\"text/javascript\">" .
                "alert('No existen Entidades Previsoras asociadas a la cédula " . $cedula['cedula'] . ". ');" .
                "</script> ";
                $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                $variable = 'pagina=formHistoria';
                $variable.='&opcion=';
                $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                exit;
            }
        }
    }

    function consultarPrevisora() {
        $parametros = array();
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "consultarPrevisora", $parametros);
        $datos_previsora = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos_previsora;
    }

    function registrarPago($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "registrarPago", $parametros);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "registrar");
        return $datos;
    }
    
     function actualizarSaldo($parametros) {

        //Verificar la cuenta de cobro y recaudo
        $consultar_ccobro = $this->consultar_cuentac($parametros);
        $consultar_recaudo = $this->consultarRecaudoUnico($parametros);

      /*  if ($consultar_ccobro == null) {
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
*/
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
    
    
     function registrarCobro() {

        // argumento original => $datos_basicos, $consecutivocc, $totales_liq
        $parametros_x = array();
        $consecutivo = $this->consecutivoCC($parametros_x);
        $consecutivo_cc = $this->generarConsecutivo($parametros_x);

        $cons = intval($consecutivo[0][0]) + 1;

        //$subtotal = $totales_liq[0]['liq_cuotap'] + $totales_liq[0]['liq_mesada_ad'];
        //$t_s_interes = $subtotal + $totales_liq[0]['liq_incremento'] + $totales_liq[0]['liq_ajustepen'];
        
        $subtotal = 0;
        $t_s_interes = 0;

        $parametros = array(
            'id_liq' => '',
            'id_cuentac' => $cons,
            'fecha_generacion' => date(),
            'cedula' => 0,
            'previsor' => $totales_liq[0]['liq_nitprev'],
            'consecutivo_cc' => $consecutivo_cc,
            'saldo_fecha' => $totales_liq[0]['liq_total'],
            'fecha_inicial' => $totales_liq[0]['liq_fdesde'],
            'fecha_final' => $totales_liq[0]['liq_fhasta'],
            'mesada_ordinaria' => $totales_liq[0]['liq_cuotap'],
            'mesada_adc' => $totales_liq[0]['liq_mesada_ad'],
            'subtotal' => $subtotal,
            'incremento' => $totales_liq[0]['liq_incremento'],
            'ajuste_pension' => $totales_liq[0]['liq_ajustepen'],
            't_sin_interes' => $t_s_interes,
            'interes' => $totales_liq[0]['liq_interes'],
            't_con_interes' => $totales_liq[0]['liq_total'],
            'total' => $totales_liq[0]['liq_total'],
            'fecha_recibido' => '',
            'estado_cuenta' => 'ACTIVA',
            'estado' => $totales_liq[0]['liq_estado'],
            'fecha_registro' => $totales_liq[0]['liq_fecha_registro']
        );

        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "guardar_cuentac", $parametros);
        $datos_registrados = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "registrar");

        if ($datos_registrados == true) {
            $registro[0] = "GUARDAR";
            $registro[1] = $parametros['cedula'] . '|' . $parametros['previsor']; //
            $registro[2] = "CUOTAS_PARTES-CuentaCobroSistema";
            $registro[3] = $parametros['consecutivo_cc'] . '|' . $parametros['fecha_inicial'] . '|' . $parametros['fecha_final'] . '|' . $parametros['mesada_ordinaria']
                    . '|' . $parametros['mesada_adc'] . '|' . $parametros['subtotal'] . '|' . $parametros['incremento'] . '|' . $parametros['t_sin_interes'] . '|' . $parametros['interes']
                    . '|' . $parametros['t_con_interes'] . '|' . $parametros['saldo_fecha'] . '|' . $parametros['fecha_recibido']; //
            $registro[4] = time();
            $registro[5] = "Registra datos cuenta de cobro del sistema del pensionado con ";
            $registro[5] .= " identificacion =" . $parametros['cedula'];
            $this->log_us->log_usuario($registro, $this->configuracion);

            "<script type=\"text/javascript\">" .
                    "alert('Datos Registrados');" .
                    "</script> ";
        } else {
            "<script type=\"text/javascript\">" .
                    "alert('Esta Cuenta de Cobro ya Existe!. ERROR en el REGISTRO');" .
                    "</script> ";
        }

        $parametros_z = array();
        $consecutivo_recta = $this->consultarConseRecta($parametros_z);

        if ($consecutivo_recta == null) {
            $rectaid = 1;
        } else {
            $rectaid = $consecutivo_recta[0][0] + 1;
        }

        $parametros_saldo = array(
            'id_registro' => $rectaid,
            'cedula' => $totales_liq[0]['liq_cedula'],
            'previsor' => $totales_liq[0]['liq_nitprev'],
            'consecutivo_cc' => $consecutivo_cc,
            'recaudo' => 0,
            'consecu_rec' => '',
            'capital' => $t_s_interes,
            'interes' => $totales_liq[0]['liq_interes'],
            't_con_interes' => $totales_liq[0]['liq_total'],
            'saldo_fecha' => $totales_liq[0]['liq_total']
        );

        $cadena_sql_saldo = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "insertarRecta", $parametros_saldo);
        $registro_saldo = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql_saldo, "registrar");

        if ($registro_saldo == true) {
            $registro[0] = "GUARDAR";
            $registro[1] = $parametros['cedula'] . '|' . $parametros['previsor']; //
            $registro[2] = "CUOTAS_PARTES-CuentaCobroManualSaldo";
            $registro[3] = $parametros['consecutivo_cc'] . '|' . $parametros['fecha_inicial'] . '|' . $parametros['fecha_final'] . '|' . $parametros['mesada']
                    . '|' . $parametros['mesada_adc'] . '|' . $parametros['subtotal'] . '|' . $parametros['incremento'] . '|' . $parametros['t_sin_interes'] . '|' . $parametros['interes']
                    . '|' . $parametros['t_con_interes'] . '|' . $parametros['saldo_fecha'] . '|' . $parametros['fecha_recibido']; //
            $registro[4] = time();
            $registro[5] = "Registra datos cuenta de cobro manual del pensionado en la tabla de saldos, con ";
            $registro[5] .= " identificacion =" . $parametros['cedula'];
            $this->log_us->log_usuario($registro, $this->configuracion);

            "<script type=\"text/javascript\">" .
                    "alert('Datos Registrados');" .
                    "</script> ";
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = "pagina=reportesCuotas";
            $variable .= "&opcion=";
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            "<script>location.replace('" . $pagina . $variable . "')</script>";
            exit;
        } else {
            "<script type=\"text/javascript\">" .
                    "alert('Datos NO Registrados Correctamente. ERROR en el REGISTRO');" .
                    "</script> ";
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = "pagina=formularioCManual";
            $variable .= "&opcion=manual";
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            "<script>location.replace('" . $pagina . $variable . "')</script>";
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
                $variable = 'pagina=formularioRManual';
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
            $variable = 'pagina=formularioRManual';
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
            $variable = 'pagina=formularioRManual';
            $variable.='&opcion=';
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace(' " . $pagina . $variable . "')</script>";
            exit;
        }

        if (doubleval($datos['valor_pagado_capital']) <= 0) {
            echo "<script type=\"text/javascript\">" .
            "alert('Valor Pagado Capital NO Válido');" .
            "</script> ";
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = 'pagina=formularioRManual';
            $variable.='&opcion=';
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
            exit;
        }

        if (doubleval($datos['valor_pagado_interes']) < 0) {
            echo "<script type=\"text/javascript\">" .
            "alert('Valor Pagado Interes NO Válido');" .
            "</script> ";
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = 'pagina=formularioRManual';
            $variable.='&opcion=';
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
            exit;
        }

        $total_capital = doubleval($datos['valor_pagado_capital']);
        $total_interes = doubleval($datos['valor_pagado_interes']);

        $total_pagado = $total_capital + $total_interes;


        if (intval($datos['total_recaudo']) !== intval($total_pagado)) {
            echo "<script type=\"text/javascript\">" .
            "alert('Valor Total Pagado no corresponde a la Suma de los valores correspondientes!');" .
            "</script> ";
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = 'pagina=formularioRManual';
            $variable.='&opcion=';
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
            exit;
        }

        /** Validar el traslape de las fechas de cobro y pago* */
        $parametros_rec = array(
            'cedula_emp' => $datos['cedula_emp'],
            'nit_previsional' => $datos['previsora']
        );

        /*
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
          } */


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
            'nit_previsional' => (isset($datos['previsora']) ? $datos['previsora'] : ''),
            'cedula_emp' => (isset($datos['cedula_emp']) ? $datos['cedula_emp'] : ''),
            'actoadmin' => '',
            'factoadmin' => '',
            'resolucion_OP' => (isset($datos['resolucion_OP']) ? $datos['resolucion_OP'] : ''),
            'fecha_resolucion' => (isset($datos['fecha_resolucion']) ? $datos['fecha_resolucion'] : ''),
            'fecha_pago_cuenta' => (isset($datos['fecha_pago_cuenta']) ? $datos['fecha_pago_cuenta'] : ''),
            'medio_pago' => (isset($datos['medio_pago']) ? $datos['medio_pago'] : ''),
            'valor_pagado_capital' => (isset($total_capital) ? $total_capital : ''),
            'valor_pagado_interes' => (isset($total_interes) ? $total_interes : ''),
            'fecha_registro' => date('Y-m-d'),
            'total_recaudo' => (isset($datos['total_recaudo']) ? $datos['total_recaudo'] : ''));

        $datos_recaudo = $this->registrarPago($parametros_recaudo);
        
        //$datos_cuentacobro=  $this->registrarCobro($parametros_cobro);
        
       

        if ($datos_recaudo == 1) {

            $total_Recaudo = intval($datos['valor_pagado_interes']) + intval($datos['valor_pagado_capital']);

            $parametros = array(
                'consecutivo_cc' => 'CSC-'.$datos['nit_previsional'],
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
                $variable = 'pagina=formularioRManual';
                $variable .= "&opcion=";
                $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                echo "<script>location.replace('" . $pagina . $variable . "')</script>";
  
            }

            $actualizar_cobro = $this->actualizarEstadoCobro($parametros['consecutivo_cc']);

            if ($actualizar_cobro == false) {
                echo "<script type=\"text/javascript\">" .
                "alert('Datos de Actualización de Cobro NO Registrados Correctamente. ERROR en el REGISTRO');" .
                "</script> ";

                $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                $variable = 'pagina=formularioRManual';
                $variable .= "&opcion=";
                $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                echo "<script>location.replace('" . $pagina . $variable . "')</script>";
  
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

/*


        //aqui temrina
        $registroL[0] = "REGISTRAR";
        $registroL[1] = $parametros_recaudo['cedula_emp'] . '|' . $parametros_recaudo['nit_previsional']; //
        $registroL[2] = "CUOTAS_PARTES";
        $registroL[3] = $parametros_recaudo['consecutivo_rec'] . '|' . $parametros_recaudo['total_recaudo']; //
        $registroL[4] = time();
        $registroL[5] = "Registra el pago para el pensionado con ";
        $registroL[5] .= " identificacion =" . $parametros_recaudo['cedula_emp'];
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
        }else{

            echo "<script type=\"text/javascript\">" .
            "alert('Datos de Recaudos NO Registrados Correctamente. ERROR en el REGISTRO');" .
            "</script> ";

            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = 'pagina=formularioRManual';
            $variable .= "&opcion=";
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
            exit;
        }*/
    }

}


