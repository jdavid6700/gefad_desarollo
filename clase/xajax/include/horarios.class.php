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

function plan($carrera) {
    
    require_once("clase/config.class.php");
    setlocale(LC_MONETARY, 'en_US');
    $esta_configuracion = new config();
    $configuracion = $esta_configuracion->variable();
    //$funcion = new funcionGeneral();
    //Conectarse a la base de datos
    $conexion=new funcionGeneral();
    $conexionOracle=$conexion->conectarDB($configuracion,"coordinador");
    //$valor=$acceso_db->verificar_variables($valor);

    $html = new html();
    
    
        $busqueda = "SELECT  distinct(pen_nro) , pen_cra_cod
                FROM  acpen
                WHERE  pen_cra_cod =" . $carrera . "
                AND  pen_estado LIKE  '%A%'
                ORDER BY pen_nro";

        $resultado = $conexion->ejecutarSQL($configuracion, $conexionOracle, $busqueda, "busqueda");

        $i = 0;
        $html = "<select name='plan' id='plan'>";
        while (isset($resultado[$i][0])) {
            $html.= "<option value='".$resultado[$i][0]."'>Plan de Estudio: " . $resultado[$i][0] . " (" . $resultado[$i][1] . " - " . $resultado[$i][2] . ")</option>";
            $i++;
        }
              
        $html.= "</select>";
        //$mi_cuadro = $html->cuadro_lista($resultado_1, "plan", $configuracion, 0, 0, FALSE, 0, "plan");
        $respuesta = new xajaxResponse();
        $respuesta->addAssign("div_plan", "innerHTML", $html);
    
    return $respuesta;
}

function validar($valor1,$valor2,$valor3,$valor4,$proyecto)
    {//echo "valida";
    require_once("clase/config.class.php");
    setlocale(LC_MONETARY, 'en_US');
    $esta_configuracion = new config();
    $configuracion = $esta_configuracion->variable();
    //$funcion = new funcionGeneral();
    //Conectarse a la base de datos
    $conexion=new funcionGeneral();
    $conexionOracle=$conexion->conectarDB($configuracion,"coordinador");
    //$valor=$acceso_db->verificar_variables($valor);

    $html = new html();
  
    //$valor=$acceso_db->verificar_variables($valor);
    $año=substr($valor2,-6,4);
    $periodo=substr($valor2,-1);
    $espacio=$valor1;
    $grupo=$valor3;
    $capacidad=$valor4;

	    if($grupo=="" || !is_numeric($grupo))
	    {
		
	    }
	    else
	    {
		  $busqueda="
		SELECT * FROM ACCURSO
		WHERE cur_ape_ano =". $año."
		AND cur_ape_per = ".$periodo."
		AND cur_asi_cod = ".$espacio."
		AND cur_nro =". $grupo."
		";//echo $busqueda;
		$resultado=$conexion->ejecutarSQL($configuracion, $conexionOracle, $busqueda,"busqueda");
	    }

            $respuesta = new xajaxResponse();
	    if((!is_numeric($grupo)) || $grupo=="")
	    {
		  $respuesta->addAlert("EL VALOR DIGITADO ".$grupo." NO ES NUMERICO");
		   
	    } 
            if(isset ($resultado[0][0]))
            {
                $respuesta->addAlert("EL GRUPO ".$grupo." YA ESTA ASIGNADO PARA ESTA ASIGNATURA\nPOR FAVOR DIGITE OTRO NÚMERO DE GRUPO");
//                $respuesta->addAssign("div_docEncargado","innerHTML",$htmlDoc);
               // $respuesta->addAssign("div_jornada","innerHTML",$htmlJor);
                $respuesta->addScript("document.getElementById('max_capacidad').value = ".$resultado[0][5]."");
                $respuesta->addScript("document.getElementById('cupos').value = ".$resultado[0][5]."");
                //$respuesta->addScript("document.getElementById('grupo').readOnly  = 'true'");
              //  $respuesta->addScript("document.getElementById('jornada').readOnly = true");
                $respuesta->addScript("document.getElementById('periodo').readOnly = true");
                $respuesta->addScript("document.getElementById('espacio').readOnly = true");
                $respuesta->addScript("document.getElementById('btnGrabar').disabled = true");
               // $respuesta->addScript("document.getElementById('btnGrabar').value = 'Actualizar Curso'");
                //$respuesta->addScript("document.getElementById('hidHorario').value = '1'");
                //$respuesta->addScript("document.getElementById('div_btnHorario').style.display = 'block'");
                //$respuesta->addScript("document.getElementById('div_btnNuevaBusqueda').style.display = 'block'");
                //$respuesta->addAssign("div_btnHorario","innerHTML",$htmlBoton);
                
            }else{
//                $respuesta->addAssign("div_docEncargado","innerHTML",$htmlDoc);
                //$respuesta->addScript("document.getElementById('jornada').readOnly  = false");
                //$respuesta->addScript("document.getElementById('periodo').readOnly = false");
                //$respuesta->addScript("document.getElementById('espacio').readOnly = false");
                $respuesta->addScript("document.getElementById('max_capacidad').value = 30");
                $respuesta->addScript("document.getElementById('cupos').value = 30");
                $respuesta->addScript("document.getElementById('btnGrabar').disabled = false");
                //$respuesta->addScript("document.getElementById('grupo').readOnly  = false");
                //$respuesta->addScript("document.getElementById('btnGrabar').value = 'Guardar Curso'");
                //$respuesta->addScript("document.getElementById('hidHorario').value = '0'");
                //$respuesta->addScript("document.getElementById('div_mostrarHorario').style.display = 'none'");
                //$respuesta->addAssign("div_btnHorario","innerHTML","");
                

            }

            return $respuesta;

        
    }

    function nuevaBusqueda($valor1,$valor2,$valor3,$valor4,$proyecto)
    {
    setlocale(LC_MONETARY, 'en_US');
    require_once("clase/config.class.php");
    $esta_configuracion = new config();
    $configuracion = $esta_configuracion->variable();
  
    //Conectarse a la base de datos
    $conexion=new funcionGeneral();
    $conexionOracle=$conexion->conectarDB($configuracion,"coordinador");
  
    $año=substr($valor2,-6,4);
    $periodo=substr($valor2,-1);
    $espacio=$valor1;
    $grupo=$valor3;
    $capacidad=$valor4;

            /*$busqueda="
            SELECT * FROM ACCURSO
            WHERE cur_ape_ano =". $año."
            AND cur_ape_per = ".$periodo."
            AND cur_asi_cod = ".$espacio."
            AND cur_nro =". $grupo."

            ";*/
            $busqueda="
            SELECT * FROM ACCURSO
            WHERE cur_ape_ano =". $año."
            AND cur_ape_per = ".$periodo."
            AND cur_asi_cod = ".$espacio."
            ";
            //echo $busqueda;
            $resultado=$conexion->ejecutarSQL($configuracion, $conexionOracle, $busqueda,"busqueda");

            $respuesta = new xajaxResponse();

//                $respuesta->addAssign("div_docEncargado","innerHTML",$htmlDoc);
                $respuesta->addScript("document.getElementById('periodo').disabled = false");
                $respuesta->addScript("document.getElementById('espacio').disabled = false");
                $respuesta->addScript("document.getElementById('grupo').readOnly  = false");
                $respuesta->addScript("document.getElementById('capacidad').value = 30");
                $respuesta->addScript("document.getElementById('grupo').value = 0");                
                $respuesta->addScript("document.getElementById('btnGrabar').value = 'Guardar Curso'");
                $respuesta->addScript("document.getElementById('hidHorario').value = '0'");
                $respuesta->addScript("document.getElementById('div_mostrarHorario').style.display = 'none'");
                $respuesta->addScript("document.getElementById('div_btnHorario').style.display = 'none'");
                $respuesta->addScript("document.getElementById('div_btnNuevaBusqueda').style.display = 'none'");
                //$respuesta->addAssign("div_btnHorario","innerHTML","");
     
            return $respuesta;

        
    }


function verhorario($espacio,$periodo, $grupo, $capacidad,$hora,$dia)
        {
            require_once("clase/config.class.php");
            setlocale(LC_MONETARY, 'en_US');
            $esta_configuracion = new config();
            $configuracion = $esta_configuracion->variable();
            //$funcion = new funcionGeneral();
            //Conectarse a la base de datos
            $conexion=new funcionGeneral();
            $conexionOracle=$conexion->conectarDB($configuracion,"coordinador");

            //$valor=$acceso_db->verificar_variables($valor);
            $periodoB=explode('-',$periodo);
            $annio=$periodoB[0];
            $per=$periodoB[1];
            $html = new html();

            $cadena_sql="SELECT HOR_G.HOR_SED_COD COD_SEDE, ";
            $cadena_sql.="SEDE.SED_ID NOM_SEDE, ";
            $cadena_sql.="SALON.SAL_COD SALON_OLD,";
            $cadena_sql.="SALON.SAL_ID_ESPACIO SALON_NVO, ";
            $cadena_sql.="SALON.SAL_NOMBRE NOM_SALON, ";
            $cadena_sql.="SALON.SAL_EDIFICIO ID_EDIFICIO, ";
            $cadena_sql.="EDIF.EDI_NOMBRE NOM_EDIFICIO ";
            $cadena_sql.="FROM achorario_2012 HOR_G ";
            $cadena_sql.=" INNER JOIN gesede SEDE ON HOR_G.HOR_SED_COD=SEDE.SED_COD";
            $cadena_sql.=" INNER JOIN gesalon_2012 SALON ON HOR_G.HOR_SAL_ID_ESPACIO=SALON.SAL_ID_ESPACIO";
            $cadena_sql.=" INNER JOIN geedificio EDIF ON SALON.SAL_EDIFICIO=EDIF.EDI_COD";
            $cadena_sql.=" WHERE hor_asi_cod=".$espacio." AND hor_nro=".$grupo." AND hor_dia_nro=".$dia." AND hor_hora=".$hora;
            $cadena_sql.=" AND hor_ape_ano=".$annio." AND hor_ape_per=".$per;
            
            
            //echo $busqueda;
            $resultado=$conexion->ejecutarSQL($configuracion, $conexionOracle, $cadena_sql,"busqueda");
            $respuesta = new xajaxResponse();
            if ($resultado==false)
                {   $resultado_1=" - ";
                    $respuesta->addAssign($dia."_".$hora,"innerHTML",$resultado_1);
                }
            else{   $resultado_1="Sede: ".$resultado[0]['NOM_SEDE']."<br>Edificio: ".$resultado[0]['NOM_EDIFICIO']."<br>Salon: ".$resultado[0]['SALON_NVO']."<BR> ".$resultado[0]['NOM_SALON']."";
                    $respuesta->addAssign($dia."_".$hora,"innerHTML",$resultado_1);
                }
            return $respuesta;
        }

function horario($espacio,$periodo, $grupo, $capacidad,$hora,$dia)
    {
        require_once("clase/config.class.php");
        $esta_configuracion=new config();
        $configuracion=$esta_configuracion->variable();
        $año=substr($periodo,-6,4);
        $periodoacad=substr($periodo,-1);

 //       echo "Espacio: ".$espacio."Periodo".$periodo."Grupo:". $grupo. "Capacidad: ".$capacidad."Hora: ".$hora."Dia: ".$dia;

                $indice=$configuracion["host"].$configuracion["site"]."/index.php?";
                $ruta="pagina=adminasignarSalon";
                $ruta.="&opcion=asignar";
                $ruta.="&hora=".$hora;
                $ruta.="&dia=".$dia;
                $ruta.="&capacidad=".$capacidad;
                $ruta.="&grupo=".$grupo;
                $ruta.="&periodo=".$periodoacad;
                $ruta.="&anio=".$año;
                $ruta.="&espacio=".$espacio;

                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                $cripto=new encriptar();
                $ruta=$cripto->codificar_url($ruta,$configuracion);
                //$abrir= "<script languaje=javascript>window.open(".$pagina.$variable.",'Sede','width=120,height=300,scrollbars=NO')</script>";
                $abrir = $indice.$ruta;
                $respuesta = new xajaxResponse();
                $respuesta->addScript("window.open('".$abrir."','Horario Academico','width=650,height=200,left=250,top=250,scrollbars=no,menubars=no,statusbar=NO,status=NO,resizable=NO,location=NO');");
                //$respuesta->addRedirect($abrir);
                //$respuesta->redirect($abrir);
                return $respuesta;

}

function salones($valor, $hora, $dia, $capacidad, $periodo, $año)
    {
    require_once("clase/config.class.php");
    setlocale(LC_MONETARY, 'en_US');
    $esta_configuracion = new config();
    $configuracion = $esta_configuracion->variable();
    //$funcion = new funcionGeneral();
    //Conectarse a la base de datos
    $conexion=new funcionGeneral();
    $conexionOracle=$conexion->conectarDB($configuracion,"coordinador");

    //$valor=$acceso_db->verificar_variables($valor);

    $html = new html();
   
    
    /* CONSULTA CON RESTRICCION
         $busqueda=" select 
                sal_id_espacio COD_SALON_NVO,
                sal_nombre NOM_SALON,
                sal_cod COD_SALON_OLD,
                sal_ocupantes CUPOS,
                sal_tipo TIPO_SALON,
                sal_edificio ID_EDIFICIO,
                edi_nombre NOM_EDIFICIO
                from mntge.gesalon_2012 , geedificio
                where SAL_ESTADO='A' AND sal_edificio=edi_cod
                    AND sal_sed_cod=".$valor." 
                    AND sal_ocupantes >=".$capacidad." AND sal_ocupantes <".($capacidad*1.5)." AND sal_id_espacio not in
                     (SELECT hor_sal_id_espacio
                         FROM mntac.achorario_2012
                             WHERE
                             hor_sed_cod=".$valor." AND
                             hor_dia_nro=".$dia." AND
                             hor_hora=".$hora." AND
                             hor_ape_ano=".$año." AND
                             hor_ape_per=".$periodo." )
                ORDER BY sal_cod"; //echo $busqueda;exit;
     */
    
                $busqueda=" select 
                            sal_id_espacio COD_SALON_NVO,
                            sal_nombre NOM_SALON,
                            sal_cod COD_SALON_OLD,
                            sal_ocupantes CUPOS,
                            sal_tipo TIPO_SALON,
                            sal_edificio ID_EDIFICIO,
                            edi_nombre NOM_EDIFICIO
                            from mntge.gesalon_2012 , geedificio
                            where SAL_ESTADO='A' AND sal_edificio=edi_cod
                                AND sal_sed_id='".$valor."'";
          
      if($valor!='PAS')
            {   //salones diferentes a PAS (Por ASignar)
                //filtro para salones con capacidad > 0, excepto el sin asignar  
                    /*restriccion para busqueda salon segun capacidad
                    $busqueda.=" AND sal_ocupantes >=".$capacidad." AND sal_ocupantes <".($capacidad*1.5);
                    */
                
                    $busqueda.=" AND sal_ocupantes>1  ";
                    $busqueda.=" AND sal_id_espacio not in
                                 (SELECT hor_sal_id_espacio
                                     FROM mntac.achorario_2012,gesede
                                         WHERE
                                         hor_sed_cod=sed_cod AND
                                         sed_id='".$valor."' AND
                                         hor_dia_nro=".$dia." AND
                                         hor_hora=".$hora." AND
                                         hor_ape_ano=".$año." AND
                                         hor_ape_per=".$periodo." )
                            ORDER BY sal_cod"; 
            }//echo $busqueda;exit;
            $resultado=$conexion->ejecutarSQL($configuracion, $conexionOracle, $busqueda,"busqueda");
            //var_dump($resultado);  exit;
            
            $i=0;
            while(isset ($resultado[$i]['COD_SALON_NVO']))
            {
                $resultado_1[$i][0]=$resultado[$i]['COD_SALON_NVO'];
                //$resultado_1[$i][1]=$resultado[$i]['COD_SALON_OLD']." - (".$resultado[$i]['COD_SALON_NVO'].") ".$resultado[$i]['NOM_EDIFICIO']." ".$resultado[$i]['NOM_SALON']." - Cap: ".$resultado[$i]['CUPOS'];
                $resultado_1[$i][1]=$resultado[$i]['COD_SALON_NVO']." - ".$resultado[$i]['NOM_EDIFICIO']." - ".$resultado[$i]['NOM_SALON']." - Cap: ".$resultado[$i]['CUPOS'];
                $i++;
            }
          
            if($resultado_1)
                {   $mi_cuadro="<select name='salon' id='salon'>";
                        $j=0;
                        while(isset ($resultado_1[$j][0]))
                        {
                            $mi_cuadro .= "<option value='".$resultado_1[$j][0]."'>".$resultado_1[$j][1]."</option>";
                            $j++;
                        }
                    $mi_cuadro.="</select>";
                 }
            else{ 
                      $mi_cuadro="<select disabled=yes >
                                    <option>No hay salones con las especificaciones Seleccionadas</option>
                                  </select>";
                 }
            

            $respuesta = new xajaxResponse();
            $respuesta->addAssign("salon","innerHTML",$mi_cuadro);
        
        return $respuesta;

    }

function cupos($espacio,$anio,$periodo,$grupo,$cupos)
   {
    require_once("clase/config.class.php");
    setlocale(LC_MONETARY, 'en_US');
    $esta_configuracion = new config();
    $configuracion = $esta_configuracion->variable();
    //$funcion = new funcionGeneral();
    //Conectarse a la base de datos
    $conexion=new funcionGeneral();
    $conexionOracle=$conexion->conectarDB($configuracion,"coordinador");

    $html = new html();
    
    //CONSULTA LA CAPACIDAD DEL CURSO
    $C_cadena_sql="SELECT cur_nro_cupo CUPOS,   ";
    $C_cadena_sql.="cur_nro_ins INSCRITOS, ";
    $C_cadena_sql.="cur_cap_max MAX_CAPACIDAD ";
    $C_cadena_sql.="  FROM accurso ";
    $C_cadena_sql.=" WHERE cur_ape_ano = '".$anio."'";
    $C_cadena_sql.=" AND cur_ape_per = '".$periodo."'";
    $C_cadena_sql.=" AND cur_asi_cod = '".$espacio."'";
    $C_cadena_sql.=" AND cur_nro = '".$grupo."'";
    $C_resultado=$conexion->ejecutarSQL($configuracion, $conexionOracle, $C_cadena_sql,"busqueda");

   if($cupos=='' || !is_numeric($cupos))
        { $cuposValue=$C_resultado[0]['CUPOS'];
          $msnj="<font color=red>Ingrese un valor numérico!</font>";
        }   
   elseif($C_resultado[0]['INSCRITOS']>$cupos)
        { $cuposValue=$C_resultado[0]['CUPOS'];
          $msnj="<font color=red>No se puede Cambiar el Cupo! El curso cuenta con ".$C_resultado[0]['INSCRITOS']." estudiante(s) inscrito(s)</font>";
        }   
   elseif($C_resultado[0]['MAX_CAPACIDAD']<$cupos)
        { $cuposValue=$C_resultado[0]['CUPOS'];
          $msnj="<font color=red>No se puede Cambiar el Cupo! la capaciad Máxima del curso es de ".$C_resultado[0]['MAX_CAPACIDAD']." cupos</font>";
        }      
   else    
        {   $U_cadena_sql=" UPDATE accurso ";
            $U_cadena_sql.=" SET cur_nro_cupo = '".$cupos."'";
            $U_cadena_sql.=" WHERE cur_ape_ano = '".$anio."'";
            $U_cadena_sql.=" AND cur_ape_per = '".$periodo."'";
            $U_cadena_sql.=" AND cur_asi_cod = '".$espacio."'";
            $U_cadena_sql.=" AND cur_nro = '".$grupo."'";
            $U_resultado=$conexion->ejecutarSQL($configuracion, $conexionOracle, $U_cadena_sql," ");
            if($U_resultado)
                 {$cuposValue=$cupos;
                  $msnj="<font color=green>El registro se actualizó correctamente!</font>";    
                 }
            else
                 {$cuposValue=$C_resultado[0]['CUPOS'];
                  $msnj="<font color=red>No fue posible actualizar el Cupo! <br>Por favor intente más tarde</font>";
                 }
        }
        $str="<center>Modificar Cupos de estudiantes para el grupo $grupo</center>";
        $evt="xajax_cupos(".$espacio.",".$anio.",".$periodo.",".$grupo.",document.getElementById('cupos".$espacio.$grupo."').value)";
        $texto= "<input type='text' size='3' maxlength='3' name='cupos".$espacio.$grupo."' id='cupos".$espacio.$grupo."' value='".$cuposValue."'  
                    onChange=".$evt."  
                    onmouseover='Tip(".$str.", SHADOW, true, TITLE, \"Cambio Cupos\" , PADDING, 9)'>";
        $divTexto=$espacio.$grupo;
        $divMsn='msn'.$espacio.$grupo;
        $respuesta = new xajaxResponse();
        $respuesta->addAssign($divTexto,"innerHTML",$texto);
        $respuesta->addAssign($divMsn,"innerHTML",$msnj);
        return $respuesta;
       }
?>
