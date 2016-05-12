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

    function validar($valor1,$valor2,$valor3,$valor4)
    {
        require_once("clase/config.class.php");
    $esta_configuracion=new config();
    $configuracion=$esta_configuracion->variable();
    $funcion=new funcionGeneral();
    //Conectarse a la base de datos
    $acceso_db=new dbms($configuracion);
    $enlace=$acceso_db->conectar_db();
    //$valor=$acceso_db->verificar_variables($valor);
    $año=substr($valor2,-6,4);
    $periodo=substr($valor2,-1);
    $espacio=$valor1;
    $grupo=$valor3;
    $capacidad=$valor4;


    $html=new html();
    $conexion=new multiConexion();
    $accesoOracle=$conexion->estableceConexion(4,$configuracion);

    if (is_resource($enlace))
        {
            $busqueda="
            SELECT * FROM ACCURSO
            WHERE cur_ape_ano =". $año."
            AND cur_ape_per = ".$periodo."
            AND cur_asi_cod = ".$espacio."
            AND cur_nro =". $grupo."

            ";
            
            $resultado=$funcion->ejecutarSQL($configuracion, $accesoOracle, $busqueda,"busqueda");
            
            $respuesta = new xajaxResponse();

            if(isset ($resultado[0][0]))
            {
                $respuesta->addAlert("EL GRUPO ".$grupo." YA ESTA ASIGNADO PARA ESTE ESPACIO ACADEMICO\nPOR FAVOR DIGITE OTRO NÚMERO DE GRUPO");
            }else{
                $respuesta->addAlert("EL GRUPO ".$grupo." SE ENCUENTRA DISPONIBLE");
                
            }

            return $respuesta;

        }
    }

    function horario($espacio,$periodo, $grupo, $capacidad,$hora,$dia)
    {
        require_once("clase/config.class.php");
        $esta_configuracion=new config();
        $configuracion=$esta_configuracion->variable();
        $año=substr($periodo,-6,4);
        $periodoacad=substr($periodo,-1);

 //       echo "Espacio: ".$espacio."Periodo".$periodo."Grupo:". $grupo. "Capacidad: ".$capacidad."Hora: ".$hora."Dia: ".$dia;

                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                $variable="pagina=asignarSalon";
				$variable.="&opcion=asignar";
                $variable.="&hora=".$hora;
                $variable.="&dia=".$dia;
                $variable.="&capacidad=".$capacidad;
                $variable.="&grupo=".$grupo;
                $variable.="&periodo=".$periodoacad;
                $variable.="&anio=".$año;
                $variable.="&espacio=".$espacio;
                
                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$cripto=new encriptar();
				$variable=$cripto->codificar_url($variable,$configuracion);
				//$abrir= "<script languaje=javascript>window.open(".$pagina.$variable.",'Sede','width=120,height=300,scrollbars=NO')</script>";
                $abrir = $pagina.$variable;
                $respuesta = new xajaxResponse();
                $respuesta->addScript("window.open('".$abrir."','Horario Academico','width=500,height=250,scrollbars=NO');");
                //$respuesta->addRedirect($abrir);
                //$respuesta->redirect($abrir);
                return $respuesta;
         
}


        function validarCapacidad($capacidad)
{
    require_once("clase/config.class.php");
    $esta_configuracion=new config();
    $configuracion=$esta_configuracion->variable();
    $funcion=new funcionGeneral();
    //Conectarse a la base de datos
    $acceso_db=new dbms($configuracion);
    $enlace=$acceso_db->conectar_db();

    $html=new html();
    $conexion=new multiConexion();
    $accesoOracle=$conexion->estableceConexion(4,$configuracion);

    if (is_resource($enlace))
        {
            $busqueda="select * from gesalon where sal_capacidad>=".$capacidad;

            $resultado=$funcion->ejecutarSQL($configuracion, $accesoOracle, $busqueda,"busqueda");
            $respuesta = new xajaxResponse();

            if(!isset ($resultado[0][0]))
            {
                $respuesta->addAlert("NO SE ENCUENTRAN SALONES CON UNA CAPACIDAD DE ".$capacidad."\nPOR FAVOR CAMBIE LA CAPACIDAD");
            }

            return $respuesta;

        }
}
function verhorario($espacio,$periodo, $grupo, $capacidad,$hora,$dia)
{
    require_once("clase/config.class.php");
    $esta_configuracion=new config();
    $configuracion=$esta_configuracion->variable();
    $funcion=new funcionGeneral();
    //Conectarse a la base de datos
    $acceso_db=new dbms($configuracion);
    $enlace=$acceso_db->conectar_db();

    $html=new html();
    $conexion=new multiConexion();
    $accesoOracle=$conexion->estableceConexion(4,$configuracion);

    if (is_resource($enlace))
        {
            $busqueda="select HOR_SED_COD, HOR_SAL_COD, SED_ABREV from achorario
                        INNER JOIN GESEDE ON ACHORARIO.HOR_SED_COD=GESEDE.SED_COD
                        where hor_asi_cod=".$espacio." and hor_nro=".$grupo." and hor_dia_nro=".$dia." and hor_hora=".$hora;
            
            $resultado=$funcion->ejecutarSQL($configuracion, $accesoOracle, $busqueda,"busqueda");

            $respuesta = new xajaxResponse();

            if ($resultado==false)
            {
                $resultado_1=" - ";
                $respuesta->addAssign($dia."_".$hora,"innerHTML",$resultado_1);
            }else{

                    $resultado_1="Sede: ".$resultado[0][2]." <br>Salon: ".$resultado[0][1];
                    $respuesta->addAssign($dia."_".$hora,"innerHTML",$resultado_1);
                }
         }
        return $respuesta;
}

function plan($proyecto)
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
    $accesoOracle=$conexion->estableceConexion(4,$configuracion);

    if (is_resource($enlace))
        {
          $busqueda="
                SELECT DISTINCT pen_nro
                        FROM acpen
                        WHERE pen_cra_cod=".$proyecto." and pen_nro>200";


            $resultado=$funcion->ejecutarSQL($configuracion, $accesoOracle, $busqueda,"busqueda");

            $i=0;
            while(isset ($resultado[$i][0]))
            {
                $resultado_1[$i][0]=$resultado[$i][0];
                $resultado_1[$i][1]="Plan de Estudio: ".$resultado[$i][0];
                $i++;
            }
            $mi_cuadro=$html->cuadro_lista($resultado_1,"plan",$configuracion,0,0,FALSE,0,"plan");
            $respuesta = new xajaxResponse();
            $respuesta->addAssign("plan","innerHTML",$mi_cuadro);
        }
        return $respuesta;

        }

    function grupo($espacio,$periodo)
    {

        $año=substr($periodo,-6,4);
        $periodoacad=substr($periodo,-1);
        
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
            $accesoOracle=$conexion->estableceConexion(4,$configuracion);

            if (is_resource($enlace))
                {
                  $busqueda="
                                SELECT DISTINCT HOR_NRO
                                FROM ACHORARIO
                                WHERE HOR_ASI_COD=".$espacio." and HOR_APE_ANO=".$año." and HOR_APE_PER=".$periodoacad;


                    $resultado=$funcion->ejecutarSQL($configuracion, $accesoOracle, $busqueda,"busqueda");

                    $i=0;
                    while(isset ($resultado[$i][0]))
                    {
                        $resultado_1[$i][0]=$resultado[$i][0];
                        $resultado_1[$i][1]="Grupo: ".$resultado[$i][0];
                        $i++;
                    }
                    $mi_cuadro=$html->cuadro_lista($resultado_1,"grupo",$configuracion,0,0,FALSE,0,"grupo");
                    $respuesta = new xajaxResponse();
                    $respuesta->addAssign("grupo","innerHTML",$mi_cuadro);
                }
                return $respuesta;

        }

?>
