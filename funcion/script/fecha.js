<!--
//Fecha
var nombre_dia = new Array("Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado")
var nombre_mes = new Array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre")
var de = " de ";

var hoy_es = new Date();
dia_mes = hoy_es.getDate();
dia_semana = hoy_es.getDay();
mes = hoy_es.getMonth() + 1;
anio = hoy_es.getYear();

if(anio < 100) 
   anio = '19' + anio;
else
   if((anio>100) && (anio<999) ){
	  var cadena_anio=new String(anio); 
	  anio='20' + cadena_anio.substring(1,3)
   }

function dia() {
	var today = new Date();
	var hrs = today.getHours();
	
	if(hrs < 12) document.write("Buenos Dias! Hoy es ");
	else if(hrs <= 18) document.write("Buenas Tardes! Hoy es ");
	else document.write("Buenas Noches! Hoy es ");

	document.write(nombre_dia[dia_semana] + ", " + dia_mes +  " de "  + nombre_mes[mes - 1] + " de " + anio)
}
//-->