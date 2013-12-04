
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
  | 11/10/2013 | Violet Sosa             | 0.0.0.3     |                                 |
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

class funciones_formDTF extends funcionGeneral {

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

        //Conexión a Postgres -
        $this->acceso_indice = $this->conectarDB($configuracion, "cuotas_partes");
        //Datos de sesion

        $this->usuario = $this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion = $this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");

        $this->configuracion = $configuracion;

        $this->html_formDTF = new html_formDTF($configuracion);
    }

    function mostrarFormulario() {
        $parametros = "";
        $indices = $this->ConsultarIndice($parametros);

        if ($indices == true) {

            foreach ($indices as $key => $value) {
                $rango[$key] = array(
                    'inicio' => date('d/m/Y', strtotime($value['dtf_fe_desde'])),
                    'fin' => date('d/m/Y', strtotime($value['dtf_fe_hasta'])));
            }
        } else {
            $rango[0] = array(
                'inicio' => date('d/m/Y', strtotime('01/01/1940')),
                'fin' => date('d/m/Y', strtotime('01/01/1940')));
        }


        $this->html_formDTF->formularioDTF($rango);
    }

    function mostrarIndice() {
        $parametros = "";
        $indices = $this->ConsultarIndice($parametros);

        $this->html_formDTF->tablaDTF($indices);
    }

    function ConsultarIndice() {
        $parametros = "";
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_indice, "Consultar", $parametros);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_indice, $cadena_sql, "busqueda");
        return $datos;
    }

    function procesarFormulario($datos) {

        $estado = 1;
        $fecha_registro = date('d-m-Y');

        $anio_inicio = date("Y", strtotime(str_replace('/', '-', $datos['fecvig_desde'])));
        $anio_fin = date("Y", strtotime(str_replace('/', '-', $datos['fecvig_hasta'])));

        foreach ($datos as $key => $value) {

            if ($datos[$key] == "") {
                echo "<script type=\"text/javascript\">" .
                "alert('Formulario NO diligenciado correctamente');" .
                "</script> ";
                $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                $variable = "pagina=formularioDTF";
                $variable .= "&opcion=";
                $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                exit;
            }
        }
        /*
          foreach ($datos as $key => $value) {
          if (!ereg("^[a-zA-Z0-9.-/]{1,30}$", $datos[$key])) {
          echo "<script type=\"text/javascript\">" .
          "alert('Formulario NO diligenciado correctamente');" .
          "</script> ";
          $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
          $variable = "pagina=formularioDTF";
          $variable .= "&opcion=";
          $variable = $this->cripto->codificar_url($variable, $this->configuracion);
          echo "<script>location.replace('" . $pagina . $variable . "')</script>";
          exit;
          }
          }


          /* verificar si es menor a 2006 para asignar 0.12 OJO con el segundo semestre */

        if ($anio_fin < 2006) {

            $datos['indice_dtf'] = 0.12;

            $fecha_inicio = '01-01-' . $datos['año_registrar'];
            $fecha_final = '01-12-' . $datos['año_registrar'];

            $parametros = "";
            $parametros = array(
                'Anio_registrado' => $anio_fin,
                'Norma' => (isset($datos['norma']) ? $datos['norma'] : ''),
                'Numero_resolucion' => (isset($datos['n_resolucion']) ? $datos['n_resolucion'] : ''),
                'Fecha_resolucion' => (isset($datos['fec_reso']) ? $datos['fec_reso'] : ''),
                'Fecha_vigencia_inicio' => $fecha_inicio,
                'Fecha_vigencia_final' => $fecha_final,
                'Interes_DTF' => (isset($datos['indice_dtf']) ? $datos['indice_dtf'] : ''),
                'estado_registro' => $estado,
                'fecha_registro' => $fecha_registro);

            $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_indice, "insertarDTF", $parametros);
            $insertar2006 = $this->ejecutarSQL($this->configuracion, $this->acceso_indice, $cadena_sql, "insertar");

            if ($insertar2006 == true) {

                $registro[0] = "GUARDAR";
                $registro[1] = $parametros['Anio_registrado'] . '|' . $parametros['Interes_DTF']; //
                $registro[2] = "CUOTAS_PARTES_DTF";
                $registro[3] = $parametros['Anio_registrado'] . '|' . $parametros['Interes_DTF'] . $parametros['Fecha_resolucion']; //
                $registro[4] = time();
                $registro[5] = "Registra datos básicos indice DTF para el ";
                $registro[5] .= " Periodo =" . $parametros['Anio_registrado'];
                $this->log_us->log_usuario($registro, $this->configuracion);

                echo "<script type=\"text/javascript\">" .
                "alert('Datos Registrados');" .
                "</script> ";

                $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                $variable = "pagina=formularioDTF";
                $variable .= "&opcion=";
                $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                exit;
            } else {
                echo "<script type=\"text/javascript\">" .
                "alert('Datos NO Registrados, puede deberse aque el registro ya existe!!');" .
                "</script> ";

                $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                $variable = "pagina=formularioDTF";
                $variable .= "&opcion=";
                $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                exit;
            }
        } else {

            $parametros = "";
            $parametros = array(
                'Anio_registrado' => $anio_fin,
                'norma' => (isset($datos['norma']) ? $datos['norma'] : ''),
                'Numero_resolucion' => (isset($datos['n_resolucion']) ? $datos['n_resolucion'] : ''),
                'Fecha_resolucion' => (isset($datos['fec_reso']) ? $datos['fec_reso'] : ''),
                'Fecha_vigencia_inicio' => (isset($datos['fecvig_desde']) ? $datos['fecvig_desde'] : ''),
                'Fecha_vigencia_final' => (isset($datos['fecvig_hasta']) ? $datos['fecvig_hasta'] : ''),
                'Interes_DTF' => (isset($datos['indice_dtf']) ? $datos['indice_dtf'] : ''),
                'estado_registro' => $estado,
                'fecha_registro' => $fecha_registro);

            $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_indice, "insertarDTF", $parametros);
            $datos_registrados = $this->ejecutarSQL($this->configuracion, $this->acceso_indice, $cadena_sql, "insertar");

            if ($datos_registrados == true) {
                $registro[0] = "GUARDAR";
                $registro[1] = $parametros['Anio_registrado'] . '|' . $parametros['Interes_DTF']; //
                $registro[2] = "CUOTAS_PARTES_DTF";
                $registro[3] = $parametros['Anio_registrado'] . '|' . $parametros['Fecha_vigencia_inicio'] . '|' . $parametros['Fecha_vigencia_final']; //
                $registro[4] = time();
                $registro[5] = "Registra datos básicos indice DTF para el ";
                $registro[5] .= " Periodo =" . $parametros['Anio_registrado'];
                $this->log_us->log_usuario($registro, $this->configuracion);

                echo "<script type=\"text/javascript\">" .
                "alert('Datos Registrados');" .
                "</script> ";

                $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                $variable = "pagina=formularioDTF";
                $variable .= "&opcion=";
                $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                exit;
            } else {
                echo "<script type=\"text/javascript\">" .
                "alert('Datos No Registrados Correctamente. Puede deberse a que el registro ya existe');" .
                "</script> ";
                $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                $variable = "pagina=formularioDTF";
                $variable .= "&opcion=";
                $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                exit;
            }
        }
    }

}
