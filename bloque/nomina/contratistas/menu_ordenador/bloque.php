<?
/***************************************************************************
  
index.php 

Paulo Cesar Coronado
Copyright (C) 2001-2005

Última revisión 6 de Marzo de 2006

*****************************************************************************
* @subpackage   
* @package	bloques
* @copyright    
* @version      0.2
* @author      	Maritza Callejas
* @link		N/D
* @description  Menu principal del modulo de ordenadores
* @usage        
*****************************************************************************/
if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;		
}


include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");

$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
$indiceSeguro=$configuracion["host"].$configuracion["site"]."/index.php?";
$cripto=new encriptar();
?><table align="center" class="tablaMenu">
	<tbody>
		<tr>
			<td >
				<table align="center" border="0" cellpadding="5" cellspacing="2" class="bloquelateral_2" width="100%">
				
					<tr class="bloquelateralcuerpo">
						<td class="cuadro_simple">
						<a href="<?		
							$variable="pagina=nom_adminNominaOrdenador";
                                                        $variable.="&opcion=consultarNomina";
							$variable=$cripto->codificar_url($variable,$configuracion);
							echo $indice.$variable;		
							?>">  Reporte Nomina</a>
							
						</td>
					</tr>
					<tr class="bloquelateralcuerpo">
						<td class="cuadro_simple">
						<a href="<?		
							$variable="pagina=nom_adminNominaOrdenador";
                                                        $variable.="&opcion=revisarSolicitudNomina";
							$variable=$cripto->codificar_url($variable,$configuracion);
							echo $indice.$variable;		
							?>">  Aprobar Solicitud Nomina</a>
							
						</td>
					</tr>
					<tr class="bloquelateralcuerpo">
						<td class="cuadro_simple">
						<a href="<?		
							$variable="pagina=nom_adminNominaOrdenador";
                                                        $variable.="&opcion=revisarNomina";
							$variable=$cripto->codificar_url($variable,$configuracion);
							echo $indice.$variable;		
							?>">  Generar Nomina</a>
							
						</td>
					</tr>						                                      
					<tr class="bloquelateralcuerpo">
						<td class="cuadro_simple">
						<a href="<?		
							$variable="pagina=nom_adminSolicitudAprobarGiroOrdenador";
                                                        $variable.="&opcion=revisarNominasGeneradas";
							$variable=$cripto->codificar_url($variable,$configuracion);
							echo $indice.$variable;		
							?>">  Autorización de Giro</a>
							
						</td>
					</tr>						                                      
				</table>
			</td>
		</tr>
	</tbody>
</table>
