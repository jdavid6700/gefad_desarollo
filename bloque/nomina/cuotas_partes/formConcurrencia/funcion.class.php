
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
  | 27/09/2013 | Violet Sosa             | 0.0.0.4     | Adaptación formulario           |
  ----------------------------------------------------------------------------------------
  | 09/12/2013 | Violet Sosa             | 0.0.0.5     |                                 |
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

class funciones_formConcurrencia extends funcionGeneral {

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

        //Datos de sesion

        $this->usuario = $this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion = $this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");

        $this->configuracion = $configuracion;

        $this->html_formConcurrencia = new html_formConcurrencia($configuracion);
    }

    function inicio() {
        $this->html_formConcurrencia->form_valor();
    }

    function mostrarPrevisoras($cedula) {
        $parametros = array('cedula' => $cedula);
        $datos_previsora = $this->consultarPrevisora($parametros);
        $datos_aceptada = $this->consultarConcurrencias($parametros);
        $datos_entidad = array();
        $pos = 0;

        if (is_array($datos_aceptada)) {
            foreach ($datos_previsora as $key => $value) {
                foreach ($datos_aceptada as $cont => $value) {
                    if ($datos_previsora[$key]['prev_nit'] !== $datos_aceptada[$cont]['dcp_nitprev']) {
                        $datos_entidad[$pos] = array(
                            'prev_nit' => $datos_previsora[$key]['prev_nit'],
                            'prev_nombre' => $datos_previsora[$key]['prev_nombre']
                        );
                        $pos++;
                    }
                }
            }

            if ($datos_entidad) {
                $datos_eunicos = array_unique($datos_entidad, SORT_REGULAR);
                $this->html_formConcurrencia->datosPrevisora($cedula, $datos_eunicos);
            } else {
                echo "<script type=\"text/javascript\">" .
                "alert('Las concurrencias para la cédula " . $parametros['cedula'] . " están diligenciadas. No existen entidades sin concurrencia registrada.');" .
                "</script> ";
                $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                $variable = 'pagina=formHistoria';
                $variable.='&opcion=';
                $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                exit;
            }
        } else {
            if (is_array($datos_previsora)) {
                $this->html_formConcurrencia->datosPrevisora($cedula, $datos_previsora);
            } else {

                echo "<script type=\"text/javascript\">" .
                "alert('No existen historias laborales registradas para la cédula " . $parametros['cedula'] . ". Por favor, diligencie el Fomulario de Registro de Historia Laboral');" .
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

    function mostrarFormulario() {

        $parametros = array(
            'cedula' => $_REQUEST['cedula_emp'],
            'previsor' => $_REQUEST['prev_nit']);

        $datos_concurrencia = $this->consultarConcurrencias($parametros);

        if (is_array($datos_concurrencia)) {
            $datos_laboral = $this->consultarHistoria($parametros);
            $datos_entidad = $this->consultarEmpleador($parametros);
            $datos_previsora = $this->consultarPrevForm($parametros);

            $this->html_formConcurrencia->formularioConcurrencia($datos_laboral, $datos_entidad, $datos_previsora, $datos_concurrencia);
        } else {
            $datos_vacio = 0;
            $datos_laboral = $this->consultarHistoria($parametros);
            $datos_entidad = $this->consultarEmpleador($parametros);
            $datos_previsora = $this->consultarPrevForm($parametros);

            $this->html_formConcurrencia->formularioConcurrencia($datos_laboral, $datos_entidad, $datos_previsora, $datos_vacio);
        }
    }

    function modificarConcurrencia($datos_concurrencia) {

        $parametros = array(
            'cedula' => $datos_concurrencia['dcp_nro_identificacion'],
            'previsor' => $datos_concurrencia['dcp_nitprev']);

        $datos_laboral = $this->consultarHistoria($parametros);
        $datos_entidad = $this->consultarEmpleador($parametros);
        $datos_previsora = $this->consultarPrevForm($parametros);

        $this->html_formConcurrencia->formularioConcurrenciaModificar($datos_laboral, $datos_entidad, $datos_previsora, $datos_concurrencia);
    }

    function registrarDescripcionCP($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "insertarDescripcionCP", $parametros);
        $datos_DescripcionCP = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "insertar");
        return $datos_DescripcionCP;
    }

    function actualizarDescripcionCP($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "actualizarDescripcionCP", $parametros);
        $datos_DescripcionCP = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "insertar");
        return $datos_DescripcionCP;
    }

    function consultarConcurrencias($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "reporteDescripcion", $parametros);
        $datos_registro = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos_registro;
    }

    function consultarHistoria($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "consultarHistoria", $parametros);
        $datos_registro = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos_registro;
    }

    function consultarEmpleador($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "consultarEmpleador", $parametros);
        $datos_registro = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos_registro;
    }

    function consultarPrevisora($parametro) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "consultarPrevisoraU", $parametro);
        $datos_previsora = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos_previsora;
    }

    function consultarPrevForm($parametro) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "consultarPrevFormulario", $parametro);
        $datos_previsora = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos_previsora;
    }

    function procesarFormulario($datos) {

        $fecha_registro = date('d/m/Y');
        $estado_registro = 1;

        foreach ($datos as $key => $value) {

            if ($datos[$key] == "") {
                echo "<script type=\"text/javascript\">" .
                "alert('Formulario NO diligenciado correctamente');" .
                "</script> ";
                $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                $variable = 'pagina=formularioConcurrencia';
                $variable.='&opcion=';
                $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                exit;
            }
        }

        if ($datos['porc_aceptado'] == 0) {
            echo "<script type=\"text/javascript\">" .
            "alert('Valor de Porcentaje NO válido');" .
            "</script> ";
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = 'pagina=formularioConcurrencia';
            $variable.='&opcion=';
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
            exit;
        }
        /*
          if (!preg_match("^\d*[0](|.\d*[0-9]|)*$^", $datos['porc_aceptado'])) {
          echo "<script type=\"text/javascript\">" .
          "alert('Formulario NO diligenciado correctamente. Formato porcentaje erróneo');" .
          "</script> ";
          $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
          $variable = 'pagina=formularioConcurrencia';
          $variable.='&opcion=';
          $variable = $this->cripto->codificar_url($variable, $this->configuracion);
          echo "<script>location.replace('" . $pagina . $variable . "')</script>";
          exit;
          }

          if (!preg_match("/([0-9]{2})\/([0-9]{2})\/([0-9]{4})/", $datos['fecha_concurrencia'])) {
          echo "<script type=\"text/javascript\">" .
          "alert('Formato fecha concurrencia diligenciado incorrectamente');" .
          "</script> ";
          $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
          $variable = 'pagina=formularioConcurrencia';
          $variable.='&opcion=';
          $variable = $this->cripto->codificar_url($variable, $this->configuracion);
          echo "<script>location.replace(' " . $pagina . $variable . "')</script>";
          exit;
          }

          if (!preg_match("/([0-9]{2})\/([0-9]{2})\/([0-9]{4})/", $datos['fecha_res_pension'])) {
          echo "<script type=\"text/javascript\">" .
          "alert('Formato fecha resolución pensión diligenciado incorrectamente');" .
          "</script> ";
          $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
          $variable = 'pagina=formularioConcurrencia';
          $variable.='&opcion=';
          $variable = $this->cripto->codificar_url($variable, $this->configuracion);
          echo "<script>location.replace(' " . $pagina . $variable . "')</script>";
          exit;
          }

          if (!preg_match("/([0-9]{2})\/([0-9]{2})\/([0-9]{4})/", $datos['fecha_pension'])) {
          echo "<script type=\"text/javascript\">" .
          "alert('Formato fecha pensión diligenciado incorrectamente');" .
          "</script> ";
          $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
          $variable = 'pagina=formularioConcurrencia';
          $variable.='&opcion=';
          $variable = $this->cripto->codificar_url($variable, $this->configuracion);
          echo "<script>location.replace(' " . $pagina . $variable . "')</script>";
          exit;
          }

          if (!preg_match("/([0-9]{2})\/([0-9]{2})\/([0-9]{4})/", $datos['fecha_acto_adm'])) {
          echo "<script type=\"text/javascript\">" .
          "alert('Formato fecha acto administrativo diligenciado incorrectamente');" .
          "</script> ";
          $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
          $variable = 'pagina=formularioConcurrencia';
          $variable.='&opcion=';
          $variable = $this->cripto->codificar_url($variable, $this->configuracion);
          echo "<script>location.replace(' " . $pagina . $variable . "')</script>";
          exit;
          } */

        $parametros_descripcion_cp = array(
            'cedula' => (isset($datos['cedula']) ? $datos['cedula'] : ''),
            'nit_entidad' => (isset($datos['entidad_empleadora']) ? $datos['entidad_empleadora'] : ''),
            'nit_previsora' => (isset($datos['entidad_previsora']) ? $datos['entidad_previsora'] : ''),
            'resolucion_pension' => (isset($datos['resolucion_pension']) ? $datos['resolucion_pension'] : ''),
            'fecha_res_pension' => (isset($datos['fecha_res_pension']) ? $datos['fecha_res_pension'] : ''),
            'fecha_pension' => (isset($datos['fecha_pension']) ? $datos['fecha_pension'] : ''),
            'valor_mesada' => (isset($datos['mesada']) ? $datos['mesada'] : ''),
            'valor_cuota' => (isset($datos['cp_aceptada']) ? $datos['cp_aceptada'] : ''),
            'porcen_cuota' => (isset($datos['porc_aceptado']) ? $datos['porc_aceptado'] : ''),
            'fecha_concurrencia' => (isset($datos['fecha_concurrencia']) ? $datos['fecha_concurrencia'] : ''),
            'tipo_actoadmin' => (isset($datos['tipo_acto']) ? $datos['tipo_acto'] : ''),
            'actoadmin' => (isset($datos['acto_adm']) ? $datos['acto_adm'] : ''),
            'factoadmin' => (isset($datos['fecha_acto_adm']) ? $datos['fecha_acto_adm'] : ''),
            'observacion' => (isset($datos['observacion']) ? $datos['observacion'] : ''),
            'estado' => $estado_registro,
            'registro' => $fecha_registro);

        $registro_descripcion_cp = $this->registrarDescripcionCP($parametros_descripcion_cp);

        if ($registro_descripcion_cp == true) {
            $registroD[0] = "GUARDAR";
            $registroD[1] = $parametros_descripcion_cp['cedula'] . '|' . $parametros_descripcion_cp['nit_entidad'] . '|' . $parametros_descripcion_cp['nit_previsora']; //
            $registroD[2] = "CUOTAS_PARTES";
            $registroD[3] = $parametros_descripcion_cp['valor_mesada'] . '|' . $parametros_descripcion_cp['valor_cuota'] . '|' . $parametros_descripcion_cp['porcen_cuota']; //
            $registroD[4] = time();
            $registroD[5] = "Registra datos cuota parte pactada para el pensionado con ";
            $registroD[5] .= " identificacion =" . $parametros_descripcion_cp['cedula'];
            $this->log_us->log_usuario($registroD, $this->configuracion);

            echo "<script type=\"text/javascript\">" .
            "alert('Datos Registrados');" .
            "</script> ";

            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = "pagina=formularioConcurrencia";
            $variable .= "&opcion=";
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
        } else {
            echo "<script type=\"text/javascript\">" .
            "alert('Datos de Concurrencia No Registrados Correctamente. Puede deberse a que el registro ya existe');" .
            "</script> ";

            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = "pagina=formularioConcurrencia";
            $variable .= "&opcion=";
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
        }
    }

    function actualizarConcurrencia($datos) {

        $fecha_registro = date('d/m/Y');
        $estado_registro = 1;

        foreach ($datos as $key => $value) {

            if ($datos[$key] == "") {
                echo "<script type=\"text/javascript\">" .
                "alert('Formulario NO diligenciado correctamente');" .
                "</script> ";
                $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                $variable = 'pagina=formularioConcurrencia';
                $variable.='&opcion=';
                $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                exit;
            }
        }

        if ($datos['porc_aceptado'] == 0) {
            echo "<script type=\"text/javascript\">" .
            "alert('Valor de Porcentaje NO válido');" .
            "</script> ";
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = 'pagina=formularioConcurrencia';
            $variable.='&opcion=';
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
            exit;
        }
        /*
          if (!preg_match("^\d*[0](|.\d*[0-9]|)*$^", $datos['porc_aceptado'])) {
          echo "<script type=\"text/javascript\">" .
          "alert('Formulario NO diligenciado correctamente. Formato porcentaje erróneo');" .
          "</script> ";
          $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
          $variable = 'pagina=formularioConcurrencia';
          $variable.='&opcion=';
          $variable = $this->cripto->codificar_url($variable, $this->configuracion);
          echo "<script>location.replace('" . $pagina . $variable . "')</script>";
          exit;
          }

          if (!preg_match("/([0-9]{2})\/([0-9]{2})\/([0-9]{4})/", $datos['fecha_concurrencia'])) {
          echo "<script type=\"text/javascript\">" .
          "alert('Formato fecha concurrencia diligenciado incorrectamente');" .
          "</script> ";
          $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
          $variable = 'pagina=formularioConcurrencia';
          $variable.='&opcion=';
          $variable = $this->cripto->codificar_url($variable, $this->configuracion);
          echo "<script>location.replace(' " . $pagina . $variable . "')</script>";
          exit;
          }

          if (!preg_match("/([0-9]{2})\/([0-9]{2})\/([0-9]{4})/", $datos['fecha_res_pension'])) {
          echo "<script type=\"text/javascript\">" .
          "alert('Formato fecha resolución pensión diligenciado incorrectamente');" .
          "</script> ";
          $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
          $variable = 'pagina=formularioConcurrencia';
          $variable.='&opcion=';
          $variable = $this->cripto->codificar_url($variable, $this->configuracion);
          echo "<script>location.replace(' " . $pagina . $variable . "')</script>";
          exit;
          }

          if (!preg_match("/([0-9]{2})\/([0-9]{2})\/([0-9]{4})/", $datos['fecha_pension'])) {
          echo "<script type=\"text/javascript\">" .
          "alert('Formato fecha pensión diligenciado incorrectamente');" .
          "</script> ";
          $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
          $variable = 'pagina=formularioConcurrencia';
          $variable.='&opcion=';
          $variable = $this->cripto->codificar_url($variable, $this->configuracion);
          echo "<script>location.replace(' " . $pagina . $variable . "')</script>";
          exit;
          }

          if (!preg_match("/([0-9]{2})\/([0-9]{2})\/([0-9]{4})/", $datos['fecha_acto_adm'])) {
          echo "<script type=\"text/javascript\">" .
          "alert('Formato fecha acto administrativo diligenciado incorrectamente');" .
          "</script> ";
          $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
          $variable = 'pagina=formularioConcurrencia';
          $variable.='&opcion=';
          $variable = $this->cripto->codificar_url($variable, $this->configuracion);
          echo "<script>location.replace(' " . $pagina . $variable . "')</script>";
          exit;
          } */

        $parametros_descripcion_cp = array(
            'cedula' => (isset($datos['cedula']) ? $datos['cedula'] : ''),
            'nit_entidad' => (isset($datos['entidad_empleadora']) ? $datos['entidad_empleadora'] : ''),
            'nit_previsora' => (isset($datos['entidad_previsora']) ? $datos['entidad_previsora'] : ''),
            'resolucion_pension' => (isset($datos['resolucion_pension']) ? $datos['resolucion_pension'] : ''),
            'fecha_res_pension' => (isset($datos['fecha_res_pension']) ? $datos['fecha_res_pension'] : ''),
            'fecha_pension' => (isset($datos['fecha_pension']) ? $datos['fecha_pension'] : ''),
            'valor_mesada' => (isset($datos['mesada']) ? $datos['mesada'] : ''),
            'valor_cuota' => (isset($datos['cp_aceptada']) ? $datos['cp_aceptada'] : ''),
            'porcen_cuota' => (isset($datos['porc_aceptado']) ? $datos['porc_aceptado'] : ''),
            'fecha_concurrencia' => (isset($datos['fecha_concurrencia']) ? $datos['fecha_concurrencia'] : ''),
            'tipo_actoadmin' => (isset($datos['tipo_acto']) ? $datos['tipo_acto'] : ''),
            'actoadmin' => (isset($datos['acto_adm']) ? $datos['acto_adm'] : ''),
            'factoadmin' => (isset($datos['fecha_acto_adm']) ? $datos['fecha_acto_adm'] : ''),
             'observacion' => (isset($datos['observacion']) ? $datos['observacion'] : ''),
            'estado' => $estado_registro,
            'registro' => $fecha_registro);

        $registro_descripcion_cp = $this->actualizarDescripcionCP($parametros_descripcion_cp);

        if ($registro_descripcion_cp == true) {
            $registroD[0] = "GUARDAR";
            $registroD[1] = $parametros_descripcion_cp['cedula'] . '|' . $parametros_descripcion_cp['nit_entidad'] . '|' . $parametros_descripcion_cp['nit_previsora']; //
            $registroD[2] = "CUOTAS_PARTES";
            $registroD[3] = $parametros_descripcion_cp['valor_mesada'] . '|' . $parametros_descripcion_cp['valor_cuota'] . '|' . $parametros_descripcion_cp['porcen_cuota']; //
            $registroD[4] = time();
            $registroD[5] = "Registra datos cuota parte pactada para el pensionado con ";
            $registroD[5] .= " identificacion =" . $parametros_descripcion_cp['cedula'];
            $this->log_us->log_usuario($registroD, $this->configuracion);

            echo "<script type=\"text/javascript\">" .
            "alert('Datos Actualizados');" .
            "</script> ";

            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = "pagina=formularioConcurrencia";
            $variable .= "&opcion=";
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
        } else {
            echo "<script type=\"text/javascript\">" .
            "alert('Datos de Concurrencia No Actualizados Correctamente. ');" .
            "</script> ";

            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = "pagina=formularioConcurrencia";
            $variable .= "&opcion=";
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
        }
    }

}

?>