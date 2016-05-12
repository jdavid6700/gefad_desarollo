<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/
/*---------------------------------------------------------------------------------------
 |				Control Versiones				    	|	
 ----------------------------------------------------------------------------------------
 | fecha      |        Autor            | version     |              Detalle            |
 ----------------------------------------------------------------------------------------
 | 17/04/2013 | Maritza Callejas C.  	| 0.0.0.1     |                                 |
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
class funciones_adminSolicitudPagoSupervisor extends funcionGeneral
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
                
                $this->htmlNomina = new html_adminSolicitudPagoSupervisor($configuracion);   
                
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
			case "multiplesSolicitudesPago":
                                $datos_documento = $this->consultarDocumento(2);
            			$this->htmlNomina->multiplesSolicitudesPago($configuracion,$registro,$datos_documento);
				break;
		
		}
		
	}
	
		
/*__________________________________________________________________________________________________
		
						Metodos especificos 
__________________________________________________________________________________________________*/

    
  
  
        /**
         * Funcion para consultar solicitudes de pago 
         */
        function consultar(){
            $cod_supervisor=$this->obtenerCodigoInternoSupervisor($this->identificacion);
            $solicitudes = $this->consultarTodasSolicitudesPago($this->identificacion);
            $dependencias= $this->consultarDependencias();
            if($solicitudes){
                foreach ($solicitudes as $key => $solicitud) {
                    $cod_dependencia = $solicitud['sol_cod_dependencia'];
                    $dependencia= $this->consultarNombreDependencia($dependencias,$cod_dependencia);
                    $solicitudes[$key]['nombre_dependencia']=$dependencia;
                }
            }
            $this->mostrarListadoSolicitudesPago($solicitudes);
        }
       
         
        /**
        * Funcion para obtener el codigo de un supervisor a partir del numero de identificacion del usuario
        * @param int $identificacion
        * @return int 
        */
       function obtenerCodigoInternoSupervisor($identificacion){
           $codigo_supervisor=0;
           $resultado = $this->consultarDependenciaUsuario($identificacion);
           if($resultado){
               $dependencia=$resultado[0]['id_dependencia'];
               $codigo_supervisor=$this->consultarCodigoInternoSupervisor($dependencia);
           }
           return $codigo_supervisor;
       }
       
        /**
        * Funcion para consultar en la base de datos el codigo de la dependencia de un usuario
        * @param int $identificacion
        * @return <array> 
        */
       function consultarDependenciaUsuario($identificacion){
           $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_db,"codigo_dependencia",$identificacion);
           return $resultado = $this->ejecutarSQL($this->configuracion, $this->acceso_db, $cadena_sql, "busqueda");
           
       }
       
       /**
        * Funcion para consultar el codigo interno del supervisor de una dependencia
        * @param int $dependencia
        * @return int
        */
       function consultarCodigoInternoSupervisor($dependencia){
           $codigo_supervisor=0;
           $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_sic,"codigo_supervisor",$dependencia);
           $resultado = $this->ejecutarSQL($this->configuracion, $this->acceso_sic, $cadena_sql, "busqueda");
           if($resultado){
               $codigo_supervisor=$resultado[0]['COD_JEFE'];
           }
           return $codigo_supervisor;
       }
       
    /**
     * Funcion que consulta los registros de las solicitudes de pago de contratistas
     * @param int $vigencia
     * @param int $cod_contrato
     * @return <array> 
     */
    function consultarTodasSolicitudesPago($id_supervisor){
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_nomina,"solicitudes_pago",$id_supervisor);
            $resultado = $this->ejecutarSQL($this->configuracion, $this->acceso_nomina, $cadena_sql, "busqueda");
            return $resultado;
    }
    
     /**
     * Funcion que recorre el arreglo con las solicitudes de pago y llama al metodo de mostrar la información relacionada
     * @param <array> $registro 
     */
    function mostrarListadoSolicitudesPago($registro){
       
        if(is_array($registro)){
                  
               $this->mostrarRegistro($this->configuracion,$registro, $this->configuracion['registro'], "multiplesSolicitudesPago", "");

        }else{
            echo "No tiene solicitudes de cumplido registradas";
        }
    }
    
     /**
     * Funcion para consultar los detalles de una solicitud de pago 
     */
    function consultarListadoDetalles(){
            $id_solicitud=(isset($_REQUEST['id_solicitud'])?$_REQUEST['id_solicitud']:$_REQUEST['id_solicitud']);
            $detalles = $this->consultarDetallesSolicitudesPago($id_solicitud);
                if($detalles ){
                foreach ($detalles as $key =>  $detalle) {
                     
                    $tipo_id_contratista = $detalle['cto_con_tipo_id'];
                    $cto_interno_co = $detalle['cto_interno_co'];
                    $cto_uni_ejecutora = $detalle['cto_uni_ejecutora'];
                    $id = $detalle['dtn_id'];
                    $cum_cto_vigencia = $detalle['dtn_cum_cto_vigencia'];
                    $cum_id = $detalle['dtn_cum_id'];
                    
                    $num_id_contratista = $detalle['cto_con_num_id'];
                    $datos_contratista = $this->consultarDatosContratista($num_id_contratista);
                    $datos_contrato = $this->consultarDatosContrato($cto_interno_co, $cum_cto_vigencia);
                    $cod_interno_minuta_contrato = $datos_contrato[0]['INTERNO_MC'];
                    $datos_disponibilidad = $this->consultarDatosDisponibilidad($cod_interno_minuta_contrato, $cto_uni_ejecutora, $cum_cto_vigencia);
                    $num_cdp = $datos_disponibilidad[0]['NUMERO_DISPONIBILIDAD'];
                    $datos_registro = $this->consultarDatosRegistroPresupuestal($num_cdp, $cto_uni_ejecutora, $cum_cto_vigencia);
                    
                    //Armamos el arreglo con la informacion del reporte
                    $registro[$key]['no_c.c_o_nit'] = $num_id_contratista;
                    $registro[$key]['primer_apellido'] = $datos_contratista[0]['PRIMER_APELLIDO'];
                    $registro[$key]['segundo_apellido'] = $datos_contratista[0]['SEGUNDO_APELLIDO'];
                    $registro[$key]['primer_nombre'] = $datos_contratista[0]['PRIMER_NOMBRE'];
                    $registro[$key]['segundo_nombre'] = (isset($datos_contratista[0]['SEGUNDO_NOMBRE'])?$datos_contratista[0]['SEGUNDO_NOMBRE']:'');
                    $registro[$key]['tipo_contrato'] = $detalle['cto_tipo_contrato'];
                    $registro[$key]['vigencia_contrato'] = $detalle['cto_vigencia'];
                    $registro[$key]['numero_contrato'] = $detalle['cto_num'];
                    $registro[$key]['C.D.P._No'] = $num_cdp;
                    $registro[$key]['R.P._No'] = $datos_registro[0]['NUMERO_REGISTRO'];
                    $registro[$key]['codigo_banco'] = $detalle['ban_id'];
                    $registro[$key]['nombre_banco'] = $detalle['ban_nombre'];
                    $registro[$key]['tipo_cuenta'] = $detalle['cta_tipo'];
                    $registro[$key]['cuenta_No'] = $detalle['cta_num'];
                    $registro[$key]['valor_contrato'] = $datos_contrato[0]['CUANTIA'];
                    $registro[$key]['fecha_inicio'] = $datos_contrato[0]['FECHA_INICIO'];
                    $registro[$key]['fecha_final'] = $datos_contrato[0]['FECHA_FINAL'];
                    $registro[$key]['valor_saldo_antes_de_pago'] = $detalle['dtn_saldo_antes_pago'];
                    $registro[$key]['fecha_inicio_periodo'] = $detalle['dtn_fecha_inicio_per'];
                    $registro[$key]['fecha_corte_periodo'] = $detalle['dtn_fecha_final_per'];
                    $registro[$key]['dias_pagados'] = $detalle['dtn_num_dias_pagados'];
                    $registro[$key]['valor_liquidacion_antes_iva'] = $detalle['dtn_valor_liq_antes_iva'];
                    $registro[$key]['valor_arp'] = $detalle['dtn_arp'];
                    $registro[$key]['valor_afc'] = $detalle['dtn_afc'];
                    $registro[$key]['valor_salud'] = $detalle['dtn_salud'];
                    $registro[$key]['valor_pension'] = $detalle['dtn_pension'];
                    $registro[$key]['valor_cooperativas_depositos'] = $detalle['dtn_cooperativas_depositos'];
                                
                }
                $this->htmlNomina->multiplesDetallesSolicitudPago($this->configuracion, $registro);
            }
            //var_dump($registro);exit;
           
            
        }
        
    
        /**
         * Funcion para consultar los detalles de solicitud de pago
         * @param int $id_nomina
         * @return <array> 
         */
        function consultarDetallesSolicitudesPago($id_solicitud){
            
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_nomina,"detalle_solicitud_pago",$id_solicitud);
            //echo "<br>cadena ".$cadena_sql;exit;
            $resultado = $this->ejecutarSQL($this->configuracion, $this->acceso_nomina, $cadena_sql, "busqueda");
            return $resultado;
    }
      
    /**
     * Funcion que consulta en la base de datos informacion del contratista
     * @param int $identificacion
    */
   function consultarDatosContratista($identificacion){
            
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_sic,"datos_contratista",$identificacion);
            return $datos_disponibilidad = $this->ejecutarSQL($this->configuracion, $this->acceso_sic, $cadena_sql, "busqueda");
       
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
            return $datos_contrato = $this->ejecutarSQL($this->configuracion, $this->acceso_sic, $cadena_sql, "busqueda");
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
     * Funcion que consulta en la base de datos informacion del registro presupuestal de un contrato
     * @param int $nro_cdp
     * @param int $cod_unidad_ejecutora
     * @param int $vigencia
    */
   function consultarDatosRegistroPresupuestal($nro_cdp, $cod_unidad_ejecutora, $vigencia){
            $datos = array( 'nro_cdp'=>$nro_cdp,
                            'cod_unidad_ejecutora'=>$cod_unidad_ejecutora,
                            'vigencia'=>$vigencia
                            );
            
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_sic,"datos_registro",$datos);
            return $datos_disponibilidad = $this->ejecutarSQL($this->configuracion, $this->acceso_sic, $cadena_sql, "busqueda");
    
    }
  
    
    /**
     * Funcion para listar los cumplidos aprobados y seleccionar los cumplidos para la solicitud de pago 
     */
    function revisarCumplidosAprobados(){
          
        include_once($this->configuracion["raiz_documento"] . $this->configuracion["bloques"]."/nomina/contratistas/nom_admin_cumplido_supervisor". $this->configuracion["clases"] . "/liquidacionNomina.class.php");
        $registro='';
        $solicitudes = $this->consultarCumplidosAprobados($this->identificacion);
        $dependencia = $this->consultarDependenciaUsuario($this->identificacion);
        //var_dump($solicitudes);exit;
        if(is_array($solicitudes)){
                foreach ($solicitudes as $key => $solicitud) {
                    
                    $cto_interno_co = $solicitud['interno_oc'];
                    $cto_uni_ejecutora = $solicitud['unidad_ejecutora'];
                    $cum_cto_vigencia = $solicitud['vigencia'];
                    
                    $num_id_contratista = $solicitud['num_id_contratista'];
                    $datos_contratista = $this->consultarDatosContratista($num_id_contratista);
                    $datos_contrato = $this->consultarDatosContrato($cto_interno_co, $cum_cto_vigencia);
                    
                    $cod_interno_minuta_contrato = $datos_contrato[0]['INTERNO_MC'];
                    $datos_disponibilidad = $this->consultarDatosDisponibilidad($cod_interno_minuta_contrato, $cto_uni_ejecutora, $cum_cto_vigencia);
                    $num_cdp = $datos_disponibilidad[0]['NUMERO_DISPONIBILIDAD'];
                    $datos_registro = $this->consultarDatosRegistroPresupuestal($num_cdp, $cto_uni_ejecutora, $cum_cto_vigencia);
                    $datos_ordenador = $this->consultarDatosOrdenador($datos_disponibilidad[0]['COD_ORDENADOR']);
                   
                    //Armamos el arreglo con la informacion 
                    $registro[$key]['cum_id'] = $solicitud['id'];
                    $registro[$key]['rubro_interno'] = $datos_disponibilidad[0]['INTERNO_RUBRO'];
                    $registro[$key]['identificacion'] = $num_id_contratista;
                    $registro[$key]['nombre_contratista'] = " ".$datos_contratista[0]['PRIMER_NOMBRE'];
                    $registro[$key]['nombre_contratista'] .= " ".(isset($datos_contratista[0]['SEGUNDO_NOMBRE'])?$datos_contratista[0]['SEGUNDO_NOMBRE']:'');
                    $registro[$key]['nombre_contratista'] .= " ".$datos_contratista[0]['PRIMER_APELLIDO'];
                    $registro[$key]['nombre_contratista'] .= " ".$datos_contratista[0]['SEGUNDO_APELLIDO'];
                    $registro[$key]['vigencia'] = $solicitud['vigencia'];
                    //$registro[$key]['tipo_contrato'] = $solicitud['tipo_contrato'];
                    $registro[$key]['numero_contrato'] = $solicitud['num_contrato'];
                    $registro[$key]['saldo_antes_pago'] = $solicitud['saldo_antes_pago'];
                    $registro[$key]['valor_contrato'] = $datos_contrato[0]['CUANTIA'];
                    $registro[$key]['finicio_contrato'] = $datos_contrato[0]['FECHA_INICIO'];
                    $registro[$key]['ffinal_contrato'] = $datos_contrato[0]['FECHA_FINAL'];
                    $registro[$key]['valor_cumplido'] = $solicitud['valor'];
                    //$registro[$key]['valor_saldo_antes_de_pago'] = $solicitud['valor'];
                    $registro[$key]['fecha_inicio_periodo'] = $solicitud['finicio_cumplido'];
                    $registro[$key]['fecha_final_periodo'] = $solicitud['ffinal_cumplido'];
                    $registro[$key]['dias_pagados'] = $solicitud['dias_cumplido'];
                    $registro[$key]['valor_arp'] = $solicitud['arp'];
                    $registro[$key]['valor_cooperativas_depositos'] = $solicitud['cooperativas_depositos'];
                    $registro[$key]['valor_afc'] = $solicitud['afc'];
                    $registro[$key]['valor_salud'] = $solicitud['salud'];
                    $registro[$key]['valor_pension'] = $solicitud['pension'];
                    $registro[$key]['cod_supervisor'] = $datos_contrato[0]['FUNCIONARIO'];
                    $registro[$key]['cod_dependencia_supervisor'] = $dependencia[0]['id_dependencia'];
                    $registro[$key]['cod_ordenador'] = $datos_ordenador[0]['COD_JEFE'];
                    $registro[$key]['id_ordenador'] = $datos_ordenador[0]['NUMERO_DOCUMENTO'];
                    
                }   
                   
            }
            $periodo_pago = $this->periodosPago();
            
            $this->htmlNomina->form_revisar_solicitud_pago($this->configuracion,$periodo_pago, $registro);
    
        }
    
      /**
       * Funcion para consultar los cumplidos aprobados relacionados a un supervisor
       * @param int $id_supervisor
       * @return <array> 
       */
        function consultarCumplidosAprobados($id_supervisor){
            
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_nomina,"cumplidos_aprobados",$id_supervisor);
            return $resultado= $this->ejecutarSQL($this->configuracion, $this->acceso_nomina, $cadena_sql, "busqueda");
	
    }
        
    
    /**
     * Funcion que retorna arreglo con los periodos para pago
     * @return <array> 
     */
    function periodosPago(){
            $anio_inicial=2009;
            $anio_final=date('Y');
            $mes_inicial='01';
            $mes_final=date('m');
            $fecha_inicial=$anio_inicial.$mes_inicial;
            $fecha_final=$anio_final.$mes_final;
            $indice=0;
            for($i=$anio_inicial;$i<=$anio_final;$i++){
                    for($mes=1;$mes<=12;$mes++){

                        if($mes<10)
                            $mes="0".$mes;
                        $fecha=$i.$mes;
                        if($fecha>=$fecha_inicial && $fecha<=$fecha_final){
                            $periodo[$indice][0]=$i.$mes;
                            $periodo[$indice][1]=$i."- ".$this->nombreMes($mes);
                            $indice++;
                        }
                    }
            }
            $periodo = $this->ordenarMeses($periodo);
            return $periodo;         
    }
    
    /**
     * Funcion que retorna el nombre de un mes de acuerdo a un número de mes
     * @param String $numero_mes
     * @return string 
     */
    function nombreMes($numero_mes){
        switch ($numero_mes) {
            case '01':
                $mes="ENERO";
                break;

            case '02':
                $mes="FEBRERO";
                break;

            case '03':
                $mes="MARZO";
                break;

            case '04':
                $mes="ABRIL";
                break;

            case '05':
                $mes="MAYO";
                break;

            case '06':
                $mes="JUNIO";
                break;

            case '07':
                $mes="JULIO";
                break;

            case '08':
                $mes="AGOSTO";
                break;

            case '09':
                $mes="SEPTIEMBRE";
                break;

            case '10':
                $mes="OCTUBRE";
                break;

            case '11':
                $mes="NOVIEMBRE";
                break;

            case '12':
                $mes="DICIEMBRE";
                break;

            default:
                 $mes="";
                break;
        }
            return $mes;
        
    }
    
    /**
     * Funcion que ordena descendentemente un arreglo de meses
     * @param <array> $meses
     * @return <array> 
     */
    function ordenarMeses($meses){
            foreach ($meses as $key => $fila) {
                    $ids[$key]  = $fila[0]; // columna de identificador
                    $nombres[$key] = $fila[1]; //columna de nombre
                }
            //ordenamos descendente por la columna elegida
            array_multisort($ids, SORT_DESC, $meses);
            return $meses;
    }
    
      
     /**
     * revisa las cumplidos aprobados y seleccionados para realizar el correspondiente registro en la base de datos con la solicitud de pago
     */
    function revisarSolicitudPagoNomina(){
        //var_dump($_REQUEST);exit;
        $aprobados=0;
        $registro='';
        $total=(isset($_REQUEST['total_registros'])?$_REQUEST['total_registros']:0);
        $periodo_pago=(isset($_REQUEST['periodo_pago'])?$_REQUEST['periodo_pago']:'');
        $anio_pago = substr($periodo_pago, 0,4);        
        $mes_pago = substr($periodo_pago, 4,2);        
        $cod_supervisor=(isset($_REQUEST['cod_supervisor_0'])?$_REQUEST['cod_supervisor_0']:'');
        $cod_dep_supervisor=(isset($_REQUEST['cod_dependencia_supervisor_0'])?$_REQUEST['cod_dependencia_supervisor_0']:'');
        
        if($total){
                $indice=0;
                    
                //buscamos y almacenamos en un arreglo los cumplidos seleccionados para la solicitud de pago
                for($i=0;$i<$total;$i++){
                    $modificado=0;
                    $nombre = "id_solicitud_".$i;
                    $nombre_rubro_interno = "rubro_interno_".$i;
                    $nombre_vigencia = "vigencia_".$i;
                    $nombre_cod_ordenador = "cod_ordenador_".$i;
                    $nombre_id_ordenador= "id_ordenador_".$i;
                    $nombre_valor_saldo_corte_pago = "valor_saldo_corte_pago_".$i;
                    $_REQUEST[$nombre]=(isset($_REQUEST[$nombre])?$_REQUEST[$nombre]:'');
                    $_REQUEST[$nombre_rubro_interno]=(isset($_REQUEST[$nombre_rubro_interno])?$_REQUEST[$nombre_rubro_interno]:'');
                    $_REQUEST[$nombre_vigencia]=(isset($_REQUEST[$nombre_vigencia])?$_REQUEST[$nombre_vigencia]:'');
                    $_REQUEST[$nombre_cod_ordenador]=(isset($_REQUEST[$nombre_cod_ordenador])?$_REQUEST[$nombre_cod_ordenador]:'');
                    $_REQUEST[$nombre_id_ordenador]=(isset($_REQUEST[$nombre_id_ordenador])?$_REQUEST[$nombre_id_ordenador]:'');
                    
                    if($_REQUEST[$nombre]){
                            
                            $existe = $this->existe_solicitud($_REQUEST[$nombre],$_REQUEST[$nombre_vigencia]);
                            if(!$existe){
                                    $registro[$indice]['id_cumplido']=$_REQUEST[$nombre];
                                    $registro[$indice]['vigencia_contrato']= $_REQUEST[$nombre_vigencia];
                                    $registro[$indice]['rubro_interno']=$_REQUEST[$nombre_rubro_interno];
                                    $registro[$indice]['cod_ordenador']=$_REQUEST[$nombre_cod_ordenador];
                                    $registro[$indice]['id_ordenador']=$_REQUEST[$nombre_id_ordenador];
                                    $indice++;
                            }
                    }
                    
                }
               
        }
        if($registro){
                $rubros = $this->obtenerRubroSolicitudes($registro);
                $datos_solicitudes = $this->obtenerNumeroSolicitudPago($rubros);
                $datos_solicitudes = $this->asignarDetallesSolicitudPago($datos_solicitudes,$registro);
                //recorremos los datos de las solicitudes para registrar en la BD las solicitudes y los correspondientes detalles
                foreach ($datos_solicitudes as $key => $datos_solicitud) {

                    $id_solicitud_pago=$datos_solicitud['num_sol_pago'];
                    $rubro_interno=$datos_solicitud['rubro_interno'];
                    $anio=$anio_pago;
                    $mes=$mes_pago;
                    $cod_supervisor=$cod_supervisor; 
                    $id_supervisor=$this->identificacion; 
                    $cod_dependencia=$cod_dep_supervisor;
                    $cod_ordenador=$datos_solicitud['cod_ordenador'];
                    $id_ordenador=$datos_solicitud['id_ordenador'];

                    $insertado = $this->insertarSolicitudPago($id_solicitud_pago,$rubro_interno,$anio, $mes, $cod_supervisor, $id_supervisor, $cod_ordenador, $id_ordenador,$cod_dependencia);
                    //VARIABLES PARA EL LOG
                    $registro[0] = "INSERTAR";
                    $registro[1] = $id_solicitud_pago;
                    $registro[2] = "SOLICITUD_PAGO_SUPERVISOR";
                    $registro[3] = $id_solicitud_pago;
                    $registro[4] = time();
                    $registro[5] = "Insertar solicitud de pago ". $id_solicitud_pago;
                    $registro[5] .= " - rubro =". $rubro_interno;
                    $registro[5] .= " - anio =". $anio;
                    $registro[5] .= " - mes =". $mes;
                    $this->log_us->log_usuario($registro,$this->configuracion);

                    if($insertado){
                            $datos_solicitudes[$key]['insertado']=1;
                            $detalles=$datos_solicitud['detalles'];

                            $cantidad_det = 0; 
                            foreach ($detalles as $detalle) {
                                $id_detalle= $this->consultarUltimoNumeroDetalleSolicitudPago()+1;
                                $id_cumplido= $detalle['id_cumplido'];
                                $vigencia = $detalle['vigencia_contrato'];
                                $modificado = $this->actualizarDetalleSolicitudPago($id_solicitud_pago,$id_detalle,$id_cumplido,$vigencia);
                                if($modificado>0){
                                        //VARIABLES PARA EL LOG
                                        $registro[0] = "INSERTAR";
                                        $registro[1] = $id_solicitud_pago;
                                        $registro[2] = "DETALLE_SOLICITUD_PAGO_SUPERVISOR";
                                        $registro[3] = $id_solicitud_pago;
                                        $registro[4] = time();
                                        $registro[5] = "Insertar detalle ".$id_detalle;
                                        $registro[5] .= " - solicitud de pago ". $id_solicitud_pago;
                                        $registro[5] .= " - id_cumplido =". $id_cumplido;
                                        $registro[5] .= " - vigencia =". $vigencia;

                                        $this->log_us->log_usuario($registro,$this->configuracion);

                                        $estado = $this->actualizarEstadoCumplido($id_cumplido,$vigencia,"PROCESADO_SUP");
                                        $cantidad_det++;
                                }
                            }
                            $datos_solicitudes[$key]['cantidad_detalles_insertados']=$cantidad_det;

                    }
                }
                //revisamos las solicitudes creadas y los detalles actualizados
                $mensaje='';
                foreach ($datos_solicitudes as $datos_solicitud) {
                    if($datos_solicitud['insertado']==1){
                        $aprobados++;
                        $mensaje .= "Solicitud No. ".$datos_solicitud['num_sol_pago']." registrada exitosamente para el rubro ".$datos_solicitud['rubro_interno'];
                        $mensaje .= ", ".$datos_solicitud['cantidad_detalles_insertados']." contratistas relacionados a la solicitud de pago. ";
                    }else{
                        $mensaje .= "<br>No se pudo realizar solicitud de pago para el rubro ".$datos_solicitud['rubro_interno'];

                    }
                    
                }
                if($aprobados>0){
                        $variable="pagina=nom_adminSolicitudPagoSupervisor";
                        $variable.="&opcion=revisar_cordis";
                }else{
                        $variable="pagina=nom_adminSolicitudPagoSupervisor";
                        $variable.="&opcion=revisarCumplidosAprobados";

                }
        }else{
                $mensaje = "No hay ningun registro valido para insertar solicitud de pago ";
                $variable="pagina=nom_adminSolicitudPagoSupervisor";
                $variable.="&opcion=revisarCumplidosAprobados";

        }
        //var_dump($datos_solicitudes);exit;
        
        $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
        
        $variable=$this->cripto->codificar_url($variable,$this->configuracion);
        $this->retornar($pagina,$variable,$mensaje);
 
        
    }
    
    /**
     * Funcion para sacar un arreglo con los numeros de rubros a partir de las solicitudes
     * @param type $registros
     * @return type 
     */
    function obtenerRubroSolicitudes($registros){
        $indice=0;
        $rubro= array();
        if($registros){
                foreach ($registros as $key => $registro) {
                    $existe = $this->buscarRubroInternoEnArreglo($rubro,$registro['rubro_interno']);
                    if($existe==0){
                        $rubro[$indice]['rubro_interno']= $registro['rubro_interno'];
                        $rubro[$indice]['cod_ordenador']= $registro['cod_ordenador'];
                        $rubro[$indice]['id_ordenador']= $registro['id_ordenador'];
                        $indice++;
                    }
                }
        }
        return $rubro;
    }
    
    
    /**
     * Funcion para asignar los numeros de solicitud de pago por cada rubro
     * @param <array> $rubros
     * @return <array> 
     */
    function obtenerNumeroSolicitudPago($rubros){
        if($rubros){
            $ultimo_numero = $this->consultarUltimoNumeroSolicitudPago();
            $numero = $ultimo_numero;    
            foreach ($rubros as $key => $rubro) {
                $numero++;
                $rubros[$key]['num_sol_pago']=$numero;
            }
        }
        return $rubros;
    }
    
    
    
    /**
     * Funcion para buscar en un arreglo un rubro interno y saber si ya existe
     * @param <array> $rubros
     * @param int $rubro_interno
     * @return int 
     */
    function buscarRubroInternoEnArreglo($rubros,$rubro_interno){
        $existe =0;
        foreach ($rubros as $rubro) {
            if($rubro['rubro_interno']==$rubro_interno){
                $existe=1;
            }
        }
        return $existe;
    }
    
    
    /**
     * Funcion para consultar el ultimo numero de solicitud de pago registrado
     * @return type 
     */    
    function consultarUltimoNumeroSolicitudPago(){

            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_nomina,"ultimo_numero_solicitud_pago","");
            $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_nomina, $cadena_sql, "busqueda");
            return $datos[0][0];
    }
    

    /**
     * Funcion para registrar en la BD la solicitud de pago realizada por el supervisor
     * @param int $id_solicitud_pago
     * @param int $rubro_interno
     * @param int $anio
     * @param int $mes
     * @param int $cod_supervisor
     * @param int $id_supervisor
     * @param int $cod_ordenador
     * @param int $id_ordenador
     * @param int $cod_dependencia
     * @return int 
     */
    function insertarSolicitudPago($id_solicitud_pago,$rubro_interno,$anio, $mes, $cod_supervisor, $id_supervisor, $cod_ordenador, $id_ordenador,$cod_dependencia){
            $datos = array('id_solicitud'=>$id_solicitud_pago,
                                'fecha_registro'=>date('Y-m-d'),
                                'rubro_interno'=>$rubro_interno,
                                'estado'=>'SOLICITADO',
                                'cordis'=>' ',
                                'observacion'=>' ',
                                'anio_pago'=>$anio,
                                'mes_pago'=>$mes,
                                'cod_supervisor'=>$cod_supervisor,
                                'id_supervisor'=>$id_supervisor,
                                'cod_ordenador'=>$cod_ordenador,
                                'id_ordenador'=>$id_ordenador,
                                'cod_dependencia'=>$cod_dependencia,
                                'estado_registro'=>'A'
                                );
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_nomina,"insertar_solicitud_pago",$datos);
             
            $this->ejecutarSQL($this->configuracion, $this->acceso_nomina, $cadena_sql, "");
            return $this->totalAfectados($this->configuracion, $this->acceso_nomina);
    }
    
    /**
     * Funcion que retorna las solicitudes con sus respectivos detalles relacionados
     * @param <array> $datos_solicitudes
     * @param <array> $registros
     * @return <array> 
     */
    function asignarDetallesSolicitudPago($datos_solicitudes,$registros){
        if($datos_solicitudes && $registros){
            foreach ($datos_solicitudes as $key => $datos_solicitud) {
                foreach ($registros as $registro) {
                    if($datos_solicitud['rubro_interno']==$registro['rubro_interno']){
                        $detalles = (isset($datos_solicitudes[$key]['detalles'])?$datos_solicitudes[$key]['detalles']:array());
                        $cantidad = count($detalles);
                        $detalles[$cantidad]['id_cumplido']=$registro['id_cumplido'];
                        $detalles[$cantidad]['vigencia_contrato']=$registro['vigencia_contrato'];
                        $detalles[$cantidad]['cod_ordenador']=$registro['cod_ordenador'];
                        $detalles[$cantidad]['id_ordenador']=$registro['id_ordenador'];
                        $datos_solicitudes[$key]['detalles']=$detalles;
                        
                    }
                }
            }
        }
        return $datos_solicitudes;
    }
    
    /**
     * Funcion para aignar los datos de la solicitud a la tabla temporal de detalle de nomina
     * @param int $id_solicitud
     * @param int $id_detalle
     * @param int $id_cumplido
     * @param int $vigencia
     * @return int 
     */
    function actualizarDetalleSolicitudPago($id_solicitud,$id_detalle,$id_cumplido,$vigencia){
            $datos=array('numero_solicitud'=>$id_solicitud,
                            'id_detalle'=>$id_detalle,
                            'id_cumplido'=>$id_cumplido,
                            'vigencia'=>$vigencia
                            );
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_nomina,"actualizar_detalle_solicitud_pago",$datos);
            
            $this->ejecutarSQL($this->configuracion, $this->acceso_nomina, $cadena_sql, "");
            return $this->totalAfectados($this->configuracion, $this->acceso_nomina);
    }
    
    /**
     * Funcion para consultar el ultimo numero de solicitud de pago
     * @return type 
     */
    function consultarUltimoNumeroDetalleSolicitudPago(){

            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_nomina,"ultimo_numero_detalle_solicitud_pago","");
            
            $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_nomina, $cadena_sql, "busqueda");
            return $datos[0][0];
    }
    
/**
     * Funcion que muestra un mensaje de alerta y retorna a una pagina
     * @param type $pagina
     * @param type $variable
     * @param type $mensaje 
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
   
    /**
     * Funcion para mostrar las solicitudes que no tiene cordis 
     */
    function revisarCordisSolicitud(){
          
            include_once($this->configuracion["raiz_documento"] . $this->configuracion["bloques"]."/nomina/contratistas/nom_admin_cumplido_supervisor". $this->configuracion["clases"] . "/liquidacionNomina.class.php");
            $registro='';
            $solicitudes = $this->consultarSolicitudesSinCordis($this->identificacion);
            $this->htmlNomina->form_asignar_cordis($this->configuracion, $solicitudes);
    
        }
    
        /**
         * Funcion para consultar en la BD las solicitudes de pago que no tienen cordis
         * @param int $identificacion
         * @return <array> 
         */
        function consultarSolicitudesSinCordis($identificacion){
                    
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_nomina,"solicitudes_sin_cordis",$identificacion);
            
            return $resultado= $this->ejecutarSQL($this->configuracion, $this->acceso_nomina, $cadena_sql, "busqueda");
   
        }
        
        /**
         * Funcion para guardar el cordis de las solicitudes 
         */
        function guardarCordisSolicitud(){
        //var_dump($_REQUEST);//exit;
        $asignados=0;
        $total=(isset($_REQUEST['total_registros'])?$_REQUEST['total_registros']:0);
        
        if($total){
                //buscamos y almacenamos en un arreglo los cumplidos seleccionados para la solicitud de pago
                for($i=0;$i<$total;$i++){
                    $modificado=0;
                    $nombre = "id_solicitud_".$i;
                    $nombre_cordis = "num_cordis_".$i;
                    $_REQUEST[$nombre]=(isset($_REQUEST[$nombre])?$_REQUEST[$nombre]:'');
                    $_REQUEST[$nombre_cordis]=(isset($_REQUEST[$nombre_cordis])?$_REQUEST[$nombre_cordis]:'');
                        
                    if($_REQUEST[$nombre]){
                            $id_solicitud=$_REQUEST[$nombre];
                            $cordis= $_REQUEST[$nombre_cordis];
                            $modificado = $this->actualizarNumeroCordisSolicitudPago($id_solicitud, $cordis);
                            if($modificado>0){
                                    $asignados++;
                                    $datos_solicitud = $this->consultarSolicitudPago($id_solicitud);
                                    $cod_supervisor = $datos_solicitud[0]['sol_cod_supervisor'];
                                    $rubro_interno = $datos_solicitud[0]['sol_rubro'];
                                    $vigencia = $datos_solicitud[0]['sol_pago_anio'];
                                    $cod_archivo = $id_solicitud."_".$cordis;
                                    $cod_ordenador=$datos_solicitud[0]['sol_cod_ordenador'];
                                    $this->generarOficioSolicitudPago($cod_archivo,$id_solicitud, $cod_supervisor,$rubro_interno,$vigencia,$cod_ordenador);
                                    //VARIABLES PARA EL LOG
                                    $registro[0] = "ACTUALIZAR_CORDIS";
                                    $registro[1] = $id_solicitud;
                                    $registro[2] = "SOLICITUD_PAGO_SUPERVISOR";
                                    $registro[3] = $id_solicitud;
                                    $registro[4] = time();
                                    $registro[5] = "Actualizado cordis de solicitud de pago ". $id_solicitud;
                                    $registro[5] .= " - cordis =". $cordis;
                                    $this->log_us->log_usuario($registro,$this->configuracion);

                            }
                    }
                    
                }
               
        }
       
        $mensaje = $asignados." números de CORDIS almacenados";
        $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
        $variable="pagina=nom_adminSolicitudPagoSupervisor";
        $variable.="&opcion=revisar_cordis";

        $variable=$this->cripto->codificar_url($variable,$this->configuracion);
        $this->retornar($pagina,$variable,$mensaje);
 
        
    }
    
    /**
     * Funcion para actualizar el numero de cordis en la solicitud de pago
     * @param type $id_solicitud
     * @param type $cordis
     * @return type 
     */
    function actualizarNumeroCordisSolicitudPago($id_solicitud,$cordis){
            $datos=array('id_solicitud'=>$id_solicitud,
                            'cordis'=>$cordis
                            );
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_nomina,"actualizar_cordis",$datos);
            $this->ejecutarSQL($this->configuracion, $this->acceso_nomina, $cadena_sql, "");
            return $this->totalAfectados($this->configuracion, $this->acceso_nomina);
    }
   
    /**
        *Funcion que llama el metodo para crear el oficio de la solicitud de pago
        */
       function generarOficioSolicitudPago($cod_archivo, $id_solicitud, $cod_supervisor,$rubro_interno,$vigencia,$cod_ordenador){
           $tipo_documento=2;
           //echo "<br>cod ".$cod_ordenador;
           //var_dump($_REQUEST);exit;
           $lista_contratistas = $this->generarListadoContratistas($id_solicitud);
           $parametro_sql=array('ID_SOLICITUD'=>$id_solicitud,
                                'VIGENCIA'=>$vigencia,
                                'COD_RUBRO'=>$rubro_interno,
                                'COD_SUPERVISOR'=>$cod_supervisor,
                                'COD_ORDENADOR'=>$cod_ordenador,
                                'LISTA_CONTRATISTAS'=>$lista_contratistas
                                );

           include_once($this->configuracion["raiz_documento"] . $this->configuracion["bloques"]."/nomina/contratistas/nom_admin_solicitud_pago_supervisor". $this->configuracion["clases"] . "/crearDocumento.class.php");
                $this->Documento = new crearDocumento($this->configuracion);
                $this->Documento->crearDocumento($tipo_documento,$parametro_sql,$cod_archivo);
       
       }
       
       /**
        * Funcion para consultar las solicitudes de pago
        * @param int $id_solicitud
        * @return <array> 
        */
       function consultarSolicitudPago($id_solicitud){
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_nomina,"solicitud_pago",$id_solicitud);
            $resultado = $this->ejecutarSQL($this->configuracion, $this->acceso_nomina, $cadena_sql, "busqueda");
            return $resultado;
    }
    
       /**
        * Funcion para consultar los datos de un ordenador de gasto a partir del codigo interno del jefe
        * @param int $cod_ordenador
        * @return int
        */
       function consultarDatosOrdenador($cod_ordenador){
           $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_sic,"datos_ordenador",$cod_ordenador);
           $resultado = $this->ejecutarSQL($this->configuracion, $this->acceso_sic, $cadena_sql, "busqueda");
           return $resultado;
       }
       
    /**
     * Funcion para generar Listado de los nombre de los contratistas relacionados a una solicitud de pago
     * @param int $id_solicitud
     * @return string 
     */
       function generarListadoContratistas($id_solicitud){
        $listado='';
        $detalles_solicitud = $this->consultarDetallesSolicitudesPago($id_solicitud);
        foreach ($detalles_solicitud as $detalle) {
            $num_id_contratista = $detalle['cto_con_num_id'];
            $datos_contratista = $this->consultarDatosContratista($num_id_contratista);
            $nombre = $datos_contratista[0]['PRIMER_NOMBRE']." ".(isset($datos_contratista[0]['SEGUNDO_NOMBRE'])?$datos_contratista[0]['SEGUNDO_NOMBRE']:'');
            $nombre .= " ".$datos_contratista[0]['PRIMER_APELLIDO']." ".$datos_contratista[0]['SEGUNDO_APELLIDO'];
            $listado .="-".$nombre."                                                                                                ";
        }
        return $listado;
       
    }
    
    /**
     * Funcion para consultar las dependencias registradas en SICAPITAL
     * @return type 
     */
    function consultarDependencias(){
           $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_sic,"dependencia",'');
           $resultado = $this->ejecutarSQL($this->configuracion, $this->acceso_sic, $cadena_sql, "busqueda");
           return $resultado;

    }
    
    /**
     * Funcion que consulta el nombre de una dependencia a partir de un arreglo que contiene los datos
     *
     * @param <array> $dependencias
     * @param int $cod_dependencia
     * @return string 
     */
    function consultarNombreDependencia($dependencias,$cod_dependencia){
        $nombre='';
        foreach ($dependencias as $dependencia) {
            if($dependencia['COD_DEPENDENCIA']==$cod_dependencia){
                $nombre=$dependencia['NOMBRE_DEPENDENCIA'];
            }
        }
        return $nombre;
    }
    
    /**
     * Funcion para actualizar el estado del cumplido
     * @param type $id_cumplido
     * @param type $vigencia
     * @param type $estado
     * @return type 
     */
    function actualizarEstadoCumplido($id_cumplido,$vigencia,$estado){
            $datos=array('estado'=>$estado,  
                        'id_cumplido'=>$id_cumplido,
                        'vigencia'=>$vigencia
                            );
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_nomina,"actualizar_estado_cumplido",$datos);
            $this->ejecutarSQL($this->configuracion, $this->acceso_nomina, $cadena_sql, "");
            return $this->totalAfectados($this->configuracion, $this->acceso_nomina);
    }
    
    
 
     /**
        * Funcion que consulta los datos de un documento
        * @param int $codigo
        * @return <array> 
        */
       function consultarDocumento($codigo){
            $cadena_sql=$this->sql->cadena_sql($this->configuracion,$this->acceso_nomina,"documento",$codigo);
            //echo $cadena_sql;
            return $resultado=$this->ejecutarSQL($this->configuracion, $this->acceso_nomina, $cadena_sql, "busqueda");
		    
       }   

       
       /**
        * Funcion para revisar si existe ya una solicitud registrada o no en el sistema
        * @param int $id_cumplido
        * @param int $vigencia_contrato
        * @return int 
        */
       function existe_solicitud($id_cumplido,$vigencia_contrato){
           $existe=0;
           $solicitud = $this->consultarDetalleSolicitudPagoxCumplido($id_cumplido, $vigencia_contrato);
           if($solicitud[0]['dtn_num_solicitud_pago']>0){
               $existe=1;
           }else{
               $existe=0;
           }
           return $existe;
       }
       
       
    /**
     * Funcion para consultar un detalle de solicitud de pago con el numero de cumplido y vigencia
     * @param int $id_cumplido
     * @param int $vigencia_contrato
     * @return <array> 
     */
       function consultarDetalleSolicitudPagoxCumplido($id_cumplido, $vigencia_contrato){
            $datos = array( 'id_cumplido'=>$id_cumplido,
                            'vigencia_contrato'=>$vigencia_contrato
                            );
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_nomina,"detalle_solicitud_pago_xcumplido",$datos);
    
            $resultado = $this->ejecutarSQL($this->configuracion, $this->acceso_nomina, $cadena_sql, "busqueda");
            return $resultado;
    }
} // fin de la clase
	

?>


                
                