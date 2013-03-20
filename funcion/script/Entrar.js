<!--
/**********************************************************************************   
Entra.js
*   Copyright (C) 2006 UNIVERSIDAD DISTRITAL FRANCISCO JOSÉ DE CALDAS
*   Este script fue realizado en la Oficina Asesora de Sistemas
*   Por: Pedro Luis Manjarrés Cuello
*********************************************************************************/
numero = Math.random().toString();
function calculaMD5(){
	var pw = document.forms["login"].elements["pass"].value
	pw += numero
	return calcMD5(pw)
}

function enviaMD5(hash) {
    valor = parseInt(document.forms["login"].elements["user"].value);

	if(isNaN(valor)) { document.forms["login"].elements["user"].value = "";}
	else{ document.forms["login"].elements["user"].value = valor; }
	
	document.forms["login"].elements["pass"].value = calcMD5(numero)+calcMD5(document.forms["login"].elements["pass"].value);
	document.forms["login"].elements["cifrado"].value = hash;
	document.forms["login"].elements["numero"].value = calcMD5(numero);
	//document.forms["login"].submit;
	document.getElementById("login").submit;
}
// -->