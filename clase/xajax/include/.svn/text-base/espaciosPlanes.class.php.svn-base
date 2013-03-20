<?
/*
/***************************************************************************
******************************************************************************
* @subpackage   
* @package	clase
* @copyright    
* @version      0.2
* @author      	Kelly K. López
* @description  Clase xajax para asignacion de caracterisiticas de espacios academicos en planes 		        de estudio
*******************************************************************************
*/
#Instancia objeto sql de la clase caracterisitcasEspacioPlan		 
include_once($configuracion["raiz_documento"]."/bloque/admin_caracteristicasEspacioPlan/sql.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
require_once("clase/config.class.php");
setlocale(LC_MONETARY, 'en_US');

class caracteristicasEspaciosPlan{

   function caracteristicasEspaciosPlan(){
	  
   }#Cierre de caracterisiticasEspaciosPlan         
   
   #Funcion que instancia un objeto de la clase conexion
   function conectar(){
	#Crea objeto y se conecta a la base de datos
    	$obj_conexion=new dbms($this->configuracion);
    	$conexion=$obj_conexion->conectar_db();
	return $obj_conexion;
   }#Cierre de funcion conectar
   
   #Funcion que instancia un objeto de la clase configuracion y obtiene la configuracion del sistema 
   function configuracion(){
   	#Instancia objeto de clase configuracion
	$obj_configuracion=new config();
	$configuracion=$obj_configuracion->variable();
   
        return $configuracion;
   }#Cierre de funcion configuracion 
   
   
   /***************Funciones segun la caracterisitica que se esta intentado definir *********************************/
   function cargar_codigos(){
   
   	  $this->cadena_html="<table border='0' width='100%' class='bloquelateral'";
 	  $this->cadena_html.="cellspacing='7' cellpadding='5'>";
	  $this->cadena_html.="<tr class='texto_pequeño'>";
	  $this->cadena_html.="<td width='43%' colspan='2' class='texto_negrita' align='center'>";
	  $this->cadena_html.="Espacios Académicos";
	  $this->cadena_html.="</td>";
	  $this->cadena_html.="<td colspan='1' align='center' class='texto_negrita'>";
	  $this->cadena_html.=$this->label;
	  $this->cadena_html.="</td>";
	  $this->cadena_html.="</tr>";
	  
	  for($this->i=0; $this->i<$this->num_espacios; $this->i++){
			/*$this->cadena_html.="<table border='0' cellspacing='7' cellpadding='5'  class='texto_pequeño' >";*/
			$this->cadena_html.="<tr class='bloquecentralcuerpo'  ";
			if(($color%2)==0){
			   $this->cadena_html.="bgcolor=#F7F7F7";	
					}
			$this->cadena_html.=">";
			
			$this->cadena_html.="<td width='8%' align='right' >";
			$this->cadena_html.=sprintf("%03d",$this->espaciosNivel[$this->i][0]);
                	$this->cadena_html.="</td>";
			$this->cadena_html.="<td width='40%' >";
			$this->cadena_html.=utf8_encode($this->espaciosNivel[$this->i][1]);
                	$this->cadena_html.="</td>"; 

			
			$this->cadena_html.="<td>";
			
			$this->cadena_html.="<input type='text' id='cod_".$this->espaciosNivel[$this->i][0]."'";
			$this->cadena_html.="class='texto_negrita' ";
			$this->cadena_html.="style='font-style:normal; text-align:center; font-size:10px' size='5' ";
			$this->cadena_html.="value='".$this->codAcademica[$this->i][1]."'>";
			
			$this->cadena_html.="</td>";
			
			$this->cadena_html.="</tr>";

			
			#Va almacenando los codigos de espacios academicos
			$this->codigos.=$this->espaciosNivel[$this->i][0].";";
			
			$color=$color+1;
	}#Cierre de for	
	  //$this->cadena_html.="</td>";
	
	  #Elimina la ultima , generada
	  $this->codigos=substr($this->codigos,0,(strlen($this->codigos)-1));
	
	  $this->cadena_html.="<tr>"; 	
	  $this->cadena_html.="<td colspan='3' align='center'>";
      	  $this->cadena_html.="<input type='hidden' value='".$this->id_planEstudio."' id='id_planEstudio'>";
      	  $this->cadena_html.="<input type='button' value='Guardar' onclick='codigosAcademica(\"".$this->codigos."\")>";
	  $this->cadena_html.="</td>";
	  $this->cadena_html.="</tr>";	
          $this->cadena_html.="</table>";
   }#Cierre de funcion cargar_codigos  
   
   
   
   function cargar_horas(){
       
	  #Campos a consultar
	  $this->campo_consulta=explode(",",$this->campo_consulta);
	  $this->htd=$this->campo_consulta[0];
	  $this->htc=$this->campo_consulta[1];
	  $this->codigos="";
	  
          $this->cadena_html="<table border='0' width='100%' class='bloquelateral' ";
	  $this->cadena_html.="cellspacing='7' cellpadding='5'>";
	  $this->cadena_html.="<tr class='texto_pequeño'>";
	  $this->cadena_html.="<td width='43%' colspan='2' class='texto_negrita' align='center'>";
	  $this->cadena_html.="Espacios Académicos";
	  $this->cadena_html.="</td>";
	  $this->cadena_html.="<td colspan='2' align='center' class='texto_negrita'>";
	  $this->cadena_html.=$this->label." Directo";
	  $this->cadena_html.="</td>";
	  $this->cadena_html.="<td colspan='2' align='center' class='texto_negrita'>";
	  $this->cadena_html.=$this->label." Cooperativo" ;
	  $this->cadena_html.="</td>";
	  	
	  $this->cadena_html.="</tr>";
	
	  for($this->i=0; $this->i<$this->num_espacios; $this->i++){
			/*$this->cadena_html.="<table border='0' cellspacing='7' cellpadding='5'   >";*/
			$this->cadena_html.="<tr class='bloquecentralcuerpo'  ";
			if(($color%2)==0){
			   $this->cadena_html.="bgcolor=#F7F7F7";	
					}
			$this->cadena_html.=">";
			$this->cadena_html.="<td width='8%' align='right' >";
			$this->cadena_html.=sprintf("%03d",$this->espaciosNivel[$this->i][0]);
                	$this->cadena_html.="</td>";
			$this->cadena_html.="<td width='35%' >";
			$this->cadena_html.=utf8_encode($this->espaciosNivel[$this->i][1]);
                	$this->cadena_html.="</td>"; 
			$this->cadena_html.="<td align='right'>HTD:</td>";
			
			$this->cadena_html.="<td>";
			
			$this->cadena_html.="<input type='text' id='".$this->espaciosNivel[$this->i][0]."_htdPlan'";
			$this->cadena_html.="class='texto_negrita' ";
			$this->cadena_html.="style='font-style:normal; text-align:center; font-size:10px' size='5' ";
			$this->cadena_html.="value='".$this->espaciosNivel[$this->i][$this->htd]."'>";
			
			$this->cadena_html.="</td>";
			
			$this->cadena_html.="<td align='right'>HTC:</td>";
			$this->cadena_html.="<td>";
			
			$this->cadena_html.="<input type='text' id='".$this->espaciosNivel[$this->i][0]."_htcPlan'";
			$this->cadena_html.="class='texto_negrita' ";
			$this->cadena_html.="style='font-style:normal; text-align:center; font-size:10px' size='5' ";
			$this->cadena_html.="value='".$this->espaciosNivel[$this->i][$this->htc]."'>";
			
			
			$this->cadena_html.="<input type='hidden' id='".$this->espaciosNivel[$this->i][0]."_horasEspacio'";
			$this->cadena_html.="class='texto_pequeño' size='5' ";
			$this->cadena_html.="value='".($this->espaciosNivel[$this->i][10] + $this->espaciosNivel[$this->i][11])."'>";
			
			$this->cadena_html.="</td>";
			
			
				   
			#Va almacenando los codigos de los espacios academicos para cuando se realice la almacenacion, se pueda realizae
			#la validacion correspondiente	    
			$this->codigos.=$this->espaciosNivel[$this->i][0].";";
			$this->cadena_html.="</tr>";
			
			
			$color=$color+1;
	}#Cierre de for	
	  #Elimina la ultima , generada
	  $this->codigos=substr($this->codigos,0,(strlen($this->codigos)-1));
	
	  $this->cadena_html.="<tr>";
		
	  $this->cadena_html.="<td align='center' colspan='6'>";
 	  $this->cadena_html.="<input type='hidden' value='".$this->id_planEstudio."' id='id_planEstudio'>";
	  $this->cadena_html.="<input type='hidden' value='".$this->id_nivel."' id='id_nivel'>";
          $this->cadena_html.="<input type='button' value='Guardar' onclick='validarHorasEspacioPlan(\"".$this->codigos."\")'>";
	  $this->cadena_html.="</td>";
	  $this->cadena_html.="</tr>";
	  
	  $this->cadena_html.="</table>";	 
	
   }#Cierre de funcioon cargar_horas()
	
   	
	
   function cargar_caracteristica(){
	$color=0;
    #Instancia objeto de clase sql  
	$obj_sql=new sql_espacioPlan(); 
		
	#Obtiene la configuracion del sistema
	$this->conf=$this->configuracion();
	 
	#Obtiene conexion 
	$obj_conexion=$this->conectar();
	
	$this->sentencia=$obj_sql->cadena_sql($this->conf,$this->caracteristica,"");
	$this->reg_caracteristica=$obj_conexion->ejecutarAcceso($this->sentencia,"busqueda");
		
	$this->cadena_html.="<table border='0'  width='100%' class='bloquelateral'";
	$this->cadena_html.="cellspacing='7' cellpadding='5'>";
        $this->cadena_html.="<tr class='texto_pequeño'>";
	$this->cadena_html.="<td width='43%' colspan='2' class='texto_negrita' align='center'>";
	$this->cadena_html.="Espacios Académicos";
	$this->cadena_html.="</td>";
	$this->cadena_html.="<td colspan='5' align='center' class='texto_negrita'>";
	$this->cadena_html.=$this->label;
	$this->cadena_html.="</td>";
	$this->cadena_html.="</tr>";


	#Genera fila por cada espacio academico
	for($this->i=0; $this->i<$this->num_espacios; $this->i++){
	 	/*$this->cadena_html.="<table border='1' class='texto_pequeño'  cellspacing='7' cellpadding='5' width='100%'>";*/
		$this->cadena_html.="<tr class='texto_pequeño'";
		if(($color%2)==0){
		   $this->cadena_html.="bgcolor=#F7F7F7";	
                }
		$this->cadena_html.=">";
		$this->cadena_html.="<td width='8%' align='right' >";
		$this->cadena_html.=sprintf("%03d",$this->espaciosNivel[$this->i][0]);
                $this->cadena_html.="</td>";
		$this->cadena_html.="<td width='35%' >";
		$this->cadena_html.=utf8_encode($this->espaciosNivel[$this->i][1]);
                $this->cadena_html.="</td>"; 
			      
		for($this->j=0; $this->j<count($this->reg_caracteristica); $this->j++){
 
   
                         				 
		     	#Carga en el vector parametros  id_planEstudio, id_nivel, id_espacio
		     	$parametros=$this->id_planEstudio.";".$this->id_nivel.";".$this->espaciosNivel[$this->i][0];
		 	$this->cadena_html.="<td>";
			$this->cadena_html.="<input type='checkbox' id='esp_".$this->espaciosNivel[$this->i][0]."_".$this->j."'  "; 
                     
			#Seleccciona opcion previamente almacenada 
			 if($this->espaciosNivel[$this->i][$this->campo_consulta]==$this->reg_caracteristica[$this->j][0]){
			     $this->cadena_html.="checked=true";
			 }
			 $this->cadena_html.="onclick='";
                         $this->cadena_html.="desactivarTodos(\"esp_";
			 $this->cadena_html.=$this->espaciosNivel[$this->i][0]."\", ";
                         $this->cadena_html.="\"".count($this->reg_caracteristica)."\",";
                         $this->cadena_html.="\"".$this->j."\");";
                         $this->cadena_html.="xajax_guardarCaracteristica";
			 $this->cadena_html.="(\"".$this->caracteristica."\",";
			 $this->cadena_html.="\"".$parametros."\",";
			 $this->cadena_html.="\"".$this->reg_caracteristica[$this->j][0]."\"";
                         $this->cadena_html.=")'";
			 $this->cadena_html.=">";
			 $this->cadena_html.="&nbsp;".utf8_encode($this->reg_caracteristica[$this->j][1]);
			 $this->cadena_html.="</td>";
		}#Cierre de for 
				 
		$this->cadena_html.="</tr>";
                
		
		$color=$color+1;
	}#Cierre de for
			
	$this->cadena_html.="</table>";
	/*if($this->caracteristica=="clasificacion"){

                   $this->sentencia=$obj_sql->cadena_sql($this->conf,$this->caracteristica,"");
	           $this->clasificacion=$obj_conexion->ejecutarAcceso($this->sentencia,"busqueda");
		   
		   	$this->cadena_html.="<table>";
                               
                        for($this->j=0; $this->j<count($this->clasificacion); $this->j++){
				$this->cadena_html.="<tr>";
				$this->cadena_html.="<td>".$this->clasificacion[$this->j][1]."</td>";
				$this->cadena_html.="<td>".$this->clasificacion[$this->j][2]."</td>";	$this->cadena_html.="</tr>";
			}#Cierre de for    
			 $this->cadena_html.="</table>";	
		   
		}#Cierre de if $this->caracteristica=="clasificacion"
		
		$this->cadena_html.="</table>";*/
   }#Cierre de funcion cargar_clasificacion
	
   function cargar_combos(){

        $color=0;
   
   	$obj_html=new html();
        #Construye Matriz_item: Es un vector, donde la posicion cero y las posiciones pares corresponden a los
	#labels de los grupos de opciones y las posiciones impares corresponden a las opciones por cada grupo.
        #Las posiciones impar contienen un vector con las opciones correspondientes algrupo de opciones
	$matriz_items=$this->itemsCombo();
	
	$this->cadena_html.="<table border='0' class='bloquelateral' cellspacing='6'"; $this->cadena_html.="cellspacing='7' cellpadding='5'>";
        $this->cadena_html.="<tr class='texto_pequeño'>";
	$this->cadena_html.="<td width='43%' colspan='2' class='texto_negrita' align='center'>";
	$this->cadena_html.="Espacios Académicos";
	$this->cadena_html.="</td>";
	$this->cadena_html.="<td colspan='5' align='center' class='texto_negrita'>";
	$this->cadena_html.=$this->label;
	$this->cadena_html.="</td>";
	$this->cadena_html.="</tr>";

	
	#Genera fila por cada espacio academico
	for($this->i=0; $this->i<$this->num_espacios; $this->i++){
	
	    	$this->nom_control='id_areaFormacion_'.$this->espaciosNivel[$this->i][0];
			
	    	#Carga en el vector parametros  id_planEstudio, id_nivel, id_espacio
 	    	$parametros=$this->id_planEstudio.";".$this->id_nivel.";".$this->espaciosNivel[$this->i][0];
			 
		$this->conf["ajax_function"]="xajax_guardarCaracteristica(";
		$this->conf["ajax_function"].="'".$this->caracteristica."','".$parametros."',this.value)"; 
		$this->conf["ajax_control"]=$this->nom_control;	
		
		     
		#Carga en el vector parametros  id_planEstudio, id_nivel, id_espacio
 	        $parametros=$this->id_planEstudio.";".$this->id_nivel.";".$this->espaciosNivel[$this->i][0]; 
		   
		
		$this->cadena_html.="<tr class='texto_pequeño'";
                #Evalua fila para poner color
                if(($color%2)==0){
		   $this->cadena_html.="bgcolor=#F7F7F7";	
                }

		$this->cadena_html.=">";
		$this->cadena_html.="<td width='8%' align='right' >";
		$this->cadena_html.=sprintf("%03d",$this->espaciosNivel[$this->i][0]);
                $this->cadena_html.="</td>";
		$this->cadena_html.="<td width='35%' >";
		$this->cadena_html.=utf8_encode($this->espaciosNivel[$this->i][1]);
                $this->cadena_html.="</td>"; 
		$this->cadena_html.="<td>";
		
                if($this->caracteristica=="modalidad"){
		   $this->cadena_html.=$obj_html->cuadro_lista($matriz_items,'id_modalidad',$this->conf,$this->espaciosNivel[$this->i][7],2,'','',$this->nom_control);
                }	
                if($this->caracteristica=="areaFormacion"){
		   $this->cadena_html.=$obj_html->cuadro_lista($matriz_items,'id_areaFormacion',$this->conf,$this->espaciosNivel[$this->i][2],'2','','',$this->nom_control); 
                }
		      
			 
		$this->cadena_html.="</td>";	 
		$this->cadena_html.="</tr>";
		
		$color=$color+1;
	}#Cierre de for
	$this->cadena_html.="</table>";
   }#Cierre de funcion cargar_areaFormacion

   function itemsCombo(){
   
        #Instancia objeto de clase sql  
	$obj_sql=new sql_espacioPlan(); 
		
	#Obtiene la configuracion del sistema
	$this->conf=$this->configuracion();
	 
	#Obtiene conexion 
	$obj_conexion=$this->conectar();

        #Si esta caragando combo para areas de formacion 
       /* if($this->caracteristica=="areaFormacion"){ 

		$this->sentencia=$obj_sql->cadena_sql($this->conf,"areasFormacion","");
		$this->areasFormacion=$obj_conexion->ejecutarAcceso($this->sentencia,"busqueda");
		
	        $matriz_valores=""; #Vector que almacena los nombres de los grupos de opciones y sus items
        	$j=0; #Contador para posicion de vector $matriz_valores
           
        	for($i=0; $i<count($this->areasFormacion); $i++)
           	{
                	 #Carga el nombre del area de conocimiento  en la matriz para los option group
                 	$this->areasFormacion[$i][1];
                 	$matriz_valores[$j]=utf8_encode($this->areasFormacion[$i][1]); #Carga nombre del grupo de opciones   
      
                 	#Consulta las areas academicas del area de conocimiento que se esta cargando
                 	$variable[0]="id_areaFormacion";
	             	$variable[1]=$this->areasFormacion[$i][0];	

			$this->sentencia=$obj_sql->cadena_sql($this->conf,"areaAcademica",$variable);
		         $this->areasAcademicas=$obj_conexion->ejecutarAcceso($this->sentencia,"busqueda");
		 	
	  	         #Construye un arreglo con los items(Areas academicas), que pertenecen al area de conocimiento, y lo  
				 #asigna a la posicion siguiente del arreglo      
				 
                 	unset($items);          
                 	if (is_array($this->areasAcademicas)){
				for($n=$m=0; $n<count($this->areasAcademicas); $n++){
								$items[$m]=$this->areasAcademicas[$n][2]; #Codigo del area academica
					$items[$m+1]=$this->areasAcademicas[$n][1]; #Nombre del area academica 
					$m=$m+2; 
		    		}#Cierre de for  
                    
                    		$matriz_valores[$j+1]=$items; 
                   
                 	}#Cierre de if
                 	else{
                    		$matriz_valores[$j+1]="0";
                 	}  
		 
                	 $j=$j+2; #Incrementa posicion en el vector $matriz_valores
           	}#Cierre de for $i

	   }#Cierre de if ($this->caracteristica=="areaFormacion")*/

	   if($this->caracteristica=="areaFormacion"){
	     $this->sentencia=$obj_sql->cadena_sql($this->conf,"areasFormacion","");
	     $matriz_valores=$obj_conexion->ejecutarAcceso($this->sentencia,"busqueda");
			
	   }

	   #si esta caragando combos para modalidad	
           if($this->caracteristica=="modalidad"){
		
	     $this->sentencia=$obj_sql->cadena_sql($this->conf,"modalidad","");
	     $matriz_valores=$obj_conexion->ejecutarAcceso($this->sentencia,"busqueda");	
             
	   }#Cierre de if $this->caracteristica=="modalidad"  
	   	
           return  $matriz_valores;
   }#Cierre de funcion cargarCombos()
   
   
  
   	

}#Cierre de clase caracteristicasEspaciosPlan

  

 function caracteristicasEspacios($caracteristica,$parametros){

     #Objeto de clase caracterisiticas 
     $obj_caracteristica=new caracteristicasEspaciosPlan();

     #Obtiene la configuracion del sistema
     $configuracion=$obj_caracteristica->configuracion();
     $obj_caracteristica->configuracion=$configuracion;
	 
		 
     #Obtiene conexion 
     $conexion=$obj_caracteristica->conectar();
		
		
     #La variable parametros trae el id del plan de estudios que se esta caragando y el nivel
     $parametros=explode(",",$parametros);
     $obj_caracteristica->id_planEstudio=$parametros[0];	
     $obj_caracteristica->id_nivel=$parametros[1];

     $variable[0]=$obj_caracteristica->id_nivel;
     $variable[1]=$obj_caracteristica->id_planEstudio;
	 
     $obj_sql=new sql_espacioPlan();	

     #Consulta caracteristicas completas de los espacios academicos que componene el nivel
     $obj_caracteristica->sentencia=$obj_sql->cadena_sql($configuracion,"espaciosNivel",$variable);
     $obj_caracteristica->espaciosNivel=$conexion->ejecutarAcceso($obj_caracteristica->sentencia,"busqueda");		

     $obj_caracteristica->num_espacios=count($obj_caracteristica->espaciosNivel);     	
     $obj_caracteristica->caracteristica=$caracteristica; 	 	
	
     switch($caracteristica){
        #$obj_caracteristica->campo_consulta, es el campo al que se debe remitir en la funcion cuando se quiera extraer el        #campo y su valor. Ej: 8,9 es la posicion que ocupan los campos horas en la consulta 
		case "horas":
			  $obj_caracteristica->campo_consulta='8,9'; 
			  $obj_caracteristica->label="Horas de trabajo";	
			  $obj_caracteristica->cargar_horas();
		break;
		
		case "clasificacion":
              		  $obj_caracteristica->campo_consulta=3;
                          $obj_caracteristica->label="Clasificación";	
			  $obj_caracteristica->cargar_caracteristica();
			  
  		break;
	
		case "areaFormacion":
    			  $obj_caracteristica->label="Area de Formación";	
			  $obj_caracteristica->cargar_combos();	
		break;
	
		case "tipo":
              		  $obj_caracteristica->campo_consulta=4;
  			  $obj_caracteristica->label="Tipo";	
			  $obj_caracteristica->cargar_caracteristica();	
		break;
		
		case "naturaleza":
			  $obj_caracteristica->campo_consulta=6;
			  $obj_caracteristica->label="Naturaleza";	
			  $obj_caracteristica->cargar_caracteristica();	
		break;
	
		case "modalidad":
			  $obj_caracteristica->campo_consulta=8;
			  $obj_caracteristica->label="Modalidad";	
			  $obj_caracteristica->cargar_combos();
	
		break;
		
		case "caracter":
			  $obj_caracteristica->campo_consulta=5;
			  $obj_caracteristica->label="Caracter";	
			  $obj_caracteristica->cargar_caracteristica();
		
		break;	
		
		case "codigos":
		      #Genera consulta para codigos de la aplicacion academica 
		      $obj_caracteristica->sentencia=$obj_sql->cadena_sql($configuracion,"codigosAcademica",$variable);
              	      $obj_caracteristica->codAcademica=$conexion->ejecutarAcceso($obj_caracteristica->sentencia,"busqueda");		
		      $obj_caracteristica->label="Códigos en Aplicación Académica";	
		      #Invoca la funcion para cargar codigos
		      $obj_caracteristica->cargar_codigos(); 
	
		break;

    }#Cierre de switch($caracterisitica)	
	
     		
    	
     $respuesta = new xajaxResponse();
     #Se asignan los valores al objeto y se envia la respuesta	
     $respuesta->addAssign('div_caracteristicas',"innerHTML",$obj_caracteristica->cadena_html);
     return $respuesta; 		

}#Cierre de funcion caracterisiticasEspacios 

function guardarCaracteristica($caracteristica,$parametros,$valor_caracteristica){

     #Objeto de clase caracterisiticas 
     $obj_caracteristica=new caracteristicasEspaciosPlan();
	 
     #La variable parametros trae el id del plan de estudios que se esta caragando y el nivel
     $parametros=explode(";",$parametros);
     $obj_caracteristica->id_planEstudio=$parametros[0];	
     $obj_caracteristica->id_nivel=$parametros[1];
     $obj_caracteristica->id_espacio=$parametros[2];
   

     #Obtiene la configuracion del sistema
     $configuracion=$obj_caracteristica->configuracion();
     $obj_caracteristica->configuracion=$configuracion;
	 
		 
     #Obtiene conexion 
     $conexion=$obj_caracteristica->conectar();
		
     #Instancia objeto sql para consultar si ya existe paramtro registrado para esta caracterisitca		
     $obj_sql=new sql_espacioPlan();	
     $variable[0]=$caracteristica;
     $variable[1]=$obj_caracteristica->id_planEstudio;
     $variable[2]=$obj_caracteristica->id_nivel;
     $variable[3]=$obj_caracteristica->id_espacio;
     $variable[4]=$valor_caracteristica;
	 
     $obj_caracteristica->sentencia=$obj_sql->cadena_sql($configuracion,"consultaCaracteristica",$variable);
     $obj_caracteristica->espacioRegistrado=$conexion->ejecutarAcceso($obj_caracteristica->sentencia,"busqueda");	
	 
     #Si esa caracterisitica ya fue registrada en el sistema se debe realizar una actualizacion de lo contrario
     #se debe realizar una insercion
     if($obj_caracteristica->espacioRegistrado[0][0]>=1){
	 
	 $obj_caracteristica->sentencia=$obj_sql->cadena_sql($configuracion,"actualizarCaracteristica",$variable);
     $obj_caracteristica->espacioActualizado=$conexion->ejecutarAcceso($obj_caracteristica->sentencia,"busqueda");	
	 
    }#Cierre de if $obj_caracteristica->espacioRegistrado[0][0]>1
}#Cierre de funcion guardarCaracteristica

function guardarHoras($htd,$htc,$id_planEstudio,$id_nivel,$id_espacio){

	 #Objeto de clase caracterisiticas 
     $obj_caracteristica=new caracteristicasEspaciosPlan();
	 
     #Obtiene la configuracion del sistema
     $configuracion=$obj_caracteristica->configuracion();
     $obj_caracteristica->configuracion=$configuracion;
	 
		 
     #Obtiene conexion 
     $conexion=$obj_caracteristica->conectar();
		
     #Instancia objeto sql para consultar si ya existe paramtro registrado para esta caracterisitca		
     $obj_sql=new sql_espacioPlan();	
     $variable[0]=$id_espacio;
     $variable[1]=$id_planEstudio;
     $variable[2]=$id_nivel;
     $variable[3]=$htd;
     $variable[4]=$htc;
	 
	 $obj_caracteristica->sentencia=$obj_sql->cadena_sql($configuracion,"actualizarHoras",$variable);
     $obj_caracteristica->espacioActualizado=$conexion->ejecutarAcceso($obj_caracteristica->sentencia,"busqueda");
	 

}#Cierre de funcion guradarHoras 

function guardarCodigos($cod_academica,$id_planEstudio,$id_espacio){

    
     #Objeto de clase caracterisiticas 
     $obj_caracteristica=new caracteristicasEspaciosPlan();
	 
     #Obtiene la configuracion del sistema
     $configuracion=$obj_caracteristica->configuracion();
     $obj_caracteristica->configuracion=$configuracion;
	 
		 
     #Obtiene conexion 
     $conexion=$obj_caracteristica->conectar();
		
     #Instancia objeto sql para consultar si ya existe codigo de la aplicacion academica, registrada 
     $obj_sql=new sql_espacioPlan();	
     $variable[0]=$id_planEstudio;
     $variable[1]=$id_espacio;
     $variable[2]=$cod_academica;
	 
     $obj_caracteristica->sentencia=$obj_sql->cadena_sql($configuracion,"consultaRegistroAcademica",$variable);
     $obj_caracteristica->codigoRegistrado=$conexion->ejecutarAcceso($obj_caracteristica->sentencia,"busqueda");	
	 
     #Si el espacio academico ya tiene codigo de la aplicacion acadenica registrado, realiza una actualizacion
     #De lo contrario realiza una insercion
     if($obj_caracteristica->codigoRegistrado[0][0]>=1){
	    	
	$obj_caracteristica->sentencia=$obj_sql->cadena_sql($configuracion,"actualizarCodigoAcademica",$variable);
        $obj_caracteristica->codigoActualizado=$conexion->ejecutarAcceso($obj_caracteristica->sentencia,"busqueda");	
     }#Cierre de if
     else{
	$obj_caracteristica->sentencia=$obj_sql->cadena_sql($configuracion,"insertaCodigoAcademica",$variable);
        $obj_caracteristica->codigoInsertado=$conexion->ejecutarAcceso($obj_caracteristica->sentencia,"busqueda");	
     }#Cierre de else	
	 
     	 
}#Cierre de funcion guardarCodigos 

?>
