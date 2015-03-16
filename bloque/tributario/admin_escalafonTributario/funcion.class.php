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

class funciones_adminTributario extends funcionGeneral {

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
        $this->acceso_pg = $this->conectarDB($configuracion, "tributario");
        
        //Conexión a Academica 
        $this->accesoNOMINA = $this->conectarDB($configuracion, "tributario_planta");
        //Conexión a Sicapital 
        $this->accesoSICAPITAL = $this->conectarDB($configuracion, "oracleSIC");
        

        //Datos de sesion

        $this->usuario = $this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion = $this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");

        $this->configuracion = $configuracion;

        $this->html_infoTributario = new html_adminTributario($configuracion);
    }

    function nuevoRegistro($configuracion, $tema, $acceso_db) {
        //echo  "nuevo Registro";exit;
        $this->buscarParametros();
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

    function listaRegistro($configuracion, $registro) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, '', 'lista_funcionarios', $registro);
        $datosFuncionario = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        
        if ($datosFuncionario) 
            { $datosEscalafon=array();
            
               foreach ($datosFuncionario as $fun=>$value)
                    {
                    $funcionario['identificacion']=$datosFuncionario[$fun]['identificacion'];
                    $funcionario['vigencia']=$registro['vigencia'];
                    /*verfifica usuario de planta*/
                    $cadena_sql_planta = $this->sql->cadena_sql($this->configuracion, '',"datosUsuario", $funcionario);
                    $datosPlanta = $this->ejecutarSQL($this->configuracion, $this->accesoNOMINA, $cadena_sql_planta, "busqueda");
                    

                    
                    if (is_array($datosPlanta)) 
                           {$datosEscalafon[$fun]['vigencia']=$registro['vigencia'];
                            $datosEscalafon[$fun]['identificacion']=$datosPlanta[0]['FUN_NRO_IDEN'];
                            $datosEscalafon[$fun]['nombre']=$datosPlanta[0]['FUN_NOMBRE1'].' '.$datosPlanta[0]['FUN_NOMBRE2'];
                            $datosEscalafon[$fun]['apellido']=$datosPlanta[0]['FUN_APELLIDO1'].' '.$datosPlanta[0]['FUN_APELLIDO2'];
                            $datosEscalafon[$fun]['vinculacion']=$datosPlanta[0]['FUN_VINCULACION'];
                            $datosEscalafon[$fun]['escalafon']=$datosFuncionario[$fun]['escalafon'];
                            $datosEscalafon[$fun]['id_escalafon']=$datosFuncionario[$fun]['id_escalafon'];
                            $datosEscalafon[$fun]['tipo_identificacion']=$datosFuncionario[$fun]['tipo_doc'];
                            $tipoBD = 'PEEMP';
                           } 
                     else {/*verfifica usuario contratista*/
                            $cadena_sql_sic = $this->sql->cadena_sql($this->configuracion, '',"datosUsuarioSDH", $funcionario);
                            $datosusuario = $this->ejecutarSQL($this->configuracion, $this->accesoSICAPITAL, $cadena_sql_sic, "busqueda");       
                            if ($datosusuario) 
                                {   $datosEscalafon[$fun]['vigencia']=$registro['vigencia'];
                                    $datosEscalafon[$fun]['identificacion']=$datosusuario[0]['FUN_NRO_IDEN'];
                                    $datosEscalafon[$fun]['nombre']=$datosusuario[0]['FUN_NOMBRE1'].' '.$datosusuario[0]['FUN_NOMBRE2'];
                                    $datosEscalafon[$fun]['apellido']=$datosusuario[0]['FUN_APELLIDO1'].' '.$datosusuario[0]['FUN_APELLIDO2'];
                                    $datosEscalafon[$fun]['vinculacion']=$datosusuario[0]['FUN_VINCULACION'];
                                    $datosEscalafon[$fun]['escalafon']=$datosFuncionario[$fun]['escalafon'];
                                    $datosEscalafon[$fun]['id_escalafon']=$datosFuncionario[$fun]['id_escalafon'];
                                    $datosEscalafon[$fun]['tipo_identificacion']=$datosFuncionario[$fun]['tipo_doc'];
                                    $tipoBD = 'SHD';
                                }
                            else{   $datosEscalafon[$fun]['vigencia']=$registro['vigencia'];
                                    $datosEscalafon[$fun]['identificacion']=$funcionario['identificacion'];
                                    $datosEscalafon[$fun]['nombre']=' Sin Datos';
                                    $datosEscalafon[$fun]['apellido']=' Sin Datos';
                                    $datosEscalafon[$fun]['vinculacion']=' Sin Datos';
                                    $datosEscalafon[$fun]['escalafon']=$datosFuncionario[$fun]['escalafon'];
                                    $datosEscalafon[$fun]['id_escalafon']=$datosFuncionario[$fun]['id_escalafon'];
                                    $datosEscalafon[$fun]['tipo_identificacion']=$datosFuncionario[$fun]['tipo_doc'];
                                    $tipoBD = ' Sin Datos';
                                }    
                        }

                    $cadena_sql_resp = $this->sql->cadena_sql($this->configuracion, '',"consultar_respuestas", $funcionario);
                    $datosRta = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql_resp, "busqueda");
                    //var_dump($datosRta);
                    if($datosRta){
                    foreach ($datosRta as $rta => $dato) 
                        {$respuestas.="( ".$datosRta[$rta]['form_posicion']." ) ".$datosRta[$rta]['resp_respuesta']."  ";
                        }
                    }
                    else{$respuestas='Sin Datos';}
                    
                    $datosEscalafon[$fun]['respuestas']=$respuestas;
                    unset($respuestas);
                    }
                    $this->html_infoTributario->listar_escalafon_tributario($datosEscalafon);
            }
        else{
            echo "<br>";
            $titulo=' No existe Registro de información Tributaria en la vigencia '.$_REQUEST['vigencia'].' ';
            $titulo.=$_REQUEST['documento']!=''?', para el funcionario '.$_REQUEST['documento'].' ':'';
            $this->html_infoTributario->sinDatos($this->configuracion,$titulo);
            }
    }

    function mostrarRegistro($configuracion, $registro, $totalRegistros, $opcion, $variable) {
        switch ($opcion) {
            
        }
    }

    /* __________________________________________________________________________________________________

      Metodos especificos
      __________________________________________________________________________________________________ */

       /**
     * Funcion que consulta losa datos de la DB de los parametros para los reportes
     * @return type
     */
    function generarEscalafon($datosClasificar,$vigencia) {
        //consulta los datos del reporte    
        //var_dump($datosClasificar);exit;
        //echo "vigencia ".$vigencia."<br>";
        
        $escalafon=array();
        
        foreach ($datosClasificar as $key => $value) 
            { 
              $aux['tipo_ident']=$datosClasificar[$key]['tipo_ident_'.$key];
              $aux['identificacion']=$datosClasificar[$key]['identificacion_'.$key];
              $aux['escalafon']=$datosClasificar[$key]['escalafon_'.$key];
              $aux['vigencia']=$vigencia;
             // var_dump($aux);
              //consulta las respuestas y las preguntas de clasificacion
              $datosRespuestas=$this->consultarRespuestas($aux);
              //var_dump($datosRespuestas);
                $clasifica=$datosRespuestas[0]['id_clasifica'];
                $escalafon[$clasifica]['ponderado']=1;
                foreach ($datosRespuestas as $clas => $valor)
                    { 
                       /*inicia la pondertacion cuando cambia nivel*/
                       if ($clasifica!=$datosRespuestas[$clas]['id_clasifica'])
                            { $clasifica=$datosRespuestas[$clas]['id_clasifica'];
                              $escalafon[$clasifica]['ponderado']=1;  
                            }   
                   
                      /*valida las respuestas de las pregunstas, si son iguales multiplica por 1, sino por 0*/ 
                      if(isset($datosRespuestas[$clas]['respuesta_clas']) && $datosRespuestas[$clas]['respuesta_clas']==$datosRespuestas[$clas]['respuesta_us'])
                            { $escalafon[$clasifica]['ponderado']=($escalafon[$clasifica]['ponderado']*1);
                            }     
                      elseif(isset($datosRespuestas[$clas]['respuesta_clas']) && $datosRespuestas[$clas]['respuesta_clas']!=$datosRespuestas[$clas]['respuesta_us'])
                            { $escalafon[$clasifica]['ponderado']=($escalafon[$clasifica]['ponderado']*0);
                            }      
                       else {$escalafon[$clasifica]['ponderado']=($escalafon[$clasifica]['ponderado']*0);
                             $defecto=$clasifica;/*identifica la clasificacion sin preguntas*/
                            }  

                    }
               //var_dump($escalafon);
               $nivel=0;
               /*verifica si algun nivel esta ponderado y lo ubica en el escalafon*/
                foreach ($escalafon as $esc => $pond) {
                        if($escalafon[$esc]['ponderado']==1)
                            {$nivel=$esc;}
                    }
                    
               $nivel==0?$nivel=$defecto:'';//verifica si viene nivel sino asigna por defecto
               $datosEscalafon=array('identificacion'=>$aux['identificacion'],
                                     'clasificacion'=>$nivel,
                                     'vigencia'=>$aux['vigencia'],
                                     'tipo_ident'=>$aux['tipo_ident'] ,
                                     'fecha'=> date('Y-m-d H:i:s') ,
                                     'estado'=>1
                                    );
               
                if($aux['escalafon']==0)
                    {$cadena_inserta = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "insertar_escalafon", $datosEscalafon);
                     $rsdatos = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_inserta, "");
                     
                        $registro[0] = "REGISTRAR ";
                        $registro[1] =  $datosEscalafon['identificacion'];
                        $registro[2] = "ESCALAFON TRIBUTARIO";
                        $registro[3] = $aux['identificacion'].' | '.$aux['vigencia'].' | '. $nivel.' ';
                        $registro[4] = time();
                        $registro[5] = "Registra el escalafon tributario para el usuario con  ";
                        $registro[5] .= " identificacion =" . $datosEscalafon['identificacion'];
                        $this->log_us->log_usuario($registro, $this->configuracion);
                     
                    }
                else{$cadena_actualiza = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "actualizar_escalafon", $datosEscalafon);
                     $rsdatos = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_actualiza, "");
                        $registro[0] = "ACTUALIZAR ";
                        $registro[1] =  $datosEscalafon['identificacion'];
                        $registro[2] = "ESCALAFON TRIBUTARIO";
                        $registro[3] = $aux['identificacion'].' | '.$aux['vigencia'].' | '. $nivel.' ';
                        $registro[4] = time();
                        $registro[5] = "Actualiza el escalafon tributario para el usuario con  ";
                        $registro[5] .= " identificacion =" . $datosEscalafon['identificacion'];
                        $this->log_us->log_usuario($registro, $this->configuracion);
                     
                    }    
                
                
               //para insertar
               //$cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "invocar_preguntas", $parametros);
               //$datos = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
               unset($escalafon);
               unset($nivel);
               unset($datosEscalafon);
               unset($aux);
            }
            
        //exit;
        /*
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, '', 'invocar_vigencias', '');
        $datosSQL = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        $this->html_infoTributario->form_muestra_parametros($datosSQL);*/
    }    
    
        /**
     * Funcion que consulta losa datos de la DB de los parametros para los reportes
     * @return type
     */
    function buscarParametros() {
        //consulta los datos del reporte    
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, '', 'invocar_vigencias', '');
        $datosSQL = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        $this->html_infoTributario->form_muestra_parametros($datosSQL);
    }
    
    
    
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