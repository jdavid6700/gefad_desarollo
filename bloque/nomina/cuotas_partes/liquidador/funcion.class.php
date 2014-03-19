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

    function datosEntidad() {
        $parametros = array(
            'cedula' => (isset($_REQUEST['cedula']) ? $_REQUEST['cedula'] : ''));

        $cedula = $_REQUEST['cedula'];
        $datos_entidad = $this->consultarEntidades($parametros);

        if (!is_array($datos_entidad)) {
            echo "<script type=\"text/javascript\">" .
            "alert('No existe detalle de la Concurrencia Aceptada para la cedula " . $cedula . ".');" .
            "</script> ";
            error_log('\n');
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = 'pagina=formularioConcurrencia';
            $variable.='&opcion=';
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
            exit;
        }

        $temp_array = array();

        foreach ($datos_entidad as $v) {

            if (!isset($temp_array[$v['prev_nit']])) {
                $temp_array[$v['prev_nit']] = $v;
            }
        }


        $datos_eunicos = array_values($temp_array);

        $this->html_liquidador->formularioEntidad($cedula, $datos_eunicos);
    }

    function datosInicialesReporte() {
        $this->html_liquidador->formularioDatosReporte();
    }

    function datosEntidadReporte() {
        $parametros = array(
            'cedula' => (isset($_REQUEST['cedula']) ? $_REQUEST['cedula'] : ''));

        $cedula = $_REQUEST['cedula'];
        $datos_entidad = $this->consultarEntidades($parametros);

        if (!is_array($datos_entidad)) {
            echo "<script type=\"text/javascript\">" .
            "alert('No existe detalle de la Concurrencia Aceptada para la cedula " . $cedula . ".');" .
            "</script> ";
            error_log('\n');
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = 'pagina=formularioConcurrencia';
            $variable.='&opcion=';
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
            exit;
        }

        //$datos_eunicos = array_intersect_key($datos_entidad, array_unique(array_map('serialize', $datos_entidad)));

        $temp_array = array();

        foreach ($datos_entidad as $v) {

            if (!isset($temp_array[$v['prev_nit']])) {
                $temp_array[$v['prev_nit']] = $v;
            }
        }

        $datos_eunicos = array_values($temp_array);

        $this->html_liquidador->formularioEntidadReporte($cedula, $datos_eunicos);
    }

//recuperar entidad a liquidar
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
            'cedula' => $datos_liquidar['cedula'],
            'entidad' => $datos_liquidar['prev_nit']);

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
    function consultarJefeRecursos($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_Oracle, "jefeRecursosH", $parametros);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_Oracle, $cadena_sql, "busqueda");
        return $datos;
    }

    function consultarJefeTesoreria($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_Oracle, "jefeTesoreria", $parametros);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_Oracle, $cadena_sql, "busqueda");
        return $datos;
    }

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

    function consultarDatosHistoriaTotal($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "datosHistoriaTotales", $parametros);
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

    function consecutivoCC($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "consecutivoCC", $parametros);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos;
    }

    function consultarCC($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "consultarCC", $parametros);
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
    function reportes($datos_basicos) {

        $parametros = array(
            'cedula' => (isset($datos_basicos['cedula']) ? $datos_basicos['cedula'] : ''),
            'entidad' => (isset($datos_basicos['entidad']) ? $datos_basicos['entidad'] : ''),
        );

        if (!isset($datos_basicos['entidad_nombre'])) {

            $nombre_entidad = $this->nombreEntidad($datos_basicos);
            $nombre_empleado = $this->datosPensionado($datos_basicos);
            $datos_basicos = array(
                'cedula' => $datos_basicos['cedula'],
                'nombre_emp' => $nombre_empleado[0]['NOMBRE'],
                'entidad_nombre' => $nombre_entidad[0]['prev_nombre'],
                'entidad' => $datos_basicos['entidad']);
        }

        $totales_liq = $this->consultarLiqui($parametros);

        if (is_array($totales_liq)) {
            $this->html_liquidador->generarReportes($datos_basicos, $totales_liq);
        } else {
            echo "<script type=\"text/javascript\">" .
            "alert('No existen Liquidaciones Generadas para la Entidad.');" .
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

    function reporteCuenta($datos_basicos, $consecutivo) {
        //recuperar información del sustituto
        //recuperar nombre jefe recursos humanos y tesorero
        $parametros = array(
            'cedula' => $datos_basicos['cedula'],
            'entidad' => $datos_basicos['entidad'],
            'liq_consecutivo' => $consecutivo
        );

        $total_liquidacion = $this->consultarLiquiFija($parametros);
        $enletras = strtoupper($this->num2letras($total_liquidacion[0]['liq_total']));
        //definir consecutivo cuenta de cobro
        $consecutivo = $this->generarConsecutivo();

        $a = array();
        $jefe_recursos = $this->consultarJefeRecursos($a);
        $jefe_tesoreria = $this->consultarJefeTesoreria($a);

        $this->html_liquidador->reporteCuenta($datos_basicos, $total_liquidacion, $enletras, $consecutivo, $jefe_recursos, $jefe_tesoreria);
    }

    function reporteResumen($datos_basicos, $consecutivo) {
        //definir consecutivo cuenta de cobro
        $parametros = array(
            'id_liq' => $consecutivo,
            'cedula' => $datos_basicos['cedula'],
            'entidad' => $datos_basicos['entidad']
        );

        $existe_cc = $this->consultarCC($parametros);

        if ($existe_cc == true) {
            $conse_cc = $existe_cc[0]['cob_consecu_cta'];
        } else {
            echo "<script type=\"text/javascript\">" .
            "alert('¡Debe crear primero una Cuenta de Cobro!');" .
            "</script> ";
            error_log('\n');
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = 'pagina=liquidadorCP';
            $variable.='&opcion=';
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
            exit;
        }

        //recuperar datos de la concurrencia
        $datos_concurrencia = $this->datosConcurrencia($parametros);
        //recuperar datos del pensionado
        $datos_pensionado = $this->datosPensionado($parametros);

        //recuperar y organizar año a año liquidación detallada
        $parametros_l = array(
            'cedula' => $datos_basicos['cedula'],
            'entidad' => $datos_basicos['entidad'],
            'liq_consecutivo' => $consecutivo
        );

        $total_liquidacion = $this->consultarLiquiFija($parametros_l);

        $datos_liquidacion = array(
            'cedula' => $total_liquidacion[0]['liq_cedula'],
            'entidad' => $total_liquidacion[0]['liq_nitprev'],
            'liquidar_desde' => $total_liquidacion[0]['liq_fdesde'],
            'liquidar_hasta' => $total_liquidacion[0]['liq_fhasta']
        );

        $liquidacion = $this->liquidacion($datos_liquidacion);
        $liquidacion_anual = $this->detalleLiquidacion($liquidacion);

        //recuperar detalle de días según historia laboral

        $dias_cargo = $this->calculoDias($datos_basicos);

        //recuperar información del sustituto
        //recuperar nombre jefe recursos humanos
        $a = array();
        $jefe_recursos = $this->consultarJefeRecursos($a);


        $this->html_liquidador->reporteResumen($datos_basicos, $total_liquidacion, $conse_cc, $datos_concurrencia, $datos_pensionado, $liquidacion_anual, $dias_cargo, $jefe_recursos);
    }

    function reportesDetalle($datos_basicos, $consecutivo) {
        //definir consecutivo cuenta de cobro
        $parametros = array(
            'id_liq' => $consecutivo,
            'cedula' => $datos_basicos['cedula'],
            'entidad' => $datos_basicos['entidad']
        );

        $existe_cc = $this->consultarCC($parametros);

        if ($existe_cc == true) {
            $conse_cc = $existe_cc[0]['cob_consecu_cta'];
        } else {
            echo "<script type=\"text/javascript\">" .
            "alert('¡Debe crear primero una Cuenta de Cobro!');" .
            "</script> ";
            error_log('\n');
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = 'pagina=liquidadorCP';
            $variable.='&opcion=';
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
            exit;
        }
        //recuperar y organizar año a año liquidación detallada
        $parametros = array(
            'cedula' => $datos_basicos['cedula'],
            'entidad' => $datos_basicos['entidad'],
            'liq_consecutivo' => $consecutivo
        );

        $total_liquidacion = $this->consultarLiquiFija($parametros);

        $datos_liquidacion = array(
            'cedula' => $total_liquidacion[0]['liq_cedula'],
            'entidad' => $total_liquidacion[0]['liq_nitprev'],
            'liquidar_desde' => $total_liquidacion[0]['liq_fdesde'],
            'liquidar_hasta' => $total_liquidacion[0]['liq_fhasta']
        );

        $liquidacion = $this->liquidacion($datos_liquidacion);
        $detalle_indice = $this->detalleIndices($liquidacion);

        foreach ($liquidacion as $key => $value) {
            $fecha_cobro = $liquidacion[$key]['fecha'];
        }
        //recuperar información del sustituto
        //recuperar nombre jefe recursos humanos
        $a = array();
        $jefe_recursos = $this->consultarJefeRecursos($a);

        $this->html_liquidador->reporteDetalle($datos_basicos, $liquidacion, $total_liquidacion, $conse_cc, $detalle_indice, $fecha_cobro, $jefe_recursos);
    }

    function detalleIndices($liquidacion) {

        $indice = array();
        $indice_f = array();

        foreach ($liquidacion as $key => $values) {
            $indice['vigencia'][$key] = date('Y', strtotime(str_replace('/', '-', $liquidacion[$key]['fecha'])));
        }

        $indice_k = array_unique($indice['vigencia'], SORT_REGULAR);

        foreach ($indice_k as $key => $values) {
            $indice_c = $this->obtenerIPC($indice_k[$key]);
            $suma_c = $this->obtenerSumafija($indice_k[$key]);
            $indice_f[$key]['vigencia'] = $indice_k[$key];
            $indice_f[$key]['ipc'] = $indice_c[0][0];
            $indice_f[$key]['suma_fija'] = $suma_c[0][0];
        }
        return $indice_f;
    }

    function detalleLiquidacion($liquidacion) {

        $indice = array();

        foreach ($liquidacion as $key => $values) {
            $indice['vigencia'][$key] = date('Y', strtotime(str_replace('/', '-', $liquidacion[$key]['fecha'])));
        }

        $indice_k = array_unique($indice['vigencia'], SORT_REGULAR);
        $año_liquidacion = array();
        $mesada = 0;
        $ajuste_pen = 0;
        $mesada_adc = 0;
        $incremento = 0;
        $cuota_parte = 0;
        $interes = 0;
        $total = 0;


        foreach ($indice_k as $key => $values) {
            foreach ($liquidacion as $cont => $values) {
                $año = date('Y', strtotime(str_replace('/', '-', $liquidacion[$cont]['fecha'])));
                $año_k = $indice_k[$key];
                if ($año == $año_k) {

                    $mesada = $liquidacion[$cont]['mesada'] + $mesada;
                    $ajuste_pen = $liquidacion[$cont]['ajuste_pension'] + $ajuste_pen;
                    $mesada_adc = $liquidacion[$cont]['mesada_adc'] + $mesada_adc;
                    $incremento = $liquidacion[$cont]['incremento'] + $incremento;
                    $cuota_parte = $liquidacion[$cont]['cuota_parte'] + $cuota_parte;
                    $interes = $liquidacion[$cont]['interes'] + $interes;
                    $total = $liquidacion[$cont]['total'] + $total;

                    $año_liquidacion[$key]['vigencia'] = $año_k;
                    $año_liquidacion[$key]['mesada'] = $mesada;
                    $año_liquidacion[$key]['ajuste_pen'] = $ajuste_pen;
                    $año_liquidacion[$key]['mesada_adc'] = $mesada_adc;
                    $año_liquidacion[$key]['incremento'] = $incremento;
                    $año_liquidacion[$key]['cuota_parte'] = $cuota_parte;
                    $año_liquidacion[$key]['interes'] = $interes;
                    $año_liquidacion[$key]['total'] = $total;
                }
            }

            $mesada = 0;
            $ajuste_pen = 0;
            $mesada_adc = 0;
            $incremento = 0;
            $cuota_parte = 0;
            $interes = 0;
            $total = 0;
        }

        return $año_liquidacion;
    }

    function generar_pdfcuenta($datos_basicos, $consecutivocc, $totales_liq) {

        $parametros_x = array();
        $consecutivo = $this->consecutivoCC($parametros_x);

        $consecutivo_cc = $this->generarConsecutivo($parametros);

        $cons = intval($consecutivo[0][0]) + 1;

        $subtotal = $totales_liq[0]['liq_mesada'] + $totales_liq[0]['liq_mesada_ad'];
        $t_s_interes = $subtotal + $totales_liq[0]['liq_incremento'];

        $parametros = array(
            'id_liq' => $totales_liq[0]['liq_consecutivo'],
            'id_cuentac' => $cons,
            'fecha_generacion' => $totales_liq[0]['liq_fgenerado'],
            'cedula' => $totales_liq[0]['liq_cedula'],
            'empleador' => null,
            'previsor' => $totales_liq[0]['liq_nitprev'],
            'consecutivo_cc' => $consecutivo_cc,
            'saldo_fecha' => $totales_liq[0]['liq_total'],
            'fecha_inicial' => $totales_liq[0]['liq_fdesde'],
            'fecha_final' => $totales_liq[0]['liq_fhasta'],
            'mesada' => $totales_liq[0]['liq_mesada'],
            'mesada_adc' => $totales_liq[0]['liq_mesada_ad'],
            'subtotal' => $subtotal,
            'incremento' => $totales_liq[0]['liq_incremento'],
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
            $registro[1] = $parametros['cedula'] . '|' . $parametros['empleador'] . '|' . $parametros['previsor']; //
            $registro[2] = "CUOTAS_PARTES-CuentaCobroSistema";
            $registro[3] = $parametros['consecutivo_cc'] . '|' . $parametros['fecha_inicial'] . '|' . $parametros['fecha_final'] . '|' . $parametros['mesada']
                    . '|' . $parametros['mesada_adc'] . '|' . $parametros['subtotal'] . '|' . $parametros['incremento'] . '|' . $parametros['t_sin_interes'] . '|' . $parametros['interes']
                    . '|' . $parametros['t_con_interes'] . '|' . $parametros['saldo_fecha'] . '|' . $parametros['fecha_recibido']; //
            $registro[4] = time();
            $registro[5] = "Registra datos cuenta de cobro del sistema del pensionado con ";
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
            "alert('Esta Cuenta de Cobro ya Existe!. ERROR en el REGISTRO');" .
            "</script> ";
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = "pagina=liquidadorCP";
            $variable .= "&opcion=reporte_inicio";
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
            exit;
        }
    }

    function generarConsecutivo() {
        $parametros = array();
        $consecutivo = $this->consecutivoCC($parametros);
        $cons = $consecutivo[0]['cob_idcob'] + 1;
        $annio = date("Y");

        if ($cons <= 9) {
            $cons_cuenta = "CP-000" . $cons . "-" . $annio;
        } elseif ($cons <= 99) {
            $cons_cuenta = "CP-00" . $cons . "-" . $annio;
        } elseif ($cons <= 999) {
            $cons_cuenta = "CP-0" . $cons . "-" . $annio;
        } else {
            $cons_cuenta = "CP-" . $cons . "-" . $annio;
        }

        return $cons_cuenta;
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
            'cedula' => (isset($datos_liquidar['cedula']) ? $datos_liquidar['cedula'] : ''),
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
            $annio = date('Y', strtotime(str_replace('/', '-', $FECHAS[$key])));

            //Valor Indices Básicos
            $sumafija = $this->obtenerSumafija($annio);
            $INDICE = $this->obtenerIPC($annio);
            $MESADA = $this->MesadaFecha(($FECHAS[$key]), $mesada, $sumafija[0][0]);

            //Determinar Cuota Parte
            $CUOTAPARTE = $this->CuotaParte($MESADA, $porcentaje_cuota);

            //Valor Ajustes Adicionales
            $AJUSTEPENSIONAL = $this->AjustePensional(($FECHAS[$key]), $sumafija[0][0]);
            $MESADAADICIONAL = $this->MesadaAdicional(($FECHAS[$key]), $CUOTAPARTE);
            $INCREMENTOSALUD = $this->IncrementoSalud(($FECHAS[$key]), $CUOTAPARTE);
            //$INTERESES = $this->Intereses($FECHAS[$key], $CUOTAPARTE, $MESADAADICIONAL, $fecha_desde_liquidación);
            $INTERESES = 0;
            //Valor Total Mes liquidado
            $TOTAL = $AJUSTEPENSIONAL + $MESADAADICIONAL + $INCREMENTOSALUD + $CUOTAPARTE + $INTERESES + $MESADA;
            $TOTAL = round($TOTAL, 0);

            //**************SALIDA FINAL****************//

            $liquidacion_cp[$key]['fecha'] = $FECHAS[$key];
            $liquidacion_cp[$key]['ipc'] = $INDICE[0][0];
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

    function calculoDias($datos_basicos) {

        $historia_laboral = $this->consultarDatosHistoriaTotal($datos_basicos);

        foreach ($historia_laboral as $key => $values) {
            $desde = strtotime(str_replace('/', '-', $historia_laboral[$key]['hlab_fingreso']));
            $hasta = strtotime(str_replace('/', '-', $historia_laboral[$key]['hlab_fretiro']));
            $dias_entre[$key]['dias'] = floor(abs(($desde - $hasta) / 86400));
            $dias_entre[$key]['entidad'] = $historia_laboral[$key]['hlab_nitprev'];
        }

        foreach ($dias_entre as $key => $values) {
            $array[$dias_entre[$key]['entidad']][$key] = $dias_entre[$key]['dias'];
        }

        $diasTotales = 0;
        foreach ($array as $k => $i) {
            $diasEntidad = 0;
            foreach ($array[$k] as $r) {
                //acumulados
                $diasEntidad += $r;
                $diasTotales +=$r;
            }
            $array[$k]['total_dia'] = $diasEntidad;
        }

        $array['Total'] = $diasTotales;
        return $array;
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
            'cedula' => $datos_liquidar['cedula'],
            'nombre_emp' => $nombre_empleado[0]['NOMBRE'],
            'entidad_nombre' => $nombre_entidad[0]['prev_nombre'],
            'entidad' => $datos_liquidar['entidad'],
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

        $consecutivo_real = intval($consecutivo[0][0]) + 1;

        //Guardar datos Liquidación
        $parametros = array(
            'liq_consecutivo' => (isset($consecutivo_real) ? $consecutivo_real : ''),
            'liq_fgenerado' => date('Y-m-d'),
            'liq_cedula' => (isset($datos_basicos['cedula']) ? $datos_basicos['cedula'] : ''),
            'liq_nitprev' => (isset($datos_basicos['entidad']) ? $datos_basicos['entidad'] : ''),
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
            "alert('Gestor de Reportes');" .
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

    /* ! 
      @function num2letras ()
      @abstract Dado un n?mero lo devuelve escrito.
      @param $num number - N?mero a convertir.
      @param $fem bool - Forma femenina (true) o no (false).
      @param $dec bool - Con decimales (true) o no (false).
      @result string - Devuelve el n?mero escrito en letra.

     */

    function num2letras($num, $fem = false, $dec = true) {
        $matuni[2] = "dos";
        $matuni[3] = "tres";
        $matuni[4] = "cuatro";
        $matuni[5] = "cinco";
        $matuni[6] = "seis";
        $matuni[7] = "siete";
        $matuni[8] = "ocho";
        $matuni[9] = "nueve";
        $matuni[10] = "diez";
        $matuni[11] = "once";
        $matuni[12] = "doce";
        $matuni[13] = "trece";
        $matuni[14] = "catorce";
        $matuni[15] = "quince";
        $matuni[16] = "dieciseis";
        $matuni[17] = "diecisiete";
        $matuni[18] = "dieciocho";
        $matuni[19] = "diecinueve";
        $matuni[20] = "veinte";
        $matunisub[2] = "dos";
        $matunisub[3] = "tres";
        $matunisub[4] = "cuatro";
        $matunisub[5] = "quin";
        $matunisub[6] = "seis";
        $matunisub[7] = "sete";
        $matunisub[8] = "ocho";
        $matunisub[9] = "nove";

        $matdec[2] = "veint";
        $matdec[3] = "treinta";
        $matdec[4] = "cuarenta";
        $matdec[5] = "cincuenta";
        $matdec[6] = "sesenta";
        $matdec[7] = "setenta";
        $matdec[8] = "ochenta";
        $matdec[9] = "noventa";
        $matsub[3] = 'mill';
        $matsub[5] = 'bill';
        $matsub[7] = 'mill';
        $matsub[9] = 'trill';
        $matsub[11] = 'mill';
        $matsub[13] = 'bill';
        $matsub[15] = 'mill';
        $matmil[4] = 'millones';
        $matmil[6] = 'billones';
        $matmil[7] = 'de billones';
        $matmil[8] = 'millones de billones';
        $matmil[10] = 'trillones';
        $matmil[11] = 'de trillones';
        $matmil[12] = 'millones de trillones';
        $matmil[13] = 'de trillones';
        $matmil[14] = 'billones de trillones';
        $matmil[15] = 'de billones de trillones';
        $matmil[16] = 'millones de billones de trillones';

        //Zi hack
        $float = explode('.', $num);
        $num = $float[0];

        $num = trim((string) @$num);
        if ($num[0] == '-') {
            $neg = 'menos ';
            $num = substr($num, 1);
        } else {
            $neg = '';
        }
        while ($num[0] == '0') {
            $num = substr($num, 1);
        }
        if ($num[0] < '1' or $num[0] > 9) {
            $num = '0' . $num;
        }
        $zeros = true;
        $punt = false;
        $ent = '';
        $fra = '';
        for ($c = 0; $c < strlen($num); $c++) {
            $n = $num[$c];
            if (!(strpos(".,'''", $n) === false)) {
                if ($punt) {
                    break;
                } else {
                    $punt = true;
                    continue;
                }
            } elseif (!(strpos('0123456789', $n) === false)) {
                if ($punt) {
                    if ($n != '0') {
                        $zeros = false;
                    }
                    $fra .= $n;
                } else {
                    $ent .= $n;
                }
            } else {
                break;
            }
        }
        $ent = '     ' . $ent;
        if ($dec and $fra and !$zeros) {
            $fin = ' coma';
            for ($n = 0; $n < strlen($fra); $n++) {
                if (($s = $fra[$n]) == '0') {
                    $fin .= ' cero';
                } elseif ($s == '1') {
                    $fin .= $fem ? ' una' : ' un';
                } else {
                    $fin .= ' ' . $matuni[$s];
                }
            }
        } else {
            $fin = '';
        }
        if ((int) $ent === 0) {
            return 'Cero ' . $fin;
        }
        $tex = '';
        $sub = 0;
        $mils = 0;
        $neutro = false;
        while (($num = substr($ent, -3)) != '   ') {
            $ent = substr($ent, 0, -3);
            if (++$sub < 3 and $fem) {
                $matuni[1] = 'una';
                $subcent = 'as';
            } else {
                $matuni[1] = $neutro ? 'un' : 'uno';
                $subcent = 'os';
            }
            $t = '';
            $n2 = substr($num, 1);
            if ($n2 == '00') {
                
            } elseif ($n2 < 21) {
                $t = ' ' . $matuni[(int) $n2];
            } elseif ($n2 < 30) {
                $n3 = $num[2];
                if ($n3 != 0) {
                    $t = 'i' . $matuni[$n3];
                }
                $n2 = $num[1];
                $t = ' ' . $matdec[$n2] . $t;
            } else {
                $n3 = $num[2];
                if ($n3 != 0) {
                    $t = ' y ' . $matuni[$n3];
                }
                $n2 = $num[1];
                $t = ' ' . $matdec[$n2] . $t;
            }
            $n = $num[0];
            if ($n == 1) {
                $t = ' ciento' . $t;
            } elseif ($n == 5) {
                $t = ' ' . $matunisub[$n] . 'ient' . $subcent . $t;
            } elseif ($n != 0) {
                $t = ' ' . $matunisub[$n] . 'cient' . $subcent . $t;
            }
            if ($sub == 1) {
                
            } elseif (!isset($matsub[$sub])) {
                if ($num == 1) {
                    $t = ' mil';
                } elseif ($num > 1) {
                    $t .= ' mil';
                }
            } elseif ($num == 1) {
                $t .= ' ' . $matsub[$sub] . '?n';
            } elseif ($num > 1) {
                $t .= ' ' . $matsub[$sub] . 'ones';
            }
            if ($num == '000')
                $mils ++;
            elseif ($mils != 0) {
                if (isset($matmil[$sub]))
                    $t .= ' ' . $matmil[$sub];
                $mils = 0;
            }
            $neutro = true;
            $tex = $t . $tex;
        }
        $tex = $neg . substr($tex, 1) . $fin;
        //Zi hack --> return ucfirst($tex);
        $end_num = ucfirst($tex) . ' PESOS M/CTE';
        return $end_num;
    }

}

// fin de la clase
?>