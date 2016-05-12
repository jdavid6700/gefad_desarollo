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
          
        include_once($this->configuracion["raiz_documento"] . $this->configuracion["bloques"]."/nomina/contratistas/nom_admin_cumplido_supervisor". $this->configuracion["clases"] . "/liquidacionNomina.class.php");
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
     * Funcion para consultar datos de rubro
     * @param int $vigencia
     * @param int $cod_rubro
     * @return <array> 
     */
    function consultarRubro($vigencia,$cod_rubro){
            $datos = array('vigencia'=>$vigencia,
                            'cod_rubro'=>$cod_rubro);
           $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_sic,"rubro",$datos);
           $resultado = $this->ejecutarSQL($this->configuracion, $this->acceso_sic, $cadena_sql, "busqueda");
           return $resultado;

    }
    
          
     /**
     * revisa las nominas generadas y seleccionadas para realizar el correspondiente registro en la base de datos con la solicitud de giro
     */
    function revisarSolicitudAprobacionGiro(){
        //var_dump($_REQUEST);exit;
        $aprobados=0;
        $mensaje="";
        $total = (isset($_REQUEST['total_registros'])?$_REQUEST['total_registros']:0);
        if($total){
                $indice=0;
                    
                //buscamos y almacenamos en un arreglo los cumplidos seleccionados para la solicitud de pago
                for($i=0;$i<$total;$i++){
                    $modificado=0;
                    $nombre = "id_nomina_".$i;
                    $nombre_vigencia = "vigencia_".$i;
                    $_REQUEST[$nombre]=(isset($_REQUEST[$nombre])?$_REQUEST[$nombre]:'');
                    $_REQUEST[$nombre_vigencia]=(isset($_REQUEST[$nombre_vigencia])?$_REQUEST[$nombre_vigencia]:'');
                    
                    if($_REQUEST[$nombre]){
                            $id_solicitud = $this->consultarUltimoNumeroSolicitudGiro()+1;
                            $id_nomina=$_REQUEST[$nombre];
                            $vigencia= $_REQUEST[$nombre_vigencia];
                            $detalles = $this->consultarDetallesNomina($id_nomina);
                            //var_dump($detalles);
                            foreach ($detalles as $detalle) {
                                $id_detalle=$detalle['dtn_id'];
                                $existe_solicitud = $this->consultarDatosSolicitudGiro("",$id_nomina,$id_detalle);
                                if(!$existe_solicitud[0][0]){
                                        $insertado = $this->insertarSolicitudGiro($id_solicitud,$id_nomina,$id_detalle);
                                        if($insertado){
                                                //VARIABLES PARA EL LOG
                                                $registro[0] = "INSERTAR";
                                                $registro[1] = $id_solicitud;
                                                $registro[2] = "SOLICITUD_GIRO_ORDENADOR";
                                                $registro[3] = $id_solicitud;
                                                $registro[4] = time();
                                                $registro[5] = "Insertar solicitud de aprobacion de giro ". $id_solicitud;
                                                $registro[5] .= " - id_nomina =". $id_nomina;
                                                $registro[5] .= " - id_detalle =". $id_detalle;
                                                $registro[5] .= " - vigencia =". $vigencia;
                                                $this->log_us->log_usuario($registro,$this->configuracion);
                                                $aprobados++;

                                        }   
                                
                                        $mensaje .= "Solicitud No. ".$id_solicitud." registrada exitosamente - Nomina No. ".$id_nomina;

                                }else{
                                    $mensaje .=  "Ya se encuentra registrada la solicitud de giro para el detalle ".$id_detalle." de la nomina ".$id_nomina;
                                }
                            }
                    }
                    
                }
              
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
    
} // fin de la clase
	

?>


                
                