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

            <h2>Ingrese la cédula a consultar historial de recaudos y cuentas de cobro: </h2>
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
            <h2>Ingrese los parametros para consultar Cuentas de Cobro y Recaudos registrados:</h2>
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
                            <input type="text" id="p1f7c" name="cedula_emp" readonly class="fieldcontent" required='required'  onKeyPress='return acceptNum(event)' value="<?php echo $cedula['cedula'] ?>">
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
                        $combo[$cmb + 1][0] = isset($datos_en[$cmb]['hlab_nitprev']) ? $datos_en[$cmb]['hlab_nitprev'] : 0;
                        $combo[$cmb + 1][1] = isset($datos_en[$cmb]['ent_nombre']) ? $datos_en[$cmb]['ent_nombre'] : '';
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

        <form autocomplete='off'>
            <table class='bordered'  width ="75%" align="center">
                <tr>
                    <th colspan="12" class='encabezado_registro'>CUENTAS COBRO REGISTRADAS</th>
                    <td class='texto_elegante<? echo '' ?> estilo_td' ></td>
                </tr>
                <tr>
                    <td rowspan="2" class='texto_elegante2 estilo_td' align=center>&nbsp;FECHA GENERACION&nbsp;</td>
                    <td rowspan="2" class='texto_elegante2 estilo_td' align=center>&nbsp;NIT ENTIDAD EMPLEADOR&nbsp;</td>
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
                            foreach ($historial as $cont => $value) {

                                $a = 0;
                                $b = 1;
                                if ($historial[$cont][1] == $historial[$b][1]) {
                                    //    echo $abono = $historial[$a][6] + $abono . '<br>';
                                }


                                /*  if ($cobros[$key][3] == $historial[$cont][1]) {
                                  // echo $key . '===>' . $cobros[$key][3] . '=>' . $historial[$cont][1] . '=>' . $cont . '<br>';
                                  ///  echo $key . '===>' . $cobros[$key][8] . '=>' . $historial[$cont][6] . '=>' . $cont . '<br><br>';

                                  echo $deuda = $cobros[$key][8] . '<br>';
                                  echo $abono = $historial[$cont][6] + $abono . '<br>';
                                  echo $saldo = $deuda - $abono . '<br>';
                                  } */
                            }


                            echo "<tr>";
                            echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $cobros[$key][0] . "</td>";
                            echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $cobros[$key][1] . "</td>";
                            echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $cobros[$key][2] . "</td>";
                            echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $cobros[$key][3] . "</td>";
                            echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $cobros[$key][4] . "</td>";
                            echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $cobros[$key][5] . "</td>";
                            echo "<td class='texto_elegante estilo_td' style='text-align:center;'>$&nbsp" . number_format($cobros[$key][6]) . "</td>";
                            echo "<td class='texto_elegante estilo_td' style='text-align:center;'>$&nbsp" . number_format($cobros[$key][7]) . "</td>";
                            echo "<td class='texto_elegante estilo_td' style='text-align:center;'>$&nbsp" . number_format($cobros[$key][8]) . "</td>";
                            echo "<td class='texto_elegante estilo_td' style='text-align:center;'>$&nbsp" . number_format($saldo) . "</td>"; //SALDO * * * * * *
                            echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $cobros[$key][9] . "</td>";
                            echo "<td class='texto_elegante estilo_td' style='text-align:center;'>
                                  <input type='checkbox' name='cuenta_pagar_" . $key . "' value='" . $cobros[$key][3] . "'>
                                  <input type='hidden' name='identificacion' value='" . $cobros[$key][10] . "'>
                                  <input type='hidden' name='entidad_previsora' value='" . $cobros[$key][2] . "'>     
                                  <input type='hidden' name='entidad_empleador' value='" . $cobros[$key][1] . "'>  
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
                <td  class='texto_elegante2 estilo_td' align=center>&nbsp;NIT ENTIDAD PREVISION&nbsp;</td>
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
                        echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $historial[$key][0] . "</td>";
                        echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $historial[$key][1] . "</td>";
                        echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $historial[$key][2] . "</td>";
                        echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $historial[$key][3] . "</td>";
                        echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $historial[$key][4] . "</td>";
                        echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $historial[$key][5] . "</td>";
                        echo "<td class='texto_elegante estilo_td' style='text-align:center;'>$" . number_format($historial[$key][6]) . "</td>";
                        echo "<td class='texto_elegante estilo_td' style='text-align:center;'>$" . number_format($historial[$key][7]) . "</td>";
                        echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $historial[$key][8] . "</td>";
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

    function formularioRecaudos($cuentas_pago) {

        $this->formulario = "formRecaudo";

        $cont = 0;
        $identificacion = $cuentas_pago['identificacion'];
        $nit_previsora = $cuentas_pago['entidad_previsora'];
        $nit_empleador = $cuentas_pago['entidad_empleador'];


        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/dbms.class.php");
        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/sesion.class.php");
        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
        ?>

        <style>                    h3{text-align: left}                </style>

        <!referencias a estilos y plugins>
        <script type="text/javascript" src="<? echo $this->configuracion["host"] . $this->configuracion["site"] . $this->configuracion["plugins"]; ?>/datepicker/js/datepicker.js"></script>
        <link	href="<? echo $this->configuracion["host"] . $this->configuracion["site"] . $this->configuracion["bloques"] ?>/nomina/cuotas_partes/formHistoria/form_estilo.css"	rel="stylesheet" type="text/css" />
        <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
        <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>

        <script>
            $(document).ready(function() {
                $("#fecha_hasta").datepicker(
                        {
                            changeMonth: true,
                            changeYear: true,
                            yearRange: '1940:2013',
                            maxDate: "+0D",
                            dateFormat: 'dd/mm/yy',
                            onSelect: function(dateValue, inst) {
                                $("#fecha_pago").datepicker("option", "minDate", dateValue)
                            }
                        }
                );

            });

            $(document).ready(function() {
                $("#fecha_desde").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    yearRange: '1940:2013',
                    maxDate: "+0D",
                    dateFormat: 'dd/mm/yy',
                    onSelect: function(dateValue, inst) {
                        $("#fecha_hasta").datepicker("option", "minDate", dateValue)
                    }
                });
            });

            $(document).ready(function() {
                $("#fecha_pago").datepicker(
                        {
                            changeMonth: true,
                            changeYear: true,
                            yearRange: '1940:2013',
                            maxDate: "+0D",
                            dateFormat: 'dd/mm/yy',
                        }
                );

            });

            $(document).ready(function() {
                $("#fecha_resolucion").datepicker(
                        {
                            changeMonth: true,
                            changeYear: true, yearRange: '2000:2013',
                            maxDate: "+0D",
                            dateFormat: 'dd/mm/yy',
                        }
                );

            });
        </script>

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

        <form id="form" method="post" action="index.php" name='<? echo $this->formulario; ?>' autocomplete='off'>
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
                        <label class="fieldlabel" for="p1f2c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve">Cédula Pensionado</span></span></span></label>
                    </div>
                    <div>
                        <input type="text" id="p1f2c" name="cedula_emp" readonly class="fieldcontent" required='required' onKeyPress='return acceptNum(event)' value='<? echo $identificacion ?>'>
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
                            <label class="fieldlabel" for="p1f7c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve">Nit. Ent. Empleadora</span></span></span></label>
                        </div>
                        <div class="control capleft">
                            <div>
                                <input type="text" id="resolucion" name="nit_empleador" class="fieldcontent" readonly required='required' onKeyPress='return acceptNum(event)' value='<? echo $nit_empleador ?>'>
                            </div> 
                        </div> 
                    </div>
                </div>

                <div class="formrow f1">
                    <div id="p1f6" class="field n1">
                        <div class="caption capleft alignleft">
                            <label class="fieldlabel" for="p1f6c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve">Nit Ent. Previsional</span></span></span></label>
                        </div>
                        <div class="control capleft">
                            <div>
                                <input type="text" id="p1f2cc" name="nit_previsional" class="fieldcontent" readonly required='required'  onKeyPress='return acceptNum(event)' value='<? echo $nit_previsora ?>'>
                            </div>
                        </div>
                    </div>
                </div>



                <div class="formrow f1">
                    <div id="p1f7" class="field n1">
                        <div class="caption capleft alignleft">
                            <label class="fieldlabel" for="p1f7c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve">Resolución</span></span></span></label>
                        </div>
                        <div class="control capleft">
                            <div>
                                <input type="text" id="resolucion" name="resolucion" class="fieldcontent" required='required' maxlength='10'>
                            </div> 
                        </div> 
                    </div>
                </div>

                <div class="formrow f1 f2">
                    <div id="p1f12" class="field n1">
                        <div class="caption capleft alignleft">
                            <label class="fieldlabel" for="p1f7c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve">Cons. Cuenta Cobro</span></span></span></label>
                        </div>
                        <div class="control capleft">
                            <div>
                                <?
                                foreach ($cuentas_pago as $key => $value) {
                                    if (strstr($key, 'cuenta_pagar_')) {
                                        $valor = substr($key, strlen('cuenta_pagar_'));
                                        echo "<input type='text' name='consec_cc" . $valor . "' class='fieldcontent' required='required'  readonly value='" . $value . "'> <br>";
                                    }
                                }
                                ?>
                            </div> 
                        </div> 
                    </div>

                    <div id="p1f12" class="field n1">
                        <div class="caption capleft alignleft">
                            <label class="fieldlabel" for="p1f7c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve">Valor Pago C. Cobro</span></span></span></label>
                        </div>
                        <div class="control capleft">

                            <?
                            foreach ($cuentas_pago as $key => $value) {
                                if (strstr($key, 'cuenta_pagar_')) {
                                    $valor = substr($key, strlen('cuenta_pagar_'));
                                    echo "<input type='text' name='valor_pago" . $valor . "' class='fieldcontent' required='required' onKeyPress='return acceptNum(event)'> maxlength='10' <br>";
                                }
                            }
                            ?>

                        </div> 
                    </div>
                </div>

                <div class="formrow f1 f2">
                    <div id="p1f12" class="field n1">
                        <div class="caption capleft alignleft">
                            <label class="fieldlabel" for="p1f7c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve">Resol. Orden Pago</span></span></span></label>
                        </div>
                        <div class="control capleft">
                            <div>
                                <input type="text" id="resolucion" name="resolucion_OP" title="Si no aplica, escriba 0" class="fieldcontent" required='required' maxlength='10' >
                            </div> 
                        </div> 
                    </div>

                    <div id="p1f12" class="field n1">
                        <div class="caption capleft alignleft">
                            <label class="fieldlabel" for="p1f7c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve">Fecha Resolución</span></span></span></label>
                        </div>
                        <div class="control capleft">
                            <div>
                                <input type="text" id="fecha_resolucion" name="fecha_resolucion" readonly title="Si no aplica, escriba 0" class="fieldcontent" required='required' >
                            </div>
                        </div>
                    </div> 
                </div>

                <div class="formrow f1">

                    <div id="p1f7" class="field n1">
                        <div class="caption capleft alignleft">
                            <label class="fieldlabel" for="fecha_pago"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" >Fecha de Pago:</span></span></span></label>
                        </div>
                        <div class="control capleft">
                            <div>
                                <input type="text" id="fecha_pago" name="fecha_pago" readonly class="fieldcontent" required='required' >
                            </div>
                        </div>
                    </div>

                    <div class="formrow f1 f2">
                        <div id="p1f12" class="field n1">
                            <div class="caption capleft alignleft">
                                <label class="fieldlabel" for="p1f7c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve">Valor Pagado Capital</span></span></span></label>
                            </div>
                            <div class="control capleft">
                                <div>
                                    <input type="text"  name="valor_pagado_capital" class="fieldcontent" required='required' onKeyPress='return acceptNum(event)' maxlength='10'>
                                </div>
                            </div>
                        </div>       

                        <div id="p1f12" class="field n1">
                            <div class="caption capleft alignleft">
                                <label class="fieldlabel" for="p1f12"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" >Valor Pagado Interes</span></span></span></label>
                            </div>
                            <div class="control capleft">
                                <div>
                                    <input type="text" id="p1f12cc" name="valor_pagado_interes" class="fieldcontent" required='required' onKeyPress='return acceptNum(event)'>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="formrow f1">
                        <div id="p1f7" class="field n1">
                            <div class="caption capleft alignleft">
                                <label class="fieldlabel" for="p1f12c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve">Total Pagado</span></span></span></label>
                            </div>
                            <div class="control capleft">
                                <div>
                                    <input type="text" id="p1f12cc" name="total_recaudo" class="fieldcontent" required='required' onKeyPress='return acceptNum(event)'>
                                </div>                       
                            </div>      
                        </div>
                    </div>

                    <div class="formrow f1">
                        <div id="p1f7" class="field n1">
                            <div class="caption capleft alignleft">
                                <label class="fieldlabel" for="p1f12c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve">Medio de Pago</span></span></span></label>
                            </div>
                            <div class="control capleft">
                                <div>
                                    <input type="text" id="p1f12cc" name="medio_pago" class="fieldcontent" required='required' >
                                </div>                       
                            </div>      
                        </div>
                    </div>

                    <div class="null"></div

                    <center> <input id="registrarBoton" type="submit" class="navbtn"  value="Registrar" onClick='return confirmarEnvio();'></center>

                    <input type='hidden' name='opcion' value='guardarRecaudo'>
                    <input type='hidden' name='action' value='<? echo $this->formulario; ?>'>

                </div>
        </form>

        <?
    }

}

