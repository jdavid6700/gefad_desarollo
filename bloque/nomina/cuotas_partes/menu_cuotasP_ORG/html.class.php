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
         * Funcion que muestra el formulario para seleccionar una vigencia
         * @param <array> $vigencias
         */
        function menu() {

            $variable = "pagina=reportesCuotas";
            $variable.="&opcion=buscar";

            //enlace para reporte de información pensionados cuotas partes
            $repCPPensionados = $variable;
            $repCPPensionados.="&reporte=CPPensionados";
            $repCPPensionados.="&Violeta=true";
            $repCPPensionados = $this->cripto->codificar_url($repCPPensionados, $this->configuracion);

            //enlace para reporte de información pensionados cuotas partes salarios
            $repvaloresPensionados = $variable;
            $repvaloresPensionados.="&reporte=valoresPensionados";
            $repvaloresPensionados = $this->cripto->codificar_url($repvaloresPensionados, $this->configuracion);

            //enlace para reporte de información pensionados cuotas partes historia laboral
            $repcpHistoria = $variable;
            $repcpHistoria.="&reporte=cpHistoria";
            $repcpHistoria = $this->cripto->codificar_url($repcpHistoria, $this->configuracion);


            //enlace para reporte de información pensionados cuotas partes valores pagados
            $repvaloresPensionados = $variable;
            $repvaloresPensionados.="&reporte=valoresPensionados";
            $repvaloresPensionados = $this->cripto->codificar_url($repvaloresPensionados, $this->configuracion);

            //enlace para reporte de informacion pensionados cuotas partes valores calculados pro entidad
            $repcpPagos = $variable;
            $repcpPagos.="&reporte=cpPagos";
            $repcpPagos = $this->cripto->codificar_url($repcpPagos, $this->configuracion);
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

                    <li><a href="#s1">Presupuesto</a>
                    </li>



                    <li>
                        <a href="#s1">Contabilidad</a>
                    </li>



                    <li>
                        <a href="#s1">Tesorería</a>
                        <span id="s1"></span>
                    </li>



                    <li>
                        <a href="#s1">Contratación</a>
                        <!--span id="s1"></span>
                        <ul class="subs">
                            <li><a href="#">Terceros</a>
                                <ul>
                                    <li><a href='<? echo $this->indice . $repListaTerceroPN; ?>'>Listado Personas Naturales</a></li>
                                    <li><a href='<? echo $this->indice . $repListaTerceroPJ; ?>'>Listado Personas Jurídicas</a></li>

                                </ul>
                            </li>

                        </ul-->
                    </li>

                    <li>
                        <a href="#s1">Nómina </a>
                        <span id="s1"></span>
                        <ul class="subs">
                            <li><a href="#">Funcionarios</a></li>
                            <li><a href="#">Contratistas</a></li>

                            <li><a href="#">Cuotas Partes</a>
                                <ul>
                                    <li><a href='<? echo $this->indice . $repCPPensionados; ?>'>Información Básica Pensionados</a></li>

                                    <li><a href=
                                           "<?
                                           $variable = "pagina=formHistoria";
                                           $variable.="&opcion=";
                                           $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                                           echo $this->indice . $variable;
                                           ?>">Formulario Registro Historia Laboral</a></li>

                                    <li><a href='<? echo $this->indice . $repcpHistoria; ?>'>Historia Laboral del Pensionado</a></li>
                                    <!--li><a href='<? echo $this->indice . $repvaloresPensionados; ?>'>Valores Pagados Pensionados</a></li-->
                                    <li><a href='<? echo $this->indice . $repcpPagos; ?>'>Relación Valor Pago Mensual Pensionado con Cuota Parte</a></li>
                                    
                                    <li><a href=
                                           "<?
                                           $variable = "pagina=cuentaCobro";
                                           $variable.="&opcion=";
                                           $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                                           echo $this->indice . $variable;
                                           ?>">Cuenta de Cobro (beta)</a></li>
                                </ul>
                            </li>
                                
                              <!--li><a href="#">Cuenta de Cobro</a>
                                <ul>
                                    <li><a href='<? echo $this->indice . $repCPPensionados; ?>'>Información Básica Pensionados</a></li>

                                    <li><a href=
                                           "<?
                                           $variable = "pagina=cuentaCobro";
                                           $variable.="&opcion=";
                                           $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                                           echo $this->indice . $variable;
                                           ?>">Cuenta de Cobro</a></li>

                                    <li><a href='<? echo $this->indice . $repcpHistoria; ?>'>Historia Laboral del Pensionado</a></li>
                                    <li><a href='<? echo $this->indice . $repvaloresPensionados; ?>'>Valores Pagados Pensionados</a></li>
                                    <li><a href='<? echo $this->indice . $repcpPagos; ?>'>Relación Valor Pago Mensual Pensionado con Cuota Parte</a></li>
                                </ul>
                            </li-->
                                
                                
                        </ul>
                    </li>




                    <!--a href="#s1">Contratación</a>
                <span id="s1"></span>
                <ul class="subs">
                    <li><a href="#">Terceros</a>
                        <ul>
                            <li><a href='<? //echo $this->indice . $repListaTerceroPN;            ?>'>Listado Personas Naturales</a></li>
                            <li><a href='<? //echo $this->indice . $repListaTerceroPJ;            ?>'>Listado Personas Jurídicas</a></li>

                        </ul>
                    </li>

                </ul>
                </li-->

                </ul>
            </div>
        </body>



        <?
    }

}
?>