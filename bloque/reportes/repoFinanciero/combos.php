<?
/*--------------------------------------------------------------------------------------------------------------------------
 @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/
/*--------------------------------------------------------------------------------------------------------------------------
 * @name          bloque.php
* @author        Paulo Cesar Coronado
* @revision      Última revisión 12 de enero de 2009
/*--------------------------------------------------------------------------------------------------------------------------
* @subpackage		bloqueAdminNovedad
* @package		bloques
* @copyright    	Universidad Distrital Francisco Jose de Caldas
* @version      	0.0.0.1 - Febrero 14 de 2013
* @author		Maritza Callejas Cely
* @author			Oficina Asesora de Sistemas
* @link			N/D
* @description  	Bloque para gestionar las novedades del sistema de contratación. Implementa los casos
*			de uso:
*			Consultar novedades de contratista
*			Registrar novedad de contratista
/*--------------------------------------------------------------------------------------------------------------------------*/

$GLOBALS["autorizado"]='true';

include("../../../clase/funcionGeneral.class.php");


//Clase
class combos_reporteFinanciero extends funcionGeneral
{
   private $configuracion;
   private $sql;
   private $conexion_db;
   
	public function __construct()
	{$GLOBALS["autorizado"]='true';
         require_once("../../../clase/config.class.php");      
         $esta_configuracion=new config();
         $this->configuracion=$esta_configuracion->variable('../../../');
         //include("../../../clase/funcionGeneral.class.php");
         ///$this->conexion=new funcionGeneral();
         $this->acceso_db = $this->conectarDB($this->configuracion,"mysqlFrame");
         
      
	}



	function action()
	{ 
            $Vpar=array('reporte'=>$_REQUEST["reporte"],'parametro'=>$_REQUEST["parametro"]);
            $param_sql=$this->cadena_sql("buscarParametrosReporte",$Vpar);
            //rescata los datos del parametro
            
            
            $parametrosSQL = $this->ejecutarSQL($this->configuracion, $this->acceso_db, $param_sql, "busqueda");
            $controlSQL = explode("|", $parametrosSQL[0]['control_busqueda']);
           
            foreach($controlSQL as $par=>$value) 
                {
                    echo $_REQUEST[$controlSQL[$par]];
                    //reemplaza los parametros de la sql, por los valores
                  if(isset($_REQUEST[$controlSQL[$par]]) && $_REQUEST[$controlSQL[$par]]>0)
                       { //reemplaza los parametros de la consulta
                         $parametrosSQL[0]['sql_par']=str_replace( '$P{\''.$controlSQL[$par].'\'}' ,
                                              ' =\''. $_REQUEST[$controlSQL[$par]].'\'',
                                              $parametrosSQL[0]['sql_par']);
                       }
                  else
                       { //reemplaza los parametros de la consulta
                         $parametrosSQL[0]['sql_par']=str_replace( '$P{\''.$controlSQL[$par].'\'}' ,
                                               " IS NOT NULL ",
                                                $parametrosSQL[0]['sql_par']);
                        }

                } 
               //crea objeto de conexion del reporte y ejecuta la consulta    
               $accesoParametro = $this->conectarDB($this->configuracion,$parametrosSQL[0]['conexion_par']);
               $parametroDatos = $this->ejecutarSQL($this->configuracion, $accesoParametro, $parametrosSQL[0]['sql_par'], "busqueda");

               $options='<option value="0" selected >Todos </option>';        
                
                if($parametroDatos)
                    { foreach($parametroDatos as $dato=>$value )
                        { $options.= ' <option value="'.$parametroDatos[$dato][0].'">'.$parametroDatos[$dato][1].'</option>'; }
                    }
                else {$options=' <option value="0">No es posible rescatar los datos</option>';}
                //imprime las opciones obtenidas
                echo $options;

	}//fin funcion action

    function cadena_sql($opcion,$variable="")
	{

		switch($opcion)
		{

			case "buscarParametrosReporte":
                                
                                $cadena_sql=" SELECT DISTINCT";
                                $cadena_sql.=" p_rep.id_reporte id_rep , ";
                                $cadena_sql.=" p_rep.id_parametro id_par , ";
                                $cadena_sql.=" p_rep.nombre nombre_par, ";
                                $cadena_sql.=" p_rep.conexion conexion_par , ";
                                $cadena_sql.=" p_rep.consulta sql_par , ";
                                $cadena_sql.=" p_rep.carga_parametro alimenta_par, ";
                                $cadena_sql.=" p_rep.control_parametro control_busqueda, ";
                                $cadena_sql.=" p_rep.tipo_caja caja_html ";
                                $cadena_sql.=" FROM ".$this->configuracion['prefijo']."parametros_reporte p_rep";
                                $cadena_sql.=" WHERE p_rep.id_reporte='".$variable['reporte']."'";
                                $cadena_sql.=" AND p_rep.nombre ='".$variable['parametro']."'";
                                $cadena_sql.=" ORDER BY p_rep.id_parametro ";
				
				break;                            

			default:
				$cadena_sql="";
				break;
		}//fin switch
		return $cadena_sql;
	}// fin funcion cadena_sql        
        
        

}// fin clase bloquenom_adminNovedad


// @ Crear un objeto bloque especifico

$esteBloque = new combos_reporteFinanciero();
$esteBloque->action();

?>


