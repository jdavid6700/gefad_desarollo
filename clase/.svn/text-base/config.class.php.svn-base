<?
/***************************************************************************
*    Copyright (c) 2004 - 2008 :                                           *
*    UNIVERSIDAD DISTRITAL Francisco Jose de Caldas                        *
****************************************************************************/
/***************************************************************************
     * @name          config.inc.php 
* @author        
* @revision      
****************************************************************************
* @subpackage   
* @package	configuracion
* @copyright    
* @version      2.0.0.2
* @author      	
* @link		
* @description  Administracion de Parametros globales de configuracion
*
****************************************************************************/

class config
{

	function config()
	{
	
	}

	function variable($ruta="")
	{
		include_once("encriptar.class.php");
		include_once("dbms.class.php");
		
		
		$this->cripto=new encriptar();
		
		$this->fp=fopen($ruta."configuracion/config.inc.php","r");
		if(!$this->fp)
		{
			return false;			
		}
		
		$this->i=0;
		while (!feof($this->fp)) 
		{
			$this->unaLinea=fgets($this->fp, 4096);
			$this->linea= $this->cripto->decodificar($this->unaLinea,"");
			$this->i++;
			switch($this->i)
			{
				case 3:
					$this->configuracion["db_sys"]= $this->linea;				
					break;
				
				case 4:
					$this->configuracion["db_dns"]= $this->linea;
					break;
				case 5:
					$this->configuracion["db_nombre"]= $this->linea;
					break;
				case 6:
					$this->configuracion["db_usuario"]= $this->linea;
					break;	
				case 7:
					$this->configuracion["db_clave"]= $this->linea;						
					break;
				case 8:
					$this->configuracion["prefijo"]= $this->linea;
					break;
					
			}			
		}


		/*foreach ($this->configuracion as $key => $value) 
			{
	
				echo $key."=>".$value."<br>";
	
			}

*/
		fclose ($this->fp);
		
		$this->base=new dbms($this->configuracion);
		$this->enlace=$this->base->conectar_db();
		
		
		
		
		//exit;
		if (is_resource($this->enlace))
		{		
			
			$cadena_sql="SELECT ";
			$cadena_sql.=" `parametro`,  ";
			$cadena_sql.=" `valor`  ";
			$cadena_sql.="FROM ";
			$cadena_sql.=$this->configuracion["prefijo"]."configuracion ";
			//echo $cadena_sql;
			
			$this->total=$this->base->registro_db($cadena_sql,0);			
			if($this->total>0)
			{
				$this->registro=$this->base->obtener_registro_db();
				for($j=0;$j<$this->total;$j++)
				{
					$this->configuracion[$this->registro[$j][0]]=$this->registro[$j][1];
					//echo $this->configuracion[$this->registro[$j][0]]."<br>";
				}
				$this->configuracion["instalado"]=1;
			}
			else
			{
				echo "<h3>ERROR FATAL</h3><br>Imposible rescatar las variables de configuraci&oacute;n.";
				exit;
			
			}
		}
		
		
		return $this->configuracion;
	}


}


?>
