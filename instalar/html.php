<?
/***************************************************************************
*    Copyright (c) 2004 - 2006 :                                           *
*    UNIVERSIDAD DISTRITAL Francisco Jose de Caldas                        *
*    Paulo Cesar Coronado                                                  *
*    paulo_cesar@udistrital.edu.co                                         *
****************************************************************************/

/***************************************************************************
* @name          index.php 
* @author        Paulo Cesar Coronado
* @revision      Última revisión 3 de marzo de 2008
****************************************************************************
* @subpackage   
* @package	backoffice
* @copyright    
* @version      0.2
* @author      	Paulo Cesar Coronado
* @link		N/D
* @description  Formulario principal de instalacion
*
*****************************************************************************/

$tema->celda         ="#a8c9eb";
$sitio=substr($_SERVER["REQUEST_URI"],0,(strlen($_SERVER["REQUEST_URI"])-strlen("/instalar/html.php")));

//Validacion
$formulario="variables";
$validar="control_vacio(".$formulario.",'titulo','Nombre del Sitio')";
$validar.="&&control_vacio(".$formulario.",'raiz_documento','Carpeta de Documentos')";
$validar.="&&control_vacio(".$formulario.",'host','Dirección URL del servidor')";
$validar.="&&control_vacio(".$formulario.",'site','Carpeta del sitio en el servidor')";
$validar.="&&control_vacio(".$formulario.",'db_dns','Dirección Servidor de Bases de Datos')";
$validar.="&&control_vacio(".$formulario.",'db_name','Nombre de la Base de Datos')";
$validar.="&&control_vacio(".$formulario.",'db_user','Usuario de la Bases de Datos')";
$validar.="&&control_vacio(".$formulario.",'db_password','Clave de Acceso a la Base de Datos')";
$validar.="&&control_vacio(".$formulario.",'expiracion','Tiempo de expiración de las sesiones')";
$validar.="&&verificar_rango(".$formulario.",'expiracion',30,1440)";
$validar.="&&control_vacio(".$formulario.",'registro','Cantidad de registros a mostrar')";
$validar.="&&verificar_rango(".$formulario.",'registro',1,100)";
$validar.="&&control_vacio(".$formulario.",'prefijo','Prefijo de las tablas en la base de datos')";
$validar.="&&control_vacio(".$formulario.",'enlace','Nombre de la variable para encriptar')";
$validar.="&&control_vacio(".$formulario.",'grafico','Carpeta de Gráficos')";
$validar.="&&control_vacio(".$formulario.",'bloques','Carpeta de Bloques')";
$validar.="&&control_vacio(".$formulario.",'javascript','Carpeta para funciones Javascript')";
$validar.="&&control_vacio(".$formulario.",'documento','Carpeta para documentos')";
$validar.="&&control_vacio(".$formulario.",'estilo','Carpeta de Estilos')";
$validar.="&&control_vacio(".$formulario.",'administrador','Nombre de Usuario Administrador')";
$validar.="&&control_vacio(".$formulario.",'clave','Clave de Administrador')";
$validar.="&&control_vacio(".$formulario.",'correo','Correo Electrónico Administrador')";
$validar.="&&verificar_correo(".$formulario.",'correo')";
	 
$tab=0;			
?><html>
<head>
<style>
.bloquecentralcuerpo {
    font-size: 12;
    font-family: "Arial", Verdana, Trebuchet MS, Helvetica, sans-serif;
    text-align: justify;
    color:#2A2A2A;
    }
</style>
<title>
Sistema Informaci&oacute;n en Telemedicina
</title>
</head>
<body leftMargin="0" topMargin="0">
<script src="funciones.js" type="text/javascript" language="javascript"></script>
<form enctype='multipart/form-data' method='POST' action='action.php' name='<? echo $formulario?>'>
<table width="795px" class='bloquelateral' align='center' width='100%' cellpadding='0' cellspacing='0' bgcolor="#a8c9eb">
<tr>
<td>
<table align='center' width='100%' cellpadding='7' cellspacing='1'>
	<tr class='bloquecentralcuerpo'>
		<td colspan="2" rowspan="1" align="center" bgcolor="#bcddff">
		<b>Configuraci&oacute;n del Sitio Web:</b>
		</td>
	</tr>	
	<tr class='bloquecentralcuerpo'>
		<td bgcolor='<? echo $tema->celda ?>'>
			Nombre del Sitio:<br>(M&aacute;ximo 255 letras)
		</td>
		<td bgcolor='<? echo $tema->celda ?>'>
			<input type='text' name='titulo' size='40' maxlength='255' tabindex='<? echo $tab++ ?>' value='Condor BackOffice'>
		</td>
	</tr>
	<tr class='bloquecentralcuerpo'>
		<td bgcolor='<? echo $tema->celda ?>'>
			Directorio ra&iacute;z de los documentos en el servidor:<br>(No es la URL)
		</td>
		<td bgcolor='<? echo $tema->celda ?>'>
			<input type='text' name='raiz_documento' size='40' maxlength='255' tabindex='<? echo $tab++ ?>' value="<? echo $_SERVER["DOCUMENT_ROOT"]?>">
		</td>
	</tr>
	<tr class='bloquecentralcuerpo'>
		<td bgcolor='<? echo $tema->celda ?>'>
			Direcci&oacute;n (URL) ra&iacute;z del servidor:<br>(Ej: http://mi_servidor)
		</td>
		<td bgcolor='<? echo $tema->celda ?>'>
			<input type='text' name='host' size='40' maxlength='255' tabindex='<? echo $tab++ ?>' value="<? echo "http://".$_SERVER["HTTP_HOST"]?>">
		</td>
	</tr>
	<tr class='bloquecentralcuerpo'>
		<td bgcolor='<? echo $tema->celda ?>'>
			Carpeta del sitio:<br>(Ej: /mi_sitio)
		</td>
		<td bgcolor='<? echo $tema->celda ?>'>
			<input type='text' name='site' size='40' maxlength='255' tabindex='<? echo $tab++ ?>'  value="<? echo $sitio?>">
		</td>
	</tr>
	<tr class='bloquecentralcuerpo'>
		<td colspan="2" rowspan="1" align="center" bgcolor="#bcddff">
		<b>Datos para la administraci&oacute;n:</b>
		</td>
	</tr>
	<tr class='bloquecentralcuerpo'>
		<td bgcolor='<? echo $tema->celda ?>'>
		Nombre de Usuario:<br>(Car&aacute;cteres alfanum&eacute;ricos)
		</td>
		<td bgcolor='<? echo $tema->celda ?>'>
		<input maxlength="50" size="30" tabindex='<? echo $tab++ ?>' name="administrador" value="administrador">
		</td>
	</tr>
	<tr class='bloquecentralcuerpo'>
		<td bgcolor='<? echo $tema->celda ?>'>
		Clave de administrador: 
		</td>
		<td bgcolor='<? echo $tema->celda ?>'>
		<input maxlength="50" size="30" tabindex='<? echo $tab++ ?>' name="clave" type="password">
		</td>
	</tr>
	<tr class='bloquecentralcuerpo'>
		<td bgcolor='<? echo $tema->celda ?>'>
		Correo Electr&oacute;nico de Administrador:<br>
		</td>
		<td bgcolor='<? echo $tema->celda ?>'>
		<input maxlength="50" size="30" tabindex='<? echo $tab++ ?>' name="correo"></td>
	</tr>
	<tr class='bloquecentralcuerpo'>
		<td colspan="2" rowspan="1" align="center" bgcolor="#bcddff">
		<b>Configuraci&oacute;n para la Base de Datos:</b>
		</td>
	</tr>
	<tr class='bloquecentralcuerpo'>
		<td bgcolor='<? echo $tema->celda ?>'>
			Sistema de Base de Datos:
		</td>
		<td bgcolor='<? echo $tema->celda ?>'>
			<select name="db_sys" tabindex='<? echo $tab++ ?>'>
			<option value="1">MySQL 3.x &oacute; superior</option>
			</select>
		</td>
	</tr>
	<tr class='bloquecentralcuerpo'>
		<td bgcolor='<? echo $tema->celda ?>'>
			DNS del servidor de base de datos:
		</td>
		<td bgcolor='<? echo $tema->celda ?>'>
			<input type='text' name='db_dns' size='40' maxlength='255' tabindex='<? echo $tab++ ?>' value="localhost">
		</td>
	</tr>
	<tr class='bloquecentralcuerpo'>
		<td bgcolor='<? echo $tema->celda ?>'>
			Nombre de la base de datos:
		</td>
		<td bgcolor='<? echo $tema->celda ?>'>
			<input type='text' name='db_name' size='40' maxlength='255' tabindex='<? echo $tab++ ?>' >
		</td>
	</tr>
	<tr class='bloquecentralcuerpo'>
		<td bgcolor='<? echo $tema->celda ?>'>
			Usuario de la base de datos:
		</td>
		<td bgcolor='<? echo $tema->celda ?>'>
			<input type='text' name='db_user' size='40' maxlength='255' tabindex='<? echo $tab++ ?>' >
		</td>
	</tr>
	<tr class='bloquecentralcuerpo'>
		<td bgcolor='<? echo $tema->celda ?>'>
			Clave de acceso:
		</td>
		<td bgcolor='<? echo $tema->celda ?>'>
			<input type='password' name='db_password' size='40' maxlength='255' tabindex='<? echo $tab++ ?>' >
		</td>
	</tr>
	<tr class='bloquecentralcuerpo'>
		<td bgcolor='<? echo $tema->celda ?>'>
			Prefijo para las tablas en la base de datos:
		</td>
		<td bgcolor='<? echo $tema->celda ?>'>
			<input type='text' name='prefijo' size='40' maxlength='255' tabindex='<? echo $tab++ ?>' value="backoffice_" >
		</td>
	</tr>	
	<tr class='bloquecentralcuerpo'>
		<td bgcolor='<? echo $tema->celda ?>'>
			N&uacute;mero de registros que se mostrar&aacute;n en los resultados:
		</td>
		<td bgcolor='<? echo $tema->celda ?>'>
			<input type='text' name='registro' size='40' maxlength='255' tabindex='<? echo $tab++ ?>' value="25" >
		</td>
	</tr>
	<tr class='bloquecentralcuerpo'>
		<td colspan="2" rowspan="1" align="center" bgcolor="#accdef">
		<b>Sesiones:</b>
		</td>
	</tr>
	<tr class='bloquecentralcuerpo'>
		<td bgcolor='<? echo $tema->celda ?>'>
			Tiempo de expiraci&oacute;n de las sesiones:<br>(En segundos)
		</td>
		<td bgcolor='<? echo $tema->celda ?>'>
			<input type='text' name='expiracion' size='40' maxlength='255' tabindex='<? echo $tab++ ?>' value="<? echo (60*24) ?>">
		</td>
	</tr>
	<tr class='bloquecentralcuerpo'>
		<td bgcolor='<? echo $tema->celda ?>'>
			Enlace a Wikipedia:
		</td>
		<td bgcolor='<? echo $tema->celda ?>'>
			<input type='text' name='wikipedia' size='40' maxlength='255' tabindex='<? echo $tab++ ?>' value="http://es.wikipedia.org/wiki/">
		</td>
	</tr>
	<tr class='bloquecentralcuerpo'>
		<td bgcolor='<? echo $tema->celda ?>'>
			Nombre de la variable con que encripta:
		</td>
		<td bgcolor='<? echo $tema->celda ?>'>
			<input type='text' name='enlace' size='40' maxlength='255' tabindex='<? echo $tab++ ?>' value="index">
		</td>
	</tr>
	<tr class='bloquecentralcuerpo'>
		<td bgcolor='<? echo $tema->celda ?>'>
			Tama&ntilde;o de la ventana:
		</td>
		<td bgcolor='<? echo $tema->celda ?>'>
			<input type='text' name='tamanno_gui' size='5' maxlength='10' tabindex='<? echo $tab++ ?>' value="795">
		</td>
	</tr>
	<tr class='bloquecentralcuerpo'>
		<td colspan="2" rowspan="1" align="center" bgcolor="#bcddff">
		<b>Ubicaci&oacute;n de las carpetas dentro del Sitio Web:</b>
		</td>
	</tr>
	<tr class='bloquecentralcuerpo'>
		<td bgcolor='<? echo $tema->celda ?>'>
			Gr&aacute;ficos:
		</td>
		<td bgcolor='<? echo $tema->celda ?>'>
			<input type='text' name='grafico' size='40' maxlength='255' tabindex='<? echo $tab++ ?>' value="/grafico" >
		</td>
	</tr>
	<tr class='bloquecentralcuerpo'>
		<td bgcolor='<? echo $tema->celda ?>'>
			Bloques (M&oacute;dulos):
		</td>
		<td bgcolor='<? echo $tema->celda ?>'>
			<input type='text' name='bloques' size='40' maxlength='255' tabindex='<? echo $tab++ ?>'  value="/bloque">
		</td>
	</tr>	
	<tr class='bloquecentralcuerpo'>
		<td bgcolor='<? echo $tema->celda ?>'>
			Funciones Javascript:
		</td>
		<td bgcolor='<? echo $tema->celda ?>'>
			<input type='text' name='javascript' size='40' maxlength='255' tabindex='<? echo $tab++ ?>' value="/funcion">
		</td>
	</tr>
	<tr class='bloquecentralcuerpo'>
		<td bgcolor='<? echo $tema->celda ?>'>
			Documentos:<br>(Con permisos de lectura y escritura)
		</td>
		<td bgcolor='<? echo $tema->celda ?>'>
			<input type='text' name='documento' size='40' maxlength='255' tabindex='<? echo $tab++ ?>' value="/documento">
		</td>
	</tr>
	<tr class='bloquecentralcuerpo'>
		<td bgcolor='<? echo $tema->celda ?>'>
			Estilo
		</td>
		<td bgcolor='<? echo $tema->celda ?>'>
			<input type='text' name='estilo' size='40' maxlength='255' tabindex='<? echo $tab++ ?>' value="/estilo">
			<input type='hidden' name='clases' value="/clase">
			<input type='hidden' name='configuracion' value="/configuracion">
		</td>
	</tr>
	<tr align='center'>
		<td colspan='2' rowspan='1'>
			<input name='aceptar' value='Aceptar' type='button' onclick="return(<? echo $validar; ?>)?this.form.submit():false"><br>
		</td>
	</tr>
</table>
</td>
</tr>
</table>
</form>
</body>
</html>