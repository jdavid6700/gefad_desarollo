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

    

    function estudiantesConsejerias($annio, $periodo, $codProyecto)
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
    $accesoGestion=$conexion->estableceConexion(99,$configuracion);

    if (is_resource($enlace))
        {
            if($annio==2000)
            {
                $busqueda="select est_cod, est_nombre , estado_cod, estado_descripcion";
                $busqueda.=" from acest ";
                $busqueda.=" inner join acestado on est_estado_est= estado_cod ";
                $busqueda.=" where est_cra_cod=".$codProyecto." and est_estado like '%A%' ";
                $busqueda.=" and (estado_activo like '%S%' or est_estado_est like '%V%' or est_estado_est like '%J%')";
                $busqueda.=" and est_cod <20010000000";
                $busqueda.=" and est_cod not in (SELECT ECO_EST_COD FROM ACESTUDIANTECONSEJERO WHERE ECO_ESTADO='A')";
                //$busqueda.=" and est_ind_cred like '%S%' ";
                $busqueda.=" ORDER BY 1 ";
                //echo $busqueda;exit;
                $resultado=$funcion->ejecutarSQL($configuracion, $accesoOracle, $busqueda,"busqueda");
            }
            else
            {
                $busqueda="select est_cod, est_nombre , estado_cod, estado_descripcion";
                $busqueda.=" from acest ";
                $busqueda.=" inner join acestado on est_estado_est= estado_cod ";
                $busqueda.=" where est_cra_cod=".$codProyecto." and est_estado like '%A%' ";
                $busqueda.=" and (estado_activo like '%S%' or est_estado_est like '%V%' or est_estado_est like '%J%')";
                $busqueda.=" and est_cod like '".$annio.$periodo."%' ";
                $busqueda.=" and est_cod not in (SELECT ECO_EST_COD FROM ACESTUDIANTECONSEJERO WHERE ECO_ESTADO='A')";
                $busqueda.=" ORDER BY 1 ";
                //echo $busqueda;exit;
                $resultado=$funcion->ejecutarSQL($configuracion, $accesoOracle, $busqueda,"busqueda");
            }
            if(is_array($resultado))
                {
                    if($annio==2000)
                    {
                        $html="<table class='contenidotabla'><caption class='sigma centrar' >ESTUDIANTES DEL PERIODO 2000-1 Y ANTERIORES</caption>";
                        $html.="<tr class='sigma'>";
                        $html.="<th class='sigma centrar'>Nro</th>";
                        $html.="<th class='sigma centrar'>C&oacute;digo</th>";
                        $html.="<th class='sigma centrar'>Nombre</th>";
                        $html.="<th class='sigma centrar'>Estado</th>";
                        $html.="<th class='sigma centrar'>Seleccionar</th>";
                        $html.="</tr>";
                        $p=0;
                    }
                    else
                    {
                        $html="<table class='contenidotabla'><caption class='sigma centrar' >ESTUDIANTES DEL PERIODO ".$annio." - ".$periodo." </caption>";
                        $html.="<tr class='sigma'>";
                        $html.="<th class='sigma centrar'>Nro</th>";
                        $html.="<th class='sigma centrar'>C&oacute;digo</th>";
                        $html.="<th class='sigma centrar'>Nombre</th>";
                        $html.="<th class='sigma centrar'>Estado</th>";
                        $html.="<th class='sigma centrar'>Seleccionar</th>";
                        $html.="</tr>";
                        $p=0;
                    }
                    for($i=0;$i<count($resultado);$i++)
                    {
                        if($i%2==0)
                            {
                                $clasetr="";
                            }else
                                {
                                    $clasetr="sigma";
                                }

                        $html.="<tr class='".$clasetr."'>";
                        $html.="<td class='cuadro_plano centrar'>".++$p."</td>";
                        $html.="<td class='cuadro_plano centrar'>".$resultado[$i][0]."</td>";
                        $html.="<td class='cuadro_plano'>".$resultado[$i][1]."</td>";
                        $html.="<td class='cuadro_plano'>".$resultado[$i][3]."</td>";
                        $html.="<td class='cuadro_plano centrar'><input type=checkbox name='estudiante".($p-1)."' value='".$resultado[$i][0]."'></td></tr>";
                        
                    }
                    $html.="<tr>";
                    $html.="<td class='centrar' colspan='5'>";
                    $html.="<input type='submit' name='Guardar' value='Guardar'>";
                    $html.="</td>";
                    $html.="</tr></table>";
                }
                elseif($annio==0)
                    {
                        $html="<table class='contenidotabla'><tr><td class='sigma_a centrar' colspan='4'>Seleccione el año y periodo a consultar";
                        $html.="</table>";

                    }

                else
                    {
                        $html="<table class='contenidotabla'><tr><td class='sigma_a centrar' colspan='4'>No existen registros de estudiantes en el periodo ".$annio." - ".$periodo." ";
                        $html.="</table>";
                    }
                    

            $respuesta = new xajaxResponse();
            $respuesta->addAssign("div_estudiantesConsejerias","innerHTML",$html);
            return $respuesta;
            
       }
    }


    function datosEstudianteConsejerias($codEstudiante, $codDocente)
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
    $accesoGestion=$conexion->estableceConexion(99,$configuracion);

    if (is_resource($enlace))
        {
            $html="<div class='pestanas'>";
            $html.="<ul>";
            $html.="<li id='pestana1' class='pestanainactiva a'>";
            $html.="<a id='pestanalink1' class='link' onclick='xajax_datosEstudianteConsejerias(".$codEstudiante.");'>";
            $html.="<font size='1'><b>Datos</b></font></a></li>";
            $html.="<li id='pestana2' class='pestanainactiva a'>";
            $html.="<a id='pestanalink2' class='link' onclick='xajax_notas(".$codEstudiante.");'>";
            $html.="<font size='1'><b>Notas</b></font></a></li>";
            $html.="<li id='pestana3' class='pestanainactiva a'>";
            $html.="<a id='pestanalink3' class='link' onclick='xajax_inscripciones(".$codEstudiante.");'>";
            $html.="<font size='1'><b>Inscripciones</b></font></a></li>";
            $html.="<li id='pestana4' class='pestanainactiva a'>";
            $html.="<a id='pestanalink4' class='link' onclick='xajax_notas_parciales(".$codEstudiante.");'>";
            $html.="<font size='1'><b>Notas Parciales</b></font></a></li>";
//            $html.="<li id='pestana5' class='pestanainactiva a'>";
//            $html.="<a id='pestanalink5' class='link' onclick='xajax_pe(".$codEstudiante.");'>";
//            $html.="<font size='1'><b>Plan de estudios</b></font></a></li>";
//            Pestañas Inactivas
//            $html.="<li id='pestana6' class='pestanainactiva a'>";
//            $html.="<a id='pestanalink6' class='link' onclick='xajax_comunicacion(".$codEstudiante.", ".$_REQUEST['codDocente'].");'>";
//            $html.="<font size='1'><b>Comunicación</b></font></a></li>";
//            $html.="<li id='pestana7' class='pestanainactiva a'>";
//            $html.="<a id='pestanalink7' class='link' onclick='xajax_coordinacion(".$codEstudiante.", ".$_REQUEST['codDocente'].");'>";
//            $html.="<font size='1'><b>Coordinacion</b></font></a></li>";
            $html.="</ul>";
            $html.="</div>";
            $html.="<div id='cuerpopestanas' class='cuerpopestanas'>";
            $html.="<table class='contenidotabla'>";

                $cadena_sql="SELECT APE_ANO, APE_PER FROM ACASPERI";
                $cadena_sql.=" WHERE APE_ESTADO LIKE '%A%'";

                $resultado_periodo=$funcion->ejecutarSQL($configuracion, $accesoOracle, $cadena_sql,"busqueda");

                $busqueda="select est_cod, est_nombre , estado_cod, estado_descripcion, est_cra_cod, cra_nombre, EOT_EMAIL, est_telefono";
                $busqueda.=" from acest ";
                $busqueda.=" inner join acestado on est_estado_est= estado_cod ";
                $busqueda.=" inner join accra on est_cra_cod= cra_cod";
                $busqueda.=" inner join acestotr on EOT_COD= EST_COD";
                $busqueda.=" where est_estado like '%A%' ";
                //$busqueda.=" and estado_activo like '%S%' ";
                $busqueda.=" and est_cod = '".$codEstudiante."' ";
                //$busqueda.=" and est_ind_cred like '%S%' ";
                $busqueda.=" ORDER BY 1 ";
                
                $resultado=$funcion->ejecutarSQL($configuracion, $accesoOracle, $busqueda,"busqueda");

                $cadena_sql="select mat_est_cod, mat_annio , mat_periodo, mat_estado_est, mat_cod_motivo, mat_motivo, mat_nro_semestres, mat_prom_acumulado, mat_prom_ponderado, mat_nro_materias_perdidas";
                $cadena_sql.=" , mat_total_perdidas, mat_veces_prueba";
                $cadena_sql.=" from sga_temp_matriculados ";
                $cadena_sql.=" where mat_est_cod = '".$codEstudiante."' ";
                $cadena_sql.=" and mat_annio = '".$resultado_periodo[0][0]."'";
                $cadena_sql.=" and mat_periodo = '".$resultado_periodo[0][1]."'";
                $cadena_sql.=" ORDER BY 1 ";

                $resultado_total=$funcion->ejecutarSQL($configuracion, $accesoGestion, $cadena_sql,"busqueda");

            if(is_array($resultado))
                {
                
            $html.="<caption class='sigma'>
                           DATOS PERSONALES
                    </caption>";
            $html.="<tr>
                       <td class='centrar' colspan='6'>
                           <b>ADMINISTRACI&Oacute;N DE CONSEJERIAS<BR>UNIVERSIDAD DISTRITAL FRANCISCO JOS&Eacute; DE CALDAS
                           <hr noshade class='hr'></b>
                       </td>
                   </tr>";
            $html.="<tr>
                       <td width='15%'><b>C&Oacute;DIGO: </b></td><td><b>".$resultado[0][0]."</b></td>
                    </tr>
                    <tr>
                       <td><b>NOMBRE: </b></td><td><b>".$resultado[0][1]."</b></td>
                    </tr>
                    <tr>
                       <td><b>CARRERA: </b></td><td><b>".$resultado[0][4]." - ".$resultado[0][5]."</b></td>
                   </tr>
                    <tr>
                       <td><b>TELEFONO: </b></td><td><b>".$resultado[0][7]."</b></td>
                   </tr>
                    <tr>
                       <td><b>E-MAIL: </b></td><td><b>".$resultado[0][6]."</b></td>
                   </tr>
                   <tr>
                       <td><b>VECES EN PRUEBA: </b></td><td><b>".$resultado_total[0][11]."</b></td>
                    </tr>
                    <tr>
                       <td><b>ESTADO ACTUAL: </b></td><td><b>".$resultado[0][3]."</b></td>
                    </tr>
                    ";

                    if($resultado[0][2]=='B'||$resultado[0][2]=='J')
                        {
                            $html.="<tr>
                                    <td><b>MOTIVO: </b></td><td><b>".$resultado_total[0][5]."</b></td>
                                    </tr>";
                        }
                }
            $html.="</table>
                        </div>";

             
            $respuesta = new xajaxResponse();
            $respuesta->addAssign("div_estudianteAconsejado","innerHTML",$html);

            
               //Pongo la class css de las pestañas sin pulsar
            for ($h=1; $h<=7; $h++){
                  if ($h != 1){
                     $respuesta->addAssign("pestanalink" . $h, "className", "pestanainactiva");
                     $respuesta->addAssign("pestana" . $h, "className", "pestanainactiva");
                  }else
                      {
                        $respuesta->addAssign("pestanalink" . $h, "className", "pestanaseleccionada");
                        $respuesta->addAssign("pestana" . $h, "className", "pestanaseleccionada");
                      }
               }

            return $respuesta;

       }
    }

    function notas($codEstudiante)
    {

    require_once("clase/config.class.php");
    require_once("clase/promedios.class.php");
    $prom=new promedios();
    $esta_configuracion=new config();
    $configuracion=$esta_configuracion->variable();
    $funcion=new funcionGeneral();
    //Conectarse a la base de datos
    $acceso_db=new dbms($configuracion);
    $enlace=$acceso_db->conectar_db();
    $valor=$acceso_db->verificar_variables($valor);

    require_once("clase/encriptar.class.php");
    $cripto=new encriptar();

    $html=new html();
    $conexion=new multiConexion();
    $accesoOracle=$conexion->estableceConexion(75,$configuracion);
    $accesoGestion=$conexion->estableceConexion(99,$configuracion);

    if (is_resource($enlace))
        {
            $html="<table class='contenidotabla'>";
            $html.="<caption class='sigma'>
                           HISTORICO DE NOTAS
                    </caption>";

                $cadena_sql="SELECT APE_ANO, APE_PER FROM ACASPERI";
                $cadena_sql.=" WHERE APE_ESTADO LIKE '%A%'";

                $resultado_periodo=$funcion->ejecutarSQL($configuracion, $accesoOracle, $cadena_sql,"busqueda");

                $busqueda="select est_cod, est_nombre , estado_cod, estado_descripcion, est_cra_cod, cra_nombre, est_ind_cred ";
                $busqueda.=" from acest ";
                $busqueda.=" inner join acestado on est_estado_est= estado_cod ";
                $busqueda.=" inner join accra on est_cra_cod= cra_cod";
                $busqueda.=" where est_estado like '%A%' ";
                //$busqueda.=" and estado_activo like '%S%' ";
                $busqueda.=" and est_cod = '".$codEstudiante."' ";
                //$busqueda.=" and est_ind_cred like '%S%' ";
                $busqueda.=" ORDER BY 1 ";

                $resultado=$funcion->ejecutarSQL($configuracion, $accesoOracle, $busqueda,"busqueda");

            if(is_array($resultado))
                {

                    $cadena_sql="select mat_est_cod, mat_annio , mat_periodo, mat_estado_est, mat_cod_motivo, mat_motivo, mat_nro_semestres, mat_prom_acumulado, mat_prom_ponderado, mat_nro_materias_perdidas";
                    $cadena_sql.=" , mat_total_perdidas, mat_veces_prueba";
                    $cadena_sql.=" from sga_temp_matriculados ";
                    $cadena_sql.=" where mat_est_cod = '".$codEstudiante."' ";
                    $cadena_sql.=" and mat_annio = '".$resultado_periodo[0][0]."'";
                    $cadena_sql.=" and mat_periodo = '".$resultado_periodo[0][1]."'";
                    $cadena_sql.=" ORDER BY 1 ";

                    $resultado_total=$funcion->ejecutarSQL($configuracion, $accesoGestion, $cadena_sql,"busqueda");

            $html.="<tr>
                       <td class='centrar' colspan='6'>
                           <b>ADMINISTRACI&Oacute;N DE CONSEJERIAS<BR>UNIVERSIDAD DISTRITAL FRANCISCO JOS&Eacute; DE CALDAS
                           <hr noshade class='hr'></b>
                       </td>
                   </tr>";
            $html.="<tr>
                       <td width='15%'><b>C&Oacute;DIGO: </b></td><td><b>".$resultado[0][0]."</b></td>
                    </tr>
                    <tr>
                       <td><b>NOMBRE: </b></td><td><b>".$resultado[0][1]."</b></td>
                    </tr>
                    <tr>
                       <td><b>ESTADO: </b></td><td><b>".$resultado[0][3]."</b></td>
                    </tr>
                    <tr>
                       <td><b>CARRERA: </b></td><td><b>".$resultado[0][4]." - ".$resultado[0][5]."</b></td>
                   </tr>
                    <tr>
                       <td><b>PROMEDIO: </b></td><td><b>".$resultado_total[0][7]."</b></td>
                   </tr>";
                }
                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
		$variable="pagina=reporte_interno";
		$variable.="&opcion=informe";
                $variable.="&codigo=".$codEstudiante;
                $variable.="&no_pagina=true";
                $variable=$cripto->codificar_url($variable,$configuracion);

                $busqueda3="SELECT DISTINCT not_asi_cod,asi_nombre,not_nota, not_sem, pen_cre creditos, not_ano, nob_nombre, not_per, pen_nro_ht, pen_nro_hp ";
                $busqueda3.=" FROM acnot ";
                $busqueda3.=" INNER JOIN acasi ON acnot.not_asi_cod = acasi.asi_cod  ";
                $busqueda3.=" INNER JOIN acpen ON acnot.not_cra_cod = acpen.pen_cra_cod AND acnot.not_asi_cod = acpen.pen_asi_cod ";
                $busqueda3.=" INNER JOIN acnotobs ON acnot.not_obs = acnotobs.nob_cod ";
                $busqueda3.=" where not_est_cod = '".$codEstudiante."' ";
                $busqueda3.=" AND NOT_OBS != 19  ";
                $busqueda3.=" AND NOT_OBS != 20  ";
                $busqueda3.=" AND not_est_reg like '%A' ";
                $busqueda3.=" ORDER BY not_sem, not_asi_cod ";

                $resultado3=$funcion->ejecutarSQL($configuracion, $accesoOracle, $busqueda3,"busqueda");

                if(count($resultado3)>1)
                    {
                        $html.="<tr class='centrar'>
                        <td class='centrar' colspan='6'>
                            <a href=".$pagina.$variable.">
                                <img src='".$configuracion['host'].$configuracion['site'].$configuracion['grafico']."/acroread.png' alt='PDF' border='0'><br>Generar Certificado
                            </a>
                        </td>
                    </tr>
                    </table>";

                        if(trim($resultado[0][6])=='S')
                            {
                                for($i=0;$i<count($resultado3);$i++)
                                {
                                    if($i%2==0){$clasetr="sigma";}else{$clasetr="sigma_a";}
                                    if($resultado3[$i-1][3]!=$resultado3[$i][3])
                                        {
                                            $html.="<table class='sigma' width='80%' align='center' border='0'>
                                                    <tr>
                                                        <th colspan='7' class='sigma_a' align='center'>NIVEL ".$resultado3[$i][3]."</th>
                                                    </tr>
                                                    <tr>
                                                        <th class='sigma' align='center' width='10%'>Asignatura</th>
                                                        <th class='sigma' align='center' width='40%'>Nombre</th>
                                                        <th class='sigma' align='center' width='10%'>Nota</th>
                                                        <th class='sigma' align='center' width='10%'>Cr&eacute;ditos</th>
                                                        <th class='sigma' align='center' width='7%'>A&ntilde;o</th>
                                                        <th class='sigma' align='center' width='8%'>Periodo</th>
                                                        <th class='sigma' align='center' width='20%'>Observaci&oacute;n</th>
                                                    </tr>
                                                    ";
                                            $html.="<tr class='".$clasetr."'>
                                                        <td class='sigma' align='center'>".$resultado3[$i][0]."</td>
                                                        <td class='sigma'>".$resultado3[$i][1]."</td>
                                                        <td class='sigma' align='center'>".number_format(($resultado3[$i][2]/10),2)."</td>
                                                        <td class='sigma' align='center'>".$resultado3[$i][4]."</td>
                                                        <td class='sigma' align='center'>".$resultado3[$i][5]."</td>
                                                        <td class='sigma' align='center'>".$resultado3[$i][7]."</td>
                                                        <td class='sigma' align='center'>".$resultado3[$i][6]."</td>
                                                    </tr>
                                                    ";
                                        }else
                                            {
                                                $html.="<tr class='".$clasetr."'>
                                                        <td class='sigma' align='center' width='10%'>".$resultado3[$i][0]."</td>
                                                        <td class='sigma' width='40%'>".$resultado3[$i][1]."</td>
                                                        <td class='sigma' align='center' width='10%'>".number_format(($resultado3[$i][2]/10),2)."</td>
                                                        <td class='sigma' align='center' width='10%'>".$resultado3[$i][4]."</td>
                                                        <td class='sigma' align='center' width='7%'>".$resultado3[$i][5]."</td>
                                                        <td class='sigma' align='center' width='8%'>".$resultado3[$i][7]."</td>
                                                        <td class='sigma' align='center' width='20%'>".$resultado3[$i][6]."</td>
                                                    </tr>
                                                    ";
                                            }
                                }
                                $html.="</table>";
                            }else if(trim($resultado[0][6])=='N')
                                {
                                    for($i=0;$i<count($resultado3);$i++)
                                    {
                                        if($i%2==0){$clasetr="sigma";}else{$clasetr="sigma_a";}
                                            if($resultado3[$i-1][3]!=$resultado3[$i][3])
                                                {
                                                    $html.="<table class='sigma' width='80%' align=center border='0'>
                                                            <tr>
                                                                <th colspan='6' class='sigma_a' align='center'>SEMESTRE ".$resultado3[$i][3]."</th>
                                                            </tr>
                                                            <tr>
                                                                <th class='sigma' align='center' width='10%'>Asignatura</th>
                                                                <th class='sigma' align='center' width='40%'>Nombre</th>
                                                                <th class='sigma' align='center' width='10%'>Nota</th>
                                                                <th class='sigma' align='center' width='10%'>A&ntilde;o</th>
                                                                <th class='sigma' align='center' width='10%'>Periodo</th>
                                                                <th class='sigma' align='center' width='15%'>Observaci&oacute;n</th>
                                                            </tr>
                                                            ";
                                                            $html.="<tr class='".$clasetr."'>
                                                                <td class='sigma' align='center'>".$resultado3[$i][0]."</td>
                                                                <td class='sigma'>".$resultado3[$i][1]."</td>
                                                                <td class='sigma' align='center'>".$resultado3[$i][2]."</td>
                                                                <td class='sigma' align='center'>".$resultado3[$i][5]."</td>
                                                                <td class='sigma' align='center'>".$resultado3[$i][7]."</td>
                                                                <td class='sigma' align='center'>".$resultado3[$i][6]."</td>
                                                            </tr>
                                                            ";
                                                }else
                                                    {
                                                        $html.="<tr class='".$clasetr."'>
                                                                <td class='sigma' align='center' width='10%'>".$resultado3[$i][0]."</td>
                                                                <td class='sigma' width='40%'>".$resultado3[$i][1]."</td>
                                                                <td class='sigma' align='center' width='10%'>".$resultado3[$i][2]."</td>
                                                                <td class='sigma' align='center' width='10%'>".$resultado3[$i][5]."</td>
                                                                <td class='sigma' align='center' width='10%'>".$resultado3[$i][7]."</td>
                                                                <td class='sigma' align='center' width='15%'>".$resultado3[$i][6]."</td>
                                                            </tr>
                                                            ";
                                                    }
                                    }
                                    $html.="</table>";
                                }
                        
                        
                    }else
                        {
                        $html.="<tr class='centrar'>
                        <td class='centrar' colspan='6'>
                            <b>El estudiante no tiene notas registradas</b>
                        </td>
                    </tr>
                    </table>
                        ";
                        }

            


            $respuesta = new xajaxResponse();
            $respuesta->addAssign("cuerpopestanas","innerHTML",$html);

            $respuesta->addAssign("pestana" . 2, "className", "pestanaseleccionada");
            $respuesta->addAssign("pestanalink" . 2, "className", "pestanaseleccionada");
               //Pongo la class css de las pestañas sin pulsar
            for ($h=1; $h<=7; $h++){
                  if ($h != 2){
                     $respuesta->addAssign("pestanalink" . $h, "className", "pestanainactiva");
                     $respuesta->addAssign("pestana" . $h, "className", "pestanainactiva");
                  }
               }

            return $respuesta;

       }
    }

    function inscripciones($codEstudiante)
    {

    require_once("clase/config.class.php");
    $esta_configuracion=new config();
    $configuracion=$esta_configuracion->variable();
    $funcion=new funcionGeneral();
    //Conectarse a la base de datos
    $acceso_db=new dbms($configuracion);
    $enlace=$acceso_db->conectar_db();
    $valor=$acceso_db->verificar_variables($valor);

    require_once("clase/encriptar.class.php");
    $cripto=new encriptar();

    $html=new html();
    $conexion=new multiConexion();
    $accesoOracle=$conexion->estableceConexion(75,$configuracion);
    $accesoGestion=$conexion->estableceConexion(99,$configuracion);

    if (is_resource($enlace))
        {
            $html="<table class='contenidotabla centrar'>";
            $html.="<caption class='sigma'>
                           INSCRIPCIONES
                    </caption>";

            $busqueda="select est_cod, est_nombre , estado_cod, estado_descripcion, est_cra_cod, cra_nombre, est_ind_cred ";
            $busqueda.=" from acest ";
            $busqueda.=" inner join acestado on est_estado_est= estado_cod ";
            $busqueda.=" inner join accra on est_cra_cod= cra_cod";
            $busqueda.=" where est_estado like '%A%' ";
            $busqueda.=" and estado_activo like '%S%' ";
            $busqueda.=" and est_cod = '".$codEstudiante."' ";
            //$busqueda.=" and est_ind_cred like '%S%' ";
            $busqueda.=" ORDER BY 1 ";

            $resultado_estudiante=$funcion->ejecutarSQL($configuracion, $accesoOracle, $busqueda,"busqueda");


            $busqueda="select ins_asi_cod, ins_gr, asi_nombre,est_pen_nro";
            $busqueda.=" from acins ";
            $busqueda.=" inner join acasi on asi_cod=ins_asi_cod ";
            $busqueda.=" inner join acest on ins_est_cod=est_cod ";
            $busqueda.=" where ins_est_cod=".$codEstudiante;
            $busqueda.=" and ins_ano=(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%A%')";
            $busqueda.=" and ins_per=(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%A%')";

            $resultado=$funcion->ejecutarSQL($configuracion, $accesoOracle, $busqueda,"busqueda");

            if(is_array($resultado))
                {

                $html.="<tr>
                       <td class='centrar' colspan='14'>
                           <b>ADMINISTRACI&Oacute;N DE CONSEJERIAS<BR>UNIVERSIDAD DISTRITAL FRANCISCO JOS&Eacute; DE CALDAS
                           <hr noshade class='hr'></b>
                       </td>
                   </tr>";

                if(trim($resultado_estudiante[0][6])=='S')
                    {
                        $html.="<tr class='sigma centrar'>
                                <th class='sigma cuadro_plano centrar'><b>C&Oacute;DIGO</b></th>
                                <th class='sigma cuadro_plano centrar'><b>NOMBRE</b></th>
                                <th class='sigma cuadro_plano centrar'><b>GRUPO</b></th>
                                <th class='sigma cuadro_plano centrar'><b>CLASIF</b></th>
                                <th class='sigma cuadro_plano centrar'><b>LUNES</b></th>
                                <th class='sigma cuadro_plano centrar'><b>MARTES</b></th>
                                <th class='sigma cuadro_plano centrar'><b>MIERCOLES</b></th>
                                <th class='sigma cuadro_plano centrar'><b>JUEVES</b></th>
                                <th class='sigma cuadro_plano centrar'><b>VIERNES</b></th>
                                <th class='sigma cuadro_plano centrar'><b>SABADO</b></th>
                                <th class='sigma cuadro_plano centrar'><b>DOMINGO</b></th>
                                </tr>";

                        for($j=0;$j<count($resultado);$j++)
                        {
                            $busqueda2="SELECT id_nivel, PEE.id_clasificacion, horasDirecto, horasCooperativo,clasificacion_nombre, clasificacion_abrev";
                            $busqueda2.=" FROM sga_planEstudio_espacio PEE ";
                            $busqueda2.=" INNER JOIN sga_espacio_clasificacion CL on PEE.id_clasificacion=CL.id_clasificacion ";
                            $busqueda2.=" WHERE id_planEstudio = '".$resultado[$j][3]."'";
                            $busqueda2.=" AND id_espacio = '".$resultado[$j][0]."'";

                            $resultado2=$funcion->ejecutarSQL($configuracion, $accesoGestion, $busqueda2,"busqueda");

                            if($j%2==0)
                            {
                                $clasetr="sigma";
                            }else
                                {
                                    $clasetr="sigma_a";
                                }

                            $html.="<tr class='".$clasetr."'>
                                    <td class='sigma cuadro_plano centrar'>
                                        ".$resultado[$j][0]."
                                    </td>
                                    <td class='sigma cuadro_plano'>
                                        ".$resultado[$j][2]."
                                    </td>
                                    <td class='sigma cuadro_plano centrar'>
                                        ".$resultado[$j][1]."
                                    </td>
                                    <td class='sigma cuadro_plano centrar'>
                                        ".$resultado2[0][5]."
                                    </td>";

                                    $cadena_sql="SELECT DISTINCT  HOR_DIA_NRO, HOR_HORA, SED_ABREV, HOR_SAL_COD ";
                                    $cadena_sql.="FROM ACHORARIO ";
                                    $cadena_sql.="INNER JOIN ACCURSO ON ACHORARIO.HOR_ASI_COD=ACCURSO.CUR_ASI_COD AND ACHORARIO.HOR_NRO=ACCURSO.CUR_NRO ";
                                    $cadena_sql.="INNER JOIN GESEDE ON ACHORARIO.HOR_SED_COD=GESEDE.SED_COD ";
                                    $cadena_sql.="WHERE CUR_ASI_COD=".$resultado[$j][0];
                                    $cadena_sql.=" AND HOR_APE_ANO=(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%A%') ";
                                    $cadena_sql.=" AND HOR_APE_PER=(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%A%')";
                                    $cadena_sql.=" AND HOR_NRO=".$resultado[$j][1];//numero de grupo
                                    $cadena_sql.=" ORDER BY 1,2,3";//no cambiar el orden

                                    $resultado_horarios=$funcion->ejecutarSQL($configuracion, $accesoOracle, $cadena_sql,"busqueda");

                                    //recorre el numero de dias del la semana 1-7 (lunes-domingo)
                                    for($i=1; $i<8; $i++)
                                    {
                                        $html.="<td class='sigma cuadro_plano centrar'>";

                                        //Recorre el arreglo del resultado de los horarios
                                        for ($k=0;$k<count($resultado_horarios);$k++)
                                        {

                                            if ($resultado_horarios[$k][0]==$i && $resultado_horarios[$k][0]==$resultado_horarios[$k+1][0] && $resultado_horarios[$k+1][1]==($resultado_horarios[$k][1]+1) && $resultado_horarios[$k+1][3]==($resultado_horarios[$k][3]))
                                            {
                                                $l=$k;
                                                while ($resultado_horarios[$k][0]==$i && $resultado_horarios[$k][0]==$resultado_horarios[$k+1][0] && $resultado_horarios[$k+1][1]==($resultado_horarios[$k][1]+1) && $resultado_horarios[$k+1][3]==($resultado_horarios[$k][3]))
                                                {

                                                    $m=$k;
                                                    $m++;
                                                    $k++;
                                                }
                                                $html.="<strong>".$resultado_horarios[$l][1]."-".($resultado_horarios[$m][1]+1)."</strong><br>".$resultado_horarios[$l][2]."<br>".$resultado_horarios[$l][3];
                                                $html.="<br>";
                                                unset ($dia);
                                            }
                                            elseif ($resultado_horarios[$k][0]==$i && $resultado_horarios[$k][0]!=$resultado_horarios[$k+1][0])
                                            {
                                                    $html.="<strong>".$resultado_horarios[$k][1]."-".($resultado_horarios[$k][1]+1)."</strong><br>".$resultado_horarios[$k][2]."<br>".$resultado_horarios[$k][3];
                                                    $html.="<br>";
                                                    unset ($dia);
                                                    $k++;
                                            }
                                            elseif ($resultado_horarios[$k][0]==$i && $resultado_horarios[$k][0]==$resultado_horarios[$k+1][0] && $resultado_horarios[$k+1][3]!=($resultado_horarios[$k][3]))
                                            {
                                                    $html.="<strong>".$resultado_horarios[$k][1]."-".($resultado_horarios[$k][1]+1)."</strong><br>".$resultado_horarios[$k][2]."<br>".$resultado_horarios[$k][3];
                                                    $html.="<br>";
                                                    unset ($dia);
                                            }
                                            elseif ($resultado_horarios[$k][0]!=$i)
                                            {

                                            }
                                        }
                                        $html.="</td>";
                                    }
                                    $html.="</tr>";
                        }
                    }else if(trim($resultado_estudiante[0][6])=='N')
                        {
                            $html.="<tr class='sigma centrar'>
                                <th class='sigma cuadro_plano centrar'><b>C&Oacute;DIGO</b></th>
                                <th class='sigma cuadro_plano centrar'><b>NOMBRE</b></th>
                                <th class='sigma cuadro_plano centrar'><b>GRUPO</b></th>
                                <th class='sigma cuadro_plano centrar'><b>LUNES</b></th>
                                <th class='sigma cuadro_plano centrar'><b>MARTES</b></th>
                                <th class='sigma cuadro_plano centrar'><b>MIERCOLES</b></th>
                                <th class='sigma cuadro_plano centrar'><b>JUEVES</b></th>
                                <th class='sigma cuadro_plano centrar'><b>VIERNES</b></th>
                                <th class='sigma cuadro_plano centrar'><b>SABADO</b></th>
                                <th class='sigma cuadro_plano centrar'><b>DOMINGO</b></th>
                                </tr>";

                                for($j=0;$j<count($resultado);$j++)
                                {
                                  if($j%2==0)
                                  {
                                      $clasetr="sigma";
                                  }else
                                      {
                                          $clasetr="sigma_a";
                                      }

                                    $html.="<tr class='".$clasetr."'>
                                            <td class='sigma cuadro_plano centrar'>
                                                ".$resultado[$j][0]."
                                            </td>
                                            <td class='sigma cuadro_plano'>
                                                ".$resultado[$j][2]."
                                            </td>
                                            <td class='sigma cuadro_plano centrar'>
                                                ".$resultado[$j][1]."
                                            </td>";

                                            $cadena_sql="SELECT DISTINCT  HOR_DIA_NRO, HOR_HORA, SED_ABREV, HOR_SAL_COD ";
                                            $cadena_sql.="FROM ACHORARIO ";
                                            $cadena_sql.="INNER JOIN ACCURSO ON ACHORARIO.HOR_ASI_COD=ACCURSO.CUR_ASI_COD AND ACHORARIO.HOR_NRO=ACCURSO.CUR_NRO ";
                                            $cadena_sql.="INNER JOIN GESEDE ON ACHORARIO.HOR_SED_COD=GESEDE.SED_COD ";
                                            $cadena_sql.="WHERE CUR_ASI_COD=".$resultado[$j][0];
                                            $cadena_sql.=" AND HOR_APE_ANO=(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%A%') ";
                                            $cadena_sql.=" AND HOR_APE_PER=(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%A%')";
                                            $cadena_sql.=" AND HOR_NRO=".$resultado[$j][1];//numero de grupo
                                            $cadena_sql.=" ORDER BY 1,2,3";//no cambiar el orden

                                            $resultado_horarios=$funcion->ejecutarSQL($configuracion, $accesoOracle, $cadena_sql,"busqueda");

                                            //recorre el numero de dias del la semana 1-7 (lunes-domingo)
                                            for($i=1; $i<8; $i++)
                                            {
                                                $html.="<td class='sigma cuadro_plano centrar'>";

                                                //Recorre el arreglo del resultado de los horarios
                                                for ($k=0;$k<count($resultado_horarios);$k++)
                                                {

                                                    if ($resultado_horarios[$k][0]==$i && $resultado_horarios[$k][0]==$resultado_horarios[$k+1][0] && $resultado_horarios[$k+1][1]==($resultado_horarios[$k][1]+1) && $resultado_horarios[$k+1][3]==($resultado_horarios[$k][3]))
                                                    {
                                                        $l=$k;
                                                        while ($resultado_horarios[$k][0]==$i && $resultado_horarios[$k][0]==$resultado_horarios[$k+1][0] && $resultado_horarios[$k+1][1]==($resultado_horarios[$k][1]+1) && $resultado_horarios[$k+1][3]==($resultado_horarios[$k][3]))
                                                        {

                                                            $m=$k;
                                                            $m++;
                                                            $k++;
                                                        }
                                                        $html.="<strong>".$resultado_horarios[$l][1]."-".($resultado_horarios[$m][1]+1)."</strong><br>".$resultado_horarios[$l][2]."<br>".$resultado_horarios[$l][3];
                                                        $html.="<br>";
                                                        unset ($dia);
                                                    }
                                                    elseif ($resultado_horarios[$k][0]==$i && $resultado_horarios[$k][0]!=$resultado_horarios[$k+1][0])
                                                    {
                                                            $html.="<strong>".$resultado_horarios[$k][1]."-".($resultado_horarios[$k][1]+1)."</strong><br>".$resultado_horarios[$k][2]."<br>".$resultado_horarios[$k][3];
                                                            $html.="<br>";
                                                            unset ($dia);
                                                            $k++;
                                                    }
                                                    elseif ($resultado_horarios[$k][0]==$i && $resultado_horarios[$k][0]==$resultado_horarios[$k+1][0] && $resultado_horarios[$k+1][3]!=($resultado_horarios[$k][3]))
                                                    {
                                                            $html.="<strong>".$resultado_horarios[$k][1]."-".($resultado_horarios[$k][1]+1)."</strong><br>".$resultado_horarios[$k][2]."<br>".$resultado_horarios[$k][3];
                                                            $html.="<br>";
                                                            unset ($dia);
                                                    }
                                                    elseif ($resultado_horarios[$k][0]!=$i)
                                                    {

                                                    }
                                                }
                                                $html.="</td>";
                                            }
                                            $html.="</tr>";
                                }
                        }

                }else
                    {
                        $html.="<tr class='centrar'>
                                    <td class='sigma cuadro_plano centrar'>
                                        <b>NO SE ENCONTRARON REGISTROS DE INSCRIPCIONES</b>
                                    </td>
                                </tr>";
                    }
                
            $html.="</table>
                        </div>";


            $respuesta = new xajaxResponse();
            $respuesta->addAssign("cuerpopestanas","innerHTML",$html);

            $respuesta->addAssign("pestana" . 3, "className", "pestanaseleccionada");
            $respuesta->addAssign("pestanalink" . 3, "className", "pestanaseleccionada");
               //Pongo la class css de las pestañas sin pulsar
            for ($h=1; $h<=7; $h++){
                  if ($h != 3){
                     $respuesta->addAssign("pestanalink" . $h, "className", "pestanainactiva");
                     $respuesta->addAssign("pestana" . $h, "className", "pestanainactiva");
                  }
               }

            return $respuesta;

       }
    }

    function notas_parciales($codEstudiante)
    {
        require_once("clase/config.class.php");
        $esta_configuracion=new config();
        $configuracion=$esta_configuracion->variable();
        $funcion=new funcionGeneral();
        //Conectarse a la base de datos
        $acceso_db=new dbms($configuracion);
        $enlace=$acceso_db->conectar_db();
        $valor=$acceso_db->verificar_variables($valor);

        require_once("clase/encriptar.class.php");
        $cripto=new encriptar();

        $html=new html();
        $conexion=new multiConexion();
        $accesoOracle=$conexion->estableceConexion(75,$configuracion);
        $accesoGestion=$conexion->estableceConexion(99,$configuracion);

        if (is_resource($enlace))
        {
            $html="<table class='contenidotabla' border='0'>";
            $html.="<caption class='sigma'>
                           NOTAS PARCIALES
                    </caption>";

            $html.="<tr>
                       <td class='centrar' colspan='14'>
                           <b>ADMINISTRACI&Oacute;N DE CONSEJERIAS<BR>UNIVERSIDAD DISTRITAL FRANCISCO JOS&Eacute; DE CALDAS
                           <hr noshade class='hr'></b>
                       </td>
                   </tr>";
            $busqueda="select est_cod, est_nombre , estado_cod, estado_descripcion, est_cra_cod, cra_nombre, est_ind_cred ";
            $busqueda.=" from acest ";
            $busqueda.=" inner join acestado on est_estado_est= estado_cod ";
            $busqueda.=" inner join accra on est_cra_cod= cra_cod";
            $busqueda.=" where est_estado like '%A%' ";
            //$busqueda.=" and estado_activo like '%S%' ";
            $busqueda.=" and est_cod = '".$codEstudiante."' ";
            //$busqueda.=" and est_ind_cred like '%S%' ";
            $busqueda.=" ORDER BY 1 ";

            $resultado_estudiante=$funcion->ejecutarSQL($configuracion, $accesoOracle, $busqueda,"busqueda");


            $busqueda="select ins_asi_cod, ins_gr, asi_nombre,est_pen_nro";
            $busqueda.=" from acins ";
            $busqueda.=" inner join acasi on asi_cod=ins_asi_cod ";
            $busqueda.=" inner join acest on ins_est_cod=est_cod ";
            $busqueda.=" where ins_est_cod=".$codEstudiante;
            $busqueda.=" and ins_ano=(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%A%')";
            $busqueda.=" and ins_per=(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%A%')";

            $resultado=$funcion->ejecutarSQL($configuracion, $accesoOracle, $busqueda,"busqueda");

            if(is_array($resultado))
                {
                    $html.="<tr class='cuadro_plano'>
                            <th class='sigma cuadro_plano centrar'>Asignatura</th>
                            <th class='sigma cuadro_plano centrar'>Nombre</th>
                            <th class='sigma cuadro_plano centrar'>Grupo</th>
                            <th class='sigma cuadro_plano centrar'>Nota Par 1</th>
                            <th class='sigma cuadro_plano centrar'>Nota Par 2</th>
                            <th class='sigma cuadro_plano centrar'>Nota Par 3</th>
                            <th class='sigma cuadro_plano centrar'>Nota Par 4</th>
                            <th class='sigma cuadro_plano centrar'>Nota Par 5</th>
                            <th class='sigma cuadro_plano centrar'>Nota Par 6</th>
                            <th class='sigma cuadro_plano centrar'>Nota Par Lab</th>
                            <th class='sigma cuadro_plano centrar'>Nota Par Examen</th>
                            <th class='sigma cuadro_plano centrar'>Nro Fallas</th>
                            <th class='sigma cuadro_plano centrar'>Docente</th>
                            </tr>
                            ";
                    for($i=0;$i<count($resultado);$i++)
                    {
                        if($i%2==0)
                            {
                                $clasetr="sigma";
                            }else
                                {
                                    $clasetr="sigma_a";
                                }

                        $cadena_sql="select cur_par1,cur_par2,cur_par3,cur_par4,cur_par5,cur_par6,cur_lab,cur_exa,doc_apellido,doc_nombre ";
                        $cadena_sql.=" from accurso ";
                        $cadena_sql.=" inner join accarga on car_cur_asi_cod= cur_asi_cod and car_cur_nro= cur_nro ";
                        $cadena_sql.=" inner join acdocente on car_doc_nro_iden= doc_nro_iden ";
                        $cadena_sql.=" where cur_asi_cod='".$resultado[$i][0]."'";
                        $cadena_sql.=" and cur_nro='".$resultado[$i][1]."'";
                        $cadena_sql.=" and cur_ape_ano=(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%A%')";
                        $cadena_sql.=" and cur_ape_per=(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%A%')";

                        $resultado_curso=$funcion->ejecutarSQL($configuracion, $accesoOracle, $cadena_sql,"busqueda");

                        $cadena_sql="select ins_nota_par1,ins_nota_par2,ins_nota_par3,ins_nota_par4,ins_nota_par5,ins_nota_par6, ";
                        $cadena_sql.=" ins_nota_lab,ins_nota_exa, ins_tot_fallas";
                        $cadena_sql.=" from acins ";
                        $cadena_sql.=" where ins_asi_cod='".$resultado[$i][0]."'";
                        $cadena_sql.=" and ins_gr='".$resultado[$i][1]."'";
                        $cadena_sql.=" and ins_est_cod='".$codEstudiante."'";
                        $cadena_sql.=" and ins_ano=(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%A%')";
                        $cadena_sql.=" and ins_per=(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%A%')";

                        $resultado_inscripciones=$funcion->ejecutarSQL($configuracion, $accesoOracle, $cadena_sql,"busqueda");

                        $html.="<tr class='".$clasetr."'>
                            <td class='sigma cuadro_plano centrar'>".$resultado[$i][0]."</td>
                            <td class='sigma cuadro_plano '>".$resultado[$i][2]."</td>
                            <td class='sigma cuadro_plano centrar'>".$resultado[$i][1]."</td>
                            <td class='sigma cuadro_plano centrar'><table class='contenidotabla centrar'><tr><td class='sigma centrar'>".$resultado_curso[0][0]." %</th><tr><tr><td class='centrar'>".number_format(($resultado_inscripciones[0][0]/10),1)."</td></tr></table></td>
                            <td class='sigma cuadro_plano centrar'><table class='contenidotabla centrar'><tr><td class='sigma centrar'>".$resultado_curso[0][1]." %</td><tr><tr><td class='centrar'>".number_format(($resultado_inscripciones[0][1]/10),1)."</td></tr></table></td>
                            <td class='sigma cuadro_plano centrar'><table class='contenidotabla centrar'><tr><td class='sigma centrar'>".$resultado_curso[0][2]." %</td><tr><tr><td class='centrar'>".number_format(($resultado_inscripciones[0][2]/10),1)."</td></tr></table></td>
                            <td class='sigma cuadro_plano centrar'><table class='contenidotabla centrar'><tr><td class='sigma centrar'>".$resultado_curso[0][3]." %</td><tr><tr><td class='centrar'>".number_format(($resultado_inscripciones[0][3]/10),1)."</td></tr></table></td>
                            <td class='sigma cuadro_plano centrar'><table class='contenidotabla centrar'><tr><td class='sigma centrar'>".$resultado_curso[0][4]." %</td><tr><tr><td class='centrar'>".number_format(($resultado_inscripciones[0][4]/10),1)."</td></tr></table></td>
                            <td class='sigma cuadro_plano centrar'><table class='contenidotabla centrar'><tr><td class='sigma centrar'>".$resultado_curso[0][5]." %</td><tr><tr><td class='centrar'>".number_format(($resultado_inscripciones[0][5]/10),1)."</td></tr></table></td>
                            <td class='sigma cuadro_plano centrar'><table class='contenidotabla centrar'><tr><td class='sigma centrar'>".$resultado_curso[0][6]." %</td><tr><tr><td class='centrar'>".number_format(($resultado_inscripciones[0][6]/10),1)."</td></tr></table></td>
                            <td class='sigma cuadro_plano centrar' align='center'><table class='contenidotabla centrar'><tr><td class='sigma centrar'>".$resultado_curso[0][7]." %</td><tr><tr><td class='centrar'>".number_format(($resultado_inscripciones[0][7]/10),1)."</td></tr></table></td>
                            <td class='sigma cuadro_plano centrar'>".$resultado_inscripciones[0][8]."</td>
                            <td class='sigma cuadro_plano centrar'>".$resultado_curso[0][9]." ".$resultado_curso[0][8]."</td>
                            </tr>
                            ";
                    }
                }else
                    {
                        $html.="<tr class='centrar'>
                                    <td class='centrar'>
                                        <b>NO SE ENCONTRARON REGISTROS DE INSCRIPCIONES</b>
                                    </td>
                                </tr>";
                    }

            $html.="</table>
                    </div>";
                    
            $respuesta = new xajaxResponse();
            $respuesta->addAssign("cuerpopestanas","innerHTML",$html);

            $respuesta->addAssign("pestana" . 4, "className", "pestanaseleccionada");
            $respuesta->addAssign("pestanalink" . 4, "className", "pestanaseleccionada");
               //Pongo la class css de las pestañas sin pulsar
            for ($h=1; $h<=7; $h++){
                  if ($h != 4){
                     $respuesta->addAssign("pestanalink" . $h, "className", "pestanainactiva");
                     $respuesta->addAssign("pestana" . $h, "className", "pestanainactiva");
                  }
               }

            return $respuesta;
        }
        
    }

    function comunicacion($codEstudiante, $codDocente)
    {
        require_once("clase/config.class.php");
        $esta_configuracion=new config();
        $configuracion=$esta_configuracion->variable();
        $funcion=new funcionGeneral();
        //Conectarse a la base de datos
        $acceso_db=new dbms($configuracion);
        $enlace=$acceso_db->conectar_db();
        $valor=$acceso_db->verificar_variables($valor);

        require_once("clase/encriptar.class.php");
        $cripto=new encriptar();

        $html=new html();
        $conexion=new multiConexion();
        $accesoOracle=$conexion->estableceConexion(75,$configuracion);
        $accesoGestion=$conexion->estableceConexion(99,$configuracion);

        if (is_resource($enlace))
        {
            $html="<table class='contenidotabla'>";

            $html.="<caption class='sigma'>
                           COMUNICACI&Oacute;N ESTUDIANTE
                    </caption>";

            $html.="<tr>
                       <td class='centrar' colspan='2'>
                           <b>ADMINISTRACI&Oacute;N DE CONSEJERIAS<BR>UNIVERSIDAD DISTRITAL FRANCISCO JOS&Eacute; DE CALDAS
                           <hr noshade class='hr'></b>
                       </td>
                   </tr>
                     ";
            $busqueda="select est_cod, est_nombre , estado_cod, estado_descripcion, est_cra_cod, cra_nombre, est_ind_cred ";
            $busqueda.=" from acest ";
            $busqueda.=" inner join acestado on est_estado_est= estado_cod ";
            $busqueda.=" inner join accra on est_cra_cod= cra_cod";
            $busqueda.=" where est_estado like '%A%' ";
            //$busqueda.=" and estado_activo like '%S%' ";
            $busqueda.=" and est_cod = '".$codEstudiante."' ";
            //$busqueda.=" and est_ind_cred like '%S%' ";
            $busqueda.=" ORDER BY 1 ";//echo $busqueda;
//echo $busqueda;exit;
            $resultado_estudiante=$funcion->ejecutarSQL($configuracion, $accesoOracle, $busqueda,"busqueda");

            if(is_array($resultado_estudiante))
                {
                    $consulta="select ACC_COD, ACC_COD_EMISOR, to_char(ACC_FECHA,'dd/mon/yyyy'), to_char(ACC_FECHA,'hh12:mi:ss am'), ACC_TIP_COMMENT, ACC_COMMENT, ACC_ESTADO, ACC_TIP_EMISOR, TCM_DES from ACCOMMENT ";
                    $consulta.="INNER JOIN ACTIPCOMMENT ";
                    $consulta.="ON TCM_COD=ACC_TIP_COMMENT ";
                    $consulta.="WHERE";
                    $consulta.=" ((ACC_COD_RECEPTOR = ".$codDocente." AND ACC_TIP_RECEPTOR=30 AND ACC_COD_EMISOR = ".$codEstudiante.")";
                    $consulta.=" OR (ACC_COD_RECEPTOR = ".$codEstudiante." AND ACC_COD_EMISOR =".$codDocente."AND ACC_TIP_EMISOR=30))";
                    //$consulta.=" AND ACC_ESTADO LIKE '%L%'";
                    $consulta.=" AND ACC_COD NOT IN (SELECT ACC_COD FROM ACCOMMENT WHERE ACC_COD_RECEPTOR = ".$codDocente." AND ACC_TIP_RECEPTOR=30 AND ACC_COD_EMISOR = ".$codEstudiante." AND ACC_ESTADO LIKE '%P%')";
                    $consulta.=" AND ACC_ANO =(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%A%')";
                    $consulta.=" AND ACC_PER =(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%A%')";
                    $consulta.=" ORDER BY 1 DESC";
                    //echo $consulta;exit;
                    $resultado_mensaje=$funcion->ejecutarSQL($configuracion, $accesoOracle, $consulta,"busqueda");

//nuevo mensaje
                    $nuevo="select COUNT (*) from ACCOMMENT ";
                    $nuevo.="WHERE";
                    $nuevo.=" ACC_COD_RECEPTOR = ".$codDocente;
                    $nuevo.=" AND ACC_TIP_RECEPTOR = 30";
                    $nuevo.=" AND ACC_COD_EMISOR = ".$codEstudiante;
                    $nuevo.=" AND ACC_ESTADO LIKE '%P%'";
                    $nuevo.=" AND ACC_ANO =(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%A%')";
                    $nuevo.=" AND ACC_PER =(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%A%')";
                    $resultado_cuenta=$funcion->ejecutarSQL($configuracion, $accesoOracle, $nuevo,"busqueda");
if($resultado_cuenta[0][0]>0)
{
                    $nuevo="select ACC_COD, to_char(ACC_FECHA,'dd/mon/yyyy'), to_char(ACC_FECHA,'hh12:mi:ss am'), ACC_TIP_COMMENT, ACC_COMMENT, ACC_ESTADO, ACC_TIP_EMISOR, TCM_DES from ACCOMMENT ";
                    $nuevo.="INNER JOIN ACTIPCOMMENT ";
                    $nuevo.="ON TCM_COD=ACC_TIP_COMMENT ";
                    $nuevo.="WHERE";
                    $nuevo.=" ACC_COD_RECEPTOR = ".$codDocente;
                    $nuevo.=" AND ACC_TIP_RECEPTOR = 30";
                    $nuevo.=" AND ACC_COD_EMISOR = ".$codEstudiante;
                    $nuevo.=" AND ACC_ESTADO LIKE '%P%'";
                    $nuevo.=" AND ACC_ANO =(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%A%')";
                    $nuevo.=" AND ACC_PER =(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%A%')";
                    $nuevo.=" ORDER BY 1 DESC";
                    //echo $nuevo;exit;
                    $resultado_nuevo=$funcion->ejecutarSQL($configuracion, $accesoOracle, $nuevo,"busqueda");
                    //echo count($resultado_nuevo); exit;

    $html.="
<tr>
    <td>
        <div id='div_nuevo1'>
            <table class='contenidotabla' centrar>
                <tr>
                    <td class='centrar' colspan='2'>Tiene ".$resultado_cuenta[0][0]." mensajes por leer de ".$resultado_estudiante[0][1]."<br>";
                    for($n=0;$n<count($resultado_nuevo);$n++)
                    {
                        $html.="<a id='mensajeleer' class='link' onclick='xajax_mensajesPorLeer(".$resultado_nuevo[$n][0].", ".$codDocente.", ".$codEstudiante.");'>
                                <font size='1'><b>".$resultado_nuevo[$n][1]." a las ".$resultado_nuevo[$n][2]."</b></font></a><br>";
                    }
                    $html.="</td>
                </tr>
            </table>
        </div>
    </td>
</tr>


";
}


//fin nuevo mensaje
                    if(is_array($resultado_mensaje))
                    {
                $cadena_sql="SELECT TCM_COD, TCM_DES FROM ACTIPCOMMENT";
                $resultado_tipo=$funcion->ejecutarSQL($configuracion, $accesoOracle, $cadena_sql,"busqueda");

$html.="<tr><td colspan='2'>
    <table class='contenidotabla centrar' border='0' background=".$configuracion['site'].$configuracion['grafico']."/escudo_fondo.png' style='background-attachment:fixed; background-repeat:no-repeat; background-position:top'>
        <tr align='center'>
            <td class='centrar' colspan='2'>
                Escr&iacute;bale un mensaje a ".$resultado_estudiante[0][1]."
            </td>
        </tr>
        <tr>
            <td class='centrar' colspan='2'>
                <textarea id='mensaje1' name='mensaje' rows='2' cols='90'></textarea>
            </td>
        </tr>
        <tr>
            <td class='centrar' colspan='2'>
            <select class='sigma' id='tipo1' name='tipo' style='width:300px'>
		<optgroup>
		    <option value=''>Seleccione tema del mensaje...</option>";
		        for($i=0;$i<count($resultado_tipo);$i++)
		            {
		                if($i==0)
		                {
		                    $html.="
		                <option value='".$resultado_tipo[$i][0]."'selected>".$resultado_tipo[$i][1]."</option>";
		                }
		                else
		                {
		                    $html.="
		                <option value='".$resultado_tipo[$i][0]."'>".$resultado_tipo[$i][1]."</option>";
		                }
		            }
		                $html.="
		    </optgroup>
            </select>
        </td>
        </tr>
        <tr>
            <td class='centrar' width='50%'>
                <input class='boton' type='button' value='Guardar' onclick='xajax_guardarMensaje(document.getElementById(\"mensaje1\").value, ".$codEstudiante.", ".$codDocente.", document.getElementById(\"tipo1\").value);mensaje1.value=\"\"'>
            </td>
            <td class='centrar' width='50%'>
                <input class='boton' type='button' value='Borrar' onclick='mensaje1.value=\"\"'>
            </td>
        </tr>
    </table>
<hr noshade class='hr'></td></tr>";


                   $html.="
                            <tr><td colspan='2'><div id='div_mensajes1'>
                    <table class='contenidotabla centrar'>
                    <tr>
                       <td class='centrar' colspan='2'>
                           <b>Mensajes entre Usted y ".$resultado_estudiante[0][1]."
                           </b>
                       </td>
                   </tr></table><table class='contenidotablaNotamanno' align='center' width='70%'>";


                    for($m=0;$m<count($resultado_mensaje);$m++)
                    {
                        if($resultado_mensaje[$m][1]==$codDocente)
                        {
                            $resultado_mensaje[$m][1]="USTED";
                        }
                        else
                        {
                            $resultado_mensaje[$m][1]=$resultado_estudiante[0][1];
                        }
                            if(trim($resultado_mensaje[$m][6])=='L')
                            {
                                $estado="Ya ha sido le&iacute;do";
                            }
                            else
                            {
                                $estado="No se ha le&iacute;do";
                            }

                            if($m%2==0)
                              {
                                $classtr="sigma";
                              }else
                                {
                                  $classtr="sigma_a";
                                }

                        $html.="<tr class='".$classtr."'>
                            <td class='sigma'>El ".$resultado_mensaje[$m][2]." a las ".$resultado_mensaje[$m][3]." ".$resultado_mensaje[$m][1]." escribi&oacute; del tema ".$resultado_mensaje[$m][8].":</td>
                            <td class='sigma' align='right'>".$estado."</td>
                            </tr>
                            <tr class='".$classtr."'>
                                <td colspan='2'>".$resultado_mensaje[$m][5]."</td>
                            </tr>
                            ";
                    }
                    $html.="</table></div></td></tr>";

                }
                else
                    {
                        $html.="<tr class='centrar'>
                                    <td class='centrar'>
                                        <b>NO HAY MENSAJES</b>
                                    </td>
                                </tr>";
                        $cadena_sql="SELECT TCM_COD, TCM_DES FROM ACTIPCOMMENT";
                        $resultado_tipo=$funcion->ejecutarSQL($configuracion, $accesoOracle, $cadena_sql,"busqueda");

                        $html.="<tr><td colspan='2'>
                            <table class='contenidotabla centrar' border='0' background=".$configuracion['site'].$configuracion['grafico']."/escudo_fondo.png' style='background-attachment:fixed; background-repeat:no-repeat; background-position:top'>
                                <tr align='center'>
                                    <td class='centrar' colspan='2'>
                                        Escr&iacute;bale un mensaje a ".$resultado_estudiante[0][1]."
                                    </td>
                                </tr>
                                <tr>
                                    <td class='centrar' colspan='2'>
                                        <textarea id='mensaje1' name='mensaje' rows='2' cols='90')'></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td class='centrar' colspan='2'>
                                    <select class='sigma' id='tipo1' name='tipo' style='width:300px'>
                                <optgroup>
                                    <option value=''>Seleccione tema del mensaje...</option>";
                                        for($i=0;$i<count($resultado_tipo);$i++)
                                            {
                                                if($i==0)
                                                {
                                                    $html.="
                                                <option value='".$resultado_tipo[$i][0]."'selected>".$resultado_tipo[$i][1]."</option>";
                                                }
                                                else
                                                {
                                                    $html.="
                                                <option value='".$resultado_tipo[$i][0]."'>".$resultado_tipo[$i][1]."</option>";
                                                }
                                            }
                                                $html.="
                                    </optgroup>
                                    </select>
                                </td>
                                </tr>
                                <tr>
                                    <td class='centrar' width='50%'>
                                        <input class='boton' type='button' class='boton' value='Guardar' onclick='xajax_guardarMensaje(document.getElementById(\"mensaje1\").value, ".$codEstudiante.", ".$codDocente.", document.getElementById(\"tipo1\").value);mensaje1.value=\"\"'>
                                    </td>
                                    <td class='centrar' width='50%'>
                                        <input class='boton' type='button' class='boton' value='Borrar' onclick='mensaje1.value=\"\"'>
                                    </td>
                                </tr>
                            </table>
                        <hr noshade class='hr'></td></tr>
                        <tr>
                            <td colspan='2'>
                                <div id='div_mensajes1'>
                                </div>
                            </td>
                       </tr>";

                    }

                }else
                    {
                        $html.="<tr><td colspan='2'><div id='div_mensajes1'>
        <tr class='centrar'>
                                    <td class='centrar'>
                                        <b>NO SE ENCONTRARON REGISTROS</b>
                                    </td>
                                </tr>
</div></td></tr>";
                    }

            $html.="</table>
                    ";

            $respuesta = new xajaxResponse();
            $respuesta->addAssign("mensaje1","innerHTML",'');
            //$respuesta->addAssign("div_mensajes","innerHTML","");
            $respuesta->addAssign("cuerpopestanas","innerHTML",$html);

            $respuesta->addAssign("pestana" . 6, "className", "pestanaseleccionada");
            $respuesta->addAssign("pestanalink" . 6, "className", "pestanaseleccionada");
               //Pongo la class css de las pestañas sin pulsar
            for ($h=1; $h<=7; $h++){
                  if ($h != 6){
                     $respuesta->addAssign("pestanalink" . $h, "className", "pestanainactiva");
                     $respuesta->addAssign("pestana" . $h, "className", "pestanainactiva");
                  }
               }

            return $respuesta;


        }
    }

    function guardarMensaje($mensaje, $codEstudiante, $codDocente, $tipo, $codCoordinador)
    {//var_dump($codCoordinador);exit;
        require_once("clase/config.class.php");
        $esta_configuracion=new config();
        $configuracion=$esta_configuracion->variable();
        $funcion=new funcionGeneral();
        //Conectarse a la base de datos
        $acceso_db=new dbms($configuracion);
        $enlace=$acceso_db->conectar_db();
        $valor=$acceso_db->verificar_variables($valor);

        require_once("clase/encriptar.class.php");
        $cripto=new encriptar();

        $html=new html();
        $conexion=new multiConexion();
        $accesoOracle=$conexion->estableceConexion(75,$configuracion);
        $accesoGestion=$conexion->estableceConexion(99,$configuracion);

        if (is_resource($enlace))
        {
            $nivel="select cla_tipo_usu from geclaves where cla_codigo=".$codEstudiante;
            $resultado_nivel=$funcion->ejecutarSQL($configuracion, $accesoOracle, $nivel,"busqueda");
            if(isset($codCoordinador))
            {
                if($resultado_nivel[0][0]==51)
                {
                    $resultado_nivel[0][0]=4;
                }
                elseif($resultado_nivel[0][0]==52)
                {
                    $resultado_nivel[0][0]=28;
                }
                $receptor=$codCoordinador;
                $div=2;
            }
            else
            {
                $receptor=$codEstudiante;
                $div=1;
            }

            $message="insert into accomment ";
            $message.="(ACC_TIP_EMISOR, ACC_COD_EMISOR, ACC_TIP_RECEPTOR, ACC_COD_RECEPTOR, ACC_FECHA, ACC_TIP_COMMENT, ACC_COMMENT, ACC_ESTADO, ACC_ANO, ACC_PER) ";
            $message.="values (30, ".$codDocente.", ".$resultado_nivel[0][0].", ".$receptor;
            $message.=", sysdate, ".$tipo.", '".$mensaje."', 'P',";
            $message.="(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%A%'), (SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%A%'))";
//echo $message;exit;
            $resultado_guardar=$funcion->ejecutarSQL($configuracion, $accesoOracle, $message,"");
//var_dump($resultado_guardar);exit;

            if($resultado_guardar==true)
            {
            if(isset($codCoordinador))
                {
                    $coordinador="select doc_nombre, doc_apellido, doc_email";
                    $coordinador.=" from acdocente";
                    $coordinador.=" where doc_nro_iden=".$codCoordinador;
                    $resultado_coordinador=$funcion->ejecutarSQL($configuracion, $accesoOracle, $coordinador,"busqueda");
                    $resultado_estudiante[0][1]=$resultado_coordinador[0][0]." ".$resultado_coordinador[0][1];
                }
                else
                {
                    $busqueda="select est_cod, est_nombre , estado_cod, estado_descripcion, est_cra_cod, cra_nombre, est_ind_cred ";
                    $busqueda.=" from acest ";
                    $busqueda.=" inner join acestado on est_estado_est= estado_cod ";
                    $busqueda.=" inner join accra on est_cra_cod= cra_cod";
                    $busqueda.=" where est_estado like '%A%' ";
                    //$busqueda.=" and estado_activo like '%S%' ";
                    $busqueda.=" and est_cod = '".$codEstudiante."' ";
                    //$busqueda.=" and est_ind_cred like '%S%' ";
                    $busqueda.=" ORDER BY 1 ";//echo $busqueda;
        //echo $busqueda;exit;
                    $resultado_estudiante=$funcion->ejecutarSQL($configuracion, $accesoOracle, $busqueda,"busqueda");
                }

            if(is_array($resultado_estudiante))
                {
                    $consulta="select ACC_COD, ACC_COD_EMISOR, to_char(ACC_FECHA,'dd/mon/yyyy'), to_char(ACC_FECHA,'hh12:mi:ss am'), ACC_TIP_COMMENT, ACC_COMMENT, ACC_ESTADO, ACC_TIP_EMISOR, TCM_DES from ACCOMMENT ";
                    $consulta.="INNER JOIN ACTIPCOMMENT ";
                    $consulta.="ON TCM_COD=ACC_TIP_COMMENT ";
                    $consulta.="WHERE";
                    $consulta.=" ((ACC_COD_RECEPTOR = ".$codDocente." AND ACC_TIP_RECEPTOR=30 AND ACC_COD_EMISOR = ".$receptor.")";
                    $consulta.=" OR (ACC_COD_RECEPTOR = ".$receptor." AND ACC_COD_EMISOR =".$codDocente."AND ACC_TIP_EMISOR=30))";
                    //$consulta.=" AND ACC_ESTADO LIKE '%L%'";
                    $consulta.=" AND ACC_COD NOT IN (SELECT ACC_COD FROM ACCOMMENT WHERE ACC_COD_RECEPTOR = ".$codDocente." AND ACC_TIP_RECEPTOR=30 AND ACC_TIP_RECEPTOR=30 AND ACC_COD_EMISOR = ".$receptor." AND ACC_ESTADO LIKE '%P%')";
                    $consulta.=" AND ACC_ANO =(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%A%')";
                    $consulta.=" AND ACC_PER =(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%A%')";
                    $consulta.=" ORDER BY 1 DESC";
                    //echo $consulta;exit;
                    $resultado_mensaje=$funcion->ejecutarSQL($configuracion, $accesoOracle, $consulta,"busqueda");

//var_dump($resultado_mensaje);exit;
                    if(is_array($resultado_mensaje))
                    {

            $html="<table class='contenidotabla'>
                    <tr>
                       <td class='centrar' colspan='2'>
                           <b>Mensajes entre Usted y ".$resultado_estudiante[0][1]."
                           </b>
                       </td>
                   </tr></table><table class='contenidotablaNotamanno' align='center' width='70%'>";

//echo trim($resultado_mensaje[0][6])."-";exit;
                    for($m=0;$m<count($resultado_mensaje);$m++)
                    {
                        if($resultado_mensaje[$m][1]==$codDocente && $resultado_mensaje[$m][7]==30)
                        {
                            $resultado_mensaje[$m][1]="USTED";
                        }
                        else
                        {
                            $resultado_mensaje[$m][1]=$resultado_estudiante[0][1];
                        }

                            if(trim($resultado_mensaje[$m][6])=='L')
                            {
                                $estado="Le&iacute;do";
                            }
                            else
                            {
                                $estado="Sin Leer";
                            }

                            if($m%2==0)
                              {
                                $classtr='sigma';
                              }else
                                {
                                  $classtr='sigma_a';
                                }

                        $html.="<tr class='".$classtr."'>
                            <td class='sigma'>El ".$resultado_mensaje[$m][2]." a las ".$resultado_mensaje[$m][3]." ".$resultado_mensaje[$m][1]." escribi&oacute; del tema ".$resultado_mensaje[$m][8].":</td>
                            <td class='sigma' align='right' colspan='1'>".$estado."</td>
                            </tr>
                            <tr class='".$classtr."'>
                                <td class='sigma' colspan='2'>".$resultado_mensaje[$m][5]."</td>
                            </tr>
                            ";
                    }
                    $html.="</table>";
                    }
                }
            }
            else
            {
                $html= "Debe escribir alg&uacute;n mensaje y seleccionar un tema";
            }


            $respuesta = new xajaxResponse();
            $respuesta->addAssign("mensaje".$div,"innerHTML",'');
            $respuesta->addAssign("div_mensajes".$div,"innerHTML",$html);
            //var_dump($respuesta);exit;
            return $respuesta;
        }
    }

    function mensajesPorLeer($codmensaje, $codDocente, $codEstudiante, $codCoordinador)
    {
        require_once("clase/config.class.php");
        $esta_configuracion=new config();
        $configuracion=$esta_configuracion->variable();
        $funcion=new funcionGeneral();
        //Conectarse a la base de datos
        $acceso_db=new dbms($configuracion);
        $enlace=$acceso_db->conectar_db();
        $valor=$acceso_db->verificar_variables($valor);

        require_once("clase/encriptar.class.php");
        $cripto=new encriptar();

        $html=new html();
        $conexion=new multiConexion();
        $accesoOracle=$conexion->estableceConexion(75,$configuracion);
        $accesoGestion=$conexion->estableceConexion(99,$configuracion);

        if (is_resource($enlace))
        {
            if(isset($codCoordinador))
            {
                $codEmisor=$codCoordinador;
                $div=2;
            }
            else
            {
                $codEmisor=$codEstudiante;
                $div=1;
            }
                    $cambiar="update ACCOMMENT ";
                    $cambiar.="SET ACC_ESTADO = 'L' ";
                    $cambiar.="WHERE ACC_COD=".$codmensaje;
                    $cambiar.=" AND ACC_COD_EMISOR=".$codEmisor;
                    $cambiar.=" AND ACC_COD_RECEPTOR=".$codDocente;

                    $resultado_cambiar=$funcion->ejecutarSQL($configuracion, $accesoOracle, $cambiar,"");

                    $nuevo="select COUNT (*) from ACCOMMENT ";
                    $nuevo.="WHERE";
                    $nuevo.=" ACC_COD_RECEPTOR = ".$codDocente;
                    $nuevo.=" AND ACC_TIP_RECEPTOR = 30";
                    $nuevo.=" AND ACC_COD_EMISOR = ".$codEmisor;
                    $nuevo.=" AND ACC_ESTADO LIKE '%P%'";
                    $nuevo.=" AND ACC_ANO =(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%A%')";
                    $nuevo.=" AND ACC_PER =(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%A%')";
                    $resultado_cuenta=$funcion->ejecutarSQL($configuracion, $accesoOracle, $nuevo,"busqueda");
            if($resultado_cuenta[0][0]>0)
            {

            if(isset($codCoordinador))
                {
                    $coordinador="select doc_nombre, doc_apellido, doc_email";
                    $coordinador.=" from acdocente";
                    $coordinador.=" where doc_nro_iden=".$codCoordinador;

                    $resultado_coordinador=$funcion->ejecutarSQL($configuracion, $accesoOracle, $coordinador,"busqueda");
                    $resultado_estudiante[0][1]=$resultado_coordinador[0][0]." ".$resultado_coordinador[0][1];
                }
                else
                {
                    $busqueda="select est_cod, est_nombre , estado_cod, estado_descripcion, est_cra_cod, cra_nombre, est_ind_cred ";
                    $busqueda.=" from acest ";
                    $busqueda.=" inner join acestado on est_estado_est= estado_cod ";
                    $busqueda.=" inner join accra on est_cra_cod= cra_cod";
                    $busqueda.=" where est_estado like '%A%' ";
                    //$busqueda.=" and estado_activo like '%S%' ";
                    $busqueda.=" and est_cod = '".$codEstudiante."' ";
                    //$busqueda.=" and est_ind_cred like '%S%' ";
                    $busqueda.=" ORDER BY 1 ";//echo $busqueda;
        //echo $busqueda;exit;
                    $resultado_estudiante=$funcion->ejecutarSQL($configuracion, $accesoOracle, $busqueda,"busqueda");
                }

                    $nuevo="select ACC_COD, to_char(ACC_FECHA,'dd/mon/yyyy'), to_char(ACC_FECHA,'hh12:mi:ss am'), ACC_TIP_COMMENT, ACC_COMMENT, ACC_ESTADO, ACC_TIP_EMISOR, TCM_DES from ACCOMMENT ";
                    $nuevo.="INNER JOIN ACTIPCOMMENT ";
                    $nuevo.="ON TCM_COD=ACC_TIP_COMMENT ";
                    $nuevo.="WHERE";
                    $nuevo.=" ACC_COD_RECEPTOR = ".$codDocente;
                    $nuevo.=" AND ACC_TIP_RECEPTOR = 30";
                    $nuevo.=" AND ACC_COD_EMISOR = ".$codEmisor;
                    $nuevo.=" AND ACC_ESTADO LIKE '%P%'";
                    $nuevo.=" AND ACC_ANO =(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%A%')";
                    $nuevo.=" AND ACC_PER =(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%A%')";
                    $nuevo.=" ORDER BY 1 DESC";
                    //echo $nuevo;exit;
                    $resultado_nuevo=$funcion->ejecutarSQL($configuracion, $accesoOracle, $nuevo,"busqueda");
                    //echo count($resultado_nuevo); exit;

    $html2="
            <table class='contenidotabla' centrar>
                <tr>
                    <td class='centrar' colspan='2'>Tiene ".$resultado_cuenta[0][0]." mensajes por leer de ".$resultado_estudiante[0][1]."<br>";
                    for($n=0;$n<count($resultado_nuevo);$n++)
                    {
                        $html2.="<a id='mensajeleer' class='link' onclick='xajax_mensajesPorLeer(".$resultado_nuevo[$n][0].", ".$codDocente.", ".$codEstudiante;
                    if(isset($codCoordinador))
                    {
                        $html2.=", ".$codCoordinador.");'>";
                    }
                    else
                    {
                        $html2.=");'>";
                    }
                        $html2.="<font size='1'><b>".$resultado_nuevo[$n][1]." a las ".$resultado_nuevo[$n][2]."</b></font></a><br>";

                    }

                $html2.="</td></tr>
            </table>";
}

                    $consulta="select ACC_COD, ACC_COD_EMISOR, to_char(ACC_FECHA,'dd/mon/yyyy'), to_char(ACC_FECHA,'hh12:mi:ss am'), ACC_TIP_COMMENT, ACC_COMMENT, ACC_ESTADO, ACC_TIP_EMISOR, TCM_DES from ACCOMMENT ";
                    $consulta.="INNER JOIN ACTIPCOMMENT ";
                    $consulta.="ON TCM_COD=ACC_TIP_COMMENT ";
                    $consulta.="WHERE ACC_COD =".$codmensaje;
                    $consulta.=" AND ACC_COD_EMISOR=".$codEmisor;
                    $consulta.=" AND ACC_COD_RECEPTOR=".$codDocente;

                    $resultado_mensaje=$funcion->ejecutarSQL($configuracion, $accesoOracle, $consulta,"busqueda");


                        if($resultado_mensaje[0][1]==$codDocente && $resultado_mensaje[$m][7]==30)
                        {
                            $resultado_mensaje[0][1]="USTED";
                        }
                        else
                        {
                            $resultado_mensaje[0][1]=$resultado_estudiante[0][1];
                        }

                            if(trim($resultado_mensaje[0][6])=='L')
                            {
                                $estado="Le&iacute;do";
                            }
                            else
                            {
                                $estado="Sin Leer";
                            }

                        $html="<table class='contenidotablaNotamanno' align='center' width='70%'>
                            <tr>
                            <td class='sigma' colspan='1'>El ".$resultado_mensaje[0][2]." a las ".$resultado_mensaje[0][3]." ".$resultado_mensaje[0][1]." escribi&oacute; del tema ".$resultado_mensaje[0][8].":</td>
                            <td class='sigma' align='right' colspan='1'>".$estado."</td>
                            </tr>
                            <tr>
                                <td class='sigma' colspan='2'>".$resultado_mensaje[0][5]."<hr noshade class='hr'></td>
                            </tr>
                            </table>
                            ";



            //$html="hola, tienes mensajes";
            $respuesta = new xajaxResponse();
            $respuesta->addAssign("div_nuevo".$div,"innerHTML",$html2);
            $respuesta->addAssign("mensaje".$div,"innerHTML",'');
            $respuesta->addAssign("div_mensajes".$div,"innerHTML",$html);
            return $respuesta;
        }

    }

    function coordinacion ($codEstudiante, $codDocente)
    {
        require_once("clase/config.class.php");
        $esta_configuracion=new config();
        $configuracion=$esta_configuracion->variable();
        $funcion=new funcionGeneral();
        //Conectarse a la base de datos
        $acceso_db=new dbms($configuracion);
        $enlace=$acceso_db->conectar_db();
        $valor=$acceso_db->verificar_variables($valor);

        require_once("clase/encriptar.class.php");
        $cripto=new encriptar();

        $html=new html();
        $conexion=new multiConexion();
        $accesoOracle=$conexion->estableceConexion(75,$configuracion);
        $accesoGestion=$conexion->estableceConexion(99,$configuracion);

        if (is_resource($enlace))
        {
            $html="<table class='contenidotabla'>";

            $html.="<caption class='sigma'>
                           COMUNICACI&Oacute;N COORDINADOR
                    </caption>";

            $html.="<tr>
                       <td class='centrar' colspan='2'>
                           <b>ADMINISTRACI&Oacute;N DE CONSEJERIAS<BR>UNIVERSIDAD DISTRITAL FRANCISCO JOS&Eacute; DE CALDAS
                           <hr noshade class='hr'></b>
                       </td>
                   </tr>
                     ";
            $busqueda="select est_cod, est_nombre , estado_cod, estado_descripcion, est_cra_cod, cra_nombre, est_ind_cred ";
            $busqueda.=" from acest ";
            $busqueda.=" inner join acestado on est_estado_est= estado_cod ";
            $busqueda.=" inner join accra on est_cra_cod= cra_cod";
            $busqueda.=" where est_estado like '%A%' ";
            //$busqueda.=" and estado_activo like '%S%' ";
            $busqueda.=" and est_cod = '".$codEstudiante."' ";
            //$busqueda.=" and est_ind_cred like '%S%' ";
            $busqueda.=" ORDER BY 1 ";//echo $busqueda;
            //echo $busqueda;exit;
            $resultado_estudiante=$funcion->ejecutarSQL($configuracion, $accesoOracle, $busqueda,"busqueda");

            if(is_array($resultado_estudiante))
                {
                    if(trim($resultado_estudiante[0][6])=='S')
                    {
                        $nivel_coordinador=28;
                    }
                    else
                    {
                        $nivel_coordinador=4;
                    }
                    $coordinador="SELECT DOC_NRO_IDEN, DOC_NOMBRE, DOC_APELLIDO FROM ACCRA ";
                    $coordinador.="INNER JOIN ACDOCENTE ON CRA_EMP_NRO_IDEN=DOC_NRO_IDEN ";
                    $coordinador.="WHERE CRA_COD=".$resultado_estudiante[0][4];

                    $resultado_coordinador=$funcion->ejecutarSQL($configuracion, $accesoOracle, $coordinador,"busqueda");
                    $codCoordinador=$resultado_coordinador[0][0];
                    $nombreCoordinador=$resultado_coordinador[0][1]." ".$resultado_coordinador[0][2];

                    $consulta="select ACC_COD, ACC_COD_EMISOR, to_char(ACC_FECHA,'dd/mon/yyyy'), to_char(ACC_FECHA,'hh12:mi:ss am'), ACC_TIP_COMMENT, ACC_COMMENT, ACC_ESTADO, ACC_TIP_EMISOR, TCM_DES from ACCOMMENT ";
                    $consulta.="INNER JOIN ACTIPCOMMENT ";
                    $consulta.="ON TCM_COD=ACC_TIP_COMMENT ";
                    $consulta.="WHERE";
                    $consulta.=" ((ACC_COD_RECEPTOR = ".$codDocente." AND ACC_TIP_RECEPTOR=30 AND ACC_COD_EMISOR = ".$codCoordinador.")";
                    $consulta.=" OR (ACC_COD_RECEPTOR = ".$codCoordinador." AND ACC_COD_EMISOR =".$codDocente." AND ACC_TIP_EMISOR=30 ))";
                    //$consulta.=" AND ACC_ESTADO LIKE '%L%'";
                    $consulta.=" AND ACC_COD NOT IN (SELECT ACC_COD FROM ACCOMMENT WHERE ACC_COD_RECEPTOR = ".$codDocente." AND ACC_TIP_RECEPTOR=30 AND ACC_COD_EMISOR = ".$codCoordinador." AND ACC_ESTADO LIKE '%P%')";
                    $consulta.=" AND ACC_ANO =(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%A%')";
                    $consulta.=" AND ACC_PER =(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%A%')";
                    $consulta.=" ORDER BY 1 DESC";
                    //echo $consulta;exit;
                    $resultado_mensaje=$funcion->ejecutarSQL($configuracion, $accesoOracle, $consulta,"busqueda");

                    //nuevo mensaje
                    $nuevo="select COUNT (*) from ACCOMMENT ";
                    $nuevo.="WHERE";
                    $nuevo.=" ACC_COD_RECEPTOR = ".$codDocente;
                    $nuevo.=" AND ACC_TIP_RECEPTOR = 30";
                    $nuevo.=" AND ACC_COD_EMISOR = ".$codCoordinador;
                    $nuevo.=" AND ACC_ESTADO LIKE '%P%'";
                    $nuevo.=" AND ACC_ANO =(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%A%')";
                    $nuevo.=" AND ACC_PER =(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%A%')";
                    $resultado_cuenta=$funcion->ejecutarSQL($configuracion, $accesoOracle, $nuevo,"busqueda");
                    if($resultado_cuenta[0][0]>0)
                    {
                        $nuevo="select ACC_COD, to_char(ACC_FECHA,'dd/mon/yyyy'), to_char(ACC_FECHA,'hh12:mi:ss am'), ACC_TIP_COMMENT, ACC_COMMENT, ACC_ESTADO, ACC_TIP_EMISOR, TCM_DES from ACCOMMENT ";
                        $nuevo.="INNER JOIN ACTIPCOMMENT ";
                        $nuevo.="ON TCM_COD=ACC_TIP_COMMENT ";
                        $nuevo.="WHERE";
                        $nuevo.=" ACC_COD_RECEPTOR = ".$codDocente;
                        $nuevo.=" AND ACC_TIP_RECEPTOR = 30";
                        $nuevo.=" AND ACC_COD_EMISOR = ".$codCoordinador;
                        $nuevo.=" AND ACC_ESTADO LIKE '%P%'";
                        $nuevo.=" AND ACC_ANO =(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%A%')";
                        $nuevo.=" AND ACC_PER =(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%A%')";
                        $nuevo.=" ORDER BY 1 DESC";
                        //echo $nuevo;exit;
                        $resultado_nuevo=$funcion->ejecutarSQL($configuracion, $accesoOracle, $nuevo,"busqueda");
                        //echo count($resultado_nuevo); exit;

                        $html.="
                            <tr>
                                <td>
                                    <div id='div_nuevo2'>
                                        <table class='contenidotabla' centrar>
                                            <tr>
                                                <td class='centrar' colspan='2'>Tiene ".$resultado_cuenta[0][0]." mensajes por leer de ".$nombreCoordinador."<br>";
                                                for($n=0;$n<count($resultado_nuevo);$n++)
                                                {
                                                    $html.="<a id='mensajeleer' class='link' onclick='xajax_mensajesPorLeer(".$resultado_nuevo[$n][0].", ".$codDocente.", ".$codEstudiante.", ".$codCoordinador.");'>
                                                            <font size='1'><b>".$resultado_nuevo[$n][1]." a las ".$resultado_nuevo[$n][2]."</b></font></a><br>";
                                                }
                                                $html.="</td>
                                            </tr>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                        ";
                    }
//fin nuevo mensaje
                    if(is_array($resultado_mensaje))
                    {
                        $cadena_sql="SELECT TCM_COD, TCM_DES FROM ACTIPCOMMENT";
                        $resultado_tipo=$funcion->ejecutarSQL($configuracion, $accesoOracle, $cadena_sql,"busqueda");

                        $html.="<tr><td colspan='2'>
                            <table class='contenidotabla centrar' border='0' background=".$configuracion['site'].$configuracion['grafico']."/escudo_fondo.png' style='background-attachment:fixed; background-repeat:no-repeat; background-position:top'>
                                <tr align='center'>
                                    <td class='centrar' colspan='2'>
                                        Escr&iacute;bale un mensaje a ".$nombreCoordinador."
                                    </td>
                                </tr>
                                <tr>
                                    <td class='centrar' colspan='2'>
                                        <textarea id='mensaje2' name='mensaje2' rows='2' cols='90'></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td class='centrar' colspan='2'>
                                    <select class='sigma' id='tipo2' name='tipo' style='width:300px'>
		                        <optgroup>
		                            <option value=''>Seleccione tema del mensaje...</option>";
		                                for($i=0;$i<count($resultado_tipo);$i++)
		                                    {
		                                        if($i==0)
		                                        {
		                                            $html.="
		                                        <option value='".$resultado_tipo[$i][0]."'selected>".$resultado_tipo[$i][1]."</option>";
		                                        }
		                                        else
		                                        {
		                                            $html.="
		                                        <option value='".$resultado_tipo[$i][0]."'>".$resultado_tipo[$i][1]."</option>";
		                                        }
		                                    }
		                                        $html.="
		                            </optgroup>
                                    </select>
                                </td>
                                </tr>
                                <tr>
                                    <td class='centrar' width='50%'>
                                        <input type='button' class='boton' value='Guardar' onclick='xajax_guardarMensaje(document.getElementById(\"mensaje2\").value, ".$codEstudiante.", ".$codDocente.", document.getElementById(\"tipo2\").value, ".$codCoordinador.");mensaje2.value=\"\";'>
                                    </td>
                                    <td class='centrar' width='50%'>
                                        <input type='button' class='boton' value='Borrar' onclick='mensaje2.value=\"\"'>
                                    </td>
                                </tr>
                            </table>
                        <hr noshade class='hr'></td></tr>";

                       $html.="
                            <tr><td colspan='2'><div id='div_mensajes2'>
                                <table class='contenidotabla centrar'>
                                    <tr>
                                        <td class='centrar' colspan='2'>
                                            <b>Mensajes entre Usted y ".$nombreCoordinador."</b>
                                        </td>
                                    </tr></table><table class='contenidotablaNotamanno' align='center' width='70%'>";

                        for($m=0;$m<count($resultado_mensaje);$m++)
                        {
                            if($resultado_mensaje[$m][1]==$codDocente && $resultado_mensaje[$m][7]==30)
                            {
                                $resultado_mensaje[$m][1]="USTED";
                            }
                            else
                            {
                                $resultado_mensaje[$m][1]=$nombreCoordinador;
                            }
                            if(trim($resultado_mensaje[$m][6])=='L')
                            {
                                $estado="Le&iacute;do";
                            }
                            else
                            {
                                $estado="Sin Leer";
                            }

                            if($m%2==0)
                              {
                                $classtr='sigma';
                              }else
                                {
                                  $classtr='sigma_a';
                                }

                            $html.="
                            <tr class='".$classtr."'>
                                <td class='sigma' colspan='1'>El ".$resultado_mensaje[$m][2]." a las ".$resultado_mensaje[$m][3]." ".$resultado_mensaje[$m][1]." escribi&oacute; del tema ".$resultado_mensaje[$m][8].":
                                </td>
                                <td class='sigma' align='right' colspan='1'>".$estado."
                                </td>
                            </tr>
                            <tr class='".$classtr."'>
                                <td class='sigma' colspan='2'>".$resultado_mensaje[$m][5]."</td>
                                </tr>                                
                                ";
                        }
                        $html.="</table></div></td></tr>";

                }
                else
                    {
                        $html.="<tr class='centrar'>
                                    <td class='centrar'>
                                        <b>NO HAY MENSAJES</b>
                                    </td>
                                </tr>";
                        $cadena_sql="SELECT TCM_COD, TCM_DES FROM ACTIPCOMMENT";
                        $resultado_tipo=$funcion->ejecutarSQL($configuracion, $accesoOracle, $cadena_sql,"busqueda");

                        $html.="<tr><td colspan='2'>
                            <table class='contenidotabla centrar' border='0' background=".$configuracion['site'].$configuracion['grafico']."/escudo_fondo.png' style='background-attachment:fixed; background-repeat:no-repeat; background-position:top'>
                                <tr align='center'>
                                    <td class='centrar' colspan='2'>
                                        Escr&iacute;bale un mensaje a ".$nombreCoordinador."
                                    </td>
                                </tr>
                                <tr>
                                    <td class='centrar' colspan='2'>
                                        <textarea id='mensaje2' name='mensaje' rows='2' cols='90'></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td class='centrar' colspan='2'>
                                    <select class='sigma' id='tipo2' name='tipo' style='width:300px'>
		                        <optgroup>
		                            <option value=''>Seleccione tema del mensaje...</option>";
		                                for($i=0;$i<count($resultado_tipo);$i++)
		                                    {
		                                        if($i==0)
		                                        {
		                                            $html.="
		                                        <option value='".$resultado_tipo[$i][0]."'selected>".$resultado_tipo[$i][1]."</option>";
		                                        }
		                                        else
		                                        {
		                                            $html.="
		                                        <option value='".$resultado_tipo[$i][0]."'>".$resultado_tipo[$i][1]."</option>";
		                                        }
		                                    }
		                                        $html.="
		                            </optgroup>
                                    </select>
                                </td>
                                </tr>
                                <tr>
                                    <td class='centrar' width='50%'>
                                        <input class='boton' type='button' value='Guardar' onclick='xajax_guardarMensaje(document.getElementById(\"mensaje2\").value, ".$codEstudiante.", ".$codDocente.", document.getElementById(\"tipo2\").value, ".$codCoordinador.");mensaje2.value=\"\"'>
                                    </td>
                                    <td class='centrar' width='50%'>
                                        <input class='boton' type='button' value='Borrar' onclick='mensaje2.value=\"\"'>
                                    </td>
                                </tr>
                            </table>
                        <hr noshade class='hr'></td></tr>
                        <tr>
                            <td colspan='2'>
                                <div id='div_mensajes2'>
                                </div>
                            </td>
                        </tr>
                                ";

                    }

                }else
                    {
                        $html.="<tr><td colspan='2'><div id='div_mensajes2'>
                            <tr class='centrar'>
                                    <td class='centrar'>
                                        <b>NO SE ENCONTRARON REGISTROS</b>
                                    </td>
                                </tr>
                        </div></td></tr>";
                    }

            $html.="</table>
                    ";

            $respuesta = new xajaxResponse();
            $respuesta->addAssign("mensaje2","innerHTML",'');
            //$respuesta->addAssign("div_mensajes","innerHTML","");
            $respuesta->addAssign("cuerpopestanas","innerHTML",$html);

            $respuesta->addAssign("pestana" . 7, "className", "pestanaseleccionada");
            $respuesta->addAssign("pestanalink" . 7, "className", "pestanaseleccionada");
               //Pongo la class css de las pestañas sin pulsar
            for ($h=1; $h<=7; $h++){
                  if ($h != 7){
                     $respuesta->addAssign("pestanalink" . $h, "className", "pestanainactiva");
                     $respuesta->addAssign("pestana" . $h, "className", "pestanainactiva");
                  }
               }

            return $respuesta;


        }
    }
?>
