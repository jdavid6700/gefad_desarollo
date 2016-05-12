<?
/*
############################################################################
#    UNIVERSIDAD DISTRITAL Francisco Jose de Caldas                        #
#    Copyright: Vea el archivo LICENCIA.txt que viene con la distribucion  #
****************************************************************************/

/**************************************************************************
* @name          pagina.class.php 
* @author        Paulo Cesar Coronado
* @revision      Última revisión 3 de marzo de 2008
****************************************************************************
* @subpackage   
* @package	clase
* @copyright    
* @version      0.2
* @author      	Paulo Cesar Coronado
* @link		N/D
* @description  Esta clase esta disennada para manejar la presentacion de las 
*               diferentes paginas usadas en la aplicacion. Se encarga de 
*               administrar los bloques constitutivos de las paginas
*
******************************************************************************/
//======= Revisar si no hay acceso ilegal ==============
if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;		
}
//======================================================


class datosPagador
{
	
	//@Constructor
	function __construct()
	{
		
	
	}
	
	
	//@ Anno y Periodo
	function annoPer($configuracion,$acceso_db)
	{
				
		$this->cadenaSQL_datosGenerales($configuracion, "annPerActual");
		$this->registro=$this->acceso_db_datosGenerales($acceso_db,"busqueda");
		if(is_array($this->registro))
		{
			$resultado["anno"]=$this->registro[0][0];
			$resultado["periodo"]=$this->registro[0][1];
			return $resultado;
		}
		else
		{
			return false;
		
		}
	
	}
	
	//@Salario Minimo
	function salarioMinimo($configuracion,$acceso_db)
	{
		unset($this->registro);
		unset($this->cadena_sql);
		$this->cadenaSQL_datosGenerales($configuracion, "salarioMinimo");
		$this->registro=$this->acceso_db_datosGenerales($acceso_db,"busqueda");
		if(is_array($this->registro))
		{
			$resultado["salarioMinimo"]=$this->registro[0][0];
			return $resultado;
		}
		else
		{
			return false;
		
		}
	
	
	}
	
	//@Nivel Carrera
	function nivelCarrera($configuracion,$acceso_db,$valor)
	{
		unset($this->registro);
		unset($this->cadena_sql);
		$this->cadenaSQL_datosGenerales($configuracion, "nivelCarrera",$valor);
		$this->registro=$this->acceso_db_datosGenerales($acceso_db,"busqueda");
		if(is_array($this->registro))
		{
			$resultado["nivelCarrera"]=$this->registro[0][0];
			return $resultado;
		}
		else
		{
			return false;
		
		}
	
	
	}
	
	
	function cadenaSQL_datosGenerales($configuracion, $tipo="", $valor="")
	{
		switch($tipo)
		{
			case "annPerActual":
				$this->cadena_sql= "SELECT ";
				$this->cadena_sql.= "ape_ano, ";
				$this->cadena_sql.= "ape_per ";
				$this->cadena_sql.= "FROM ";
				$this->cadena_sql.= "acasperi ";
				$this->cadena_sql.= "WHERE ";
				$this->cadena_sql.= "ape_estado='A' ";
				break;
			case "salarioMinimo":
				
				$this->cadena_sql= "SELECT ";
				$this->cadena_sql.= "smi_valor ";
				$this->cadena_sql.= "FROM ";
				$this->cadena_sql.= "acsalmin ";
				$this->cadena_sql.= "WHERE ";
				$this->cadena_sql.= "smi_estado='A'";
				break;
				
			case "nivelCarrera":
				$this->cadena_sql= "SELECT ";
				$this->cadena_sql.= "tra_nivel ";
				$this->cadena_sql.= "FROM ";
				$this->cadena_sql.= "actipcra, ";
				$this->cadena_sql.= "ACCRA ";
				$this->cadena_sql.= "WHERE ";
				$this->cadena_sql.= "cra_cod = ".$valor." ";
				$this->cadena_sql.= "AND ";
				$this->cadena_sql.= "cra_tip_cra = tra_cod";
				break;
			
			
		}
		
		return $this->cadena_sql;
	}
	
	function acceso_db_datosGenerales($acceso_db,$tipo)
	{
		if($tipo=="busqueda")
		{
			$acceso_db->registro_db($this->cadena_sql,0);
			$this->registro=$acceso_db->obtener_registro_db();
			return $this->registro;
		}
		else
		{
			$resultado=$acceso_db->ejecutar_acceso_db($this->cadena_sql);
			return $resultado;
		}
		
		return false;
	}


}
?>
