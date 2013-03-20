<?
/*
############################################################################
#    UNIVERSIDAD DISTRITAL Francisco Jose de Caldas                        #
#    Desarrollo Por:                                                       #
#    Paulo Cesar Coronado 2004 - 2005                                      #
#    paulo_cesar@berosa.com                                                #
#    Copyright: Vea el archivo EULA.txt que viene con la distribucion      #
############################################################################
*/
/****************************************************************************
  
html.class.php 
 
Paulo Cesar Coronado
Copyright (C) 2001-2005

Última revisión 6 de Marzo de 2006

******************************************************************************
* @subpackage   
* @package	clase
* @copyright    
* @version      0.2
* @author      	Paulo Cesar Coronado
* @link		http://acreditacion.udistrital.edu.co
* @description  Clase para el manejo de elementos HTML (XML)
*******************************************************************************
*Atributos
*
*@access private
*@param  $conexion_id
*		Identificador del enlace a la base de datos 
*******************************************************************************
*/

/*****************************************************************************
*Métodos
*
*@access public
*
******************************************************************************
* @USAGE
* 
* 
* 
*/

class notiCondor
{
	
	function __construct()
	{
		
		
	}
	
	function titular($configuracion, $numero)
	{
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/dbms.class.php");
		$acceso_db=new dbms($configuracion);
		$enlace=$acceso_db->conectar_db();
		if (is_resource($enlace))
		{
			$cadena_sql="SELECT ";
			$cadena_sql.="id_noticia, ";
			$cadena_sql.="titular, ";
			$cadena_sql.="fecha ";
			$cadena_sql.="FROM ";
			$cadena_sql.=$configuracion["prefijo"]."noticia ";
			$cadena_sql.="ORDER BY ";
			$cadena_sql.="fecha ASC ";
			$cadena_sql.="LIMIT ".$numero;
			//echo $cadena_sql;
			$acceso_db->registro_db($cadena_sql,0);
			$registro=$acceso_db->obtener_registro_db();
			$total=$acceso_db->obtener_conteo_db();
			if($total<1)
			{
				return date("d/m/Y",time())."- Bienvenidos al Sistema de Informaci&oacute;n CONDOR";
			}
			else
			{
				return $registro;
				
			}
		}
		else
		{
			return date("d/m/Y",time())."- Bienvenidos al Sistema de Informaci&oacute;n CONDOR";
		}
		
	}
	
}//notiCondor
	
	?>
