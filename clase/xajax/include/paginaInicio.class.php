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

    

    function mensaje($valor)
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
            switch ($valor)
                {
                    case 'horario_des':
                        $html_des="<table width='100%' class='sigma' bgcolor='#F3F6FA'>
                                        <tr class='sigma_a'>
                                            <td  class='sigma'>
                                             En este momento no se encuentran fechas habilitadas para generaci&oacute;n de horarios
                                             <br>
                                             La creaci&oacute;n de horarios se realiza temporalmente por la aplicaci&oacute;n acad&eacute;mica
                                            </td>
                                        </tr>";
                        $html_des.="</table>";
                        break;

                    case 'horario_hab':
                        $html_des="<table width='100%' class='sigma'  bgcolor='#F3F6FA'>
                                        <tr  class='sigma_a'>
                                            <td class='sigma'>
                                             En este momento esta habilitada la aplicaci&oacute;n acad&eacute;mica para generaci&oacute;n de horarios
                                             <br>
                                             La creaci&oacute;n de horarios se realiza temporalmente por la aplicaci&oacute;n acad&eacute;mica
                                            </td>
                                        </tr>";
                        $html_des.="</table>";
                        break;

                    case 'preinscripcion_des':
                        $html_des="<table width='100%' class='sigma' bgcolor='#F3F6FA'>
                                        <tr class='sigma_a'>
                                            <td  class='sigma'>
                                             Hasta el momento no se ha ejecutado la preinscripci&oacute;n autom&aacute;tica.
                                             <br>
                                             Este proceso se realiza por la aplicaci&oacute;n acad&eacute;mica o C&oacute;ndor (Perfil Coordinador).
                                            </td>
                                        </tr>";
                        $html_des.="</table>";
                        break;

                    case 'preinscripcion_hab':
                        $html_des="<table width='100%' class='sigma'  bgcolor='#F3F6FA'>
                                        <tr  class='sigma_a'>
                                            <td class='sigma'>
                                             Ya se ha ejecutado la preinscripci&oacute;n automatica. Continúe con los procesos de Inscripci&oacute;n de Estudiantes Nuevos y Adiciones y Cancelaciones.
                                            </td>
                                        </tr>";
                        $html_des.="</table>";
                        break;

                    case 'inscripcion_des':
                        $html_des="<table width='100%' class='sigma' bgcolor='#F3F6FA'>
                                        <tr class='sigma_a'>
                                            <td  class='sigma'>
                                             En este momento no se encuentran fechas habilitadas para adiciones y cancelaciones
                                             <br>
                                             Puede consultar las inscripciones dando click sobre la imagen de adiciones y cancelaciones
                                            </td>
                                        </tr>";
                        $html_des.="</table>";
                        break;

                    case 'inscripcion_hab':
                        $html_des="<table width='100%' class='sigma'  bgcolor='#F3F6FA'>
                                        <tr  class='sigma_a'>
                                            <td class='sigma'>
                                             En este momento esta habilitado el sistema para realizar adiciones y cancelaciones
                                             <br>
                                             Puede realizar inscripciones dando click sobre la imagen de adiciones y cancelaciones
                                            </td>
                                        </tr>";
                        $html_des.="</table>";
                        break;
                    
                    case 'grupoNuevo_des':
                        $html_des="<table width='100%' class='sigma' bgcolor='#F3F6FA'>
                                        <tr class='sigma_a'>
                                            <td  class='sigma'>
                                             En este momento no se encuentran fechas habilitadas para realizar inscripci&oacute;n de espacios acad&eacute;micos a estudiantes nuevos
                                             <br>
                                            </td>
                                        </tr>";
                        $html_des.="</table>";
                        break;

                    case 'grupoNuevo_hab':
                        $html_des="<table width='100%' class='sigma'  bgcolor='#F3F6FA'>
                                        <tr  class='sigma_a'>
                                            <td class='sigma'>
                                             En este momento se encuentra habilitado el sistema para realizar inscripci&oacute;n de espacios acad&eacute;micos a estudiantes nuevos
                                             <br>
                                             Puede realizar inscripciones dando click sobre la imagen de Inscripci&oacute;n Nuevos
                                            </td>
                                        </tr>";
                        $html_des.="</table>";
                        break;

                    default :
                        $html_des="<table width='100%' class='sigma'>
                                        <tr>
                                            <td>
                                            </td>
                                        </tr>";
                        $html_des.="</table>";
                        break;
                }

            $respuesta = new xajaxResponse();
            $respuesta->addAssign("mensajeProceso","innerHTML",$html_des);
            return $respuesta;
            
       }
    }
    
?>