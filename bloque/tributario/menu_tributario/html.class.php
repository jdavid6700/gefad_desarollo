<head>
    <?php
    /*
      ############################################################################
      #    UNIVERSIDAD DISTRITAL Francisco Jose de Caldas                        #
      #    Copyright: Vea el archivo EULA.txt que viene con la distribucion      #
      ############################################################################
     */
    ini_set('display_errors', 0);
    if (!isset($GLOBALS["autorizado"])) {
        include("../index.php");
        exit;
    }

    class html_menuReporteFin {

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
         * Funcion que muestra menu de tributario
         * @param <array> $vigencias
         */
        function menu() {

 
            ?>    

            <script type="text/javascript" src="<? echo $this->configuracion["host"] . $this->configuracion["site"] . $this->configuracion["plugins"]; ?>/jquery/js/jquery-1.9.1.js"></script>
            <script type="text/javascript" src="<? echo $this->configuracion["host"] . $this->configuracion["site"] . $this->configuracion["plugins"]; ?>/jquery/js/jquery-1.9.1.min.js"></script>


            <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />

            <meta charset="utf-8" />

            <!-- add styles -->
            <link href="bloque/nomina/cuotas_partes/menu_cuotasP/estilo_repFin.css"	rel="stylesheet" type="text/css" />

        </head>
        <body>
            <div class="container">
                <ul id="nav">


                    <li>
                        <a href="#s1">Información Tributaria </a>
                        <span id="s1"></span>
                        <ul class="subs">

                            <li><a href="#">Información Tributaria</a>
                                <ul>
                                    <li><a href=
                                           "<?
                                           $variable = "pagina=asistenteTributario";
                                           $variable.="&opcion=consultar";
                                           $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                                           echo $this->indice . $variable;
                                           ?>">Registro Información Tributaria</a></li>


                                </ul>
                            </li>

                        </ul>
                    </li>


                </ul>
            </div>
        </body>



        <?
    }

}
?>