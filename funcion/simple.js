/*
SimpleJS ver 0.1 beta
----------------------
SimpleJS is developed by Christophe "Dyo" Lefevre (http://bleebot.com/)
*/
function $(id){
return document.getElementById(id);
}
function STO(_24,_25){
return window.setTimeout(_24,_25);
}
function DecToHexa(_26){
var _27=parseInt(_26).toString(16);
if(_26<16){
_27="0"+_27;
}
return _27;
}
function addslashes(str){
str=str.replace(/\"/g,"\\\"");
str=str.replace(/\'/g,"\\'");
return str;
}
function $toggle(id){
if(act_height(id)==0){
$blinddown(id);
}else{
$blindup(id);
}
}
function act_height(id){
height=$(id).clientHeight;
if(height==0){
height=$(id).offsetHeight;
}
return height;
}
function act_width(id){
width=$(id).clientWidth;
if(width==0){
width=$(id).offsetWidth;
}
return width;
}
function max_height(id){
var ids=$(id).style;
ids.overflow="hidden";
if(act_height(id)!=0){
return act_height(id);
}else{
origdisp=ids.display;
origheight=ids.height;
origpos=ids.position;
origvis=ids.visibility;
ids.visibility="hidden";
ids.height="";
ids.display="block";
ids.position="absolute";
height=act_height(id);
ids.display=origdisp;
ids.height=origheight;
ids.position=origpos;
ids.visibility=origvis;
return height;
}
}
function $blindup(id,_2f){
if(!_2f){
_2f=200;
}
acth=act_height(id);
maxh=max_height(id);
if(acth==maxh){
$(id).style.display="block";
var _30;
_30=Math.ceil(_2f/acth);
for(i=0;i<=acth;i++){
newh=acth-i;
STO("$('"+id+"').style.height='"+newh+"px'",_30*i);
}
}
}
function $blinddown(id,_32){
if(!_32){
_32=200;
}
acth=act_height(id);
if(acth==0){
maxh=max_height(id);
$(id).style.display="block";
$(id).style.height="0px";
var _33;
_33=Math.ceil(_32/maxh);
for(i=1;i<=maxh;i++){
STO("$('"+id+"').style.height='"+i+"px'",_33*i);
}
}
}
function $opacity(id,_35,_36,_37){
if($(id).style.width==0){
$(id).style.width=act_width(id);
}
var _38=Math.round(_37/100);
var _39=0;
if(_35>_36){
for(i=_35;i>=_36;i--){
STO("changeOpac("+i+",'"+id+"')",(_39*_38));
_39++;
}
}else{
if(_35<_36){
for(i=_35;i<=_36;i++){
STO("changeOpac("+i+",'"+id+"')",(_39*_38));
_39++;
}
}
}
}
function $pulsate(id,num,speed){
if (!speed) speed = 300;
for(i = 1; i <= num; i++) {
numx=i*((speed*2)+100)-(speed*2);
STO("$opacity('"+id+"', 100, 0, "+speed+")",numx);
STO("$opacity('"+id+"', 0, 100, "+speed+")",numx+speed+100);
}
}
function changeOpac(_3a,id){
var ids=$(id).style;
ids.opacity=(_3a/100);
ids.MozOpacity=(_3a/100);
ids.KhtmlOpacity=(_3a/100);
ids.filter="alpha(opacity="+_3a+")";
}
function $shiftOpacity(id,_3e){
if($(id).style.opacity<0.5){
$opacity(id,0,100,_3e);
}else{
$opacity(id,100,0,_3e);
}
}
function currentOpac(id,_40,_41){
var _42=100;
if($(id).style.opacity<100){
_42=$(id).style.opacity*100;
}
$opacity(id,_42,_40,_41);
}
function $highlight(id,_44,_45,_46){
if(_44){
milli=_44;
}else{
milli=900;
}
if(_45){
endcol=_45;
}else{
endcol="#FFFFFF";
}
if(_46){
origcol=_46;
}else{
origcol="#FFFFA6";
}
$colorize(origcol,endcol,id,milli,"high");
}
function $textColor(id,_48,_49,_4a){
if(_4a){
milli=_4a;
}else{
milli=900;
}
$colorize(_48,_49,id,milli,"text");
}
function $morphColor(id,_4c,_4d,_4e,_4f,_50,_51,_52){
if(_52){
milli=_52;
}else{
milli=900;
}
$colorize(_4c,_4d,id,milli,"text");
$colorize(_4e,_4f,id,milli,"back");
if(_50!=false){
$colorize(_50,_51,id,milli,"border");
}
}
function $colorize(_53,end,id,_56,_57){
dr=parseInt(_53.substring(1,3),16);
dg=parseInt(_53.substring(3,5),16);
db=parseInt(_53.substring(5,7),16);
fr=parseInt(end.substring(1,3),16);
fg=parseInt(end.substring(3,5),16);
fb=parseInt(end.substring(5,7),16);
steps=_56/10;
cr=dr;
cg=dg;
cb=db;
sr=(fr-dr)/steps;
sg=(fg-dg)/steps;
sb=(fb-db)/steps;
var zzi=10;
for(var x=0;x<steps;x++){
color="#"+DecToHexa(cr)+DecToHexa(cg)+DecToHexa(cb);
if(x==(steps-1)){
if(_57=="high"){
color="";
}else{
color=end;
}
}
mytime=(x);
if(_57=="back"||_57=="high"){
newfonc="$(\""+id+"\").style.backgroundColor=\""+color+"\";";
}else{
if(_57=="text"){
newfonc="$(\""+id+"\").style.color=\""+color+"\";";
}else{
if(_57=="border"){
newfonc="$(\""+id+"\").style.borderColor=\""+color+"\";";
}
}
}
STO(newfonc,zzi);
cr+=sr;
cg+=sg;
cb+=sb;
zzi+=10;
}
}