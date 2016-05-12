<?
//===================NOTAS
//La variable no_pagina hace que la pagina no se cargue.
//Trozos de código importantes
//Conectarse a una base de datos diferente a la por defecto
	$conexion=new dbConexion($configuracion);
	$acceso_db=$conexion->recursodb($configuracion,"oracle");
	$enlace=$acceso_db->conectar_db();
	if($enlace)
	{
	
	}
//Recorrer una matriz
	
	foreach ($_REQUEST as $key => $value) 
	{
	
		echo $key."=>".$value."<br>";
	
	}
	



?>