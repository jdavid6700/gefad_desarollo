<?
/*
 ############################################################################
#    UNIVERSIDAD DISTRITAL Francisco Jose de Caldas                        #
#    Desarrollo Por:                                                       #
#    Paulo Cesar Coronado
#    Copyright: Vea el archivo EULA.txt que viene con la distribucion      #
############################################################################
*//*--------------------------------------------------------------------------------------------------------------------------
@ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/
/*--------------------------------------------------------------------------------------------------------------------------
 * @name          perfil.class.php
* @author        Maritza Callejas
* @revision      Última revisión 20 de enero de 2010
/*--------------------------------------------------------------------------------------------------------------------------
* @subpackage
* @package		clase
* @copyright    	Universidad Distrital Francisco Jose de Caldas
* @version      		0.0.0.1
* @author			Maritza Callejas
* @author			Oficina Asesora de Sistemas
* @link			N/D
* @description  	Clase para gestionar los perfiles en los bloques y opciones,
*                       de igual forma las opciones que los usuarios utilizan.
*
/*--------------------------------------------------------------------------------------------------------------------------*/

/*---------------------------------------------------------------------------------------
 |				Control Versiones				    	|
----------------------------------------------------------------------------------------
| fecha      |        Autor            | version     |              Detalle            |
----------------------------------------------------------------------------------------
| 24/02/2011 | Maritza Callejas C.  	| 0.0.0.3     |Se actualizo funcion de ultima   |
|            |                   	|             |fecha de carga                   |
----------------------------------------------------------------------------------------
*/
class funcionCarga
{

	function funcionCarga()
	{


	}

	function lista_tablas_bd($configuracion, $conexion, $variable){

		$cadena_sql = "SHOW TABLE STATUS ";
		$cadena_sql.= "FROM ";
		$cadena_sql.= $variable[0];//base de datos
		if($variable[1]){
			$cadena_sql.= " WHERE Name";
			$cadena_sql.= " LIKE '%".$variable[1]."%'";//cadena a buscar
		}
		$tablas = $conexion->ejecutarAcceso($cadena_sql,"busqueda");
		/*			['Name']
			['Data_length']
		['Index_length']
		['Total']
		['Rows']
		['Avg_row_length']
		*/

		return $tablas;
	}//fin funcion lista tablas

	function campos_estructura_tabla($configuracion, $conexion, $variable ){

		$cadena_sql = "SHOW FIELDS ";
		$cadena_sql.= "FROM ";
		$cadena_sql.= $variable[0];
		if($variable[1]){
			$cadena_sql.= " WHERE Field ";
			$cadena_sql.= " LIKE '%".$variable[1]."%'";//cadena a buscar
		}
		$campos = $conexion->ejecutarAcceso($cadena_sql,"busqueda");

		return $campos;
	}//fin funcion campos_estructura_tabla

	function consultar_fecha_ultima_carga($configuracion, $conexion, $variable){
		//$datos[0] = $variable;//base de datos
		//$datos[1] = "LOG_CARGA";
		//$tabla_datamart = $this->lista_tablas_bd($configuracion, $conexion, $datos);
		//echo "<br><br>tabla ".$tabla_datamart[0][0];

		$variable_tabla[0]="LOG_CARGAS";
		$variable_tabla[1]="TIE_CARGA";
		$campos_tabla = $this->campos_estructura_tabla($configuracion, $conexion, $variable_tabla );
		//consultamos la ultima fecha de carga para el datamart
		if ($campos_tabla){
			$cadena_sql = "SELECT ";
			$cadena_sql.= $campos_tabla[0][0]." ";
			$cadena_sql.= "FROM ";
			$cadena_sql.= $variable_tabla[0]." ";
			if($variable[1]){
				$cadena_sql.= " WHERE ";
				$cadena_sql.= " LOG_PROCESO LIKE '%".$variable[1]."%' ";

			}
			$cadena_sql.= "ORDER BY ";
			$cadena_sql.= $campos_tabla[0][0]." ";
			$cadena_sql.= "DESC ";
			$cadena_sql.= "LIMIT 0,3 ";
			$fecha_carga = $conexion->ejecutarAcceso($cadena_sql,"busqueda");

			//echo "<br><br><br><br>SQL ".$cadena_sql;
		}
		if ($fecha_carga ){
			$annio = substr($fecha_carga[0][0], 0, 4);
			$mes = substr($fecha_carga[0][0], 4, 2);
			$dia = substr($fecha_carga[0][0], 6, 2);
			$fecha_carga[0][0] = $annio."-".$mes."-".$dia;
			return $fecha_carga[0][0];
		}
	}
	/*
	 function consultar_fecha_ultima_carga($configuracion, $conexion, $variable){
	$datos[0] = $variable;//base de datos
	$datos[1] = "DMH";
	$tabla_datamart = $this->lista_tablas_bd($configuracion, $conexion, $datos);
	//echo "<br>tabla ".$tabla_datamart[0][0];

	$variable_tabla[0]=$tabla_datamart[0][0];
	$variable_tabla[1]="TIE_CARGA";
	$campos_tabla = $this->campos_estructura_tabla($configuracion, $conexion, $variable_tabla );
	//consultamos la ultima fecha de carga para el datamart
	if ($campos_tabla){
	$cadena_sql = "SELECT ";
	$cadena_sql.= $campos_tabla[0][0]." ";
	$cadena_sql.= "FROM ";
	$cadena_sql.= $tabla_datamart[0][0]." ";
	$cadena_sql.= "ORDER BY ";
	$cadena_sql.= $campos_tabla[0][0]." ";
	$cadena_sql.= "DESC ";
	$cadena_sql.= "LIMIT 0,3 ";
	$fecha_carga = $conexion->ejecutarAcceso($cadena_sql,"busqueda");

	//echo "<br>SQL ".$cadena_sql;
	}
	if ($fecha_carga ){
	$annio = substr($fecha_carga[0][0], 0, 4);
	$mes = substr($fecha_carga[0][0], 4, 2);
	$dia = substr($fecha_carga[0][0], 6, 2);
	$fecha_carga[0][0] = $annio."-".$mes."-".$dia;
	return $fecha_carga[0][0];
	}else{
	//return 0;
	$variable_tabla[0]=$tabla_datamart[1][0];
	$variable_tabla[1]="TIE_CARGA";
	$campos_tabla = $this->campos_estructura_tabla($configuracion, $conexion, $variable_tabla );
	//consultamos la ultima fecha de carga para el datamart
	if ($campos_tabla){
	$cadena_sql2 = "SELECT ";
	$cadena_sql2.= $campos_tabla[0][0]." ";
	$cadena_sql2.= "FROM ";
	$cadena_sql2.= $tabla_datamart[1][0]." ";
	$cadena_sql2.= "ORDER BY ";
	$cadena_sql2.= $campos_tabla[0][0]." ";
	$cadena_sql2.= "DESC ";
	$cadena_sql2.= "LIMIT 0,3 ";
	$fecha_carga2 = $conexion->ejecutarAcceso($cadena_sql2,"busqueda");

	//  echo "<br>SQL ".$cadena_sql2;
	}
	if ($fecha_carga2 ){
	$annio = substr($fecha_carga2[0][0], 0, 4);
	$mes = substr($fecha_carga2[0][0], 4, 2);
	$dia = substr($fecha_carga2[0][0], 6, 2);
	$fecha_carga2[0][0] = $annio."-".$mes."-".$dia;
	return $fecha_carga2[0][0];
	}else{
	return 0;
	}
	}
	}
	*/
	function compara_fechas($fecha1, $fecha2){
		list($annio,$mes,$dia)=explode('-',$fecha1);
		list($annio2,$mes2,$dia2)=explode('-',$fecha2);
		$diferencia = mktime(0,0,0,$mes,$dia,$annio) - mktime(0,0,0,$mes2,$dia2,$annio2);
		return ($diferencia);

	}
}//Fin de la clase

?>
