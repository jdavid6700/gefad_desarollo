<?php

/* --------------------------------------------------------------------------------------------------------------------------
 @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
--------------------------------------------------------------------------------------------------------------------------- */

if (!isset($GLOBALS["autorizado"])) {
	include("../index.php");
	exit;
}

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/sql.class.php");

class sql_bannerFlotante extends sql { //@ Método que crea las sentencias sql para el modulo admin_noticias

	function cadena_sql($tipo, $variable="") {

		switch ($tipo) {

			 
			default:
				$cadena_sql ="";
				break;

		}#Cierre de switch
		return $cadena_sql;
	}
	#Cierre de funcion cadena_sql
}
#Cierre de clase
?>