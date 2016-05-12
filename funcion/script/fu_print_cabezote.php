<?PHP
require_once('dir_relativo.cfg');


function fu_print_cabezote($titulo){

	include_once("../clase/multiConexion.class.php");

	$esta_configuracion=new config();
	$configuracion=$esta_configuracion->variable("../"); 

	$usuario = $_SESSION['usuario_login'];

	$conexion=new multiConexion();
	$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);

	$fec = date("j-M-Y");
	$hor = date("g:i:s A");
	$escudo = dir_img.'Uescudo.gif';

	$cadena_sql="SELECT trim(dep_nombre) ";
	$cadena_sql.="FROM gedep, accra ";
	$cadena_sql.="WHERE dep_cod = cra_dep_cod ";
	$cadena_sql.="AND cra_cod = (select est_cra_cod from acest where est_cod=$usuario)";

	$registro=$conexion->ejecutarSQL($configuracion,$accesoOracle,$cadena_sql,"busqueda");
	$Facultad = $registro[0][0];


echo <<< HTML
	<div align="center"><table border="0" width="90%">
	<tr><td width="14%" rowspan="2" align="center"><IMG SRC=$escudo></td>
    <td width="86%" align="center"><b><font color="#0000FF" face="Arial">UNIVERSIDAD DISTRITAL FRANCISCO JOS&Eacute; DE CALDAS<br>
	$Facultad</font></b></td></tr>
	<tr><td width="86%" align="center"><b><font face="Arial" size="2" color="#FF3300"><br>$titulo<br>
    </font></b></td></tr></table>

	<table border="0" width="90%" cellpadding="2" height="3"><tr><td width="16%" align="center"><div align="right">
    <input name="button" type="button" onClick="javascript:window.print();" value="Impreso: " style="cursor:pointer" title="Clic par imprimir el reporte">
    </div></td>

    <td width="14%" align="center"><div align="left"><font face="Tahoma" size="2">$fec</font></div></td>
    <td width="14%" align="center"><div align="left"><font face="Tahoma" size="2">$hor</font></div></td>
    <td width="17%" align="center">&nbsp;</td>
    <td width="17%" align="center">&nbsp;</td>
    <td width="17%" align="center">&nbsp;</td>
	<td width="17%" align="center">&nbsp;</td>
    </tr></table></div><BR>
HTML;
}
?>