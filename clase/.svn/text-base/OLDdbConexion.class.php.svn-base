<?
/*
############################################################################
#    UNIVERSIDAD DISTRITAL Francisco Jose de Caldas                        #
#    Copyright: Vea el archivo LICENCIA.txt que viene con la distribucion  #
############################################################################
*/
/***************************************************************************
* @name          dbms.class.php 
* @author        Paulo Cesar Coronado
* @revision      Última revisión 26 de junio de 2005
****************************************************************************
* @subpackage   
* @package	clase
* @copyright    
* @version      0.2
* @author      	Paulo Cesar Coronado
* @link		http://acreditacion.udistrital.edu.co
* @description  Esta clase esta disennada para administrar todas las tareas 
*               relacionadas con la base de datos.
*
******************************************************************************/


/*****************************************************************************
*Atributos
*
*@access private
*@param  $servidor
*		URL del servidor de bases de datos. 
*@param  $db
*		Nombre de la base de datos
*@param  $usuario
*		Usuario de la base de datos
*@param  $clave
*		Clave de acceso al servidor de bases de datos
*@param  $enlace
*		Identificador del enlace a la base de datos
*@param  $dbms
*		Nombre del DBMS, por defecto se tiene MySQL
*@param  $cadena_sql
*		Clausula SQL a ejecutar
*@param  $error
*		Mensaje de error devuelto por el DBMS
*@param  $numero
*		Número de registros a devolver en una consulta
*@param  $conteo
*		Número de registros que existen en una consulta
*@param  $registro
*		Matriz para almacenar los resultados de una búsqueda
*@param  $campo
*		Número de campos que devuelve una consulta
*TO DO    	Implementar la funcionalidad en DBMS diferentes a MySQL		
*******************************************************************************/

/*****************************************************************************
*Métodos
*
*@access public
*
     * @name db_admin
*	 Constructor. Define los valores por defecto 
     * @name especificar_db
*	 Especifica a través de código el nombre de la base de datos
     * @name especificar_usuario
*	 Especifica a través de código el nombre del usuario de la DB
     * @name especificar_clave
*	 Especifica a través de código la clave de acceso al servidor de DB
     * @name especificar_servidor
*	 Especificar a través de código la URL del servidor de DB
     * @name especificar_dbms
*	 Especificar a través de código el nombre del DBMS
     * @name especificar_enlace
*	 Especificar el recurso de enlace a la DBMS
     * @name conectar_db
*	 Conecta a un DBMS
     * @name probar_conexion
*	 Con la cual se realizan acciones que prueban la validez de la conexión
     * @name desconectar_db
*	 Libera la conexion al DBMS
     * @name ejecutar_acceso_db
*	 Ejecuta clausulas SQL de tipo INSERT, UPDATE, DELETE
     * @name obtener_error
*	 Devuelve el mensaje de error generado por el DBMS
     * @name obtener_conteo_dbregistro_db
*	 Devuelve el número de registros que tiene una consulta
     * @name registro_db
*	 Ejecuta clausulas SQL de tipo SELECT
     * @name obtener_registro_db
*	 Devuelve el resultado de una consulta como una matriz bidimensional
     * @name obtener_error
*	 Realiza una consulta SQL y la guarda en una matriz bidimensional
*
******************************************************************************/

class dbConexion
{
	public function __construct()
	{
		
	}
	
	public function recursodb($configuracion,$nombre="")
	{
		include_once("dbms.class.php");
		include_once("mysql.class.php");
		include_once("oci8.class.php");
		include_once("pgsql.class.php");
		//include_once("mysqli.class.php");		
			
		$acceso_db=new dbms($configuracion);
		$enlace=$acceso_db->conectar_db();
		
		if (is_resource($enlace))
		{
			$cadena_sql="SELECT "; 
			$cadena_sql.="`nombre`, ";			 
			$cadena_sql.="`servidor`, "; 
			$cadena_sql.="`puerto`, "; 
			$cadena_sql.="`ssl`, "; 
			$cadena_sql.="`db`, "; 
			$cadena_sql.="`usuario`, "; 
			$cadena_sql.="`password`, ";
			$cadena_sql.="`dbms` "; 
			$cadena_sql.="FROM "; 
			$cadena_sql.=$configuracion["prefijo"]."dbms ";
			$cadena_sql.="WHERE "; 
			$cadena_sql.="nombre='".$nombre."'";  
			
			$acceso_db->registro_db($cadena_sql,0);
			$registro=$acceso_db->obtener_registro_db();
			if(is_array($registro))
			{
				$dbms=$registro[0][7];
				return new $dbms($registro);
			}
			else
			{
				throw new Exception('Lamentablemente esta instancia no se encuentra registrada en el sistema.');
			}
		}
			
		
		
	}
	
}//Fin de la clase db_admin

?>
