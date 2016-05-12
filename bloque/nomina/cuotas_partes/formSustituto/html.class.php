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

date_default_timezone_set('America/Bogota');

class html_formSustituto {

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
        $this->formulario = "formSustituto";
    }

    function form_valor() {

        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/dbms.class.php");
        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/sesion.class.php");
        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
        $this->formulario = "formSustituto";
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

            <h2>Ingrese la cédula del pensionado para realizar <br> Registro de Sustituto: </h2><br><br>

            <input type="text" name="cedula_emp" required='required' onKeyPress='return acceptNum(event)' title="*Campo Obligatorio">
            <br><br>
            <center> <input id="registrarBoton" type="submit" class="navbtn"  value="Consultar" ></center>

            <input type='hidden' name='pagina' value='formularioSustituto'>
            <input type='hidden' name='opcion' value='historiaSustituto'>
        </form>
        <?
    }

    function formularioSustituto($cedula, $pensionado, $defuncion) {

        $this->formulario = "formSustituto";
        $fecha_min = date('d/m/Y', strtotime(str_replace('/', '-', $pensionado[0][1])));

        $i_fecha_anio = date('Y', (strtotime(str_replace('/', '-', $pensionado[0][1]))));
        $i_fecha_dia = date('d', (strtotime(str_replace('/', '-', $pensionado[0][1]))));
        $i_fecha_mes = date('m', (strtotime("" . str_replace('/', '-', $pensionado[0][1]))));

        $disable = '';

        if ($defuncion['fecha_defuncion'] != 0) {
            $disable = 'readonly';
            $defuncion['fecha_defuncion'] = date('d/m/Y', strtotime(str_replace('/', '-', $defuncion['fecha_defuncion'])));
            $defuncion['fecha_defuncioncertificado'] = date('d/m/Y', strtotime(str_replace('/', '-', $defuncion['fecha_defuncioncertificado'])));
        } else {
            $defuncion['fecha_defuncion'] = '';
            $defuncion['fecha_defuncioncertificado'] = '';
            $defuncion['defuncion_certificado'] = '';
        }

        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/dbms.class.php");
        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/sesion.class.php");
        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
        ?>

        <!referencias a estilos y plugins>
        <script type="text/javascript" src="<? echo $this->configuracion["host"] . $this->configuracion["site"] . $this->configuracion["plugins"]; ?>/datepicker/js/datepicker.js"></script>
        <link href="<? echo $this->configuracion["host"] . $this->configuracion["site"] . $this->configuracion["bloques"] ?>/nomina/cuotas_partes/formSustituto/form_estilo.css"	rel="stylesheet" type="text/css" />
        <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
        <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>

        <script>
                function acceptNum2(e) {
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
            $(document).ready(function() {
                $("#fecha_nacs").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    yearRange: '1940:c',
                    maxDate: "+2M",
                    dateFormat: 'dd/mm/yy',
                });
            });

            $(document).ready(function() {
                $("#fecha_muerte").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    yearRange: '1980:c',
                    maxDate: "+2M",
                    dateFormat: 'dd/mm/yy',
                    onSelect: function(dateValue, inst) {
                        $("#fecha_res_sustitucion").datepicker("option", "minDate", dateValue)
                        $("#fecha_certificado").datepicker("option", "minDate", dateValue)
                        $("#fecha_tersentencia").datepicker("option", "minDate", dateValue)
                    }
                });
                $("#fecha_muerte").datepicker('setDate', '<?php echo $defuncion['fecha_defuncion'] ?>');
                $("#fecha_muerte").datepicker("option", "minDate", '<?php echo $fecha_min ?>');

            });

            $(document).ready(function() {
                $("#fecha_res_sustitucion").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    yearRange: '1940:c',
                    maxDate: "+2M",
                    dateFormat: 'dd/mm/yy'
                });
                $("#fecha_res_sustitucion").datepicker('option', 'minDate', '<?php echo $fecha_min ?>');
            });

            $(document).ready(function() {
                $("#fecha_certificado").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    yearRange: '1940:c',
                    maxDate: "+2M",
                    dateFormat: 'dd/mm/yy',
                    onSelect: function(dateValue, inst) {
                        $("#fecha_res_sustitucion").datepicker("option", "minDate", dateValue)
                    }
                });
                $("#fecha_certificado").datepicker('setDate', '<?php echo $defuncion['fecha_defuncioncertificado'] ?>');
            });

            $(document).ready(function() {
                $("#fecha_tersentencia").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    yearRange: '1940:c',
                    maxDate: "+2M",
                    dateFormat: 'dd/mm/yy',
                });
            });


        </script>

        <script language = "Javascript">
            //Éste script valida si las fechas ingresadas en el formulario no son menores a la fecha de pension
            function echeck(str) {

                var min = new Date('<? echo $i_fecha_anio ?>,<? echo $i_fecha_mes ?>,<? echo $i_fecha_dia ?>');

                        var y = str.substring(6);
                        var m3 = str.substring(3, 5);
                        var m2 = m3 - 1;
                        var m = '0' + m2;
                        var d = str.substring(0, 2);
                        var cadena = new Date(y, m, d);
                        if (cadena < min) {
                            alert('Fecha fuera del rango especificado')
                            return false
                        }

                        return true
                    }

                    function minDate() {

                        var fechaID = document.formSustituto.fecha_muerte

                        if ((fechaID.value == null) || (fechaID.value == "")) {
                            alert("Ingrese una fecha válida!")
                            emailID.focus()
                            return false
                        }

                        if (echeck(fechaID.value) == false) {
                            fechaID.value = ""
                            fechaID.focus()
                            return false
                        }


                        var fechaID = document.formSustituto.fecha_res_sustitucion

                        if ((fechaID.value == null) || (fechaID.value == "")) {
                            alert("Ingrese una fecha válida!")
                            emailID.focus()
                            return false
                        }

                        if (echeck(fechaID.value) == false) {
                            fechaID.value = ""
                            fechaID.focus()
                            return false
                        }

                        var fechaID = document.formSustituto.fecha_certificado

                        if ((fechaID.value == null) || (fechaID.value == "")) {
                            alert("Ingrese una fecha válida!")
                            emailID.focus()
                            return false
                        }

                        if (echeck(fechaID.value) == false) {
                            fechaID.value = ""
                            fechaID.focus()
                            return false
                        }
                        return true
                    }
        </script>

        <script>
            function  validarFecha() {
                var desde = (document.getElementById("fecha_res_sustitucion").value);
                var hasta = (document.getElementById("fecha_certificado").value);
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

                if (cadena1.getTime() < cadena2.getTime()) {
                    document.getElementById("fecha_certificado").focus();
                    document.getElementById("fecha_res_sustitucion").focus();
                    alert("Fecha No Válida");
                    return false
                }
                return true
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
            function acceptLetter(e) {
                key = e.keyCode || e.which;
                tecla = String.fromCharCode(key).toLowerCase();
                letras = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789ñÑ";
                especiales = [8, 9, 64, 32];
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
            $(function() {
                $('.tercero').hide();

                $('#tutor').change(function() {
                    $('.tercero').hide();
                    $('#' + $(this).val()).show();
                });
            });
        </script>

        <form id="form" method="post" action="index.php" name='<? echo $this->formulario; ?>' onSubmit="return minDate();" autocomplete='Off'>
            <h1>Registrar Sustituto de Pensionado</h1>

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
                    <div class="staticcontrol"><span class="wordwrap"><span class="pspan arial" style="text-align: left; font-size:14px;"><span class="ispan" style="color:#000099" xml:space="preserve">DATOS SUSTITUCIÓN</span><span class="ispan" style="color:#EE3D23" xml:space="preserve"> </span></span></span></div>
                    <div class="null"></div>
                </div>
                <div class="null"></div>
            </div>

            <div class="formrow f1">
                <div id="p1f12" class="field n1">
                    <div class="caption capleft alignleft">
                        <label class="fieldlabel" for="p1f12c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve"><a STYLE="color: red" >* </a>Cédula Pensionado</span></span></span></label>
                        <div class="null"></div>
                    </div>
                    <div class="control capleft">
                        <div>
                            <input type="text" id="p1f12c" name="cedula_pen" title="*Campo Obligatorio" maxlenght="16" class="fieldcontent" readonly required='required' value="<? echo $cedula; ?>" onpaste="return false">
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
                        <label class="fieldlabel" for="fecha_muerte"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" ><a STYLE="color: red" >* </a>Fecha Defunción <br>  Pensionado</span></span></span></label>
                        <div class="null"></div>
                    </div>
                    <div class="control capleft">
                        <div>
                            <input type="text" id="fecha_muerte" <? echo $disable ?> title="*Campo Obligatorio" name="fecha_muerte" maxlenght="10" placeholder="dd/mm/aaaa" required='required' pattern="(0[1-9]|[12][0-9]|3[01])[/](0[1-9]|1[012])[/](19|20)\d\d" onpaste="return false" value="<?php echo $defuncion['fecha_defuncion'] ?>">
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
                        <label class="fieldlabel" for=certificado_defuncion"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF"><a STYLE="color: red" >* </a>Certificado<br>  Defunción</span></span></span></label>
                        <div class="null"></div>
                    </div>
                    <div class="control capleft">
                        <div>
                            <input type="text" id="certificado_defuncion" <? echo $disable ?> title="*Campo Obligatorio" name="certificado_defuncion" required='required' onKeyPress='return acceptNumLetter(event)' maxlength="12" pattern=".{1,12}." onpaste="return false" value="<?php echo $defuncion['defuncion_certificado'] ?>" >
                        </div>
                        <div class="null"></div>
                    </div>
                    <div class="null"></div>
                </div>

                <div id="p1f11" class="field n2">
                    <div class="caption capleft alignleft">
                        <label class="fieldlabel" for="fecha_certificado"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" ><a STYLE="color: red" >* </a>Fecha <br>  Certificado <br>  Defunción</span></span></span></label>
                        <div class="null"></div>
                    </div>
                    <div class="control capleft">
                        <div>
                            <input type="text" id="fecha_certificado" <? echo $disable ?> title="*Campo Obligatorio" name="fecha_certificadod" maxlenght="10" placeholder="dd/mm/aaaa" required='required' pattern="(0[1-9]|[12][0-9]|3[01])[/](0[1-9]|1[012])[/](19|20)\d\d" onpaste="return false"  onchange="validarFecha()">
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
                        <label class="fieldlabel" for="p1f12c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve"><a STYLE="color: red" >* </a>Documento<br>   Sustituto</span></span></span></label>
                        <div class="null"></div>
                    </div>
                    <div class="control capleft">
                        <div>
                            <input type="text" id="p1f12c" name="cedula_sustituto" maxlenght="16" title="*Campo Obligatorio"  onKeyPress='return acceptNum2(event)' class="fieldcontent" required='required' onpaste="return false">
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
                        <label class="fieldlabel" for="p1f12c"><span><span class="pspan arial"  style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve"><a STYLE="color: red" >* </a>Nombre<br>   Sustituto</span></span></span></label>
                        <div class="null"></div>
                    </div>
                    <div class="control capleft">
                        <div>
                            <input type="text" id="p1f7c" name="nombre_sustituto" maxlenght="50" title="*Campo Obligatorio"  onKeyPress='return acceptLetter(event)' class="fieldcontent" required='required' onpaste="return false">
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
                        <label class="fieldlabel" for="p1f6c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve"><a STYLE="color: red" >* </a>Parentezco</span></span></span></label>
                        <div class="null"></div>
                    </div>
                    <div class="control capleft">
                        <div>
                            <select id="select" name="parentezco" class="fieldcontent" title="*Campo Obligatorio">
                                <option value="Cónyugue">Cónyugue</option> 
                                <option value="Compañera(o)">Compañera(o)</option>
                                <option value="Hija(o)">Hija(o)</option>
                                <option value="Madre (Padre)">Madre (Padre)</option>
                                <option value="Otro" selected="selected">Otro</option></select>
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
                        <label class="fieldlabel" for="p1f6c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve"><a STYLE="color: red" >* </a>Género</span></span></span></label>
                        <div class="null"></div>
                    </div>
                    <div class="control capleft">
                        <div>
                            <select id="select" name="genero" class="fieldcontent" title="*Campo Obligatorio">
                                <option value="M">Masculino</option> 
                                <option value="F" selected="selected">Femenino</option></select>
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
                        <label class="fieldlabel" for="fecha_nacsustituto"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" ><a STYLE="color: red" >* </a>Fecha Nacimiento <br>  Sustituto</span></span></span></label>
                        <div class="null"></div>
                    </div>
                    <div class="control capleft">
                        <div>
                            <input type="text" id="fecha_nacs" title="*Campo Obligatorio" name="fecha_nacsustituto" maxlenght="10" placeholder="dd/mm/aaaa" required='required' pattern="(0[1-9]|[12][0-9]|3[01])[/](0[1-9]|1[012])[/](19|20)\d\d" onpaste="return false" >
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
                        <label class="fieldlabel" for="resolucion_sustitucion"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF"><a STYLE="color: red" >* </a>Resolución<br>  Sustitución</span></span></span></label>
                        <div class="null"></div>
                    </div>
                    <div class="control capleft">
                        <div>
                            <input type="text" id="res_sustitucion" title="*Campo Obligatorio" name="resolucion_sustitucion" required='required' onKeyPress='return acceptNumLetter(event)' maxlength="12" pattern=".{1,12}." onpaste="return false" >
                        </div>
                        <div class="null"></div>
                    </div>
                    <div class="null"></div>
                </div>

                <div id="p1f11" class="field n2">
                    <div class="caption capleft alignleft">
                        <label class="fieldlabel" for="fecha_res_sustitucion"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" ><a STYLE="color: red" >* </a>Fecha<br>  Resolución<br>  Sustitución</span></span></span></label>
                        <div class="null"></div>
                    </div>
                    <div class="control capleft">
                        <div>
                            <input type="text" id="fecha_res_sustitucion" title="*Campo Obligatorio" name="fecha_res_sustitucion" maxlenght="10" placeholder="dd/mm/aaaa" required='required' pattern="(0[1-9]|[12][0-9]|3[01])[/](0[1-9]|1[012])[/](19|20)\d\d" onpaste="return false" >
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
                        <label class="fieldlabel" for="p1f6c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve"><a STYLE="color: red" >* </a>Tutor</span></span></span></label>
                        <div class="null"></div>
                    </div>
                    <div class="control capleft">
                        <div>
                            <select id="tutor" name="tutor" class="fieldcontent" title="*Campo Obligatorio">
                                <option value="madre" >Madre</option> 
                                <option value="padre">Padre</option>
                                <option value="tercero">Tercero</option>
                                <option value="noAplica" selected="selected">No Aplica</option></select>
                        </div>
                        <div class="null"></div>
                    </div>
                    <div class="null"></div>
                </div>
                <div class="null"></div>
            </div>
            <div id="tercero" class="tercero">
                <div class="formrow f1">
                    <div id="p1f12" class="field n1">
                        <div class="caption capleft alignleft">
                            <label class="fieldlabel" for="p1f12c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve"><a STYLE="color: red" >* </a>Documento<br>   Tercero</span></span></span></label>
                            <div class="null"></div>
                        </div>
                        <div class="control capleft">
                            <div>
                                <input type="text" id="p1f12c" name="cedula_tercero" maxlenght="16" title="*Campo Obligatorio"  onKeyPress='return acceptNum2(event)' class="fieldcontent"  onpaste="return false">
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
                            <label class="fieldlabel" for="p1f12c"><span><span class="pspan arial"  style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve"><a STYLE="color: red" >* </a>Nombre<br>   Tercero</span></span></span></label>
                            <div class="null"></div>
                        </div>
                        <div class="control capleft">
                            <div>
                                <input type="text" id="p1f7c" name="nombre_tercero" maxlenght="50" title="*Campo Obligatorio"  onKeyPress='return acceptLetter(event)' class="fieldcontent"  onpaste="return false">
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
                            <label class="fieldlabel" for="tercero_sentencia"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF"><a STYLE="color: red" >* </a>Sentencia<br>  Judicial</span></span></span></label>
                            <div class="null"></div>
                        </div>
                        <div class="control capleft">
                            <div>
                                <textarea id="observacion" rows="4" cols="50" title="*Campo Obligatorio" name="tercero_sentencia"  maxlenght="100" onpaste="return false" ></textarea>
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
                            <label class="fieldlabel" for="fecha_tersentencia"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" ><a STYLE="color: red" >* </a>Fecha<br>  Sentencia</span></span></span></label>
                            <div class="null"></div>
                        </div>
                        <div class="control capleft">
                            <div>
                                <input type="text" id="fecha_tersentencia" title="*Campo Obligatorio" name="fecha_tersentencia" maxlenght="10" placeholder="dd/mm/aaaa" pattern="(0[1-9]|[12][0-9]|3[01])[/](0[1-9]|1[012])[/](19|20)\d\d" onpaste="return false" >
                            </div>
                            <div class="null"></div>
                        </div>
                        <div class="null"></div>
                    </div>
                    <div class="null"></div>
                </div>
            </div>
            <div class="formrow f1">
                <div id="p1f6" class="field n1">
                    <div class="caption capleft alignleft">
                        <label class="fieldlabel" for="observacion"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" > Observaciones</span></label>
                                    <div class="null"></div>
                                    </div>
                                    <div class="control capleft">
                                        <div>
                                            <textarea id="observacion" rows="4" cols="50" title="*Campo Obligatorio" name="observacion"  maxlenght="80" onpaste="return false" ></textarea>
                                        </div>
                                        <div class="null"></div>
                                    </div>
                                    <div class="null">
                                    </div>
                                    </div>
                                    <div class="null"></div>
                                    </div>

                                    <div align="left"><a STYLE="color: red" ><br><br>* Campo obligatorio</a></div>

                                    <div class="null"></div>
                                    <center> <input id="registrarBoton" type="submit" class="navbtn"  onClick='return confirmarEnvio();' value="Registrar"></center>

                                    <input type='hidden' name='opcion' value='registrarSustituto'>
                                    <input type='hidden' name='action' value='<? echo $this->formulario; ?>'>

                                    </form>
                                    <?
                                }

                                function reporteSustituto($datos_sustitutos) {

                                    include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/dbms.class.php");
                                    include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/sesion.class.php");
                                    include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");

                                    $this->formulario = "formSustituto";
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
                                                        <th colspan="14" class='encabezado_registro'>SUSTITUTOS REGISTRADOS</th>
                                                        <td class='texto_elegante<? echo '' ?> estilo_td' ></td>
                                                    </tr>
                                                    <tr>
                                                        <td class='texto_elegante2 estilo_td' align=center>CÉDULA PENSIONADO</td>
                                                        <td class='texto_elegante2 estilo_td' align=center>FECHA DEFUNCION</td>
                                                        <td class='texto_elegante2 estilo_td' align=center>CERTIFICADO DEFUNCIÓN</td>
                                                        <td class='texto_elegante2 estilo_td' align=center>FECHA C. D.</td>
                                                        <td class='texto_elegante2 estilo_td' align=center>CÉDULA SUSTITUTO</td>
                                                        <!--td class='texto_elegante2 estilo_td' align=center>NOMBRE SUSTITUTO</td-->
                                                        <td class='texto_elegante2 estilo_td' align=center>FECHA NAC. SUSTITUTO</td>
                                                        <td class='texto_elegante2 estilo_td' align=center>RES. SUSTITUCIÓN</td>
                                                        <td class='texto_elegante2 estilo_td' align=center>FECHA RES. SUSTITUCIÓN</td>
                                                        <td class='texto_elegante2 estilo_td' align=center>PARENTEZCO</td>
                                                        <!--td class='texto_elegante2 estilo_td' align=center>GÉNERO</td-->
                                                        <td class='texto_elegante2 estilo_td' align=center>TUTOR</td>
                                                        <td class='texto_elegante2 estilo_td' align=center>CÉDULA TERCERO </td>
                                                        <!--td class='texto_elegante2 estilo_td' align=center>NOMBRE TERCERO</td-->
                                                        <td class='texto_elegante2 estilo_td' align=center>SENTENCIA</td>
                                                        <td class='texto_elegante2 estilo_td' align=center>FECHA SENTENCIA</td>
                                                        <td class='texto_elegante2 estilo_td' align=center>OBSERVACIONES </td>
                                                    </tr>
                                                    <tbody id="itemContainer">
                                                        <?
                                                        if (is_array($datos_sustitutos)) {

                                                            foreach ($datos_sustitutos as $key => $value) {
                                                                echo "<tr>";
                                                                echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $datos_sustitutos[$key]['sus_cedulapen'] . "</td>";
                                                                echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $datos_sustitutos[$key]['sus_fdefuncion'] . "</td>";
                                                                echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $datos_sustitutos[$key]['sus_certificado_defuncion'] . "</td>";
                                                                echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $datos_sustitutos[$key]['sus_fcertificado_defuncion'] . "</td>";
                                                                echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $datos_sustitutos[$key]['sus_cedulasus'] . "</td>";
                                                                //echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $datos_sustitutos[$key]['sus_nombresus'] . "</td>";
                                                                echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $datos_sustitutos[$key]['sus_fnac_sustituto'] . "</td>";
                                                                echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $datos_sustitutos[$key]['sus_resol_sustitucion'] . "</td>";
                                                                echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $datos_sustitutos[$key]['sus_fresol_sustitucion'] . "</td>";
                                                                echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $datos_sustitutos[$key]['sus_parentezcosus'] . "</td>";
                                                                //echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $datos_sustitutos[$key]['sus_generosus'] . "</td>";
                                                                echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $datos_sustitutos[$key]['sus_tutor'] . "</td>";
                                                                echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $datos_sustitutos[$key]['sus_cedula_tercero'] . "</td>";
                                                                //echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $datos_sustitutos[$key]['sus_nombre_tercero'] . "</td>";
                                                                echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $datos_sustitutos[$key]['sus_tercero_sentencia'] . "</td>";
                                                                echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $datos_sustitutos[$key]['sus_fecha_tersentencia'] . "</td>";
                                                                echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $datos_sustitutos[$key]['sus_observacion'] . "</td>";
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
                                                            echo "<td class='texto_elegante estilo_td' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                                                            echo "<td class='texto_elegante estilo_td' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                                                            //echo "<td class='texto_elegante estilo_td' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                                                            //echo "<td class='texto_elegante estilo_td' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                                                            // echo "<td class='texto_elegante estilo_td' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
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
                                