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

            $variable = "pagina=reportesFinanciero";
            $variable.="&opcion=buscar";
            //enlace para reporte de reservas
            $repReservas1 = $variable;
            $repReservas1.="&reporte=reservas01";
            $repReservas1.="&unidad=01";
            $repReservas1 = $this->cripto->codificar_url($repReservas1, $this->configuracion);

            $repReservas2 = $variable;
            $repReservas2.="&reporte=reservas02";
            $repReservas2.="&unidad=02";
            $repReservas2 = $this->cripto->codificar_url($repReservas2, $this->configuracion);

            //enlace para reporte de lista de terceros
            $repListaTerceroPN = $variable;
            $repListaTerceroPN.="&reporte=listaTercerosPN";
            $repListaTerceroPN = $this->cripto->codificar_url($repListaTerceroPN, $this->configuracion);

            //enlace para reporte de lista de terceros
            $repListaTerceroPJ = $variable;
            $repListaTerceroPJ.="&reporte=listaTercerosPJ";
            $repListaTerceroPJ = $this->cripto->codificar_url($repListaTerceroPJ, $this->configuracion);

            //enlace para reporte de registros presupuestales
            $repCRP = $variable;
            $repCRP.="&reporte=registroPresupuestalxrubro";
            $repCRP = $this->cripto->codificar_url($repCRP, $this->configuracion);

            //enlace para reporte de disponibilidades
            $repCDP = $variable;
            $repCDP.="&reporte=disponibilidadPresupuestalxrubro";
            $repCDP = $this->cripto->codificar_url($repCDP, $this->configuracion);


            //enlace para reporte de ordenes de pago
            $repOP = $variable;
            $repOP.="&reporte=ordenesPagoxrubro";
            $repOP = $this->cripto->codificar_url($repOP, $this->configuracion);

            //enlace para reporte de SIIGO ordenes de pago  
            $repSIIGO_op = $variable;
            $repSIIGO_op.="&reporte=SIIGO_op";
            $repSIIGO_op = $this->cripto->codificar_url($repSIIGO_op, $this->configuracion);

            //enlace para reporte de SIIGO para importación desde SIICAPITAL para órdenes de pago
            $repSIIGO_op_formato = $variable;
            $repSIIGO_op_formato.="&reporte=SIIGO_op_formato";
            $repSIIGO_op_formato = $this->cripto->codificar_url($repSIIGO_op_formato, $this->configuracion);
            ?>    
            <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
            <meta charset="utf-8" />

            <!-- add styles -->
            <link href="bloque/reportes/menu_reportesFin/estilo_repFin.css"	rel="stylesheet" type="text/css" />
        <body>
            <div class="container">
                <ul id="nav">

                    <li><a href="#s1">Presupuesto</a>
                        <span id="s1"></span>
                        <ul class="subs">
                            <li><a href="#">Reservas</a>
                                <ul>
                                    <li><a href="<? echo $this->indice . $repReservas1; ?>">Rectoría</a></li>
                                    <li><a href="<? echo $this->indice . $repReservas2; ?>">Convenios</a></li>
                                </ul>
                            </li>
                            <li><a href="<? echo $this->indice . $repCDP; ?>">Disponibilidades</a>
                            </li>
                            <li><a href="<? echo $this->indice . $repCRP; ?>">Registros</a>

                            <li><a href="<? echo $this->indice . $repOP; ?>">Órdenes de Pago</a>

                            </li>
                        </ul>
                    </li>



                    <li>
                        <a href="#s1">Contabilidad</a>
                        <span id="s1"></span>
                        <ul class="subs">
                            <li><a href="#">SIIGO</a>
                                <ul>
                                    <li><a href='<? echo $this->indice . $repSIIGO_op; ?>'>Órdenes Pago</a></li>
                                    <li><a href='<? echo $this->indice . $repSIIGO_op_formato; ?>'>OP Formato SICAPITAL</a></li>

                                </ul>
                            </li>

                        </ul>
                    </li>



                    <li>
                        <a href="#s1">Tesorería</a>
                        <span id="s1"></span>
                        <ul class="subs">
                            <li><a href="#">Terceros</a>
                                <ul>
                                    <li><a href='<? echo $this->indice . $repListaTerceroPN; ?>'>Listado Personas Naturales</a></li>
                                    <li><a href='<? echo $this->indice . $repListaTerceroPJ; ?>'>Listado Personas Jurídicas</a></li>

                                </ul>
                            </li>

                        </ul>
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
                        </ul>
                    </li>




                    <!--a href="#s1">Contratación</a>
                <span id="s1"></span>
                <ul class="subs">
                    <li><a href="#">Terceros</a>
                        <ul>
                            <li><a href='<? echo $this->indice . $repListaTerceroPN; ?>'>Listado Personas Naturales</a></li>
                            <li><a href='<? echo $this->indice . $repListaTerceroPJ; ?>'>Listado Personas Jurídicas</a></li>

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