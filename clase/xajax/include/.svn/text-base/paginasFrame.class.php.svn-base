<?

//======= Revisar si no hay acceso ilegal ==============
if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;
}
//======================================================
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/multiConexion.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");

    function pagina($valor)
    {
    require_once("clase/config.class.php");
    $esta_configuracion=new config();
    $configuracion=$esta_configuracion->variable();
    $funcion=new funcionGeneral();
    //Conectarse a la base de datos
    $acceso_db=new dbms($configuracion);
    $enlace=$funcion->conectarDB($configuracion,"");
//    $this->acceso_db=$this->conectarDB($configuracion,"");
    
    $valor=$acceso_db->verificar_variables($valor);
    
    $html=new html();
    
    
          $busqueda="
                    SELECT sga_bloque.id_bloque , sga_bloque.nombre, sga_bloque_pagina.seccion, sga_bloque_pagina.posicion FROM sga_bloque
                        INNER JOIN sga_bloque_pagina ON sga_bloque.id_bloque = sga_bloque_pagina.id_bloque
                            WHERE id_pagina=".$valor."

                    ";

            $resultado=$funcion->ejecutarSQL($configuracion, $enlace, $busqueda,"busqueda");
            $i=0;
            while(isset ($resultado[$i][0]))
            {
                $resultado_1[$i][0]=$resultado[$i][0];
                $resultado_1[$i][1]=$resultado[$i][2]." - ".$resultado[$i][3]." - Bloque: ".$resultado[$i][0]." - ".$resultado[$i][1];
                $i++;
            }
            
            $mi_cuadro=$html->cuadro_lista($resultado_1,"relacion",$configuracion,0,0,FALSE,0,"relacion");
            $respuesta = new xajaxResponse();
            $respuesta->addAssign("relacionar","innerHTML",$mi_cuadro);
        
            return $respuesta;


}

?>