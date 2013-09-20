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


class html_menuNovedad {
    
    public $configuracion;
    public $cripto;
    public $indice;
    
    function __construct($configuracion) {
        
        $this->configuracion = $configuracion;   
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
        $indice=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
        $this->cripto=new encriptar();
        $this->indice=$configuracion["host"].$configuracion["site"]."/index.php?";
    }    
   
 /**
  * Esta funcion presenta el menu para las novedades 
  */
  function mostrarMenuFlotante() {
	$interno_oc = isset($_REQUEST['interno_oc'])?$_REQUEST['interno_oc']:'';
	
        $indice=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
	$ruta="pagina=adminNovedad";
	$ruta.="&opcion=crearNovedad";
	$ruta.="&interno_oc=".$interno_oc;
        $rutaCrear=$this->cripto->codificar_url($ruta,$this->configuracion);
	
      ?>
    <header>
<!--        <div class="top">-->
        <div>
            <table class="sigma_borde"  width="100%">
		<tr>
			<td class="cuadro_plano ">
				<div id="navbar">
					<span class="inbar">
						<ul>
							<li><a href="<?echo $indice.$rutaCrear?>"><span>:: Registrar Novedad</span></a></li>
							
						</ul>
					</span>
				</div>
			</td>
		</tr>
	  </table>
        </div>
    </header>
    <br>
    <?

    }

}
?>
