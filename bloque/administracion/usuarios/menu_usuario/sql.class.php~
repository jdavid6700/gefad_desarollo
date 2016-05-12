<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;		
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sql.class.php");

class sql_menuactivo extends sql
{
	function cadena_sql($configuracion,$tipo,$variable="")
		{
			
			switch($tipo)
			{
				case "select":
					$this->cadena_sql="SELECT ";
					$this->cadena_sql.="`id_tipo`, ";
					$this->cadena_sql.="`valor` ";
					$this->cadena_sql.="FROM ";
					$this->cadena_sql.=$configuracion["prefijo"]."variable ";
					$this->cadena_sql.="WHERE ";
					$this->cadena_sql.="tipo='NOTICIA'";					
					break;
					
					
				default:
				break;
			
			}
			
			
		
			return $this->cadena_sql;
		
		}
}
?>
