
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
  | 04/08/2014 | Violet Sosa             | 0.0.0.4     |                                 |
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

date_default_timezone_set('America/Bogota');

class funciones_formHistoria extends funcionGeneral {

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

        //Conexión a Oracle
        $this->acceso_oracle = $this->conectarDB($configuracion, "cuotasP");

        $this->usuario = $this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion = $this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");

        $this->configuracion = $configuracion;

        $this->html_formHistoria = new html_formHistoria($configuracion);
    }

    function actualizarHLaboral($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "actualizarHistoria", $parametros);
        $datos_Hlaboral = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "registrar");
        return $datos_Hlaboral;
    }

    function actualizarNitInterrupcion($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "actualizarNitInterrupcion", $parametros);
        $datos_Hlaboral = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "registrar");
        return $datos_Hlaboral;
    }

    function consultarPrevisora() {
        $parametros = array();
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "consultarPrevisora", $parametros);
        $datos_previsora = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos_previsora;
    }

    function consultarHistoria($parametro) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "consultarHistoria", $parametro);
        $datos_historia = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos_historia;
    }

    function consultarConsecutivo($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "consultarConsecutivo", $parametros);
        $datos_historia = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos_historia;
    }

    function consultarConsecutivoI($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "consultarConsecutivoI", $parametros);
        $datos_historia = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos_historia;
    }

    function consultarSustituto($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "consultarSustituto", $parametros);
        $datos_historia = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos_historia;
    }

    function consultarDatoBasicoPen($parametro) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_oracle, "consultarDatosBasicos", $parametro);
        $datos_basicos = $this->ejecutarSQL($this->configuracion, $this->acceso_oracle, $cadena_sql, "busqueda");
        return $datos_basicos;
    }

    function inactivarInterrupcion($parametros) {
        echo $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "inactivarInterrupcion", $parametros);
        $datos_historia = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "registrar");
        return $datos_historia;
       
    }

    function registrarHLaboral($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "insertarHistoria", $parametros);
        $datos_Hlaboral = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "registrar");
        return $datos_Hlaboral;
    }

    function registrarEntidad($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "insertarEntidad", $parametros);
        $datos_Entidad = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "registrar");
        return $datos_Entidad;
    }

    function reporteHistoria($parametro) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "reporteHistoria", $parametro);
        $datos_historia = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos_historia;
    }

    function reporteHistoriaEmpleador($parametro) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "reporteHistoria2", $parametro);
        $datos_historia = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos_historia;
    }

    function reporteInterrupcionEntidad($parametro) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "reporteInterrupcionEntidad", $parametro);
        $datos_interrupcion = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos_interrupcion;
    }

    function reporteInterrupcionEntidad_Mod($parametro) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "reporteInterrupcionEntidad_Modificar", $parametro);
        $datos_interrupcion = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos_interrupcion;
    }

    function reporteInterrupcion($parametro) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "reporteInterrupcion", $parametro);
        $datos_interrupcion = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos_interrupcion;
    }

    function reporteDescripcion($parametro) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "reporteDescripcion", $parametro);
        $datos_descripcion = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos_descripcion;
    }

    function dbasicoHistoria() {
        $this->html_formHistoria->datoBasico();
    }

    function dbasicoHistoriaR() {
        $this->html_formHistoria->datoBasicoR();
    }

    function mostrarFormulario($cedula) {

        $datos_previsor = $this->consultarPrevisora();
        $datos_historia = $this->consultarHistoria($cedula);

        /* Para determinar los limites del registro de la historia laboral */

        if ($datos_historia == true) {
            foreach ($datos_historia as $key => $value) {
                $rango[$key] = array(
                    'inicio' => date('d/m/Y', strtotime($value['hlab_fingreso'])),
                    'fin' => date('d/m/Y', strtotime($value['hlab_fretiro'])));
            }
        } else {
            $rango[0] = array(
                'inicio' => date('d/m/Y', strtotime('01/01/1900')),
                'fin' => date('d/m/Y', strtotime('01/01/1901')));
        }

        //array_multisort($fin, SORT_DESC, $inicio, SORT_DESC, $datos_historia);

        $this->html_formHistoria->formularioHistoria($datos_previsor, $datos_historia, $cedula, $rango);
    }

    function mostrarHistoria($cedula) {

        $parametro = $cedula;
        $consulta_sustituto = $this->consultarSustituto($parametro);
        $consulta_historia = $this->reporteHistoria($parametro);
        $consulta_historia2 = $this->reporteHistoriaEmpleador($parametro);
        $consulta_interrupcion = $this->reporteInterrupcion($parametro);
        $consulta_descripcion = $this->reporteDescripcion($parametro);
        $consulta_basicopen = $this->consultarDatoBasicoPen($parametro);


        if (is_array($consulta_basicopen)) {
            $consulta_basico = $consulta_basicopen;
        } else {
            $consulta_basico[0] = array(
                0 => $parametro,
                1 => 'No registra en la base de datos.',
            );
        }

        if (is_array($consulta_historia)) {
            $this->html_formHistoria->datosReporte($consulta_historia, $consulta_historia2, $consulta_interrupcion, $consulta_descripcion, $consulta_basico, $consulta_sustituto);
        } else {
            echo "<script type=\"text/javascript\">" .
            "alert('No existen historias laborales registradas para la cédula " . $parametro . ". Por favor, diligencie el Formulario de Registro de Historia Laboral');" .
            "</script> ";
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = 'pagina=formHistoria';
            $variable.='&opcion=';
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
            exit;
        }
    }

    function nuevaInterrupcion($datos_interrupcion) {

        $cedula = $datos_interrupcion['cedula'];

        $parametro = array(
            'cedula' => $datos_interrupcion['cedula'],
            'empleador' => $datos_interrupcion['nit_entidad'],
            'previsora' => $datos_interrupcion['nit_previsora'],
        );

        $datos_previsor = $this->consultarPrevisora();
        $datos_historia = $this->consultarHistoria($cedula);
        $datos_regint = $this->reporteInterrupcionEntidad($parametro);

        $this->html_formHistoria->formularioInterrupcion($datos_previsor, $datos_interrupcion, $datos_historia, $datos_regint);
    }

    function modificarInterrupcion($datos_interrupcion) {

        $cedula = $datos_interrupcion['int_nro_identificacion'];

        $parametro = array(
            'cedula' => $datos_interrupcion['int_nro_identificacion'],
            'empleador' => $datos_interrupcion['int_nitent'],
            'previsora' => $datos_interrupcion['int_nitprev'],
            'h_ingreso' => $datos_interrupcion['int_nro_ingreso']
        );

        $datos_previsor = $this->consultarPrevisora();
        $datos_historia = $this->consultarHistoria($cedula);
        $datos_int_historia = $this->reporteInterrupcionEntidad_Mod($parametro);
        $datos_regint = $this->reporteInterrupcionEntidad($parametro);

        $this->html_formHistoria->formularioInterrupcion_Modificar($datos_previsor, $datos_interrupcion, $datos_historia, $datos_regint, $datos_int_historia);
    }

    function modificarHistoria($historia_laboral) {

        $cedula = $historia_laboral['hlab_nro_identificacion'];
        $datos_previsor = $this->consultarPrevisora();
        $datos_historia = $this->consultarHistoria($cedula);

        /* Para determinar los limites del registro de la historia laboral */

        if ($datos_historia == true) {
            foreach ($datos_historia as $key => $value) {
                $rango[$key] = array(
                    'inicio' => date('d/m/Y', strtotime($value['hlab_fingreso'])),
                    'fin' => date('d/m/Y', strtotime($value['hlab_fretiro'])));
            }
        } else {
            $rango[0] = array(
                'inicio' => date('d/m/Y', strtotime('01/01/1900')),
                'fin' => date('d/m/Y', strtotime('01/01/1901')));
        }

        $this->html_formHistoria->modificarHistoria($datos_previsor, $historia_laboral, $rango);
    }

    function procesarFormulario($datos) {

        /* foreach ($datos as $key => $value) {
          if ($datos[$key] == "") {
          echo "<script type=\"text/javascript\">" .
          "alert('Formulario NO diligenciado correctamente');" .
          "</script> ";
          $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
          $variable = 'pagina=formHistoria';
          $variable.='&opcion=';
          $variable = $this->cripto->codificar_url($variable, $this->configuracion);
          echo "<script>location.replace('" . $pagina . $variable . "')</script>";
          exit;
          }
          }
         */
        $parametros = array(
            'cedula' => (isset($datos['cedula_emp']) ? $datos['cedula_emp'] : ''),
            'nit_entidad' => (isset($datos['empleador_nit']) ? $datos['empleador_nit'] : ''),
            'nit_previsora' => (isset($datos['prev_nit']) ? $datos['prev_nit'] : ''),
        );

        $consecutivo_acumulado = $this->consultarConsecutivo($parametros);

        if ($consecutivo_acumulado == true) {
            $consecutivo = $consecutivo_acumulado[0]['hlab_nro_ingreso'];
        } else {
            $consecutivo = 0;
        }

        $consecutivo_def = $consecutivo + 1;
        $estado = 1;
        $fecha_registro = date('d/m/Y');


        $fecha_max = date('d/m/Y', strtotime(str_replace('/', '-', $datos['fecha_salida'])));

        $antes = strtotime(str_replace('/', '-', $datos['fecha_ingreso']));
        $despues = strtotime(str_replace('/', '-', $datos['fecha_salida']));

        $dias_transcurridos = abs(($despues - $antes) / 86400);

        /*
          $certificado = strtotime(str_replace('/', '-', $datos['fecha_certificado']));
          if ($certificado < $despues) {
          echo "<script type=\"text/javascript\">" .
          "alert('La fecha del certificado no corresponde con el periodo laborado.');" .
          "</script> ";
          $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
          $variable = 'pagina=formHistoria';
          $variable.='&opcion=';
          $variable = $this->cripto->codificar_url($variable, $this->configuracion);
          echo "<script>location.replace('" . $pagina . $variable . "')</script>";
          exit;
          } */

        /*
          if ($dias_transcurridos < ) {
          echo "<script type=\"text/javascript\">" .
          "alert('El número de días laborados es menor a 30 días.');" .
          "</script> ";
          $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
          $variable = 'pagina=formHistoria';
          $variable.='&opcion=';
          $variable = $this->cripto->codificar_url($variable, $this->configuracion);
          echo "<script>location.replace('" . $pagina . $variable . "')</script>";
          exit;
          }

          /*  foreach ($datos as $key => $value) {
          if (!preg_match("#^[a-zA-Z0-9/.-\s]{1,80}$#", $datos[$key])) {
          echo "<script type=\"text/javascript\">" .
          "alert('Formulario NO diligenciado correctamente');" .
          "</script> ";
          $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
          $variable = 'pagina=formHistoria';
          $variable.='&opcion=';
          $variable = $this->cripto->codificar_url($variable, $this->configuracion);
          echo "<script>location.replace('" . $pagina . $variable . "')</script>";
          exit;
          }
          }

          if (!preg_match("/([0-9]{2})\/([0-9]{2})\/([0-9]{4})/", $datos['fecha_ingreso'])) {
          echo "<script type=\"text/javascript\">" .
          "alert('Formato fecha ingreso diligenciado incorrectamente');" .
          "</script> ";
          $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
          $variable = 'pagina=formHistoria';
          $variable.='&opcion=';
          $variable = $this->cripto->codificar_url($variable, $this->configuracion);
          echo "<script>location.replace(' " . $pagina . $variable . "')</script>";
          exit;
          }

          if (!preg_match("/([0-9]{2})\/([0-9]{2})\/([0-9]{4})/", $datos['fecha_salida'])) {
          echo "<script type=\"text/javascript\">" .
          "alert('Formato fecha salida diligenciado incorrectamente');" .
          "</script> ";
          $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
          $variable = 'pagina=formHistoria';
          $variable.='&opcion=';
          $variable = $this->cripto->codificar_url($variable, $this->configuracion);
          echo "<script>location.replace(' " . $pagina . $variable . "')</script>";
          exit;
          }
         */
        if (!preg_match("([0-9]{1,3})", $datos['horas_laboradas'])) {
            echo "<script type=\"text/javascript\">" .
            "alert('Formato ingreso no diligenciado correctamente');" .
            "</script> ";
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = 'pagina=formHistoria';
            $variable.='&opcion=';
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace(' " . $pagina . $variable . "')</script>";
            exit;
        }

        if ($antes > $despues) {
            echo "<script type=\"text/javascript\">" .
            "alert('Fecha de Salida no coincide con Fecha de Ingreso');" .
            "</script> ";
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = 'pagina=formHistoria';
            $variable.='&opcion=';
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace(' " . $pagina . $variable . "')</script>";
            exit;
        }
        /*
          $fecha_registro2 = strtotime(date('d/m/Y'));
          $fecha_max2 = strtotime(str_replace('/', '-', $datos['fecha_salida']));

          if ($fecha_max2 > $fecha_registro2) {
          echo "<script type=\"text/javascript\">" .
          "alert('El intervalo de fechas no es válido');" .
          "</script> ";
          error_log('\n');
          $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
          $variable = 'pagina=formHistoria';
          $variable.='&opcion=';
          $variable = $this->cripto->codificar_url($variable, $this->configuracion);
          echo "<script>location.replace('" . $pagina . $variable . "')</script>";
          exit;
          }

          $historia = $this->consultarHistoria($datos['cedula_emp']);

          if ($datos['prev_nit'] == '0' || $datos['prev_nit'] == null) {
          $datos['prev_nit'] = $datos['empleador_nit'];
          }


          //VALIDACIÓN DE TRASLAPE DE FECHAS
          $datos_historia = $this->consultarHistoria($datos['cedula_emp']);

          /* Para determinar los limites del registro de la historia laboral

          if (is_array($datos_historia)) {
          if ($datos_historia == true) {
          foreach ($datos_historia as $key => $value) {
          $rango[$key] = array(
          'inicio' => date('d/m/Y', strtotime($value['hlab_fingreso'])),
          'fin' => date('d/m/Y', strtotime($value['hlab_fretiro'])));
          }
          } else {
          $rango[$key] = array(
          'inicio' => date('d/m/Y', strtotime('01/01/1900')),
          'fin' => date('d/m/Y', strtotime('01/01/1901')));
          }

          foreach ($rango as $key => $values) {

          $inicio = strtotime(str_replace('/', '-', $rango[$key]['inicio']));
          $fin = strtotime(str_replace('/', '-', $rango[$key]['fin']));

          if ($antes >= $inicio && $antes <= $fin) {
          echo "<script type=\"text/javascript\">" .
          "alert('El intervalo de fechas de tiempo laborado no es válido');" .
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
          "alert('El intervalo de fechas de tiempo laborado no es válido');" .
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

          /
          /* Terminan las validaciones de los datos */

        $parametros_hlaboral = array(
            'nro_ingreso' => $consecutivo_def,
            'cedula' => (isset($datos['cedula_emp']) ? $datos['cedula_emp'] : ''),
            'nit_entidad' => (isset($datos['empleador_nit']) ? $datos['empleador_nit'] : ''),
            'nit_previsora' => (isset($datos['prev_nit']) ? $datos['prev_nit'] : ''),
            'fecha_ingreso' => (isset($datos['fecha_ingreso']) ? $datos['fecha_ingreso'] : ''),
            'fecha_salida' => (isset($datos['fecha_salida']) ? $datos['fecha_salida'] : ''),
            'horas_labor' => (isset($datos['horas_laboradas']) ? $datos['horas_laboradas'] : ''),
            'periodo_labor' => (isset($datos['tipo_horas']) ? $datos['tipo_horas'] : ''),
            'num_certificado' => (isset($datos['num_certificado']) ? $datos['num_certificado'] : ''),
            'fecha_certificado' => (isset($datos['fecha_certificado']) ? $datos['fecha_certificado'] : NULL),
            'estado' => $estado,
            'registro' => $fecha_registro);

        if ($datos['interrupcion']) {

            $dias_transcurridos = abs(($despues - $antes) / 86400);

            if ($dias_transcurridos < 10) {
                echo "<script type=\"text/javascript\">" .
                "alert('Intervalo de trabajo muy corto para registrar una interrupción! Historia Laboral Registrada');" .
                "</script> ";
                $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                $variable = 'pagina=formHistoria';
                $variable.='&opcion=';
                $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                echo "<script>location.replace(' " . $pagina . $variable . "')</script>";
                exit;
            }

            echo "<script type=\"text/javascript\">" .
            "alert('Ingresando a Formulario Registro de Interrupción');" .
            "</script> ";

            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = "pagina=formHistoria";
            $variable.="&opcion=interrupcion";
            $variable.="&h_nro_ingreso=" . $consecutivo_def;
            $variable.="&cedula=" . $datos['cedula_emp'];
            $variable.="&nit_entidad=" . $datos['empleador_nit'];
            $variable.="&nit_previsora=" . $datos['prev_nit'];
            $variable.="&h_fecha_ingreso=" . $datos['fecha_ingreso'];
            $variable.="&h_fecha_salida=" . $datos['fecha_salida'];
            $variable.="&h_horas_labor=" . $datos['horas_laboradas'];
            $variable.="&h_periodo_labor=" . $datos['tipo_horas'];
            $variable.="&num_certificado=" . (isset($datos['num_certificado']) ? $datos['num_certificado'] : '');
            $variable.="&fecha_certificado=" . (isset($datos['fecha_certificado']) ? $datos['fecha_certificado'] : '');
            $variable.="&h_estado=" . $estado;
            $variable.="&h_registro=" . $fecha_registro;
            $variable.="&h_tipo=registrar";
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "' ) </script>";
            exit;
        } else {

            $registro_hlaboral = $this->registrarHLaboral($parametros_hlaboral);

            if ($registro_hlaboral == true) {
                $registroL[0] = "GUARDAR";
                $registroL[1] = $parametros_hlaboral['cedula'] . '|' . $parametros_hlaboral['nro_ingreso'] . '|' . $parametros_hlaboral['nit_entidad']; //
                $registroL[2] = "CUOTAS_PARTES";
                $registroL[3] = $parametros_hlaboral['nit_previsora'] . '|' . $parametros_hlaboral['fecha_ingreso'] . '|' . $parametros_hlaboral['fecha_salida']; //
                $registroL[4] = time();
                $registroL[5] = "Registra datos de la historia laboral del pensionado con ";
                $registroL[5] .= " identificacion =" . $parametros_hlaboral['cedula'];
                $this->log_us->log_usuario($registroL, $this->configuracion);

                echo "<script type = \"text/javascript\">" .
                "alert('Datos Registrados');" .
                "</script> ";

                $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                $variable = "pagina=formHistoria";
                $variable .= "&opcion=";
                $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                exit;
            } else {
                echo "<script type=\"text/javascript\">" .
                "alert('Datos de Historia Laboral NO Registrados Correctamente. ERROR en el REGISTRO');" .
                "</script> ";

                $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                $variable = "pagina=formHistoria";
                $variable .= "&opcion=";
                $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                exit;
            }
        }
    }

    function actualizarFormulario($datos_nuevos, $datos_antiguos) {

        /* foreach ($datos_nuevos as $key => $value) {
          if ($datos_nuevos[$key] == "") {
          echo "<script type=\"text/javascript\">" .
          "alert('Formulario NO diligenciado correctamente');" .
          "</script> ";
          $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
          $variable = 'pagina=formHistoria';
          $variable.='&opcion=';
          $variable = $this->cripto->codificar_url($variable, $this->configuracion);
          echo "<script>location.replace('" . $pagina . $variable . "')</script>";
          exit;
          }
          }
         */
        $parametros = array(
            'cedula' => (isset($datos_nuevos['cedula_emp']) ? $datos_nuevos['cedula_emp'] : ''),
            'nit_entidad' => (isset($datos_nuevos['empleador_nit']) ? $datos_nuevos['empleador_nit'] : ''),
            'nit_previsora' => (isset($datos_nuevos['prev_nit']) ? $datos_nuevos['prev_nit'] : ''),
        );


        $consecutivo_def = $datos_antiguos["hlab_nro_ingreso"];
        $estado = 1;
        $fecha_registro = date('d/m/Y');

        $fecha_max = date('d/m/Y', strtotime(str_replace('/', '-', $datos_nuevos['fecha_salida'])));

        $antes = strtotime(str_replace('/', '-', $datos_nuevos['fecha_ingreso']));
        $despues = strtotime(str_replace('/', '-', $datos_nuevos['fecha_salida']));

        $dias_transcurridos = abs(($despues - $antes) / 86400);

        $certificado = strtotime(str_replace('/', '-', $datos_nuevos['fecha_certificado']));


        if ($certificado < $despues) {
            echo "<script type=\"text/javascript\">" .
            "alert('La fecha del certificado no corresponde con el periodo de historia.');" .
            "</script> ";
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = 'pagina=formHistoria';
            $variable.='&opcion=';
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
            exit;
        }

        /*
          if ($dias_transcurridos < 30) {
          echo "<script type=\"text/javascript\">" .
          "alert('El número de días laborados es menor a 30 días.');" .
          "</script> ";
          $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
          $variable = 'pagina=formHistoria';
          $variable.='&opcion=';
          $variable = $this->cripto->codificar_url($variable, $this->configuracion);
          echo "<script>location.replace('" . $pagina . $variable . "')</script>";
          exit;
          }

          /*  foreach ($datos_nuevos as $key => $value) {
          if (!preg_match("#^[a-zA-Z0-9/.-\s]{1,80}$#", $datos_nuevos[$key])) {
          echo "<script type=\"text/javascript\">" .
          "alert('Formulario NO diligenciado correctamente');" .
          "</script> ";
          $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
          $variable = 'pagina=formHistoria';
          $variable.='&opcion=';
          $variable = $this->cripto->codificar_url($variable, $this->configuracion);
          echo "<script>location.replace('" . $pagina . $variable . "')</script>";
          exit;
          }
          }

          if (!preg_match("/([0-9]{2})\/([0-9]{2})\/([0-9]{4})/", $datos_nuevos['fecha_ingreso'])) {
          echo "<script type=\"text/javascript\">" .
          "alert('Formato fecha ingreso diligenciado incorrectamente');" .
          "</script> ";
          $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
          $variable = 'pagina=formHistoria';
          $variable.='&opcion=';
          $variable = $this->cripto->codificar_url($variable, $this->configuracion);
          echo "<script>location.replace(' " . $pagina . $variable . "')</script>";
          exit;
          }

          if (!preg_match("/([0-9]{2})\/([0-9]{2})\/([0-9]{4})/", $datos_nuevos['fecha_salida'])) {
          echo "<script type=\"text/javascript\">" .
          "alert('Formato fecha salida diligenciado incorrectamente');" .
          "</script> ";
          $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
          $variable = 'pagina=formHistoria';
          $variable.='&opcion=';
          $variable = $this->cripto->codificar_url($variable, $this->configuracion);
          echo "<script>location.replace(' " . $pagina . $variable . "')</script>";
          exit;
          }
         */
        if (!preg_match("([0-9]{1,3})", $datos_nuevos['horas_laboradas'])) {
            echo "<script type=\"text/javascript\">" .
            "alert('Formato ingreso no diligenciado correctamente');" .
            "</script> ";
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = 'pagina=formHistoria';
            $variable.='&opcion=';
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace(' " . $pagina . $variable . "')</script>";
            exit;
        }

        if ($antes > $despues) {
            echo "<script type=\"text/javascript\">" .
            "alert('Fecha de Salida no coincide con Fecha de Ingreso');" .
            "</script> ";
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = 'pagina=formHistoria';
            $variable.='&opcion=';
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace(' " . $pagina . $variable . "')</script>";
            exit;
        }
        /*
          $fecha_registro2 = strtotime(date('d/m/Y'));
          $fecha_max2 = strtotime(str_replace('/', '-', $datos_nuevos['fecha_salida']));

          if ($fecha_max2 > $fecha_registro2) {
          echo "<script type=\"text/javascript\">" .
          "alert('El intervalo de fechas no es válido');" .
          "</script> ";
          error_log('\n');
          $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
          $variable = 'pagina=formHistoria';
          $variable.='&opcion=';
          $variable = $this->cripto->codificar_url($variable, $this->configuracion);
          echo "<script>location.replace('" . $pagina . $variable . "')</script>";
          exit;
          }

          $historia = $this->consultarHistoria($datos_nuevos['cedula_emp']);

          if ($datos_nuevos['prev_nit'] == '0' || $datos_nuevos['prev_nit'] == null) {
          $datos_nuevos['prev_nit'] = $datos_nuevos['empleador_nit'];
          }


          //VALIDACIÓN DE TRASLAPE DE FECHAS
          $datos_nuevos_historia = $this->consultarHistoria($datos_nuevos['cedula_emp']);

          /* Para determinar los limites del registro de la historia laboral

          if (is_array($datos_nuevos_historia)) {
          if ($datos_nuevos_historia == true) {
          foreach ($datos_nuevos_historia as $key => $value) {
          $rango[$key] = array(
          'inicio' => date('d/m/Y', strtotime($value['hlab_fingreso'])),
          'fin' => date('d/m/Y', strtotime($value['hlab_fretiro'])));
          }
          } else {
          $rango[$key] = array(
          'inicio' => date('d/m/Y', strtotime('01/01/1900')),
          'fin' => date('d/m/Y', strtotime('01/01/1901')));
          }

          foreach ($rango as $key => $values) {

          $inicio = strtotime(str_replace('/', '-', $rango[$key]['inicio']));
          $fin = strtotime(str_replace('/', '-', $rango[$key]['fin']));

          if ($antes >= $inicio && $antes <= $fin) {
          echo "<script type=\"text/javascript\">" .
          "alert('El intervalo de fechas de tiempo laborado no es válido');" .
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
          "alert('El intervalo de fechas de tiempo laborado no es válido');" .
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
         * 
         * 
         * 
          //VALIDAR SI LOS DATOS DE LA NUEVA HISTORIA POSEE INTERRUPCIONES ANTIGUAS Y QUÉ SE QUIERE HACER CON ESTOS
         * */

        $parametros_hlaboral = array(
            'serial' => (isset($datos_antiguos['hlab_serial']) ? $datos_antiguos['hlab_serial'] : ''),
            'nro_ingreso' => $consecutivo_def,
            'cedula' => (isset($datos_nuevos['cedula_emp']) ? $datos_nuevos['cedula_emp'] : ''),
            'nit_entidad' => (isset($datos_nuevos['empleador_nit']) ? $datos_nuevos['empleador_nit'] : ''),
            'nit_previsora' => (isset($datos_nuevos['prev_nit']) ? $datos_nuevos['prev_nit'] : ''),
            'fecha_ingreso' => (isset($datos_nuevos['fecha_ingreso']) ? $datos_nuevos['fecha_ingreso'] : ''),
            'fecha_salida' => (isset($datos_nuevos['fecha_salida']) ? $datos_nuevos['fecha_salida'] : ''),
            'horas_labor' => (isset($datos_nuevos['horas_laboradas']) ? $datos_nuevos['horas_laboradas'] : ''),
            'periodo_labor' => (isset($datos_nuevos['tipo_horas']) ? $datos_nuevos['tipo_horas'] : ''),
            'num_certificado' => (isset($datos_nuevos['num_certificado']) ? $datos_nuevos['num_certificado'] : ''),
            'fecha_certificado' => (isset($datos_nuevos['fecha_certificado']) ? $datos_nuevos['fecha_certificado'] : null),
            'estado' => $estado,
            'registro' => $fecha_registro);

        //CONSULTAR INTERRUPCIONES ASOCIADAS A LA HISTORIA

        $parametro = $datos_antiguos['hlab_nro_identificacion'];
        $datos_regint = $this->reporteInterrupcion($parametro);


        if (is_array($datos_regint)) {
            if ($datos_regint == true) {
                foreach ($datos_regint as $key => $value) {
                    $rango[$key] = array(
                        'inicio' => date('d/m/Y', strtotime($datos_regint[$key]['int_fdesde'])),
                        'fin' => date('d/m/Y', strtotime($datos_regint[$key]['int_fhasta'])),
                        'nro_ingreso' => $datos_regint[$key]['int_nro_ingreso']);
                }
            } else {
                $rango[$key] = array(
                    'inicio' => date('d/m/Y', strtotime('01/01/1940')),
                    'fin' => date('d/m/Y', strtotime('01/02/1940')),
                    'nro_ingreso' => '0');
            }

            foreach ($rango as $key => $values) {

                $parametros_int = array(
                    'nro_ingreso' => $rango[$key]['nro_ingreso'],
                    'cedula' => (isset($datos_nuevos['cedula_emp']) ? $datos_nuevos['cedula_emp'] : ''),
                    'nit_entidad' => (isset($datos_nuevos['empleador_nit']) ? $datos_nuevos['empleador_nit'] : ''),
                    'nit_previsora' => (isset($datos_nuevos['prev_nit']) ? $datos_nuevos['prev_nit'] : ''),
                    'registro' => $fecha_registro
                );

                $inicio = strtotime(str_replace('/', '-', $rango[$key]['inicio']));
                $fin = strtotime(str_replace('/', '-', $rango[$key]['fin']));

                if ($antes >= $inicio) {
                    echo "<script type=\"text/javascript\">" .
                    "alert('El intervalo de historia laboral no corresponde con las interrupciones registradas previamente. Éstas se eliminarán "
                    . " automáticamente.');" .
                    "</script> ";

                    $inactivar_interrupcion = $this->inactivarInterrupcion($parametros_int);
                }

                if ($despues <= $fin) {
                    echo "<script type=\"text/javascript\">" .
                    "alert('El intervalo de historia laboral no corresponde con las interrupciones registradas previamente. Éstas se eliminarán "
                    . " automáticamente');" .
                    "</script> ";
                    $inactivar_interrupcion = $this->inactivarInterrupcion($parametros_int);
                }
            }
        }

        /* Terminan las validaciones de los datos_nuevos */

        if (isset($datos_nuevos['interrupcion'])) {

            $dias_transcurridos = abs(($despues - $antes) / 86400);

            if ($dias_transcurridos < 10) {
                echo "<script type=\"text/javascript\">" .
                "alert('Intervalo de trabajo muy corto para registrar una interrupción! ');" .
                "</script> ";
                $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                $variable = 'pagina=formHistoria';
                $variable.='&opcion=';
                $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                echo "<script>location.replace(' " . $pagina . $variable . "')</script>";
                exit;
            }

            echo "<script type=\"text/javascript\">" .
            "alert('Ingresando a Formulario Registro de Interrupción');" .
            "</script> ";

            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = "pagina=formHistoria";
            $variable.="&opcion=interrupcion";
            $variable.="&serial=" . (isset($datos_antiguos['hlab_serial']) ? $datos_antiguos['hlab_serial'] : '');
            $variable.="&nit_entidad_vieja=" . $datos_antiguos['hlab_nitenti'];
            $variable.="&nit_previsora_vieja=" . $datos_antiguos['hlab_nitprev'];
            $variable.="&h_nro_ingreso=" . $consecutivo_def;
            $variable.="&cedula=" . $datos_nuevos['cedula_emp'];
            $variable.="&nit_entidad=" . $datos_nuevos['empleador_nit'];
            $variable.="&nit_previsora=" . $datos_nuevos['prev_nit'];
            $variable.="&h_fecha_ingreso=" . $datos_nuevos['fecha_ingreso'];
            $variable.="&h_fecha_salida=" . $datos_nuevos['fecha_salida'];
            $variable.="&h_horas_labor=" . $datos_nuevos['horas_laboradas'];
            $variable.="&h_periodo_labor=" . $datos_nuevos['tipo_horas'];
            $variable.="&num_certificado=" . (isset($datos['num_certificado']) ? $datos['num_certificado'] : '');
            $variable.="&fecha_certificado=" . (isset($datos['fecha_certificado']) ? $datos['fecha_certificado'] : '');
            $variable.="&h_estado=" . $estado;
            $variable.="&h_registro=" . $fecha_registro;
            $variable.="&h_tipo=actualizar";
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "' ) </script>";
            exit;
        } else {
            $actualizar_hlaboral = $this->actualizarHLaboral($parametros_hlaboral);
            $actualizar_interrupciones = $this->actualizarNitInterrupcion($parametros_hlaboral);

            if ($actualizar_hlaboral == true) {
                $registroL[0] = "GUARDAR";
                $registroL[1] = $parametros_hlaboral['cedula'] . '|' . $parametros_hlaboral['nro_ingreso'] . '|' . $parametros_hlaboral['nit_entidad']; //
                $registroL[2] = "CUOTAS_PARTES";
                $registroL[3] = $parametros_hlaboral['nit_previsora'] . '|' . $parametros_hlaboral['fecha_ingreso'] . '|' . $parametros_hlaboral['fecha_salida']; //
                $registroL[4] = time();
                $registroL[5] = "Registra datos_nuevos de la historia laboral del pensionado con ";
                $registroL[5] .= " identificacion =" . $parametros_hlaboral['cedula'];
                $this->log_us->log_usuario($registroL, $this->configuracion);

                echo "<script type = \"text/javascript\">" .
                "alert('Datos Actualizados');" .
                "</script> ";

                $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                $variable = "pagina=formHistoria";
                $variable .= "&opcion=";
                $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                exit;
            } else {
                echo "<script type=\"text/javascript\">" .
                "alert('Datos de Historia Laboral NO Actualizados Correctamente. ERROR en el REGISTRO');" .
                "</script> ";

                $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                $variable = "pagina=formHistoria";
                $variable .= "&opcion=";
                $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                exit;
            }
        }
    }

    function procesarFormularioInterrupcion($datos) {

        $parametros = array(
            'cedula' => (isset($datos['cedula_emp']) ? $datos['cedula_emp'] : ''),
            'nit_entidad' => (isset($datos['empleador_nit']) ? $datos['empleador_nit'] : ''),
            'nit_previsora' => (isset($datos['prev_nit']) ? $datos['prev_nit'] : ''),
        );

        $consecutivo_acumulado = $this->consultarConsecutivoI($parametros);

        if ($consecutivo_acumulado == true) {
            $consecutivo = $consecutivo_acumulado[0]['int_nro_interrupcion'];
            $ingreso = $consecutivo_acumulado[0]['int_nro_ingreso'];
        } else {
            $consecutivo = 0;
            $ingreso = 0;
        }

        $consecutivo_def = $consecutivo + 1;
        $ingreso_def = $ingreso + 1;
        $fecha_registro = date('d/m/Y');
        $estado = 1;

        $antes = strtotime(str_replace('/', '-', $datos['dias_nor_desde']));
        $despues = strtotime(str_replace('/', '-', $datos['dias_nor_hasta']));

        if ($antes == 0) {
            $datos['dias_nor_desde'] = NULL;
        }

        if ($despues == 0) {
            $datos['dias_nor_hasta'] = NULL;
        }

        $dias_transcurridos = abs(($despues - $antes) / 86400);
        $dias_registrados = intval($datos['total_dias']);

        if ($dias_registrados == 0) {
            $datos['total_dias'] = 0;
        }

        if ($antes > $despues) {
            echo "<script type=\"text/javascript\">" .
            "alert('Las fechas de interrupción no son válidas');" .
            "</script> ";
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = 'pagina=formHistoria';
            $variable.='&opcion=';
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
            exit;
        }

        if ($dias_registrados != 0 && $dias_transcurridos != 0) {
            if (($dias_registrados) > $dias_transcurridos) {
                echo "<script type=\"text/javascript\">" .
                "alert('El número de días de interrupción es mayor a los días existentes en el periodo de interrupción.');" .
                "</script> ";
                $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                $variable = 'pagina=formHistoria';
                $variable.='&opcion=';
                $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                exit;
            }
        }

        foreach ($datos as $key => $value) {

            /*  if ($datos[$key] == "") {
              echo "<script type=\"text/javascript\">" .
              "alert('Formulario NO diligenciado correctamente');" .
              "</script> ";
              $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
              $variable = 'pagina=formHistoria';
              $variable.='&opcion=';
              $variable = $this->cripto->codificar_url($variable, $this->configuracion);
              echo "<script>location.replace('" . $pagina . $variable . "')</script>";
              exit;
              }
              }
             */
            if (isset($datos['total_dias']) == 0) {
                echo "<script type=\"text/javascript\">" .
                "alert('Valor días de trabajo NO válido');" .
                "</script> ";
                $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                $variable = 'pagina=formHistoria';
                $variable.='&opcion=';
                $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                exit;
            }

            /*  foreach ($datos as $key => $value) {
              if (!preg_match("#^[a-zA-Z0-9/.-\só]{1,50}$#", $datos[$key])) {
              echo "<script type=\"text/javascript\">" .
              "alert('Formulario NO diligenciado correctamente ...');" .
              "</script> ";
              $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
              $variable = 'pagina=formHistoria';
              $variable.='&opcion=';
              $variable = $this->cripto->codificar_url($variable, $this->configuracion);
              echo "<script>location.replace('" . $pagina . $variable . "')</script>";
              exit;
              }
              } */

            //VALIDACIÓN DE TRASLAPE DE FECHAS

            $parametro = $datos['cedula_emp'];
            $datos_regint = $this->reporteInterrupcion($parametro);

            /* Para determinar los limites del registro de la historia laboral */
            if ($antes != 0) {
                if (is_array($datos_regint)) {
                    if ($datos_regint == true) {
                        foreach ($datos_regint as $key => $value) {
                            $rango[$key] = array(
                                'inicio' => date('d/m/Y', strtotime($datos_regint[$key]['int_fdesde'])),
                                'fin' => date('d/m/Y', strtotime($datos_regint[$key]['int_fhasta'])));
                        }
                    } else {
                        $rango[$key] = array(
                            'inicio' => date('d/m/Y', strtotime('01/01/1940')),
                            'fin' => date('d/m/Y', strtotime('01/02/1940')));
                    }

                    foreach ($rango as $key => $values) {
                        $inicio = strtotime(str_replace('/', '-', $rango[$key]['inicio']));
                        $fin = strtotime(str_replace('/', '-', $rango[$key]['fin']));

                        if ($antes >= $inicio && $antes <= $fin) {
                            echo "<script type=\"text/javascript\">" .
                            "alert('El intervalo de fechas de interrupción no es válido');" .
                            "</script> ";
                            EXIT;
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
                            "alert('El intervalo de fechas de interrupción no es válido');" .
                            "</script> ";
                            EXIT;
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
            /* Terminan las validaciones de los datos */

            $parametros = array(
                'nro_interrupcion' => ($consecutivo_def),
                'nro_ingreso' => ($ingreso_def),
                'cedula' => (isset($datos['cedula_emp']) ? $datos['cedula_emp'] : ''),
                'nit_entidad' => (isset($datos['nit_entidad']) ? $datos['nit_entidad'] : ''),
                'entidad_previsora' => (isset($datos['prev_nit']) ? $datos['prev_nit'] : ''),
                'dias_nor_desde' => (isset($datos['dias_nor_desde']) ? $datos['dias_nor_desde'] : ''),
                'dias_nor_hasta' => (isset($datos['dias_nor_hasta']) ? $datos['dias_nor_hasta'] : ''),
                'estado' => $estado,
                'registro' => $fecha_registro,
                'total_dias' => (isset($datos['total_dias']) ? $datos['total_dias'] : 0));


            $parametros_hlaboral = array(
                'serial' => (isset($datos['serial']) ? $datos['serial'] : ''),
                'nro_ingreso' => (isset($datos['h_nro_ingreso']) ? $datos['h_nro_ingreso'] : ''),
                'cedula' => (isset($datos['cedula_emp']) ? $datos['cedula_emp'] : ''),
                'nit_entidad' => (isset($datos['nit_entidad']) ? $datos['nit_entidad'] : ''),
                'nit_previsora' => (isset($datos['prev_nit']) ? $datos['prev_nit'] : ''),
                'fecha_ingreso' => (isset($datos['h_fecha_ingreso']) ? $datos['h_fecha_ingreso'] : ''),
                'fecha_salida' => (isset($datos['h_fecha_salida']) ? $datos['h_fecha_salida'] : ''),
                'horas_labor' => (isset($datos['h_horas_labor']) ? $datos['h_horas_labor'] : ''),
                'periodo_labor' => (isset($datos['h_periodo_labor']) ? $datos['h_periodo_labor'] : ''),
                'num_certificado' => (isset($datos['num_certificado']) ? $datos['num_certificado'] : ''),
                'fecha_certificado' => (isset($datos['fecha_certificado']) ? $datos['fecha_certificado'] : NULL),
                'estado' => (isset($datos['h_estado']) ? $datos['h_estado'] : ''),
                'registro' => (isset($datos['h_registro']) ? $datos['h_registro'] : ''));

            if ($datos['h_tipo'] == 'actualizar') {
                $procesar_hlaboral = $this->actualizarHLaboral($parametros_hlaboral);
            } else {
                $procesar_hlaboral = $this->registrarHLaboral($parametros_hlaboral);
            }

            if ($procesar_hlaboral == true) {
                $registroL[0] = "GUARDAR";
                $registroL[1] = $parametros_hlaboral['cedula'] . '|' . $parametros_hlaboral['nro_ingreso'] . '|' . $parametros_hlaboral['nit_entidad']; //
                $registroL[2] = "CUOTAS_PARTES";
                $registroL[3] = $parametros_hlaboral['nit_previsora'] . '|' . $parametros_hlaboral['fecha_ingreso'] . '|' . $parametros_hlaboral['fecha_salida']; //
                $registroL[4] = time();
                $registroL[5] = "Registra datos de la historia laboral del pensionado con ";
                $registroL[5] .= " identificacion =" . $parametros_hlaboral['cedula'];
                $this->log_us->log_usuario($registroL, $this->configuracion);
                echo "<script type = \"text/javascript\">" .
                "alert('Datos Historia Laboral Registrados');" .
                "</script> ";
            } else {
                echo "<script type=\"text/javascript\">" .
                "alert('Datos de Historia Laboral no procesados');" .
                "</script> ";
            }

            $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "insertarInterrupcion", $parametros);
            $datos_registrados = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "registrar");

            if ($datos_registrados == true) {
                $registro[0] = "GUARDAR";
                $registro[1] = $parametros['cedula'] . '|' . $parametros['nro_interrupcion'] . '|' . $parametros['nit_entidad']; //
                $registro[2] = "CUOTAS_PARTES";
                $registro[3] = $parametros['entidad_previsora'] . '|' . $parametros['dias_nor_desde'] . '|' . $parametros['dias_nor_hasta']; //
                $registro[4] = time();
                $registro[5] = "Registra datos de interrupcion laboral del pensionado con ";
                $registro[5] .= " identificacion =" . $parametros['cedula'];
                $this->log_us->log_usuario($registro, $this->configuracion);

                echo "<script type=\"text/javascript\">" .
                "alert('Datos Registrados Interrupción Laboral Registrados');" .
                "</script> ";

                switch ($datos['registro']) {

                    case "Guardar Interrupción":

                        $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                        $variable = "pagina=formHistoria";
                        $variable.="&opcion=interrupcion";
                        $variable.="&cedula=" . (isset($datos['cedula_emp']) ? $datos['cedula_emp'] : '');
                        $variable.="&nit_entidad=" . (isset($datos['nit_entidad']) ? $datos['nit_entidad'] : '');
                        $variable.="&nit_previsora=" . (isset($datos['prev_nit']) ? $datos['prev_nit'] : '');
                        $variable.="&fecha_ingreso_int=" . (isset($datos['dias_nor_desde']) ? $datos['dias_nor_desde'] : NULL);
                        $variable.="&fecha_salida_int=" . (isset($datos['dias_nor_hasta']) ? $datos['dias_nor_hasta'] : NULL);
                        $variable.="&h_fecha_ingreso=" . (isset($parametros_hlaboral['fecha_ingreso']) ? $parametros_hlaboral['fecha_ingreso'] : '');
                        $variable.="&h_fecha_salida=" . (isset($parametros_hlaboral['fecha_salida']) ? $parametros_hlaboral['fecha_salida'] : '');
                        $variable.="&h_nro_ingreso=" . (isset($parametros_hlaboral['nro_ingreso']) ? $parametros_hlaboral['nro_ingreso'] : '');
                        $variable.="&h_horas_labor=" . (isset($parametros_hlaboral['horas_labor']) ? $parametros_hlaboral['horas_labor'] : '');
                        $variable.="&h_periodo_labor=" . (isset($parametros_hlaboral['periodo_labor']) ? $parametros_hlaboral['periodo_labor'] : '');
                        $variable.="&h_estado=" . (isset($parametros_hlaboral['estado']) ? $parametros_hlaboral['estado'] : '');
                        $variable.="&num_certificado=" . (isset($datos['num_certificado']) ? $datos['num_certificado'] : '');
                        $variable.="&fecha_certificado=" . (isset($datos['fecha_certificado']) ? $datos['fecha_certificado'] : '');
                        $variable.="&h_registro=" . (isset($parametros_hlaboral['registro']) ? $parametros_hlaboral['registro'] : '');
                        $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                        echo "<script>location.replace('" . $pagina . $variable . "' ) </script>";
                        break;
                }
            } else {
                echo "<script type=\"text/javascript\">" .
                "alert('Datos de Interrupción NO Registrados Correctamente. ERROR en el REGISTRO');" .
                "</script> ";

                $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                $variable = "pagina=reportesCuotas";
                $variable .= "&opcion=";
                $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                exit;
            }
        }
    }

    function procesarFormularioInterrupcion_Modificar($datos) {

        $parametros = array(
            'cedula' => (isset($datos['cedula_emp']) ? $datos['cedula_emp'] : ''),
            'nit_entidad' => (isset($datos['empleador_nit']) ? $datos['empleador_nit'] : ''),
            'nit_previsora' => (isset($datos['prev_nit']) ? $datos['prev_nit'] : ''),
        );

        $fecha_registro = date('d/m/Y');
        $estado = 1;

        $antes = strtotime(str_replace('/', '-', $datos['dias_nor_desde']));
        $despues = strtotime(str_replace('/', '-', $datos['dias_nor_hasta']));

        if ($antes == 0) {
            $datos['dias_nor_desde'] = NULL;
        }

        if ($despues == 0) {
            $datos['dias_nor_hasta'] = NULL;
        }

        $dias_transcurridos = abs(($despues - $antes) / 86400);
        $dias_registrados = intval($datos['total_dias']);

        if ($dias_registrados == 0) {
            $datos['total_dias'] = 0;
        }

        if ($antes > $despues) {
            echo "<script type=\"text/javascript\">" .
            "alert('Las fechas de interrupción no son válidas');" .
            "</script> ";
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = 'pagina=formHistoria';
            $variable.='&opcion=';
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
            exit;
        }

        if ($dias_registrados != 0 && $dias_transcurridos != 0) {
            if (($dias_registrados) > $dias_transcurridos) {
                echo "<script type=\"text/javascript\">" .
                "alert('El número de días de interrupción es mayor a los días existentes en el periodo de interrupción.');" .
                "</script> ";
                $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                $variable = 'pagina=formHistoria';
                $variable.='&opcion=';
                $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                exit;
            }
        }

        foreach ($datos as $key => $value) {

            /*  if ($datos[$key] == "") {
              echo "<script type=\"text/javascript\">" .
              "alert('Formulario NO diligenciado correctamente');" .
              "</script> ";
              $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
              $variable = 'pagina=formHistoria';
              $variable.='&opcion=';
              $variable = $this->cripto->codificar_url($variable, $this->configuracion);
              echo "<script>location.replace('" . $pagina . $variable . "')</script>";
              exit;
              }
              }
             */
            if (isset($datos['total_dias']) == 0) {
                echo "<script type=\"text/javascript\">" .
                "alert('Valor días de trabajo NO válido');" .
                "</script> ";
                $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                $variable = 'pagina=formHistoria';
                $variable.='&opcion=';
                $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                exit;
            }

            /*  foreach ($datos as $key => $value) {
              if (!preg_match("#^[a-zA-Z0-9/.-\só]{1,50}$#", $datos[$key])) {
              echo "<script type=\"text/javascript\">" .
              "alert('Formulario NO diligenciado correctamente ...');" .
              "</script> ";
              $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
              $variable = 'pagina=formHistoria';
              $variable.='&opcion=';
              $variable = $this->cripto->codificar_url($variable, $this->configuracion);
              echo "<script>location.replace('" . $pagina . $variable . "')</script>";
              exit;
              }
              } */

            //VALIDACIÓN DE TRASLAPE DE FECHAS

            $parametro = $datos['cedula_emp'];
            $datos_regint = $this->reporteInterrupcion($parametro);

            /* Para determinar los limites del registro de la historia laboral */
            if ($antes != 0) {
                if (is_array($datos_regint)) {
                    if ($datos_regint == true) {
                        foreach ($datos_regint as $key => $value) {
                            if ($datos_regint[$key]['int_nro_interrupcion'] !== $datos['int_nro_ingreso']) {
                                $rango[$key] = array(
                                    'inicio' => date('d/m/Y', strtotime($datos_regint[$key]['int_fdesde'])),
                                    'fin' => date('d/m/Y', strtotime($datos_regint[$key]['int_fhasta'])));
                            }
                        }
                    } else {
                        $rango[$key] = array(
                            'inicio' => date('d/m/Y', strtotime('01/01/1940')),
                            'fin' => date('d/m/Y', strtotime('01/02/1940')));
                    }

                    foreach ($rango as $key => $values) {

                        $inicio = strtotime(str_replace('/', '-', $rango[$key]['inicio']));
                        $fin = strtotime(str_replace('/', '-', $rango[$key]['fin']));

                        if ($antes >= $inicio && $antes <= $fin) {
                            echo "<script type=\"text/javascript\">" .
                            "alert('El intervalo de fechas de interrupción no es válido');" .
                            "</script> ";
                            EXIT;
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
                            "alert('El intervalo de fechas de interrupción no es válido');" .
                            "</script> ";
                            EXIT;
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
            /* Terminan las validaciones de los datos */

            $parametros = array(
                'nro_interrupcion' => (isset($datos['int_nro_ingreso']) ? $datos['int_nro_ingreso'] : ''),
                'nro_ingreso' => (isset($datos['int_nro_interrupcion']) ? $datos['int_nro_interrupcion'] : ''),
                'cedula' => (isset($datos['cedula_emp']) ? $datos['cedula_emp'] : ''),
                'nit_entidad' => (isset($datos['nit_entidad']) ? $datos['nit_entidad'] : ''),
                'entidad_previsora' => (isset($datos['prev_nit']) ? $datos['prev_nit'] : ''),
                'dias_nor_desde' => (isset($datos['dias_nor_desde']) ? $datos['dias_nor_desde'] : ''),
                'dias_nor_hasta' => (isset($datos['dias_nor_hasta']) ? $datos['dias_nor_hasta'] : ''),
                'estado' => $estado,
                'registro' => $fecha_registro,
                'total_dias' => (isset($datos['total_dias']) ? $datos['total_dias'] : 0));

            $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "actualizarInterrupcion", $parametros);
            $datos_actualizados = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "registrar");

            if ($datos_actualizados == true) {
                $registro[0] = "ACTUALIZAR";
                $registro[1] = $parametros['cedula'] . '|' . $parametros['nro_interrupcion'] . '|' . $parametros['nit_entidad']; //
                $registro[2] = "CUOTAS_PARTES";
                $registro[3] = $parametros['entidad_previsora'] . '|' . $parametros['dias_nor_desde'] . '|' . $parametros['dias_nor_hasta']; //
                $registro[4] = time();
                $registro[5] = "Actualizar datos de interrupcion laboral del pensionado con ";
                $registro[5] .= " identificacion =" . $parametros['cedula'];
                $this->log_us->log_usuario($registro, $this->configuracion);

                echo "<script type=\"text/javascript\">" .
                "alert('Datos Interrupción Laboral Actualizados');" .
                "</script> ";

                $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                $variable = "pagina=reportesCuotas";
                $variable .= "&opcion=";
                $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                exit;
                exit;
            } else {
                echo "<script type=\"text/javascript\">" .
                "alert('Datos de Interrupción NO Actualizados Correctamente. ERROR en el REGISTRO');" .
                "</script> ";

                $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                $variable = "pagina=reportesCuotas";
                $variable .= "&opcion=";
                $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                exit;
            }
        }
    }

}
