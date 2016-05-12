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
		$borrar_cadena_sql="SELECT ";
		$borrar_cadena_sql.="US.id_usuario, ";
		$borrar_cadena_sql.="US.nombre, ";
		$borrar_cadena_sql.="US.apellido, ";
		$borrar_cadena_sql.="PRY.nombre " ;
		$borrar_cadena_sql.="FROM ";
		$borrar_cadena_sql.=$configuracion["prefijo"]."registrado AS US ";
		$borrar_cadena_sql.="INNER JOIN ";
		$borrar_cadena_sql.=$configuracion["prefijo"]."integrante_proyecto AS US_PRY ";
		$borrar_cadena_sql.=" ON US.id_usuario=US_PRY.id_usuario ";
		$borrar_cadena_sql.="INNER JOIN ";
		$borrar_cadena_sql.=$configuracion["prefijo"]."proyecto AS PRY ";
		$borrar_cadena_sql.=" ON US_PRY.id_proyecto=PRY.id_proyecto ";
		$borrar_cadena_sql.="WHERE ";
		$borrar_cadena_sql.="US.id_usuario=".$_REQUEST["registro"]." ";
		$borrar_cadena_sql.="AND ";
		$borrar_cadena_sql.="PRY.id_proyecto=".$_REQUEST["id_proyecto"]." ";
		$borrar_cadena_sql.="LIMIT 1 ";
		//echo $borrar_cadena_sql;
		
		$borrar_acceso_db->registro_db($borrar_cadena_sql,0);
		$borrar_registro=$borrar_acceso_db->obtener_registro_db();
		$borrar_campos=$borrar_acceso_db->obtener_conteo_db();
		if($borrar_campos>0)
		{
			$opcion["nombre"]=" El Integrante: ".$borrar_registro[0][1]." ".$borrar_registro[0][2]." del Proyecto: ".$borrar_registro[0][3];
			
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
	$variable.="&id_usuario=";
	$variable.=$_REQUEST["registro"];
	$variable.="&id_proyecto=";
	$variable.=$_REQUEST["id_proyecto"];
	$variable.="&nombre=";
	$variable.=$borrar_registro[0][1]." ".$borrar_registro[0][2];
	$variable.="&proyecto=";
	$variable.=$borrar_registro[0][3];
	
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
	
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
	$cripto=new encriptar();
	$variable=$cripto->codificar_url($variable,$configuracion);
	
	/*pendiente para realizar diferente verificaciones
	
	$conf_cadena_sql="SELECT ";
	$conf_cadena_sql.="`id_objeto2` ";
	$conf_cadena_sql.="FROM ";
	$conf_cadena_sql.=$configuracion["prefijo"]."relacion_objeto ";
	$conf_cadena_sql.="WHERE ";
	$conf_cadena_sql.="`id_objeto1`= ";
	$conf_cadena_sql.=$_REQUEST['registro'];
			
	//echo $conf_cadena_sql;
		
	//$borrar_acceso_db->registro_db($conf_cadena_sql,0);
	//$conf_campos=$borrar_acceso_db->obtener_conteo_db();*/

	$conf_campos=0;	
	//Opciones
	if($conf_campos>0)
		{
			$opciones="<table width='100%' align='center' border='0'>\n";
			$opciones.="<tr align='center'>\n";
			$opciones.="<td>";
			$opciones.="El objeto NO se puede Eliminar, ya que tiene ".$conf_campos." objetos relacionados <br> Borre primero estas relaciones e intente de nuevo!";
			$opciones.='<br>';
			$opciones.="</td>\n"; 
			$opciones.="</tr>\n";
			$opciones.="<tr align='center'>\n";
			$opciones.="<td>\n";
			$opciones.='<a href="'.$pagina.$variable_2.'">Aceptar</a>';
			$opciones.='<br>';
			$opciones.="</td>\n"; 
			$opciones.="</tr>\n";
			$opciones.="</table>\n";

			}
		else
		{
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
		}
	$opcion["confirmar"]=$opciones;
	return $opcion;
}

//@ Método que generay ejecuta la sentencia sql para borrar el registro de una noticia.

function borrar($configuracion)
{	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/log.class.php");
	$log_us= new log();
	$borrar_acceso_db=new dbms($configuracion);
	$borrar_enlace=$borrar_acceso_db->conectar_db();
	
	if (is_resource($borrar_enlace))
	{	$variable[0]=$_REQUEST['registro'];
		$variable[1]=$_REQUEST['id_proyecto'];
		
	borrar_integrante($configuracion,$variable); 
		//VARIABLES PARA EL LOG
	$registro[0]="BORRAR";//ACCION
	$registro[1]=$_REQUEST['registro'];//ID_REGISTRO
	$registro[2]="INTEGRANTE_PROYECTO";//TIPO_REGISTRO
	$registro[3]=$_REQUEST['nombre'];//NOMBRE
	$registro[4]=time();//FECHA_LOG
	$registro[5]="Borra el integrante ".$registro[3]." del proyecto ".$_REQUEST['proyecto']." el dia ".date("d/m/Y h:m:s",$registro[4]);//DESCRIPCION
	//echo $registro[5];exit;
	$log_us->log_usuario($registro,$configuracion);		
	}
	
	return true;	
}

function borrar_integrante($configuracion,$registro)
{						
	$borrar_acceso_db=new dbms($configuracion);
	$borrar_enlace=$borrar_acceso_db->conectar_db();

	$borrar_cadena_sql="DELETE ";
	$borrar_cadena_sql.="FROM "; 
	$borrar_cadena_sql.=$configuracion["prefijo"]."integrante_proyecto ";
	$borrar_cadena_sql.="WHERE ";
	$borrar_cadena_sql.="id_usuario=".$registro[0];
	$borrar_cadena_sql.=" AND ";
	$borrar_cadena_sql.="id_proyecto=".$registro[1];	

	//	echo "<br>".$borrar_cadena_sql;exit;
	$_REQUEST["resultado"]=$borrar_acceso_db->ejecutar_acceso_db($borrar_cadena_sql);
	
	return true;	
}


?>
