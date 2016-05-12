<?php 
/*
 ############################################################################
#    UNIVERSIDAD DISTRITAL Francisco Jose de Caldas                        #
#    Copyright: Vea el archivo EULA.txt que viene con la distribucion      #
############################################################################
*/

if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;
}


class html_reporteFinanciero {

	public $configuracion;
	public $cripto;
	public $indice;

	function __construct($configuracion) {

		$this->configuracion = $configuracion;
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
		$indice=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
		$this->cripto=new encriptar();
		$this->html = new html();

	}

        
        
        function bienvenida()
        {   echo "<br>Bienvenido al Módulo de reportes de modulo financiero";
            
        }        
        

 /**
     * Funcion que muestra el formulario para seleccionar los parametros del reporte
     * @param <array> $vigencias
     */
    function form_muestra_parametros($parametrosHtml){
            $tab=0;
            $this->formulario = "repoFinanciero";
          //  var_dump(var_dump($parametrosHtml));
       ?>
        <table width="100%" align="center" border="0" cellpadding="10" cellspacing="0">
         <tr class="texto_subtitulo">
           <td><br><br>
               <?echo $parametrosHtml[0]['titulo'];?><br><br>
               <hr class="hr_subtitulo">
           </td>
         </tr>       
         <tbody> 
          <tr>
           <td>
             <form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario;?>'>
               <table align="center"  border="0" width="98%" >
                <?
                   foreach($parametrosHtml as $key=>$value)
                          {  
                          ?><tr> 
                             <td class='texto_elegante ' valign='top' width="120px" >
                               <? echo ucfirst($parametrosHtml[$key]['nombre']).": ";?>   
                              </td> 
                              <td class='texto_elegante ' valign='top' >
                                  <?
                              //verifica que tipo de caja debe armar
                              switch($parametrosHtml[$key]['caja_html'])
                                  {  case 'combo':
                                                   if($parametrosHtml[$key]['actualiza'])    
                                                        { //identifica que parametros de deben enviar registrados en la base de datos
                                                           $controlPar = explode("|", $parametrosHtml[$key]['enviar']);
                                                           //identifica cada uno de lso parametros a enviar
                                                           $control_param='';
                                                           $post_param='';
                                                            foreach($controlPar as $par=>$value) 
                                                                { //rescata los valores del formulario a envia
                                                                  if($controlPar[$par] != $parametrosHtml[$key]['nombre'] )
                                                                       { $control_param.= $controlPar[$par]."=$(".$this->formulario.".".$controlPar[$par].").val(); ";
                                                                         $post_param.= $controlPar[$par].":".$controlPar[$par].", ";
                                                                       }
                                                                }

                                                            ?>
                                                            <script language="javascript">
                                                             $(document).ready(function()
                                                                                {  $("#<? echo $parametrosHtml[$key]['nombre'];?>").change(function () 
                                                                                    {      reporte=<? echo $parametrosHtml[$key]['id_reporte'];?>;
                                                                                           parametro='<? echo $parametrosHtml[$key]['actualiza'];?>';
                                                                                           <? echo $control_param;?>
                                                                                      $("#<? echo $parametrosHtml[$key]['nombre'];?> option:selected" ).each(function ()
                                                                                           {<? echo $parametrosHtml[$key]['nombre'];?>=$(this).val();        
                                                                                            $.post("<?echo $this->configuracion["host"].$this->configuracion["site"].$this->configuracion["bloques"].'/reportes/repoFinanciero/combos.php';?>", 
                                                                                                    {   reporte:reporte, parametro:parametro,
                                                                                                        <? echo $post_param;?> 
                                                                                                        <? echo $parametrosHtml[$key]['nombre'];?>: <? echo $parametrosHtml[$key]['nombre'];?> 
                                                                                                    }, function(data) 
                                                                                                        {$("#<? echo $parametrosHtml[$key]['actualiza'];?>").html(data);
                                                                                                        }    
                                                                                                  );            
                                                                                           });
                                                                                     })
                                                                                });
                                                             </script>    
                                                            <?   
                                                            }
                                                  unset($combo);
                                                  //prepara los datos como se deben mostrar en el combo
                                                  $combo[0][0]='0';
                                                  $combo[0][1]='Todos';
                                                  foreach($parametrosHtml[$key]['datos'] as $cmb => $values)
                                                       {  $combo[$cmb+1][0]=isset($parametrosHtml[$key]['datos'][$cmb][0])?$parametrosHtml[$key]['datos'][$cmb][0]:0;
                                                          $combo[$cmb+1][1]=isset($parametrosHtml[$key]['datos'][$cmb][1])?$parametrosHtml[$key]['datos'][$cmb][1]:'';
                                                       }
                                                   if (isset($_REQUEST[$parametrosHtml[$key]['nombre']]))    
                                                       { $lista_combo = $this->html->cuadro_lista($combo,$parametrosHtml[$key]['nombre'],$this->configuracion,$_REQUEST[$parametrosHtml[$key]['nombre']],0,FALSE,$tab++,$parametrosHtml[$key]['nombre']);
                                                       }
                                                   else{ $lista_combo = $this->html->cuadro_lista($combo,$parametrosHtml[$key]['nombre'],$this->configuracion,-1,0,FALSE,$tab++,$parametrosHtml[$key]['nombre']);
                                                       }    
                                                   echo $lista_combo;

                                           break;

                                     default: 
                                          echo $this->html->cuadro_texto($parametrosHtml[$key]['nombre'],$this->configuracion,$parametrosHtml[$key]['datos'],'',0,'',20,25,"");
                                          break;
                                  }
                            ?></td>
                           </tr> 
                       <? }  ?>
                 <tr>
                    <td align='center' colspan ="2"><br> 
                          <input type='hidden' name='action' value='repoFinanciero'>
                          <input type='hidden' name='opcion' value='generar'> 
                          <input type='hidden' name='reporte' value='<? echo $parametrosHtml[0]['reporte']; ?>'> 
                          <input type='hidden' name='pagina' value='<? echo $parametrosHtml[0]['pagina']; ?>'> 
                          <input value="Generar" name="generar_reporte" tabindex="<?= $tab++ ?>" type="button" onclick="document.forms['<? echo $this->formulario?>'].submit()">
                    </td>
                </tr>
              </table>
            </form>
           </td>
          </tr>
         </tbody>
        </table>
	<?   
        }        

        /**
         * funcion que muestra la información del reporte
         */
        
        function mostrarReportes($configuracion,$registro,$nombre,$titulo)
        {   include_once($configuracion["raiz_documento"].$configuracion["clases"]."/reporteadorHtml.class.php");
            $reporte = new reporteador();
            $reporte->mostrarReporte($configuracion,$registro,$nombre,$titulo);
        }
        /**
         * funcion que muestra la información del reporte
         */
        
        function sinDatos($configuracion,$titulo)
        {   include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php"); 
            $cadena=".::".$titulo."::."; 
            $cadena=htmlentities($cadena, ENT_COMPAT, "UTF-8");
            alerta::sin_registro($configuracion,$cadena);
        }        

}
?>