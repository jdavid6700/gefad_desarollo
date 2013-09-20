<?php
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
//Esta clase contiene la logica de negocio del bloque y extiende a la clase funcion general la cual encapsula los
//metodos mas utilizados en la aplicacion

//Para evitar redefiniciones de clases el nombre de la clase del archivo funcion debe corresponder al nombre del bloque
//en camel case precedido por la palabra funcion
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");

class funcionadminAsistente extends funcionGeneral
{

        var $ruta;
        var $sql;
        var $funcion;
        var $lenguaje;
        var $formulario;

	function __construct($configuracion)
	{
		//[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo		
		//include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
		include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
		$this->tema=$tema;
                //Los objetos de esta clase deben conectarse a la base principal del aplicativo, no a la estructura
                $this->conectarDB($configuracion, "");
	}

        public function setRuta($unaRuta){
            $this->ruta=$unaRuta;
            //Incluir las funciones
        }

        function setSql($a)
        {
                 $this->sql=$a;
        }

        function setFuncion($funcion)
        {
                 $this->funcion=$funcion;
        }

        public function setLenguaje($lenguaje)
        {
            $this->lenguaje=$lenguaje;
        }

        public function setFormulario($formulario){
            $this->formulario=$formulario;
        }

        function verificarCampos($configuracion){
            include_once($this->ruta."/funcion/verificarCampos.php");
            if($error==true){
                return false;
            }else{
                return true;
            }
            
            
        }


        function verificarRegistro($configuracion)
        {
            include_once($this->ruta."/funcion/verificarRegistro.php");
        }


        function nuevo($configuracion){
            include_once($this->ruta."/funcion/nuevo.php");
        }

        function confirmar($configuracion){
            include_once($this->ruta."/funcion/confirmar.php");
        }

        function confirmarEditar($configuracion){
            include_once($this->ruta."/funcion/confirmarEditar.php");
        }

        function editar($configuracion){
            include_once($this->ruta."/funcion/editar.php");
        }

        function redireccionar($configuracion, $opcion, $valor=""){
            include_once($this->ruta."/funcion/redireccionar.php");
        }

        function action($configuracion)
	{

                


                //Procesar el formulario
//                foreach($_REQUEST as $clave=>$valor){
//                    echo $clave."--->".$valor."<br>";
//                }
//                exit;
                            
                //Evitar que se ingrese codigo HTML y PHP en los campos de texto
                $this->excluir="descripcion|observacion";
                $this->revisarFormulario();



                if(!isset($_REQUEST["opcion"])
                    ||(isset($_REQUEST["opcion"])
                        &&($_REQUEST["opcion"]=="nuevo" || $_REQUEST["opcion"]=="editar"))){

                        $validacion=$this->verificarCampos($configuracion);

                        if($validacion==false){
                                //Instanciar a la clase pagina con mensaje de correcion de datos
                                echo "Datos Incorrectos";

                        }else{
                                   //Validar las variables para evitar ataques por insercion de SQL
                                    $_REQUEST=$this->verificarVariables($configuracion, $_REQUEST);
                                     //Si el campo region o el campo ciudad no son numeros indica que el usuario
                                    //ingreso nuevos datos y la base de datos debe actualizarse.

                                   if(!isset($_REQUEST['opcion'])||$_REQUEST["opcion"]=="nuevo"){

                                            $resultado=$this->verificarRegistro($configuracion);

                                            if($resultado==true){

                                                 $identificador=time();
                                                 if(isset($_REQUEST["opcion"])&&$_REQUEST["opcion"]=="antiguo"){
                                                            $this->recursoPersonalAntiguo($configuracion);
                                                    }else{
                                                           //echo "El nombre de usuario ya est&aacute; registrado.";exit;
                                                           $this->redireccionar($configuracion, "personalExiste");

                                                    }
                                            }else{
                                                    $this->nuevo($configuracion);

                                            }
                                    }else{

                                                   if($_REQUEST["opcion"]=="editar"){
                                                        $this->editar($configuracion);
                                                   }
                                    }

                        }
                }else{

                        if($_REQUEST["opcion"]=="confirmar"){
                            $this->confirmar($configuracion);
                       }else{

                          if($_REQUEST["opcion"]=="confirmarEditar"){
                            $this->confirmarEditar($configuracion);
                          }
                       }
                }
        }
}
?>
