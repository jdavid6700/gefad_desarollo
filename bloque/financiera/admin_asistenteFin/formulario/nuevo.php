<?
/***************************************************************************
 *   PHP Application Framework Version 10                                  *
 *   Copyright (c) 2003 - 2009                                             *
 *   Teleinformatics Technology Group de Colombia                          *
 *   ttg@ttg.com.co                                                        *
 *                                                                         *
****************************************************************************/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

if(isset($certificado)&&$certificado=="registroParticipante") {


    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
    $cripto=new encriptar();
    $valorCodificado="action=registro_participante";
    $valorCodificado=$cripto->codificar($valorCodificado, $configuracion);

    $indice=$configuracion["host"].$configuracion["site"]."/index.php?";
  
    $tab=1;
    ?><div id="imagenCentral">
    <table cellpadding="0" border="0" cellspacing="0">
        <tr>
                  <td colspan="1" rowspan="1" >
                    <?
                    echo "";
                    
                    /*
                    //Formulario para nuevos registros de usuario
                    $atributos["tipoFormulario"]="multipart/form-data";
                    $atributos["metodo"]="POST";
                    $atributos["nombreFormulario"]=$certificado;
                    $atributos["verificarFormulario"]="1";

                    echo $this->miFormulario->marcoFormulario("inicio",$atributos);

                    //-------------------------------Mensaje-------------------------------------
                    $atributos["id"]="mensaje1";
                    $atributos["obligatorio"]=false;
                    $atributos["etiqueta"]="";
                    $atributos["mensaje"]=$this->lenguaje["camposObligatorios"];
                    echo $this->miFormulario->campoMensaje($configuracion,$atributos);

                    //-------------Control cuadroTexto-----------------------
                    $esteCampo="nombre";
                    $atributos["id"]=$esteCampo;
                    $atributos["etiqueta"]=$this->lenguaje[$esteCampo];
                    $atributos["tabIndex"]=$tab++;
                    $atributos["obligatorio"]=true;
                    $atributos["tamanno"]=20;
                    $atributos["tipo"]="";
                    $atributos["estilo"]="";
                    $atributos["verificarFormulario"].="&& control_vacio(".$atributos["nombreFormulario"].",'".$atributos["id"]."')";
                    $atributos["verificarFormulario"].="&& longitud_cadena(".$atributos["nombreFormulario"].",'".$atributos["id"]."',3)";
                    echo $this->miFormulario->campoCuadroTexto($configuracion,$atributos);

                    //-------------Control cuadroTexto-----------------------
                    $esteCampo="apellido";
                    $atributos["id"]=$esteCampo;
                    $atributos["etiqueta"]=$this->lenguaje[$esteCampo];
                    $atributos["tabIndex"]=$tab++;
                    $atributos["obligatorio"]=true;
                    $atributos["tamanno"]=20;
                    $atributos["tipo"]="";
                    $atributos["estilo"]="";
                    echo $this->miFormulario->campoCuadroTexto($configuracion,$atributos);

                    //-------------Control cuadroTexto-----------------------
                    $esteCampo="identificacion";
                    $atributos["id"]=$esteCampo;
                    $atributos["etiqueta"]=$this->lenguaje[$esteCampo];
                    $atributos["tabIndex"]=$tab++;
                    $atributos["obligatorio"]=true;
                    $atributos["tamanno"]=20;
                    $atributos["tipo"]="";
                    $atributos["estilo"]="";
                    $atributos["verificarFormulario"].="&& control_vacio(".$atributos["nombreFormulario"].",'".$atributos["id"]."')";
                    $atributos["verificarFormulario"].="&& longitud_cadena(".$atributos["nombreFormulario"].",'".$atributos["id"]."',3)";
                    $atributos["verificarFormulario"].="&& verificar_numerico(".$atributos["nombreFormulario"].",'".$atributos["id"]."','".$this->lenguaje["numeroNoValido"]."')";
                    echo $this->miFormulario->campoCuadroTexto($configuracion,$atributos);

                    //-------------Control cuadroTexto-----------------------
                    $esteCampo="codigo";
                    $atributos["id"]=$esteCampo;
                    $atributos["etiqueta"]=$this->lenguaje[$esteCampo];
                    $atributos["tabIndex"]=$tab++;
                    $atributos["obligatorio"]=true;
                    $atributos["tamanno"]=20;
                    $atributos["tipo"]="";
                    $atributos["estilo"]="";
                    $atributos["verificarFormulario"].="&& control_vacio(".$atributos["nombreFormulario"].",'".$atributos["id"]."')";
                    $atributos["verificarFormulario"].="&& longitud_cadena(".$atributos["nombreFormulario"].",'".$atributos["id"]."',3)";
                    $atributos["verificarFormulario"].="&& verificar_numerico(".$atributos["nombreFormulario"].",'".$atributos["id"]."','".$this->lenguaje["numeroNoValido"]."')";
                    echo $this->miFormulario->campoCuadroTexto($configuracion,$atributos);

                    //-------------Control cuadroTexto-----------------------
                    $esteCampo="correo";
                    $atributos["id"]=$esteCampo;
                    $atributos["tabIndex"]=$tab++;
                    $atributos["etiqueta"]=$this->lenguaje[$esteCampo];
                    $atributos["tamanno"]=20;
                    $atributos["obligatorio"]=true;
                    $atributos["verificarFormulario"].="&& control_vacio(".$atributos["nombreFormulario"].",'".$atributos["id"]."')";
                    $atributos["verificarFormulario"].="&& verificar_correo(".$atributos["nombreFormulario"].",'".$atributos["id"]."')";
                    echo $this->miFormulario->campoCuadroTexto($configuracion,$atributos);

                    //------------------Division para los botones-------------------------
                    $atributos["id"]="botones";
                    $atributos["estilo"]="marcoBotones";
                    echo $this->miFormulario->division("inicio",$atributos);

                    //-------------Control Boton-----------------------
                    $atributos["verificar"]="";
                    $atributos["tipo"]="boton";
                    $atributos["id"]="botonAceptar";
                    $atributos["estilo"]="";
                    $atributos["tabIndex"]=$tab++;
                    $atributos["valor"]=$this->lenguaje["botonAceptar"];
                    echo $this->miFormulario->campoBoton($configuracion,$atributos);
                    //-------------Fin Control Boton----------------------

                    //-------------Control Boton-----------------------
                    $atributos["verificar"]="";
                    $atributos["tipo"]="boton";
                    $atributos["id"]="botonCancelar";
                    $atributos["cancelar"]="true";
                    $atributos["tabIndex"]=$tab++;
                    $atributos["valor"]=$this->lenguaje["botonCancelar"];
                    echo $this->miFormulario->campoBoton($configuracion,$atributos);
                    //-------------Fin Control Boton----------------------

                    //------------------Fin Division para los botones-------------------------
                    echo $this->miFormulario->division("fin",$atributos);



                    //-------------Control cuadroTexto con campos ocultos-----------------------
                    $atributos["id"]="formulario";
                    $atributos["tipo"]="hidden";
                    $atributos["obligatorio"]=false;
                    $atributos["etiqueta"]="";
                    $atributos["valor"]=$valorCodificado;
                    echo $this->miFormulario->campoCuadroTexto($configuracion,$atributos);


                    //Fin del Formulario
                    echo $this->miFormulario->marcoFormulario("fin",$atributos);*/
                    ?>
            </td>
        </tr>

    </table>


    <?

}
?>