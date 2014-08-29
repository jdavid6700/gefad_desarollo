
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
  | 18/05/2013 | Violet Sosa             | 0.0.0.1     |                                 |
  ----------------------------------------------------------------------------------------
  | 02/08/2013 | Violet Sosa             | 0.0.0.2     | Adaptación formulario           |                                |
  ----------------------------------------------------------------------------------------
  | 15/08/2013 | Violet Sosa             | 0.0.0.3     | Adaptación formulario           |
  ----------------------------------------------------------------------------------------
 */

/* --------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
  --------------------------------------------------------------------------------------------------------------------------- */


if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

date_default_timezone_set('America/Bogota');
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/funcionGeneral.class.php");
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/navegacion.class.php");
include_once("html.class.php");

class funciones_formPrevisora extends funcionGeneral {

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

        //Datos de sesion
        $this->usuario = $this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion = $this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");

        $this->configuracion = $configuracion;

        $this->html_formPrevisora = new html_formPrevisora($configuracion);
    }

    function consultarGeografia($parametro) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_oracle, "consultarGeografia", $parametro);
        $datos_geo = $this->ejecutarSQL($this->configuracion, $this->acceso_oracle, $cadena_sql, "busqueda");
        return $datos_geo;
    }

    function consultarDepartamento($parametro) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_oracle, "consultarGeografiaDEP", $parametro);
        $datos_geo = $this->ejecutarSQL($this->configuracion, $this->acceso_oracle, $cadena_sql, "busqueda");
        return $datos_geo;
    }

    function consultarMunicipio($parametro) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_oracle, "consultarGeografiaMUN", $parametro);
        $datos_geo = $this->ejecutarSQL($this->configuracion, $this->acceso_oracle, $cadena_sql, "busqueda");
        return $datos_geo;
    }

    function consultarRegistros() {

        $parametros = array();
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "consultarPrevisora", $parametros);
        $datos_registro = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");

        $this->html_formPrevisora->mostrarRegistros($datos_registro);
    }

    function consultarNITS() {
        $parametros = array();
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "consultarPrevisora", $parametros);
        $datos_registro = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos_registro;
    }

    function modificarRegistro($datos_entidad) {
        $parametros = array();

        $deptoC = $this->consultarDepartamento($parametros);
        $munC = $this->consultarMunicipio($parametros);

        if ($deptoC == true) {
            foreach ($deptoC as $key => $value) {
                $depto[$key] = array('departamento' => $value['DEP_NOMBRE']);
            }
        }

        if ($munC == true) {
            foreach ($munC as $key => $value) {
                $mun[$key] = array(
                    'departamento' => $value['DEP_NOMBRE'],
                    'municipio' => $value['MUN_NOMBRE']);
            }
        }
        $this->html_formPrevisora->modificarPrevisora($depto, $mun, $datos_entidad);
    }

    function mostrarFormulario() {

        $parametros = array();

        $deptoC = $this->consultarDepartamento($parametros);
        $munC = $this->consultarMunicipio($parametros);

        if ($deptoC == true) {
            foreach ($deptoC as $key => $value) {
                $depto[$key] = array('departamento' => $value['DEP_NOMBRE']);
            }
        }

        if ($munC == true) {
            foreach ($munC as $key => $value) {
                $mun[$key] = array(
                    'departamento' => $value['DEP_NOMBRE'],
                    'municipio' => $value['MUN_NOMBRE']);
            }
        }
        $this->html_formPrevisora->formularioPrevisora($depto, $mun);
    }

    function procesarFormulario($datos) {

        $fecha_registro = date('d/m/Y');
        $estado_registro = 1;

        $verificacion_nit = $this->consultarNITS();
        $nit_registro = $datos['nit_previsora'];

        if (is_array($verificacion_nit)) {
            foreach ($verificacion_nit as $key => $value) {
                $Nit = $verificacion_nit[$key]['prev_nit'];

                if ($Nit == $nit_registro) {

                    echo "<script type=\"text/javascript\">" .
                    "alert('El NIT ya se encuentra registrado.');" .
                    "</script> ";
                    $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                    $variable = "pagina=formularioPrevisora";
                    $variable .= "&opcion=";
                    $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                    echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                    exit;
                }
            }
        }

        $parametros = array(
            'nit_previsora' => (isset($datos['nit_previsora']) ? $datos['nit_previsora'] : ''),
            'nombre_previsora' => (isset($datos['nombre_previsora']) ? $datos['nombre_previsora'] : ''),
            'estado' => (isset($datos['estado']) ? $datos['estado'] : ''),
            'observacion' => (isset($datos['observacion']) ? $datos['observacion'] : ''),
            'direccion' => (isset($datos['direccion']) ? $datos['direccion'] : ''),
            'ciudad' => (isset($datos['municipios']) ? $datos['municipios'] : ''),
            'departamento' => (isset($datos['departamentos']) ? $datos['departamentos'] : ''),
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

        if ($datos_registrados == true) {
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
        } else {
            echo "<script type=\"text/javascript\">" .
            "alert('Los datos NO se registraron correctamente');" .
            "</script> ";
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = "pagina=formularioPrevisora";
            $variable .= "&opcion=";
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
            exit;
        }
    }

    function procesarFormularioActualizar($datos) {

        $fecha_registro = date('d/m/Y');
        $estado_registro = 1;

        $parametros = array(
            'serial' => (isset($datos['serial']) ? $datos['serial'] : ''),
            'nit_previsora' => (isset($datos['nit_previsora']) ? $datos['nit_previsora'] : ''),
            'nombre_previsora' => (isset($datos['nombre_previsora']) ? $datos['nombre_previsora'] : ''),
            'estado' => (isset($datos['estado']) ? $datos['estado'] : ''),
            'observacion' => (isset($datos['observacion']) ? $datos['observacion'] : ''),
            'direccion' => (isset($datos['direccion']) ? $datos['direccion'] : ''),
            'ciudad' => (isset($datos['municipios']) ? $datos['municipios'] : ''),
            'departamento' => (isset($datos['departamentos']) ? $datos['departamentos'] : ''),
            'telefono' => (isset($datos['telefono']) ? $datos['telefono'] : ''),
            'responsable' => (isset($datos['responsable']) ? $datos['responsable'] : ''),
            'cargo' => (isset($datos['cargo']) ? $datos['cargo'] : ''),
            'otro_contacto' => (isset($datos['otro_contacto']) ? $datos['otro_contacto'] : ''),
            'otro_cargo' => (isset($datos['otro_cargo']) ? $datos['otro_cargo'] : ''),
            'correo1' => (isset($datos['txtEmail']) ? $datos['txtEmail'] : ''),
            'correo2' => (isset($datos['txtEmail2']) ? $datos['txtEmail2'] : ''),
            'estado_registro' => ($estado_registro),
            'fecha_registro' => $fecha_registro,);

        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "actualizarPrevisora", $parametros);
        $datos_registrados = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "actualizar");

        if ($datos_registrados == true) {
            $registro[0] = "ACTUALIZAR";
            $registro[1] = $parametros['nit_previsora'] . '|' . $parametros['nombre_previsora'] . '|' . $parametros['estado']; //
            $registro[2] = "CUOTAS_PARTES_previsora";
            $registro[3] = $parametros['direccion'] . '|' . $parametros['telefono'] . '|' . $parametros['responsable']; //
            $registro[4] = time();
            $registro[5] = "Actualiza datos básicos entidad previsora con ";
            $registro[5] .= "NIT =" . $parametros['nit_previsora'];
            $this->log_us->log_usuario($registro, $this->configuracion);

            echo "<script type=\"text/javascript\">" .
            "alert('Datos Actualizados');" .
            "</script> ";

            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = "pagina=formularioPrevisora";
            $variable .= "&opcion=";
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
        } else {
            echo "<script type=\"text/javascript\">" .
            "alert('Los datos NO se actualizaron correctamente');" .
            "</script> ";
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = "pagina=formularioPrevisora";
            $variable .= "&opcion=";
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
            exit;
        }
    }

}

?>