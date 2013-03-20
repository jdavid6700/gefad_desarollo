<?
/*
/***************************************************************************
******************************************************************************
* @subpackage   
* @package	clase
* @copyright    
* @version      0.2
* @author      	Kelly K. López
* @description  Clase para el manejo muestra de espacios academicos en arboles de consulta
*******************************************************************************
*/
 /***************************************************************
  * @ $id_div        Div a la que se va a asignar la siguiente jerarquia del arbol
  * @ $id_elemento   Codigo del elemento a partir del que se van a generar las consultas
  * @ $nivel         Nivel de jerarquia del arbol
  * @ $descripcion   Determina si se va a generar un nivel mas del arbol o si va a generar  		     la descripcion del elemento, por lo general el nivel mas alto, es el 		     que muestra la descripcion (Para este caso es el 4)
 /***************************************************************/



 function generar_lista($id_div,$id_elemento,$nivel,$descripcion){
    
    require_once("clase/config.class.php");
    setlocale(LC_MONETARY, 'en_US');
    	
    $esta_configuracion=new config();
    $configuracion=$esta_configuracion->variable(); 

    #Crea objeto y se conecta a la base de datos
    $acceso_db=new dbms($configuracion);
    $enlace=$acceso_db->conectar_db();
    $sentencia="";
    $botones=""; 
    $tipoElemento="";
    $describe=0;
    #Nivel indica el tipo de consulta que se debe hacer,dependiendo de la seccion del arbol que se esta solicitando.
  
    switch($nivel){
        #Consulta areas academicas relacionadas al area de conocimiento seleccionada
        case '1':
    	         $link=1; 
        break;
        #Consulta espacios academicos relacionados al area academica seleccionada
	case '2':
    	    $sentencia="SELECT ESPACIO.id_espacio, espacio_nombre FROM ";
	    $sentencia.=$configuracion["prefijo"]."espacio_academico ESPACIO ";
	    $sentencia.="INNER JOIN ".$configuracion["prefijo"];
	    $sentencia.="area_formacionEspacio ESPACIO_FORMACION ON  ";
	    $sentencia.="ESPACIO_FORMACION.id_espacio=ESPACIO.id_espacio ";
	    $sentencia.="WHERE ESPACIO_FORMACION.id_areaFormacion=".$id_elemento;
		

            #Determina si  tiene boton de link para mostrar un nivel inferior 
            #(No tiene elementos asociados)    
            $link=0; 
            #Determina si el nombre del elemento tiene posibilidad de ampliar la informacion generada
            $describe=1;
            
            $tipoElemento="elementoHtml";
      	    #Determina si el elemento tiene botones para editar y cancelar

            #Se carga un vector por cada boton que se va a presentar con:
            # 0. Prefijo del boton
            # 1. Funcion javascript que se va a activar cuando se ejecute
            # 2. Etiqueta del elemento

            #---Boton Seleccion
            #Se asigna el vector del boton editar a la posicion 0
            $boton_chk[0]="chkEspa_"; //prefijo_boton
            $boton_chk[1]="cargarEspacioMalla"; //Funcion javascript que va a llamar
            
            #Si la funcion javascript lleva uno o mas elementos se debe guardar en una rray y asignarlo a una posicion del array de caracterisiticas para el objeto html 
            $elementosfuncionJava[0]="id_elemento";
            $elementosfuncionJava[1]="id_tag";
            $boton_chk[2]=$elementosfuncionJava;
                
 	    $boton_chk[3]="<input type='checkbox' "; //Primera parte de objeto html
            $boton_chk[4]="/>"; //Cierre de objeto html
             
            #Asigna al vector general de botones
            $botones[0]=$boton_chk;
       

        break;
    }#Cierre de switch $nivel 
    
    /*Si descripcion esta en cero, se esta generando una  nivel jerarquico en el arbol*/ 
    if($descripcion==0){
      
       $resultado=$acceso_db->ejecutarAcceso($sentencia,"busqueda");

	#Si existen resultados, carga el vector, genera objeto de la clase arbol y carga la informacion
	if(count($resultado)>=1){
	#Recorre los registros y almacena en un vector id del elemento que se esta trabajando
	#Nombre del elemento que se esta trabajando
	for($n=$m=0; $n<count($resultado); $n++){
                if($describe==1){
                   $cod_espacio="<span class='texto_negrita'>(".sprintf("%03d",$resultado[$n][0]).")</span>&nbsp;"; 
                }
		$items_lista[$m]=$resultado[$n][0]; #Codigo del area academica
		$items_lista[$m+1]=$cod_espacio.$resultado[$n][1]; #Nombre del area academica 
		$m=$m+2; 
	}#Cierre de for  
	
	
	#Incluye la clase arbol, genera el objeto para invocar a la funcion que genera la seccion 
	#Buscar un registro que coincida con el valor
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/arboles.class.php");
	
	#Genera un objeto de la clase arbol 
	$obj_arbol=new arbol();
	
	$cadena_html=$obj_arbol->generar_seccion($items_lista,$nivel,$link,$botones,$tipoElemento,$describe,"#F7F7F7",$configuracion);
	}#count($resultado)>1
	else{
	$cadena_html="<span class='texto_subtitulo'>
			No existen elementos asociados
			<span>" ;
	}#Cierre de else count($resultado)>1 	

 
    }#Cierre de if $descripcion=0
    else{

        $cadena_html=generarDescripcion($id_elemento,$configuracion,$acceso_db);
 
    }

    $respuesta = new xajaxResponse();

    #Se asignan los valores al objeto y se envia la respuesta	
    $respuesta->addAssign($id_div,"innerHTML",$cadena_html);
    return $respuesta; 	
 }#Cierre de funcion cargarCapa

 
 function descripcionEspacioAcademico($id_elemento,$id_nivel,$id_malla,$nombre_div,$asociar){

    require_once("clase/config.class.php");
    setlocale(LC_MONETARY, 'en_US');

    $esta_configuracion=new config();
    $configuracion=$esta_configuracion->variable(); 

    #Crea objeto y se conecta a la base de datos
    $acceso_db=new dbms($configuracion);
    //$enlace=$acceso_db->conectar_db();

    #Sentencia para consultar informacion completa del espacio academico
    $sentencia=sentencias($id_elemento,$configuracion);
						
    $resultado=$acceso_db->ejecutarAcceso($sentencia,"busqueda");
   
    $nom_espacio="espacio_".$id_nivel.$resultado[0][0];

    $cadena_html="<table border='0' width='100%'";
    $cadena_html.="cellpadding='0' cellspacing='0'>";

    #Fila para nombre y codigo de espacio academico
    $cadena_html.="<tr class='texto_elegante' bgcolor='#FFFFFF'>";
    //$cadena_html.="<td><a onclick=muestra_capa('".$nom_espacio."')>";
    $cadena_html.="<td >&nbsp;<a onclick=muestra_capa('".$nom_espacio."')>";
    $cadena_html.="&nbsp;".utf8_encode($resultado[0][3]);  
    $cadena_html.=" - ".sprintf("%03d",$resultado[0][0])."</a></td>";
    $cadena_html.="</tr>";

    #Fila para division que contiene descripcion de espacio academico
    $cadena_html.="<tr>";
    $cadena_html.="<td align='left' colspan='2'>";
    $cadena_html="<div style='display:block' id='".$nom_espacio."'>";
    $cadena_html.=formEspacio($id_elemento,$id_nivel,$id_malla,$configuracion,$resultado,$acceso_db,"","");
    $cadena_html.="</div>";
    $cadena_html.="</td>";
    $cadena_html.="</tr>";
     
    #Cierre de fila para  nombre y codigo de espacio academico 
    $cadena_html.="</table>";
   
    /*if($asociar=='true'){
       asociarEspacioMalla($id_malla,$id_elemento,$id_nivel);
    }*/

    $respuesta = new xajaxResponse();
    #Se asignan los valores al objeto y se envia la respuesta	
    $respuesta->addAssign($nombre_div,"innerHTML",$cadena_html);
    return $respuesta; 	
   
 }#Cierre de funcion descripcionEspacioAcademico
 
function asociadosElectivo($id_espacio,$id_planEstudio){
	
	$esta_configuracion=new config();
    $configuracion=$esta_configuracion->variable(); 

    #Crea objeto y se conecta a la base de datos
    $acceso_db=new dbms($configuracion);
    $enlace=$acceso_db->conectar_db();

    include_once($configuracion["raiz_documento"]."/bloque/admin_mallas/sql.class.php");
    $obj_sql=new sql_malla();
    
	$variable[0]=$id_espacio;
	$variable[1]=$id_planEstudio;
    $sentencia=$obj_sql->cadena_sql($configuracion,"relacionadosElectivo",$variable);
    $resultado=$acceso_db->ejecutarAcceso($sentencia,"busqueda");
	
    $cadena_html.="<table class='texto_pequeño' align='left'>";
	for($i=0; $i<count($resultado); $i++){
		$cadena_html.="<tr>";
		$cadena_html.="<td><img src=".$configuracion['site'].$configuracion['grafico']."/vineta_pequena.gif'></td>";
		$cadena_html.="<td>".$resultado[$i][1]."</td>";
		$cadena_html.="</tr>";
	}	
		$cadena_html.="</table>";
		
    return $cadena_html;
}#Cierre de funcion asociadosElectivo

function formEspacio($id_elemento,$id_nivel,$id_malla,$configuracion,$resultado,$acceso_db,$nombre_div,$ejecucion){
    
    if($resultado==""){
      $esta_configuracion=new config();
      $configuracion=$esta_configuracion->variable(); 

      #Crea objeto y se conecta a la base de datos
      $acceso_db=new dbms($configuracion);

      #Sentencia para consultar informacion completa del espacio academico
      $sentencia=sentencias($id_elemento,$configuracion);
      $resultado=$acceso_db->ejecutarAcceso($sentencia,"busqueda");
    }   

    
    //$cadena_html=$ejecucion;
    $cadena_html="<table border='0' class='texto_pequeño' bgcolor='#FFFFFF' width='100%' >";
    $cadena_html.="<tr >";
    
    $cadena_html.="<td><span class='texto_negrita'>Número de Créditos:  </span>".$resultado[0][4]."</td>";  
    //$cadena_html.="<td bgcolor='#F7F7F7'> ".$resultado[0][4]."</td>";
    $cadena_html.="<td colspan='5'>&nbsp;</td>";
    //$cadena_html.="<td bgcolor='#F7F7F7'>&nbsp;</td>";
    $cadena_html.="</tr>";

    $cadena_html.="<tr>";
    $cadena_html.="<td colspan='3' valign='top' width='50%'>";
    #Tabla para horas de trabajo academico
    $cadena_html.="<table border='0' align='center' class='texto_pequeño' width='100%'";
    $cadena_html.="cellpadding='1' cellspacing='1' >";
    $cadena_html.="<tr bgcolor='#F7F7F7'><td colspan='2' align='center'><strong>Horas de Trabajo Académico</strong></td></tr>";
    $cadena_html.="<tr ><td>Directo (HTD): </td><td>".$resultado[0][5]."</td></tr>";
    $cadena_html.="<tr ><td>Cooperativo (HTC): </td><td>".$resultado[0][6]."</td></tr>";
    $cadena_html.="<tr ><td>Autónomo (HTA):</td><td>".$resultado[0][7]."</td></tr>";
    $cadena_html.="</table>";
    #Cierre de tabla para horas de trabajo academico
    
    $cadena_html.="</td>";
    $cadena_html.="<td colspan='2' valign='top'> ";
    $cadena_html.="<table border='0' class='texto_pequeño' bgcolor='#FFFFFF' width='100%' >";
    $cadena_html.="<tr bgcolor='#F7F7F7'>";
    $cadena_html.="<td class='texto_negrita' align='center'> Cod. académica</td>";	
    $cadena_html.="</tr>";	
    $cadena_html.="<tr>";
    $cadena_html.="<td align='center'> ".$resultado[0][15]."</td>";	
    $cadena_html.="</tr>";	
    
    $cadena_html.="</table>";
    
    $cadena_html.="</td>";
    $cadena_html.="</tr>";
    
    #Titulo para caracterisiticas en mallas
    $cadena_html.="<tr bgcolor='#F7F7F7'>";
    $cadena_html.="<td colspan='5' align='left'><strong>Caracteristicas en Plan de Estudio</strong>";
    $cadena_html.="</td>";
    $cadena_html.="</tr>";
    $cadena_html.="<tr>";
    $cadena_html.="<td colspan='5'>";

    $cadena_html.="<table border='0' width='100%'>";
    $cadena_html.="<tr>";
    $cadena_html.="<td>";

	/*Tabla de Area, Clasificacion, Tipo */
	$cadena_html.="<table border='0' align='left' class='texto_pequeño'";
	$cadena_html.="cellpadding='1' cellspacing='1' >";
	$cadena_html.="<tr class='texto_pequeño'>";
	$cadena_html.="<td><img src='".$configuracion['site'].$configuracion['grafico']."/vineta_pequena.gif'></td>";
	$cadena_html.="<td >";
	$cadena_html.="Area de Formación: </td><td>";
	
	if($resultado[0][13]!=""){
	   $cadena_html.=utf8_encode($resultado[0][13]);
	}
	else{
	   $cadena_html.="No clasificado en el Plan de Estudios";
	}
	$cadena_html.="</td>";
	$cadena_html.="</tr>";	
	$cadena_html.="<tr>";
	$cadena_html.="<td><img src='".$configuracion['site'].$configuracion['grafico']."/vineta_pequena.gif'></td>";
	$cadena_html.="<td  >";
    $cadena_html.="Clasificación:</td><td>";
	if($resultado[0][9]!=""){
	   $cadena_html.=utf8_encode($resultado[0][9]);
	}
	else{
	   $cadena_html.="No clasificado en el Plan de Estudios";
	}

	$cadena_html.="</td>";
	$cadena_html.="</tr>";
	
	$cadena_html.="</table>";
	$cadena_html.="</td>";
	/*Cierre de tabla de Area, Clasificacion, Tipo */
		
	
	/*$cadena_html.="<td>&nbsp;";
	Tabla de caracter, naturaleza, modalidad
	$cadena_html.="<table border='0' align='center' class='texto_pequeño' width='100%'";
	$cadena_html.=" cellpadding='1' cellspacing='1' >"; 
	$cadena_html.="<tr>";
	$cadena_html.="<td>";
 	$cadena_html.="<tr>";
        $cadena_html.="<td class='texto_cursiva_negrita'>";
	$cadena_html.="Caracter: </td><td>".utf8_encode($resultado[0][12])."</td>";
        $cadena_html.="</tr>";
	$cadena_html.="<tr>";
        $cadena_html.="<td class='texto_cursiva_negrita'>";
        $cadena_html.="Naturaleza: </td><td>".utf8_encode($resultado[0][14])."</td>";
        $cadena_html.="</tr>";
	$cadena_html.="<tr>";
        $cadena_html.="<td class='texto_cursiva_negrita'>";
        $cadena_html.="Modalidad: </td><td>".utf8_encode($resultado[0][11])."</td>";
        $cadena_html.="</tr>";
	$cadena_html.="</table>";
	$cadena_html.="</td>";	*/
    $cadena_html.="</tr>";
	
	
    $cadena_html.="</table>";	
    $cadena_html.="</td>";
    $cadena_html.="</tr>";
	
	if($resultado[0][16]==3 or $resultado[0][16]==4){
	   $cadena_html.="<tr bgcolor='#F7F7F7' class='texto_pequeño'>";
	   $cadena_html.="<td colspan='5' align='left' class='texto_negrita'>Espacios Asociados a Electivo</td>";
	   $cadena_html.="</tr>";
	   
   	   $cadena_html.="<tr class='texto_pequeño'>";
	   $cadena_html.="<td colspan='5' align='center' class='texto_negrita'>";
	   $cadena_html.=asociadosElectivo($resultado[0][0],$resultado[0][17]);
	   $cadena_html.="</td>";
	   $cadena_html.="</tr>";

	}		
    #Cierre para caracterisiticas en mallas
    
    #Cierre para tabla clasificacion
 //   $cadena_html.="</td>";		
   // $cadena_html.="</tr>"; 
     
    
    $cadena_html.="<tr>";
    $cadena_html.="<td colspan='5' align='center'>";

 /*   $cadena_html.="<form name=form_".$id_elemento." id=form_".$id_elemento;
    $cadena_html.="enctype='tipo:multipart/form-data,application/x-www-form-urlencoded";
    $cadena_html.=",text/plain'";
    $cadena_html.="method='POST' action='index.php'>";
    
    $cadena_html.="<table class='texto_pequeño' align='left' border='1' width='100%' bgcolor='#F7F7F7'>";
    $cadena_html.="<tr>";
    $cadena_html.="<td>&nbsp;Area de Formación: </td>";
    $cadena_html.="<td>";

    #Si la ejecucion es-1 no debe desabilitar nada  	
   /* if($ejecucion==-1)
    {
       $evento="-1";
       $deshabilitar="";
    }#Cierre de if isset($ejecucion)
    else{
       $evento=3; #Para deshabilitar el combo en la clase html 
       $deshabilitar="disabled";
    
    }


    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
    $html=new html();
    $busqueda="SELECT  id_areaFormacion, areaFormacion_nombre ";
    $busqueda.="FROM ".$configuracion["prefijo"]."area_formacion ";
    $busqueda.="AS AFMEN WHERE id_areaFormacion!=0 ORDER BY areaFormacion_nombre";

    $mi_cuadro=$html->cuadro_lista($busqueda,'id_areaFormacionMen',$configuracion,$resultado[0][15],$evento,FALSE,1,"areaFormacionMen");
			

    $cadena_html.=$mi_cuadro;
    $cadena_html.="</td>";
    $cadena_html.="</tr>";
    $cadena_html.="<tr><td colspan='2' bgcolor='#FFFFFF'>";


    $clasificacionEspacio="SELECT id_clasificacion, clasificacion_nombre FROM ";
    $clasificacionEspacio.=$configuracion["prefijo"]."espacio_clasificacion";

    $clasificacionEspacio=$acceso_db->ejecutarAcceso($clasificacionEspacio,"busqueda");	

    $cadena_html.="<table border=0 width='100%' class='texto_pequeño' bgcolor='#F7F7F7' cellpadding='1' cellspacing='1' >";

    $totalClasificacion=count($clasificacionEspacio);
    $filasClasificacion=round(($totalClasificacion/2)); 

    #Genera la tabla para la clasificacion del espacio academico 
    #For filas   
    for($i=$m=0; $i<$filasClasificacion; $i++){
	$cadena_html.="<tr>";

	#For celdas
        for($j=0; $j<1; $j++){
	    #Genera celda para el nombre	
            $cadena_html.="<td width=45%>".utf8_encode($clasificacionEspacio[$m][1])."</td>";

            #Si existe el nombre, genera el boton de seleccion
            if($clasificacionEspacio[$m][1]!=""){
           	$cadena_html.="<td>";
                $cadena_html.="<input type='radio' name='clasificacion' ";
                $cadena_html.="value=".$clasificacionEspacio[$m][0];
                #Valida para seleccionar
		if($resultado[0][16]==$clasificacionEspacio[$m][0]){
       			$cadena_html.=" 'checked' "; 
    		}
                #Si no se seleccionado la clasificacion para el espacio  
                if($resultado[0][16]=="0" && $clasificacionEspacio[$m][1]=="No clasificado"){
                   $cadena_html.=" 'checked' "; 
		}
                $cadena_html.=">";
                $cadena_html.="</td>";
            }

	    #Genera celda para el nombre	
	    $cadena_html.="<td>".utf8_encode($clasificacionEspacio[$m+1][1])."</td>";

            #Si existe el nombre, genera el boton de seleccion
            if($clasificacionEspacio[$m+1][1]!=""){
           	$cadena_html.="<td>";
		$cadena_html.="<input type='radio' name='clasificacion' ";
                $cadena_html.="value=".$clasificacionEspacio[$m+1][0];

		#Valida para seleccionar
		if($resultado[0][16]==$clasificacionEspacio[$m+1][0]){
	 	   $cadena_html.=" 'checked' "; 
    		}
                #Si no se seleccionado la clasificacion para el espacio  
                if($resultado[0][16]=="0" && $clasificacionEspacio[$m+1][1]=="No clasificado"){
                   $cadena_html.=" 'checked' "; 
		} 
                $cadena_html.=">";

                $cadena_html.="</td>";
            }
        }#Cierre de for celdas
  	$m=$m+2; #Incrementa el contador para la muestra del registro 	
        $cadena_html.="</tr>";
    }#Cierre de for filas
    
    
    $cadena_html.="<tr><td align='center' colspan='5'>";
    $cadena_html.="<input type='hidden' name='id_espacio' value='".$id_elemento."'>";
    $cadena_html.="<input type='hidden' name='id_nivel' value='".$id_nivel."'>";
    $cadena_html.="<input type='hidden' name='id_malla' value='".$id_malla."'>";
    $cadena_html.="<input type='button' name='Guardar' value='Guardar' align='center'  onclick='formEspacio(this.form)'  ".$deshabilitar.">";
    $cadena_html.="</td></tr>";
    $cadena_html.="</table>";
   // $cadena_html.="</form>";*/
    
    $cadena_html.="</td>";
    $cadena_html.="</tr>";
   

    $cadena_html.="</table>";
    

    $cadena_html.="</td>";
    $cadena_html.="</tr>";

    $cadena_html.="<tr>";
    $cadena_html.="<td colspan='5' align='center'>";
    $cadena_html.="</td>";
    $cadena_html.="</tr>";

    $cadena_html.="</table>";
    
    
    if($nombre_div==""){
      return $cadena_html;
    }
    else{
 	$respuesta = new xajaxResponse();
    	#Se asignan los valores al objeto y se envia la respuesta	
    	$respuesta->addAssign($nombre_div,"innerHTML",$cadena_html);
    	return $respuesta; 		
    }
}#formEspacio

/*Funcion para mostrar solo la descripcion del espacio academico 
  esta funcion se utiliza cuando se esta realizando la muestra por arbol para los espacios academicos 
*/
function generarDescripcion($id_elemento,$configuracion,$acceso_db){
    
    $enlace=$acceso_db->conectar_db();

    #Sentencia para consultar informacion completa del espacio academico
    $sentencia=sentencias($id_elemento,$configuracion);		

    $resultado=$acceso_db->ejecutarAcceso($sentencia,"busqueda");


    $cadena_html.="<table border=0 width='100%' class='bloquelateral'>";
    $cadena_html.="<tr class='texto_pequeño'>";
    $cadena_html.="<td align='right' colspan='2'>"; 
    $cadena_html.="<strong>".sprintf("%03d",$resultado[0][0])."<strong>&nbsp;</td>";
    $cadena_html.="</tr>";
    $cadena_html.="<tr class='texto_pequeño'>";
    $cadena_html.="<td class='texto_negrita'>Código(s) en aplicación académica</td>";
    $cadena_html.="<td align='left'>".$resultado[0][15]."</td>";
    $cadena_html.="</tr>";
    $cadena_html.="<tr>";
    $cadena_html.="<td colspan='2'>";
    $cadena_html.="<table border='0' width='100%'  align='left' cellpadding='0' cellspacing='0' >";
    $cadena_html.="<tr class='texto_pequeño'  bgcolor='#CCCCCC'>";
    $cadena_html.="<td align='center' colspan=3 class='texto_negrita'>Creditos</td>";
    $cadena_html.="<td colspan='7' align='center' width='50%'><strong>Horas de trabajo Académico</strong></td>";
    $cadena_html.="<tr class='texto_pequeño'>";
    $cadena_html.="<td width='1%'>&nbsp;</td>";
    $cadena_html.="<td align='left' width='30%'>Nro. Creditos:</td>";
    $cadena_html.="<td width='10%'>".$resultado[0][4]."</td>";
    $cadena_html.="<td width='40%'> Directo (HTD): </td>";
    $cadena_html.="<td align='left' width='10%'>".$resultado[0][5]."</td>";
    $cadena_html.="</tr>";                             
    $cadena_html.="<tr class='texto_pequeño'>";
    $cadena_html.="<td>&nbsp;</td><td>&nbsp;</td>";
    $cadena_html.="<td>&nbsp;</td>";
    $cadena_html.="<td align='left'>Cooperativo (HTC):</td>";
    $cadena_html.="<td align='left'>".$resultado[0][6]."</td>";
    $cadena_html.="</tr>";
    $cadena_html.="<tr class='texto_pequeño'>";
    $cadena_html.="<td>&nbsp;</td>";
    $cadena_html.="<td>&nbsp;</td>";
    $cadena_html.="<td>&nbsp;</td>";
    $cadena_html.="<td align='left'>Autónomo (HTA): </td>";
    $cadena_html.="<td>".$resultado[0][7]."</td>";
    $cadena_html.="</tr></table>";
    $cadena_html.="</td>";
    $cadena_html.="</tr>";
    $cadena_html.="</table>";
    
   /* 

    $cadena_html.="<table border='0' width='100%' cellpadding='0' cellspacing='0' >";
    $cadena_html.="<tr class='texto_subtitulo'  bgcolor='#CCCCCC'>";
    $cadena_html.="<td colspan='3' align='center'>";
    $cadena_html.="<strong>Clasificación</strong></td></tr>";
    $cadena_html.="<tr class='texto_subtitulo'>";
    $cadena_html.="<td width='1%'>&nbsp;</td>"; 
    $cadena_html.="<td width='25%' >Tipo:</td><td>".utf8_encode($resultado[0][11])."</td></tr>";
    $cadena_html.="<tr class='texto_subtitulo'>";
    $cadena_html.="<td width='1%'>&nbsp;</td>";  
    $cadena_html.="<td width='25%'>Caracter:</td><td>".utf8_encode($resultado[0][12])."</td></tr>";
    $cadena_html.="<tr class='texto_subtitulo'>";
    $cadena_html.="<td width='1%'>&nbsp;</td>";  
    $cadena_html.="<td width='25%'>Naturaleza:</td><td>".utf8_encode($resultado[0][13])."</td></tr>";
    $cadena_html.="</table>";
    $cadena_html.="</td>";
    $cadena_html.="</tr>";
    $cadena_html.="<tr><td>&nbsp;</td></tr>";
    $cadena_html.="</table>";*/
    
    return $cadena_html;
}#Cierre de funcion generarDescripcion 

function sentencias($id_elemento,$configuracion){
  #Sentencia para consultar informacion completa del espacio academico
    
    $sentencia="SELECT ";
    $sentencia.="ESPACIO.id_espacio, ";
    $sentencia.="ESPACIO.espacio_codCreditos, ";
    $sentencia.="ESPACIO.espacio_codAcademica, ";
    $sentencia.="ESPACIO.espacio_nombre, ";
    $sentencia.="ESPACIO.espacio_nroCreditos, ";
    $sentencia.="ESPACIO.espacio_horasDirecto, ";
    $sentencia.="ESPACIO.espacio_horasCooperativo, ";
    $sentencia.="ESPACIO.espacio_horasAutonomo, ";
    $sentencia.="ESPACIO.espacio_fechaCreacion, ";	
    $sentencia.="CLASIF.clasificacion_nombre, ";
    $sentencia.="TIPO.tipo_nombre, ";
    $sentencia.="MODAL.modalidad_nombre, ";
    $sentencia.="CARACTER.caracter_nombre, ";
    $sentencia.="AREAF.areaFormacion_nombre, ";	
    $sentencia.="NATURALEZA.naturaleza_nombre, ";	
    $sentencia.="GROUP_CONCAT(DISTINCT COD.codAcademica SEPARATOR \",\"), ";
	$sentencia.="CLASIF.id_clasificacion, ";
	$sentencia.="PLAN_ESPACIO.id_planEstudio ";

    $sentencia.="FROM ";
    $sentencia.=$configuracion["prefijo"];
    $sentencia.="espacio_academico ESPACIO ";

    $sentencia.="LEFT JOIN ".$configuracion["prefijo"]."planEstudio_espacio PLAN_ESPACIO ";
    $sentencia.="ON PLAN_ESPACIO.id_espacio=ESPACIO.id_espacio ";
	
    $sentencia.="LEFT JOIN ".$configuracion["prefijo"]."area_formacion AREAF "; 
    $sentencia.="ON AREAF.id_areaFormacion=PLAN_ESPACIO.id_areaFormacion ";	

    $sentencia.="LEFT JOIN ".$configuracion["prefijo"]."espacio_clasificacion CLASIF ";	
    $sentencia.="ON CLASIF.id_clasificacion=PLAN_ESPACIO.id_clasificacion ";	

    $sentencia.="LEFT JOIN ".$configuracion["prefijo"]."espacio_tipo TIPO ";	
    $sentencia.="ON TIPO.id_tipo=PLAN_ESPACIO.id_tipo ";	

    $sentencia.="LEFT JOIN ".$configuracion["prefijo"]."espacio_modalidad MODAL ";	
    $sentencia.="ON MODAL.id_modalidad=PLAN_ESPACIO.id_modalidad ";	

    $sentencia.="LEFT JOIN ".$configuracion["prefijo"]."espacio_caracter CARACTER ";	
    $sentencia.="ON CARACTER.id_caracter=PLAN_ESPACIO.id_caracter ";	

    $sentencia.="LEFT JOIN ".$configuracion["prefijo"]."espacio_naturaleza NATURALEZA ";	
    $sentencia.="ON NATURALEZA.id_naturaleza=PLAN_ESPACIO.id_naturaleza ";	
	
    $sentencia.="LEFT JOIN ".$configuracion["prefijo"]."espacio_codAcademica AS COD "; 
    $sentencia.="ON COD.id_espacio=ESPACIO.id_espacio ";

    $sentencia.="WHERE ";
    $sentencia.="ESPACIO.id_espacio=".$id_elemento." ";			
    $sentencia.="GROUP BY ESPACIO.id_espacio";
					
    //$resultado=$acceso_db->ejecutarAcceso($sentencia,"busqueda");	
    return $sentencia;
}

function asociarEspacioMalla($id_malla,$id_espacio,$id_nivel){

  
    require_once("clase/config.class.php");
    setlocale(LC_MONETARY, 'en_US');
	
    $esta_configuracion=new config();
    $configuracion=$esta_configuracion->variable(); 

    #Crea objeto y se conecta a la base de datos
    $acceso_db=new dbms($configuracion);
    $enlace=$acceso_db->conectar_db();

    $variable[0]=$id_malla;
    $variable[1]=$id_espacio;
    $variable[2]=$id_nivel;

    include_once($configuracion["raiz_documento"]."/bloque/admin_mallas/sql.class.php");
	
	$obj_sql=new sql_malla();
	
	$sentencia=$obj_sql->cadena_sql($configuracion,"id_espacio",$id_espacio);
    $resultado=$acceso_db->ejecutarAcceso($sentencia,"busqueda");
	
	$variable[3]=$resultado[0][0]; //Horas de trabajo Directo
	$variable[4]=$resultado[0][1]; //Horas de trabajo Cooperativo
	
    $sentencia=$obj_sql->cadena_sql($configuracion,"asociarEspacioPlan",$variable);
    $resultado=$acceso_db->ejecutarAcceso($sentencia,"busqueda");

}#Cierre de funcion asociarEspacioMalla


function editarEspacioMalla($areaFormacion,$clasificacion,$id_espacio,$id_nivel,$id_malla){
    
    $respuesta = new xajaxResponse();
    
    #Asigna los datos enviados por parametro al vector que se va a enviar
    $variable[0]=$areaFormacion;
    $variable[1]=$clasificacion;
    $variable[2]=$id_espacio;
    $variable[3]=$id_nivel;  
    $variable[4]=$id_malla;

    $esta_configuracion=new config();
    $configuracion=$esta_configuracion->variable(); 

    #Crea objeto y se conecta a la base de datos
    $acceso_db=new dbms($configuracion);
    $enlace=$acceso_db->conectar_db();

    include_once($configuracion["raiz_documento"]."/bloque/admin_mallas/sql.class.php");
    $obj_sql=new sql_malla();
    $sentencia=$obj_sql->cadena_sql($configuracion,"editarEspacioPlan",$variable);
    $resultado=$acceso_db->ejecutarAcceso($sentencia,"busqueda");

    return $respuesta; 	 
}

#Funcion que valida si el espacio academico ya se encuentra asociado a la malla que se esta construyendo
function buscarEspacioMalla($id_espacio,$id_malla){
    $respuesta = new xajaxResponse();

    $variable[0]=$id_espacio;
    $variable[1]=$id_malla;
    
    $esta_configuracion=new config();
    $configuracion=$esta_configuracion->variable(); 

    #Crea objeto y se conecta a la base de datos
    $acceso_db=new dbms($configuracion);
    $enlace=$acceso_db->conectar_db();

    include_once($configuracion["raiz_documento"]."/bloque/admin_mallas/sql.class.php");
    $obj_sql=new sql_malla();
    
    $sentencia=$obj_sql->cadena_sql($configuracion,"buscarEspacioPlan",$variable);
    $resultado=$acceso_db->ejecutarAcceso($sentencia,"busqueda");
    
    
   #Si existen registros, el espacio ya fue asignado a esta malla curricular

   $respuesta = new xajaxResponse();
   if($resultado[0][0]==$id_espacio){ $existe="1";} 
   else{  $existe="0"; } 

   $respuesta->addAssign("divExiste","innerHTML",$existe);
   return $respuesta;
   
}#Cierre de funcion buscarEspacioMalla
?>
