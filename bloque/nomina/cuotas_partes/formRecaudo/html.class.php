<?
/*
  ############################################################################
  #    UNIVERSIDAD DISTRITAL Francisco Jose de Caldas                        #
  #    Copyright: Vea el archivo EULA.txt que viene con la distribucion      #
  ############################################################################
 */
/* --------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
  --------------------------------------------------------------------------------------------------------------------------- */
/* ---------------------------------------------------------------------------------------
  |				Control Versiones				    	|
  ----------------------------------------------------------------------------------------
  | fecha      |        Autor            | version     |              Detalle            |
  ----------------------------------------------------------------------------------------
  | 11/06/2013 | Violet Sosa             | 0.0.0.1     |                                 |
  ----------------------------------------------------------------------------------------
  | 02/08/2013 | Violet Sosa             | 0.0.0.2     |                                 |
  ----------------------------------------------------------------------------------------
 */
if (!isset($GLOBALS["autorizado"])) {
    //include("../index.php");
    exit;
}

class html_formRecaudo {

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

    function form_valor() {


        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/dbms.class.php");
        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/sesion.class.php");
        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
        $this->formulario = "formRecaudo";
        ?>
        <script>
            function acceptNum(e) {
                key = e.keyCode || e.which;
                tecla = String.fromCharCode(key).toLowerCase();
                letras = "01234567890";
                especiales = [8, 39];
                tecla_especial = false
                for (var i in especiales) {
                    if (key == especiales[i]) {
                        tecla_especial = true;
                        break;
                    }
                }

                if (letras.indexOf(tecla) == -1 && !tecla_especial) {
                    return false;
                }
            }
        </script>

        <link href = "<? echo $this->configuracion["host"] . $this->configuracion["site"] . $this->configuracion["bloques"] ?>/nomina/cuotas_partes/cuentaCobro/cuentaC.css" rel = "stylesheet" type = "text/css" />
        <form method='POST' action='index.php' name='<? echo $this->formulario; ?>' autocomplete='off'>

            <h2>Ingrese la cédula a consultar <br>Historial de Recaudos: </h2>
            <br>
            <input type="text" name="cedula_emp" required='required' onKeyPress='return acceptNum(event)'>
            <br><br>
            <center> <input id="registrarBoton" type="submit" class="navbtn"  value="Consultar" ></center>

            <input type='hidden' name='pagina' value='formularioRecaudo'>
            <input type='hidden' name='opcion' value='historiaRecaudo'>
            <br>
        </form>
        <?
    }

    function datosRecaudos($cedula, $datos_en) {

        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/dbms.class.php");
        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/sesion.class.php");
        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");

        $this->formulario = "formRecaudo";
        ?>
        <script>
            function acceptNum(e) {
                key = e.keyCode || e.which;
                tecla = String.fromCharCode(key).toLowerCase();
                letras = "01234567890";
                especiales = [8, 39];
                tecla_especial = false
                for (var i in especiales) {
                    if (key == especiales[i]) {
                        tecla_especial = true;
                        break;
                    }
                }

                if (letras.indexOf(tecla) == -1 && !tecla_especial) {
                    return false;
                }
            }
        </script>
        <!referencias a estilos y plugins>
        <link href = "<? echo $this->configuracion["host"] . $this->configuracion["site"] . $this->configuracion["bloques"] ?>/nomina/cuotas_partes/cuentaCobro/cuentaC.css" rel = "stylesheet" type = "text/css" />
        <link href = "http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel = "stylesheet" type = "text/css"/>
        <script type = "text/javascript" src = "http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
        <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>

        <form id="form" method="post" action='index.php' name='<? echo $this->formulario; ?>' autocomplete='off'>
            <h2>Ingrese los parámetros para consultar Recaudos registrados:</h2>
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
                            <input type="text" onpaste="return false" id="p1f7c" name="cedula_emp" readonly class="fieldcontent" required='required'  onKeyPress='return acceptNum(event)' value="<?php echo $cedula['cedula'] ?>">
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
                    $combo[0][1] = '';
                    foreach ($datos_en as $cmb => $values) {
                        $combo[$cmb][0] = isset($datos_en[$cmb]['hlab_nitprev']) ? $datos_en[$cmb]['hlab_nitprev'] : 0;
                        $combo[$cmb][1] = isset($datos_en[$cmb]['prev_nombre']) ? $datos_en[$cmb]['prev_nombre'] : '';
                    }
                    // echo$combo;
                    if (isset($_REQUEST['hlab_nitprev'])) {
                        $lista_combo = $this->html->cuadro_lista($combo, 'hlab_nitprev', $this->configuracion, $_REQUEST['hlab_nitprev'], 0, FALSE, 0, 'hlab_nitprev');
                    } else {
                        $lista_combo = $this->html->cuadro_lista($combo, 'hlab_nitprev', $this->configuracion, 0, 0, FALSE, 0, 'hlab_nitprev');
                    }
                    echo $lista_combo;
                    ?> 
                </div>
            </div>
            <div>
                <br><br><br>
                <input id="generarBoton" type="submit" class="navbtn"  value="Generar">
                <input type='hidden' name='pagina' value='formularioRecaudo'>
                <input type='hidden' name='opcion' value='consultar'>
            </div>
        </form>

        <?
    }

    function historiaRecaudos($historial, $cobros) {

        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/dbms.class.php");
        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/sesion.class.php");
        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");

        $this->formulario = "formRecaudo";

        $variable = 'pagina=formularioRecaudo';
        $variable.='&opcion=interrupcion';
        $variable = $this->cripto->codificar_url($variable, $this->configuracion);
        ?>

        <script>
            function validate() {
                a = document.formRecaudo.cuenta.length;

                count = 0;
                for (x = 0; x < document.formRecaudo.cuenta.length; x++) {
                    if (document.formRecaudo.cuenta[x].checked == true) {
                        count++
                    }
                }

                if (count == 0) {
                    alert("Debe elegir al menos una cuenta de cobro!");
                    return false
                }
            }
        </script>

        <!referencias a estilos y plugins>
        <link href = "<? echo $this->configuracion["host"] . $this->configuracion["site"] . $this->configuracion["bloques"] ?>/nomina/cuotas_partes/cuentaCobro/cuentaC.css" rel = "stylesheet" type = "text/css" />
        <link href = "http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel = "stylesheet" type = "text/css"/>
        <script type = "text/javascript" src = "http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
        <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>

        <!referencias a estilos y plugins>
        <link href = "<? echo $this->configuracion["host"] . $this->configuracion["site"] . $this->configuracion["bloques"] ?>/nomina/cuotas_partes/cuentaCobro/cuentaC.css" rel = "stylesheet" type = "text/css" />

        <link href = "http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel = "stylesheet" type = "text/css"/>
        <script type = "text/javascript" src = "http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
        <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>


        <h2>Reporte de Cobros y Registro de Recaudos</h2>

        <h1>Cuentas de Cobro Registradas</h1>

        <form id="<? echo $this->formulario; ?>" method="post" action="index.php" name='<? echo $this->formulario; ?>' autocomplete='Off' onSubmit="return validate();">
            <table class='bordered'  width ="75%" align="center">
                <tr>
                    <th colspan="12" class='encabezado_registro'>CUENTAS COBRO REGISTRADAS</th>
                    <td class='texto_elegante<? echo '' ?> estilo_td' ></td>
                </tr>
                <tr>
                    <td rowspan="2" class='texto_elegante2 estilo_td' align=center>&nbsp;FECHA GENERACION&nbsp;</td>
                    <td rowspan="2" class='texto_elegante2 estilo_td' align=center>&nbsp;ENTIDAD EMPLEADOR&nbsp;</td>
                    <td rowspan="2" class='texto_elegante2 estilo_td' align=center>NIT ENTIDAD PREVISORA</td>
                    <td rowspan="2" class='texto_elegante2 estilo_td' align=center>CONSECUTIVO CUENTA COBRO</td>
                    <td colspan="2" class='texto_elegante2 estilo_td' align=center>PERIODO DE COBRO</td>
                    <td rowspan="2" class='texto_elegante2 estilo_td' align=center>&nbsp;VALOR SIN INTERES&nbsp;</td>
                    <td rowspan="2" class='texto_elegante2 estilo_td' align=center>&nbsp;INTERES&nbsp;</td>
                    <td rowspan="2" class='texto_elegante2 estilo_td' align=center>&nbsp;TOTAL CON INTERES&nbsp;</td>
                    <td rowspan="2" class='texto_elegante2 estilo_td' align=center>&nbsp;SALDO&nbsp;</td>
                    <td rowspan="2" class='texto_elegante2 estilo_td' align=center>&nbsp;IE_CORRESPONDENCIA&nbsp;</td>
                    <td rowspan="2" class='texto_elegante2 estilo_td' align=center>&nbsp;&nbsp;&nbsp;REGISTRO&nbsp;&nbsp;&nbsp;</td>
                </tr>
                <tr>
                    <td class='texto_elegante2 estilo_td' style="text-align:center" >INICIO</td>
                    <td class='texto_elegante2 estilo_td' style="text-align:center" >FIN</td>
                </tr>
                <tr>
                    <?
                    if (is_array($cobros)) {

                        foreach ($cobros as $key => $value) {
                            $saldo = 0;

                            /* foreach ($historial as $cont => $value) {

                              $a = 0;
                              $b = 1;
                              if ($historial[$cont][1] == $historial[$b][1]) {
                              //    echo $abono = $historial[$a][6] + $abono . '<br>';
                              }
                              /*
                              if ($cobros[$key][3] == $historial[$cont][1]) {
                              // echo $key . '===>' . $cobros[$key][3] . '=>' . $historial[$cont][1] . '=>' . $cont . '<br>';
                              ///  echo $key . '===>' . $cobros[$key][8] . '=>' . $historial[$cont][6] . '=>' . $cont . '<br><br>';

                              echo $deuda = $cobros[$key][8] . '<br>';
                              echo $abono = $historial[$cont][6] + $abono . '<br>';
                              echo $saldo = $deuda - $abono . '<br>';
                              }
                              }
                             */
                            echo "<tr id='yesOptions'>";
                            echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $cobros[$key]['cob_fgenerado'] . "</td>";
                            echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $cobros[$key]['cob_nitemp'] . "</td>";
                            echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $cobros[$key]['cob_nitprev'] . "</td>";
                            echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $cobros[$key]['cob_consecu_cta'] . "</td>";
                            echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $cobros[$key]['cob_finicial'] . "</td>";
                            echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $cobros[$key]['cob_ffinal'] . "</td>";
                            echo "<td class='texto_elegante estilo_td' style='text-align:center;'>$&nbsp" . number_format($cobros[$key]['cob_ts_interes']) . "</td>";
                            echo "<td class='texto_elegante estilo_td' style='text-align:center;'>$&nbsp" . number_format($cobros[$key]['cob_interes']) . "</td>";
                            echo "<td class='texto_elegante estilo_td' style='text-align:center;'>$&nbsp" . number_format($cobros[$key]['cob_tc_interes']) . "</td>";
                            echo "<td class='texto_elegante estilo_td' style='text-align:center;'>$&nbsp" . number_format($saldo) . "</td>"; //SALDO * * * * * *
                            echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $cobros[$key]['cob_ie_correspondencia'] . "</td>";
                            echo "<td class='texto_elegante estilo_td' style='text-align:center;'>
                                     
                                  <input type='hidden' name='consecutivo_pagar[" . $key . "]' value='" . $cobros[$key]['cob_consecu_cta'] . "'>
                                  <input type='hidden' name='fecha_cuenta[" . $key . "]' value='" . $cobros[$key]['cob_fgenerado'] . "'>
                                  <input type='hidden' name='entidad_empleador[" . $key . "]' value='" . $cobros[$key]['cob_nitemp'] . "'>
                                  <input type='hidden' name='entidad_previsora[" . $key . "]' value='" . $cobros[$key]['cob_nitprev'] . "'>
                                  <input type='hidden' name='fechai_pago[" . $key . "]' value='" . $cobros[$key]['cob_finicial'] . "'>
                                  <input type='hidden' name='fechaf_pago[" . $key . "]' value='" . $cobros[$key]['cob_ffinal'] . "'>
                                  <input type='hidden' name='valor_pago[" . $key . "]' value='" . $cobros[$key]['cob_tc_interes'] . "'>
                                  <input type='hidden' name='saldo[" . $key . "]' value='" . $saldo . "'>
                                  <input type='hidden' name='identificacion[" . $key . "]' value='" . $cobros[$key]['cob_cedula'] . "'>
                                  
                                  <input type='checkbox' id='cuenta' name='cuenta_pagar[" . $key . "]' value='" . $key . "'>  
                                      
                                  </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr>";
                        echo "<td class='texto_elegante estilo_td' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                        echo "<td class='texto_elegante estilo_td' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                        echo "<td class='texto_elegante estilo_td' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                        echo "<td class='texto_elegante estilo_td' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                        echo "<td class='texto_elegante estilo_td' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                        echo "<td class='texto_elegante estilo_td' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                        echo "<td class='texto_elegante estilo_td' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                        echo "<td class='texto_elegante estilo_td' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                        echo "<td class='texto_elegante estilo_td' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                        echo "<td class='texto_elegante estilo_td' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                        echo "<td class='texto_elegante estilo_td' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                        echo "<td class='texto_elegante estilo_td' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                        echo "</tr>";
                    }
                    ?>
            </table >

            <div>
                <input id="generarBoton" type="submit" class="navbtn"  value="Registrar Pago">
                <input type='hidden' name='pagina' value='formularioRecaudo'>
                <input type='hidden' name='opcion' value='registro_pago'>
            </div>
        </form>

        <br><br>

        <h1>Recaudos Registrados</h1>

        <table class='bordered'  width ="75%" align="center">
            <tr>
                <th colspan="11" class='encabezado_registro'>RECAUDOS REGISTRADOS</th>
                <td class='texto_elegante<? echo '' ?> estilo_td' ></td>
            </tr>
            <tr>
                <td  class='texto_elegante2 estilo_td' align=center>&nbsp;ENTIDAD PREVISION&nbsp;</td>
                <td  class='texto_elegante2 estilo_td' align=center>&nbsp;CONSECUTIVO CUENTA COBRO&nbsp;</td>
                <td  class='texto_elegante2 estilo_td' align=center>IE_CORRESPONDENCIA</td>
                <td  class='texto_elegante2 estilo_td' align=center>RES. ORDEN DE PAGO</td>
                <td  class='texto_elegante2 estilo_td' align=center>FECHA RES. ORDEN DE PAGO</td>
                <td  class='texto_elegante2 estilo_td' align=center>FECHA PAGO</td>
                <td  class='texto_elegante2 estilo_td' align=center>&nbsp;VALOR A CAPITAL&nbsp;</td>
                <td  class='texto_elegante2 estilo_td' align=center>&nbsp;VALOR A INTERESES&nbsp;</td>
                <td  class='texto_elegante2 estilo_td' align=center>&nbsp;MEDIO DE PAGO&nbsp;</td>
            </tr>

            <tr>
                <?
                if (is_array($historial)) {
                    foreach ($historial as $key => $value) {
                        echo "<tr>";
                        echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $historial[$key]['prev_nombre'] . "</td>";
                        echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $historial[$key]['recta_consecu_cta'] . "</td>";
                        echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $historial[$key]['cob_ie_correspondencia'] . "</td>";
                        echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $historial[$key]['rec_resolucionop'] . "</td>";
                        echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $historial[$key]['rec_fecha_resolucion'] . "</td>";
                        echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $historial[$key]['recta_fechapago'] . "</td>";
                        echo "<td class='texto_elegante estilo_td' style='text-align:center;'>$" . number_format($historial[$key]['rec_pago_capital']) . "</td>";
                        echo "<td class='texto_elegante estilo_td' style='text-align:center;'>$" . number_format($historial[$key]['rec_pago_interes']) . "</td>";
                        echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $historial[$key]['rec_medio_pago'] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr>";
                    echo "<td class='texto_elegante estilo_td' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                    echo "<td class='texto_elegante estilo_td' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                    echo "<td class='texto_elegante estilo_td' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                    echo "<td class='texto_elegante estilo_td' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                    echo "<td class='texto_elegante estilo_td' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                    echo "<td class='texto_elegante estilo_td' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                    echo "<td class='texto_elegante estilo_td' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                    echo "<td class='texto_elegante estilo_td' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                    echo "<td class='texto_elegante estilo_td' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                    echo "</tr>";
                }
                ?>
        </table >

        <?
    }

    function formularioRecaudos($cuentas_pago, $fecha_minima_datepicker) {

        $this->formulario = "formRecaudo";

        $cont = 0;
        $identificacion = $cuentas_pago[0]['identificacion'];
        $nit_previsora = $cuentas_pago[0]['previsor'];
        $nit_empleador = $cuentas_pago[0]['empleador'];

        $maxDate = $fecha_minima_datepicker;

        $f_fecha_anio = date('Y', (strtotime(str_replace('/', '-', $fecha_minima_datepicker))));
        $f_fecha_dia = date('d', (strtotime(str_replace('/', '-', $fecha_minima_datepicker))));
        $f_fecha_mes = date('m', (strtotime(str_replace('/', '-', $fecha_minima_datepicker))));

        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/dbms.class.php");
        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/sesion.class.php");
        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
        ?>

        <style>                    h3{text-align: left}                </style>

        <!referencias a estilos y plugins>
        <script type="text/javascript" src="<? echo $this->configuracion["host"] . $this->configuracion["site"] . $this->configuracion["plugins"]; ?>/datepicker/js/datepicker.js"></script>
        <link	href="<? echo $this->configuracion["host"] . $this->configuracion["site"] . $this->configuracion["bloques"] ?>/nomina/cuotas_partes/formRecaudo/form_estilo.css"	rel="stylesheet" type="text/css" />
        <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
        <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>

        <script>
            $(document).ready(function() {
                $("#fecha_resolucion").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    yearRange: '1940:c',
                    maxDate: "+0D",
                    dateFormat: 'dd/mm/yy'
                });
                $("#fecha_resolucion").datepicker('option', 'minDate', '<?php echo $maxDate ?>');
            });
            $(document).ready(function() {
                $("#fecha_pago_cuenta").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    yearRange: '1940:c',
                    maxDate: "+0D",
                    dateFormat: 'dd/mm/yy'
                });
                $("#fecha_pago_cuenta").datepicker('option', 'minDate', '<?php echo $maxDate ?>');
            });</script>

        <script language = "Javascript">
            //Éste script valida si las fechas ingresadas en el formulario no son menores a la fecha de retiro de la entidad
            function echeck(str) {

                var min = new Date('<? echo $f_fecha_anio ?>,<? echo $f_fecha_mes ?>,<? echo $f_fecha_dia ?>,');
                var y = str.substring(6);
                var m3 = str.substring(3, 5);
                var m2 = m3 - 1;
                var m = '0' + m2;
                var d = str.substring(0, 2);
                var cadena = new Date(y, m, d);
                if (cadena < min) {
                    alert('Ingrese una fecha válida')
                    return false
                }

                return true
            }

            function minDate() {

                var fechaID = document.formRecaudo.fecha_resolucion
                if ((fechaID.value == null) || (fechaID.value == "")) {
                    alert("Ingrese una fecha válida!")
                    fechaID.focus()
                    return false
                }

                if (echeck(fechaID.value) == false) {
                    fechaID.value = ""
                    fechaID.focus()
                    return false
                }

                var fechaID = document.formRecaudo.fecha_pago_cuenta
                if ((fechaID.value == null) || (fechaID.value == "")) {
                    alert("Ingrese una fecha válida!")
                    fechaID.focus()
                    return false
                }

                if (echeck(fechaID.value) == false) {
                    fechaID.value = ""
                    fechaID.focus()
                    return false
                }

            }

        </script>

        <script>
            function acceptNum2(e) {
                key = e.keyCode || e.which;
                tecla = String.fromCharCode(key).toLowerCase();
                letras = "01234567890";
                especiales = [8, 39];
                tecla_especial = false
                for (var i in especiales) {
                    if (key == especiales[i]) {
                        tecla_especial = true;
                        break;
                    }
                }

                if (letras.indexOf(tecla) == -1 && !tecla_especial) {
                    return false;
                }
            }
        </script>

        <script>
            function acceptNumLetter(e) {
                key = e.keyCode || e.which;
                tecla = String.fromCharCode(key).toLowerCase();
                letras = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789.-";
                especiales = [8, 9,32];
                tecla_especial = false
                for (var i in especiales) {
                    if (key == especiales[i]) {
                        tecla_especial = true;
                        break;
                    }
                }

                if (letras.indexOf(tecla) == -1 && !tecla_especial) {
                    return false;
                }
            }
        </script>

        <script>
            function confirmarEnvio()
            {
                var r = confirm("Revisó si está bien el formulario? Si es así, Aceptar. Si desea corregir, Cancelar");
                if (r == true) {
                    return true;
                } else {
                    return false;
                }
            }
        </script>


        <script  type="text/javascript">

            function valor()
            {

        <? foreach ($cuentas_pago as $key => $values) { ?>
                    var num<?php echo $key ?> = document.formRecaudo.valor_pago_<?php echo $key ?>.value;
                    var num<?php echo $key ?> = document.formRecaudo.valor_pago_<?php echo $key ?>.value;
        <? } ?>
                var total = parseInt(num0) + parseInt(num1);
                document.getElementById('total_recaudo').value = total;


        <? foreach ($cuentas_pago as $key => $values) { ?>
                    var num_<?php echo $key ?> = document.formRecaudo.valor_cobro_<?php echo $key ?>.value;
                    var num_<?php echo $key ?> = document.formRecaudo.valor_cobro_<?php echo $key ?>.value;
        <? } ?>

                var total_cobro = parseInt(num_0) + parseInt(num_1);
                if (total_cobro < total) {
                    alert("Cuidado! Suma de Valor Pago es MAYOR a la Suma de Valor Cobrado")
                    return false
                }


                if (total_cobro > total) {
                    alert("Cuidado! Suma de Valor Pago es MENOR a la Suma de Valor Cobrado")
                    return false
                }
            }

        </script>

        <form id="form" method="post" action="index.php" name='<? echo $this->formulario; ?>' autocomplete='off'  onSubmit="return minDate();">
            <h1>Registro Recaudos Pensionado CP</h1>
            <div class="formrow f1">
                <div id="p1f1" class="field n1">
                    <div class="staticcontrol"><span class="wordwrap"><span class="pspan arial" style="text-align: left; font-size:14px;"><span class="ispan" style="color:#000099" xml:space="preserve">INFORMACIÓN BÁSICA</span><span class="ispan" style="color:#EE3D23" xml:space="preserve"> </span></span></span></div>
                    <div class="null"></div>
                </div>
            </div>

            <div class="formrow f1">
                <div id="p1f2" class="field n1">
                    <div class="caption capleft alignleft">
                        <label class="fieldlabel" for="p1f2c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve">Cédula Pensionado<a STYLE="color: red" >*</a></span></span></span></label>
                    </div>
                    <div>
                        <input type="text" id="p1f2c" onpaste="return false" name="cedula_emp" readonly class="fieldcontent" readonly required='required' onKeyPress='return acceptNum(event)' value='<? echo $identificacion ?>'>
                    </div>
                </div>

                <div class="formrow f1">
                    <div id="p1f4" class="field n1">
                        <div class="staticcontrol">
                            <div class="hrcenter px1"></div>
                        </div>          
                    </div>         
                </div>
                <div class="formrow f1">
                    <div id="p1f5" class="field n1">
                        <div class="staticcontrol"><span class="wordwrap"><span class="pspan arial" style="text-align: left; font-size:14px;"><span class="ispan" style="color:#000099" xml:space="preserve">DATOS DEL RECAUDO</span><span class="ispan" style="color:#EE3D23" xml:space="preserve"> </span></span></span></div>

                    </div>
                </div>
                <br>

                <br>
                <br>

                <div class="formrow f1">
                    <div id="p1f7" class="field n1">
                        <div class="caption capleft alignleft">
                            <label class="fieldlabel" for="p1f7c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve">Nit. Ent. Empleadora<a STYLE="color: red" >*</a></span></span></span></label>
                        </div>
                        <div class="control capleft">
                            <div>
                                <input type="text" id="resolucion" onpaste="return false" name="nit_empleador" class="fieldcontent" readonly required='required' onKeyPress='return acceptNum(event)' value='<? echo $nit_empleador ?>'>
                            </div> 
                        </div> 
                    </div>
                </div>

                <div class="formrow f1">
                    <div id="p1f6" class="field n1">
                        <div class="caption capleft alignleft">
                            <label class="fieldlabel" for="p1f6c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve">Nit Ent. Previsional<a STYLE="color: red" >*</a></span></span></span></label>
                        </div>
                        <div class="control capleft">
                            <div>
                                <input type="text" id="p1f2cc" onpaste="return false" name="nit_previsional" class="fieldcontent" readonly required='required'  onKeyPress='return acceptNum(event)' value='<? echo $nit_previsora ?>'>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="formrow f1">
                    <div id="p1f7" class="field n1">
                        <div class="caption capleft alignleft">
                            <label class="fieldlabel" for="p1f7c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve">Resolución<a STYLE="color: red" >*</a></span></span></span></label>
                        </div>
                        <div class="control capleft">
                            <div>
                                <input type="text" id="resolucion" onpaste="return false" name="resolucion" class="fieldcontent" required='required' maxlength='10' onKeyPress='return acceptNumLetter(event)'>
                            </div> 
                        </div> 
                    </div>
                </div>

                <div class="formrow f1 f2">
                    <div id="p1f12" class="field n1">
                        <div class="caption capleft alignleft">
                            <label class="fieldlabel" for="p1f7c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve">Cons. Cuenta Cobro<a STYLE="color: red" >*</a></span></span></span></label>
                        </div>
                        <div class="control capleft">
                            <div>
                                <?
                                foreach ($cuentas_pago as $key => $value) {
                                    $valor = $key;
                                    $cuenta = $cuentas_pago[$key]['consecutivo_cuenta'];
                                    echo "<input type='text' onpaste='return false' name='consec_cc" . $valor . "' class='fieldcontent' required='required'  readonly value='" . $cuenta . "'> <br>";
                                }
                                ?>
                            </div> 
                        </div> 
                    </div>

                    <div id="p1f12" class="field n1">
                        <div class="caption capleft alignleft">
                            <label class="fieldlabel" for="p1f7c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve">Valor Cuenta Cobro</span></span></span></label>
                        </div>
                        <div class="control capleft">
                            <?
                            foreach ($cuentas_pago as $key => $value) {
                                $valor = $key;
                                $cobro = $cuentas_pago[$key]['valor_pago'];
                                echo "<input type='text' onpaste='return false' name='valor_cobro_" . $valor . "' class='fieldcontent' required='required'  readonly value='" . $cobro . "'> <br>";
                            }
                            ?>

                        </div> 
                    </div>
                </div>

                <div class="formrow f1 f2">
                    <div id="p1f12" class="field n1">
                        <div class="caption capleft alignleft">
                            <label class="fieldlabel" for="p1f7c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve">Saldo Actual</span></span></span></label>
                        </div>
                        <div class="control capleft">
                            <div>
                                <?
                                foreach ($cuentas_pago as $key => $value) {
                                    $valor = $key;
                                    $saldo = $cuentas_pago[$key]['saldo'];
                                    echo "<input type='text' onpaste='return false' name='valor_saldo" . $valor . "' class='fieldcontent' required='required'  readonly value='" . $saldo . "'> <br>";
                                }
                                ?>
                            </div> 
                        </div> 
                    </div>

                    <div id="p1f12" class="field n1">
                        <div class="caption capleft alignleft">
                            <label class="fieldlabel" for="p1f7c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve">Valor a Pagar<a STYLE="color: red" >*</a></span></span></span></label>
                        </div>
                        <div class="control capleft">
                            <?
                            foreach ($cuentas_pago as $key => $value) {
                                $valor = $key;
                                $cobro = $cuentas_pago[$key]['valor_pago'];
                                echo "<input type='text' onpaste='return false' name='valor_pago_" . $valor . "' class='fieldcontent' required='required' maxlength='12' value='" . $cobro . "'> <br>";
                            }
                            ?>

                        </div> 
                    </div>
                </div>


                <div class="formrow f1 f2">
                    <div id="p1f12" class="field n1">
                        <div class="caption capleft alignleft">
                            <label class="fieldlabel" for="p1f7c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve">Resol. Orden Pago<a STYLE="color: red" >*</a></span></span></span></label>
                        </div>
                        <div class="control capleft">
                            <div>
                                <input type="text" id="resolucion" onpaste="return false" name="resolucion_OP" title="Si no aplica, escriba 0" class="fieldcontent" required='required' maxlength='8' onKeyPress='return acceptNumLetter(event)' >
                            </div> 
                        </div> 
                    </div>

                    <div id="p1f12" class="field n1">
                        <div class="caption capleft alignleft">
                            <label class="fieldlabel" for="p1f7c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve">Fecha Resolución<a STYLE="color: red" >*</a></span></span></span></label>
                        </div>
                        <div class="control capleft">
                            <div>
                                <input type="text" id="fecha_resolucion" name="fecha_resolucion" title="Si no aplica, escriba 0" class="fieldcontent" required='required' placeholder="dd/mm/aaaa" maxlength="10" pattern="(0[1-9]|[12][0-9]|3[01])[/](0[1-9]|1[012])[/](19|20)\d\d" >
                            </div>
                        </div>
                    </div> 
                </div>

                <div class="formrow f1">

                    <div id="p1f7" class="field n1">
                        <div class="caption capleft alignleft">
                            <label class="fieldlabel" for="fecha_pago"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" >Fecha de Pago:<a STYLE="color: red" >*</a></span></span></span></label>
                        </div>
                        <div class="control capleft">
                            <div>
                                <input type="text" id="fecha_pago_cuenta" onpaste='return false' name="fecha_pago_cuenta" class="fieldcontent" placeholder="dd/mm/aaaa" required='required' maxlength="10" pattern="(0[1-9]|[12][0-9]|3[01])[/](0[1-9]|1[012])[/](19|20)\d\d" >
                            </div>
                        </div>
                    </div>

                    <div class="formrow f1 f2">
                        <div id="p1f12" class="field n1">
                            <div class="caption capleft alignleft">
                                <label class="fieldlabel" for="p1f7c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve">Valor Pagado Capital<a STYLE="color: red" >*</a></span></span></span></label>
                            </div>
                            <div class="control capleft">
                                <div>
                                    <input type="text"  onpaste='return false' name="valor_pagado_capital" class="fieldcontent" maxlength="12" required='required' onKeyPress='return acceptNum2(event)' maxlength='10'>
                                </div>
                            </div>
                        </div>       

                        <div id="p1f12" class="field n1">
                            <div class="caption capleft alignleft">
                                <label class="fieldlabel" for="p1f12"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" >Valor Pagado Interes</span></span></span></label>
                            </div>
                            <div class="control capleft">
                                <div>
                                    <input type="text" id="p1f12cc" onpaste='return false' name="valor_pagado_interes" class="fieldcontent" maxlength="12" required='required' onKeyPress='return acceptNum2(event)'>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="formrow f1">
                        <div id="p1f7" class="field n1">
                            <div class="caption capleft alignleft">
                                <label class="fieldlabel" for="p1f12c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve">Total Pagado<a STYLE="color: red" >*</a></span></span></span></label>
                            </div>
                            <div class="control capleft">
                                <div>
                                    <input type="text" id="total_recaudo" onpaste='return false' name="total_recaudo" class="fieldcontent" required='required' maxlength="12" onKeyPress='return acceptNum2(event)'>
                                    <input name="suma" type="button" class="navbtn2" value="Sumar" onClick="valor()" />
                                </div>                       
                            </div>      
                        </div>
                    </div>

                    <div class="formrow f1">
                        <div id="p1f6" class="field n1">
                            <div class="caption capleft alignleft">
                                <label class="fieldlabel" for="p1f12c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve">Medio de Pago<a STYLE="color: red" >*</a></span></span></span></label>
                            </div>
                            <div class="control capleft">
                                <div>
                                    <input type="text" id="p1f7c" onpaste='return false' name="medio_pago" maxlength="50" class="fieldcontent" required='required' onKeyPress='return acceptNumLetter(event)' >
                                </div>                       
                            </div>      
                        </div>
                    </div>

                    <div class="null"></div
                    <center> <input id="registrarBoton" type="submit" class="navbtn"  value="Guardar" onClick='return confirmarEnvio();'></center>
                    <input type='hidden' name='opcion' value='guardarRecaudo'>
                    <input type='hidden' name='action' value='<? echo $this->formulario; ?>'>
                </div>
        </form>

        <?
    }

}
