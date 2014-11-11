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

class html_formSalario {

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

    function tablaSalarios($datos) {
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
            });</script>

        <center>     

            <h2><br><br>Reporte Salarios Mínimos Legales Registrados<br><br></h2>
            <table width="90%" class='bordered' >
                <tr>
                    <th colspan="7" class='encabezado_registro'>SALARIOS MINIMOS LEGALES</th>
                    <td class='texto_elegante<? echo '' ?> estilo_td' ></td>
                </tr>
                <tr>
                    <th class='encabezado_registro' style="text-align:center">NORMA</th>
                    <th class='encabezado_registro' style="text-align:center">NUMERO</th>
                    <th class='encabezado_registro' style="text-align:center">AÑO</th>
                    <th class='encabezado_registro' style="text-align:center">DESDE</th>
                    <th class='encabezado_registro' style="text-align:center">HASTA</th>
                    <th class='encabezado_registro' style="text-align:center">MONTO MENSUAL</th>
                    <th class='encabezado_registro' style="text-align:center">MODIFICAR</th>
                </tr>
                <tbody id="itemContainer">
                    <tr>
                        <?php
                        if (is_array($datos)) {
                            foreach ($datos as $key => $values) {
                                echo "<tr>";
                                echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $datos[$key]['salario_norma'] . "</td>";
                                echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $datos[$key]['salario_numero'] . "</td>";
                                echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $datos[$key]['salario_anio'] . "</td>";
                                echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $datos[$key]['salario_vdesde'] . "</td>";
                                echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $datos[$key]['salario_vhasta'] . "</td>";
                                echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $datos[$key]['salario_monto'] . "</td>";
                                echo "<td class='texto_elegante estilo_td' style='text-align:center;'>"
                                . " <a href='";
                                $variable = 'pagina=formularioSalario';
                                $variable.='&opcion=modificarSalario';
                                $variable.='&datos_salario=' . serialize($datos[$key]);
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
            <center><div class="holder" style="-moz-user-select: none;"></div></center>
        </center>
        <?php
    }

    function formularioSalario_Modificar($rango, $salarios, $datos_salario) {

        $f_desde = date('d/m/Y', (strtotime("" . str_replace('/', '-', $datos_salario['salario_vdesde']))));
        $f_hasta = date('d/m/Y', (strtotime("" . str_replace('/', '-', $datos_salario['salario_vhasta']))));

        $this->formulario = "formSalario";

        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/dbms.class.php");
        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/sesion.class.php");
        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
        ?>


        <style>                    h3{text-align: left}                </style>

        <!referencias a estilos y plugins>
        <script type="text/javascript" src="<? echo $this->configuracion["host"] . $this->configuracion["site"] . $this->configuracion["plugins"]; ?>/datepicker/js/datepicker.js"></script>
        <link	href="<? echo $this->configuracion["host"] . $this->configuracion["site"] . $this->configuracion["bloques"] ?>/nomina/cuotas_partes/formSalario/form_estilo.css"	rel="stylesheet" type="text/css" />
        <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
        <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>

        <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
        <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
        <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
        <link rel="stylesheet" href="/resources/demos/style.css" />

        <script>
            function acceptNum(e) {
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
                letras = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-";
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
            $(document).ready(function() {
                $("#fecvig_desde").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    yearRange: '1940:c',
                    dateFormat: 'dd/mm/yy',
                    maxDate: "+0D",
                });
                $("#fecvig_desde").datepicker('setDate', '<?php echo $f_desde ?>');

                $("#fecvig_hasta").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    yearRange: '1940:c',
                    dateFormat: 'dd/mm/yy',
                    maxDate: "+1M"
                });
                $("#fecvig_hasta").datepicker('setDate', '<?php echo $f_hasta ?>');

                $("#fec_reso").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    yearRange: '1940:c',
                    dateFormat: 'dd/mm/yy',
                    maxDate: "+0D"
                });

            });</script>

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
                    return false;
                }

                if (parseInt(y1) !== parseInt(y2)) {
                    document.getElementById("fecvig_desde").focus();
                    document.getElementById("fecvig_hasta").focus();
                    alert("Los Años de Fecha de Vigencia no pertenecen al mismo año.");
                    return false;
                }
                return true;
            }
        </script>

        <script language = "Javascript">
            //Éste script valida si las fechas ingresadas en el formulario estan entre otras historias laborales
            function echeck(str) {
        <?
        foreach ($rango as $key => $values) {
            /* echo "var min = new Date('" . $rango[$key]['inicio'] . "');\n";
              echo "var max = new Date('" . $rango[$key]['fin'] . "');    \n"; */

            $i_fecha_anio = date('Y', (strtotime(str_replace('/', '-', $rango[$key]['inicio']))));
            $i_fecha_mes = date('m', (strtotime(str_replace('/', '-', $rango[$key]['inicio']))));
            $i_fecha_dia = date('d', (strtotime("" . str_replace('/', '-', $rango[$key]['inicio']))));

            $f_fecha_anio = date('Y', (strtotime(str_replace('/', '-', $rango[$key]['fin']))));
            $f_fecha_mes = date('m', (strtotime(str_replace('/', '-', $rango[$key]['fin']))));
            $f_fecha_dia = date('d', (strtotime("" . str_replace('/', '-', $rango[$key]['fin']))));

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

            function minDate() {

                var fechaID = document.formSalario.fecvig_desde;
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

                var fechaID = document.formSalario.fecvig_hasta;
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


        <form id="form" method="post" action="index.php" name='<?php echo $this->formulario; ?>'  autocomplete='Off'  onsubmit="return minDate()">
            <h1>Salario Mínimo Legal</h1> 
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
                        <div class="staticcontrol"><span class="wordwrap"><span class="pspan arial" style="text-align: left; font-size:14px;"><span class="ispan" style="color:#000099" xml:space="preserve">DETALLES DEL REGISTRO<span class="ispan" style="color:#EE3D23" xml:space="preserve"> </span></span></span></div>
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
                                <select id="p1f13c" name="norma" required='required' class="fieldcontent"><option value="Ley" selected="selected">Ley</option><option value="Decreto">Decreto</option></select>
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
                            <label class="fieldlabel" for="p1f6c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve"><a STYLE="color: red" >* </a>Número Norma</span></span></span></label>
                            <div class="null"></div>
                        </div>
                        <div class="control capleft">
                            <div>
                                <input type="text" id="numero" value='<? echo $datos_salario['salario_numero'] ?>' onpaste='return false' title="*Campo Obligatorio" name="numero"  maxlength="7" class="fieldcontent" required='required' autocomplete="off" onKeyPress='return acceptNum(event)'>
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
                            <label class="fieldlabel" for="año_registrar"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF"><a STYLE="color: red" >* </a>Año a Registrar</span></span></span></label>
                            <div class="null"></div>
                        </div>
                        <div class="control capleft">
                            <div>
                                <input type="text" id="año_registrar" readonly="true" value='<? echo $datos_salario['salario_anio'] ?>' name="año_registrar" autocomplete="off" onpaste='return false' title="*Campo Obligatorio" required='required'>
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
                            <label class="fieldlabel" for="fecvig_desde"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" ><a STYLE="color: red" >* </a>Fecha Vigencia<br>  Desde</span></span></span></label>
                            <div class="null"></div>
                        </div>
                        <div class="control capleft">
                            <div>
                                <input type="text" id="fecvig_desde" onpaste='return false' title="*Campo Obligatorio" name="fecvig_desde" placeholder="dd/mm/aaaa" required='required'  maxLength="10"  pattern="(0[1-9]|[12][0-9]|3[01])[/](0[1-9]|1[012])[/](19|20)\d\d" >
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
                            <label class="fieldlabel" for="fecvig_hasta"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" ><a STYLE="color: red" >* </a>Fecha Vigencia<br>  Hasta</span></span></span></label>
                            <div class="null"></div>
                        </div>
                        <div class="control capleft">
                            <div>
                                <input type="text" id="fecvig_hasta" onchange="validarFecha()" onpaste='return false' title="*Campo Obligatorio" name="fecvig_hasta" placeholder="dd/mm/aaaa" required='required'  maxLength="10" pattern="(0[1-9]|[12][0-9]|3[01])[/](0[1-9]|1[012])[/](19|20)\d\d">
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
                            <label class="fieldlabel" for="p1f6c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve"><a STYLE="color: red" >* </a>Monto Mensual
                                        </span></span></span></label>
                            <div class="null"></div>
                        </div>
                        <div class="control capleft">
                            <div>
                                <input type="text" id="monto_mensual" value='<? echo $datos_salario['salario_monto'] ?>' onpaste='return false' title="*Campo Obligatorio" name="monto_mensual" class="fieldcontent" placeholder="00000000.00" pattern="\d{3,11}\.?\d{0,2}" maxlength='11' required='required'  onKeyPress='return acceptNum2(event)' >** Mínimo 3 cifras
                            </div>
                            <div class="null"></div>
                        </div>
                        <div class="null"></div>
                    </div>
                    <div class="null"></div>
                </div>

                <div align="left"><a STYLE="color: red" ><br><br>* Campo obligatorio</a></div>

                <div class="null"></div>
                <center> <input id="registrarBoton" type="submit" class="navbtn"  value="Actualizar Registro" onClick='return confirmarEnvio();'></center>

                <input type='hidden' name='opcion' value='actualizarSalario'>
                <input type='hidden' name='serial'  value='<? echo $datos_salario['salario_serial'] ?>'>
                <input type='hidden' name='action' value='<? echo $this->formulario; ?>'>

                </form>

                <?
            }

            function formularioSalario($rango, $salarios) {

                $this->formulario = "formSalario";

                include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/dbms.class.php");
                include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/sesion.class.php");
                include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
                ?>


                <style>                    h3{text-align: left}                </style>

                <!referencias a estilos y plugins>
                <script type="text/javascript" src="<? echo $this->configuracion["host"] . $this->configuracion["site"] . $this->configuracion["plugins"]; ?>/datepicker/js/datepicker.js"></script>
                <link	href="<? echo $this->configuracion["host"] . $this->configuracion["site"] . $this->configuracion["bloques"] ?>/nomina/cuotas_partes/formSalario/form_estilo.css"	rel="stylesheet" type="text/css" />
                <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
                <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
                <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>

                <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
                <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
                <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
                <link rel="stylesheet" href="/resources/demos/style.css" />

                <script>
                    function acceptNum(e) {
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
                        letras = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-";
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
                    $(document).ready(function() {
                        $("#fecvig_desde").datepicker({
                            changeMonth: true,
                            changeYear: true,
                            yearRange: '1940:c',
                            dateFormat: 'dd/mm/yy',
                            maxDate: "+0D",
                        });

                        $("#fecvig_hasta").datepicker({
                            changeMonth: true,
                            changeYear: true,
                            yearRange: '1940:c',
                            dateFormat: 'dd/mm/yy',
                            maxDate: "+1M"
                        });

                        $("#fec_reso").datepicker({
                            changeMonth: true,
                            changeYear: true,
                            yearRange: '1940:c',
                            dateFormat: 'dd/mm/yy',
                            maxDate: "+0D"
                        });
                    });</script>

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
                            return false;
                        }

                        if (parseInt(y1) !== parseInt(y2)) {
                            document.getElementById("fecvig_desde").focus();
                            document.getElementById("fecvig_hasta").focus();
                            alert("Los Años de Fecha de Vigencia no pertenecen al mismo año.");
                            return false;
                        }
                        return true;
                    }
                </script>

                <script language = "Javascript">
                    //Éste script valida si las fechas ingresadas en el formulario estan entre otras historias laborales
                    function echeck(str) {
        <?
        foreach ($rango as $key => $values) {
            /* echo "var min = new Date('" . $rango[$key]['inicio'] . "');\n";
              echo "var max = new Date('" . $rango[$key]['fin'] . "');    \n"; */

            $i_fecha_anio = date('Y', (strtotime(str_replace('/', '-', $rango[$key]['inicio']))));
            $i_fecha_mes = date('m', (strtotime(str_replace('/', '-', $rango[$key]['inicio']))));
            $i_fecha_dia = date('d', (strtotime("" . str_replace('/', '-', $rango[$key]['inicio']))));

            $f_fecha_anio = date('Y', (strtotime(str_replace('/', '-', $rango[$key]['fin']))));
            $f_fecha_mes = date('m', (strtotime(str_replace('/', '-', $rango[$key]['fin']))));
            $f_fecha_dia = date('d', (strtotime("" . str_replace('/', '-', $rango[$key]['fin']))));

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

                    function minDate() {

                        var fechaID = document.formSalario.fecvig_desde;
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

                        var fechaID = document.formSalario.fecvig_hasta;
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


                <form id="form" method="post" action="index.php" name='<?php echo $this->formulario; ?>'  autocomplete='Off'  onsubmit="return minDate()">
                    <h1>Salario Mínimo Legal</h1> 
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
                                <div class="staticcontrol"><span class="wordwrap"><span class="pspan arial" style="text-align: left; font-size:14px;"><span class="ispan" style="color:#000099" xml:space="preserve">DETALLES DEL REGISTRO<span class="ispan" style="color:#EE3D23" xml:space="preserve"> </span></span></span></div>
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
                                        <select id="p1f13c" name="norma" required='required' class="fieldcontent"><option value="Ley" selected="selected">Ley</option><option value="Decreto">Decreto</option></select>
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
                                    <label class="fieldlabel" for="p1f6c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve"><a STYLE="color: red" >* </a>Número Norma</span></span></span></label>
                                    <div class="null"></div>
                                </div>
                                <div class="control capleft">
                                    <div>
                                        <input type="text" id="numero" onpaste='return false' title="*Campo Obligatorio" name="numero"  maxlength="7" class="fieldcontent" required='required' autocomplete="off" onKeyPress='return acceptNum(event)'>
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
                                    <label class="fieldlabel" for="año_registrar"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF"><a STYLE="color: red" >* </a>Año a Registrar</span></span></span></label>
                                    <div class="null"></div>
                                </div>
                                <div class="control capleft">
                                    <div>
                                        <select id="año_registrar" name="año_registrar" autocomplete="off" onchange="validaranio()" required='required'>

                                            <?php
                                            $var = "<option selected value=''>" . "Seleccione Año" . "</option>";

                                            $anio = date("Y");
                                            $anio_inicial = 1950;

                                            do {
                                                $var.= "<option>" . $anio_inicial . "</option>";
                                                $anio_inicial = $anio_inicial + 1;
                                            } while ($anio_inicial <= $anio);

                                            echo $var;
                                            ?>   

                                        </select>

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
                                    <label class="fieldlabel" for="fecvig_desde"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" ><a STYLE="color: red" >* </a>Fecha Vigencia<br>  Desde</span></span></span></label>
                                    <div class="null"></div>
                                </div>
                                <div class="control capleft">
                                    <div>
                                        <input type="text" id="fecvig_desde" onpaste='return false' title="*Campo Obligatorio" name="fecvig_desde" placeholder="dd/mm/aaaa" required='required'  maxLength="10"  pattern="(0[1-9]|[12][0-9]|3[01])[/](0[1-9]|1[012])[/](19|20)\d\d" >
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
                                    <label class="fieldlabel" for="fecvig_hasta"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" ><a STYLE="color: red" >* </a>Fecha Vigencia<br>  Hasta</span></span></span></label>
                                    <div class="null"></div>
                                </div>
                                <div class="control capleft">
                                    <div>
                                        <input type="text" id="fecvig_hasta" onchange="validarFecha()" onpaste='return false' title="*Campo Obligatorio" name="fecvig_hasta" placeholder="dd/mm/aaaa" required='required'  maxLength="10" pattern="(0[1-9]|[12][0-9]|3[01])[/](0[1-9]|1[012])[/](19|20)\d\d">
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
                                    <label class="fieldlabel" for="p1f6c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve"><a STYLE="color: red" >* </a>Monto Mensual
                                                </span></span></span></label>
                                    <div class="null"></div>
                                </div>
                                <div class="control capleft">
                                    <div>
                                        <input type="text" id="sum_fj" onpaste='return false' title="*Campo Obligatorio" name="monto_mensual" class="fieldcontent" placeholder="00000000.00" pattern="\d{3,11}\.?\d{0,2}" maxlength='11' required='required'  onKeyPress='return acceptNum2(event)' >** Mínimo 3 cifras
                                    </div>
                                    <div class="null"></div>
                                </div>
                                <div class="null"></div>
                            </div>
                            <div class="null"></div>
                        </div>

                        <div align="left"><a STYLE="color: red" ><br><br>* Campo obligatorio</a></div>

                        <div class="null"></div>
                        <center> <input id="registrarBoton" type="submit" class="navbtn"  value="Registrar" onClick='return confirmarEnvio();'></center>

                        <input type='hidden' name='opcion' value='insertarSalario'>
                        <input type='hidden' name='action' value='<? echo $this->formulario; ?>'>

                        </form>

                        <?
                    }

                }
                