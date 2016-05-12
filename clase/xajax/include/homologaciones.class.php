<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Horarios
 *
 * @author Edwin Sanchez
 */
//======= Revisar si no hay acceso ilegal ==============
if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}
//======================================================
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/funcionGeneral.class.php");
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/html.class.php");

function buscarPadre($cod_espacio,$carrera,$num_div,$num_formulario) {
    require_once("clase/config.class.php");
    setlocale(LC_MONETARY, 'en_US');
    $esta_configuracion = new config();
    $configuracion = $esta_configuracion->variable();
    //echo $num_div;exit;
    //$funcion = new funcionGeneral();
    //Conectarse a la base de datos
    $conexion=new funcionGeneral();
    $conexionOracle=$conexion->conectarDB($configuracion,"coordinadorCred");
    //$valor=$acceso_db->verificar_variables($valor);
    $html = new html();
    $mensaje="";   
        $busqueda = "SELECT DISTINCT ASI_NOMBRE NOM_ASIGNATURA,
                    (CASE WHEN PEN_CRE IS NOT NULL THEN PEN_CRE    ELSE 0 END)   AS CREDITOS
                    FROM ACPEN, ACASI
                    WHERE PEN_ESTADO='A'
                    AND PEN_ASI_COD = ASI_COD
                    AND PEN_ASI_COD= ".$cod_espacio."
                    ORDER BY ASI_NOMBRE";
        //echo $busqueda;exit;
        $resultado_espacio = $conexion->ejecutarSQL($configuracion, $conexionOracle, $busqueda, "busqueda");
        $nombre_espacio= $resultado_espacio[0]['NOM_ASIGNATURA'];
         if($nombre_espacio==''){
              $mensaje = "<b >El espacio academico ".$cod_espacio.":  no corresponde a un código de un espacio académico de la Universidad.</b>";
              $html2 ="";
         }else{
                $busqueda2 = "SELECT DISTINCT ASI_NOMBRE NOM_ASIGNATURA,
                    (CASE WHEN PEN_CRE IS NOT NULL THEN PEN_CRE    ELSE 0 END)   AS CREDITOS
                    FROM ACPEN, ACASI
                    WHERE PEN_CRA_COD= ".$carrera."
                    AND PEN_ESTADO='A'
                    AND PEN_ASI_COD = ASI_COD
                    AND PEN_ASI_COD= ".$cod_espacio."
                    ORDER BY ASI_NOMBRE";
                $resultado_proyecto = $conexion->ejecutarSQL($configuracion, $conexionOracle, $busqueda2, "busqueda");

                $espacio_proyecto= $resultado_proyecto[0]['NOM_ASIGNATURA'];
                if($espacio_proyecto==''){
                    $mensaje = "<b >El espacio academico ".$cod_espacio." - ".$nombre_espacio.": no pertenece al proyecto curricular.</b>";
                    
                }else{
                    $mensaje="";
                        //echo $busqueda2;exit;

                    }
                }
               if ($resultado_espacio[0]['CREDITOS']>0 ){
                        $creditos_espacio= $resultado_espacio[0]['CREDITOS'];
                        $html2 = "<input type='text' name='cred_padre".$num_div."' id='cred_padre".$num_div."' size='3' value='".$creditos_espacio."' readonly='true'>";

               }else{
                            $creditos_espacio= 0;
                            $html2 ="";
               }
        
        $html = "<input type='text' name='nom_padre".$num_div."' id='nom_padre".$num_div."' size='18' value='".$nombre_espacio."' readonly='true'>";
                //$mi_cuadro = $html->cuadro_lista($resultado_1, "plan", $configuracion, 0, 0, FALSE, 0, "plan");
        $respuesta = new xajaxResponse();
        $respuesta->addAssign("div_nomPadre$num_div", "innerHTML", $html);
        $respuesta->addAssign("div_credPadre$num_div", "innerHTML", $html2);
        $respuesta->addAssign("div_mensaje$num_formulario", "innerHTML", $mensaje);
        
    
    return $respuesta;
}

function buscarHijo($cod_espacio,$carrera,$num_div,$num_formulario){
        
    require_once("clase/config.class.php");
    setlocale(LC_MONETARY, 'en_US');
    $esta_configuracion = new config();
    $configuracion = $esta_configuracion->variable();
    //$funcion = new funcionGeneral();
    //Conectarse a la base de datos
    $conexion=new funcionGeneral();
    $conexionOracle=$conexion->conectarDB($configuracion,"coordinadorCred");
    //$valor=$acceso_db->verificar_variables($valor);
    $html = new html();
     $mensaje="";
        $busqueda = "SELECT DISTINCT ASI_NOMBRE NOM_ASIGNATURA,
                    (CASE WHEN PEN_CRE IS NOT NULL THEN PEN_CRE    ELSE 0 END)   AS CREDITOS
                    FROM ACPEN, ACASI
                    WHERE PEN_ESTADO='A'
                    AND PEN_ASI_COD = ASI_COD
                    AND PEN_ASI_COD= ".$cod_espacio."
                    ORDER BY ASI_NOMBRE";
        //echo "<br>busqueda ".$busqueda;exit;
        $resultado = $conexion->ejecutarSQL($configuracion, $conexionOracle, $busqueda, "busqueda");
        $nombre_espacio= $resultado[0]['NOM_ASIGNATURA'];
         if($nombre_espacio=='')
            $mensaje = "<b >".$cod_espacio.": no corresponde a un código de un espacio académico de la Universidad.</b>";
        else
            $mensaje="";
       if ($resultado[0]['CREDITOS']>0){
            $creditos_espacio= $resultado[0]['CREDITOS'];
            $html2 = "<input type='text' name='cred_hijo".$num_div."' id='cred_hijo".$num_div."' size='3' value='".$creditos_espacio."' readonly='true'>";
        
            }else{
                $creditos_espacio= 0;
                $html2 ="";
            }
        $i = 0;
        $html = "<input type='text' name='nom_hijo".$num_div."' id='nom_hijo".$num_div."' size='18' value='".$nombre_espacio."' readonly='true'>";
        
        //$mi_cuadro = $html->cuadro_lista($resultado_1, "plan", $configuracion, 0, 0, FALSE, 0, "plan");
        $respuesta = new xajaxResponse();
        $respuesta->addAssign("div_nomHijo$num_div", "innerHTML", $html);
        $respuesta->addAssign("div_credHijo$num_div", "innerHTML", $html2);
        $respuesta->addAssign("div_mensaje$num_formulario", "innerHTML", $mensaje);
        
    return $respuesta;

}

?>
