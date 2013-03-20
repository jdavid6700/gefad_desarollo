/**********************************************************************************   
SigReg 
*   Código Creado por 2006 Pedro Luis Manjarres
*   Función creada para la Universidad Distrital
*	Uso: onKeypress="return SigReg(event, formulario, campo)"
*********************************************************************************/
function SigReg(event, forma, campo){
  var keycode = 0;
  if(event)
  if(event.which){ keycode = event.which; }
  else if(typeof(window.event) != "undefined"){ keycode = window.event.keyCode; }
  if(keycode == 13){ document.forms[forma].elements[campo].focus(); }
  return true;
}
//-->