
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

 function usuario($valor)
    {
        require_once("clase/config.class.php");
    $esta_configuracion=new config();
    $configuracion=$esta_configuracion->variable();
    $funcion=new funcionGeneral();
    //Conectarse a la base de datos
    $acceso_db=new dbms($configuracion);
    $enlace=$acceso_db->conectar_db();
    $valor=$acceso_db->verificar_variables($valor);

    $html=new html();
    $conexion=new multiConexion();
    $accesoOracle=$conexion->estableceConexion(99,$configuracion);

    if (is_resource($enlace))
        {

        switch($valor){

            case "3":
            $busqueda="SELECT id_facultad, nombre_facultad FROM ".$configuracion['prefijo']."facultad";//echo $busqueda;

            $resultado=$funcion->ejecutarSQL($configuracion, $accesoOracle, $busqueda,"busqueda");

            $i=0;
            while(isset ($resultado[$i][0]))
            {
                $resultado_1[$i][0]=$resultado[$i][0];
                $resultado_1[$i][1]=htmlentities($resultado[$i][1]);
                $i++;
            }
            $mi_cuadro=$html->cuadro_lista($resultado_1,"facultad",$configuracion,0,0,TRUE,0,"facultad",200);
            $respuesta = new xajaxResponse();
            $respuesta->addAssign("div_seleccion","innerHTML",$mi_cuadro);
            return $respuesta;
            break;

            case "4":
            $busqueda="SELECT DISTINCT `planEstudioProyecto_idPlanEstudio` , planEstudio_nombre
                        FROM `sga_planEstudio_proyecto` PEP
                        INNER JOIN sga_planEstudio PE ON PEP.planEstudioProyecto_idPlanEstudio = PE.id_planEstudio";//echo $busqueda;exit;

            $resultado=$funcion->ejecutarSQL($configuracion, $accesoOracle, $busqueda,"busqueda");

            $i=0;
            while(isset ($resultado[$i][0]))
            {
                $resultado_1[$i][0]=$resultado[$i][0];
                $resultado_1[$i][1]=htmlentities($resultado[$i][1]);
                $i++;
            }
            $mi_cuadro=$html->cuadro_lista($resultado_1,"proyecto",$configuracion,0,0,TRUE,0,"proyecto",200);
            $respuesta = new xajaxResponse();
            $respuesta->addAssign("div_seleccion","innerHTML",$mi_cuadro);
            return $respuesta;
            break;

            default:
                $mi_cuadro=" "; //var_dump($mi_cuadro);exit;
                $respuesta = new xajaxResponse();
                $respuesta->assign("div_seleccion","innerHTML",$mi_cuadro);
                return $respuesta;
                break;
        }
        }


        }
?>
