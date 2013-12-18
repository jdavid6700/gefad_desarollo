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

            //enlace para reporte de informacion pensionados cuotas partes valores calculados pro entidad
            $repRecaudos = $variable;
            $repRecaudos.="&reporte=cpRecaudos";
            $repRecaudos = $this->cripto->codificar_url($repRecaudos, $this->configuracion);
            ?>    
            <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
            <meta charset="utf-8" />

            <!-- add styles -->
            <link href="<? echo $this->configuracion["host"] . $this->configuracion["site"] . $this->configuracion["bloques"] ?>/reportes/menu_reportesFin/estilo_repFin.css"	rel="stylesheet" type="text/css" />
        <body>
            <div class="container">
                <ul id="nav">

                    <li>
                        <a href="#s1">Cuotas Partes </a>
                        <span id="s1"></span>
                        <ul class="subs">

                            <li><a href="#">Consulta</a>
                                <ul>
                                    <li><a href='<? echo $this->indice . $repCPPensionados; ?>'>Información Básica Pensionados</a></li>


                                    <li><a href='<?
                                        $variable = 'pagina=formHistoria';
                                        $variable.='&opcion=dbasicoHistoria';
                                        $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                                        echo $this->indice . $variable;
                                        ?>'>Consultar Historia Laboral</a></li>

                                    <!--li><a href=
                                            "<?
                                    $variable = "pagina=liquidadorCP";
                                    $variable.="&opcion=";
                                    $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                                    echo $this->indice . $variable;
                                    ?>">Consultar Detalle Liquidación Cuota Parte</a></li-->
                                </ul>
                            </li>

                            <li><a href="#">Gestión</a>
                                <ul>
                                    <li><a href=
                                           "<?
                                           $variable = "pagina=formHistoria";
                                           $variable.="&opcion=";
                                           $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                                           echo $this->indice . $variable;
                                           ?>">Registrar Historia Laboral</a></li>

                                    <li><a href="<?
                                        $variable = 'pagina=formularioRecaudo';
                                        $variable.='&opcion=';
                                        $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                                        echo $this->indice . $variable;
                                        ?>">Registrar y Consultar Recaudos</a></li>

                                    <li><a href="<?
                                        $variable = 'pagina=formularioCManual';
                                        $variable.='&opcion=manual';
                                        $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                                        echo $this->indice . $variable;
                                        ?>">Registrar Cuenta Cobro Manual</a></li>

                                    <li><a href="<?
                                        $variable = 'pagina=formularioPrevisora';
                                        $variable.='&opcion=';
                                        $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                                        echo $this->indice . $variable;
                                        ?>">Registrar y Consultar Entidades Previsoras y Empleadoras</a></li>

                                    <li><a href="<?
                                        $variable = 'pagina=formularioIPC';
                                        $variable.='&opcion=';
                                        $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                                        echo $this->indice . $variable;
                                        ?>">Registrar y Consultar Indice Precios Consumidor (IPC)</a></li>

                                    <li><a href="<?
                                        $variable = 'pagina=formularioDTF';
                                        $variable.='&opcion=';
                                        $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                                        echo $this->indice . $variable;
                                        ?>">Registrar y Consultar Indice Tasa de Interés</a></li>

                                    <li><a href="<?
                                        $variable = 'pagina=formularioSalario';
                                        $variable.='&opcion=';
                                        $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                                        echo $this->indice . $variable;
                                        ?>">Registrar y Consultar Salarios Mínimos Legales</a></li>

                                    <li><a href="<?
                                        $variable = 'pagina=formularioConcurrencia';
                                        $variable.='&opcion=';
                                        $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                                        echo $this->indice . $variable;
                                        ?>">Registrar Concurrencia Aceptada</a></li>
                                </ul>
                            </li>


                            <!--li><a href="#">Cuenta de Cobro</a>
                                <ul>
                                    <li><a href="<?
                            $variable = 'pagina=cuentaCobro';
                            $variable.='&opcion=';
                            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                            echo $this->indice . $variable;
                            ?>">Generar Cuenta de Cobro</a></li-->
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
