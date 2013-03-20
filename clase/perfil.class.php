<?
/*
############################################################################
#    UNIVERSIDAD DISTRITAL Francisco Jose de Caldas                        #
#    Desarrollo Por:                                                       #
#    Paulo Cesar Coronado
#    Copyright: Vea el archivo EULA.txt que viene con la distribucion      #
############################################################################
*//*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/
/*--------------------------------------------------------------------------------------------------------------------------
* @name          perfil.class.php 
* @author        Maritza Callejas
* @revision      Última revisión 20 de enero de 2010
/*--------------------------------------------------------------------------------------------------------------------------
* @subpackage		
* @package		clase
* @copyright    	Universidad Distrital Francisco Jose de Caldas
* @version      		0.0.0.1
* @author			Maritza Callejas
* @author			Oficina Asesora de Sistemas
* @link			N/D
* @description  	Clase para gestionar los perfiles en los bloques y opciones,
*                       de igual forma las opciones que los usuarios utilizan.
*
/*--------------------------------------------------------------------------------------------------------------------------*/

class perfil
{

	function perfil()
	{
		
		
	}
	
        function verifica_opcion($opciones,$nombre_opcion){
            $band=0;
            $totalOpciones = count($opciones);
            for($i=0; $i<$totalOpciones; $i++){
                if ($opciones[$i][3] == $nombre_opcion){
                    $band=1;
                }
            }
            return $band;
        }

        function consultar_opciones_perfil($configuracion, $conexion, $variable){

                //consultamos las opciones a las que tiene acceso dependiendo del perfil.
            if ($variable[1]!= 1){//perfil diferente de personalizado
                $cadena_sql="SELECT ";
		$cadena_sql.="`pop_id_perfil`, ";
		$cadena_sql.="`pop_id_opcion`, ";
		$cadena_sql.="`opp_nombre`, ";
                $cadena_sql.="`nombre` ";
		$cadena_sql.="FROM ";
		$cadena_sql.=$configuracion["prefijo"]."perfil_opcion ";
                $cadena_sql.="INNER JOIN ";
                $cadena_sql.= $configuracion["prefijo"]."bloque_opcion ";
                $cadena_sql.="ON pop_id_opcion=opp_id_opcion ";
                $cadena_sql.="INNER JOIN ";
                $cadena_sql.= $configuracion["prefijo"]."bloque ";
                $cadena_sql.="ON id_bloque=opp_id_bloque ";
		$cadena_sql.="WHERE ";
		$cadena_sql.="pop_estado = 'A'";
		$cadena_sql.=" AND pop_id_perfil = ".$variable[1];
                 if($variable[3])
                        $cadena_sql.=" AND pop_id_opcion = ".$variable[3];

            }else{
                $cadena_sql="SELECT ";
		$cadena_sql.="`rop_id_registrado`, ";
		$cadena_sql.="`rop_id_opcion`, ";
		$cadena_sql.="`opp_nombre`, ";
                $cadena_sql.="`nombre` ";
		$cadena_sql.="FROM ";
		$cadena_sql.=$configuracion["prefijo"]."registrado_opcion_personalizado ";
                $cadena_sql.="INNER JOIN ";
                $cadena_sql.= $configuracion["prefijo"]."bloque_opcion ";
                $cadena_sql.="ON rop_id_opcion=opp_id_opcion ";
                $cadena_sql.="INNER JOIN ";
                $cadena_sql.= $configuracion["prefijo"]."bloque ";
                $cadena_sql.="ON id_bloque=opp_id_bloque ";
		$cadena_sql.="WHERE ";
		$cadena_sql.="rop_estado = 'A'";
		$cadena_sql.=" AND rop_id_registrado = ".$variable[2];
                 if($variable[3])
                        $cadena_sql.=" AND rop_id_opcion = ".$variable[3];

            }
                
                $registro = $conexion->ejecutarAcceso($cadena_sql,"busqueda");
//echo "<br>".$cadena_sql;
                return $registro;
        }

	function consultar_procesos_perfil($configuracion, $conexion, $variable=""){
                //consultamos los procesos a los que tiene acceso dependiendo del perfil.
		$cadena_sql = "SELECT ";
		$cadena_sql.= "pro_id_proceso idProceso, ";
		$cadena_sql.= "pro_nombre nombre, ";
		$cadena_sql.= "pro_id_tipo_proceso idTipoProceso, ";
		$cadena_sql.= "pro_estado estado, ";
		$cadena_sql.= "cre_ubicacion_archivos creUbicacionArchivos, ";
		$cadena_sql.= "cre_nombre_dbms nombreDbms, ";
                $cadena_sql.= "cre_reporte_xdefecto reporteXdefecto, ";
                $cadena_sql.= "cre_parametros_xdefecto parametrosXdefecto ";
		$cadena_sql.= "FROM ";
		$cadena_sql.= $configuracion["prefijo"]."proceso ";
		$cadena_sql.="INNER JOIN ";
		$cadena_sql.= $configuracion["prefijo"]."config_reporte ";
		$cadena_sql.="ON pro_id_proceso=cre_id_proceso ";
                if ($variable[1]!= 1){//perfil diferente de personalizado
                    $cadena_sql.="INNER JOIN ";
                    $cadena_sql.= $configuracion["prefijo"]."perfil_proceso ";
                    $cadena_sql.="ON pro_id_proceso=pep_id_proceso ";
                }else{
                    $cadena_sql.="INNER JOIN ";
                    $cadena_sql.= $configuracion["prefijo"]."registrado_proceso_personalizado ";
                    $cadena_sql.="ON rpp_id_proceso = pro_id_proceso ";
                    
                }
                if ($variable[1]!= 1){//perfil diferente de personalizado
                    $cadena_sql.="WHERE ";
                    $cadena_sql.="pro_id_tipo_proceso = ".$variable[0];
                    $cadena_sql.=" AND pro_estado = 'A'";
                    $cadena_sql.=" AND pep_id_perfil = ".$variable[1];
                    $cadena_sql.=" AND pep_estado = 'A'";
                }else{
                    $cadena_sql.="WHERE ";
                    $cadena_sql.="pro_id_tipo_proceso = ".$variable[0];
                    $cadena_sql.=" AND pro_estado = 'A'";
                    $cadena_sql.=" AND rpp_id_registrado = ".$variable[3];
                    $cadena_sql.=" AND rpp_estado = 'A'";
                }


                if($variable[2])
                    $cadena_sql.=" AND pro_id_proceso = ".$variable[2];


                $registro = $conexion->ejecutarAcceso($cadena_sql,"busqueda");
//echo  "<br><br>".$cadena_sql;
                return $registro;

        }

	function consultar_aspectos_perfil($configuracion, $conexion, $variable=""){
                //consultamos los aspectos de procesos a los que tiene acceso dependiendo del perfil.
		$cadena_sql = "SELECT ";
		$cadena_sql.= "apr_id_aspecto, ";
		$cadena_sql.= "apr_nombre, ";
		$cadena_sql.= "apr_id_proceso, ";
		$cadena_sql.= "apr_estado, ";
		$cadena_sql.= "apr_carpeta_aspecto, ";
		$cadena_sql.= "cre_ubicacion_archivos, ";
		$cadena_sql.= "cre_nombre_dbms, ";
                $cadena_sql.= "cre_reporte_xdefecto, ";
                $cadena_sql.= "cre_parametros_xdefecto ";
		$cadena_sql.= "FROM ";
		$cadena_sql.= $configuracion["prefijo"]."aspecto_proceso ";
		$cadena_sql.="INNER JOIN ";
		$cadena_sql.= $configuracion["prefijo"]."proceso ";
		$cadena_sql.="ON pro_id_proceso=apr_id_proceso ";
                $cadena_sql.="INNER JOIN ";
		$cadena_sql.= $configuracion["prefijo"]."config_reporte ";
		$cadena_sql.="ON pro_id_proceso=cre_id_proceso ";
                if ($variable[1]!= 1){//perfil diferente de personalizado
                    $cadena_sql.="INNER JOIN ";
                    $cadena_sql.= $configuracion["prefijo"]."perfil_proceso ";
                    $cadena_sql.="ON pro_id_proceso=pep_id_proceso ";
                }else{
                    $cadena_sql.="INNER JOIN ";
                    $cadena_sql.= $configuracion["prefijo"]."registrado_proceso_personalizado ";
                    $cadena_sql.="ON rpp_id_proceso = pro_id_proceso ";

                }
                if ($variable[1]!= 1){//perfil diferente de personalizado
                    $cadena_sql.="WHERE ";
                    $cadena_sql.="pro_id_proceso = ".$variable[0];
                    $cadena_sql.=" AND pro_estado = 'A'";
                    $cadena_sql.=" AND pep_id_perfil = ".$variable[1];
                    $cadena_sql.=" AND apr_estado = 'A'";
                }else{
                    $cadena_sql.="WHERE ";
                    $cadena_sql.="pro_id_proceso = ".$variable[0];
                    $cadena_sql.=" AND pro_estado = 'A'";
                    $cadena_sql.=" AND rpp_id_registrado = ".$variable[3];
                    $cadena_sql.=" AND apr_estado = 'A'";
                }


                if($variable[2])
                    $cadena_sql.=" AND apr_id_aspecto = ".$variable[2];

               
                $registro = $conexion->ejecutarAcceso($cadena_sql,"busqueda");
                //echo  "<br><br>".$cadena_sql;
                return $registro;

        }


        function inserta_vista($configuracion, $conexion, $variable){
            $cadena_sql = "INSERT INTO ";
            $cadena_sql.= $configuracion["prefijo"]."vistas_opcion ";
            $cadena_sql.= "(";
            $cadena_sql.= "vop_fecha_visto, ";
            $cadena_sql.= "vop_id_usuario, ";
            $cadena_sql.= "vop_id_proceso,";
            $cadena_sql.= "vop_id_opcion, ";
            $cadena_sql.= "vop_nombre_archivo, ";
            $cadena_sql.= "vop_parametros, ";
            $cadena_sql.= "vop_observacion, ";
            $cadena_sql.= "vop_id_aspecto, ";
            $cadena_sql.= "vop_id_reporte";
            $cadena_sql.= ") ";
            $cadena_sql.= "VALUES (";
            $cadena_sql.= "'".$variable[0]."', ";
            $cadena_sql.= "'".$variable[1]."', ";
            $cadena_sql.= "'".$variable[2]."', ";
            $cadena_sql.= "'".$variable[3]."', ";
            $cadena_sql.= "'".$variable[4]."', ";
            $cadena_sql.= "'".$variable[5]."', ";
            $cadena_sql.= "'".$variable[6]."', ";
            $cadena_sql.= "'".$variable[7]."', ";
            $cadena_sql.= "'".$variable[8]."'";
            $cadena_sql.= ")";
            $registro = $conexion->ejecutarAcceso($cadena_sql,"");
          
        }
       
}//Fin de la clase
	
?>
