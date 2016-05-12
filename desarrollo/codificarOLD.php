<?
echo codificar("mysql")."<br>";//Motor
echo codificar("localhost")."<br>";//Servidor
echo codificar("frame_gefad")."<br>";//DB
echo codificar("frame_gefad")."<br>";//Usuario
echo codificar("admin_gefad2013")."<br>";//Clave
echo codificar("gestion_")."<br>";//Prefijo


foreach ($fecha as $key => $value) 
	{
	
		echo $key."=>".$value."<br>";
	
	}
	
		echo count($fecha);


function decodificar($cadena)
{
	$cadena=strrev($cadena);
	$cadena=base64_decode($cadena);
	
	return $cadena;


}

function codificar($cadena)
	{
		$cadena=base64_encode($cadena);
		$cadena=strrev($cadena);
		return $cadena;
	
	}
	
?>

