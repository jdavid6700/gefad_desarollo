
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

        $this->html_formConcurrencia = new html_formConcurrencia($configuracion);
    }

    function registrarDescripcionCP($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "insertarDescripcionCP", $parametros);
        $datos_DescripcionCP = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos_DescripcionCP;
    }

    function consultarRegistros() {

        $parametros = array();
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "consultarConcurrencia", $parametros);
        $datos_registro = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");

        $this->html_formConcurrencia->mostrarRegistros($datos_registro);
    }

    function mostrarFormulario() {
        $this->html_formConcurrencia->formularioConcurrencia();
    }

    function procesarFormulario($datos) {
     
        $fecha_registro = date('d/m/Y');
        $estado_registro = 1;

     /*   foreach ($datos as $key => $value) {

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
*/
        $parametros_descripcion_cp = array(
            'cedula' => (isset($datos['cedula']) ? $datos['cedula'] : ''),
            'nit_entidad' => (isset($datos['entidad_empleadora']) ? $datos['entidad_empleadora'] : ''),
            'nit_previsora' => (isset($datos['entidad_previsora']) ? $datos['entidad_previsora'] : ''),
            'valor_mesada' => (isset($datos['mesada']) ? $datos['mesada'] : ''),
            'valor_cuota' => (isset($datos['cp_aceptada']) ? $datos['cp_aceptada'] : ''),
            'porcen_cuota' => (isset($datos['porc_aceptado']) ? $datos['porc_aceptado'] : ''),
            'fecha_concurrencia' => (isset($datos['fecha_concurrencia']) ? $datos['fecha_concurrencia'] : ''),
            'tipo_actoadmin' => (isset($datos['tipo_acto']) ? $datos['tipo_acto'] : ''),
            'actoadmin' => (isset($datos['acto_adm']) ? $datos['acto_adm'] : ''),
            'factoadmin' => (isset($datos['fecha_acto_adm']) ? $datos['fecha_acto_adm'] : ''),
            'estado' => $estado_registro,
            'registro' => $fecha_registro);

        $registro_descripcion_cp = $this->registrarDescripcionCP($parametros_descripcion_cp);
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
    }

}

?>