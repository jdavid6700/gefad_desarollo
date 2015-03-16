<?php

/* --------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
  --------------------------------------------------------------------------------------------------------------------------- */
/* ---------------------------------------------------------------------------------------
  |				Control Versiones				    	|
  ----------------------------------------------------------------------------------------
  | fecha      |        Autor            | version     |              Detalle            |
  ----------------------------------------------------------------------------------------
  | 24/05/2013 | Violeta Sosa            | 0.0.0.1     |                                 |
  ----------------------------------------------------------------------------------------
 */


if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/funcionGeneral.class.php");
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/navegacion.class.php");
include_once("html.class.php");

class funciones_infoTributario extends funcionGeneral {

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

        //Conexión a Postgres 10.20.2.101
        $this->acceso_pg = $this->conectarDB($configuracion, "tributario");

        //Datos de sesion

        $this->usuario = $this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion = $this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");

        $this->configuracion = $configuracion;

        $this->html_infoTributario = new html_infoTributario($configuracion);
    }

    function nuevoRegistro($configuracion, $tema, $acceso_db) {
        $registro = (isset($registro) ? $registro : '');
        $this->form_usuario($configuracion, $registro, $this->tema, "");
    }

    function editarRegistro($configuracion, $tema, $id, $acceso_db, $formulario) {
        $this->cadena_sql = $this->sql->cadena_sql($configuracion, $this->acceso_db, "usuario", $id);

        $registro = $this->acceso_db->ejecutarAcceso($this->cadena_sql, "busqueda");
        if ($_REQUEST['opcion'] == 'cambiar_clave') {
            $this->formContrasena($configuracion, $registro, $this->tema, '');
        } else {
            $this->form_usuario($configuracion, $registro, $this->tema, '');
        }
    }

    function listaRegistro($configuracion, $id_registro) {
        // $this->html_infoTributario->encuesta_info_tributario();
    }

    function mostrarRegistro($configuracion, $registro, $totalRegistros, $opcion, $variable) {
        switch ($opcion) {
            
        }
    }

    /* __________________________________________________________________________________________________

      Metodos especificos
      __________________________________________________________________________________________________ */

//
    function envioConsulta() {

        if (isset($_REQUEST['identificacion']) && $_REQUEST['identificacion'] == $this->identificacion) {
            $parametros = array(
                'identificacion' => (isset($_REQUEST['identificacion']) ? $_REQUEST['identificacion'] : ''),
                'vinculacion' => (isset($_REQUEST['vinculacion']) ? $_REQUEST['vinculacion'] : ''));
            $datos_pregunta = $this->consultarPreguntas($parametros);
            //var_dump($datos_pregunta);
            $this->html_infoTributario->encuesta_info_tributario($datos_pregunta);
        } else {
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = "pagina=asistenteTributario";
            $variable .= "&opcion=";
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
        }
    }

    function consultarPreguntas($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "invocar_preguntas", $parametros);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos;
    }

    function consultarRespuestas($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "invocar_respuestas", $parametros);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos;
    }

    function consultaForm() {


        if (isset($_REQUEST['identificacion']) && $_REQUEST['identificacion'] == $this->identificacion) {
             $parametros = array(
                'vigencia' => (isset($_REQUEST['vigencia']) ? $_REQUEST['vigencia'] : ''),
                'identificacion' => (isset($_REQUEST['identificacion']) ? $_REQUEST['identificacion'] : ''),
                'vinculacion' => (isset($_REQUEST['vinculacion']) ? $_REQUEST['vinculacion'] : ''));
            
            $datos_resp = $this->consultarRespuestas($parametros);
            $datos_pre = $this->consultarPreguntas($parametros);

            $this->html_infoTributario->respuesta_info_tributario($datos_pre, $datos_resp);
        } else {
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = "pagina=asistenteTributario";
            $variable .= "&opcion=";
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
        }
    }

}

// fin de la clase
?>