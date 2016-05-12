<?php

/* --------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
  --------------------------------------------------------------------------------------------------------------------------- */
/* ---------------------------------------------------------------------------------------
  |				Control Versiones				    	|
  ----------------------------------------------------------------------------------------
  | fecha      |        Autor            | version     |              Detalle            |
  ----------------------------------------------------------------------------------------
  | 24/05/2013 | Violeta Sosa            | 0.0.0.1     |                                 |
  ----------------------------------------------------------------------------------------
 */


if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/funcionGeneral.class.php");
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/navegacion.class.php");
include_once("html.class.php");

class funciones_adminCuentaCobro extends funcionGeneral {

    function __construct($configuracion, $sql) {
        //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo		
        //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
        include ($configuracion["raiz_documento"] . $configuracion["estilo"] . "/basico/tema.php");
        include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
        include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/log.class.php");
        // include_once($configuracion["host"].$configuracion["site"].$configuracion["bloques"]. "/nomina/liquidador/funcion.class.php");

        $this->cripto = new encriptar();
        $this->log_us = new log();
        $this->sql = $sql;

        //Conexion General
        $this->acceso_db = $this->conectarDB($configuracion, "mysqlFrame");

        //Conexión a Postgres 10.20.2.101
        $this->acceso_pg = $this->conectarDB($configuracion, "cuotas_partes");

        //Conexión a Oracle SUDD
        /*   $this->acceso_sudd = $this->conectarDB($configuracion, "cuotasP"); */

        //Datos de sesion

        $this->usuario = $this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion = $this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
        $this->configuracion = $configuracion;
        $this->htmlCuentaCobro = new html_adminCuentaCobro($configuracion);
    }

    function registroManual() {
        $this->htmlCuentaCobro->formDatoManual();
    }

    function consecutivoCC($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "consecutivoCC", $parametros);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos;
    }

    function consultarConseRecta($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "consecutivoRecta", $parametros);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos;
    }

    function consultarPrevisora($parametro) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "consultarPrevisora", $parametro);
        $datos_previsora = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos_previsora;
    }

    function consultarPrevisoraUnica($parametro) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "consultarPrevisoraUnica", $parametro);
        $datos_previsora = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos_previsora;
    }

    function consultarEmpleador($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "consultarEmpleador", $parametros);
        $datos_registro = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos_registro;
    }

    function consultarEmpleadorUnico($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "consultarEmpleadorUnico", $parametros);
        $datos_registro = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos_registro;
    }

    function consultarPrevForm($parametro) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "consultarPrevFormulario", $parametro);
        $datos_previsora = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos_previsora;
    }

    function consultarHistoria($parametro) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "consultarHistoria", $parametro);
        $datos_historia = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos_historia;
    }

    function consultarCobros($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "consultarCobros", $parametros);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos;
    }

    function consultaPManual($cedula) {

        $datos_previsora = $this->consultarPrevisoraUnica($cedula);

        if (is_array($datos_previsora)) {
            $this->htmlCuentaCobro->previsoraManual($cedula, $datos_previsora);
        } else {

            echo "<script type=\"text/javascript\">" .
            "alert('No existen historias laborales registradas para la cédula " . $cedula . ". Por favor, diligencie el Fomulario de Registro de Historia Laboral');" .
            "</script> ";
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = 'pagina=formHistoria';
            $variable.='&opcion=';
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
            exit;
        }
    }

    function formularioCManual($form_manual) {

        $parametros = array(
            'cedula' => (isset($form_manual['cedula_emp']) ? $form_manual['cedula_emp'] : ''),
            'previsor' => (isset($form_manual['hlab_nitprev']) ? $form_manual['hlab_nitprev'] : '')
        );


        $datos_previsora = $this->consultarPrevForm($parametros);

        $datos_historia = $this->consultarHistoria($parametros);
        $datos_cuentas = $this->consultarCobros($parametros);

        /* Para determinar los limites de las cuentas de cobro */

        if ($datos_cuentas == true) {
            foreach ($datos_cuentas as $key => $value) {
                $rango_cobro[$key] = array(
                    'inicio' => date('d/m/Y', strtotime($value['cob_finicial'])),
                    'fin' => date('d/m/Y', strtotime($value['cob_ffinal'])));
            }
        } else {
            $rango_cobro[0] = array(
                'inicio' => date('d/m/Y', strtotime('01/01/1900')),
                'fin' => date('d/m/Y', strtotime('01/02/1900')));
        }

        /* Para determinar los limites del registro de la historia laboral */

        if ($datos_historia == true) {
            foreach ($datos_historia as $key => $value) {
                $rango[$key] = array(
                    'inicio' => date('d/m/Y', strtotime($value['hlab_fingreso'])),
                    'fin' => date('d/m/Y', strtotime($value['hlab_fretiro'])));
            }
        } else {
            $rango[0] = array(
                'inicio' => date('d/m/Y', strtotime('01/01/1940')),
                'fin' => date('d/m/Y', strtotime('01/01/2000')));
        }


        $this->htmlCuentaCobro->formRegistroManual($datos_previsora, $form_manual, $rango, $rango_cobro);
    }

    function registrarManual($datos) {

        /* validación campos vacíos */
        foreach ($datos as $key => $value) {

            if ($datos[$key] == "") {
                echo "<script type=\"text/javascript\">" .
                "alert('Formulario NO diligenciado correctamente');" .
                "</script> ";
                $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                $variable = 'pagina=formularioCManual';
                $variable.='&opcion=manual';
                $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                exit;
            }
        }

        if ($datos['mesada'] == 0) {
            echo "<script type=\"text/javascript\">" .
            "alert('Valor de Mesada No Válido');" .
            "</script> ";
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = 'pagina=formularioCManual';
            $variable.='&opcion=manual';
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
            exit;
        }

        if ($datos['mesada_adc'] == 0) {
            echo "<script type=\"text/javascript\">" .
            "alert('Valor de Mesada Adicional No Válido');" .
            "</script> ";
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = 'pagina=formularioCManual';
            $variable.='&opcion=manual';
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
            exit;
        }

        if ($datos['subtotal'] == 0) {
            echo "<script type=\"text/javascript\">" .
            "alert('Valor de Subtotal No Válido');" .
            "</script> ";
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = 'pagina=formularioCManual';
            $variable.='&opcion=manual';
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
            exit;
        }

        if ($datos['incremento'] < 0) {
            echo "<script type=\"text/javascript\">" .
            "alert('Valor de Incremento No Válido');" .
            "</script> ";
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = 'pagina=formularioCManual';
            $variable.='&opcion=manual';
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
            exit;
        }

        if ($datos['t_sin_interes'] == 0) {
            echo "<script type=\"text/javascript\">" .
            "alert('Valor de Total sin Interes No Válido');" .
            "</script> ";
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = 'pagina=formularioCManual';
            $variable.='&opcion=manual';
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
            exit;
        }

        if ($datos['interes'] < 0) {
            echo "<script type=\"text/javascript\">" .
            "alert('Valor de Interes No Válido');" .
            "</script> ";
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = 'pagina=formularioCManual';
            $variable.='&opcion=manual';
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
            exit;
        }

        if ($datos['t_con_interes'] == 0) {
            echo "<script type=\"text/javascript\">" .
            "alert('Valor de Total con Interes No Válido');" .
            "</script> ";
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = 'pagina=formularioCManual';
            $variable.='&opcion=manual';
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
            exit;
        }

        /* validación suma de campos */

        $subtotal = floatval($datos['mesada'] + $datos['mesada_adc']);
        $total_sin_interes = floatval($subtotal + $datos['incremento']);
        $total_con_interes = floatval($total_sin_interes + $datos['interes']);

        if (floatval($datos['subtotal']) !== $subtotal) {
            echo "<script type=\"text/javascript\">" .
            "alert('El Subtotal no corresponde a la suma de valores correspondientes!');" .
            "</script> ";
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = 'pagina=formularioCManual';
            $variable.='&opcion=manual';
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
            exit;
        }

        if (floatval($datos['t_sin_interes']) !== $total_sin_interes) {
            echo "<script type=\"text/javascript\">" .
            "alert('El Valor Total Sin Interes no corresponde a la suma de valores correspondientes!');" .
            "</script> ";
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = 'pagina=formularioCManual';
            $variable.='&opcion=manual';
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
            exit;
        }

        if (floatval($datos['t_con_interes']) !== $total_con_interes) {
            echo "<script type=\"text/javascript\">" .
            "alert('El Valor Total con Interes no corresponde a la suma de valores correspondientes!');" .
            "</script> ";
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = 'pagina=formularioCManual';
            $variable.='&opcion=manual';
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
            exit;
        }

        /* validación formato de campos numéricos */

        if (!preg_match("^(?!\.?$)\d{0,10}(\.\d{0,2})?$^", $datos['mesada'])) {
            echo "<script type=\"text/javascript\">" .
            "alert('Valor de Mesada No Válido');" .
            "</script> ";
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = 'pagina=formularioCManual';
            $variable.='&opcion=manual';
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
            exit;
        }

        if (!preg_match("^(?!\.?$)\d{0,10}(\.\d{0,2})?$^", $datos['mesada_adc'])) {
            echo "<script type=\"text/javascript\">" .
            "alert('Valor de Mesada Adicional No Válido');" .
            "</script> ";
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = 'pagina=formularioCManual';
            $variable.='&opcion=manual';
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
            exit;
        }

        if (!preg_match("^(?!\.?$)\d{0,10}(\.\d{0,2})?$^", $datos['subtotal'])) {
            echo "<script type=\"text/javascript\">" .
            "alert('Valor de Subtotal No Válido');" .
            "</script> ";
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = 'pagina=formularioCManual';
            $variable.='&opcion=manual';
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
            exit;
        }

        if (!preg_match("^(?!\.?$)\d{0,10}(\.\d{0,2})?$^", $datos['incremento'])) {
            echo "<script type=\"text/javascript\">" .
            "alert('Valor de Incremento No Válido');" .
            "</script> ";
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = 'pagina=formularioCManual';
            $variable.='&opcion=manual';
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
            exit;
        }

        if (!preg_match("^(?!\.?$)\d{0,10}(\.\d{0,2})?$^", $datos['t_sin_interes'])) {
            echo "<script type=\"text/javascript\">" .
            "alert('Valor de Total sin Interes No Válido');" .
            "</script> ";
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = 'pagina=formularioCManual';
            $variable.='&opcion=manual';
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
            exit;
        }

        if (!preg_match("^(?!\.?$)\d{0,10}(\.\d{0,2})?$^", $datos['interes'])) {
            echo "<script type=\"text/javascript\">" .
            "alert('Valor de Interes No Válido');" .
            "</script> ";
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = 'pagina=formularioCManual';
            $variable.='&opcion=manual';
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
            exit;
        }

        if (!preg_match("^(?!\.?$)\d{0,10}(\.\d{0,2})?$^", $datos['t_con_interes'])) {
            echo "<script type=\"text/javascript\">" .
            "alert('Valor de Total con Interes No Válido');" .
            "</script> ";
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = 'pagina=formularioCManual';
            $variable.='&opcion=manual';
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
            exit;
        }

        if (!preg_match("^(?!\.?$)\d{0,10}(\.\d{0,2})?$^", $datos['saldo_fecha'])) {
            echo "<script type=\"text/javascript\">" .
            "alert('Valor de Saldo Fecha No Válido');" .
            "</script> ";
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = 'pagina=formularioCManual';
            $variable.='&opcion=manual';
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
            exit;
        }

        /* validación traslape fechas */

        $antes = strtotime(str_replace('/', '-', $datos['fecha_inicial']));
        $despues = strtotime(str_replace('/', '-', $datos['fecha_final']));

        if ($antes > $despues) {
            echo "<script type=\"text/javascript\">" .
            "alert('Intervalo de fechas de cobro no válido');" .
            "</script> ";
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = 'pagina=formularioCManual';
            $variable.='&opcion=manual';
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace(' " . $pagina . $variable . "')</script>";
            exit;
        }

        $antes_2 = strtotime(str_replace('/', '-', $datos['fecha_final']));
        $despues_2 = strtotime(str_replace('/', '-', $datos['fecha_generacion']));

        if ($antes_2 > $despues_2) {
            echo "<script type=\"text/javascript\">" .
            "alert('Fecha Final no coincide con Fecha Generación Cuenta de Cobro');" .
            "</script> ";
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = 'pagina=formularioCManual';
            $variable.='&opcion=manual';
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace(' " . $pagina . $variable . "')</script>";
            exit;
        }

        /* validación de las fechas de cobro con la historia laboral */

        $parametros = array(
            'cedula' => $datos['cedula']
        );

        $datos_historia = $this->consultarHistoria($parametros);

        /* Para determinar los limites del registro de la historia laboral */

        if ($datos_historia == true) {
            foreach ($datos_historia as $key => $value) {
                $rango_h[$key] = array(
                    'inicio' => date('d/m/Y', strtotime($value['hlab_fingreso'])),
                    'fin' => date('d/m/Y', strtotime($value['hlab_fretiro'])));
            }
        } else {
            $rango_h[$key] = array(
                'inicio' => date('d/m/Y', strtotime('01/01/1940')),
                'fin' => date('d/m/Y', strtotime('01/01/2000')));
        }

        foreach ($rango_h as $key => $values) {

            $inicio = strtotime(str_replace('/', '-', $rango_h[$key]['inicio']));
            $fin = strtotime(str_replace('/', '-', $rango_h[$key]['fin']));

            if ($antes < $inicio) {
                echo "<script type=\"text/javascript\">" .
                "alert('El intervalo de fechas de cobro no es válido');" .
                "</script> ";
                error_log('\n');
                $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                $variable = 'pagina=formularioCManual';
                $variable.='&opcion=manual';
                $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                exit;
            }

            if ($despues < $fin) {
                echo "<script type=\"text/javascript\">" .
                "alert('El intervalo de fechas de cobro laborado no es válido');" .
                "</script> ";
                error_log('\n');
                $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                $variable = 'pagina=formularioCManual';
                $variable.='&opcion=manual';
                $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                exit;
            }
        }

        /* para revisar que las cuentas de cobro no se traslapen entre ellas mismas */

        $parametros = array(
            'cedula' => $datos['cedula'],
            'previsor' => $datos['entidad_previsora']);

        $datos_cuentas = $this->consultarCobros($parametros);

        if (is_array($datos_cuentas)) {
            if ($datos_cuentas == true) {
                foreach ($datos_cuentas as $key => $value) {
                    $rango_cobro[$key] = array(
                        'inicio' => date('d/m/Y', strtotime($value['cob_finicial'])),
                        'fin' => date('d/m/Y', strtotime($value['cob_ffinal'])));
                }
            } else {
                $rango_cobro[$key] = array(
                    'inicio' => date('d/m/Y', strtotime('01/01/1900')),
                    'fin' => date('d/m/Y', strtotime('01/02/1900')));
            }

            foreach ($rango_cobro as $key => $values) {

                $inicio = strtotime(str_replace('/', '-', $rango_cobro[$key]['inicio']));
                $fin = strtotime(str_replace('/', '-', $rango_cobro[$key]['fin']));

                if ($antes > $inicio && $antes < $fin) {
                    echo "<script type=\"text/javascript\">" .
                    "alert('El intervalo de cobro no es válido');" .
                    "</script> ";
                    error_log('\n');
                    $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                    $variable = 'pagina=formularioCManual';
                    $variable.='&opcion=manual';
                    $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                    echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                    exit;
                }

                if ($despues > $inicio && $despues < $fin) {
                    echo "<script type=\"text/javascript\">" .
                    "alert('El intervalo de cobro no es válido');" .
                    "</script> ";
                    error_log('\n');
                    $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                    $variable = 'pagina=formularioCManual';
                    $variable.='&opcion=manual';
                    $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                    echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                    exit;
                }
            }
        }
        /* Proceder a registrar después de procesar los datos */

        $estado = 1;
        $fecha_registro = date('d/m/Y');

        $parametros = array(
            
            'cedula' => (isset($datos['cedula']) ? $datos['cedula'] : ''),
            'previsor' => (isset($datos['entidad_previsora']) ? $datos['entidad_previsora'] : ''),
            'consecutivo_cc' => (isset($datos['consecutivo_cc']) ? $datos['consecutivo_cc'] : ''),
            'fecha_generacion' => (isset($datos['fecha_generacion']) ? $datos['fecha_generacion'] : ''),
            'fecha_inicial' => (isset($datos['fecha_inicial']) ? $datos['fecha_inicial'] : ''),
            'fecha_final' => (isset($datos['fecha_final']) ? $datos['fecha_final'] : ''),
            'mesada' => (isset($datos['mesada']) ? $datos['mesada'] : ''),
            'mesada_adc' => (isset($datos['mesada_adc']) ? $datos['mesada_adc'] : ''),
            'subtotal' => (isset($datos['subtotal']) ? $datos['subtotal'] : ''),
            'incremento' => (isset($datos['incremento']) ? $datos['incremento'] : ''),
            't_sin_interes' => (isset($datos['t_sin_interes']) ? $datos['t_sin_interes'] : ''),
            'interes' => (isset($datos['interes']) ? $datos['interes'] : ''),
            't_con_interes' => (isset($datos['t_con_interes']) ? $datos['t_con_interes'] : ''),
            'saldo_fecha' => (isset($datos['saldo_fecha']) ? $datos['saldo_fecha'] : ''),
            'fecha_recibido' => (isset($datos['fecha_recibido']) ? $datos['fecha_recibido'] : ''),
            'estado_cuenta' => 'ACTIVA',
            'estado' => 'ACTIVA',
            'fecha_registro' => $fecha_registro
        );

        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "insertarCManual", $parametros);
        $datos_registrados = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "registrar");

        if ($datos_registrados == true) {
            $registro[0] = "GUARDAR";
            $registro[1] = $parametros['cedula'] . '|' . $parametros['previsor']; //
            $registro[2] = "CUOTAS_PARTES-CuentaCobroManual";
            $registro[3] = $parametros['consecutivo_cc'] . '|' . $parametros['fecha_inicial'] . '|' . $parametros['fecha_final'] . '|' . $parametros['mesada']
                    . '|' . $parametros['mesada_adc'] . '|' . $parametros['subtotal'] . '|' . $parametros['incremento'] . '|' . $parametros['t_sin_interes'] . '|' . $parametros['interes']
                    . '|' . $parametros['t_con_interes'] . '|' . $parametros['saldo_fecha'] . '|' . $parametros['fecha_recibido']; //
            $registro[4] = time();
            $registro[5] = "Registra datos cuenta de cobro manual del pensionado con ";
            $registro[5] .= " identificacion =" . $parametros['cedula'];
            $this->log_us->log_usuario($registro, $this->configuracion);
        } else {
            echo "<script type=\"text/javascript\">" .
            "alert('Datos NO Registrados Correctamente. ERROR en el REGISTRO');" .
            "</script> ";
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = "pagina=formularioCManual";
            $variable .= "&opcion=manual";
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
            exit;
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
            'cedula' => (isset($datos['cedula']) ? $datos['cedula'] : ''),
            'previsor' => (isset($datos['entidad_previsora']) ? $datos['entidad_previsora'] : ''),
            'consecutivo_cc' => (isset($datos['consecutivo_cc']) ? $datos['consecutivo_cc'] : ''),
            'recaudo' => 0,
            'consecu_rec' => '',
            'capital'=>(isset($datos['t_sin_interes']) ? $datos['t_sin_interes'] : ''),
            'interes' => (isset($datos['interes']) ? $datos['interes'] : ''),
            't_con_interes' => (isset($datos['t_con_interes']) ? $datos['t_con_interes'] : ''),
            'saldo_fecha' => (isset($datos['saldo_fecha']) ? $datos['saldo_fecha'] : '')
        );

        $cadena_sql_saldo = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "insertarCManualSaldo", $parametros_saldo);
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
            "alert('Datos NO Registrados Correctamente. ERROR en el REGISTRO');" .
            "</script> ";
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = "pagina=formularioCManual";
            $variable .= "&opcion=manual";
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
            exit;
        }
    }

    /* __________________________________________________________________________________________________

      Metodos especificos
      __________________________________________________________________________________________________ */

//fin 
    function inicio() {
        $this->htmlCuentaCobro->form_valores_cuotas_partes();
    }

}

// fin de la clase
?>