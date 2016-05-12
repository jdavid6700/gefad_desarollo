<?
/*
 ############################################################################
#    UNIVERSIDAD DISTRITAL Francisco Jose de Caldas                        #
#    Desarrollo Por:                                                       #
#    Jairo Lavado Hernandez 2013                                           #
#    jlavadoh@correo.udistrital.edu.co                                     #
#    Copyright: Vea el archivo EULA.txt que viene con la distribucion      #
############################################################################
*/
/****************************************************************************

reporteadorHtml.class.php

Jairo Lavado 
Copyright (C) 2013

Última revisión 8 de Abril de 2013

******************************************************************************
* @subpackage
* @package	clase
* @copyright
* @version      0.1
* @author      	
* @link		
* @description  Clase para la ageneración automatica de Reportes HTML
*******************************************************************************
*******************************************************************************
*******************************************************************************

*Atributos
*
*@access private
*@param  $conexion_id
*		Identificador del enlace a la base de datos
*******************************************************************************


*/

/*****************************************************************************
 *Métodos
*
*@access public
*
******************************************************************************
* @USAGE
*
*
*
*/

class  reporteador
{
	
	/**
	 * @name repoteador
	 * constructor
	 */
	function reporteador()
	{


	}
/**
*Funcion que apartir de los datos crea el reporte
* @return type
*/

function mostrarReporte($configuracion,$registro,$nombre,$titulo)
	{   
            //var_dump($registro);exit;
            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
            $indice = $configuracion["host"].$configuracion["site"]."/index.php?";
            $cripto = new encriptar();
            //resuelve el nombre de los alias para usar el la cabecera
             foreach($registro[0] as $ali=>$value)
                        { if(!is_numeric($ali))
                              {$cabecera[$ali]=$ali;}
                        }                        
            ?>

            <link rel="stylesheet" href="<? echo $configuracion["host"].$configuracion["site"].$configuracion["plugins"];?>/jPages-master/css/jPages.css">
            <script src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["plugins"];?>/jPages-master/js/jPages.js"></script>
            <script src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["plugins"];?>/jquery/js/base64_encode.js"></script>
            <!-- permite la paginacion-->        
            <script>
                    $(function (){
                        $("div.holder").jPages({
                        containerID : "<?echo $nombre;?>",
                        previous : "←",
                        next : "→",
                        perPage : <? echo $configuracion["registro"]?>,
                        delay : 20
                        });
                    });
            </script>
            </script>
            <!-- permite exporta a hoja de calculo-->
             <script type='text/javascript'>//<![CDATA[ 
                $(window).load(function(){
                $("#btnExport").click(function(e) {
	    window.open('data:application/vnd.ms-excel;base64,' + encodeURIComponent($.base64.encode($('#dvData').html())));
	    e.preventDefault();
                });
                });//]]>  
 
            </script>
            
            
            <table width="100%" align="center" border="0" cellpadding="10"  cellspacing="0">
                    <tbody>
                            <tr>
                                    <td>
                                            <table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px">
                                                    <tr>
                                                            <td><center> <div class="holder"></div></center>
                                                                <!--input type="button" id="btnExport" value=" Exportar a Excel " /-->
                                                                <button name="boton" type="submit" id="btnExport">
                                                                    <img src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["grafico"];?>/excel2.jpg" width="25" height="25" >
                                                                    <br>Exportar
                                                                </button>
                                                                <div id="dvData">
                                                                    <table class="bordered" width="100%">
                                                                        <tr class='cuadro_color'>
                                                                            <th colspan='<? echo count($cabecera);?>' class='titulo_th'> <? echo strtoupper($titulo);?> </th>
                                                                       </tr> 
                                                                        <tr class='cuadro_color'>
                                                                            <? foreach($cabecera as $cab=>$value)
                                                                                        { ?><th  class='subtitulo_th'> <?echo ucfirst(strtolower(str_replace('_',' ',$cabecera[$cab])));?> </th>
                                                                                          <?
                                                                                        }
                                                                            ?>
                                                                       </tr> 
                                                                       <tbody id="<?echo $nombre;?>">
                                                                         <?         foreach ($registro as $key => $value)
                                                                                        {?><tr><?
                                                                                                foreach($cabecera as $cab=>$value)
                                                                                                    { ?><td class='texto_elegante estilo_td' ><?
                                                                                                        //$dato=(strtolower(substr($cabecera[$cab],0,5))=='valor'?isset($registro[$key][$cabecera[$cab]])?number_format($registro[$key][$cabecera[$cab]],2, ',', '.'):'':isset($registro[$key][$cabecera[$cab]])?$registro[$key][$cabecera[$cab]]:'');
                                                                                                        if (isset($registro[$key][$cabecera[$cab]]) && strtolower(substr($cabecera[$cab],0,5))=='valor')
                                                                                                            {$dato=number_format($registro[$key][$cabecera[$cab]],2, '.', ',');}
                                                                                                        elseif (!isset($registro[$key][$cabecera[$cab]]))
                                                                                                             {$dato='SD';}    
                                                                                                        else{ $dato=$registro[$key][$cabecera[$cab]];}
                                                                                                        echo "&nbsp;".$dato."&nbsp;";
                                                                                                        ?></td><?
                                                                                                    } 
                                                                                        ?></tr><?
                                                                                        }//fin for
                                                                          ?>
                                                                    </table>
                                                                    </div>  
                                                                    <center>
                                                                            <div class="holder"></div>
                                                                    </center>
                                                            </td>
                                                    </tr>
                                                    </tbody>
                                            </table>

                                    </td>
                            </tr>
                    </tbody>
            </table>
   
            <?
	}



}//Fin de la clase html
?>
