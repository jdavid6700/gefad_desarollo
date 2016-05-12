<?
/*
############################################################################
#    UNIVERSIDAD DISTRITAL Francisco Jose de Caldas                        #
#    Copyright: Vea el archivo LICENCIA.txt que viene con la distribucion  #
############################################################################
*/
/***************************************************************************
* @name          accesos.class.php 
* @author        Jairo Lavado
* @revision      Última revisión 22 de Diciembre de 2011
****************************************************************************
* @subpackage   
* @package	clase
* @copyright    
* @version      0.2
* @author       Jairo Lavado
* @link		
* @description  
*
******************************************************************************/
require_once("funcionGeneral.class.php");

class codificar_clave extends funcionGeneral
{
    private $acceso_db;
    private $acceso_MY;
    private $cripto;
    private $semilla;
    
	public function __construct()
	{       
                include_once("dbms.class.php");
		include_once("trunc.class.php");
                require_once("encriptar.class.php");
                $this->cripto=new encriptar();
                $this->semilla="condor";
	}
        
	/**
         * @name registrar
         * @param type $user
         * @param type $apli 
         * @descripcion Registra en el log, los accesos de los usuarios a las diferentes aplicaciones.
         */
	 function registrar()
	{       
            $var_conecta['db_dns']='localhost';		
            $var_conecta['db_usuario']='root';		
            //$var_conecta['db_clave']='administrar';
            $var_conecta['db_clave']='4dm1nC0nd0rSGA';
            $var_conecta['db_sys']='mysql';
                
           $bases[0][0]='dbms';$bases[0][1]='dbms_';     
           $bases[1][0]='weboffice';$bases[1][1]='backoffice_';
           $bases[2][0]='pro_sgawork';$bases[2][1]='sga_';
           $bases[3][0]='docencia';$bases[3][1]='docencia_';
           $bases[4][0]='moodle';$bases[4][1]='mdl_';
           
                
            for($k=0;$k<5;$k++)
            {
		/*Conexion para base 0*/
                $var_conecta['db_nombre']=$bases[$k][0];
                echo "<br>inicia BASE ".$var_conecta['db_nombre'];
                $this->acceso_db=new dbms($var_conecta);
		$this->acceso_MY=$this->acceso_db->conectar_db();
                
                $parametro['prefijo']=$bases[$k][1];;
                $consul_sql = $this->cadena_sql('conexiones',$parametro);
                $this->acceso_db->registro_db($consul_sql,0);
		$registro=$this->acceso_db->obtener_registro_db();
                
                $i=0;
                while(isset($registro[$i][0])){
                    //echo '<br> '.$registro[$i][0].' '.$registro[$i][1].' '.$registro[$i][2].' '.$registro[$i][3].' '.$registro[$i][4].' '.$registro[$i][5].' '.$registro[$i][6].' '.$registro[$i][7]; 
                    $dbms[$i]=$registro[$i][7];

                    if($dbms[$i]=='oci8_t')
                        {   $trunc_ps=new trunc();
                            $registro[$i][6]=$trunc_ps->transformar($registro[$i][6]);
                            $registro[$i][7]='oci8';
                        }
                    echo "<br>Codificando ".$registro[$i][5];
                    $parametro['nombre']=$registro[$i][0];
                    $parametro['usuario']=  $this->cripto->codificar_variable($registro[$i][5],  $this->semilla);
                    $parametro['password']=  $this->cripto->codificar_variable($registro[$i][6],  $this->semilla);
                    $parametro['dbms']=$registro[$i][7];
                    $actualiza_sql = $this->cadena_sql('actualiza_conexiones',$parametro);
                    $this->acceso_db->ejecutarAcceso($actualiza_sql,"");
                    //exit;
                    
                    $i++;
                }
                echo "<br>LISTO BASE ".$var_conecta['db_nombre']."<br>";
                usleep(1000000);
            }   
            
                
	}
        
         function cadena_sql($tipo,$variable)
		{
			
			switch($tipo)
			{
                        
                        case "conexiones":
                                $cadena_sql="SELECT "; 
				$cadena_sql.="`nombre`, ";			 
				$cadena_sql.="`servidor`, "; 
				$cadena_sql.="`puerto`, "; 
				$cadena_sql.="`ssl`, "; 
				$cadena_sql.="`db`, "; 
				$cadena_sql.="`usuario`, "; 
				$cadena_sql.="`password`, ";
				$cadena_sql.="`dbms` "; 
				$cadena_sql.="FROM "; 
				$cadena_sql.=$variable["prefijo"]."dbms ";
                        break;    

                        case "actualiza_conexiones":
                                
                                $cadena_sql="UPDATE "; 
				$cadena_sql.=$variable["prefijo"]."dbms ";
                                $cadena_sql.="SET "; 
				$cadena_sql.="`usuario`='".$variable['usuario']."', "; 
				$cadena_sql.="`password`='".$variable['password']."', "; 
				$cadena_sql.="`dbms`='".$variable['dbms']."' ";  
				$cadena_sql.="WHERE "; 
                                $cadena_sql.="`nombre`='".$variable['nombre']."'"; 			 
                        break;    
                         
                     default    :
                            $cadena_sql= ""; 
                            break;

			}
			
			
		
			return $cadena_sql;
		
		}        
	
}//Fin de la clase db_admin
$esteBloque = new codificar_clave();
$esteBloque->registrar();
?>
