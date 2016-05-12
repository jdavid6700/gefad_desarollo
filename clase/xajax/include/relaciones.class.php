<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of valicacionCursoclass
 *
 * @author Edwin Sanchez
 */
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

    function planEst($valor)
    {
    require_once("clase/config.class.php");
    $esta_configuracion=new config();
    $configuracion=$esta_configuracion->variable();
    $funcion=new funcionGeneral();
    //Conectarse a la base de datos
    $acceso_db=new dbms($configuracion);
    $enlace=$acceso_db->conectar_db();
    $conexion=$funcion->conectarDB($configuracion, "conexionGestion");
    //$valor=$acceso_db->verificar_variables($valor);
    $espacio=$valor;
    
    $html=new html();
    

    if (is_resource($enlace))
        {
            $busqueda="
            SELECT id_planEstudio from ". $configuracion['prefijo']."planEstudio_espacio
                WHERE id_espacio = ".$espacio;

            
                
            $resultado=$funcion->ejecutarSQL($configuracion, $conexion, $busqueda,"busqueda");
            $respuesta = new xajaxResponse();
            $i=0;
            while(isset ($resultado[$i][0]))
            {
                $resultado_1[$i][0]=$resultado[$i][0];
                $resultado_1[$i][1]=$resultado[$i][0];
                $i++;
            }
            $mi_cuadro=$html->cuadro_lista($resultado_1,"id_planEstudio",$configuracion,0,0,FALSE,0,"id_planEstudio");
            $respuesta->addAssign("plan","innerHTML",$mi_cuadro);

            return $respuesta;

        }
    }

    function horas($valor)
    {
        
    require_once("clase/config.class.php");
    $esta_configuracion=new config();
    $configuracion=$esta_configuracion->variable();
    $funcion=new funcionGeneral();
    //Conectarse a la base de datos
    $acceso_db=new dbms($configuracion);
    $enlace=$acceso_db->conectar_db();
    $conexion_db=$funcion->conectarDB($configuracion, "conexionGestion");
    //$valor=$acceso_db->verificar_variables($valor);
    $asignatura=$valor;

    $html=new html();
    $conexion_ora=new multiConexion();
    $accesoOracle=$conexion_ora->estableceConexion(4,$configuracion);
    

    if (is_resource($enlace))
        {
            $busqueda="select PEN_NRO from ACPEN WHERE PEN_ASI_COD=".$asignatura;

            $resultado=$funcion->ejecutarSQL($configuracion, $accesoOracle, $busqueda,"busqueda");
            $respuesta = new xajaxResponse();
            $i=0;
            while(isset ($resultado[$i][0]))
            {
                $resultado_1[$i][0]=$resultado[$i][0];
                $resultado_1[$i][1]=$resultado[$i][0];
                $i++;
            }
            $mi_cuadro=$html->cuadro_lista($resultado_1,"id_pensumAcademica",$configuracion,0,0,FALSE,0,"id_pensumAcademica");
            $respuesta->addAssign("pensum","innerHTML",$mi_cuadro);

            return $respuesta;

        }
    }

    function carrera($asignatura,$pensum)
    {
    
    require_once("clase/config.class.php");
    $esta_configuracion=new config();
    $configuracion=$esta_configuracion->variable();
    $funcion=new funcionGeneral();
    //Conectarse a la base de datos
    $acceso_db=new dbms($configuracion);
    $enlace=$acceso_db->conectar_db();
    $conexion_db=$funcion->conectarDB($configuracion, "conexionGestion");
    //$valor=$acceso_db->verificar_variables($valor);

    $html=new html();
    $conexion_ora=new multiConexion();
    $accesoOracle=$conexion_ora->estableceConexion(4,$configuracion);


    if (is_resource($enlace))
        {
            $busqueda="select PEN_CRA_COD from ACPEN WHERE PEN_ASI_COD=".$asignatura." AND PEN_NRO=".$pensum;

            $resultado=$funcion->ejecutarSQL($configuracion, $accesoOracle, $busqueda,"busqueda");
            $respuesta = new xajaxResponse();
            $i=0;
            while(isset ($resultado[$i][0]))
            {
                $resultado_1[$i][0]=$resultado[$i][0];
                $resultado_1[$i][1]=$resultado[$i][0];
                $i++;
            }
            $mi_cuadro=$html->cuadro_lista($resultado_1,"id_codCarreraAcademica",$configuracion,0,0,FALSE,0,"id_codCarreraAcademica");
            $respuesta->addAssign("carrera","innerHTML",$mi_cuadro);

            return $respuesta;

        }
    }
    ?>