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
class funciones_adminNovedad extends funcionGeneral
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
                
                $this->htmlNovedad = new html_adminNovedad($configuracion);   
                
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
				$this->htmlNovedad->multiplesNovedades($registro, $totalRegistros, $variable);
				break;
		
		}
		
	}
	
		
/*__________________________________________________________________________________________________
		
						Metodos especificos 
__________________________________________________________________________________________________*/

	/**
         *  Funcion que verifica si existe seleccion de un contrato para mostrar el detalle de lo contrario muestra el listado de Contratistas
         * @param type $configuracion
         * @param type $cod_contratista 
         */
        function consultarContratista($interno_co,$cod_contrato,$vigencia){
            
                if($interno_co == ""){
                    
			$this->mostrarListadoInformacionContratistas($vigencia);
		}
		else{
                        $this->mostrarInformacionContratista($interno_co,$cod_contrato,$vigencia);
                }//fin else existe $cod_contrato
	}//fin funcion consultar novedades

	/**
         * Funcion que consulta la informacion del contratista y contrato para mostrarla 
         * @param int $cod_contrato
         * @param int $vigencia
         
          */
        function mostrarInformacionContratista($interno_co,$cod_contrato,$vigencia){
    			$contrato = $this->consultarDatosContrato($interno_co,$vigencia);
                        $cuenta = $this->consultarDatosCta($contrato[0]['INTERNO_PROVEEDOR']);
                        $this->revisarDatosContratista($contrato);
                        $this->revisarDatosActaInicio($contrato);
                        $this->revisarCuentasBancarias($contrato[0]['NUM_IDENTIFICACION'],$contrato[0]['TIPO_IDENTIFICACION'],$cuenta);
                        $contratista = $this->consultarDatosContratista($contrato[0]['NUM_IDENTIFICACION']);
                        $disponibilidad = $this->consultarDatosDisponibilidad($contrato[0]['INTERNO_MC'],$contrato[0]['CODIGO_UNIDAD_EJECUTORA'],$vigencia);
                        $nro_cdp = $disponibilidad[0]['NUMERO_DISPONIBILIDAD']; 
                        $registroPresupuestal = $this->consultarDatosRegistroPresupuestal($nro_cdp,$contrato[0]['CODIGO_UNIDAD_EJECUTORA'],$vigencia);
                        $fecha_contrato=$registroPresupuestal[0]["FECHA_REGISTRO"];
                        $ordenPago = $this->consultarDatosOrdenPago($contrato[0]['NUM_IDENTIFICACION'],$nro_cdp,$vigencia);
                        $saldo_contrato = $this->calcularSaldoContrato($registroPresupuestal,$ordenPago);
                        $tipo_contrato = $this->consultarTipoContrato($vigencia,$contrato[0]['CODIGO_UNIDAD_EJECUTORA'],$registroPresupuestal[0]['NUMERO_REGISTRO']);
                        //var_dump($ordenPago);exit;
                        $contrato[0]['NUM_CONTRATO']=(isset($contrato[0]['NUM_CONTRATO'])?$contrato[0]['NUM_CONTRATO']:'');
                        if ($contrato[0]['NUM_CONTRATO']){
                            $this->revisarDatosContrato($contrato);
                        }
                        if($cod_contrato){ 
                            $novedades = $this->consultarDatosNovedades( $cod_contrato,$vigencia,"","","","","","");
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
                                                        $this->htmlNovedad->mostrarDatosContratista($contratista);
                                                        ?>
						</td>
					  </tr>
                                          <tr>
						<td>
							<?
                                                        $this->htmlNovedad->mostrarDatosContrato($contrato,$tipo_contrato,$fecha_contrato);
                                                        ?>
						</td>
					  </tr>
                                          <tr>
						<td>
							<?
                                                        $this->htmlNovedad->mostrarDatosCuentaBanco($cuenta);
                                                        ?>
						</td>
					  </tr>
                                          <tr>
						<td>
							<?
                                                        if($disponibilidad){
                                                            $this->htmlNovedad->mostrarDatosDisponibilidad($disponibilidad);
                                                        }
                                                        ?>
						</td>
					  </tr>    
                                          <tr>
						<td>
							<?
                                                        if($registroPresupuestal){
                                                            $this->htmlNovedad->mostrarDatosRegistroPresupuestal($registroPresupuestal);
                                                        }
                                                        ?>
						</td>
					  </tr>    
                                          <tr>
						<td>
							<?
                                                        $this->htmlNovedad->mostrarDatosOrdenPago($ordenPago);
                                                        ?>
						</td>
					  </tr>  
                                          <tr>
						<td>
							<?
                                                        $this->htmlNovedad->mostrarSaldo($saldo_contrato);
                                                        ?>
						</td>
					  </tr>  
                                          <tr>
						<td>
							<?
                                                        $this->htmlNovedad->mostrarNovedades($novedades,$contrato);
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
     * Funcion que muestra el listado de los contratistas
     * @param int $vigencia
     */  
    function mostrarListadoInformacionContratistas($vigencia){
                
            $this->formulario = "nom_admin_novedad";
            $indice=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";	
            $tab=0;
            $id_usuario = $this->usuario;
            $mensaje = $this->asignarMensajeSinRegistro();
            //$cod_supervisor = 40;
            $cod_supervisor=$this->obtenerCodigoInternoSupervisor($this->identificacion);
            //echo "codiguito ".$cod_supervisor;
            $busquedaNovedad = $this->revisarExisteBusqueda();
            $registro = $this->consultarContratistas($busquedaNovedad,$vigencia,$cod_supervisor);
            
            $resultado_vigencias = $this->consultarVigencias();
            foreach ($resultado_vigencias as $key => $op_vigencia) {
                $vigencias[$key][0]=$op_vigencia['ID_VIG'];
                $vigencias[$key][1]=$op_vigencia['NOMBRE'];
            }
            $this->htmlNovedad->form_seleccionar_vigencia($vigencias);

            if(is_array($registro))
            {
                $totalRegistros=count($registro);
                    //evaluamos si existe mas de un usuario
                    if($totalRegistros > 1)
                    {
                           
                            $this->mostrarRegistro($this->configuracion,$registro, $this->configuracion['registro'], "multiplesNovedades", "");
                      
                    }
                    else
                    {
                            //Consultar un contratista especifico
                            $this->consultarContratista($registro[0][0],$vigencia);
                    }
            }
            else
            {
                    include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/alerta.class.php");

                    alerta::sin_registro($this->configuracion,$mensaje);
            }
    
    }
    
    /**
     * Funcion que asigna el mensaje correspondiente dependiendo si es consulta o busqueda
     */
     function asignarMensajeSinRegistro(){
            $_REQUEST['clave']=(isset($_REQUEST['clave'])?$_REQUEST['clave']:'');
            if ($_REQUEST['clave']){
                    $cadena = "No existen Contratistas para la consulta.";
            }
            else{
                    $cadena = "No existen Contratistas Relacionados al supervisor.";
            }
            return $cadena;
    }
    
    /**
    * Funcion que revisa si existe parametros para realizar uuna busqueda
    */
    function revisarExisteBusqueda(){
            $_REQUEST['clave']=(isset($_REQUEST['clave'])?$_REQUEST['clave']:'');
            if ($_REQUEST['clave']){
                    $busquedaNovedad = array( 'id_admin' => $this->usuario,
                                                'criterio_busqueda' => $_REQUEST['criterio_busqueda'], 
                                                'valor' => $_REQUEST['clave']);
            }
            else{
                    $busquedaNovedad = '';
            }
            return $busquedaNovedad;
    }

    /**
     * Funcion que consulta los datos de los contratistas
     * @param String $busquedaNovedad
     * @param int $vigencia
     * @param int $id_supervidor
     */
    function consultarContratistas($busquedaNovedad,$vigencia,$id_supervidor){
            if (is_array($busquedaNovedad)){
                    $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_sic,"novedades_todas",$busquedaNovedad);
            }		
            else{
                    $datos = array('vigencia'=>$vigencia,
                                    'id_supervisor'=>$id_supervidor);
                    $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_sic,"novedades_todas",$datos);
                }

            $registro = $this->ejecutarSQL($this->configuracion, $this->acceso_sic,$cadena_sql, "busqueda");

            return $registro;
            
    }
    
    
    
    /**
     * Funcion que consulta en la base de datos informacion del contrato
     * @param int $cod_contrato
     * @param int $vigencia
    */
    function consultarDatosContrato($cod_contrato,$vigencia){
            $datos = array('vigencia'=>$vigencia,
                            'cod_contrato'=>$cod_contrato);
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_sic,"datos_contrato",$datos);
            //echo "<br>cadena ".$cadena_sql ;
            return $datos_contrato = $this->ejecutarSQL($this->configuracion, $this->acceso_sic, $cadena_sql, "busqueda");
			
    }
    
    /**
     * Funcion que consulta en la base de datos informacion de las cuentas bancarias
     * @param int $cod_interno_proveedor
    */
    function consultarDatosCta($cod_interno_proveedor){
        
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_sic,"datos_cuenta",$cod_interno_proveedor);
            //echo "cadena ".$cadena_sql ;
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
            return $resultado = $this->ejecutarSQL($this->configuracion, $this->acceso_sic, $cadena_sql, "busqueda");
    
    }
    
    /**
     * Funcion que consulta en la base de datos informacion del contratista
     * @param int $identificacion
    */
   function consultarDatosContratista($identificacion){
            
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_sic,"datos_contratista",$identificacion);
            return $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_sic, $cadena_sql, "busqueda");
       
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
            //echo $cadena_sql;exit;
            return $datos_disponibilidad = $this->ejecutarSQL($this->configuracion, $this->acceso_sic, $cadena_sql, "busqueda");
       
    }
    
   /**
     * Funcion que consulta en la base de datos informacion de las novedades registradas
     * @param int $interno_contrato
    */
   function consultarDatosNovedades($cod_contrato,$vigencia,$tipo,$fecha_ini,$fecha_fin,$valor,$descripcion,$cta_id){
            $datos= array(  'vigencia'=>$vigencia,
                            'cod_contrato'=>$cod_contrato,
                            'tipo'=>$tipo,
                            'fecha_ini'=>$fecha_ini,
                            'fecha_fin'=>$fecha_fin,
                            'valor'=>$valor,
                            'descripcion'=>$descripcion,
                            'cta_id'=>$cta_id);               
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_nomina,"datos_novedades",$datos);
            return $datos_novedad = $this->ejecutarSQL($this->configuracion, $this->acceso_nomina, $cadena_sql, "busqueda");

    }

   /**
     * Funcion que consulta los datos necesarios para mostrar en el formulario de creacion de novedades
     * @param int $interno_contrato
     * Utiliza los metodos consultarTipoNovedad, consultarBancos,consultarCodigoContratista,consultarCuentas,form_novedad
    */
   function crearNovedad(){
       
            $cod_contrato=(isset($_REQUEST['cod_contrato'])?$_REQUEST['cod_contrato']:'');
            $cod_contratista=(isset($_REQUEST['cod_contratista'])?$_REQUEST['cod_contratista']:'');
            $tipo_id_contratista=(isset($_REQUEST['tipo_id'])?$_REQUEST['tipo_id']:'');
            $interno_prov=(isset($_REQUEST['interno_prov'])?$_REQUEST['interno_prov']:'');
            $vigencia=(isset($_REQUEST['vigencia'])?$_REQUEST['vigencia']:'');
            $interno_oc=(isset($_REQUEST['interno_oc'])?$_REQUEST['interno_oc']:'');
            $unidad_ejec=(isset($_REQUEST['unidad_ejec'])?$_REQUEST['unidad_ejec']:'');
            //var_dump($_REQUEST);exit;
            $cuentas_sic = $this->consultarDatosCta($interno_prov);
            $this->revisarCuentasBancarias($cod_contratista,$tipo_id_contratista,$cuentas_sic);
                        
            $tipo = $this->consultarTipoNovedad();
            if(is_array($tipo)){    
                foreach ($tipo as $key => $tp_novedad) {
                    $tipo_novedad[$key][0]=$tp_novedad['id_tipo'];
                    $tipo_novedad[$key][1]=$tp_novedad['tipo_nov'];
                }
            }else{
                $tipo_novedad = array(0=>'0');
            
            }
            
            $info_bancos = $this->consultarBancos();
            if(is_array($info_bancos)){    
                foreach ($info_bancos as $key => $banco) {
                    $bancos[$key][0]=$banco['id'];
                    $bancos[$key][1]=$banco['nombre'];
                }
            }else{
                $bancos = array(0=>'0');
            
            }
            
            if($cod_contratista){
                $info_cuentas = $this->consultarCuentas($cod_contratista,$tipo_id_contratista);
            }else{
                $info_cuentas = "";
            }
            if(is_array($info_cuentas)){    
                foreach ($info_cuentas as $key => $value) {
                    $cuentas[$key][0]=$info_cuentas[$key]['id'];
                    $cuentas[$key][1]=$info_cuentas[$key]['nombre'];
                }
            }else{
                $cuentas = array(0=>'0');
            
            }
            
            $tipo_cta[0][0] = "A";
            $tipo_cta[0][1] = "AHORROS";
            $tipo_cta[1][0] = "C";
            $tipo_cta[1][1] = "CORRIENTE";
            
            $this->htmlNovedad->form_novedad($tipo_novedad,$cuentas,$bancos,$tipo_cta,$cod_contrato,$vigencia,$interno_prov,$cod_contratista ,$tipo_id_contratista,$unidad_ejec,$interno_oc,"", "");
            
   }        
   
   /**
    * Funcion que consulta los tipos de novedades
    * @return <array>  
    */
   function consultarTipoNovedad(){
                           
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_nomina,"tipo_novedad","");
            return $resultado = $this->ejecutarSQL($this->configuracion, $this->acceso_nomina, $cadena_sql, "busqueda");
    }

    /**
     * Funcion que consulta el listado de cuentas bancarias de un contratista
     * @param String $identificacion
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
     * Funcion que consulta los bancos que existen en el sistema
     * @return <array>  
     */
    function consultarBancos(){
                           
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_nomina,"bancos","");
            return $resultado = $this->ejecutarSQL($this->configuracion, $this->acceso_nomina, $cadena_sql, "busqueda");
    
    }


    
    /**
     * Funcion que captura los datos y valida para realizar el registro de la novedad en el sistema 
     */
    function registrarNovedad(){
            $insertado=0;
            $mensaje='';
            $id=$this->obtenerNumeroNovedad();
            $id_tipo=(isset($_REQUEST['id_tipo'])?$_REQUEST['id_tipo']:'');
            $cod_contrato=(isset($_REQUEST['cod_contrato'])?$_REQUEST['cod_contrato']:''); 
            $interno_oc = (isset($_REQUEST['interno_oc'])?$_REQUEST['interno_oc']:''); 
            $interno_prov = (isset($_REQUEST['interno_prov'])?$_REQUEST['interno_prov']:''); 
            $cod_contratista=(isset($_REQUEST['cod_contratista'])?$_REQUEST['cod_contratista']:''); 
            $tipo_id_contratista=(isset($_REQUEST['tipo_id'])?$_REQUEST['tipo_id']:''); 
            $vigencia=(isset($_REQUEST['vigencia'])?$_REQUEST['vigencia']:''); 
            $unidad_ejec=(isset($_REQUEST['unidad_ejec'])?$_REQUEST['unidad_ejec']:''); 
            $fecha=date('Y-m-d');
            $fecha_ini=(isset($_REQUEST['finicial'])?$_REQUEST['finicial']:''); 
            $fecha_fin=(isset($_REQUEST['ffinal'])?$_REQUEST['ffinal']:''); 
            $valor=(isset($_REQUEST['valor'])?$_REQUEST['valor']:''); 
            $nivel=(isset($_REQUEST['nivel'])?$_REQUEST['nivel']:''); 
            $dependientes=(isset($_REQUEST['dependientes'])?$_REQUEST['dependientes']:''); 
            $descripcion=  strtoupper(isset($_REQUEST['descripcion'])?$_REQUEST['descripcion']:''); 
            $cta_id=(isset($_REQUEST['cta_id'])?$_REQUEST['cta_id']:0);
            $estado="A";
            $id_banco=(isset($_REQUEST['id_banco'])?$_REQUEST['id_banco']:'');
            $tipo=(isset($_REQUEST['id_cta_tipo'])?$_REQUEST['id_cta_tipo']:'');
            $num_cta=(isset($_REQUEST['num_cta'])?$_REQUEST['num_cta']:''); 
            $datos_contratista =$this->consultarExisteDatosContratista($cod_contratista,$tipo_id_contratista);
            if(!is_array($datos_contratista)){
                $this->insertarDatosContratista($cod_contratista,$tipo_id_contratista,$interno_prov);
                $this->insertarDatosContrato($vigencia,$cod_contrato,$unidad_ejec,$interno_oc ,$cod_contratista,$tipo_id_contratista);
                
            }
            
            if($id_tipo==2){
                $valor=$nivel;
            }//ARP
            elseif($id_tipo==7){
                $valor=$dependientes;
            }
                        
            if($cta_id>0){
                $existe_novedad = $this->consultarDatosNovedades( $cod_contrato,$vigencia,$id_tipo,$fecha_ini,$fecha_fin,$valor,$descripcion,$cta_id);
            }else{
                $cta_id=$this->consultarCodigoCuentaBanco($cod_contratista,$tipo_id_contratista,$id_banco,$num_cta,$tipo);
                $existe_novedad = $this->consultarDatosNovedades( $cod_contrato,$vigencia,$id_tipo,$fecha_ini,$fecha_fin,$valor,$descripcion,$cta_id);
            }
            if($existe_novedad[0][0]){
                $mensaje = "Ya existe el registro de la Novedad";
                $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                $variable="pagina=nom_adminNovedad";
                $variable.="&opcion=crearNovedad";
                $variable.="&interno_oc=".$interno_oc;
                $variable.="&vigencia=".$vigencia;
                $variable.="&cod_contrato=".$cod_contrato;
                $variable.="&cod_contratista=".$cod_contratista;
                $variable.="&tipo_id=".$tipo_id_contratista;
                $variable.="&interno_prov=".$interno_prov;
                $variable.="&unidad_ejec=".$unidad_ejec;
                $insertado='';
                $variable=$this->cripto->codificar_url($variable,$this->configuracion);
                $this->retornar($pagina,$variable,$mensaje);

            }else{
                if($fecha_ini < $fecha_fin){
                    
                    switch ($id_tipo) {
                        case 1://cuenta AFC
                                
                                $resultado_afc = $this->registrarAFC($cta_id,$id_banco,$num_cta,$tipo,$fecha_ini,$fecha_fin,$valor,$descripcion,$cod_contratista,$tipo_id_contratista,$cod_contrato,$vigencia);
                                $insertado=$resultado_afc['insertado'];
                                $mensaje=$resultado_afc['mensaje'];
                                break;
                        
                        case 2: //arp
                                if($fecha_ini && $fecha_fin && $valor){
                                    $codigo_novedad_arp = $this->consultarNovedadARP($cod_contrato,$vigencia);
                                    if($codigo_novedad_arp){
                                        $modificado = $this->inactivarNovedad($codigo_novedad_arp);
                                    }
                                    $cta_id=0;
                                    $insertado = $this->insertarNovedad($id,$id_tipo, $vigencia,$cod_contrato,$fecha,$fecha_ini,$fecha_fin,$valor,$descripcion,$estado,$cta_id);        
                                }else{
                                    $mensaje='Verifique los campos obligatorios';
                                }
                                break;
                       
                        default:
                                if($fecha_ini && $fecha_fin && $valor){
                                    $codigo_novedad_arp = $this->consultarNovedadPorTipo($cod_contrato,$vigencia,$id_tipo);
                                    if($codigo_novedad_arp){
                                        $modificado = $this->inactivarNovedad($codigo_novedad_arp);
                                    }
                                    $cta_id=0;
                                    $insertado = $this->insertarNovedad($id,$id_tipo,$vigencia, $cod_contrato,$fecha,$fecha_ini,$fecha_fin,$valor,$descripcion,$estado,$cta_id);
                                }else{
                                    $mensaje='Verifique los campos obligatorios';
                                }
                                break;
                    }
                }else{
                        $mensaje='La fecha inicial debe ser menor a la fecha final';
                }
                    if($insertado){
                        //VARIABLES PARA EL LOG
                                $registro[0] = "INSERTAR";
                                $registro[1] = $id;
                                $registro[2] = "NOVEDAD";
                                $registro[3] = $id;
                                $registro[4] = time();
                                $registro[5] = "Insertar novedad ". $id;
                                $registro[5] .= " - tipo de novedad =". $id_tipo;
                                $registro[5] .= " - vigencia =". $vigencia;
                                $registro[5] .= " - cod_contrato =". $cod_contrato;
                                $this->log_us->log_usuario($registro,$this->configuracion);
                    }
                    $this->verificarRegistroNovedad($insertado,$interno_oc,$vigencia,$cod_contrato,$mensaje);
            }
            
                    
    }

   
    /**
     * Funcion que inserta en la base de datos el registro que relaciona en la base de datos una cuenta bancaria con un contratista
     * @param String $cod_contratista
     * @param String $tipo_id_contratista
     * @param int $id_banco
     * @param int $num_cta
     * @param String $tipo
     * @return type 
     */
    function relacionarCuentaBancariaAContratista($cod_contratista,$tipo_id_contratista,$id_banco,$num_cta,$tipo){
       $numero = $this->obtenerNumeroCuentaBanco();    
       $insertado=$this->insertarCuentaBanco($numero,$cod_contratista,$tipo_id_contratista,$id_banco,$num_cta,$tipo);
       return $insertado;
    }

    
    /**
     * Funcion que inserta en la base de datos el registro de una cuenta bancaria
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
     * Funcion que inserta en la base de datos el registro de una novedad
     */
    function insertarNovedad($id,$id_tipo, $vigencia,$cod_contrato,$fecha,$fecha_ini,$fecha_fin,$valor,$descripcion,$estado,$cta_id){
            $datos_novedad = array('id'=>$id,
                                'id_tipo'=>$id_tipo,
                                'fecha'=>$fecha,
                                'vigencia'=>$vigencia,
                                'cod_contrato'=>$cod_contrato,
                                'fecha_ini'=>$fecha_ini,
                                'fecha_fin'=>$fecha_fin,
                                'cta_id'=>$cta_id,
                                'valor'=>$valor,
                                'descripcion'=>$descripcion,
                                'estado'=>"A",
                                'duracion'=>0);
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_nomina,"insertar_novedad",$datos_novedad);
            //echo "<br>cadena ".$cadena_sql;
            $this->ejecutarSQL($this->configuracion, $this->acceso_nomina, $cadena_sql, "");
            return $this->totalAfectados($this->configuracion, $this->acceso_nomina);
    }

    /**
     * Funcin que retorna un numero identificador consecutivo, para la relacion de cuenta banco a partir del ultimo numero de 
     * cuenta banco que se encuentre registrado en la base de datos
     * @return int 
     */
    function obtenerNumeroCuentaBanco(){
        $numero = $this->consultarUltimoNumeroCuentaBanco();
        $numero++;
        return $numero;
    }

    
    /**
     * Funcion que consulta el ultimo numero de cuenta banco que se encuentre registrado en la base de datos
     * 
     * @return type 
     */
    function consultarUltimoNumeroCuentaBanco(){

            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_nomina,"ultimo_numero_cuenta_banco","");
            $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_nomina, $cadena_sql, "busqueda");
            return $datos[0][0];
    }

    /**
     * Funcion que retorna un numero identificador consecutivo, para la novedad a partir del ultimo numero de 
     * novedad que se encuentre registrada en la base de datos
     * @return type 
     */
    function obtenerNumeroNovedad(){
        $numero = $this->consultarUltimoNumeroNovedad();
        $numero++;
        return $numero;
    }

    /**
     * Funcion que consulta el ultimo numero de la novedad que se encuentre registrado en la base de datos
     * @return type 
     */
    function consultarUltimoNumeroNovedad(){

            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_nomina,"ultimo_numero_novedad","");
            $datos_novedad = $this->ejecutarSQL($this->configuracion, $this->acceso_nomina, $cadena_sql, "busqueda");
            return $datos_novedad[0][0];
    }

    /**
     * Funcion que consulta el numero y tipo de identificacion de un contratista
     * @param int $cod_contrato
     * @param int $vigencia
     * @return <array> 
     */
    function consultarCodigoContratista($cod_contrato,$vigencia){
            $datos=array('cod_contrato'=>$cod_contrato,
                         'vigencia'=>$vigencia);
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_nomina,"codigo_contratista",$datos);
            $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_nomina, $cadena_sql, "busqueda");
            return $datos;
        
    }
    
    /**
     *Funcion que consulta el listado de vigencias que tienen relacionadas contratistas
     * @return type 
     */
    function consultarVigencias(){
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_sic,"lista_vigencias","");
            $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_sic, $cadena_sql, "busqueda");
            return $datos;
        
    }
    
    /**
     * Funcion que consulta el identificador de una cuenta bancaria para un contratista
     * @param String $cod_contratista
     * @param String $tipo_id
     * @param int $id_banco
     * @param int $num_cta
     * @param String $tipo
     * @return int 
     */
    function consultarCodigoCuentaBanco($cod_contratista,$tipo_id, $id_banco,$num_cta,$tipo){
             $datos = array('cod_contratista'=>$cod_contratista,
                                'tipo_id'=>$tipo_id,
                                'id_banco'=>$id_banco,
                                'num_cta'=>$num_cta,
                                'tipo'=>$tipo);
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_nomina,"codigo_cuenta_banco",$datos);
            $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_nomina, $cadena_sql, "busqueda");
            return $datos[0][0];
        
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
    
    /**
     * Funcion que verifica si el registro del contratista ya se encuentra en el sistema de lo contrario lo envia a insertar
     * @param <array> $datos_contrato 
     */
    function revisarDatosContratista($datos_contrato){
            $cod_contratista = $datos_contrato[0]['NUM_IDENTIFICACION'] ;
            $tipo_id_contratista = $datos_contrato[0]['TIPO_IDENTIFICACION'] ;
            $contratista =$this->consultarExisteDatosContratista($cod_contratista,$tipo_id_contratista);
            if(!$contratista[0][0]){
                $interno_prov = $datos_contrato[0]['INTERNO_PROVEEDOR'] ;
                $relacionado = $this->insertarDatosContratista($cod_contratista,$tipo_id_contratista,$interno_prov);
            }
    }
    
    /**
     * Funcion que verifica si el registro del contrato ya se encuentra en el sistema de lo contrario lo envia a insertar
     * @param <array> $datos_contrato 
     */
    function revisarDatosContrato($datos_contrato){
            $cod_contratista = $datos_contrato[0]['NUM_IDENTIFICACION'] ;
            $tipo_id_contratista = $datos_contrato[0]['TIPO_IDENTIFICACION'] ;
            $vigencia = $datos_contrato[0]['VIGENCIA'] ;
            $cod_contrato = $datos_contrato[0]['NUM_CONTRATO'];
            $unidad_ejec = $datos_contrato[0]['CODIGO_UNIDAD_EJECUTORA'] ;
            $interno_oc = $datos_contrato[0]['INTERNO_OC'] ;
            $contrato =$this->consultarExisteDatosContrato($cod_contratista,$tipo_id_contratista,$vigencia,$cod_contrato);
            if(!$contrato[0][0]){
                $relacionado = $this->insertarDatosContrato($vigencia,$cod_contrato,$unidad_ejec,$interno_oc ,$cod_contratista,$tipo_id_contratista);
            }
    }

    /**
     *
     * @param int $cod_contratista
     * @param String $tipo_id_contratista
     * @param int $vigencia
     * @param int $numero
     * @return <array> 
     */
    function consultarExisteDatosContrato($cod_contratista,$tipo_id_contratista,$vigencia,$numero){
            $datos = array('cod_contratista'=>$cod_contratista,
                                'tipo_id'=>$tipo_id_contratista,
                                'vigencia'=>$vigencia,
                                'num_contrato'=>$numero);
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_nomina,"existe_datos_contrato",$datos);
            return $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_nomina, $cadena_sql, "busqueda");
            
    }
    
    /**
     * Funcion que consulta el tipo de contrato relacionado a un contratista
     * @param type $vigencia
     * @param type $unidad_ejecutora
     * @param type $numero_registro
     * @return type 
     */ 
    function consultarTipoContrato($vigencia,$unidad_ejecutora,$numero_registro){
        //busca si existen registro de datos de usuarios en la base de datos 
            $datos = array('vigencia'=>$vigencia,
                            'unidad_ejec'=>$unidad_ejecutora,
                            'num_registro'=>$numero_registro);
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_sic,"tipo_contrato",$datos);
            //echo "<br>cadena ".$cadena_sql;
            $datos_contrato = $this->ejecutarSQL($this->configuracion, $this->acceso_sic, $cadena_sql, "busqueda");
            return $datos_contrato[0][1];
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

   
    /**
     * 
     * @param int $insertado
     * @param int $interno_oc
     * @param int $vigencia
     * @param int $cod_contrato 
     */
    function verificarRegistroNovedad($insertado,$interno_oc,$vigencia,$cod_contrato,$mensaje){
       if ($insertado>0){
                $mensaje = "Novedad registrada con exito";
                $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                $variable="pagina=nom_adminNovedad";
                $variable.="&opcion=consultar";
                $variable.="&interno_oc=".$interno_oc;
                $variable.="&vigencia=".$vigencia;
                $variable.="&cod_contrato=".$cod_contrato;
                
           }else{
                $mensaje = "Error al registrar Novedad - ".$mensaje;
                $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                $variable="pagina=nom_adminNovedad";
                $variable.="&opcion=consultar";
                $variable.="&interno_oc=".$interno_oc;
                $variable.="&vigencia=".$vigencia;
                $variable.="&cod_contrato=".$cod_contrato;
                
            }
            

            $variable=$this->cripto->codificar_url($variable,$this->configuracion);
            $this->retornar($pagina,$variable,$mensaje);

   }
    
     /**
     * Funcion que revisa si existe una cuenta bancaria relacionada a un contratista, de lo contrario realiza el llamado al 
     * metodo correspondiente para insertar el registro
     * @param int $cod_contratista
     * @param String $tipo_id_contratista
     * @param <array> $cuentas 
     */
    function revisarCuentasBancarias($cod_contratista,$tipo_id_contratista,$cuentas){
       if(is_array($cuentas)){ 
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
    }
     
    /**
     * Funcion para consultar en la base de datos el codigo para nomina de un banco
     * @param type $codigo_sic
     * @return type 
     */
    function consultarCodigoBanco($codigo_sic){
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_nomina,"codigo_banco",$codigo_sic);
            //echo $cadena_sql ;exit;
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
            //echo $cadena_sql ;exit;
            $resultado = $this->ejecutarSQL($this->configuracion, $this->acceso_nomina, $cadena_sql, "busqueda");
            return $resultado[0][0];
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

       function consultarNovedadAFC($cod_contrato,$vigencia){
             $datos = array('cod_contrato'=>$cod_contrato,
                                'vigencia'=>$vigencia);
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_nomina,"novedad_afc",$datos);
            $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_nomina, $cadena_sql, "busqueda");
            return $datos[0][0];
        
    }
    
    function inactivarNovedad($id_novedad){

            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_nomina,"inactivar_novedad",$id_novedad);
            // echo "cadena ".$cadena_sql;//exit;
            $this->ejecutarSQL($this->configuracion, $this->acceso_nomina, $cadena_sql, "");
            return $this->totalAfectados($this->configuracion, $this->acceso_nomina);
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

                        $interno_prov = $datos_contrato[0]['INTERNO_PROVEEDOR'] ;
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
            return $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_nomina, $cadena_sql, "busqueda");
            
    }
    
    /**
     * Función para insertar el registro de acta de inicio
     * @param int $id
     * @param int $cod_contrato
     * @param int $vigencia_contrato
     * @param date $fecha
     * @param date $fecha_ini
     * @param date $fecha_fin
     * @param date $fecha_firma
     * @param string $estado
     * @param string $tipo_nomina
     * @return type 
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

    /**
     * Funcion para consultar novedad de tipo arp
     * @param type $cod_contrato
     * @param type $vigencia
     * @return type 
     */
    function consultarNovedadARP($cod_contrato,$vigencia){
             $datos = array('cod_contrato'=>$cod_contrato,
                                'vigencia'=>$vigencia);
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_nomina,"novedad_arp",$datos);
            $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_nomina, $cadena_sql, "busqueda");
            return $datos[0][0];
        
    }
    
    function consultarNovedadPorTipo($cod_contrato,$vigencia,$id_tipo){
             $datos = array('cod_contrato'=>$cod_contrato,
                                'vigencia'=>$vigencia,
                                'id_tipo'=>$id_tipo);
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_nomina,"novedad_por_tipo",$datos);
            $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_nomina, $cadena_sql, "busqueda");
            return $datos[0][0];
        
    }
    
    function verificarMontoAfc($vigencia,$cod_contrato,$valor){
            $validacion='';
            $valor_max_afc = $this->calcularValorMaximoAFC();
            if($valor <= $valor_max_afc){
                $validacion = 'ok';
            }
            return $validacion;
    }
    
    function calcularValorMaximoAFC(){
            $valor_maximo = 0;
            $valor_mes = 0;
            $interno_oc = (isset($_REQUEST['interno_oc'])?$_REQUEST['interno_oc']:'');
            $vigencia = (isset($_REQUEST['vigencia'])?$_REQUEST['vigencia']:'');
            $contrato = $this->consultarDatosContrato($interno_oc,$vigencia);
            if(is_array($contrato)){
                $valor_contrato=$contrato[0]['CUANTIA'];
                $dias_contrato=  $this->calcularDiasContrato($contrato[0]['FECHA_INICIO'], $contrato[0]['FECHA_FINAL']);
                $valor_mes = ($valor_contrato/$dias_contrato)*30;
                $valor_maximo = $valor_mes * 0.3;
            }else{
                echo "Error al calcular valor máximo de AFC, datos incompletos";
            }
            return $valor_maximo;
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
    
    function calcularSaldoContrato($registrosPresupuestales,$ordenesPago){
        $valor_crp=0;
        $valor_op=0;
        $saldo=0;
        foreach ($registrosPresupuestales as $datos_registro) {
                    $valor_crp=$valor_crp+(isset($datos_registro['VALOR'])?$datos_registro['VALOR']:0);
        }
        foreach ($ordenesPago as $datos_orden) {
                   $valor_op=$valor_op+(isset($datos_orden['VALOR_OP'])?$datos_orden['VALOR_OP']:0);
        }
        
        $saldo=$valor_crp-$valor_op;
        return $saldo;
    }
    
    function registrarAFC($cta_id,$id_banco,$num_cta,$tipo,$fecha_ini,$fecha_fin,$valor,$descripcion,$cod_contratista,$tipo_id_contratista,$cod_contrato,$vigencia){
            $id=$this->obtenerNumeroNovedad();
            $id_tipo=1;
            $fecha=date('Y-m-d');
            $estado="A";
            $resultado='';
            $insertado = 0;
            $mensaje='';
            if(($cta_id || ($id_banco && $num_cta && $tipo)) && $fecha_ini && $fecha_fin && $valor){
                    if($fecha_ini < $fecha_fin){
                            //si no selecciono una cuenta existente toma los datos para crearla y relacionarla al contratista    
                            if($cta_id<1 && $id_banco && $num_cta && $tipo){
                                    $cta_id=$this->consultarCodigoCuentaBanco($cod_contratista,$tipo_id_contratista,$id_banco,$num_cta,$tipo);
                                    if($cta_id<1){
                                            $relacionado = $this->relacionarCuentaBancariaAContratista($cod_contratista,$tipo_id_contratista,$id_banco,$num_cta,$tipo);
                                            if($relacionado >0){
                                                $cta_id=$relacionado;

                                            }
                                    }

                                }
                                //si selecciono una cuenta bancaria existente   
                                if($cta_id>0 ){
                                        $codigo_nov_afc = $this->consultarNovedadAFC($cod_contrato,$vigencia);
                                        if($codigo_nov_afc){
                                            $modificado = $this->inactivarNovedad($codigo_nov_afc);
                                        }
                                        $valida_monto = $this->verificarMontoAfc($vigencia,$cod_contrato,$valor);
                                        if($valida_monto =='ok'){
                                            $insertado = $this->insertarNovedad($id,$id_tipo, $vigencia,$cod_contrato,$fecha,$fecha_ini,$fecha_fin,$valor,$descripcion,$estado,$cta_id);
                                        }else{
                                            $insertado = 0;
                                            $mensaje= "Verifique valor de consignacion a la cuenta AFC, debe ser menor al 30 % del valor del pago ";
                                        }
                                }else{
                                        $insertado = 0;
                                        $mensaje= "Verifique cuenta bancaria ";
                                }
                    }else{
                        $mensaje='La fecha inicial debe ser menor a la fecha final';
                    }

            }else{
                $mensaje='Verifique los campos obligatorios';
            }
            $resultado= array('insertado'=>$insertado,
                            'mensaje'=>$mensaje);
            return $resultado;
    }
   
} // fin de la clase
	

?>


                
                