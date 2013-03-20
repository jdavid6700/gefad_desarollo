<?php

require_once ($configuracion["raiz_documento"].$configuracion["clases"]."/reader.php");
require_once ($configuracion["raiz_documento"].$configuracion["clases"]."/oleread.inc");
require_once ($configuracion["raiz_documento"].$configuracion["clases"]."/dbms.class.php");

class lector extends Spreadsheet_Excel_Reader
{
    function tabla($tabla="",$configuracion)
    {
    	$this->tabla=$tabla;
    	$this->configuracion=$configuracion;
    }
    
    function addcell($row, $col, $string, $raw = '')
    {
        
        if(!isset($this->registro))
        {
		$this->registro=array();	
		$this->tipo=array();	
        
        }
        else
        {
        	if($this->fila<$row)
        	{
        		
        		$this->total_columnas=count($this->registro[0]);
        		switch($this->tabla)
        		{
				case "jerarquia":
					$columna=0;
					
					$cadena_sql=$this->cadena_busqueda_lector("buscar_componente", $this->registro[0][1]);
					$registro_docente=$this->busqueda_lector("buscar",$cadena_sql);
					if(!is_array($registro_docente))
					{
						$cadena_sql=$this->cadena_busqueda_lector("profesor", $this->registro);	
						$resultado=$this->busqueda_lector("insertar",$cadena_sql);
					}
					unset($this->registro);
					unset($this->tipo);
				break;
				
				default:
				break;
				
			}
			
			
		
		}
        	
        }
        $this->registro[0][$col]=htmlentities($string);
	$this->fila=$row;
    }
    
    function busqueda_lector($tipo,$cadena_sql)
    {
    	echo $cadena_sql."<br>";
    	$acceso_db=new dbms($this->configuracion);
	$enlace=$acceso_db->conectar_db();
	if (is_resource($enlace))
	{
		switch($tipo)
		{
			case "insertar":
				$resultado=$acceso_db->ejecutar_acceso_db($cadena_sql); 
				echo $cadena_sql."<br>";
				break;
			case "buscar":
				$campos=$acceso_db->registro_db($cadena_sql,0);
				$registro=$acceso_db->obtener_registro_db();				
				break;
				
			default:
				break;
		}
	}
	else
	{
		echo "<h1>Imposible realizar la acci&oacute; solicitada.</h1>";
	}
	
	if(isset($registro))
	{
		return $registro;	
	}
	else
	{
		return $resultado;
	}
    }
    
    
    function cadena_busqueda_lector($tipo, $valor)
    {
    		
    		switch($tipo)
    		{
			
			case "profesor":
			
				$cadena_sql="INSERT INTO ";
				$cadena_sql.=$this->configuracion["prefijo"]."profesor "; 
				$cadena_sql.="( ";
				$cadena_sql.="`id_profesor`, ";
				$cadena_sql.="`id_tipo_identificacion`, ";
				$cadena_sql.="`identificacion`, ";
				$cadena_sql.="`nombre`, ";
				$cadena_sql.="`apellido`, ";
				$cadena_sql.="`sexo`, ";
				$cadena_sql.="`nacimiento`, ";
				$cadena_sql.="`correo`, ";
				$cadena_sql.="`direccion`, ";
				$cadena_sql.="`telefono`, ";
				$cadena_sql.="`fecha` ";
				$cadena_sql.=") ";
				$cadena_sql.="VALUES ";
				$cadena_sql.="( ";
				$cadena_sql.="'NULL', ";
				$cadena_sql.="'".$valor[0][0]."', "; 
				$cadena_sql.="'".$valor[0][1]."', "; 
				$cadena_sql.="'".$valor[0][2]."', "; 
				$cadena_sql.="'".$valor[0][3]."', "; 
				$cadena_sql.="'".$valor[0][4]."', "; 
				$cadena_sql.="'".$valor[0][5]."', "; 
				$cadena_sql.="'".$valor[0][6]."', ";
				$cadena_sql.="'".$valor[0][7]."', ";
				$cadena_sql.="'".$valor[0][8]."', ";				
				$cadena_sql.="'".time()."' ";
				$cadena_sql.=")";
				break;
				
			case "buscar_profesor":	
				$cadena_sql="SELECT ";
				$cadena_sql.="id_profesor ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$this->configuracion["prefijo"]."profesor "; 
				$cadena_sql.="WHERE ";
				$cadena_sql.="identificacion='".$valor."' ";
				$cadena_sql.="LIMIT 1 ";
				
				
			default:
				break;
    		}
    		
    		return $cadena_sql;
    		//echo $cadena_sql."<br>";
    
    }


}


?>
