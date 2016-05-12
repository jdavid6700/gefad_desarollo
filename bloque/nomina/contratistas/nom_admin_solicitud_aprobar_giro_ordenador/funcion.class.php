<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/
/*---------------------------------------------------------------------------------------
 |				Control Versiones				    	|	
 ----------------------------------------------------------------------------------------
 | fecha      |        Autor            | version     |              Detalle            |
 ----------------------------------------------------------------------------------------
 | 10/05/2013 | Maritza Callejas C.  	| 0.0.0.1     |                                 |
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
class funciones_adminSolicitudAprobarGiro extends funcionGeneral
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
                
                $this->htmlNomina = new html_adminSolicitudAprobarGiro($configuracion);   
                
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
     * Funcion para listar las nominas generadas y seleccionarlas para solicitar la aprobacion de giro
     */
    function revisarNominasGeneradas(){
          
        $nominas = $this->consultarNominasGeneradasSinSolicitudGiro($this->identificacion);
        $dependencias= $this->consultarDependencias();
        if(is_array($nominas)){
                foreach ($nominas as $key => $nomina) {
                    //completamos la informacion 
                    $cod_dependencia = $nomina['nom_cod_dep_supervisor'];
                    $dependencia= $this->consultarNombreDependencia($dependencias,$cod_dependencia);
                    $nominas[$key]['nombre_dependencia'] = $dependencia;
                    $cod_rubro = $nomina['nom_rubro_interno'];
                    $vigencia = $nomina['nom_anio'];
                    $rubro = $this->consultarRubro($vigencia,$cod_rubro);
                    $nominas[$key]['nombre_rubro'] = $rubro[0]['NOMBRE_RUBRO'];
                }   
            }
            
            $this->htmlNomina->form_solicitar_giro($this->configuracion, $nominas);
        }
    
          /**
       * Funcion para consultar las nominas generadas relacionados a un ordenador
       * @param int $id_supervisor
       * @return <array> 
       */
        function consultarNominasGeneradasSinSolicitudGiro($id_ordenador){
            
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_nomina,"nominas_generadas_sin_solicitud_giro",$id_ordenador);
            //echo "<br>cadena ".$cadena_sql;
            return $resultado= $this->ejecutarSQL($this->configuracion, $this->acceso_nomina, $cadena_sql, "busqueda");
	
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
     * revisa las nominas generadas y seleccionadas para realizar el correspondiente registro en la base de datos con la solicitud de giro
     */
    function revisarSolicitudAprobacionGiro(){
        //var_dump($_REQUEST);exit;
        $this->hacerRollback();
        exit;
        $aprobados=0;
        $mensaje="";
        $total = (isset($_REQUEST['total_registros'])?$_REQUEST['total_registros']:0);
        if($total){
                $indice=0;
                $num_op_uni_uno = $this->obtenerNumeroOrdenPago('01');
                $num_op_uni_dos = $this->obtenerNumeroOrdenPago('02');
//                $num_op_uni_uno = 100;
//                $num_op_uni_dos = 50;
                $conceptos_nomina = $this->consultarConceptosCuentasNomina();
                //buscamos y almacenamos en un arreglo los cumplidos seleccionados para la solicitud de pago
                for($i=0;$i<$total;$i++){
                    $valido=0;
                    $nombre = "id_nomina_".$i;
                    $nombre_vigencia = "vigencia_".$i;
                    $_REQUEST[$nombre]=(isset($_REQUEST[$nombre])?$_REQUEST[$nombre]:'');
                    $_REQUEST[$nombre_vigencia]=(isset($_REQUEST[$nombre_vigencia])?$_REQUEST[$nombre_vigencia]:'');
                    
                    if($_REQUEST[$nombre]){
                            $id_solicitud = $this->consultarUltimoNumeroSolicitudGiro()+1;
                            $id_nomina=$_REQUEST[$nombre];
                            $vigencia= $_REQUEST[$nombre_vigencia];
                            $detalles = $this->consultarDetallesNomina($id_nomina);
                            $detalles = $this->asignarDatosContratoDetalle($detalles);
                            //var_dump($detalles);
                            $this->vaciarTablasTemporales();
                            foreach ($detalles as $detalle) {
                                    //ejecutar proceso para archivo tmp de ordenes de pago
                                    if($detalle['cto_uni_ejecutora']=='01'){
                                        $num_op_uni_uno++;
                                        $num_op=$num_op_uni_uno;
                                        
                                    }elseif($detalle['cto_uni_ejecutora']=='02'){
                                        $num_op_uni_dos++;
                                        $num_op=$num_op_uni_dos;
                                    }
                                    $cuentas = $this->buscarConceptosCuentasPorUnidad($conceptos_nomina,$detalle['cto_uni_ejecutora']);
                                    $datos_concepto = $this->buscarConcepto($cuentas,$detalle['aci_cno_codigo']);
                                    $temporales = $this->registrarTablasTmp($detalle,$num_op,$datos_concepto);
                                    if(!$temporales){
                                        break;
                                    }
                                    //exit;
    
                            }
                                                       
                                if($temporales){
                                    
                                        $actualizaciones = $this->actualizarCampos();
                                        //exit;
                                        if($actualizaciones==1){
                                                $tablas_finales = $this->cargaTablasFinales();
                                        }
                                        if($tablas_finales){
                                                if($detalle['cto_uni_ejecutora']=='01'){
                                                    $num_op_uni_uno++;

                                                }elseif($detalle['cto_uni_ejecutora']=='02'){
                                                    $num_op_uni_dos++;
                                                }
                                                $valido=1;
                                        }else{
                                                $valido=0;
                                        }
                                    }else{
                                        $valido=0;
                                        echo "error al intentar cargar tablas temporales";
                                        break;
                                    }
                    }
                    if(!$valido){
                        echo "<br>Error al verificar una nomina";
                        $this->hacerRollback();
                        break;
                    }
                    
                }
                
                //                                $id_detalle=$detalle['dtn_id'];
    //                                $existe_solicitud = $this->consultarDatosSolicitudGiro("",$id_nomina,$id_detalle);
    //                                if(!$existe_solicitud[0][0]){
    //                                        $insertado = $this->insertarSolicitudGiro($id_solicitud,$id_nomina,$id_detalle);
    //                                        if($insertado){
    //                                                //VARIABLES PARA EL LOG
    //                                                $registro[0] = "INSERTAR";
    //                                                $registro[1] = $id_solicitud;
    //                                                $registro[2] = "SOLICITUD_GIRO_ORDENADOR";
    //                                                $registro[3] = $id_solicitud;
    //                                                $registro[4] = time();
    //                                                $registro[5] = "Insertar solicitud de aprobacion de giro ". $id_solicitud;
    //                                                $registro[5] .= " - id_nomina =". $id_nomina;
    //                                                $registro[5] .= " - id_detalle =". $id_detalle;
    //                                                $registro[5] .= " - vigencia =". $vigencia;
    //                                                $this->log_us->log_usuario($registro,$this->configuracion);
    //                                                $aprobados++;
    //
    //                                        }   
    //                                
    //                                        $mensaje .= "Solicitud No. ".$id_solicitud." registrada exitosamente - Nomina No. ".$id_nomina;
    //
    //                                }else{
    //                                    $mensaje .=  "Ya se encuentra registrada la solicitud de giro para el detalle ".$id_detalle." de la nomina ".$id_nomina;
    //                                }
              
        }

        $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
        $variable="pagina=nom_adminSolicitudAprobarGiroOrdenador";
        $variable.="&opcion=revisarNominasGeneradas";

        $variable=$this->cripto->codificar_url($variable,$this->configuracion);
        $this->retornar($pagina,$variable,$mensaje);
 
        
    }
    
    
 /**
  * Función para registrar en la bd la solicitud de aprobacion de giro
  * @param type $id_solicitud
  * @param type $id_nomina
  * @param type $id_detalle
  * @return type 
  */
    function insertarSolicitudGiro($id_solicitud, $id_nomina, $id_detalle){
            $datos = array('id_solicitud'=>$id_solicitud,
                                'id_nomina'=>$id_nomina,
                                'id_detalle'=>$id_detalle,
                                'fecha'=>date('Y-m-d'),
                                'estado'=>'SOLICITADO',
                                );
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_nomina,"insertar_solicitud_giro",$datos);
             //echo "<br>cadena ".$cadena_sql;
            $this->ejecutarSQL($this->configuracion, $this->acceso_nomina, $cadena_sql, "");
            return $this->totalAfectados($this->configuracion, $this->acceso_nomina);
    }    
    
    
    /**
     * Funcion para consultar el ultimo numero de solicitud de giro
     * @return type 
     */
    function consultarUltimoNumeroSolicitudGiro(){

            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_nomina,"ultimo_numero_solicitud_giro","");
            $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_nomina, $cadena_sql, "busqueda");
            //echo "<br>cadena ".$cadena_sql;exit;
            if(is_array($datos)){
                return $datos[0][0];
            }else{
                return 0;
            }
               
    }

     /**
        * Funcion para consultar en la BD los detalles de una nomina
        * @param type $id_nomina
        * @return type 
        */
    function consultarDetallesNomina($id_nomina){

        $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_nomina,"detalle_nomina",$id_nomina);
        //echo "<br>cadena detalle ".$cadena_sql;exit;
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
     * Funcion para consultar los datos de una solicitud de giro
     * @param int $id_solicitud
     * @param int $id_nomina
     * @param int $id_detalle
     * @return <array> 
     */
    function consultarDatosSolicitudGiro($id_solicitud,$id_nomina,$id_detalle){
        $datos = array('id_solicitud'=>$id_solicitud,
                        'id_nomina'=>$id_nomina,
                        'id_detalle'=>$id_detalle
            );
        $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_nomina,"datos_solicitud_giro",$datos);
        $resultado = $this->ejecutarSQL($this->configuracion, $this->acceso_nomina, $cadena_sql, "busqueda");
        return $resultado;

    }
    
    
    /**
     * Funcion para organizar los datos que se van a registrar en la tabla temporal de ordenes de pago
     * @param <array> $detalle
     * @param int $num_op
     * @param <array> $datos_concepto
     * @return int
     */
    function registrarTmpOrdenPago($detalle,$num_op,$datos_concepto){
        $tipo_id=$detalle['cto_con_tipo_id'];
        $identificacion=$detalle['cto_con_num_id'];
        $codigo_tercero = $this->validarTercero($tipo_id,$identificacion);
        $insertado=0;
        //echo "<br>cod tercero ".$codigo_tercero;
        if($codigo_tercero>0){
                $datos_orden['vigencia']=$detalle['nom_anio'];
                $datos_orden['codigo_entidad']=230;
                $datos_orden['unidad_ejecutora']=$detalle['cto_uni_ejecutora'];
                $datos_orden['tipo_documento_obligacion']='OP';
                $datos_orden['consecutivo_op']=$num_op;
                $datos_orden['numero_convenio']='';
                $datos_orden['num_documento_soporte']=$detalle['dtn_cum_id'];
                $datos_orden['concepto_tesoreria']=$detalle['aci_cno_codigo'];
                $datos_orden['codigo_interno_tercero']=$codigo_tercero;
                $datos_orden['codigo_contable_neto']=$datos_concepto['cno_cuenta_cxpagar_credito'];
                $datos_orden['codigo_contable_bruto']=$datos_concepto['cno_cuenta_gasto_debito'];
                $datos_orden['forma_pago']='A';
                $datos_orden['cuenta_dueno_obligacion']=$detalle['cta_num'];;
                $datos_orden['codigo_interno_banco']=$detalle['ban_codigo_sic'];
                $datos_orden['tipo_cta_deposito']=$detalle['cta_tipo'];
                $datos_orden['tipo_regimen']=$detalle['dtn_regimen_comun'];
                $datos_orden['tipo_id']=$detalle['cto_con_tipo_id'];
                $datos_orden['num_id']=$detalle['cto_con_num_id'];
                $datos_orden['detalle_obligacion']='Pago nomina '.$detalle['nom_anio'].' mes '.$detalle['nom_mes'].' - cumplido '.$detalle['cum_anio'].' mes '.$detalle['cum_mes'];
                $datos_orden['valor_bruto']=$detalle['dtn_valor_liq_antes_iva'];
                $datos_orden['valor_neto']=$detalle['dtn_neto_aplicar_sic'];
                $datos_orden['codigo_contable_op_debito']=$datos_concepto['cno_cuenta_orden_debito'];
                $datos_orden['codigo_contable_op_credito']=$datos_concepto['cno_cuenta_orden_credito'];
                $datos_orden['vigencia_presupuestal']=$detalle['dtn_cum_cto_vigencia'];

                $insertado = $this->insertarTmpOrdenPago($datos_orden);
        }else{
            echo "<br>Tercero no valido, tipo de identificación ".$tipo_id." - ".$identificacion;
        }
        return $insertado;
    }
    
    /**
     * Funcion para organizar los datos que se van a registrar en la tabla temporal de imputacion
     * @param <array> $detalle
     * @param int $num_op
     * @param <array> $datos_concepto 
     */
    function registrarTmpImputacion($detalle,$num_op,$datos_concepto){
        //var_dump($detalle);exit;
        $valor_pagado = $this->valorPagado($detalle['num_rp'],$detalle['num_cdp'],$num_op, $detalle['cto_uni_ejecutora'],$detalle['nom_anio'],$detalle['nom_rubro_interno']);
        $valor_reintegro = $this->valorReintegro($detalle['num_rp'], $detalle['num_cdp'], $detalle['cto_uni_ejecutora'], $detalle['nom_anio'], $detalle['nom_rubro_interno']);
        $valor_anulaciones = $this->valorAnulaciones($detalle['num_rp'], $detalle['num_cdp'], $detalle['cto_uni_ejecutora'], $detalle['nom_anio'], $detalle['nom_rubro_interno']);
        $total_pagado = $valor_pagado + $valor_reintegro  - $valor_anulaciones;
        $valor_rp = $this->valorRP($detalle['num_rp'], $detalle['num_cdp'], $detalle['cto_uni_ejecutora'], $detalle['nom_anio'], $detalle['nom_rubro_interno']);
        $disponible= $valor_rp-($total_pagado+$detalle['dtn_valor_liq_antes_iva']);
        $insertado=0;
        
        if($disponible>0){
                $datos_imputacion['vigencia']=$detalle['nom_anio'];
                $datos_imputacion['codigo_entidad']=230;
                $datos_imputacion['unidad_ejecutora']=$detalle['cto_uni_ejecutora'];
                $datos_imputacion['tipo_documento_obligacion']='OP';
                $datos_imputacion['consecutivo_op']=$num_op;
                $codigo =  $this->consultarRubro($detalle['nom_anio'], $detalle['nom_rubro_interno']);
                $datos_imputacion['codigo_rubro']= $codigo[0]['CODIGO_RUBRO'];
                $datos_imputacion['codigo_rubro_interno']=$detalle['nom_rubro_interno'];
                $datos_imputacion['num_cdp']=$detalle['num_cdp'];
                $datos_imputacion['num_rp']=$detalle['num_rp'];
                $datos_imputacion['tipo_compromiso']=$detalle['tipo_compromiso'];
                $datos_imputacion['num_compromiso']=$detalle['cto_num'];
                $datos_imputacion['valor_bruto']=$detalle['dtn_valor_liq_antes_iva'];
                $datos_imputacion['vigencia_presupuestal']=$detalle['dtn_cum_cto_vigencia'];

                $insertado = $this->insertarTmpImputacion($datos_imputacion);
        }else{
            echo "Atencion : El total pagado supera el total del registro presupuestal. Por eso no puede efectuar el cargue con la OP N.  ".$num_op." de la vigencia ".$detalle['nom_anio']." Del CDP N.".$detalle['num_cdp']." con RP N. ".$detalle['num_rp']." Por el valor de: ".$detalle['dtn_valor_liq_antes_iva'];
        }
        return $insertado;
        
    }
    
    
    /**
     * Funcion para organizar los datos que se van a registrar en la tabla temporal de descuentos
     * @param <array> $detalle
     * @param int $num_op
     * @param <array> $datos_concepto 
     */
    function registrarTmpDescuentos($detalle,$num_op,$datos_concepto){
        //verificamos que descuentos tiene para insertarlos
        $descuentos = $this->verificarDescuentos($detalle,$datos_concepto);
        foreach ($descuentos as $key => $descuento) {
                $insertado=0;
                $datos_descuentos['vigencia']=$detalle['nom_anio'];
                $datos_descuentos['codigo_entidad']=230;
                $datos_descuentos['unidad_ejecutora']=$detalle['cto_uni_ejecutora'];
                $datos_descuentos['tipo_documento_obligacion']='OP';
                $datos_descuentos['consecutivo_op']=$num_op;
                $datos_descuentos['codigo_rubro_interno']=$detalle['nom_rubro_interno'];
                $datos_descuentos['num_cdp']=$detalle['num_cdp'];
                $datos_descuentos['num_rp']=$detalle['num_rp'];
                $datos_descuentos['num_compromiso']=$detalle['cto_num'];
                $datos_descuentos['codigo_interno_descuento']=$descuento['codigo_interno_descuento'];
                $datos_descuentos['valor_base_retencion']=$descuento['valor_base'];
                $datos_descuentos['valor_descuento']=$descuento['valor'];
                $codigo =  $this->consultarRubro($detalle['nom_anio'], $detalle['nom_rubro_interno']);
                $datos_descuentos['codigo_rubro']= $codigo[0]['CODIGO_RUBRO'];
                $datos_descuentos['codigo_cuenta_descuento']=$descuento['codigo_cuenta_descuento'];
                $datos_descuentos['vigencia_presupuestal']=$detalle['dtn_cum_cto_vigencia'];
                
                $insertado = $this->insertarTmpDescuentos($datos_descuentos);
                if(!$insertado){
                    break;
                }
        }
        return $insertado;
        
    }
    
    /**
     * Funcion para obtener el numero de orden de pago
     * @param type $unidad_ejec
     * @return type 
     */
    function obtenerNumeroOrdenPago($unidad_ejec){
        $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_sic,"nuevo_numero_orden_pago",$unidad_ejec);
        //echo "<br>cadena ".$cadena_sql;exit;
        $resultado = $this->ejecutarSQL($this->configuracion, $this->acceso_sic, $cadena_sql, "busqueda");
        return $resultado[0][0];

    }
    
    /**
     * Funcion para insertar en la tabla temporal de orden de pago
     * @param type $datos
     * @return type 
     */
    function insertarTmpOrdenPago($datos){
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_sic,"insertar_tmp_orden_pago",$datos);
            // echo "<br>cadena ".$cadena_sql;
            $this->ejecutarSQL($this->configuracion, $this->acceso_sic, $cadena_sql, "");
            return $this->totalAfectados($this->configuracion, $this->acceso_sic);
    } 
    
    /**
     * Funcion para insertar en la tabla temporal de imputacion
     * @param <array> $datos
     * @return type 
     */
    function insertarTmpImputacion($datos){
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_sic,"insertar_tmp_imputacion",$datos);
            // echo "<br>cadena ".$cadena_sql;
            $this->ejecutarSQL($this->configuracion, $this->acceso_sic, $cadena_sql, "");
            return $this->totalAfectados($this->configuracion, $this->acceso_sic);
    } 

    /**
     * Funcion para insertar en la tabla temporal de descuentos
     * @param <array> $datos
     * @return type 
     */
    function insertarTmpDescuentos($datos){
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_sic,"insertar_tmp_descuentos",$datos);
            // echo "<br>cadena ".$cadena_sql;
            $this->ejecutarSQL($this->configuracion, $this->acceso_sic, $cadena_sql, "");
            return $this->totalAfectados($this->configuracion, $this->acceso_sic);
    } 
    
    /**
     * Funcion para registar las ordendes en las tablas temporales
     * @param <array> $detalle
     * @param int $num_op
     * @param <array> $datos_concepto
     * @return int 
     */
    function registrarTablasTmp($detalle,$num_op,$datos_concepto){
        //ejecutar insercion para archivo tmp de op
        $res_archivo_tmp_op = $this->registrarTmpOrdenPago($detalle,$num_op,$datos_concepto);
        
        if($res_archivo_tmp_op){
            //ejecutar insercion para archivo tmp de imputacion
            $res_archivo_tmp_op = $this->registrarTmpImputacion($detalle,$num_op,$datos_concepto);
            if($res_archivo_tmp_op){
                //ejecutar insercion para archivo tmp de decuentos
                $res_archivo_tmp_op = $this->registrarTmpDescuentos($detalle,$num_op,$datos_concepto);
            }else{
                break;
            }
            
        }
        if($res_archivo_tmp_op && $res_archivo_tmp_op && $res_archivo_tmp_op){
            return $res_archivo_tmp_op;
        }else{
            return 0;
        }
                                
    }
    
    
    /**
     * Consulta los conceptos que se encuentran registrados de nomina
     * @return type 
     */
    function consultarConceptosCuentasNomina(){
        $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_nomina,"conceptos_cuentas_nomina",'');
        $resultado = $this->ejecutarSQL($this->configuracion, $this->acceso_nomina, $cadena_sql, "busqueda");
        return $resultado;
        
    }
    
    
    /**
     * Funcion para buscar los concepto de las cuentas por unidad
     * @param <array> $conceptos_nomina
     * @param string $cod_uni_ejecutora
     * @return <array> 
     */
    function buscarConceptosCuentasPorUnidad($conceptos_nomina,$cod_uni_ejecutora){
        $concepto='';
        if(is_array($conceptos_nomina) && $cod_uni_ejecutora){
            $indice=0;
            foreach ($conceptos_nomina as $concepto_nomina) {
                if($concepto_nomina['cno_cod_unidad_ejec']==$cod_uni_ejecutora){
                    $concepto[$indice]['cno_codigo']=$concepto_nomina['cno_codigo'];
                    $concepto[$indice]['cno_nombre']=$concepto_nomina['cno_nombre'];
                    $concepto[$indice]['cno_tipo_nomina']=$concepto_nomina['cno_tipo_nomina'];
                    $concepto[$indice]['cno_cuenta_gasto_debito']=$concepto_nomina['cno_cuenta_gasto_debito'];
                    $concepto[$indice]['cno_cuenta_retefuente_credito']=$concepto_nomina['cno_cuenta_retefuente_credito'];
                    $concepto[$indice]['cno_cuenta_orden_debito']=$concepto_nomina['cno_cuenta_orden_debito'];
                    $concepto[$indice]['cno_cuenta_orden_credito']=$concepto_nomina['cno_cuenta_orden_credito'];
                    $concepto[$indice]['cno_cuenta_cxpagar_credito']=$concepto_nomina['cno_cuenta_cxpagar_credito'];
                    $concepto[$indice]['cno_cod_unidad_ejec']=$concepto_nomina['cno_cod_unidad_ejec'];
                    $concepto[$indice]['cno_tipo_convenio']=$concepto_nomina['cno_tipo_convenio'];
                    $indice++;
                }
            }
        }
        return $concepto;
        
    }
    
    /**
     * Funcion para buscar un concepto dentro del arreglo de las cuentas
     * @param <array> $cuentas
     * @param string $cod_concepto
     * @return <array> 
     */
    function buscarConcepto($cuentas,$cod_concepto){
        //var_dump($cuentas);exit;
        $datos_concepto='';
        if(is_array($cuentas)){
            foreach ($cuentas as $cuenta) {
                if($cuenta['cno_codigo']==$cod_concepto){
                    $datos_concepto['cno_codigo']=$cuenta['cno_codigo'];
                    $datos_concepto['cno_nombre']=$cuenta['cno_nombre'];
                    $datos_concepto['cno_tipo_nomina']=$cuenta['cno_tipo_nomina'];
                    $datos_concepto['cno_cuenta_gasto_debito']=$cuenta['cno_cuenta_gasto_debito'];
                    $datos_concepto['cno_cuenta_retefuente_credito']=$cuenta['cno_cuenta_retefuente_credito'];
                    $datos_concepto['cno_cuenta_orden_debito']=$cuenta['cno_cuenta_orden_debito'];
                    $datos_concepto['cno_cuenta_orden_credito']=$cuenta['cno_cuenta_orden_credito'];
                    $datos_concepto['cno_cuenta_cxpagar_credito']=$cuenta['cno_cuenta_cxpagar_credito'];
                    $datos_concepto['cno_cod_unidad_ejec']=$cuenta['cno_cod_unidad_ejec'];
                    $datos_concepto['cno_tipo_convenio']=$cuenta['cno_tipo_convenio'];
                    break;
                }
            }
        }
        return $datos_concepto;
    }
    
    /**
     * Funcion para asignar los datos del contrato a cada detalle
     * @param type $detalles
     * @return type 
     */
    function asignarDatosContratoDetalle($detalles){
        //var_dump($detalles);
        if(is_array($detalles)){
            foreach ($detalles as $key => $detalle) {
                $vigencia = $detalle['dtn_cum_cto_vigencia'];
                $unidad = $detalle['cto_uni_ejecutora'];
                $interno_oc = $detalle['cto_interno_co'];
                $contrato = $this->consultarDatosContrato($interno_oc,$vigencia);
                $disponibilidad = $this->consultarDatosDisponibilidad($contrato[0]['INTERNO_MC'],$unidad,$vigencia);
                $num_cdp = $disponibilidad[0]['NUMERO_DISPONIBILIDAD']; 
                $registroPresupuestal = $this->consultarDatosRegistroPresupuestal($num_cdp,$unidad,$vigencia);
                $num_rp = $registroPresupuestal[0]['NUMERO_REGISTRO'];
                $tipo_contrato = $this->consultarTipoContrato($vigencia,$unidad,$num_rp);
                if($num_cdp && $num_rp && $tipo_contrato[0]['TIPO_COMPROMISO']){
                    $detalles[$key]['num_cdp']=$num_cdp;
                    $detalles[$key]['num_rp']=$num_rp;
                    $detalles[$key]['tipo_compromiso']=$tipo_contrato[0]['TIPO_COMPROMISO'];
                }
            }
        }
        
        return $detalles;
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
            //echo "<br>cadena ".$cadena_sql;
            $datos_contrato = $this->ejecutarSQL($this->configuracion, $this->acceso_sic, $cadena_sql, "busqueda");
            return $datos_contrato;
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
            //echo "<br>consulta reg ".$cadena_sql;
            return $datos_disponibilidad = $this->ejecutarSQL($this->configuracion, $this->acceso_sic, $cadena_sql, "busqueda");
    
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
     * Funcion para revisar los descuentos que se le realizan y armar un arreglo con los datos para retornarlo
     * @param type $detalle
     * @param type $datos_concepto
     * @return type 
     */
    function verificarDescuentos($detalle,$datos_concepto){
            include_once($this->configuracion["raiz_documento"] . $this->configuracion["bloques"]."/nomina/contratistas/nom_admin_solicitud_aprobar_giro_ordenador". $this->configuracion["clases"] . "/liquidacionNomina.class.php");
            $this->Liquidacion = new liquidacionNomina($this->configuracion);
            $cuentas_descuentos = $this->Liquidacion->consultarCuentasDescuentos();
            //var_dump($detalle);exit;
            //var_dump($datos_concepto);exit;
            //estampillas
            $descuento[0]['cod_descuento'] =4;
            $descuento[0]['descuento'] ="dtn_estampilla_ud";
            $descuento[0]['valor_base'] = $detalle['dtn_base_ica_estampillas'];
            $descuento[0]['valor'] = $detalle['dtn_estampilla_ud'];
            $descuento[0]['codigo_interno_descuento'] = '';
            $descuento[0]['codigo_cuenta_descuento'] = $this->Liquidacion->buscarCuentasDescuentos($cuentas_descuentos, $descuento[0]['cod_descuento'],'credito');


            $descuento[1]['cod_descuento'] =5;
            $descuento[1]['descuento'] ="dtn_estampilla_procultura";
            $descuento[1]['valor_base'] = $detalle['dtn_base_ica_estampillas'];
            $descuento[1]['valor'] = $detalle['dtn_estampilla_procultura'];
            $descuento[1]['codigo_interno_descuento'] = '';
            $descuento[1]['codigo_cuenta_descuento'] = $this->Liquidacion->buscarCuentasDescuentos($cuentas_descuentos, $descuento[1]['cod_descuento'],'credito');

            $descuento[2]['cod_descuento'] =6;
            $descuento[2]['descuento'] ="dtn_estampilla_proadultomayor";
            $descuento[2]['valor_base'] = $detalle['dtn_base_ica_estampillas'];
            $descuento[2]['valor'] = $detalle['dtn_estampilla_proadultomayor'];
            $descuento[2]['codigo_interno_descuento'] = '';
            $descuento[2]['codigo_cuenta_descuento'] = $this->Liquidacion->buscarCuentasDescuentos($cuentas_descuentos, $descuento[2]['cod_descuento'],'credito');
            
            //utilizado para seguir construyendo el arreglo de descuentos
            $indice = 3;
            
            //retefuente
             if($detalle['dtn_valor_retefuente_renta']>0){
                $descuento[$indice]['descuento'] ="dtn_valor_retefuente_renta";
                $descuento[$indice]['valor_base'] = $detalle['dtn_base_retefuente_renta'];
                $descuento[$indice]['valor'] = $detalle['dtn_valor_retefuente_renta'];
                $descuento[$indice]['codigo_interno_descuento'] = '';
                $descuento[$indice]['codigo_cuenta_descuento'] =$datos_concepto['cno_cuenta_retefuente_credito'];
                $indice++;
                }
            
             
            //reteiva
            if($detalle['dtn_valor_reteiva']>0){
                $descuento[$indice]['cod_descuento'] =9;
                $descuento[$indice]['descuento'] ="dtn_valor_reteiva";
                $descuento[$indice]['valor_base'] = $detalle['dtn_valor_liq_antes_iva'];
                $descuento[$indice]['valor'] = $detalle['dtn_valor_reteiva'];
                $descuento[$indice]['codigo_interno_descuento'] = '';
                $descuento[$indice]['codigo_cuenta_descuento'] = $this->Liquidacion->buscarCuentasDescuentos($cuentas_descuentos, $descuento[$indice]['cod_descuento'],'credito');
                $indice++;
            }
             
            //reteica
            if($detalle['dtn_valor_ica']>0){
                $descuento[$indice]['cod_descuento'] =10;
                $descuento[$indice]['descuento'] ="dtn_valor_ica";
                $descuento[$indice]['valor_base'] = $detalle['dtn_base_ica_estampillas'];
                $descuento[$indice]['valor'] = $detalle['dtn_valor_ica'];
                $descuento[$indice]['codigo_interno_descuento'] = '';
                $descuento[$indice]['codigo_cuenta_descuento'] = $this->Liquidacion->buscarCuentasDescuentos($cuentas_descuentos, $descuento[$indice]['cod_descuento'],'credito');
                $indice++;
            }
            
           return $descuento;
            
    }
   
    /**
     * Funcion que retorna el codigo interno del tercero
     * @param type $tipo_id
     * @param type $identificacion
     * @return int 
     */
    function validarTercero($tipo_id,$identificacion){
        $tercero = $this->consultarTercero($tipo_id, $identificacion);
        if($tercero[0]['ID']){
            return $tercero[0]['ID'];
        }else{
            return 0;
        }
    }
    
    /**
     * Funcion para consultar los datos del tercero 
     * @param type $tipo_id
     * @param type $identificacion
     * @return type 
     */
    function consultarTercero($tipo_id,$identificacion){
        //busca si existen registro de datos de usuarios en la base de datos 
            $datos = array('tipo_id'=>$tipo_id,
                            'identificacion'=>$identificacion);
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_sic,"datos_tercero",$datos);
            //echo "<br>cadena ".$cadena_sql;
            return $tercero = $this->ejecutarSQL($this->configuracion, $this->acceso_sic, $cadena_sql, "busqueda");
			
    }
    
    /**
     * Función que consulta el valor que ha sido pagado del contrato hasta el momento
     * @param int $num_rp
     * @param int $num_cdp
     * @param int $consecutivo_op
     * @param string $unidad_ejec
     * @param int $vigencia
     * @param int $rubro_interno
     * @return double 
     */
    function valorPagado($num_rp,$num_cdp,$consecutivo_op, $unidad_ejec,$vigencia,$rubro_interno){
        
            $datos = array( 'num_rp'=>$num_rp,
                            'num_cdp'=>$num_cdp,
                            'consecutivo_op'=>$consecutivo_op,
                            'unidad_ejec'=>$unidad_ejec,
                            'vigencia'=>$vigencia,
                            'codigo_compania'=>230,
                            'rubro_interno'=>$rubro_interno);
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_sic,"valor_pagado",$datos);
            //echo "<br>cadena ".$cadena_sql;
            $valor = $this->ejecutarSQL($this->configuracion, $this->acceso_sic, $cadena_sql, "busqueda");
            return $valor[0][0];
    }
    
    /**
     * Consulta el valor reintegrado de un contrato
     * @param int $num_rp
     * @param int $num_cdp
     * @param string $unidad_ejec
     * @param int $vigencia
     * @param int $rubro_interno
     * @return double 
     */
    function valorReintegro($num_rp,$num_cdp, $unidad_ejec,$vigencia,$rubro_interno){
        
            $datos = array( 'num_rp'=>$num_rp,
                            'num_cdp'=>$num_cdp,
                            'unidad_ejec'=>$unidad_ejec,
                            'vigencia'=>$vigencia,
                            'rubro_interno'=>$rubro_interno);
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_sic,"valor_reintegro",$datos);
            //echo "<br>cadena ".$cadena_sql;
            $valor = $this->ejecutarSQL($this->configuracion, $this->acceso_sic, $cadena_sql, "busqueda");
            return $valor[0][0];
    }
    
    /**
     * Función que consulta el valor de los registros presupuestales que se han anulado
     * @param int $num_rp
     * @param string $unidad_ejec
     * @param int $vigencia
     * @param int $rubro_interno
     * @return double 
     */
    function valorRPAnulados($num_rp,$unidad_ejec,$vigencia,$rubro_interno){
        
            $datos = array( 'num_rp'=>$num_rp,
                            'unidad_ejec'=>$unidad_ejec,
                            'vigencia'=>$vigencia,
                            'codigo_compania'=>230,
                            'rubro_interno'=>$rubro_interno);
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_sic,"valor_rp_anulados",$datos);
            //echo "<br>cadena ".$cadena_sql;
            $valor = $this->ejecutarSQL($this->configuracion, $this->acceso_sic, $cadena_sql, "busqueda");
            return $valor[0][0];
    }
    
    /**
     * Funcion que consulta el valor de las anulaciones parciales de los registros presupuestales
     * @param int $num_rp
     * @param string $unidad_ejec
     * @param int $vigencia
     * @param int $rubro_interno
     * @return double 
     */ 
    function valorRPAnuladosParciales($num_rp,$unidad_ejec,$vigencia,$rubro_interno){
        
            $datos = array( 'num_rp'=>$num_rp,
                            'unidad_ejec'=>$unidad_ejec,
                            'vigencia'=>$vigencia,
                            'codigo_compania'=>230,
                            'rubro_interno'=>$rubro_interno);
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_sic,"valor_rp_anulados_parciales",$datos);
            //echo "<br>cadena ".$cadena_sql;
            $valor = $this->ejecutarSQL($this->configuracion, $this->acceso_sic, $cadena_sql, "busqueda");
            return $valor[0][0];
    }
    
    /**
     * Función para retornar el valor de las anulaciones de un contrato
     * @param int $num_rp
     * @param string $unidad_ejec
     * @param int $vigencia
     * @param int $rubro_interno
     * @return double 
     */
    function valorAnulaciones($num_rp,$unidad_ejec,$vigencia,$rubro_interno){
        $valor_anulado = $this->valorRPAnulados($num_rp, $unidad_ejec, $vigencia, $rubro_interno);
        $valor_anulados_parciales = $this->valorRPAnuladosParciales($num_rp, $unidad_ejec, $vigencia, $rubro_interno);
        $anulaciones =  $valor_anulado+$valor_anulados_parciales;
        return $anulaciones;
    }
    
    /**
     * Función para consultar el valor de un registro presupuestal
     * @param int $num_rp
     * @param int $num_cdp
     * @param string $unidad_ejec
     * @param int $vigencia
     * @param int $rubro_interno
     * @return double 
     */
    function valorRP($num_rp,$num_cdp, $unidad_ejec,$vigencia,$rubro_interno){
        
            $datos = array( 'num_rp'=>$num_rp,
                            'num_cdp'=>$num_cdp,
                            'unidad_ejec'=>$unidad_ejec,
                            'vigencia'=>$vigencia,
                            'codigo_compania'=>230,
                            'rubro_interno'=>$rubro_interno);
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_sic,"valor_rp",$datos);
           // echo "<br>cadena RP ".$cadena_sql;
            $valor = $this->ejecutarSQL($this->configuracion, $this->acceso_sic, $cadena_sql, "busqueda");
            return $valor[0][0];
    }
    
    /**
     * Funcion para consultar datos de rubro
     * @param int $vigencia
     * @param int $rubro_interno
     * @return <array> 
     */
    function consultarRubro($vigencia,$rubro_interno){
            $datos = array( 'vigencia'=>$vigencia,
                            'rubro_interno'=>$rubro_interno);
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_sic,"codigo_rubro",$datos);
            //echo "<br>cadena ".$cadena_sql;
            $valor = $this->ejecutarSQL($this->configuracion, $this->acceso_sic, $cadena_sql, "busqueda");
            return $valor;
        
    }
    
    
    /**
     * Funcion que actualiza el numero de documento en la tabla de imputaciones
     * @return type 
     */
    function actualizarNumeroDocumentoImputacion(){
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_sic,"actualizar_numero_documento_imputacion",'');
            //echo "<br>cadena ".$cadena_sql;//exit;
            $this->ejecutarSQL($this->configuracion, $this->acceso_sic, $cadena_sql, "");
            return $this->totalAfectados($this->configuracion, $this->acceso_sic);
        
    }
    
    /**
     * Función que actualiza el tipo de documento en la tabla de imputaciones
     * @return type 
     */
    function actualizarTipoDocumentoImputacion(){
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_sic,"actualizar_tipo_documento_imputacion",'');
            //echo "<br>cadena ".$cadena_sql;//exit;
            $this->ejecutarSQL($this->configuracion, $this->acceso_sic, $cadena_sql, "");
            return $this->totalAfectados($this->configuracion, $this->acceso_sic);
        
    }
    
    /**
     * Función que consulta la cantidad de registros de descuentos
     * @return type 
     */
    function cantidadRegistrosDescuentos(){
             $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_sic,"cantidad_registros_descuentos",'');
            //echo "<br>cadena ".$cadena_sql;
            $valor = $this->ejecutarSQL($this->configuracion, $this->acceso_sic, $cadena_sql, "busqueda");
            return $valor[0][0];
    }
    
    
    /**
     * Función que actualiza el número de documento en la tabla de descuento
     * @return type 
     */
    function actualizarNumeroDocumentoDescuento(){
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_sic,"actualizar_numero_documento_descuento",'');
            //echo "<br>cadena ".$cadena_sql;//exit;
            $this->ejecutarSQL($this->configuracion, $this->acceso_sic, $cadena_sql, "");
            return $this->totalAfectados($this->configuracion, $this->acceso_sic);
        
    }
    
    /**
     * Función que actualiza el código interno con los que se identifican los descuentos
     * @return type 
     */
    function actualizarCodigoInternoDescuentos(){
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_sic,"actualizar_codigo_interno_descuentos",'');
            //echo "<br>cadena ".$cadena_sql;//exit;
            $this->ejecutarSQL($this->configuracion, $this->acceso_sic, $cadena_sql, "");
            return $this->totalAfectados($this->configuracion, $this->acceso_sic);
        
    }
    
    /**
     * Función que inserta en documento pago información de la tabla temporal de orden pago 
     * @return type 
     */
    function insertarDocumentoPagoDeOdenPagoTmp(){
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_sic,"insertar_documento_pago_de_oden_pago_tmp",'');
            echo "<br>cadena ".$cadena_sql;//exit;
            $this->ejecutarSQL($this->configuracion, $this->acceso_sic, $cadena_sql, "");
            return $this->totalAfectados($this->configuracion, $this->acceso_sic);
        
    }

    /**
     * Función que inserta la orden de pago con informacion de la temporal de orden pago
     * @return type 
     */
    function insertarOrdenPagoDeTmp(){
        
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_sic,"insertar_orden_pago_de_tmp",'');
            //echo "<br>cadena ".$cadena_sql;//exit;
            $this->ejecutarSQL($this->configuracion, $this->acceso_sic, $cadena_sql, "");
            return $this->totalAfectados($this->configuracion, $this->acceso_sic);
        
    }
    
    /**
     * Función que inserta en la tabla imputacion desde la tabla temporal de imputacion
     * @return type 
     */
    function insertarImputacionDeTmp(){
        
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_sic,"insertar_imputacion_de_tmp",'');
            //echo "<br>cadena ".$cadena_sql;//exit;
            $this->ejecutarSQL($this->configuracion, $this->acceso_sic, $cadena_sql, "");
            return $this->totalAfectados($this->configuracion, $this->acceso_sic);
        
    }
    
    /**
     * Función que inserta en la tabla de ogt registro presupuestal
     * @return type 
     */
    function insertarOgtRegistroPresupuestal(){
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_sic,"insertar_ogt_registro_presupuestal",'');
            echo "<br>cadena ".$cadena_sql;//exit;
            $this->ejecutarSQL($this->configuracion, $this->acceso_sic, $cadena_sql, "");
            return $this->totalAfectados($this->configuracion, $this->acceso_sic);
        
    }
    
    /**
     * Función para insertar informacion exogena
     * @return type 
     */
    function insertarInformacionExogena(){
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_sic,"insertar_informacion_exogena",'');
            echo "<br>cadena ".$cadena_sql;//exit;
            $this->ejecutarSQL($this->configuracion, $this->acceso_sic, $cadena_sql, "");
            return $this->totalAfectados($this->configuracion, $this->acceso_sic);
        
    }
    
    /**
     *Función para insertar los detalles de descuento a partir de los datos de la tabla temporal de detalle descuentos
     * @return type 
     */
    function insertarDetalleDescuentoDeTmp(){
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_sic,"insertar_detalle_descuento_de_tmp",'');
            //echo "<br>cadena ".$cadena_sql;//exit;
            $this->ejecutarSQL($this->configuracion, $this->acceso_sic, $cadena_sql, "");
            return $this->totalAfectados($this->configuracion, $this->acceso_sic);
        
    }
    
    /**
     * Funcion que consulta si existen cuentas de orden
     * @return type 
     */
    function existeCuentasOrden(){
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_sic,"existe_cuentas_orden",'');
            //echo "<br>cadena ".$cadena_sql;
            $valor = $this->ejecutarSQL($this->configuracion, $this->acceso_sic, $cadena_sql, "busqueda");
            return $valor[0][0];
    }
    
    /**
     * Función que inserta las cuentas de orden 2
     * @return type 
     */
    function insertar_cuentas_orden_pago2(){
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_sic,"insertar_cuentas_orden_pago2",'');
            echo "<br>cadena ".$cadena_sql;//exit;
            $this->ejecutarSQL($this->configuracion, $this->acceso_sic, $cadena_sql, "");
            return $this->totalAfectados($this->configuracion, $this->acceso_sic);
        
    }
    
    /**
     * Función que inserta las cuentas de orden pago 1
     * @return type 
     */
    function insertar_cuentas_orden_pago1(){
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_sic,"insertar_cuentas_orden_pago1",'');
            echo "<br>cadena ".$cadena_sql;//exit;
            $this->ejecutarSQL($this->configuracion, $this->acceso_sic, $cadena_sql, "");
            return $this->totalAfectados($this->configuracion, $this->acceso_sic);
        
    }
    
   /**
    * Función que inserta las cuentas de orden para el codigo contable que guarda el valor bruto
    * @return type 
    */
    function insertarCuentasOrdenCodigoContableBruto(){
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_sic,"insertar_cuentas_orden_codigo_contable_bruto",'');
            echo "<br>cadena ".$cadena_sql;//exit;
            $this->ejecutarSQL($this->configuracion, $this->acceso_sic, $cadena_sql, "");
            return $this->totalAfectados($this->configuracion, $this->acceso_sic);
        
   }
    
   /**
    * Función que inserta las cuentas de orden para el codigo contable que guarda el neto
    * @return type 
    */
   function insertarCuentasOrdenCodigoContableNeto(){
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_sic,"insertar_cuentas_orden_codigo_contable_neto",'');
            echo "<br>cadena ".$cadena_sql;//exit;
            $this->ejecutarSQL($this->configuracion, $this->acceso_sic, $cadena_sql, "");
            return $this->totalAfectados($this->configuracion, $this->acceso_sic);
        
   }
    
   /**
    * Función que inserta los conceptos de orden de pago
    * @return type 
    */
   function insertarConceptosOrdenPago(){
       
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_sic,"insertar_conceptos_orden_pago",'');
            echo "<br>cadena ".$cadena_sql;//exit;
            $this->ejecutarSQL($this->configuracion, $this->acceso_sic, $cadena_sql, "");
            return $this->totalAfectados($this->configuracion, $this->acceso_sic);
        
   }
   
   /**
    * Funcion que realiza el cargue a las tablas finales
    * @return int 
    */
   function cargaTablasFinales(){
            $tablas_cargadas=0;
            $insertado_documento_pago = $this->insertarDocumentoPagoDeOdenPagoTmp();
            if(!$insertado_documento_pago ){
                echo "Ya existe una orden de pago a cargar. Estructura ogt_documento_pago.";
                $tablas_cargadas=0;
            }else{
                    
                    $insertado_orden_pago = $this->insertarOrdenPagoDeTmp();
                    if(!$insertado_orden_pago ){
                        echo "Ya existe una orden de pago a cargar. Estructura ogt_orden_pago.";
                        break;
                        
                    }else{
                            $insertado_imputacion = $this->insertarImputacionDeTmp();
                            if(!$insertado_imputacion ){
                                echo "Ya existe una orden de pago a cargar. Estructura ogt_imputacion.";
                                break;
                            }else{
                                    $insertado_ogt_registro_presupuestal = $this->insertarOgtRegistroPresupuestal();
                                    if(!$insertado_ogt_registro_presupuestal ){
                                        echo "Ya existe una orden de pago a cargar. Estructura ogt_registro_presupuestal.";
                                        break;
                                    }else{
                                            $insertado_informacion_exogena = $this->insertarInformacionExogena();
                                            if(!$insertado_informacion_exogena ){
                                                echo "Ya existe una orden de pago a cargar. Estructura ogt_informacion_exogena.";
                                                break;
                                            }else{
                                                    $cantidad_descuentos = $this->cantidadRegistrosDescuentos();
                                                    if ($cantidad_descuentos>0){
                                                        $insertado_detalle_descuento = $this->insertarDetalleDescuentoDeTmp();
                                                        if(!$insertado_detalle_descuento ){
                                                            echo "Ya existe una orden de pago a cargar. Estructura ogt_detalle_descuento.";
                                                            $tablas_cargadas=0;
                                                            break;
                                                        }
                                                    }
                                                    $existe_cuentas_orden = $this->existeCuentasOrden();
                                                    if($existe_cuentas_orden==0){
                                                        $insertado_cuentas_orden = $this->insertarCuentasOrden();
                                                        if($insertado_cuentas_orden){
                                                             $tablas_cargadas=1;
                                                        }
                                                    }
                                                   
                                            }
                                    }
                            }
                    }
            }
            return $tablas_cargadas;
   }
   
   /**
    * Función para realizar la insercion de las cuentas de orden
    * @return int 
    */
   function insertarCuentasOrden(){
            $tablas_cargadas=0;
            $insertado_cuentas_orden_pago2 = $this->insertar_cuentas_orden_pago2();
            if(!$insertado_cuentas_orden_pago2 ){
                    echo "Ya existe una orden de pago a cargar. Estructura ogt_cuentas_orden_pago.";
                    $tablas_cargadas=0;
            }else{
                    $insertado_cuentas_orden_pago1 = $this->insertar_cuentas_orden_pago1();
                    if(!$insertado_cuentas_orden_pago1 ){
                        echo "Ya existe una orden de pago a cargar. Estructura ogt_cuentas_orden_pago.";
                        $tablas_cargadas=0;
                    }else{
                            $insertado_cuentas_orden_codigo_contable_bruto = $this->insertarCuentasOrdenCodigoContableBruto();
                            if(!$insertado_cuentas_orden_codigo_contable_bruto ){
                                echo "Ya existe una orden de pago a cargar. Estructura ogt_cuentas_orden_pago.";
                                $tablas_cargadas=0;
                            }else{
                                    $insertado_cuentas_orden_codigo_contable_neto = $this->insertarCuentasOrdenCodigoContableNeto();
                                    if(!$insertado_cuentas_orden_codigo_contable_neto ){
                                        echo "Ya existe una orden de pago a cargar. Estructura ogt_cuentas_orden_pago.";
                                        $tablas_cargadas=0;
                                    }else{
                                            $insertado_conceptos_orden_pago = $this->insertarConceptosOrdenPago();
                                            if(!$insertado_conceptos_orden_pago){
                                                echo "Ya existe una orden de pago a cargar. Estructura ogt_conceptos_orden_pago.";
                                                $tablas_cargadas=0;
                                            }else{
                                                $tablas_cargadas=1;
                                            }
                                    }

                                    
                            }

                            
                    }

                    
            }
            
            return $tablas_cargadas;
            
   }
   
   /**
    * Función para actualizar los campos faltantes de las tablas temporales
    * @return int 
    */
   function actualizarCampos(){
            $numero_documento_imputacion = $this->actualizarNumeroDocumentoImputacion();
            $tipo_documento = $this->actualizarTipoDocumentoImputacion();
            
            $cantidad_descuentos = $this->cantidadRegistrosDescuentos();
            if ($cantidad_descuentos>0){
                $numero_documento_descuento = $this->actualizarNumeroDocumentoDescuento();
                $codigo_interno_descuento = $this->actualizarCodigoInternoDescuentos();
                
            }
            if($numero_documento_imputacion && $tipo_documento){
                return 1;
            }else{
                return 0;
            }
            
   }
   
   /**
    * Función para realizar la limpieza de las tablas temporales 
    */
   function vaciarTablasTemporales(){
       $this->vaciarOrdenPagoTmp();
       $this->vaciarImputacionTmp();
       $this->vaciarDetalleDescuentoTmp();
   }
   
   /**
    * Funcion para vaciar la tabla temporal de ordenes de pago
    * @return type 
    */
   function vaciarOrdenPagoTmp(){
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_sic,"vaciar_orden_pago_tmp",'');
           // echo "<br>cadena ".$cadena_sql;//exit;
            $this->ejecutarSQL($this->configuracion, $this->acceso_sic, $cadena_sql, "");
            return $this->totalAfectados($this->configuracion, $this->acceso_sic);
        
    }
   
    /**
     * Función para vaciar la tabla temporal de imputacion
     * @return type 
     */
    function vaciarImputacionTmp(){
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_sic,"vaciar_imputacion_tmp",'');
            //echo "<br>cadena ".$cadena_sql;//exit;
            $this->ejecutarSQL($this->configuracion, $this->acceso_sic, $cadena_sql, "");
            return $this->totalAfectados($this->configuracion, $this->acceso_sic);
        
    }
   
    /**
     * Función para vaciar la tabla temporal de detalle de descuentos
     * @return type 
     */
    function vaciarDetalleDescuentoTmp(){
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_sic,"vaciar_detalle_descuento_tmp",'');
            //echo "<br>cadena ".$cadena_sql;//exit;
            $this->ejecutarSQL($this->configuracion, $this->acceso_sic, $cadena_sql, "");
            return $this->totalAfectados($this->configuracion, $this->acceso_sic);
        
    }
    
    /**
     * Función que hace los llamados a los metodos que sirven para eliminar todos los registro 
     * insertados anteriormentecuando, al momento de algun error 
     */
    function hacerRollback(){
            $ordenes_pago_tmp = $this->consultarTmpOrdenPago();
        
            $this->rollbackDocumentoPago($ordenes_pago_tmp);
            //$this->rollbackOrdenPago($ordenes_pago_tmp);
            $imputaciones_tmp = $this->consultarImputacionTmp();
            $informacion_exogena_tmp = $this->consultarInformacionExogenaDeTmp();
            //var_dump($informacion_exogena_tmp);exit;
            $informacion_detalle_descuentos_tmp = $this->consultarDetalleDescuentosDeTmp();
            $this->rollbackDetalleDescuento($informacion_detalle_descuentos_tmp);
            //$this->rollbackInformacionExogena($informacion_exogena_tmp);
            //$this->rollbackOgtRegistroPresupuestal($imputaciones_tmp);
            //$this->rollbackImputacion($imputaciones_tmp);
            $cuentas_orden_pago2 = $this->consultarCuentasOrdenPago2Tmp();
            $this->rollbackCuentasOrdenPago2($cuentas_orden_pago2);
    }
  
    /**
     * Función para realizar el borrado de la tabla documento de pago
     * @param type $ordenes_pago_tmp 
     */
    function rollbackDocumentoPago($ordenes_pago_tmp){
        //var_dump($ordenes_pago_tmp);
        if(is_array($ordenes_pago_tmp)){
            foreach ($ordenes_pago_tmp as $key => $orden) {
                $datos = array('VIGENCIA' => $orden['VIGENCIA'],
                'ENTIDAD' => $orden['ENTIDAD'],
                'UNIDAD_EJECUTORA' => $orden['UNIDAD_EJECUTORA'],
                'TIPO_DOCUMENTO' => $orden['TIPO_DOCUMENTO'],
                'CONSECUTIVO' => $orden['CONSECUTIVO']);
                $this->eliminarDocumentoPago($datos);
            }
        }
        
    }
    
    /**
     * Función para eliminar documento de pago
     * @param type $datos
     * @return type 
     */
    function eliminarDocumentoPago($datos){
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_sic,"eliminar_documento_pago",$datos);
            echo "<br>cadena ".$cadena_sql;//exit;
            $this->ejecutarSQL($this->configuracion, $this->acceso_sic, $cadena_sql, "");
            return $this->totalAfectados($this->configuracion, $this->acceso_sic);
        
    }
 
    /**
     * Función para consultar las ordenes de pago que estan en la tabla temporal
     * @return type 
     */
    function consultarTmpOrdenPago(){
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_sic,"orden_pago_tmp",'');
            echo "<br>cadena ".$cadena_sql;
            $valor = $this->ejecutarSQL($this->configuracion, $this->acceso_sic, $cadena_sql, "busqueda");
            return $valor;
    }

    
    /**
     * Función para realizar el borrado de la tabla de ordenes de pago 
     */
    function rollbackOrdenPago($ordenes_pago_tmp){
        //var_dump($ordenes_pago_tmp);
        if(is_array($ordenes_pago_tmp)){
            foreach ($ordenes_pago_tmp as $orden) {
                $datos = array('VIGENCIA'=>$orden['VIGENCIA'],
                'ENTIDAD'=>$orden['ENTIDAD'],
                'UNIDAD_EJECUTORA'=>$orden['UNIDAD_EJECUTORA'],
                'TIPO_DOCUMENTO'=>$orden['TIPO_DOCUMENTO'],
                'CONSECUTIVO'=>$orden['CONSECUTIVO'],
                'TER_ID'=>$orden['TER_ID'],
                'DETALLE'=>$orden['DETALLE'],
                'FORMA_PAGO'=>$orden['FORMA_PAGO'],
                'NUMERO_CUENTA'=>$orden['NUMERO_CUENTA'],
                'BANCO'=>$orden['BANCO'],
                'CLASE'=>$orden['CLASE']);
                $this->eliminarOrdenPago($datos);
            }
        }
        
    }
    
    /**
     * Funcion para eliminar orden de pago
     * @param type $datos
     * @return type 
     */
    function eliminarOrdenPago($datos){
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_sic,"eliminar_orden_pago",$datos);
            echo "<br>cadena OP ".$cadena_sql;//exit;
            $this->ejecutarSQL($this->configuracion, $this->acceso_sic, $cadena_sql, "");
            return $this->totalAfectados($this->configuracion, $this->acceso_sic);
        
    }
    
    /**
     * Funcion para realizar la verificación uy borrado de imputaciones
     * @param type $imputaciones 
     */
    function rollbackImputacion($imputaciones){
        var_dump($imputaciones);
        if(is_array($imputaciones)){
            foreach ($imputaciones as $imputacion) {
                $datos = array('VIGENCIA'=>$imputacion['VIGENCIA'],
                'ENTIDAD'=>$imputacion['ENTIDAD'],
                'UNIDAD_EJECUTORA'=>$imputacion['UNIDAD_EJECUTORA'],
                'TIPO_DOCUMENTO'=>$imputacion['TIPO_DOCUMENTO'],
                'CONSECUTIVO'=>$imputacion['CONSECUTIVO'],
                'RUBRO_INTERNO'=>$imputacion['RUBRO_INTERNO'],
                'DISPONIBILIDAD'=>$imputacion['DISPONIBILIDAD'],
                'VALOR_BRUTO'=>$imputacion['VALOR_BRUTO'],
                'ANO_PAC'=>$imputacion['ANO_PAC'],
                'MES_PAC'=>$imputacion['MES_PAC'],
                'REGISTRO'=>$imputacion['REGISTRO']
                );
                $this->eliminarImputacion($datos);
            }
        }
        
    }
    
    /**
     * Función para eliminar imputacion
     * @param type $datos
     * @return type 
     */
    function eliminarImputacion($datos){
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_sic,"eliminar_imputacion",$datos);
            echo "<br>cadena ".$cadena_sql;//exit;
            $this->ejecutarSQL($this->configuracion, $this->acceso_sic, $cadena_sql, "");
            return $this->totalAfectados($this->configuracion, $this->acceso_sic);
        
    }
    
    /**
     * Función para consultar las imputaciones de la table temporal
     * @return type 
     */
    function consultarImputacionTmp(){
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_sic,"imputacion_tmp",'');
            echo "<br>cadena ".$cadena_sql;
            $valor = $this->ejecutarSQL($this->configuracion, $this->acceso_sic, $cadena_sql, "busqueda");
            return $valor;
    }
    
     
    /**
     * Función para realizar la verificacion y borrado de los registros de ogt registro presupuestal
     * @param type $imputaciones 
     */
    function rollbackOgtRegistroPresupuestal($imputaciones){
        var_dump($imputaciones);
        if(is_array($imputaciones)){
            foreach ($imputaciones as $imputacion) {
                $datos = array('VIGENCIA'=> $imputacion['VIGENCIA'],
                            'ENTIDAD'=> $imputacion['ENTIDAD'],
                            'UNIDAD_EJECUTORA'=> $imputacion['UNIDAD_EJECUTORA'],
                            'TIPO_DOCUMENTO'=> $imputacion['TIPO_DOCUMENTO'],
                            'CONSECUTIVO'=> $imputacion['CONSECUTIVO'],
                            'RUBRO_INTERNO'=> $imputacion['RUBRO_INTERNO'],
                            'DISPONIBILIDAD'=> $imputacion['DISPONIBILIDAD'],
                            'REGISTRO'=> $imputacion['REGISTRO'],
                            'VALOR_REGISTRO'=> $imputacion['VALOR_BRUTO']
                );
                $this->eliminarOgtRegistroPresupuestal($datos);
            }
        }
        
    }
    
    /**
     * Función para eliminar de la tabla ogt registro presupuestal
     * @param type $datos
     * @return type 
     */
    function eliminarOgtRegistroPresupuestal($datos){
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_sic,"eliminar_ogt_registro_presupuestal",$datos);
            echo "<br>cadena ogt ".$cadena_sql;//exit;
            $this->ejecutarSQL($this->configuracion, $this->acceso_sic, $cadena_sql, "");
            return $this->totalAfectados($this->configuracion, $this->acceso_sic);
        
    }
    
    /**
     * Función para realizar la verificación y borrado de la informacion exogena
     * @param type $datos_tmp 
     */
    function rollbackInformacionExogena($datos_tmp){
        var_dump($datos_tmp);
        if(is_array($datos_tmp)){
            foreach ($datos_tmp as $dato) {
                $datos = array('VIGENCIA'=>$dato['VIGENCIA'],
                                'ENTIDAD'=>$dato['ENTIDAD'],
                                'UNIDAD_EJECUTORA'=>$dato['UNIDAD_EJECUTORA'],
                                'TIPO_DOCUMENTO'=>$dato['TIPO_DOCUMENTO'],
                                'CONSECUTIVO'=>$dato['CONSECUTIVO'],
                                'TIPO_DOCUMENTO_IE'=>$dato['TIPO_DOCUMENTO_IE'],
                                'NUMERO_DOCUMENTO'=>$dato['NUMERO_DOCUMENTO'],
                                'RUBRO_INTERNO'=>$dato['RUBRO_INTERNO'],
                                'DISPONIBILIDAD'=>$dato['DISPONIBILIDAD'],
                                'REGISTRO'=>$dato['REGISTRO'],
                                'TER_ID'=>$dato['TER_ID'],
                                'FORMA_PAGO'=>$dato['FORMA_PAGO'],
                                'NUMERO_CUENTA'=>$dato['NUMERO_CUENTA'],
                                'BANCO'=>$dato['BANCO'],
                                'CLASE'=>$dato['CLASE'],
                                'VALOR_BRUTO'=>$dato['VALOR_BRUTO']

                );
                $this->eliminarInformacionExogena($datos);
            }
        }
        
    }
    
    /**
     * Función para eliminar informacion exogena
     * @param type $datos
     * @return type 
     */
    function eliminarInformacionExogena($datos){
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_sic,"eliminar_informacion_exogena",$datos);
            echo "<br>cadena ogt ".$cadena_sql;//exit;
            $this->ejecutarSQL($this->configuracion, $this->acceso_sic, $cadena_sql, "");
            return $this->totalAfectados($this->configuracion, $this->acceso_sic);
        
    }
    
    
    /**
     * Función para consultar la informacion exogena que esta en las temporales
     * @return type 
     */
    function consultarInformacionExogenaDeTmp(){
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_sic,"exogena_de_tmp",'');
            echo "<br>cadena ".$cadena_sql;
            $valor = $this->ejecutarSQL($this->configuracion, $this->acceso_sic, $cadena_sql, "busqueda");
            return $valor;
    }
    
    /**
     * Función para realizar la verificación y borrado de los detalles de descuento
     * @param type $datos_tmp 
     */
    function rollbackDetalleDescuento($datos_tmp){
        var_dump($datos_tmp);
        if(is_array($datos_tmp)){
            foreach ($datos_tmp as $dato) {
                $datos = array('VIGENCIA'=>$dato['VIGENCIA'],
                                'ENTIDAD'=>$dato['ENTIDAD'],
                                'UNIDAD_EJECUTORA'=>$dato['UNIDAD_EJECUTORA'],
                                'TIPO_DOCUMENTO'=>$dato['TIPO_DOCUMENTO'],
                                'CONSECUTIVO'=>$dato['CONSECUTIVO'],
                                'RUBRO_INTERNO'=>$dato['RUBRO_INTERNO'],
                                'DISPONIBILIDAD'=>$dato['DISPONIBILIDAD'],
                                'REGISTRO'=>$dato['REGISTRO'],
                                'NUMERO_DOCUMENTO'=>$dato['NUMERO_DOCUMENTO'],
                                'CODIGO_INTERNO'=>$dato['CODIGO_INTERNO'],
                                'VALOR_BASE_RETENCION'=>$dato['VALOR_BASE_RETENCION'],
                                'VALOR_DESCUENTO'=>$dato['VALOR_DESCUENTO']  );
                $this->eliminarDetalleDescuento($datos);
            }
        }
        
    }
    
    /**
     * Función para eliminar detalle de descuentos
     * @param type $datos
     * @return type 
     */
    function eliminarDetalleDescuento($datos){
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_sic,"eliminar_detalle_descuento",$datos);
            echo "<br>cadena ogt ".$cadena_sql;//exit;
            $this->ejecutarSQL($this->configuracion, $this->acceso_sic, $cadena_sql, "");
            return $this->totalAfectados($this->configuracion, $this->acceso_sic);
        
    }
    
    
    /**
     * Función para consultar los detalles de descuentos de la tabla temporal
     * @return type 
     */
    function consultarDetalleDescuentosDeTmp(){
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_sic,"detalle_descuento_tmp",'');
            echo "<br>cadena ".$cadena_sql;
            $valor = $this->ejecutarSQL($this->configuracion, $this->acceso_sic, $cadena_sql, "busqueda");
            return $valor;
        
    }
    
    /**
     * Función para realizar la verificación y borrado de las cuentas de orden pago 2
     * @param type $datos_cuentas_orden_pago2 
     */
    function rollbackCuentasOrdenPago2($datos_cuentas_orden_pago2){
        var_dump($datos_cuentas_orden_pago2);
        if(is_array($datos_cuentas_orden_pago2)){
            foreach ($datos_cuentas_orden_pago2 as $dato) {
                $datos = array('CONSECUTIVO'=>$dato['CONSECUTIVO'],
                                'ENTIDAD'=>$dato['ENTIDAD'],
                                'TIPO_DOCUMENTO'=>$dato['TIPO_DOCUMENTO'],
                                'UNIDAD_EJECUTORA'=>$dato['UNIDAD_EJECUTORA'],
                                'VIGENCIA'=>$dato['VIGENCIA'],
                                'CUENTA_CONTABLE'=>$dato['CUENTA_CONTABLE'],
                                'NATURALEZA'=>$dato['NATURALEZA'],
                                'CONSECUTIVO_CUENTA'=>$dato['CONSECUTIVO_CUENTA'],
                                'VALOR_BRUTO'=>$dato['VALOR_BRUTO'],
                                'ES_DESCUENTO'=>$dato['ES_DESCUENTO']);
                $this->eliminarDetalleDescuento($datos);
            }
        }
        
    }
    
    /**
     * Función para eliminar las cuentas de orden pago 2
     * @param type $datos
     * @return type 
     */
    function eliminarCuentasOrdenPago2($datos){
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_sic,"eliminar_cuentas_orden_pago2",$datos);
            echo "<br>cadena ogt ".$cadena_sql;//exit;
            $this->ejecutarSQL($this->configuracion, $this->acceso_sic, $cadena_sql, "");
            return $this->totalAfectados($this->configuracion, $this->acceso_sic);
        
    }
    
    
    /**
     * Funcion para consultar las cuentas de orden pago 2 que estan en las temporales
     * @return type 
     */
    function consultarCuentasOrdenPago2Tmp(){
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_sic,"cuentas_orden_pago2_tmp",'');
            echo "<br>cadena ".$cadena_sql;
            $valor = $this->ejecutarSQL($this->configuracion, $this->acceso_sic, $cadena_sql, "busqueda");
            return $valor;
        
    }
    
    
} // fin de la clase
	

?>


                
                