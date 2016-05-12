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
                $resultado_1[$i][0]=$resultado[$i][0]." - ".htmlentities($resultado[$i][1]);
                $resultado_1[$i][1]=htmlentities($resultado[$i][1]);
                $i++;
            }
            $mi_cuadro=$html->cuadro_lista($resultado_1,"facultad",$configuracion,0,0,TRUE,0,"facultad",200);
            $respuesta = new xajaxResponse();
            $respuesta->addAssign("div_seleccion","innerHTML",$mi_cuadro);
            return $respuesta;
            break;

            case "4":
            $busqueda="SELECT DISTINCT id_proyectoAcademica , planEstudio_nombre
                        FROM `sga_planEstudio_proyecto` PEP
                        INNER JOIN sga_planEstudio PE ON PEP.planEstudioProyecto_idPlanEstudio = PE.id_planEstudio
                        INNER JOIN sga_proyectoCurricular PC ON PE.id_proyectoCurricular = PC.id_proyectoCurricular";//echo $busqueda;exit;

            $resultado=$funcion->ejecutarSQL($configuracion, $accesoOracle, $busqueda,"busqueda");

            $i=0;
            while(isset ($resultado[$i][0]))
            {
                $resultado_1[$i][0]=$resultado[$i][0]." - ".htmlentities($resultado[$i][1]);
                $resultado_1[$i][1]=$resultado[$i][0]." - ".htmlentities($resultado[$i][1]);
                $i++;
            }
            $mi_cuadro=$html->cuadro_lista($resultado_1,"proyecto",$configuracion,0,0,TRUE,0,"proyecto",400);
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

            case "5":
            $busqueda="SELECT DISTINCT `planEstudioProyecto_idPlanEstudio` , planEstudio_nombre
                        FROM `sga_planEstudio_proyecto` PEP
                        INNER JOIN sga_planEstudio PE ON PEP.planEstudioProyecto_idPlanEstudio = PE.id_planEstudio";//echo $busqueda;exit;

            $resultado=$funcion->ejecutarSQL($configuracion, $accesoOracle, $busqueda,"busqueda");

            $i=0;
            while(isset ($resultado[$i][0]))
            {
                $resultado_1[$i][0]=$resultado[$i][0]." - ".htmlentities($resultado[$i][1]);
                $resultado_1[$i][1]=$resultado[$i][0]." - ".htmlentities($resultado[$i][1]);
                $i++;
            }
            $mi_cuadro=$html->cuadro_lista($resultado_1,"planEstudio",$configuracion,0,0,TRUE,0,"planEstudio",400);
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

        function nombreEstudiante($codEstudiante, $posicion)
            {

          if(!isset ($codEstudiante) ||  is_null($codEstudiante) || $codEstudiante=="")
            {
              echo 'Por favor ingrese el código del estudiante';
              exit;
            }

            if(!is_numeric($codEstudiante))
              {
              echo 'El valor ingresado debe ser numérico';
              exit;
              }
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
                $accesoOracle=$conexion->estableceConexion(75,$configuracion);

                if (is_resource($enlace))
                {
                    $busqueda="SELECT DISTINCT est_nombre, cra_nombre, est_estado_est ";
                    $busqueda.="from acest ";
                    $busqueda.="INNER JOIN accra ON acest.est_cra_cod=accra.cra_cod ";
                    $busqueda.="where est_cod=".$codEstudiante." and est_ind_cred like '%S%'";
                    //echo $busqueda;exit;

                    $resultado=$funcion->ejecutarSQL($configuracion, $accesoOracle, $busqueda,"busqueda");
                    
                    if(is_array($resultado))
                        {
                            $mi_cuadro=htmlentities(utf8_decode($resultado[0][0]));
                            $mi_cuadro.="&nbsp;&nbsp;&nbsp; <b>Estado: ".$resultado[0][2]."</b><br>";
                            $mi_cuadro.=htmlentities(utf8_decode($resultado[0][1]));
                        }else if($codEstudiante=='')
                            {
                                $mi_cuadro="<font color=red>POR FAVOR DIGITE UN C&Oacute;DIGO QUE CORRESPONDA A UN ESTUDIANTE DE CR&Eacute;DITOS.</font>";
                            }else
                            {
                             $mi_cuadro="<font color=red>EL C&Oacute;DIGO INGRESADO NO CORRESPONDE A UN ESTUDIANTE DE CR&Eacute;DITOS.</font>";
                            }

                    
                    $respuesta = new xajaxResponse();
                    //echo "div_nombreEstudiante".$posicion;//exit;
                    $respuesta->addAssign("div_nombreEstudiante".$posicion,"innerHTML",$mi_cuadro);
                    return $respuesta;
                }
            }

        function carreras()
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
                $accesoOracle=$conexion->estableceConexion(75,$configuracion);

                if (is_resource($enlace))
                {
                    $busqueda="select distinct CRA_COD, CRA_NOMBRE ";
                    $busqueda.="from accra ";
                    $busqueda.="inner join actipcra on accra.cra_tip_cra= tra_cod ";
                    $busqueda.="inner join acpen on accra.cra_cod=acpen.pen_cra_cod ";
                    $busqueda.="where tra_nivel='PREGRADO' and pen_nro BETWEEN 200 and 300 ";
                    $busqueda.="ORDER BY 2";

                    $resultado=$funcion->ejecutarSQL($configuracion, $accesoOracle, $busqueda,"busqueda");

                    $i=0;
                    while(isset ($resultado[$i][0]))
                    {
                        $resultado_1[$i][0]=$resultado[$i][0];
                        $resultado_1[$i][1]=htmlentities($resultado[$i][1]);
                        $i++;
                    }
                    $mi_cuadro=$html->cuadro_lista($resultado_1,"carrera",$configuracion,0,1,TRUE,0,"carrera",250);
                    $respuesta = new xajaxResponse();
                    $respuesta->addAssign("div_carrera","innerHTML",$mi_cuadro);
                    return $respuesta;
                   
                }
            }

   

?>