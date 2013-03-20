<?php

require_once ($configuracion["raiz_documento"].$configuracion["clases"]."/reader.php");
require_once ($configuracion["raiz_documento"].$configuracion["clases"]."/oleread.inc");
require_once ($configuracion["raiz_documento"].$configuracion["clases"]."/dbms.class.php");

class lector_estructura 
{
    function lector_estructura()
    {
    }
    
    static function verificar_datos_estructura($filas, $columnas, $data, $id_componente,$tipo=0)
    {
    	$error=false;
    	//Verificar que los nodos declarados sean unicos
    	
    	switch($tipo)
    	{
		case 0:
			$contador=0;
			$contador_2=0;
			$nivel=0;
			for ($i = 2; $i <= $filas; $i++) 
			{
				if($data->sheets[0]['cells'][$i][1]!=$contador)
				{
					$error=true;
					break;
				}
				$contador++;
				
				if($data->sheets[0]['cells'][$i][3]>$contador_2)
				{
					if($nivel<$data->sheets[0]['cells'][$i][3])
					{
						$nivel=$data->sheets[0]['cells'][$i][3];
					}
					$contador_2++;
				}
			}
			//Verificar que las niveles jerarquicos sean coherentes		
			if($error==false)
			{
				if($contador_2!=$nivel)
				{
					$error=true;
				}
				else
				{
					$error=false;
				}
			}
		break;
		
		default:
			break;
	}	
	
    	return $error;
    
    }
    
	static function guardar_estructura($filas, $columnas, $data, $valor,$tipo,$acceso_db,$enlace,$id_usuario, $configuracion)
	{
	
		$cadena_sql=lector_estructura::cadena_busqueda_estructura("borrar_estructura", $valor, $configuracion);
		$resultado=lector_estructura::busqueda_estructura("borrar", $cadena_sql, $acceso_db, $enlace);
		if($resultado==true)
		{
			//Guardar la nueva jerarquia (se asume encabezado en las paginas)
			for ($i = 2; $i <= $filas; $i++) 
			{
				for ($j = 1; $j <= $columnas; $j++) 
				{
					$valor[($j+1)]=$data->sheets[0]['cells'][$i][$j];
				}
			
				$cadena_sql=lector_estructura::cadena_busqueda_estructura("estructura", $valor, $configuracion);	
				$resultado=lector_estructura::busqueda_estructura("insertar",$cadena_sql, $acceso_db, $enlace);
				
				if($resultado==true)
				{
					$cadena_sql=lector_estructura::cadena_busqueda_estructura("ultimo_insertado","", $configuracion);	
					$registro=lector_estructura::busqueda_estructura("buscar",$cadena_sql, $acceso_db, $enlace);
					
					if(is_array($registro))
					{
						$valor2[0]=$registro[0][0];
						$valor2[1]=$id_usuario;
						$valor2[2]=$valor[5];
						
						$cadena_sql=lector_estructura::cadena_busqueda_estructura("nombre_componente", $valor2, $configuracion);	
						$resultado=lector_estructura::busqueda_estructura("insertar",$cadena_sql, $acceso_db, $enlace);
					
					}
					
				}
			}
		}
	}
	
	
	
	function busqueda_estructura($tipo,$cadena_sql,$acceso_db,$enlace)
	{
		switch($tipo)
		{
			case "insertar":
			case "borrar":
				$resultado=$acceso_db->ejecutar_acceso_db($cadena_sql); 
				
				break;
			case "buscar":
				$campos=$acceso_db->registro_db($cadena_sql,0);
				$registro=$acceso_db->obtener_registro_db();				
				break;
				
			default:
				break;
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
    
	static function cadena_busqueda_estructura($tipo, $valor="", $configuracion)
	{
    		
    		switch($tipo)
    		{
			
			case "estructura":
			
				$cadena_sql="INSERT INTO ";
				$cadena_sql.=$configuracion["prefijo"]."jerarquia "; 
				$cadena_sql.="( ";
				$cadena_sql.="`id_elemento`, ";
				$cadena_sql.="`tipo_jerarquia`, ";
				$cadena_sql.="`id_componente`, ";
				$cadena_sql.="`id_nodo`, ";
				$cadena_sql.="`id_padre`, ";
				$cadena_sql.="`nivel` ";
				$cadena_sql.=") ";
				$cadena_sql.="VALUES ";
				$cadena_sql.="( ";
				$cadena_sql.="NULL, "; 
				$cadena_sql.="'".$valor[0]."', "; 
				$cadena_sql.="'".$valor[1]."', "; 
				$cadena_sql.="'".$valor[2]."', "; 
				$cadena_sql.="'".$valor[3]."', "; 
				$cadena_sql.="'".$valor[4]."' "; 
				$cadena_sql.=")";
				break;
			
			case "nombre_componente":
			
				$cadena_sql="INSERT INTO ";
				$cadena_sql.=$configuracion["prefijo"]."c_organizacion "; 
				$cadena_sql.="( ";
				$cadena_sql.="`id_c_organizacion`, ";
				$cadena_sql.="`id_usuario`, ";
				$cadena_sql.="`fecha`, ";
				$cadena_sql.="`nombre` ";
				$cadena_sql.=") ";
				$cadena_sql.="VALUES ";
				$cadena_sql.="( ";
				$cadena_sql.="'".$valor[0]."', ";
				$cadena_sql.="'".$valor[1]."', ";
				$cadena_sql.="'".time()."', ";
				$cadena_sql.="'".$valor[2]."' ";				
				$cadena_sql.=")";
				break;
				
			case "ultimo_insertado":
				$cadena_sql="SELECT ";
				$cadena_sql.="LAST_INSERT_ID()";
				break;
				
			case "buscar_entidad":	
				$cadena_sql="SELECT ";
				$cadena_sql.="`tipo_jerarquia`, ";
				$cadena_sql.="`id_componente`, ";
				$cadena_sql.="`id_nodo`, ";
				$cadena_sql.="`id_padre`, ";
				$cadena_sql.="`nivel` ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$configuracion["prefijo"]."jerarquia ";				
				$cadena_sql.="WHERE ";
				$cadena_sql.="tipo_jerarquia=".$valor[0].", ";
				$cadena_sql.="AND ";
				$cadena_sql.="id_componente=".$valor[1]." ";
				break;
			
			case "borrar_estructura":	
				$cadena_sql="DELETE ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$configuracion["prefijo"]."jerarquia ";				
				$cadena_sql.="WHERE ";
				$cadena_sql.="tipo_jerarquia=".$valor[0]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="id_componente=".$valor[1]." ";
				break;
				
			default:
				$cadena_sql="";
				break;
    		}
    		echo $cadena_sql."<br>";
    		return $cadena_sql;
    		
    
    	}


}


?>
