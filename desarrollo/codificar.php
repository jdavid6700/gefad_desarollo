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
$crypto = new encriptar();

echo $crypto->codificar("mysql") . "<br>"; //Motor
echo $crypto->codificar("10.20.2.28") . "<br>"; //Servidor
echo $crypto->codificar("frame_gefad") . "<br>"; //DB;
echo $crypto->codificar("root") . "<br>"; //Usuario
echo $crypto->codificar("sistemasoas") . "<br>"; //Clave
echo $crypto->codificar("gestion_") . "<br>"; //Prefijo

?>
