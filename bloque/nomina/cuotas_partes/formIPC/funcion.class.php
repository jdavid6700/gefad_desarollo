
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
  | 09/12/2013 | Violet Sosa             | 0.0.0.4     |                                 |
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

class funciones_formIPC extends funcionGeneral {

    function __construct($configuracion, $sql) {

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

        $this->html_formIPC = new html_formIPC($configuracion);
    }

    function mostrarFormulario() {

        $datos_ipc = $this->ConsultarHistorico();

        if (is_array($datos_ipc)) {
            $anio_fin = $datos_ipc[0]['ipc_fecha'];
        } else {
            $anio_fin = 1970;
        }

        $this->html_formIPC->formularioIPC($anio_fin);
    }

    function modificarIPC($modificar_ipc) {

        $datos_ipc = $this->ConsultarHistorico();

        if (is_array($datos_ipc)) {
            $anio_fin = $datos_ipc[0]['ipc_fecha'];
        } else {
            $anio_fin = 1970;
        }

        $this->html_formIPC->formularioModificar_IPC($anio_fin, $modificar_ipc);
    }

    function ConsultarHistorico() {
        $parametros = "";
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_indice, "Consultar", $parametros);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_indice, $cadena_sql, "busqueda");
        return $datos;
    }

    function ConsultarIndice() {
        $parametros = "";
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_indice, "Consultar", $parametros);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_indice, $cadena_sql, "busqueda");
        $this->html_formIPC->tablaIPC($datos);
    }

    function procesarFormulario($datos) {

        $estado = 1;
        $fecha_registro = date('d/m/Y');
        $sumas_fijas = 0;

        $indice = (int) ($datos['indice_Ipc']);

        foreach ($datos as $key => $value) {

            if ($datos[$key] == "") {
                echo "<script type=\"text/javascript\">" .
                "alert('Formulario NO diligenciado correctamente');" .
                "</script> ";
                $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                $variable = 'pagina=formularioIPC';
                $variable.='&opcion=';
                $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                exit;
            }
        }

        if ($datos['indice_Ipc'] == 0) {
            echo "<script type=\"text/javascript\">" .
            "alert('Valor de Índice NO válido');" .
            "</script> ";
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = 'pagina=formularioIPC';
            $variable.='&opcion=';
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
            exit;
        }


        $long_ipc = strlen($datos['indice_Ipc']);

        if ($long_ipc > 11) {
            echo "<script type=\"text/javascript\">" .
            "alert('Formulario NO diligenciado correctamente. Formato índice erróneo');" .
            "</script> ";
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = 'pagina=formularioIPC';
            $variable.='&opcion=';
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
            exit;
        }



        if (!preg_match("^[0]{1}(\.[0-9]{1,9})?$^", $datos['indice_Ipc'])) {
            echo "<script type=\"text/javascript\">" .
            "alert('Formulario NO diligenciado correctamente. Formato índice erróneo');" .
            "</script> ";
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = 'pagina=formularioIPC';
            $variable.='&opcion=';
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
            exit;
        }



        $parametros = "";
        $anio = $datos['año_registrar'];

        if (($anio >= 1976 && $anio <= 1988)) {
            $sumas_fijas = $datos['sum_fj'];
        } else {
            if (isset($datos['sum_fj'])) {
                echo "<script type=\"text/javascript\">" .
                "alert('El valor de sumas fijas fue ingresado en un intervalo no válido.');" .
                "</script> ";
                $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                $variable = "pagina=formularioIPC";
                $variable .= "&opcion=";
                $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                exit;
            }
        }

        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_indice, "VeriAnio", $parametros);
        $verificacion = $this->ejecutarSQL($this->configuracion, $this->acceso_indice, $cadena_sql, "busqueda");

        if (is_array($verificacion)) {
            foreach ($verificacion as $key => $value) {
                $Ani_ = $verificacion[$key]['ipc_fecha'];

                if ($anio == $Ani_) {

                    echo "<script type=\"text/javascript\">" .
                    "alert('El año ya registra indice.');" .
                    "</script> ";
                    $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                    $variable = "pagina=formularioIPC";
                    $variable .= "&opcion=";
                    $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                    echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                    exit;
                }
            }
        }

        $parametros2 = array(
            'Fecha' => (isset($datos['año_registrar']) ? $datos['año_registrar'] : ''),
            'Indice_IPC' => (isset($datos['indice_Ipc']) ? $datos['indice_Ipc'] : ''),
            'Suma_fijas' => $sumas_fijas,
            'estado_registro' => $estado,
            'fecha_registro' => $fecha_registro);

        $cadena_sql2 = $this->sql->cadena_sql($this->configuracion, $this->acceso_indice, "insertarIPC", $parametros2);
        $datos_registrados = $this->ejecutarSQL($this->configuracion, $this->acceso_indice, $cadena_sql2, "registrar");

        if ($datos_registrados == true) {

            $registro[0] = "GUARDAR";
            $registro[1] = $parametros2['Fecha'] . '|' . $parametros2['Indice_IPC'] . '|' . $parametros2['Suma_fijas']; //
            $registro[2] = "CUOTAS_PARTES_IPC";
            $registro[3] = $parametros2['Fecha'] . '|' . $parametros2['Indice_IPC'] . '|' . $parametros2['Suma_fijas']; //
            $registro[4] = time();
            $registro[5] = "Registra datos básicos indice IPC para el ";
            $registro[5] .= " Periodo =" . $parametros2['Fecha'];
            $this->log_us->log_usuario($registro, $this->configuracion);

            echo "<script type=\"text/javascript\">" .
            "alert('Los datos se registraron correctamente');" .
            "</script> ";

            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = "pagina=formularioIPC";
            $variable .= "&opcion=";
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
        } else {
            echo "<script type=\"text/javascript\">" .
            "alert('Los datos NO se registraron correctamente');" .
            "</script> ";
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = "pagina=formularioIPC";
            $variable .= "&opcion=";
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
            exit;
        }
    }

    function procesarFormulario_Modificar($datos) {

        $estado = 1;
        $fecha_registro = date('d/m/Y');
        $sumas_fijas = 0;

        $indice = (int) ($datos['indice_Ipc']);

        foreach ($datos as $key => $value) {

            if ($datos[$key] == "") {
                echo "<script type=\"text/javascript\">" .
                "alert('Formulario NO diligenciado correctamente');" .
                "</script> ";
                $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                $variable = 'pagina=formularioIPC';
                $variable.='&opcion=';
                $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                exit;
            }
        }

        if ($datos['indice_Ipc'] == 0) {
            echo "<script type=\"text/javascript\">" .
            "alert('Valor de Índice NO válido');" .
            "</script> ";
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = 'pagina=formularioIPC';
            $variable.='&opcion=';
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
            exit;
        }


        $long_ipc = strlen($datos['indice_Ipc']);

        if ($long_ipc > 11) {
            echo "<script type=\"text/javascript\">" .
            "alert('Formulario NO diligenciado correctamente. Formato índice erróneo');" .
            "</script> ";
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = 'pagina=formularioIPC';
            $variable.='&opcion=';
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
            exit;
        }



        if (!preg_match("^[0]{1}(\.[0-9]{1,9})?$^", $datos['indice_Ipc'])) {
            echo "<script type=\"text/javascript\">" .
            "alert('Formulario NO diligenciado correctamente. Formato índice erróneo');" .
            "</script> ";
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = 'pagina=formularioIPC';
            $variable.='&opcion=';
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
            exit;
        }

        $parametros = "";
        $anio = $datos['año_registrar'];

        if (($anio >= 1976 && $anio <= 1988)) {
            $sumas_fijas = $datos['sum_fj'];
        } else {
            if (isset($datos['sum_fj'])) {
                echo "<script type=\"text/javascript\">" .
                "alert('El valor de sumas fijas fue ingresado en un intervalo no válido.');" .
                "</script> ";
                $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                $variable = "pagina=formularioIPC";
                $variable .= "&opcion=";
                $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                exit;
            }
        }

        $parametros2 = array(
            'Fecha' => (isset($datos['año_registrar']) ? $datos['año_registrar'] : ''),
            'Indice_IPC' => (isset($datos['indice_Ipc']) ? $datos['indice_Ipc'] : ''),
            'Suma_fijas' => $sumas_fijas,
            'estado_registro' => $estado,
            'fecha_registro' => $fecha_registro,
            'serial' => $datos['serial']);

        $cadena_sql2 = $this->sql->cadena_sql($this->configuracion, $this->acceso_indice, "actualizarIPC", $parametros2);
        $datos_actualizados = $this->ejecutarSQL($this->configuracion, $this->acceso_indice, $cadena_sql2, "registrar");

        if ($datos_actualizados == true) {

            $registro[0] = "ACTUALIZAR";
            $registro[1] = $parametros2['Fecha'] . '|' . $parametros2['Indice_IPC'] . '|' . $parametros2['Suma_fijas']; //
            $registro[2] = "CUOTAS_PARTES_IPC";
            $registro[3] = $parametros2['Fecha'] . '|' . $parametros2['Indice_IPC'] . '|' . $parametros2['Suma_fijas']; //
            $registro[4] = time();
            $registro[5] = "Modifica datos básicos indice IPC para el ";
            $registro[5] .= " Periodo =" . $parametros2['Fecha'];
            $this->log_us->log_usuario($registro, $this->configuracion);

            echo "<script type=\"text/javascript\">" .
            "alert('Los datos se actualizaron correctamente');" .
            "</script> ";

            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = "pagina=formularioIPC";
            $variable .= "&opcion=";
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
        } else {
            echo "<script type=\"text/javascript\">" .
            "alert('Los datos NO se actualizaron correctamente');" .
            "</script> ";
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = "pagina=formularioIPC";
            $variable .= "&opcion=";
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
            exit;
        }
    }

}

?>