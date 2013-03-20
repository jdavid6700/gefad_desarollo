/**********************************************************************************   
SoloTexto 
*   Código Creado por 2006 Pedro Luis Manjarres
*   Función creada para la Universidad Distrital
*	Uso: onKeypress="return SoloTexto(event)"
*********************************************************************************/
function SoloTexto(evento) {
    tecla = (document.all) ? evento.keyCode : evento.which;
    if(tecla==8) return true;
	//patron = /\d/; 			// Solo acepta números
	//patron = /\w/; 			// Acepta números y letras
	patron = /\D/; 				// No acepta números
	//patron =/[A-Za-zñÑ\s]/; 	// Acepta las letras ñ y Ñ
    te = String.fromCharCode(tecla);
    return patron.test(te);
}