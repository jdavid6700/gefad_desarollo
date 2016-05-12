<?
///////////////////////////////////////////////////////////////////////////////////////////////
//Libreria para mostrar un calendario y obtener una fecha
//
//La p�gina que llame a esta libreria debe contener un formulario con 
//tres campos donde se introducir� el d�a el mes y el a�o que se desee
//Para que este calendario pueda actualizar los campos de formulario 
//correctos debe recibir varios datos (por GET)
//formulario (con el nombre del formulario donde estan los campos
//dia (con el nombre del campo donde se colocar� el d�a)
//mes (con el nombre del campo donde se colocar� el mes)
//ano (con el nombre del campo donde se colocar� el a�o)
///////////////////////////////////////////////////////////////////////////////////////////////
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>Calendario</title>
<link href="cal-estilo.css" rel="stylesheet" type="text/css">
<script>

function devuelveFecha(dia,mes,ano){
	//Se encarga de escribir en el formulario adecuado los valores seleccionados
	//tambi�n debe cerrar la ventana del calendario
	
	var formulario_destino = '<?echo $_REQUEST["formulario"]?>'
	var campo_destino = '<?echo $_REQUEST["nomcampo"]?>'
	
	//meto el dia
	eval ("opener.document." + formulario_destino + "." + campo_destino + ".value='" + dia + "/" + mes + "/" + ano + "'")
	window.close()
}
</script>
</head>
<body>
<?


//TOMO LOS DATOS QUE RECIBO POR LA url Y LOS COMPONGO PARA PASARLOS 
//EN SUCESIVAS EJECUCIONES DEL CALENDARIO
//$parametros_formulario = "formulario=" . $_GET["formulario"] . "&nomcampo=" . $_GET["nomcampo"];
?>
<div align="center">
<?

require_once("calendario.php");
$tiempo_actual = time();
$dia_solo_hoy = date("d",$tiempo_actual);
if (!$_POST && !isset($_GET["nuevo_mes"]) && !isset($_GET["nuevo_ano"])){
	$mes = date("m", $tiempo_actual);
	$ano = date("Y", $tiempo_actual);
}elseif ($_POST) {
	$mes = $_POST["nuevo_mes"];
	$ano = $_POST["nuevo_ano"];
}else{
	$mes = $_GET["nuevo_mes"];
	$ano = $_GET["nuevo_ano"];
}

mostrar_calendario($mes,$ano);
formularioCalendario($mes,$ano);
?>
</div>
</body>
</html>
