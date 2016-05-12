<?
/*
############################################################################
#    UNIVERSIDAD DISTRITAL Francisco Jose de Caldas                        #
#    Copyright: Vea el archivo LICENCIA.txt que viene con la distribucion  #
############################################################################
*/
/****************************************************************************
  
estilo.php 

Paulo Cesar Coronado
Copyright (C) 2001-2007

Última revisión 6 de junio de 2007

******************************************************************************
* @subpackage   
* @package	bloques
* @copyright    
* @version      0.2
* @author      	Paulo Cesar Coronado
* @link		N/D
* @description  Definicion de estilos - es una pagina CSS
* @usage        
*****************************************************************************/


   include_once("../../clase/config.class.php");
   include_once("tema.php");

   $esta_configuracion=new config();
   $configuracion=$esta_configuracion->variable("../../"); 

    if (!isset($mi_tema)) 
    {
        $mi_tema = "basico";
	
    }

?>

h1{
    font-family: 'Times New Roman',Times,serif;
    font-size: 12pt;
    font-style: inherit;
    font-weight: 600;
    font-variant: inherit;
    color: #992114;
    background-position: center center;
    background-attachment: scroll;
    text-align: center;
    vertical-align: middle;

}

h2{
    font-family: 'Times New Roman',Times,serif;
    font-size: 10pt;
    font-style: inherit;
    font-weight: 600;
    font-variant: inherit;
    color: #992114;
    background-position: center center;
    background-attachment: scroll;
    text-align: center;
    vertical-align: middle;
    
}

body, td, th, li 
{
    font-family: "Arial", Verdana, Trebuchet MS, Helvetica, sans-serif;
}

body 
{
	text-align:center;
}

th 
{
    --font-weight: bold;
    --background-image: url(<?PHP echo $configuracion['host'].$configuracion['site'].$configuracion['estilo'].'/'.$mi_tema ?>/gradient.jpg);
}

DIV
{
	-moz-box-sizing:border-box;
	box-sizing:border-box;
	margin:0;
	padding:0;
}

a:link 
{
    text-decoration: none;
    color: <? echo $tema->enlace ?>;
}

a:visited 
{
    text-decoration: none;
    color: <? echo $tema->enlace ?>;
}

a:hover 
{
    text-decoration: none;
    color: <? echo $tema->enlace ?>;
}

a.enlace:link 
{
    text-decoration: none;
    color: #FFFFFF;
}

a.enlace:visited {
    text-decoration: none;
    color: #FFFFFF;
}


a.linkHorizontal:link 
{
    color: #000000;
}

a.linkHorizontal:visited {
    color: #000000;
}

a.linkHorizontal:hover {
    text-decoration: underline;
    color: <? echo $tema->sobreOscuro ?>;
     background-image: url(<?PHP echo $configuracion["host"].$configuracion["site"].$configuracion["estilo"].'/'.$mi_tema ?>/gradient2.jpg)
}

a.wiki:link 
{
    text-decoration: none;
    color: #0000FF;    
}

a.wiki:visited {
    text-decoration: none;
    color: #0000FF;
}

a.wiki:hover {
    text-decoration: underline;
    color: #FF0000;
    
}

hr.hr_subtitulo
{
	border: 0;
	color: #000000;
	background-color: #999999;
	height: 1px;
	width: 100%;
	text-align: left;
}

.fondoprincipal {
    background-color: <?PHP echo $tema->fondo?>;
}

.fondoPie {
    background-color: #242f47;
}

.fondoImportante 
{
    background-color: <?PHP echo $tema->apuntado?>;;
}


.tabla_general {
	padding:15px;
	background-color: <?PHP echo $tema->cuerpotabla ?>;
	width:<?PHP echo $configuracion["tamanno_gui"]?>;
	border-width: 1px;
	border-color: <?PHP echo $tema->bordes?>;
	border-style: solid;
	margin-left:auto; 
	margin-right:auto;
}

form {
    margin-bottom: 0;
}


.highlight {
    background-color: <?PHP echo $tema->highlight?>;
}

.bloquelateral {
    border-width: 1px;
    border-color: <?PHP echo $tema->bordes?>;
    border-style: solid;
    -moz-border-radius-bottomleft: 10px;
    -moz-border-radius-bottomright: 10px;
    background-color: <?PHP echo $tema->cuerpotabla?>;
}

.bloquelateral_2 {
    border-width: 1px;
    border-color: <?PHP echo $tema->bordes?>;
    border-style: solid;
    background-color: <?PHP echo $tema->celda_clara?>;
    width:100%;
    
}


.seccion_B {
    background-color: <?PHP echo $tema->fondo_B ?>;
    width:<?PHP echo $configuracion["tamanno_gui"]*(0.2) ?>;
}


td.seccion_B
{
	width:<?PHP echo $configuracion["tamanno_gui"]*(0.15) ?>%;
	border: 0px;
	border-collapse: collapse;
	border-spacing: 0px;
}

td.seccion_C
{
	width:<?PHP echo $configuracion["tamanno_gui"]*(0.6) ?>%;
	border: 0px;
	border-collapse: collapse;
	border-spacing: 0px;
}


td.seccion_D
{
	width:<?PHP echo $configuracion["tamanno_gui"]*(0.15) ?>%;
	border: 0px;
	border-collapse: collapse;
	border-spacing: 0px;
}

td.seccion_C_colapsada
{
	width:<?PHP echo $configuracion["tamanno_gui"]*(0.8) ?>%;
	border: 0px;
	border-collapse: collapse;
	border-spacing: 0px;
}


.login_celda1 
{
    background-color: #f4f5eb;
}

.cuadro_color
{
    background-color: #f4f5eb;
}

.cuadro_brown
{
    background-color: LemonChiffon;
    color:brown;
}

.cuadro_azulOscuro
{
    background-color: #F3E597;
    color:white;
}

.cuadro_brownOscuro
{
    background-color: #F3E3A4;
    color:black;
    border-width: 1px;
}

.cuadro_azulClaro
{
    background-color: #6D84F4;
    color:white;
}

toolTipBox
{
       display: none;
       padding: 5;
       font-size: 12px;
       border: black solid 1px;
       font-family: verdana;
       position: absolute;
       background-color: #ffd038;
       color: 000000;
}

.cuadro_azul
{
    background-color: #F3E3A4;
}

.cuadro_login {
    border-width: 1px;
    font-size: 12;
    font-family: "Arial", Verdana, Trebuchet MS, Helvetica, sans-serif;
    text-align: left;
}

.cuadro_plano {
    border-width: 1px;
    border:1px solid #AAAAAA;
    font-size: 12;
    font-family: "Arial", Verdana, Trebuchet MS, Helvetica, sans-serif;
    text-align: left;
}
.cuadro_planoPequeño {
    border-width: 1px;
    border:1px solid #AAAAAA;
    font-size: 10;
    font-family: "Arial", Verdana, Trebuchet MS, Helvetica, sans-serif;
    text-align: left;
}


.cuadro_simple {
    border-width: 0px;
    font-size: 12;
    font-family: "Arial", Verdana, Trebuchet MS, Helvetica, sans-serif;
    text-align: left;
    background-color: <?PHP echo $tema->celda_oscura?>;
}

.cuadro_corregir {
    font-family: "Arial", Verdana, Trebuchet MS, Helvetica, sans-serif;
    text-align: left;
    font-weight: bold;	
    background-color: #FF0000;
}

li.formal{
    font-size: 11px;
    padding-left: 17px;
    margin-left: 2px;
    margin-bottom: 5px;
    background:url(<?echo $configuracion['host'].$configuracion['site'].$configuracion['grafico']?>/Vineta.gif) bottom left no-repeat;
    List-style:none;
}

<? /*===================Estilos de Pestañas ===================================*/
/**************Encabezado cuando se muestra un registro*********************/?>

.pestanas{
    float:left;
    width:100%;
    repeat-x bottom;
    font-size:50%;
    line-height:normal;
}

.pestanas ul{
   margin:0;
    padding:10px 10px 0;
    list-style:none;

}

.pestanas li{
   float:left;
   background:url("left.gif")
      no-repeat left top;
    margin:0;
    padding:0 0 0 9px;

}

li.pestanaseleccionada{
   background-color: #F3F6FA;
   color: #2E2EFE;
   font-weight: bold;
   background-image:url("left_on.gif");
}

.cuerpopestanas{
   background-color: #FFFFFF;
   color: #000000;
   clear:both;
   margin:0px;
   padding:4px;
   height: 400px;
   overflow: auto;
   border:4px double #000000;
   padding:5px;
   -moz-border-radius:8px;
}

.pestanas a {
    float:left;
    display:block;
    background:url("right.gif")
      no-repeat right top;
    padding:5px 15px 4px 6px;

    }
    
a.pestanaseleccionada{
    float:left;
    display:block;
    background:url("right_on.gif")
      no-repeat right top;
    padding:5px 15px 4px 6px;
    }

a.pestanainactiva{
    float:left;
    display:block;
    background:url("right.gif")
      no-repeat right top;
    padding:5px 15px 4px 6px;
    }

.pestanas pestanainactiva {
    background-image:url("left_on.gif");
    }

.pestanas pestanainactiva a {
    background-image:url("right_on.gif");
    padding-bottom:5px;
    }

<? /*===================Estilos de Texto ===================================*/
/**************Encabezado cuando se muestra un registro*********************/?>
.encabezado_registro 
{
    border-width: 0px;
    font-size: 16;
    font-family: "Arial", Verdana, Trebuchet MS, Helvetica, sans-serif;
    text-align: left;
    font-weight: bold;	
}
<?/***************************************************************************/?>


<?/*==========================Estilo SIGMA============================*/
 /**************************************************************************/?>

BODY{
	background: #D7E3F1;
}
BODY.b{
	background: #FFFFFF;
}
BODY.c{
	background: #D7E3F1;
}
h1.sigma
{
	font-family: verdana,sans serif,helvetica;
	font-size: 16px;
	text-transform: UPPERCASE;
}
h2.sigma
{
	font-family: verdana,sans serif,helvetica;
	font-size: 14px;
	text-transform: UPPERCASE;
}
h3.sigma
{
	font-family: verdana,sans serif,helvetica;
	font-size: 12px
}
P.sigma
{
	font-family: verdana,sans serif,helvetica;
	font-size: 10px
}
th.sigma
{
	background: #d5d5d5;
	text-transform: capitalize;
	font-size: 11px;
	color: #000000;
}
th.sigma_a
{
	background: #23b0be;
	-moz-border-radius:7px 7px 7px 7px;
	font-weight:bold;
	font-family: verdana,sans serif,helvetica;
	font-size: 14px;
	color: #FFFFFF;
	text-transform: uppercase;
	height: 16px;
}
td.sigma
{
	font-size: 11px;
	color: #000000;
}

td.sin_inscripciones
{
	font-size: 12px;
	color: #000000;
        padding-left:210px; 
}

td.sigma_a
{
    font-size: 11px;
    color: #FFFFFF;
    background: #1E3B86;
}

td.sigma_b
{
    font-size: 11px;
    color: #000000;
    background: #E0ECF8;
    height: 16px;
}

tr.sigma
{
	background: #E4EBFE;
}

caption.sigma
{
	background: #23b0be;
	-moz-border-radius:7px 7px 7px 7px;
	font-weight:bold;
	font-family: verdana,sans serif,helvetica;
	font-size: 14px;
	color: #FFFFFF;
	text-transform: uppercase;
	height: 16px;
}
BODY.izq{
	background-repeat:repeat-y;
	background-position: right;
	font-family: verdana,sans-serif,helvetica
}
BODY.der{
	background-repeat:repeat-y;
	background-position: left;
}
table.sigma
{
	font-family: verdana,sans serif,helvetica;
	border-spacing: 3px;
	border-style: solid;
	border-width: thin;
        border: 0px;
}
table.sigma_borde
{
	font-family: verdana,sans serif,helvetica;
        font-size: 10;
        text-align: justify;
	border-collapse: collapse;
        border-spacing: 0px;
}

select.sigma
{
    font-family: verdana;
    font-size: 12px;
    color: #1E3B86;
    font-color: #1E3B86;
    background-color:#EAF0F6;
    padding-left:10px;  
    border:2px solid #1E3B86;
}

option.sigma
{
    font-family: verdana;
    font-size: 10px;
    color: #1E3B86;
    background-color:#EAF0F6;
    font-color: #1E3B86;
}

optgroup.sigma
{
    font-family: verdana;
    font-size: 10px;
    color: #1E3B86;
    background-color:#EAF0F6;
    font-color: #1E3B86;
}

input.boton
{
    border-width:1px;
    border-color:#1E3B86;
    border-style:solid;
    text-align:center;
    font-weight:bold;
    font-size:10pt;
    font-family:arial;
    background-color:#1E3B86;
    color:#FFFFFF;
}

select.boton
{
    border-width:1px;
    border-color:#1E3B86;
    border-style:solid;
    text-align:center;
    font-weight:bold;
    font-size:10pt;
    font-family:arial;
    background-color:#1E3B86;
    color:#FFFFFF;
}

option.boton
{
    border-width:0px;
    border-style:solid;
    text-align:center;
    background-color:#E6E6E6;
    color:#000000;
}

#toolTipBox {
                display: none;
                position:absolute;
                background:#E9EFE6;
                border:4px double #fff;
                text-align:left;
                padding:5px;
                -moz-border-radius:8px;
                z-index:1000;
                margin:0;
                padding:0;
                color:#1E3B86;
                font:11px/12px verdana,arial,serif;
                margin-top:3px;
                font-style:normal;
                font-weight:bold;
                opacity:0.85;
        }

p.sigma
{
   color:red;
   font-size:10px;
   font-family:Courier;
   border:2px;
   border-color:white;
   border-style:solid;
   width:200px;
   height:100px;
}
<?/***************************************************************************/?>




<?/*************************  ESTILO ADICAN  **********************************/?>

div.top
{
    font-family:"Lucida Grande",Helvetica,Arial,Verdana,sans-serif;font-size: 13px;
    position: fixed; top: 0px; left: 0px; width: 100%; height: 40px;
background: -moz-linear-gradient(top,  rgba(216,216,216,1) 0%, rgba(248,248,248,1) 74%, rgba(255,255,255,0.35) 91%, rgba(255,255,255,0) 100%); /* FF3.6+ */
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(216,216,216,1)), color-stop(74%,rgba(248,248,248,1)), color-stop(91%,rgba(255,255,255,0.35)), color-stop(100%,rgba(255,255,255,0))); /* Chrome,Safari4+ */
background: -webkit-linear-gradient(top,  rgba(216,216,216,1) 0%,rgba(248,248,248,1) 74%,rgba(255,255,255,0.35) 91%,rgba(255,255,255,0) 100%); /* Chrome10+,Safari5.1+ */
background: -o-linear-gradient(top,  rgba(216,216,216,1) 0%,rgba(248,248,248,1) 74%,rgba(255,255,255,0.35) 91%,rgba(255,255,255,0) 100%); /* Opera 11.10+ */
background: -ms-linear-gradient(top,  rgba(216,216,216,1) 0%,rgba(248,248,248,1) 74%,rgba(255,255,255,0.35) 91%,rgba(255,255,255,0) 100%); /* IE10+ */
background: linear-gradient(to bottom,  rgba(216,216,216,1) 0%,rgba(248,248,248,1) 74%,rgba(255,255,255,0.35) 91%,rgba(255,255,255,0) 100%); /* W3C */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#d8d8d8', endColorstr='#00ffffff',GradientType=0 ); /* IE6-9 */
  _position:absolute;
  _top:expression(eval(document.body.scrollTop));
  _width:110%;
}

div.enlace
{
    clear:both;
    margin:0px;padding:2px;
    width: 100%;display:block;
}

.div_fechas
{
    width: 800px;
    margin: auto;
    margin-top:-1px;
    display:-moz-compact;
}
.list_izq_inactivo
{
display:block;
float:left; padding-left: 5px;
line-height:18px;
    border:0px;
    font-family: arial,sans-serif;
    text-align:left;
    font-size: 12px;  
    color: #6E6E6E;  
}
.list_izq
{
display:block;
float:left; padding-left: 5px;
line-height:18px;
    border:0px;
    font-family: arial,sans-serif;
    text-align:left;
    font-size: 12px;  
    color: #222222;  
    cursor: pointer;
}
.list_der
{
display:block;
float:right; padding-right: 20px;
line-height:18px;
    border:0px;
    font-family: arial,sans-serif;
    text-align:left;
    font-size: 12px;  
    color: #222222;  
    cursor: pointer;
}
<?/****************************************************************************/?>

.texto_negrita {
    font-weight: bold;	
}

.texto_tituloPrincipal
{
font-size: 12px;
color:#9F2F23;
font-family: "Arial", Verdana, Trebuchet MS, Helvetica, sans-serif;
}

.texto_subtituloPrincipal
{
font-size: 8px;
color:#9F2F23;
font-family: "Arial", Verdana, Trebuchet MS, Helvetica, sans-serif;
background-color: LemonChiffon;
}

.texto_subtitulo 
{
    border-width: 0px;
    font-size: 14;
    font-family: "Arial", Verdana, Trebuchet MS, Helvetica, sans-serif;
    text-align: left;    
    color:  <?PHP echo $tema->subtitulo?>;
}

.texto_elegante 
{
    border-width: 0px;
    font-size: 13;
    font-family: "Arial", Verdana, Trebuchet MS, Helvetica, sans-serif;
    text-align: left;    
    color:  <?PHP echo $tema->subtitulo?>;
}


.texto_subtitulo_verde
{
    border-width: 0px;
    font-size: 12;
    font-family: "Arial", Verdana, Trebuchet MS, Helvetica, sans-serif;
    text-align: left;   
    background-color: #D5F5B1;
    color:  <?PHP echo $tema->subtitulo?>;
}

.texto_subtitulo_rojo
{
    border-width: 0px;
    font-size: 12;
    font-family: "Arial", Verdana, Trebuchet MS, Helvetica, sans-serif;
    text-align: left;   
    background-color: #FBBFB6;     
    color:  <?PHP echo $tema->subtitulo?>;
}

.texto_subtitulo_gris
{
    border-width: 0px;
    font-size: 12;
    font-family: "Arial", Verdana, Trebuchet MS, Helvetica, sans-serif;
    text-align: left;   
    background-color: #CCCCCC;     
    color: #000000;
}

.texto_titulo_negro
{
    border-width: 0px;
    font-size: 14;
    font-family: "Arial", Verdana, Trebuchet MS, Helvetica, sans-serif;
    text-align: left;    
    color:  <?PHP echo $tema->subtitulo?>;
}

.texto_azul
{
    color:#0000FF;
}

.texto_gris
{
    color:#555555;
}

.textoBlanco
{
    color:#FFFFFF;
}

.texto_titulo 
{
    border-width: 0px;
    font-size: 18;
    font-family: "Arial", Verdana, Trebuchet MS, Helvetica, sans-serif;
    text-align: left;
    font-weight: bold;
    color:  <?PHP echo $tema->titulo?>;
}


<? /*===================Estilos de Tablas ===================================*/?>

table.tablaBase
{
	width:100%;
	border-collapse: collapse;
}

table.tablaGrafico
{
     font: 11px Verdana, Arial, Helvetica, sans-serif;
     color: #FEFEFE;
     font-color: #FEFEFE;
     padding:7px;
}

table.tablaBase td
{
	padding: 0px;
	border: 0px;
	border-collapse: collapse;
	border-spacing: 0px;	
	border-color: <?PHP echo $tema->bordes?>;
    	border-style: solid
}





table.tablaMarcoLateral
{
	width:100%;
	
}

table.tablaMarco
{
	width:100%;
	padding:10px;
	border-collapse: collapse;
	border-spacing: 0px;	
	
}

table.tablaMarco td
{
	padding:5px;
	
}

table.tablaMarcoGeneral
{
	width:100%;
	padding:30px;
	border-collapse: collapse;
	border-spacing: 0px;	
	
}

table.tablaMarcoGeneral td
{
	padding:10px;
	
}

table.tablaMarcoPequenno
{
	width:100%;
	padding:5px;
	
}


table.contenidotabla
{
    font-size: 10;
    font-family: "Arial", Verdana, Trebuchet MS, Helvetica, sans-serif;
    text-align: justify;
    width:100%;
    border-collapse: collapse;
    border-spacing: 0px;
}

table.contenidotablaNotamanno
{
    font-size: 10;
    font-family: "Arial", Verdana, Trebuchet MS, Helvetica, sans-serif;
    text-align: justify;
    border-collapse: collapse;
    border-spacing: 0px;
}

table.tablaUniversidad
{
    width:100%;
    font-size: 10pt;
    font-family: Arial,Helvetica,sans-serif;
    background-image: url(<?PHP echo $configuracion["host"].$configuracion["site"].$configuracion["grafico"]?>/escudo_fondo.png);
    background-repeat: repeat-y;
    background-attachment: fixed;
    background-position: center center;
    border-collapse: collapse;
    border-spacing: 0px;
}

table.contenidotablaAjustable
{
	font-size: 10;
	font-family: "Arial", Verdana, Trebuchet MS, Helvetica, sans-serif;
	text-align: center;
	border-collapse: collapse;
	border-spacing: 0px;
    }
    
table.contenidotabla2 td
{
	padding:3px;
}

table.contenidotabla2 
{
	font-size: 10;
	font-family: "Arial", Verdana, Trebuchet MS, Helvetica, sans-serif;
	text-align: justify;
	width:70%;
	border-collapse: collapse;
	border-spacing: 0px;	
    }
    
table.contenidotabla td
{
	padding:3px;
}


table.Cuadricula 
{
	font-size: 11px;
	font-family: "Arial", Verdana, Trebuchet MS, Helvetica, sans-serif;
	width:100%;
	border:1px;
	border-collapse: collapse;
	border-spacing: 0px;	
	border-color: <?PHP echo $tema->bordes?>;
    	border-style: solid;
}
    
table.Cuadricula td
{
	padding:3px;
	border:1px;
	border-collapse: collapse;
	border-spacing: 0px;
	border-color: <?PHP echo $tema->bordes?>;
    	border-style: solid;
}


table.tarjeton
{
	font-size: 12px;
	font-family: "Arial", Verdana, Trebuchet MS, Helvetica, sans-serif;
	width:100%;
	border:0px;
	border-spacing: 2px;	
	border-color: <?PHP echo $tema->bordes?>;
    	border-style: solid;
}
    
table.tarjeton td
{
	padding:5px;
	border:0px;
	border-spacing: 3px;
	border-color: <?PHP echo $tema->bordes?>;
    	border-style: solid;
}

.tabla_basico {
	background-color:#F9F9F9;
	border:1px solid #AAAAAA;
	font-size:95%;
	padding:5px;
	width:90%;
	margin-left:10%; 
	margin-right:10%;
}


table.normalTarjeton 
{
	font-size: 11px;
	font-family: "Arial", Verdana, Trebuchet MS, Helvetica, sans-serif;
	width:100%;
	border:0px;
	border-collapse: collapse;
	border-spacing: 0px;	
	border-color: <?PHP echo $tema->bordes?>;
<!--     	border-style: solid; -->
}
    
table.normalTarjeton td
{
	padding:3px;
	border:0px;
	border-collapse: collapse;
	border-spacing: 0px;
	border-color: <?PHP echo $tema->bordes?>;
<!--     	border-style: solid; -->
}


table.normalTarjeton tr
{
	padding:3px;
	border:0px;
	border-collapse: collapse;
	border-spacing: 0px;
	border-color: <?PHP echo $tema->bordes?>;
<!--     	border-style: solid; -->
}

.tabla_organizacion
{
	border:0px;
	padding:10px;
	width:100%;	
}

.tabla_alerta {
	background-color:#fdffe5;
	border:1px solid #AAAAAA;
	font-size:95%;
	padding:5px;
	width:90%;
	margin-left:10%; 
	margin-right:10%;
}


.posicionTarjeton {
	background-color:#fdffe5;
	padding:5px;
	margin-left:10%; 
	margin-right:10%;
	text-align: center;
	font-size:20px;
	    font-family: "Arial", Verdana, Trebuchet MS, Helvetica, sans-serif;
    font-weight: bold;	
}


.tabla_simple 
{
	background-color:#FFFFF5;
	border:1px solid #CCCCCC;
	font-size:11px;
	width:100%;
	text-align: center;
}



.paginacentral {
    border-width: 1px;
    border-color: <?PHP echo $tema->bordes?>;
    border-style: solid;
    -moz-border-radius-bottomleft: 10px;
    -moz-border-radius-bottomright: 10px;
    -moz-border-radius-topleft: 10px;
    -moz-border-radius-topright: 10px;
    background-color: <?PHP echo $tema->cuerpotabla?>;
}

.bloquelateralencabezado {
    font-size: 13;
    font-family: "Arial", Verdana, Trebuchet MS, Helvetica, sans-serif;
    font-weight: bold;	
    background-color: <?PHP echo $tema->encabezado?>;
<!--     background-image: url(<?PHP echo $configuracion["host"].$configuracion["site"].$configuracion["estilo"].'/'.$mi_tema ?>/gradient.jpg) -->
}

.bloquelateralcuerpo {
    font-size: 11;
    font-family: "Arial", Verdana, Trebuchet MS, Helvetica, sans-serif;
    }
    
.bloquecentralencabezado {
    font-size: 13;
    font-family: "Arial", Verdana, Trebuchet MS, Helvetica, sans-serif;
    font-weight: bold;	
    background-color: <?PHP echo $tema->encabezado?>;
<!--     background-image: url(<?PHP echo $configuracion['host'].$configuracion['site'].$configuracion['estilo'].'/'.$mi_tema ?>/gradient.jpg) -->
}

.bloquecentralcuerpo {
    font-size: 12;
    font-family: "Arial", Verdana, Trebuchet MS, Helvetica, sans-serif;
    text-align: justify;
    }


    
.bloquelateralayuda {
    font-size: 10;
    font-family: "Arial", Verdana, Trebuchet MS, Helvetica, sans-serif;
    text-decoration: italic;
    }    
    
.centralcuerpo {
    font-size: 12;
    font-family: "Arial", Verdana, Trebuchet MS, Helvetica, sans-serif;
    color: #0F0F0F;
    background-color: <?PHP echo $tema->encabezado?>;
<!--     background-image: url(<?PHP echo $configuracion['host'].$configuracion['site'].$configuracion['estilo'].'/'.$mi_tema ?>/gradient.jpg) -->
}  
  
 .menuHorizontal {
    font-size: 12;
    text-align: center;
    font-family: "Arial", Verdana, Trebuchet MS, Helvetica, sans-serif;
    background-image: url(<?PHP echo $configuracion['host'].$configuracion['site'].$configuracion['estilo'].'/'.$mi_tema ?>/gradient.jpg)
}   
.centralencabezado {
    font-size: 12;
    font-family: "Arial", Verdana, Trebuchet MS, Helvetica, sans-serif;
    text-align: center;
    background-color: <?PHP echo $tema->encabezado?>;
<!--     background-image: url(<?PHP echo $configuracion['host'].$configuracion['site'].$configuracion['estilo'].'/'.$mi_tema ?>/gradient.jpg) -->
}


.centrar {
    text-align: center;
}

.derecha {
    text-align: right;
}
.izquierda {
    text-align: left;
}

.textoCentral {
    font-size: 12;
    font-family: "Arial", Verdana, Trebuchet MS, Helvetica, sans-serif;
    }

.subrayado {
    text-decoration: underline;
}
    
<? /*===================Estilos Especificos para el Proyecto WebOffice===================================*/?>
.webofficeFondoOscuro
{
	background-color: #2d3750;
	heigth:10px;
}

#cuadroLogin
{
	position:relative;
	top:0px;
	left:0px;
	
}

#menu1
{
	list-style-image:url(<?PHP echo $configuracion['host'].$configuracion['site'].$configuracion['grafico'].'/'?>/bullet1.jpg);
	
}

#menu1 li
{
	padding: 2px 0px 0px 0px;
}

#divMenu2 
{  
	padding:0px 0px 0px 0px;
	heigth:10px;
	margin:0;
	padding:0;
}

#menu2 {  
	list-style:none;
	margin:0;
	padding:0;
}

#menu2 li {
	margin:0px;
	padding:0px 5px 0px 5px;
	border:0px solid #CCCCCC;
	float:left; 
	color:#FFFFFF;
	text-align:center;
}


#mensaje1
{
	padding:5px;
}

.textoNivel1 
{
    font-size: 10px;
    font-family: "Arial", Verdana, Trebuchet MS, Helvetica, sans-serif;
}

html>body .textoNivel1 
{
    font-size: 12;
    font-family: "Arial", Verdana, Trebuchet MS, Helvetica, sans-serif;
}


.textoNivel0 
{
    font-size: 11;
    font-family: "Arial", Verdana, Trebuchet MS, Helvetica, sans-serif;
}

.textoTema
{
	color: #2d3750;
}

#notiCondor
{
	background-color:#EEEEEE;
	heigth:127px;
	
	
}

html>body #notiCondor
{
	background-image: url(<?PHP echo $configuracion['host'].$configuracion['site'].$configuracion['grafico'].'/login_principal'?>/index_2_5.jpg);
	
}

#noticiero 
{  
	position:relative;
	overflow:auto;
	margin:2px;
	padding:0px 5px 0px 5px;
	height:110px;	
}


table.formulario td {
	border-width: 1px 1px 1px 1px;
	padding: 5px 5px 5px 5px;
	border-style: inset inset inset inset;
	border-color: #DEDEDE #DEDEDE #DEDEDE #DEDEDE;
	-moz-border-radius: 0px 0px 0px 0px;
}


table.formulario {
	border-width: 1px 1px 1px 1px;
	border-spacing: 2px;
	border-style: outset outset outset outset;
	border-color: #DEDEDE #DEDEDE #DEDEDE #DEDEDE;
	border-collapse: collapse;
	background-color:#FFFFFF;
	border:1px solid #DEDEDE;
	font-size:12px;
	padding:10px;
	width:100%;
}


table.tablaImportante
{
	border-width: 0px 0px 1px 15px;
	border-style:solid;
	border-color:#b30012;
	width:100%;
	padding:10px;
	border-collapse: collapse;
	border-spacing: 0px;	
	
}

table.tablaImportante td
{
	padding:5px;
	background-color:#FFFFFF;
	
}

.ancho10
{
	width: 10%;
}


#datosbasicos {
width:100%;
display: block;
background-color:#FDF4E1;
}
#infonbc {
width:100%;
display: none;
background-color:#FDF4E1;
}
#infoduracion {
width:100%;
display: none;
background-color:#FDF4E1;
}
#infoacreditacion {
width:100%;
display: none;
background-color:#FDF4E1;
}
#infoadicional {
width:100%;
display: none;
background-color:#FDF4E1;
}

.mostrar{
	background:transparent url(<?PHP echo $configuracion['host'].$configuracion['site'].$configuracion['grafico'].'/'?>tab.jpg) repeat scroll 0 0;
	display:block;
	font-size:12px;
	height:15px;
	padding:5px;
	width:120px;
	text-decoration: none;
	background-repeat:no-repeat;
}


<? /*===================botones enlaces===================================*/?>


.botonEnlacePreinscripcion {  
    background-color:#FFFFFF;
    border:0px;
    font-family: arial,sans-serif;
    text-align:left;
    font-size: 12px;  
    color: #222222;  
    cursor: pointer;
    line-height: 30px;
    width:100%;
   
}
.botonEnlacePreinscripcion2 {  
    background-color:#FFFFFF;
    border:0px;
    font-family: arial,sans-serif;
    text-align:left;
    font-size: 10px;  
    color: #222222;  
    cursor: pointer;
    line-height: 12px;
    width:100%;
}

.botonEnlaceInscripcion {
    clear:both;
    background-color:#FFFFFF;
    border:0px;
    font-family: arial,sans-serif;
    text-align:center;
    font-size: 12px;  
    color: #222222;  
    cursor: pointer;
    line-height: 15px;
    width:50%;
}


.botonEnlaceInscripcionHoras {
    clear:both;
    background-color:#FFFFFF;
    border:0px;
    font-family: arial,sans-serif;
    text-align:center;
    font-size: 12px;  
    color: #222222;  
    cursor: pointer;
    line-height: 15px;
    width:100%;
}

.botonEnlacePreinscripcionDeshabilitado {  
    background-color:#FFFFFF;
    border:0px;
    font-family: arial,sans-serif;
    text-align:left;
    font-size: 12px;  
    color: #999999;  
    cursor: pointer;
    line-height: 28px;
    width:100%;
   
}

.botonEnlacePreinscripcion:hover {
    background-color:#EEEEEE;
    color: #222222;
    }

.botonEnlaceInscripcion:hover {
    background-color:#EEEEEE;
    color: #222222;
    }

.botonEnlaceInscripcionHoras:hover {
    background-color:#EEEEEE;
    color: #222222;
    }

.ab_name {
    color: #DD4B39;
    font-family: arial,sans-serif;
    font-size: small;
    font-weight: bold;
}

.cuadro_espacio{display:inline-block; font-size: 12px; vertical-align: middle;line-height: 15px;
 }

  .cuadro_clase
 {
    border-bottom:1px dotted #CCC;
 }
 
.cuadro_plano_centrar
{
vertical-align:middle; display:inline-block;text-align:center; font-size: 12px;line-height:15px;
 }

.cuadro_plano_derecha
{
h vertical-align:top; border-bottom:1px dotted #CCC; /*height:30px;*/display:inline-block;text-align:right; font-size: 12px;
 }

.contenidotabla_equivalencias{ width: 100%; }

/*Columnas*/

.columna0{
font-size: 14px; width: 99%;}

.columna_1{ 
display: inline-block;width: 40%;vertical-align: top; padding-left: 3px;}

.columna_2{ 
display: inline-block;width: 30%;vertical-align: top;}

.columna_3{ 
display: inline-block;width: 28%;vertical-align: top;}

.columna2{

border-top: 1px solid #CCC;border-right: 1px solid #CCC;border-bottom: 1px solid #CCC;border-left: 1px solid #CCC;float:left;
background: rgb(255,255,255); /* Old browsers */
background: -moz-linear-gradient(top, rgba(255,255,255,1) 0%, rgba(252,252,252,1) 91%); /* FF3.6+ */
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(255,255,255,1)), color-stop(91%,rgba(252,252,252,1))); /* Chrome,Safari4+ */
background: -webkit-linear-gradient(top, rgba(255,255,255,1) 0%,rgba(252,252,252,1) 91%); /* Chrome10+,Safari5.1+ */
background: -o-linear-gradient(top, rgba(255,255,255,1) 0%,rgba(252,252,252,1) 91%); /* Opera 11.10+ */
background: -ms-linear-gradient(top, rgba(255,255,255,1) 0%,rgba(252,252,252,1) 91%); /* IE10+ */
background: linear-gradient(to bottom, rgba(255,255,255,1) 0%,rgba(252,252,252,1) 91%); /* W3C */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#fcfcfc',GradientType=0 ); /* IE6-9 */
}

.columna3{

border-right:1px solid #CCC;border-bottom: 1px solid #CCC; border-top: 1px solid #CCC; border-left: 1px solid #CCC;float:center;

}

.columna4{

margin-bottom: 5px;
margin-top: 10px;


}

.tablaEspacios{
display:block;
clear:both;
border-top: 1px solid #CCC;
border-right: 1px solid #CCC;
border-bottom: 1px solid #CCC;
border-left: 1px solid #CCC;
border-radius: 5px 5px 5px 5px;
margin-bottom: 10px;
}

.tablaobservaciones{
border-top: 1px solid #CCC;
border-right: 1px solid #CCC;
border-bottom: 1px solid #CCC;
border-left: 1px solid #CCC;
border-radius: 5px 5px 5px 5px;
margin-bottom: 10px;
font-size:12px;
}

.tablaClasificaciones
{
/* IE9 SVG, needs conditional override of 'filter' to 'none' */
background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPGxpbmVhckdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIwJSIgeTI9IjEwMCUiPgogICAgPHN0b3Agb2Zmc2V0PSIwJSIgc3RvcC1jb2xvcj0iI2ZmZmZmZiIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9Ijk5JSIgc3RvcC1jb2xvcj0iI2ZmZmZmZiIgc3RvcC1vcGFjaXR5PSIwIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjEwMCUiIHN0b3AtY29sb3I9IiNmZmZmZmYiIHN0b3Atb3BhY2l0eT0iMCIvPgogIDwvbGluZWFyR3JhZGllbnQ+CiAgPHJlY3QgeD0iMCIgeT0iMCIgd2lkdGg9IjEiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+);
background: -moz-linear-gradient(top,  rgba(255,255,255,1) 0%, rgba(255,255,255,0) 99%, rgba(255,255,255,0) 100%); /* FF3.6+ */
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(255,255,255,1)), color-stop(99%,rgba(255,255,255,0)), color-stop(100%,rgba(255,255,255,0))); /* Chrome,Safari4+ */
background: -webkit-linear-gradient(top,  rgba(255,255,255,1) 0%,rgba(255,255,255,0) 99%,rgba(255,255,255,0) 100%); /* Chrome10+,Safari5.1+ */
background: -o-linear-gradient(top,  rgba(255,255,255,1) 0%,rgba(255,255,255,0) 99%,rgba(255,255,255,0) 100%); /* Opera 11.10+ */
background: -ms-linear-gradient(top,  rgba(255,255,255,1) 0%,rgba(255,255,255,0) 99%,rgba(255,255,255,0) 100%); /* IE10+ */
background: linear-gradient(to bottom,  rgba(255,255,255,1) 0%,rgba(255,255,255,0) 99%,rgba(255,255,255,0) 100%); /* W3C */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#00ffffff',GradientType=0 ); /* IE6-8 */

border-top: 1px solid #CCC;border-right: 1px solid #CCC;border-bottom: 1px solid #CCC;border-left: 1px solid #CCC;border-radius: 5px 5px 5px 5px;margin-bottom: 10px;
}
.tablaCreditos
{
display:-moz-compact;
clear:both;
margin-left:auto;
margin-right:auto;
width:400px;
margin-bottom:1px;
padding-left:1px;
}

/*Titulo espacios permitidos*/
.espacios_permitidos{
font-size: 14px;
background: rgb(181,189,200); 
background: -moz-linear-gradient(top, rgba(181,189,200,1) 0%, rgba(130,140,149,1) 36%, rgba(40,52,59,1) 100%); 
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(181,189,200,1)), color-stop(36%,rgba(130,140,149,1)), color-stop(100%,rgba(40,52,59,1))); 
background: -webkit-linear-gradient(top, rgba(181,189,200,1) 0%,rgba(130,140,149,1) 36%,rgba(40,52,59,1) 100%); 
background: -o-linear-gradient(top, rgba(181,189,200,1) 0%,rgba(130,140,149,1) 36%,rgba(40,52,59,1) 100%); 
background: -ms-linear-gradient(top, rgba(181,189,200,1) 0%,rgba(130,140,149,1) 36%,rgba(40,52,59,1) 100%); 
background: linear-gradient(to bottom, rgba(181,189,200,1) 0%,rgba(130,140,149,1) 36%,rgba(40,52,59,1) 100%); 
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#b5bdc8', endColorstr='#28343b',GradientType=0 ); 
color: #FFF;
padding: 10px;
border-radius:5px 5px 0px 0px;
text-align: left;
}

/*Titulo espacios permitidos*/
.espacios_horario{
font-size: 14px;
background: rgb(181,189,200); 
background: -moz-linear-gradient(top, rgba(181,189,200,1) 0%, rgba(130,140,149,1) 36%, rgba(40,52,59,1) 100%); 
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(181,189,200,1)), color-stop(36%,rgba(130,140,149,1)), color-stop(100%,rgba(40,52,59,1))); 
background: -webkit-linear-gradient(top, rgba(181,189,200,1) 0%,rgba(130,140,149,1) 36%,rgba(40,52,59,1) 100%); 
background: -o-linear-gradient(top, rgba(181,189,200,1) 0%,rgba(130,140,149,1) 36%,rgba(40,52,59,1) 100%); 
background: -ms-linear-gradient(top, rgba(181,189,200,1) 0%,rgba(130,140,149,1) 36%,rgba(40,52,59,1) 100%); 
background: linear-gradient(to bottom, rgba(181,189,200,1) 0%,rgba(130,140,149,1) 36%,rgba(40,52,59,1) 100%); 
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#b5bdc8', endColorstr='#28343b',GradientType=0 ); 
color: #FFF;
padding: 1px;
border-radius:5px 5px 0px 0px;
text-align: left;
}

/*Para niveles*/
.niveles
{

font-size: 12px;
background: rgb(254,255,232); 
background: -moz-linear-gradient(top,  rgba(254,255,232,1) 0%, rgba(214,219,191,1) 100%); 
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(254,255,232,1)), color-stop(100%,rgba(214,219,191,1))); 
background: -webkit-linear-gradient(top,  rgba(254,255,232,1) 0%,rgba(214,219,191,1) 100%); 
background: -o-linear-gradient(top,  rgba(254,255,232,1) 0%,rgba(214,219,191,1) 100%); 
background: -ms-linear-gradient(top,  rgba(254,255,232,1) 0%,rgba(214,219,191,1) 100%); 
background: linear-gradient(to bottom,  rgba(254,255,232,1) 0%,rgba(214,219,191,1) 100%); 
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#feffe8', endColorstr='#d6dbbf',GradientType=0 ); 
border-radius:5px 5px 0px 0px;

}

/*Para datos*/
.datos
{
width: 100%;
height:15px;display:inline-block;text-align:left; font-size: 12px;
}


/* Horario*/
.horario{
font-size: 14px;
background: rgb(181,189,200); 
background: -moz-linear-gradient(top, rgba(181,189,200,1) 0%, rgba(130,140,149,1) 36%, rgba(40,52,59,1) 100%); 
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(181,189,200,1)), color-stop(36%,rgba(130,140,149,1)), color-stop(100%,rgba(40,52,59,1))); 
background: -webkit-linear-gradient(top, rgba(181,189,200,1) 0%,rgba(130,140,149,1) 36%,rgba(40,52,59,1) 100%); 
background: -o-linear-gradient(top, rgba(181,189,200,1) 0%,rgba(130,140,149,1) 36%,rgba(40,52,59,1) 100%); 
background: -ms-linear-gradient(top, rgba(181,189,200,1) 0%,rgba(130,140,149,1) 36%,rgba(40,52,59,1) 100%); 
background: linear-gradient(to bottom, rgba(181,189,200,1) 0%,rgba(130,140,149,1) 36%,rgba(40,52,59,1) 100%); 
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#b5bdc8', endColorstr='#28343b',GradientType=0 ); 
color: #FFF;
padding:2px;
border-radius:5px 5px 0px 0px;
text-align: center;
float:left;
width:100%;
display:inline-block;
}


/* Horario*/
.tabla_creditos{
font-size: 14px;
background: rgb(181,189,200); 
background: -moz-linear-gradient(top, rgba(181,189,200,1) 0%, rgba(130,140,149,1) 36%, rgba(40,52,59,1) 100%); 
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(181,189,200,1)), color-stop(36%,rgba(130,140,149,1)), color-stop(100%,rgba(40,52,59,1))); 
background: -webkit-linear-gradient(top, rgba(181,189,200,1) 0%,rgba(130,140,149,1) 36%,rgba(40,52,59,1) 100%); 
background: -o-linear-gradient(top, rgba(181,189,200,1) 0%,rgba(130,140,149,1) 36%,rgba(40,52,59,1) 100%); 
background: -ms-linear-gradient(top, rgba(181,189,200,1) 0%,rgba(130,140,149,1) 36%,rgba(40,52,59,1) 100%); 
background: linear-gradient(to bottom, rgba(181,189,200,1) 0%,rgba(130,140,149,1) 36%,rgba(40,52,59,1) 100%); 
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#b5bdc8', endColorstr='#28343b',GradientType=0 ); 
color: #FFF;
padding:2px;
border-radius:5px 5px 0px 0px;
text-align: center;

}

/*fechas adiciones y cancelaciones*/
.fechas{
font-size: 12px;
background: rgb(181,189,200); 
background: -moz-linear-gradient(top, rgba(181,189,200,1) 0%, rgba(130,140,149,1) 36%, rgba(40,52,59,1) 100%); 
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(181,189,200,1)), color-stop(36%,rgba(130,140,149,1)), color-stop(100%,rgba(40,52,59,1))); 
background: -webkit-linear-gradient(top, rgba(181,189,200,1) 0%,rgba(130,140,149,1) 36%,rgba(40,52,59,1) 100%); 
background: -o-linear-gradient(top, rgba(181,189,200,1) 0%,rgba(130,140,149,1) 36%,rgba(40,52,59,1) 100%); 
background: -ms-linear-gradient(top, rgba(181,189,200,1) 0%,rgba(130,140,149,1) 36%,rgba(40,52,59,1) 100%); 
background: linear-gradient(to bottom, rgba(181,189,200,1) 0%,rgba(130,140,149,1) 36%,rgba(40,52,59,1) 100%); 
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#b5bdc8', endColorstr='#28343b',GradientType=0 ); 
color: #FFF;
padding: 1px;
border-radius:5px 5px 0px 0px;
text-align: center;
width:400px;

}

.creditosAprobados{
font-size: 12px;
background: rgb(181,189,200); 
background: -moz-linear-gradient(top, rgba(181,189,200,1) 0%, rgba(130,140,149,1) 36%, rgba(40,52,59,1) 100%); 
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(181,189,200,1)), color-stop(36%,rgba(130,140,149,1)), color-stop(100%,rgba(40,52,59,1))); 
background: -webkit-linear-gradient(top, rgba(181,189,200,1) 0%,rgba(130,140,149,1) 36%,rgba(40,52,59,1) 100%); 
background: -o-linear-gradient(top, rgba(181,189,200,1) 0%,rgba(130,140,149,1) 36%,rgba(40,52,59,1) 100%); 
background: -ms-linear-gradient(top, rgba(181,189,200,1) 0%,rgba(130,140,149,1) 36%,rgba(40,52,59,1) 100%); 
background: linear-gradient(to bottom, rgba(181,189,200,1) 0%,rgba(130,140,149,1) 36%,rgba(40,52,59,1) 100%); 
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#b5bdc8', endColorstr='#28343b',GradientType=0 ); 
color: #FFF;
padding: 1px;
border-radius:5px 5px 0px 0px;
text-align: center;

}

/*Observaciones adiciones y cancelaciones*/
.observaciones{
font-size: 12px;
background: rgb(181,189,200); 
background: -moz-linear-gradient(top, rgba(181,189,200,1) 0%, rgba(130,140,149,1) 36%, rgba(40,52,59,1) 100%); 
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(181,189,200,1)), color-stop(36%,rgba(130,140,149,1)), color-stop(100%,rgba(40,52,59,1))); 
background: -webkit-linear-gradient(top, rgba(181,189,200,1) 0%,rgba(130,140,149,1) 36%,rgba(40,52,59,1) 100%); 
background: -o-linear-gradient(top, rgba(181,189,200,1) 0%,rgba(130,140,149,1) 36%,rgba(40,52,59,1) 100%); 
background: -ms-linear-gradient(top, rgba(181,189,200,1) 0%,rgba(130,140,149,1) 36%,rgba(40,52,59,1) 100%); 
background: linear-gradient(to bottom, rgba(181,189,200,1) 0%,rgba(130,140,149,1) 36%,rgba(40,52,59,1) 100%); 
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#b5bdc8', endColorstr='#28343b',GradientType=0 ); 
color: #FFF;
padding: 1px;
border-radius:5px 5px 0px 0px;
text-align: center;
}

.contenedor_fechas
{
    display:block;
    width: 45%;
    clear:both;
    float:left;
    padding:5px;
}

.contenedor_observaciones{width: 55%; float:right; padding:5px; }
.fecha_inicio{ width: 46%;display: inline-block;vertical-align: top; font-size: 10px; text-align: center;}
.separador{ width: 5%;display: inline-block;vertical-align: top; font-size: 10px; text-align: center;}
.fecha_fin{ width: 46%;display: inline-block;vertical-align: top; font-size: 10px; text-align: center; }
.contenedor_fechas_titulo{width: 100%; font-size: 10px;}
.contenedor_fechas_fechas{width: 100%; font-size: 10px;}
.contenedor_abreviaturas{margin-left:auto;margin-right:auto;width: 80%; float:center;}
}


/*Para niveles*/
.creditos
{

font-size: 11px;
background: rgb(254,255,232); 
background: -moz-linear-gradient(top,  rgba(254,255,232,1) 0%, rgba(214,219,191,1) 100%); 
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(254,255,232,1)), color-stop(100%,rgba(214,219,191,1))); 
background: -webkit-linear-gradient(top,  rgba(254,255,232,1) 0%,rgba(214,219,191,1) 100%); 
background: -o-linear-gradient(top,  rgba(254,255,232,1) 0%,rgba(214,219,191,1) 100%); 
background: -ms-linear-gradient(top,  rgba(254,255,232,1) 0%,rgba(214,219,191,1) 100%); 
background: linear-gradient(to bottom,  rgba(254,255,232,1) 0%,rgba(214,219,191,1) 100%); 
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#feffe8', endColorstr='#d6dbbf',GradientType=0 ); 
border-radius:5px 5px 0px 0px;
}

.contenidotablaCreditos
{
    font-size: 11px;
    font-family: "Arial", Verdana, Trebuchet MS, Helvetica, sans-serif;
    text-align: justify;
    width:400px;
    border-collapse: collapse;
    border-spacing: 0px;
    /*opacity:0.85;*/
    color:#fffffa;
    /*background-color:#fffffa;*/
    border-top: 1px solid #CCC;
    font-weight: bold;
    
    
}
   
.nombre{font-size: 11px; float:left;margin-left:auto; margin-right:auto; padding-left:17px;width:65%; text-align: center;}
.abreviatura{font-size: 11px; float:left;margin-left:auto; margin-right:auto;padding-right:17px; width:35%; text-align: center;}
.tablet
{
background-image: url(<?echo $configuracion['site'].$configuracion['grafico']."/tablet4.png";?>);
width: 100%;
float:right;
background-repeat: no-repeat;
padding-top: 70px;
height:375px;
background-position: bottom center;
}

.porcentajes
{
    /* IE9 SVG, needs conditional override of 'filter' to 'none' */
background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPGxpbmVhckdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIxMDAlIiB5Mj0iMCUiPgogICAgPHN0b3Agb2Zmc2V0PSIwJSIgc3RvcC1jb2xvcj0iI2ZmZmNlYSIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjEwMCUiIHN0b3AtY29sb3I9IiNmZmZmZmYiIHN0b3Atb3BhY2l0eT0iMCIvPgogIDwvbGluZWFyR3JhZGllbnQ+CiAgPHJlY3QgeD0iMCIgeT0iMCIgd2lkdGg9IjEiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+);
background: -moz-linear-gradient(left,  rgba(255,252,234,1) 0%, rgba(255,255,255,0) 100%); /* FF3.6+ */
background: -webkit-gradient(linear, left top, right top, color-stop(0%,rgba(255,252,234,1)), color-stop(100%,rgba(255,255,255,0))); /* Chrome,Safari4+ */
background: -webkit-linear-gradient(left,  rgba(255,252,234,1) 0%,rgba(255,255,255,0) 100%); /* Chrome10+,Safari5.1+ */
background: -o-linear-gradient(left,  rgba(255,252,234,1) 0%,rgba(255,255,255,0) 100%); /* Opera 11.10+ */
background: -ms-linear-gradient(left,  rgba(255,252,234,1) 0%,rgba(255,255,255,0) 100%); /* IE10+ */
background: linear-gradient(to right,  rgba(255,252,234,1) 0%,rgba(255,255,255,0) 100%); /* W3C */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#fffcea', endColorstr='#00ffffff',GradientType=1 ); /* IE6-8 */
    font-size: 11px;
    color: #000000;
}

.numeroEspacios
{
    font-size: 12px;
    display:block;
    clear:both;
    float:right;
    padding:5px;
}
   
/*cambiar grupo*/
.nombreEspacio
{
font-size: 14px;
background: rgb(181,189,200); 
background: -moz-linear-gradient(top, rgba(181,189,200,1) 0%, rgba(130,140,149,1) 36%, rgba(40,52,59,1) 100%); 
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(181,189,200,1)), color-stop(36%,rgba(130,140,149,1)), color-stop(100%,rgba(40,52,59,1))); 
background: -webkit-linear-gradient(top, rgba(181,189,200,1) 0%,rgba(130,140,149,1) 36%,rgba(40,52,59,1) 100%); 
background: -o-linear-gradient(top, rgba(181,189,200,1) 0%,rgba(130,140,149,1) 36%,rgba(40,52,59,1) 100%); 
background: -ms-linear-gradient(top, rgba(181,189,200,1) 0%,rgba(130,140,149,1) 36%,rgba(40,52,59,1) 100%); 
background: linear-gradient(to bottom, rgba(181,189,200,1) 0%,rgba(130,140,149,1) 36%,rgba(40,52,59,1) 100%); 
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#b5bdc8', endColorstr='#28343b',GradientType=0 ); 
color: #FFF;
padding: 2px;
border-radius:5px 5px 0px 0px;
text-align: left;
}


/*cambiar grupo*/
.nombrecarrera{
font-size: 13px;
background: rgb(240,242,234); /* Old browsers */
background: -moz-linear-gradient(top, rgba(240,242,234,1) 1%, rgba(252,249,249,1) 100%); /* FF3.6+ */
background: -webkit-gradient(linear, left top, left bottom, color-stop(1%,rgba(240,242,234,1)), color-stop(100%,rgba(252,249,249,1))); /* Chrome,Safari4+ */
background: -webkit-linear-gradient(top, rgba(240,242,234,1) 1%,rgba(252,249,249,1) 100%); /* Chrome10+,Safari5.1+ */
background: -o-linear-gradient(top, rgba(240,242,234,1) 1%,rgba(252,249,249,1) 100%); /* Opera 11.10+ */
background: -ms-linear-gradient(top, rgba(240,242,234,1) 1%,rgba(252,249,249,1) 100%); /* IE10+ */
background: linear-gradient(to bottom, rgba(240,242,234,1) 1%,rgba(252,249,249,1) 100%); /* W3C */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#f0f2ea', endColorstr='#fcf9f9',GradientType=0 ); /* IE6-9 */
padding: 1px;
border-radius:0px 0px 0px 0px;
text-align: center;
}

/*cambiar grupo*/
.nombreobservacion{
width: 100%;
_width: 100%;
font-size: 12px;
color:white;
background: rgb(181,189,200); 
background: -moz-linear-gradient(top, rgba(181,189,200,1) 0%, rgba(130,140,149,1) 36%, rgba(40,52,59,1) 100%); 
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(181,189,200,1)), color-stop(36%,rgba(130,140,149,1)), color-stop(100%,rgba(40,52,59,1))); 
background: -webkit-linear-gradient(top, rgba(181,189,200,1) 0%,rgba(130,140,149,1) 36%,rgba(40,52,59,1) 100%); 
background: -o-linear-gradient(top, rgba(181,189,200,1) 0%,rgba(130,140,149,1) 36%,rgba(40,52,59,1) 100%); 
background: -ms-linear-gradient(top, rgba(181,189,200,1) 0%,rgba(130,140,149,1) 36%,rgba(40,52,59,1) 100%); 
background: linear-gradient(to bottom, rgba(181,189,200,1) 0%,rgba(130,140,149,1) 36%,rgba(40,52,59,1) 100%); 
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#b5bdc8', endColorstr='#28343b',GradientType=0 ); 
padding: 1px;
border-radius:5px 5px 0px 0px;
text-align: center;
}
.scroll
{
    font-size: 12px;
    clear:both;
    float:none;
    padding:5px;
}


/*HOMOLOGACIONES*/
.espacios_proyecto{

background: rgb(171,189,115); /* Old browsers */
background: -moz-linear-gradient(top, rgba(171,189,115,1) 0%, rgba(228,239,192,1) 100%); /* FF3.6+ */
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(171,189,115,1)), color-stop(100%,rgba(228,239,192,1))); /* Chrome,Safari4+ */
background: -webkit-linear-gradient(top, rgba(171,189,115,1) 0%,rgba(228,239,192,1) 100%); /* Chrome10+,Safari5.1+ */
background: -o-linear-gradient(top, rgba(171,189,115,1) 0%,rgba(228,239,192,1) 100%); /* Opera 11.10+ */
background: -ms-linear-gradient(top, rgba(171,189,115,1) 0%,rgba(228,239,192,1) 100%); /* IE10+ */
background: linear-gradient(to bottom, rgba(171,189,115,1) 0%,rgba(228,239,192,1) 100%); /* W3C */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#abbd73', endColorstr='#e4efc0',GradientType=0 ); /* IE6-9 */
padding: 1px;
border-radius:6px 6px 0px 0px;
text-align: center;
font-size: 12px;

}
.sub_espacios_proyecto{

background: rgb(228,239,192); /* Old browsers */
background: -moz-linear-gradient(top, rgba(228,239,192,1) 0%, rgba(171,189,115,1) 100%); /* FF3.6+ */
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(228,239,192,1)), color-stop(100%,rgba(171,189,115,1))); /* Chrome,Safari4+ */
background: -webkit-linear-gradient(top, rgba(228,239,192,1) 0%,rgba(171,189,115,1) 100%); /* Chrome10+,Safari5.1+ */
background: -o-linear-gradient(top, rgba(228,239,192,1) 0%,rgba(171,189,115,1) 100%); /* Opera 11.10+ */
background: -ms-linear-gradient(top, rgba(228,239,192,1) 0%,rgba(171,189,115,1) 100%); /* IE10+ */
background: linear-gradient(to bottom, rgba(228,239,192,1) 0%,rgba(171,189,115,1) 100%); /* W3C */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#e4efc0', endColorstr='#abbd73',GradientType=0 ); /* IE6-9 */
padding: 1px;
text-align: center;
font-size: 12px;
}

.espacios_homologos{
background: rgb(224,239,249); /* Old browsers */
background: -moz-linear-gradient(top, rgba(224,239,249,1) 0%, rgba(242,246,248,1) 100%); /* FF3.6+ */
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(224,239,249,1)), color-stop(100%,rgba(242,246,248,1))); /* Chrome,Safari4+ */
background: -webkit-linear-gradient(top, rgba(224,239,249,1) 0%,rgba(242,246,248,1) 100%); /* Chrome10+,Safari5.1+ */
background: -o-linear-gradient(top, rgba(224,239,249,1) 0%,rgba(242,246,248,1) 100%); /* Opera 11.10+ */
background: -ms-linear-gradient(top, rgba(224,239,249,1) 0%,rgba(242,246,248,1) 100%); /* IE10+ */
background: linear-gradient(to bottom, rgba(224,239,249,1) 0%,rgba(242,246,248,1) 100%); /* W3C */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#e0eff9', endColorstr='#f2f6f8',GradientType=0 ); /* IE6-9 */
padding: 1px;
border-radius:6px 6px 0px 0px;
text-align: center;
font-size: 12px;
}
.sub_espacios_homologos{
background: rgb(242,246,248); /* Old browsers */
background: -moz-linear-gradient(top, rgba(242,246,248,1) 0%, rgba(181,198,208,1) 100%, rgba(224,239,249,1) 100%); /* FF3.6+ */
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(242,246,248,1)), color-stop(100%,rgba(181,198,208,1)), color-stop(100%,rgba(224,239,249,1))); /* Chrome,Safari4+ */
background: -webkit-linear-gradient(top, rgba(242,246,248,1) 0%,rgba(181,198,208,1) 100%,rgba(224,239,249,1) 100%); /* Chrome10+,Safari5.1+ */
background: -o-linear-gradient(top, rgba(242,246,248,1) 0%,rgba(181,198,208,1) 100%,rgba(224,239,249,1) 100%); /* Opera 11.10+ */
background: -ms-linear-gradient(top, rgba(242,246,248,1) 0%,rgba(181,198,208,1) 100%,rgba(224,239,249,1) 100%); /* IE10+ */
background: linear-gradient(to bottom, rgba(242,246,248,1) 0%,rgba(181,198,208,1) 100%,rgba(224,239,249,1) 100%); /* W3C */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#f2f6f8', endColorstr='#e0eff9',GradientType=0 ); /* IE6-9 */
padding: 1px;
text-align: center;
font-size: 12px;
}


#navbar{  
  	width:auto;  
	height:36px;  
	background:url(img/navbar-bg.png) left top repeat-x; 
}  
  
#navbar .inbar{  
  
	display:block;  
	height:36px;  
	background:url(img/right-round.png) right top no-repeat; 
}  
  
#navbar ul, #navbar ul li{  
	border:0px;  
	margin:0px;  
	padding:0px;  
	list-style:none;  
	height:36px;  
	line-height:36px; 
}


 #navbar ul{  
  
	background:url(img/left-round.png) left top no-repeat; }  
  
#navbar ul li{  
  
float:left;  
  
display:block;  
  
line-height:36px; }  
  
#navbar ul li a{  
  
color:#403e32;  
  
text-decoration:none;  
  
font-weight:bold;  
  
display:block; }  
  
#navbar ul li a span{  
  
padding:0 20px 0 0;  
  
height:36px;  
  
line-height:36px;  
  
display:block;  
  
margin-left:20px; }  
  
#navbar .navhome a, #navbar .navhome a:hover{  
  
background:url(img/a-bg.png) left top no-repeat;  
  
height:36px;  
  
line-height:36px; }  
  
#navbar .navhome a span, #navbar .navhome a:hover span{  
  
color:#FFFFFF;  
  
background:url(img/span-bg.png) right top no-repeat;  
  
height:36px;  
  
line-height:36px; }  
  
#navbar ul li a:hover{  
  
background:url(img/ahover-bg.png) left top no-repeat;  
  
height:36px;  
  
line-height:36px; }  
  
#navbar ul li a:hover span{  
  
background:url(img/spanhover-bg.png) right top no-repeat;  
  
height:36px;  
  
line-height:36px; }

/*enlace homologacion*/
a.enlaceHomologaciones{
text-decoration:underline;
color:#000;
}
