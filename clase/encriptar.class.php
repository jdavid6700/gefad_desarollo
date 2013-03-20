<?

require_once("aes/aes.class.php");
require_once("aes/aesctr.class.php");

class encriptar{

	private static $instance;


	//Constructor
	function __construct(){
		$this->cifrado = MCRYPT_RIJNDAEL_256;
		$this->modo = MCRYPT_MODE_ECB;
	}



	function codificar_url($cadena,$configuracion="")
	{       /*reemplaza valores + / */
		$cadena=rtrim(strtr(AesCtr::encrypt($cadena,"", 256), '+/', '-_'), '=');
		$cadena=$configuracion['enlace']."=".$cadena;
		return $cadena;

	}

	/**
	 *
	 * Método para decodificar la cadena GET para obtener las variables de la petición
	 * @param $cadena
	 * @return boolean
	 */

	function decodificar_url($cadena,$configuracion='')
	{       /*reemplaza valores + / */
		$cadena=AesCtr::decrypt(str_pad(strtr($cadena, '-_', '+/'), strlen($cadena) % 4, '=', STR_PAD_RIGHT),"",256);

		parse_str($cadena,$matriz);

		foreach($matriz as $clave=>$valor)
		{
			$_REQUEST[$clave]=$valor;
		}

		return true;
	}

	function codificar($cadena,$configuracion='')
	{       /*reemplaza valores + / */
		$cadena=rtrim(strtr(AesCtr::encrypt($cadena,"", 256), '+/', '-_'), '=');
		return $cadena;

	}


	function decodificar($cadena)
	{       /*reemplaza valores + / */
		$cadena=AesCtr::decrypt(str_pad(strtr($cadena, '-_', '+/'), strlen($cadena) % 4, '=', STR_PAD_RIGHT),"",256);
		return $cadena;


	}


	function codificar_variable($cadena,$semilla)
	{
		$cadena=rtrim(strtr(AesCtr::encrypt($cadena,$semilla, 256), '+/', '-_'), '=');
		return $cadena;
	}

	function decodificar_variable($cadena,$semilla)
	{
		$cadena=AesCtr::decrypt(str_pad(strtr($cadena, '-_', '+/'), strlen($cadena) % 4, '=', STR_PAD_RIGHT),$semilla,256);
		return $cadena;
	}

}//Fin de la clase

?>
