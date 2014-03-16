<?php

/* --------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
  --------------------------------------------------------------------------------------------------------------------------- */
/* ---------------------------------------------------------------------------------------
  |				Control Versiones				    	|
  ----------------------------------------------------------------------------------------
  | fecha      |        Autor            | version     |              Detalle            |
  ----------------------------------------------------------------------------------------
  | 09/07/2013 | Violeta Sosa            | 0.0.0.1     |                                 |
  ----------------------------------------------------------------------------------------
  | 00/03/2014 | Violeta Sosa            | 0.0.0.2     |                                 |
  ----------------------------------------------------------------------------------------
 */


if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/funcionGeneral.class.php");
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/navegacion.class.php");
include_once("html.class.php");

class funciones_liquidador extends funcionGeneral {

    function __construct($configuracion, $sql) {
        //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo		
        //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
        include ($configuracion["raiz_documento"] . $configuracion["estilo"] . "/basico/tema.php");
        include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
        include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/log.class.php");


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

        $this->html_liquidador = new html_liquidador($configuracion);
    }

    /* __________________________________________________________________________________________________

      Metodos específicos
      __________________________________________________________________________________________________ */

//datos basicos para liquidar
    function datosIniciales() {
        $this->html_liquidador->formularioDatos();
    }

//recuperar entidad a liquidar
    function datosEntidad() {
        $parametros = array(
            'cedula' => (isset($_REQUEST['cedula_emp']) ? $_REQUEST['cedula_emp'] : ''));

        $cedula = $_REQUEST['cedula_emp'];
        $datos_entidad = $this->consultarEntidades($parametros);

        $datos_eunicos = array_unique($datos_entidad, SORT_REGULAR);

        $this->html_liquidador->formularioEntidad($cedula, $datos_eunicos);
    }

    //NUEVAS FUNCIONALIDADES A MARZO 2014
    function cadenaLiquidacion() {
        $periodos_liquidacion = array();

        for ($i = 1; $i < 12; $i++) {
            $periodos_liquidacion[$i]['tipo_mesada_1'] = 'mesada_ordinaria';
        }

        $periodos_liquidacion[5]['tipo_mesada_2'] = 'mesada_adicionaljun';
        $periodos_liquidacion[12]['tipo_mesada_2'] = 'mesada_adicionaldic';

        return $periodos_liquidacion;
    }

    function periodoLiquidar($datos_liquidar) {

        $parametros = array(
            'cedula' => $datos_liquidar['cedula_emp'],
            'entidad' => $datos_liquidar['entidad']);

        $consultar_fechas = $this->consultarRecaudos($parametros);
        $consultar_pension = $this->datosConcurrencia($parametros);


        if (is_array($consultar_pension)) {
            if (is_array($consultar_fechas)) {
                $fecha_inicial = date('d/m/Y', strtotime(str_replace('/', '-', $consultar_fechas[0]['recta_fechahasta'])));
            }
            $fecha_inicial = date('d/m/Y', strtotime(str_replace('/', '-', $consultar_pension[0]['dcp_fecha_pension'])));
        } else {
            echo "<script type=\"text/javascript\">" .
            "alert('No existe detalle de la Concurrencia Aceptada para la entidad.');" .
            "</script> ";
            error_log('\n');
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = 'pagina=formularioConcurrencia';
            $variable.='&opcion=';
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
            exit;
        }
        $this->html_liquidador->formularioPeriodo($parametros, $fecha_inicial);
    }

//consultar
    function consultarEntidades($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "consultarEntidades", $parametros);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos;
    }

    function nombreEntidad($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "nombreEntidad", $parametros);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos;
    }

    function consultarDatosHistoria($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "datosHistoria", $parametros);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos;
    }

    function datosPensionado($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_Oracle, "datos_pensionado", $parametros);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_Oracle, $cadena_sql, "busqueda");
        return $datos;
    }

    function datosConcurrencia($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "datos_concurrencia", $parametros);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos;
    }

    //--
    function consultarRecaudos($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "recaudos", $parametros);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos;
    }

    function obtenerIPC($parametro) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "valor_ipc", $parametro);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos;
    }

    function obtenerSumafija($parametro) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "valor_sumafija", $parametro);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos;
    }

    function obtenerDTF($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "valor_dtf", $parametros);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos;
    }

    function mesadaInicial($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "valor_mesada_inicial", $parametros);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos;
    }

    function consecutivo($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "consecutivo", $parametros);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos;
    }

    function guardarLiqui($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "guardarLiquidacion", $parametros);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "registro");
        return $datos;
    }

    function consultarLiqui($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "consultarLiquidacion", $parametros);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos;
    }

    function consultarLiquiFija($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "consultarLiquidacionConsecutivo", $parametros);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos;
    }

    // Reportes Liquidación
    function reportes($datos_basicos, $liquidacion) {

        $parametros = array(
            'cedula_emp' => (isset($datos_basicos['cedula_emp']) ? $datos_basicos['cedula_emp'] : ''),
            'entidad_nit' => (isset($datos_basicos['entidad_nit']) ? $datos_basicos['entidad_nit'] : ''),
        );

        $totales_liq = $this->consultarLiqui($parametros);
        $this->html_liquidador->generarReportes($datos_basicos, $liquidacion, $totales_liq);
    }

    function reporteCuenta($datos_basicos) {
        //definir consecutivo cuenta de cobro
        //recuperar información del sustituto
        //recuperar nombre jefe recursos humanos y tesorero
        $total_liquidacion = $this->consultarLiqui($datos_basicos);
        $this->html_liquidador->reporteCuenta($datos_basicos, $total_liquidacion);
    }

    function reporteResumen($datos_basicos) {
        //definir consecutivo cuenta de cobro
        //recuperar datos de la concurrencia
        //recuperar y organizar año a año liquidación detallada
        //recuperar información del sustituto
        //recuperar nombre jefe recursos humanos
        $total_liquidacion = $this->consultarLiqui($datos_basicos);
        $this->html_liquidador->reporteResumen($datos_basicos, $total_liquidacion);
    }

    function reportesDetalle($datos_basicos, $liquidacion, $consecutivo) {
        //definir consecutivo cuenta de cobro
        //recuperar datos de la concurrencia
        //recuperar y organizar año a año liquidación detallada
        //recuperar información del sustituto
        //recuperar nombre jefe recursos humanos

        $parametros = array(
            'cedula_emp' => $datos_basicos['cedula_emp'],
            'entidad_nit' => $datos_basicos['entidad_nit'],
            'liq_consecutivo' => $consecutivo
        );


        $total_liquidacion = $this->consultarLiquiFija($parametros);
   
        $datos_liquidacion = array(
            'cedula_emp' => $total_liquidacion[0]['liq_cedula'],
            'entidad' => $total_liquidacion[0]['liq_nitprev'],
            'liquidar_desde' => $total_liquidacion[0]['liq_fdesde'],
            'liquidar_hasta' => $total_liquidacion[0]['liq_fhasta']
        );

        $liquidacion=$this->liquidacion($datos_liquidacion);
         $this->html_liquidador->reporteDetalle($datos_basicos, $liquidacion, $total_liquidacion);
    }

//liquidacion

    function calculoTotales($liquidacion_periodo) {

        $calculo_totales = array(
            'mesada' => 0,
            'ajuste_pension' => 0,
            'mesada_adc' => 0,
            'incremento' => 0,
            'cuota_parte' => 0,
            'interes' => 0,
            'total' => 0,
        );

        foreach ($liquidacion_periodo as $key => $value) {
            $calculo_totales['mesada'] = $liquidacion_periodo[$key]['mesada'] + $calculo_totales['mesada'];
            $calculo_totales['ajuste_pension'] = $liquidacion_periodo[$key]['ajuste_pension'] + $calculo_totales['ajuste_pension'];
            $calculo_totales['mesada_adc'] = $liquidacion_periodo[$key]['mesada_adc'] + $calculo_totales['mesada_adc'];
            $calculo_totales['incremento'] = $liquidacion_periodo[$key]['incremento'] + $calculo_totales['incremento'];
            $calculo_totales['cuota_parte'] = $liquidacion_periodo[$key]['cuota_parte'] + $calculo_totales['cuota_parte'];
            $calculo_totales['interes'] = $liquidacion_periodo[$key]['interes'] + $calculo_totales['interes'];
            $calculo_totales['total'] = $liquidacion_periodo[$key]['total'] + $calculo_totales['total'];
        }

        return $calculo_totales;
    }

    function liquidacion($datos_liquidar) {

        $periodo_liquidar = $this->cadenaLiquidacion();

        $parametros = array(
            'cedula' => (isset($datos_liquidar['cedula_emp']) ? $datos_liquidar['cedula_emp'] : ''),
            'entidad' => (isset($datos_liquidar['entidad']) ? $datos_liquidar['entidad'] : ''));

        $datos_concurrencia = $this->datosConcurrencia($parametros);

        $f_pension = date('d/m/Y', strtotime(str_replace('/', '-', $datos_concurrencia[0]['dcp_fecha_pension'])));
        $f_actual = date('d/m/Y');
        $porcentaje_cuota = $datos_concurrencia[0]['dcp_porcen_cuota'];
        $mesada = $datos_concurrencia[0]['dcp_valor_mesada'];

        list ($FECHAS) = $fechas = $this->GenerarFechas($f_pension, $f_actual);
        $TOTAL = 0;

        $liquidacion_cp = array();

        foreach ($FECHAS as $key => $value) {

            //Cadena del periodo liquidar
            $annio = substr($FECHAS[$key], 0, 4);

            //Valor Indices Básicos
            $sumafija = $this->obtenerSumafija($annio);
            $INDICE = $this->obtenerIPC($annio);
            $MESADA = $this->MesadaFecha(($FECHAS[$key]), $mesada, $sumafija);

            //Determinar Cuota Parte
            $CUOTAPARTE = $this->CuotaParte($MESADA, $porcentaje_cuota);

            //Valor Ajustes Adicionales
            $AJUSTEPENSIONAL = $this->AjustePensional(($FECHAS[$key]), $sumafija);
            $MESADAADICIONAL = $this->MesadaAdicional(($FECHAS[$key]), $CUOTAPARTE);
            $INCREMENTOSALUD = $this->IncrementoSalud(($FECHAS[$key]), $CUOTAPARTE);
            //$INTERESES = $this->Intereses($FECHAS[$key], $CUOTAPARTE, $MESADAADICIONAL, $fecha_desde_liquidación);
            $INTERESES = 0;
            //Valor Total Mes liquidado
            $TOTAL = $AJUSTEPENSIONAL + $MESADAADICIONAL + $INCREMENTOSALUD + $CUOTAPARTE + $INTERESES;
            $TOTAL = round($TOTAL, 0);

            //**************SALIDA FINAL****************//

            $liquidacion_cp[$key]['fecha'] = $FECHAS[$key];
            $liquidacion_cp[$key]['ipc'] = $INDICE;
            $liquidacion_cp[$key]['mesada'] = $MESADA;
            $liquidacion_cp[$key]['ajuste_pension'] = $AJUSTEPENSIONAL;
            $liquidacion_cp[$key]['mesada_adc'] = $MESADAADICIONAL;
            $liquidacion_cp[$key]['incremento'] = $INCREMENTOSALUD;
            $liquidacion_cp[$key]['cuota_parte'] = $CUOTAPARTE;
            $liquidacion_cp[$key]['interes'] = $INTERESES;
            $liquidacion_cp[$key]['total'] = $TOTAL;
            $mesada = $MESADA;
        }

        return $liquidacion_cp;
    }

    function calcularPeriodoLiq($datos_liquidar) {
        //Datos del Periodo a Liquidar
        $fecha_inicial = date('m/Y', strtotime(str_replace('/', '-', $datos_liquidar['liquidar_desde'])));
        $fecha_final = date('m/Y', strtotime(str_replace('/', '-', $datos_liquidar['liquidar_hasta'])));

        $liquidacion = $this->liquidacion($datos_liquidar);

        //Generación Arreglo para el periodo especificado
        if (is_array($liquidacion)) {
            $inicio = 0;
            $fin = 0;

            foreach ($liquidacion as $key => $values) {
                $fecha_liq = date('m/Y', strtotime(str_replace('/', '-', $liquidacion[$key]['fecha'])));

                if ($fecha_inicial == $fecha_liq) {
                    $inicio = $key;
                }

                if ($fecha_final == $fecha_liq) {
                    $fin = $key;
                }
            }

            $periodo_calculado = array();

            for ($i = $inicio; $i <= $fin; $i++) {
                $periodo_calculado[$i] = $liquidacion[$i];
            }

            return $periodo_calculado;
        } else {
            echo "<script type=\"text/javascript\">" .
            "alert('Error recuperando la liquidación. Reinicie el proceso.');" .
            "</script> ";
            error_log('\n');
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = 'pagina=liquidadorCP';
            $variable.='&opcion=';
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
            exit;
        }
    }

    function mostrarLiquidacion($datos_liquidar) {
//recuperar periodo calculado
        //Datos Básicos del Detalle de Liquidación
        $nombre_entidad = $this->nombreEntidad($datos_liquidar);
        $nombre_empleado = $this->datosPensionado($datos_liquidar);

        $datos_basicos = array(
            'cedula_emp' => $datos_liquidar['cedula_emp'],
            'nombre_emp' => $nombre_empleado[0]['NOMBRE'],
            'entidad_nombre' => $nombre_entidad[0]['prev_nombre'],
            'entidad_nit' => $datos_liquidar['entidad'],
            'liquidar_desde' => $datos_liquidar['liquidar_desde'],
            'liquidar_hasta' => $datos_liquidar['liquidar_hasta']);

        $periodo_calculado = $this->calcularPeriodoLiq($datos_liquidar);
        // Calculo de Totales
        $total_calculado = $this->calculoTotales($periodo_calculado);
        $this->html_liquidador->liquidador($periodo_calculado, $datos_basicos, $total_calculado);
    }

    function guardarLiquidacion($datos_basicos, $totales_liquidacion) {
        //Generar consecutivo liquidación
        $parametro = array();
        $consecutivo = $this->consecutivo($parametro);
        $consecutivo_real = intval($consecutivo) + 1;

        //Guardar datos Liquidación

        $parametros = array(
            'liq_consecutivo' => (isset($consecutivo_real) ? $consecutivo_real : ''),
            'liq_fgenerado' => date('Y-m-d'),
            'liq_cedula' => (isset($datos_basicos['cedula_emp']) ? $datos_basicos['cedula_emp'] : ''),
            'liq_nitprev' => (isset($datos_basicos['entidad_nit']) ? $datos_basicos['entidad_nit'] : ''),
            'liq_fdesde' => (isset($datos_basicos['liquidar_desde']) ? $datos_basicos['liquidar_desde'] : ''),
            'liq_fhasta' => (isset($datos_basicos['liquidar_hasta']) ? $datos_basicos['liquidar_hasta'] : ''),
            'liq_mesada' => (isset($totales_liquidacion['mesada']) ? $totales_liquidacion['mesada'] : ''),
            'liq_ajustepen' => (isset($totales_liquidacion['ajuste_pension']) ? $totales_liquidacion['ajuste_pension'] : ''),
            'liq_mesada_ad' => (isset($totales_liquidacion['mesada_adc']) ? $totales_liquidacion['mesada_adc'] : ''),
            'liq_incremento' => (isset($totales_liquidacion['incremento']) ? $totales_liquidacion['incremento'] : ''),
            'liq_interes' => (isset($totales_liquidacion['interes']) ? $totales_liquidacion['interes'] : ''),
            'liq_cuotap' => (isset($totales_liquidacion['cuota_parte']) ? $totales_liquidacion['cuota_parte'] : ''),
            'liq_total' => (isset($totales_liquidacion['total']) ? $totales_liquidacion['total'] : ''),
            'liq_estado_cc' => 'ACTIVO',
            'liq_fecha_estado_cc' => null,
            'liq_estado_ccdetalle' => 'ACTIVO',
            'liq_fecha_estado_ccdetalle' => null,
            'liq_estado_ccresumen' => 'ACTIVO',
            'liq_fecha_estado_ccresumen' => null,
            'liq_estado' => 'ACTIVO',
            'liq_fecha_registro' => date('Y-m-d')
        );

        $datos_registrados = $this->guardarLiqui($parametros);

        if ($datos_registrados == true) {
            $registro[0] = "GUARDAR";
            $registro[1] = $parametros['liq_cedula'] . '|' . $parametros['liq_nitprev']; //
            $registro[2] = "CUOTAS_PARTES-LiquidacionGenerada";
            $registro[3] = $parametros['liq_consecutivo'] . '|' . $parametros['liq_fdesde'] . '|' . $parametros['liq_fhasta'] . '|' . $parametros['liq_mesada']
                    . '|' . $parametros['liq_total'] . '|' . $parametros['liq_estado'] . '|' . $parametros['liq_fecha_registro'];
            $registro[4] = time();
            $registro[5] = "Registra datos liquidacion generada para el pensionado con ";
            $registro[5] .= " identificacion =" . $parametros['liq_cedula'];
            $this->log_us->log_usuario($registro, $this->configuracion);

            echo "<script type=\"text/javascript\">" .
            "alert('Liquidación Guardada con éxito');" .
            "</script> ";
        }
    }

// operar

    function GenerarFechas($Fecha_pension, $Fecha_actual) {
        $Anio_p = date('Y', strtotime(str_replace('/', '-', $Fecha_pension)));
        $Mes_p = date("m", strtotime(str_replace('/', '-', $Fecha_pension)));
        $Dia_p = date("d", strtotime(str_replace('/', '-', $Fecha_pension)));

        $Anio_a = date("Y", strtotime(str_replace('/', '-', $Fecha_actual)));
        $Mes_a = date("m", strtotime(str_replace('/', '-', $Fecha_actual)));
        $Dia_a = date("d", strtotime(str_replace('/', '-', $Fecha_actual)));

        settype($Anio_p, "integer");
        settype($Mes_p, "integer");
        settype($Dia_p, "integer");

        settype($Anio_a, "integer");
        settype($Mes_a, "integer");

        $Dia = $Dia_p;

        for ($Anio_p; $Anio_p <= $Anio_a; $Anio_p++) {
            for ($Mes_p; $Mes_p <= 12; $Mes_p++) {
                //echo $Mes_p;
                if ($Anio_p != $Anio_a) {
                    $fecha[] = $Dia . "/" . $Mes_p . "/" . $Anio_p;
                    $Dia = mktime(0, 0, 0, $Mes_p + 2, 0, $Anio_p);
                    $Dia = date("d", $Dia);
                    settype($Dia, "integer");
                    settype($Dia, "integer");
                } elseif ($Mes_p <= $Mes_a) {
                    $fecha[] = $Dia . "/" . $Mes_p . "/" . $Anio_p;
                    $Dia = mktime(0, 0, 0, $Mes_p + 2, 0, $Anio_p);
                    $Dia = date("d", $Dia);
                    settype($Dia, "integer");
                    settype($Dia, "integer");
                }
                //echo $Anio;
                //echo $Anio_p;	
            }
            $Mes_p = 1;
        }
        return array($fecha);
    }

    function AjustePensional($FECHA, $sumafija) {

        $Anio = substr(date("Y", strtotime(str_replace('/', '-', $FECHA))), 0, 4);
        $Mes = substr(date("m", strtotime(str_replace('/', '-', $FECHA))), 0, 2);
        $Dia = substr(date("d", strtotime(str_replace('/', '-', $FECHA))), 0, 2);

        settype($Anio, "integer");
        settype($Mes, "integer");
        settype($Dia, "integer");

        if ($Anio <= 1988) {
            if ($Mes == 1) {
                $AjustePensional = $sumafija[0][0];
            } else {
                $AjustePensional = 0;
            }
        } else {
            $AjustePensional = 0;
        }
        $AjustePensional = round($AjustePensional, 2);
        return ($AjustePensional);
    }

    function CuotaParte($Mesada, $porcentaje) {
        $Mesadacp = round($Mesada);
        $porcentajecp = round($porcentaje, 6);

        //Cuota Parte	
        $Cuotaparte = $porcentajecp * $Mesadacp;
        $Cuotaparte2 = round($Cuotaparte);

        return($Cuotaparte2);
    }

    function IncrementoSalud($fecha, $cuota_calculada) {
        if ($fecha < '1994-1-1') {
            $Incr_Salud = $cuota_calculada * 0.07;
        } else {
            $Incr_Salud = 0;
        }

        $Incr_S = round($Incr_Salud, 2);
        return ($Incr_S);
    }

    function MesadaFecha($FECHA, $Mesada, $sumafija) {
        $Anio = substr(date("Y", strtotime(str_replace('/', '-', $FECHA))), 0, 4);
        $Mes = substr(date("m", strtotime(str_replace('/', '-', $FECHA))), 0, 2);

        settype($Anio, "integer");
        settype($Mes, "integer");

        $Mesada = round($Mesada);

        $INDICE = $this->obtenerIPC($Anio);
        //Ajuste Pensional
        $AJUSTEPENSIONAL = $this->AjustePensional($FECHA, $sumafija);

        if ($Mes == 1) {
            $Mesada_Fecha = ($Mesada * $INDICE[0][0]) + $Mesada + $AJUSTEPENSIONAL;
        } else {
            $Mesada_Fecha = ($Mesada);
        }

        $Mesada_Fecha = round($Mesada_Fecha, 2);
        return ($Mesada_Fecha);
    }

    //MesadaAdicional
    //$cuota_calculada Cuota Parte Calculada
    function MesadaAdicional($FECHA, $cuota_calculada) {
        //Rescatando Año , Mes y Dia
        $Anio = substr(date("Y", strtotime(str_replace('/', '-', $FECHA))), 0, 4);
        $Mes = substr(date("m", strtotime(str_replace('/', '-', $FECHA))), 0, 2);

        settype($Anio, "integer");
        settype($Mes, "integer");

        if ($Anio >= 1994) {
            if ($Mes == 6) {
                $MesaAD = $cuota_calculada;
            } elseif ($Mes == 12) {
                $MesaAD = $cuota_calculada;
            } else {
                $MesaAD = 0;
            }
        } elseif ($Mes == 12) {
            $MesaAD = $cuota_calculada;
        } else {
            $MesaAD = 0;
        }

        $MesaAD = round($MesaAD, 2);
        return ($MesaAD);
    }

    //Rescatando DTF
    function RescatarDTF($FECHA, $fecha_liquidacion) {
        $Anio = substr(date("Y", strtotime(str_replace('/', '-', $FECHA))), 0, 4);
        $Mes = substr(date("m", strtotime(str_replace('/', '-', $FECHA))), 0, 2);

        settype($Anio, "integer");
        settype($Mes, "integer");

        $fecha1 = strtotime($fecha_liquidacion);
        $fecha2 = strtotime($FECHA);

        settype($fecha1, "integer");
        settype($fecha2, "integer");

        $m = $Mes;
        $res[] = 0;

        if ($fecha2 >= $fecha1) {
            for ($a = $Anio; $a <= date("Y"); $a++) {
                for ($m; $m <= 12; $m++) {
                    if ($a != date("Y")) {
                        $fec = $Anio . "-" . $m;
                        $res[] = ($this->obtenerDTF($fec));
                    } elseif ($Anio == date("Y")) {
                        for ($r = 1; $r < date("m"); $r++) {
                            $fec2 = $Anio . "-" . $r;
                            $res[] = ($this->obtenerDTF($fec2));
                        }
                    } else {
                        $res[] = 0;
                    }
                }
                $m = 1;
                $Anio = $Anio + 1;
            }
        }
        //var_dump($res);
        return ($res);
    }

    function Intereses($FECHA, $cuota_parte, $mesada_ad, $fecha_liquidacion) {
        $Anio = substr(date("Y", strtotime(str_replace('/', '-', $FECHA))), 0, 4);
        $Mes = substr(date("m", strtotime(str_replace('/', '-', $FECHA))), 0, 2);

        settype($Anio, "integer");
        settype($Mes, "integer");

        $dtf = ($this->RescatarDtf($FECHA, $fecha_liquidacion));
        // var_dump($dtf);

        $var2 = 1;

        if ($FECHA < (strtotime(date("Y-m")))) {

            foreach ($dtf as $key => $value) {
                $var = ($dtf[$key][0][0]);
                $var = ($var + 1);
                $var2 = $var * $var2;
            }
            // echo $var2 = round($var2, 6) . '<br>';

            $cuota_parte_i = $cuota_parte;
            $mesadaadicional = $mesada_ad;

            $a = ($cuota_parte_i + $mesadaadicional) * $var2;
            $b = $cuota_parte_i + $mesadaadicional;

            $interes_Fecha = $a - $b;
        } else {
            $interes_Fecha = 0;
        }
        $interes_Fecha = round($interes_Fecha, 2);

        // echo $Anio.'--'.$interes_Fecha.'<br>';
        return ($interes_Fecha);
    }

    function cambiafecha_format($fecha) {
        ereg("([0-9]{1,2})/([0-9]{1,2})/([0-9]{2,4})", $fecha, $mifecha);
        $fechana = $mifecha[3] . "-" . $mifecha[2] . "-" . $mifecha[1];
        return $fechana;
    }

}

// fin de la clase
?>