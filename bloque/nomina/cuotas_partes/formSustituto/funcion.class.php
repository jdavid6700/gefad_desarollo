
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
  | 01/05/2014 | Violet Sosa             | 0.0.0.1     |                                 |
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

class funciones_formSustituto extends funcionGeneral {

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

        $this->html_formSustituto = new html_formSustituto($configuracion);
    }

    function inicio() {
        $this->html_formSustituto->form_valor();
    }

    function mostrarFormulario($cedula) {

        $parametros = array(
            'cedula' => $cedula);

        $datos_sustituto = $this->consultarSustituto($parametros);
        $datos_pensionado = $this->consultarPensionado($parametros);
        $datos_pensionado_pg = $this->consultarPensionadoPG($parametros);

        if (is_array($datos_sustituto)) {
            echo "<script type=\"text/javascript\">" .
            "alert('Ya existe un sustituto registrado para el pensionado con cédula " . $cedula . "');" .
            "</script> ";
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = 'pagina=formulariosustituto';
            $variable.='&opcion=';
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
            exit;
        } else {
            if (is_array($datos_pensionado)) {
                $this->html_formSustituto->formularioSustituto($cedula, $datos_pensionado);
            } else {
                if (is_array($datos_pensionado_pg)) {
                    $this->html_formSustituto->formularioSustituto($cedula, $datos_pensionado_pg);
                }
            }
        }
    }

    function registrarSustituto($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "insertarSustituto", $parametros);
        $datos_DescripcionCP = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "insertar");
        return $datos_DescripcionCP;
    }

    function consultarSustituto($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "reporteSustituto", $parametros);
        $datos_registro = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos_registro;
    }

    function consultarPensionado($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_Oracle, "datos_pensionado", $parametros);
        $datos_registro = $this->ejecutarSQL($this->configuracion, $this->acceso_Oracle, $cadena_sql, "busqueda");
        return $datos_registro;
    }

    function consultarPensionadoPG($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "datos_pensionado_pg", $parametros);
        $datos_registro = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos_registro;
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

        if (!preg_match("/([0-9]{2})\/([0-9]{2})\/([0-9]{4})/", $datos['fecha_nacsustituto'])) {
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

        if (!preg_match("/([0-9]{2})\/([0-9]{2})\/([0-9]{4})/", $datos['fecha_muerte'])) {
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

        if (!preg_match("/([0-9]{2})\/([0-9]{2})\/([0-9]{4})/", $datos['fecha_res_sustitucion'])) {
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

        $parametros = array(
            'cedula_pen' => (isset($datos['cedula_pen']) ? $datos['cedula_pen'] : ''),
            'cedula_sustituto' => (isset($datos['cedula_sustituto']) ? $datos['cedula_sustituto'] : ''),
            'fecha_nacsustituto' => (isset($datos['fecha_nacsustituto']) ? $datos['fecha_nacsustituto'] : ''),
            'fecha_muerte' => (isset($datos['fecha_muerte']) ? $datos['fecha_muerte'] : ''),
            'fecha_res_sustitucion' => (isset($datos['cedula_sustituto']) ? $datos['cedula_sustituto'] : ''),
            'res_sustitucion' => (isset($datos['resolucion_sustitucion']) ? $datos['resolucion_sustitucion'] : ''),
            'estado' => $estado_registro,
            'registro' => $fecha_registro);

        $registro_sustituto = $this->registrarSustituto($parametros);

        if ($registro_sustituto == true) {
            $registroD[0] = "GUARDAR";
            $registroD[1] = $parametros['cedula_pen'] . '|' . $parametros['cedula_sustituto']; //
            $registroD[2] = "CUOTAS_PARTES";
            $registroD[3] = $parametros['fecha_muerte'] . '|' . $parametros['res_sustitucion'] . '|' . $parametros['fecha_res_sustitucion']; //
            $registroD[4] = time();
            $registroD[5] = "Registra datos sustituto del pensionado con cedula ";
            $registroD[5] .= " identificacion =" . $parametros['cedula_pen'];
            $this->log_us->log_usuario($registroD, $this->configuracion);

            echo "<script type=\"text/javascript\">" .
            "alert('Datos Registrados');" .
            "</script> ";

            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = "pagina=reportesCuotas";
            $variable .= "&opcion=";
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
        } else {
            echo "<script type=\"text/javascript\">" .
            "alert('Datos de Interrupción No Registrados Correctamente. Puede deberse a que el registro ya existe');" .
            "</script> ";

            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = "pagina=formularioSustituto";
            $variable .= "&opcion=";
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
        }
    }

}

?>