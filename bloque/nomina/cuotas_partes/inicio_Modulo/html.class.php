<head>
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
    }

    class html_inicio_Modulo {

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

        
        function bienvenida(){
            
            echo "<br><br><br><br>Bienvenido al Módulo de Cuotas Partes Pensionales";
        }

}
?>
