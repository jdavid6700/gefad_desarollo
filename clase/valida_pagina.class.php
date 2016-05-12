<?PHP

/*
############################################################################
#    UNIVERSIDAD DISTRITAL Francisco Jose de Caldas                        #
#    Copyright: Vea el archivo LICENCIA.txt que viene con la distribucion  #
############################################################################
*/
/***************************************************************************
* @name          valida_pag.php 
* @author        Jairo Lavado
* @revision      Última revisión 22 de Diciembre de 2011
****************************************************************************
* @subpackage   
* @package	clase
* @copyright    
* @version      0.2
* @author        Jairo Lavado
* @link		
* @description  
*
******************************************************************************/


require_once("funcionGeneral.class.php");

class valida_pag extends funcionGeneral
{  
    private $configuracion;
    private $acceso_OCI;
    private $acceso_MY;
    private $cripto;
    private $usser;
    private $nueva_sesion;
    
    public function __construct($configuracion,$id_usuario)
                {       
                    //require_once("sesion.class.php");
                    require_once("encriptar.class.php");
                    //require_once("config.class.php");
                    
                    
                    $this->configuracion=$configuracion;

                    
                    //var_dump($this->configuracion);exit;
                    
                    $this->cripto=new encriptar();
                    //$this->cripto->decodificar_url($_REQUEST['index'], $this->configuracion);
                    //$this->nueva_sesion=new sesiones($this->configuracion);
                    
                    $this->acceso_MY = $this->conectarDB($this->configuracion,"logueo");  
                    //$this->acceso_OCI = $this->conectarDB($this->configuracion,"default");  
                    //session_name($this->configuracion["usuarios_sesion"]);
                    //session_start();
                    $this->usser=$id_usuario;
                    
                }
	
    function action()
                {   
                    $indice=$this->configuracion['host_logueo']."/appserv";
                    $variable['USER']=$this->usser;
                    
                    /*verifica si existe sesion para el usuario*/
                    $consulta_ses = $this->cadena_sql('rescatar_id_sesion',$variable);
                    $registroSesion= $this->ejecutarSQL($this->configuracion, $this->acceso_MY, $consulta_ses,"busqueda");
                    //var_dump($registroSesion);
                    //exit;

                    if($registroSesion)
                        {
                        /*revisa que tipo de sesion tiene registrada en usuario*/
                        $cod_consul = $this->cadena_sql('rescatar_tipo_sesion',$registroSesion[0][0]);
                        $reg_ses= $this->ejecutarSQL($this->configuracion,  $this->acceso_MY, $cod_consul,"busqueda");
                        //var_dump($reg_ses);exit;
                        
                        $variable['ses']=$registroSesion[0][0];                 
                    
                        if($reg_ses[0][0]=='estudiante')
                              {
                                /*verifica que la sesion del estudiante este activa, de lo contrario la cierra y redirecciona a la pagina principal*/
                               $variable['vr']='expiracion';
                               $vr_consul = $this->cadena_sql('rescatar_valor_sesion',$variable);
                               $expiracion=$this->ejecutarSQL($this->configuracion,  $this->acceso_MY, $vr_consul,"busqueda");
                               //var_dump($expiracion);

                               if($expiracion[0][0]<time())
                                  { 
                                   /*redirecciona al servidor de logueo para cerrar la sesion expirada*/
                                   $dir=$indice."/clase/logout.class.php";
                                   $variable="msgIndex=113";
                                   $variable=$this->cripto->codificar_url($variable,$this->configuracion);
                                   $this->direccionar($dir,$variable); 
                                   exit;
                                  }

                               }
                         elseif($reg_ses[0][0]=='funcionario')
                               {/*actualiza el tiempo de expiracion para usuarios funcionario*/
                               $this->configuracion["expiracion_funcionario"];
                               $variable['vl']=(time()+$this->configuracion["expiracion_funcionario"]);
                               $variable['vr']='expiracion';
                               $up_consul = $this->cadena_sql('actualizar_valor_sesion',$variable);
                               $this->ejecutarSQL($this->configuracion,  $this->acceso_MY, $up_consul,"");

                               } 
                     } 
                  else   
                     {/*redirecciona al servidor de logueo si no existe la sesion*/
                        $dir=$indice."/clase/logout.class.php";
                        $variable="msgIndex=113";
                        $variable=$this->cripto->codificar_url($variable,$this->configuracion);
                        $this->direccionar($dir,$variable);
                      exit;
                     }
  
                }//fin funcion action
                
                
    function direccionar($url,$var)
                { echo " <script> 
                             top.location='".$url."?".$var."';
                          </script>" ;
                  exit;
                }// fin funcion direccionar    
                
                
  function cadena_sql($tipo,$variable)
		{
			$prefijo_sesion="dbms.dbms_";
                        
			switch($tipo)
			{

                            
			case "busca_us":
                                                        
                             $cadena_sql = "SELECT ";
                             $cadena_sql.= "cla_codigo COD, ";
                             $cadena_sql.= "cla_clave PWD, ";
                             $cadena_sql.= "cla_tipo_usu TIP_US, ";
                             $cadena_sql.= "cla_estado EST ";
                             $cadena_sql.= "FROM ";
                             $cadena_sql.= $this->configuracion["sql_tabla1"]." ";
                             $cadena_sql.= "WHERE ";
                             $cadena_sql.= "cla_codigo='".$this->usser."' ";
                             $cadena_sql.= "ORDER BY 4";
                            
                            break;
                        
                        case "sesiones":
                                                        
                             $cadena_sql = "SELECT ";
                             $cadena_sql.= "count(distinct id_sesion) NUM_SES ";
                             $cadena_sql.= "FROM ";
                             $cadena_sql.= $prefijo_sesion."valor_sesion ";
                                                        
                            break;
                        
                        case "busca_db":
                            
                            $cadena_sql="SELECT "; 
			    $cadena_sql.="`nombre`, ";	
			    $cadena_sql.="`tabla_sesion` ";						 
			    $cadena_sql.="FROM "; 
			    $cadena_sql.=$prefijo_sesion."bd ";
                            
                            break;
                        
                        case "rescatar_id_sesion":

                            $cadena_sql="SELECT DISTINCT ";
                            $cadena_sql.="id_sesion ";
                            $cadena_sql.="FROM ";
                            $cadena_sql.=$prefijo_sesion."valor_sesion ";
                            $cadena_sql.="WHERE ";
                            $cadena_sql.="variable='usuario' ";
                            $cadena_sql.="AND ";
                            $cadena_sql.="valor ='".$variable['USER']."' ";
                                                        
                            break;     
                        
                        case "rescatar_valor_sesion":

                            $cadena_sql="SELECT DISTINCT ";
                            $cadena_sql.="valor ";
                            $cadena_sql.="FROM ";
                            $cadena_sql.=$prefijo_sesion."valor_sesion ";
                            $cadena_sql.="WHERE ";
                            $cadena_sql.="variable='".$variable['vr']."' ";
                            $cadena_sql.="AND ";
                            $cadena_sql.="id_sesion ='".$variable['ses']."' ";
                            
                            
                            break;                        
                        
                        case "rescatar_tipo_sesion":

                            $cadena_sql="SELECT DISTINCT ";
                            $cadena_sql.="valor tipo_ses ";
                            $cadena_sql.="FROM ";
                            $cadena_sql.=$prefijo_sesion."valor_sesion ";
                            $cadena_sql.="WHERE ";
                            $cadena_sql.="variable='tipo_sesion' ";
                            $cadena_sql.="AND ";
                            $cadena_sql.="id_sesion='".$variable."' ";                            
                            break;
                        
                        
                        case "borrar_sesion":
                                                        
                            $cadena_sql="DELETE  ";
                            $cadena_sql.="FROM ";
                            $cadena_sql.=$variable['DB'].".".$variable['TABLA']." ";
                            $cadena_sql.="WHERE ";
                            $cadena_sql.="id_sesion='".$variable['SES']."' ";
                                
                            break;                        
                        
                        case "guardar_valor_sesion":
                                                        
                            $cadena_sql="INSERT INTO ";
                            $cadena_sql.=$prefijo_sesion."valor_sesion (id_sesion,variable,valor) ";
                            $cadena_sql.="VALUES (";
                            $cadena_sql.="'".$variable['ses']."', ";
                            $cadena_sql.="'".$variable['vr']."', ";
                            $cadena_sql.="'".$variable['vl']."' ";
                            $cadena_sql.=");";
                        
                            break;                        

                        case "actualizar_valor_sesion":
                                                        
                            $cadena_sql="UPDATE ";
                            $cadena_sql.=$prefijo_sesion."valor_sesion ";
                            $cadena_sql.="SET ";
                            $cadena_sql.="valor='".$variable['vl']."' ";
                            $cadena_sql.="WHERE ";
                            $cadena_sql.="id_sesion='".$variable['ses']."' ";
                            $cadena_sql.="AND ";
                            $cadena_sql.="variable='".$variable['vr']."' ";
                                                    
                            break;                                                
                        
 
                        default    :
                            $cadena_sql= ""; 
                            break;        
			}
			
			
		
			return $cadena_sql;
		
		}              
                
                
                
	
	
}// fin clase bloqueAdminUsuario

// @ Crear un objeto bloque especifico

//$esteBloque = new valida_pag();
//$esteBloque->action();


?>
