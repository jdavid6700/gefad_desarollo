<?	
class codigoBarras
{
	function codigoBarras($configuracion)
	{
			
		if(!function_exists("imagecreate"))
		{
			die("Se requiere la libreria GD.");
			return false;
		}

		$this->fuenteSimbolo=$configuracion["raiz_documento"].$configuracion["grafico"]."/fuente/"."Vera.ttf";
	}


	function colorSimbolo($color,$fondo)
	{
		$this->colorBarras(hexdec(substr($color,1,2)),hexdec(substr($color,3,2)),hexdec(substr($color,5,2)));
		$this->fondoSimbolo(hexdec(substr($fondo,1,2)),hexdec(substr($fondo,3,2)),hexdec(substr($fondo,5,2)));
	}

	function colorBarras($r,$g,$b)
	{
		$this->color=array($r,$g,$b);
	}

	function fondoSimbolo($r,$g,$b)
	{
		$this->fondo=array($r,$g,$b);
	}

	function escalaSimbolo($escala)
	{
		$this->escala=$escala;
	}

	function altoSimbolo($alto)
	{
		$this->alto=$alto;
	}


	function error()
	{
		return false;
	}

	function generar($codigoBarra,$escala,$archivo="")
	{
		$this->code128($codigoBarra,$this->escala,$archivo);
			
	}

	function ean128($codigoBarra)
	{
		$tablaean128=$this->codificacionean128();

		//Inicio C
		$inicioC="11010011100";
			
		//Caracter de Parada
		$CP="11000111010";
			
			
		$suma=0;
		$posicion = 0;
		$codigoCadena="";
			
		for($i=0;$i<strlen($codigoBarra);$i+=2)
		{
			$posicion++;
			$valor=substr($codigoBarra,$i,2);
			if(is_numeric($valor))
			{
				$valorPosicion=$posicion*(int)($valor);
				$codigoCadena.=$tablaean128[(int) $valor];
			}
			else
			{
				$valorPosicion=$posicion*102;
				$codigoCadena.=$tablaean128[102];

			}
			$suma+=$valorPosicion;

		}
			

		$CC=($suma+105)%103;
			
		//echo "Suma:".$suma."<br>";
		//echo "Digito de Control:".$CC."<br>";
		return $inicioC.$codigoCadena.$tablaean128[$CC].$CP."11";
	}

	function code128($codigoBarra,$escala=1,$archivo="")
	{
			
		$codificacion=$this->ean128($codigoBarra);

		if(empty($archivo))
		{
			header("Content-type: image/png");
		}

		if ($escala<1)
		{
			$escala=1;
		}

		$total_y=(double)$escala * $this->alto+10*$escala;
			
			
		if (!isset($space))
		{
			$unEspacio=2;
			$space=array('top'=>$unEspacio*$escala,'bottom'=>$unEspacio*$escala,'left'=>$unEspacio*$escala,'right'=>$unEspacio*$escala);
		}
			
		$xpos=0;
			
		$xpos=$escala*strlen($codificacion)+2*$escala*10;

		$total_x= $xpos +$space['left']+$space['right'];
			
			
		$xpos=$space['left']+$escala*10;

		$altoSimbolo=floor($total_y-($escala*18));
		$altoSimbolo2=floor($total_y-$space['bottom']);

		$imagenCodigo=imagecreatetruecolor($total_x, $total_y);
			
		$bg_color = imagecolorallocate($imagenCodigo, $this->fondo[0], $this->fondo[1],$this->fondo[2]);
			
		imagefilledrectangle($imagenCodigo,0,0,$total_x,$total_y,$bg_color);
			
		$bar_color = imagecolorallocate($imagenCodigo, $this->color[0], $this->color[1],$this->color[2]);

		for($i=0;$i<strlen($codificacion);$i++)
		{
			$h=$altoSimbolo;
			$val=strtoupper($codificacion[$i]);

			if($val==1)
			{
				imagefilledrectangle($imagenCodigo,$xpos, $space['top'],$xpos, $h,$bar_color);
			}
			$xpos+=$escala;
		}
			
		$font_arr=imagettfbbox ( $escala*10, 0, $this->fuenteSimbolo, $codigoBarra);
		$x= floor($total_x-(int)$font_arr[0]-(int)$font_arr[2]+$escala*10)/2;
			
		$codigoCadena="";
		$posicion=0;
		for($i=0;$i<strlen($codigoBarra);$i++)
		{
			$posicion++;
			$valor=substr($codigoBarra,$i,1);
			if(is_numeric($valor))
			{
				if($posicion==3||$posicion==19||$posicion==43||$posicion==59)
				{
					$codigoCadena.="(";
				}
				else
				{
					if($posicion==6||$posicion==23||$posicion==47||$posicion==61)
					{
						$codigoCadena.=")";
					}

				}
				$codigoCadena.=$valor;
			}

		}


		imagettftext($imagenCodigo,$escala*6.5,0,$x+65, $altoSimbolo2-5, $bar_color,$this->fuenteSimbolo , $codigoCadena);

			
		if(!empty($archivo))
		{
			imagepng($imagenCodigo,$archivo.".png");
		}
		else
		{
			imagepng($imagenCodigo);
		}
		imagedestroy($imagenCodigo);
	}

	function codificacionean128()
	{

		return array(
				"11011001100","11001101100","11001100110","10010011000","10010001100","10001001100","10011001000","10011000100","10001100100","11001001000",
				"11001000100","11000100100","10110011100","10011011100","10011001110","10111001100","10011101100","10011100110","11001110010","11001011100",
				"11001001110","11011100100","11001110100","11101101110","11101001100","11100101100","11100100110","11101100100","11100110100","11100110010",
				"11011011000","11011000110","11000110110","10100011000","10001011000","10001000110","10110001000","10001101000","10001100010","11010001000",
				"11000101000","11000100010","10110111000","10110001110","10001101110","10111011000","10111000110","10001110110","11101110110","11010001110",
				"11000101110","11011101000","11011100010","11011101110","11101011000","11101000110","11100010110","11101101000","11101100010","11100011010",
				"11101111010","11001000010","11110001010","10100110000","10100001100","10010110000","10010000110","10000101100","10000100110","10110010000",
				"10110000100","10011010000","10011000010","10000110100","10000110010","11000010010","11001010000","11110111010","11000010100","10001111010",
				"10100111100","10010111100","10010011110","10111100100","10011110100","10011110010","11110100100","11110010100","11110010010","11011011110",
				"11011110110","11110110110","10101111000","10100011110","10001011110","10111101000","10111100010","11110101000","11110100010","10111011110",
				"10111101110","11101011110","11110101110","11010000100","11010010000","11010011100","11000111010");


	}

}
?>