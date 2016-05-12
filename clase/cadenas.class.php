<?
/*********************************************************************************************************************

cadenas.class.php
UNIVERSIDAD DISTRITAL Francisco José de Caldas
Comité Institucional de Acreditación


TTG de Colombia
Paulo Cesar Coronado
Copyright (C) 2001-2004


Este programa es software libre; usted lo puede distribuir o modificar bajo los términos de la version 2
de GNU - General Public License, tal como es publicada por la Free Software Foundation

Este programa se distribuye con la esperanza de que sea útil pero SIN NINGUNA GARANTÍA;
sin garantía implícita de COMERCIALIZACIÓN  o de USO PARA UN PROPÒSITO EN PARTICULAR.

Por favor lea GNU General Public License para más detalles.

************************************************************************************************************************
* @subpackage
* @package	db
* @copyright     GPL
* @version      	1.0
* @author      	Paulo Cesar Coronado
* @link		http://acreditacion.udistrital.edu.co

* pregunta Class
*
* Esta clase de utiliza manejar las tildes
*
*
*
*/

/**********************************************************************************************************************/

class cadenas
{

	function cadenas()
	{
		$this->cadenas="";
	}

	/**
	 *@method unhtmlentities
	 * @param array berosa
	 * @param int  id_instrumento
	 * @return string cadena_sql
	 * @access public
	 */


	function unhtmlentities($cadena)
	{
		$trans_tbl = get_html_translation_table(HTML_ENTITIES);
		$trans_tbl = array_flip($trans_tbl);
		return strtr($cadena, $trans_tbl);
	}

	function formatohtml($cadena)
	{
		$trans_tbl = get_html_translation_table(HTML_ENTITIES);
		return strtr($cadena, $trans_tbl);
	}

	function verificarCorreoElectronico($email)
	{
		//Verificar que solo existe una @y que los tamannos de las secciones son correctos segun la RFC 2822
		if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email))
		{
			return false;
		}

		// Dividir el correo en secciones para analizarlas
		$email_array = explode("@", $email);
		$local_array = explode(".", $email_array[0]);

		for ($i = 0; $i < sizeof($local_array); $i++)
		{
			if (!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$", $local_array[$i]))
			{
				return false;
			}
		}

		if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1]))
		{
			// Verificar si hay una direccion IP en el dominio
			$domain_array = explode(".", $email_array[1]);
			if (sizeof($domain_array) < 2)
			{
				return false;
			}
			for ($i = 0; $i < sizeof($domain_array); $i++)
			{
				if (!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$", $domain_array[$i]))
				{
					return false;
				}
			}
		}
		return true;

	}


}
?>
