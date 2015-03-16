<?php

/* --------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
  --------------------------------------------------------------------------------------------------------------------------- */
/* ---------------------------------------------------------------------------------------
  |				Control Versiones				    	|
  ----------------------------------------------------------------------------------------
  | fecha      |        Autor            | version     |              Detalle            |
  ----------------------------------------------------------------------------------------
  | 14/02/2013 | Maritza Callejas C.  	| 0.0.0.1     |                                 |
  ----------------------------------------------------------------------------------------
 */


if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/funcionGeneral.class.php");
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/navegacion.class.php");
include_once("html.class.php");

class funciones_reporteFinanciero extends funcionGeneral {

    function __construct($configuracion, $sql) {
        //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
        //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
        include ($configuracion["raiz_documento"] . $configuracion["estilo"] . "/basico/tema.php");
        include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
        include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/log.class.php");

        $this->cripto = new encriptar();
        $this->log_us = new log();
        $this->configuracion = $configuracion;
        $this->tema = $tema;
        $this->sql = $sql;

        $this->htmlReporte = new html_reporteFinanciero($configuracion);
        //Conexion General
        $this->acceso_db = $this->conectarDB($configuracion, "mysqlFrame");

        //Conexion SICAPITAL
        $this->acceso_sic = $this->conectarDB($configuracion, "oracleSIC");

        //Conexion Oracle
        $this->acceso_cp = $this->conectarDB($configuracion, "cuotasP");

        //Datos de sesion
        $this->usuario = $this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion = $this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
    }

    function nuevoRegistro($configuracion, $reporte) {
        
    }

    function editarRegistro($configuracion, $tema, $id, $acceso_db, $formulario) {
        
    }

    function corregirRegistro() {
        
    }

    function listaRegistro($configuracion, $reporte) {
        $this->buscarParametros($reporte);
    }

    function mostrarRegistro($configuracion, $registro, $totalRegistros, $opcion, $variable) {
        
    }

    /* __________________________________________________________________________________________________

      Metodos especificos
      __________________________________________________________________________________________________ */

    /**
     * Funcion que consulta los datos de la DB de los parametros para los reportes
     * @return type
     */
    function buscarParametros($reporte) {
        //consulta los datos del reporte    
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, '', 'buscarParametrosReporte', $reporte);
        $parametrosSQL = $this->ejecutarSQL($this->configuracion, $this->acceso_db, $cadena_sql, "busqueda");

        if (is_array($parametrosSQL)) {  //verifica si el reporte usa parametros si NO, ejecuta el reporte
            if ($parametrosSQL[0]['usa_par'] == 'N') {
                $this->generarReporte($reporte);
                exit;
            } else {
                $parametroRep = array();
                foreach ($parametrosSQL as $key => $value) { //echo $parametrosSQL[$key]['nombre_par']."<br>";
                    $parametroRep[$key]['reporte'] = $parametrosSQL[$key]['rep_nom'];
                    $parametroRep[$key]['pagina'] = $reporte['pagina'];
                    $parametroRep[$key]['titulo'] = $parametrosSQL[$key]['rep_titulo'];
                    $parametroRep[$key]['nombre'] = $parametrosSQL[$key]['nombre_par'];
                    $parametroRep[$key]['caja_html'] = $parametrosSQL[$key]['caja_html'];
                    $parametroRep[$key]['actualiza'] = $parametrosSQL[$key]['alimenta_par'];


                    //verifica los parametros de control para ejecutar la consulta sql 
                    if ($parametrosSQL[$key]['sql_par'] <> '' && $parametrosSQL[$key]['control_busqueda'] <> '' && strstr($parametrosSQL[$key]['control_busqueda'], "|") && $parametrosSQL[$key]['caja_html'] == 'combo') {
                        //identifica los controles registrados para la consulta
                        $controlSQL = explode("|", $parametrosSQL[$key]['control_busqueda']);
                        foreach ($controlSQL as $par => $value) { //reemplaza los parametros de la sql, por los valores
                            if (isset($_REQUEST[$controlSQL[$par]]) && $_REQUEST[$controlSQL[$par]] > 0) { //reemplaza los parametros de la consulta
                                $parametrosSQL[$key]['sql_par'] = str_replace('$P{\'' . $controlSQL[$par] . '\'}', ' = ' . $_REQUEST[$controlSQL[$par]], $parametrosSQL[$key]['sql_par']);
                            } else { //reemplaza los parametros de la consulta
                                $parametrosSQL[$key]['sql_par'] = str_replace('$P{\'' . $controlSQL[$par] . '\'}', " IS NOT NULL ", $parametrosSQL[$key]['sql_par']);
                            }
                        }
                        //crea objeto de conexion del reporte y ejecuta la consulta    
                        $accesoParametro = $this->conectarDB($this->configuracion, $parametrosSQL[$key]['conexion_par']);
                        $parametroDatos = $this->ejecutarSQL($this->configuracion, $accesoParametro, $parametrosSQL[$key]['sql_par'], "busqueda");

                        //var_dump($parametroDatos);
                        $parametroRep[$key]['datos'] = $parametroDatos;
                    } elseif ($parametrosSQL[$key]['sql_par'] <> '' && $parametrosSQL[$key]['control_busqueda'] <> '' && $parametrosSQL[$key]['caja_html'] == 'combo') { //echo "<br> 1 control y sql<br>";
                        //reemplaza la etiqueta del parametro $P[], por el valor  que venga en el REQUEST[]
                        //echo "<br> ".$parametrosSQL[$key]['control_busqueda']." : ".$_REQUEST[$parametrosSQL[$key]['control_busqueda']];
                        if (isset($_REQUEST[$parametrosSQL[$key]['control_busqueda']]) && $_REQUEST[$parametrosSQL[$key]['control_busqueda']] > 0) { //reemplaza los parametros de la consulta
                            $parametrosSQL[$key]['sql_par'] = str_replace('$P{\'' . $parametrosSQL[$key]['control_busqueda'] . '\'}', ' = ' . $_REQUEST[$parametrosSQL[$key]['control_busqueda']], $parametrosSQL[$key]['sql_par']);
                        } else { //reemplaza los parametros de la consulta
                            $parametrosSQL[$key]['sql_par'] = str_replace('$P{\'' . $parametrosSQL[$key]['control_busqueda'] . '\'}', " IS NOT NULL ", $parametrosSQL[$key]['sql_par']);
                        }

                        //crea objeto de conexion del reporte y ejecuta la consulta    
                        $accesoParametro = $this->conectarDB($this->configuracion, $parametrosSQL[$key]['conexion_par']);
                        $parametroDatos = $this->ejecutarSQL($this->configuracion, $accesoParametro, $parametrosSQL[$key]['sql_par'], "busqueda");

                        //var_dump($parametroDatos);
                        $parametroRep[$key]['datos'] = $parametroDatos;
                    } else {  //echo "<br> sin control o sql<br>";
                        $parametroRep[$key]['datos'] = (isset($_REQUEST[$parametrosSQL[$key]['nombre_par']]) ? $_REQUEST[$parametrosSQL[$key]['nombre_par']] : '');
                    }
                }

//llama la funcion que muestra los parametros
                $this->htmlReporte->form_muestra_parametros($parametroRep);
            }
        } else {//muestra mensaje si el reporte no esta registrado
            $this->htmlReporte->sinDatos($this->configuracion, " EL REPORTE SELECCIONADO, NO SE HA REGISTRADO EN EL SISTEMA ");
        }
    }

    /**
     * Funcion que consulta el listado de vigencias de las reservas
     * @return type
     */
    function generarReporte($reporte) {   //consulta los datos del reporte    
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, '', 'buscarReporte', $reporte);
        $reporteSQL = $this->ejecutarSQL($this->configuracion, $this->acceso_db, $cadena_sql, "busqueda");

        if (is_array($reporteSQL)) {
            //Reemplaza las variables en la consulta sql
            foreach ($_REQUEST as $key => $value) {
                if (isset($_REQUEST[strtolower($key)]) && $_REQUEST[strtolower($key)] > 0 && $_REQUEST[strtolower($key)] != '') {
                    $reemplazo = " LIKE '%" . $_REQUEST[strtolower($key)] . "%'";
                }
                /* {$reemplazo=" = ".$_REQUEST[strtolower($key)];} */ else {
                    $reemplazo = " IS NOT NULL ";
                }

                $reporteSQL[0]['rep_sql'] = str_replace('$P{\'' . strtolower($key) . '\'}', $reemplazo, $reporteSQL[0]['rep_sql']);
            }
            // echo $reporteSQL[0]['rep_sql'];     
            //busca y ejecuta los datos para generar el reporte
            $accesoReporte = $this->conectarDB($this->configuracion, $reporteSQL[0]['rep_conect']);
            $datosReporte = $this->ejecutarSQL($this->configuracion, $accesoReporte, $reporteSQL[0]['rep_sql'], "busqueda");

            $i = 0;


        /*    if (isset($_REQUEST["Violeta"])) {
                foreach ($datosReporte as $arreglo) {

                    $indice = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                    //enlace para reporte de información pensionados cuotas partes
                    $variable = "pagina=" . $reporte['pagina'];
                    $variable.="&opcion=buscar";

                    $repvaloresPensionados = $variable;
                    $repvaloresPensionados.="&reporte=valoresPensionados";
                    $repvaloresPensionados = $this->cripto->codificar_url($repvaloresPensionados, $this->configuracion);

                    $repCPPensionados = $variable;
                    $repCPPensionados.="&reporte=CPPensionados";
                    $repCPPensionados.="&cedula=" . $arreglo[0];
                    $repCPPensionados.="&Violeta=true";
                    $repCPPensionados = $this->cripto->codificar_url($repCPPensionados, $this->configuracion);

                    $arreglo["NOMBRE"] = "<a href='" . $indice . $repvaloresPensionados . "'>" . $arreglo["NOMBRE"] . "</a>";

                    $datosReporte[$i++] = $arreglo;
                }
            }*/


            //llama la funcion para mostrar el reporte
            if (is_array($datosReporte)) {
                $this->htmlReporte->mostrarReportes($this->configuracion, $datosReporte, $reporteSQL[0]['rep_nom'], $reporteSQL[0]['rep_titulo']);
            } else {
                $this->htmlReporte->sinDatos($this->configuracion, " NO EXISTEN DATOS PARA EL REPORTE " . $reporteSQL[0]['rep_titulo']);
            }
        } else {
            $this->htmlReporte->sinDatos($this->configuracion, " EL REPORTE SELECCIONADO, NO SE HA REGISTRADO EN EL SISTEMA ");
        }
    }

}

// fin de la clase
?>



