<?
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/
/*--------------------------------------------------------------------------------------------------------------------------
* @name          navegacion.class.php 
* @author        Paulo Cesar Coronado
* @revision      Última revisión 15 de enero de 2009
/*--------------------------------------------------------------------------------------------------------------------------
* @subpackage		
* @package		clase
* @copyright    	Universidad Distrital Francisco Jose de Caldas
* @version      		1.0.0.1
* @author			Paulo Cesar Coronado
* @author			Oficina Asesora de Sistemas
* @link			N/D
* @description  	Clase para gestionar el menu de navegacion
*
/*--------------------------------------------------------------------------------------------------------------------------*/

class navegacion
{

	function navegacion()
	{
		
		
	}
	
	function menu_navegacion($configuracion,$hoja=1,$hojas,$variable="")
	{
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
		$cripto=new encriptar();
		
		//Rescatar las variables
		$mi_variable="";
		if(is_array($variable))
		{
			foreach ($variable as $clave => $valor) 
			{
				$mi_variable.="&".$clave."=".$valor;			
			}
		}
		
	?><table width="100%" align="center" border="0" cellpadding="10" cellspacing="0" >
		<tbody>
			<tr>
				<td >
					<table width="100%" cellpadding="2" cellspacing="2" class="bloquelateral">
					<tr class="bloquecentralcuerpo">
						<td align="left"  width="33%"><?
						if($hoja>1)
						{
							echo $this->anterior($configuracion, $cripto, $hoja,$mi_variable);
						}
						
						?></td>
						<td align="center" class="celdatabla"><?
							if($hojas>1)
							{
								echo $this->formulario_navegacion($configuracion,$hojas, $mi_variable,$cripto);
							}
							else
							{
								echo "Hoja  ".$_REQUEST["hoja"]." de ".($hojas)."\n";	
							}
					?>	</td>
						<td align="right" width="33%">
						<?
							if($_REQUEST["hoja"]<$hojas)
							{
							
								echo $this->siguiente($configuracion, $cripto, $hoja,$mi_variable);
							
							}
					?>
						</td>
					</tr>
					</table>
				</td>
			</tr>
		</tbody>
	</table><?
	}
	
	
	function menu_hojas()
	{
	
	
	}
	
	function anterior($configuracion, $cripto, $hoja,$mi_variable)
	{
		
		//Indice
		$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
		//Agregar las variables de hoja
		$mi_variable.="&hoja=".($hoja-1);
		
		$variable=$cripto->codificar_url($mi_variable,$configuracion);
		$mi_variable=$indice.$variable;
		
		$cadena_html="<a ";
		$cadena_html.="title='Pasar a la p&aacute;gina No ".($hoja-1)."' "; 
		$cadena_html.="href=".$mi_variable;
		$cadena_html.=">";
		$cadena_html.="<< Anterior";
		$cadena_html.="</a>";
		
		return $cadena_html;
		
	}
	
	function siguiente($configuracion, $cripto, $hoja,$mi_variable)
	{
		//Indice
		$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
		//Agregar las variables de hoja
		$mi_variable.="&hoja=".($hoja+1);
		
		$variable=$cripto->codificar_url($mi_variable,$configuracion);
		$mi_variable=$indice.$variable;
		$cadena_html="<a ";
		$cadena_html.="title='Pasar a la p&aacute;gina No ".($hoja+1)."' ";
		$cadena_html.="href=".$mi_variable;
		$cadena_html.=">";
		$cadena_html.="Siguiente>>";
		$cadena_html.="</a>";
		
		return $cadena_html;
	}
	
	
	function formulario_navegacion($configuracion,$hojas,$mi_variable,$cripto="")
	{
		$formulario="menu_navegar".rand();
		$verificar="verificar_numero(".$formulario.",'hoja')";
		$verificar.="&&control_vacio(".$formulario.",'hoja')";
		$verificar.="&&verificar_rango(".$formulario.",'hoja', 1, ".$hojas.")";
		
		$cadena_html="<form ";
		$cadena_html.="method='GET' ";
		$cadena_html.="name=".$formulario.">";
		
		//Indice
		$mi_variable=$cripto->codificar($mi_variable,$configuracion);
		
		$cadena_html.="<input type='hidden' name='formulario' value='".$mi_variable."'>\n";
		
		$texto_ayuda="<b>Desplazarse entre p&aacute;ginas de resultados</b><br>Ingrese el n&uacute;mero de la p&aacute;gina a la que desea ir y presione la tecla ENTER.<br>Existe un total de <b>".$hojas."</b> p&aacute;gina(s).";
		$cadena_html.="Hoja  <input class='celdanavegacion' type='text' name='hoja' size='2' maxlength='6' value='".$_REQUEST["hoja"]."' onmouseover='return escape(\"".$texto_ayuda."\")' onkeypress=\"if ((event.which == 13) || (event.keyCode == 13) || (event.which == 10) || (event.keyCode == 10)){if(".$verificar."){document.forms['".$formulario."'].submit()}else{return(false)}}\"> de ".($hojas)."\n";	
		$cadena_html.="</form>";
		
		return $cadena_html;
	
	}
		

	
}//Fin de la clase
	
?>