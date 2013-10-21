
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
        $this->tema = $tema;
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
        $this->html_formDTF->formularioDTF();
    }

    function ConsultarIndice() {
        $parametros = "";
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_indice, "Consultar", $parametros);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_indice, $cadena_sql, "busqueda");
        $this->html_formDTF->tablaDTF($datos);
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
                $variable = 'pagina=reportesCuotaFormularis';
                $variable.='&opcion=';
                $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                exit;
            }
        }

        foreach ($datos as $key => $value) {
            if (!ereg("^[a-zA-Z0-9.-/]{1,20}$", $datos[$key])) {
                echo "<script type=\"text/javascript\">" .
                "alert('Formulario NO diligenciado correctamente');" .
                "</script> ";
                $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                $variable = 'pagina=reportesCuotaFormularis';
                $variable.='&opcion=';
                $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                exit;
            }
        }

        if (count($datos) == 3) {

            $datos['indice_dtf'] = 0.12;

            for ($i = 1; $i <= 4; $i++) {
                $datos['tri_mestre'] = $i;

                if (!preg_match('((^(10|12|0?[13578])([/])(3[01]|[12][0-9]|0?[1-9])([/])((1[8-9]\d{2})|([2-9]\d{3}))$)|(^(11|0?[469])([/])(30|[12][0-9]|0?[1-9])([/])((1[8-9]\d{2})|([2-9]\d{3}))$)|(^(0?2)([/])(2[0-8]|1[0-9]|0?[1-9])([/])((1[8-9]\d{2})|([2-9]\d{3}))$)|(^(0?2)([/])(29)([/])([2468][048]00)$)|(^(0?2)([/])(29)([/])([3579][26]00)$)|(^(0?2)([/])(29)([/])([1][89][0][48])$)|(^(0?2)([/])(29)([/])([2-9][0-9][0][48])$)|(^(0?2)([/])(29)([/])([1][89][2468][048])$)|(^(0?2)([/])(29)([/])([2-9][0-9][2468][048])$)|(^(0?2)([/])(29)([/])([1][89][13579][26])$)|(^(0?2)([/])(29)([/])([2-9][0-9][13579][26])$))', $datos['fec_reso'])) {
                    echo "<script type=\"text/javascript\">" .
                    "alert('Formato fecha de Resolucion no valido.');" .
                    "</script> ";
                    $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                    $variable = 'pagina=formularioDTF';
                    $variable.='&opcion=';
                    $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                    echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                    exit;
                }


                $parametros = "";
                $parametros = array(
                    'Anio_registrado' => (isset($datos['año_registrar']) ? $datos['año_registrar'] : ''),
                    'Trimestre' => (isset($datos['tri_mestre']) ? $datos['tri_mestre'] : ''),
                    'Interes_DTF' => (isset($datos['indice_dtf']) ? $datos['indice_dtf'] : ''),
                    'Numero_resolucion' => (isset($datos['n_resolucion']) ? $datos['n_resolucion'] : ''),
                    'Fecha_resolucion' => (isset($datos['fec_reso']) ? $datos['fec_reso'] : ''),
                    'estado_registro' => $estado,
                    'fecha_registro' => $fecha_registro);

                $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_indice, "periodo_ante", $parametros);
                $verificacion = $this->ejecutarSQL($this->configuracion, $this->acceso_indice, $cadena_sql, "insertar");
            }
            if ($verificacion == true) {

                $registro[0] = "GUARDAR";
                $registro[1] = $parametros['Anio_registrado'] . '|' . $parametros['Trimestre'] . '|' . $parametros['Interes_DTF']; //
                $registro[2] = "CUOTAS_PARTES_DTF";
                $registro[3] = $parametros['Anio_registrado'] . '|' . $parametros['Trimestre'] . '|' . $parametros['Interes_DTF']; //
                $registro[4] = time();
                $registro[5] = "Registra datos básicos indice DTF para el ";
                $registro[5] .= " Periodo =" . $parametros['Anio_registrado'] . '-' . $parametros['Trimestre'];
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
                "alert('Datos NO Registrados, puede deberse aque el registro ya existe.');" .
                "</script> ";

                $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                $variable = "pagina=formularioDTF";
                $variable .= "&opcion=";
                $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                exit;
            }
        } elseif (count($datos) == 2) {

            $datos['indice_dtf'] = 0.12;

            if (!preg_match('((^(10|12|0?[13578])([/])(3[01]|[12][0-9]|0?[1-9])([/])((1[8-9]\d{2})|([2-9]\d{3}))$)|(^(11|0?[469])([/])(30|[12][0-9]|0?[1-9])([/])((1[8-9]\d{2})|([2-9]\d{3}))$)|(^(0?2)([/])(2[0-8]|1[0-9]|0?[1-9])([/])((1[8-9]\d{2})|([2-9]\d{3}))$)|(^(0?2)([/])(29)([/])([2468][048]00)$)|(^(0?2)([/])(29)([/])([3579][26]00)$)|(^(0?2)([/])(29)([/])([1][89][0][48])$)|(^(0?2)([/])(29)([/])([2-9][0-9][0][48])$)|(^(0?2)([/])(29)([/])([1][89][2468][048])$)|(^(0?2)([/])(29)([/])([2-9][0-9][2468][048])$)|(^(0?2)([/])(29)([/])([1][89][13579][26])$)|(^(0?2)([/])(29)([/])([2-9][0-9][13579][26])$))', $datos['fec_reso'])) {
                echo "<script type=\"text/javascript\">" .
                "alert('Formato fecha de Resolucion no valido.');" .
                "</script> ";
                $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                $variable = 'pagina=formularioDTF';
                $variable.='&opcion=';
                $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                exit;
            }

            $parametros = "";
            $parametros = array(
                'Anio_registrado' => (isset($datos['año_registrar']) ? $datos['año_registrar'] : ''),
                'Trimestre' => (isset($datos['tri_mestre']) ? $datos['tri_mestre'] : ''),
                'Interes_DTF' => (isset($datos['indice_dtf']) ? $datos['indice_dtf'] : ''),
                'Numero_resolucion' => (isset($datos['n_resolucion']) ? $datos['n_resolucion'] : ''),
                'Fecha_resolucion' => (isset($datos['fec_reso']) ? $datos['fec_reso'] : ''),
                'estado_registro' => $estado,
                'fecha_registro' => $fecha_registro);

            $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_indice, "periodo_ante", $parametros);
            $verificacion = $this->ejecutarSQL($this->configuracion, $this->acceso_indice, $cadena_sql, "insertar");

            if ($verificacion == true) {

                $registro[0] = "GUARDAR";
                $registro[1] = $parametros['Anio_registrado'] . '|' . $parametros['Trimestre'] . '|' . $parametros['Interes_DTF']; //
                $registro[2] = "CUOTAS_PARTES_DTF";
                $registro[3] = $parametros['Anio_registrado'] . '|' . $parametros['Trimestre'] . '|' . $parametros['Interes_DTF'] . $parametros['Fecha_resolucion']; //
                $registro[4] = time();
                $registro[5] = "Registra datos básicos indice DTF para el ";
                $registro[5] .= " Periodo =" . $parametros['Anio_registrado'] . '-' . $parametros['Trimestre'];
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
                "alert('Datos NO Registrados, puede deberse aque el registro ya existe.');" .
                "</script> ";

                $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                $variable = "pagina=formularioDTF";
                $variable .= "&opcion=";
                $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                exit;
            }
        } else {


            if (!preg_match('((^(10|12|0?[13578])([/])(3[01]|[12][0-9]|0?[1-9])([/])((1[8-9]\d{2})|([2-9]\d{3}))$)|(^(11|0?[469])([/])(30|[12][0-9]|0?[1-9])([/])((1[8-9]\d{2})|([2-9]\d{3}))$)|(^(0?2)([/])(2[0-8]|1[0-9]|0?[1-9])([/])((1[8-9]\d{2})|([2-9]\d{3}))$)|(^(0?2)([/])(29)([/])([2468][048]00)$)|(^(0?2)([/])(29)([/])([3579][26]00)$)|(^(0?2)([/])(29)([/])([1][89][0][48])$)|(^(0?2)([/])(29)([/])([2-9][0-9][0][48])$)|(^(0?2)([/])(29)([/])([1][89][2468][048])$)|(^(0?2)([/])(29)([/])([2-9][0-9][2468][048])$)|(^(0?2)([/])(29)([/])([1][89][13579][26])$)|(^(0?2)([/])(29)([/])([2-9][0-9][13579][26])$))', $datos['fec_reso'])) {
                echo "<script type=\"text/javascript\">" .
                "alert('Formato fecha de Resolucion no valido.');" .
                "</script> ";
                $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                $variable = 'pagina=formularioDTF';
                $variable.='&opcion=';
                $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                exit;
            }

            if (!preg_match('((^(10|12|0?[13578])([/])(3[01]|[12][0-9]|0?[1-9])([/])((1[8-9]\d{2})|([2-9]\d{3}))$)|(^(11|0?[469])([/])(30|[12][0-9]|0?[1-9])([/])((1[8-9]\d{2})|([2-9]\d{3}))$)|(^(0?2)([/])(2[0-8]|1[0-9]|0?[1-9])([/])((1[8-9]\d{2})|([2-9]\d{3}))$)|(^(0?2)([/])(29)([/])([2468][048]00)$)|(^(0?2)([/])(29)([/])([3579][26]00)$)|(^(0?2)([/])(29)([/])([1][89][0][48])$)|(^(0?2)([/])(29)([/])([2-9][0-9][0][48])$)|(^(0?2)([/])(29)([/])([1][89][2468][048])$)|(^(0?2)([/])(29)([/])([2-9][0-9][2468][048])$)|(^(0?2)([/])(29)([/])([1][89][13579][26])$)|(^(0?2)([/])(29)([/])([2-9][0-9][13579][26])$))', $datos['fecvig_desde'])) {

                echo "<script type=\"text/javascript\">" .
                "alert('Formato fecha de \"Vigencia Inicio\" no valido.');" .
                "</script> ";
                $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                $variable = 'pagina=formularioDTF';
                $variable.='&opcion=';
                $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                exit;
            }

            if (!preg_match('((^(10|12|0?[13578])([/])(3[01]|[12][0-9]|0?[1-9])([/])((1[8-9]\d{2})|([2-9]\d{3}))$)|(^(11|0?[469])([/])(30|[12][0-9]|0?[1-9])([/])((1[8-9]\d{2})|([2-9]\d{3}))$)|(^(0?2)([/])(2[0-8]|1[0-9]|0?[1-9])([/])((1[8-9]\d{2})|([2-9]\d{3}))$)|(^(0?2)([/])(29)([/])([2468][048]00)$)|(^(0?2)([/])(29)([/])([3579][26]00)$)|(^(0?2)([/])(29)([/])([1][89][0][48])$)|(^(0?2)([/])(29)([/])([2-9][0-9][0][48])$)|(^(0?2)([/])(29)([/])([1][89][2468][048])$)|(^(0?2)([/])(29)([/])([2-9][0-9][2468][048])$)|(^(0?2)([/])(29)([/])([1][89][13579][26])$)|(^(0?2)([/])(29)([/])([2-9][0-9][13579][26])$))', $datos['fecvig_hasta'])) {

                echo "<script type=\"text/javascript\">" .
                "alert('Formato fecha de \"Vigencia Hasta\"  no valido.');" .
                "</script> ";
                $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                $variable = 'pagina=formularioDTF';
                $variable.='&opcion=';
                $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                exit;
            }

            if ($datos['año_registrar'] == "Seleccione Año") {

                echo "<script type=\"text/javascript\">" .
                "alert('Campo no valido \"Año a Registrar\"');" .
                "</script> ";
                $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                $variable = 'pagina=formularioDTF';
                $variable.='&opcion=';
                $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                exit;
            }

            if ($datos['tri_mestre'] == "Seleccione Trimestre") {

                echo "<script type=\"text/javascript\">" .
                "alert('Campo no valido \"Trimestre a Registrar\"');" .
                "</script> ";
                $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                $variable = 'pagina=formularioDTF';
                $variable.='&opcion=';
                $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                exit;
            }

            $parametros = "";
            $periodo = $datos['año_registrar'] . "-" . $datos['tri_mestre'];

            $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_indice, "Veriperiodo", $parametros);
            $verificacion = $this->ejecutarSQL($this->configuracion, $this->acceso_indice, $cadena_sql, "busqueda");

            foreach ($verificacion as $key => $value) {
                $per = $verificacion[$key]['periodo'];
                if ($periodo == $per) {
                    echo "<script type=\"text/javascript\">" .
                    "alert('El periodo  ya registra indice.');" .
                    "</script> ";
                    $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                    $variable = "pagina=formularioDTF";
                    $variable .= "&opcion=";
                    $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                    echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                    exit;
                }
            }

            $parametros = array(
                'Anio_registrado' => (isset($datos['año_registrar']) ? $datos['año_registrar'] : ''),
                'Trimestre' => (isset($datos['tri_mestre']) ? $datos['tri_mestre'] : ''),
                'Numero_resolucion' => (isset($datos['n_resolucion']) ? $datos['n_resolucion'] : ''),
                'Fecha_resolucion' => (isset($datos['fec_reso']) ? $datos['fec_reso'] : ''),
                'Fecha_vigencia_inicio' => (isset($datos['fecvig_desde']) ? $datos['fecvig_desde'] : ''),
                'Fecha_vigencia_final' => (isset($datos['fecvig_hasta']) ? $datos['fecvig_hasta'] : ''),
                'Interes_DTF' => (isset($datos['indice_dtf']) ? $datos['indice_dtf'] : ''),);

            $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_indice, "insertarDTF", $parametros);
            $datos_registrados = $this->ejecutarSQL($this->configuracion, $this->acceso_indice, $cadena_sql, "insertar");

            if ($datos_registrados == true) {
                $registro[0] = "GUARDAR";
                $registro[1] = $parametros['Anio_registrado'] . '|' . $parametros['Trimestre'] . '|' . $parametros['Interes_DTF']; //
                $registro[2] = "CUOTAS_PARTES_DTF";
                $registro[3] = $parametros['Anio_registrado'] . '|' . $parametros['Fecha_vigencia_inicio'] . '|' . $parametros['Fecha_vigencia_final']; //
                $registro[4] = time();
                $registro[5] = "Registra datos básicos indice DTF para el ";
                $registro[5] .= " Periodo =" . $parametros['Anio_registrado'] . '-' . $parametros['Trimestre'];
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

?>