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


echo "<br><br>DECODIFICACION DE CLAVE CUOTAS_PARTES<BR> ";
$usuario2='OAAQS2tH3FElkrKO4nG8yvexuZY';
$pwd2='QwB0uy05mVc9ryGWyZlTjNeIgoCgpY7PrA';

echo "<br>usuario: ".$usuario2." => ".$crypto->decodificar_variable($usuario2,  $semilla);
echo "<br>clave: ".$pwd2." => ".$crypto->decodificar_variable($pwd2,  $semilla);



echo "<br><br>DECODIFICACION DE CLAVE PROD SIC<BR> ";
$usuario2='FwO2VwLLSFFj3Ucw0EM';
$pwd2='GQN1QwLLSFEzRg3wMZQ';	



echo "<br>usuario: ".$usuario2." => ".$crypto->decodificar_variable($usuario2,  $semilla);
echo "<br>clave: ".$pwd2." => ".$crypto->decodificar_variable($pwd2,  $semilla);



echo "<br><br>DECODIFICACION DE CLAVE FRAME<BR> ";
$usuario2='pAGFfiy7SFE8jqhcYMbM247gnA';
$pwd2='pgH-aiy7SFFDEhI-AlYmuwpC8VudPFI';



echo "<br>usuario: ".$usuario2." => ".$crypto->decodificar_variable($usuario2,  $semilla);
echo "<br>clave: ".$pwd2." => ".$crypto->decodificar_variable($pwd2,  $semilla);




echo "<br><br>DECODIFICACION DE CLAVE<BR> ";
$usuario2='lsYVOD4uhG-M2aKJleTG0M5-FryrgF9RImIjvC-R7dQ';
$pwd2='';


echo "<br>usuario: ".$usuario2." => ".$crypto->decodificar_variable($usuario2,  $semilla);
echo "<br>clave: ".$pwd2." => ".$crypto->decodificar_variable($pwd2,  $semilla);	


echo "<br><br>DECODIFICACION DE CLAVE<BR> ";
$usuario2='2AAeuMxf51Ti5PMdembvXIEh-7m1';
$pwd2='RgBgB69f51QlZVzFi3V2HjvGzY7vuH9rptwXEpcDKmkR';


echo "<br>usuario: ".$usuario2." => ".$crypto->decodificar_variable($usuario2,  $semilla);
echo "<br>clave: ".$pwd2." => ".$crypto->decodificar_variable($pwd2,  $semilla);


echo "<br><br>DECODIFICACION DE CLAVE DB<BR> ";
$usuario2='-QA99wMcnVd4PtUsf-1ZPiquyc0c-Zc6Q8n2QfGr3jZLpXYPzbOMGmE_FJ65v_aDXn-WL3qMuXatl3nwHRVIVPT8JAw';
$pwd2='bAKz0CkZ_FF7QW57tjbHXr_rKE21uMafJUlGOkGH';


echo "<br>usuario: ".$usuario2." => ".$crypto->decodificar_variable($usuario2,  $semilla);
echo "<br>clave: ".$pwd2." => ".$crypto->decodificar_variable($pwd2,  $semilla);
      

echo "<br><br>DECODIFICACION DE CLAVE DB PRODUCCION<BR> ";
$usuario2='YwClqhZZzlc34DaJwbwsF9r7ZmHYSwPkMLjSU5ulL74kHzHhw2_aWIlj2qBAK7yfaDC4l77EsVyyJlMgOK_ULB4kqw';
$pwd2='bAKz0CkZ_FF7QW57tjbHXr_rKE21uMafJUlGOkGH';


echo "<br>usuario: ".$usuario2." => ".$crypto->decodificar_variable($usuario2,  $semilla);
echo "<br>clave: ".$pwd2." => ".$crypto->decodificar_variable($pwd2,  $semilla);

echo "<br>=============================================================================================<br>";

echo "<br>TEXTS: ".$pwd2." => ".$crypto->decodificar_variable('LgG7dPYN2VRXIaBIHA',  $semilla);
echo "<br>TEXTS: ".$pwd2." => ".$crypto->decodificar_variable('BQECz1RhEVeWwAfyoo4r75c',  $semilla);
echo "<br>TEXTS: ".$pwd2." => ".$crypto->decodificar_variable('MgHg6PYN2VRJD3nFodzE5JQjIA',  $semilla);
echo "<br>TEXTS: ".$pwd2." => ".$crypto->decodificar_variable('NAEoX_YN2VQf0HjS',  $semilla);
echo "<br>TEXTS: ".$pwd2." => ".$crypto->decodificar_variable('NgEIm_YN2VSeIa6MIW4nx-recQ',  $semilla);
echo "<br>TEXTS: ".$pwd2." => ".$crypto->decodificar_variable('OAFg0vYN2VQgQnU1-sAYHg',  $semilla);

?>
