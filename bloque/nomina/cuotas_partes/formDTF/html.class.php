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

class html_formDTF {

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

    function tablaDTF($datos) {
        ?>

        <link	href="<? echo $this->configuracion["host"] . $this->configuracion["site"] . $this->configuracion["bloques"] ?>/nomina/cuotas_partes/formPrevisora/form_estilo.css"	rel="stylesheet" type="text/css" />
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

            <h2><br><br>Reporte de Índices de Tasas de Interés Registradas<br><br></h2>
            <table width="90%" class='bordered' align="center">
                <tr>
                    <th colspan="7" class='encabezado_registro'>TASA DE INTERÉS</th>
                    <td class='texto_elegante estilo_td' ></td>
                </tr>

                <tr>                       
                    <th class='encabezado_registro' style="text-align:center">Norma</th>
                    <th class='encabezado_registro' style="text-align:center">N° Resolución</th>
                    <th class='encabezado_registro' style="text-align:center">Fecha Resolución</th>
                    <th class='encabezado_registro' style="text-align:center">Fecha Vigencia Inicio</th>
                    <th class='encabezado_registro' style="text-align:center">Fecha Vigencia Hasta</th>
                    <th class='encabezado_registro' style="text-align:center">Tasa Interes(DTF)</th>
                    <th class='encabezado_registro' style="text-align:center">Modificar</th>
                </tr>
                <tbody id="itemContainer">
                    <?php
                    if (is_array($datos)) {
                        foreach ($datos as $key => $values) {
                            echo "<tr>";
                            echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $datos[$key][0] . "</td>";
                            echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $datos[$key][1] . "</td>";
                            echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $datos[$key][2] . "</td>";
                            echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $datos[$key][3] . "</td>";
                            echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $datos[$key][4] . "</td>";
                            echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $datos[$key][5] . "</td>";
                            echo "<td class='texto_elegante estilo_td' style='text-align:center;'>"
                            . " <a href='";
                            $variable = 'pagina=formularioDTF';
                            $variable.='&opcion=modificarDTF';
                            $variable.='&datos_dtf=' . serialize($datos[$key]);
                            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                            echo " " . $this->indice . $variable . "'>
                            <img alt='Imagen' width='20px' src='" . $this->configuracion["host"] . $this->configuracion["site"] . $this->configuracion["bloques"] . "/nomina/cuotas_partes/Images/cuentacobro.png'/></td>";
                            echo "</tr>";
                        }
                    } else {

                        echo "<tr>";
                        echo "<td class='texto_elegante estilo_td' style='text-align:center;'></td>";
                        echo "<td class='texto_elegante estilo_td' style='text-align:center;'></td>";
                        echo "<td class='texto_elegante estilo_td' style='text-align:center;'></td>";
                        echo "<td class='texto_elegante estilo_td' style='text-align:center;'></td>";
                        echo "<td class='texto_elegante estilo_td' style='text-align:center;'></td>";
                        echo "<td class='texto_elegante estilo_td' style='text-align:center;'></td>";
                        echo "<td class='texto_elegante estilo_td' style='text-align:center;'></td>";
                        echo "</tr>";
                    }
                    ?>

            </table>

            <center><div class="holder"></div></center>
            <BR><BR>
        </center>

        <?php
    }

    function formularioDTF($rango) {


        $fecha_max = date('d/m/Y', (strtotime("" . str_replace('/', '-', $rango[0]['fin']) . "+1 day")));
        $fecha_min = date('d/m/Y', strtotime(str_replace('/', '-', $rango[0]['inicio'])));

        $fecha_anio = date('Y', (strtotime(str_replace('/', '-', $rango[0]['fin']))));
        $fecha_mes = date('m', (strtotime(str_replace('/', '-', $rango[0]['fin']))));
        $fecha_dia = date('d', (strtotime("" . str_replace('/', '-', $rango[0]['fin']) . "+ 1 day")));

        $this->formulario = "formDTF";


        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/dbms.class.php");
        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/sesion.class.php");
        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
        ?>

        <style>                    h3{text-align: left}                </style>

        <!referencias a estilos y plugins>
        <script type="text/javascript" src="<? echo $this->configuracion["host"] . $this->configuracion["site"] . $this->configuracion["plugins"]; ?>/datepicker/js/datepicker.js"></script>
        <link	href="<? echo $this->configuracion["host"] . $this->configuracion["site"] . $this->configuracion["bloques"] ?>/nomina/cuotas_partes/formSustituto/form_estilo.css"	rel="stylesheet" type="text/css" />
        <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
        <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>

        <script>
            $(document).ready(function() {
                $("#fecvig_desde").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    yearRange: '1940:c',
                    dateFormat: 'dd/mm/yy'
                });
                $("#fecvig_desde").datepicker('option', 'minDate', '<?php echo $fecha_max ?>');

                $("#fecvig_hasta").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    yearRange: '1940:c',
                    dateFormat: 'dd/mm/yy',
                });
                $("#fecvig_hasta").datepicker('option', 'minDate', '<?php echo $fecha_max ?>');

                $("#fec_reso").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    yearRange: '1940:c',
                    dateFormat: 'dd/mm/yy',
                    maxDate: "+0D"
                });

            });</script>

        <script>
            function acceptNum(e) {
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
            function  validaranio() {
                var anio = document.getElementById("año_registrar").value;

                if (anio < 2006) {
                    document.getElementById("indice_dtf").value = 0.12;
                    document.getElementById("indice_dtf").disabled = true;
                    document.getElementById("fecvig_hasta").disabled = true;
                    document.getElementById("fecvig_desde").disabled = true;
                    document.getElementById("n_resolucion").disabled = false;
                    document.getElementById("fec_reso").disabled = false;
                } else {
                    document.getElementById("indice_dtf").value = "";
                    document.getElementById("indice_dtf").disabled = false;
                    document.getElementById("fecvig_hasta").disabled = false;
                    document.getElementById("fecvig_desde").disabled = false;
                    document.getElementById("n_resolucion").disabled = false;
                    document.getElementById("fec_reso").disabled = false;
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
            //Éste script valida si las fechas ingresadas en el formulario estan entre otras historias laborales
            function trascheck(str) {

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
            echo "alert('Ya existen registros para este periodo.')\n";
            echo " return false\n";
            echo "    }\n\n";
        }
        ?>
                return true
            }

            function echeck(str) {

                var min = new Date('<? echo $fecha_anio ?>,<? echo $fecha_mes ?>,<? echo $fecha_dia ?>');
                        var fecha = '<? echo $fecha_max ?>'

                        if (str < fecha || str > fecha) {
                            alert('Fecha vigencia desde, mínimo es ' + fecha)
                            return false
                        }
                        return true
                    }

                    function traslape() {

                        var fechaID = document.formDTF.fecvig_desde;
                        if ((fechaID.value == null) || (fechaID.value == "")) {
                            alert("Ingrese una fecha válida!")
                            fechaID.focus()
                            return false
                        }

                        if (trascheck(fechaID.value) == false) {
                            fechaID.value = ""
                            fechaID.focus()
                            return false
                        }

                        var fechaID = document.formDTF.fecvig_hasta;
                        if ((fechaID.value == null) || (fechaID.value == "")) {
                            alert("Ingrese una fecha válida!")
                            fechaID.focus()
                            return false
                        }

                        if (trascheck(fechaID.value) == false) {
                            fechaID.value = ""
                            fechaID.focus()
                            return false
                        }

                        var fechaID = document.formDTF.fecvig_desde
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
                var desde = (document.getElementById("fecvig_desde").value);
                var hasta = (document.getElementById("fecvig_hasta").value);
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
                    document.getElementById("fecvig_desde").focus();
                    document.getElementById("fecvig_hasta").focus();
                    alert("El intervalo de fecha de vigencia no es válido.");
                    return false
                }

                return true
            }
        </script>

        <form id="form" method="post" action="index.php" name='<?php echo $this->formulario; ?>' autocomplete='Off' onSubmit="return traslape();">
            <h1>Tasa de Interés DTF</h1> 
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


                <div class="formrow f1">
                    <div id="p1f5" class="field n1">
                        <div class="staticcontrol"><span class="wordwrap"><span class="pspan arial" style="text-align: left; font-size:14px;"><span class="ispan" style="color:#000099" xml:space="preserve">DETALLE DEL REGISTRO<span class="ispan" style="color:#EE3D23" xml:space="preserve"> </span></span></span></div>
                        <div class="null"></div>
                    </div>
                    <div class="null"></div>
                </div>

                <div class="formrow f1">
                    <div id="p1f6" class="field n1">
                        <div class="caption capleft alignleft">
                            <label class="fieldlabel" for="p1f6c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve"><a STYLE="color: red" >* </a>Norma</span></span></span></label>
                            <div class="null"></div>
                        </div>
                        <div class="control capleft">
                            <div>
                                <select id="p1f13c" name="norma" class="fieldcontent" title="*Campo Obligatorio"><option value="Ley">Ley</option><option value="Resolucion" selected="selected">Resolución</option></select>
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
                            <label class="fieldlabel" for="n_resolucion"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve"><a STYLE="color: red" >* </a>N° de Norma</span></span></span></label>*Superintendencia<br> Financiera
                            <div class="null"></div>
                        </div>
                        <div class="control capleft">
                            <div>
                                <input type="text" id="n_resolucion" name="n_resolucion" title="*Campo Obligatorio" placeholder="0000" class="fieldcontent" required='required'  maxLength="7" pattern=".{2,7}"  autocomplete="off" onKeyPress='return acceptNum2(event)' onpaste="return false">
                            </div>
                            <div class="null"></div>
                        </div>
                        <div class="null"></div>
                    </div>
                    <div class="null"></div>
                </div>

                <div class="formrow f1 ">
                    <div id="p1f10" class="field n1">
                        <div class="caption capleft alignleft">
                            <label class="fieldlabel" for="fec_reso"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF"><a STYLE="color: red" >* </a>Fecha Norma</span></span></span></label>
                            <div class="null"></div>
                        </div>
                        <div class="control capleft">
                            <div>
                                <input type="text" id="fec_reso" name="fec_reso" title="*Campo Obligatorio" placeholder="dd/mm/aaaa" required='required'  maxLength="10"  pattern="(0[1-9]|[12][0-9]|3[01])[/](0[1-9]|1[012])[/](19|20)\d\d" onpaste="return false">
                            </div>
                            <div class="null"></div>
                        </div>
                        <div class="null"></div>
                    </div>
                    <div class="null"></div>
                </div>


                <div class="formrow f1 ">
                    <div id="p1f10" class="field n1">
                        <div class="caption capleft alignleft">
                            <label class="fieldlabel" for="fecvig_desde"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF"><a STYLE="color: red" >* </a>Fecha Vigencia<br>   Desde</span></span></span></label>
                            <div class="null"></div>
                        </div>
                        <div class="control capleft">
                            <div>
                                <input type="text" id="fecvig_desde" name="fecvig_desde" title="*Campo Obligatorio" placeholder="dd/mm/aaaa" required='required'  maxLength="10"  pattern="(0[1-9]|[12][0-9]|3[01])[/](0[1-9]|1[012])[/](19|20)\d\d" onpaste="return false" value="<? echo $fecha_max ?>">
                            </div>
                            <div class="null"></div>
                        </div>
                        <div class="null"></div>
                    </div>
                    <div class="null"></div>
                </div>


                <div class="formrow f1 ">
                    <div id="p1f10" class="field n1">
                        <div class="caption capleft alignleft">
                            <label class="fieldlabel" for="fecvig_hasta"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF"><a STYLE="color: red" >* </a>Fecha Vigencia<br>   Hasta</span></span></span></label>
                            <div class="null"></div>
                        </div>
                        <div class="control capleft">
                            <div>
                                <input type="text" id="fecvig_hasta" name="fecvig_hasta" onchange="validarFecha()" title="*Campo Obligatorio" placeholder="dd/mm/aaaa" required='required'  maxLength="10" pattern="(0[1-9]|[12][0-9]|3[01])[/](0[1-9]|1[012])[/](19|20)\d\d" onpaste="return false">
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
                            <label class="fieldlabel" for="indice_dtf"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve"><a STYLE="color: red" >* </a>Tasa de Interés<br>   (DTF)</span></span></span></label>
                            <div class="null"></div>
                        </div>
                        <div class="control capleft">
                            <div>
                                <input type="text" id="indice_dtf" name="indice_dtf" title="*Campo Obligatorio" placeholder="0.0000"  maxlength="10" pattern="[0]+([\.|,][0-9]+[0-9])?" onpaste="return false" step="0.0000" title="*Campo obligatorio. Ingrese indice en numeros decimales" class="fieldcontent"  required='required'  onKeyPress='return acceptNum(event)' >&nbsp;*Ingrese formato decimal. Ejemplo: 0.25  

                            </div>

                        </div>


                    </div>
                    <div class="null"></div>
                </div>

                <div align="left"><a STYLE="color: red" ><br><br>* Campo obligatorio</a></div>


                <center> <input id="registrarBoton" type="submit" class="navbtn"  value="Registrar"  onClick='return confirmarEnvio();'></center>

                <input type='hidden' name='opcion' value='insertarDTF'>
                <input type='hidden' name='action' value='<? echo $this->formulario; ?>'>

                </form>


                <?
            }

            function formularioDTF_Modificar($rango, $datos_dtf) {

                $fecha_max = date('d/m/Y', (strtotime("" . str_replace('/', '-', $rango[0]['fin']) . "+1 day")));
                $fecha_min = date('d/m/Y', strtotime(str_replace('/', '-', $rango[0]['inicio'])));

                $fecha_anio = date('Y', (strtotime(str_replace('/', '-', $rango[0]['fin']))));
                $fecha_mes = date('m', (strtotime(str_replace('/', '-', $rango[0]['fin']))));
                $fecha_dia = date('d', (strtotime("" . str_replace('/', '-', $rango[0]['fin']) . "+ 1 day")));

                $this->formulario = "formDTF";

                $f_norma = date('d/m/Y', (strtotime("" . str_replace('/', '-', $datos_dtf['dtf_fe_resolucion']))));
                $f_desde = date('d/m/Y', (strtotime("" . str_replace('/', '-', $datos_dtf['dtf_fe_desde']))));
                $f_hasta = date('d/m/Y', (strtotime("" . str_replace('/', '-', $datos_dtf['dtf_fe_hasta']))));

                include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/dbms.class.php");
                include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/sesion.class.php");
                include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
                ?>

                <style>                    h3{text-align: left}                </style>

                <!referencias a estilos y plugins>
                <script type="text/javascript" src="<? echo $this->configuracion["host"] . $this->configuracion["site"] . $this->configuracion["plugins"]; ?>/datepicker/js/datepicker.js"></script>
                <link	href="<? echo $this->configuracion["host"] . $this->configuracion["site"] . $this->configuracion["bloques"] ?>/nomina/cuotas_partes/formDTF/form_estilo.css"	rel="stylesheet" type="text/css" />
                <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
                <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
                <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>

                <script>
                    $(document).ready(function() {
                        $("#fecvig_desde").datepicker({
                            changeMonth: true,
                            changeYear: true,
                            yearRange: '1940:c',
                            dateFormat: 'dd/mm/yy'
                        });
                        $("#fecvig_desde").datepicker('setDate', '<?php echo $f_desde ?>');

                        $("#fecvig_hasta").datepicker({
                            changeMonth: true,
                            changeYear: true,
                            yearRange: '1940:c',
                            dateFormat: 'dd/mm/yy',
                        });
                        $("#fecvig_hasta").datepicker('setDate', '<?php echo $f_hasta ?>');

                        $("#fec_reso").datepicker({
                            changeMonth: true,
                            changeYear: true,
                            yearRange: '1940:c',
                            dateFormat: 'dd/mm/yy',
                            maxDate: "+0D"
                        });
                        $("#fec_reso").datepicker('setDate', '<?php echo $f_norma ?>');

                    });</script>

                <script>
                    function acceptNum(e) {
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
                    function  validaranio() {
                        var anio = document.getElementById("año_registrar").value;

                        if (anio < 2006) {
                            document.getElementById("indice_dtf").value = 0.12;
                            document.getElementById("indice_dtf").disabled = true;
                            document.getElementById("fecvig_hasta").disabled = true;
                            document.getElementById("fecvig_desde").disabled = true;
                            document.getElementById("n_resolucion").disabled = false;
                            document.getElementById("fec_reso").disabled = false;
                        } else {
                            document.getElementById("indice_dtf").value = "";
                            document.getElementById("indice_dtf").disabled = false;
                            document.getElementById("fecvig_hasta").disabled = false;
                            document.getElementById("fecvig_desde").disabled = false;
                            document.getElementById("n_resolucion").disabled = false;
                            document.getElementById("fec_reso").disabled = false;
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
                    //Éste script valida si las fechas ingresadas en el formulario estan entre otras historias laborales
                    function trascheck(str) {

        <?
        foreach ($rango as $key => $values) {
            /* echo "var min = new Date('" . $rango[$key]['inicio'] . "');\n";
              echo "var max = new Date('" . $rango[$key]['fin'] . "');    \n"; 

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
            echo "alert('Ya existen registros para este periodo.')\n";
            echo " return false\n";
            echo "    }\n\n";*/
        }
        ?>
                        return true
                    }

                    function echeck(str) {

                        return true
                    }

                    function traslape() {

                        var fechaID = document.formDTF.fecvig_desde;
                        if ((fechaID.value == null) || (fechaID.value == "")) {
                            alert("Ingrese una fecha válida!")
                            fechaID.focus()
                            return false
                        }

                        if (trascheck(fechaID.value) == false) {
                            fechaID.value = ""
                            fechaID.focus()
                            return false
                        }

                        var fechaID = document.formDTF.fecvig_hasta;
                        if ((fechaID.value == null) || (fechaID.value == "")) {
                            alert("Ingrese una fecha válida!")
                            fechaID.focus()
                            return false
                        }

                        if (trascheck(fechaID.value) == false) {
                            fechaID.value = ""
                            fechaID.focus()
                            return false
                        }

                        var fechaID = document.formDTF.fecvig_desde
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
                        var desde = (document.getElementById("fecvig_desde").value);
                        var hasta = (document.getElementById("fecvig_hasta").value);
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
                            document.getElementById("fecvig_desde").focus();
                            document.getElementById("fecvig_hasta").focus();
                            alert("El intervalo de fecha de vigencia no es válido.");
                            return false
                        }

                        return true
                    }
                </script>

                <form id="form" method="post" action="index.php" name='<?php echo $this->formulario; ?>' autocomplete='Off' onSubmit="return traslape();">
                    <h1>Tasa de Interés DTF</h1> 
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


                        <div class="formrow f1">
                            <div id="p1f5" class="field n1">
                                <div class="staticcontrol"><span class="wordwrap"><span class="pspan arial" style="text-align: left; font-size:14px;"><span class="ispan" style="color:#000099" xml:space="preserve">DETALLE DEL REGISTRO<span class="ispan" style="color:#EE3D23" xml:space="preserve"> </span></span></span></div>
                                <div class="null"></div>
                            </div>
                            <div class="null"></div>
                        </div>

                        <div class="formrow f1">
                            <div id="p1f6" class="field n1">
                                <div class="caption capleft alignleft">
                                    <label class="fieldlabel" for="p1f6c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve"><a STYLE="color: red" >* </a>Norma</span></span></span></label>
                                    <div class="null"></div>
                                </div>
                                <div class="control capleft">
                                    <div>
                                        <select id="p1f13c" name="norma" class="fieldcontent" title="*Campo Obligatorio"><option value="Ley">Ley</option><option value="Resolucion" selected="selected">Resolución</option></select>
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
                                    <label class="fieldlabel" for="n_resolucion"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve"><a STYLE="color: red" >* </a>N° de Norma</span></span></span></label>*Superintendencia<br> Financiera
                                    <div class="null"></div>
                                </div>
                                <div class="control capleft">
                                    <div>
                                        <input type="text" id="n_resolucion" name="n_resolucion" title="*Campo Obligatorio" placeholder="0000" class="fieldcontent" value='<? echo $datos_dtf['dtf_n_reso'] ?>' required='required'  maxLength="7" pattern=".{2,7}"  autocomplete="off" onKeyPress='return acceptNum2(event)' onpaste="return false">
                                    </div>
                                    <div class="null"></div>
                                </div>
                                <div class="null"></div>
                            </div>
                            <div class="null"></div>
                        </div>

                        <div class="formrow f1 ">
                            <div id="p1f10" class="field n1">
                                <div class="caption capleft alignleft">
                                    <label class="fieldlabel" for="fec_reso"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF"><a STYLE="color: red" >* </a>Fecha Norma</span></span></span></label>
                                    <div class="null"></div>
                                </div>
                                <div class="control capleft">
                                    <div>
                                        <input type="text" id="fec_reso" name="fec_reso"  title="*Campo Obligatorio" placeholder="dd/mm/aaaa" required='required'  maxLength="10"  pattern="(0[1-9]|[12][0-9]|3[01])[/](0[1-9]|1[012])[/](19|20)\d\d" onpaste="return false">
                                    </div>
                                    <div class="null"></div>
                                </div>
                                <div class="null"></div>
                            </div>
                            <div class="null"></div>
                        </div>


                        <div class="formrow f1 ">
                            <div id="p1f10" class="field n1">
                                <div class="caption capleft alignleft">
                                    <label class="fieldlabel" for="fecvig_desde"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF"><a STYLE="color: red" >* </a>Fecha Vigencia<br>   Desde</span></span></span></label>
                                    <div class="null"></div>
                                </div>
                                <div class="control capleft">
                                    <div>
                                        <input type="text" id="fecvig_desde" name="fecvig_desde" title="*Campo Obligatorio" placeholder="dd/mm/aaaa" required='required'  maxLength="10"  pattern="(0[1-9]|[12][0-9]|3[01])[/](0[1-9]|1[012])[/](19|20)\d\d" onpaste="return false" value="<? echo $fecha_max ?>">
                                    </div>
                                    <div class="null"></div>
                                </div>
                                <div class="null"></div>
                            </div>
                            <div class="null"></div>
                        </div>


                        <div class="formrow f1 ">
                            <div id="p1f10" class="field n1">
                                <div class="caption capleft alignleft">
                                    <label class="fieldlabel" for="fecvig_hasta"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF"><a STYLE="color: red" >* </a>Fecha Vigencia<br>   Hasta</span></span></span></label>
                                    <div class="null"></div>
                                </div>
                                <div class="control capleft">
                                    <div>
                                        <input type="text" id="fecvig_hasta"  name="fecvig_hasta" onchange="validarFecha()" title="*Campo Obligatorio" placeholder="dd/mm/aaaa" required='required'  maxLength="10" pattern="(0[1-9]|[12][0-9]|3[01])[/](0[1-9]|1[012])[/](19|20)\d\d" onpaste="return false">
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
                                    <label class="fieldlabel" for="indice_dtf"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve"><a STYLE="color: red" >* </a>Tasa de Interés<br>   (DTF)</span></span></span></label>
                                    <div class="null"></div>
                                </div>
                                <div class="control capleft">
                                    <div>
                                        <input type="text" id="indice_dtf" value='<? echo $datos_dtf['dtf_indi_ce'] ?>' name="indice_dtf" title="*Campo Obligatorio" placeholder="0.0000"  maxlength="10" pattern="[0]+([\.|,][0-9]+[0-9])?" onpaste="return false" step="0.0000" title="*Campo obligatorio. Ingrese indice en numeros decimales" class="fieldcontent"  required='required'  onKeyPress='return acceptNum(event)' >&nbsp;*Ingrese formato decimal. Ejemplo: 0.25  

                                    </div>

                                </div>


                            </div>
                            <div class="null"></div>
                        </div>

                        <div align="left"><a STYLE="color: red" ><br><br>* Campo obligatorio</a></div>


                        <center> <input id="registrarBoton" type="submit" class="navbtn"  value="Actualizar Registro"  onClick='return confirmarEnvio();'></center>

                        <input type='hidden' name='opcion' value='actualizarDTF'>
                        <input type='hidden' name='serial' value='<? echo $datos_dtf['dtf_serial'] ?>'>
                        <input type='hidden' name='action' value='<? echo $this->formulario; ?>'>

                        </form>


                        <?
                    }

                }
                