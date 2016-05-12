<?
/*
############################################################################
#    UNIVERSIDAD DISTRITAL Francisco Jose de Caldas                        #
#    Desarrollo Por:                       				   #
#    Paulo Cesar Coronado 2004 - 2005                                      #
#    paulo_cesar@ttg.net.co                                                #
#    Copyright: Vea el archivo EULA.txt que viene con la distribucion      #
############################################################################
*/
/****************************************************************************
  
registro.action.php 

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
* @description  Action de registro de usuarios
* @usage        
******************************************************************************/


if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;		
}

//Evitar que se ingrese codigo HTML y PHP en los campos de texto
foreach ($_REQUEST as $clave => $valor) 
{
    $_REQUEST[$clave]= strip_tags($valor);    
}



//Verificacion de validez de los datos en el servidor
if(!(strlen($_REQUEST['nombre'])>2)||!(strlen($_REQUEST['apellido'])>2)||!(strlen($_REQUEST['correo'])>6)||!(strlen($_REQUEST['usuario'])>2)||!(strlen($_REQUEST['clave'])>4))
{
	//Instanciar a la clase pagina con mensaje de correcion de datos
}
else
{
	//Verificar validez del correo electronico
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/cadenas.class.php");
	if(cadenas::verificarCorreoElectronico($_REQUEST["correo"]))
	{
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
		$cripto=new encriptar();
		
		$acceso_db=new dbms($configuracion);
		$enlace=$acceso_db->conectar_db();
		if (is_resource($enlace))
		{	 
			//Validar las variables para evitar ataques por insercion de SQL

			$_REQUEST=$acceso_db->verificar_variables($_REQUEST);			
			
			if(!isset($_REQUEST["id_usuario"]))
			{
				if(!isset($_REQUEST["confirmacion"]))
				{
					//Para nuevos registros
					nuevoUsuario($configuracion ,$acceso_db);
				}
				else
				{
					confirmarUsuario($configuracion ,$acceso_db);
				}
			}
			else
			{
				if(isset($_REQUEST["confirmar"]))
				{
					ingresarUsuario($configuracion);
				}
				else
				{
					//Para registros antiguos
					usuarioAntiguo($configuracion);
				}
				
			}	
		} 
		else
		{
			//Mensaje de error de no disponibilidad de base de datos 
				
		}
	
		
	
	}
	
	
}


/****************************************************************************************
*				Funciones						*
****************************************************************************************/

function enviar_correo($configuracion)
{

	$destinatario=$configuracion["correo"];
	$encabezado="Nuevo Usuario ".$configuracion["titulo"];
	
	$mensaje="Administrador:\n";
	$mensaje.=$_REQUEST['nombre']." ".$_REQUEST['apellido']."\n";
	$mensaje.="Correo Electronico:".$_REQUEST['correo']."\n";
	$mensaje.="Telefono:".$_REQUEST['telefono']."\n\n";
	$mensaje.="Ha solicitado acceso a ".$configuracion["titulo"]."\n\n";
	$mensaje.="Por favor visite la seccion de administracion para gestionar esta peticion.\n";
	$mensaje.="_____________________________________________________________________\n";
	$mensaje.="Por compatibilidad con los servidores de correo, en este mensaje se han omitido a\n";
	$mensaje.="proposito las tildes.";
	
	$correo= mail($destinatario, $encabezado,$mensaje) ;
	
	
	$destinatario=$_REQUEST['correo'];
	$encabezado="Solicitud de Confirmacion ".$configuracion["titulo"];
	
	
	$mensaje="Hemos recibido una solicitud para acceder al portal web\n";
	$mensaje.=$configuracion["titulo"];
	$mensaje.="en donde se referencia esta direccion de correo electronico.\n\n";
	$mensaje.="Si efectivamente desea inscribirse a nuestra comunidad por favor seleccione el siguiente enlace:\n";	
	$mensaje="En caso contrario por favor omita el contenido del presente mensaje.";
	$mensaje.="_____________________________________________________________________\n";
	$mensaje.="Por compatibilidad con los servidores de correo en este mensaje se han omitido a\n";
	$mensaje.="proposito las tildes.";
	$mensaje.="_____________________________________________________________________\n";
	$mensaje.="Si tiene inquietudes por favor envie un correo a: ".$configuracion["correo"]."\n";
	
	$correo= mail($destinatario, $encabezado,$mensaje) ;


}

function sqlRegistroUsuario($configuracion, $opcion, $valor)
{
	switch($opcion)
	{
	
		case "usuario":
		$cadena_sql="SELECT ";
		$cadena_sql.="* ";
		$cadena_sql.="FROM ";
		$cadena_sql.="".$configuracion["prefijo"]."registrado ";
		$cadena_sql.="WHERE ";
		$cadena_sql.="usuario='".$valor."' ";
		$cadena_sql.="LIMIT 1";
		break;
		
		case  "rescatarUsuario":		
			$cadena_sql="SELECT ";
			$cadena_sql.="`id_usuario`, ";
			$cadena_sql.="`nombre`, ";
			$cadena_sql.="`apellido`, ";
			$cadena_sql.="`correo`, ";
			$cadena_sql.="`telefono`, ";
			$cadena_sql.="`usuario`, ";
			$cadena_sql.="`clave` ";
			$cadena_sql.="FROM ";
			$cadena_sql.="".$configuracion["prefijo"]."registrado ";
			$cadena_sql.="WHERE ";
			$cadena_sql.="id_usuario='".$valor."' ";			
			$cadena_sql.="LIMIT 1";
			break;
		
		case "correo":
			$cadena_sql="SELECT ";
			$cadena_sql.="* ";
			$cadena_sql.="FROM ";
			$cadena_sql.=$configuracion["prefijo"]."registrado ";
			$cadena_sql.="WHERE ";
			$cadena_sql.="correo='".$valor."'";
			break;
			
		case "insertarBorrador":
			$cadena_sql="INSERT INTO ";
			$cadena_sql.=$configuracion["prefijo"]."registrado_borrador "; 
			$cadena_sql.="( ";
			$cadena_sql.="`identificador`, ";
			$cadena_sql.="`nombre`, ";
			$cadena_sql.="`apellido`, ";
			$cadena_sql.="`id_tipo_documento`, ";
			$cadena_sql.="`identificacion`, ";
			$cadena_sql.="`direccion`, ";
			$cadena_sql.="`ciudad`, ";
			$cadena_sql.="`pais`, ";
			$cadena_sql.="`correo`, ";
			$cadena_sql.="`telefono`, ";
			$cadena_sql.="`usuario`, ";
			$cadena_sql.="`clave`, ";
			$cadena_sql.="`asociado`, ";
			$cadena_sql.="`region` ";
			$cadena_sql.=") ";
			$cadena_sql.="VALUES ";
			$cadena_sql.="( ";
			$cadena_sql.="'".$valor[11]."', ";
			$cadena_sql.="'".$valor[0]."', ";
			$cadena_sql.="'".$valor[1]."', ";
			$cadena_sql.="'".$valor[6]."', ";
			$cadena_sql.="'".$valor[7]."', ";
			$cadena_sql.="'".$valor[8]."', ";
			$cadena_sql.="'".$valor[9]."', ";
			$cadena_sql.="'".$valor[10]."', ";
			$cadena_sql.="'".$valor[2]."', ";
			$cadena_sql.="'".$valor[3]."', ";
			$cadena_sql.="'".$valor[4]."', ";
			$cadena_sql.="'".$valor[5]."', ";
			$cadena_sql.="'0', ";
			$cadena_sql.="'".$valor[12]."' ";			
			$cadena_sql.=")";
			break;
			
		case "insertarBorradorAcademica":
			$cadena_sql="INSERT INTO ";
			$cadena_sql.=$configuracion["prefijo"]."registrado_formacion_borrador "; 
			$cadena_sql.="( ";
			$cadena_sql.="`id_usuario`, ";
			$cadena_sql.="`id_formacion`, ";
			$cadena_sql.="`id_area_desempenno`, ";
			$cadena_sql.="`institucion`, ";
			$cadena_sql.="`ciudad`, ";
			$cadena_sql.="`region`, ";
			$cadena_sql.="`pais` ";
			$cadena_sql.=") ";
			$cadena_sql.="VALUES ";
			$cadena_sql.="( ";
			$cadena_sql.="'".$valor[0]."', ";
			$cadena_sql.="'".$valor[1]."', ";
			$cadena_sql.="'".$valor[3]."', ";
			$cadena_sql.="'".$valor[4]."', ";
			$cadena_sql.="'".$valor[5]."', ";
			$cadena_sql.="'".$valor[6]."' ";			
			$cadena_sql.=")";
			break;
			
		case "insertar":
			$cadena_sql = "INSERT INTO ";
			$cadena_sql.=$configuracion["prefijo"]."registrado ";
			$cadena_sql.="( ";
			$cadena_sql.="id_usuario, ";
			$cadena_sql.="nombre, ";
			$cadena_sql.="apellido, ";
			$cadena_sql.="correo, ";
			$cadena_sql.="telefono, ";
			$cadena_sql.="usuario, ";
			$cadena_sql.="clave, ";
			$cadena_sql.="id_tipo_documento, ";
			$cadena_sql.="identificacion, ";
			$cadena_sql.="direccion, ";
			$cadena_sql.="ciudad, ";
			$cadena_sql.="pais, ";
			$cadena_sql.="region ";
			$cadena_sql.=") ";
			$cadena_sql.="VALUES ";
			$cadena_sql.="(";
			$cadena_sql.="NULL, ";
			$cadena_sql.="'".$valor[0]."',";
			$cadena_sql.="'".$valor[1]."', ";
			$cadena_sql.="'".$valor[2]."', ";
			$cadena_sql.="'".$valor[3]."',";
			$cadena_sql.="'".$valor[4]."',";
			$cadena_sql.="'".$valor[5]."',";
			$cadena_sql.="'".$valor[6]."',";
			$cadena_sql.="'".$valor[7]."',";
			$cadena_sql.="'".$valor[8]."',";
			$cadena_sql.="'".$valor[9]."',";
			$cadena_sql.="'".$valor[10]."', ";
			$cadena_sql.="'".$valor[12]."' ";
			$cadena_sql.=")";			
			
			break;
		
		case "actualizar":
			$cadena_sql="UPDATE ";
			$cadena_sql.=$configuracion["prefijo"]."registrado "; 
			$cadena_sql.="SET "; 
			$cadena_sql.="`id_usuario`='".$valor[0]."', ";
			$cadena_sql.="`nombre`='".$valor[1]."', ";
			$cadena_sql.="`apellido`='".$valor[2]."', ";
			$cadena_sql.="`correo`='".$valor[3]."', ";
			$cadena_sql.="`telefono`='".$valor[4]."', ";
			$cadena_sql.="`usuario`='".$valor[5]."', ";
			$cadena_sql.= "`clave`='".$valor[6]."' ";
			$cadena_sql.="WHERE "; 
			$cadena_sql.="`id_usuario`='".$valor[0]."' ";
			break;
	
	}
	//echo $cadena_sql;
	return $cadena_sql;
}

function nuevoUsuario($configuracion,$acceso_db)
{
	$cadena_sql=sqlRegistroUsuario($configuracion, "usuario",$_REQUEST['usuario']);
	$acceso_db->registro_db($cadena_sql,0);
	$registro=$acceso_db->obtener_registro_db();
	$campos=$acceso_db->obtener_conteo_db();
	$identificador=time();
	
	//Si el usuario ya existe
	if($campos>0)
	{
		
		$cadena_sql=sqlRegistroUsuario($configuracion, "correo",$_REQUEST['correo']);
		$acceso_db->registro_db($cadena_sql,0);
		$registro=$acceso_db->obtener_registro_db();
		$campos=$acceso_db->obtener_conteo_db();
		if($campos>0)
		{
			unset ($_REQUEST["correo"]);
		}
		unset ($_REQUEST["action"]);
		
		$valor[0]=$_REQUEST['nombre'];
		$valor[1]=$_REQUEST['apellido'];
		if(isset($_REQUEST['correo']))
		{
			$valor[2]=$_REQUEST['correo'];
		}
		else
		{
			$valor[2]="'Verificar correo', ";
		}
		$valor[3]=$_REQUEST['telefono'];
		$valor[4]="Verificar Usuario";
		$valor[5]=md5($_REQUEST['clave']);
		$valor[6]=$_REQUEST['id_tipo_documento'];
		$valor[7]=$_REQUEST['identificacion'];
		$valor[8]=$_REQUEST['direccion'];
		$valor[9]=$_REQUEST['ciudad'];
		$valor[10]=$_REQUEST['pais'];
		$valor[11]=$identificador;
		
		$cadena_sql=sqlRegistroUsuario($configuracion, "insertarBorrador",$valor);
		
		
		$resultado=$acceso_db->ejecutar_acceso_db($cadena_sql); 
		
		if($resultado==TRUE)
		{
			redireccionarUsuario($configuracion,"corregirUsuario");
		}	
	}
	else
	{
		$cadena_sql=sqlRegistroUsuario($configuracion, "correo",$_REQUEST["correo"]);
		$acceso_db->registro_db($cadena_sql,0);
		$registro=$acceso_db->obtener_registro_db();
		$campos=$acceso_db->obtener_conteo_db();
		if($campos>0)
		{
			unset ($_REQUEST["action"]);
			unset ($_REQUEST["correo"]);
			
			$valor[0]=$_REQUEST['nombre'];
			$valor[1]=$_REQUEST['apellido'];
			$valor[2]="'Verificar correo', ";
			$valor[3]=$_REQUEST['telefono'];
			$valor[4]=$_REQUEST['usuario'];
			$valor[5]=md5($_REQUEST['clave']);
			$valor[6]=$_REQUEST['id_tipo_documento'];
			$valor[7]=$_REQUEST['identificacion'];
			$valor[8]=$_REQUEST['direccion'];
			$valor[9]=$_REQUEST['ciudad'];
			$valor[10]=$_REQUEST['pais'];
			$valor[11]=$identificador;
			$valor[12]=$_REQUEST['region'];
						
			$cadena_sql=sqlRegistroUsuario($configuracion, "insertarBorrador",$identificador);
		
			$resultado=$acceso_db->ejecutar_acceso_db($cadena_sql); 
			
			if($resultado==TRUE)
			{
				unset($valor);
				$valor[0]=$identificador;
				$valor[1]=$_REQUEST['formacion'];
				$valor[2]=$_REQUEST['areaDesempenno'];
				$valor[3]=$_REQUEST['institucion'];
				$valor[4]=$_REQUEST['paisFormacion'];
				$valor[5]=$_REQUEST['regionFormacion'];
				$valor[6]=$_REQUEST['ciudadFormacion'];
				
				$cadena_sql=sqlRegistroUsuario($configuracion, "insertarBorradorAcademica",$identificador);		
				$resultado=$acceso_db->ejecutar_acceso_db($cadena_sql); 
				
				if($resultado==TRUE)
				{
					redireccionarUsuario($configuracion, "corregirUsuario");
				}
				
			}	
			
			unset($valor);
		}
		unset($valor);
		//Valores a ingresar
		$valor[0]=$_REQUEST['nombre'];
		$valor[1]=$_REQUEST['apellido'];
		$valor[2]=$_REQUEST['correo'];
		$valor[3]=$_REQUEST['telefono'];
		$valor[4]=$_REQUEST['usuario'];
		$valor[5]=md5($_REQUEST['clave']);
		$valor[6]=$_REQUEST['id_tipo_documento'];
		$valor[7]=$_REQUEST['identificacion'];
		$valor[8]=$_REQUEST['direccion'];
		$valor[9]=$_REQUEST['ciudad'];
		$valor[10]=$_REQUEST['pais'];
		$valor[11]=$identificador;
		$valor[12]=$_REQUEST['region'];
		
		$cadena_sql=sqlRegistroUsuario($configuracion, "insertarBorrador",$valor);
		//exit;
		$resultado=$acceso_db->ejecutar_acceso_db($cadena_sql);
		 		
		unset($valor);
		$valor[0]=$identificador;
		$valor[1]=$_REQUEST['formacion'];
		$valor[2]=$_REQUEST['areaDesempenno'];
		$valor[3]=$_REQUEST['institucion'];
		$valor[4]=$_REQUEST['paisFormacion'];
		$valor[5]=$_REQUEST['regionFormacion'];
		$valor[6]=$_REQUEST['ciudadFormacion'];
		
		$cadena_sql=sqlRegistroUsuario($configuracion, "insertarBorradorAcademica",$identificador);		
		
		if($resultado==TRUE)
		{
			if(!isset($_REQUEST["admin"]))
			{
				//enviar_correo($configuracion);
				reset($_REQUEST);
				while(list($clave,$valor)=each($_REQUEST))
				{
					unset($_REQUEST[$clave]);
						
				}
				
				redireccionarUsuario($configuracion, "confirmacion",$identificador);
				
			}
			else
			{
				
				redireccionarUsuario($configuracion,"administracion");		
				
			}
		}
		else
		{
			exit;
		}
				
	}

}

function redireccionarUsuario($configuracion, $opcion, $valor="")
{
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
	unset($_REQUEST['action']);
	$cripto=new encriptar();
	$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
	
	switch($opcion)
	{
		case "administracion":
			$variable="pagina=admin_usuario";
			$variable.="&accion=1";
			$variable.="&hoja=0";
			break;
			
		case "confirmacion":
			$variable="pagina=confirmacionRegistro";
			$variable.="&opcion=confirmar";
			$variable.="&identificador=".$valor;
			break;
			
		case "corregirUsuario":			
			$variable="pagina=registro_usuario";
			$variable.="&opcion=corregir";
			$variable.="&identificador=".$valor;
			break;
			
		case "indice":
			$variable="pagina=index";
			$variable.="&registro_exito=1";
			break;
		
		
		
	}
	
	$variable=$cripto->codificar_url($variable,$configuracion);
	echo "<script>location.replace('".$indice.$variable."')</script>"; 
	exit();
}

function usuarioAntiguo()
{
	$valor=$_REQUEST['id_usuario'];
	$cadena_sql=sqlRegistroUsuario($configuracion, "rescatarUsuario",$valor);	
	$acceso_db->registro_db($cadena_sql,0);
	$registro=$acceso_db->obtener_registro_db();
	$campos=$acceso_db->obtener_conteo_db();
	if($campos>0)
	{
	
		//Verificar nombre de usuario y correo
		if($registro[0][3]!=$_REQUEST["correo"])
		{
		
		
		}
		else
		{
			$correo=$_REQUEST["correo"];
		}
		
		if($registro[0][5]!=$_REQUEST["usuario"])
		{
		
		
		}
		else
		{
			$usuario=$_REQUEST["usuario"];
		}
		
		//
		unset($valor);
		$valor[0]=$registro[0][0];
		$valor[1]=$_REQUEST['nombre'];
		$valor[2]=$_REQUEST['apellido'];
		$valor[3]=$correo;
		$valor[4]=$_REQUEST['telefono'];
		$valor[5]=$usuario;
		
		if($_REQUEST["clave"]==$cripto->codificar("la_clave",$configuracion))
		{
			$valor[6]=$registro[0][6];
		}
		else
		{
			$valor[6]=md5($_REQUEST['clave']);
		}
		
		$cadena_sql=sqlRegistroUsuario($configuracion, "actualizar",$valor);			
		
		$resultado=$acceso_db->ejecutar_acceso_db($cadena_sql);
		
		$logger=$acceso_db->logger($configuracion,$_REQUEST["id_usuario"],"Actualizacion datos de usuario No ".$_REQUEST["id_usuario"]);
		unset($_REQUEST['action']);
			
			
		if($resultado==TRUE)
		{
			if(!isset($_REQUEST["admin"]))
			{
				enviar_correo($configuracion);
				reset($_REQUEST);
				while(list($clave,$valor)=each($_REQUEST))
				{
					unset($_REQUEST[$clave]);
						
				}
				
				redireccionarUsuario($configuracion, "indice");
				
			}
			else
			{
				redireccionarUsuario($configuracion,"administracion");
			}
		}
		else
		{
			
		}
						
						
	}
	else
	{
		echo "<h1>Error de Acceso</h1>Por favor contacte con el administrador del sistema.";				
	}
}
	
?>
