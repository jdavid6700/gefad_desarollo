<?
/*---------------------------------------------------------------------------------------
 |				Control Versiones				    	|
 ----------------------------------------------------------------------------------------
 | fecha      |        Autor            | version     |              Detalle            |
 ----------------------------------------------------------------------------------------
|
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

function ASIG_USUARIO($est,$dTram)

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
        $html=new html();
        
        //busca los dartos del tramite y posicion
        $area_sql=$sql->cadena_sql($configuracion,"busqueda_radicado",$dTram);
        $rs_area = $conexion->ejecutarSQL($configuracion,$conexion_db,$area_sql,"busqueda");
        $total_area = $conexion->totalRegistros($configuracion,$conexion_db);

        $usua_tram= array(id_tramite=>$rs_area[0]['ID_TRAM']);
        //si esta aprobado
        if($est==6 || $est==1)
            {
             //busca el maximo de areas del tramite
             $areaTram_sql=$sql->cadena_sql($configuracion,"areas_tramite",$rs_area[0]['ID_TRAM']);
             $rs_areaTram = $conexion->ejecutarSQL($configuracion,$conexion_db,$areaTram_sql,"busqueda");
             
             if($rs_area[0]['POS']<$rs_areaTram[0]['areas'])
                 { $usua_tram['posicion']=($rs_area[0]['POS']+1);
                 
                   $usu_sql=$sql->cadena_sql($configuracion,"busqueda_usuario_area",$usua_tram); 
                   $area_us=$html->cuadro_lista($usu_sql,'asig_usuario',$configuracion,-1,0,FALSE,$tab++,'asig_usuario');
                 }
             else{ $area_us="No Existen más Áreas para este Tramite!";}
                                       
            }
        else{
            if($rs_area[0]['POS']==0)
                { $area_us="No Existen más Áreas para este Tramite!";}
            else    
                {
                $usua_tram['posicion']=0;
                $usu_sql=$sql->cadena_sql($configuracion,"busqueda_usuario_area",$usua_tram); 
                $area_us=$html->cuadro_lista($usu_sql,'asig_usuario',$configuracion,-1,0,FALSE,$tab++,'asig_usuario');
                }
            }    
        
        
	//Se asignan los valores al objeto y se envia la respuesta-
        $respuesta = new xajaxResponse();
        $respuesta->addAssign("asigUs","innerHTML",$area_us);

	return $respuesta;

}

?>
