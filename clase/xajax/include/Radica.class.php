<?
/*---------------------------------------------------------------------------------------
 |				Control Versiones				    	|
 ----------------------------------------------------------------------------------------
 | fecha      |        Autor            | version     |              Detalle            |
 ----------------------------------------------------------------------------------------
 | 06/04/2010 | Jairo Lavado      	| 0.0.0.1     |                                 |
 ----------------------------------------------------------------------------------------
*/

function AREA2($a)
{
	$respuesta = new xajaxResponse();
		
	$respuesta->addAssign("areaDep","innerHTML","Hola Mundo!!!");
	return $respuesta;
}


function AREA($id_dep)

{

	//rescata el valor de la configuracion
	require_once("clase/config.class.php");
	setlocale(LC_MONETARY, 'en_US');
	$esta_configuracion=new config();
	$configuracion=$esta_configuracion->variable();
	//Buscar un registro que coincida con el valor
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");


	$conexion=new funcionGeneral();
	//$conexionOracle=$conexion->conectarDB($configuracion,"coordinador");
	$conexion_db=$conexion->conectarDB($configuracion,"");
        include_once("sql.class.php");
	$sql=new sql_xajax();
        
        $busquedaArea =$sql->cadena_sql($configuracion,"buscar_area",$id_dep);
        //echo $busquedaArea;
        $html=new html();
        $area=$html->cuadro_lista($busquedaArea,'id_area',$configuracion,0,0,FALSE,$tab++,'id_area');

	//Se asignan los valores al objeto y se envia la respuesta-
        $respuesta = new xajaxResponse();
        $respuesta->addAssign("areaDep","innerHTML",$area);

	return $respuesta;

}


function DOCUMENTO($id_tram)

{

	//rescata el valor de la configuracion
	require_once("clase/config.class.php");
	setlocale(LC_MONETARY, 'en_US');
	$esta_configuracion=new config();
	$configuracion=$esta_configuracion->variable();
	//Buscar un registro que coincida con el valor
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");


	$conexion=new funcionGeneral();
	//$conexionOracle=$conexion->conectarDB($configuracion,"coordinador");
	$conexion_db=$conexion->conectarDB($configuracion,"");
        include_once("sql.class.php");
	$sql=new sql_xajax();
        
        $busquedaDoc =$sql->cadena_sql($configuracion,"buscar_doc",$id_tram);
        //echo $busquedaDoc;
        $html=new html();
        $doc_tram=$html->cuadro_lista($busquedaDoc,'id_doc',$configuracion,0,0,FALSE,$tab++,'id_doc');

	//Se asignan los valores al objeto y se envia la respuesta-
        $respuesta = new xajaxResponse();
        $respuesta->addAssign("docTram","innerHTML", $doc_tram);

	return $respuesta;

}



?>
