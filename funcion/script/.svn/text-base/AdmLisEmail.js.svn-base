<!--
/**********************************************************************************   
AdmLisEmail.js
*   Copyright (C) 2006 UNIVERSIDAD DISTRITAL FRANCISCO JOSÉ DE CALDAS
*   Este script fue realizado en la Oficina Asesora de Sistemas
*   Por: Pedro Luis Manjarrés Cuello
*********************************************************************************/
function check(){
	email_dir = "";
	for(i=0; i<document.EmaiForm.elements.length; i++){
        if(document.EmaiForm.elements[i].checked==1){
           if(email_dir != "")
              email_dir += ',' + document.EmaiForm.elements[i].value;
           else
             email_dir = document.EmaiForm.elements[i].value;
        }
	}
    return email_dir;
}

function Enviar(cuentas){
	if(cuentas == "")
       alert('No ha seleccionado el destinatario'); 
    else
       //parent.location = 'mailto:'+cuentas;
	   document.ButtonForm.elements["ctas"].value = cuentas;
	   document.ButtonForm.submit();
}

function set(n){
	if(n==0)
	   document.ButtonForm.elements["ctas"].value = "";
	for(i=0; i<document.EmaiForm.elements.length; i++)
		document.EmaiForm.elements[i].checked = n;
}

function set_invert(){
	for(i=0; i<document.EmaiForm.elements.length; i++)
		document.EmaiForm.elements[i].checked = !(document.EmaiForm.elements[i].checked);
}
// -->