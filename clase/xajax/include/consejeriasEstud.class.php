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

    

    function datosEstudianteConsejerias($codEstudiante)
    {
//var_dump($_REQUEST);exit;
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
            $html.="<li id='pestana6' class='pestanainactiva a'>";
            $html.="<a id='pestanalink6' class='link' onclick='xajax_comunicacion(".$codEstudiante.", ".$_REQUEST['codDocente'].");'>";
            $html.="<font size='1'><b>Comunicación</b></font></a></li>";
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

            $respuesta->addAssign("pestana" . 1, "className", "pestanaseleccionada");
            $respuesta->addAssign("pestanalink" . 1, "className", "pestanaseleccionada");
               //Pongo la class css de las pestañas sin pulsar
            for ($h=1; $h<=6; $h++){
                  if ($h != 1){
                     $respuesta->addAssign("pestanalink" . $h, "className", "pestanainactiva");
                     $respuesta->addAssign("pestana" . $h, "className", "pestanainactiva");
                  }
               }

            return $respuesta;

       }
    }


    function comunicacion($codEstudiante, $codDocente)
    {
        //var_dump($_REQUEST);exit;
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
            $cadena_sql="select doc_nombre, doc_apellido, doc_email";
            $cadena_sql.=" from acdocente";
            $cadena_sql.=" where doc_nro_iden=".$codDocente;

            $resultado_nombreDocente=$funcion->ejecutarSQL($configuracion, $accesoOracle, $cadena_sql,"busqueda" );

            $estudiante="select est_cod, est_nombre , estado_cod, estado_descripcion, est_cra_cod, cra_nombre, est_ind_cred ";
            $estudiante.=" from acest ";
            $estudiante.=" inner join acestado on est_estado_est= estado_cod ";
            $estudiante.=" inner join accra on est_cra_cod= cra_cod";
            $estudiante.=" where est_estado like '%A%' ";
            //$estudiante.=" and estado_activo like '%S%' ";
            $estudiante.=" and est_cod = '".$codEstudiante."' ";
            //$cadena_sql.=" and est_ind_cred like '%S%' ";
            $estudiante.=" ORDER BY 1 ";

            $html="<table class='contenidotabla'>";

            $html.="<tr>
                       <td class='centrar' colspan='6'>
                           <b>ADMINISTRACI&Oacute;N DE CONSEJERIAS<BR>UNIVERSIDAD DISTRITAL FRANCISCO JOS&Eacute; DE CALDAS
                           <hr noshade class='hr'></b>
                       </td>
                   </tr>
                     ";
            $resultado_estudiante=$funcion->ejecutarSQL($configuracion, $accesoOracle, $estudiante,"busqueda");
            
            //var_dump($resultado_estudiante);exit;
            if(is_array($resultado_estudiante))
                {
                    //echo $consulta;exit;
                    $datos=array($codDocente, $codEstudiante);
                    $mensajes="select ACC_COD, ACC_COD_EMISOR, to_char(ACC_FECHA,'dd/mon/yyyy'), to_char(ACC_FECHA,'hh12:mi:ss am'), ACC_TIP_COMMENT, ACC_COMMENT, ACC_ESTADO, ACC_TIP_EMISOR, TCM_DES from ACCOMMENT ";
                    $mensajes.="INNER JOIN ACTIPCOMMENT ";
                    $mensajes.="ON TCM_COD=ACC_TIP_COMMENT ";
                    $mensajes.="WHERE";
                    $mensajes.=" ((ACC_COD_RECEPTOR = ".$codDocente." AND ACC_TIP_RECEPTOR=30 AND ACC_COD_EMISOR = ".$codEstudiante.")";
                    $mensajes.=" OR (ACC_COD_RECEPTOR = ".$codEstudiante." AND ACC_COD_EMISOR =".$codDocente." AND ACC_TIP_EMISOR=30))";
                    //$mensajes.=" AND ACC_ESTADO LIKE '%L%'";
                    $mensajes.=" AND ACC_COD NOT IN (SELECT ACC_COD FROM ACCOMMENT WHERE ACC_COD_RECEPTOR = ".$codEstudiante." AND ACC_COD_EMISOR = ".$codDocente." AND ACC_TIP_EMISOR=30 AND ACC_ESTADO LIKE '%P%')";
                    $mensajes.=" AND ACC_ANO =(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%A%')";
                    $mensajes.=" AND ACC_PER =(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%A%')";
                    $mensajes.=" ORDER BY 1 DESC";
                    //echo $mensajes;exit;
                    $resultado_mensaje=$funcion->ejecutarSQL($configuracion, $accesoOracle, $mensajes,"busqueda");
                    //var_dump($resultado_mensaje);exit;
//nuevo mensaje
                    $cuenta_nuevos="select COUNT (*) from ACCOMMENT ";
                    $cuenta_nuevos.="WHERE";
                    $cuenta_nuevos.=" ACC_COD_RECEPTOR = ".$codEstudiante;
                    $cuenta_nuevos.=" AND ACC_COD_EMISOR = ".$codDocente;
                    $cuenta_nuevos.=" AND ACC_TIP_EMISOR = 30";
                    $cuenta_nuevos.=" AND ACC_ESTADO LIKE '%P%'";
                    $cuenta_nuevos.=" AND ACC_ANO =(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%A%')";
                    $cuenta_nuevos.=" AND ACC_PER =(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%A%')";

                    $resultado_cuenta=$funcion->ejecutarSQL($configuracion, $accesoOracle, $cuenta_nuevos,"busqueda");
if($resultado_cuenta[0][0]>0)
{
                    //echo $nuevo;exit;
                    $mensajes_nuevos="select ACC_COD, to_char(ACC_FECHA,'dd/mon/yyyy'), to_char(ACC_FECHA,'hh12:mi:ss am'), ACC_TIP_COMMENT, ACC_COMMENT, ACC_ESTADO from ACCOMMENT ";
                    $mensajes_nuevos.="INNER JOIN ACTIPCOMMENT ";
                    $mensajes_nuevos.="ON TCM_COD=ACC_TIP_COMMENT ";
                    $mensajes_nuevos.="WHERE";
                    $mensajes_nuevos.=" ACC_COD_RECEPTOR = ".$codEstudiante;
                    $mensajes_nuevos.=" AND ACC_COD_EMISOR = ".$codDocente;
                    $mensajes_nuevos.=" AND ACC_TIP_EMISOR = 30";
                    $mensajes_nuevos.=" AND ACC_ESTADO LIKE '%P%'";
                    $mensajes_nuevos.=" AND ACC_ANO =(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%A%')";
                    $mensajes_nuevos.=" AND ACC_PER =(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%A%')";
                    $mensajes_nuevos.=" ORDER BY 1 DESC";

                    $resultado_nuevo=$funcion->ejecutarSQL($configuracion, $accesoOracle, $mensajes_nuevos,"busqueda");
                    //echo count($resultado_nuevo); exit;

    $html.="
<tr>
    <td>
        <div id='div_nuevo'>
            <table class='contenidotabla' centrar>
                <tr>
                    <td class='centrar' colspan='2'>Tiene ".$resultado_cuenta[0][0]." mensajes por leer de su consejero ".$resultado_nombreDocente[0][0]." ".$resultado_nombreDocente[0][1]."<br>";
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
		        $cadena_sql="SELECT TCM_COD, TCM_DES FROM ACTIPCOMMENT";
		        $resultado_tipo=$funcion->ejecutarSQL($configuracion, $accesoOracle, $cadena_sql,"busqueda");    

                    if(is_array($resultado_mensaje))
                    {

$html.="<tr><td colspan='2'>
    <table class='contenidotabla centrar' border='0' background=".$configuracion['site'].$configuracion['grafico']."/escudo_fondo.png' style='background-attachment:fixed; background-repeat:no-repeat; background-position:top'>
        <tr align='center'>
            <td class='centrar' colspan='2'>
                Escr&iacute;bale un mensaje a ".$resultado_nombreDocente[0][0]." ".$resultado_nombreDocente[0][1]."
            </td>
        </tr>
        <tr>
            <td class='centrar' colspan='2'>
                <textarea id='mensaje' name='mensaje' rows='2' cols='90'></textarea>
            </td>
        </tr>
        <tr>
            <td class='centrar' colspan='2'>
            <select class='sigma' id='tipo' style='width:300px'>
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
                <input type='button' value='Guardar' onclick='xajax_guardarMensaje(document.getElementById(\"mensaje\").value, ".$codEstudiante.", ".$codDocente.", document.getElementById(\"tipo\").value);mensaje.value=\"\"'>
            </td>
            <td class='centrar' width='50%'>
                <input type='reset' value='Borrar'>
            </td>
        </tr>
    </table>
<hr noshade class='hr'></td></tr>";


                   $html.="
                            <tr><td colspan='2'><div id='div_mensajes'>
                    <table class='contenidotabla centrar'>
                    <tr>
                       <td class='centrar' colspan='2'>
                           <b>Mensajes entre Usted y ".$resultado_nombreDocente[0][0]." ".$resultado_nombreDocente[0][1]."
                           </b>
                       </td>
                   </tr>";


                    for($m=0;$m<count($resultado_mensaje);$m++)
                    {
                        if($resultado_mensaje[$m][1]==$codEstudiante)
                        {
                            $resultado_mensaje[$m][1]="USTED";
                        }
                        else
                        {
                            $resultado_mensaje[$m][1]=$resultado_nombreDocente[0][0]." ".$resultado_nombreDocente[0][1];
                        }
                            if(trim($resultado_mensaje[$m][6])=='L')
                            {
                                $estado="Ya ha sido le&iacute;do";
                            }
                            else
                            {
                                $estado="No se ha le&iacute;do";
                            }

                        $html.="<tr>
                            <td colspan='1'>El ".$resultado_mensaje[$m][2]." a las ".$resultado_mensaje[$m][3]." ".$resultado_mensaje[$m][1]." escribi&oacute; del tema ".$resultado_mensaje[$m][8].":</td>
                            <td align='right' colspan='1'>".$estado."</td>
                            </tr>
                            <tr>
                                <td colspan='2'>".$resultado_mensaje[$m][5]."<hr noshade class='hr'></td>
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
                        $html.="<tr><td colspan='2'>
                            <table class='contenidotabla centrar' border='0' background=".$configuracion['site'].$configuracion['grafico']."/escudo_fondo.png' style='background-attachment:fixed; background-repeat:no-repeat; background-position:top'>
                                <tr align='center'>
                                    <td class='centrar' colspan='2'>
                                        Escr&iacute;bale un mensaje a ".$resultado_nombreDocente[0][0]." ".$resultado_nombreDocente[0][1]."
                                    </td>
                                </tr>
                                <tr>
                                    <td class='centrar' colspan='2'>
                                        <textarea id='mensaje' name='mensaje' rows='2' cols='90'></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td class='centrar' colspan='2'>
                                    <select class='sigma' id='tipo' style='width:300px'>
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
                                        <input type='button' value='Guardar' onclick='xajax_guardarMensaje(document.getElementById(\"mensaje\").value, ".$codEstudiante.", ".$codDocente.", document.getElementById(\"tipo\").value);mensaje.value=\"\"'>
                                    </td>
                                    <td class='centrar' width='50%'>
                                        <input type='reset' value='Borrar'>
                                    </td>
                                </tr>
                            </table>
                        <hr noshade class='hr'></td></tr>
                        <tr>
                            <td colspan='2'>
                                <div id='div_mensajes'>
                                </div>
                            </td>
                        </tr>";

                    }

                }else
                    {
                        $html.="<tr><td colspan='2'><div id='div_mensajes'>
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
            //$respuesta->addAssign("mensaje","innerHTML",'');
            //$respuesta->addAssign("div_mensajes","innerHTML","");
            $respuesta->addAssign("cuerpopestanas","innerHTML",$html);

            $respuesta->addAssign("pestana" . 1, "className", "pestanaseleccionada");
            $respuesta->addAssign("pestanalink" . 1, "className", "pestanaseleccionada");
               //Pongo la class css de las pestañas sin pulsar
            for ($h=1; $h<=3; $h++){
                  if ($h != 3){
                     $respuesta->addAssign("pestanalink" . $h, "className", "pestanainactiva");
                     $respuesta->addAssign("pestana" . $h, "className", "pestanainactiva");
                  }
               }

            return $respuesta;


        }
    }

        function guardarMensaje($mensaje, $codEstudiante, $codDocente, $tipo)
    {//var_dump($tipo);exit;
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

            $message="insert into accomment ";
            $message.="(ACC_TIP_EMISOR, ACC_COD_EMISOR, ACC_TIP_RECEPTOR, ACC_COD_RECEPTOR, ACC_FECHA, ACC_TIP_COMMENT, ACC_COMMENT, ACC_ESTADO, ACC_ANO, ACC_PER) ";
            $message.="values (".$resultado_nivel[0][0].", ".$codEstudiante.", 30, ".$codDocente;
            $message.=", sysdate, ".$tipo.", '".$mensaje."', 'P',";
            $message.="(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%A%'), (SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%A%'))";
            //echo $message;exit;
            $resultado_guardar=$funcion->ejecutarSQL($configuracion, $accesoOracle, $message,"");

            if($resultado_guardar==true)
            {
            $cadena_sql="select doc_nombre, doc_apellido, doc_email";
            $cadena_sql.=" from acdocente";
            $cadena_sql.=" where doc_nro_iden=".$codDocente;

            $resultado_nombreDocente=$funcion->ejecutarSQL($configuracion, $accesoOracle, $cadena_sql,"busqueda" );

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
                    $mensajes="select ACC_COD, ACC_COD_EMISOR, to_char(ACC_FECHA,'dd/mon/yyyy'), to_char(ACC_FECHA,'hh12:mi:ss am'), ACC_TIP_COMMENT, ACC_COMMENT, ACC_ESTADO, ACC_TIP_EMISOR, TCM_DES from ACCOMMENT ";
                    $mensajes.="INNER JOIN ACTIPCOMMENT ";
                    $mensajes.="ON TCM_COD=ACC_TIP_COMMENT ";
                    $mensajes.="WHERE";
                    $mensajes.=" ((ACC_COD_RECEPTOR = ".$codDocente." AND ACC_TIP_RECEPTOR=30 AND ACC_COD_EMISOR = ".$codEstudiante.")";
                    $mensajes.=" OR (ACC_COD_RECEPTOR = ".$codEstudiante." AND ACC_COD_EMISOR =".$codDocente." AND ACC_TIP_EMISOR=30))";
                    //$mensajes.=" AND ACC_ESTADO LIKE '%L%'";
                    $mensajes.=" AND ACC_COD NOT IN (SELECT ACC_COD FROM ACCOMMENT WHERE ACC_COD_RECEPTOR = ".$codEstudiante." AND ACC_COD_EMISOR = ".$codDocente." AND ACC_TIP_EMISOR=30 AND ACC_ESTADO LIKE '%P%')";
                    $mensajes.=" AND ACC_ANO =(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%A%')";
                    $mensajes.=" AND ACC_PER =(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%A%')";
                    $mensajes.=" ORDER BY 1 DESC";
                    //echo $mensajes;exit;
                    $resultado_mensaje=$funcion->ejecutarSQL($configuracion, $accesoOracle, $mensajes,"busqueda");

                    if(is_array($resultado_mensaje))
                        {

                    $html="<table class='contenidotabla'>
                            <tr>
                               <td class='centrar' colspan='2'>
                                   <b>Mensajes entre Usted y ".$resultado_nombreDocente[0][0]." ".$resultado_nombreDocente[0][1]."
                                   </b>
                               </td>
                           </tr>";

                    for($m=0;$m<count($resultado_mensaje);$m++)
                    {
                        if($resultado_mensaje[$m][1]==$codEstudiante)
                        {
                            $resultado_mensaje[$m][1]="USTED";
                        }
                        else
                        {
                            $resultado_mensaje[$m][1]=$resultado_nombreDocente[0][0]." ".$resultado_nombreDocente[0][1];
                        }
                            if(trim($resultado_mensaje[$m][6])=='L')
                            {
                                $estado="Ya ha sido le&iacute;do";
                            }
                            else
                            {
                                $estado="No se ha le&iacute;do";
                            }

                        $html.="<tr>
                            <td colspan='1'>El ".$resultado_mensaje[$m][2]." a las ".$resultado_mensaje[$m][3]." ".$resultado_mensaje[$m][1]." escribi&oacute; del tema ".$resultado_mensaje[$m][8].":</td>
                            <td align='right' colspan='1'>".$estado."</td>
                            </tr>
                            <tr>
                                <td colspan='2'>".$resultado_mensaje[$m][5]."<hr noshade class='hr'></td>
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
            $respuesta->addAssign("mensaje","innerHTML",'');
            $respuesta->addAssign("div_mensajes","innerHTML",$html);

            return $respuesta;
        }
    }

    function mensajesPorLeer($codmensaje, $codDocente, $codEstudiante)
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
            $cambiar="update ACCOMMENT ";
            $cambiar.="SET ACC_ESTADO = 'L' ";
            $cambiar.="WHERE ACC_COD=".$codmensaje;

            $resultado_cambiar=$funcion->ejecutarSQL($configuracion, $accesoOracle, $cambiar,"");


                    //echo $nuevo;exit;

            $nuevo="select COUNT (*) from ACCOMMENT ";
            $nuevo.="WHERE";
            $nuevo.=" ACC_COD_RECEPTOR = ".$codEstudiante;
            $nuevo.=" AND ACC_COD_EMISOR = ".$codDocente;
            $nuevo.=" AND ACC_TIP_EMISOR = 30";
            $nuevo.=" AND ACC_ESTADO LIKE '%P%'";
            $nuevo.=" AND ACC_ANO =(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%A%')";
            $nuevo.=" AND ACC_PER =(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%A%')";
            $resultado_cuenta=$funcion->ejecutarSQL($configuracion, $accesoOracle, $nuevo,"busqueda");
            if($resultado_cuenta[0][0]>0)
            {
                $cadena_sql="select doc_nombre, doc_apellido, doc_email";
                $cadena_sql.=" from acdocente";
                $cadena_sql.=" where doc_nro_iden=".$codDocente;

                $resultado_nombreDocente=$funcion->ejecutarSQL($configuracion, $accesoOracle, $cadena_sql,"busqueda" );

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

                $nuevo="select ACC_COD, to_char(ACC_FECHA,'dd/mon/yyyy'), to_char(ACC_FECHA,'hh12:mi:ss am'), ACC_TIP_COMMENT, ACC_COMMENT, ACC_ESTADO, ACC_TIP_EMISOR, TCM_DES from ACCOMMENT ";
                $nuevo.="INNER JOIN ACTIPCOMMENT ";
                $nuevo.="ON TCM_COD=ACC_TIP_COMMENT ";
                $nuevo.="WHERE";
                $nuevo.=" ACC_COD_RECEPTOR = ".$codEstudiante;
                $nuevo.=" AND ACC_COD_EMISOR = ".$codDocente;
                $nuevo.=" AND ACC_TIP_EMISOR = 30";
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
                                <td class='centrar' colspan='2'>Tiene ".$resultado_cuenta[0][0]." mensajes por leer de ".$resultado_nombreDocente[0][0]." ".$resultado_nombreDocente[0][1]."<br>";
                                for($n=0;$n<count($resultado_nuevo);$n++)
                                {
                                    $html2.="<a id='mensajeleer' class='link' onclick='xajax_mensajesPorLeer(".$resultado_nuevo[$n][0].", ".$codDocente.", ".$codEstudiante.");'>
                                    <font size='1'><b>".$resultado_nuevo[$n][1]." a las ".$resultado_nuevo[$n][2]."</b></font></a><br>";
                                }
                                $html2.="</td>
                            </tr>
                        </table>";
            }

            $consulta="select ACC_COD, ACC_COD_EMISOR, to_char(ACC_FECHA,'dd/mon/yyyy'), to_char(ACC_FECHA,'hh12:mi:ss am'), ACC_TIP_COMMENT, ACC_COMMENT, ACC_ESTADO, ACC_TIP_EMISOR, TCM_DES from ACCOMMENT ";
            $consulta.="INNER JOIN ACTIPCOMMENT ";
            $consulta.="ON TCM_COD=ACC_TIP_COMMENT ";
            $consulta.="WHERE ACC_COD =".$codmensaje;
            //echo $consulta;exit;
            $resultado_mensaje=$funcion->ejecutarSQL($configuracion, $accesoOracle, $consulta,"busqueda");


            if($resultado_mensaje[0][1]==$codEstudiante)
            {
                $resultado_mensaje[0][1]="USTED";
            }
            else
            {
                $resultado_mensaje[0][1]=$resultado_nombreDocente[0][0]." ".$resultado_nombreDocente[0][1];
            }

            if(trim($resultado_mensaje[0][6])=='L')
            {
                $estado="Mensaje le&iacute;do";
            }
            else
            {
                $estado="No se ha le&iacute;do";
            }

            $html="
            <table class='contenidotabla centrar'>
                <tr>
                    <td colspan='1'>El ".$resultado_mensaje[0][2]." a las ".$resultado_mensaje[0][3]." ".$resultado_mensaje[0][1]." escribi&oacute; del tema ".$resultado_mensaje[0][8].":</td>
                    <td align='right' colspan='1'>".$estado."</td>
                </tr>
                <tr>
                    <td colspan='2'>".$resultado_mensaje[0][5]."<hr noshade class='hr'></td>
                </tr>
            </table>
                ";



            //$html="hola, tienes mensajes";
            $respuesta = new xajaxResponse();
            $respuesta->addAssign("div_nuevo","innerHTML",$html2);
            $respuesta->addAssign("mensaje","innerHTML",'');
            $respuesta->addAssign("div_mensajes","innerHTML",$html);

            return $respuesta;
        }

    }
?>
