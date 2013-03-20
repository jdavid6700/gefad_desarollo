<?
/*
############################################################################
#    UNIVERSIDAD DISTRITAL Francisco Jose de Caldas                        #
#    Desarrollo Por:                                                       #
#    Paulo Cesar Coronado 2004 - 2007                                      #
#    paulo_cesar@udistrital.edu.co                                         #
#    Copyright: Vea el archivo EULA.txt que viene con la distribucion      #
############################################################################
*/
?>
<?
/****************************************************************************
  
index.php 

Paulo Cesar Coronado
Copyright (C) 2001-2005

Última revisión 6 de Marzo de 2006

******************************************************************************
* @subpackage   
* @package	bloques
* @copyright    
* @version      0.2
* @author      	Paulo Cesar Coronado
* @link		N/D
* @description  Menu para mostrar el calendario.
* @usage        
*******************************************************************************/
?><?

if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;		
}

//include_once($configuracion["raiz_documento"].$configuracion["bloques"]."/enlace.inc.php");
class calendario
{
	//@Constructor
	function __construct()
	{
		
	
	}
	
	
	function  buscar_festivo($dia,$mes,$anno,$configuracion)
	{
		//Tipo
		//1:Fecha Fija
		//2:Lunes siguiente
		//3:Respecto a la Pascua Fijos
		//4:Respecto a la Pascua Lunes siguiente
		
		$pascua=pascua();
		
		$buscar=cadena_busqueda_calendario(1,$configuracion,$dia,$mes);
		//echo $buscar."<hr>";
		
		//echo $pascua["mes"];
		
		$nuestra_pascua=strtotime($pascua["mes"]."/".$pascua["dia"]."/".$anno);
		$esta_fecha=strtotime($mes."/".$dia."/".$anno);
			
		
		$acceso_db=new dbms($configuracion);
		$enlace=$acceso_db->conectar_db();
		if (is_resource($enlace))
		{
			$acceso_db->registro_db($buscar,0);
			$registro_festivo=$acceso_db->obtener_registro_db();
			$campos=$acceso_db->obtener_conteo_db();
		}	
		if($campos>0)
		{
			if($registro_festivo[0][0]==2)
			{
				//Se corre al lunes siguiente.
				//Determinar el dia de la semana...
				
				$este_dia_semana = date("w",$esta_fecha); 
				
				//echo $este_dia_semana;
				
				if($este_dia_semana==0)
				{
					$suma=1;
				}
				else
				{
					if($este_dia_semana==1)
					{
						$suma=0;
					}
					else
					{
						$suma=8-$este_dia_semana;
					}
				}
				$esta_fecha+=($suma*24*60*60);
				$el_dia=date("d",$esta_fecha);
				$el_mes=date("n",$esta_fecha);
				$el_anno=date("Y",$esta_fecha);
				$la_descripcion=$registro_festivo[0][3];
				if($el_mes==$mes)
				{
					$es_festivo["dia"]=$el_dia;
					$es_festivo["descripcion"]=$la_descripcion;
					$es_festivo["mes"]=$el_mes;
					$es_festivo["anno"]=$el_anno;
					return $es_festivo;	
				}
				else
				{
					return FALSE;
				}
			}
			else
			{
				$es_festivo["dia"]=$registro_festivo[0][1];
				$es_festivo["descripcion"]=$registro_festivo[0][3];
				$es_festivo["mes"]=$registro_festivo[0][2];
				$es_festivo["anno"]=$anno;
				return $es_festivo;
			
			}
			
			
			
		}
		else
		{
			
			if(date("d",$esta_fecha)==date("d",($nuestra_pascua+(43*24*60*60)))
			&& date("n",$esta_fecha)==date("n",($nuestra_pascua+(43*24*60*60)))
			)
			{
				$es_festivo["descripcion"]="Ascensi&oacute;n de Jes&uacute;s";
				$festivo=1;
			}
			else
			{
				if(date("d",$esta_fecha)==date("d",($nuestra_pascua+(64*24*60*60)))
				&& date("n",$esta_fecha)==date("n",($nuestra_pascua+(64*24*60*60)))
				)
				{
					$es_festivo["descripcion"]="Corpus Christi";
					$festivo=1;
				}
				else
				{
					if(date("d",$esta_fecha)==date("d",($nuestra_pascua+(71*24*60*60)))
					&& date("n",$esta_fecha)==date("n",($nuestra_pascua+(71*24*60*60)))
					)
					{
						$es_festivo["descripcion"]="Sagrado Coraz&oacute;n";
						$festivo=1;
					}
					else
					{
						if(date("d",$esta_fecha)==date("d",($nuestra_pascua-(3*24*60*60)))
						&& date("n",$esta_fecha)==date("n",($nuestra_pascua+(-3*24*60*60)))
						)
						{
							$es_festivo["descripcion"]="Viernes Santo";
							$festivo=1;
						}
						else
						{
							if(date("d",$esta_fecha)==date("d",($nuestra_pascua-(4*24*60*60)))
							&& date("n",$esta_fecha)==date("n",($nuestra_pascua-(4*24*60*60)))
							)
							{
								$es_festivo["descripcion"]="Jueves Santo";
								$festivo=1;
							}
							else
							{
								$festivo=0;
							}
						}
					}
				}
			}
			if($festivo==1)
			{
				
		
				$este_dia_semana = date("w",$esta_fecha); 
				
				if($este_dia_semana==0)
				{
					$suma=1;
				}
				else
				{
					if($este_dia_semana==1)
					{
						$suma=0;
					}
					else
					{
						$suma=8-$este_dia_semana;
					}
				}
				$esta_fecha+=$suma;
				$el_dia=date("d",$esta_fecha);
				$el_mes=date("n",$esta_fecha);
				$el_anno=date("Y",$esta_fecha);
				$la_descripcion=$registro_festivo[0][3];
				
				
				if($el_mes==$mes)
				{
					$es_festivo["dia"]=$el_dia;
					$es_festivo["descripcion"]=$la_descripcion;
					$es_festivo["mes"]=$el_mes;
					$es_festivo["anno"]=$el_anno;
					return $es_festivo;	
				}
				else
				{
					return FALSE;
				}
				
				
			}
			else
			{
						return FALSE;
			}	
		
			
		}
		
	
	}
	
	
	function pascua()
	{
		$anno=date("Y",time());
		$A=$anno%19;
		$B=$anno%4;
		$C=$anno%7;	
		$D = (19*$A+24)%30;	
		$E = (2*$B+4*$C+6*$D+5)%7;
		
		if(($D+$E)<10)
		{
			$la_pascua["mes"]=3;
			$la_pascua["dia"]=$D + $E + 22;
		}
		else
		{
			$la_pascua["mes"]=4;
			$la_pascua["dia"]=$D+$E-9;
			
			if($la_pascua["dia"]==26)
			{
				$la_pascua["dia"]=19;
			}
			else
			{
				if(($la_pascua["dia"]==25)&&($D==28)&&($E==6)&&($A>10))
				{
					$la_pascua["dia"]=18;
				}
			}
			
		}
			
			
			
			
			
		
		return $la_pascua;
	
	}
	
	
	function cadena_busqueda_calendario($tipo,$configuracion,$dia,$mes,$anno="")
	{
		if ($anno=="")
		{
			$anno=date("Y",time());	
		}
		switch($tipo)
		{
			
			case 1:
				$cadena_sql="SELECT ";
				$cadena_sql.="`tipo`, ";
				$cadena_sql.="`dia`, ";
				$cadena_sql.="`mes`, ";
				$cadena_sql.="`descripcion` ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$configuracion["prefijo"]."calendario ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="dia=".$dia." ";
				$cadena_sql.="AND ";
				$cadena_sql.="mes=".$mes." ";
				$cadena_sql.="LIMIT 1";
				break;
			
			case 2:
				$cadena_sql="SELECT ";
				$cadena_sql.="`nombre` ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$configuracion["prefijo"]."mes ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="id_mes=".$mes." ";
				$cadena_sql.="LIMIT 1";
				
				break;
				
			default:
				break;		
		}	
		return $cadena_sql;
	}
}

?>