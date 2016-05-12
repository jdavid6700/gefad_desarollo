<?php
require_once('dir_relativo.cfg');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);

$qry_sede = "SELECT SED_NOMBRE
  		FROM GESEDE
 		WHERE SED_COD = $sedcod";
 		
$resul_sede=$conexion->ejecutarSQL($configuracion,$accesoOracle,$qry_sede,"busqueda");
$nom_sede = $resul_sede[0][0];
?>