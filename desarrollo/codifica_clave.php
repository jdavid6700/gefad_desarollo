<?
/*
############################################################################
#    UNIVERSIDAD DISTRITAL Francisco Jose de Caldas                        #
#    Copyright: Vea el archivo LICENCIA.txt que viene con la distribucion  #
############################################################################
*/
/***************************************************************************
* @name          codifica_clave.class.php 
* @author        Jairo Lavado
* @revision      Última revisión 19 de Marzo de 2013
****************************************************************************
* @subpackage   
* @package	clase
* @copyright    
* @version      0.2
* @author       Jairo Lavado
* @link		
* @description  
*
******************************************************************************/

require_once("../clase/encriptar.class.php");
$crypto=new encriptar();
$semilla="";

echo "<br>CODIFICACION DE CLAVE<BR> ";
$usuario='PRUEBASCROJASV';
$pwd='PRUEBASCROJASV2016';		
                
echo "<br>usuario: ".$usuario." => ".$crypto->codificar_variable($usuario,  $semilla);
echo "<br>clave: ".$pwd." => ".$crypto->codificar_variable($pwd,  $semilla);	


echo "<br>CODIFICACION DE CLAVE MySQL<BR> ";
$usuario='';
$pwd='foxoni4053my';		
                
echo "<br>usuario: ".$usuario." => ".$crypto->codificar_variable($usuario,  $semilla);
echo "<br>clave: ".$pwd." => ".$crypto->codificar_variable($pwd,  $semilla);	

echo "<br>CODIFICACION DE CLAVE Oracle<BR> ";
$usuario='';
$pwd='foxoni4053ora';

echo "<br>usuario: ".$usuario." => ".$crypto->codificar_variable($usuario,  $semilla);
echo "<br>clave: ".$pwd." => ".$crypto->codificar_variable($pwd,  $semilla);

echo "<br>CODIFICACION DE CLAVE Postgres<BR> ";
$usuario='';
$pwd='foxoni4053pg';

echo "<br>usuario: ".$usuario." => ".$crypto->codificar_variable($usuario,  $semilla);
echo "<br>clave: ".$pwd." => ".$crypto->codificar_variable($pwd,  $semilla);

echo "<br><br>DECODIFICACION DE CLAVE<BR> ";
$usuario2='';
$pwd2='OgC5s2tH3FF8GIMdZP-H3fx8J4KFa2Gk';	


echo "<br>usuario: ".$usuario2." => ".$crypto->decodificar_variable($usuario2,  $semilla);
echo "<br>clave: ".$pwd2." => ".$crypto->decodificar_variable($pwd2,  $semilla);


echo "<br><br>DECODIFICACION DE CLAVE<BR> ";
$usuario2='LgNNFz-mzVEjF9CQWCwS7A';
$pwd2='';


echo "<br>usuario: ".$usuario2." => ".$crypto->decodificar_variable($usuario2,  $semilla);
echo "<br>clave: ".$pwd2." => ".$crypto->decodificar_variable($pwd2,  $semilla);	


echo "<br><br>DECODIFICACION DE CLAVE<BR> ";
$usuario2='2AAeuMxf51Ti5PMdembvXIEh-7m1';
$pwd2='RgBgB69f51QlZVzFi3V2HjvGzY7vuH9rptwXEpcDKmkR';


echo "<br>usuario: ".$usuario2." => ".$crypto->decodificar_variable($usuario2,  $semilla);
echo "<br>clave: ".$pwd2." => ".$crypto->decodificar_variable($pwd2,  $semilla);


echo "<br><br>DECODIFICACION DE CLAVE<BR> ";
$usuario2='_wD6cgN5cVLX-GMR9ZE7vw';
$pwd2='';


echo "<br>usuario: ".$usuario2." => ".$crypto->decodificar_variable($usuario2,  $semilla);
echo "<br>clave: ".$pwd2." => ".$crypto->decodificar_variable($pwd2,  $semilla);
          

?>
