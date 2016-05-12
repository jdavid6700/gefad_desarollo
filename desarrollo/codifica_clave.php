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
$usuario='SICGEFAD';
$pwd='s1cg3f@d2013';		
                
echo "<br>usuario: ".$usuario." => ".$crypto->codificar_variable($usuario,  $semilla);
echo "<br>clave: ".$pwd." => ".$crypto->codificar_variable($pwd,  $semilla);	


echo "<br>CODIFICACION DE CLAVE<BR> ";
$usuario='postgres';
$pwd='cu0t4sP4rt3s2013=';		
                
echo "<br>usuario: ".$usuario." => ".$crypto->codificar_variable($usuario,  $semilla);
echo "<br>clave: ".$pwd." => ".$crypto->codificar_variable($pwd,  $semilla);	


echo "<br><br>DECODIFICACION DE CLAVE<BR> ";
$usuario2='4QON2E68eVHH9mzaiqVBMg';
$pwd2='4gNQjk68eVErQchONtU';		

echo "<br>usuario: ".$usuario2." => ".$crypto->decodificar_variable($usuario2,  $semilla);
echo "<br>clave: ".$pwd2." => ".$crypto->decodificar_variable($pwd2,  $semilla);	
          

?>
