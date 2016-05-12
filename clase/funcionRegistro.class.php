<?
/*--------------------------------------------------------------------------------------------------------------------------
 @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;
}

interface  funcionRegistro
{

	public function nuevoRegistro($configuracion,$tema,$acceso_db);
	public function mostrarRegistro($configuracion,$tema,$id, $acceso_db, $formulario);
	public function editarRegistro($configuracion,$tema,$id,$acceso_db,$formulario);
	public function corregirRegistro();

}


//Fin de la interfaz

?>
