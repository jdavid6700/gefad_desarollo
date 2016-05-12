<?php
/*
  ############################################################################
  #    UNIVERSIDAD DISTRITAL Francisco Jose de Caldas                        #
  #    Copyright: Vea el archivo EULA.txt que viene con la distribucion      #
  ############################################################################
 */

if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;

    include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/dbms.class.php");
    include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/sesion.class.php");
    include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
}

class html_adminTributario {

    public $configuracion;
    public $cripto;
    public $indice;

    function __construct($configuracion) {

        $this->configuracion = $configuracion;
        include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
        include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/html.class.php");
        $indice = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
        $this->cripto = new encriptar();
        $this->indice = $configuracion["host"] . $configuracion["site"] . "/index.php?";
        $this->html = new html();
    }
    
    /**
    * funcion que muestra la información del reporte
    */
        
     function sinDatos($configuracion,$titulo)
        {   include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php"); 
            $cadena=".:: ".$titulo." ::."; 
            $cadena=htmlentities($cadena, ENT_COMPAT, "UTF-8");
            alerta::sin_registro($configuracion,$cadena);
        }        
    
     /**
     * Funcion que muestra el formulario para seleccionar los parametros del reporte
     * @param <array> $vigencias
     */
    function form_muestra_parametros($parametrosHtml){
            $tab=0;
            $this->formulario = "admin_escalafonTributario";
       ?>
        <table width="95%" align="center" border="0" cellpadding="10" cellspacing="0">
         <tr class="texto_subtitulo">
           <td><br><br>
               Listado de personas naturales con información tributaria registrada<br><br>
               <hr class="hr_subtitulo">
           </td>
         </tr>       
         <tbody> 
          <tr>
           <td>
             <form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario;?>'>
               <table align="center"  border="0" width="98%" >
                   <tr> <td class='texto_elegante ' valign='top' width="5%" >&nbsp;</td>
                        <td class='texto_elegante ' valign='top' width="20%" >
                          Vigencia :   
                             <?
                         //verifica que tipo de caja debe armar
                           unset($combo);
                           foreach($parametrosHtml as $cmb => $values)
                                {  $combo[$cmb][0]=isset($parametrosHtml[$cmb]['vigencia'])?$parametrosHtml[$cmb]['vigencia']:0;
                                   $combo[$cmb][1]=isset($parametrosHtml[$cmb]['vigencia'])?$parametrosHtml[$cmb]['vigencia']:'';
                                }
                            if (isset($_REQUEST['vigencia']))    
                                { $lista_combo = $this->html->cuadro_lista($combo,'vigencia',$this->configuracion,$_REQUEST['vigencia'],0,FALSE,$tab++,'vigencia');
                                }
                            else{ $lista_combo = $this->html->cuadro_lista($combo,'vigencia',$this->configuracion,0,0,FALSE,$tab++,'vigencia');
                                }    
                            echo $lista_combo;
                          ?>
                         </td>
                         <td class='texto_elegante ' valign='top' width="30%" >
                         Documento :   
                         <? //verifica que tipo de caja debe armar
                           $funcionario=isset($_REQUEST['identificacion'])?$_REQUEST['identificacion']:'';  
                           echo $this->html->cuadro_texto('identificacion',$this->configuracion,$funcionario,'',0,'',20,25,"");
                          ?>
                         </td>
                         <td class='texto_elegante ' valign='top' width="10%" >
                            Buscar :
                            
                            
                            <?// (isset($_REQUEST['opBuscar']) && $_REQUEST['opBuscar']=='clas')?'checked':'';?>
                            
                            <br>
                                <input type="radio" name="opBuscar" value="Todos" checked> Todos<br>
                                <input type="radio" name="opBuscar" value="clas" <? if(isset($_REQUEST['opBuscar']) && $_REQUEST['opBuscar']=='clas'){echo'checked';}?> > Con escalafòn<br>
                                <input type="radio" name="opBuscar" value="noClass" <? if(isset($_REQUEST['opBuscar']) && $_REQUEST['opBuscar']=='noClass'){echo'checked';}?>> Sin escalafòn
                         </td>                         
                    <td align='left' >
                          <input type='hidden' name='action' value='<? echo $this->formulario;?>'>
                          <input type='hidden' name='opcion' value='buscar'> 
                          <input value="Consultar" name="listar_personas" tabindex="<?= $tab++ ?>" type="button" onclick="document.forms['<? echo $this->formulario?>'].submit()">
                    </td>
                </tr>
              </table>
            </form>
           </td>
          </tr>
         
         </tbody>
        </table>
        <br><br>
	<?   
        }    
    
    function listar_escalafon_tributario($datos_escalafon) {
        
        $this->formulario2 = "admin_escalafonTributario"; 
        $this->verificar = "verificar_Checks(".$this->formulario2.", 1, '')";
        $texto_ayuda =" Seleccionar - Deseleccionar todos "; //el comentario sirve para el texto ayuda
        ?>

        <!-- Script para seleccionar todos los ckechbox-->
        <script type="text/javascript">
            $(document).ready(function(){

                    //Checkbox
                    $("input[name=checktodos]").change(function(){
                            $('input[type=checkbox]').each( function() {			
                                    if($("input[name=checktodos]:checked").length == 1){
                                            this.checked = true;
                                    } else {
                                            this.checked = false;
                                    }
                            });
                    });

            });
            </script>
            <!-- permite exporta a hoja de calculo-->
            <script type='text/javascript'>//<![CDATA[ 
                $(window).load(function(){
                $("#btnExport").click(function(e) {
                window.open('data:application/vnd.ms-excel,' + encodeURIComponent($('#dvData').html()));
                e.preventDefault();
                });
                });//]]>  
 
            </script>
            <button name="boton" type="submit" id="btnExport">
                <img src="<? echo $this->configuracion["host"].$this->configuracion["site"].$this->configuracion["grafico"];?>/excel2.jpg" width="25" height="25" >
                <br>Exportar
            </button>
            <center> <div class="holder"></div></center>
            <form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario2;?>' id='<? echo $this->formulario2;?>' >
                
                <div id="dvData">
                <table class='bordered'  width ="95%"  >
                    
                <th class='espacios_proyecto' colspan ='8'>ESCALAFÒN TRIBUTARIO<br></th>    
                    <tr>
                        <th class='subtitulo_th centrar' >Vigencia</th>
                        <th class='subtitulo_th centrar' >Identificaciòn</th>
                        <th class='subtitulo_th centrar' >Nombres</th>
                        <th class='subtitulo_th centrar' >Apellidos</th>
                        <th class='subtitulo_th centrar' >Vinculaciòn</th>
                        <th class='subtitulo_th centrar' >Escalafón</th>
                        <th class='subtitulo_th centrar' >Respuestas</th>
                        <th class='subtitulo_th centrar'><input name="checktodos" type="checkbox" /><span onmouseover="return escape ('<? echo $texto_ayuda?>')" > Todos </span>  </th>
                        
                        

                    </tr>
                    <tbody id="escalafon">
                    <?
                    //var_dump($datos_pre);
                    //exit;
                     
                    foreach ($datos_escalafon as $key => $dato) 
                        {  ?>
                            <tr>
                                <td class='estilo_td texto_elegante' style='text-align:center;' ><?echo $dato['vigencia']; ?></td>
                                <td class='estilo_td texto_elegante'><?echo $dato['identificacion']; ?></td>
                                <td class='estilo_td texto_elegante'><?echo $dato['nombre']; ?></td>
                                <td class='estilo_td texto_elegante'><?echo $dato['apellido']; ?></td>
                                <td class='estilo_td texto_elegante'><?echo $dato['vinculacion']; ?></td>
                                <td class='estilo_td texto_elegante'><?echo $dato['escalafon']; ?></td>
                                <td class='estilo_td texto_elegante'  style='text-align:center;'><?echo $dato['respuestas']; ?></td>
                                <td class='estilo_td texto_elegante' style='text-align:center;'>
                                    <input type="checkbox" tabindex="<?echo $tab++ ?>" name= "<? echo 'per_esc_'.$key ?>" value="<? echo $dato['identificacion'] ?>" id="<?echo "per_esc_".$key; ?>">
                                    <input type="hidden" name= "<? echo 'tipo_ident_'.$key; ?>" value="<? echo $dato['tipo_identificacion']; ?>" id="<? 'tipo_ident_'.$key; ?>">
                                    <input type="hidden" name= "<? echo 'escal_'.$key; ?>" value="<? echo $dato['id_escalafon']; ?>" id="<? 'escal_'.$key; ?>">
                                </td>
                            </tr>
                     <? } ?>    
                    </tbody>
                    <tr>
                        <td class='estilo_td2 texto_elegante' colspan='7'>
                    <center>
                        <input id="registrarBoton" type="submit" class="navbtn"  value="Calcular Escalafon" > 
                        <!--input value="Calcular Escalafon" name="Calcular Escalafon" tabindex='<?= $tab++ ?>' type="button" onclick=" if(confirm('Recuerde que una vez guarde la información no puede modificarla! Desea Recibir los tramites? ')){if(<?= $this->verificar; ?>){document.forms['<? echo $this->formulario2?>'].submit()}else{false; alert('Debe seleccionar almenos una opción')}}else{false}"/-->
                    </center>
                    <input type='hidden' name='opcion' value='clasificar'>
                    <input type='hidden' name='action' value='<? echo $this->formulario2; ?>'>
                    <input type='hidden' name='vigencia' value='<? echo $dato['vigencia']; ?>'>
                    <input type='hidden' name='opBuscar' value='<? echo $_REQUEST['opBuscar']; ?>'>
                    <input type='hidden' name='identificacion' value='<? echo $_REQUEST['identificacion']; ?>'>
                    <br>
                    </td>
                    </tr>
                </table>
                </div>    
                <center><div class="holder"></div></center>    
                
            </form>
          
        <?
    }

    function respuesta_info_tributario($datos_pre, $datos_respu) {

        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/dbms.class.php");
        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/sesion.class.php");
        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
        $this->formulario = "admin_DocumentosVinculacion";

        //Datos traídos desde la tabla datos básicos

        $vigencia = $_REQUEST['vigencia'];
        $vinculacion = $_REQUEST['vinculacion'];
        $estado = $_REQUEST['estado'];
        $contrato = $_REQUEST['contrato'];
        $nombre = $_REQUEST['nombre'];
        $nombre2 = $_REQUEST['nombre2'];
        $apellido = $_REQUEST['apellido'];
        $ap2 = $_REQUEST['ap2'];
        $id = $_REQUEST['identificacion'];
        $id_tipo = $_REQUEST['id_tipo'];

        $fecha_registro = strtotime($datos_respu[0]['resp_fec_registro']);

        //Datos traidos desde postgres
        ?>

        <link href = "<? echo $this->configuracion["host"] . $this->configuracion["site"] . $this->configuracion["bloques"] ?>/tributario/infoTributario/cuentaC.css" rel = "stylesheet" type = "text/css" />


        <center> <table class='bordered'  width ="85%">

                <thead>
                    <tr>
                        <th  class='encabezado_registro' colspan="1" ><img alt="Imagen" src="<? echo $this->configuracion["host"] . $this->configuracion["site"] . $this->configuracion["bloques"] ?>/tributario/infoTributario/escudo.png" /></th>
                        <th  class='encabezado_registro2'colspan="3" >
                            <br>UNIVERSIDAD DISTRITAL FRANCISCO JOSÉ DE CALDAS
                            <br>NIT 899999230-7 <br><br>
                        </th>
                    </tr>

                    <tr>
                        <td colspan='4' class='estilo_td2 texto_elegante2' align=center >

                            <br>FORMATO PERSONAS NATURALES 
                            <br>CERTIFICACIÓN DECRETO 1070 DE 2013 PARA PERSONAS NATURALES 
                            <br>VINCULADAS CONTRACTUALMENTE CON LA UNIVERSIDAD DISTRITAL

                        </td>
                    </tr>
                    <tr>
                        <td colspan='4' class='estilo_td2 texto_elegante2'  >
                            <br><br>
                            <?
                            $dias = array("Domingo, ", "Lunes, ", "Martes, ", "Miercoles, ", "Jueves, ", "Viernes, ", "Sábado, ");
                            $meses = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
                            echo 'Bogotá D.C. ' . $dias[date('w', $fecha_registro)] . " " . date('d', $fecha_registro) . " de " . $meses[date('n', $fecha_registro) - 1] . " del " . date('Y', $fecha_registro);

                            echo '<br>';
                            ?>
                            <br>
                            <br>Señores
                            <br>UNIVERSIDAD DISTRITAL FRANCISCO JOSÉ DE CALDAS
                            <br>Ciudad
                            <br><br><br>

                            Yo <? echo $nombre . ' ' . $nombre2 . ' ' . $apellido . ' ' . $ap2 ?>, por medio de la presente y para dar cumplimiento al artículo 1° 
                            del decreto 1070 de 2013, me permito informar bajo la gravedad de juramento lo siguiente:
                            <br><br>
                        </td>
                    </tr>
                </thead>
            </table>


            <form method="post" action="index.php" name='<? echo $this->formulario; ?>'>
                <table class='bordered'  width ="85%"  >

                    <tr>
                        <th class='texto_elegante estilo_th' >ID</th>
                        <th class='texto_elegante estilo_th' >Pregunta</th>
                        <th class='texto_elegante estilo_th' >SI</th>
                        <th class='texto_elegante estilo_th' >NO</th>

                    </tr>

                    <?
                    foreach ($datos_pre as $key => $dato) {
                        $id_pregunta = (isset($dato['preg_id']) ? $dato['preg_id'] : '');
                        $pregunta = (isset($dato['preg_nombre']) ? $dato['preg_nombre'] : '');
                        $id_encuesta = (isset($dato['form_enc_id']) ? $dato['form_enc_id'] : '');
                        
                        echo "<tr>";
                        echo "<td class='texto_elegante2 estilo_td' align=center width=30 >" . $id_pregunta . "</td>";
                        echo "<td class='texto_elegante estilo_td' width=500>" . $pregunta . "</td>";
                        echo "
                        <td class='texto_elegante estilo_td' width=50 style='text-align: center; vertical-align: middle;'>
                        <input type='hidden' name='id_pregunta" . $id_pregunta . "' value='$id_pregunta'>
                        <input type='radio' name='respuesta_" . $id_pregunta . "' value='SI'";

                        if ($datos_respu[$key]['resp_respuesta'] == 'SI') {
                            echo "checked>";
                        }


                        echo "            </td>";


                        echo "
                        <td class='texto_elegante estilo_td' width=50 style='text-align: center; vertical-align: middle;'>
                        <input type='hidden' name='id_pregunta" . $id_pregunta . "' value='$id_pregunta'>
                        <input type='radio' name='respuesta_" . $id_pregunta . "' value='NO'";

                        if ($datos_respu[$key]['resp_respuesta'] == 'NO') {
                            echo "checked>";
                        }


                        echo "            </td>";

                        echo "</tr>";
                    }
                    ?>

                    <tr>
                        <td class='estilo_td2 texto_elegante2' colspan='4'>
                            <?
                            echo '<br>';
                            echo 'Nombre: ' . $nombre . ' ' . $nombre2 . ' ' . $apellido . ' ' . $ap2;
                            echo '<br>';
                            echo 'Identificación: ' . $id;
                            if ($tipo_id = 1)
                                echo ' CC';
                            ?>

                        </td>
                    </tr>

                    <tr>
                        <td class='estilo_td2 texto_elegante' colspan='4'>



                    <center>
                        <input id="registrarBoton" type="submit" class="navbtn"  value="Actualizar" > 
                    </center>

                    <input type='hidden' name='opcion' value='actualizarRespuestas'>
                    <input type='hidden' name='action' value='<? echo $this->formulario; ?>'>


                    <input type='hidden' name='func_documento' value='<? echo $id ?>'>
                    <input type='hidden' name='tipo_documento' value='<? echo $id_tipo ?>'>
                    <input type='hidden' name='annio' value='<? echo $vigencia ?>'>
                    <input type='hidden' name='contrato' value='<? echo $contrato ?>'>
                    <input type='hidden' name='fecha_registro' value='<? echo date('d/m/Y') ?>'>
                    <input type='hidden' name='id_encuesta' value='<? echo $id_encuesta ?>'>

                    <br>

                    <br>



                    <? /* Para deshabilitar cambiar las respuestas, añadir disable a radio buttons
                     * comentariar mètodos de envio de datos y descomentariar envio de formulario


                      <center>


                      <a href="<?
                      $variable = 'pagina=asistenteTributario';
                      $variable.='&opcion=';
                      $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                      echo $this->indice . $variable;
                      ?>" >
                      <img alt="Ir a Formulario" src="<? echo $this->configuracion["host"] . $this->configuracion["site"] . $this->configuracion["bloques"] ?>/tributario/IconSummary-blue.jpg" /></a>
                      </center>

                      <br>


                     */ ?>


                    </td>
                    </tr>


                </table>
            </form>       </center><?
    }

}
?>