<!-- //HORA
var popUpWin=0;
function popUpWindow(URLStr, yes, left, top, width, height){
  if(popUpWin){
     if(!popUpWin.closed) 
	    popUpWin.close();
  }
  popUpWin = open(URLStr, 'popUpWin', 'resizable=no,scrollbars='+yes+',menubar=no,toolbar=no,status=no,location=no,titlebar=no,directories=no", width='+width+',height='+height+',left='+left+', top='+top+',screenX='+left+',screenY='+top+'');
}
//-->