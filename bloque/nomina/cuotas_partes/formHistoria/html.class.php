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

        $minY = date("Y", strtotime($datos_interrupcion['fecha_ingreso']));
        $minD = date("n", strtotime($datos_interrupcion['fecha_ingreso']));
        $minM = date("j", strtotime($datos_interrupcion['fecha_ingreso'])) - 1;

        $maxY = date("Y", strtotime($datos_interrupcion['fecha_salida']));
        $maxD = date("n", strtotime($datos_interrupcion['fecha_salida']));
        $maxM = date("j", strtotime($datos_interrupcion['fecha_salida'])) - 1;

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
                    yearRange: '1940:c',
                    minDate: new Date(<? echo $minY ?>,<? echo $minM ?>,<? echo $minD ?>),
                    maxDate: new Date(<? echo $maxY ?>,<? echo $maxM ?>,<? echo $maxD ?>),
                    dateFormat: 'dd/mm/yy',
                    onSelect: function(dateValue, inst) {
                        $("#dias_nor_hasta").datepicker("option", "minDate", dateValue)
                    }
                });
            });

            $(document).ready(function() {
                $("#dias_nor_hasta").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    yearRange: '1940:c',
                    dateFormat: 'dd/mm/yy',
                    minDate: new Date(<? echo $minY ?>,<? echo $minM ?>,<? echo $minD ?>),
                    maxDate: new Date(<? echo $maxY ?>,<? echo $maxM ?>,<? echo $maxD ?>),
                });
            });

            $(document).ready(function() {
                $("#fecha_certificado").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    yearRange: '1940:c',
                    dateFormat: 'dd/mm/yy',
                    minDate: new Date(<? echo $minY ?>,<? echo $minM ?>,<? echo $minD ?>),
                    maxDate: new Date(<? echo $maxY ?>,<? echo $maxM ?>,<? echo $maxD ?>),
                });
            });
        </script>

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

        <form id="form" method="post" action="index.php" name='<? echo $this->formulario; ?>' autocomplete='Off'>
            <h1>Formulario de Registro Interrupciones</h1>

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
                        <label class="fieldlabel" for="p1f2c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve">Cédula Pensionado<a STYLE="color: red" >*</a></span></span></span></label>
                        <div class="null"></div>
                    </div>
                    <div>
                        <input type="text" id="p1f2c" name="cedula_emp" class="fieldcontent" required='required' onKeyPress='return acceptNum2(event)' readonly value='<? echo $datos_interrupcion['cedula'] ?>' maxlength="10">
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
                            <label class="fieldlabel" for="p1f6c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve">Número Interrupción<a STYLE="color: red" >*</a></span></span></span></label>
                            <div class="null"></div>
                        </div>
                        <div class="control capleft">
                            <div>
                                <input type="text" id="p1f6c" name="nro_interrupcion" class="fieldcontent" required='required' onKeyPress='return acceptNum(event)' maxlength="2"  value='<? echo $datos_interrupcion['nro_interrupcion'] ?>'>
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
                            <label class="fieldlabel" for="p1f6c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve">Número Ingreso<a STYLE="color: red" >*</a></span></span></span></label>
                            <div class="null"></div>
                        </div>
                        <div class="control capleft">
                            <div>
                                <input type="text" id="p1f6c" name="nro_ingreso" class="fieldcontent" required='required' onKeyPress='return acceptNum(event)' maxlength="2" value='<? echo $datos_interrupcion['nro_ingreso'] ?>'>
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
                            <label class="fieldlabel" for="p1f6c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve">Nit Empleador<a STYLE="color: red" >*</a></span></span></span></label>
                            <div class="null"></div>
                        </div>
                        <div class="control capleft">
                            <div>
                                <input type="text" id="p1f6c" name="nit_entidad" class="fieldcontent" maxlength="15" required='required' onKeyPress='return acceptNum(event)' readonly value='<? echo $datos_interrupcion['nit_entidad'] ?>'>
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
                            <label class="fieldlabel" for="p1f6c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve">Nit Entidad Previsora<a STYLE="color: red" >*</a></span></span></span></label>
                            <div class="null"></div>
                        </div>
                        <div class="control capleft">
                            <div class="control capleft">
                                <div>
                                    <input type="text" id="p1f6c" name="prev_nit" class="fieldcontent" maxlength="15" required='required' onKeyPress='return acceptNum(event)' readonly value='<? echo $datos_interrupcion['nit_previsora'] ?>'> 
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
                            <label class="fieldlabel" for="dias_nor_desde"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" >No remunera desde<a STYLE="color: red" >*</a></span></span></span></label>
                            <div class="null"></div>
                        </div>
                        <div class="control capleft">
                            <div>
                                <input type="text" id="dias_nor_desde" name="dias_nor_desde" placeholder="dd/mm/aaaa" required='required' pattern="(0[1-9]|[12][0-9]|3[01])[/](0[1-9]|1[012])[/](19|20)\d\d">
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
                            <label class="fieldlabel" for="dias_nor_hasta"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" >No remunera hasta<a STYLE="color: red" >*</a></span></span></span></label>
                            <div class="null"></div>
                        </div>
                        <div class="control capleft">
                            <div>
                                <input type="text" id="dias_nor_hasta"  name="dias_nor_hasta" placeholder="dd/mm/aaaa" required='required' pattern="(0[1-9]|[12][0-9]|3[01])[/](0[1-9]|1[012])[/](19|20)\d\d">
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
                            <label class="fieldlabel" for="p1f6c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve">Total Días<a STYLE="color: red" >*</a></span></span></span></label>
                            <div class="null"></div>
                        </div>
                        <div class="control capleft">
                            <div>
                                <input type="text" id="p1f6c" name="total_dias" class="fieldcontent" required='required' onKeyPress='return acceptNum2(event)' maxlength="4">
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
                            <label class="fieldlabel" for="p1f6c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve">Núm. Certificado<a STYLE="color: red" >*</a></span></span></span></label>
                            <div class="null"></div>
                        </div>
                        <div class="control capleft">
                            <div>
                                <input type="text" id="p1f6c" name="num_certificado" class="fieldcontent" required='required' onKeyPress='return acceptNum2(event)' maxlength="7">
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
                            <label class="fieldlabel" for="fecha_certificado"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" >Fecha Certificado<a STYLE="color: red" >*</a></span></span></span></label>
                            <div class="null"></div>
                        </div>
                        <div class="control capleft">
                            <div>
                                <input type="text" id="fecha_certificado"  name="fecha_certificado" placeholder="dd/mm/aaaa" required='required' pattern="(0[1-9]|[12][0-9]|3[01])[/](0[1-9]|1[012])[/](19|20)\d\d">
                            </div>
                            <div class="null"></div>
                        </div>
                        <div class="null"></div>
                    </div>
                    <div class="null"></div>
                </div>

                <div class="null"></div>
                <center> 
                    <input name='registro' id="registrarBoton" type="submit" class="navbtn"  value="Registrar Otra Interrupción" onClick='return confirmarEnvio();'>
                    <input name='registro' id="registrarBoton" type="submit" class="navbtn"  value="Registrar Interrupción Actual" onClick='return confirmarEnvio();'>
                </center>

                <input type='hidden' name='opcion' value='registrarInterrupcion'>
                <input type='hidden' name='action' value='<? echo $this->formulario; ?>'>
                <input type='hidden' name='fecha_ingreso' value='<? echo $datos_interrupcion['fecha_ingreso'] ?>'>
                <input type='hidden' name='fecha_salida' value='<? echo $datos_interrupcion['fecha_salida'] ?>'>
            </div>

        </form>
        <?
    }

    function formularioHistoria($datos_previsora) {

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
            });
        </script>

        <script>
            function acceptNum(e) {
                key = e.keyCode || e.which;
                tecla = String.fromCharCode(key).toLowerCase();
                letras = "01234567890-";
                especiales = [8, 39, 37, 9];

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

        <script type="text/javascript">
            function validar() {
                //obteniendo el valor que se puso en el campo text del formulario
                miCampoTexto = document.getElementById("miCampo").value;
                //la condición
                if (miCampoTexto.length == 0 || /^\s+$/.test(miCampoTexto)) {
                    alert('Existen campos diligenciados incorrectamente');
                    return true;
                }

                return false;
            }
        </script>

        <script>
            function acceptNum2(e) {
                key = e.keyCode || e.which;
                tecla = String.fromCharCode(key).toLowerCase();
                letras = "01234567890.";
                especiales = [8, 39, 37, 9];

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
            function acceptNum3(e) {
                key = e.keyCode || e.which;
                tecla = String.fromCharCode(key).toLowerCase();
                letras = "01234567890";
                especiales = [8, 39, 37, 9];

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
                letras = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ ";
                especiales = [8, 39, 37, 9];

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
                especiales = [8, 39, 37, 9, 32];

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

        <form id="form" method="post" action="index.php" name='<? echo $this->formulario; ?>' autocomplete='Off'>
            <h1>Formulario de Registro Historia Laboral Pensionado CP</h1>
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
                        <label class="fieldlabel" for="p1f2c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve">Cédula Pensionado<a STYLE="color: red" >*</a></span></span></span></label>
                        <div class="null"></div>
                    </div>
                    <div>
                        <input type="text" id="p1f2c" name="cedula_emp" class="fieldcontent" required='required' onKeyPress='return acceptNum3(event)' maxlength="10" pattern=".{4,10}">

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
                            <label class="fieldlabel" for="p1f6c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve">Número Ingreso<a STYLE="color: red" >*</a></span></span></span></label>
                            <div class="null"></div>
                        </div>
                        <div class="control capleft">
                            <div>
                                <input type="text" id="miCampo" name="nro_ingreso" class="fieldcontent" required='required' onKeyPress='return acceptNum(event)' maxlength="2" pattern=".{1,2}">
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
                                    $combo[0][1] = 'No registra en la base de datos';
                                    foreach ($datos_previsora as $cmb => $values) {
                                        $combo[$cmb + 1][0] = isset($datos_previsora[$cmb]['prev_nit']) ? $datos_previsora[$cmb]['prev_nit'] : 0;
                                        $combo[$cmb + 1][1] = isset($datos_previsora[$cmb]['prev_nombre']) ? $datos_previsora[$cmb]['prev_nombre'] : '';
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
                                    $combo[0][1] = 'Empleador';
                                    foreach ($datos_previsora as $cmb => $values) {
                                        $combo[$cmb + 1][0] = isset($datos_previsora[$cmb]['prev_nit']) ? $datos_previsora[$cmb]['prev_nit'] : 0;
                                        $combo[$cmb + 1][1] = isset($datos_previsora[$cmb]['prev_nombre']) ? $datos_previsora[$cmb]['prev_nombre'] : '';
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
                            <label class="fieldlabel" for="fecha_ingreso"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" >Fecha Ingreso<a STYLE="color: red" >*</a></span></span></span></label>
                            <div class="null"></div>
                        </div>
                        <div class="control capleft">
                            <div>
                                <input type="text" id="fecha_ingreso" placeholder="dd/mm/aaaa" name="fecha_ingreso" required='required' pattern="(0[1-9]|[12][0-9]|3[01])[/](0[1-9]|1[012])[/](19|20)\d\d">
                            </div>
                            <div class="null"></div>
                        </div>
                        <div class="null"></div>
                    </div>

                    <div id="p1f11" class="field n2">
                        <div class="caption capleft alignleft">
                            <label class="fieldlabel" for="fecha_salida"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" >Fecha Retiro<a STYLE="color: red" >*</a></span></span></span></label>
                            <div class="null"></div>
                        </div>
                        <div class="control capleft">
                            <div>
                                <input type="text" id="fecha_salida" placeholder="dd/mm/aaaa" name="fecha_salida" required='required' pattern="(0[1-9]|[12][0-9]|3[01])[/](0[1-9]|1[012])[/](19|20)\d\d">

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
                            <label class="fieldlabel" for="p1f6c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve">Horas Laboradas<a STYLE="color: red" >*</a></span></span></span></label>
                            <div class="null"></div>
                        </div>
                        <div class="control capleft">
                            <div>
                                <input type="text" id="p1f12c" name="horas_laboradas"  placeholder="00.00"  class="fieldcontent" required='required' onKeyPress='return acceptNum2(event)' maxlength="6" pattern="[\d]{1,3}\.[\d]{1,2}" >Dos cifras enteras y dos decimales como máximo

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
                            <label class="fieldlabel" for="p1f7c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve">Tipo Hora Laborada<a STYLE="color: red" >*</a></span></span></span></label>
                            <div class="null"></div>
                        </div>
                        <div class="control capleft">
                            <div>
                                <div class="dropdown">
                                    <select id="p1f13c" name="tipo_horas" class="fieldcontent"><option value="0">Diaria</option><option value="1">Semanal</option><option value="2">Mensual</option><option value="4" selected="selected">En el Periodo</option></select>
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
                            <div class="grouplabel"><span class="wordwrap"><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve">Ingreso Interrupción<a STYLE="color: red" >*</a></span></span></span><div class="null"></div>
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

            <h2>Ingrese la cédula a realizar la consulta de historia laboral: </h2>
            <br>
            <input type="text" name="cedula_emp" required='required' onKeyPress='return acceptNum(event)'>
            <br><br>
            <center> <input id="registrarBoton" type="submit" class="navbtn"  value="Consultar" ></center>
            <input type='hidden' name='pagina' value='formHistoria'>
            <input type='hidden' name='opcion' value='mostrarHistoria'>

            <br>
        </form>
        <?
    }

    function datosEntidad($historial) {
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

            <h1>Historia Laboral Registrada Pensionado Cuota Parte</h1>

            <table class='bordered'  width ="100%"  >
                <tr>
                    <th colspan="8" class='encabezado_registro'>HISTORIA LABORAL</th>
                    <td class='texto_elegante<? echo '' ?> estilo_td' ></td>
                </tr>
                <tr>
                    <td class='texto_elegante2 estilo_td' align=center>&nbsp;INGRESO&nbsp;</td>
                    <td class='texto_elegante2 estilo_td' align=center>&nbsp;IDENTIFICACIÓN&nbsp;</td>
                    <td class='texto_elegante2 estilo_td' align=center>&nbsp;EMPLEADOR&nbsp;</td>
                    <td class='texto_elegante2 estilo_td' align=center>ENTIDAD PREVISORA</td>
                    <td class='texto_elegante2 estilo_td' align=center>FECHA INGRESO</td>
                    <td class='texto_elegante2 estilo_td' align=center>FECHA RETIRO</td>
                    <td class='texto_elegante2 estilo_td' align=center>HORAS LABORADAS</td>
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
                            echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $historial[$key][6] . "</td>";
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
        </form>

        <?
    }

}