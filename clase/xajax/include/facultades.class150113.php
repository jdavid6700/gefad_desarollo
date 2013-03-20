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

    

    function facultad($valor)
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
    $accesoGestion=$conexion->estableceConexion(99,$configuracion);
    $accesoOracle=$conexion->estableceConexion(75,$configuracion);

    //pestañasFacultad($valor);

    if (is_resource($enlace))
        {
            $variablesEvento=array('ano'=>2012,
                                'periodo'=>3);
            $cadena_sql = sql_facultad($configuracion, "buscarEventoGestionPlanes",$variablesEvento);
            $fechasEventoPlanes = $funcion->ejecutarSQL($configuracion,$accesoOracle,$cadena_sql, "busqueda");
            $fecha=  date('Ymd');
            if($fecha>$fechasEventoPlanes[0]['FIN'])
            {
                $permiso=0;
            }else{$permiso=1;}

            $cadena_sql=sql_facultad($configuracion,"datosFacultad", $valor);
            $resultadoPlan=$funcion->ejecutarSQL($configuracion, $accesoGestion, $cadena_sql,"busqueda");

            $mostrar="<table class='contenidotabla'>";

            for($i=0;$i<count($resultadoPlan);$i++)
            {
                $cadena_sql=sql_facultad($configuracion, "consultaEspacioPlan", $resultadoPlan[$i][5]);
                $resultadoElectivos=$funcion->ejecutarSQL($configuracion, $accesoGestion, $cadena_sql,"busqueda");

                if(is_array($resultadoElectivos))
                    {
                        $mostrar.="<tr><td colspan='12' align='center'><b>".$resultadoPlan[$i][4]." - ".$resultadoPlan[$i][0]."<br>PLAN ESTUDIO:".$resultadoPlan[$i][5]."</b></h2></td></tr>
                                        <tr class='cuadro_color'>
                                        <td class='cuadro_plano centrar'>Cod. </td>
                                        <td class='cuadro_plano centrar'>Nombre </td>
                                        <td class='cuadro_plano centrar'>N&uacute;mero<br>Cr&eacute;ditos</td>
                                        <td class='cuadro_plano centrar'>HTD </td>
                                        <td class='cuadro_plano centrar'>HTC </td>
                                        <td class='cuadro_plano centrar'>HTA </td>
                                        <td class='cuadro_plano centrar'>Clasificaci&oacute;n </td>
                                        <td class='cuadro_plano centrar' colspan='2'>Aprobar </td>
                                        <td class='cuadro_plano centrar' >Modificar</td>
                                        </tr>";

                        for($a=0;$a<count($resultadoElectivos);$a++)
                        {
                            
                            $comentarioVar=array($resultadoElectivos[$a][0], $resultadoElectivos[$a][12]);
                            //Busca los comentarios no leidos
                            $cadena_sql=sql_facultad($configuracion,"comentariosNoLeidos",$comentarioVar);//echo $this->cadena_sql;exit;
                            $comentariosNoLeidos=$funcion->ejecutarSQL($configuracion, $accesoGestion, $cadena_sql,"busqueda" );

                            $mostrar.="<tr>";
                            $mostrar.="<td class='cuadro_plano centrar'>".$resultadoElectivos[$a][0]."</td>";
                            $mostrar.="<td class='cuadro_plano'>".$resultadoElectivos[$a][1]."</td>";
                                //$mostrar.="<br>".(48*$resultadoElectivos[$a][3]);
                                //$mostrar.="<br>".$resultadoElectivos[$a][4]."-".$resultadoElectivos[$a][5]."-".$resultadoElectivos[$a][6]."-".$resultadoElectivos[$a][13];
                            if((48*$resultadoElectivos[$a][3])==(($resultadoElectivos[$a][4]+$resultadoElectivos[$a][5]+$resultadoElectivos[$a][6])*$resultadoElectivos[$a][13]))
                                {
                                    $mostrar.="<td class='cuadro_plano centrar'>".$resultadoElectivos[$a][3]."</td>";
                                    $mostrar.="<td class='cuadro_plano centrar'>".$resultadoElectivos[$a][4]."</td>";
                                    $mostrar.="<td class='cuadro_plano centrar'>".$resultadoElectivos[$a][5]."</td>";
                                    $mostrar.="<td class='cuadro_plano centrar'>".$resultadoElectivos[$a][6]."</td>";
                                }
                                else
                                    {
                                        $mostrar.="<td class='cuadro_plano centrar'><font color='red'>".$resultadoElectivos[$a][3]."</font></td>";
                                        $mostrar.="<td class='cuadro_plano centrar'><font color='red'>".$resultadoElectivos[$a][4]."</font></td>";
                                        $mostrar.="<td class='cuadro_plano centrar'><font color='red'>".$resultadoElectivos[$a][5]."</font></td>";
                                        $mostrar.="<td class='cuadro_plano centrar'><font color='red'>".$resultadoElectivos[$a][6]."</font></td>";
                                    }

                            $mostrar.="<td class='cuadro_plano'>".$resultadoElectivos[$a][7]."</td>";

                            //verifica que este aprobado el espacio academico
                            switch ($registro[$a][8])
                            {
                                case '1':
                                    $ob++;
                                    $creob+=$resultadoElectivos[$a][3];
                                break;
                                case '2':
                                    $oc++;
                                    $creoc+=$resultadoElectivos[$a][3];
                                break;
                                case '3':
                                    $ei++;
                                    $creei+=$resultadoElectivos[$a][3];
                                break;
                                case '4':
                                    $ee++;
                                    $creee+=$resultadoElectivos[$a][3];
                                break;
                            }

                            $porNotas=='0';
                            $porInscripcion=='0';
                            $porHorario=='0';

                            if ($resultadoElectivos[$a][11]=='1')
                                {

                                    $mostrar.="<td class='cuadro_plano centrar' colspan='2'>";
                                    $mostrar.="Aprobado";
                                    $mostrar.="</td><td class='cuadro_plano centrar'>";

                                    if($porNotas=='1' || $porInscripcion=='1' || $porHorario=='1')
                                        {
                                            $mostrar.="<div class='centrar'>";
                                            $mostrar.="<img src='".$configuracion['site'].$configuracion['grafico']."/error.png' width='25' height='25' border='0'>";
                                            $mostrar.="</div>";
                                        }
                                        else
                                            {
        if($permiso==1)
        {

                                            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                            $ruta="pagina=registro_aprobarPortafolio";
                                            $ruta.="&opcion=modificarEspacio";
                                            $ruta.="&codEspacio=".$resultadoElectivos[$a][0];
                                            $ruta.="&planEstudio=".$resultadoElectivos[$a][12];
                                            $ruta.="&nivel=".$resultadoElectivos[$a][2];
                                            $ruta.="&creditos=".$resultadoElectivos[$a][3];
                                            $ruta.="&htd=".$resultadoElectivos[$a][4];
                                            $ruta.="&htc=".$resultadoElectivos[$a][5];
                                            $ruta.="&hta=".$resultadoElectivos[$a][6];
                                            $ruta.="&clasificacion=".$resultadoElectivos[$a][8];
                                            $ruta.="&nombreEspacio=". $resultadoElectivos[$a][1];
                                            $ruta.="&semanas=".$resultadoElectivos[$a][13];
                                            $ruta.="&facultad=".$valor;

                                            $ruta=$cripto->codificar_url($ruta,$configuracion);

                                            $mostrar.="<a href='".$pagina.$ruta."' class='enlace centrar'>";
                                            $mostrar.="<img src='".$configuracion['site'].$configuracion['grafico']."/modificar.png' width='25' height='25' border='0'>";
                                            $mostrar.="</a>";
                                            }
                                            }
                                            $mostrar.="</td>";
                                            $mostrar.="</tr>";

                                 }
                                 else if ($resultadoElectivos[$a][11]=='2')
                                     {

                                        $mostrar.="<td class='cuadro_plano centrar' colspan='2'>No aprobado</td>";
                                        $mostrar.="<td class='cuadro_plano centrar'>";

                                        if($porNotas=='1' || $porInscripcion=='1' || $porHorario=='1')
                                            {

                                            $mostrar.="<div class='centrar'>";
                                            $mostrar.="<img src='".$configuracion['site'].$configuracion['grafico']."/error.png' width='25' height='25' border='0'";
                                            $mostrar.="</div>";

                                            }
                                            else
                                                {
        if($permiso==1)
        {

                                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                                $ruta="pagina=registro_aprobarPortafolio";
                                                $ruta.="&opcion=modificarEspacio";
                                                $ruta.="&codEspacio=".$resultadoElectivos[$a][0];
                                                $ruta.="&planEstudio=".$resultadoElectivos[$a][12];
                                                $ruta.="&nivel=".$resultadoElectivos[$a][2];
                                                $ruta.="&creditos=".$resultadoElectivos[$a][3];
                                                $ruta.="&htd=".$resultadoElectivos[$a][4];
                                                $ruta.="&htc=".$resultadoElectivos[$a][5];
                                                $ruta.="&hta=".$resultadoElectivos[$a][6];
                                                $ruta.="&clasificacion=".$resultadoElectivos[$a][8];
                                                $ruta.="&nombreEspacio=". $resultadoElectivos[$a][1];
                                                $ruta.="&semanas=".$resultadoElectivos[$a][13];
                                                $ruta.="&facultad=".$valor;

                                                $ruta=$cripto->codificar_url($ruta,$configuracion);

                                                $mostrar.="<a href='".$pagina.$ruta."' class='enlace centrar'>";
                                                $mostrar.="<img src='".$configuracion['site'].$configuracion['grafico']."/modificar.png' width='25' height='25' border='0'>";
                                                $mostrar.="</a>";
        }
                                                }

                                                $mostrar.="</td></tr>";
                                      }
                                      else {

                                                $mostrar.="<td class='cuadro_plano centrar'>";
        if($permiso==1)
        {

                                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                                $ruta="pagina=registro_aprobarPortafolio";
                                                $ruta.="&opcion=aprobarElectiva";
                                                $ruta.="&codEspacio=".$resultadoElectivos[$a][0];
                                                $ruta.="&planEstudio=".$resultadoElectivos[$a][12];
                                                $ruta.="&nivel=".$resultadoElectivos[$a][2];
                                                $ruta.="&creditos=".$resultadoElectivos[$a][3];
                                                $ruta.="&htd=".$resultadoElectivos[$a][4];
                                                $ruta.="&htc=".$resultadoElectivos[$a][5];
                                                $ruta.="&hta=".$resultadoElectivos[$a][6];
                                                $ruta.="&clasificacion=".$resultadoElectivos[$a][8];
                                                $ruta.="&nombreEspacio=". $resultadoElectivos[$a][1];
                                                $ruta.="&facultad=".$valor;

                                                $ruta=$cripto->codificar_url($ruta,$configuracion);

                                                $mostrar.="<a href='".$pagina.$ruta."' class='enlace centrar'>";
                                                $mostrar.="<img src='".$configuracion['site'].$configuracion['grafico']."/clean.png' width='25' height='25' border='0'>";
        }
                                                $mostrar.="</td><td class='cuadro_plano centrar'>";
        if($permiso==1)
        {

                                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                                $ruta="pagina=registro_aprobarPortafolio";
                                                $ruta.="&opcion=noAprobar";
                                                $ruta.="&codEspacio=".$resultadoElectivos[$a][0];
                                                $ruta.="&planEstudio=".$resultadoElectivos[$a][12];
                                                $ruta.="&nivel=".$resultadoElectivos[$a][2];
                                                $ruta.="&creditos=".$resultadoElectivos[$a][3];
                                                $ruta.="&htd=".$resultadoElectivos[$a][4];
                                                $ruta.="&htc=".$resultadoElectivos[$a][5];
                                                $ruta.="&hta=".$resultadoElectivos[$a][6];
                                                $ruta.="&clasificacion=".$resultadoElectivos[$a][8];
                                                $ruta.="&nombreEspacio=". $resultadoElectivos[$a][1];
                                                $ruta.="&facultad=".$valor;

                                                $ruta=$cripto->codificar_url($ruta,$configuracion);

                                                $mostrar.="<a href='".$pagina.$ruta."' class='enlace centrar'>";
                                                $mostrar.="<img src='".$configuracion['site'].$configuracion['grafico']."/x.png' width='25' height='25' border='0'>";
        }
                                                $mostrar.="</td><td class='cuadro_plano centrar'>";

                                                if($porNotas=='1' || $porInscripcion=='1' || $porHorario=='1')
                                                {

                                                    $mostrar.="<div class='centrar'>";
                                                    $mostrar.="<img src='".$configuracion['site'].$configuracion['grafico']."/error.png' width='25' height='25' border='0'>";
                                                    $mostrar.="</div>";

                                                }
                                                else
                                                    {
        if($permiso==1)
        {

                                                        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                                        $ruta="pagina=registro_aprobarPortafolio";
                                                        $ruta.="&opcion=modificarEspacio";
                                                        $ruta.="&codEspacio=".$resultadoElectivos[$a][0];
                                                        $ruta.="&planEstudio=".$resultadoElectivos[$a][12];
                                                        $ruta.="&nivel=".$resultadoElectivos[$a][2];
                                                        $ruta.="&creditos=".$resultadoElectivos[$a][3];
                                                        $ruta.="&htd=".$resultadoElectivos[$a][4];
                                                        $ruta.="&htc=".$resultadoElectivos[$a][5];
                                                        $ruta.="&hta=".$resultadoElectivos[$a][6];
                                                        $ruta.="&clasificacion=".$resultadoElectivos[$a][8];
                                                        $ruta.="&nombreEspacio=". $resultadoElectivos[$a][1];
                                                        $ruta.="&semanas=".$resultadoElectivos[$a][13];
                                                        $ruta.="&facultad=".$valor;

                                                        $ruta=$cripto->codificar_url($ruta,$configuracion);

                                                        $mostrar.="<a href='".$pagina.$ruta."' class='enlace centrar'>";
                                                        $mostrar.="<img src='".$configuracion['site'].$configuracion['grafico']."/modificar.png' width='25' height='25' border='0'>";
                                                        $mostrar.="</a>";
        }
                                                    }

                                                    $mostrar.="</td></tr>";
                        }
                    }
                    }
            }

            $mostrar.="</table>";
            

            $respuesta = new xajaxResponse();
            $respuesta->addAssign("cuerpopestanas","innerHTML",$mostrar);
            
            $cadena_sql=sql_facultad($configuracion,"buscar_facultad",'');//echo $cadena_sql;exit;
            $registroFacultad=$funcion->ejecutarSQL($configuracion, $accesoGestion, $cadena_sql,"busqueda" );//var_dump($registroFacultad);

            $respuesta->addAssign("pestana" . $valor, "className", "pestanaseleccionada");
            $respuesta->addAssign("pestanalink" . $valor, "className", "pestanaseleccionada");
               //Pongo la class css de las pestañas sin pulsar
            for ($h=0; $h<=count($registroFacultad); $h++){
                  if ($h != $valor){
                     $respuesta->addAssign("pestanalink" . $h, "className", "pestanainactiva");
                     $respuesta->addAssign("pestana" . $h, "className", "pestanainactiva");
                  }
               }

            return $respuesta;
            break;
           
        }
    }
    
  

    function sql_facultad($configuracion,$opcion,$variable)
    {
        switch($opcion)
        {
            case "datosFacultad":
                    $cadena_sql="SELECT PC.proyecto_nombre, PC.id_facultad, PC.id_facultad_academica, ";
                    $cadena_sql.="FAC.nombre_facultad, PC.id_proyectoAcademica, PEP.planEstudioProyecto_idPlanEstudio ";
                    $cadena_sql.="FROM sga_proyectoCurricular PC ";
                    $cadena_sql.="INNER JOIN sga_planEstudio_proyecto PEP ON PEP.planEstudioProyecto_idProyectoCurricular = PC.id_proyectoAcademica ";
                    $cadena_sql.="INNER JOIN sga_facultad FAC ON FAC.id_facultad= PC.id_facultad ";
                    $cadena_sql.="WHERE PC.id_facultad =".$variable;
                break;

            case "consultaEspacioPlan":
                $cadena_sql="SELECT ESPACIO.id_espacio, ";
                $cadena_sql.="ESPACIO.espacio_nombre, ";
                $cadena_sql.="PLAN_ESPACIO.id_nivel, ";
                $cadena_sql.="espacio_nroCreditos, ";
                $cadena_sql.="horasDirecto, ";        //tabla planEstudioEspacio
                $cadena_sql.="horasCooperativo, ";    //tabla planEstudioEspacio
                $cadena_sql.="espacio_horasAutonomo, ";
                $cadena_sql.="CLASIFICACION.clasificacion_nombre, ";
                $cadena_sql.="CLASIFICACION.id_clasificacion, ";
                $cadena_sql.="REL_ELECTIVO.id_nombreElectivo, ";  //registro[9]
                $cadena_sql.="ELECTIVO.nombreElectivo, ";
                $cadena_sql.="PLAN_ESPACIO.id_aprobado, ";        //registro[11]
                $cadena_sql.="PLAN_ESPACIO.id_planEstudio, ";      //registro[12]
                $cadena_sql.="PLAN_ESPACIO.semanas ";      //registro[13]
                $cadena_sql.="FROM sga_espacio_academico AS ESPACIO ";
                $cadena_sql.="INNER JOIN sga_planEstudio_espacio AS PLAN_ESPACIO ";
                $cadena_sql.="ON PLAN_ESPACIO.id_espacio = ESPACIO.id_espacio ";
                $cadena_sql.="INNER JOIN sga_espacio_clasificacion AS CLASIFICACION ";
                $cadena_sql.="ON CLASIFICACION.id_clasificacion = PLAN_ESPACIO.id_clasificacion ";
                $cadena_sql.="LEFT OUTER JOIN sga_espacioNombreElectivo AS REL_ELECTIVO ";
                $cadena_sql.="ON PLAN_ESPACIO.id_espacio = REL_ELECTIVO.id_espacio ";
                $cadena_sql.="LEFT OUTER JOIN sga_nombreElectivo AS ELECTIVO ";
                $cadena_sql.="ON REL_ELECTIVO.id_nombreElectivo = ELECTIVO.id_nombreElectivo ";
                $cadena_sql.="WHERE PLAN_ESPACIO.id_planEstudio=".$variable." ";
                $cadena_sql.=" AND PLAN_ESPACIO.id_estado=1 ";
                $cadena_sql.=" AND PLAN_ESPACIO.id_clasificacion=4 ";
                //$cadena_sql.="AND PLAN_ESPACIO.id_espacio not in (SELECT DISTINCT id_espacio FROM sga_espacioEncabezado WHERE id_planEstudio =".$variable." ) ";
                $cadena_sql.="ORDER BY ESPACIO.id_espacio, REL_ELECTIVO.id_nombreElectivo, ESPACIO.espacio_nombre ASC";
                break;

            case "modificarEspacio_notas":
                $cadena_sql="SELECT count(*) FROM ACNOT ";
                $cadena_sql.=" WHERE not_asi_cod=".$variable;
                break;

            case "modificarEspacio_inscripcion":
                $cadena_sql="SELECT count(*) FROM ACINS ";
                $cadena_sql.=" WHERE ins_asi_cod=".$variable;
                break;

            case "modificarEspacio_horario":
                $cadena_sql="SELECT count(*) FROM achorario ";
                $cadena_sql.=" WHERE hor_asi_cod=".$variable;
                break;

            case 'buscarParametros':

                $cadena_sql="select distinct parametro_creditosPlan,parametros_OB,parametros_OC,parametros_EI,parametros_EE ";
                $cadena_sql.="from ".$configuracion['prefijo']."parametro_plan_estudio ";
                $cadena_sql.=" where parametro_idPlanEstudio=".$variable;

                break;

            case "buscar_facultad":

                $cadena_sql="SELECT id_facultad, nombre_facultad ";
                $cadena_sql.="FROM sga_facultad ";

                break;

            case 'buscarEventoGestionPlanes':
                $cadena_sql=" select to_char(ace_fec_ini, 'yyyymmdd') INICIO,";
                $cadena_sql.=" to_char(ace_fec_fin, 'yyyymmdd') FIN";
                $cadena_sql.=" from accaleventos";
                $cadena_sql.=" where ace_anio=".$variable['ano'];
                $cadena_sql.=" and ace_periodo=".$variable['periodo'];
                $cadena_sql.=" and ace_cra_cod=0";
                $cadena_sql.=" and ace_cod_evento=86";
                break;
            
        }

        return $cadena_sql;
    
    }
        
        

?>