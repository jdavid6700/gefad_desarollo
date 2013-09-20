<?
if(!isset($GLOBALS["autorizado"]))
{
	include("index.php");
	exit;
}else{
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
        unset($_REQUEST['action']);
        $cripto=new encriptar();
        $indice=$configuracion["host"].$configuracion["site"]."/index.php?";

        switch($opcion)
        {
                case "confirmacionAdmin":
                        $variable="pagina=inscripcionConferencia";
                        $variable.="&opcion=confirmar";
                        if($valor!=""){
                            $variable.="&id_sesion=".$valor;
                        }
                        break;

                 case "adminParticipante":
                        $variable="pagina=adminParticipante";
                        $variable.="&opcion=mostrar";
                        $variable.="&conBusqueda=true";
                        break;

                case "confirmacionAdminEditar":
                        $variable="pagina=conductorAdministrador";
                        $variable.="&opcion=confirmarEditar";
                        if($valor!=""){
                            $variable.="&registro=".$valor;
                        }
                        break;

               case "exitoRegistro":
                        $variable="pagina=inscripcionConferencia";
                        $variable.="&opcion=mostrar";
                        $variable.="&mensaje=exitoRegistro";
                        $variable.="&registro=".$configuracion["idRegistrado"];

                        break;

               case "adminFalloRegistro":
                        $variable="pagina=adminParticipante";
                        $variable.="&opcion=mostrar";
                        $variable.="&mensaje=falloRegistro";
                        break;

               case "adminExitoEdicion":
                        $variable="pagina=adminParticipante";
                        $variable.="&opcion=mostrar";
                        $variable.="&mensaje=exitoEdicion";
                        break;

               case "adminFalloEdicion":
                        $variable="pagina=adminParticipante";
                        $variable.="&opcion=mostrar";
                        $variable.="&mensaje=falloRegistro";
                        break;

               case "paginaPrincipal":
                        $variable="pagina=index";
                        break;


        }
        foreach($_REQUEST as $clave=>$valor)
        {
                unset($_REQUEST[$clave]);

        }
        $variable=$cripto->codificar_url($variable,$configuracion);

        echo "<script>location.replace('".$indice.$variable."')</script>";
        exit();

        }

            ?>