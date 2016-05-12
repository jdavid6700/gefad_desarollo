<!--
//saludo
function saludo(){
   var today = new Date();
   var hrs = today.getHours();
   
   document.write("<p align=center class=Estilo5>");
   
   if(hrs < 12) document.write("Buenos Dias");
   else if(hrs <= 18) document.write("Buenas Tardes");
   else document.write("Buenas Noches");
   
   document.write("!</p>");
}
//document.writeln("<p>&nbsp;</p>");
//saludo();
//-->