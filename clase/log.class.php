<?
/*
############################################################################
#    UNIVERSIDAD DISTRITAL Francisco Jose de Caldas                        #
#    Desarrollo Por:                                                       #
#    Paulo Cesar Coronado
#    Jairo Lavado 		 2004 - 2008                                      #
#    paulo_cesar@berosa.com                                                #
#    Copyright: Vea el archivo EULA.txt que viene con la distribucion      #
############################################################################
*/
/***************************************************************************
  
log.class.php 

Paulo Cesar Coronado
Jairo Lavado
Copyright (C) 2001-2008

Última revisión 2 de Diciembre de 2008

*****************************************************************************
* @subpackage   
* @package	bloques
* @copyright    
* @version      0.1
* @author      	Jairo Lavado 
* @link		N/D
* @description  Script para guarda el historico de las acciones de los usuarios.
* @usage        Toda pagina tiene un id_pagina que es propagado por cualquier metodo GET, POST.
******************************************************************************/
class log
{
		
	function log_usuario($registro,$configuracion)
	{
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/dbms.class.php");
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sesion.class.php");
		
		$acceso_db=new dbms($configuracion);
		$enlace=$acceso_db->conectar_db();
		
			
			$this->nueva_sesion=new sesiones($configuracion);
			$this->nueva_sesion->especificar_enlace($enlace);
			$this->esta_sesion=$this->nueva_sesion->numero_sesion();
			//Rescatar el valor de la variable usuario de la sesion
			$usuario=$this->nueva_sesion->rescatar_valor_sesion($configuracion,"id_usuario");
			if($usuario)
			{
				
				$id_usuario=$usuario[0][0];
			}
			$cadena_sql="INSERT INTO ";
			$cadena_sql.=$configuracion["prefijo"]."log_usuario "; 
			$cadena_sql.="( "; 
			$cadena_sql.="`id_usuario`, "; 
			$cadena_sql.="`accion`, ";
			$cadena_sql.="`id_registro`, ";
			$cadena_sql.="`tipo_registro`, "; 
			$cadena_sql.="`nombre_registro`, ";
			$cadena_sql.="`fecha_log`, "; 
			$cadena_sql.="`descripcion` ";
			$cadena_sql.=") "; 
			$cadena_sql.="VALUES ";
			$cadena_sql.="( "; 
			$cadena_sql.="'".$id_usuario."', "; 
			$cadena_sql.="'".$registro[0]."', "; 
			$cadena_sql.="'".$registro[1]."', "; 
			$cadena_sql.="'".$registro[2]."', ";
			$cadena_sql.="'".$registro[3]."', ";
			$cadena_sql.="'".date("F j, Y, g:i a")."', ";
			$cadena_sql.="'".$registro[5]."' "; 
		   echo '<br>';     $cadena_sql.=")"; 
			//$cadena_sql;exit;
 
			$acceso_db->registro_db($cadena_sql,0);
			$resultado=$acceso_db->obtener_registro_db();
			
		unset($acceso_db);
		unset($this->nueva_sesion);
	}

}
?>
