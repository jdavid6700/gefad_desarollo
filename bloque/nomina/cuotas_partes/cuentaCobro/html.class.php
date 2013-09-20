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

    include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/dbms.class.php");
    include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/sesion.class.php");
    include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
}

class html_adminCuentaCobro {

    public $configuracion;
    public $cripto;
    public $indice;

    function __construct($configuracion) {

        $this->configuracion = $configuracion;

        include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
        include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/html.class.php");

        $this->cripto = new encriptar();
        $this->indice = $configuracion["host"] . $configuracion["site"] . "/index.php?";
        $this->html = new html();
    }

    function form_valores_cuotas_partes() {

        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/dbms.class.php");
        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/sesion.class.php");
        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
        $this->formulario = "cuentaCobro";
        ?>
        <link href = "<? echo $this->configuracion["host"] . $this->configuracion["site"] . $this->configuracion["bloques"] ?>/nomina/cuotas_partes/cuentaCobro/cuentaC.css" rel = "stylesheet" type = "text/css" />
        <form method='POST' action='index.php' name='<? echo $this->formulario; ?>'>

            <h2>Ingrese la cédula a realizar la cuenta de cobro: </h2>
            <br>
            <input type="text" name="cedula_emp" required='required'>
            <br><br>
            <center> <input id="registrarBoton" type="submit" class="navbtn"  value="Consultar" ></center>
            <input type='hidden' name='pagina' value='cuentaCobro'>
            <input type='hidden' name='opcion' value='verificar'>

            <br>
        </form>
        <?
    }

    function form_generar_cuenta($datos_en, $datos_em) {
        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/dbms.class.php");
        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/sesion.class.php");
        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");

        $this->formulario = "cuentaCobro";

        $cedula_em = (isset($datos_em[0]['EMP_NRO_IDEN']) ? $datos_em[0]['EMP_NRO_IDEN'] : '');

        ?>
        <!referencias a estilos y plugins>
        <script type = "text/javascript" src = "<? echo $this->configuracion["host"] . $this->configuracion["site"] . $this->configuracion["plugins"]; ?>/datepicker/js/datepicker.js"></script>
        <link href = "<? echo $this->configuracion["host"] . $this->configuracion["site"] . $this->configuracion["bloques"] ?>/nomina/cuotas_partes/cuentaCobro/cuentaC.css" rel = "stylesheet" type = "text/css" />
        <link href = "http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel = "stylesheet" type = "text/css"/>
        <script type = "text/javascript" src = "http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
        <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>


        <!--p>Inicio de Declaraciòn del Formulario</p-->

        <form id="form" method="post" action='index.php' name='<? echo $this->formulario; ?>'>
            <h2>Ingrese los parametros para generar la cuenta de cobro:</h2>
            <div class="formrow f1">
                <div class="formrow f1">
                    <div id="p1f4" class="field n1">
                        <div class="staticcontrol">
                            <div class="hrcenter px1"></div>
                        </div>
                        <div class="null"></div>
                    </div>
                    <div class="null"></div>
                </div>
            </div>

            <div class="formrow f1">
                <div id="p1f7" class="field n1">
                    <div class="caption capleft alignleft">
                        <label class="fieldlabel" for="p1f7c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve">Cédula Empleado</span></span></span></label>
                        <div class="null"></div>
                    </div>
                    <div class="control capleft">
                        <div>
                            <input type="text" id="p1f7c" name="cedula_emp" class="fieldcontent" value="<?php echo $cedula_em ?>">
                        </div>
                        <div class="null"></div>
                    </div>
                    <div class="null"></div>
                </div>
                <div class="null"></div>
            </div>

            <div id="p1f103" class="field n1">
                <div class="caption capleft alignleft">
                    <label class="fieldlabel" for="entidades"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" >Entidad a cobrar:</span></span></span></label>
                    <div class="null"></div>
                </div>
            </div>

            <div class="control capleft">
                <div class="dropdown">

                    <?
                    unset($combo);
                    //prepara los datos como se deben mostrar en el combo
                    $combo[0][0] = '0';
                    $combo[0][1] = 'Todos';
                    foreach ($datos_en as $cmb => $values) {
                        $combo[$cmb + 1][0] = isset($datos_en[$cmb]['nombre_entidad']) ? $datos_en[$cmb]['nombre_entidad'] : 0;
                        $combo[$cmb + 1][1] = isset($datos_en[$cmb]['nombre_entidad']) ? $datos_en[$cmb]['nombre_entidad'] : '';
                    }

                    // echo$combo;
                    if (isset($_REQUEST['entidad2'])) {
                        $lista_combo = $this->html->cuadro_lista($combo, 'entidad2', $this->configuracion, $_REQUEST['entidad2'], 0, FALSE, 0, 'entidad2');
                    } else {
                        $lista_combo = $this->html->cuadro_lista($combo, 'entidad2', $this->configuracion, 0, 0, FALSE, 0, 'entidad2');
                    }
                    echo $lista_combo;
                    ?> 

                </div>
            </div>

            <div>
                <br><br><br>
                <input id="generarBoton" type="submit" class="navbtn"  value="Generar">
                <input type='hidden' name='pagina' value='cuentaCobro'>
                <input type='hidden' name='opcion' value='generar'>

            </div>
        </form>

        <?
    }

    function form_cuenta_cobro($datos_entidad, $datos_h, $datos_p, $recaudos, $liquidacion, $fecha_inicio_liq, $consecutivo_sql) {
        //DATOS DE FORMULARIO
        $this->formulario = "cuentaCobro";

        $consecutivo_liq = $consecutivo_sql[0][0];
        $fecha_inicial = $fecha_inicio_liq;
        $inicio = 0;
        $fin = 0;

        foreach ($liquidacion as $key => $values) {
            if ($liquidacion[$key][0] == $fecha_inicial) {
                $inicio = $key + 1;
            }
            $fin = $key;
        }

        $fecha_liquidacion = $liquidacion[$inicio][0];

        $total_cuota = 0;
        $total_salud = 0;
        $total_mesada_ad = 0;
        $total_interes = 0;
        $total_ajuste_p = 0;

        for ($inicio; $inicio <= $fin; $inicio++) {
            $total_ajuste_p = $total_ajuste_p + $liquidacion[$inicio][3];
            $total_mesada_ad = $total_mesada_ad + $liquidacion[$inicio][4];
            $total_salud = $total_salud + $liquidacion[$inicio][5];
            $total_cuota = $total_cuota + $liquidacion[$inicio][6];
            $total_interes = $total_interes + $liquidacion[$inicio][7];
            $fecha_final_liq = $liquidacion[$inicio][0];
        }

        $fecha_final = $fecha_final_liq;
        $total_cuota2 = $total_cuota;
        $total_mesada_ad2 = $total_mesada_ad;
        $total_salud2 = $total_salud;
        $total_interes2 = $total_interes;
        $total_ajuste_p2 = $total_ajuste_p;

        settype($total_cuota2, "integer");
        settype($total_mesada_ad2, "integer");
        settype($total_salud2, "integer");
        settype($total_interes2, "integer");
        settype($total_ajuste_p2, "integer");

        $subtotal = $total_mesada_ad + $total_cuota + $total_ajuste_p;
        $total_s_interes = $subtotal + $total_salud;
        $total_c_interes = $total_s_interes + $total_interes2;

        //DATOS DE LA ENTIDAD
        $entidad = (isset($datos_entidad[0]['nombre_entidad']) ? $datos_entidad[0]['nombre_entidad'] : '');
        $nit_entidad = (isset($datos_entidad[0]['nit_entidad']) ? $datos_entidad[0]['nit_entidad'] : '');
        $mesada1 = (isset($datos_entidad[0]['mesada']) ? $datos_entidad[0]['mesada'] : '');
        $porcentaje_c = round((isset($datos_entidad[0]['porcentaje_cuota']) ? $datos_entidad[0]['porcentaje_cuota'] : ''), 2);
        $fecha_ingreso = (isset($datos_entidad[0]['fecha_ingreso']) ? $datos_entidad[0]['fecha_ingreso'] : '');
        $fecha_salida = (isset($datos_entidad[0]['fecha_salida']) ? $datos_entidad[0]['fecha_salida'] : '');
        $diast = (isset($datos_entidad[0]['dias']) ? $datos_entidad[0]['dias'] : '');
        //DATOS DEL PENSIONADO
        $cedula_emp = (isset($datos_h[0]['cedula']) ? $datos_h[0]['cedula'] : '');
        $nombre_p = (isset($datos_p[0]['EMP_NOMBRE']) ? $datos_p[0]['EMP_NOMBRE'] : '');
        $fecha_p = (isset($datos_p[0]['EMP_FECHA_PEN']) ? $datos_p[0]['EMP_FECHA_PEN'] : '');

        //SALDO
        $total_debe_entidad = 0;
        $recaudos_pagados = 0;


        if (is_array($recaudos)) {
            foreach ($recaudos as $key => $values) {
                $recaudos_pagados = $recaudos_pagados + $recaudos[$key][6];
            }

            foreach ($liquidacion as $key => $values) {
                $total_debe_entidad = $total_debe_entidad + $liquidacion[$key][8];
            }
        } else {
            $total_debe_entidad = $total_c_interes;
            $recaudos_pagados = 0;
        }

        /*         * ******************OJOOOOOOO AQUI CAMBIADO********************************************************* */
        echo $total_debe_entidad . ' - ' . $recaudos_pagados;

        $saldo_debe_entidad = $total_debe_entidad - $recaudos_pagados;

        $contador = 1;
        $contador_consecutivo = $consecutivo_liq + $contador;
        $consecutivo = 'CCP' . '-' . $contador_consecutivo . '-' . date('Y');
        ?>
        <link href = "<? echo $this->configuracion["host"] . $this->configuracion["site"] . $this->configuracion["bloques"] ?>/nomina/cuotas_partes/cuentaCobro/cuentaC.css" rel = "stylesheet" type = "text/css" />
        <form id="form" method="post" action='index.php' name='<? echo $this->formulario; ?>'>
            <center> 

                <table class='bordered'  width ="100%">
                    <thead>
                        <tr>
                            <th  class='encabezado_registro' colspan="1" ><img alt="Imagen" src="<? echo $this->configuracion["host"] . $this->configuracion["site"] . $this->configuracion["bloques"] ?>/nomina/cuotas_partes/Images/escudo1.png" /></th>
                            <th  class='encabezado_registro'colspan="2"  >UNIVERSIDAD DISTRITAL FRANCISCO JOSÉ DE CALDAS
                                <br> NIT 899999230-7
                                <br> VICERRECTORIA ADMINISTRATIVA Y FINANCIERA
                                <br>
                                <br> DIVISION DE RECURSOS HUMANOS - FONDO DE PENSIONES</th>
                            <th class='encabezado_registro' colspan="1" >Fecha de Elaboración:
                                <br><?
        $dias = array("Domingo, ", "Lunes, ", "Martes, ", "Miercoles, ", "Jueves, ", "Viernes, ", "Sábado, ");
        $meses = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
        echo $fecha_cc = $dias[date('w')] . " " . date('d') . " de " . $meses[date('n') - 1] . " del " . date('Y');
        ?> </th>
                        </tr>

                    </thead>

                    <tr>
                        <th colspan="4" class='encabezado_registro'>LIQUIDACIÓN CUENTA DE COBRO: <? echo $consecutivo; ?></th>
                        <td class='texto_elegante<? echo '' ?> estilo_td' ></td>
                    </tr>

                    <tr>
                        <td colspan='4' class='estilo_td texto_elegante2' align=center >
                            <?
                            echo ' <br>' . $entidad . ' <br>' .
                            'NIT: ' . $nit_entidad . '<br><br>' .
                            'DEBE: $' . number_format($saldo_debe_entidad) . '<br><br>';
                            ?>
                        </td>
                    </tr>

                    <tr>
                        <th colspan="4" class='encabezado_registro'>DATOS DEL PENSIONADO</th>
                        <td class='texto_elegante<? echo '' ?> estilo_td' ></td>
                        <td class='texto_elegante<? echo '' ?> estilo_td' ></td>
                    </tr>

                    <tr>
                        <td class='texto_elegante estilo_td' >Nombre:</td>
                        <td class='texto_elegante estilo_td'><? echo'&nbsp;&nbsp;' . $nombre_p ?></td>
                        <td class='texto_elegante estilo_td' >Cedula Pensionado:</td>
                        <td class='texto_elegante estilo_td' ><? echo '&nbsp;&nbsp;' . $cedula_emp ?></td>
                    </tr>

                    <tr> <td class='texto_elegante estilo_td' >Fecha Pensión:</td>
                        <td class='texto_elegante estilo_td'><? echo '&nbsp;&nbsp;' . date("d/m/Y", strtotime($fecha_p)) ?></td>
                        <td class='texto_elegante estilo_td' >Entidad donde laboró:</td>
                        <td class='texto_elegante estilo_td'><? echo '&nbsp;&nbsp;' . $entidad ?></td>
                    </tr>

                    <tr> <td class='texto_elegante estilo_td' >Fecha Ingreso:</td>
                        <td class='texto_elegante estilo_td'><? echo '&nbsp;&nbsp;' . date("d/m/Y", strtotime($fecha_ingreso)) ?></td>
                        <td class='texto_elegante estilo_td' >Fecha Salida:</td>
                        <td class='texto_elegante estilo_td'><? echo '&nbsp;&nbsp;' . date("d/m/Y", strtotime($fecha_salida)) ?></td>
                    </tr>

                    <tr> <td class='texto_elegante estilo_td' >Total Dias Trabajados:</td>
                        <td colspan="3" class='texto_elegante estilo_td'><? echo '&nbsp;&nbsp;' . $diast . ' días' ?></td>
                    </tr>
                </table>

                <table class='bordered'  width ="100%"  >
                    <tr>
                        <th colspan="4" class='encabezado_registro'>DATOS DEL RECONOCIMIENTO</th>
                        <td class='texto_elegante<? echo '' ?> estilo_td' ></td>
                    </tr>
                    <tr>
                        <td class='texto_elegante estilo_td' >Resolución Número:</td>
                        <td class='texto_elegante estilo_td' ><? echo '&nbsp;&nbsp&nbsp;&nbsp&nbsp;&nbsp
                        &nbsp;&nbsp&nbsp;&nbsp&nbsp;&nbsp&nbsp;&nbsp&nbsp;&nbsp&nbsp;&nbsp&nbsp;&nbsp
                        &nbsp;&nbsp&nbsp;&nbsp&nbsp;&nbsp&nbsp;&nbsp&nbsp;&nbsp&nbsp;&nbsp&nbsp;&nbsp' ?></td>
                        <td class='texto_elegante estilo_td' >Fecha de Resolución:</td>
                        <td class='texto_elegante estilo_td' ><? echo '&nbsp;&nbsp&nbsp;&nbsp&nbsp;&nbsp
                        &nbsp;&nbsp&nbsp;&nbsp&nbsp;&nbsp&nbsp;&nbsp&nbsp;&nbsp&nbsp;&nbsp&nbsp;&nbsp
                        &nbsp;&nbsp&nbsp;&nbsp&nbsp;&nbsp&nbsp;&nbsp&nbsp;&nbsp&nbsp;&nbsp&nbsp;&nbsp' ?></td> 
                    </tr>

                    <tr>
                        <td class='texto_elegante estilo_td' >Valor inicial de Pensión:</td>
                        <td class='texto_elegante estilo_td' colspan="3"><? echo '&nbsp;&nbsp;$ ' . $mesada1 ?></td>
                    </tr>

                    <tr>
                        <td class='texto_elegante estilo_td' >Valor inicial pensión de Concurrencia:</td>
                        <td class='texto_elegante estilo_td' colspan="3"><? echo '&nbsp;&nbsp;$ ' . $mesada1 ?></td>
                    </tr>

                    <tr>
                        <td class='texto_elegante estilo_td' >Porcentaje Cuota Parte (%):</td>
                        <td class='texto_elegante estilo_td' colspan="3"><? echo '&nbsp;&nbsp;' . $porcentaje_c . ' %' ?></td>
                    </tr>

                    <tr>
                        <td class='texto_elegante estilo_td' >Fecha inicial Cobro:</td>
                        <td class='texto_elegante estilo_td' colspan="3"><? echo '&nbsp;&nbsp;' . $fecha_liquidacion ?></td>
                    </tr>
                    <tr>
                        <td class='texto_elegante estilo_td' >Fecha final Cobro:</td>
                        <td class='texto_elegante estilo_td' colspan="3"><? echo '&nbsp;&nbsp;' . $fecha_final ?></td>
                    </tr>
                </table>

                <table class='bordered'  width ="100%"  >
                    <tr>
                        <th colspan="4" class='encabezado_registro'>DETALLES DE LA LIQUIDACIÓN</th>
                        <td class='texto_elegante<? echo '' ?> estilo_td' ></td>
                    </tr>
                    <tr>
                        <td class='texto_elegante2 estilo_td' align=center valign=center >ITEMS LIQUIDADOS</td>
                        <td class='texto_elegante2 estilo_td'  align=center valign=center colspan="2">TOTAL</td>
                    </tr>

                    <tr>
                        <td class='texto_elegante estilo_td' ><? echo 'Cuota Parte Mesadas' ?></td>
                        <td class='texto_elegante estilo_td' style='text-align:center;' colspan="2" ><? echo'$ ' . number_format($total_cuota2) ?></td>

                    </tr>
                    <tr>
                        <td class='texto_elegante estilo_td' ><? echo 'Mesadas Adicionales' ?></td>
                        <td class='texto_elegante estilo_td' style='text-align:center;' colspan="2"  ><? echo'$ ' . number_format($total_mesada_ad2) ?></td>

                    </tr>
                    <tr>
                        <td class='texto_elegante2 estilo_td' ><? echo 'SUBTOTAL' ?></td>
                        <td class='texto_elegante estilo_td' style='text-align:center;' colspan="2"  ><? echo '$ ' . number_format($subtotal) ?></td>
                    </tr>
                    <tr>
                        <td class='texto_elegante estilo_td' ><? echo 'Incremento Salud' ?></td>               
                        <td class='texto_elegante estilo_td' style='text-align:center;' colspan="2"  ><? echo '$ ' . number_format($total_salud2) ?></td>
                    </tr>
                    <tr>
                        <td class='texto_elegante2 estilo_td'  ><? echo 'TOTAL SIN INTERESES' ?></td>
                        <td class='texto_elegante estilo_td' style='text-align:center;' colspan="2"  ><? echo '$ ' . number_format($total_s_interes) ?></td>
                    </tr>
                    <tr>
                        <td class='texto_elegante estilo_td' ><? echo 'Intereses' ?></td>                   
                        <td class='texto_elegante estilo_td' style='text-align:center;' colspan="2" ><? echo '$ ' . number_format($total_interes2) ?></td>
                    </tr>
                    <tr>
                        <td class='texto_elegante2 estilo_td'><? echo 'TOTAL CON INTERESES' ?></td>                   
                        <td class='texto_elegante estilo_td' style='text-align:center;'colspan="2" ><? echo '$ ' . number_format($total_c_interes) ?></td>
                    </tr>
                    <tr>   
                        <td class='texto_elegante2 estilo_td' align=right valign=right  ><? echo 'TOTAL&nbsp;&nbsp;' ?></td>
                        <td class='texto_elegante estilo_td'  style='text-align:center;' colspan="2" ><? echo '$ ' . number_format($total_c_interes) ?></td>      
                    </tr>
                </table>

                <table class='bordered'  width ="100%"  >
                    <tr>
                        <th colspan="8" class='encabezado_registro'>COBROS ANTERIORES</th>
                        <td class='texto_elegante<? echo '' ?> estilo_td' ></td>
                    </tr>
                    <tr>
                        <td class='texto_elegante2 estilo_td' align=center>RESOLUCION</td>
                        <td class='texto_elegante2 estilo_td' align=center>FECHA RESOLUCION</td>
                        <td class='texto_elegante2 estilo_td' align=center>DESDE</td>
                        <td class='texto_elegante2 estilo_td' align=center>HASTA</td>
                        <td class='texto_elegante2 estilo_td' align=center>FECHA PAGO</td>
                        <td class='texto_elegante2 estilo_td' align=center>VALOR PAGADO</td>
                        <td class='texto_elegante2 estilo_td' align=center>OBSERVACIONES</td>
                    </tr>

                    <tr>
                        <?
                        if (is_array($recaudos)) {
                            foreach ($recaudos as $key => $value) {
                                echo "<tr>";
                                echo "<td class='texto_elegante estilo_td' >" . $recaudos[$key][2] . "</td>";
                                echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $recaudos[$key][3] . "</td>";
                                echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $recaudos[$key][4] . "</td>";
                                echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $recaudos[$key][5] . "</td>";
                                echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $recaudos[$key][7] . "</td>";
                                echo "<td class='texto_elegante estilo_td' style='text-align:center;'>$" . number_format($recaudos[$key][6]) . "</td>";
                                echo "<td class='texto_elegante estilo_td' >" . $recaudos[$key][8] . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr>";
                            echo "<td class='texto_elegante estilo_td' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                            echo "<td class='texto_elegante estilo_td' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                            echo "<td class='texto_elegante estilo_td' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                            echo "<td class='texto_elegante estilo_td' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                            echo "<td class='texto_elegante estilo_td' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                            echo "<td class='texto_elegante estilo_td' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                            echo "<td class='texto_elegante estilo_td' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                            echo "</tr>";
                        }
                        ?>
                </table >

                <table class='bordered'  width ="100%" >
                    <tr>
                        <td class='texto_elegante2 estilo_td' align=center>NOTA 1: El Jefe de la División de Recursos Humanos de la Universidad
                            Distrital, certifica que la persona por quien se realiza este cobro se encuentra incluida en nómina de pensionados
                            y ha presentado oportunamente su certificado de supervivencia, conforme a los dispuesto en el Decreto 2751 de 2002
                            y la Ley 962 de 2005</td>
                    </tr>
                    <tr>
                        <td class='texto_elegante2 estilo_td' align=center>NOTA 2: El tesorero de la Universidad Distrital
                            certifica que la persona por quien se realiza éste cobro se le ha pagado oportunamente la mesada pensional</td>
                    </tr>
                    <tr>
                        <td class='estilo_td texto_elegante'>
                            <?
                            echo '<br><br><br><br><br>
                            <br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            EUSEBIO ANTONIO RANGEL ROA
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            JACQUELINE ORTIZ ARENAS<br>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            Jefe de División de Recursos Humanos
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            Tesorería<br><br>  
                            Elaboró:<br> 
                            Revisó: <br>';
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th colspan="8" class='encabezado_registro'></th>
                        <td class='texto_elegante<? echo '' ?> estilo_td' ></td>
                    </tr>
                </table>

                <table>
                    <tr>    
                    <br>
                    <input id="registrarBoton" type="submit" class="navbtn"  value="Guardar Cuenta Cobro">

                    <input type='hidden' name='opcion' value='guardarCC'>
                    <input type='hidden' name='action' value='<? echo $this->formulario; ?>'>

                    <input type='hidden' name='consecutivo_contador' value='<? echo $contador_consecutivo; ?>'>
                    <input type='hidden' name='consecutivo' value='<? echo $consecutivo; ?>'>
                    <input type='hidden' name='fecha_cc' value='<? echo $fecha_cc; ?>'>
                    <input type='hidden' name='cc_pensionado' value='<? echo $cedula_emp; ?>'>
                    <input type='hidden' name='nit_entidad' value='<? echo $nit_entidad; ?>'>
                    <input type='hidden' name='saldo' value='<? echo $saldo_debe_entidad; ?>'>
                    <input type='hidden' name='liq_fechain' value='<? echo $fecha_liquidacion; ?>'>
                    <input type='hidden' name='liq_fechafin' value='<? echo $fecha_final; ?>'>
                    <input type='hidden' name='liq_mesada' value='<? echo $total_cuota2; ?>'>
                    <input type='hidden' name='liq_mesada_ad' value='<? echo $total_mesada_ad2; ?>'>
                    <input type='hidden' name='liq_subtotal' value='<? echo $subtotal; ?>'>
                    <input type='hidden' name='liq_incremento_salud' value='<? echo $total_salud2; ?>'>
                    <input type='hidden' name='liq_total_sinteres' value='<? echo $total_s_interes; ?>'>
                    <input type='hidden' name='liq_interes' value='<? echo $total_interes2; ?>'>
                    <input type='hidden' name='liq_total_cinteres' value='<? echo $total_c_interes; ?>'>
                    <input type='hidden' name='liq_total' value='<? echo $total_c_interes; ?>'>
                    </tr>
                </table>

            </center>
        </form>         



        <?
    }

}
?>