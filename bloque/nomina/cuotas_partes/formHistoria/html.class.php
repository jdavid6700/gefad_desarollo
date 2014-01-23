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

class html_formHistoria {

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

    function formularioInterrupcion($datos_prev, $datos_interrupcion) {

        $fecha_min = date('d/m/Y', (strtotime("" . str_replace('/', '-', $datos_interrupcion['fecha_ingreso']) . "+1 month")));
        $fecha_max = date('d/m/Y', strtotime(str_replace('/', '-', $datos_interrupcion['fecha_salida'])));


        $i_fecha_anio = date('Y', (strtotime(str_replace('/', '-', $datos_interrupcion['fecha_ingreso']))));
        $i_fecha_dia = date('d', (strtotime(str_replace('/', '-', $datos_interrupcion['fecha_ingreso']))));
        $i_fecha_mes = date('m', (strtotime("" . str_replace('/', '-', $datos_interrupcion['fecha_ingreso']) . "+1 month")));

        $f_fecha_anio = date('Y', (strtotime(str_replace('/', '-', $datos_interrupcion['fecha_salida']))));
        $f_fecha_mes = date('m', (strtotime(str_replace('/', '-', $datos_interrupcion['fecha_salida']))));
        $f_fecha_dia = date('d', (strtotime(str_replace('/', '-', $datos_interrupcion['fecha_salida']))));

        $this->formulario = "formHistoria";

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
                $("#dias_nor_desde").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    yearRange: '1940:2100',
                    dateFormat: 'dd/mm/yy'
                });
                $("#dias_nor_desde").datepicker('option', 'minDate', '<?php echo $fecha_min ?>');
                $("#dias_nor_desde").datepicker('option', 'maxDate', '<?php echo $fecha_max ?>');
            });
            $(document).ready(function() {
                $("#dias_nor_hasta").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    yearRange: '1940:c',
                    dateFormat: 'dd/mm/yy',
                    onSelect: function(dateValue, inst) {
                        $("#fecha_certificado").datepicker("option", "minDate", dateValue)
                    }
                });
                $("#dias_nor_hasta").datepicker('option', 'minDate', '<?php echo $fecha_min ?>');
                $("#dias_nor_hasta").datepicker('option', 'maxDate', '<?php echo $fecha_max ?>');
            });
            $(document).ready(function() {
                $("#fecha_certificado").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    yearRange: '1940:c',
                    dateFormat: 'dd/mm/yy'
                });
            });</script>


        <script>
            function acceptNum(e) {
                key = e.keyCode || e.which;
                tecla = String.fromCharCode(key).toLowerCase();
                letras = "01234567890-";
                especiales = [8, 39, 9];
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
                letras = "1234567890";
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
                var r = confirm("Confirmar envío de formulario.");
                if (r == true) {
                    return true;
                } else {
                    return false;
                }
            }
        </script>

        <script language = "Javascript">
            //Éste script valida si las fechas ingresadas en el formulario no son menores a la fecha de retiro de la entidad

            function echeck(str) {

                var min = new Date('<? echo $i_fecha_anio ?>,<? echo $i_fecha_mes ?>,<? echo $i_fecha_dia ?>');
                        var max = new Date('<? echo $f_fecha_anio ?>,<? echo $f_fecha_mes ?>,<? echo $f_fecha_dia ?>');
                                var y = str.substring(6);
                                var m3 = str.substring(3, 5);
                                var m2 = m3 - 1;
                                var m = '0' + m2;
                                var d = str.substring(0, 2);
                                var cadena = new Date(y, m, d);
                                if (cadena < min || cadena > max) {
                                    alert('Fuera del rango')
                                    return false
                                }
                                return true
                            }

                            function minDate() {

                                var fechaID = document.formHistoria.dias_nor_desde
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

                                var fechaID = document.formHistoria.dias_nor_hasta
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
            function validarDias() {

                var _MS_PER_DAY = 1000 * 60 * 60 * 24;

                var hasta = (document.getElementById("dias_nor_hasta").value)
                var desde = (document.getElementById("dias_nor_desde").value)

                var y1 = desde.substring(6);
                var m13 = desde.substring(3, 5);
                var m12 = m13 - 1;
                var m1 = '0' + m12;
                var d1 = desde.substring(0, 2);
                var y2 = hasta.substring(6);
                var m23 = hasta.substring(3, 5);
                var m22 = m23 - 1;
                var m2 = '0' + m22;
                var d2 = hasta.substring(0, 2);

                var utc1 = new Date(y1, m1, d1);
                var utc2 = new Date(y2, m2, d2);

                var ingreso_dias = parseInt((document.getElementById("total_dias").value))

                var diferencia = Math.floor((utc2 - utc1) / _MS_PER_DAY);

                if (ingreso_dias > diferencia) {
                    alert("El número total de días es mayor a lo esperado.")
                }

            }

        </script>

        <form id="form" method="post" action="index.php" name='<? echo $this->formulario; ?>' autocomplete='Off' onSubmit="return minDate();">
            <h1>Registro Interrupción Laboral</h1>

            <div class="formrow f1">
                <div id="p1f1" class="field n1">
                    <div class="staticcontrol"><span class="wordwrap"><span class="pspan arial" style="text-align: left; font-size:14px;"><span class="ispan" style="color:#000099" xml:space="preserve">INFORMACIÓN BÁSICA</span><span class="ispan" style="color:#EE3D23" xml:space="preserve"> </span></span></span></div>
                    <div class="null"></div>
                </div>
                <div class="null"></div>
            </div>

            <div class="formrow f1">
                <div id="p1f2" class="field n1">
                    <div class="caption capleft alignleft">
                        <label class="fieldlabel" for="p1f2c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve"><a STYLE="color: red" >* </a>Cédula Pensionado</span></span></span></label>
                        <div class="null"></div>
                    </div>
                    <div>
                        <input type="text" id="p1f2c" name="cedula_emp" title="*Campo Obligatorio" onpaste="return false" class="fieldcontent" required='required' onKeyPress='return acceptNum2(event)' readonly value='<? echo $datos_interrupcion['cedula'] ?>' maxlength="10">
                    </div>
                </div>

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
                        <div class="staticcontrol"><span class="wordwrap"><span class="pspan arial" style="text-align: left; font-size:14px;"><span class="ispan" style="color:#000099" xml:space="preserve">REGISTRO ENTIDAD</span><span class="ispan" style="color:#EE3D23" xml:space="preserve"> </span></span></span></div>
                        <div class="null"></div>
                    </div>
                    <div class="null"></div>
                </div>

                <div class="formrow f1">
                    <div id="p1f6" class="field n1">
                        <div class="caption capleft alignleft">
                            <label class="fieldlabel" for="p1f6c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve"><a STYLE="color: red" >* </a>Nit Empleador</span></span></span></label>
                            <div class="null"></div>
                        </div>
                        <div class="control capleft">
                            <div>
                                <input type="text" id="p1f6c" title="*Campo Obligatorio" onpaste="return false" name="nit_entidad" class="fieldcontent" maxlength="15" required='required' onKeyPress='return acceptNum(event)' readonly value='<? echo $datos_interrupcion['nit_entidad'] ?>'>
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
                            <label class="fieldlabel" for="p1f6c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve"><a STYLE="color: red" >* </a>Nit Entidad Previsora</span></span></span></label>
                            <div class="null"></div>
                        </div>
                        <div class="control capleft">
                            <div class="control capleft">
                                <div>
                                    <input type="text" id="p1f6c" title="*Campo Obligatorio" onpaste="return false" name="prev_nit" class="fieldcontent" maxlength="15" required='required' onKeyPress='return acceptNum(event)' readonly value='<? echo $datos_interrupcion['nit_previsora'] ?>'> 
                                </div>
                            </div>

                            <div class="null"></div>
                        </div>
                        <div class="null"></div>
                    </div>
                    <div class="null"></div>
                </div>

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
                        <div class="staticcontrol"><span class="wordwrap"><span class="pspan arial" style="text-align: left; font-size:14px;"><span class="ispan" style="color:#000099" xml:space="preserve">REGISTRO INTERRUPCIÓN</span><span class="ispan" style="color:#EE3D23" xml:space="preserve"> </span></span></span></div>
                        <div class="null"></div>
                    </div>
                    <div class="null"></div>
                </div>

                <div class="formrow f1">
                    <div id="p1f6" class="field n1">
                        <div class="caption capleft alignleft">
                            <label class="fieldlabel" for="dias_nor_desde"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" ><a STYLE="color: red" >* </a>Fecha Inicio</span></span></span></label>
                            <div class="null"></div>
                        </div>
                        <div class="control capleft">
                            <div>
                                <input type="text" id="dias_nor_desde" title="*Campo Obligatorio" onpaste="return false" name="dias_nor_desde" placeholder="dd/mm/aaaa" required='required' pattern="(0[1-9]|[12][0-9]|3[01])[/](0[1-9]|1[012])[/](19|20)\d\d">
                            </div>
                            <div class="null"></div>
                        </div>
                        <div class="null"></div>
                    </div>
                    <div class="null"></div>
                </div>

                <div class="formrow f1">
                    <div id="p1f6" class="field n2">
                        <div class="caption capleft alignleft">
                            <label class="fieldlabel" for="dias_nor_hasta"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" ><a STYLE="color: red" >* </a>Fecha Final</span></span></span></label>
                            <div class="null"></div>
                        </div>
                        <div class="control capleft">
                            <div>
                                <input type="text" id="dias_nor_hasta" title="*Campo Obligatorio" onpaste="return false" name="dias_nor_hasta" placeholder="dd/mm/aaaa" required='required' pattern="(0[1-9]|[12][0-9]|3[01])[/](0[1-9]|1[012])[/](19|20)\d\d">
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
                            <label class="fieldlabel" for="p1f6c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve"><a STYLE="color: red" >* </a>Total Días</span></span></span></label>
                            <div class="null"></div>
                        </div>
                        <div class="control capleft">
                            <div>
                                <input type="text" id="total_dias" title="*Campo Obligatorio" onchange="validarDias()" placeholder="000" name="total_dias" onpaste="return false" class="fieldcontent" required='required' onKeyPress='return acceptNum2(event)' maxlength="3">
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
                            <label class="fieldlabel" for="p1f6c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve"><a STYLE="color: red" >* </a>Núm. Certificado</span></span></span></label>
                            <div class="null"></div>
                        </div>
                        <div class="control capleft">
                            <div>
                                <input type="text" id="p1f6c" title="*Campo Obligatorio" name="num_certificado" onpaste="return false" class="fieldcontent" required='required' onKeyPress='return acceptNum2(event)' maxlength="7">
                            </div>
                            <div class="null"></div>
                        </div>
                        <div class="null"></div>
                    </div>
                    <div class="null"></div>
                </div>

                <div class="formrow f1">
                    <div id="p1f6" class="field n2">
                        <div class="caption capleft alignleft">
                            <label class="fieldlabel" title="*Campo Obligatorio" for="fecha_certificado"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" ><a STYLE="color: red" >* </a>Fecha Certificado</span></span></span></label>
                            <div class="null"></div>
                        </div>
                        <div class="control capleft">
                            <div>
                                <input type="text" id="fecha_certificado"  onpaste="return false" name="fecha_certificado" placeholder="dd/mm/aaaa" required='required' pattern="(0[1-9]|[12][0-9]|3[01])[/](0[1-9]|1[012])[/](19|20)\d\d">
                            </div>
                            <div class="null"></div>
                        </div>
                        <div class="null"></div>
                    </div>
                    <div class="null"></div>
                </div>

                <div class="null"></div>


                <div align="left"><a STYLE="color: red" ><br><br>* Campo obligatorio</a></div>


                <center> 
                    <input name='registro' id="registrarBoton" type="submit" class="navbtn"  value="Guardar y Registrar Otra Interrupción" onClick='return confirmarEnvio();'>
                    <input name='registro' id="registrarBoton" type="submit" class="navbtn"  value="Guardar Interrupción" onClick='return confirmarEnvio();'>
                </center>

                <input type='hidden' name='opcion' value='registrarInterrupcion'>
                <input type='hidden' name='action' value='<? echo $this->formulario; ?>'>
                <input type='hidden' name='fecha_ingreso' value='<? echo $datos_interrupcion['fecha_ingreso'] ?>'>
                <input type='hidden' name='fecha_salida' value='<? echo $datos_interrupcion['fecha_salida'] ?>'>
            </div>

        </form>
        <?
    }

    function formularioHistoria($datos_previsora, $datos_historia, $cedula, $rango) {

        $this->formulario = "formHistoria";

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
                            $("#fecha_salida").datepicker(
                                    {
                                        changeMonth: true,
                                        changeYear: true,
                                        yearRange: '1940:c',
                                        maxDate: "+0D",
                                        dateFormat: 'dd/mm/yy',
                                        onSelect: function(dateValue, inst) {
                                            $("#fecha_acto_adm").datepicker("option", "minDate", dateValue)
                                        }
                                    }
                            );
                        });
                        $(document).ready(function() {
                            $("#fecha_ingreso").datepicker({
                                changeMonth: true,
                                changeYear: true,
                                yearRange: '1940:c',
                                maxDate: "+0D",
                                dateFormat: 'dd/mm/yy',
                                onSelect: function(dateValue, inst) {
                                    $("#fecha_salida").datepicker("option", "minDate", dateValue)
                                }
                            });
                        });</script>

        <script>
            function acceptNum8(e) {
                key = e.keyCode || e.which;
                tecla = String.fromCharCode(key).toLowerCase();
                letras = "01234567890";
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
                var r = confirm("Confirmar envío de formulario.");
                if (r == true) {
                    return true;
                } else {
                    return false;
                }
            }
        </script>

        <script>
            //Éste script valida si las fechas ingresadas en el formulario estan entre otras historias laborales
            function echeck(str) {

        <?
        foreach ($rango as $key => $values) {
            /* echo "var min = new Date('" . $rango[$key]['inicio'] . "');\n";
              echo "var max = new Date('" . $rango[$key]['fin'] . "');    \n"; */

            $i_fecha_anio = date('Y', (strtotime(str_replace('/', '-', $rango[$key]['inicio']))));
            $i_fecha_mes = date('m', (strtotime(str_replace('/', '-', $rango[$key]['inicio']))));
            $i_fecha_dia = date('d', (strtotime("" . str_replace('/', '-', $rango[$key]['inicio']) . "+ 1 day")));

            $f_fecha_anio = date('Y', (strtotime(str_replace('/', '-', $rango[$key]['fin']))));
            $f_fecha_mes = date('m', (strtotime(str_replace('/', '-', $rango[$key]['fin']))));
            $f_fecha_dia = date('d', (strtotime("" . str_replace('/', '-', $rango[$key]['fin']) . "+ 1 day")));

            echo "var min = new Date('" . $i_fecha_anio . "," . $i_fecha_mes . "," . $i_fecha_dia . "');\n";
            echo "var max = new Date('" . $f_fecha_anio . "," . $f_fecha_mes . "," . $f_fecha_dia . "');    \n";

            echo "var y1 = str.substring(6);\n\n";
            echo "var m13 = str . substring(3, 5);\n\n";
            echo "var m12 = m13 - 1;\n\n";
            echo "var m1 = '0' + m12;\n\n";
            echo "var d1 = str.substring(0, 2);\n\n";
            echo "var cadena = new Date(y1, m1, d1);\n\n";

            echo "var ming = min.getTime();\n\n";
            echo "var maxg = max.getTime();\n\n";
            echo "var cadenag = cadena.getTime();\n\n";

            echo "if (cadenag > ming && cadenag < maxg) {\n";
            // echo "alert(min  cadena  max)\n\n";
            echo "alert('Ya existen historias laborales registradas para este periodo.')\n";
            echo " return false\n";
            echo "    }\n\n";
        }
        ?>
                return true
            }

            function minDate() {

                var fechaID = document.formHistoria.fecha_ingreso;
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

                var fechaID = document.formHistoria.fecha_salida
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
            function  validarFecha() {
                var desde = (document.getElementById("fecha_ingreso").value);
                var hasta = (document.getElementById("fecha_salida").value);
                var y1 = desde.substring(6);
                var m13 = desde.substring(3, 5);
                var m12 = m13 - 1;
                var m1 = '0' + m12;
                var d1 = desde.substring(0, 2);
                var y2 = hasta.substring(6);
                var m23 = hasta.substring(3, 5);
                var m22 = m23 - 1;
                var m2 = '0' + m22;
                var d2 = hasta.substring(0, 2);
                var cadena1 = new Date(y1, m1, d1);
                var cadena2 = new Date(y2, m2, d2);

                if (cadena1.getTime() > cadena2.getTime()) {
                    document.getElementById("fecha_ingreso").focus();
                    document.getElementById("fecha_salida").focus();
                    alert("La fecha de RETIRO es anterior a la fecha de INGRESO");
                    return false
                }

                return true
            }
        </script>

        <form id="form" method="post" action="index.php" name='<? echo $this->formulario; ?>' autocomplete='Off' onsubmit="return minDate()">
            <h1>Registro Historia Laboral Pensionado CP</h1>
            <div class="formrow f1">
                <div id="p1f1" class="field n1">
                    <div class="staticcontrol"><span class="wordwrap"><span class="pspan arial" style="text-align: left; font-size:14px;"><span class="ispan" style="color:#000099" xml:space="preserve">INFORMACIÓN BÁSICA</span><span class="ispan" style="color:#EE3D23" xml:space="preserve"> </span></span></span></div>
                    <div class="null"></div>
                </div>
                <div class="null"></div>
            </div>

            <div class="formrow f1">
                <div id="p1f2" class="field n1">
                    <div class="caption capleft alignleft">
                        <label class="fieldlabel" for="p1f2c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve"> <a STYLE="color: red" >* </a>Cédula Pensionado</span></span></span></label>
                        <div class="null"></div>
                    </div>
                    <div>
                        <input type="text" id="p1f2c" name="cedula_emp" onpaste="return false" class="fieldcontent" required='required' onKeyPress='return acceptNum(event)' readonly maxlength="10" pattern=".{4,10}" value="<? echo $cedula ?>">

                    </div>
                </div>

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
                        <div class="staticcontrol"><span class="wordwrap"><span class="pspan arial" style="text-align: left; font-size:14px;"><span class="ispan" style="color:#000099" xml:space="preserve">DATOS ENTIDAD DE LABOR</span><span class="ispan" style="color:#EE3D23" xml:space="preserve"> </span></span></span></div>
                        <div class="null"></div>
                    </div>
                    <div class="null"></div>
                </div>

                <div class="formrow f1">
                    <div id="p1f6" class="field n1">
                        <div class="caption capleft alignleft">
                            <label class="fieldlabel" for="p1f6c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve"><a STYLE="color: red" >* </a>Nombre Empleador</span></span></span></label>
                            <div class="null"></div>
                        </div>
                        <div class="control capleft">
                            <div class="control capleft">
                                <div class="dropdown" required='required' title="*Campo Obligatorio" required='required'>

                                    <?
                                    unset($combo);
                                    //prepara los datos como se deben mostrar en el combo
                                    $combo[0][0] = '1';
                                    $combo[0][1] = 'No registra en la base de datos';
                                    foreach ($datos_previsora as $cmb => $values) {
                                        $combo[$cmb][0] = isset($datos_previsora[$cmb]['prev_nit']) ? $datos_previsora[$cmb]['prev_nit'] : 0;
                                        $combo[$cmb][1] = isset($datos_previsora[$cmb]['prev_nombre']) ? $datos_previsora[$cmb]['prev_nombre'] : '';
                                    }

                                    // echo$combo;
                                    if (isset($_REQUEST['entidad2'])) {
                                        $lista_combo = $this->html->cuadro_lista($combo, 'empleador_nit', $this->configuracion, $_REQUEST['prev_nit'], 0, FALSE, 0, 'empleador_nit');
                                    } else {
                                        $lista_combo = $this->html->cuadro_lista($combo, 'empleador_nit', $this->configuracion, 0, 0, FALSE, 0, 'empleador_nit');
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
                            <label class="fieldlabel" for="p1f6c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve"><a STYLE="color: red" >* </a>Nombre Previsora</span></span></span></label>
                            <div class="null"></div>
                        </div>
                        <div class="control capleft">
                            <div class="control capleft">
                                <div class="dropdown" required='required' title="*Campo Obligatorio" required='required'>

                                    <?
                                    unset($combo);
                                    //prepara los datos como se deben mostrar en el combo
                                    $combo[0][0] = '0';
                                    $combo[0][1] = 'Empleador';
                                    foreach ($datos_previsora as $cmb => $values) {
                                        $combo[$cmb][0] = isset($datos_previsora[$cmb]['prev_nit']) ? $datos_previsora[$cmb]['prev_nit'] : 0;
                                        $combo[$cmb][1] = isset($datos_previsora[$cmb]['prev_nombre']) ? $datos_previsora[$cmb]['prev_nombre'] : '';
                                    }

                                    // echo$combo;
                                    if (isset($_REQUEST['entidad2'])) {
                                        $lista_combo = $this->html->cuadro_lista($combo, 'prev_nit', $this->configuracion, $_REQUEST['prev_nit'], 0, FALSE, 0, 'prev_nit');
                                    } else {
                                        $lista_combo = $this->html->cuadro_lista($combo, 'prev_nit', $this->configuracion, 0, 0, FALSE, 0, 'prev_nit');
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
                        <div class="staticcontrol"><span class="wordwrap"><span class="pspan arial" style="text-align: left; font-size:14px;"><span class="ispan" style="color:#000099" xml:space="preserve">DATOS TIEMPO LABORAL</span><span class="ispan" style="color:#EE3D23" xml:space="preserve"> </span></span></span></div>
                        <div class="null"></div>
                    </div>
                    <div class="null"></div>
                </div>

                <div class="formrow f1 f2">
                    <div id="p1f10" class="field n1">
                        <div class="caption capleft alignleft">
                            <label class="fieldlabel" for="fecha_ingreso"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF"><a STYLE="color: red" >* </a>Fecha Ingreso</span></span></span></label>
                            <div class="null"></div>
                        </div>
                        <div class="control capleft">
                            <div>
                                <input type="text" id="fecha_ingreso" title="*Campo Obligatorio" onpaste="return false" placeholder="dd/mm/aaaa" name="fecha_ingreso" required='required' pattern="(0[1-9]|[12][0-9]|3[01])[/](0[1-9]|1[012])[/](19|20)\d\d">
                            </div>
                            <div class="null"></div>
                        </div>
                        <div class="null"></div>
                    </div>

                    <div id="p1f11" class="field n2">
                        <div class="caption capleft alignleft">
                            <label class="fieldlabel" for="fecha_salida"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" ><a STYLE="color: red" >* </a>Fecha Retiro</span></span></span></label>
                            <div class="null"></div>
                        </div>
                        <div class="control capleft">
                            <div>
                                <input type="text" id="fecha_salida" title="*Campo Obligatorio" onchange="validarFecha()" onpaste="return false" placeholder="dd/mm/aaaa" name="fecha_salida" required='required' pattern="(0[1-9]|[12][0-9]|3[01])[/](0[1-9]|1[012])[/](19|20)\d\d">

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
                            <label class="fieldlabel" for="p1f6c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve"> <a STYLE="color: red" >* </a>Horas Laboradas</span></span></span></label>
                            <div class="null"></div>
                        </div>
                        <div class="control capleft">
                            <div>
                                <input type="text" id="p1f12c" title="*Campo Obligatorio" onpaste="return false" name="horas_laboradas"  placeholder="000"  class="fieldcontent" required='required' onKeyPress='return acceptNum8(event)' maxlength="3" min="8" max="240" value="8">
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
                            <label class="fieldlabel" for="p1f7c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve"><a STYLE="color: red" >* </a>Tipo Hora Laborada</span></span></span></label>
                            <div class="null"></div>
                        </div>
                        <div class="control capleft">
                            <div>
                                <div class="dropdown">
                                    <select id="p1f13c" name="tipo_horas" title="*Campo Obligatorio" class="fieldcontent"><option value="Diario" selected="selected">Diarias</option><option value="Semanal">Semanales</option><option value="Mensual">Mensuales</option><option value="3" >En el Periodo</option></select>
                                    <div class="fielderror"></div>
                                </div>
                            </div>
                            <div class="null"></div>
                        </div>
                        <div class="null"></div>
                    </div>
                    <div class="null"></div>
                </div>

                <div class="formrow f1">
                    <div id="p1f14" class="field checkBoxField n1">
                        <div class="caption capleft alignleft">
                            <div class="grouplabel"><span class="wordwrap"><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve">   Ingreso Interrupción</span></span></span><div class="null"></div>
                            </div>
                            <div class="null"></div>
                        </div>
                        <div class="control capleft">
                            <div>
                                <div class="choicegroup stack">
                                    <div class="choices">
                                        <div class="choicefield choice">
                                            <div class="choiceinput">
                                                <input type="checkbox" class="fieldcontent single" name="interrupcion" id="p1f14c" value="interrupcion">
                                            </div>
                                            <div class="choicelabel">
                                                <span class="wordwrap"><a STYLE="color:red;font-size:13px;" >&iquest;Desea registrar una interrupci&oacute;n?</a></span>
                                            </div>
                                        </div>
                                        <div class="null"></div>
                                    </div>
                                </div>
                                <div class="fielderror"></div>
                                <div class="null"></div>
                            </div>
                            <div class="null"></div>
                        </div>
                        <div class="null"></div>
                    </div>
                    <div class="null"></div>
                </div>

                <div align="left"><a STYLE="color: red" ><br><br>* Campo obligatorio</a></div>

                <div class="null"></div>
                <center> <input id="registrarBoton" type="submit" class="navbtn"  onClick='return confirmarEnvio();' value="Registrar"></center>

                <input type='hidden' name='opcion' value='registrarHistoria'>
                <input type='hidden' name='action' value='<? echo $this->formulario; ?>'>
            </div>

        </form>
        <?
    }

    function datoBasico() {

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
                especiales = [8, 39, 9];
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
        <form method='POST' action='index.php' name='<? echo $this->formulario; ?>' autocomplete='Off'>

            <h2>Ingrese la cédula a realizar la <br>Consulta de Historia Laboral: </h2>

            <br>
            <input type="text" name="cedula_emp" required='required' onKeyPress='return acceptNum(event)' maxlength="10" pattern=".{4,10}" title="*Campo Obligatorio">
            <br><br>
            <center> <input id="registrarBoton" type="submit" class="navbtn"  value="Consultar" ></center>
            <input type='hidden' name='pagina' value='formHistoria'>
            <input type='hidden' name='opcion' value='mostrarHistoria'>

            <br>
        </form>
        <?
    }

    function datoBasicoR() {

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
                especiales = [8, 39, 9];
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
        <form method='POST' action='index.php' name='<? echo $this->formulario; ?>' autocomplete='Off'>

            <h2>Ingrese la cédula a realizar<br>Registro de Historia Laboral: </h2>
            <br>
            <input type="text" name="cedula_emp" required='required' onKeyPress='return acceptNum(event)' maxlength="10" pattern=".{4,10}">
            <br><br>
            <center> <input id="registrarBoton" type="submit" class="navbtn"  value="Consultar" ></center>
            <input type='hidden' name='pagina' value='formHistoria'>
            <input type='hidden' name='opcion' value='registrarHistoria'>

            <br>
        </form>
        <?
    }

    function datosReporte($historia, $interrupcion, $descripcion,$basico) {
        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/dbms.class.php");
        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/sesion.class.php");
        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");

        $this->formulario = "formHistoria";

        $variable = 'pagina=formHistoria';
        $variable.='&opcion=interrupcion';
        $variable = $this->cripto->codificar_url($variable, $this->configuracion);
        ?>
        <!referencias a estilos y plugins>
        <link href = "<? echo $this->configuracion["host"] . $this->configuracion["site"] . $this->configuracion["bloques"] ?>/nomina/cuotas_partes/cuentaCobro/cuentaC.css" rel = "stylesheet" type = "text/css" />
        <link href="<? echo $this->configuracion["host"] . $this->configuracion["site"] . $this->configuracion["bloques"] ?>/reportes/menu_reportesFin/estilo_repFin.css"	rel="stylesheet" type="text/css" />
        <link href = "http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel = "stylesheet" type = "text/css"/>
        <script type = "text/javascript" src = "http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
        <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>

        <!--p>Inicio de Declaraciòn del Formulario</p-->

        <form id="form" method="post" action='index.php' name='<? echo $this->formulario; ?>' autocomplete='Off'>

            <h1>Reporte Historia Laboral</h1>

            <table class='bordered'  width ="100%"  >
                <tr>
                    <th colspan="12" class='encabezado_registro'>DATOS BÁSICOS DEL PENSIONADO</th>
                    <td class='texto_elegante<? echo '' ?> estilo_td' ></td>
                </tr>
                <tr>
                    <td class='texto_elegante2 estilo_td' colspan="2" align=center>&nbsp;IDENTIFICACIÓN&nbsp;</td>
                    <td class='texto_elegante2 estilo_td' colspan="10" align=center>&nbsp;NOMBRE&nbsp;</td>
                </tr>
                <tr>
                    <td class='texto_elegante2 estilo_td' colspan="2" align=center><? echo $basico[0][0] ?></td>
                    <td class='texto_elegante2 estilo_td' colspan="10" align=center><? echo $basico[0][1] ?></td>
                </tr>

                <tr>
                    <th colspan="12" class='encabezado_registro'>HISTORIA LABORAL REGISTRADA</th>
                    <td class='texto_elegante<? echo '' ?> estilo_td' ></td>
                </tr>
                <tr>
                    <td class='texto_elegante2 estilo_td' align=center>&nbsp;INGRESO&nbsp;</td>
                    <td class='texto_elegante2 estilo_td' colspan="3" align=center>&nbsp;EMPLEADOR&nbsp;</td>
                    <td class='texto_elegante2 estilo_td' colspan="2" align=center>FECHA INGRESO</td>
                    <td class='texto_elegante2 estilo_td' colspan="2" align=center>FECHA RETIRO</td>
                    <td class='texto_elegante2 estilo_td' colspan='2' align=center>JORNADA</td>
                    <td class='texto_elegante2 estilo_td' colspan='2' align=center>HORAS LABORADAS</td>
                </tr>

                <tr>
                    <?
                    if (is_array($historia)) {
                        foreach ($historia as $key => $value) {

                            echo "<tr>";
                            echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $historia[$key]['hlab_nro_ingreso'] . "</td>";
                            echo "<td class='texto_elegante estilo_td' colspan='3' style='text-align:center;'>" . $historia[$key]['hlab_nitenti'] . "</td>";
                            echo "<td class='texto_elegante estilo_td' colspan='2'style='text-align:center;'>" . $historia[$key]['hlab_fingreso'] . "</td>";
                            echo "<td class='texto_elegante estilo_td' colspan='2' style='text-align:center;'>" . $historia[$key]['hlab_fretiro'] . "</td>";
                            echo "<td class='texto_elegante estilo_td' colspan='2' style='text-align:center;'>" . $historia[$key]['hlab_periodicidad'] . "</td>";
                            echo "<td class='texto_elegante estilo_td' colspan='2'style='text-align:center;'>" . $historia[$key]['hlab_horas'] . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr>";
                        echo "<td class='texto_elegante estilo_td' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                        echo "<td class='texto_elegante estilo_td' colspan='3'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                        echo "<td class='texto_elegante estilo_td' colspan='2'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                        echo "<td class='texto_elegante estilo_td' colspan='2'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                        echo "<td class='texto_elegante estilo_td' colspan='2'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                        echo "<td class='texto_elegante estilo_td' colspan='2'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                        echo "</tr>";
                    }
                    ?>

                <tr>
                    <th colspan="12" class='encabezado_registro'>ENTIDADES DE PREVISIÓN</th>
                    <td class='texto_elegante<? echo '' ?> estilo_td' ></td>
                </tr>
                <tr>
                    <td class='texto_elegante2 estilo_td' align=center>&nbsp;INGRESO&nbsp;</td>
                    <td class='texto_elegante2 estilo_td' colspan='2' align=center>&nbsp;NIT&nbsp;</td>
                    <td class='texto_elegante2 estilo_td' colspan='5' align=center>&nbsp;NOMBRE&nbsp;</td>
                    <td class='texto_elegante2 estilo_td' colspan='2' align=center>FECHA INGRESO</td>
                    <td class='texto_elegante2 estilo_td' colspan='2' align=center>FECHA RETIRO</td>
                </tr>

                <tr>
                    <?
                    if (is_array($historia)) {
                        foreach ($historia as $key => $value) {

                            echo "<tr>";
                            echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $historia[$key]['hlab_nro_ingreso'] . "</td>";
                            echo "<td class='texto_elegante estilo_td' colspan='2' style='text-align:center;'>" . $historia[$key]['hlab_nitprev'] . "</td>";
                            echo "<td class='texto_elegante estilo_td' colspan='5' style='text-align:center;'>" . $historia[$key]['prev_nombre'] . "</td>";
                            echo "<td class='texto_elegante estilo_td' colspan='2'style='text-align:center;'>" . $historia[$key]['hlab_fingreso'] . "</td>";
                            echo "<td class='texto_elegante estilo_td' colspan='2' style='text-align:center;'>" . $historia[$key]['hlab_fretiro'] . "</td>";

                            echo "</tr>";
                        }
                    } else {
                        echo "<tr>";
                        echo "<td class='texto_elegante estilo_td' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                        echo "<td class='texto_elegante estilo_td' colspan='2' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                        echo "<td class='texto_elegante estilo_td' colspan='5' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                        echo "<td class='texto_elegante estilo_td' colspan='2' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                        echo "<td class='texto_elegante estilo_td' colspan='2' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                        echo "</tr>";
                    }
                    ?>
                <tr>
                    <th colspan="12" class='encabezado_registro'>PERIODOS DE INTERRUPCIÓN</th>
                    <td class='texto_elegante<? echo '' ?> estilo_td' ></td>
                </tr>
                <tr>
                    <td class='texto_elegante2 estilo_td' align=center>&nbsp;INGRESO&nbsp;</td>
                    <td class='texto_elegante2 estilo_td' align=center>&nbsp;INTERRUPCION&nbsp;</td>
                    <td class='texto_elegante2 estilo_td' colspan='2' align=center>&nbsp;EMPLEADOR&nbsp;</td>
                    <td class='texto_elegante2 estilo_td' colspan='3' align=center>FECHA INICIO</td>
                    <td class='texto_elegante2 estilo_td' colspan='3' align=center>FECHA FINAL</td>
                    <td class='texto_elegante2 estilo_td' colspan='2' align=center>DIAS INTERRUMPIDOS</td>
                </tr>

                <tr>
                    <?
                    if (is_array($interrupcion)) {
                        foreach ($interrupcion as $key => $value) {

                            echo "<tr>";
                            echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $interrupcion[$key]['int_nro_ingreso'] . "</td>";
                            echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $interrupcion[$key]['int_nro_interrupcion'] . "</td>";
                            echo "<td class='texto_elegante estilo_td' colspan='2' style='text-align:center;'>" . $interrupcion[$key]['int_nitent'] . "</td>";
                            echo "<td class='texto_elegante estilo_td' colspan='3' style='text-align:center;'>" . $interrupcion[$key]['int_fdesde'] . "</td>";
                            echo "<td class='texto_elegante estilo_td' colspan='3'style='text-align:center;'>" . $interrupcion[$key]['int_fhasta'] . "</td>";
                            echo "<td class='texto_elegante estilo_td' colspan='2' style='text-align:center;'>" . $interrupcion[$key]['int_dias'] . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr>";
                        echo "<td class='texto_elegante estilo_td' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                        echo "<td class='texto_elegante estilo_td' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                        echo "<td class='texto_elegante estilo_td' colspan='2' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                        echo "<td class='texto_elegante estilo_td' colspan='3' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                        echo "<td class='texto_elegante estilo_td' colspan='3' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                        echo "<td class='texto_elegante estilo_td' colspan='2' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                        echo "</tr>";
                    }
                    ?>

                <tr>
                    <th colspan="12" class='encabezado_registro'>DATOS CERTIFICACIÓN INTERRUPCIÓN LABORAL</th>
                    <td class='texto_elegante<? echo '' ?> estilo_td' ></td>
                </tr>
                <tr>
                    <td class='texto_elegante2 estilo_td' align=center>INGRESO</td>
                    <td class='texto_elegante2 estilo_td' align=center>INTERRUPCION</td>
                    <td class='texto_elegante2 estilo_td' colspan="3" align=center>NÚMERO CERTIFICACIÓN</td>
                    <td class='texto_elegante2 estilo_td' colspan="6" align=center>FECHA CERTIFICACIÓN</td>

                </tr>

                <tr>
                    <?
                    if (is_array($interrupcion)) {
                        foreach ($interrupcion as $key => $value) {

                            echo "<tr>";
                            echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $interrupcion[$key]['int_nro_ingreso'] . "</td>";
                            echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $interrupcion[$key]['int_nro_interrupcion'] . "</td>";
                            echo "<td class='texto_elegante estilo_td' colspan='3' style='text-align:center;'>" . $interrupcion[$key]['int_num_certificado'] . "</td>";
                            echo "<td class='texto_elegante estilo_td' colspan='6' style='text-align:center;'>" . $interrupcion[$key]['int_fecha_cert'] . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr>";
                        echo "<td class='texto_elegante estilo_td' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                        echo "<td class='texto_elegante estilo_td' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                        echo "<td class='texto_elegante estilo_td' colspan='3' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                        echo "<td class='texto_elegante estilo_td'  colspan='6'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                        echo "</tr>";
                    }
                    ?>

                <tr>
                    <th colspan="12" class='encabezado_registro'>INFORMACIÓN CONCURRENCIAS ACEPTADAS</th>
                    <td class='texto_elegante<? echo '' ?> estilo_td' ></td>
                </tr>
                <tr>
                    <td class='texto_elegante2 estilo_td' align=center>&nbsp;ENTIDAD PREVISORA&nbsp;</td>
                    <td class='texto_elegante2 estilo_td' align=center>&nbsp;FECHA RESOLUCIÓN&nbsp;</td>
                    <td class='texto_elegante2 estilo_td' align=center>&nbsp;NÚMERO RESOLUCIÓN&nbsp;</td>
                    <td class='texto_elegante2 estilo_td' colspan='2' align=center>FECHA PENSIÓN</td>
                    <td class='texto_elegante2 estilo_td' align=center>FECHA ACTO ADMTIVO ACEPTACIÓN</td>
                    <td class='texto_elegante2 estilo_td' align=center>ACTO ADMTIVO ACEPTACIÓN</td>
                    <td class='texto_elegante2 estilo_td' align=center>FECHAS INICIO CONCURRENCIA</td>
                    <td class='texto_elegante2 estilo_td' align=center>MESADA INICIAL</td>
                    <td class='texto_elegante2 estilo_td' align=center>% CUOTA PARTE</td>
                    <td class='texto_elegante2 estilo_td' colspan='3'align=center>VALOR CUOTA PARTE</td>
                </tr>

                <tr>
                    <?
                    if (is_array($descripcion)) {
                        foreach ($descripcion as $key => $value) {

                            echo "<tr>";
                            echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $descripcion[$key]['dcp_nitprev'] . "</td>";
                            echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $descripcion[$key]['dcp_resol_pension_fecha'] . "</td>";
                            echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $descripcion[$key]['dcp_resol_pension'] . "</td>";
                            echo "<td class='texto_elegante estilo_td' colspan='2' style='text-align:center;'>" . $descripcion[$key]['dcp_fecha_pension'] . "</td>";
                            echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $descripcion[$key]['dcp_factoadmin'] . "</td>";
                            echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $descripcion[$key]['dcp_actoadmin'] . "</td>";
                            echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $descripcion[$key]['dcp_fecha_concurrencia'] . "</td>";
                            echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $descripcion[$key]['dcp_valor_mesada'] . "</td>";
                            echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $descripcion[$key]['dcp_porcen_cuota'] . "</td>";
                            echo "<td class='texto_elegante estilo_td' colspan='3'style='text-align:center;'>" . $descripcion[$key]['dcp_valor_cuota'] . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr>";
                        echo "<td class='texto_elegante estilo_td' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                        echo "<td class='texto_elegante estilo_td' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                        echo "<td class='texto_elegante estilo_td' colspan='2' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                        echo "<td class='texto_elegante estilo_td' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                        echo "<td class='texto_elegante estilo_td' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                        echo "<td class='texto_elegante estilo_td' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                        echo "<td class='texto_elegante estilo_td' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                        echo "<td class='texto_elegante estilo_td' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                        echo "<td class='texto_elegante estilo_td' colspan='3' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                        echo "</tr>";
                    }
                    ?>
            </table >
        </form>

        <?
    }

}
