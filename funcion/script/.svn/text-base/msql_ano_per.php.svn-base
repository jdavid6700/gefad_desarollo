<?php
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);

$per_consul = "SELECT ape_ano, ape_per FROM acasperi WHERE ape_estado='A'";
$row=$conexion->ejecutarSQL($configuracion,$accesoOracle,$per_consul,"busqueda");
$ano = $row[0][0];
$per = $row[0][1];
?>