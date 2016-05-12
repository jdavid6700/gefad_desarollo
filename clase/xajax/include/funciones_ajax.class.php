<?php
function datos_basicos($valor)
{
	require_once("clase/config.class.php");
	setlocale(LC_MONETARY, 'en_US');
	
	$esta_configuracion=new config();
	$configuracion=$esta_configuracion->variable(); 
	//Buscar un registro que coincida con el valor
	
	
	$acceso_db=new dbms($configuracion);
	$enlace=$acceso_db->conectar_db();
	
	$valor=$acceso_db->verificar_variables($valor);

	if (is_resource($enlace))
	{
		
		$cadena_sql="SELECT ";
		$cadena_sql.="* ";
		$cadena_sql.="FROM ";
		$cadena_sql.=$configuracion["prefijo"]."estudiante ";
		$cadena_sql.="WHERE ";
		$cadena_sql.="codigo_est='".$valor."' ";
		$cadena_sql.="LIMIT 1";
		
		$conteo=$acceso_db->registro_db($cadena_sql,0);
		$registro=$acceso_db->obtener_registro_db();
		if($conteo>0)
		{
			$cadena_html="<table class='bloquelateral' width='100%'>\n";
			$cadena_html.="<tr class='bloquecentralcuerpo'>\n";
			$cadena_html.="<td>\n";
			$cadena_html.="Nombre: ";
			$cadena_html.="</td>\n";
			$cadena_html.="<td>\n";
			$cadena_html.=$registro[0][2];
			$cadena_html.="</td>\n";
			$cadena_html.="</tr>\n";
			$cadena_html.="<tr class='bloquecentralcuerpo'>\n";
			$cadena_html.="<td>\n";
			$cadena_html.="Identificaci&oacute;n: ";
			$cadena_html.="</td>\n";
			$cadena_html.="<td>\n";
			$cadena_html.=$registro[0][1];
			$cadena_html.="</td>\n";
			$cadena_html.="</tr>\n";
			$cadena_html.="<tr class='bloquecentralcuerpo'>\n";
			$cadena_html.="<td>\n";
			$cadena_html.="Matr&iacute;cula Base: ";
			$cadena_html.="</td>\n";
			$cadena_html.="<td>\n";
			$cadena_html.=money_format('$%!.0i', $registro[0][4]);
			$cadena_html.="</td>\n";
			$cadena_html.="</tr>\n";
			$cadena_html.="<tr class='bloquecentralcuerpo'>\n";
			$cadena_html.="<td>\n";
			$cadena_html.="Matr&iacute;cula Reliquidada: ";
			$cadena_html.="</td>\n";
			$cadena_html.="<td>\n";
			$cadena_html.=money_format('$%!.0i', $registro[0][5]);
			$cadena_html.="</td>\n";
			$cadena_html.="</tr>\n";
			$cadena_html.="</table>\n";
			$respuesta = new xajaxResponse();
			$respuesta->addAssign("registro","innerHTML",$cadena_html);
			
			
			unset($registro);
			unset($conteo);
			
			$cadena_sql="SELECT ";
			$cadena_sql.="* ";
			$cadena_sql.="FROM ";
			$cadena_sql.=$configuracion["prefijo"]."exencion ";
			$conteo=$acceso_db->registro_db($cadena_sql,0);
			$registro=$acceso_db->obtener_registro_db();
			
			if($conteo>0)
			{
				for($i=0;$i<$conteo;$i++)
				{
					$respuesta->addAssign("exencion_".$registro[$i][0],"checked",false);
					
				}
				unset($registro);
				unset($conteo);
				$cadena_sql="SELECT ";
				$cadena_sql.="`codigo_est`, ";
				$cadena_sql.="`id_programa`, ";
				$cadena_sql.="`id_exencion`, ";
				$cadena_sql.="`anno`, ";
				$cadena_sql.="`periodo`, ";
				$cadena_sql.="`fecha` ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$configuracion["prefijo"]."estudiante_exencion ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="codigo_est='".$valor."' ";
				$conteo=$acceso_db->registro_db($cadena_sql,0);
				$registro=$acceso_db->obtener_registro_db();
				
				if($conteo>0)
				{
					for($i=0;$i<$conteo;$i++)
					{
						$respuesta->addAssign("exencion_".$registro[$i][2],"checked",1);
						
					}	
				}	
			}
			
		}
		else
		{
			$cadena_html="<table class='bloquelateral' width='100%'>\n";
			$cadena_html.="<tr class='bloquecentralcuerpo'>\n";
			$cadena_html.="<td class='centrar'>\n";
			$cadena_html.="<span class='texto_negrita'>El c&oacute;digo del estudiante no se encuentra registrado.</span>";
			$cadena_html.="</td>\n";
			$cadena_html.="</tr>\n";
			$cadena_html.="</table>\n";
			$respuesta = new xajaxResponse();
			$respuesta->addAssign("registro","innerHTML",$cadena_html);
			
			
		
		}
		//echo $respuesta;
		return $respuesta;
	}
		
	
}



?>
