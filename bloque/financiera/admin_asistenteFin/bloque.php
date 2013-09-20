<?
/***************************************************************************
 *   PHP Application Framework Version 10                                  *
 *   Copyright (c) 2003 - 2009                                             *
 *   Teleinformatics Technology Group de Colombia                          *
 *   ttg@ttg.com.co                                                        *
 *                                                                         *
****************************************************************************/

if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;		
}


include_once($configuracion["raiz_documento"].$configuracion["clases"]."/bloque.class.php");
include_once("funcion.class.php");
include_once("frontera.class.php");
include_once("sql.class.php");

//Esta clase actua como control del bloque en un patron FCE


//Para evitar redefiniciones de clases el nombre de la clase del archivo bloque debe corresponder al nombre del bloque
//en camel case precedida por la palabra bloque

class bloqueadminAsistente extends bloque
{

        var $ruta;
        var $formulario;

	 public function __construct($configuracion,$lenguaje="")
	{
		$this->ruta=$configuracion["raiz_documento"].$configuracion["bloques"]."/financiera/admin_asistenteFin/";

                $this->formulario="admin_asistenteFin";
                
                $this->funcion=new funcionadminAsistente($configuracion);
                $this->sql=new sqladminAsistente();
                $this->frontera=new fronteraadminAsistente();
                if(is_array($lenguaje)){
                    $this->frontera->setLenguaje($lenguaje);
                    $this->funcion->setLenguaje($lenguaje);

                }
                $this->frontera->setRuta($this->ruta);
                $this->funcion->setRuta($this->ruta);

                $this->frontera->setFormulario($this->formulario);
                $this->funcion->setFormulario($this->formulario);
	}
	
	
	public function bloque($configuracion){

               
                if(isset($_REQUEST['botonCancelar'])&&$_REQUEST['botonCancelar']=="true"){

                       $this->funcion->redireccionar($configuracion, "paginaPrincipal");
                       
                }else{
                    
                        if(!isset($_REQUEST['action'])){

                                $this->frontera->setSql($this->sql);
                                $this->frontera->setFuncion($this->funcion);
                                $this->frontera->frontera($configuracion);
                        }else{

                                $this->funcion->setSql($this->sql);
                                $this->funcion->setFuncion($this->funcion);
                                $this->funcion->action($configuracion);

                              }
                
                }

        }
	
		
}


// @ Crear un objeto bloque especifico
if(isset($lenguaje)){
    $esteBloque=new bloqueadminAsistente($configuracion,$lenguaje);
}else{
    $esteBloque=new bloqueadminAsistente($configuracion);
}
$esteBloque->bloque($configuracion);


?>