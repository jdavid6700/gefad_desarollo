<?
if(!isset($GLOBALS["autorizado"]))
{
	include("index.php");
	exit;
}else{
    //Buscar el mayor indice de ciudad existente para el departamento
    $cadena_sql=$this->sql->cadena_sql($configuracion,"selectCiudad",$variable);
    $registro=$this->ejecutarSQL($configuracion, $cadena_sql);
    if($this->totalRegistros($configuracion)>0&&$registro[0][0]!=NULL){
        $variable["id_ciudad"]=($registro[0][0]+1);
    }else{
        $variable["id_ciudad"]=1;
    }

    $cadena_sql=$this->sql->cadena_sql($configuracion,"insertarCiudad",$variable);
    $resultado=$this->ejecutarSQL($configuracion, $cadena_sql,"insertar");
    return $resultado;

}
?>