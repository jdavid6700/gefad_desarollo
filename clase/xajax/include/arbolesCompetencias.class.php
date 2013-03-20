<?
/*
/***************************************************************************
******************************************************************************
* @subpackage   
* @package	clase
* @copyright    
* @version      0.1
* @author      	Kelly K. López
* @description  Clase para el manejo de muestra de competencias en arboles de consulta
*******************************************************************************
*/
 /***************************************************************
  * @ $id_div        Div a la que se va a asignar la siguiente jerarquia del arbol
  * @ $id_elemento   Codigo del elemento a partir del que se van a generar las consultas
  * @ $nivel         Nivel de jerarquia del arbol
  * @ $descripcion   Determina si se va a generar un nivel mas del arbol o si va a generar  		     la descripcion del elemento, por lo general el nivel mas alto, es el 		     que muestra la descripcion (Para este caso es el 4)
 /***************************************************************/

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
include_once($configuracion["raiz_documento"]."/bloque/admin_competencia/sql.class.php");


/* En esta clase fue necesario crear una clase que herede de la clase funcionGeneral
   para poder rescatar la variable de sesion donde se encuentra el arbol que se esta consultando 
*/
class arbolesCompetencias extends funcionGeneral{
    
   function nuevoRegistro($configuracion,$tema,$acceso_db){ }
   function mostrarRegistro($configuracion,$registro, $total, $opcion="",$variable){ }
   function editarRegistro($configuracion,$tema,$id_entidad,$acceso_db,$formulario){ }
   function corregirRegistro(){ }

   function __construct($configuracion)
   {
     include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
     $this->tema=$tema;
     $this->sql=new sql_adminCompetencia();
   }
}#Cierre de clase arbolesCompetencias


function generar_lista($id_div,$id_elemento,$nivel,$descripcion){
    
    require_once("clase/config.class.php");
    setlocale(LC_MONETARY, 'en_US');

    
    $esta_configuracion=new config();
    $configuracion=$esta_configuracion->variable(); 
    
    /*Incluye estas clases para poder trabajar los vinculos de los botones*/
    
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
    $obj_competencias= new arbolesCompetencias($configuracion);
    $id_arbol=$obj_competencias->rescatarValorSesion($configuracion,$acceso_db, "id_arbol");
   
    $variable[0]=$id_arbol; 
    $variable[1]=$id_elemento;
    $sentencia=$obj_competencias->sql->cadena_sql($configuracion,"relacionado",$variable);
    
    /*Consulta por la competencia padre del arbol para volver a cargar el arbol*/
    $sentencia_padre=$obj_competencias->sql->cadena_sql($configuracion,"padreArbol",$variable);
    $resultado=$acceso_db->ejecutarAcceso($sentencia_padre,"busqueda");
    $id_padre=$resultado[0][0]; 
   
    #Determina si  tiene boton de link para mostrar un nivel inferior (Tiene elementos asociados)
    $link=1; 

    #Determina si el nombre del elemento tiene posibilidad de ampliar la informacion generada
    $describe=0;

    $tipoBoton="botonImagen";

                                  /*Construye vector botones */

    #---Boton Ver
    $vector_ver[0]="Ver";
    #Para este caso id_espacio debe estar en la ultima parte cargarlo desde la
    #clase arbol
    $vector_ver[1]="pagina=verCompetencia&opcion=ver&id_arbol=".$id_arbol."&id_competencia=";
    $vector_ver[2]="<?echo $configuracion['site'].$configuracion['grafico']?>/viewrel.png";
    $vector_ver[3]="onclick"; #Boton que abre ventana emergente
    $vector_ver[4]="','Competencia','700','580','390','140')\";"; #Caracteristicas de ventan emergente
    #Se asigna el vector del boton editar a la posicion 0
    $botones[0]=$vector_ver;

    #---Boton Editar
    $vector_editar[0]="Editar";
    #Para este caso id_espacio debe estar en la ultima parte cargarlo desde la
    #clase arbol
    $vector_editar[1]="pagina=adminCompetencia&opcion=editar&id_arbol=".$id_arbol."&comp_padre=".$id_padre."&id_competencia=";
    $vector_editar[2]="<?echo $configuracion['site'].$configuracion['grafico']?>/editar.png";
    $vector_editar[3]="href"; #Boton que redirecciona la pagina
    #Se asigna el vector del boton editar a la posicion 1
    $botones[1]=$vector_editar;
                
    #---Boton Eliminar
    #Debe construir la direccion a la que va a retornar la pagina despues de realizar la eliminacion, para pasarla como parametro
    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
    $cripto=new encriptar(); 
    $redireccion="";	
    reset ($_REQUEST);
	while (list ($clave, $val) = each ($_REQUEST)) 
	{
 	      $redireccion.="&".$clave."=".$val;
	}
    $redireccion="&redireccion=".$cripto->codificar_url($redireccion,$configuracion); 

    $vector_eliminar[0]="Eliminar";
    $vector_eliminar[1]="pagina=borrar_registro&opcion=competencia".$redireccion."&registro=";
    $vector_eliminar[2]="<?echo $configuracion['site'].$configuracion['grafico']?>/cancelar.gif";
    $vector_eliminar[3]="href";
    #Se asigna el vector del boton editar a la posicion 2. 
    $botones[2]=$vector_eliminar;

    /*Si descripcion esta en cero, se esta generando una  nivel jerarquico en el arbol*/ 
    if($descripcion==0){
        unset($resultado); //Limpia la variable
        $resultado=$acceso_db->ejecutarAcceso($sentencia,"busqueda");

	#Si existen resultados, carga el vector, genera objeto de la clase arbol y carga la informacion
	if(count($resultado)>=1){
	#Recorre los registros y almacena en un vector id del elemento que se esta trabajando
	#Nombre del elemento que se esta trabajando
	for($n=$m=0; $n<count($resultado); $n++){
		$items_lista[$m]=$resultado[$n][0]; #Codigo de la competencia
		$items_lista[$m+1]=$resultado[$n][2]; #Nombre de la competencia 
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
	$cadena_html="<table width='100%' border=0><tr><td class='texto_subtitulo'>
			No existen elementos asociados
		      </td></tr></table>" ;
	}#Cierre de else count($resultado)>1 	

 
    }#Cierre de if $descripcion=0
   
    $respuesta = new xajaxResponse();

    #Se asignan los valores al objeto y se envia la respuesta	
    $respuesta->addAssign($id_div,"innerHTML",$cadena_html);
    return $respuesta; 	
 }#Cierre de funcion cargarCapa

 

  
?>
