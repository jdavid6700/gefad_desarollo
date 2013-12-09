<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/
/*---------------------------------------------------------------------------------------
 |				Control Versiones				    	|	
 ----------------------------------------------------------------------------------------
 | fecha      |        Autor            | version     |              Detalle            |
 ----------------------------------------------------------------------------------------
 | 15/05/2013 | Maritza Callejas C.  	| 0.0.0.1     |                                 |
 ----------------------------------------------------------------------------------------
*/


if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;		
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/navegacion.class.php");
include_once("html.class.php");
class funciones_adminActaInicio extends funcionGeneral
{

	function __construct($configuracion, $sql)
	{
		//[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo		
		//include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
		include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/log.class.php");
                
		$this->cripto = new encriptar();
		$this->log_us = new log();
		$this->tema = $tema;
		$this->sql = $sql;
		
		//Conexion General
		$this->acceso_db = $this->conectarDB($configuracion,"mysqlFrame");
                
                //Conexion SICAPITAL
		$this->acceso_sic = $this->conectarDB($configuracion,"oracleSIC");
                
                //Conexion NOMINA 
		$this->acceso_nomina = $this->conectarDB($configuracion,"nominapg");
               
		//Datos de sesion
		
		$this->usuario = $this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
		$this->identificacion = $this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
		
                $this->configuracion = $configuracion;
                
                $this->htmlActaInicio = new html_adminActaInicio($configuracion);   
                
	}
	
	
	function nuevoRegistro($configuracion,$tema,$acceso_db)
	{
            $registro = (isset($registro)?$registro:'');
            $this->form_usuario($configuracion,$registro,$this->tema,"");
		
	}
	
   	function editarRegistro($configuracion,$tema,$id,$acceso_db,$formulario)
   	{						
		$this->cadena_sql = $this->sql->cadena_sql($configuracion,$this->acceso_db,"usuario",$id);
                
		$registro = $this->acceso_db->ejecutarAcceso($this->cadena_sql,"busqueda");
		if ($_REQUEST['opcion'] == 'cambiar_clave')
		{
		$this->formContrasena($configuracion,$registro,$this->tema,'');
		}
		else
		{
		$this->form_usuario($configuracion,$registro,$this->tema,'');
		}
	}
   	
   	function corregirRegistro()
    	{
	}
	
	function listaRegistro($configuracion,$id_registro)
	
    	{	
	}
		

	function mostrarRegistro($configuracion,$registro, $totalRegistros, $opcion, $variable)
    	{	
		switch($opcion)
		{
			case "multiplesNovedades":
				$this->htmlActaInicio->multiplesNovedades($configuracion,$registro, $totalRegistros, $variable);
				break;
		
		}
		
	}
	
		
/*__________________________________________________________________________________________________
		
						Metodos especificos 
__________________________________________________________________________________________________*/

          /**
     * Funcion que consulta los datos necesarios para mostrar en el formulario de creacion de acta de inicio
     *
     * Utiliza los metodos consultarConceptosNomina
    */
   function crearActa(){
      
            $interno_oc=(isset($_REQUEST['interno_oc'])?$_REQUEST['interno_oc']:'');
            $vigencia_contrato=(isset($_REQUEST['vigencia_contrato'])?$_REQUEST['vigencia_contrato']:'');
            
            $contrato = $this->consultarDatosContrato($interno_oc,$vigencia_contrato);
            $cod_contratista = (isset($contrato[0]['NUM_IDENTIFICACION'])?$contrato[0]['NUM_IDENTIFICACION']:''); 
            $tipo_id_contratista = (isset($contrato[0]['TIPO_IDENTIFICACION'])?$contrato[0]['TIPO_IDENTIFICACION']:''); 
            
            $datos_contratista =$this->consultarExisteDatosContratista($cod_contratista,$tipo_id_contratista);
            if(!is_array($datos_contratista)){
                $interno_prov = (isset($contrato[0]['INTERNO_PROVEEDOR'])?$contrato[0]['INTERNO_PROVEEDOR']:''); 
                $unidad_ejec = (isset($contrato[0]['CODIGO_UNIDAD_EJECUTORA'])?$contrato[0]['CODIGO_UNIDAD_EJECUTORA']:''); 
                $cod_contrato = (isset($contrato[0]['NUM_CONTRATO'])?$contrato[0]['NUM_CONTRATO']:''); 
            
                $this->insertarDatosContratista($cod_contratista,$tipo_id_contratista,$interno_prov);
                $this->insertarDatosContrato($vigencia_contrato,$cod_contrato,$unidad_ejec,$interno_oc ,$cod_contratista,$tipo_id_contratista);
                
            }
            
            $this->revisarDatosActaInicio($contrato);
            $dias_contrato= $this->calcularDiasContrato($contrato[0]['FECHA_INICIO'], $contrato[0]['FECHA_FINAL']);
            $contrato[0]['CANTIDAD_DIAS']=$dias_contrato;
            $tipo = $this->consultarConceptosNomina();
            if(is_array($tipo)){    
                $tipo_nomina[0][0]="0";
                $tipo_nomina[0][1]=" ";
                $indice=1;
                foreach ($tipo as $tp_nomina) {
                    $tipo_nomina[$indice][0]=$tp_nomina['cno_codigo'];
                    $tipo_nomina[$indice][1]=$tp_nomina['cno_nombre'];
                    $indice++;
                }
            }else{
                $tipo_nomina = array(0=>'0');
            
            }
            //var_dump($contrato);
            $contrato[0]['NUM_CONTRATO']=(isset($contrato[0]['NUM_CONTRATO'])?$contrato[0]['NUM_CONTRATO']:'');
            if($contrato[0]['NUM_CONTRATO'] && $vigencia_contrato){
                $acta = $this->consultarActaInicio($contrato[0]['NUM_CONTRATO'], $vigencia_contrato);
                $contratista = $this->consultarAspectoContratista($contrato[0]['TIPO_IDENTIFICACION'], $contrato[0]['NUM_IDENTIFICACION']);
                $this->htmlActaInicio->form_acta($tipo_nomina,$contrato,$acta,$contratista,"", "");
            
            }else{
                $mensaje = "No se encuentra registrado el número de contrato, necesario para registrar el acta de inicio";
                $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                $variable="pagina=nom_adminNovedad";
                $variable.="&opcion=consultar";
                $variable.="&vigencia=".$vigencia_contrato;
                $variable=$this->cripto->codificar_url($variable,$this->configuracion);
                $this->retornar($pagina,$variable,$mensaje);
                
            }
            
   }        
   
    /**
    * Funcion que consulta los conceptos de nomina
    * @return <array>  
    */
   function consultarConceptosNomina(){
                           
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_nomina,"conceptos_nomina","");
            return $resultado = $this->ejecutarSQL($this->configuracion, $this->acceso_nomina, $cadena_sql, "busqueda");
    }
        
    
       
    /**
     * Funcion que captura los datos y valida para realizar el registro de la acta de inicio en el sistema 
     */
    function registrarActaInicio(){
            $mensaje='';
            $cod_contrato=(isset($_REQUEST['cod_contrato'])?$_REQUEST['cod_contrato']:''); 
            $interno_oc=(isset($_REQUEST['interno_oc'])?$_REQUEST['interno_oc']:''); 
            $vigencia_contrato=(isset($_REQUEST['vigencia_contrato'])?$_REQUEST['vigencia_contrato']:''); 
            $fecha=date('Y-m-d');
            $fecha_ini=(isset($_REQUEST['finicial'])?$_REQUEST['finicial']:''); 
            $fecha_fin=(isset($_REQUEST['ffinal'])?$_REQUEST['ffinal']:''); 
            $fecha_firma=(isset($_REQUEST['facta'])?$_REQUEST['facta']:''); 
            $tipo_nomina=(isset($_REQUEST['id_tipo'])?$_REQUEST['id_tipo']:'');
            $regimen=(isset($_REQUEST['regimen'])?$_REQUEST['regimen']:'');
            $declarante=(isset($_REQUEST['declarante'])?$_REQUEST['declarante']:'');
            $pensionado=(isset($_REQUEST['pensionado'])?$_REQUEST['pensionado']:'');
            $pasante=(isset($_REQUEST['pasante'])?$_REQUEST['pasante']:'');
            $estado = 'A';
            $id=$this->obtenerNumeroActaInicio();
            $existe_acta = $this->verificaExisteActaInicio($cod_contrato,$vigencia_contrato);
            $contrato = $this->consultarDatosContrato($interno_oc,$vigencia_contrato);
            $disponibilidad = $this->consultarDatosDisponibilidad($contrato[0]['INTERNO_MC'],$contrato[0]['CODIGO_UNIDAD_EJECUTORA'],$vigencia_contrato);
            $nro_cdp = $disponibilidad[0]['NUMERO_DISPONIBILIDAD']; 
            $ordenPago = $this->consultarDatosOrdenPago($contrato[0]['NUM_IDENTIFICACION'],$nro_cdp,$vigencia_contrato);
            //var_dump($contrato);exit;
            $tipo_id = $contrato[0]['TIPO_IDENTIFICACION'];
            $identificacion = $contrato[0]['NUM_IDENTIFICACION'];
            //var_dump($_REQUEST);
            if($fecha_ini && $fecha_fin && $fecha_firma && $tipo_nomina && $regimen && $declarante && $pensionado && $pasante){
                    $modificar_fechas='';
                    //procedimiento para SICapital
                    if(!is_array($ordenPago) || !$contrato[0]['FECHA_INICIO'] || !$contrato[0]['FECHA_FINAL'] ){
              //              $modificar_fechas = $this->modificarFechasSIC($interno_oc,$vigencia_contrato,$fecha_ini,$fecha_fin);
                    }
                    
                    //procedimiento sistema web nomina 
                    //proceso para la tabla de acta
                    if(!$existe_acta){
                            $insertado = $this->insertarActaInicio($id,$cod_contrato,$vigencia_contrato,$fecha,$fecha_ini,$fecha_fin,$fecha_firma,$estado,$tipo_nomina);

                    }else{
                            $modificar_acta = $this->inactivarActaInicio($existe_acta);
                            if($modificar_acta){
                                //VARIABLES PARA EL LOG
                                        $registro[0] = "INACTIVAR";
                                        $registro[1] = $existe_acta;
                                        $registro[2] = "ACTA_INICIO";
                                        $registro[3] = $existe_acta;
                                        $registro[4] = time();
                                        $registro[5] = "Modifica estado acta de inicio ". $existe_acta;
                                        $registro[5] .= " - vigencia =". $vigencia_contrato;
                                        $registro[5] .= " - cod_contrato =". $cod_contrato;
                                        $this->log_us->log_usuario($registro,$this->configuracion);
                            }
                            $insertado = $this->insertarActaInicio($id,$cod_contrato,$vigencia_contrato,$fecha,$fecha_ini,$fecha_fin,$fecha_firma,$estado,$tipo_nomina);

                    }
                    
                    if($insertado){
                        //$this->generarDocumentoActaInicio($fecha_ini, $fecha_fin, $fecha_firma,$contrato);
                                    
                        //VARIABLES PARA EL LOG
                                $registro[0] = "INSERTAR";
                                $registro[1] = $id;
                                $registro[2] = "ACTA_INICIO";
                                $registro[3] = $id;
                                $registro[4] = time();
                                $registro[5] = "Insertar acta de inicio ". $id;
                                $registro[5] .= " - vigencia =". $vigencia_contrato;
                                $registro[5] .= " - cod_contrato =". $cod_contrato;
                                $this->log_us->log_usuario($registro,$this->configuracion);
                    }
                    if($modificar_fechas){
                        //VARIABLES PARA EL LOG
                                $registro[0] = "MODIFICAR";
                                $registro[1] = $interno_oc;
                                $registro[2] = "FECHAS_ACTA";
                                $registro[3] = $interno_oc;
                                $registro[4] = time();
                                $registro[5] = "Modifica fechas de acta de inicio en tabla de legalizacion interno_oc= ". $interno_oc;
                                $registro[5] .= " - vigencia =". $vigencia_contrato;
                                $registro[5] .= " - cod_contrato =". $cod_contrato;
                                $registro[5] .= " - fecha inicial =". $fecha_ini;
                                $registro[5] .= " - fecha final =". $fecha_fin;
                                $this->log_us->log_usuario($registro,$this->configuracion);
                    }
                    
                    // proceso para tabla de contratista
                    $contratista_modificado = $this->modificarAspectosContratista($tipo_id, $identificacion,$regimen, $declarante, $pensionado, $pasante);
                    if($contratista_modificado){
                        //VARIABLES PARA EL LOG
                                $registro[0] = "MODIFICAR";
                                $registro[1] = $interno_oc;
                                $registro[2] = "CONTRATISTA";
                                $registro[3] = $interno_oc;
                                $registro[4] = time();
                                $registro[5] = "Modifica aspectos regimen comun, declarante, pensionado y pasante, ";
                                $registro[5] .= " tipo_id =". $tipo_id;
                                $registro[5] .= " - identificacion =". $identificacion;
                                $this->log_us->log_usuario($registro,$this->configuracion);
                    }
            }
           // exit;
            $this->verificarRegistroActaInicio($insertado,$vigencia_contrato,$cod_contrato,$mensaje);
            
                    
    }
    
     /**
     * Funcion que inserta en la base de datos el registro de una acta de inicio
     */
    function insertarActaInicio($id,$cod_contrato,$vigencia_contrato,$fecha,$fecha_ini,$fecha_fin,$fecha_firma,$estado,$tipo_nomina){
            $datos_acta = array('id'=>$id,
                                'cod_contrato'=>$cod_contrato,
                                'vigencia_contrato'=>$vigencia_contrato,
                                'fecha'=>$fecha,
                                'fecha_ini'=>$fecha_ini,
                                'fecha_fin'=>$fecha_fin,
                                'fecha_firma'=>$fecha_firma,
                                'estado'=>$estado,
                                'tipo_nomina'=>$tipo_nomina);
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_nomina,"insertar_acta_inicio",$datos_acta);
            //echo "<br>cadena ".$cadena_sql;
            $this->ejecutarSQL($this->configuracion, $this->acceso_nomina, $cadena_sql, "");
            return $this->totalAfectados($this->configuracion, $this->acceso_nomina);
    }

     /**
     * Funcin que retorna un numero identificador consecutivo, para el acta de inicio
     * @return int 
     */
    function obtenerNumeroActaInicio(){
        $numero = $this->consultarUltimoNumeroActaInicio();
        $numero++;
        return $numero;
    }

    
    /**
     * Funcion que consulta el ultimo numero de acta de inicio que se encuentre registrado en la base de datos
     * 
     * @return type 
     */
    function consultarUltimoNumeroActaInicio(){

            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_nomina,"ultimo_numero_acta_inicio","");
            $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_nomina, $cadena_sql, "busqueda");
            return $datos[0][0];
    }

    
    function verificaExisteActaInicio($cod_contrato,$vigencia_contrato){
        $existe = 0;
        if($cod_contrato && $vigencia_contrato){
            $acta = $this->consultarActaInicio($cod_contrato, $vigencia_contrato);
        
            if($acta[0]['aci_id']>0 ){
                $existe=$acta[0]['aci_id'];
            }
        }
        return $existe;
    }
    
    
    function consultarActaInicio($cod_contrato,$vigencia_contrato){
        $datos = array('vigencia_contrato'=>$vigencia_contrato,
                            'cod_contrato'=>$cod_contrato);
        $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_nomina,"acta_inicio",$datos);
        return $datos_contrato = $this->ejecutarSQL($this->configuracion, $this->acceso_nomina, $cadena_sql, "busqueda");
	
    }
	
    
    function inactivarActaInicio($id_acta){

            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_nomina,"inactivar_acta_inicio",$id_acta);
            // echo "cadena ".$cadena_sql;//exit;
            $this->ejecutarSQL($this->configuracion, $this->acceso_nomina, $cadena_sql, "");
            return $this->totalAfectados($this->configuracion, $this->acceso_nomina);
    }
   
    
      /**
     * 
     * @param int $insertado
     * @param int $vigencia
     * @param int $cod_contrato 
     */
    function verificarRegistroActaInicio($insertado,$vigencia,$cod_contrato,$mensaje){
       if ($insertado>0){
                $mensaje = "Acta de inicio registrada con exito";
                $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                $variable="pagina=nom_adminNovedad";
                $variable.="&opcion=consultar";
                $variable.="&vigencia=".$vigencia;
                $variable.="&cod_contrato=".$cod_contrato;
                
           }else{
                $mensaje = "Error al registrar Acta de inicio - ".$mensaje;
                $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                $variable="pagina=nom_adminNovedad";
                $variable.="&opcion=consultar";
                $variable.="&vigencia=".$vigencia;
                $variable.="&cod_contrato=".$cod_contrato;
                
            }
            

            $variable=$this->cripto->codificar_url($variable,$this->configuracion);
            $this->retornar($pagina,$variable,$mensaje);

   }
   
   
      /**
     * Funcion que consulta en la base de datos informacion del contrato
     * @param int $cod_contrato
     * @param int $vigencia
    */
    function consultarDatosContrato($cod_contrato,$vigencia){
        //busca si existen registro de datos de usuarios en la base de datos 
            $datos = array('vigencia'=>$vigencia,
                            'interno_oc'=>$cod_contrato);
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_sic,"datos_contrato",$datos);
            //echo "<br>cadena ".$cadena_sql;
            return $datos_contrato = $this->ejecutarSQL($this->configuracion, $this->acceso_sic, $cadena_sql, "busqueda");
			
    }
    
    function revisarDatosActaInicio($datos_contrato){
        
            $cod_contrato = (isset($datos_contrato[0]['NUM_CONTRATO'])?$datos_contrato[0]['NUM_CONTRATO']:'');
            $vigencia = (isset($datos_contrato[0]['VIGENCIA'])?$datos_contrato[0]['VIGENCIA']:'');
            if($cod_contrato && $vigencia){
                $acta =$this->consultarExisteDatosActa($cod_contrato,$vigencia);
                if(!$acta[0][0] && $datos_contrato[0]['FECHA_INICIO'] && $datos_contrato[0]['FECHA_FINAL']){
                    $fecha=date('Y-m-d');
                    $fecha_ini=$datos_contrato[0]['FECHA_INICIO']; 
                    $fecha_fin=$datos_contrato[0]['FECHA_FINAL']; 
                    $fecha_firma=$datos_contrato[0]['FECHA_INICIO']; 
                    $estado = 'A';
                    $id=$this->obtenerNumeroActaInicio();
                    $tipo_nomina='0';
                    $relacionado = $this->insertarActaInicio($id,$cod_contrato,$vigencia,$fecha,$fecha_ini,$fecha_fin,$fecha_firma,$estado,$tipo_nomina);
                }
            }
    }
    
    /**
     * Funcion para consultar si existen datos de acta en la base de aplicativo de nomina
     * @param int $cod_contrato
     * @param int $vigencia
     * @return <array> 
     */
    function consultarExisteDatosActa($cod_contrato,$vigencia){
            $datos = array('num_contrato'=>$cod_contrato,
                            'vigencia'=>$vigencia);
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_nomina,"existe_datos_acta",$datos);
            //echo "<br>cadena ".$cadena_sql;
            return $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_nomina, $cadena_sql, "busqueda");
            
    }
    
     /**
     * Funcion para calcular la cantidad de días de un contrato a partir de la fecha inicial y fecha final de este
     * @param date $fecha_inicio
     * @param date $fecha_fin
     * @return int 
     */
    function calcularDiasContrato($fecha_inicio,$fecha_fin){
        if(strtotime($fecha_inicio) > strtotime($fecha_fin)){
                echo "ERROR -> la fecha inicial es mayor a la fecha final <br>";
               exit();
        }else{
                    
                $dia_inicio= substr($fecha_inicio, 8,2);
                $dia_fin= substr($fecha_fin, 8,2);
                
                $dias_mes_inicial = 30 - $dia_inicio + 1;
                $dias_mes_final = $dia_fin;
                $meses=$this->calcularCantidadMeses($fecha_inicio,$fecha_fin);
                $meses=(int)$meses-1;
                $dias_meses = $meses*30;
     
                $dias= $dias_mes_inicial + $dias_meses + $dias_mes_final;
        }
      
        return $dias;
    }
    
        /**
     * Funcion para calcular la cantidad de meses entre 2 fechas
     * @param date $fecha_inicio
     * @param date $fecha_fin
     * @return int 
     */
    function calcularCantidadMeses($fecha_inicio,$fecha_fin){
        $dia_inicio= substr($fecha_inicio, 8,2);
        $mes_inicio= substr($fecha_inicio, 5,2);
        $ano_inicio= substr($fecha_inicio, 0,4);

        $dia_fin= substr($fecha_fin, 8,2);
        $mes_fin= substr($fecha_fin, 5,2);
        $ano_fin= substr($fecha_fin, 0,4);
        $dif_anios = $ano_fin- $ano_inicio;
                if($dif_anios == 1){
                    $mes_inicio = 12 - $mes_inicio;
                    $meses = $mes_fin + $mes_inicio;
                   
                   
                }
                else{
                        if($dif_anios == 0){
                            $meses=$mes_fin - $mes_inicio;
                           
                            
                        }
                        else{
                            if($dif_anios > 1){
                                $mes_inicio = 12 - $mes_inicio;
                                $meses = $mes_fin + $mes_inicio + (($dif_anios - 1) * 12);
                                
                            }
                            else { exit;    }
                        }
                    }
                    return $meses;
    }
    
     /**
     * Funcion que consulta en la base de datos informacion de la disponibilidad de un contrato
     * @param int $cod_interno_minuta_contrato
     * @param int $cod_unidad_ejecutora
     * @param int $vigencia
    */
    function consultarDatosDisponibilidad($cod_interno_minuta_contrato, $cod_unidad_ejecutora, $vigencia){
            $datos = array('vigencia'=>$vigencia,
                            'cod_unidad_ejecutora'=>$cod_unidad_ejecutora,
                            'cod_minuta_contrato'=>$cod_interno_minuta_contrato);
            
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_sic,"datos_disponibilidad",$datos);
            return $datos_disponibilidad = $this->ejecutarSQL($this->configuracion, $this->acceso_sic, $cadena_sql, "busqueda");
    
    }
    
     /**
     * Funcion que consulta en la base de datos informacion de ordenes de pago
     * @param int $identificacion
     * @param int $num_disponibilidad
     * @param int $vigencia
    */
    function consultarDatosOrdenPago($identificacion,$num_disponibilidad,$vigencia){
            $datos = array( 'identificacion'=>$identificacion,
                            'num_disponibilidad'=>$num_disponibilidad,
                            'vigencia'=>$vigencia
                            );
            
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_sic,"datos_orden_pago",$datos);
           // echo "cadena ".$cadena_sql;exit;
            return $datos_disponibilidad = $this->ejecutarSQL($this->configuracion, $this->acceso_sic, $cadena_sql, "busqueda");
       
    }
    
    function modificarFechasSIC($interno_oc,$vigencia,$fecha_ini,$fecha_fin){
            $datos = array( 'interno_oc'=>$interno_oc,
                            'vigencia'=>$vigencia_contrato,
                            'fecha_ini'=>$fecha_ini,
                            'fecha_fin'=>$fecha_fin
                            );
            
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_sic,"actualizar_fechas_minuta_legalizacion",$datos);
            //echo "cadena ".$cadena_sql;//exit;
            $this->ejecutarSQL($this->configuracion, $this->acceso_sic, $cadena_sql, "");
            return $this->totalAfectados($this->configuracion, $this->acceso_sic);
        
    }
    
       
    /**
     * Funcion que muestra un mensaje y llama un metodo para retornar a una página
     * @param String $pagina
     * @param String $variable
     * @param String $mensaje 
     */
    function retornar($pagina,$variable,$mensaje=""){
        if($mensaje=="")
        {
          
        }
        else
        {
          echo "<script>alert ('".$mensaje."');</script>";
        }
        $this->enlaceParaRetornar($pagina, $variable);
    }

     /**
     * Funcion que retorna a una pagina 
     * @param <string> $pagina
     * @param <string> $variable
     */
    function enlaceParaRetornar($pagina,$variable) {
        echo "<script>location.replace('".$pagina.$variable."')</script>";
        exit;
    }
    
    
    function modificarAspectosContratista($tipo_id, $identificacion,$regimen, $declarante, $pensionado, $pasante){
            $datos = array( 'tipo_id'=>$tipo_id,
                            'identificacion'=>$identificacion,
                            'regimen'=>$regimen,
                            'declarante'=>$declarante,
                            'pensionado'=>$pensionado,
                            'pasante'=>$pasante
                            
                            );
            
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_nomina,"actualizar_aspectos_contratista",$datos);
            //echo "cadena ".$cadena_sql;//exit;
            $this->ejecutarSQL($this->configuracion, $this->acceso_nomina, $cadena_sql, "");
            return $this->totalAfectados($this->configuracion, $this->acceso_nomina);
        
        
    }
    
    /**
     * Funcion para consultar los aspectos del contratista relacionados a regimen, si es declarante, pensionado o pasante
     * @param String $tipo_id
     * @param int $identificacion
     * @return <array> 
     */
    function consultarAspectoContratista($tipo_id,$identificacion){
        $datos = array('tipo_id'=>$tipo_id,
                            'identificacion'=>$identificacion);
        $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_nomina,"aspectos_contratista",$datos);
        //echo "<br>cadena ".$cadena_sql ;
        return $datos_contrato = $this->ejecutarSQL($this->configuracion, $this->acceso_nomina, $cadena_sql, "busqueda");
	
    }
    
    
    function generarDocumentoActaInicio($fecha_ini, $fecha_fin, $fecha_firma,$contrato){
           $tipo_documento=3;
           //var_dump($_REQUEST);exit;
           
           $parametro_sql=array('CEDULA'=>$contrato[0]['NUM_IDENTIFICACION'],
                                'VIGENCIA'=>$contrato[0]['VIGENCIA'],
                                'FECHA_INICIACION'=>$fecha_ini,
                                'FECHA_TERMINACION'=>$fecha_fin,
                                'FECHA_FIRMA'=>$fecha_firma,
                                'COD_SUPERVISOR'=>$contrato[0]['FUNCIONARIO']
                                
                                );
            //var_dump($parametro_sql);
           $cod_archivo=$contrato[0]['NUM_IDENTIFICACION']."_".$contrato[0]['VIGENCIA'];
           include_once($this->configuracion["raiz_documento"] . $this->configuracion["bloques"]."/nomina/contratistas/nom_admin_acta_inicio". $this->configuracion["clases"] . "/crearDocumento.class.php");
                $this->Documento = new crearDocumento($this->configuracion);
                $this->Documento->crearDocumento($tipo_documento,$parametro_sql,$cod_archivo);
       
       }
       
 /**
     * Funcion 
     * @param type $cod_contratista
     * @param type $tipo_id
     * @return type 
     */
    function consultarExisteDatosContratista($cod_contratista,$tipo_id){
            $datos = array('cod_contratista'=>$cod_contratista,
                                'tipo_id'=>$tipo_id);
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_nomina,"existe_datos_contratista",$datos);
            return $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_nomina, $cadena_sql, "busqueda");
            
    }
    
 /**
     * Funcion para insertar el registro de un contratista en la base de datos
     * @param String $cod_contratista
     * @param String $tipo_id_contratista
     * @param int $interno_prov
     * @return int 
     */
    function insertarDatosContratista($cod_contratista,$tipo_id_contratista,$interno_prov){
            $datos = array('tipo_id'=>$tipo_id_contratista,
                                'cod_contratista'=>$cod_contratista,
                                'interno_prov'=>$interno_prov);
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_nomina,"insertar_datos_contratista",$datos);
            $this->ejecutarSQL($this->configuracion, $this->acceso_nomina, $cadena_sql, "");
            return $this->totalAfectados($this->configuracion, $this->acceso_nomina);
    }
           
    /**
     * Funcion para insertar el registro del contrato en la base de datos 
     * @param int $vigencia
     * @param int $cod_contrato
     * @param int $unidad_ejec
     * @param int $interno_oc
     * @param String $cod_contratista
     * @param String $tipo_id_contratista
     * @return type 
     */
    function insertarDatosContrato($vigencia,$cod_contrato,$unidad_ejec,$interno_oc ,$cod_contratista,$tipo_id_contratista){
            $datos = array('vigencia'=>$vigencia,
                                'cod_contrato'=>$cod_contrato,
                                'unidad_ejec'=>$unidad_ejec,
                                'interno_oc'=>$interno_oc,
                                'tipo_id'=>$tipo_id_contratista,
                                'cod_contratista'=>$cod_contratista);
                            
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_nomina,"insertar_datos_contrato",$datos);
            $this->ejecutarSQL($this->configuracion, $this->acceso_nomina, $cadena_sql, "");
            return $this->totalAfectados($this->configuracion, $this->acceso_nomina);
    }
    
} // fin de la clase
	

?>


                
                