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
            $borrar_cadena_sql.= "asig.id_sol ID_RAD, ";
            $borrar_cadena_sql.= "area.nombre AREA, ";
            $borrar_cadena_sql.= " asig.id_usuario ID_TMP_USU,";
            $borrar_cadena_sql.= "(SELECT concat(usu2.nombre,' ',usu2.apellido)  ";
            $borrar_cadena_sql.= "FROM  ";
            $borrar_cadena_sql.= $configuracion["prefijo"]."registrado usu2";
            $borrar_cadena_sql.= " WHERE usu2.id_usuario=asig.id_usuario ) TMP_USU ";
            $borrar_cadena_sql.= "FROM ";
            $borrar_cadena_sql.= $configuracion["prefijo"]."tmp_asigna asig";
            $borrar_cadena_sql.= " INNER JOIN ";
            $borrar_cadena_sql.= $configuracion["prefijo"]."area area ON area.id_area=asig.id_area ";
            $borrar_cadena_sql.="WHERE "; 
            $borrar_cadena_sql.=" asig.id_sol='".$_REQUEST["cod_rad"]."' ";    
            $borrar_cadena_sql.="AND asig.id_usuario='".$_REQUEST["cod_usuario"]."' ";
            $borrar_cadena_sql.="AND asig.id_area='".$_REQUEST['cod_area']."' ";
            $borrar_cadena_sql.="AND asig.estado='1'";
            
		//echo $borrar_cadena_sql;exit;
		
		$borrar_acceso_db->registro_db($borrar_cadena_sql,0);
		$borrar_registro=$borrar_acceso_db->obtener_registro_db();
                //var_dump($borrar_registro);
                
		$borrar_campos=$borrar_acceso_db->obtener_conteo_db();
		if($borrar_campos>0)
		{
			$opcion["nombre"]=" la asignacion del Radicado a ".$borrar_registro[0]['TMP_USU'];
			
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
            $borrar_cadena_sql.= $configuracion["prefijo"]."tmp_asigna asig";
            $borrar_cadena_sql.= " SET ";
            $borrar_cadena_sql.="asig.fecha_fin='".date('Y-m-d');
            $borrar_cadena_sql.="',asig.estado='0' ";
            $borrar_cadena_sql.="WHERE "; 
            $borrar_cadena_sql.=" asig.id_sol='".$_REQUEST["cod_rad"]."' ";    
            $borrar_cadena_sql.="AND asig.id_usuario='".$_REQUEST["cod_usuario"]."' ";
            $borrar_cadena_sql.="AND asig.id_area='".$_REQUEST['cod_area']."' ";
            $borrar_cadena_sql.="AND asig.estado='1'";
                
    //echo $borrar_cadena_sql;exit;
       	    $_REQUEST["resultado"]=$borrar_acceso_db->ejecutar_acceso_db($borrar_cadena_sql);		
	}
	
	return true;	
}


?>
