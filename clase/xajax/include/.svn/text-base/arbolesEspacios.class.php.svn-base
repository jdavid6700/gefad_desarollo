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
    
    /*Incluye estas clases para poder trabajar los vinculos de los botones*/
   /* include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
    $cripto=new encriptar();

    /*$indice=$configuracion["host"].$configuracion["site"]."/index.php?";*/
	
    #Crea objeto y se conecta a la base de datos
    $acceso_db=new dbms($configuracion);
    $enlace=$acceso_db->conectar_db();
    $sentencia="";
    $botones=""; 
    $tipoBoton=""; #Variable que determina el tipo de elemento, que se va a generar
                   #para este caso son botones con imagenes 
    $describe=0;
    #Nivel indica el tipo de consulta que se debe hacer,dependiendo de la seccion del arbol que se esta solicitando.
  
    /*IMPORTANTE: EL NIVEL AL QUE SE VA A ASOCIAR AL DESCRIPCION SIEMPRE DEBE SER EL MAYOR 

    */
                
    switch($nivel){
        #Consulta areas academicas relacionadas al area de conocimiento seleccionada
        case '1':
                
    		/*ESTE NIVEL NO SE VA USAR POR EL MOMENTO POR LA ESTRUCTURA PARA MUETRA DE LA CONSULTA, REQUIERE QUE DEL PRIMER NIVEL SE PASE DIRECTAMENTE A UN NIVEL DE 	DESCRIPCION*/
		
                #Determina si  tiene boton de link para mostrar un nivel inferior 
                #(Tiene elementos asociados)
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
          
      	    #Determina si el elemento tiene botones para editar y cancelar

            #Se carga un vector, por cada posicion del vector se cargan las caracterisitcas y la estructura del boton boton que se va a presentar con:
           
            $tipoBoton="botonImagen"; #Esta variable controla si a los botones que se van a generar se les debe agregar un id como parametro en un vinculo  


            #---Boton Editar
            $vector_editar[0]="Editar";
            #Para este caso id_espacio debe estar en la ultima parte cargarlo desde la
            #clase arbol
            $vector_editar[1]="pagina=adminEspacio&opcion=editar&id_espacio=";
            $vector_editar[2]="<?echo $configuracion['site'].$configuracion['grafico']?>/editarGrande.png ";
            $vector_editar[3]="href";

            #Se asigna el vector del boton editar a la posicion 0
            $botones[0]=$vector_editar;
                
            #---Boton Eliminar
            $vector_eliminar[0]="Eliminar";
            $vector_eliminar[1]="pagina=borrar_registro&opcion=espacioacademico&registro=";
            $vector_eliminar[2]="<?echo $configuracion['site'].$configuracion['grafico']?>/boton_borrar.png";
            $vector_eliminar[3]="href";
            
            #Se asigna el vector del boton editar a la posicion 1. 
            $botones[1]=$vector_eliminar;
        
         case '4':
         break; 

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
	
	$cadena_html=$obj_arbol->generar_seccion($items_lista,$nivel,$link,$botones,$tipoBoton,$describe,"#F7F7F7",$configuracion);
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

 
 function generarDescripcion($id_elemento,$configuracion,$acceso_db){
    
    $enlace=$acceso_db->conectar_db();

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
    $sentencia.="aprobado, ";
    $sentencia.="id_estado, ";
    $sentencia.="GROUP_CONCAT(DISTINCT COD.codAcademica SEPARATOR \",\") ";
    $sentencia.="FROM ";
    $sentencia.=$configuracion["prefijo"];
    $sentencia.="espacio_academico AS ESPACIO ";
    $sentencia.="LEFT JOIN ";
    $sentencia.=$configuracion["prefijo"];
    $sentencia.="espacio_codAcademica AS COD ON COD.id_espacio=ESPACIO.id_espacio ";

    $sentencia.="WHERE ";
    $sentencia.="ESPACIO.id_espacio= ".$id_elemento." ";
    $sentencia.="GROUP BY ESPACIO.id_espacio";						
    $resultado=$acceso_db->ejecutarAcceso($sentencia,"busqueda");

    $cadena_html.="<table border=0 width='100%' class='bloquelateral'>";
    $cadena_html.="<tr class='texto_pequeño'>";
    $cadena_html.="<td align='right' colspan='2'>"; 
    $cadena_html.="<strong>".sprintf("%03d",$resultado[0][0])."<strong>&nbsp;</td>";
    $cadena_html.="</tr>";
    $cadena_html.="<tr class='texto_pequeño'>";
    $cadena_html.="<td class='texto_negrita'>Código(s) en aplicación académica</td>";
    $cadena_html.="<td align='left'>".$resultado[0][11]."</td>";
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
    $cadena_html.="<tr class='texto_pequeño'  bgcolor='#CCCCCC'>";
    $cadena_html.="<td colspan='6' align=left' class='texto_negrita'>";
    $cadena_html.="&nbsp;Espacio académico común en:</td>";
    $cadena_html.="</tr>";
    $cadena_html.="<tr>";
    $cadena_html.="<td colspan='6' align='center'>";
    $cadena_html.=formComunes($configuracion,$acceso_db,$resultado[0][0]); 
    $cadena_html.="</td>";
    $cadena_html.="</tr>";	
    $cadena_html.="</table>";
    
    return $cadena_html;

 }#Cierre de funcion generarDescripcion

 #Funcion que presenta el formulario para determinar si un espacio academico es comun o no
 function formComunes($configuracion,$acceso_db,$id_espacio){

  
   /* $esta_configuracion=new config();
   $configuracion=$esta_configuracion->variable(); */

   #Crea objeto y se conecta a la base de datos
   $acceso_db=new dbms($configuracion);
   $enlace=$acceso_db->conectar_db();

   

   include_once($configuracion["raiz_documento"]."/bloque/admin_espacio/sql.class.php");
   $obj_sql=new sql_espacio();

   		

  #Consulta por las facultades 

  $cadena_sql=$obj_sql->cadena_sql($configuracion,"consultaFacultades",$id_espacio);
  $facultades=$acceso_db->ejecutarAcceso($cadena_sql,"busqueda");	
  if(count($facultades)>=1){
       $cadena_html="<table border=0 cellspacing='10'  width='100%' class='texto_pequeño' >";
		
	   for($i=0; $i<count($facultades); $i++){ 
			$cadena_html.="<tr class='bloquecentralcuerpo'>";
		$cadena_html.="<td colspan='2' class='texto_negrita'>";
		$cadena_html.="<img src='<?echo $configuracion['site'].$configuracion['grafico']?>/chk.png' >";
		$cadena_html.=utf8_encode($facultades[$i][1]);
		$cadena_html.="</td>";
		$cadena_html.="</tr>";
		$cadena_html.="<tr><td></td>";
		$cadena_html.="<td>";
		
		$variable[0]=$facultades[$i][0];
		$variable[1]=$id_espacio;
		
		$cadena_sql=$obj_sql->cadena_sql($configuracion,"consultaProyectos",$variable);
		$proyectos=$acceso_db->ejecutarAcceso($cadena_sql,"busqueda");	
		
		
		$cadena_html.="<table class='texto_pequeño' border='0'>";
			for($j=0; $j<count($proyectos); $j++){
							
				$cadena_html.="<tr>";
				$cadena_html.="<td>&nbsp;</td>";
			
				$valor=$proyectos[$j][0];
				if(isset($proyectos_comunes)){
				  $activar=0;
				  for($n=0; $n<count($proyectos_comunes); $n++){
					$proyectos_comunes[$n];
					if($valor==$proyectos_comunes[$n][0]){
						$activar="1";
						break;
					}				
				  }
				}#Cierre de if 
	
				$cadena_html.="<td>";
				$cadena_html.="<img src='<?echo $configuracion['site'].$configuracion['grafico']?>/Vineta.gif' >";
				$cadena_html.="</td>";
				$cadena_html.="<td>";
				$cadena_html.="<p alig='justify'>";
				$cadena_html.=utf8_encode($proyectos[$j][1]);
				$cadena_html.="</p>";
				$cadena_html.="</td>";
				$cadena_html.="</tr>";
				
			}#Cierre de for proyectos 
		
		$cadena_html.="</table>";
		$cadena_html.="</td>";
		$cadena_html.="</tr>";
						
	   } #Cierre de for
	   $cadena_html.="</table>";
   }#Cierre de if count($facultades)>=1
   else{
      $cadena_html="<span class='texto_pequeño' align='center'>No es un espacio académico comun </span>";
   }
   
   return $cadena_html; 
   
   }#Cierre de funcion formComunes	
?>
