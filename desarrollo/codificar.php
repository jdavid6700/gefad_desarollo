<?php
// +----------------------------------------------------------------------
// | PHP Source                                                           
// +----------------------------------------------------------------------
// | Copyright (C) 2013 by Jairo Lavado 
// +----------------------------------------------------------------------
// |
// | Copyright: See COPYING file that comes with this distribution
// +----------------------------------------------------------------------
//
	require_once("../clase/encriptar.class.php");
	$crypto=new encriptar();
	
	echo $crypto->codificar("mysql")."<br>";//Motor
	echo $crypto->codificar("pruebasfuncionarios.c8an9arwoaua.us-east-1.rds.amazonaws.com")."<br>";//Servidor
	echo $crypto->codificar("frame_gefad")."<br>";//DB;
	echo $crypto->codificar("frame_gefad")."<br>";//Usuario
	echo $crypto->codificar("foxoni4053my")."<br>";//Clave
	echo $crypto->codificar("gestion_")."<br>";//Prefijo
	
	/*
	echo $crypto->codificar("mysql")."<br>";//Motor
        echo $crypto->codificar("10.20.0.127")."<br>";//Servidor
        echo $crypto->codificar("frame_gefad")."<br>";//DB;
        echo $crypto->codificar("frame_gefad")."<br>";//Usuario
        echo $crypto->codificar("admin_gefad2013")."<br>";//Clave
        echo $crypto->codificar("gestion_")."<br>";//Prefijo
      */  
	/*
	 	echo $crypto->codificar("mysql")."<br>";//Motor
        echo $crypto->codificar("condorfuncionariosprod.c8an9arwoaua.us-east-1.rds.amazonaws.com")."<br>";//Servidor
        echo $crypto->codificar("frame_gefad")."<br>";//DB;
        echo $crypto->codificar("frame_gefad")."<br>";//Usuario
        echo $crypto->codificar("admin_gefad2013")."<br>";//Clave
        echo $crypto->codificar("gestion_")."<br>";//Prefijo
	  
	 */
?>
