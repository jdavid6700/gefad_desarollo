var height = 20; // height of the menu headers
var iheight = 15; // height of the menu_items

var bgc = "#FFFFFF" // COLOR DE FONDO DE LOS ITEMS
var tc = "black" // COLOR DE LA LETRAS DE LOS ITEMS

var over_bgc = "#FFB3B3";
var over_tc = "red";

var speed = 0;
var timerID = "";
var N = (document.all) ? 0 : 1;
var width = 152    //Ancho de un los cuadritos de los submenus
var self_menu = new Array();

function write_menu() {
  smc = 0; // count the position of the self_menu
  document.write("<div style='position:absolute'>");
  mn = 0;
  mni = 1;
  start = -1;

  for(i=0;i<Link.length;i++) {
   la = Link[i].split("|");
   if (la[0] == "0") {
    if(start == 0) {
      document.write("</div>");
      h =  csmc * iheight;
      tmn = mn; //-h
      self_menu[smc] = new Array(tmn,h,0,-2);
      smc++;
      mn--;
     }
     csmc = 0;
    document.write("<div class='menu' style='top:"+mn+";height:"+height+"' id='down"+smc+"' onclick='pull_down("+smc+","+mni+")'> "+ la[1] + "</div>");
    self_menu[smc] = new Array(mn,height,0,mni);
    smc++;
    mni++;
    mn+=height;
    start = 1;
   } else {
    if(start == 1) {
      if(N)mn+=2;
       document.write("<div class='item_panel' id='down"+smc+"' style='top:"+mn+"'>");
       start = 0;
     }

    document.write("<a href='"+la[2]+"'");
    if (la[3] != "") document.write(" target='" + la[3] + "' ");
    document.write("><div class='item' id='d"+i+"' style='height:"+iheight);
    if (N) document.write(";width:150");
    document.write("' onmouseover='color(this.id)' onmouseout='uncolor(this.id)'>  "+ la[1] + "</div></a>");
    csmc++;
   }
  }
  if (start == 0) {
     document.write("</div>");
     h =  csmc * iheight;
     tmn = mn + 5; //-h
     self_menu[smc] = new Array(tmn,h,0);
     name = "down" + (self_menu.length-1);
     obj = document.getElementById(name);
     obj.style.borderBottomColor = "white";
     obj.style.borderBottomWidth = 1;
     obj.style.borderBottomStyle = "solid";
   }
  document.write("</div>");
}

function color(obj) {
 document.getElementById(obj).style.backgroundColor = over_bgc;
 document.getElementById(obj).style.color = over_tc
}

function uncolor(obj) {
 document.getElementById(obj).style.backgroundColor = bgc;
 document.getElementById(obj).style.color = tc
}

function pull_down(nr,c) {
 if (timerID == "") {
 to = self_menu[nr+1][1]
 begin = nr + 2;
 if (timerID != "") clearTimeout(timerID);
 if (self_menu[nr+1][2] == 0) {
  self_menu[nr+1][2] = 1;
  if(nr == self_menu.length-2) {to++;}
  epull_down(begin,to,0);
 } else {
  to = 0;
  self_menu[nr+1][2] = 0;
  name = "down"+(nr+2);
  open_item = 0;
  for(i=0;i<nr;i++) {
   if(self_menu[i][2] == 1)
    {open_item += self_menu[i][1];
    }
  }
  if (N == false) {open_item-= (c*1)};
  if (nr== self_menu.length-2) {val = self_menu[self_menu.length-1][1];to=-1;}
  else  val = parseInt(document.getElementById(name).style.top) -(open_item)-(c*height);
  epull_up(begin,to,val);
  }
  }
}

function epull_down(nr,to,nowv) {
 name = "down" + (nr-1);
 obj = document.getElementById(name).style.clip = "rect(0,"+width+","+(nowv+1)+",0)";
 for (i=nr;i<self_menu.length;i++) {
  name = "down" + i;
  obj = document.getElementById(name);
  obj.style.top = parseInt(obj.style.top)+1;
 }
 nowv++;
 if(nowv < to) timerID = setTimeout("epull_down("+nr+","+to+","+nowv+")",speed);
 else timerID = "";
}

function epull_up(nr,to,nowv) {
 name = "down" + (nr-1);
 obj = document.getElementById(name).style.clip = "rect(0,"+width+","+nowv+",0)";
 for (i=nr;i<self_menu.length;i++) {
  name = "down" + i;
  obj = document.getElementById(name);
  obj.style.top = parseInt(obj.style.top)-1;
 }
 nowv--;
 if(nowv > to) timerID = setTimeout("epull_up("+nr+","+to+","+nowv+")",speed);
 else timerID = "";
}

function startup(nr) {
 write_menu();
 if (nr != 0) {
 for(i=0;i<self_menu.length;i++)
 {
  if(self_menu[i][3] == nr) pull_down(i,nr)
  i==self_menu.length;
 }
 }
}