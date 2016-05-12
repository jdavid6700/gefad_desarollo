<!-- Hide
/**********************************************************************************   
MuestraLayer
*   Copyright (C) 2006 UNIVERSIDAD DISTRITAL FRANCISCO JOSÉ DE CALDAS
*   Este script fue realizado en la Oficina Asesora de Sistemas
*   Por: Pedro Luis Manjarrés Cuello
*********************************************************************************/
function MostrarCapa(capa,ar,iz){
  document.getElementById(capa).style.top=parseInt(ar)+'px';
  document.getElementById(capa).style.left=parseInt(iz)+'px';
  if(document.layers) document.layers[capa].visibility='show';		//Si utilizamos NS
  if(document.all) document.all(capa).style.visibility='visible';	//Si utilizamos IE
  document.getElementById(capa).style.visibility = 'visible';		//Si utilizamos Mz
  window.status = capa;
  setTimeout("borra()",10000);
}

function OcultarCapa(capa){
  if(document.layers) document.layers[capa].visibility='hide';		//Si utilizamos NS
  if(document.all) document.all(capa).style.visibility='hidden';	//Si utilizamos IE
  document.getElementById(capa).style.visibility = 'hidden'; 		//Si utilizamos Mz
  borra();
}

function borra() {
	window.status = 'Oficina Asesora de Sistemas';
}
// -->