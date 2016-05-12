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
class funciones_adminCumplidoContratista extends funcionGeneral
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
                
                $this->htmlCumplido = new html_adminCumplidoContratista($configuracion);   
                
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
         * Funcion que llama el formulario para solicitar un cumplido
         */
        function solicitarCumplido(){
            $contratos = $this->consultarContratos($this->identificacion);
            $meses = $this->mesesContrato($contratos);
            foreach ($contratos as $key => $contrato) {
                $contrato['NUM_CONTRATO']=(isset($contrato['NUM_CONTRATO'])?$contrato['NUM_CONTRATO']:'');
                $datos_contrato[$key][0]=$contrato['VIGENCIA']."-".$contrato['INTERNO_OC']."-".$contrato['NUM_CONTRATO'];
                $datos_contrato[$key][1]=$contrato['VIGENCIA']." - No.".$contrato['NUM_CONTRATO'];
            }
            $this->htmlCumplido->form_solicitud($datos_contrato,$meses,"","");
            $this->mostrarListadoSolicitudes($contratos);
            
        }
        
        /**
         * Funcion que verifica los datos de una solicitud para insertarla o no 
         */
        function verificarSolicitud(){
            $variables =explode("-", $_REQUEST['codigo_contrato']);
            $vigencia= $variables[0];
            $interno_oc= $variables[1];
            $cod_contrato = $variables[2];
            $mes_cumplido=(isset($_REQUEST['mes_cumplido'])?$_REQUEST['mes_cumplido']:'');
            $anio = substr($mes_cumplido, 0,4);        
            $mes = substr($mes_cumplido, 4,2);
            //verifica que no exista una solicitud del mismo año y mes para ese contrato
            $datos_solicitud =$this->consultarExisteSolicitud($vigencia, $cod_contrato,$anio,$mes);
            
            if(!is_array($datos_solicitud) ){
                    //consultamos datos del contrato y contratista
                    $contrato = $this->consultarDatosContrato($interno_oc,$vigencia);
                    $cuenta = $this->consultarDatosCta($contrato[0]['INTERNO_PROVEEDOR']);
                    $contratista = $this->consultarDatosContratista($contrato[0]['NUM_IDENTIFICACION']);
                    $disponibilidad = $this->consultarDatosDisponibilidad($contrato[0]['INTERNO_MC'],$contrato[0]['CODIGO_UNIDAD_EJECUTORA'],$vigencia);
                    $nro_cdp = $disponibilidad[0]['NUMERO_DISPONIBILIDAD']; 
                    $registroPresupuestal = $this->consultarDatosRegistroPresupuestal($nro_cdp,$contrato[0]['CODIGO_UNIDAD_EJECUTORA'],$vigencia);
                    $ordenPago = $this->consultarDatosOrdenPago($contrato[0]['NUM_IDENTIFICACION'],$nro_cdp,$vigencia);
                    $cod_contratista = $contrato[0]['NUM_IDENTIFICACION'];
                    $tipo_id_contratista = $contrato[0]['TIPO_IDENTIFICACION'];
                    $tipo_contrato = $this->consultarTipoContrato($vigencia,$contrato[0]['CODIGO_UNIDAD_EJECUTORA'],$registroPresupuestal[0]['NUMERO_REGISTRO']);
                    $novedades = $this->consultarDatosNovedades( $cod_contrato,$vigencia);
                    $nivel_arp = $this->buscarNivelARP($novedades);
                    
                    //se validan los datos requeridos para el cumplido 
                    $valida_contratista = $this->validaContratista($contratista);
                    if($valida_contratista!='ok'){
                        $cadena = "Falta información de contratista";
                        $this->htmlCumplido->mensajeAlerta($cadena);
                    }
                    
                    if($nivel_arp ==0){
                        $cadena = "Falta información de nivel de ARP";
                        $this->htmlCumplido->mensajeAlerta($cadena);
                    }

                    $valida_contrato = $this->validaContrato($contrato,$tipo_contrato);
                    if($valida_contrato!='ok'){
                        $cadena = "Falta información de contrato";
                        $this->htmlCumplido->mensajeAlerta($cadena);
                    }

                    $valida_certificados= $this->validaCertificados($disponibilidad,$registroPresupuestal);
                    if($valida_certificados!='ok'){
                        $cadena = "Falta información de registros presupuestales ";
                        $this->htmlCumplido->mensajeAlerta($cadena);
                    }

                    if($valida_contratista=='ok' && $valida_contrato=='ok' && $valida_certificados=='ok' ){
                                $this->htmlCumplido->mostrarMensajeVerificacion();
                    }
                    //se verifica que halla pasado la validacion de datos para mostrar los datos de la solicitud
                    if($valida_contratista=='ok' && $valida_contrato=='ok' && $valida_certificados=='ok' && $nivel_arp>0){
                                $this->revisarCuentasBancarias($cod_contratista,$tipo_id_contratista,$cuenta);
                                $datos_solicitud= array('finicio_contrato'=>$contrato[0]['FECHA_INICIO'],
                                                    'ffinal_contrato'=>$contrato[0]['FECHA_FINAL'],
                                                    'valor_contrato'=>$contrato[0]['CUANTIA'],
                                                    'dias_contrato'=>  $this->calcularDiasContrato($contrato[0]['FECHA_INICIO'], $contrato[0]['FECHA_FINAL']),
                                                    'saldo_contrato'=>  $this->calcularSaldoContrato($contrato,$registroPresupuestal,$ordenPago),
                                                    'mes'=>$mes,
                                                    'anio'=>$anio,
                                                    'vigencia'=>$contrato[0]['VIGENCIA'],
                                                    'num_contrato'=>$contrato[0]['NUM_CONTRATO'],
                                                    'cod_supervisor'=>$contrato[0]['FUNCIONARIO']
                                    );
                                $solicitud[0]=$datos_solicitud;
                
                                $solicitud = $this->asignarValorCumplido($solicitud);
                                $solicitud = $this->asignarValorParafiscales($solicitud,$novedades);
                                $solicitud = $this->asignarValorAFC($solicitud);
                                $solicitud = $this->asignarValorCooperativasYDepositos($solicitud);
                                
                                if(count($cuenta)>1){
                                        $info_cuentas = $this->consultarCuentas($cod_contratista,$tipo_id_contratista);
                                        if(is_array($info_cuentas)){    
                                            foreach ($info_cuentas as $key => $value) {
                                                $cuentas[$key][0]=$info_cuentas[$key]['id'];
                                                $cuentas[$key][1]=$info_cuentas[$key]['nombre'];
                                            }
                                        }else{
                                            $cuentas = array(0=>'0');

                                        }
                                        $this->htmlCumplido->form_envio_solicitud($contrato,$solicitud,$cuentas,"","");

                                }else{
                                    $cod_banco = $this->consultarCodigoBanco($cuenta[0]['CODIGO']);
                                    $num_cta=$cuenta[0]['NRO_CTA'];
                                    $tipo=$cuenta[0]['TIPO_CTA'];

                                    $cod_relacion = $this->consultarCodigoRelacionCuentas($cod_contratista,$tipo_id_contratista,$cod_banco,$num_cta,$tipo);
                                    $this->htmlCumplido->form_envio_solicitud($contrato,$solicitud,$cod_relacion,"","");
                                }
                    }
                    $this->mostrarInformacionContratista($interno_oc,$cod_contrato,$vigencia);

            }else{
                
                $mensaje = "Ya existe una solicitud de cumplido de ese mes";
                $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                $variable="pagina=nom_adminCumplidoContratista";
                $variable.="&opcion=solicitar";
                $variable.="&cod_contrato=".$cod_contrato;
                $variable.="&mes_cumplido=".$mes_cumplido;
                
                $variable=$this->cripto->codificar_url($variable,$this->configuracion);
                $this->retornar($pagina,$variable,$mensaje);

            }

        }
        

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
                        $contrato[0]['DIAS_CONTRATO']=$this->calcularDiasContrato($contrato[0]['FECHA_INICIO'], $contrato[0]['FECHA_FINAL']);
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
           // echo "<br>consulta reg ".$cadena_sql;
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
           // echo "cadena ".$cadena_sql;exit;
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
     * Funcion que toma los datos para registrar una solicitud
     */
    function registrarSolicitud(){
            //capturamos datos que llegan del formulario
            $insertado=0;
            $id=$this->obtenerNumeroSolicitud();
            $cod_contrato=(isset($_REQUEST['cod_contrato'])?$_REQUEST['cod_contrato']:''); 
            $saldo_contrato=(isset($_REQUEST['saldo_contrato'])?$_REQUEST['saldo_contrato']:''); 
            $mes_cumplido=(isset($_REQUEST['mes_cumplido'])?$_REQUEST['mes_cumplido']:''); 
            $vigencia=(isset($_REQUEST['vigencia_contrato'])?$_REQUEST['vigencia_contrato']:''); 
            $finicial=(isset($_REQUEST['finicial_cumplido'])?$_REQUEST['finicial_cumplido']:''); 
            $ffinal=(isset($_REQUEST['ffinal_cumplido'])?$_REQUEST['ffinal_cumplido']:''); 
            $num_dias=(isset($_REQUEST['dias_cumplido'])?$_REQUEST['dias_cumplido']:0); 
            $valor=(isset($_REQUEST['valor_cumplido'])?$_REQUEST['valor_cumplido']:0); 
            $salud=(isset($_REQUEST['salud'])?$_REQUEST['salud']:0); 
            $pension=(isset($_REQUEST['pension'])?$_REQUEST['pension']:0); 
            $arp=(isset($_REQUEST['arp'])?$_REQUEST['arp']:0); 
            $afc=(isset($_REQUEST['afc'])?$_REQUEST['afc']:0); 
            //revisamos si existe novedad de afc
            if($afc){
                $nov_id_afc=(isset($_REQUEST['nov_id_afc'])?$_REQUEST['nov_id_afc']:0); 
            }else{
                $nov_id_afc='';
            }
            $cooperativas_depositos=(isset($_REQUEST['cooperativas_depositos'])?$_REQUEST['cooperativas_depositos']:0); 
            //revisamos si existe novedad de cooperativas
            if($cooperativas_depositos){
                $nov_id_cooperativas_depositos=(isset($_REQUEST['nov_id_cooperativas_depositos'])?$_REQUEST['nov_id_cooperativas_depositos']:0); 
            }else{
                $nov_id_cooperativas_depositos='';
            }
            $annio = substr($mes_cumplido, 0, 4);
            $mes = substr($mes_cumplido, 4, 2);
            $procesado='N';
            $estado = 'SOLICITADO';
            $fecha=date('Y-m-d');
            $estado_reg="A";
            $num_impresion=0;
            $cta_id=(isset($_REQUEST['cta_id'])?$_REQUEST['cta_id']:''); 
            $cod_supervisor=(isset($_REQUEST['cod_supervisor'])?$_REQUEST['cod_supervisor']:0); 
            
            //verificamos que no exista ya una solicitud para ese periodo y contrato
            $datos_solicitud =$this->consultarExisteSolicitud($vigencia, $cod_contrato,$annio,$mes);
            if(!is_array($datos_solicitud) && $vigencia && $cod_contrato && $annio && $mes && $cta_id){
                     $insertado = $this->insertarSolicitud($id,$vigencia,$cod_contrato,$annio,$mes,$num_dias,$procesado, $estado ,$fecha,$estado_reg, $num_impresion, $valor,$cta_id,$finicial,$ffinal,$cod_supervisor);
                     if ($insertado>0){
                            
                         $tmp_insertado = $this->insertarTemporalDetalleNomina($id,$vigencia,$finicial,$ffinal,$num_dias, $valor,$salud,$pension,$arp,$afc,$saldo_contrato,$cooperativas_depositos);
                         if($tmp_insertado){
                                //VARIABLES PARA EL LOG
                                $registro[0] = "INSERTAR";
                                $registro[1] = $id;
                                $registro[2] = "CUMPLIDO";
                                $registro[3] = $id;
                                $registro[4] = time();
                                $registro[5] = "Insertar solicitud cumplido ". $id;
                                $registro[5] .= " - vigencia =". $vigencia;
                                $registro[5] .= " - cod_contrato =". $cod_contrato;
                                $registro[5] .= " - anio =". $annio;
                                $registro[5] .= " - mes =". $mes;
                                $registro[5] .= " - fecha =". $fecha;
                                $this->log_us->log_usuario($registro,$this->configuracion);
                         }
                         if($nov_id_afc){
                                        $this->insertarNovedadCumplido($id,$vigencia,$nov_id_afc);
                          }
                          if($nov_id_cooperativas_depositos){
                                        $this->insertarNovedadCumplido($id,$vigencia,$nov_id_cooperativas_depositos);
                          }
                     }
            }
            
            if ($insertado>0 && $tmp_insertado>0){
                $mensaje = "Solicitud registrada con exito";
                $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                $variable="pagina=nom_adminCumplidoContratista";
                $variable.="&opcion=solicitar";
                $variable.="&cod_contrato=".$cod_contrato;
                $variable.="&mes_cumplido=".$mes_cumplido;
                
                $variable=$this->cripto->codificar_url($variable,$this->configuracion);
                $this->retornar($pagina,$variable,$mensaje);

            }else{
                if($insertado<1){
                    
                }
                $mensaje = "Error al registrar Solicitud";
                $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                $variable="pagina=nom_adminCumplidoContratista";
                $variable.="&opcion=solicitar";
                $variable.="&cod_contrato=".$cod_contrato;
                $variable.="&mes_cumplido=".$mes_cumplido;
                
                $variable=$this->cripto->codificar_url($variable,$this->configuracion);
                $this->retornar($pagina,$variable,$mensaje);

            }
                    
    }

    
    /**
     * Funcion que inserta una solcicitud de cumplido
     * @param int $id
     * @param int $vigencia
     * @param int $cod_contrato
     * @param int $annio
     * @param int $mes
     * @param int $num_dias
     * @param String $procesado
     * @param String $estado
     * @param date $fecha
     * @param String $estado_reg
     * @param int $num_impresion
     * @param double $valor
     * @param int $cta_id
     * @param int $cod_supervisor
     * @return int 
     */
    function insertarSolicitud($id,$vigencia,$cod_contrato,$annio,$mes,$num_dias,$procesado, $estado ,$fecha,$estado_reg, $num_impresion, $valor,$cta_id,$finicial,$ffinal,$cod_supervisor){
            $datos_novedad = array('id'=>$id,
                                'vigencia'=>$vigencia,
                                'cod_contrato'=>$cod_contrato,
                                'annio'=>$annio,
                                'mes'=>$mes,
                                'num_dias'=>$num_dias,
                                'procesado'=>$procesado,
                                'estado'=>$estado,
                                'fecha'=>$fecha,
                                'estado_reg'=>$estado_reg,
                                'num_impresion'=>$num_impresion,
                                'valor'=>$valor,
                                'cta_id'=>$cta_id,
                                'finicial_cumplido'=>$finicial,
                                'ffinal_cumplido'=>$ffinal,
                                'cod_supervisor'=>$cod_supervisor);
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_nomina,"insertar_solicitud",$datos_novedad);
            $this->ejecutarSQL($this->configuracion, $this->acceso_nomina, $cadena_sql, "");
            return $this->totalAfectados($this->configuracion, $this->acceso_nomina);
    }

    /**
     * Funcion para obtener un numero consecutivo para la solicitud
     * @return int 
     */
    function obtenerNumeroSolicitud(){
        $numero = $this->consultarUltimoNumeroSolicitud();
        $numero++;
        return $numero;
    }

    /**
     * Funcion que consulta el último número de solicitud de la novedad
     * @return int 
     */
    function consultarUltimoNumeroSolicitud(){

            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_nomina,"ultimo_numero_solicitud","");
            $datos_novedad = $this->ejecutarSQL($this->configuracion, $this->acceso_nomina, $cadena_sql, "busqueda");
            return $datos_novedad[0][0];
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
     * 
     * @param int $vigencia
     * @param int $cod_contrato
     * @param int $anio
     * @param int $mes
     * @return <array> 
     */
    function consultarExisteSolicitud($vigencia, $cod_contrato,$anio,$mes){
            $datos = array('vigencia'=>$vigencia,
                                'cod_contrato'=>$cod_contrato,
                                'anio'=>$anio,
                                'mes'=>$mes);
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_nomina,"existe_cumplido",$datos);
            return $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_nomina, $cadena_sql, "busqueda");
            
    }
    
    /**
     * Funcion que revisa si existe una cuenta bancaria relacionada a un contratista, de lo contrario realiza el llamado al 
     * metodo correspondiente para insertar el registro
     * @param int $cod_contratista
     * @param String $tipo_id_contratista
     * @param <array> $cuentas 
     */
    function revisarCuentasBancarias($cod_contratista,$tipo_id_contratista,$cuentas){
        
        foreach ($cuentas as $key => $cuenta) {
            $cod_banco = $this->consultarCodigoBanco($cuenta['CODIGO']);
            $num_cta=$cuenta['NRO_CTA'];
            $tipo=$cuenta['TIPO_CTA'];
            $cod_relacion = $this->consultarCodigoRelacionCuentas($cod_contratista,$tipo_id_contratista,$cod_banco,$num_cta,$tipo);
            if(!$cod_relacion){
                $relacionado = $this->relacionarCuentaBancariaAContratista($cod_contratista,$tipo_id_contratista,$cod_banco,$num_cta,$tipo);
            }

        }
    }
    
    /**
     * Funcion para consultar en la base de datos el codigo para nomina de un banco
     * @param type $codigo_sic
     * @return type 
     */
    function consultarCodigoBanco($codigo_sic){
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_nomina,"codigo_banco",$codigo_sic);
            $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_nomina, $cadena_sql, "busqueda");
           return $datos[0][0];
    }
    
    /**
     * Funcion para consultar en la base de datos el codigo de la relación de una cuenta con un contratista
     * @param type $cod_contratista
     * @param String $tipo_id_contratista
     * @param int $cod_banco
     * @param int $num_cta
     * @param String $tipo
     * @return int 
     */
    function consultarCodigoRelacionCuentas($cod_contratista,$tipo_id_contratista,$cod_banco,$num_cta,$tipo){
            $datos = array('cod_contratista'=>$cod_contratista,
                            'tipo_id'=>$tipo_id_contratista,
                            'id_banco'=>$cod_banco,
                            'num_cta'=>$num_cta,
                            'tipo'=>$tipo);
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_nomina,"codigo_cuenta_banco",$datos);
            $resultado = $this->ejecutarSQL($this->configuracion, $this->acceso_nomina, $cadena_sql, "busqueda");
            return $resultado[0][0];
    }
    
    /**
     * Funcion que busca el numero de una cuenta de banco y llama el metodo para hacer el registro de la cuenta con el contratista
     * @param String $cod_contratista
     * @param String $tipo_id_contratista
     * @param int $id_banco
     * @param int $num_cta
     * @param String $tipo
     * @return int 
     */
    function relacionarCuentaBancariaAContratista($cod_contratista,$tipo_id_contratista,$id_banco,$num_cta,$tipo){
       $numero = $this->obtenerNumeroCuentaBanco();    
       $insertado=$this->insertarCuentaBanco($numero,$cod_contratista,$tipo_id_contratista,$id_banco,$num_cta,$tipo);
       return $insertado;
    }

    /**
     * Funcion que retorna el numero consecutivo a partir del ultimo numero de relacion de cuenta banco registrado
     * @return type 
     */    
    function obtenerNumeroCuentaBanco(){
        $numero = $this->consultarUltimoNumeroCuentaBanco();
        $numero++;
        return $numero;
    }

       
    /**
     * Funcion que consulta en la base de datos el ultimo numero de relacion de cuenta banco 
     * @return int 
     */
    function consultarUltimoNumeroCuentaBanco(){

            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_nomina,"ultimo_numero_cuenta_banco","");
            $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_nomina, $cadena_sql, "busqueda");
            return $datos[0][0];
    }

    /**
     * Funcion que inserta un registro de cuenta bancaria de un contratista en la base de datos
     * @param int $id
     * @param int $cod_contratista
     * @param String $tipo_id_contratista
     * @param type $id_banco
     * @param type $num_cta
     * @param type $tipo
     * @return int 
     */
    
    function insertarCuentaBanco($id,$cod_contratista,$tipo_id_contratista,$id_banco,$num_cta,$tipo){
            $datos = array('id'=>$id,
                                'cod_contratista'=>$cod_contratista,
                                'tipo_id'=>$tipo_id_contratista,
                                'id_banco'=>$id_banco,
                                'num_cta'=>$num_cta,
                                'tipo'=>$tipo,
                                'estado'=>"A");
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_nomina,"insertar_relacion_cuenta_banco",$datos);
            $this->ejecutarSQL($this->configuracion, $this->acceso_nomina, $cadena_sql, "");
            if ($this->totalAfectados($this->configuracion, $this->acceso_nomina)>0){
                return $id;
            }else{
                return 0;
            }

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
            $datos_contrato = $this->ejecutarSQL($this->configuracion, $this->acceso_sic, $cadena_sql, "busqueda");
            return $datos_contrato;
    }
    
    /**
     * Funcion que retorna un arreglo con los meses que posee un contrato con el respectivo año
     * @param type $contratos
     * @return type 
     */
    function mesesContrato($contratos){
        $indice=0;
        foreach ($contratos as $key => $contrato) {
                $contrato['FECHA_INICIO']=(isset($contrato['FECHA_INICIO'])?$contrato['FECHA_INICIO']:'');
                $contrato['FECHA_FINAL']=(isset($contrato['FECHA_FINAL'])?$contrato['FECHA_FINAL']:'');
                    
                if($contrato['FECHA_INICIO'] && $contrato['FECHA_FINAL']){
                        $anio_inicial=substr($contrato['FECHA_INICIO'], 0,4);
                        $anio_final=substr($contrato['FECHA_FINAL'], 0,4);
                        $mes_inicial=substr($contrato['FECHA_INICIO'], 5,2);
                        $mes_final=substr($contrato['FECHA_FINAL'], 5,2);
                        $fecha_inicial=$anio_inicial.$mes_inicial;
                        $fecha_final=$anio_final.$mes_final;
                        
                        for($i=$anio_inicial;$i<=$anio_final;$i++){
                            for($mes=1;$mes<=12;$mes++){
                                
                                if($mes<10)
                                    $mes="0".$mes;
                                $fecha=$i.$mes;
                                if($fecha>=$fecha_inicial && $fecha<=$fecha_final){
                                    $meses[$indice][0]=$i.$mes;
                                    $meses[$indice][1]=$i."- ".$this->nombreMes($mes);
                                    $indice++;
                                }
                            }
                        }
                        
                }
        }
        $meses=(isset($meses)?$meses:'');
        if($meses){
            $meses = $this->ordenarMeses($meses);
        }
        return $meses;
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
     * Funcion que llama al metodo de consultar las solicitudes y al metodo de mostrar la información relacionada
     * @param <array> $contratos 
     */
    function mostrarListadoSolicitudes($contratos){
        //var_dump($contratos);exit;
        $registro='';
        
        if(is_array($contratos)){
            foreach ($contratos as $key => $contrato) {
                $resultado='';
                $vigencia=$contrato['VIGENCIA'];
                $cod_contrato=(isset($contrato['NUM_CONTRATO'])?$contrato['NUM_CONTRATO']:'');
                if($vigencia && $cod_contrato){
                    $resultado = $this->consultarSolicitudes($vigencia,$cod_contrato,'');
                }
                if($resultado){
                    $registro[] = $resultado;
                }

            }
            
        }
        
       
        if($registro){
            $indice=0;
            foreach ($registro as $key => $value) {
                $arreglos=$value;
                foreach ($arreglos as $arreglo) {
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
                        $contratista = $this->consultarDatosContratista($cumplidos[$indice]['num_id_contratista']);
                        $cumplidos[$indice]['nombre_contratista'] =$contratista[0]['PRIMER_NOMBRE'];
                        $cumplidos[$indice]['nombre_contratista'] .=" ".(isset($contratista[0]['SEGUNDO_NOMBRE'])?$contratista[0]['SEGUNDO_NOMBRE']:'');
                        $cumplidos[$indice]['nombre_contratista'] .=" ".$contratista[0]['PRIMER_APELLIDO']." ".$contratista[0]['SEGUNDO_APELLIDO'];
                        
                        $indice++;

                }
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
    function consultarSolicitudes($vigencia,$cod_contrato,$id_solicitud){
            $datos=array('vigencia'=>$vigencia,
                            'cod_contrato'=>$cod_contrato,
                            'id_solicitud'=>$id_solicitud);
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_nomina,"solicitudes_cumplido",$datos);
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
     * Funcion que consulta los registros de todas las solicitudes de cumplido 
     * @param int $vigencia
     * @param int $cod_contrato
     * @return <array> 
     */
    function consultarTodasSolicitudesCumplido(){
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_nomina,"todas_solicitudes_cumplido","");
            $resultado = $this->ejecutarSQL($this->configuracion, $this->acceso_nomina, $cadena_sql, "busqueda");
            return $resultado;
        
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
        * Funcion que consulta los datos de un documento
        * @param int $codigo
        * @return <array> 
        */
       function consultarDocumento($codigo){
            $cadena_sql=$this->sql->cadena_sql($this->configuracion,$this->acceso_nomina,"documento",$codigo);
            return $resultado=$this->ejecutarSQL($this->configuracion, $this->acceso_nomina, $cadena_sql, "busqueda");
		    
       }   

        /**
     * Funcion que retorna fechas de cumplido, valor y dias 
     * @param type $solicitudes
     * @return <array>/String 
     */
    function asignarValorCumplido($solicitudes){
        if(is_array($solicitudes)){
            foreach ($solicitudes as $key => $solicitud) {
                $fecha_inicial=$solicitud['finicio_contrato'];
                $fecha_final=$solicitud['ffinal_contrato'];
                $valor_contrato=$solicitud['valor_contrato'];
                $tiempo_contrato_dias=$solicitud['dias_contrato'];
                $mes_cumplido=$solicitud['mes'];
                $anio_cumplido=$solicitud['anio'];
                $cumplido = $this->calcularFechasCumplido($fecha_inicial,$fecha_final,$mes_cumplido,$anio_cumplido);
                $cumplido['dias'] = $this->calcularDiasCumplido($cumplido['finicial'],$cumplido['ffinal']);
                $dias_cumplido = $cumplido['dias'];
                $valor_dia = $this->calcularValorDia($valor_contrato,$tiempo_contrato_dias);
                $valor_cumplido = $dias_cumplido*$valor_dia;
                //reviso el saldo si es menor al valor del cumplido asigno este valor
                $valor_saldo = $solicitud['saldo_contrato'];
                if($valor_saldo>=$valor_cumplido){
                    $solicitudes[$key]['valor']=$valor_cumplido;
                }else{
                    $solicitudes[$key]['valor']=$valor_saldo;
                }
                $solicitudes[$key]['finicio_cumplido']=$cumplido['finicial'];
                $solicitudes[$key]['ffinal_cumplido']=$cumplido['ffinal'];
                $solicitudes[$key]['dias_cumplido']=$dias_cumplido;

            }                       
        }else{
            $solicitudes='';
        }
        return $solicitudes;
    }
    
     /**
     * Funcion que retorna las fechas para el cumplido
     * @param date $fecha_inicial
     * @param date $fecha_final
     * @param int $mes_cumplido
     * @param int $anio_cumplido
     * @return string 
     */
    function calcularFechasCumplido($fecha_inicial,$fecha_final,$mes_cumplido,$anio_cumplido){
            $mes_inicio_contrato= substr($fecha_inicial, 5,2);
            $anio_inicio_contrato= substr($fecha_inicial, 0,4);
            $mes_fin_contrato= substr($fecha_final, 5,2);
            $anio_fin_contrato= substr($fecha_final, 0,4);
            //revisamos la fecha inicial si corresponde a la fecha inicial del contrato
            if($mes_cumplido==$mes_inicio_contrato && $anio_cumplido==$anio_inicio_contrato){
                $finicial_cumplido=$fecha_inicial;
            }else{
                $finicial_cumplido=$anio_cumplido.'-'.$mes_cumplido.'-01';
            }
            
            //revisamos la fecha final si corresponde a la fecha final del contrato
            if($mes_cumplido==$mes_fin_contrato && $anio_cumplido==$anio_fin_contrato){
                $ffinal_cumplido=$fecha_final;
            }else{
                $mes = mktime( 0, 0, 0, $mes_cumplido, 1, $anio_cumplido ); 
                $numeroDeDias = date("t",$mes); 
                $ffinal_cumplido=$anio_cumplido.'-'.$mes_cumplido.'-'.$numeroDeDias;
            }
            
            $resultado=array(
                            'finicial'=>$finicial_cumplido,
                            'ffinal'=>$ffinal_cumplido
            );
            
            return $resultado;
    }
    
    /**
     * Funcion que calcula la cantidad de dias para un cumplido con base a la fecha inicial y la fecha final del cumplido
     * @param type $fecha_inicio
     * @param string $fecha_fin
     * @return int 
     */
    function calcularDiasCumplido($fecha_inicio,$fecha_fin){
        $dia_fin= substr($fecha_fin, 8,2);
        $mes_fin= substr($fecha_fin, 5,2);
        $ano_fin= substr($fecha_fin, 0,4);
           
        if($dia_fin==28 && $mes_fin=='02'){
            $fecha_fin=$ano_fin.'-'.$mes_fin.'-30';
        }
        $dias= ((strtotime($fecha_fin)-strtotime($fecha_inicio))/86400);    
        $dias++;
        if($dias>30){
            $dias=30;
        }
        return $dias;
    }
    
    
       /**
     * Funcion que calcula el valor a pagar por día
     * @param double $valor_contrato
     * @param int $tiempo_contrato_dias
     * @return double 
     */
    function calcularValorDia($valor_contrato,$tiempo_contrato_dias){
            $valor_dia = $valor_contrato/$tiempo_contrato_dias;
            return $valor_dia;
    }
    
    /**
     * Funcion para liquidar y asignar a las solicitudes los valores de parafiscales
     * @param type $solicitudes
     * @param type $novedades
     * @return string 
     */
    function asignarValorParafiscales($solicitudes,$novedades){
        include_once($this->configuracion["raiz_documento"] . $this->configuracion["bloques"]."/nomina/contratistas/nom_admin_cumplido_supervisor". $this->configuracion["clases"] . "/liquidacionNomina.class.php");
        $this->Liquidacion = new liquidacionNomina($this->configuracion);
        //var_dump($solicitudes);exit;
        if(is_array($solicitudes)){
            foreach ($solicitudes as $key => $solicitud) {
                
                $solicitudes[$key]['salud']=10;
                $parametro[0]['nombre_parametro']='valor_mes';
                $parametro[0]['valor_parametro']=$solicitud['valor'];
                $solicitudes[$key]['salud'] = $this->Liquidacion->obtenerValorLiquidacion(1,'liq_salud',$parametro);
                $solicitudes[$key]['pension']=$this->Liquidacion->obtenerValorLiquidacion(2,'liq_pension',$parametro);
                $parametro[0]['nivel_arp']=$this->buscarNivelARP($novedades);
                $solicitudes[$key]['arp']=$this->Liquidacion->obtenerValorLiquidacion(3,'liq_arp',$parametro);
                
            }                       
        }else{
            $solicitudes='';
        }
        return $solicitudes;
    }
       /**
     * Funcion que inserta una solcicitud de cumplido
     * @param int $id
     * @param int $vigencia
     * @param int $cod_contrato
     * @param int $annio
     * @param int $mes
     * @param int $num_dias
     * @param String $procesado
     * @param String $estado
     * @param date $fecha
     * @param String $estado_reg
     * @param int $num_impresion
     * @param double $valor
     * @param int $cta_id
     * @return int 
     */
    function insertarTemporalDetalleNomina($id_cumplido,$vigencia,$finicial,$ffinal,$num_dias, $valor,$salud,$pension,$arp,$afc,$saldo_contrato, $cooperativas){
            $datos_temporales = array('id_cumplido'=>$id_cumplido,
                                'vigencia'=>$vigencia,
                                'finicial_cumplido'=>$finicial,
                                'ffinal_cumplido'=>$ffinal,
                                'num_dias'=>$num_dias,
                                'valor_liq_antes_iva'=>$valor,
                                'salud'=>$salud,
                                'pension'=>$pension,
                                'arp'=>$arp,
                                'afc'=>$afc,
                                'cooperativas_depositos'=>$cooperativas,
                                'saldo_contrato'=>$saldo_contrato,
                                'estado'=>'SOLICITADO');
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_nomina,"insertar_temporal_detalle_nomina",$datos_temporales);
             //echo "cadena ".$cadena_sql;//exit;
            $this->ejecutarSQL($this->configuracion, $this->acceso_nomina, $cadena_sql, "");
            return $this->totalAfectados($this->configuracion, $this->acceso_nomina);
    }

    /**
     * Funcion para buscar el nivel de arp a partir de las novedades
     * @param <array> $novedades
     * @return int 
     */
    function buscarNivelARP($novedades){
        $nivel='0';
        if(is_array($novedades)){
            foreach ($novedades as $novedad) {

                if($novedad['cod_tipo_nov']=='2' && $novedad['estado_nov']='A' ){
                    $nivel = (int)$novedad['valor_nov'];
                    break;
                }
            }
        }
        return $nivel;
    }
  
      /**
     * Funcion que retorna solicitudes con valores de AFC si lo tienen
     * @param <array> $solicitudes
     * @return <array>/String 
     */
    function asignarValorAFC($solicitudes){
        //var_dump($solicitudes);exit;
        if(is_array($solicitudes)){
            foreach ($solicitudes as $key => $solicitud) {
                $vigencia=$solicitud['vigencia'];
                $num_contrato=$solicitud['num_contrato'];
                $fecha_inicial_cum=$solicitud['finicio_cumplido'];
                $fecha_final_cum=$solicitud['ffinal_cumplido'];
                $afc = $this->consultarNovedadAFCContrato($vigencia,$num_contrato,$fecha_inicial_cum,$fecha_final_cum);
                if(is_array($afc)){
                    $solicitudes[$key]['valor_afc']=$afc[0]['nov_valor'];
                    $solicitudes[$key]['nov_id_afc']=$afc[0]['nov_id'];
                }else{
                    $solicitudes[$key]['valor_afc']=0;
                }
                
            }                       
        }else{
            $solicitudes='';
        }
        return $solicitudes;
    }
    
    
    /**
     * Funcion que consulta las novedades de tipo AFC
     * @param int $vigencia
     * @param int $cod_contrato
     * @param date $finicio_per_pago
     * @param date $ffinal_per_pago
     * @return <array> 
     */
    function consultarNovedadAFCContrato($vigencia,$cod_contrato,$finicio_per_pago,$ffinal_per_pago){
            $datos= array(  'vigencia'=>$vigencia,
                            'cod_contrato'=>$cod_contrato,
                            'finicio_per_pago'=>$finicio_per_pago,
                            'ffinal_per_pago'=>$ffinal_per_pago
                );
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_nomina,"novedad_afc_contrato",$datos);
            return $resultado = $this->ejecutarSQL($this->configuracion, $this->acceso_nomina, $cadena_sql, "busqueda");
          
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
            $this->ejecutarSQL($this->configuracion, $this->acceso_nomina, $cadena_sql, "");
            return $this->totalAfectados($this->configuracion, $this->acceso_nomina);
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
     * Funcion para calcular el saldo de un contrato de acuerdo a las ordenes de pago
     * @param <array> $contrato
     * @param <array> $registroPresupuestal
     * @param <array> $ordenPago
     * @return int 
     */
    function calcularSaldoContrato($contrato,$registroPresupuestal,$ordenPago){
        $acumulado = $this->sumarPagos($ordenPago);
        $total_contrato = $this->calcularValorContrato($registroPresupuestal);
        $saldo = $total_contrato - $acumulado;
        return $saldo;
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
    
    /**
     * Funcion para sumar los valores de los registros presupuestales de un contrato
     * @param <array> $registros
     * @return int 
     */ 
    function calcularValorContrato($registros){
        $acumulado=0;
        if(is_array($registros)){
            foreach ($registros as $rp) {
                $acumulado = $acumulado + $rp['VALOR'];
            }
        }
        return $acumulado;
    }
    
    /**
     * Funcion para asignar los valores registrados de cooperativas por medio de novedad
     * @param <array> $solicitudes
     * @return string 
     */
    function asignarValorCooperativasYDepositos($solicitudes){
        if(is_array($solicitudes)){
            foreach ($solicitudes as $key => $solicitud) {
                $vigencia=$solicitud['vigencia'];
                $num_contrato=$solicitud['num_contrato'];
                $fecha_inicial_cum=$solicitud['finicio_cumplido'];
                $fecha_final_cum=$solicitud['ffinal_cumplido'];
                $cooperativas = $this->consultarNovedadCooperativasContrato($vigencia,$num_contrato,$fecha_inicial_cum,$fecha_final_cum);
                if(is_array($cooperativas)){
                    $solicitudes[$key]['valor_cooperativas_depositos']=$cooperativas[0]['nov_valor'];
                    $solicitudes[$key]['nov_id_cooperativas_depositos']=$cooperativas[0]['nov_id'];
                }else{
                    $solicitudes[$key]['valor_cooperativas_depositos']=0;
                }
                
            }                       
        }else{
            $solicitudes='';
        }
        return $solicitudes;
    }
    
    /**
     * Funcion para consultar las novedades de tipo cooperativas relacionadas a un contrato
     * @param int $vigencia
     * @param int $cod_contrato
     * @param date $finicio_per_pago
     * @param date $ffinal_per_pago
     * @return <array>
     */
    function consultarNovedadCooperativasContrato($vigencia,$cod_contrato,$finicio_per_pago,$ffinal_per_pago){
            $datos= array(  'vigencia'=>$vigencia,
                            'cod_contrato'=>$cod_contrato,
                            'finicio_per_pago'=>$finicio_per_pago,
                            'ffinal_per_pago'=>$ffinal_per_pago
                );
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_nomina,"novedad_cooperativas_contrato",$datos);
            return $resultado = $this->ejecutarSQL($this->configuracion, $this->acceso_nomina, $cadena_sql, "busqueda");
          
    }
    
    
} // fin de la clase
	

?>


                
                