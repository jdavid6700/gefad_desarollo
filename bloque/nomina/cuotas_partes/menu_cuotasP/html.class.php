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

    class html_menu_cuotasP {

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

                            <li><a href="#">Módulo de Consulta </a>
                                <ul>
                                    <li><a href='<? echo $this->indice . $repCPPensionados; ?>'>Información Básica Pensionados</a></li>

                                    <li><a href='<?
                                        $variable = 'pagina=formHistoria';
                                        $variable.='&opcion=dbasicoHistoria';
                                        $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                                        echo $this->indice . $variable;
                                        ?>'>Consultar Historia Laboral</a></li>

                                    <li><a href="<?
                                        $variable = 'pagina=formularioRecaudo';
                                        $variable.='&opcion=consulta_cp';
                                        $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                                        echo $this->indice . $variable;
                                        ?>">Consultar Estado de Cuenta</a></li>
                                    
                                    <li><a href="<?
                                        $variable = 'pagina=formularioSustituto';
                                        $variable.='&opcion=reporte';
                                        $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                                        echo $this->indice . $variable;
                                        ?>">Consultar Sustitutos Registrados</a></li>
                                </ul>
                            </li>

                            <li><a href="#">Gestión Datos Pensionado</a>
                                <ul>
                                    <li><a href=
                                           "<?
                                           $variable = "pagina=formHistoria";
                                           $variable.="&opcion=";
                                           $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                                           echo $this->indice . $variable;
                                           ?>">Registrar Historia Laboral</a></li>

                                    <li><a href="<?
                                        $variable = 'pagina=formularioConcurrencia';
                                        $variable.='&opcion=';
                                        $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                                        echo $this->indice . $variable;
                                        ?>">Registrar Concurrencia Aceptada</a></li>

                                    <li><a href="<?
                                        $variable = 'pagina=formularioRecaudo';
                                        $variable.='&opcion=';
                                        $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                                        echo $this->indice . $variable;
                                        ?>">Registrar Recaudos (Pagos) con Cuenta de Cobro</a></li>
                                    
                                    <!--li><a href="<?
                                        $variable = 'pagina=formularioRManual';
                                        $variable.='&opcion=';
                                        $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                                        echo $this->indice . $variable;
                                        ?>">Registrar Recaudos Manual</a></li-->
                                    
                                     <li><a href="<?
                                        $variable = 'pagina=formularioSustituto';
                                        $variable.='&opcion=';
                                        $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                                        echo $this->indice . $variable;
                                        ?>">Registrar Sustitutos</a></li>
                                </ul>
                            </li>

                            <li><a href="#">Gestión Información del Sistema</a>
                                <ul>
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
                                        ?>">Registrar y Consultar Índice Precios Consumidor (IPC)</a></li>

                                    <li><a href="<?
                                        $variable = 'pagina=formularioDTF';
                                        $variable.='&opcion=';
                                        $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                                        echo $this->indice . $variable;
                                        ?>">Registrar y Consultar Índice Tasa de Interés</a></li>

                                    <li><a href="<?
                                        $variable = 'pagina=formularioSalario';
                                        $variable.='&opcion=';
                                        $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                                        echo $this->indice . $variable;
                                        ?>">Registrar y Consultar Salarios Mínimos Legales</a></li>

                                </ul>
                            </li>

                            <li><a href="#">Gestión Liquidación</a>
                                <ul>                               
                                    <li><a href=
                                           "<?
                                           $variable = "pagina=liquidadorCP";
                                           $variable.="&opcion=";
                                           $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                                           echo $this->indice . $variable;
                                           ?>">Generar Liquidación para Cuota Parte</a></li>

                                    <li><a href=
                                           "<?
                                           $variable = "pagina=liquidadorCP";
                                           $variable.="&opcion=reporte_inicio";
                                           $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                                           echo $this->indice . $variable;
                                           ?>">Generar Formatos de Cuenta de Cobro</a></li>
                                                                        
                                    <li><a href="<?
                                        $variable = 'pagina=formularioCManual';
                                        $variable.='&opcion=manual';
                                        $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                                        echo $this->indice . $variable;
                                        ?>">Registrar Cuenta Cobro Manual</a></li>
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
