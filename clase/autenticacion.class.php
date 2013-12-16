<?
/*
 ############################################################################
#    UNIVERSIDAD DISTRITAL Francisco Jose de Caldas                        #
#    Desarrollo Por:                                                       #
#    Paulo Cesar Coronado 2004 - 2005                                      #
#    paulo_cesar@berosa.com                                                #
#    Copyright: Vea el archivo EULA.txt que viene con la distribucion      #
############################################################################
*/
/***************************************************************************

autenticacion.inc.php

Paulo Cesar Coronado
Copyright (C) 2001-2005

Última revisión 12 de Mayo de 2011

*****************************************************************************
* @subpackage
* @package	bloques
* @copyright
* @version      0.2
* @author      	Paulo Cesar Coronado
* @link		N/D
* @description  Script para la validacion de usuarios y el control de acceso a los modulos.
* @usage        Toda pagina tiene un id_pagina que es propagado por cualquier metodo GET, POST.
******************************************************************************

Kelly K. Lopez
Modificacion 06 de Julio de 2009
Se realizo modificacion apra generar acceso al sistema de gestion academica desde el sistema condor
y realizar una autenticacion por codigo

*******************************************************************

Milton J. Parra L.
Modificacion 12 de Mayo de 2011
Se realizo modificacion para generar error al cerrarse la sesion y estar utilizando ajax

*******************************************************************/


class autenticacion
{

	function autenticacion($nombre,$configuracion)
	{
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/dbms.class.php");
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sesion.class.php");

		$acceso_db=new dbms($configuracion);
		$enlace=$acceso_db->conectar_db();
		if (is_resource($enlace))
		{
			$cadena_sql="SELECT ";
			$cadena_sql.="nivel ";
			$cadena_sql.="FROM ";
			$cadena_sql.=$configuracion["prefijo"]."pagina ";
			$cadena_sql.="WHERE ";
			$cadena_sql.="nombre='".$nombre."' ";
			$cadena_sql.="LIMIT 1";

				
				
			$acceso_db->registro_db($cadena_sql,0);
			$registro=$acceso_db->obtener_registro_db();
			$total=$acceso_db->obtener_conteo_db();

			if($total<1)
			{
				include_once($configuracion["raiz_documento"].$configuracion["bloques"]."/mensaje/fallo_temporal.php");
				exit;
			}
			else
			{
				$pagina_nivel=$registro[0][0];

			}


			//$this->opcion=$_REQUEST["opcion"];
			/*Si la opcion es igual a loginCondor, el sistema no ingresa de forma normal
			Debe dirigirse a el bloque lognCondor para autenticar por debajo */
			 
			if(!isset($_REQUEST["aplicacion"])){
				$nueva_sesion=new sesiones($configuracion);
				$nueva_sesion->especificar_enlace($enlace);
				$nueva_sesion->especificar_nivel($pagina_nivel);
				$esta_sesion=$nueva_sesion->sesion($configuracion);

				if($pagina_nivel!=0)
				{
						
					if(!$esta_sesion)
					{
						if (isset ($_POST['xajax'])||isset ($_GET['xajax']))
						{
							echo "\nNo ha sido posible iniciar una sesión de trabajo.\nProbablemente la sesión iniciada ya haya expirado.\nPor favor regrese a la página principal e ingrese su nombre de usuario y clave.\nSi el problema persiste por favor contacte al administrador del sistema en: ".$configuracion['correo']."\nRecuerde que el sistema Cóndor está optimizado para Mozilla Firefox.";
							exit;
						}
						autenticacion::mensaje_error($configuracion);
						exit();
					}
						
				}
				$nueva_sesion->borrar_sesion_expirada($configuracion);


			}#Cierre de if  $this->opcion!="loginCondor"
			else{
				#Segun la aplicacion que se  este colgando al sistema de gestion academica se debe generar un case en el switch e indicar la carpeta desde dpmde se  va a realizar la validacion de la sesion
				switch($_REQUEST["aplicacion"]){

					case "Condor":
						include_once($configuracion["raiz_documento"].$configuracion["bloques"]."/loginCondor/bloque.php");

						break;


				}#cierre de switch
			}#Cierre de else !isset($_REQUEST["aplicacion"]
		}
		else
		{
				
			include_once($configuracion["raiz_documento"].$configuracion["encabezado"].'/header.php');
				
			$error["encabezado"]="IMPOSIBLE REALIZAR LA ACCI&Oacute;N SOLICITADA";
			$error["cuerpo"]="<br>Se ha detectado un ingreso ilegal al sistema.<br>";
			$error["cuerpo"].="<br>Esto posiblemente se deba a que su sesi&oacute;n de trabajo ha expirado,";
			$error["cuerpo"].="por favor regrese a la p&aacute;gina de autenticaci&oacute;n e ingrese su nombre";
			$error["cuerpo"].="de usuario y contrase&ntilde;a.";
				
			include_once($configuracion["raiz_documento"].$configuracion["incluir"]."/error.php");
			include_once($configuracion["raiz_documento"].$configuracion["encabezado"].'/footer.php');
			exit();
				
				
		}
		unset($acceso_db);
		unset($nueva_sesion);
	}

	function mensaje_error($configuracion)
		{
            $indice=$configuracion["host"].$configuracion["site"];
            ?>
<link
	rel='stylesheet' type='text/css'
	href='<? echo $configuracion["host"].$configuracion["site"].$configuracion["estilo"]."/basico/estilo.php" ?>' />
<table class="bloquelateral" cellpadding="0" cellspacing="0"
	align="center" width="50%">
	<tbody>
		<tr class="bloquelateralencabezado">
			<td valign="middle" align="right" width="10%"><img
				src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["grafico"]?>/importante.png"
				border="0" />
			</td>
			<td valign="middle" align="left">Fallo General de
				Autenticaci&oacute;n</td>
		</tr>
		<tr>
			<td colspan="2">
				<table border="0" cellpadding="10" cellsapcing="0">
					<tbody>
						<tr class="bloquecentralcuerpo">
							<td><b>No ha sido posible iniciar una sesi&oacute;n de trabajo.</b><br>
								Probablemente la sesi&oacute;n iniciada ya haya expirado. Por
								favor regrese a la p&aacute;gina principal e ingrese su nombre
								de usuario y clave.<br> <br> Si el problema persiste por favor
								contacte al administrador del sistema en:<br> <a
								href="mailto:<? echo $configuracion["correo"]?>"><? echo $configuracion["correo"]?>
							</a><br></td>
						<tr class="bloquecentralcuerpo">
							<td style="font-size: 130%" align="center"><a
								href="<? echo $indice;?>" target='_top'><b>Aceptar</b> </a>
							</td>
						</tr>
					</tbody>
				</table>
			</td>
		</tr>
	</tbody>
</table>
<?
		}


}
?>
