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
        $this->acceso_wcuotas = $this->conectarDB($configuracion,"cuotasP");
        
        
        //TEST Conexiones
        $this->acceso_sic = $this->conectarDB($configuracion,"oracleSIC");
        $this->acceso_tributarioP = $this->conectarDB($configuracion,"tributario_planta");
        $this->acceso_nomina = $this->conectarDB($configuracion,"nominapg");
        $this->acceso_tributario = $this->conectarDB($configuracion,"tributario");
        $this->acceso_cuotas_partes = $this->conectarDB($configuracion,"cuotas_partes");
        

        //Conexion SICAPITAL
        //$this->acceso_sic = $this->conectarDB($configuracion,"oracleSIC");
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
     * Funcion que consulta losa datos de la DB de los parametros para los reportes
     * @return type
     */
    function buscarParametros($reporte) {
    	
        //consulta los datos del reporte    
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, '', 'buscarParametrosReporte', $reporte);
        $parametrosSQL = $this->ejecutarSQL($this->configuracion, $this->acceso_db, $cadena_sql, "busqueda");
        
        
        
        var_dump("<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<< FRAME");
        echo "<br>";
        var_dump($this->acceso_db);
        echo "<br>";
        var_dump("<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<< WCUOTASPARTES");
        echo "<br>";
        var_dump($this->acceso_wcuotas);
        echo "<br>";
        var_dump("<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<< TRIBUTARIO PLANTA");
        echo "<br>";
        var_dump($this->acceso_tributarioP);
        echo "<br>";
        var_dump("<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<< SICAPITAL");
        echo "<br>";
        var_dump($this->acceso_sic);
        echo "<br>";
        var_dump("<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<< NOMINA");
        echo "<br>";
        var_dump($this->acceso_nomina);
        echo "<br>";
        var_dump("<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<< TRIBUTARIO");
        echo "<br>";
        var_dump($this->acceso_tributario);
        echo "<br>";
        var_dump("<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<< CUOTAS PARTES");
        echo "<br>";
        var_dump($this->acceso_cuotas_partes);
        
        
        
        //echo $cadena_sql;
        //var_dump($reporte);
        //var_dump("<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<");
        //var_dump($this->acceso_db);
        //exit();

        if (is_array($parametrosSQL)) {  //verifica si el reporte usa parametros si NO, ejecuta el reporte
            if ($parametrosSQL[0]['usa_par'] == 'N') {
                $this->generarReporte($reporte);
                exit;
            } else {
                $parametroRep = array();
                foreach ($parametrosSQL as $key => $value) { //echo $parametrosSQL[$key]['nombre_par']."<br>";
                    $parametroRep[$key]['id_reporte'] = $parametrosSQL[$key]['id_rep'];
                    $parametroRep[$key]['reporte'] = $parametrosSQL[$key]['rep_nom'];
                    $parametroRep[$key]['pagina'] = $reporte['pagina'];
                    $parametroRep[$key]['titulo'] = $parametrosSQL[$key]['rep_titulo'];
                    $parametroRep[$key]['id_parametro'] = $parametrosSQL[$key]['id_par'];
                    $parametroRep[$key]['nombre'] = $parametrosSQL[$key]['nombre_par'];
                    $parametroRep[$key]['caja_html'] = $parametrosSQL[$key]['caja_html'];
                    $parametroRep[$key]['actualiza'] = $parametrosSQL[$key]['alimenta_par'];
                    $parametroRep[$key]['enviar'] = $parametrosSQL[$key]['envia_par'];


                    //verifica los parametros de control para ejecutar la consulta sql 
                    if ($parametrosSQL[$key]['sql_par'] <> '' && $parametrosSQL[$key]['control_busqueda'] <> '' && strstr($parametrosSQL[$key]['control_busqueda'], "|") && $parametrosSQL[$key]['caja_html'] == 'combo') {
                        //identifica los controles registrados para la consulta
                        $controlSQL = explode("|", $parametrosSQL[$key]['control_busqueda']);
                        foreach ($controlSQL as $par => $value) { //reemplaza los parametros de la sql, por los valores
                            if (isset($_REQUEST[$controlSQL[$par]]) && $_REQUEST[$controlSQL[$par]] > 0) { //reemplaza los parametros de la consulta
                                $parametrosSQL[$key]['sql_par'] = str_replace('$P{\'' . $controlSQL[$par] . '\'}', ' = \'' . $_REQUEST[$controlSQL[$par]] . '\'', $parametrosSQL[$key]['sql_par']);
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
                            $parametrosSQL[$key]['sql_par'] = str_replace('$P{\'' . $parametrosSQL[$key]['control_busqueda'] . '\'}', ' = \'' . $_REQUEST[$parametrosSQL[$key]['control_busqueda']] . '\'', $parametrosSQL[$key]['sql_par']);
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
		
        //var_dump($reporteSQL);
        //var_dump($cadena_sql);
        
        
        
        if (is_array($reporteSQL)) {
            //Reemplaza las variables en la consulta sql
            //var_dump($_REQUEST);
            
        	//var_dump($reporteSQL);
        	//var_dump($datosReporte);
        	
            foreach ($_REQUEST as $key => $value) {
                
                if (isset($_REQUEST[strtolower($key)]) && (is_numeric($_REQUEST[strtolower($key)]) && $_REQUEST[strtolower($key)] > 0) && $_REQUEST[strtolower($key)] != '') {
                    $reemplazo = " LIKE '%" . $_REQUEST[strtolower($key)] . "%'";
                } elseif (isset($_REQUEST[strtolower($key)]) && $_REQUEST[strtolower($key)] != '' && !is_numeric($_REQUEST[strtolower($key)])) {
                    $reemplazo = " LIKE '%" . $_REQUEST[strtolower($key)] . "%'";
                }
                /* {$reemplazo=" = ".$_REQUEST[strtolower($key)];} */ 
                else {
                    $reemplazo = " IS NOT NULL ";
                }
                $reporteSQL[0]['rep_sql'] = str_replace('$P{\'' . strtolower($key) . '\'}', $reemplazo, $reporteSQL[0]['rep_sql']);
            }
            //ECHO $reporteSQL[0]['rep_sql'] ;
            //exit();
            
            
             //echo $reporteSQL[0]['rep_sql'];     
            //busca y ejecuta los datos para generar el reporte
            $accesoReporte = $this->conectarDB($this->configuracion, $reporteSQL[0]['rep_conect']);
            
            
            echo "<br>";
            echo "<br>";
            echo "<br>";
            echo "<br>";
            echo "<br>";
            echo "<br>";
            echo "<br>";
            echo "<br>";
            echo "<br>";
            echo "<br>";
            var_dump($accesoReporte);
            var_dump("<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<");
            var_dump($reporteSQL[0]['rep_conect']);
            var_dump("<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<");
            var_dump("<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<");
            var_dump($reporteSQL[0]['rep_titulo']);
            var_dump("<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<");
            var_dump("<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<");
            var_dump("<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<");
            echo $reporteSQL[0]['rep_sql'];
            
            
            //exit();
            
            $datosReporte = $this->ejecutarSQL($this->configuracion, $accesoReporte, $reporteSQL[0]['rep_sql'], "busqueda");
            
            //var_dump($datosReporte);
            //exit();
            
            
            //var_dump($datosReporte);
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



