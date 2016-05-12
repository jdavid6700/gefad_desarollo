<?
if(!isset($GLOBALS["autorizado"]))
{
	include("index.php");
	exit;
}else{

    if(!isset($_REQUEST['region'])&&isset($_REQUEST['otraRegion'])){

        $variable["id_pais"]=$_REQUEST["pais"];
        $variable["mayorDepartamento"]=true;

        //Buscar el mayor indice de departamento existente para el pais
        $cadena_sql=$this->sql->cadena_sql($configuracion,"selectDepartamento",$variable);
        $registro=$this->ejecutarSQL($configuracion, $cadena_sql);

        if($this->totalRegistros($configuracion)>0&&$registro[0][0]!=NULL){
            $variable["id_localidad"]=($registro[0][0]+1);

        }else{
            $variable["id_localidad"]=1;
        }

        $variable["nombre"]=utf8_decode($_REQUEST["otraRegion"]);

        $cadena_sql=$this->sql->cadena_sql($configuracion,"insertarDepartamento",$variable);


        $resultado=$this->ejecutarSQL($configuracion, $cadena_sql,"insertar");

        if($resultado==false){
                echo "OOOPS!!!!. Region DB Engine Access Error";
                exit;
        }else{
            $idRegion=$variable["id_localidad"];
        }
    }

    if((isset($_REQUEST['ciudad'])
            &&!is_numeric($_REQUEST['ciudad'])
            &&$_REQUEST['ciudad']=="ttg")
        || isset($_REQUEST['otraCiudad'])){

        $variable["id_pais"]=$_REQUEST["pais"];

        if(isset($idRegion)){
            $variable["id_departamento"]=$idRegion;
            $_REQUEST["region"]=$idRegion;
        }else{
            $variable["id_departamento"]=$_REQUEST["region"];
        }

        $variable["mayorCiudad"]=true;

        //Buscar el mayor indice de departamento existente para el pais
        $cadena_sql=$this->sql->cadena_sql($configuracion,"selectCiudad",$variable);
        $registro=$this->ejecutarSQL($configuracion, $cadena_sql);

        if($this->totalRegistros($configuracion)>0&&$registro[0][0]!=NULL){
            $variable["id_ciudad"]=($registro[0][0]+1);

        }else{
            $variable["id_ciudad"]=1;
        }

        $variable["nombre"]=utf8_decode($_REQUEST["otraCiudad"]);

        $cadena_sql=$this->sql->cadena_sql($configuracion,"insertarCiudad",$variable);


        $resultado=$this->ejecutarSQL($configuracion, $cadena_sql,"insertar");

        if($resultado==false){
                echo "OOOPS!!!!. Region DB Engine Access Error";
                exit;
        }else{
            $idCiudad=$variable["id_ciudad"];
            $_REQUEST["ciudad"]=$idCiudad;
        }
    }
}
?>