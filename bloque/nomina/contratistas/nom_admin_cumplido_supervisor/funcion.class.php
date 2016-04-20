<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/
/*---------------------------------------------------------------------------------------
 |				Control Versiones				    	|	
 ----------------------------------------------------------------------------------------
 | fecha      |        Autor            | version     |              Detalle            |
 ----------------------------------------------------------------------------------------
 | 14/02/2013 | Maritza Callejas C.  	| 0.0.0.1     |                                 |
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
class funciones_adminCumplidoSupervisor extends funcionGeneral
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
                
                $this->htmlCumplido = new html_adminCumplidoSupervisor($configuracion);   
                
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
			case "multiplesCumplidos":
                                $datos_documento = $this->consultarDocumento(1);
            			$this->htmlCumplido->multiplesCumplidos($configuracion,$registro, $totalRegistros, $variable,$datos_documento);
				break;
		
		}
		
	}
	
		
/*__________________________________________________________________________________________________
		
						Metodos especificos 
__________________________________________________________________________________________________*/

    

        /**
         * Funcion que consulta la informacion del contratista y contrato para mostrarla 
         * @param int $interno_co
         * @param int $cod_contrato
         * @param int $vigencia 
         */
        function mostrarInformacionContratista($interno_co,$cod_contrato,$vigencia){
    			$contrato = $this->consultarDatosContrato($interno_co,$vigencia);
                        $cuenta = $this->consultarDatosCta($contrato[0]['INTERNO_PROVEEDOR']);
                        $contratista = $this->consultarDatosContratista($contrato[0]['NUM_IDENTIFICACION']);
                        $disponibilidad = $this->consultarDatosDisponibilidad($contrato[0]['INTERNO_MC'],$contrato[0]['CODIGO_UNIDAD_EJECUTORA'],$vigencia);
                        $nro_cdp = $disponibilidad[0]['NUMERO_DISPONIBILIDAD']; 
                        $registroPresupuestal = $this->consultarDatosRegistroPresupuestal($nro_cdp,$contrato[0]['CODIGO_UNIDAD_EJECUTORA'],$vigencia);
                        $ordenPago = $this->consultarDatosOrdenPago($contrato[0]['NUM_IDENTIFICACION'],$nro_cdp,$vigencia);
                        $tipo_contrato = $this->consultarTipoContrato($vigencia,$contrato[0]['CODIGO_UNIDAD_EJECUTORA'],$registroPresupuestal[0]['NUMERO_REGISTRO']);
                        $fecha_contrato= (isset($registroPresupuestal[0]['FECHA_REGISTRO'])?$registroPresupuestal[0]['FECHA_REGISTRO']:'');
                        if($cod_contrato){ 
                            $novedades = $this->consultarDatosNovedades( $cod_contrato,$vigencia);
                        }else{
                            $novedades ="";
                        }
                        //Obtener el total de registros
			$totalRoles = $this->totalRegistros($this->configuracion, $this->acceso_db);
                        ?>			
			<table width="90%" align="center" border="0" cellpadding="10" cellspacing="0" >
			<tbody>
								<tr>
				<td>
					<table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
					  					  
                                          
                                          <tr>
						<td>
							<?
                                                        $this->htmlCumplido->mostrarDatosContratista($contratista);
                                                        ?>
						</td>
					  </tr>
                                          <tr>
						<td>
							<?
                                                        $this->htmlCumplido->mostrarDatosContrato($contrato,$tipo_contrato,$fecha_contrato);
                                                        ?>
						</td>
					  </tr>
                                          <tr>
						<td>
							<?
                                                        $this->htmlCumplido->mostrarDatosCuentaBanco($cuenta);
                                                        ?>
						</td>
					  </tr>
                                          <tr>
						<td>
							<?
                                                        $this->htmlCumplido->mostrarDatosDisponibilidad($disponibilidad);
                                                        ?>
						</td>
					  </tr>    
                                          <tr>
						<td>
							<?
                                                        $this->htmlCumplido->mostrarDatosRegistroPresupuestal($registroPresupuestal);
                                                        ?>
						</td>
					  </tr>    
                                          <tr>
						<td>
							<?
                                                        $this->htmlCumplido->mostrarDatosOrdenPago($ordenPago);
                                                        ?>
						</td>
					  </tr>    
                                          <tr>
						<td>
							<?
                                                        $this->htmlCumplido->mostrarNovedades($novedades);
                                                        ?>
						</td>
					  </tr>
					</table>
				   </td>
				</tr>
				<tr>
			<td>
				
			</tbody>
			</table>
			<?				
		
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
    
    /**
     * Funcion que consulta en la base de datos informacion de las cuentas bancarias
     * @param int $cod_interno_proveedor
    */
    function consultarDatosCta($cod_interno_proveedor){
        
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_sic,"datos_cuenta",$cod_interno_proveedor);
            //echo $cadena_sql;
            return $datos_cuenta = $this->ejecutarSQL($this->configuracion, $this->acceso_sic, $cadena_sql, "busqueda");
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
     * Funcion que consulta en la base de datos informacion del contratista
     * @param int $identificacion
    */
   function consultarDatosContratista($identificacion){
            
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_sic,"datos_contratista",$identificacion);
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
            //echo "cadena ".$cadena_sql;exit;
            return $datos_disponibilidad = $this->ejecutarSQL($this->configuracion, $this->acceso_sic, $cadena_sql, "busqueda");
       
    }
    
   /**
     * Funcion que consulta en la base de datos informacion de las novedades registradas
     * @param int $interno_contrato
    */
   function consultarDatosNovedades($cod_contrato,$vigencia){
            $datos= array(  'vigencia'=>$vigencia,
                            'cod_contrato'=>$cod_contrato);               
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_nomina,"datos_novedades",$datos);
            //echo "cadena ".$cadena_sql;exit;
            return $datos_novedad = $this->ejecutarSQL($this->configuracion, $this->acceso_nomina, $cadena_sql, "busqueda");

    }


 
    /**
     * Funcion que valida que existan los datos de un contratista requeridos para la elaboración del cumplido
     * @param <array> $contratista
     * @return String/array 
     */
    function validaContratista($contratista){
        $indice = 1;
        if(!$contratista[0]['TIPO_DOCUMENTO']){
            $valido[$indice]="Tipo documento";
            $indice++;
        }
        if(!$contratista[0]['NUMERO_DOCUMENTO']){
            $valido[$indice]="Número documento";
            $indice++;
        }
        if(!$contratista[0]['PRIMER_NOMBRE']){
            $valido[$indice]="Primer Nombre";
            $indice++;
        }
        if(!$contratista[0]['PRIMER_APELLIDO']){
            $valido[$indice]="Primer Apellido";
            $indice++;
        }
        $valido=(isset($valido)?$valido:'ok');
        return $valido;
    }
    
    
    /**
     * Funcion que valida que existan los datos de un contrato requeridos para la elaboración del cumplido
     * @param type $contrato
     * @param type $tipo_contrato
     * @return type 
     */ 
    function validaContrato($contrato,$tipo_contrato){
        $indice = 1;
        $contrato[0]['NUM_CONTRATO']=(isset( $contrato[0]['NUM_CONTRATO'])? $contrato[0]['NUM_CONTRATO']:'');
        $contrato[0]['FECHA_INICIO']=(isset( $contrato[0]['FECHA_INICIO'])? $contrato[0]['FECHA_INICIO']:'');
        $contrato[0]['FECHA_FINAL']=(isset( $contrato[0]['FECHA_FINAL'])? $contrato[0]['FECHA_FINAL']:'');
        if(!$contrato[0]['NUM_CONTRATO']){
            $valido[$indice]="Numero documento";
            $indice++;
        }
        if(!$contrato[0]['FECHA_INICIO']){
            $valido[$indice]="Fecha de inicio";
            $indice++;
        }
        
        if(!$contrato[0]['FECHA_FINAL']){
            $valido[$indice]="Fecha final";
            $indice++;
        }
        if(!$tipo_contrato){
            $valido[$indice]="Tipo de contrato";
            $indice++;
        }
        
        $valido=(isset($valido)?$valido:'ok');
        return $valido;
    }
    
    /**
     * Funcion que valida que existan los datos de cretificados de disponibilidad y registro presupuestal requeridos para la elaboración del cumplido
     * @param <array> $disponibilidad
     * @param <array> $registroPresupuestal
     * @return type 
     */
    function validaCertificados($disponibilidad,$registroPresupuestal){
        $indice = 1;
        if(!$disponibilidad[0]['NUMERO_DISPONIBILIDAD']){
            $valido[$indice]="Número de disponibilidad";
            $indice++;
        }
        if(!$disponibilidad[0]['FECHA_DISPONIBILIDAD']){
            $valido[$indice]="Fecha de disponibilidad";
            $indice++;
        }
        if(!$disponibilidad[0]['VALOR']){
            $valido[$indice]="Valor disponibilidad";
            $indice++;
        }
         if(!$registroPresupuestal[0]['NUMERO_REGISTRO']){
            $valido[$indice]="Número de registro";
            $indice++;
        }
        if(!$registroPresupuestal[0]['FECHA_REGISTRO']){
            $valido[$indice]="Fecha de registro";
            $indice++;
        }
        if(!$registroPresupuestal[0]['VALOR']){
            $valido[$indice]="Valor REGISTRO";
            $indice++;
        }
        
        $valido=(isset($valido)?$valido:'ok');
        return $valido;
    }
    
 
    
    /**
     * Funcion que consulta las cuentas bancarias relacionadas a un contratista
     * @param int $identificacion
     * @param String $tipo_id
     * @return <array> 
     */
    function consultarCuentas($identificacion, $tipo_id){
            $datos = array('tipo_id'=>$tipo_id,
                        'num_id'=>$identificacion);
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_nomina,"lista_cuentas",$datos);
            return $resultado = $this->ejecutarSQL($this->configuracion, $this->acceso_nomina, $cadena_sql, "busqueda");

    }
    
    
  
    /**
     * Funcion que consulta el tipo de contrato de un contrato
     * @param int $vigencia
     * @param int $unidad_ejecutora
     * @param int $numero_registro
     * @return type 
     */
    function consultarTipoContrato($vigencia,$unidad_ejecutora,$numero_registro){
            $datos = array('vigencia'=>$vigencia,
                            'unidad_ejec'=>$unidad_ejecutora,
                            'num_registro'=>$numero_registro);
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_sic,"tipo_contrato",$datos);
            //echo "<br>tipo ".$cadena_sql;exit;
            $datos_contrato = $this->ejecutarSQL($this->configuracion, $this->acceso_sic, $cadena_sql, "busqueda");
            return $datos_contrato[0][1];
    }
      
    /**
     * Funcion que consulta los registro de contratos relacionados a un contratista
     * @param int $identificacion
     * @return <array>
     */
    function consultarContratos($identificacion){
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_sic,"contratos",$identificacion);
            //echo "<br>cadena ".$cadena_sql;
            $datos_contrato = $this->ejecutarSQL($this->configuracion, $this->acceso_sic, $cadena_sql, "busqueda");
            return $datos_contrato;
    }
    
 
    
    /**
     * Funcion que recorre el arreglo con las solicitudes y llama al metodo de mostrar la información relacionada
     * @param <array> $registro 
     */
    function mostrarListadoSolicitudes($registro){
       
        if(is_array($registro)){
            $cumplidos =array();
            $indice=0;
            //var_dump($registro);
            foreach ($registro as $key => $arreglo) {
                        $cumplidos[$indice]['id'] = $arreglo['id'];
                        $cumplidos[$indice]['vigencia'] =$arreglo['vigencia'];
                        $cumplidos[$indice]['num_contrato'] =$arreglo['num_contrato'];
                        $cumplidos[$indice]['anio'] =$arreglo['anio'];
                        $cumplidos[$indice]['mes'] =$arreglo['mes'];
                        $cumplidos[$indice]['procesado'] =$arreglo['procesado'];
                        $cumplidos[$indice]['estado'] =$arreglo['estado'];
                        $cumplidos[$indice]['estado_reg'] =$arreglo['estado_reg'];
                        $cumplidos[$indice]['fecha'] =$arreglo['fecha'];
                        $cumplidos[$indice]['num_impresiones'] =$arreglo['num_impresiones'];
                        $cumplidos[$indice]['valor'] =$arreglo['valor'];
                        $cumplidos[$indice]['id_cta'] =$arreglo['id_cta'];
                        $cumplidos[$indice]['finicio_per_pago'] =$arreglo['finicio_per_pago'];
                        $cumplidos[$indice]['ffinal_per_pago'] =$arreglo['ffinal_per_pago'];
                        $cumplidos[$indice]['acumulado_valor_pagos'] =$arreglo['acumulado_valor_pagos'];
                        $cumplidos[$indice]['acumulado_dias_pagos'] =$arreglo['acumulado_dias_pagos'];
                        $cumplidos[$indice]['interno_co'] =$arreglo['interno_co'];
                        $cumplidos[$indice]['num_id_contratista'] =$arreglo['num_id_contratista'];
                        $contratista = $this->consultarDatosContratista($arreglo['num_id_contratista']);
                        $cumplidos[$indice]['nombre_contratista'] =$contratista[0]['PRIMER_NOMBRE'];
                        $cumplidos[$indice]['nombre_contratista'] .=" ".(isset($contratista[0]['SEGUNDO_NOMBRE'])?$contratista[0]['SEGUNDO_NOMBRE']:'');
                        $cumplidos[$indice]['nombre_contratista'] .=" ".$contratista[0]['PRIMER_APELLIDO'];
                        $cumplidos[$indice]['nombre_contratista'] .=" ".$contratista[0]['SEGUNDO_APELLIDO'];
                        
                        $indice++;
            }
        
               $this->mostrarRegistro($this->configuracion,$cumplidos, $this->configuracion['registro'], "multiplesCumplidos", "");

        }else{
            echo "No tiene solicitudes de cumplido registradas";
        }
        
         
    }
    
    /**
     * Funcion que consulta los registros de las solicitudes de cumplido de un contratista
     * @param int $vigencia
     * @param int $cod_contrato
     * @return <array> 
     */
    function consultarSolicitudes($vigencia,$cod_contrato,$id_solicitud,$codigos_internos){
            $datos=array('vigencia'=>$vigencia,
                            'cod_contrato'=>$cod_contrato,
                            'id_solicitud'=>$id_solicitud,
                            'codigos_internos'=>$codigos_internos);
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_nomina,"solicitudes_cumplido",$datos);
            //echo "<br>cadena ".$cadena_sql;
            $resultado = $this->ejecutarSQL($this->configuracion, $this->acceso_nomina, $cadena_sql, "busqueda");
            return $resultado;
        
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
     * Funcion que consulta las solicitudes por aprobar, muestra los datos para ser revisadas
     * @param type $configuracion 
     */
    function revisarSolicitudCumplido(){
            //$cod_supervisor=40;
            $cod_supervisor=$this->obtenerCodigoInternoSupervisor($this->identificacion);
            $contratos = $this->consultarContratosSupervisor($cod_supervisor);
            $codigos_internos=$this->obtenerCadenaCodigosInternosContrato($contratos);
            
            $solicitudes = $this->consultarTodasSolicitudesCumplido($codigos_internos);
            $solicitudes = $this->asignarDatosContrato($solicitudes);
                    
            //var_dump($solicitudes);exit;
            $this->htmlCumplido->form_revisar_solicitud($this->configuracion, $solicitudes);
            
        }
   
     /**
     * Funcion que consulta los registros de todos los contratos relacionados a un supervisor
     * @param int $vigencia
     * @param int $cod_contrato
     * @return <array> 
     */
    function consultarContratosSupervisor($cod_supervisor){
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_sic,"contratos_supervisor",$cod_supervisor);
         //   echo "<br>cadena ".$cadena_sql;
            $resultado = $this->ejecutarSQL($this->configuracion, $this->acceso_sic, $cadena_sql, "busqueda");
            return $resultado;
        
    }
    
     /**
     * Funcion que consulta los registros de todas las solicitudes de cumplido 
     * @param int $codigos_internos
     * @return <array> 
     */
    function consultarTodasSolicitudesCumplido($codigos_internos){
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_nomina,"todas_solicitudes_cumplido",$codigos_internos);
            //echo "<br>cadena ".$cadena_sql;exit;
            $resultado = $this->ejecutarSQL($this->configuracion, $this->acceso_nomina, $cadena_sql, "busqueda");
            return $resultado;
        
    }
    
    /**
     * revisa las solicitudes aprobadas para realizar el correspondiente registro en la base de datos
     */
    function revisarAprobacion(){
        //var_dump($_REQUEST);//exit;
        $aprobados=0;
        $total=(isset($_REQUEST['total_registros'])?$_REQUEST['total_registros']:0);
        if($total){
                for($i=0;$i<$total;$i++){
                    $modificado=0;
                    $nombre = "id_solicitud_".$i;
                    $nombre_valor = "valor_cumplido_".$i;
                    $nombre_finicio_cumplido = "finicio_cumplido_".$i;
                    $nombre_ffinal_cumplido = "ffinal_cumplido_".$i;
                    $nombre_dias_cumplido = "dias_cumplido_".$i;
                    $nombre_acumulado_antes_pago = "acumulado_antes_pago_".$i;
                    $nombre_id_nov = "nov_id_".$i;
                    $nombre_vigencia = "vigencia_".$i;
                    $_REQUEST[$nombre]=(isset($_REQUEST[$nombre])?$_REQUEST[$nombre]:'');
                    $id_supervisor= $this->identificacion;
                    if($_REQUEST[$nombre]){
                        //echo "<br>".$nombre." seleccionado , id=".$_REQUEST[$nombre];
                        $acumulado = $this->calcularAcumulado($_REQUEST[$nombre],$_REQUEST[$nombre_valor],$_REQUEST[$nombre_dias_cumplido],$_REQUEST[$nombre_acumulado_antes_pago]);
                        
                        $modificado = $this->aprobarSolicitud($_REQUEST[$nombre],$_REQUEST[$nombre_valor],$_REQUEST[$nombre_finicio_cumplido],$_REQUEST[$nombre_ffinal_cumplido],$_REQUEST[$nombre_dias_cumplido],$acumulado['valor'],$acumulado['dias'],$id_supervisor);
                            if($modificado>0){
                                    $id_cumplido=$_REQUEST[$nombre];
                                    $datos_cumplidos = $this->consultarSolicitudes("", "", $id_cumplido,'');
                                    $vigencia=$datos_cumplidos[0]['vigencia'];
                                    $interno_co= $datos_cumplidos[0]['interno_co'];
                                    $datos_contrato = $this->consultarDatosContrato($interno_co, $vigencia);
                                    $cedula=$datos_contrato[0]['NUM_IDENTIFICACION'];
                                    $interno_mc=$datos_contrato[0]['INTERNO_MC'];
                                    $cod_contrato=$datos_contrato[0]['NUM_CONTRATO'];
                                    $unidad=$datos_contrato[0]['CODIGO_UNIDAD_EJECUTORA'];
                                    $cod_supervisor=$datos_contrato[0]['FUNCIONARIO'];
                                    $cod_archivo=$cedula."_".$id_cumplido;
                                    $nov_afc= $this->consultarIdNovAFC($id_cumplido,$vigencia);
                                    $id_nov_afc= (isset($nov_afc[0]['cnov_nov_id'])?$nov_afc[0]['cnov_nov_id']:0);
                                    $this->generarCumplido($id_cumplido,$cedula,$vigencia,$interno_mc,$unidad,$cod_supervisor,$cod_archivo,$id_nov_afc);
                                    //exit;
                                    $aprobados++;
                                    //VARIABLES PARA EL LOG
                                    $registro[0] = "APROBAR";
                                    $registro[1] = $id_cumplido;
                                    $registro[2] = "CUMPLIDO";
                                    $registro[3] = $id_cumplido;
                                    $registro[4] = time();
                                    $registro[5] = "Aprobar solicitud cumplido ". $id_cumplido;
                                    $registro[5] .= " - vigencia =". $vigencia;
                                    $registro[5] .= " - cod_contrato =". $cod_contrato;
                                    $registro[5] .= " - id_contratista =". $cedula;
                                    
                                    $this->log_us->log_usuario($registro,$this->configuracion);

                            }
                    }
                     //exit;
                }
                if($aprobados>0){
                        $mensaje = $aprobados. " solicitudes aprobadas con exito";
                }else{
                        $mensaje = " No se aprobó ninguna solicitud";
                }
                $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                $variable="pagina=nom_adminCumplidoSupervisor";
                $variable.="&opcion=revisar_solicitud";
                
                $variable=$this->cripto->codificar_url($variable,$this->configuracion);
                $this->retornar($pagina,$variable,$mensaje);
 
        }
    }
    
    /**
     * Funcion que toma los datos para actualizar la solicitud
     * @param int $id_solicitud
     * @param double $valor_cumplido
     * @param date $finicial_cumplido
     * @param date $ffinal_cumplido
     * @param int $dias_cumplido
     * @param double $valor_acumulado
     * @param int $dias_acumulados
     * @return type 
     */
    function aprobarSolicitud($id_solicitud,$valor_cumplido,$finicial_cumplido,$ffinal_cumplido,$dias_cumplido,$valor_acumulado,$dias_acumulados,$id_supervisor){
        
            $aprobado=$this->actualizarSolicitud($id_solicitud,$valor_cumplido,$finicial_cumplido,$ffinal_cumplido,$dias_cumplido,$valor_acumulado,$dias_acumulados,$id_supervisor);
            return $aprobado;
    }
    
    /**
     * Actualiza en la base de datos la informacion de una solicitud de cumplido
     * @param int $id_solicitud
     * @param double $valor_cumplido
     * @param date $finicial_cumplido
     * @param date $ffinal_cumplido
     * @param int $dias_cumplido
     * @param double $valor_acumulado
     * @param int $dias_acumulados
     * @return type 
     */
    function actualizarSolicitud($id_solicitud,$valor_cumplido,$finicial_cumplido,$ffinal_cumplido,$dias_cumplido,$valor_acumulado,$dias_acumulados,$id_supervisor){
            $datos=array('id'=>$id_solicitud,
                            'valor'=>$valor_cumplido,
                            'estado'=>'APROBADO',
                            'finicial'=>$finicial_cumplido,
                            'ffinal'=>$ffinal_cumplido,
                            'dias'=>$dias_cumplido,
                            'valor_acumulado'=>$valor_acumulado,
                            'dias_acumulados'=>$dias_acumulados,
                            'id_supervisor'=>$id_supervisor);
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_nomina,"aprobar_solicitud",$datos);
            // echo "cadena ".$cadena_sql;//exit;
            $this->ejecutarSQL($this->configuracion, $this->acceso_nomina, $cadena_sql, "");
            return $this->totalAfectados($this->configuracion, $this->acceso_nomina);
    }
    
    /**
     * Funcion para asignar los datos del contrato a las solicitudes realizadas
     * @param type $solicitudes
     * @return string 
     */
    function asignarDatosContrato($solicitudes){
        if(is_array($solicitudes)){
            foreach ($solicitudes as $key => $solicitud) {
                $identificacion = $solicitud['num_id_contratista'];
                $cod_contrato=$solicitud['interno_oc'];
                $vigencia=$solicitud['vigencia'];
                $datos_contrato = $this->consultarDatosContrato($cod_contrato,$vigencia);
                if($datos_contrato){
                    //$contrato = $this->consultarDatosContrato($interno_oc,$vigencia);
                    $disponibilidad = $this->consultarDatosDisponibilidad($datos_contrato[0]['INTERNO_MC'],$datos_contrato[0]['CODIGO_UNIDAD_EJECUTORA'],$vigencia);
                    // var_dump($disponibilidad);//exit;
                    $nro_cdp = $disponibilidad[0]['NUMERO_DISPONIBILIDAD']; 
                    //$registroPresupuestal = $this->consultarDatosRegistroPresupuestal($nro_cdp,$contrato[0]['CODIGO_UNIDAD_EJECUTORA'],$vigencia);
                    $ordenPago = $this->consultarDatosOrdenPago($datos_contrato[0]['NUM_IDENTIFICACION'],$nro_cdp,$vigencia);
                    //echo "<br>cdp ".$nro_cdp;
                    //var_dump($ordenPago);exit;
                    
                    $acumulado_antes_pago = $this->sumarPagos($ordenPago);
                    //echo "<br>acum ".$acumulado_antes_pago;exit;
                    
                    $solicitudes[$key]['identificacion']=$identificacion;
                    $solicitudes[$key]['nombre']=$datos_contrato[0]['RAZON_SOCIAL'];
                    $solicitudes[$key]['valor_contrato']=$datos_contrato[0]['CUANTIA'];
                    $solicitudes[$key]['finicio_contrato']=$datos_contrato[0]['FECHA_INICIO'];
                    $solicitudes[$key]['ffinal_contrato']=$datos_contrato[0]['FECHA_FINAL'];
                    $solicitudes[$key]['dias_contrato']=  $this->calcularDiasContrato($datos_contrato[0]['FECHA_INICIO'], $datos_contrato[0]['FECHA_FINAL']);
                    $solicitudes[$key]['acumulado_antes_pago']=$acumulado_antes_pago;
                    
                }

            }
        }else{
            $solicitudes='';
        }
        return $solicitudes;
        
    }
    
    /**
     * Funcion que retorna la informacion del cumplido del mes anterior de un contrato, de acuerdo a los parametros ingresados
     * @param int $vigencia
     * @param int $num_contrato
     * @param int $mes
     * @param int $anio
     * @return <array>
     */
    function consultarCumplidoAnterior($vigencia,$num_contrato,$mes,$anio){
        
        $mesanterior =  date("Y-m",mktime(0,0,0,$mes-1,date("d"),$anio));
        $anio_anterior = substr($mesanterior, 0,4);
        $mes_anterior = substr($mesanterior, 5,2);
        
        $datos_cumplidos = $this->consultarSolicitudes($vigencia,$num_contrato,'','');
        $solicitud = $this->buscarSolicitudMes($datos_cumplidos,$anio_anterior,$mes_anterior);
        return $solicitud;
    }
    
    
    /**
     * Funcion que busca en un arreglo de solicitudes, una solicitud aprobada correspondiente a un mes especifico
     * @param <array> $datos_cumplidos
     * @param int $anio
     * @param int $mes
     * @return <array>  
     */
    function buscarSolicitudMes($datos_cumplidos,$anio,$mes){
        $indice='';
        foreach ($datos_cumplidos as $key => $cumplido) {
            if($cumplido['anio']==$anio && $cumplido['mes']==$mes && $cumplido['estado']=='APROBADO'){
                $indice=$key;
            }
        }
        if($indice){
            return $datos_cumplidos[$indice];
        }else{
            return $indice;
        }
    }
    
    /**
     * Funcion que calcula el valor y los dias acumulado para un cumplido
     * @param int $id_solicitud
     * @param double $valor_cumplido
     * @param int $dias_cumplido
     * @return <array> 
     */
    function calcularAcumulado($id_solicitud,$valor_cumplido,$dias_cumplido,$acumulado_pagos){
//        $cumplido = $this->consultarSolicitudes('','',$id_solicitud,'');
//        $anio = $cumplido[0]['anio'];
//        $mes = $cumplido[0]['mes'];
//        $vigencia=$cumplido[0]['vigencia'];
//        $num_contrato=$cumplido[0]['num_contrato'];
        
        //$cumplido_anterior = $this->consultarCumplidoAnterior($vigencia,$num_contrato,$mes,$anio);
        //$valor_acumulado = (isset($cumplido_anterior['acumulado_valor_pagos'])?$cumplido_anterior['acumulado_valor_pagos']:0)+$valor_cumplido;
        //$dias_acumulados = (isset($cumplido_anterior['acumulado_dias_pagos'])?$cumplido_anterior['acumulado_dias_pagos']:0)+$dias_cumplido;
        
        if($dias_cumplido>0){
            $valor_dia =  $valor_cumplido/$dias_cumplido;
            if($valor_dia>0){
                $dias_acumulados = $acumulado_pagos/$valor_dia;
            }
        }        
        $acumulado['valor']=$acumulado_pagos + $valor_cumplido;
        $acumulado['dias']=$dias_acumulados + $dias_cumplido;
        return $acumulado;
        
    }
    
   
    /**
        *Funcion que llama el metodo para crear el cumplido de labores
        */
       function generarCumplido($id_cumplido,$cedula,$vigencia,$interno_mc,$unidad,$cod_supervisor,$cod_archivo,$id_nov_afc){
           $tipo_documento=1;
           //var_dump($_REQUEST);exit;
           
           $parametro_sql=array('ID_CUMPLIDO'=>$id_cumplido,
                                'CEDULA'=>$cedula,
                                'VIGENCIA'=>$vigencia,
                                'INTERNO_MC'=>$interno_mc,
                                'UNIDAD'=>$unidad,
                                'COD_SUPERVISOR'=>$cod_supervisor,
                                'ID_NOV_AFC'=>(isset($id_nov_afc)?$id_nov_afc:'0')
                                );
            //var_dump($parametro_sql);
           include_once($this->configuracion["raiz_documento"] . $this->configuracion["bloques"]."/nomina/contratistas/nom_admin_cumplido_supervisor". $this->configuracion["clases"] . "/crearDocumento.class.php");
                $this->Documento = new crearDocumento($this->configuracion);
                $this->Documento->crearDocumento($tipo_documento,$parametro_sql,$cod_archivo);
       
       }
       
     /**
        * Funcion que consulta los datos de un documento
        * @param int $codigo
        * @return <array> 
        */
       function consultarDocumento($codigo){
            $cadena_sql=$this->sql->cadena_sql($this->configuracion,$this->acceso_nomina,"documento",$codigo);
//            echo "<br>cadena ".$cadena_sql;exit;
            return $resultado=$this->ejecutarSQL($this->configuracion, $this->acceso_nomina, $cadena_sql, "busqueda");
		    
       }   

       /**
        * Funcion para obtener la cadena con los codigos internos de los contratos de un supervisor
        * @param <array> $contratos
        * @return String 
        */
       function obtenerCadenaCodigosInternosContrato($contratos){
           $cadena="";
           if(is_array($contratos)){
               foreach ($contratos as $contrato) {
                   if($cadena){
                        $cadena .= ",".$contrato['INTERNO_OC'];
                   }else{
                        $cadena = $contrato['INTERNO_OC'];
                   }
               }
           }
           return $cadena;
       }
       
       /**
        * Funcion para obtener el codigo de un supervisor a partir del numero de identificacion del usuario
        * @param int $identificacion
        * @return int 
        */
       function obtenerCodigoInternoSupervisor($identificacion){
           $codigo_supervisor=0;
           $resultado = $this->consultarDependenciaUsuario($identificacion);
           //var_dump($resultado);exit;
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
//            echo "<br>cadena ".$cadena_sql;exit;
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
           //echo "<br>cadena ".$cadena_sql;exit;
           $resultado = $this->ejecutarSQL($this->configuracion, $this->acceso_sic, $cadena_sql, "busqueda");
           if($resultado){
               $codigo_supervisor=$resultado[0]['COD_JEFE'];
           }
           return $codigo_supervisor;
       }
       
 
    
       /**
     * Ingresa en la BD el codigo de la novedad relacionada a un cumplido
     * @param int $id_cumplido
     * @param int $vigencia
     * @param int $id_novedad
     * @return int
     */
    function insertarNovedadCumplido($id_cumplido,$vigencia,$id_novedad){
            $datos=array('id_cumplido'=>$id_cumplido,
                            'vigencia'=>$vigencia,
                            'id_novedad'=>$id_novedad,
                            'estado'=>'A'
                            );
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_nomina,"insertar_novedad_cumplido",$datos);
             //echo "cadena ".$cadena_sql;exit;
            $this->ejecutarSQL($this->configuracion, $this->acceso_nomina, $cadena_sql, "");
            return $this->totalAfectados($this->configuracion, $this->acceso_nomina);
    }
    
  /**
         * Funcion que llama el formulario mostrar los cumplidos
         */
        function consultar(){
            
            $cod_supervisor=$this->obtenerCodigoInternoSupervisor($this->identificacion);
            //echo "<br>cod_sup ".$cod_supervisor;exit;
            $contratos = $this->consultarContratosSupervisor($cod_supervisor);
            
            $codigos_internos=$this->obtenerCadenaCodigosInternosContrato($contratos);
//            echo "<br>cod_internos ".$codigos_internos;exit;
//            var_dump($contratos);exit;
            $solicitudes = $this->consultarSolicitudes('','','',$codigos_internos);
                        //var_dump($solicitudes);exit;
            $this->mostrarListadoSolicitudes($solicitudes);
            
        }
        
        /**
         * Funcion para consultar el codigo de una novedad de AFC
         * @param int $id_cumplido
         * @param int $vigencia
         * @return <array> 
         */
        function consultarIdNovAFC($id_cumplido,$vigencia){
            $datos = array( 'id_cumplido'=>$id_cumplido,
                            'vigencia'=>$vigencia
                
            );
             $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_nomina,"id_novedad_afc",$datos);
//            echo "<br>cadena ".$cadena_sql;exit;
            return $resultado = $this->ejecutarSQL($this->configuracion, $this->acceso_nomina, $cadena_sql, "busqueda");
           
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
//                $fecha_inicio="2013-01-05";
//                $fecha_fin="2013-06-15";
//                    
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
                                
                            //echo utf8_encode("la diferencia de meses con mas de un año de diferencia es -> ".$meses."<br>");
                            }
                            else { exit;    }
                        }
                    }
                    return $meses;
    }
    
    /**
     * Funcion para sumar las ordenes de pago a partir del arreglo de OP
     * @param <array> $ordenPago
     * @return int 
     */
    function sumarPagos($ordenPago){
        $acumulado=0;
        if(is_array($ordenPago)){
            foreach ($ordenPago as $op) {
                $acumulado = $acumulado + $op['VALOR_OP'];
            }
        }
        return $acumulado;
    }
    
    
} // fin de la clase
	

?>


                
                