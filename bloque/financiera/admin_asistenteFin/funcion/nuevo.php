<?
if(!isset($GLOBALS["autorizado"]))
{
	include("index.php");
	exit;
}else{
            //1. Borrar todos los registros que pertenezcan a la misma sesion

            $variable["fecha"]=time();
            if(!isset($configuracion["id_sesion"])||$configuracion["id_sesion"]==0){
                $configuracion["id_sesion"]=$variable["fecha"];
            }

            $cadena_sql=$this->sql->cadena_sql($configuracion, "eliminarTemp",$configuracion["id_sesion"]);
            $resultado=$this->ejecutarSQL($configuracion, $cadena_sql,"acceso",$configuracion["db_principal"]);

            //2. Insertar Borrador
            
            $variable["formulario"]=$this->formulario;

            $cadena_sql=$this->sql->cadena_sql($configuracion, "insertarTemp",$variable);
            $resultado=$this->ejecutarSQL($configuracion, $cadena_sql,"acceso",$configuracion["db_principal"]);
            if($resultado==true){

                    $this->funcion->redireccionar($configuracion,"confirmacionAdmin",$configuracion["id_sesion"]);
            }else{
                    echo "OOOPS!!!!. DB Engine Access Error";
                    exit;
            }

    }
?>