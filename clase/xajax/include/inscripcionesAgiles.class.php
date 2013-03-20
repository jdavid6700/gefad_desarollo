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


function buscarEspacios($codEspacio, $planEstudio, $codProyecto)
{
    require_once("clase/config.class.php");
    $esta_configuracion=new config();
    $fechas=new validar_fechas();
    $configuracion=$esta_configuracion->variable();
    $funcion=new funcionGeneral();
    //Conectarse a la base de datos
    $acceso_db=new dbms($configuracion);
    $enlace=$acceso_db->conectar_db();
    //$valor=$acceso_db->verificar_variables($valor);

    $html=new html();
    $conexion=new multiConexion();
    $accesoOracle=$conexion->estableceConexion(28,$configuracion);
    $accesoGestion=$conexion->estableceConexion(99,$configuracion);

    if (is_resource($enlace))
        {
            $cadena_sql=generarSQL($configuracion,"periodoActivo", '');
            $ano=$funcion->ejecutarSQL($configuracion, $accesoOracle, $cadena_sql, "busqueda");

            $infoEspacio=array('codEspacio'=>$codEspacio,
                              'planEstudio'=>$planEstudio,
                              'codProyecto'=>$codProyecto,
                              'ano'=>$ano[0]['ANO'],
                              'periodo'=>$ano[0]['PERIODO'],
                              'parametro'=>'!=');

            $variablesInfo=array($codEspacio,$planEstudio);
            $cadena_sql=generarSQL($configuracion, "buscarInfoEspacio", $variablesInfo);
            $resultado_infoEspacio=$funcion->ejecutarSQL($configuracion, $accesoGestion, $cadena_sql, "busqueda");

            if(is_array($resultado_infoEspacio))
                {
                    $htmlInfo="<table class='contenidotabla centrar' border='1'>";
                    $htmlInfo.="<tr><td class='derecha' width='20%'>Nombre E.A:</td><td>".$resultado_infoEspacio[0][0]."</td></tr>";
                    $htmlInfo.="<tr><td class='derecha' width='20%'>Cr&eacute;ditos:</td><td>".$resultado_infoEspacio[0][1]."</td></tr>";
                    $htmlInfo.="<tr><td class='derecha' width='20%'>Clasificaci&oacute;n:</td><td>".$resultado_infoEspacio[0][6]."</td></tr>";
                    $htmlInfo.="<tr><td colspan='2' class='centrar'>  H.T.D: ".$resultado_infoEspacio[0][3]."  H.T.C: ".$resultado_infoEspacio[0][4]."  H.T.A: ".$resultado_infoEspacio[0][5]."</td></tr>";
                }else
                    {
                        $cadena_sql=generarSQL($configuracion, "buscarInfoEspacioNoPlan", $variablesInfo);
                        $resultado_infoEspacioNoPlan=$funcion->ejecutarSQL($configuracion, $accesoGestion, $cadena_sql, "busqueda");

                        if(is_array($resultado_infoEspacioNoPlan))
                            {
                                $cadena_sql=generarSQL($configuracion, "buscarInfoEspacioOracle", $variablesInfo);
                                $resultado_infoEspacioNoPlanOracle=$funcion->ejecutarSQL($configuracion, $accesoOracle, $cadena_sql, "busqueda");

                                $htmlInfo="<table class='contenidotabla centrar' border='1'>";
                                $htmlInfo.="<tr><td class='derecha' width='20%'>Nombre E.A:</td><td>".$resultado_infoEspacioNoPlan[0][0]."</td></tr>";
                                $htmlInfo.="<tr><td class='derecha' width='20%'>Cr&eacute;ditos:</td><td>".$resultado_infoEspacioNoPlan[0][1]."</td></tr>";
                                $htmlInfo.="<tr><td class='derecha' width='20%'>Clasificaci&oacute;n:</td><td>".$resultado_infoEspacioNoPlan[0][6]."</td></tr>";
                                $htmlInfo.="<tr><td colspan='2' class='centrar'>  H.T.D: ".$resultado_infoEspacioNoPlan[0][3]."  H.T.C: ".$resultado_infoEspacioNoPlan[0][4]."  H.T.A: ".$resultado_infoEspacioNoPlan[0][5]."</td></tr>";
                                if($resultado_infoEspacioNoPlan[0][2]=='4' || $resultado_infoEspacioNoPlanOracle[0][3]=='4')
                                    {
                                        $htmlInfo.="<tr><td colspan='3' class='centrar'><b>ESPACIO ACAD&Eacute;MICO ELECTIVO EXTR&Iacute;NSECO</b></td></tr>";
                                    }else
                                        {
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
                    }
            $htmlHorario="Seleccione el grupo para ver el horario";
            $respuesta = new xajaxResponse();
            $respuesta->addAssign("div_infoea","innerHTML",$htmlInfo);
            $respuesta->addAssign("div_horario","innerHTML",$htmlHorario);


            $variablesOtrosGrupos=array($codEspacio,$planEstudio,$codProyecto);
            $cadena_sql=generarSQL($configuracion, "buscarGruposProyectosUniversidad", $infoEspacio);
            $resultado_gruposOtrosEspacio=$funcion->ejecutarSQL($configuracion, $accesoOracle, $cadena_sql, "busqueda");

            $cadena_sql=generarSQL($configuracion, "buscarGruposProyecto", $variablesOtrosGrupos);
            $resultado_gruposEspacio=$funcion->ejecutarSQL($configuracion, $accesoOracle, $cadena_sql, "busqueda");

            if(is_array($resultado_gruposEspacio) || is_array($resultado_gruposOtrosEspacio) )
                {
                    $htmlGrupo="<table class='contenidotabla centrar'>";
                    $htmlGrupo.="<tr><td>";
                    //$grupoSeleccionado="document.getElementById(gruposEspacio).value";
                    //$htmlGrupo.="<select id='gruposEspacio' name='gruposEspacio' style='width: 230px;' size='6' onchange='xajax_buscarHorario(document.getElementById(\"gruposEspacio\").value,".$codEspacio.",".$codProyecto.")'>";
                    $htmlGrupo.="<select class='sigma' id='gruposEspacio' name='gruposEspacio' style='width: 100%; cursor:pointer' size='7' onchange='javascript:buscarHorario(document.getElementById(\"gruposEspacio\").value,".$codEspacio.",".$codProyecto.")'>";

                    if(is_array($resultado_gruposEspacio))
                        {
                            $htmlGrupo.="<optgroup class='uno' label='Grupos de Mi Proyecto'></optgroup>";
                        }
                    $valorArreglo=0;
                    //(isset($js)?$js:0);
                    for($i=0;$i<count($resultado_gruposEspacio);$i++)
                    {
                        if($resultado_gruposEspacio[$i][2]==(isset($resultado_gruposEspacio[$i+1][2])?$resultado_gruposEspacio[$i+1][2]:''))
                            {
                                $arregloGrupos[$valorArreglo]=$resultado_gruposEspacio[$i][2]."|".$resultado_gruposEspacio[$i][3]."|".$resultado_gruposEspacio[$i][4]."|".$resultado_gruposEspacio[$i][5]."|".$resultado_gruposEspacio[$i][6]."|".$resultado_gruposEspacio[$i][7]."|".$resultado_gruposEspacio[$i][8]."|".$resultado_gruposEspacio[$i][1]."|";
                                $js.=$arregloGrupos[$valorArreglo];
                                $valorArreglo++;
                            }else
                                {
                                    $arregloGrupos[$valorArreglo]=$resultado_gruposEspacio[$i][2]."|".$resultado_gruposEspacio[$i][3]."|".$resultado_gruposEspacio[$i][4]."|".$resultado_gruposEspacio[$i][5]."|".$resultado_gruposEspacio[$i][6]."|".$resultado_gruposEspacio[$i][7]."|".$resultado_gruposEspacio[$i][8]."|".$resultado_gruposEspacio[$i][1]."|";
                                    $js.=$arregloGrupos[$valorArreglo];
                                    $valorArreglo=0;
                                    $htmlGrupo.="<option class='uno' value='".$js."'>".$resultado_gruposEspacio[$i][2]." (".$resultado_gruposEspacio[$i][1].")</option>";
                                    //$htmlGrupo.="<option value='".$js."' onmouseover='toolTip(\"".$resultado_gruposEspacio[$i][2]." (".$resultado_gruposEspacio[$i][1].")\",this)'>".$resultado_gruposEspacio[$i][2]." (".$resultado_gruposEspacio[$i][1].")</option>";
                                    //$htmlGrupo.="<div class='centrar'><span id='toolTipBox' width='300' ></span></div>";
                                    unset($js);
                                    //(isset($js)?$js:0);
                                }
                        
                    }
                   
            if(is_array($resultado_gruposOtrosEspacio))
            {
                $valorArreglo=0;
                $aviso=0;
                for($j=0;$j<count($resultado_gruposOtrosEspacio);$j++)
                {
                    if ((isset($resultado_gruposOtrosEspacio[$j-1]['CARRERA'])?$resultado_gruposOtrosEspacio[$j-1]['CARRERA']:'')!=$resultado_gruposOtrosEspacio[$j]['CARRERA'])
                    {
                        $permiso=$fechas->validar_fechas_coordinador_otros_grupos($configuracion, $resultado_gruposOtrosEspacio[$j]['CARRERA']);
                    }
                    if ($permiso=='adicion')
                    {
                        if ($aviso==0)
                        {
                            if(!isset ($htmlGrupo))
                            {
                                $htmlGrupo="<table class='contenidotabla centrar'>";
                                $htmlGrupo.="<tr><td>";
                                $htmlGrupo.="<select class='sigma' id='gruposEspacio' name='gruposEspacio' style='width: 100%; cursor:pointer' size='7' onchange='javascript:buscarHorario(document.getElementById(\"gruposEspacio\").value,".$codEspacio.",".$codProyecto.")'>";
                            }
                            $htmlGrupo.="<optgroup class='uno' label='Grupos de otros Proyectos'></optgroup>";
                        }
                        if($resultado_gruposOtrosEspacio[$j]['GRUPO']==$resultado_gruposOtrosEspacio[$j+1]['GRUPO'])
                        {
                            $datosInscritos=array_merge($infoEspacio, $resultado_gruposOtrosEspacio[$j]);
                            $resultado_gruposOtrosEspacio[$j]['INSCRITOS']=consultarInscritos($configuracion,$accesoOracle,$datosInscritos);
                            $arregloGrupos[$valorArreglo]=$resultado_gruposOtrosEspacio[$j]['GRUPO']."|".$resultado_gruposOtrosEspacio[$j]['CUPO']."|".$resultado_gruposOtrosEspacio[$j]['INSCRITOS']."|".$resultado_gruposOtrosEspacio[$j]['DIA']."|".$resultado_gruposOtrosEspacio[$j]['HORA']."|".$resultado_gruposOtrosEspacio[$j]['SEDE']."|".$resultado_gruposOtrosEspacio[$j]['SALON']."|".$resultado_gruposOtrosEspacio[$j]['NOMBRE']."|".$resultado_gruposOtrosEspacio[$j]['CARRERA']."|";
                            $js.=$arregloGrupos[$valorArreglo];
                            $aviso++;
                            $valorArreglo++;
                        }else
                            {
                                $datosInscritos=array_merge($infoEspacio, $resultado_gruposOtrosEspacio[$j]);
                                $resultado_gruposOtrosEspacio[$j]['INSCRITOS']=consultarInscritos($configuracion,$accesoOracle,$datosInscritos);
                                $arregloGrupos[$valorArreglo]=$resultado_gruposOtrosEspacio[$j]['GRUPO']."|".$resultado_gruposOtrosEspacio[$j]['CUPO']."|".$resultado_gruposOtrosEspacio[$j]['INSCRITOS']."|".$resultado_gruposOtrosEspacio[$j]['DIA']."|".$resultado_gruposOtrosEspacio[$j]['HORA']."|".$resultado_gruposOtrosEspacio[$j]['SEDE']."|".$resultado_gruposOtrosEspacio[$j]['SALON']."|".$resultado_gruposOtrosEspacio[$j]['NOMBRE']."|".$resultado_gruposOtrosEspacio[$j]['CARRERA']."|";
                                $js.=$arregloGrupos[$valorArreglo];
                                $valorArreglo=0;
                                $aviso++;
                                $htmlGrupo.="<option class='uno' value='".$js."'>".$resultado_gruposOtrosEspacio[$j]['GRUPO']." (".$resultado_gruposOtrosEspacio[$j]['NOMBRE'].")</option>";
                                unset($js);
                            }
                    }else
                        {}
                }
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
                    
                //$respuesta = new xajaxResponse();
                $respuesta->addAssign("div_grupos","innerHTML",$htmlGrupo);



            return $respuesta;
        }
        
}

function generarSQL($configuracion, $tipo, $variable="")
    {
        switch ($tipo)
        {
            case 'buscarInfoEspacio':

                $cadena_sql="SELECT EA.espacio_nombre, EA.espacio_nroCreditos, PEE.id_clasificacion, PEE.horasDirecto, PEE.horasCooperativo, EA.espacio_horasAutonomo, EC.clasificacion_nombre";
                $cadena_sql.=" FROM ".$configuracion['prefijo']."planEstudio_espacio PEE ";
                $cadena_sql.=" INNER JOIN ".$configuracion['prefijo']."espacio_academico EA ON EA.id_espacio = PEE.id_espacio ";
                $cadena_sql.=" INNER JOIN ".$configuracion['prefijo']."espacio_clasificacion EC ON EC.id_clasificacion = PEE.id_clasificacion ";
                $cadena_sql.=" WHERE PEE.id_espacio='".$variable[0]."'";
                $cadena_sql.=" AND PEE.id_planEstudio ='".$variable[1]."'";
                
                break;
            
            case 'buscarInfoEspacioNoPlan':

                $cadena_sql="SELECT EA.espacio_nombre, EA.espacio_nroCreditos, PEE.id_clasificacion, PEE.horasDirecto, PEE.horasCooperativo, EA.espacio_horasAutonomo, EC.clasificacion_nombre";
                $cadena_sql.=" FROM ".$configuracion['prefijo']."planEstudio_espacio PEE ";
                $cadena_sql.=" INNER JOIN ".$configuracion['prefijo']."espacio_academico EA ON EA.id_espacio = PEE.id_espacio ";
                $cadena_sql.=" INNER JOIN ".$configuracion['prefijo']."espacio_clasificacion EC ON EC.id_clasificacion = PEE.id_clasificacion ";
                $cadena_sql.=" WHERE PEE.id_espacio='".$variable[0]."'";
                //$cadena_sql.=" AND PEE.id_planEstudio ='".$variable[1]."'";

                break;

            case 'buscarInfoEspacioOracle':

                $cadena_sql="SELECT CLP_CRA_COD, CLP_ASI_COD, CLP_PEN_NRO, CLP_CEA_COD, CLP_ESTADO FROM ACCLASIFICACPEN";
                $cadena_sql.=" WHERE CLP_ASI_COD='".$variable[0]."'";
                $cadena_sql.=" AND CLP_CEA_COD=4";
                $cadena_sql.=" AND CLP_ESTADO LIKE '%A%'";
                //$cadena_sql.=" AND PEE.id_planEstudio ='".$variable[1]."'";

                break;

            case 'buscarGruposOtrosProyectos':

                $cadena_sql = "SELECT cur_cra_cod, ";
                $cadena_sql .= "       cra_nombre, ";
                $cadena_sql .= "       cur_nro, ";
                $cadena_sql .= "       cur_nro_cupo, ";
                $cadena_sql .= "       (SELECT COUNT (*) ";
                $cadena_sql .= "        FROM   acins ";
                $cadena_sql .= "        WHERE  ins_asi_cod = cur_asi_cod ";
                $cadena_sql .= "               AND ins_gr = cur_nro ";
                $cadena_sql .= "               AND ins_ano = cur_ape_ano ";
                $cadena_sql .= "               AND ins_per = cur_ape_per) AS inscritos, ";
                $cadena_sql .= "       dia_nombre, ";
                $cadena_sql .= "       hor_rango, ";
                $cadena_sql .= "       sed_abrev, ";
                $cadena_sql .= "       hor_sal_id_espacio ";
                $cadena_sql .= "FROM   accurso ";
                $cadena_sql .= "       inner join accra ";
                $cadena_sql .= "         ON cra_cod = cur_cra_cod ";
                $cadena_sql .= "       inner join achorario_2012 ";
                $cadena_sql .= "         ON hor_asi_cod = cur_asi_cod ";
                $cadena_sql .= "            AND cur_nro = hor_nro AND cur_ape_ano= hor_ape_ano AND cur_ape_per= hor_ape_per";
                $cadena_sql .= "       inner join gedia ";
                $cadena_sql .= "         ON dia_cod = hor_dia_nro ";
                $cadena_sql .= "       inner join gehora ";
                $cadena_sql .= "         ON hor_cod = hor_hora ";
                $cadena_sql .= "       inner join gesede ";
                $cadena_sql .= "         ON sed_cod = hor_sed_cod ";
                $cadena_sql .= "WHERE  cur_asi_cod = '".$variable[0]."' ";
                $cadena_sql .= "       AND cur_ape_ano = (SELECT ape_ano ";
                $cadena_sql .= "                          FROM   acasperi ";
                $cadena_sql .= "                          WHERE  ape_estado LIKE '%A%') ";
                $cadena_sql .= "       AND cur_ape_per = (SELECT ape_per ";
                $cadena_sql .= "                          FROM   acasperi ";
                $cadena_sql .= "                          WHERE  ape_estado LIKE '%A%') ";
                $cadena_sql .= "       AND cur_cra_cod != '".$variable[2]."' ";
                $cadena_sql .= "ORDER  BY cur_nro,cur_cra_cod, ";
                $cadena_sql .= "          dia_cod, ";
                $cadena_sql .= "          hor_cod " ;

                break;
            case 'buscarGruposProyectosUniversidad':
                $cadena_sql = "SELECT cur_cra_cod CARRERA,";
                $cadena_sql.=" cra_nombre NOMBRE,";
                $cadena_sql.=" cur_nro GRUPO,";
                $cadena_sql.=" cur_nro_cupo CUPO,";
                $cadena_sql.=" dia_nombre DIA,";
                $cadena_sql.=" hor_rango HORA,";
                $cadena_sql.=" sed_abrev SEDE,";
                $cadena_sql.=" hor_sal_id_espacio SALON";
                $cadena_sql.=" FROM accurso";
                $cadena_sql.=" INNER JOIN accra ON cra_cod=cur_cra_cod";
                $cadena_sql.=" INNER JOIN achorario_2012 ON hor_asi_cod=cur_asi_cod";
                $cadena_sql.=" AND cur_nro=hor_nro AND cur_ape_ano=hor_ape_ano AND cur_ape_per=hor_ape_per";
                $cadena_sql.=" INNER JOIN gedia ON dia_cod=hor_dia_nro";
                $cadena_sql.=" INNER JOIN gehora ON hor_cod=hor_hora";
                $cadena_sql.=" INNER JOIN gesede ON sed_cod=hor_sed_cod";
                $cadena_sql.=" WHERE cur_asi_cod=".$variable['codEspacio'];
                $cadena_sql.=" AND cur_ape_ano=".$variable['ano'];
                $cadena_sql.=" AND cur_ape_per=".$variable['periodo'];
                $cadena_sql.=" AND cur_cra_cod ".$variable['parametro'].$variable['codProyecto'];
                $cadena_sql.=" ORDER BY cur_nro,";
                $cadena_sql.=" cur_cra_cod,";
                $cadena_sql.=" dia_cod,";
                $cadena_sql.=" hor_cod" ;
                break;


            case 'buscarGruposProyecto':
                $cadena_sql = "SELECT cur_cra_cod, ";
                $cadena_sql .= "       cra_nombre, ";
                $cadena_sql .= "       cur_nro, ";
                $cadena_sql .= "       cur_nro_cupo, ";
                $cadena_sql .= "       (SELECT COUNT (*) ";
                $cadena_sql .= "        FROM   acins ";
                $cadena_sql .= "        WHERE  ins_asi_cod = cur_asi_cod ";
                $cadena_sql .= "               AND ins_gr = cur_nro ";
                $cadena_sql .= "               AND ins_ano = cur_ape_ano ";
                $cadena_sql .= "               AND ins_per = cur_ape_per) AS inscritos, ";
                $cadena_sql .= "       dia_nombre, ";
                $cadena_sql .= "       hor_rango, ";
                $cadena_sql .= "       sed_abrev, ";
                $cadena_sql .= "       hor_sal_id_espacio ";
                $cadena_sql .= "FROM   accurso ";
                $cadena_sql .= "       inner join accra ";
                $cadena_sql .= "         ON cra_cod = cur_cra_cod ";
                $cadena_sql .= "       inner join achorario_2012 ";
                $cadena_sql .= "         ON hor_asi_cod = cur_asi_cod ";
                $cadena_sql .= "            AND cur_nro = hor_nro AND cur_ape_ano= hor_ape_ano AND cur_ape_per= hor_ape_per";
                $cadena_sql .= "       inner join gedia ";
                $cadena_sql .= "         ON dia_cod = hor_dia_nro ";
                $cadena_sql .= "       inner join gehora ";
                $cadena_sql .= "         ON hor_cod = hor_hora ";
                $cadena_sql .= "       inner join gesede ";
                $cadena_sql .= "         ON sed_cod = hor_sed_cod ";
                $cadena_sql .= "WHERE  cur_asi_cod = '".$variable[0]."' ";
                $cadena_sql .= "       AND cur_ape_ano = (SELECT ape_ano ";
                $cadena_sql .= "                          FROM   acasperi ";
                $cadena_sql .= "                          WHERE  ape_estado LIKE '%A%') ";
                $cadena_sql .= "       AND cur_ape_per = (SELECT ape_per ";
                $cadena_sql .= "                          FROM   acasperi ";
                $cadena_sql .= "                          WHERE  ape_estado LIKE '%A%') ";
                $cadena_sql .= "       AND cur_cra_cod = '".$variable[2]."' ";
                $cadena_sql .= "ORDER  BY cur_nro,cur_cra_cod, ";
                $cadena_sql .= "          dia_cod, ";
                $cadena_sql .= "          hor_cod " ;
                break;
            
            case 'periodoActivo':

                $cadena_sql="SELECT ape_ano ANO,";
                $cadena_sql.=" ape_per PERIODO";
                $cadena_sql.=" FROM acasperi";
                $cadena_sql.=" WHERE";
                $cadena_sql.=" ape_estado LIKE '%A%'";
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
