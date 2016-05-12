<?

//@ funcion que verifica si se registro una acción para ejecutarla
if(!isset($_REQUEST["action"]))
{
	$opcion=confirmar($configuracion);
	$borrar_nombre=$opcion["nombre"];
	$opciones=$opcion["confirmar"];
	
}
else
{
	$resultado=borrar($configuracion);
}
/********************************************************************************************
				Funciones
*********************************************************************************************/

//@ Método que recupera los datos del registro a borrar, y los construye el mensaje de confirmación de borrar el registro de una noticia.

function confirmar($configuracion)
{
	$borrar_acceso_db=new dbms($configuracion);
	$borrar_enlace=$borrar_acceso_db->conectar_db();
	if (is_resource($borrar_enlace))
	{       
                       
                $borrar_cadena_sql= "SELECT ";
                $borrar_cadena_sql.="reg.id_usuario ID_US, ";
                $borrar_cadena_sql.="reg.fecha_registro F_INI, ";
                $borrar_cadena_sql.="reg.fecha_fin F_FIN, ";
                $borrar_cadena_sql.= "reg.estado ESTADO, ";
                $borrar_cadena_sql.= "sub.id_subsistema ID_ROL, ";
                $borrar_cadena_sql.= "sub.nombre ROL, ";
                $borrar_cadena_sql.= "reg.id_area ID_AREA, ";
                $borrar_cadena_sql.= "area.nombre AREA, ";
                $borrar_cadena_sql.= "area.id_dependencia ID_DEPE, ";
                $borrar_cadena_sql.= "dep.nombre DEPE ";
                $borrar_cadena_sql.= "FROM ";
                $borrar_cadena_sql.= $configuracion["prefijo"]."registrado_subsistema reg ";
                $borrar_cadena_sql.= "INNER JOIN ";
                $borrar_cadena_sql.= $configuracion["prefijo"]."subsistema sub on reg.id_subsistema=sub.id_subsistema ";
                $borrar_cadena_sql.= "INNER JOIN ";
                $borrar_cadena_sql.= $configuracion["prefijo"]."area area on reg.id_area=area.id_area ";
                $borrar_cadena_sql.= "INNER JOIN ";
                $borrar_cadena_sql.= $configuracion["prefijo"]."dependencia dep on dep.id_dependencia=area.id_dependencia ";
                $borrar_cadena_sql.="WHERE ";
                $borrar_cadena_sql.="reg.id_usuario='".$_REQUEST["cod_usuario"]."' ";
                $borrar_cadena_sql.="AND reg.id_subsistema='".$_REQUEST['cod_rol']."' ";
                $borrar_cadena_sql.="AND reg.id_area='".$_REQUEST['cod_area']."' ";
                $borrar_cadena_sql.="AND reg.estado='1'";
            
		//echo $borrar_cadena_sql;exit;
		
		$borrar_acceso_db->registro_db($borrar_cadena_sql,0);
		$borrar_registro=$borrar_acceso_db->obtener_registro_db();
		$borrar_campos=$borrar_acceso_db->obtener_conteo_db();
		if($borrar_campos>0)
		{
			$opcion["nombre"]=" el rol ".$borrar_registro[0][5]." en el Área de ".$borrar_registro[0][7];
			
		}
		else
		{
			$borrar_nombre="Registro Desconocido";
		}
	}
	else
	{
	//ERROR AL INGRESAR A LA BD
	
	}	
	
	$pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
	$variable="pagina=borrar_registro";
	$variable.="&action=borrar_registro";
	
	$variable_2=$_REQUEST["redireccion"];
	
	reset ($_REQUEST);
	while (list ($clave, $val) = each ($_REQUEST)) 
	{
		if($clave!='pagina')
		{
			$variable.="&".$clave."=".$val;
			//echo $clave."=".$val."<br>";
		}
		
	}
	//echo $variable."<br>";	
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
	$cripto=new encriptar();
	$variable=$cripto->codificar_url($variable,$configuracion);
	
	
	//Opciones
	$opciones="<table width='50%' align='center' border='0'>\n";
	$opciones.="<tr align='center'>\n";
	$opciones.="<td>\n";
	//Si
	$opciones.='<a href="'.$pagina.$variable.'">Si</a>';
	$opciones.="</td>\n";
	//No
	$opciones.="<td>\n";
	$opciones.='<a href="'.$pagina.$variable_2.'">No</a>';
	$opciones.='<br>';
	$opciones.="</td>\n"; 
	$opciones.="</tr>\n";
	$opciones.="</table>\n";
	$opcion["confirmar"]=$opciones;
	return $opcion;
}

//@ Método que generay ejecuta la sentencia sql para borrar el registro de una noticia.

function borrar($configuracion)
{
	$borrar_acceso_db=new dbms($configuracion);
	$borrar_enlace=$borrar_acceso_db->conectar_db();
	if (is_resource($borrar_enlace))
	{       
                $borrar_cadena_sql="UPDATE ";
                $borrar_cadena_sql.=$configuracion["prefijo"]."registrado_subsistema reg ";
                $borrar_cadena_sql.= " SET ";
                $borrar_cadena_sql.="reg.fecha_fin='".date('Y-m-d');
                $borrar_cadena_sql.="',reg.estado='0' ";
                $borrar_cadena_sql.="WHERE ";
                $borrar_cadena_sql.="reg.id_usuario='".$_REQUEST["cod_usuario"]."' ";
                $borrar_cadena_sql.="AND reg.id_subsistema='".$_REQUEST['cod_rol']."' ";
                $borrar_cadena_sql.="AND reg.id_area='".$_REQUEST['cod_area']."' ";
                $borrar_cadena_sql.="AND reg.estado='1'";
   // echo $borrar_cadena_sql;exit;
                
                
		$_REQUEST["resultado"]=$borrar_acceso_db->ejecutar_acceso_db($borrar_cadena_sql);		
	}
	
	return true;	
}


?>
