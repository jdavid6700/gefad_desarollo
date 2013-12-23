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

    function formDatoManual() {

        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/dbms.class.php");
        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/sesion.class.php");
        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
        $this->formulario = "cuentaCobro";
        ?>
        <link href = "<? echo $this->configuracion["host"] . $this->configuracion["site"] . $this->configuracion["bloques"] ?>/nomina/cuotas_partes/cuentaCobro/cuentaC.css" rel = "stylesheet" type = "text/css" />
        <form method='POST' action='index.php' name='<? echo $this->formulario; ?>'>

            <h2>Ingrese la cédula a registrar<br> la Cuenta de Cobro Manualmente: </h2>
            <br>
            <input type="text" name="cedula_emp" required='required'>
            <br><br>
            <center> <input id="registrarBoton" type="submit" class="navbtn"  value="Consultar" ></center>
            <input type='hidden' name='pagina' value='cuentaCobro'>
            <input type='hidden' name='opcion' value='manual_consulta'>

            <br>
        </form>
        <?
    }

    function previsoraManual($cedula, $datos_en) {

        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/dbms.class.php");
        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/sesion.class.php");
        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");

        $this->formulario = "cuentaCobro";
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
            <h2>Seleccione la entidad a <br> Registrar Cuenta de Cobro Manual:</h2>
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
                            <input type="text" onpaste="return false" id="p1f7c" name="cedula_emp" readonly class="fieldcontent" required='required'  onKeyPress='return acceptNum(event)' value="<?php echo $cedula ?>">
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
                    $combo[0][1] = ' ';
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
                <input id="generarBoton" type="submit" class="navbtn"  value="Seleccionar">
                <input type='hidden' name='pagina' value='formularioCManual'>
                <input type='hidden' name='opcion' value='formulario_manual'>
            </div>
        </form>

        <?
    }

    function formRegistroManual($datos_empleador, $datos_previsora, $basicos) {

        $this->formulario = "cuentaCobro";

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
            function acceptNum(e) {
                key = e.keyCode || e.which;
                tecla = String.fromCharCode(key).toLowerCase();
                letras = "01234567890-";
                especiales = [8, 9];
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
            function acceptNum2(e) {
                key = e.keyCode || e.which;
                tecla = String.fromCharCode(key).toLowerCase();
                letras = "01234567890.";
                especiales = [8, 9];
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
                especiales = [8, 9];

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

        <script>
            $(document).ready(function() {
                $("#fecha_generacion").datepicker(
                        {
                            changeMonth: true,
                            changeYear: true,
                            yearRange: '1940:c',
                            maxDate: "+0D",
                            dateFormat: 'dd/mm/yy',
                            onSelect: function(dateValue, inst) {
                                $("#fecha_recibido").datepicker("option", "minDate", dateValue)
                            }
                        }
                );
            });

            $(document).ready(function() {
                $("#fecha_final").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    yearRange: '1940:c',
                    maxDate: "+0D",
                    dateFormat: 'dd/mm/yy',
                    onSelect: function(dateValue, inst) {
                        $("#fecha_generacion").datepicker("option", "minDate", dateValue)
                    }
                });
            });

            $(document).ready(function() {
                $("#fecha_inicial").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    yearRange: '1940:c',
                    maxDate: "+0D",
                    dateFormat: 'dd/mm/yy',
                    onSelect: function(dateValue, inst) {
                        $("#fecha_final").datepicker("option", "minDate", dateValue)
                    }
                });
            });

            $(document).ready(function() {
                $("#fecha_recibido").datepicker(
                        {
                            changeMonth: true,
                            changeYear: true,
                            yearRange: '1940:c',
                            maxDate: "+0D",
                            dateFormat: 'dd/mm/yy'
                        }
                );
            });
        </script>

        <script  type="text/javascript">

            function valor()
            {
                var num0 = document.cuentaCobro.mesada.value;
                var num1 = document.cuentaCobro.mesada_adc.value;
                var total = parseFloat(num0) + parseFloat(num1);
                document.getElementById('subtotal').value = total;

            }

            function valor2()
            {
                var num0 = document.cuentaCobro.subtotal.value;
                var num1 = document.cuentaCobro.incremento.value;
                var total = parseFloat(num0) + parseFloat(num1);
                document.getElementById('t_sin_interes').value = total;

            }

            function valor3()
            {
                var num0 = document.cuentaCobro.interes.value;
                var num1 = document.cuentaCobro.t_sin_interes.value;
                var total = parseFloat(num0) + parseFloat(num1);
                document.getElementById('t_con_interes').value = total;

            }


        </script>

        <form id="form" method="post" action="index.php" name='<? echo $this->formulario; ?>' autocomplete='Off'>
            <h1>Formulario de Registro Cuentas de Cobro Manuales</h1>

            <div class="formrow f1">
                <div id="p1f4" class="field n1">
                    <div class="staticcontrol">
                        <div class="hrcenter px1"></div>
                    </div>
                    <div class="null"></div>
                </div>
                <div class="null"></div>
            </div>

            <div class="formrow f1">
                <div id="p1f5" class="field n1">
                    <div class="staticcontrol"><span class="wordwrap"><span class="pspan arial" style="text-align: left; font-size:14px;"><span class="ispan" style="color:#000099" xml:space="preserve">DATOS BÁSICOS</span><span class="ispan" style="color:#EE3D23" xml:space="preserve"> </span></span></span></div>
                    <div class="null"></div>
                </div>
                <div class="null"></div>
            </div>

            <div class="formrow f1">
                <div id="p1f12" class="field n1">
                    <div class="caption capleft alignleft">
                        <label class="fieldlabel" for="p1f12c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve">Cédula Pensionado<a STYLE="color: red" >*</a></span></span></span></label>
                        <div class="null"></div>
                    </div>
                    <div class="control capleft">
                        <div>
                            <input type="text" id="p1f12c" name="cedula" class="fieldcontent" readonly required='required' value="<? echo $basicos['cedula_emp'] ?>" onpaste="return false">
                        </div>
                        <div class="null"></div>
                    </div>
                    <div class="null"></div>
                </div>
                <div class="null"></div>
            </div>

            <div class="formrow f1">
                <div id="p1f6" class="field n1">
                    <div class="caption capleft alignleft">
                        <label class="fieldlabel" for="p1f6c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve">Nombre Empleador<a STYLE="color: red" >*</a></span></span></span></label>
                        <div class="null"></div>
                    </div>
                    <div class="control capleft">
                        <div class="control capleft">
                            <div class="dropdown" required='required' >

                                <?
                                unset($combo);
                                //prepara los datos como se deben mostrar en el combo
                                $combo[0][0] = '1';
                                $combo[0][1] = '';
                                foreach ($datos_empleador as $cmb => $values) {
                                    $combo[$cmb][0] = isset($datos_empleador[$cmb]['hlab_nitenti']) ? $datos_empleador[$cmb]['hlab_nitenti'] : 0;
                                    $combo[$cmb][1] = isset($datos_empleador[$cmb]['prev_nombre']) ? $datos_empleador[$cmb]['prev_nombre'] : '';
                                }

                                // echo$combo;
                                if (isset($_REQUEST['entidad2'])) {
                                    $lista_combo = $this->html->cuadro_lista($combo, 'entidad_empleadora', $this->configuracion, $_REQUEST['hlab_nitenti'], 0, FALSE, 0, 'entidad_empleadora');
                                } else {
                                    $lista_combo = $this->html->cuadro_lista($combo, 'entidad_empleadora', $this->configuracion, 0, 0, FALSE, 0, 'entidad_empleadora');
                                }
                                echo $lista_combo;
                                ?> 

                            </div>
                        </div>

                        <div class="null"></div>
                    </div>
                    <div class="null"></div>
                </div>
                <div class="null"></div>
            </div>

            <div class="formrow f1">
                <div id="p1f6" class="field n1">
                    <div class="caption capleft alignleft">
                        <label class="fieldlabel" for="p1f6c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve">Nombre Previsora<a STYLE="color: red" >*</a></span></span></span></label>
                        <div class="null"></div>
                    </div>
                    <div class="control capleft">
                        <div class="control capleft">
                            <div class="dropdown" required='required' >

                                <?
                                unset($combo);
                                //prepara los datos como se deben mostrar en el combo
                                $combo[0][0] = '0';
                                $combo[0][1] = '';
                                foreach ($datos_previsora as $cmb => $values) {
                                    $combo[$cmb][0] = isset($datos_previsora[$cmb]['prev_nit']) ? $datos_previsora[$cmb]['prev_nit'] : 0;
                                    $combo[$cmb][1] = isset($datos_previsora[$cmb]['prev_nombre']) ? $datos_previsora[$cmb]['prev_nombre'] : '';
                                }

                                // echo$combo;
                                if (isset($_REQUEST['entidad2'])) {
                                    $lista_combo = $this->html->cuadro_lista($combo, 'entidad_previsora', $this->configuracion, $_REQUEST['prev_nit'], 0, FALSE, 0, 'entidad_previsora');
                                } else {
                                    $lista_combo = $this->html->cuadro_lista($combo, 'entidad_previsora', $this->configuracion, 0, 0, FALSE, 0, 'entidad_previsora');
                                }
                                echo $lista_combo;
                                ?> 

                            </div>
                        </div>

                        <div class="null"></div>
                    </div>
                    <div class="null"></div>
                </div>
                <div class="null"></div>
            </div>

            <div class="formrow f1">
                <div id="p1f5" class="field n1">
                    <div class="staticcontrol"><span class="wordwrap"><span class="pspan arial" style="text-align: left; font-size:14px;"><span class="ispan" style="color:#000099" xml:space="preserve">CUENTA DE COBRO</span><span class="ispan" style="color:#EE3D23" xml:space="preserve"> </span></span></span></div>
                    <div class="null"></div>
                </div>
                <div class="null"></div>
            </div>

            <div class="formrow f1">
                <div id="p1f6" class="field n1">
                    <div class="caption capleft alignleft">
                        <label class="fieldlabel" for="consec"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" >Cons. Cuenta Cobro<a STYLE="color: red" >*</a></span></span></span></label>
                        <div class="null"></div>
                    </div>
                    <div class="control capleft">
                        <div>
                            <input type="text" id="consec" name="consecutivo_cc" maxlenght="10" required='required' onpaste="return false" onKeyPress='return acceptNumLetter(event)'>
                        </div>
                        <div class="null"></div>
                    </div>
                    <div class="null"></div>
                </div>
                <div class="null"></div>
            </div>

            <div class="formrow f1">
                <div id="p1f6" class="field n1">
                    <div class="caption capleft alignleft">
                        <label class="fieldlabel" for="fecha_generacion"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" >Fecha Generación<a STYLE="color: red" >*</a></span></span></span></label>
                        <div class="null"></div>
                    </div>
                    <div class="control capleft">
                        <div>
                            <input type="text" id="fecha_generacion" name="fecha_generacion"  maxlenght="10" placeholder="dd/mm/aaaa" required='required' pattern="(0[1-9]|[12][0-9]|3[01])[/](0[1-9]|1[012])[/](19|20)\d\d" onpaste="return false">
                        </div>
                        <div class="null"></div>
                    </div>
                    <div class="null"></div>
                </div>
                <div class="null"></div>
            </div>

            <div class="formrow f1 f2">
                <div id="p1f10" class="field n1">
                    <div class="caption capleft alignleft">
                        <label class="fieldlabel" for="fecha_inicial"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" >Fecha Inicial Cobro<a STYLE="color: red" >*</a></span></span></span></label>
                        <div class="null"></div>
                    </div>
                    <div class="control capleft">
                        <div>
                            <input type="text" id="fecha_inicial" onpaste="return false" placeholder="dd/mm/aaaa" name="fecha_inicial" required='required' pattern="(0[1-9]|[12][0-9]|3[01])[/](0[1-9]|1[012])[/](19|20)\d\d">
                        </div>
                        <div class="null"></div>
                    </div>
                    <div class="null"></div>
                </div>

                <div id="p1f12" class="field n2">
                    <div class="caption capleft alignleft">
                        <label class="fieldlabel" for="fecha_final"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" >Fecha Final Cobro<a STYLE="color: red" >*</a></span></span></span></label>
                        <div class="null"></div>
                    </div>
                    <div class="control capleft">
                        <div>
                            <input type="text" id="fecha_final" onchange="validarFecha()" onpaste="return false" placeholder="dd/mm/aaaa" name="fecha_final" required='required' pattern="(0[1-9]|[12][0-9]|3[01])[/](0[1-9]|1[012])[/](19|20)\d\d">

                        </div>
                        <div class="null"></div>
                    </div>
                    <div class="null"></div>
                </div>
                <div class="null"></div>
            </div>


            <div class="formrow f1">
                <div id="p1f5" class="field n1">
                    <div class="staticcontrol"><span class="wordwrap"><span class="pspan arial" style="text-align: left; font-size:14px;"><span class="ispan" style="color:#000099" xml:space="preserve">Detalle Valores de la Cuenta de Cobro</span><span class="ispan" style="color:#EE3D23" xml:space="preserve"> </span></span></span></div>
                    <div class="null"></div>
                </div>
                <div class="null"></div>
            </div>

            <div class="formrow f1">
                <div id="p1f12" class="field n1">
                    <div class="caption capleft alignleft">
                        <label class="fieldlabel" for="p1f12c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve">Mesada Cuota Parte<a STYLE="color: red" >*</a></span></span></span></label>
                        <div class="null"></div>
                    </div>
                    <div class="control capleft">
                        <div>
                            <input type="text" id="mesada" name="mesada" class="fieldcontent" required='required' onKeyPress='return acceptNum2(event)' maxlength="10" pattern="^[0-9]\d{0,9}(\.\d{1,2})?%?$" onpaste="return false">

                        </div>
                        <div class="null"></div>
                    </div>
                    <div class="null"></div>
                </div>
                <div class="null"></div>
            </div>

            <div class="formrow f1">
                <div id="p1f12" class="field n1">
                    <div class="caption capleft alignleft">
                        <label class="fieldlabel" for="p1f12c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve">Mesada Adicional<a STYLE="color: red" >*</a></span></span></span></label>
                        <div class="null"></div>
                    </div>
                    <div class="control capleft">
                        <div>
                            <input type="text" id="mesada_adc" name="mesada_adc" class="fieldcontent" required='required' onKeyPress='return acceptNum2(event)' maxlength="10" pattern="^[0-9]\d{0,9}(\.\d{1,2})?%?$" onpaste="return false">

                        </div>
                        <div class="null"></div>
                    </div>
                    <div class="null"></div>
                </div>
                <div class="null"></div>
            </div>

            <div class="formrow f1">
                <div id="p1f7" class="field n1">
                    <div class="caption capleft alignleft">
                        <label class="fieldlabel" for="p1f12c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve">Subtotal<a STYLE="color: red" >*</a></span></span></span></label>
                        <div class="null"></div>
                    </div>
                    <div class="control capleft">
                        <div>
                            <input type="text" id="subtotal" name="subtotal" class="fieldcontent" required='required' onKeyPress='return acceptNum2(event)' maxlength="10" pattern="^[0-9]\d{0,9}(\.\d{1,2})?%?$" onpaste="return false">
                            <input name="suma" type="button" class="navbtn2" value="Sumar" onClick="valor()" />
                        </div>
                        <div class="null"></div>
                    </div>
                    <div class="null"></div>
                </div>
                <div class="null"></div>
            </div>

            <div class="formrow f1">
                <div id="p1f12" class="field n1">
                    <div class="caption capleft alignleft">
                        <label class="fieldlabel" for="p1f12c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve">Incremento Salud<a STYLE="color: red" >*</a></span></span></span></label>
                        <div class="null"></div>
                    </div>
                    <div class="control capleft">
                        <div>
                            <input type="text" id="incremento" name="incremento" class="fieldcontent" required='required' onKeyPress='return acceptNum2(event)' maxlength="10" pattern="^[0-9]\d{0,9}(\.\d{1,2})?%?$" onpaste="return false">
                        </div>
                        <div class="null"></div>
                    </div>
                    <div class="null"></div>
                </div>
                <div class="null"></div>
            </div>

            <div class="formrow f1">
                <div id="p1f7" class="field n1">
                    <div class="caption capleft alignleft">
                        <label class="fieldlabel" for="p1f12c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve">Total sin Intereses<a STYLE="color: red" >*</a></span></span></span></label>
                        <div class="null"></div>
                    </div>
                    <div class="control capleft">
                        <div>
                            <input type="text" id="t_sin_interes" name="t_sin_interes" class="fieldcontent" required='required' onKeyPress='return acceptNum2(event)' maxlength="10" pattern="^[0-9]\d{0,9}(\.\d{1,2})?%?$" onpaste="return false">
                            <input name="suma" type="button" class="navbtn2" value="Sumar" onClick="valor2()" />
                        </div>
                        <div class="null"></div>
                    </div>
                    <div class="null"></div>
                </div>
                <div class="null"></div>
            </div>

            <div class="formrow f1">
                <div id="p1f12" class="field n1">
                    <div class="caption capleft alignleft">
                        <label class="fieldlabel" for="p1f12c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve">Valor de Intereses<a STYLE="color: red" >*</a></span></span></span></label>
                        <div class="null"></div>
                    </div>
                    <div class="control capleft">
                        <div>
                            <input type="text" id="interes" name="interes" class="fieldcontent" required='required' onKeyPress='return acceptNum2(event)' maxlength="10" pattern="^[0-9]\d{0,9}(\.\d{1,2})?%?$" onpaste="return false">
                        </div>
                        <div class="null"></div>
                    </div>
                    <div class="null"></div>
                </div>
                <div class="null"></div>
            </div>

            <div class="formrow f1">
                <div id="p1f7" class="field n1">
                    <div class="caption capleft alignleft">
                        <label class="fieldlabel" for="p1f12c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve">Total con Interés<a STYLE="color: red" >*</a></span></span></span></label>
                        <div class="null"></div>
                    </div>
                    <div class="control capleft">
                        <div>
                            <input type="text" id="t_con_interes" name="t_con_interes" class="fieldcontent" required='required' onKeyPress='return acceptNum2(event)' maxlength="10" pattern="^[0-9]\d{0,9}(\.\d{1,2})?%?$" onpaste="return false">
                            <input name="suma" type="button" class="navbtn2" value="Sumar" onClick="valor3()" />
                        </div>
                        <div class="null"></div>
                    </div>
                    <div class="null"></div>
                </div>
                <div class="null"></div>
            </div>

            <div class="formrow f1">
                <div id="p1f12" class="field n1">
                    <div class="caption capleft alignleft">
                        <label class="fieldlabel" for="p1f12c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve">Saldo<a STYLE="color: red" >*</a></span></span></span></label>
                        <div class="null"></div>
                    </div>
                    <div class="control capleft">
                        <div>
                            <input type="text" id="p1f12c" name="saldo_fecha" class="fieldcontent" required='required' onKeyPress='return acceptNum2(event)' maxlength="10" pattern="^[0-9]\d{0,9}(\.\d{1,2})?%?$" onpaste="return false">

                        </div>
                        <div class="null"></div>
                    </div>
                    <div class="null"></div>
                </div>
                <div class="null"></div>
            </div>

            <div class="formrow f1">
                <div id="p1f6" class="field n1">
                    <div class="caption capleft alignleft">
                        <label class="fieldlabel" for="fecha_recibido"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" >Fecha de Recepción<a STYLE="color: red" >*</a></span></span></span></label>
                        <div class="null"></div>
                    </div>
                    <div class="control capleft">
                        <div>
                            <input type="text" id="fecha_recibido" name="fecha_recibido" maxlenght="10" placeholder="dd/mm/aaaa" required='required' pattern="(0[1-9]|[12][0-9]|3[01])[/](0[1-9]|1[012])[/](19|20)\d\d" onpaste="return false">
                        </div>
                        <div class="null"></div>
                    </div>
                    <div class="null"></div>
                </div>
                <div class="null"></div>
            </div>

            <div class="null"></div>
            <center> <input id="registrarBoton" type="submit" class="navbtn"  onClick='return confirmarEnvio();' value="Registrar"></center>
            <input type='hidden' name='opcion' value='cuentaManual'>
            <input type='hidden' name='action' value='<? echo $this->formulario; ?>'>

        </form>
        <?
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