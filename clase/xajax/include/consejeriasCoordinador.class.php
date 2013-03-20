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

    

    function comunicacion($codCoordinador, $codDocente, $nivelCoordinador)
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

            $html="<table class='contenidotabla'>";

            $html.="<tr>
                       <td class='centrar' colspan='6'>
                           <b>ADMINISTRACI&Oacute;N DE CONSEJERIAS<BR>UNIVERSIDAD DISTRITAL FRANCISCO JOS&Eacute; DE CALDAS
                           <hr noshade class='hr'></b>
                       </td>
                   </tr>
                     ";
            //var_dump($resultado_estudiante);exit;
            if(isset($codDocente, $codCoordinador))
                {
                    //echo $consulta;exit;
                    $datos=array($codDocente, $codCoordinador);
                    $mensajes="select ACC_COD, ACC_COD_EMISOR, to_char(ACC_FECHA,'dd/mon/yyyy'), to_char(ACC_FECHA,'hh12:mi:ss am'), ACC_TIP_COMMENT, ACC_COMMENT, ACC_ESTADO, ACC_TIP_EMISOR, TCM_DES from ACCOMMENT ";
                    $mensajes.="INNER JOIN ACTIPCOMMENT ";
                    $mensajes.="ON TCM_COD=ACC_TIP_COMMENT ";
                    $mensajes.="WHERE";
                    $mensajes.=" ((ACC_COD_RECEPTOR = ".$codDocente." AND ACC_TIP_RECEPTOR=30 AND ACC_COD_EMISOR = ".$codCoordinador.")";
                    $mensajes.=" OR (ACC_COD_RECEPTOR = ".$codCoordinador." AND ACC_COD_EMISOR =".$codDocente." AND ACC_TIP_EMISOR=30))";
                    //$mensajes.=" AND ACC_ESTADO LIKE '%L%'";
                    $mensajes.=" AND ACC_COD NOT IN (SELECT ACC_COD FROM ACCOMMENT WHERE ACC_COD_RECEPTOR = ".$codCoordinador." AND ACC_COD_EMISOR = ".$codDocente." AND ACC_TIP_EMISOR=30 AND ACC_ESTADO LIKE '%P%')";
                    $mensajes.=" AND ACC_ANO =(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%A%')";
                    $mensajes.=" AND ACC_PER =(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%A%')";
                    $mensajes.=" ORDER BY 1 DESC";
                    //echo $mensajes;exit;
                    $resultado_mensaje=$funcion->ejecutarSQL($configuracion, $accesoOracle, $mensajes,"busqueda");
                    //var_dump($resultado_mensaje);exit;
//nuevo mensaje
                    $cuenta_nuevos="select COUNT (*) from ACCOMMENT ";
                    $cuenta_nuevos.="WHERE";
                    $cuenta_nuevos.=" ACC_COD_RECEPTOR = ".$codCoordinador;
                    $cuenta_nuevos.=" AND ACC_COD_EMISOR = ".$codDocente;
                    $cuenta_nuevos.=" AND ACC_TIP_EMISOR = 30";
                    $cuenta_nuevos.=" AND ACC_ESTADO LIKE '%P%'";
                    $cuenta_nuevos.=" AND ACC_ANO =(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%A%')";
                    $cuenta_nuevos.=" AND ACC_PER =(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%A%')";

                    $resultado_cuenta=$funcion->ejecutarSQL($configuracion, $accesoOracle, $cuenta_nuevos,"busqueda");
if($resultado_cuenta[0][0]>0)
{
                    //echo $nuevo;exit;
                    $mensajes_nuevos="select ACC_COD, to_char(ACC_FECHA,'dd/mon/yyyy'), to_char(ACC_FECHA,'hh12:mi:ss am'), ACC_TIP_COMMENT, ACC_COMMENT, ACC_ESTADO, ACC_TIP_EMISOR, TCM_DES from ACCOMMENT ";
                    $mensajes_nuevos.="INNER JOIN ACTIPCOMMENT ";
                    $mensajes_nuevos.="ON TCM_COD=ACC_TIP_COMMENT ";
                    $mensajes_nuevos.="WHERE";
                    $mensajes_nuevos.=" ACC_COD_RECEPTOR = ".$codCoordinador;
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
                    <td class='centrar' colspan='2'>Tiene ".$resultado_cuenta[0][0]." mensajes por leer del Consejero ".$resultado_nombreDocente[0][0]." ".$resultado_nombreDocente[0][1]."<br>";
                    for($n=0;$n<count($resultado_nuevo);$n++)
                    {
                        $html.="<a id='mensajeleer' class='link' onclick='xajax_mensajesPorLeer(".$resultado_nuevo[$n][0].", ".$codDocente.", ".$codCoordinador.", ".$nivelCoordinador.");'>
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
                Escr&iacute;bale un mensaje al Consejero ".$resultado_nombreDocente[0][0]." ".$resultado_nombreDocente[0][1]."
            </td>
        </tr>
        <tr>
            <td class='centrar' colspan='2'>
                <textarea id='mensaje' name='mensaje' rows='2' cols='90'></textarea>
            </td>
        </tr>
        <tr>
            <td class='centrar' colspan='2'>
            <select class='sigma' id='tipo' name='tipo' style='width:300px'>
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
                <input type='button' value='Guardar' onclick='xajax_guardarMensaje(document.getElementById(\"mensaje\").value, ".$codCoordinador.", ".$codDocente.", document.getElementById(\"tipo\").value, ".$nivelCoordinador.");mensaje.value=\"\"'>
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
                        if($resultado_mensaje[$m][1]==$codDocente && $resultado_mensaje[$m][7]==30)
                        {
                            $resultado_mensaje[$m][1]=$resultado_nombreDocente[0][0]." ".$resultado_nombreDocente[0][1];
                        }
                        else
                        {
                            $resultado_mensaje[$m][1]="USTED";
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
                        $cadena_sql="SELECT TCM_COD, TCM_DES FROM ACTIPCOMMENT";
                        $resultado_tipo=$funcion->ejecutarSQL($configuracion, $accesoOracle, $cadena_sql,"busqueda");

                        $html.="<tr><td colspan='2'>
                            <table class='contenidotabla centrar' border='0' background=".$configuracion['site'].$configuracion['grafico']."/escudo_fondo.png' style='background-attachment:fixed; background-repeat:no-repeat; background-position:top'>
                                <tr align='center'>
                                    <td class='centrar' colspan='2'>
                                        Escr&iacute;bale un mensaje al Consejero ".$resultado_nombreDocente[0][0]." ".$resultado_nombreDocente[0][1]."
                                    </td>
                                </tr>
                                <tr>
                                    <td class='centrar' colspan='2'>
                                        <textarea id='mensaje' name='mensaje' rows='2' cols='90'></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td class='centrar' colspan='2'>
                                    <select class='sigma' id='tipo' name='tipo' style='width:300px'>
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
                                        <input type='button' value='Guardar' onclick='xajax_guardarMensaje(document.getElementById(\"mensaje\").value, ".$codCoordinador.", ".$codDocente.", document.getElementById(\"tipo\").value, ".$nivelCoordinador.");mensaje.value=\"\"'>
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
            $respuesta->addAssign("mensaje","innerHTML",'');
            //$respuesta->addAssign("div_mensajes","innerHTML","");
            $respuesta->addAssign("cuerpopestanas","innerHTML",$html);

            $respuesta->addAssign("pestana" . 1, "className", "pestanaseleccionada");
            $respuesta->addAssign("pestanalink" . 1, "className", "pestanaseleccionada");
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

        function guardarMensaje($mensaje, $codCoordinador, $codDocente, $tipo, $nivelCoordinador)
    {//var_dump($mensaje);exit;
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
            //$nivel="select cla_tipo_usu from geclaves where cla_codigo=".$codCoordinador;
            //$resultado_nivel=$funcion->ejecutarSQL($configuracion, $accesoOracle, $nivel,"busqueda");

            $message="insert into accomment ";
            $message.="(ACC_TIP_EMISOR, ACC_COD_EMISOR, ACC_TIP_RECEPTOR, ACC_COD_RECEPTOR, ACC_FECHA, ACC_TIP_COMMENT, ACC_COMMENT, ACC_ESTADO, ACC_ANO, ACC_PER) ";
            $message.="values (".$nivelCoordinador.", ".$codCoordinador.", 30, ".$codDocente;
            $message.=", sysdate, ".$tipo.", '".$mensaje."', 'P',";
            $message.="(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%A%'), (SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%A%'))";
            $resultado_guardar=$funcion->ejecutarSQL($configuracion, $accesoOracle, $message,"");

            if($resultado_guardar==true)
            {
            $cadena_sql="select doc_nombre, doc_apellido, doc_email";
            $cadena_sql.=" from acdocente";
            $cadena_sql.=" where doc_nro_iden=".$codDocente;

            $resultado_nombreDocente=$funcion->ejecutarSQL($configuracion, $accesoOracle, $cadena_sql,"busqueda" );


            if(is_array($resultado_nombreDocente))
                {
                    $mensajes="select ACC_COD, ACC_COD_EMISOR, to_char(ACC_FECHA,'dd/mon/yyyy'), to_char(ACC_FECHA,'hh12:mi:ss am'), ACC_TIP_COMMENT, ACC_COMMENT, ACC_ESTADO, ACC_TIP_EMISOR, TCM_DES from ACCOMMENT ";
                    $mensajes.="INNER JOIN ACTIPCOMMENT ";
                    $mensajes.="ON TCM_COD=ACC_TIP_COMMENT ";
                    $mensajes.="WHERE";
                    $mensajes.=" ((ACC_COD_RECEPTOR = ".$codDocente." AND ACC_TIP_RECEPTOR=30 AND ACC_COD_EMISOR = ".$codCoordinador.")";
                    $mensajes.=" OR (ACC_COD_RECEPTOR = ".$codCoordinador." AND ACC_COD_EMISOR =".$codDocente." AND ACC_TIP_EMISOR=30))";
                    //$mensajes.=" AND ACC_ESTADO LIKE '%L%'";
                    $mensajes.=" AND ACC_COD NOT IN (SELECT ACC_COD FROM ACCOMMENT WHERE ACC_COD_RECEPTOR = ".$codCoordinador." AND ACC_COD_EMISOR = ".$codDocente." AND ACC_TIP_EMISOR=30 AND ACC_ESTADO LIKE '%P%')";
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
                        if($resultado_mensaje[$m][1]==$codDocente && $resultado_mensaje[$m][7]==30)
                        {
                            $resultado_mensaje[$m][1]=$resultado_nombreDocente[0][0]." ".$resultado_nombreDocente[0][1];
                        }
                        else
                        {
                            $resultado_mensaje[$m][1]="USTED";
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

    function mensajesPorLeer($codmensaje, $codDocente, $codCoordinador, $nivelCoordinador)
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
            $nuevo.=" ACC_COD_RECEPTOR = ".$codCoordinador;
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

                $nuevo="select ACC_COD, to_char(ACC_FECHA,'dd/mon/yyyy'), to_char(ACC_FECHA,'hh12:mi:ss am'), ACC_TIP_COMMENT, ACC_COMMENT, ACC_ESTADO, ACC_TIP_EMISOR, TCM_DES from ACCOMMENT ";
                $nuevo.="INNER JOIN ACTIPCOMMENT ";
                $nuevo.="ON TCM_COD=ACC_TIP_COMMENT ";
                $nuevo.="WHERE";
                $nuevo.=" ACC_COD_RECEPTOR = ".$codCoordinador;
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
                                    $html2.="<a id='mensajeleer' class='link' onclick='xajax_mensajesPorLeer(".$resultado_nuevo[$n][0].", ".$codDocente.", ".$codCoordinador.", ".$nivelCoordinador.");'>
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


            if($resultado_mensaje[0][1]==$codDocente && $resultado_mensaje[0][7]==30)
            {
                $resultado_mensaje[0][1]=$resultado_nombreDocente[0][0]." ".$resultado_nombreDocente[0][1];
            }
            else
            {
                $resultado_mensaje[0][1]="USTED";
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

    function estudiantes($codCoordinador, $codDocente, $nivelCoordinador)
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

            $cadena_sql="select ECO_EST_COD,ECO_DOC_NRO_IDENT, ";
            $cadena_sql.=" est_cod, est_nombre , estado_cod, estado_descripcion ";
            $cadena_sql.="from ACESTUDIANTECONSEJERO ";
            $cadena_sql.=" INNER JOIN ACEST ON EST_COD=ECO_EST_COD";
            $cadena_sql.=" inner join acestado on est_estado_est= estado_cod";
            $cadena_sql.=" where ECO_DOC_NRO_IDENT='".$codDocente."' and ECO_ESTADO='A'";
            $cadena_sql.=" ORDER BY ECO_EST_COD";

//            $cadena_sql="select docenteEstudiante_codEstudiante,docenteEstudiante_docDocente from ".$configuracion["prefijo"]."docenteEstudiante ";
//            $cadena_sql.=" where docenteEstudiante_docDocente='".$codDocente."' and docenteEstudiante_estado='1'";
            //echo $cadena_sql;exit;
            $resultado_estudiantesAsociados=$funcion->ejecutarSQL($configuracion, $accesoOracle, $cadena_sql,"busqueda" );
            //var_dump($resultado_estudiantesAsociados);exit;
            $cadena_sql="select doc_nombre, doc_apellido, doc_email";
            $cadena_sql.=" from acdocente";
            $cadena_sql.=" where doc_nro_iden=".$codDocente;
            $resultado_nombreDocente=$funcion->ejecutarSQL($configuracion, $accesoOracle, $cadena_sql,"busqueda" );


            if (is_array($resultado_estudiantesAsociados))
            {


            $html="<table class='contenidotabla centrar'>
<tr class='sigma_a'>
        <th class='sigma centrar' colspan='6'>
            ESTUDIANTES ASOCIADOS AL DOCENTE ".$resultado_nombreDocente[0][0]." ".$resultado_nombreDocente[0][1]."
        </th>
    </tr>
    <tr class='sigma_a'>
        <th class='sigma centrar'>
            Nro
        </th>
        <th class='sigma centrar'>
            C&oacute;digo
        </th>
        <th class='sigma centrar'>
            Nombre
        </th>
        <th class='sigma centrar'>
            Estado
        </th>
    </tr>";
                $p=0;
                    if($p%2==0)
                        {
                            $clasetr="sigma";
                        }else
                            {
                                $clasetr="";
                            }
            for($i=0;$i<count($resultado_estudiantesAsociados);$i++)
            {
//                $cadena_sql="select est_cod, est_nombre , estado_cod, estado_descripcion";
//                $cadena_sql.=" from acest ";
//                $cadena_sql.=" inner join acestado on est_estado_est= estado_cod ";
//                $cadena_sql.=" where est_estado like '%A%' ";
//                $cadena_sql.=" and estado_activo like '%S%' ";
//                $cadena_sql.=" and est_cod = '".$resultado_estudiantesAsociados[$i][0]."' ";
//                //$cadena_sql.=" and est_ind_cred like '%S%' ";
//                $cadena_sql.=" ORDER BY 1 ";
//
//                $resultado_datosEstudiantes=$funcion->ejecutarSQL($configuracion, $accesoOracle, $cadena_sql,'busqueda' );
                    if($p%2==0)
                        {
                            $clasetr="sigma";
                        }else
                            {
                                $clasetr="";
                            }

    $html.="<tr class='".$clasetr."'>
        <td class='cuadro_plano centrar'>
            ".++$p."
        </td>
        <td class='cuadro_plano centrar'>
            ".$resultado_estudiantesAsociados[$i][2]."
        </td>
        <td class='cuadro_plano'>
        <a id='codEstudiante' class='link' onclick='xajax_datosEstudianteConsejerias(".$resultado_estudiantesAsociados[$i][2].", ".$codDocente.", ".$nivelCoordinador.", ".$codCoordinador.");'>
            ".$resultado_estudiantesAsociados[$i][3]."
        </a></td>
        <td class='cuadro_plano'>
            ".$resultado_estudiantesAsociados[$i][5]."
        </td>
    </tr>";
            }
$html.="</table>";
         }else
             {
             $html="
             <table class='contenidotabla centrar'>
                <tr class='sigma'>
                    <th class='sigma centrar'>
                        EL DOCENTE ".$resultado_nombreDocente[0][0]." ".$resultado_nombreDocente[0][1]." NO TIENE ESTUDIANTES ASOCIADOS PARA CONSEJERIAS
                    </th>
                </tr>
             </table>";
             }
    }

            $respuesta = new xajaxResponse();
            $respuesta->addAssign("mensaje","innerHTML",'');
            //$respuesta->addAssign("div_mensajes","innerHTML","");
            $respuesta->addAssign("cuerpopestanas","innerHTML",$html);

            $respuesta->addAssign("pestana" . 1, "className", "pestanaseleccionada");
            $respuesta->addAssign("pestanalink" . 1, "className", "pestanaseleccionada");
               //Pongo la class css de las pestañas sin pulsar
            for ($h=1; $h<=7; $h++){
                  if ($h != 7){
                     $respuesta->addAssign("pestanalink" . $h, "className", "pestanainactiva");
                     $respuesta->addAssign("pestana" . $h, "className", "pestanainactiva");
                  }
               }

            return $respuesta;




            $respuesta = new xajaxResponse();
            $respuesta->addAssign("mensaje","innerHTML",'');
            //$respuesta->addAssign("div_mensajes","innerHTML","");
            $respuesta->addAssign("cuerpopestanas","innerHTML",$html);

            $respuesta->addAssign("pestana" . 2, "className", "pestanaseleccionada");
            $respuesta->addAssign("pestanalink" . 2, "className", "pestanaseleccionada");
               //Pongo la class css de las pestañas sin pulsar
            for ($h=1; $h<=3; $h++){
                  if ($h != 3){
                     $respuesta->addAssign("pestanalink" . $h, "className", "pestanainactiva");
                     $respuesta->addAssign("pestana" . $h, "className", "pestanainactiva");
                  }
               }

            return $respuesta;
      
    }

        function datosEstudianteConsejerias($codEstudiante,$codDocente,$nivelCoordinador,$codCoordinador)
    {
//var_dump($_REQUEST);
//echo $codEstudiante."<br>".$codDocente."<br>".$nivelCoordinador."<br>".$codEstudiante;exit;
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
            $html.="<li id='pestana3' class='pestanainactiva a'>";
            $html.="<a id='pestanalink3' class='link' onclick='xajax_datosEstudianteConsejerias(".$codEstudiante.");'>";
            $html.="<font size='1'><b>Datos</b></font></a></li>";
            $html.="<li id='pestana4' class='pestanainactiva a'>";
            $html.="<a id='pestanalink4' class='link' onclick='xajax_notas(".$codEstudiante.");'>";
            $html.="<font size='1'><b>Notas</b></font></a></li>";
            $html.="<li id='pestana5' class='pestanainactiva a'>";
            $html.="<a id='pestanalink5' class='link' onclick='xajax_inscripciones(".$codEstudiante.");'>";
            $html.="<font size='1'><b>Inscripciones</b></font></a></li>";
            $html.="<li id='pestana6' class='pestanainactiva a'>";
            $html.="<a id='pestanalink6' class='link' onclick='xajax_notas_parciales(".$codEstudiante.");'>";
            $html.="<font size='1'><b>Notas Parciales</b></font></a></li>";
//            $html.="<li id='pestana7' class='pestanainactiva a'>";
//            $html.="<a id='pestanalink7' class='link' onclick='xajax_pe(".$codEstudiante.");'>";
//            $html.="<font size='1'><b>Plan de estudios</b></font></a></li>";
//            $html.="<li id='pestana8' class='pestanainactiva a'>";
//            $html.="<a id='pestanalink8' class='link' onclick='xajax_comunicacionEstud(".$codEstudiante.", ".$_REQUEST['codDocente'].");'>";
//            $html.="<font size='1'><b>Comunicación</b></font></a></li>";
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
            $respuesta->addAssign("cuerpopestanas","innerHTML",$html);

            $respuesta->addAssign("pestana" . 3, "className", "pestanaseleccionada");
            $respuesta->addAssign("pestanalink" . 3, "className", "pestanaseleccionada");
               //Pongo la class css de las pestañas sin pulsar
            for ($h=3; $h<=9; $h++){
                  if ($h != 3){
                     $respuesta->addAssign("pestanalink" . $h, "className", "pestanainactiva");
                     $respuesta->addAssign("pestana" . $h, "className", "pestanainactiva");
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
            $html="<div class='pestanas'>";
            $html.="<ul>";
            $html.="<li id='pestana3' class='pestanainactiva a'>";
            $html.="<a id='pestanalink3' class='link' onclick='xajax_datosEstudianteConsejerias(".$codEstudiante.");'>";
            $html.="<font size='1'><b>Datos</b></font></a></li>";
            $html.="<li id='pestana4' class='pestanainactiva a'>";
            $html.="<a id='pestanalink4' class='link' onclick='xajax_notas(".$codEstudiante.");'>";
            $html.="<font size='1'><b>Notas</b></font></a></li>";
            $html.="<li id='pestana5' class='pestanainactiva a'>";
            $html.="<a id='pestanalink5' class='link' onclick='xajax_inscripciones(".$codEstudiante.");'>";
            $html.="<font size='1'><b>Inscripciones</b></font></a></li>";
            $html.="<li id='pestana6' class='pestanainactiva a'>";
            $html.="<a id='pestanalink6' class='link' onclick='xajax_notas_parciales(".$codEstudiante.");'>";
            $html.="<font size='1'><b>Notas Parciales</b></font></a></li>";
//            $html.="<li id='pestana7' class='pestanainactiva a'>";
//            $html.="<a id='pestanalink7' class='link' onclick='xajax_pe(".$codEstudiante.");'>";
//            $html.="<font size='1'><b>Plan de estudios</b></font></a></li>";
//            $html.="<li id='pestana8' class='pestanainactiva a'>";
//            $html.="<a id='pestanalink8' class='link' onclick='xajax_comunicacionEstud(".$codEstudiante.", ".$_REQUEST['codDocente'].");'>";
//            $html.="<font size='1'><b>Comunicación</b></font></a></li>";
            $html.="</ul>";
            $html.="</div>";
            $html.="<div id='cuerpopestanas' class='cuerpopestanas'>";
            $html.="<table class='contenidotabla'>";

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

            $respuesta->addAssign("pestana" . 4, "className", "pestanaseleccionada");
            $respuesta->addAssign("pestanalink" . 4, "className", "pestanaseleccionada");
               //Pongo la class css de las pestañas sin pulsar
            for ($h=3; $h<=8; $h++){
                  if ($h != 4){
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
            $html="<div class='pestanas'>";
            $html.="<ul>";
            $html.="<li id='pestana3' class='pestanainactiva a'>";
            $html.="<a id='pestanalink3' class='link' onclick='xajax_datosEstudianteConsejerias(".$codEstudiante.");'>";
            $html.="<font size='1'><b>Datos</b></font></a></li>";
            $html.="<li id='pestana4' class='pestanainactiva a'>";
            $html.="<a id='pestanalink4' class='link' onclick='xajax_notas(".$codEstudiante.");'>";
            $html.="<font size='1'><b>Notas</b></font></a></li>";
            $html.="<li id='pestana5' class='pestanainactiva a'>";
            $html.="<a id='pestanalink5' class='link' onclick='xajax_inscripciones(".$codEstudiante.");'>";
            $html.="<font size='1'><b>Inscripciones</b></font></a></li>";
            $html.="<li id='pestana6' class='pestanainactiva a'>";
            $html.="<a id='pestanalink6' class='link' onclick='xajax_notas_parciales(".$codEstudiante.");'>";
            $html.="<font size='1'><b>Notas Parciales</b></font></a></li>";
//            $html.="<li id='pestana7' class='pestanainactiva a'>";
//            $html.="<a id='pestanalink7' class='link' onclick='xajax_pe(".$codEstudiante.");'>";
//            $html.="<font size='1'><b>Plan de estudios</b></font></a></li>";
//            $html.="<li id='pestana8' class='pestanainactiva a'>";
//            $html.="<a id='pestanalink8' class='link' onclick='xajax_comunicacionEstud(".$codEstudiante.", ".$_REQUEST['codDocente'].");'>";
//            $html.="<font size='1'><b>Comunicación</b></font></a></li>";
            $html.="</ul>";
            $html.="</div>";
            $html.="<div id='cuerpopestanas' class='cuerpopestanas'>";
            $html.="<table class='contenidotabla'>";

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
                        $html.="<tr class='cuadro_brownOscuro centrar'>
                        <th class='sigma' align='center'><b>C&Oacute;DIGO</b></th>
                                <th class='sigma' align='center'><b>NOMBRE</b></th>
                                <th class='sigma' align='center'><b>GRUPO</b></th>
                                <th class='sigma' align='center'><b>CLASIFICACI&Oacute;N</b></th>
                                <th class='sigma' align='center'><b>LUNES</b></th>
                                <th class='sigma' align='center'><b>MARTES</b></th>
                                <th class='sigma' align='center'><b>MIERCOLES</b></th>
                                <th class='sigma' align='center'><b>JUEVES</b></th>
                                <th class='sigma' align='center'><b>VIERNES</b></th>
                                <th class='sigma' align='center'><b>SABADO</b></th>
                                <th class='sigma' align='center'><b>DOMINGO</b></th>
                        </tr>";

                        for($j=0;$j<count($resultado);$j++)
                        {
                            $busqueda2="SELECT id_nivel, PEE.id_clasificacion, horasDirecto, horasCooperativo,clasificacion_nombre, clasificacion_abrev";
                            $busqueda2.=" FROM sga_planEstudio_espacio PEE ";
                            $busqueda2.=" INNER JOIN sga_espacio_clasificacion CL on PEE.id_clasificacion=CL.id_clasificacion ";
                            $busqueda2.=" WHERE id_planEstudio = '".$resultado[$j][3]."'";
                            $busqueda2.=" AND id_espacio = '".$resultado[$j][0]."'";

                            $resultado2=$funcion->ejecutarSQL($configuracion, $accesoGestion, $busqueda2,"busqueda");

                            $html.="<tr>
                                    <td class='cuadro_plano centrar'>
                                        ".$resultado[$j][0]."
                                    </td>
                                    <td class='cuadro_plano'>
                                        ".$resultado[$j][2]."
                                    </td>
                                    <td class='cuadro_plano centrar'>
                                        ".$resultado[$j][1]."
                                    </td>
                                    <td class='cuadro_plano centrar'>
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
                                        $html.="<td class='cuadro_plano centrar'>";

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
                                <th class='sigma' align='center'><b>C&Oacute;DIGO</b></th>
                                <th class='sigma' align='center'><b>NOMBRE</b></th>
                                <th class='sigma' align='center'><b>GRUPO</b></th>
                                <th class='sigma' align='center'><b>LUNES</b></th>
                                <th class='sigma' align='center'><b>MARTES</b></th>
                                <th class='sigma' align='center'><b>MIERCOLES</b></th>
                                <th class='sigma' align='center'><b>JUEVES</b></th>
                                <th class='sigma' align='center'><b>VIERNES</b></th>
                                <th class='sigma' align='center'><b>SABADO</b></th>
                                <th class='sigma' align='center'><b>DOMINGO</b></th>
                                </tr>";

                                for($j=0;$j<count($resultado);$j++)
                                {
                                    $html.="<tr>
                                            <td class='cuadro_plano centrar'>
                                                ".$resultado[$j][0]."
                                            </td>
                                            <td class='cuadro_plano '>
                                                ".$resultado[$j][2]."
                                            </td>
                                            <td class='cuadro_plano centrar'>
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
                                                $html.="<td class='cuadro_plano centrar'>";

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
                                    <td class='centrar'>
                                        <b>NO SE ENCONTRARON REGISTROS DE INSCRIPCIONES</b>
                                    </td>
                                </tr>";
                    }

            $html.="</table>
                        </div>";


            $respuesta = new xajaxResponse();
            $respuesta->addAssign("cuerpopestanas","innerHTML",$html);

            $respuesta->addAssign("pestana" . 5, "className", "pestanaseleccionada");
            $respuesta->addAssign("pestanalink" . 5, "className", "pestanaseleccionada");
               //Pongo la class css de las pestañas sin pulsar
            for ($h=3; $h<=8; $h++){
                  if ($h != 5){
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
            $html="<div class='pestanas'>";
            $html.="<ul>";
            $html.="<li id='pestana3' class='pestanainactiva a'>";
            $html.="<a id='pestanalink3' class='link' onclick='xajax_datosEstudianteConsejerias(".$codEstudiante.");'>";
            $html.="<font size='1'><b>Datos</b></font></a></li>";
            $html.="<li id='pestana4' class='pestanainactiva a'>";
            $html.="<a id='pestanalink4' class='link' onclick='xajax_notas(".$codEstudiante.");'>";
            $html.="<font size='1'><b>Notas</b></font></a></li>";
            $html.="<li id='pestana5' class='pestanainactiva a'>";
            $html.="<a id='pestanalink5' class='link' onclick='xajax_inscripciones(".$codEstudiante.");'>";
            $html.="<font size='1'><b>Inscripciones</b></font></a></li>";
            $html.="<li id='pestana6' class='pestanainactiva a'>";
            $html.="<a id='pestanalink6' class='link' onclick='xajax_notas_parciales(".$codEstudiante.");'>";
            $html.="<font size='1'><b>Notas Parciales</b></font></a></li>";
//            $html.="<li id='pestana7' class='pestanainactiva a'>";
//            $html.="<a id='pestanalink7' class='link' onclick='xajax_pe(".$codEstudiante.");'>";
//            $html.="<font size='1'><b>Plan de estudios</b></font></a></li>";
//            $html.="<li id='pestana8' class='pestanainactiva a'>";
//            $html.="<a id='pestanalink8' class='link' onclick='xajax_comunicacionEstud(".$codEstudiante.", ".$_REQUEST['codDocente'].");'>";
//            $html.="<font size='1'><b>Comunicación</b></font></a></li>";
            $html.="</ul>";
            $html.="</div>";
            $html.="<div id='cuerpopestanas' class='cuerpopestanas'>";
            $html.="<table class='contenidotabla'>";

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
                    $html.="<tr>
                            <th class='sigma' align='center'>Asignatura</th>
                            <th class='sigma' align='center'>Nombre</th>
                            <th class='sigma' align='center'>Grupo</th>
                            <th class='sigma' align='center'>Nota Par 1</th>
                            <th class='sigma' align='center'>Nota Par 2</th>
                            <th class='sigma' align='center'>Nota Par 3</th>
                            <th class='sigma' align='center'>Nota Par 4</th>
                            <th class='sigma' align='center'>Nota Par 5</th>
                            <th class='sigma' align='center'>Nota Par 6</th>
                            <th class='sigma' align='center'>Nota Par Lab</th>
                            <th class='sigma' align='center'>Nota Par Examen</th>
                            <th class='sigma' align='center'>Nro Fallas</th>
                            <th class='sigma' align='center'>Docente</th>
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
                        $cadena_sql.=" ins_nota_lab,ins_nota_exa";
                        $cadena_sql.=" from acins ";
                        $cadena_sql.=" where ins_asi_cod='".$resultado[$i][0]."'";
                        $cadena_sql.=" and ins_gr='".$resultado[$i][1]."'";
                        $cadena_sql.=" and ins_est_cod='".$codEstudiante."'";
                        $cadena_sql.=" and ins_ano=(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%A%')";
                        $cadena_sql.=" and ins_per=(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%A%')";

                        $resultado_inscripciones=$funcion->ejecutarSQL($configuracion, $accesoOracle, $cadena_sql,"busqueda");

                        $html.="<tr class='".$clasetr."'>
                            <td class='sigma' align='center'>".$resultado[$i][0]."</td>
                            <td class='sigma' >".$resultado[$i][2]."</td>
                            <td class='sigma' align='center'>".$resultado[$i][1]."</td>
                            <td class='sigma' align='center'><table class='contenidotabla centrar'><tr><td class='centrar'>".$resultado_curso[0][0]." %</td><tr><tr><td class='centrar'>".number_format(($resultado_inscripciones[0][0]/10),1)."</td></tr></table></td>
                            <td class='sigma' align='center'><table class='contenidotabla centrar'><tr><td class='centrar'>".$resultado_curso[0][1]." %</td><tr><tr><td class='centrar'>".number_format(($resultado_inscripciones[0][1]/10),1)."</td></tr></table></td>
                            <td class='sigma' align='center'><table class='contenidotabla centrar'><tr><td class='centrar'>".$resultado_curso[0][2]." %</td><tr><tr><td class='centrar'>".number_format(($resultado_inscripciones[0][2]/10),1)."</td></tr></table></td>
                            <td class='sigma' align='center'><table class='contenidotabla centrar'><tr><td class='centrar'>".$resultado_curso[0][3]." %</td><tr><tr><td class='centrar'>".number_format(($resultado_inscripciones[0][3]/10),1)."</td></tr></table></td>
                            <td class='sigma' align='center'><table class='contenidotabla centrar'><tr><td class='centrar'>".$resultado_curso[0][4]." %</td><tr><tr><td class='centrar'>".number_format(($resultado_inscripciones[0][4]/10),1)."</td></tr></table></td>
                            <td class='sigma' align='center'><table class='contenidotabla centrar'><tr><td class='centrar'>".$resultado_curso[0][5]." %</td><tr><tr><td class='centrar'>".number_format(($resultado_inscripciones[0][5]/10),1)."</td></tr></table></td>
                            <td class='sigma' align='center'><table class='contenidotabla centrar'><tr><td class='centrar'>".$resultado_curso[0][6]." %</td><tr><tr><td class='centrar'>".number_format(($resultado_inscripciones[0][6]/10),1)."</td></tr></table></td>
                            <td class='sigma' align='center'><table class='contenidotabla centrar'><tr><td class='centrar'>".$resultado_curso[0][7]." %</td><tr><tr><td class='centrar'>".number_format(($resultado_inscripciones[0][7]/10),1)."</td></tr></table></td>
                            <td class='sigma' align='center'></td>
                            <td class='sigma'>".$resultado_curso[0][9]." ".$resultado_curso[0][8]."</td>
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

            $respuesta->addAssign("pestana" . 6, "className", "pestanaseleccionada");
            $respuesta->addAssign("pestanalink" . 6, "className", "pestanaseleccionada");
               //Pongo la class css de las pestañas sin pulsar
            for ($h=3; $h<=8; $h++){
                  if ($h != 6){
                     $respuesta->addAssign("pestanalink" . $h, "className", "pestanainactiva");
                     $respuesta->addAssign("pestana" . $h, "className", "pestanainactiva");
                  }
               }

            return $respuesta;
        }

    }

        function comunicacionEstud($codEstudiante, $codDocente)
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
            $html="<div class='pestanas'>";
            $html.="<ul>";
            $html.="<li id='pestana3' class='pestanainactiva a'>";
            $html.="<a id='pestanalink3' class='link' onclick='xajax_datosEstudianteConsejerias(".$codEstudiante.");'>";
            $html.="<font size='1'><b>Datos</b></font></a></li>";
            $html.="<li id='pestana4' class='pestanainactiva a'>";
            $html.="<a id='pestanalink4' class='link' onclick='xajax_notas(".$codEstudiante.");'>";
            $html.="<font size='1'><b>Notas</b></font></a></li>";
            $html.="<li id='pestana5' class='pestanainactiva a'>";
            $html.="<a id='pestanalink5' class='link' onclick='xajax_inscripciones(".$codEstudiante.");'>";
            $html.="<font size='1'><b>Inscripciones</b></font></a></li>";
            $html.="<li id='pestana6' class='pestanainactiva a'>";
            $html.="<a id='pestanalink6' class='link' onclick='xajax_notas_parciales(".$codEstudiante.");'>";
            $html.="<font size='1'><b>Notas Parciales</b></font></a></li>";
//            $html.="<li id='pestana7' class='pestanainactiva a'>";
//            $html.="<a id='pestanalink7' class='link' onclick='xajax_pe(".$codEstudiante.");'>";
//            $html.="<font size='1'><b>Plan de estudios</b></font></a></li>";
//            $html.="<li id='pestana8' class='pestanainactiva a'>";
//            $html.="<a id='pestanalink8' class='link' onclick='xajax_comunicacionEstud(".$codEstudiante.", ".$codDocente.");'>";
//            $html.="<font size='1'><b>Comunicación</b></font></a></li>";
            $html.="</ul>";
            $html.="</div>";
            $html.="<div id='cuerpopestanas' class='cuerpopestanas'>";
            $html.="<table class='contenidotabla'>";

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

                    $cadena_sql="select doc_nombre, doc_apellido, doc_email";
                    $cadena_sql.=" from acdocente";
                    $cadena_sql.=" where doc_nro_iden=".$codDocente;
                    $resultado_nombreDocente=$funcion->ejecutarSQL($configuracion, $accesoOracle, $cadena_sql,"busqueda" );


                    if(is_array($resultado_mensaje))
                    {

                   $html.="
                            <tr><td colspan='2'><div id='div_mensajes1'>
                    <table class='contenidotabla centrar'>
                    <tr>
                       <td class='centrar' colspan='2'>
                           <b>Mensajes entre el Consejero ".$resultado_nombreDocente[0][0]." ".$resultado_nombreDocente[0][1]." y el estudiante ".$resultado_estudiante[0][1]."
                           </b>
                       </td>
                   </tr>";


                    for($m=0;$m<count($resultado_mensaje);$m++)
                    {
                        if($resultado_mensaje[$m][1]==$codDocente)
                        {
                            $resultado_mensaje[$m][1]=$resultado_nombreDocente[0][0]." ".$resultado_nombreDocente[0][1];
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
                                        <b>NO HAY MENSAJES ENTRE CONSEJERO Y ESTUDIANTE</b>
                                    </td>
                                </tr>
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

            $respuesta->addAssign("pestana" . 8, "className", "pestanaseleccionada");
            $respuesta->addAssign("pestanalink" . 8, "className", "pestanaseleccionada");
               //Pongo la class css de las pestañas sin pulsar
            for ($h=3; $h<=8; $h++){
                  if ($h != 8){
                     $respuesta->addAssign("pestanalink" . $h, "className", "pestanainactiva");
                     $respuesta->addAssign("pestana" . $h, "className", "pestanainactiva");
                  }
               }

            return $respuesta;


        }
    }
?>
