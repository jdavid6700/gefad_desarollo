<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;		
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sql.class.php");

class sql_adminGeneral extends sql
{
	function cadena_sql($configuracion,$conexion, $opcion,$variable="")
	{
		
		switch($opcion)
		{	
			case "busqueda_usuario_xnombre":
								
				$cadena_sql= "SELECT ";
				$cadena_sql.= "id_usuario ID_US, ";
				$cadena_sql.= "nombre  NOMBRE, ";
				$cadena_sql.= "apellido APELLIDO, ";
				$cadena_sql.= "correo MAIL, ";
				$cadena_sql.= "telefono TEL, ";
				$cadena_sql.= "usuario NICK, ";
				$cadena_sql.= "celular CEL, ";
				$cadena_sql.= "identificacion IDENT ";
				$cadena_sql.= "FROM ";
				$cadena_sql.= $configuracion["prefijo"]."registrado ";
				$cadena_sql.= "WHERE usuario = '".$variable."'";
			break;	
	
                           
                            
                      case "usuario":
				$cadena_sql= "SELECT ";
				$cadena_sql.= "id_usuario ID_US, ";
				$cadena_sql.= "nombre NOMBRE, ";
				$cadena_sql.= "apellido APELLIDO, ";
				$cadena_sql.= "correo MAIL, ";
				$cadena_sql.= "telefono1 TEL, ";
                                $cadena_sql.= "extensiones1 EXT, ";
				$cadena_sql.= "usuario NICK, ";
				$cadena_sql.= "celular CEL, ";
				$cadena_sql.= "identificacion IDENT, ";
				$cadena_sql.= "clave PASS ";
				$cadena_sql.= "FROM ";
				$cadena_sql.= $configuracion["prefijo"]."registrado ";
				$cadena_sql.= "WHERE ";
				$cadena_sql.= "id_usuario = ";
				$cadena_sql.= $variable;
                               
				break;	    
				
			case "editar_usuario":
				$cadena_sql  = "UPDATE "; 
				$cadena_sql .= $configuracion["prefijo"]."registrado "; 
				$cadena_sql .= "SET " ; 
				$cadena_sql .= "`nombre`='".$variable[1]."', ";
				$cadena_sql .= "`apellido`='".$variable[2]."', ";
				$cadena_sql .= "`correo`='".$variable[5]."', ";
				$cadena_sql .= "`telefono`='".$variable[4]."', ";
				$cadena_sql .= "`celular`='".$variable[6]."', ";
				$cadena_sql .= "`identificacion`='".$variable[3]."'";
				$cadena_sql .= " WHERE ";
				$cadena_sql .= "`id_usuario`= ";
				$cadena_sql .= $variable[0];
				break;

			case "editar_contrasena":
				$cadena_sql  = "UPDATE "; 
				$cadena_sql .= $configuracion["prefijo"]."registrado "; 
				$cadena_sql .= "SET " ; 
				$cadena_sql .= "`clave`='".$variable[1]."' ";
				$cadena_sql .= " WHERE ";
				$cadena_sql .= "`id_usuario`= ";
				$cadena_sql .= $variable[0];
				break;

			case "pagina_subsistema":
				$cadena_sql  = "SELECT "; 
				$cadena_sql .= "SUB.id_subsistema, SUB.nombre, PAG.id_pagina, PAG.nombre ";
				$cadena_sql .= "FROM ";
				$cadena_sql .= $configuracion["prefijo"]."registrado_subsistema AS REGS ";
				$cadena_sql .= "INNER JOIN ".$configuracion["prefijo"]."subsistema AS SUB ";
				$cadena_sql .= "ON REGS.id_subsistema = SUB.id_subsistema ";
				$cadena_sql .= "INNER JOIN ".$configuracion["prefijo"]."pagina AS PAG ";
				$cadena_sql .= "ON  PAG.id_pagina = SUB.id_pagina ";
				$cadena_sql .= "WHERE REGS.id_usuario = ".$variable;

				break;

			default:
				$cadena_sql="";
				break;
		}//fin switch
		return $cadena_sql;
	}// fin funcion cadena_sql
	
	
}//fin clase sql_adminGeneral
?>
