<?php
// +----------------------------------------------------------------------
// | PHP Source                                                           
// +----------------------------------------------------------------------
// | Copyright (C) 2005 by Paulo Cesar Coronado <paulo_cesar@localhost.localdomain>
// +----------------------------------------------------------------------
// |
// | Copyright: See COPYING file that comes with this distribution
// +----------------------------------------------------------------------
//

?><?
if(isset($_REQUEST["aceptar"]))
{

	require_once("../clase/config.class.php");
	$esta_configuracion=new config();
	$configuracion=$esta_configuracion->variable("../"); 
	
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
	
	
	$crypto=new encriptar();
	
	$crypto->decodificar_url($_POST["pagina"],$configuracion);
	
	echo "<b>Variables</b><br>";
	foreach ($_REQUEST as $key => $value) 
	{
	echo $key."=>".$value."<br>";
	
	}
	echo "<hr>";
	$pagina=$_REQUEST["pagina"];
	echo "<b>P&aacute;gina</b><br><b>".$pagina."</b><br>";
	$acceso_db=new dbms($configuracion);
	$enlace=$acceso_db->conectar_db();
	if (is_resource($enlace))
	{
		$cadena_sql="SELECT id_pagina FROM ".$configuracion["prefijo"]."pagina WHERE nombre='".$pagina."' LIMIT 1";
		$acceso_db->registro_db($cadena_sql,0);
		$registro=$acceso_db->obtener_registro_db();
		$conteo=$acceso_db->obtener_conteo_db();
		if($conteo>0)
		{
			echo "id_pagina: ".$registro[0][0]."<br><hr>";
			echo "Bloques que componen esta p&aacute;gina:<br>";
			$cadena_sql="SELECT ";
			$cadena_sql.="".$configuracion["prefijo"]."bloque_pagina.id_bloque, ";
			$cadena_sql.="".$configuracion["prefijo"]."bloque_pagina.seccion, ";
			$cadena_sql.="".$configuracion["prefijo"]."bloque_pagina.posicion, ";
			$cadena_sql.="".$configuracion["prefijo"]."bloque.nombre ";
			$cadena_sql.="FROM ";
			$cadena_sql.="".$configuracion["prefijo"]."bloque_pagina,";
			$cadena_sql.="".$configuracion["prefijo"]."bloque "; 
			$cadena_sql.="WHERE ";
			$cadena_sql.="".$configuracion["prefijo"]."bloque_pagina.id_pagina='".$registro[0][0]."' ";
			$cadena_sql.="AND ";
			$cadena_sql.="".$configuracion["prefijo"]."bloque_pagina.id_bloque=".$configuracion["prefijo"]."bloque.id_bloque";
			//echo $cadena_sql."<br>";
			$acceso_db->registro_db($cadena_sql,0);
			$registro=$acceso_db->obtener_registro_db();
			$conteo=$acceso_db->obtener_conteo_db();
			if($conteo>0)
			{
				?>
<table border="0" align="center" cellpadding="5" cellspacing="1">
<tr bgcolor="#ECECEC">
		<td align="center">id</td>			
		<td align="center">nombre</td>
		<td align="center">secci&oacute;n</td>
		<td align="center">posici&oacute;n</td>
</tr>	
<?
				for($contador=0;$contador<$conteo;$contador++)
				{
				?>
	<tr bgcolor="#ECECEC">
		<td><? echo  $registro[$contador][0]?></td>			
		<td><? echo  $registro[$contador][3]?></td>
		<td><? echo  $registro[$contador][1]?></td>
		<td><? echo  $registro[$contador][2]?></td>
	</tr>	
				<?	
					
				}
				?>
</table>
<hr>
P&aacute;gina generada autom&aacute;ticamente el: <? echo date("d/m/Y",time())?><br>
Ambiente de desarrollo para aplicaciones web. - Software amparado por licencia GPL. Copyright (c) 2004-2006.<br>
Paulo Cesar Coronado - Universidad Distrital Francisco Jos&eacute; de Caldas.<br>
<hr>
				
				<?
				
			}
		
		}
		else
		{
			echo "La p&aacute;gina no se encuentra registrada en el sistema<br>";?>
<hr>
P&aacute;gina generada autom&aacute;ticamente el: <? echo date("d/m/Y",time())?><br>
Ambiente de desarrollo para aplicaciones web. - Software amparado por licencia GPL. Copyright (c) 2004-2006.<br>
Paulo Cesar Coronado - Universidad Distrital Francisco Jos&eacute; de Caldas.<br>
<hr>			
			<?
		}
	}
	
}
?><form method="post" action="desenlace.php" name="desenlazar" >
  <table class="bloquelateral" align="center" cellpadding="0" cellspacing="0" width="100%" >
    <tbody>
      <tr>
        <td align="center" valign="middle">
        <input size="40" tabindex="1" name="pagina">
        </td>
      </tr>
      <tr>
        <td align="center" valign="middle">
        <input value="aceptar" name="aceptar" type="submit">
        </td>
      </tr>
    <tbody>
 </table>
<form>
