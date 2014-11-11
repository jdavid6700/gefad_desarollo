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

class html_formRecaudoManual {

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
        $this->formulario = "formRecaudoManual";
    }

    function form_valor() {

        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/dbms.class.php");
        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/sesion.class.php");
        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
        $this->formulario = "formRecaudoManual";
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

            <h2>Ingrese la cédula del pensionado para realizar <br> Registro de Recaudo (Pago) Manual: </h2><br><br>

            <input type="text" name="cedula_emp" required='required' onKeyPress='return acceptNum(event)' title="*Campo Obligatorio">
            <br><br>
            <center> <input id="registrarBoton" type="submit" class="navbtn"  value="Consultar" ></center>

            <input type='hidden' name='pagina' value='formularioRManual'>
            <input type='hidden' name='opcion' value='validarCedula'>
        </form>
        <?
    }

    function datosRecaudos($cedula, $datos_en) {

        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/dbms.class.php");
        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/sesion.class.php");
        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");

        $this->formulario = "formRecaudoManual";
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
            <h2>Ingrese los parámetros para consultar la entidad a asociar el pago:</h2>
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
                        <label class="fieldlabel" for="p1f7c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve">Cédula</span></span></span></label>
                        <div class="null"></div>
                    </div>
                    <div class="control capleft">
                        <div>
                            <input type="text" onpaste="return false" title="*Campo Obligatorio" id="p1f7c" name="cedula_emp" readonly class="fieldcontent" required='required'  onKeyPress='return acceptNum(event)' value="<?php echo $cedula['cedula'] ?>">
                        </div>
                        <div class="null"></div>
                    </div>
                    <div class="null"></div>
                </div>
                <div class="null"></div>
            </div>

            <div id="p1f103" class="field n1">
                <div class="caption capleft alignleft">
                    <label class="fieldlabel" for="entidades"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" >Entidad Previsora:</span></span></span></label>
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
                <input type='hidden' name='pagina' value='formularioRManual'>
                <input type='hidden' name='opcion' value='pasoFormulario'>
            </div>
        </form>
        <?
    }

    function formularioRegistro($datos_manual) {

        $this->formulario = "formRecaudoManual";

        $maxDate = $fecha_minima_datepicker;

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
                    maxDate: "+1m",
                    dateFormat: 'dd/mm/yy'
                });
                $("#fecha_resolucion").datepicker('option', 'minDate', '<?php echo $maxDate ?>');
            });

            $(document).ready(function() {
                $("#fecha_acto_adm").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    yearRange: '1980:c',
                    maxDate: "+2M",
                    dateFormat: 'dd/mm/yy'
                });
                $("#fecha_acto_adm").datepicker('option', 'minDate', '<?php echo $maxDate ?>');
            });

            $(document).ready(function() {
                $("#fecha_pago_inicio").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    yearRange: '1980:c',
                    maxDate: "+2M",
                    dateFormat: 'dd/mm/yy'
                });
                $("#fecha_pago_inicio").datepicker('option', 'minDate', '<?php echo $maxDate ?>');
            });

            $(document).ready(function() {
                $("#fecha_pago_fin").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    yearRange: '1980:c',
                    maxDate: "+2M",
                    dateFormat: 'dd/mm/yy'
                });
                $("#fecha_pago_fin").datepicker('option', 'minDate', '<?php echo $maxDate ?>');
            });

            $(document).ready(function() {
                $("#fecha_pago_cuenta").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    yearRange: '1940:c',
                    maxDate: "+1m",
                    dateFormat: 'dd/mm/yy'
                });
                $("#fecha_pago_cuenta").datepicker('option', 'minDate', '<?php echo $maxDate ?>');
            });</script>


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
                especiales = [8, 9, 32];
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
                var r = confirm("Confirmar envío de formulario.");
                if (r == true) {
                    return true;
                } else {
                    return false;
                }
            }
        </script>

        <script>
            function valor() {
                var capital = parseInt(document.formRecaudoManual.valor_pagado_capital.value);
                var interes = parseInt(document.formRecaudoManual.valor_pagado_interes.value);
                var total = capital + interes;

                document.getElementById('total_recaudo').value = total;


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
                        <label class="fieldlabel" for="p1f2c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve"><a STYLE="color: red" >* </a>Cédula Pensionado</span></span></span></label>
                    </div>
                    <div>
                        <input type="text" title="*Campo Obligatorio" id="p1f2c" onpaste="return false" name="cedula_emp" readonly class="fieldcontent" readonly required='required' onKeyPress='return acceptNum(event)' value='<? echo $datos_manual['cedula'] ?>'>
                    </div>
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
                    <div class="staticcontrol"><span class="wordwrap"><span class="pspan arial" style="text-align: left; font-size:14px;"><span class="ispan" style="color:#000099" xml:space="preserve">DATOS DEL PAGO</span><span class="ispan" style="color:#EE3D23" xml:space="preserve"> </span></span></span></div>
                </div>
            </div>

            <div class="formrow f1">
                <div id="p1f2" class="field n1">
                    <div class="caption capleft alignleft">
                        <label class="fieldlabel" for="p1f2c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve"><a STYLE="color: red" >* </a>Entidad Previsora</span></span></span></label>
                    </div>
                    <div>
                        <input type="text" title="*Campo Obligatorio" id="p1f2c" onpaste="return false" name="previsora" readonly class="fieldcontent" readonly required='required' onKeyPress='return acceptNum(event)' value='<? echo $datos_manual['previsora'] ?>'>
                    </div>
                </div>
            </div>

            <div class="formrow f1">
                <div id="p1f7" class="field n1">
                    <div class="caption capleft alignleft">
                        <label class="fieldlabel" for="p1f7c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve">  Saldo Actual</span></span></span></label>
                    </div>
                    <div class="control capleft">
                        <div>
                            <?
                            /* foreach ($cuentas_pago as $key => $value) {
                              $valor = $key;
                              $saldo = $cuentas_pago[$key]['saldo']; */
                            echo "<input type='text' onpaste='return false' pattern='\d{4,8}\.?\d{0,2}' name='saldo' class='fieldcontent' required='required'  readonly value=0> <br>";
                            /* } */
                            ?>
                        </div> 
                    </div> 
                </div></div>


            <div class="formrow f1 f2">
                <div id="p1f12" class="field n1">
                    <div class="caption capleft alignleft">
                        <label class="fieldlabel" for="p1f7c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve"><a STYLE="color: red" >* </a>Resolución<BR>  Orden Pago</span></span></span></label>
                    </div>
                    <div class="control capleft">
                        <div>
                            <input type="text" id="resolucion" onpaste="return false" name="resolucion_OP" title="Si no aplica, escriba 0" class="fieldcontent" required='required' maxlength='8' onKeyPress='return acceptNumLetter(event)' >
                        </div> 
                    </div> 
                </div>

                <div id="p1f12" class="field n1">
                    <div class="caption capleft alignleft">
                        <label class="fieldlabel" for="p1f7c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve"><a STYLE="color: red" >* </a>Fecha<BR>  Resolución</span></span></span></label>
                    </div>
                    <div class="control capleft">
                        <div>
                            <input type="text" id="fecha_resolucion"  name="fecha_resolucion" title="Si no aplica, escriba 0" class="fieldcontent" required='required' placeholder="dd/mm/aaaa" maxlength="10" pattern="(0[1-9]|[12][0-9]|3[01])[/](0[1-9]|1[012])[/](19|20)\d\d" >
                        </div>
                    </div>
                </div> 
            </div>

            <div class="formrow f1 f2">
                <div id="p1f12" class="field n1">
                    <div class="caption capleft alignleft">
                        <label class="fieldlabel" for="p1f7c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve"><a STYLE="color: red" >* </a>Valor Pagado <BR>  Capital</span></span></span></label>
                    </div>
                    <div class="control capleft">
                        <div>
                            <?
                            /*  foreach ($cuentas_pago as $key => $value) {
                              $valor = $key;
                              $cobro = $cuentas_pago[$key]['saldo'];
                             */ echo "<input type='text'  id='valor_pagado_capital' onpaste='return false' value=0 title='*Campo Obligatorio' placeholder='00000000.00' pattern='\d{4,8}\.?\d{0,2}' name='valor_pagado_capital' class='fieldcontent' maxlength='11' required='required' onKeyPress='return acceptNum2(event)' ><br>";
                            /* } */
                            ?>

                        </div>
                    </div>
                </div>
                <div id="p1f12" class="field n1">
                    <div class="caption capleft alignleft">
                        <label class="fieldlabel" for="p1f12"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" >   Valor Pagado <BR>   Interes</span></span></span></label>
                    </div>
                    <div class="control capleft">
                        <div>
                            <?
                            /*  foreach ($cuentas_pago as $key => $value) {
                              $valor = $key;
                             */ echo "<input type='text' id='valor_pagado_interes' value='0' onpaste='return false' title='*Campo Obligatorio' placeholder='00000000.00' pattern='\d{0,8}\.?\d{0,2}' name='valor_pagado_interes' class='fieldcontent' maxlength='11' required='required' onKeyPress='return acceptNum2(event)'><br>";
                            /* } */
                            ?>
                        </div>
                    </div>
                </div>    
            </div> 

            <div class="formrow f1">
                <div id="p1f7" class="field n1">
                    <div class="caption capleft alignleft">
                        <label class="fieldlabel" for="p1f12c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve"><a STYLE="color: red" >* </a>Total Pagado</span></span></span></label>
                    </div>
                    <div class="control capleft">
                        <div>
                            <input type="text" id="total_recaudo" readonly onpaste='return false' title="*Campo Obligatorio" pattern="\d{4,8}\.?\d{0,2}" placeholder="00000000.00" name="total_recaudo" class="fieldcontent" required='required' maxlength="11" onKeyPress='return acceptNum2(event)'>
                            <input name="suma" type="button" class="navbtn2" value="Sumar" onClick="valor()" />
                        </div>                       
                    </div>      
                </div>
            </div>
            <div class="formrow f1">
                <div id="p1f7" class="field n1">
                    <div class="caption capleft alignleft">
                        <label class="fieldlabel" for="p1f7c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve"><a STYLE="color: red" >* </a>Fecha Pago</span></span></span></label>
                    </div>
                    <div class="control capleft">
                        <div>
                            <input type="text" id="fecha_pago_cuenta"  name="fecha_pago_cuenta" title="Campo obligatorio" class="fieldcontent" required='required' placeholder="dd/mm/aaaa" maxlength="10" pattern="(0[1-9]|[12][0-9]|3[01])[/](0[1-9]|1[012])[/](19|20)\d\d" >
                        </div>
                    </div>
                </div> 
            </div>

            <div class="formrow f1 f2">
                <div id="p1f12" class="field n1">
                    <div class="caption capleft alignleft">
                        <label class="fieldlabel" for="p1f7c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" ><a STYLE="color: red" >* </a>Fecha Desde<br>  Pago</span></span></span></label>
                    </div>
                    <div class="control capleft">
                        <div>
                            <?
                            /* foreach ($cuentas_pago as $key => $value) {
                              $valor = $key;
                             */ echo "<input type='text' id='fecha_pago_inicio' title='*Campo Obligatorio' onpaste='return false' name='fecha_pinicio' class='fieldcontent' placeholder='dd/mm/aaaa' required='required' maxlength='10' pattern='(0[1-9]|[12][0-9]|3[01])[/](0[1-9]|1[012])[/](19|20)\d\d' >";
                            /* } */
                            ?>

                        </div>
                    </div>
                </div>  <div id="p1f12" class="field n1">
                    <div class="caption capleft alignleft">
                        <label class="fieldlabel" for="p1f7c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve"><a STYLE="color: red" >* </a>Fecha Hasta<BR>  Pago</span></span></span></label>
                    </div>
                    <div class="control capleft">
                        <div>
                            <?
                            /* foreach ($cuentas_pago as $key => $value) {
                              $valor = $key;
                             */ echo "<input type='text' id='fecha_pago_fin' title='*Campo Obligatorio' onpaste='return false' name='fecha_pfin' class='fieldcontent' placeholder='dd/mm/aaaa' required='required' maxlength='10' pattern='(0[1-9]|[12][0-9]|3[01])[/](0[1-9]|1[012])[/](19|20)\d\d' >";
                            /* } */
                            ?>
                        </div>
                    </div>
                </div> 
            </div>

            <div class="formrow f1">
                <div id="p1f6" class="field n1">
                    <div class="caption capleft alignleft">
                        <label class="fieldlabel" for="p1f12c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve"><a STYLE="color: red" >* </a>Observación<br>  de Pago</span></span></span></label>
                    </div>
                    <div class="control capleft">
                        <div>
                            <input type="text" id="p1f7c" onpaste='return false' title="*Campo Obligatorio" name="medio_pago" maxlength="50" placeholder="Medio de pago/Prescripción Cuota" class="fieldcontent" required='required' onKeyPress='return acceptNumLetter(event)' >
                        </div>
                        <div align="left"><a STYLE="color: red" ><br><br>* Campo obligatorio</a></div>
                    </div>      
                </div>
            </div>

            <div class="null"></div
            <center> <input id="registrarBoton" type="submit" class="navbtn"  value="Guardar" onClick='return confirmarEnvio();'></center>
            <input type='hidden' name='opcion' value='guardarRecaudo'>
            <input type='hidden' name='action' value='<? echo $this->formulario; ?>'>
            <? /*
              foreach ($fecha_cobro as $key => $values) {
              echo "<input type='hidden' name='fecha_cinicio" . $key . "' value='" . $fecha_cobro[$key]['inicio'] . "'>";
              echo "<input type='hidden' name='fecha_cfin" . $key . "' value='" . $fecha_cobro[$key]['fin'] . "'>";
              } */
            ?>
            </div>
        </form>
        <?
    }

    function reporteSustituto($datos_sustitutos) {

        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/dbms.class.php");
        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/sesion.class.php");
        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");

        $this->formulario = "formRecaudoManual";
        ?>
        <!referencias a estilos y plugins>
        <link href = "<? echo $this->configuracion["host"] . $this->configuracion["site"] . $this->configuracion["bloques"] ?>/nomina/cuotas_partes/cuentaCobro/cuentaC.css" rel = "stylesheet" type = "text/css" />
        <link href = "http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel = "stylesheet" type = "text/css"/>
        <script type = "text/javascript" src = "http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
        <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
        <link rel="stylesheet" href="<? echo $this->configuracion["host"] . $this->configuracion["site"] . $this->configuracion["plugins"]; ?>/jPages-master/css/jPages.css">
        <script src="<? echo $this->configuracion["host"] . $this->configuracion["site"] . $this->configuracion["plugins"]; ?>/jPages-master/js/jPages.js"></script>
        <!-- permite la paginacion-->        

        <script>
                $(function() {
                    $("div.holder").jPages({
                        containerID: "itemContainer",
                        previous: "←",
                        next: "→",
                        perPage: 4,
                        delay: 20
                    });
                });
        </script>
        <center>     

            <h1>Sustitutos Registrados</h1>

            <form id="<? echo $this->formulario; ?>" method="post" action="index.php" name='<? echo $this->formulario; ?>' autocomplete='Off' onSubmit="return validate();">
                <center><table class='bordered'  width ="75%" align="center">
                        <tr>
                            <th colspan="8" class='encabezado_registro'>SUSTITUTOS REGISTRADOS</th>
                            <td class='texto_elegante<? echo '' ?> estilo_td' ></td>
                        </tr>
                        <tr>
                            <td class='texto_elegante2 estilo_td' align=center>CÉDULA PENSIONADO</td>
                            <td class='texto_elegante2 estilo_td' align=center>CÉDULA SUSTITUTO</td>
                            <td class='texto_elegante2 estilo_td' align=center>FECHA DEFUNCION</td>
                            <td class='texto_elegante2 estilo_td' align=center>CERTIFICADO DEFUNCIÓN</td>
                            <td class='texto_elegante2 estilo_td' align=center>CERTIFICADO DEFUNCIÓN</td>
                            <td class='texto_elegante2 estilo_td' align=center>FECHA NAC. SUSTITUTO</td>
                            <td class='texto_elegante2 estilo_td' align=center>RES. SUSTITUCIÓN</td>
                            <td class='texto_elegante2 estilo_td' align=center>FECHA RES. SUSTITUCIÓN</td>
                        </tr>
                        <tbody id="itemContainer">
                            <?
                            if (is_array($datos_sustitutos)) {

                                foreach ($datos_sustitutos as $key => $value) {
                                    echo "<tr>";
                                    echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $datos_sustitutos[$key]['sus_cedulapen'] . "</td>";
                                    echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $datos_sustitutos[$key]['sus_cedulasus'] . "</td>";
                                    echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $datos_sustitutos[$key]['sus_fdefuncion'] . "</td>";
                                    echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $datos_sustitutos[$key]['sus_certificado_defuncion'] . "</td>";
                                    echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $datos_sustitutos[$key]['sus_fcertificado_defuncion'] . "</td>";
                                    echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $datos_sustitutos[$key]['sus_fnac_sustituto'] . "</td>";
                                    echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $datos_sustitutos[$key]['sus_resol_sustitucion'] . "</td>";
                                    echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $datos_sustitutos[$key]['sus_fresol_sustitucion'] . "</td>";
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
                                echo "</tr>";
                            }
                            ?>
                    </table >
                    <center><div class="holder"></div></center>

                    <div>
                        <div class="null"></div>
                        <input id="generarBoton" type="submit" class="navbtn" value="Generar PDF">
                        <input type='hidden' name='no_pagina' value="formularioSustituto">
                        <input type='hidden' name='opcion' value='pdf_reporte'>
                        <input type="hidden" name='datos_sustitutos' value='<?php echo serialize($datos_sustitutos) ?>'>
                    </div>
            </form>
            <?
        }

    }
    