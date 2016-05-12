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

class sql_mensaje extends sql
{	//@ Método que crea las sentencias sql para el modulo admin_noticias
function cadena_sql($configuracion,$tipo,$variable)
{
	switch($tipo)
	{
			
		case "buscar_id":
			$this->cadena_sql="SELECT ";
			$this->cadena_sql.="`id_diccionario`, ";
			$this->cadena_sql.="`id_proyecto`, ";
			$this->cadena_sql.="`nombre`, ";
			$this->cadena_sql.="`descripcion`, ";
			$this->cadena_sql.="`fecha` ";
			$this->cadena_sql.="FROM ";
			$this->cadena_sql.=$configuracion["prefijo"]."diccionario ";
			$this->cadena_sql.="WHERE ";
			$this->cadena_sql.="`id_diccionario`= ";
			$this->cadena_sql.=$variable;
			break;

				
		case "buscar_obj":
			$this->cadena_sql="SELECT ";
			$this->cadena_sql.="`id_objeto`, ";
			$this->cadena_sql.="`nombre`, ";
			$this->cadena_sql.="`etiqueta`, ";
			$this->cadena_sql.="`descripcion`, ";
			$this->cadena_sql.="`tipo_dato` ";
			$this->cadena_sql.="FROM ";
			$this->cadena_sql.=$configuracion["prefijo"]."objeto ";
			$this->cadena_sql.="WHERE ";
			$this->cadena_sql.="`id_diccionario`= ";
			$this->cadena_sql.=$variable[0];
			$this->cadena_sql.=" AND ";
			$this->cadena_sql.="`nivel`=";
			$this->cadena_sql.=$variable[1];
			$this->cadena_sql.=" ORDER BY `nombre` ";
			break;
				
				

		case "select_obj":
			$this->cadena_sql="SELECT ";
			$this->cadena_sql.="REL.id_objeto2, ";
			$this->cadena_sql.="OBJ.nombre, ";
			$this->cadena_sql.="OBJ.tipo_dato, ";
			$this->cadena_sql.="OBJ.nivel, ";
			$this->cadena_sql.="OBJ.descripcion ";
			$this->cadena_sql.="FROM ";
			$this->cadena_sql.=$configuracion["prefijo"]."relacion_objeto AS REL ";
			$this->cadena_sql.="INNER JOIN ";
			$this->cadena_sql.=$configuracion["prefijo"]."objeto AS OBJ ";
			$this->cadena_sql.="ON REL.id_objeto2=OBJ.id_objeto ";
			$this->cadena_sql.="WHERE ";
			$this->cadena_sql.="REL.id_objeto1=";
			$this->cadena_sql.=$variable;
			$this->cadena_sql.=" AND ";
			$this->cadena_sql.="REL.tipo=1";
			$this->cadena_sql.=" ORDER BY OBJ.nivel,OBJ.nombre DESC";
			break;
				
	}
		
	return $this->cadena_sql;

}
}
?>
