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

class html_formConcurrencia {

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
        $this->formulario = "formConcurrencia";
    }

    function formularioConcurrencia($datos_previsora) {

        $this->formulario = "formConcurrencia";

        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/dbms.class.php");
        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/sesion.class.php");
        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
        ?>

        <style>                    h3{text-align: left}                </style>

        <!referencias a estilos y plugins>
        <script type="text/javascript" src="<? echo $this->configuracion["host"] . $this->configuracion["site"] . $this->configuracion["plugins"]; ?>/datepicker/js/datepicker.js"></script>
        <link	href="<? echo $this->configuracion["host"] . $this->configuracion["site"] . $this->configuracion["bloques"] ?>/nomina/cuotas_partes/formConcurrencia/form_estilo.css"	rel="stylesheet" type="text/css" />
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
            function acceptNum3(e) {
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
                $("#fecha_con").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    yearRange: '1940:c',
                    maxDate: "+0D",
                    dateFormat: 'dd/mm/yy',
                    onSelect: function(dateValue, inst) {
                        $("#fecha_act").datepicker("option", "minDate", dateValue)
                    }
                });
            });

            $(document).ready(function() {
                $("#fecha_act").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    yearRange: '1940:c',
                    maxDate: "+0D",
                    dateFormat: 'dd/mm/yy'
                });
            });

            $(document).ready(function() {
                $("#fecha_res_pension").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    yearRange: '1940:c',
                    maxDate: "+0D",
                    dateFormat: 'dd/mm/yy'
                });
            });

            $(document).ready(function() {
                $("#fecha_pension").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    yearRange: '1940:c',
                    maxDate: "+0D",
                    dateFormat: 'dd/mm/yy'
                });
            });
        </script>

        <script language = "Javascript">
            /**
             * DHTML email validation script. Courtesy of SmartWebby.com (http://www.smartwebby.com/dhtml/)
             */

            function echeck(str) {

                var at = "@"
                var dot = "."
                var lat = str.indexOf(at)
                var lstr = str.length
                var ldot = str.indexOf(dot)
                if (str.indexOf(at) == -1) {
                    alert("Verifique su e-mail")
                    return false
                }

                if (str.indexOf(at) == -1 || str.indexOf(at) == 0 || str.indexOf(at) == lstr) {
                    alert("Verifique su e-mail")
                    return false
                }

                if (str.indexOf(dot) == -1 || str.indexOf(dot) == 0 || str.indexOf(dot) == lstr) {
                    alert("Verifique su e-mail")
                    return false
                }

                if (str.indexOf(at, (lat + 1)) != -1) {
                    alert("Verifique su e-mail")
                    return false
                }

                if (str.substring(lat - 1, lat) == dot || str.substring(lat + 1, lat + 2) == dot) {
                    alert("Verifique su e-mail")
                    return false
                }

                if (str.indexOf(dot, (lat + 2)) == -1) {
                    alert("Verifique su e-mail")
                    return false
                }

                if (str.indexOf(" ") != -1) {
                    alert("Verifique su e-mail")
                    return false
                }

                return true
            }

            function ValidateForm() {
                var emailID = document.formConcurrencia.txtEmail

                if ((emailID.value == null) || (emailID.value == "")) {
                    alert("Ingrese un correo electrónico!")
                    emailID.focus()
                    return false
                }
                if (echeck(emailID.value) == false) {
                    emailID.value = ""
                    emailID.focus()
                    return false
                }

                var emailID2 = document.formConcurrencia.txtEmail2

                if ((emailID2.value == null) || (emailID2.value == "")) {
                    alert("Ingrese un correo electrónico!")
                    emailID2.focus()
                    return false
                }
                if (echeck(emailID2.value) == false) {
                    emailID2.value = ""
                    emailID2.focus()
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

        <form id="form" method="post" action="index.php" name='<? echo $this->formulario; ?>' onSubmit="return  ValidateForm();" autocomplete='Off'>
            <h1>Formulario de Registro Descripción Cuota Parte Aceptada</h1>

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
                    <div class="staticcontrol"><span class="wordwrap"><span class="pspan arial" style="text-align: left; font-size:14px;"><span class="ispan" style="color:#000099" xml:space="preserve">REGISTRO DESCRIPCIÓN CUOTA PARTE</span><span class="ispan" style="color:#EE3D23" xml:space="preserve"> </span></span></span></div>
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
                            <input type="text" id="p1f12c" name="cedula" class="fieldcontent" required='required' onKeyPress='return acceptNum3(event)' maxlength="10" pattern=".{3,10}." onpaste="return false">

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
                                    $lista_combo = $this->html->cuadro_lista($combo, 'entidad_empleadora', $this->configuracion, $_REQUEST['prev_nit'], 0, FALSE, 0, 'entidad_empleadora');
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
                                $combo[0][1] = 'Empleador';
                                foreach ($datos_previsora as $cmb => $values) {
                                    $combo[$cmb + 1][0] = isset($datos_previsora[$cmb]['prev_nit']) ? $datos_previsora[$cmb]['prev_nit'] : 0;
                                    $combo[$cmb + 1][1] = isset($datos_previsora[$cmb]['prev_nombre']) ? $datos_previsora[$cmb]['prev_nombre'] : '';
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
                <div id="p1f6" class="field n1">
                    <div class="caption capleft alignleft">
                        <label class="fieldlabel" for="fecha_con"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" >Fecha I.Concurrencia<a STYLE="color: red" >*</a></span></span></span></label>
                        <div class="null"></div>
                    </div>
                    <div class="control capleft">
                        <div>
                            <input type="text" id="fecha_con" name="fecha_concurrencia" maxlenght="10" placeholder="dd/mm/aaaa" required='required' pattern="(0[1-9]|[12][0-9]|3[01])[/](0[1-9]|1[012])[/](19|20)\d\d" onpaste="return false">
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
                        <label class="fieldlabel" for="resolucion_pension"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" >Res. Pensión<a STYLE="color: red" >*</a></span></span></span></label>
                        <div class="null"></div>
                    </div>
                    <div class="control capleft">
                        <div>
                            <input type="text" id="res_pensión" name="resolucion_pension" required='required' onKeyPress='return acceptNumLetter(event)' maxlength="12" pattern=".{1,12}." onpaste="return false">
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
                        <label class="fieldlabel" for="fecha_pension"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" >Fecha Pensión<a STYLE="color: red" >*</a></span></span></span></label>
                        <div class="null"></div>
                    </div>
                    <div class="control capleft">
                        <div>
                            <input type="text" id="fecha_pension" name="fecha_pension"  maxlenght="10" placeholder="dd/mm/aaaa" required='required' pattern="(0[1-9]|[12][0-9]|3[01])[/](0[1-9]|1[012])[/](19|20)\d\d" onpaste="return false">
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
                        <label class="fieldlabel" for="fecha_res_pension"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" >Fecha Res. Pensión<a STYLE="color: red" >*</a></span></span></span></label>
                        <div class="null"></div>
                    </div>
                    <div class="control capleft">
                        <div>
                            <input type="text" id="fecha_res_pension" name="fecha_res_pension" maxlenght="10" placeholder="dd/mm/aaaa" required='required' pattern="(0[1-9]|[12][0-9]|3[01])[/](0[1-9]|1[012])[/](19|20)\d\d" onpaste="return false">
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
                        <label class="fieldlabel" for="p1f12c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve">Mesada Inicial<a STYLE="color: red" >*</a></span></span></span></label>
                        <div class="null"></div>
                    </div>
                    <div class="control capleft">
                        <div>
                            <input type="text" id="p1f12c" name="mesada" class="fieldcontent" required='required' onKeyPress='return acceptNum2(event)' maxlength="7" pattern=".{3,7}." onpaste="return false">

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
                        <label class="fieldlabel" for="p1f6c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve">Cuota Aceptada<a STYLE="color: red" >*</a></span></span></span></label>
                        <div class="null"></div>
                    </div>
                    <div class="control capleft">
                        <div>
                            <input type="text" id="p1f6c" name="cp_aceptada"  class="fieldcontent" required='required' onKeyPress='return acceptNum2(event)' maxlength="7" pattern=".{3,7}." onpaste="return false">
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
                        <label class="fieldlabel" for="p1f6c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve">Porcentaje Aceptado<a STYLE="color: red" >*</a></span></span></span></label>
                        <div class="null"></div>
                    </div>
                    <div class="control capleft">
                        <div>
                            <input type="text" id="p1f6c" name="porc_aceptado" placeholder="0.00" class="fieldcontent" required='required' onKeyPress='return acceptNum2(event)' maxlength="6" pattern="^[0]\d{0,1}(\.\d{1,4})?%?$" step="0.00" onpaste="return false">Decimal en formato: 0.9999, mín. dos decimales
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
                        <label class="fieldlabel" for="p1f7c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve">Tipo Acto Adm.<a STYLE="color: red" >*</a></span></span></span></label>
                        <div class="null"></div>
                    </div>
                    <div class="control capleft">
                        <div>
                            <div class="dropdown">
                                <select id="p1f13c" name="tipo_acto" class="fieldcontent"><option selected="selected "value="Silencio Administrativo">Silencio Administrativo</option><option value="Resolución">Resolución</option><option value="Oficio">Oficio</option></select>
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
                <div id="p1f6" class="field n1">
                    <div class="caption capleft alignleft">
                        <label class="fieldlabel" for="p1f7c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve">Acto Administrativo<a STYLE="color: red" >*</a></span></span></span></label>
                        <div class="null"></div>
                    </div>
                    <div class="control capleft">
                        <div>
                            <input type="text" id="p1f7c" name="acto_adm" class="fieldcontent" onKeyPress='return acceptNumLetter(event)' maxlength="15" placeholder="Oficio/Resolución/Silencio Administrativo" onpaste="return false">
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
                        <label class="fieldlabel" for="fecha_act"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" >Fecha Acto Admin.<a STYLE="color: red" >*</a></span></span></span></label>
                        <div class="null"></div>
                    </div>
                    <div class="control capleft">
                        <div>
                            <input type="text" id="fecha_act" name="fecha_acto_adm"  maxlenght="10" placeholder="dd/mm/aaaa" required='required' pattern="(0[1-9]|[12][0-9]|3[01])[/](0[1-9]|1[012])[/](19|20)\d\d" onpaste="return false" >
                        </div>
                        <div class="null"></div>
                    </div>
                    <div class="null"></div>
                </div>
                <div class="null"></div>
            </div>




            <div class="null"></div>
            <center> <input id="registrarBoton" type="submit" class="navbtn"  onClick='return confirmarEnvio();' value="Registrar"></center>

            <input type='hidden' name='opcion' value='registrarConcurrencia'>
            <input type='hidden' name='action' value='<? echo $this->formulario; ?>'>

        </form>
        <?
    }

}
