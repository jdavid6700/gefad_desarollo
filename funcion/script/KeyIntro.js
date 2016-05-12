<!-- 
/**********************************************************************************   
KeyIntro
*   Copyright (C) 2006 UNIVERSIDAD DISTRITAL FRANCISCO JOSÉ DE CALDAS
*   Este script fue realizado en la Oficina Asesora de Sistemas
*   Por: Pedro Luis Manjarrés Cuello
*********************************************************************************/
function check_enter_key(event, submit_form){
  var keycode = 0;
  if(event)
  if(event.which){ keycode = event.which; }
  else if(typeof(window.event) != "undefined"){ keycode = window.event.keyCode; }

  if(keycode == 13){ document.getElementById("login").elements["submit"].click(); }
  return true;
}
//-->