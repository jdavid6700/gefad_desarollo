<?
/***************************************************************************
 *   PHP Application Framework Version 10                                  *
 *   Copyright (c) 2003 - 2009                                             *
 *   Teleinformatics Technology Group de Colombia                          *
 *   ttg@ttg.com.co                                                        *
 *                                                                         *
****************************************************************************/
include_once("html.class.php");

class  FormularioHtml extends html
{
    function recaptcha($configuracion,$atributos){
        
      require_once($configuracion["raiz_documento"].$configuracion["clases"]."/recaptcha/recaptchalib.php");
      $publickey =$configuracion["captcha_llavePublica"];


       if(isset($atributos["estilo"]) && $atributos["estilo"]!=""){
            $this->cadenaHTML="<div class='".$atributos["estilo"]."'>\n";
        }else{
            $this->cadenaHTML="<div class='recaptcha'>\n";
        }
        $this->cadenaHTML.=recaptcha_get_html($publickey);
        $this->cadenaHTML.="</div>\n";
        return $this->cadenaHTML;
     
    }


    function marcoFormulario($tipo,$atributos){

            if($tipo=="inicio"){

                if(isset($atributos["estilo"]) && $atributos["estilo"]!=""){
                    $this->cadenaHTML="<div class='".$atributos["estilo"]."'>\n";
                }else{
                    $this->cadenaHTML="<div class='formulario'>\n";
                }
                $this->cadenaHTML.="<form ";
                $this->cadenaHTML.="enctype='".$atributos["tipoFormulario"]."' ";
                $this->cadenaHTML.="method='".$atributos["metodo"]."' ";
                $this->cadenaHTML.="action='index.php' ";
                $this->cadenaHTML.="name='".$atributos["nombreFormulario"]."'>\n";
            }else{
                $this->cadenaHTML="</form>\n";
                $this->cadenaHTML.="</div>\n";
            }

            return $this->cadenaHTML;

    }

    function marcoAGrupacion($tipo,$atributos){

            $this->cadenaHTML="";
            if($tipo=="inicio"){
                $this->cadenaHTML="<div class='marcoControles'>\n";
                $this->cadenaHTML.="<fieldset>\n";
                $this->cadenaHTML.="<legend>\n".$atributos["leyenda"]."</legend>\n";
            }else{
                $this->cadenaHTML.="</fieldset>\n";
                $this->cadenaHTML.="</div>\n";

            }
            
            return $this->cadenaHTML;
    }


    function campoCuadroTexto($configuracion,$atributos){
        if(isset($atributos["estilo"]) && $atributos["estilo"]!=""){
            $this->cadenaHTML="<div class='".$atributos["estilo"]."'>\n";
        }else{
            $this->cadenaHTML="<div class='campoCuadroTexto'>\n";
        }
        $this->cadenaHTML.=$this->etiqueta($atributos);
        if(isset($atributos["dobleLinea"])){
            $this->cadenaHTML.="<br>";
        }
        $atributos["estilo"]="";
        $this->cadenaHTML.=$this->cuadro_texto($configuracion, $atributos);
        $this->cadenaHTML.="</div>\n";

        return $this->cadenaHTML;
    }

    function campoEspacio(){

        $this->cadenaHTML="<div class='espacioBlanco'>\n</div>\n";
        return $this->cadenaHTML;

    }
    function campoFecha($configuracion,$atributos){
        if(isset($atributos["estilo"]) && $atributos["estilo"]!=""){
            $this->cadenaHTML="<div class='".$atributos["estilo"]."'>\n";
        }else{
            $this->cadenaHTML="<div class='campoFecha'>\n";
        }
        $this->cadenaHTML.=$this->etiqueta($atributos);
        $this->cadenaHTML.="<div style='display:table-cell;vertical-align:top;float:left;'><span style='white-space:pre;'>  </span>";
        $this->cadenaHTML.=$this->cuadro_texto($configuracion, $atributos);
        $this->cadenaHTML.="</div>";
        $this->cadenaHTML.="<div style='display:table-cell;vertical-align:top;float:left;'>";
        $this->cadenaHTML.="<span style='white-space:pre;'>  </span><img src=\"".$configuracion["host"].$configuracion["site"].$configuracion["grafico"]."/calendarito.jpg\" ";
        $this->cadenaHTML.="id=\"imagen".$atributos["id"]."\" style=\"cursor: pointer; border: 0px solid red;\" ";
        $this->cadenaHTML.="title=\"Date selector\" onmouseover=\"this.style.background='red';\" onmouseout=\"this.style.background=''\" />";
        $this->cadenaHTML.="</div>";
        $this->cadenaHTML.="</div>\n";

        return $this->cadenaHTML;
    }

    function campoMensaje($configuracion,$atributos){

        if(isset($atributos["estilo"])&&$atributos["estilo"]!=""){
            $this->cadenaHTML="<div class='".$atributos["estilo"]."'>\n";
        }else{
            $this->cadenaHTML="<div class='campoMensaje'>\n";
        }

        if(isset($atributos["tamanno"])){
            switch($atributos["tamanno"]){
                case "grande":
                    $this->cadenaHTML.="<span class='textoGrande texto_negrita'>".$atributos["mensaje"]."</span>";
                    break;

                case "enorme":
                    $this->cadenaHTML.="<span class='textoEnorme texto_negrita'>".$atributos["mensaje"]."</span>";
                    break;

                case "pequenno":
                    $this->cadenaHTML.="<span class='textoPequenno'>".$atributos["mensaje"]."</span>";
                    break;

                default:
                    $this->cadenaHTML.="<span class='textoMediano'>".$atributos["mensaje"]."</span>";
                    break;
            }
            
        }else{
            $this->cadenaHTML.="<span class='textoMediano texto_negrita'>".$atributos["mensaje"]."</span>";
        }

        if(isset($atributos["linea"])&&$atributos["linea"]==true){
            $this->cadenaHTML.="<hr class='hr_division'>";
        }
        $this->cadenaHTML.="</div>\n";

        return $this->cadenaHTML;
    }

    function campoTextArea($configuracion,$atributos){


    if(isset($atributos["estilo"]) && $atributos["estilo"]!=""){
            $this->cadenaHTML="<div class='".$atributos["estilo"]."'>\n";
        }else{
            $this->cadenaHTML="<div class='campoAreaTexto'>\n";
        }
       $this->cadenaHTML.="<div class='campoTextoEtiqueta'>\n";
       $this->cadenaHTML.=$this->etiqueta($atributos);
       $this->cadenaHTML.="\n</div>\n";
       $this->cadenaHTML.="<div class='campoAreaContenido'>\n";
       $this->cadenaHTML.=$this->area_texto($configuracion, $atributos);
       $this->cadenaHTML.="\n</div>\n";
       $this->cadenaHTML.="</div>\n";
       return $this->cadenaHTML;
 }

    function campoBoton($configuracion,$atributos){
            if(isset($atributos["estilo"]) && $atributos["estilo"]!=""){
                $this->cadenaHTML="<div class='".$atributos["estilo"]."'>\n";
            }else{
                $this->cadenaHTML="<div class='campoBoton'>\n";
            }

            $this->cadenaHTML.=$this->boton($configuracion,$atributos);
            $this->cadenaHTML.="</div>\n";

            return $this->cadenaHTML;
    }

    function campoBotonRadial($configuracion,$atributos){


            if(isset($atributos["estilo"]) && $atributos["estilo"]!=""){
                $this->cadenaHTML="<div class='".$atributos["estilo"]."'>\n";
            }else{
                $this->cadenaHTML="<div class='campoBotonRadial'>\n";
            }
            $this->cadenaHTML.=$this->radioButton($configuracion,$atributos);
            $this->cadenaHTML.="\n</div>\n";
            return $this->cadenaHTML;
    }

    function campoCuadroSeleccion($configuracion,$atributos){


            if(isset($atributos["estilo"]) && $atributos["estilo"]!=""){
                $this->cadenaHTML="<div class='".$atributos["estilo"]."'>\n";
            }else{
                $this->cadenaHTML="<div class='campoCuadroSeleccion'>\n";
            }
            $this->cadenaHTML.=$this->checkBox($configuracion,$atributos);
            $this->cadenaHTML.="\n</div>\n";
            return $this->cadenaHTML;
    }


    function campoImagen($configuracion,$atributos){

            if(isset($atributos["estilo"]) && $atributos["estilo"]!=""){
                $this->cadenaHTML="<div class='".$atributos["estilo"]."'>\n";
            }else{
                $this->cadenaHTML="<div class='campoImagen'>\n";
            }

            $this->cadenaHTML.="<div class='marcoCentrado'>\n";
            $this->cadenaHTML.="<img src='".$atributos["imagen"]."' ";
            
            if(isset($atributos["borde"])){
                $this->cadenaHTML.="border='".$atributos["borde"]."' ";
            }else{
                $this->cadenaHTML.="border='0' ";
            }

            if(isset($atributos["ancho"])){
                $this->cadenaHTML.="width='".$atributos["ancho"]."' ";
            }else{
                $this->cadenaHTML.="width='200px' ";
            }

            if(isset($atributos["alto"])){
                $this->cadenaHTML.="height='".$atributos["alto"]."' />";
            }else{
                $this->cadenaHTML.="height='200px' /> ";
            }
            $this->cadenaHTML.="</div>\n";
            $this->cadenaHTML.="</div>\n";
            return $this->cadenaHTML;
    }

    function campoCuadroLista($configuracion,$atributos){


        $this->cadenaHTML="<div class='campoCuadroLista'>\n";
        $this->cadenaHTML.=$this->etiqueta($atributos);
        $this->cadenaHTML.=$this->cuadro_lista($configuracion,$atributos);
        $this->cadenaHTML.="</div>\n";

        return $this->cadenaHTML;
    }

    function campoTexto($configuracion,$atributos){

        
        if(isset($atributos["estilo"]) && $atributos["estilo"]!=""){
            $this->cadenaHTML="<div class='".$atributos["estilo"]."'>\n";
        }else{
            $this->cadenaHTML="<div class='campoTexto'>\n";
        }
        
        $this->cadenaHTML.="<div class='campoTextoEtiqueta'>\n";
        $this->cadenaHTML.=$atributos["etiqueta"];
        $this->cadenaHTML.="\n</div>\n";
        $this->cadenaHTML.="<div class='campoTextoContenido'>\n";
        if($atributos["texto"]!=""){
            $this->cadenaHTML.=nl2br($atributos["texto"]);
        }else{
            $this->cadenaHTML.="--";
        }
        $this->cadenaHTML.="\n</div>\n";
        $this->cadenaHTML.="\n</div>\n";

        return $this->cadenaHTML;
    }


    function campoMapa($atributos){

        $this->cadenaCampoMapa="<div class='campoMapaEtiqueta'>\n";
        $this->cadenaCampoMapa.=$atributos["etiqueta"];
        $this->cadenaCampoMapa.="</div>\n";
        $this->cadenaCampoMapa.="<div class='campoMapa'>\n";
        $this->cadenaCampoMapa.=$this->division("inicio",$atributos);
        $this->cadenaCampoMapa.=$this->division("fin",$atributos);
        $this->cadenaCampoMapa.="\n</div>\n";

        return $this->cadenaCampoMapa;
    }

    function division($tipo,$atributos){

            $this->cadenaHTML="";
            if($tipo=="inicio"){
                if(isset($atributos["estilo"])){
                    $this->cadenaHTML="<div class='".$atributos["estilo"]."' ";
                }else{
                    $this->cadenaHTML="<div ";
                }
                $this->cadenaHTML.="id='".$atributos["id"]."' ";
                $this->cadenaHTML.="nombre='".$atributos["id"]."' ";
                $this->cadenaHTML.=">\n";
                
            }else{

                $this->cadenaHTML.="\n</div>\n";

            }

            return $this->cadenaHTML;
    }

}//Fin de la clase FormularioHtml
?>