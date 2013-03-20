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


class datosGenerales
{
	
	//@Constructor
	function __construct()
	{
		
	
	}
	
	function rescatarDatoGeneral($configuracion, $tipo, $variable, $acceso_db)
	{
		$this->cadenaSQL_datosGenerales($configuracion, $tipo, $variable);	
		$this->registro=$this->acceso_db_datosGenerales($acceso_db,"busqueda");
		if(is_array($this->registro))
		{
			return $this->registro[0][0];
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
			case "anno":
				$this->cadena_sql= "SELECT ";
				$this->cadena_sql.= "ape_ano ";
				$this->cadena_sql.= "FROM ";
				$this->cadena_sql.= "acasperi ";
				$this->cadena_sql.= "WHERE ";
				$this->cadena_sql.= "ape_estado='A' ";
				break;
				
			case "per":
				$this->cadena_sql= "SELECT ";
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
