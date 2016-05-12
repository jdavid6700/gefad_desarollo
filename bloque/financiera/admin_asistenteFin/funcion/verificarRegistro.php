<?
if(!isset($GLOBALS["autorizado"]))
{
	include("index.php");
	exit;
}else{
    //Verificar si el nombre de usuario ya existe
    $cadena_sql=$this->sql->cadena_sql($configuracion,"buscarParticipante",$_REQUEST["identificacion"]);

    $registro=$this->ejecutarSQL($configuracion, $cadena_sql,"busqueda",$configuracion["db_principal"]);

    if($this->totalRegistros($configuracion,$configuracion["db_principal"])>0){
        return true;
    }

    return false;

}
?>