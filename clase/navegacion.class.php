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
/****************************************************************************
  
navegacion.class.php 
 
Paulo Cesar Coronado
Copyright (C) 2001-2005

Última revisión 6 de Marzo de 2006

******************************************************************************
* @subpackage   
* @package	clase
* @copyright    
* @version      0.2
* @author      	Paulo Cesar Coronado
* @link		http://acreditacion.udistrital.edu.co
* @description  Clase para el manejo de elementos HTML (XML)
*******************************************************************************
*Atributos
*
*@access private
*@param  $conexion_id
*		Identificador del enlace a la base de datos 
*********************************************************************************
*/

/*********************************************************************************
*Métodos
*
*@access public
*
**********************************************************************************/

class navegacion
{

	function navegacion()
	{
		
		
	}
	
	function menu_navegacion($configuracion,$hoja=1,$hojas,$variable="")
	{

                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                $cripto=new encriptar();
                //Indice
		$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
		
		//Rescatar las variables
		$mi_variable="";

                if(is_array($variable))
		{
			foreach ($variable as $clave => $valor) 
			{
				$mi_variable.="&".$clave."=".$valor;			
			}
		}

                $anterior=$mi_variable."&hoja=".($hoja-1);
                $siguiente=$mi_variable."&hoja=".($hoja+1);
                $anterior=$cripto->codificar_url($anterior,$configuracion);
                $siguiente=$cripto->codificar_url($siguiente,$configuracion);

		$anterior=$indice.$anterior;
                $siguiente=$indice.$siguiente;
		
	?><table width="100%" align="center" border="0" cellpadding="10" cellspacing="0" >
		<tbody>
			<tr>
				<td >
					<table width="100%" cellpadding="2" cellspacing="2" class="bloquelateral">
					<tr class="bloquecentralcuerpo">
						<td align="left"  width="33%"><?
						if($hoja>1)
						{
							echo $this->anterior($hoja, $anterior);
						}
						
						?></td>
						<td align="center" class="celdatabla"><?
							if($hojas>1)
							{
								echo $this->formulario_navegacion($hojas);
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
							
								echo $this->siguiente($hoja, $siguiente);
							
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
	
	function anterior($hoja,$mi_variable)
	{
		
		$cadena_html="<a ";
		$cadena_html.="title='Pasar a la p&aacute;gina No ".($hoja-1)."' ";
		$cadena_html.="href='".$mi_variable."'";
		$cadena_html.=">";
		$cadena_html.="<< Anterior";
		$cadena_html.="</a>";
		
		return $cadena_html;
		
	}
	
	function siguiente($hoja, $mi_variable)
	{
		$cadena_html="<a ";
		$cadena_html.="title='Pasar a la p&aacute;gina No ".($hoja+1)."' ";
		$cadena_html.="href='".$mi_variable."'";
		$cadena_html.=">";
		$cadena_html.=" Siguiente >>";
		$cadena_html.="</a>";
		
		return $cadena_html;
	}
	
	
	function formulario_navegacion($hojas)
	{
		$formulario="menu_navegar";
		$verificar="verificar_numero(document.forms['".$formulario."'],'ir_hoja')";
		$verificar.="&& verificar_rango(document.forms['".$formulario."'],'ir_hoja',1,".$hojas.")";
		
		$cadena_html="<form ";
		$cadena_html.="method='GET' ";
		$cadena_html.="name=".$formulario.">";
		$cadena_html.="<input type='hidden' name='".$clave."' value='".$mi_variable."'>\n";
		$texto_ayuda="<b>Desplazarse entre p&aacute;ginas de resultados</b><br>Ingrese el n&uacute;mero de la p&aacute;gina a la que desea ir y presione la tecla ENTER.<br>Existe un total de <b>".$hojas."</b> p&aacute;gina(s).";
		$cadena_html.="Hoja  <input class='celdanavegacion' type='text' name='ir_hoja' size='2' maxlength='6' value='".$_REQUEST["hoja"]."' onmouseover='return escape(\"".$texto_ayuda."\")' onkeypress=\"if ((event.which == 13) || (event.keyCode == 13) || (event.which == 10) || (event.keyCode == 10)){return(".$verificar.")?document.forms['".$formulario."'].submit():false}\"> de ".$hojas."\n";	
		$cadena_html.="</form>";
		
		return $cadena_html;
	
	}
		

	
}//Fin de la clase
	
?>