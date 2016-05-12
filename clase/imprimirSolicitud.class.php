<?
/*
############################################################################
#    UNIVERSIDAD DISTRITAL Francisco Jose de Caldas                        #
#    paulo_cesar@etb.net.co                                                #
#    Copyright: Vea el archivo EULA.txt que viene con la distribucion      #
############################################################################
*/
/***************************************************************************
  
index.php 

Oficina Asesora de Sistemas
Copyright (C) 2008

Última revisión 15 de julio de 2008

*****************************************************************************
* @subpackage   
* @package	bloques
* @copyright    
* @version      0.2
* @author      	
* @link		N/D
* @description  Formulario para el registro de un archivo de bloques
* @usage        
*******************************************************************************/ 
if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;		
}



class imprimirSolicitud
{
	function __construct()
	{
			
	}
	
	function rescatarDatoImpresionSolicitud($configuracion, $tipo, $variable, $acceso_db)
	{
		$cadena_sql=$this->cadenaSQL_imprimirSolicitud($configuracion, $tipo, $variable);	
		$this->registro=$this->acceso_db_imprimirSolicitud($cadena_sql,$acceso_db,"busqueda");
		if(is_array($this->registro))
		{
			return $this->registro;
		}
		else
		{
			return false;
		
		}
		
	
	}
	
	
	function impresionSolicitud($configuracion, $tipo, $variable, $acceso_db)
	{
		$cadena_sql=$this->cadenaSQL_imprimirSolicitud($configuracion, $tipo, $variable);	
		
		$resultado=$this->acceso_db_imprimirSolicitud($cadena_sql,$acceso_db,"");
		
		return $resultado;
		
	}
	
	
	
	
	function cadenaSQL_imprimirSolicitud($configuracion, $tipo, $variable)
	{
		$cadena_sql="";
		switch($tipo)
		{
			case "secuencia":
				$cadena_sql="SELECT ";
				$cadena_sql.="seq_matricula.NEXTVAL ";
				$cadena_sql.="FROM ";
				$cadena_sql.="dual ";
				break;
				
			case "actualizarSolicitud":
				$cadena_sql="UPDATE ";
				$cadena_sql.=$configuracion["prefijo"]."solicitudRecibo "; 
				$cadena_sql.="SET "; 
				$cadena_sql.="`estado`='1' ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="id_solicitud_recibo=".$variable." ";
				break;
				
			case "actualizaracestmat":
				$cadena_sql="UPDATE ";
				$cadena_sql.="ACESTMAT "; 
				$cadena_sql.="SET "; 
				$cadena_sql.="ema_estado='I' ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="ema_est_cod=".$variable["referencia1"]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="ema_ano=".$variable["anno"]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="ema_per=".$variable["periodo"]." ";
				break;
			
			case "insertarCuota":
				$cadena_sql="INSERT INTO ";
				$cadena_sql.="ACESTMAT ";
				$cadena_sql.="(";
				$cadena_sql.="ema_est_cod, ";
				$cadena_sql.="ema_cra_cod, ";
				$cadena_sql.="ema_valor, ";
				$cadena_sql.="ema_ext, ";
				$cadena_sql.="ema_ano, ";
				$cadena_sql.="ema_per, ";
				$cadena_sql.="ema_cuota, ";
				$cadena_sql.="ema_fecha, ";
				$cadena_sql.="ema_estado, ";
				$cadena_sql.="ema_secuencia ";	
				$cadena_sql.=") ";
				$cadena_sql.="VALUES (";
				$cadena_sql.=$variable["referencia1"].", ";
				$cadena_sql.=$variable["idCarrera"].", ";
				$cadena_sql.=$variable["matricula"].", ";
				$cadena_sql.=$variable["matriculaExtra"].", ";
				$cadena_sql.=$variable["anno"].", ";
				$cadena_sql.=$variable["periodo"].", ";
				$cadena_sql.=$variable["cuota"].", ";
				$cadena_sql.="SYSDATE, ";
				$cadena_sql.="'A', ";
				$cadena_sql.=$variable["secuencia"]." ";
				$cadena_sql.=")";
				//echo $cadena_sql;
				break;
		
			case "clausulaInsertarSecuencia":
				$cadena_sql="INSERT INTO ";
				$cadena_sql.="ACESTMAT ";
				$cadena_sql.="(";
				$cadena_sql.="ema_est_cod, ";
				$cadena_sql.="ema_cra_cod, ";
				$cadena_sql.="ema_valor, ";
				$cadena_sql.="ema_ext, ";
				$cadena_sql.="ema_ano, ";
				$cadena_sql.="ema_per, ";
				$cadena_sql.="ema_cuota, ";
				$cadena_sql.="ema_fecha, ";
				$cadena_sql.="ema_estado, ";
				$cadena_sql.="ema_secuencia, ";
				$cadena_sql.="ema_fecha_ord, ";
				$cadena_sql.="ema_fecha_ext ";				
				$cadena_sql.=") ";
				$cadena_sql.="VALUES (";
				$cadena_sql.=$variable["referencia1"].", ";
				$cadena_sql.=$variable["idCarrera"].", ";
				$cadena_sql.=$variable["matricula"].", ";
				$cadena_sql.=$variable["matriculaExtra"].", ";
				$cadena_sql.=$variable["anno"].", ";
				$cadena_sql.=$variable["periodo"].", ";
				$cadena_sql.=$variable["cuota"].", ";
				$cadena_sql.="SYSDATE, ";
				$cadena_sql.="'A', ";
				$cadena_sql.="seq_matricula.NEXTVAL,";
				$cadena_sql.=$variable["fechaOrdinaria"].", ";
				$cadena_sql.=$variable["fechaExtraordinaria"]." ";
				$cadena_sql.=")";
				break;
			
		
		}
		
		return $cadena_sql;
	
	
	
	}
	
	
	//Funcion para el acceso a las bases de datos
	
	function acceso_db_imprimirSolicitud($cadena_sql,$acceso_db,$tipo)
	{
		//echo $cadena_sql;
		if($tipo=="busqueda")
		{
			$acceso_db->registro_db($cadena_sql,0);
			$this->esteRegistro=$acceso_db->obtener_registro_db();
			return $this->esteRegistro;
		}
		else
		{
			$resultado=$acceso_db->ejecutar_acceso_db($cadena_sql);
			return $resultado;
		}
	}
}
		
?>
