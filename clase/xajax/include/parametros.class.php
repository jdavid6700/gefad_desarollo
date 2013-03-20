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

    

    function creditosPlan($valor,$proyecto,$planEstudio,$nombreProyecto)
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


            $busqueda="select cra_tip_cra from accra where cra_cod=".$proyecto;

            $resultado=$funcion->ejecutarSQL($configuracion, $accesoOracle, $busqueda,"busqueda");

            $htmlParametro = "<table class='contenidotabla'><tr><td class='cuadro_plano centrar' colspan='5'>El proyecto curricular pertenece a un nivel ";
            $nombreProyecto=trim($nombreProyecto, "'");
            $nombreProyecto='"'.$nombreProyecto.'"';
            if($resultado[0][0]=='3')
                    {
                        $htmlParametro.= "Profesional Tecnol&oacute;gico";
                        if($valor<='108' && $valor>='96')
                            {
                                $htmlParametro.="<br>El total de cr&eacute;ditos digitado es permitido para el plan de estudios<br><td></tr>";
                                $htmlParametro.="<tr class='centrar'>
                                                    <td>Obligatorios B&aacute;sicos<br><input type='text' value='0' id='OB".$valor."' name='OB".$valor."' size='3' maxlength='3' onchange='xajax_graficar(OB".$valor.".value,OC".$valor.".value,EI".$valor.".value,EE".$valor.".value,CP".$valor.".value,".$valor.",".$planEstudio.",".$proyecto.",".$nombreProyecto.")'></td>
                                                    <td>Obligatorios Complementarios<br><input type='text' value='0' id='OC".$valor."' name='OC".$valor."' size='3' maxlength='3' onchange='xajax_graficar(OB".$valor.".value,OC".$valor.".value,EI".$valor.".value,EE".$valor.".value,CP".$valor.".value,".$valor.",".$planEstudio.",".$proyecto.",".$nombreProyecto.")'></td>
                                                    <td>Electivas Intrinsecas<br><input type='text' value='0' id='EI".$valor."' name='EI".$valor."' size='3' maxlength='3' onchange='xajax_graficar(OB".$valor.".value,OC".$valor.".value,EI".$valor.".value,EE".$valor.".value,CP".$valor.".value,".$valor.",".$planEstudio.",".$proyecto.",".$nombreProyecto.")'></td>
                                                    <td>Electivas Extrinsecas<br><input type='text' value='0' id='EE".$valor."' name='EE".$valor."' size='3' maxlength='3' onchange='xajax_graficar(OB".$valor.".value,OC".$valor.".value,EI".$valor.".value,EE".$valor.".value,CP".$valor.".value,".$valor.",".$planEstudio.",".$proyecto.",".$nombreProyecto.")'></td>
                                                    <td>Componente Proped&eacute;utico<br><input type='text' value='0' id='CP".$valor."' name='CP".$valor."' size='3' maxlength='3' onchange='xajax_graficar(OB".$valor.".value,OC".$valor.".value,EI".$valor.".value,EE".$valor.".value,CP".$valor.".value,".$valor.",".$planEstudio.",".$proyecto.",".$nombreProyecto.")'></td>
                                                </tr></table>";
                            }else
                                {
                                    $htmlParametro.="<br>El total de cr&eacute;ditos no es permitido<br><td></tr>";
                                }
                    }else
                        {
                            $htmlParametro.= "Profesional";
                            if($valor<='180' && $valor>='160')
                            {
                                $htmlParametro.="<br>El total de cr&eacute;ditos digitado es permitido para el plan de estudios<br><td></tr>";
                                $htmlParametro.="<tr class='centrar'>
                                                    <td>Obligatorios B&aacute;sicos<br><input type='text' value='0' id='OB".$valor."' name='OB".$valor."' size='3' maxlength='3' onchange='xajax_graficar(OB".$valor.".value,OC".$valor.".value,EI".$valor.".value,EE".$valor.".value,CP".$valor.".value,".$valor.",".$planEstudio.",".$proyecto.",".$nombreProyecto.")'></td>
                                                    <td>Obligatorios Complementarios<br><input type='text' value='0' id='OC".$valor."' name='OC".$valor."' size='3' maxlength='3' onchange='xajax_graficar(OB".$valor.".value,OC".$valor.".value,EI".$valor.".value,EE".$valor.".value,CP".$valor.".value,".$valor.",".$planEstudio.",".$proyecto.",".$nombreProyecto.")'></td>
                                                    <td>Electivas Intrinsecas<br><input type='text' value='0' id='EI".$valor."' name='EI".$valor."' size='3' maxlength='3' onchange='xajax_graficar(OB".$valor.".value,OC".$valor.".value,EI".$valor.".value,EE".$valor.".value,CP".$valor.".value,".$valor.",".$planEstudio.",".$proyecto.",".$nombreProyecto.")'></td>
                                                    <td>Electivas Extrinsecas<br><input type='text' value='0' id='EE".$valor."' name='EE".$valor."' size='3' maxlength='3' onchange='xajax_graficar(OB".$valor.".value,OC".$valor.".value,EI".$valor.".value,EE".$valor.".value,CP".$valor.".value,".$valor.",".$planEstudio.",".$proyecto.",".$nombreProyecto.")'></td>
                                                    <td>Componente Proped&eacute;utico<br><input type='text' value='0' id='CP".$valor."' name='CP".$valor."' size='3' maxlength='3' onchange='xajax_graficar(OB".$valor.".value,OC".$valor.".value,EI".$valor.".value,EE".$valor.".value,CP".$valor.".value,".$valor.",".$planEstudio.",".$proyecto.",".$nombreProyecto.")'></td>
                                                </tr></table>";
                            }else
                                {
                                    $htmlParametro.="<br>El total de cr&eacute;ditos no es permitido<br><td></tr>";
                                }
                        }

            $respuesta = new xajaxResponse();
            $respuesta->addAssign("div_creditos","innerHTML",$htmlParametro);
            $respuesta->addAssign("div_graficas","innerHTML","");
            return $respuesta;
            
            }
    }

    function graficar($OB,$OC,$EI,$EE,$CP,$totalCreditos,$planEstudio,$proyecto,$nombreProyecto)
    {
    require_once("clase/config.class.php");
    $esta_configuracion=new config();
    $configuracion=$esta_configuracion->variable();

    require_once("clase/encriptar.class.php");
    $cripto=new encriptar();

    $funcion=new funcionGeneral();
    //Conectarse a la base de datos
    $acceso_db=new dbms($configuracion);
    $enlace=$acceso_db->conectar_db();
    //$valor=$acceso_db->verificar_variables($valor);

    $html=new html();
    $conexion=new multiConexion();
    $accesoOracle=$conexion->estableceConexion(75,$configuracion);
    $accesoGestion=$conexion->estableceConexion(99,$configuracion);

    if (is_resource($enlace))
        {
            $porcentajeObligatorios=$porcentajeObligatoriosBasicos=$porcentajeObligatoriosComplementarios=0;
            $porcentajeElectivos=$porcentajeElectivosIntrinsecos=$porcentajeElectivosExtrinsecos=0;
            $busqueda="select planEstudio_propedeutico PROPEDEUTICO";
            $busqueda.=" from ".$configuracion['prefijo']."planEstudio";
            $busqueda.=" where id_planEstudio=".$planEstudio;
            //planes de estudio de ciclos de facultad tecnologica
            $resultado_datosPlan=$funcion->ejecutarSQL($configuracion, $accesoGestion, $busqueda,"busqueda");

            $maximo = $totalCreditos;
            $porcentajeObligatorios=(($OB+$OC)/$maximo)*100;
            if ($OB>0){
            $porcentajeObligatoriosBasicos=(($OB)/($OB+$OC))*100;}
            if ($OC>0){
            $porcentajeObligatoriosComplementarios=(($OC)/($OB+$OC))*100;}
            $porcentajeElectivos=(($EI+$EE)/$maximo)*100;
            if ($EI>0){
            $porcentajeElectivosIntrinsecos=(($EI)/($EI+$EE))*100;}
            if($EE>0){
            $porcentajeElectivosExtrinsecos=($EE/($EI+$EE))*100;}
            if ($resultado_datosPlan[0]['PROPEDEUTICO']==1)
            {
                $sumaCreditos=$OB+$OC+$EI+$EE+$CP;
            }
            else
                {
                    $sumaCreditos=$OB+$OC+$EI+$EE;
                }
           
            $vista="<table class='contenidotabla centrar'><tr><td class='centrar'>Suma de Cr&eacute;ditos: ".$sumaCreditos."<br>Total de cr&eacute;ditos:".$totalCreditos."</td></tr></table>";
            if($sumaCreditos!=$totalCreditos)
              {
              $vista.="<table class='contenidotabla centrar'><tr><td align='center'>El n&uacute;mero total de cr&eacute;ditos no corresponde con la suma de cr&eacute;ditos ingresados</td></tr></table>";
            }
            $vista.=
                "<table class='tablaGrafico' align='center' width='100%' cellspacing='0' cellpadding='2'>
                    ";
            if($porcentajeObligatorios>'0')
                {
                    $vista.="<tr>
                    <td  width='70%' class='centrar' bgcolor='#F3DF8D'> <font color='black'>Obligatorios: ".round($porcentajeObligatorios,1)." %</font>
                    <table class='tablaGrafico' width='100%' cellspacing='0' cellpadding='1' ";
                    if($porcentajeObligatoriosBasicos>'0')
                        {
                            if ($resultado_datosPlan[0]['PROPEDEUTICO']==1)//para planes de estudio de Ingenieria de la facultad tecnologica
                            { $ancho=$porcentajeObligatoriosBasicos-20;
                                $vista.="<tr>
                                        <td width='".$ancho."%' class='centrar'  height='100%'  bgcolor='#29467F'> OB<br>".round($porcentajeObligatoriosBasicos,1)." %
                                        </td>
                                        <td width='20%' class='centrar texto_gris' bgcolor='#CEE3F6' style='border:5px solid #29467F'> CP ".$CP." cred</td>";
                            }else{
                            $vista.="<tr>
                                        <td width='".$porcentajeObligatoriosBasicos."%' class='centrar'  height='100%'  bgcolor='#29467F'> OB<br>".round($porcentajeObligatoriosBasicos,1)." %
                                        </td>";
                            }
                        }

                    if($porcentajeObligatoriosComplementarios>'0')
                        {
                            $vista.="<td width='".$porcentajeObligatoriosComplementarios."%' class='centrar'  height='100%' bgcolor='#6B8FD4'>OC<br> ".round($porcentajeObligatoriosComplementarios,1)." %
                                    </td>
                                    </tr>
                                </table>";
                        }else
                                {
                                $vista.="</tr>
                                    </table>";
                                }
                }

            if($porcentajeElectivos>'0')
                {
                    $vista.="</td>
                                <td width='30%' class='centrar' bgcolor='#F7EDC5'><font color='black'>Electivos: ".round($porcentajeElectivos,1)." %</font>
                                <table class='tablaGrafico'  width='100%' cellspacing='0' cellpadding='1' >";
                    if($porcentajeElectivosIntrinsecos>'0')
                        {
                            $vista.="<tr>
                                    <td width='".$porcentajeElectivosIntrinsecos."%' class='centrar'  height='100%' bgcolor='#006064'>EI<br> ".round($porcentajeElectivosIntrinsecos,1)." %
                                    </td>";
                        }
                        if($porcentajeElectivosExtrinsecos>'0')
                            {
                                $vista.="<td width='".$porcentajeElectivosExtrinsecos."%' class='centrar'  height='100%' bgcolor='#36979E'>EE<br> ".round($porcentajeElectivosExtrinsecos,1)." %
                                         </td>
                                         </tr>
                                    </table>";
                            }else
                                {
                                $vista.="</tr>
                                    </table>";
                                }
                }
                        
                        
                    $vista.="</td>
                    </tr>
                    </table>";

                if($sumaCreditos>$totalCreditos)
                    {
                        $vista="<table class='contenidotabla centrar''><tr><td colspan='5' class='centrar'><img src='".$configuracion['site'].$configuracion['grafico']."/error.png' width='35' height='35' alt='Continuar' border='0'><br>El n&uacute;mero de cr&eacute;ditos supera los cr&eacute;ditos del plan de estudio<br>Suma de Cr&eacute;ditos: ".($OB+$OC+$EI+$EE)."<br>Total de cr&eacute;ditos:".$totalCreditos."</td><tr></table>";
                    }else if($sumaCreditos==$totalCreditos)
                        {
                            if($porcentajeObligatorios>='0' && $porcentajeObligatorios<='100')
                                        {
                                            if($porcentajeObligatorios>='80' && $porcentajeObligatorios<='85')
                                                {

                                                }else
                                                    {
                                                        $vista.= "<table class='contenidotabla centrar'><tr><td class='centrar'><img src='".$configuracion['site'].$configuracion['grafico']."/error.png' width='35' height='35' alt='Continuar' border='0'><br>El porcentaje de obligatorios no cumple con lo establecido en el acuerdo 009 de 2006</td><tr></table>";
                                                    }

                                            if((round($porcentajeObligatoriosBasicos,1)>='0' && round($porcentajeObligatoriosBasicos,1)<='100') && (round($porcentajeObligatoriosComplementarios,1)>='0') && (round($porcentajeObligatoriosComplementarios,1)<='100'))
                                                {
                                                    if((round($porcentajeObligatoriosBasicos,1)>='85' && round($porcentajeObligatoriosBasicos,1)<='95') && (round($porcentajeObligatoriosComplementarios,1)>='5') && (round($porcentajeObligatoriosComplementarios,1)<='15'))
                                                        {

                                                        }else
                                                            {
                                                                $vista.="<table class='contenidotabla centrar'><tr><td class='centrar'><img src='".$configuracion['site'].$configuracion['grafico']."/error.png' width='35' height='35' alt='Continuar' border='0'><br>El porcentaje de obligatorios basicos y complementarios no cumple con lo establecido en el acuerdo 009 de 2006</td><tr></table>";
                                                            }

                                                    if($porcentajeElectivos>='0' && $porcentajeElectivos<='100')
                                                        {

                                                            if($porcentajeElectivos>='15' && $porcentajeElectivos<='20')
                                                            {

                                                            }else
                                                                {
                                                                    $vista.= "<table class='contenidotabla centrar'><tr><td class='centrar'><img src='".$configuracion['site'].$configuracion['grafico']."/error.png' width='35' height='35' alt='Continuar' border='0'><br>El porcentaje de electivos no cumple con lo establecido en el acuerdo 009 de 2006</td><tr></table>";
                                                                }

                                                            if((round($porcentajeElectivosIntrinsecos,1)>='0' && round($porcentajeElectivosIntrinsecos,1)<='100') && (round($porcentajeElectivosExtrinsecos,1)>='0' && round($porcentajeElectivosExtrinsecos,1)<='100'))
                                                                {

                                                                    if((round($porcentajeElectivosIntrinsecos,1)>='65' && round($porcentajeElectivosIntrinsecos,1)<='75') && (round($porcentajeElectivosExtrinsecos,1)>='25' && round($porcentajeElectivosExtrinsecos,1)<='35'))
                                                                    {

                                                                    }else
                                                                        {
                                                                            $vista.="<table class='contenidotabla centrar'><tr><td class='centrar'><img src='".$configuracion['site'].$configuracion['grafico']."/error.png' width='35' height='35' alt='Continuar' border='0'><br>El porcentaje de electivos intrinsecos y extrinsecos no cumple con lo establecido en el acuerdo 009 de 2006</td><tr></table>";
                                                                        }

                                                                            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                                                            $ruta="pagina=registro_parametrosPlanEstudio";
                                                                            $ruta.="&opcion=registrar";
                                                                            $ruta.="&totalCreditos=".$totalCreditos;
                                                                            $ruta.="&OB=".$OB;
                                                                            $ruta.="&OC=".$OC;
                                                                            $ruta.="&EI=".$EI;
                                                                            $ruta.="&EE=".$EE;
                                                                            $ruta.="&CP=".$CP;
                                                                            $ruta.="&planEstudio=".$planEstudio;
                                                                            $ruta.="&codProyecto=".$proyecto;
                                                                            $ruta.="&nombreProyecto=".$nombreProyecto;

                                                                            $ruta=$cripto->codificar_url($ruta,$configuracion);

                                                                            $vista.="
                                                                                <a href='".$pagina.$ruta."'>
                                                                                <img src='".$configuracion['site'].$configuracion['grafico']."/3floppy_mount.png' width='35' height='35' alt='Continuar' border='0'><br>Guardar datos
                                                                                </a>
                                                                            ";

                                                                }else
                                                                    {
                                                                        $vista.="<table class='contenidotabla centrar'><tr><td class='centrar'><img src='".$configuracion['site'].$configuracion['grafico']."/error.png' width='35' height='35' alt='Continuar' border='0'><br>El porcentaje de electivos intrinsecos y extrinsecos no cumple con lo establecido en el acuerdo 009 de 2006</td><tr></table>";

                                                                    }
                                                        }else
                                                            {
                                                                $vista.= "<table class='contenidotabla centrar'><tr><td class='centrar'><img src='".$configuracion['site'].$configuracion['grafico']."/error.png' width='35' height='35' alt='Continuar' border='0'><br>El porcentaje de electivos no cumple con lo establecido en el acuerdo 009 de 2006</td><tr></table>";

                                                            }
                                                }else
                                                    {
                                                        $vista.="<table class='contenidotabla centrar'><tr><td class='centrar'><img src='".$configuracion['site'].$configuracion['grafico']."/error.png' width='35' height='35' alt='Continuar' border='0'><br>El porcentaje de obligatorios basicos y complementarios no cumple con lo establecido en el acuerdo 009 de 2006</td><tr></table>";

                                                    }
                                        }else
                                            {
                                                $vista.= "<table class='contenidotabla centrar'><tr><td class='centrar'><img src='".$configuracion['site'].$configuracion['grafico']."/error.png' width='35' height='35' alt='Continuar' border='0'><br>El porcentaje de obligatorios no cumple con lo establecido en el acuerdo 009 de 2006</td><tr></table>";

                                            }
                        }

                       

            $respuesta = new xajaxResponse();
            $respuesta->addAssign("div_graficas","innerHTML",$vista);
            return $respuesta;
        }
    }

    function editarCreditosPlan($valor,$proyecto,$planEstudio,$nombreProyecto)
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


            $busqueda="select cra_tip_cra from accra where cra_cod=".$proyecto;
            $resultado=$funcion->ejecutarSQL($configuracion, $accesoOracle, $busqueda,"busqueda");

            $busqueda="select parametros_OB, parametros_OC,parametros_EI,parametros_EE,parametro_creditosPlan,parametros_CP from sga_parametro_plan_estudio where parametro_idPlanEstudio=".$planEstudio;
            $parametros=$funcion->ejecutarSQL($configuracion, $accesoGestion, $busqueda,"busqueda");
            $nombreProyecto=trim($nombreProyecto, "'");
            $nombreProyecto='"'.$nombreProyecto.'"';
            if($parametros[0][4]!=$valor)
                {
                    $parametros[0][0]='0';
                    $parametros[0][1]='0';
                    $parametros[0][2]='0';
                    $parametros[0][3]='0';
                    $parametros[0][5]='0';
                }

            $htmlParametro = "<table class='contenidotabla'><tr><td class='cuadro_plano centrar' colspan='5'>El proyecto curricular pertenece a un nivel ";

            if($resultado[0][0]=='3')
                    {
                        $htmlParametro.= "Profesional Tecnol&oacute;gico";
                        if($valor<='108' && $valor>='96')
                            {
                                $htmlParametro.="<br>El total de cr&eacute;ditos digitado es permitido para el plan de estudio<br><td></tr>";
                                $htmlParametro.="<tr class='centrar'>
                                                    <td>Obligatorios B&aacute;sicos<br><input type='text' value='".$parametros[0][0]."' id='OB' name='OB' size='3' maxlength='3' onchange='xajax_graficarEdicion(OB.value,OC.value,EI.value,EE.value,CP.value,".$valor.",".$planEstudio.",".$proyecto.",".$nombreProyecto.")'></td>
                                                    <td>Obligatorios Complementarios<br><input type='text' value='".$parametros[0][1]."' id='OC' name='OC' size='3' maxlength='3' onchange='xajax_graficarEdicion(OB.value,OC.value,EI.value,EE.value,CP.value,".$valor.",".$planEstudio.",".$proyecto.",".$nombreProyecto.")'></td>
                                                    <td>Electivas Intrinsecas<br><input type='text' value='".$parametros[0][2]."' id='EI' name='EI' size='3' maxlength='3' onchange='xajax_graficarEdicion(OB.value,OC.value,EI.value,EE.value,CP.value,".$valor.",".$planEstudio.",".$proyecto.",".$nombreProyecto.")'></td>
                                                    <td>Electivas Extrinsecas<br><input type='text' value='".$parametros[0][3]."' id='EE' name='EE' size='3' maxlength='3' onchange='xajax_graficarEdicion(OB.value,OC.value,EI.value,EE.value,CP.value,".$valor.",".$planEstudio.",".$proyecto.",".$nombreProyecto.")'></td>
                                                    <td>Componente Proped&eacute;utico<br><input type='text' value='".$parametros[0][5]."' id='CP' name='CP' size='3' maxlength='3' onchange='xajax_graficarEdicion(OB.value,OC.value,EI.value,EE.value,CP.value,".$valor.",".$planEstudio.",".$proyecto.",".$nombreProyecto.")'></td>
                                                </tr>
                                                <tr><td align='center' colspan='5'><img src=".$configuracion['site'].$configuracion['grafico']."/lassists.png width='25' height='25' alt='Validar' border='0'><br>Verificar Porcentajes</td></tr>
                                                </table>";
                            }else
                                {
                                    $htmlParametro.="<br>El total de cr&eacute;ditos no es permitido<br><td></tr>";
                                }
                    }else
                        {
                            $htmlParametro.= "Profesional";
                            if($valor<='180' && $valor>='160')
                            {
                                $htmlParametro.="<br>El total de cr&eacute;ditos digitado es permitido para el plan de estudio<br><td></tr>";
                                $htmlParametro.="<tr class='centrar'>
                                                    <td>Obligatorios B&aacute;sicos<br><input type='text' value='".$parametros[0][0]."' id='OB' name='OB' size='3' maxlength='3' onchange='xajax_graficarEdicion(OB.value,OC.value,EI.value,EE.value,CP.value,".$valor.",".$planEstudio.",".$proyecto.",".$nombreProyecto.")'></td>
                                                    <td>Obligatorios Complementarios<br><input type='text' value='".$parametros[0][1]."' id='OC' name='OC' size='3' maxlength='3' onchange='xajax_graficarEdicion(OB.value,OC.value,EI.value,EE.value,CP.value,".$valor.",".$planEstudio.",".$proyecto.",".$nombreProyecto.")'></td>
                                                    <td>Electivas Intrinsecas<br><input type='text' value='".$parametros[0][2]."' id='EI' name='EI' size='3' maxlength='3' onchange='xajax_graficarEdicion(OB.value,OC.value,EI.value,EE.value,CP.value,".$valor.",".$planEstudio.",".$proyecto.",".$nombreProyecto.")'></td>
                                                    <td>Electivas Extrinseecas<br><input type='text' value='".$parametros[0][3]."' id='EE' name='EE' size='3' maxlength='3' onchange='xajax_graficarEdicion(OB.value,OC.value,EI.value,EE.value,CP.value,".$valor.",".$planEstudio.",".$proyecto.",".$nombreProyecto.")'></td>
                                                    <td>Componente Proped&eacute;utico<br><input type='text' value='".$parametros[0][5]."' id='CP' name='CP' size='3' maxlength='3' onchange='xajax_graficarEdicion(OB.value,OC.value,EI.value,EE.value,CP.value,".$valor.",".$planEstudio.",".$proyecto.",".$nombreProyecto.")'></td>
                                                </tr>
                                                <tr><td align='center' colspan='5'><img src=".$configuracion['site'].$configuracion['grafico']."/lassists.png width='25' height='25' alt='Validar' border='0'><br>Verificar Porcentajes</td></tr>
                                                </table>";
                            }else
                                {
                                    $htmlParametro.="<br>El total de cr&eacute;ditos no es permitido<br><td></tr>";
                                }
                        }

            $respuesta = new xajaxResponse();
            $respuesta->addAssign("div_creditos","innerHTML",$htmlParametro);
            $respuesta->addAssign("div_graficas","innerHTML","");
            return $respuesta;

            }
    }

    function graficarEdicion($OB,$OC,$EI,$EE,$CP,$totalCreditos,$planEstudio,$proyecto,$nombreProyecto)
    {
    require_once("clase/config.class.php");
    $esta_configuracion=new config();
    $configuracion=$esta_configuracion->variable();

    require_once("clase/encriptar.class.php");
    $cripto=new encriptar();

    $funcion=new funcionGeneral();
    //Conectarse a la base de datos
    $acceso_db=new dbms($configuracion);
    $enlace=$acceso_db->conectar_db();
    //$valor=$acceso_db->verificar_variables($valor);

    $html=new html();
    $conexion=new multiConexion();
    $accesoOracle=$conexion->estableceConexion(75,$configuracion);
    $accesoGestion=$conexion->estableceConexion(99,$configuracion);

    if (is_resource($enlace))
        {
            $porcentajeObligatorios=$porcentajeObligatoriosBasicos=$porcentajeObligatoriosComplementarios=0;
            $porcentajeElectivos=$porcentajeElectivosIntrinsecos=$porcentajeElectivosExtrinsecos=0;
            $busqueda="select planEstudio_propedeutico PROPEDEUTICO";
            $busqueda.=" from ".$configuracion['prefijo']."planEstudio";
            $busqueda.=" where id_planEstudio=".$planEstudio;
            $resultado_datosPlan=$funcion->ejecutarSQL($configuracion, $accesoGestion, $busqueda,"busqueda");
            //planes de estudio de ciclos de facultad tecnologica
            $maximo = $totalCreditos;
            $porcentajeObligatorios=(($OB+$OC)/$maximo)*100;
            if ($OB>0){
            $porcentajeObligatoriosBasicos=(($OB)/($OB+$OC))*100;}
            if ($OC>0){
            $porcentajeObligatoriosComplementarios=(($OC)/($OB+$OC))*100;}
            $porcentajeElectivos=(($EI+$EE)/$maximo)*100;
            if ($EI>0){
            $porcentajeElectivosIntrinsecos=(($EI)/($EI+$EE))*100;}
            if($EE>0){
            $porcentajeElectivosExtrinsecos=($EE/($EI+$EE))*100;}
            if ($resultado_datosPlan[0]['PROPEDEUTICO']==1)
            {
                $sumaCreditos=$OB+$OC+$EI+$EE+$CP;
            }
            else
                {
                    $sumaCreditos=$OB+$OC+$EI+$EE;
                }

            $vista="<table class='contenidotabla centrar' width='100%'><tr><td class='centrar'>Suma de Cr&eacute;ditos: ".$sumaCreditos."<br>Total de cr&eacute;ditos:".$totalCreditos."</td></tr></table>";
            if($sumaCreditos!=$totalCreditos)
              {
              $vista.="<table class='contenidotabla centrar'><tr><td align='center' colspan=6>El n&uacute;mero total de cr&eacute;ditos no corresponde con la suma de cr&eacute;ditos ingresados</td></tr></table>";
            }
            $vista.=
                "<table class='tablaGrafico' align='center' width='100%' cellspacing='0' cellpadding='2'>
                    ";
            if($porcentajeObligatorios>'0')
                {
                    $vista.="<tr>
                    <td  width='70%' class='centrar' bgcolor='#F3DF8D'>  <font color='black'>Obligatorios: ".round($porcentajeObligatorios,1)." %</font>
                    <table class='tablaGrafico' width='100%' cellspacing='0' cellpadding='1' ";
                    if($porcentajeObligatoriosBasicos>'0')
                        {
                            $vista.="<tr>
                                        <td width='".$porcentajeObligatoriosBasicos."%' class='centrar'  height='100%'  bgcolor='#29467F'> OB<br>".round($porcentajeObligatoriosBasicos,1)." %
                                        </td>";
                        }

                    if($porcentajeObligatoriosComplementarios>'0')
                        {
                            $vista.="<td width='".$porcentajeObligatoriosComplementarios."%' class='centrar'  height='100%' bgcolor='#6B8FD4'>OC<br> ".round($porcentajeObligatoriosComplementarios,1)." %
                                    </td>
                                    </tr>
                                </table>";
                        }else
                                {
                                $vista.="</tr>
                                    </table>";
                                }
                }

            if($porcentajeElectivos>'0')
                {
                    $vista.="</td>
                                <td width='30%' class='centrar' bgcolor='#F7EDC5'> <font color='black'>Electivos: ".round($porcentajeElectivos,1)." %</font>
                                <table class='tablaGrafico'  width='100%' cellspacing='0' cellpadding='1' >";
                    if($porcentajeElectivosIntrinsecos>'0')
                        {
                            $vista.="<tr>
                                    <td width='".$porcentajeElectivosIntrinsecos."%' class='centrar'  height='100%' bgcolor='#006064'>EI<br> ".round($porcentajeElectivosIntrinsecos,1)." %
                                    </td>";
                        }
                        if($porcentajeElectivosExtrinsecos>'0')
                            {
                                $vista.="<td width='".$porcentajeElectivosExtrinsecos."%' class='centrar'  height='100%' bgcolor='#36979E'>EE<br> ".round($porcentajeElectivosExtrinsecos,1)." %
                                         </td>
                                         </tr>
                                    </table>";
                            }else
                                {
                                $vista.="</tr>
                                    </table>";
                                }
                }


                    $vista.="</td>
                    </tr>
                    </table>";

                if($sumaCreditos>$totalCreditos)
                    {
                        $vista="<tr><td colspan='5' width='100%' class='centrar'><table class='contenidotabla centrar'><tr><td colspan='5' class='centrar'><img src='".$configuracion['site'].$configuracion['grafico']."/error.png' width='35' height='35' alt='Continuar' border='0'><br>El n&uacute;mero de cr&eacute;ditos supera los cr&eacute;ditos del plan de estudio<br>Suma de Cr&eacute;ditos: ".$sumaCreditos."<br>Total de cr&eacute;ditos:".$totalCreditos."</td><tr></table></td></tr>";
                    }else if($sumaCreditos==$totalCreditos)
                        {
                        $vista="<table class='contenidotabla centrar'>";
                        if($porcentajeObligatorios>='0' && $porcentajeObligatorios<='100')
                                        {
                                            if($porcentajeObligatorios>='80' && $porcentajeObligatorios<='85')
                                                {

                                                }else
                                                    {
                                                        $vista.= "<tr><td colspan='5' class='centrar'><table class='contenidotabla centrar'><tr><td class='centrar'><img src='".$configuracion['site'].$configuracion['grafico']."/error.png' width='35' height='35' alt='Continuar' border='0'><br>El porcentaje de obligatorios no cumple con lo establecido en el acuerdo 009 de 2006</td><tr></table></td></tr>";
                                                    }

                                            if((round($porcentajeObligatoriosBasicos,1)>='0' && round($porcentajeObligatoriosBasicos,1)<='100') && (round($porcentajeObligatoriosComplementarios,1)>='0') && (round($porcentajeObligatoriosComplementarios,1)<='100'))
                                                {
                                                    if((round($porcentajeObligatoriosBasicos,1)>='85' && round($porcentajeObligatoriosBasicos,1)<='95') && (round($porcentajeObligatoriosComplementarios,1)>='5') && (round($porcentajeObligatoriosComplementarios,1)<='15'))
                                                        {

                                                        }else
                                                            {
                                                                $vista.="<tr><td colspan='5' class='centrar'><table class='contenidotabla centrar'><tr><td class='centrar'><img src='".$configuracion['site'].$configuracion['grafico']."/error.png' width='35' height='35' alt='Continuar' border='0'><br>El porcentaje de obligatorios basicos y complementarios no cumple con lo establecido en el acuerdo 009 de 2006</td><tr></table></td></tr>";
                                                            }

                                                    if($porcentajeElectivos>='0' && $porcentajeElectivos<='100')
                                                        {

                                                            if($porcentajeElectivos>='15' && $porcentajeElectivos<='20')
                                                            {

                                                            }else
                                                                {
                                                                    $vista.= "<tr><td colspan='5' class='centrar'><table class='contenidotabla centrar'><tr><td class='centrar'><img src='".$configuracion['site'].$configuracion['grafico']."/error.png' width='35' height='35' alt='Continuar' border='0'><br>El porcentaje de electivos no cumple con lo establecido en el acuerdo 009 de 2006</td><tr></table></td></tr>";
                                                                }

                                                            if((round($porcentajeElectivosIntrinsecos,1)>='0' && round($porcentajeElectivosIntrinsecos,1)<='100') && (round($porcentajeElectivosExtrinsecos,1)>='0' && round($porcentajeElectivosExtrinsecos,1)<='100'))
                                                                {

                                                                    if((round($porcentajeElectivosIntrinsecos,1)>='65' && round($porcentajeElectivosIntrinsecos,1)<='75') && (round($porcentajeElectivosExtrinsecos,1)>='25' && round($porcentajeElectivosExtrinsecos,1)<='35'))
                                                                    {

                                                                    }else
                                                                        {
                                                                            $vista.="<tr><td colspan='5' class='centrar'><table class='contenidotabla centrar'><tr><td class='centrar'><img src='".$configuracion['site'].$configuracion['grafico']."/error.png' width='35' height='35' alt='Continuar' border='0'><br>El porcentaje de electivos intrinsecos y extrinsecos no cumple con lo establecido en el acuerdo 009 de 2006</td><tr></table></td></tr>";
                                                                        }

                                                                            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                                                            $ruta="pagina=adminAprobarEspacioPlan";
                                                                            $ruta.="&opcion=aprobarParametros";
                                                                            $ruta.="&totalCreditos=".$totalCreditos;
                                                                            $ruta.="&OB=".$OB;
                                                                            $ruta.="&OC=".$OC;
                                                                            $ruta.="&EI=".$EI;
                                                                            $ruta.="&EE=".$EE;
                                                                            $ruta.="&CP=".$CP;
                                                                            $ruta.="&planEstudio=".$planEstudio;
                                                                            $ruta.="&codProyecto=".$proyecto;
                                                                            $ruta.="&nombreProyecto=".$nombreProyecto;

                                                                            $ruta=$cripto->codificar_url($ruta,$configuracion);

                                                                            $vista.="<tr align='center'><td colspan='5' align='center'>
                                                                                <a href='".$pagina.$ruta."'>
                                                                                <img src='".$configuracion['site'].$configuracion['grafico']."/3floppy_mount.png' width='35' height='35' alt='Continuar' border='0'><br>Guardar datos
                                                                                </a></td></tr>
                                                                            ";

                                                                }else
                                                                    {
                                                                        $vista.="<tr><td colspan='5' class='centrar'><table class='contenidotabla centrar'><tr><td class='centrar'><img src='".$configuracion['site'].$configuracion['grafico']."/error.png' width='35' height='35' alt='Continuar' border='0'><br>El porcentaje de electivos intrinsecos y extrinsecos no cumple con lo establecido en el acuerdo 009 de 2006</td><tr></table></td></tr>";

                                                                    }
                                                        }else
                                                            {
                                                                $vista.= "<tr><td colspan='5' class='centrar'><table class='contenidotabla centrar'><tr><td class='centrar'><img src='".$configuracion['site'].$configuracion['grafico']."/error.png' width='35' height='35' alt='Continuar' border='0'><br>El porcentaje de electivos no cumple con lo establecido en el acuerdo 009 de 2006</td><tr></table></td></tr>";

                                                            }
                                                }else
                                                    {
                                                        $vista.="<tr><td colspan='5' class='centrar'><table class='contenidotabla centrar'><tr><td class='centrar'><img src='".$configuracion['site'].$configuracion['grafico']."/error.png' width='35' height='35' alt='Continuar' border='0'><br>El porcentaje de obligatorios basicos y complementarios no cumple con lo establecido en el acuerdo 009 de 2006</td><tr></table>";

                                                    }
                                        }else
                                            {
                                                $vista.= "<tr><td colspan='5' class='centrar'><table class='contenidotabla centrar'><tr><td class='centrar'><img src='".$configuracion['site'].$configuracion['grafico']."/error.png' width='35' height='35' alt='Continuar' border='0'><br>El porcentaje de obligatorios no cumple con lo establecido en el acuerdo 009 de 2006</td><tr></table></td></tr>";

                                            }
                        }

                        $vista.="</table>";

            $respuesta = new xajaxResponse();
            $respuesta->addAssign("div_graficas","innerHTML",$vista);
            return $respuesta;
        }
    }

?>