<?
/***************************************************************************
 *   PHP Application Framework Version 10                                  *
 *   Copyright (c) 2003 - 2009                                             *
 *   Teleinformatics Technology Group de Colombia                          *
 *   ttg@ttg.com.co                                                        *
 *                                                                         *
****************************************************************************/


class fronteraadminAsistente{

        var $ruta;
        var $sql;
        var $funcion;
        var $lenguaje;
        var $formulario;

        public function setRuta($unaRuta){
            $this->ruta=$unaRuta;
        }

        public function setLenguaje($lenguaje){
            $this->lenguaje=$lenguaje;
        }

        public function setFormulario($formulario){
            $this->formulario=$formulario;
        }

        function frontera($configuracion)
        {
                $this->html($configuracion);
        }

        function setSql($a)
        {
                 $this->sql=$a;

        }

        function setFuncion($funcion)
        {
                 $this->funcion=$funcion;

        }

	function html($configuracion)
	{
            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/formularioHtml.class.php");
            
            $this->miFormulario=new formularioHtml();
            $certificado="registroParticipante";
            
          		if(isset($_REQUEST['opcion'])){
                            
				$accion=$_REQUEST['opcion'];

                                

				switch($accion){
					case "verificar":
                                                include_once($this->ruta."/formulario/verificar.php");
						break;
					case "confirmar":
						include_once($this->ruta."/formulario/confirmar.php");
						break;		
					case "nuevo":
						include_once($this->ruta."/formulario/nuevo.php");
						break;
					case "editar":
						include_once($this->ruta."/formulario/editar.php");
						break;
					case "corregir":
						include_once($this->ruta."/formulario/corregir.php");
						break;
					case "mostrar":
						include_once($this->ruta."/formulario/mostrar.php");
						break;

                                        case "confirmarEditar":
                                            	include_once($this->ruta."/formulario/confirmarEditar.php");
						break;
				}
			}else{
				$accion="nuevo";
                                include_once($this->ruta."/formulario/nuevo.php");				
			}
		

	}





}
?>