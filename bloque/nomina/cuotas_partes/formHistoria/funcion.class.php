
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

    function registrarHLaboral($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "insertarHistoria", $parametros);
        $datos_Hlaboral = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos_Hlaboral;
    }

    function registrarEntidad($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "insertarEntidad", $parametros);
        $datos_Entidad = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos_Entidad;
    }

    function registrarDescripcionCP($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "insertarDescripcionCP", $parametros);
        $datos_DescripcionCP = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos_DescripcionCP;
    }

    function procesarFormulario($datos) {
        $estado = 1;
        $fecha_registro = date('d/m/Y');

        foreach ($datos as $key => $value) {

            if ($datos[$key] == "") {
                echo "<script type=\"text/javascript\">" .
                "alert('Formulario NO diligenciado correctamente');" .
                "</script> ";
                $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                $variable = 'pagina=reportesCuotas';
                $variable.='&opcion=';
                $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                exit;
            }
        }

        /*
          if (!preg_match('/^\d{1, 2}\/\d{1, 2}\/\d{4}$/', $datos['fecha_ingreso'])) {

          echo 'tuca';
          exit;
          echo "<script type=\"text/javascript\">" .
          "alert('Formato fecha ingreso diligenciado incorrectamente');" .
          "</script> ";
          $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
          $variable = 'pagina = formHistoria';
          $variable.='&opcion = ';
          $variable = $this->cripto->codificar_url($variable, $this->configuracion);
          echo "<script>location.replace(' " . $pagina . $variable . "')</script>";
          exit;
          }

          if (!preg_match('/^\d{1, 2}\/\d{1, 2}\/\d{4}$/', $datos[' fecha_salida'])) {
          echo "<script type=\"text/javascript\">" .
          "alert('Formato fecha salida diligenciado incorrectamente');" .
          "</script> ";
          $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
          $variable = 'pagina = formHistoria';
          $variable.='&opcion = ';
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
          $variable = 'pagina = formHistoria';
          $variable.='&opcion = ';
          $variable = $this->cripto->codificar_url($variable, $this->configuracion);
          echo "<script>location.replace(' " . $pagina . $variable . "')</script>";
          exit;
          }

          foreach ($datos as $key => $value) {
          if (ereg("^[a-zA-Z0-9\.-]{3,20}$", $datos[$key])) {
          echo "El formato de $datos[$key] es correcto<br>";
          } else {
          echo "El formato de $datos[$key] es incorrecto<br>";
          }
          }
         */

        if ($datos['prev_nit'] == '0') {
            $datos['prev_nit'] = $datos['nit_empleador'];
        }


        $parametros_hlaboral = array(
            'nro_ingreso' => (isset($datos['nro_ingreso']) ? $datos['nro_ingreso'] : ''),
            'cedula' => (isset($datos['cedula_emp']) ? $datos['cedula_emp'] : ''),
            'nit_entidad' => (isset($datos['nit_empleador']) ? $datos['nit_empleador'] : ''),
            'nit_previsora' => (isset($datos['prev_nit']) ? $datos['prev_nit'] : ''),
            'fecha_ingreso' => (isset($datos['fecha_ingreso']) ? $datos['fecha_ingreso'] : ''),
            'fecha_salida' => (isset($datos['fecha_salida']) ? $datos['fecha_salida'] : ''),
            'horas_labor' => (isset($datos['horas_laboradas']) ? $datos['horas_laboradas'] : ''),
            'periodo_labor' => (isset($datos['tipo_horas']) ? $datos['tipo_horas'] : ''),
            'estado' => $estado,
            'registro' => $fecha_registro);
        /*
          $parametros_descripcion_cp = array(
          'cedula' => (isset($datos['cedula_emp']) ? $datos['cedula_emp'] : ''),
          'nit_entidad' => (isset($datos['nit_empleador']) ? $datos['nit_empleador'] : ''),
          'nit_previsora' => (isset($datos['prev_nit']) ? $datos['prev_nit'] : ''),
          'valor_mesada' => (isset($datos['mesada']) ? $datos['mesada'] : ''),
          'valor_cuota' => (isset($datos['cp_aceptada']) ? $datos['cp_aceptada'] : ''),
          'porcen_cuota' => (isset($datos['porc_aceptado']) ? $datos['porc_aceptado'] : ''),
          'actoadmin' => (isset($datos['acto_adm']) ? $datos['acto_adm'] : ''),
          'factoadmin' => (isset($datos['fecha_acto_adm']) ? $datos['fecha_acto_adm'] : ''),
          'estado' => $estado,
          'registro' => $fecha_registro); */

        $parametros_entidad = array(
            'nit_entidad' => (isset($datos['nit_empleador']) ? $datos['nit_empleador'] : ''),
            'nombre_entidad' => (isset($datos['nombre_empleador']) ? $datos['nombre_empleador'] : ''),
            'ciudad_entidad' => (isset($datos['ciudad_entidad']) ? $datos['ciudad_entidad'] : ''),
            'direccion_entidad' => (isset($datos['direccion_entidad']) ? $datos['direccion_entidad'] : ''),
            'telefono_entidad' => (isset($datos['telefono_entidad']) ? $datos['telefono_entidad'] : ''),
            'contacto_entidad' => (isset($datos['contacto_entidad']) ? $datos['contacto_entidad'] : ''),
            'estado' => $estado,
            'registro' => $fecha_registro);

        $registro_hlaboral = $this->registrarHLaboral($parametros_hlaboral);
        $registroL[0] = "GUARDAR";
        $registroL[1] = $parametros_hlaboral['cedula'] . '|' . $parametros_hlaboral['nro_ingreso'] . '|' . $parametros_hlaboral['nit_entidad']; //
        $registroL[2] = "CUOTAS_PARTES";
        $registroL[3] = $parametros_hlaboral['nit_previsora'] . '|' . $parametros_hlaboral['fecha_ingreso'] . '|' . $parametros_hlaboral['fecha_salida']; //
        $registroL[4] = time();
        $registroL[5] = "Registra datos de la historia laboral del pensionado con ";
        $registroL[5] .= " identificacion =" . $parametros_hlaboral['cedula'];
        $this->log_us->log_usuario($registroL, $this->configuracion);

        /*   $registro_descripcion_cp = $this->registrarDescripcionCP($parametros_descripcion_cp);
          $registroD[0] = "GUARDAR";
          $registroD[1] = $parametros_descripcion_cp['cedula'] . '|' . $parametros_descripcion_cp['nit_entidad'] . '|' . $parametros_descripcion_cp['nit_previsora']; //
          $registroD[2] = "CUOTAS_PARTES";
          $registroD[3] = $parametros_descripcion_cp['valor_mesada'] . '|' . $parametros_descripcion_cp['valor_cuota'] . '|' . $parametros_descripcion_cp['porcen_cuota']; //
          $registroD[4] = time();
          $registroD[5] = "Registra datos cuota parte pactada para el pensionado con ";
          $registroD[5] .= " identificacion =" . $parametros_descripcion_cp['cedula'];
          $this->log_us->log_usuario($registroD, $this->configuracion); */

        $registro_entidad = $this->registrarEntidad($parametros_entidad);
        $registroE[0] = "GUARDAR";
        $registroE[1] = $parametros_entidad['nit_entidad'] . '|' . $parametros_entidad['nombre_entidad']; //
        $registroE[2] = "CUOTAS_PARTES";
        $registroE[3] = $parametros_entidad['ciudad_entidad'] . '|' . $parametros_entidad['telefono_entidad'] . '|' . $parametros_entidad['contacto_entidad']; //
        $registroE[4] = time();
        $registroE[5] = "Registra datos básicos de la entidad cuotas partes  ";
        $registroE[5] .= " nit_entidad =" . $parametros_entidad['nit_entidad'];
        $this->log_us->log_usuario($registroE, $this->configuracion);


        if ($datos['interrupcion'] == 'interrupcion') {
            echo "<script type=\"text/javascript\">" .
            "alert('A interrupcion');" .
            "</script> ";

            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = "pagina=formHistoria";
            $variable.="&opcion=interrupcion";
            $variable.="&cedula=" . $datos['cedula_emp'];
            $variable.="&nit_entidad=" . $datos['nit_empleador'];
            $variable.="&nit_previsora=" . $datos['prev_nit'];
            $variable.="&nro_ingreso=" . $datos['nro_ingreso'];
            $variable.="&fecha_ingreso=" . $datos['fecha_ingreso'];
            $variable.="&fecha_salida=" . $datos['fecha_salida'];
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "' ) </script>";
            exit;
        }

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

    function procesarFormularioInterrupcion($datos) {
        $fecha_registro = date('d/m/Y');
        $estado = 1;

      /*  foreach ($datos as $key => $value) {

            if ($datos[$key] == "") {
                echo "<script type=\"text/javascript\">" .
                "alert('Formulario NO diligenciado correctamente');" .
                "</script> ";
                $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                $variable = 'pagina = reportesCuotas';
                $variable.='&opcion = ';
                $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                exit;
            }
        }

        if (!preg_match('/^\d{1, 2}\/\d{1, 2}\/\d{4}$/', $datos['dias_nor_desde'])) {
            echo "<script type=\"text/javascript\">" .
            "alert('Formato fecha diligenciado incorrectamente');" .
            "</script> ";
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = 'pagina = formHistoria';
            $variable.='&opcion = interrupcion';
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
            exit;
        }

        if (!preg_match('/^\d{1, 2}\/\d{1, 2}\/\d{4}$/', $datos['dias_nor_hasta'])) {
            echo "<script type=\"text/javascript\">" .
            "alert('Formato fecha diligenciado incorrectamente');" .
            "</script> ";
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = 'pagina = formHistoria';
            $variable.='&opcion = interrupcion';
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
            exit;
        }*/

        $parametros = array(
            'nro_interrupcion' => (isset($datos['nro_interrupcion']) ? $datos['nro_interrupcion'] : ''),
            'nro_ingreso' => (isset($datos['nro_ingreso']) ? $datos['nro_ingreso'] : ''),
            'cedula' => (isset($datos['cedula_emp']) ? $datos['cedula_emp'] : ''),
            'nit_entidad' => (isset($datos['nit_entidad']) ? $datos['nit_entidad'] : ''),
            'entidad_previsora' => (isset($datos['prev_nit']) ? $datos['prev_nit'] : ''),
            'dias_nor_desde' => (isset($datos['dias_nor_desde']) ? $datos['dias_nor_desde'] : ''),
            'dias_nor_hasta' => (isset($datos['dias_nor_hasta']) ? $datos['dias_nor_hasta'] : ''),
            'estado' => $estado,
            'registro' => $fecha_registro,
            'total_dias' => (isset($datos['total_dias']) ? $datos['total_dias'] : ''),);

        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "insertarInterrupcion", $parametros);
        $datos_registrados = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");

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

        
        
        
        
        $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
        $variable = "pagina=reportesCuotas";
        $variable .= "&opcion=";
        $variable = $this->cripto->codificar_url($variable, $this->configuracion);
        echo "<script>location.replace('" . $pagina . $variable . "')</script>";
    }

    function dbasicoHistoria() {
        $this->html_formHistoria->datoBasico();
    }

    function mostrarHistoria($cedula) {
        $parametro = $cedula;
        $consulta = $this->consultarHistoria($parametro);
        $this->html_formHistoria->datosEntidad($consulta);
    }

}
?>