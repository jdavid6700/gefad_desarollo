<?php
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
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/validar_fechas.class.php");



/*
 * Funcion que presenta la informacion del espacio que se ha ingresado en el campo de busqueda de eespacio
 * @param <int> $codEspacio
 * @param <int> $planEstudio
 * @param <int> $codProyecto
 */
function buscarEspacios($codEspacio, $planEstudio, $codProyecto)
{
  if(!isset($codEspacio)||is_null($codEspacio)||$codEspacio==''||!is_numeric($codEspacio))
  {
    if(!is_numeric($codEspacio))
    {
      echo "El código del espacio deber ser numérico";exit;
    }
    else
      {
        echo "Por favor ingrese el código del espacio.";exit;
      }
  }
  require_once("clase/config.class.php");
    $esta_configuracion=new config();
    $fechas=new validar_fechas();
    $configuracion=$esta_configuracion->variable();
    $funcion=new funcionGeneral();
    //Conectarse a la base de datos
    $acceso_db=new dbms($configuracion);
    $enlace=$acceso_db->conectar_db();
    $valor=$acceso_db->verificar_variables($valor);

    $html=new html();
    $conexion=new multiConexion();
    $accesoOracle=$conexion->estableceConexion(28,$configuracion);
    $accesoGestion=$conexion->estableceConexion(99,$configuracion);

    if (is_resource($enlace))
        {
            $cadena_sql=generarSQL("periodoActivo", '');
            $ano=$funcion->ejecutarSQL($configuracion, $accesoOracle, $cadena_sql, "busqueda");

            $infoEspacio=array(codEspacio=>$codEspacio,
                              planEstudio=>$planEstudio,
                              codProyecto=>$codProyecto,
                              ano=>$ano[0]['ANO'],
                              periodo=>$ano[0]['PERIODO'],
                              parametro=>'!=');
            $cadena_sql=generarSQL("buscarInfoEspacio", $infoEspacio);
            $resultado_infoEspacio=$funcion->ejecutarSQL($configuracion, $accesoOracle, $cadena_sql, "busqueda");
            if(is_array($resultado_infoEspacio))
                {
                  $noPlan=0;
                  foreach ($resultado_infoEspacio as $key => $datosEspacio)
                    {
                      if ($datosEspacio['PLAN']==$planEstudio && $datosEspacio['CARRERA']==$codProyecto)
                        {
                            $htmlInfo="<table class='contenidotabla centrar' border='1'>";
                            $htmlInfo.="<tr><td class='derecha' width='20%'>Nombre E.A:</td><td>".$datosEspacio['NOMBRE']."</td></tr>";
                            $htmlInfo.="<tr><td class='derecha' width='20%'>Cr&eacute;ditos:</td><td>".$datosEspacio['CREDITOS']."</td></tr>";
                            if(trim($datosEspacio[0]['CLASIFICACION'])=='S')$clasificacion='Electivo';
                            else $clasificacion='Obligatorio';
                            $htmlInfo.="<tr><td class='derecha' width='20%'>Clasificaci&oacute;n:</td><td>".$clasificacion."</td></tr>";
                            $htmlInfo.="<tr><td colspan='2' class='centrar'>  H.T.D: ".$datosEspacio['HTD']."  H.T.C: ".$datosEspacio['HTC']."  H.T.A: ".$datosEspacio['HTA']."</td></tr>";
                            unset ($noPlan);
                            break;
                        }
                        else
                        {
                          $noPlan=1;
                        }
                    }
                    if(isset ($noPlan))
                    {
                            $htmlInfo="<table class='contenidotabla centrar' border='1'>";
                            $htmlInfo.="<tr><td class='derecha' width='20%'>Nombre E.A:</td><td>".$datosEspacio['NOMBRE']."</td></tr>";
                            $htmlInfo.="<tr><td class='derecha' width='20%'>Cr&eacute;ditos:</td><td>".$datosEspacio['CREDITOS']."</td></tr>";
                            if(trim($datosEspacio[0]['CLASIFICACION'])=='S')$clasificacion='Electivo';
                            else $clasificacion='Obligatorio';
                            $htmlInfo.="<tr><td class='derecha' width='20%'>Clasificaci&oacute;n:</td><td>".$clasificacion."</td></tr>";
                            $htmlInfo.="<tr><td colspan='2' class='centrar'>  H.T.D: ".$datosEspacio['HTD']."  H.T.C: ".$datosEspacio['HTC']."  H.T.A: ".$datosEspacio['HTA']."</td></tr>";
                            $htmlInfo.="<tr><td colspan='3' class='centrar'><b><font color='red'><blink>EL ESPACIO ACAD&Eacute;MICO NO PERTENECE AL PLAN DE ESTUDIOS DEL ESTUDIANTE</blink></font></b></td></tr>";
                    }
                }else
                    {
                        $htmlInfo="<table class='contenidotabla centrar' border='1'>";
                        $htmlInfo.="<tr><td class='derecha' width='20%'>Nombre E.A:</td><td></td></tr>";
                        $htmlInfo.="<tr><td class='derecha' width='20%'>Cr&eacute;ditos:</td><td></td></tr>";
                        $htmlInfo.="<tr><td class='derecha' width='20%'>Clasificaci&oacute;n:</td><td></td></tr>";
                        $htmlInfo.="<tr><td colspan='2' class='centrar'>  H.T.D:   H.T.C:   H.T.A: </td></tr>";
                        $htmlInfo.="<tr><td colspan='3' class='centrar'><font color='red'><blink>EL ESPACIO ACAD&Eacute;MICO NO EXISTE</blink></td></tr>";
                    }
            $htmlHorario="<&minus;&minus; Seleccione el grupo para ver el horario";
            $respuesta = new xajaxResponse();
            $respuesta->addAssign("div_infoea","innerHTML",$htmlInfo);
            $respuesta->addAssign("div_horario","innerHTML",$htmlHorario);

            $cadena_sql=generarSQL("buscarGruposProyecto", $infoEspacio);
            $resultado_gruposOtrosEspacio=$funcion->ejecutarSQL($configuracion, $accesoOracle, $cadena_sql, "busqueda");
            $infoEspacio['parametro']='=';
            $cadena_sql=generarSQL("buscarGruposProyecto", $infoEspacio);
            $resultado_gruposEspacio=$funcion->ejecutarSQL($configuracion, $accesoOracle, $cadena_sql, "busqueda");

            if(is_array($resultado_gruposEspacio) || is_array($resultado_gruposOtrosEspacio) )
                {
                    $htmlGrupo="<table class='contenidotabla centrar'>";
                    $htmlGrupo.="<tr><td>";
                    $htmlGrupo.="<select class='sigma' id='gruposEspacio' name='gruposEspacio' style='width: 100%; cursor:pointer' size='7' onchange='javascript:buscarHorario(document.getElementById(\"gruposEspacio\").value,".$codEspacio.",".$codProyecto.")'>";

                    if(is_array($resultado_gruposEspacio))
                        {
                            $htmlGrupo.="<optgroup class='uno' label='Grupos de Mi Proyecto'></optgroup>";
                        }
                    $valorArreglo=0;
                    
                    for($i=0;$i<count($resultado_gruposEspacio);$i++)
                    {
                        if($resultado_gruposEspacio[$i]['GRUPO']==$resultado_gruposEspacio[$i+1]['GRUPO'])
                            {
                                $arregloGrupos[$valorArreglo]=$resultado_gruposEspacio[$i]['GRUPO']."|".$resultado_gruposEspacio[$i]['CUPO']."|".$resultado_gruposEspacio[$i]['INSCRITOS']."|".$resultado_gruposEspacio[$i]['DIA']."|".$resultado_gruposEspacio[$i]['HORA']."|".$resultado_gruposEspacio[$i]['SEDE']."|".$resultado_gruposEspacio[$i]['SALON']."|".$resultado_gruposEspacio[$i]['NOMBRE']."|".$resultado_gruposEspacio[$i]['CARRERA']."|";
                                $js.=$arregloGrupos[$valorArreglo];
                                $valorArreglo++;
                            }else
                                {
                                    $arregloGrupos[$valorArreglo]=$resultado_gruposEspacio[$i]['GRUPO']."|".$resultado_gruposEspacio[$i]['CUPO']."|".$resultado_gruposEspacio[$i]['INSCRITOS']."|".$resultado_gruposEspacio[$i]['DIA']."|".$resultado_gruposEspacio[$i]['HORA']."|".$resultado_gruposEspacio[$i]['SEDE']."|".$resultado_gruposEspacio[$i]['SALON']."|".$resultado_gruposEspacio[$i]['NOMBRE']."|".$resultado_gruposEspacio[$i]['CARRERA']."|";
                                    $js.=$arregloGrupos[$valorArreglo];
                                    $valorArreglo=0;
                                    $htmlGrupo.="<option class='uno' value='".$js."'>".$resultado_gruposEspacio[$i]['GRUPO']." (".$resultado_gruposEspacio[$i]['NOMBRE'].")</option>";
                                    unset($js);
                                }
                        
                    }
                   
                    if(is_array($resultado_gruposOtrosEspacio))
                        {
                            $htmlGrupo.="<optgroup class='uno' label='Grupos de otros Proyectos'></optgroup>";
                            unset($js);
                        }
                    $valorArreglo=0;
                    
                    for($j=0;$j<count($resultado_gruposOtrosEspacio);$j++)
                    {
                    $permiso=$fechas->validar_fechas_grupo_coordinador($configuracion, $resultado_gruposOtrosEspacio[$j]['CARRERA']);
                    if ($permiso=='adicion'){
                       if($resultado_gruposOtrosEspacio[$j]['GRUPO']==$resultado_gruposOtrosEspacio[$j+1]['GRUPO'])
                            {
                                $arregloGrupos[$valorArreglo]=$resultado_gruposOtrosEspacio[$j]['GRUPO']."|".$resultado_gruposOtrosEspacio[$j]['CUPO']."|".$resultado_gruposOtrosEspacio[$j]['INSCRITOS']."|".$resultado_gruposOtrosEspacio[$j]['DIA']."|".$resultado_gruposOtrosEspacio[$j]['HORA']."|".$resultado_gruposOtrosEspacio[$j]['SEDE']."|".$resultado_gruposOtrosEspacio[$j]['SALON']."|".$resultado_gruposOtrosEspacio[$j]['NOMBRE']."|".$resultado_gruposOtrosEspacio[$j]['CARRERA']."|";
                                $js.=$arregloGrupos[$valorArreglo];
                                $valorArreglo++;
                            }else
                                {
                                    $arregloGrupos[$valorArreglo]=$resultado_gruposOtrosEspacio[$j]['GRUPO']."|".$resultado_gruposOtrosEspacio[$j]['CUPO']."|".$resultado_gruposOtrosEspacio[$j]['INSCRITOS']."|".$resultado_gruposOtrosEspacio[$j]['DIA']."|".$resultado_gruposOtrosEspacio[$j]['HORA']."|".$resultado_gruposOtrosEspacio[$j]['SEDE']."|".$resultado_gruposOtrosEspacio[$j]['SALON']."|".$resultado_gruposOtrosEspacio[$j]['NOMBRE']."|".$resultado_gruposOtrosEspacio[$j]['CARRERA']."|";
                                    $js.=$arregloGrupos[$valorArreglo];
                                    $valorArreglo=0;
                                    $htmlGrupo.="<option class='uno' value='".$js."'>".$resultado_gruposOtrosEspacio[$j]['GRUPO']." (".$resultado_gruposOtrosEspacio[$j]['NOMBRE'].")</option>";
                                    unset($js);
                                }
                    }else
                    {}
                    }

                }else
                    {
                        $htmlGrupo="<table class='contenidotabla centrar'>";
                        $htmlGrupo.="<tr><td>";
                        $htmlGrupo.="<select id='gruposEspacio' name='gruposEspacio' style='width: 100%;' size='7' onchange='javascript:buscarHorario(document.getElementById(\"gruposEspacio\").value,".$codEspacio.",".$codProyecto.")'>";
                        $htmlGrupo.="<optgroup label='No existen registros de grupos'></optgroup>";

                        $htmlHorario="Seleccione el grupo para ver el horario";
                        $respuesta->addAssign("div_horario","innerHTML",$htmlHorario);
                        $respuesta->addAssign("div_InfoGrupo","innerHTML","");
                        $respuesta->addAssign("div_registrar","innerHTML","");
                    }
                    
                $respuesta->addAssign("div_grupos","innerHTML",$htmlGrupo);



            return $respuesta;
        }
        
}

function generarSQL($tipo, $variable="")
    {
        switch ($tipo)
        {
            case 'periodoActivo':

                $cadena_sql="SELECT ape_ano ANO,";
                $cadena_sql.=" ape_per PERIODO";
                $cadena_sql.=" FROM acasperi";
                $cadena_sql.=" WHERE";
                $cadena_sql.=" ape_estado LIKE '%A%'";
                break;

            case 'buscarInfoEspacio':
                $cadena_sql="SELECT asi_cod CODIGO,";
                $cadena_sql.=" asi_nombre NOMBRE,";
                $cadena_sql.=" pen_ind_ele CLASIFICACION,";
                $cadena_sql.=" pen_cre CREDITOS,";
                $cadena_sql.=" pen_nro_ht HTD,";
                $cadena_sql.=" pen_nro_hp HTC,";
                $cadena_sql.=" pen_nro_aut HTA,";
                $cadena_sql.=" pen_nro PLAN,";
                $cadena_sql.=" pen_cra_cod CARRERA";
                $cadena_sql.=" FROM acasi";
                $cadena_sql.=" INNER JOIN acpen ON pen_asi_cod= asi_cod";
                $cadena_sql.=" WHERE asi_cod=".$variable['codEspacio'];
                $cadena_sql.=" AND asi_ind_cred LIKE '%S%'";
                $cadena_sql.=" AND pen_estado LIKE '%A%'";
                break;
            
            case 'buscarGruposProyecto':
                $cadena_sql = "SELECT cur_cra_cod CARRERA,";
                $cadena_sql.=" cra_nombre NOMBRE,";
                $cadena_sql.=" cur_nro GRUPO,";
                $cadena_sql.=" cur_nro_cupo CUPO,";
                $cadena_sql.=" (SELECT COUNT (*)";
                $cadena_sql.=" FROM acins";
                $cadena_sql.=" WHERE ins_asi_cod = cur_asi_cod";
                $cadena_sql.=" AND ins_gr=cur_nro";
                $cadena_sql.=" AND ins_ano=cur_ape_ano";
                $cadena_sql.=" AND ins_per=cur_ape_per) INSCRITOS,";
                $cadena_sql.=" dia_nombre DIA,";
                $cadena_sql.=" hor_rango HORA,";
                $cadena_sql.=" sed_abrev SEDE,";
                $cadena_sql.=" hor_sal_cod SALON";
                $cadena_sql.=" FROM accurso";
                $cadena_sql.=" INNER JOIN accra";
                $cadena_sql.=" ON cra_cod=cur_cra_cod";
                $cadena_sql.=" INNER JOIN achorario";
                $cadena_sql.=" ON hor_asi_cod=cur_asi_cod";
                $cadena_sql.=" AND cur_nro=hor_nro AND cur_ape_ano=hor_ape_ano AND cur_ape_per=hor_ape_per";
                $cadena_sql.=" INNER JOIN gedia";
                $cadena_sql.=" ON dia_cod=hor_dia_nro";
                $cadena_sql.=" INNER JOIN gehora";
                $cadena_sql.=" ON hor_cod=hor_hora";
                $cadena_sql.=" INNER JOIN gesede";
                $cadena_sql.=" ON sed_cod=hor_sed_cod";
                $cadena_sql.=" WHERE cur_asi_cod=".$variable['codEspacio'];
                $cadena_sql.=" AND cur_ape_ano=".$variable['ano'];
                $cadena_sql.=" AND cur_ape_per=".$variable['periodo'];
                $cadena_sql.=" AND cur_cra_cod ".$variable['parametro'].$variable['codProyecto'];
                $cadena_sql.=" ORDER BY cur_nro,";
                $cadena_sql.=" cur_cra_cod,";
                $cadena_sql.=" dia_cod,";
                $cadena_sql.=" hor_cod" ;
                break;
              

        }
        return $cadena_sql;
    }

function php2js ($var) {

            if (is_array($var)) {
                $res = "[";
                $array = array();
                foreach ($var as $a_var) {
                    $array[] = php2js($a_var);
                }
                return "[" . join(",", $array) . "]";
            }
            elseif (is_bool($var)) {
                return $var ? "true" : "false";
            }
            elseif (is_int($var) || is_integer($var) || is_double($var) || is_float($var)) {
                return $var;
            }
            elseif (is_string($var)) {
                return "\"' . addslashes(stripslashes($var)) . '\"";
            }

            return FALSE;
        }
?>
