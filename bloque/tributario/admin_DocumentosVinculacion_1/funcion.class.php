<?php
/* --------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
  --------------------------------------------------------------------------------------------------------------------------- */

if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}


include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/funcionGeneral.class.php");

class funciones_adminVinculacion extends funcionGeneral {

    //Crea un objeto tema y un objeto SQL.
    private $pagina;
    private $opcion;
    private $configuracion;


    function __construct($configuracion, $sql) {
        //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
        //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
        //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
        include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
        include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/html.class.php");
        include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/log.class.php");

        $indice = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
        $this->indice = $configuracion["host"] . $configuracion["site"] . "/index.php?";
        $this->html = new html();
        $this->cripto = new encriptar();
        $this->sql = $sql;

        //Conexion General
        $this->acceso_db = $this->conectarDB($configuracion, "");
        //Conexion SICAPITAL
        $this->accesoSICAPITAL = $this->conectarDB($configuracion, "oracleSIC");
        //Conexion Oracle
        $this->accesoOracle = $this->conectarDB($configuracion, "cuotasP");
        //Conexion postgres
        $this->acceso_pg = $this->conectarDB($configuracion, "tributario");
        //Conexion Oracle
        $this->accesoOracle = $this->conectarDB($configuracion, "cuotasP");

        //Datos de sesion
        $this->usuario = $this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion = $this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
    
        $this->pagina = "adminDocumentosVinculacion";
        $this->opcion = "mostrar";

        $this->configuracion = $configuracion;
        $this->log_us = new log();
    }

    /**
     * Funcion que da la bienvenida la usuario
     * @param <array> $this->verificar
     * @param <array> $this->formulario
     * @param <array> $_REQUEST (pagina,opcion,cod_proyecto)
     * Utiliza los metodos insertarDatos, mostrarInicio, historialVinculacion
     */
    function mostrarInicio() {
        
        echo 'llegamos';
        exit;

        if (date('m') <= '07') {
            $per = '1';
        } else {
            $per = '3';
        }

        $funcionario = array('identificacion' => $this->identificacion,
            'anio' => date('Y'),
            'periodo' => $per);

        $cadena_sql = $this->sql->cadena_sql("datosUsuarioSDH", $funcionario);
        $cadena_sql2 = $this->sql->cadena_sql("datosUsuario", $funcionario);
        $cadena_sql3 = $this->sql->cadena_sql("datos_contrato", $funcionario);

        $cadena_sql7 = $this->sql->cadena_sql("vinculaciones", $funcionario);
        $cadena_sql8 = $this->sql->cadena_sql("consultar_direccion_SHD", $funcionario);
        $cadena_sql9 = $this->sql->cadena_sql("consultar_telefono_SHD", $funcionario);

        $datosU = $this->ejecutarSQL($this->configuracion, $this->accesoSICAPITAL, $cadena_sql, "busqueda");
        $datosSDH = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql2, "busqueda");
        $datosVinculacion = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql7, "busqueda");
        $datosContrato = $this->ejecutarSQL($this->configuracion, $this->accesoSICAPITAL, $cadena_sql3, "busqueda");
        $datos_dir = $this->ejecutarSQL($this->configuracion, $this->accesoSICAPITAL, $cadena_sql8, "busqueda");
        $datos_tel = $this->ejecutarSQL($this->configuracion, $this->accesoSICAPITAL, $cadena_sql9, "busqueda");

        if ($datosSDH == "") {
            $datosusuario = $datosU;
        } else {
            $datosusuario = $datosSDH;
        }
        //enviar a html $datosVinculacion, $datosContrato, $datosusuario, $datos_dir, $datos_tel
        $this->html_adminDocumentosVinculacion->mostrarDatosBasicos($datosVinculacion, $datosContrato, $datosusuario, $datos_dir, $datos_tel);
    }

    function historialVinculacion() {

        $funcionario = array('identificacion' => $this->identificacion);

        $cadena_sql = $this->sql->cadena_sql("datosUsuarioSDH", $funcionario);
        $cadena_sql2 = $this->sql->cadena_sql("datosUsuario", $funcionario);
        $cadena_sql3 = $this->sql->cadena_sql("datos_contrato", $funcionario);
        $cadena_sql5 = $this->sql->cadena_sql("vinculaciones", $funcionario);
        $cadena_sql4 = $this->sql->cadena_sql("consultar_respuestas", $funcionario);

        $datosU = $this->ejecutarSQL($this->configuracion, $this->accesoSICAPITAL, $cadena_sql, "busqueda");
        $datosSDH = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql2, "busqueda");
        $datos_consulta = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql4, "busqueda");
        $datosVinculacion = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql5, "busqueda");
        $datosContrato = $this->ejecutarSQL($this->configuracion, $this->accesoSICAPITAL, $cadena_sql3, "busqueda");

        if ($datosSDH == "") {
            $datosusuario = $datosU;
        } else {
            $datosusuario = $datosSDH;
        }

        $this->html_adminDocumentosVinculacion->mostrarHistorialVinculacion($datosVinculacion, $datosContrato, $datosusuario,$datos_consulta);
    }

    function insertarDatos($resp_encuesta) {

        if (isset($resp_encuesta['func_documento']) && $resp_encuesta['func_documento'] == $this->identificacion) {

            //echo 'comprobacion datos exactos';

            $id_num = $resp_encuesta['func_documento'];
            $id_tipo = $resp_encuesta['tipo_documento'];
            $vigencia = $resp_encuesta['annio'];
            $contrato = $resp_encuesta['contrato'];
            $fec_registro = $resp_encuesta['fecha_registro'];
            $id_enc = $resp_encuesta['id_encuesta'];

            $parametros = array(
                'id_enc' => $id_enc,
                'id_num' => $id_num,
                'id_tipo' => $id_tipo,
                'vigencia' => $vigencia,
                'contrato' => $contrato,
                'fecha_reg' => $fec_registro);

            $cont = 1;

            foreach ($resp_encuesta as $key => $values) {
                if ($key == 'id_pregunta' . $cont && ($resp_encuesta['respuesta_' . $cont] != 'SI' && $resp_encuesta['respuesta_' . $cont] != 'NO')) {
                    echo "<script type=\"text/javascript\">" .
                    "alert('Formulario NO diligenciado correctamente');" .
                    "</script> ";
                    unset($resp_encuesta);
                    $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                    $variable = 'pagina=encuestaTributario';
                    $variable.='&opcion=';
                    $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                    echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                    exit;
                } elseif ($key == 'id_pregunta' . $cont) {
                    $cont++;
                }
            }

            $cont = 1;
            foreach ($resp_encuesta as $key => $values) {

                if ($key == 'id_pregunta' . $cont) {

                    $parametros['id_preg'] = $resp_encuesta['id_pregunta' . $cont];
                    $parametros['resp'] = $resp_encuesta['respuesta_' . $cont];

                    $cadena_sql = $this->sql->cadena_sql("insertar_respuestas", $parametros);
                    //echo '<br>' . $cadena_sql;
                    $insertar = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");

                    //clase log para registrar los cambios en la BDD
                    //VARIABLES PARA EL LOG
                    $registro[0] = "GUARDAR"; //
                    $registro[1] = $parametros['id_num'] . '|' . $parametros['id_enc'] . '|' . $parametros['id_preg']; //
                    $registro[2] = "TRIBUTARIO"; //
                    $registro[3] = $parametros['id_enc'] . '|' . $parametros['id_preg'] . '|' . $parametros['resp']; //
                    $registro[4] = time(); //
                    $registro[5] = "Registra datos insertados a la base de datos para el usuario con  "; //
                    $registro[5] .= " identificacion =" . $parametros['id_num'];
                    $this->log_us->log_usuario($registro, $this->configuracion);

                    $cont++;
                }
            }


            $variable = 'pagina=asistenteTributario';
            $variable.='&opcion=';
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            ?>
            <script language="javascript">    window.location.href = "<? echo $this->indice . $variable; ?>"</script>
            <?
        } else {

            //echo 'comprobacion diferentes datos';

            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = "pagina=asistenteTributario";
            $variable.= "&opcion=";
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
        }
    }

    function noData($mensaje) {

        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/alerta.class.php");
        $cadena = ".::" . $mensaje . "::.";
        $cadena = htmlentities($cadena, ENT_COMPAT, "UTF-8");
        alerta::sin_registro($this->configuracion, $cadena);
    }

}
?>
