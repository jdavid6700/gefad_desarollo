<?
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/
/*--------------------------------------------------------------------------------------------------------------------------
* @name          encriptar.class.php 
* @author        Paulo Cesar Coronado
* @revision      Última revisión 12 de enero de 2009
/*--------------------------------------------------------------------------------------------------------------------------
* @subpackage		admin_solicitud
* @package		bloques
* @copyright    	Universidad Distrital Francisco Jose de Caldas
* @version      		1.0.0.1
* @author			Paulo Cesar Coronado
* @author			Oficina Asesora de Sistemas
* @link			N/D
* @description  	 Esta clase esta disennada para cifrar y decifrar las variables que se pasan a las paginas
*		se recomienda que en cada distribucion el administrador del sistema use mecanismos de cifrado.
*		diferentes a los originales
*
/*--------------------------------------------------------------------------------------------------------------------------*/

class encriptar
{
	//Constructor
	function encriptar()
	{
	
	
	
	}
	
	
	
	function codificar_url($cadena,$configuracion)
	{
		$cadena=base64_encode($cadena);
		$cadena=strrev($cadena);
		$cadena=$configuracion["enlace"]."=".$cadena;
		return $cadena;
	
	}
	
	
	function decodificar_url($cadena,$configuracion)
	{
		$cadena=strrev($cadena);
		$cadena=base64_decode($cadena);
		parse_str($cadena,$matriz);
		
		reset($_REQUEST);
		foreach($_REQUEST as $clave => $valor) 
		{
			unset($_REQUEST[$clave]);
		} 
		
		
		foreach($matriz as $clave=>$valor)
		{
			$_REQUEST[$clave]=$valor;			
		}
		
		return TRUE;
	}
	
	function codificar($cadena,$configuracion)
	{
		$cadena=base64_encode($cadena);
		$cadena=strrev($cadena);
		return $cadena;
	
	}
	
	
	function decodificar($cadena)
	{
		$cadena=strrev($cadena);
		$cadena=base64_decode($cadena);
		
		return $cadena;
	
	
	}
	
}//Fin de la clase

?>
