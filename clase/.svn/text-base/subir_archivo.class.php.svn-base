<?
/*
############################################################################
#    UNIVERSIDAD DISTRITAL Francisco Jose de Caldas                        #
#    Desarrollo Por:                                                       #
#    Paulo Cesar Coronado 2004 - 2005                                      #
#    paulo_cesar@etb.net.co                                                #
#    Copyright: Vea el archivo EULA.txt que viene con la distribucion      #
############################################################################
*/
/***************************************************************************
  
institucional.inc.php 

Paulo Cesar Coronado
Universidad Distrital Francisco Jose de Caldas
Copyright (C) 2001-2006

Última revisión 6 de Marzo de 2006

****************************************************************************
* @subpackage   
* @package	clase
* @copyright    
* @version      0.2
* @author      	Paulo Cesar Coronado
* @link		N/D
* @description  Clase para gestionar la carga de archivos al servidor
* @usage
****************************************************************************/
?><?

class subir_archivo
{
		
		var $tipo_archivo;
		var $tamanno_maximo;
		var $directorio_carga;
		var $remotebasepath;
		var $especial;
		var $nombre_campo;
		var $tipos_permitidos;
		var $maxfiles;
		var $unico;
		var $permisos;
		
		function subir_archivo()
		{
		
			$this->especial = "[[:space:]]|[\"\*\\\'\%\$\&\@\<\>]";
			$this->tamanno_maximo = 2*1000*1000;
			$this->unico = FALSE;
			
			$this->semilla = 1;
			
			@set_time_limit (0);
			
		}
		
		
		function cargar()
		{
			
			global $_FILES,$HTTP_POST_FILES;
			
			// 1. Obtener todos los datos del archivo
			
			if($this->nombre_campo == "")
			{
				$this->log["resultado"][0] = "ERROR: Imposible determinar el nombre del campo.";
			}
			
			// Verificar la ruta del directorio de carga
			
			if($this->directorio_carga != "" AND !is_dir($this->directorio_carga) )
			{
				$this->log["resultado"][0] = "ERROR: Imposible determinar el directorio de carga.";
				return false;
			}
			
			$archivo = $_FILES;
			
			$this->nombre_archivo	= $archivo[$this->nombre_campo]["name"];
			$this->tipo_MIME	= $archivo[$this->nombre_campo]["type"];
			$this->tammano		= $archivo[$this->nombre_campo]["size"];
			$this->nombre_temporal	= $archivo[$this->nombre_campo]["tmp_name"];
			$this->error		= $archivo[$this->nombre_campo]["error"];
				
			if($this->tammano<=0)
			{
				$this->log["resultado"][0] = "ERROR: Error en la estructura del documento.";
				return false;
			} 
			elseif($this->tammano > $this->tamanno_maximo) 
			{
				$this->log["resultado"][0] = "ERROR: Documento demasiado grande.";

			} 
			else
			{
				$this->primer_paso(0);
			}
			
			return $this->log;
			
		
			
		}
		
		
		function primer_paso($contador)
		{
			
			if(!is_uploaded_file($this->nombre_temporal))
			{
				$this->log["resultado"][$contador] = "ERROR: El archivo no se ha cargado en el servidor.";
				return false;
			}
			$this->resolver_bug($this->nombre_campo);
			$this->revisar_nombre($this->nombre_archivo);
			$this->nombre_archivo = $this->revisar_caracteres($this->nombre_archivo);
			$this->tipo_archivo = $this->obtener_tipo($this->nombre_archivo);
			$this->revisar_tipo($this->tipo_archivo);			
			
			if(isset($this->log["resultado"][0]))
			{
				if(substr($this->log["resultado"][0],0,5) == "ERROR")
				{
					return false;
				}
			}
			
			// Nuevo
			if($this->unico === TRUE)
			{
				$this->mi_archivo = $this->nuevo_nombre();
			} 
			else 
			{
				$this->mi_archivo = $this->nombre_archivo;
			}
			
			
			// Copiar el archivo
			if(file_exists($this->directorio_carga . $this->mi_archivo))
			{
				$this->log["resultado"][$contador] = "ERROR: El archivo ya existe en el servidor.";

			}
			elseif (move_uploaded_file($this->nombre_temporal, $this->directorio_carga . $this->mi_archivo)) 
			{
				if($this->permisos != "")
				{
					chmod($this->directorio_carga . $this->mi_archivo,$this->permisos);
				}
			
   			 	$this->log["resultado"][$contador] = "OK";
   			 	$this->log["mi_archivo"][$contador] = $this->mi_archivo;
   			 	$this->log["nombre_archivo"][$contador] = $this->nombre_archivo;
   			 	return true;
   			} 
   			else 
   			{
   			 	$this->log["resultado"][$contador] = "ERROR: Imposible cargar el archivo al directorio.";
   			 	return false;
    			}
			
			
		}
		
		function nuevo_nombre($prefijo="")
		{
			/*
			$tiempo = date("hms");
		  	mt_srand($tiempo);
			
		  	$url = mt_rand().$this->semilla.".".$this->tipo_archivo;
   			while(file_exists($this->directorio_carga.$url))
            		{
                		$this->semilla++;
                		$tiempo = date("hms");
		  		mt_srand($tiempo);
   				$url = mt_rand().$this->semilla.".".$this->tipo_archivo;
   			}
   			*/
   			$url=time().".".$this->tipo_archivo;
   			return $url;
		}
		
		
		function resolver_bug($nombre_campo)
		{

			global $HTTP_POST_VARS,$HTTP_COOKIE_VARS,$HTTP_GET_VARS;

			if(isset($HTTP_COOKIE_VARS[$nombre_campo]) 
			|| isset($HTTP_POST_VARS  [$nombre_campo]) 
			|| isset($HTTP_GET_VARS   [$nombre_campo]) )
			{
			    	$this->log["resultado"][$contador] = "ERROR";
			    	return false;	
			}

		}
		
		
		function revisar_nombre($mi_archivo)
		{
			
			if($mi_archivo == "")
			{
				$this->log["resultado"][$contador] = "ERROR";
				return false;
			} 
			elseif(ereg("\.+.+\.+",$mi_archivo))
			{
				$this->log["resultado"][$contador] = "ERROR";
				return false;
			} 
			else 
			{
				return TRUE;
			}
		}
		
		
		function revisar_caracteres($mi_archivo)
		{
			
			if($this->especial != "")
			{
				$nuevo_archivo = eregi_replace($this->especial,"",$mi_archivo);
			} 
			else 
			{
				$nuevo_archivo = $mi_archivo;
			}
			$nuevo_archivo = strtolower($nuevo_archivo);
			return $nuevo_archivo;
		}
		
		
		function obtener_tipo($nombre_archivo)
		{
			
			$fileinfo = @pathinfo($nombre_archivo);
			
			if(is_array($fileinfo) AND $fileinfo["extension"] != "")
			{
				return $fileinfo["extension"];
			}
			else
			{
				$this->log["resultado"][$contador] = "ERROR";
				return false;
			}
		}
		
		
		function revisar_tipo($tipo_archivo)
		{
			
			if(is_array($this->tipos_permitidos))
			{
				if(in_array($tipo_archivo,$this->tipos_permitidos))
				{
					return TRUE;
				} 
				else 
				{
					$this->log["resultado"][$contador] = "ERROR";
					return false;
				}
			}
		}
		
		function eliminar_archivo($mi_archivo)
		{
			
			if(!unlink($this->directorio_carga.$mi_archivo))
			{
					return false;
			} 
			else 
			{
					return TRUE;
			}
		}
		
		
		
	}

	

?>
