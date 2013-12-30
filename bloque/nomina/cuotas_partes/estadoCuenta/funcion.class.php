
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
  | 30/12/2013 | Violet Sosa             | 0.0.0.1     |                                 |
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

class funciones_estadoCuenta extends funcionGeneral {

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

        $this->html_estadoCuenta = new html_estadoCuenta($configuracion);
    }

    //Métodos de Estado de Cuenta

    function entregarSaldo() {

        $saldo_cuenta = 0;

        return $saldo_cuenta;
       
     /*   $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
        $variable = "pagina=formularioRecaudo";
        $variable.="&opcion=consultar";
        $variable.="&saldo_cuenta=" . $saldo_cuenta;
        $variable = $this->cripto->codificar_url($variable, $this->configuracion);
        echo "<script>location.replace('" . $pagina . $variable . "' ) </script>";
        exit;*/
    }

    /*     * ******************************************************************************************************************************************** */

    function consultarRegistros() {

        $parametros = array();
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "consultarPrevisora", $parametros);
        $datos_registro = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");

        $this->html_estadoCuenta->mostrarRegistros($datos_registro);
    }

    function mostrarFormulario() {
        $this->html_estadoCuenta->formularioPrevisora();
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
                $variable = 'pagina=reportesCuotas';
                $variable.='&opcion=';
                $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                exit;
            }
        }

        $parametros = array(
            'nit_previsora' => (isset($datos['nit_previsora']) ? $datos['nit_previsora'] : ''),
            'nombre_previsora' => (isset($datos['nombre_previsora']) ? $datos['nombre_previsora'] : ''),
            'estado' => (isset($datos['estado']) ? $datos['estado'] : ''),
            'observacion' => (isset($datos['observacion']) ? $datos['observacion'] : ''),
            'direccion' => (isset($datos['direccion']) ? $datos['direccion'] : ''),
            'ciudad' => (isset($datos['ciudad']) ? $datos['ciudad'] : ''),
            'departamento' => (isset($datos['departamento']) ? $datos['departamento'] : ''),
            'telefono' => (isset($datos['telefono']) ? $datos['telefono'] : ''),
            'responsable' => (isset($datos['responsable']) ? $datos['responsable'] : ''),
            'cargo' => (isset($datos['cargo']) ? $datos['cargo'] : ''),
            'otro_contacto' => (isset($datos['otro_contacto']) ? $datos['otro_contacto'] : ''),
            'otro_cargo' => (isset($datos['otro_cargo']) ? $datos['otro_cargo'] : ''),
            'correo1' => (isset($datos['txtEmail']) ? $datos['txtEmail'] : ''),
            'correo2' => (isset($datos['txtEmail2']) ? $datos['txtEmail2'] : ''),
            'estado_registro' => ($estado_registro),
            'fecha_registro' => $fecha_registro,);

        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "insertarPrevisora", $parametros);
        $datos_registrados = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "registrar");


        $registro[0] = "GUARDAR";
        $registro[1] = $parametros['nit_previsora'] . '|' . $parametros['nombre_previsora'] . '|' . $parametros['estado']; //
        $registro[2] = "CUOTAS_PARTES_previsora";
        $registro[3] = $parametros['direccion'] . '|' . $parametros['telefono'] . '|' . $parametros['responsable']; //
        $registro[4] = time();
        $registro[5] = "Registra datos básicos entidad previsora con ";
        $registro[5] .= "NIT =" . $parametros['nit_previsora'];
        $this->log_us->log_usuario($registro, $this->configuracion);


        echo "<script type=\"text/javascript\">" .
        "alert('Datos Registrados');" .
        "</script> ";

        $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
        $variable = "pagina=formularioPrevisora";
        $variable .= "&opcion=";
        $variable = $this->cripto->codificar_url($variable, $this->configuracion);
        echo "<script>location.replace('" . $pagina . $variable . "')</script>";
    }

}

?>