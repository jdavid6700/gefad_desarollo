
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
        //$this->acceso_oracle = $this->conectarDB($configuracion, "cuotasP");
        //var_dump($this->acceso_oracle);
        //Datos de sesion

        $this->usuario = $this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion = $this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");

        $this->configuracion = $configuracion;

        $this->html_formHistoria = new html_formHistoria($configuracion);
    }

    function mostrarFormulario() {
        $datos_previsor = $this->consultarPrevisora();
        $this->html_formHistoria->formularioHistoria($datos_previsor);
    }

    function nuevaInterrupcion($datos_interrupcion) {
        $datos_previsor = $this->consultarPrevisora();
        $this->html_formHistoria->formularioInterrupcion($datos_previsor, $datos_interrupcion);
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

    /* function consultarGeografia($parametro) {
      echo $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_oracle, "consultarGeografia", $parametro);
      $datos_geo = $this->ejecutarSQL($this->configuracion, $this->acceso_oracle, $cadena_sql, "busqueda");
      return $datos_geo;
      }
     */

    function reporteHistoria($parametro) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "reporteHistoria", $parametro);
        $datos_historia = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos_historia;
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

    function dbasicoHistoria() {
        $this->html_formHistoria->datoBasico();
    }

    function mostrarHistoria($cedula) {
        $parametro = $cedula;
        $consulta_historia = $this->reporteHistoria($parametro);
        $consulta_interrupcion = $this->reporteInterrupcion($parametro);
        $consulta_descripcion = $this->reporteDescripcion($parametro);
        // $consulta_geo = $this->consultarGeografia($parametro);
        $this->html_formHistoria->datosReporte($consulta_historia, $consulta_interrupcion, $consulta_descripcion);
    }

    function procesarFormulario($datos) {
        
        var_dump($datos);
        $estado = 1;
        $fecha_registro = date('d/m/Y');

        foreach ($datos as $key => $value) {
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

        foreach ($datos as $key => $value) {
            if (!preg_match("#^[a-zA-Z0-9/.-\s]{1,80}$#", $datos[$key])) {
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
        }


        if (!preg_match('^(?:(?:31(\/|-|\.)(?:0?[13578]|1[02]))\1|(?:(?:29|30)(\/|-|\.)(?:0?[1,3-9]|1[0-2])\2))(?:(?:1[6-9]|[2-9]\d)?\d{2})$|^(?:29(\/|-|\.)0?2\3(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:0?[1-9]|1\d|2[0-8])(\/|-|\.)(?:(?:0?[1-9])|(?:1[0-2]))\4(?:(?:1[6-9]|[2-9]\d)?\d{2})$', $datos['fecha_ingreso'])) {
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

        //((^(10|12|0?[13578])([/])(3[01]|[12][0-9]|0?[1-9])([/])((1[8-9]\d{2})|([2-9]\d{3}))$)|(^(11|0?[469])([/])(30|[12][0-9]|0?[1-9])([/])((1[8-9]\d{2})|([2-9]\d{3}))$)|(^(0?2)([/])(2[0-8]|1[0-9]|0?[1-9])([/])((1[8-9]\d{2})|([2-9]\d{3}))$)|(^(0?2)([/])(29)([/])([2468][048]00)$)|(^(0?2)([/])(29)([/])([3579][26]00)$)|(^(0?2)([/])(29)([/])([1][89][0][48])$)|(^(0?2)([/])(29)([/])([2-9][0-9][0][48])$)|(^(0?2)([/])(29)([/])([1][89][2468][048])$)|(^(0?2)([/])(29)([/])([2-9][0-9][2468][048])$)|(^(0?2)([/])(29)([/])([1][89][13579][26])$)|(^(0?2)([/])(29)([/])([2-9][0-9][13579][26])$))
        if (!preg_match('^(?:(?:31(\/|-|\.)(?:0?[13578]|1[02]))\1|(?:(?:29|30)(\/|-|\.)(?:0?[1,3-9]|1[0-2])\2))(?:(?:1[6-9]|[2-9]\d)?\d{2})$|^(?:29(\/|-|\.)0?2\3(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:0?[1-9]|1\d|2[0-8])(\/|-|\.)(?:(?:0?[1-9])|(?:1[0-2]))\4(?:(?:1[6-9]|[2-9]\d)?\d{2})$', $datos['fecha_salida'])) {
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

        if(!preg_match('[0-9]{1}', $datos['nro_ingreso'])) {
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


        $antes = strtotime($datos['fecha_ingreso']);
        $despues = strtotime($datos['fecha_salida']);

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

        if ($datos['prev_nit'] == '0' || $datos['prev_nit'] == null) {
            $datos['prev_nit'] = $datos['empleador_nit'];
        }

        $parametros_hlaboral = array(
            'nro_ingreso' => (isset($datos['nro_ingreso']) ? $datos['nro_ingreso'] : ''),
            'cedula' => (isset($datos['cedula_emp']) ? $datos['cedula_emp'] : ''),
            'nit_entidad' => (isset($datos['empleador_nit']) ? $datos['empleador_nit'] : ''),
            'nit_previsora' => (isset($datos['prev_nit']) ? $datos['prev_nit'] : ''),
            'fecha_ingreso' => (isset($datos['fecha_ingreso']) ? $datos['fecha_ingreso'] : ''),
            'fecha_salida' => (isset($datos['fecha_salida']) ? $datos['fecha_salida'] : ''),
            'horas_labor' => (isset($datos['horas_laboradas']) ? $datos['horas_laboradas'] : ''),
            'periodo_labor' => (isset($datos['tipo_horas']) ? $datos['tipo_horas'] : ''),
            'estado' => $estado,
            'registro' => $fecha_registro);

        $parametros_entidad = array(
            'nit_entidad' => (isset($datos['empleador_nit']) ? $datos['empleador_nit'] : ''),
            'ciudad_entidad' => (isset($datos['ciudad_entidad']) ? $datos['ciudad_entidad'] : ''),
            'direccion_entidad' => (isset($datos['direccion_entidad']) ? $datos['direccion_entidad'] : ''),
            'telefono_entidad' => (isset($datos['telefono_entidad']) ? $datos['telefono_entidad'] : ''),
            'contacto_entidad' => (isset($datos['contacto_entidad']) ? $datos['contacto_entidad'] : ''),
            'estado' => $estado,
            'registro' => $fecha_registro);

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


            if ($datos['interrupcion'] == 'interrupcion') {
                echo "<script type=\"text/javascript\">" .
                "alert('A interrupcion');" .
                "</script> ";

                $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                $variable = "pagina=formHistoria";
                $variable.="&opcion=interrupcion";
                $variable.="&cedula=" . $datos['cedula_emp'];
                $variable.="&nit_entidad=" . $datos['empleador_nit'];
                $variable.="&nit_previsora=" . $datos['prev_nit'];
                $variable.="&nro_ingreso=" . $datos['nro_ingreso'];
                $variable.="&fecha_ingreso=" . $datos['fecha_ingreso'];
                $variable.="&fecha_salida=" . $datos['fecha_salida'];
                $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                echo "<script>location.replace('" . $pagina . $variable . "' ) </script>";
                exit;
            } else {

                echo "<script type = \"text/javascript\">" .
                "alert('Datos Registrados');" .
                "</script> ";

                $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                $variable = "pagina=formHistoria";
                $variable .= "&opcion=dbasicoHistoria";
                $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                exit;
            }
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

    function procesarFormularioInterrupcion($datos) {
        $fecha_registro = date('d/m/Y');
        $estado = 1;

        foreach ($datos as $key => $value) {

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

        foreach ($datos as $key => $value) {
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
        }

        if (preg_match('^(?:(?:31(\/|-|\.)(?:0?[13578]|1[02]))\1|(?:(?:29|30)(\/|-|\.)(?:0?[1,3-9]|1[0-2])\2))(?:(?:1[6-9]|[2-9]\d)?\d{2})$|^(?:29(\/|-|\.)0?2\3(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:0?[1-9]|1\d|2[0-8])(\/|-|\.)(?:(?:0?[1-9])|(?:1[0-2]))\4(?:(?:1[6-9]|[2-9]\d)?\d{2})$', $datos['dias_nor_desde'])) {
            echo "<script type=\"text/javascript\">" .
            "alert('Formato fecha diligenciado incorrectamente');" .
            "</script> ";
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = 'pagina=formHistoria';
            $variable.='&opcion=interrupcion';
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
            exit;
        }

        if (preg_match('^(?:(?:31(\/|-|\.)(?:0?[13578]|1[02]))\1|(?:(?:29|30)(\/|-|\.)(?:0?[1,3-9]|1[0-2])\2))(?:(?:1[6-9]|[2-9]\d)?\d{2})$|^(?:29(\/|-|\.)0?2\3(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:0?[1-9]|1\d|2[0-8])(\/|-|\.)(?:(?:0?[1-9])|(?:1[0-2]))\4(?:(?:1[6-9]|[2-9]\d)?\d{2})$', $datos['dias_nor_hasta'])) {
            echo "<script type=\"text/javascript\">" .
            "alert('Formato fecha diligenciado incorrectamente');" .
            "</script> ";
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = 'pagina=formHistoria';
            $variable.='&opcion=interrupcion';
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
            exit;
        }

        $parametros = array(
            'nro_interrupcion' => (isset($datos['nro_interrupcion']) ? $datos['nro_interrupcion'] : ''),
            'nro_ingreso' => (isset($datos['nro_ingreso']) ? $datos['nro_ingreso'] : ''),
            'cedula' => (isset($datos['cedula_emp']) ? $datos['cedula_emp'] : ''),
            'nit_entidad' => (isset($datos['nit_entidad']) ? $datos['nit_entidad'] : ''),
            'entidad_previsora' => (isset($datos['prev_nit']) ? $datos['prev_nit'] : ''),
            'dias_nor_desde' => (isset($datos['dias_nor_desde']) ? $datos['dias_nor_desde'] : ''),
            'dias_nor_hasta' => (isset($datos['dias_nor_hasta']) ? $datos['dias_nor_hasta'] : ''),
            'num_certificado' => (isset($datos['num_certificado']) ? $datos['num_certificado'] : ''),
            'fecha_certificado' => (isset($datos['fecha_certificado']) ? $datos['fecha_certificado'] : ''),
            'estado' => $estado,
            'registro' => $fecha_registro,
            'total_dias' => (isset($datos['total_dias']) ? $datos['total_dias'] : ''),);

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
            "alert('Datos Registrados');" .
            "</script> ";

            switch ($datos['registro']) {

                case "Registrar Interrupción Actual":
                    $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                    $variable = "pagina=reportesCuotas";
                    $variable .= "&opcion=";
                    $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                    echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                    break;

                case "Registrar Otra Interrupción":

                    $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                    $variable = "pagina=formHistoria";
                    $variable.="&opcion=interrupcion";
                    $variable.="&cedula=" . $datos['cedula_emp'];
                    $variable.="&nit_entidad=" . $datos['nit_entidad'];
                    $variable.="&nit_previsora=" . $datos['prev_nit'];
                    $variable.="&nro_ingreso=" . $datos['nro_ingreso'];
                    $variable.="&fecha_ingreso=" . $datos['fecha_ingreso'];
                    $variable.="&fecha_salida=" . $datos['fecha_salida'];
                    $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                    echo "<script>location.replace('" . $pagina . $variable . "' ) </script>";

                    break;

                default:
                    $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                    $variable = "pagina=reportesCuotas";
                    $variable .= "&opcion=";
                    $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                    echo "<script>location.replace('" . $pagina . $variable . "')</script>";
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

