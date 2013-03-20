<?

function ejemplo1()
{
	$respuesta = new xajaxResponse();
	
$form="<input type='button' onclick='xajax_ejemplo2()'> ";
	
	$respuesta->addAssign("ejemplo","innerHTML",$form);
	return $respuesta;
}


function ejemplo2()
{
	$respuesta = new xajaxResponse();
		
	$respuesta->addAssign("ejemplo2","innerHTML","Hola Mundo!!!");
	return $respuesta;
}

?>