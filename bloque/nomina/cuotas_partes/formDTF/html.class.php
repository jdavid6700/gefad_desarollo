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
            <center><div class="holder"></div></center>
            <table width="90%" class='bordered' align="center">
                <tr>
                    <th colspan="11" class='encabezado_registro'>TABLA TASA DE INTERÉS</th>
                    <td class='texto_elegante estilo_td' ></td>
                </tr>

                <tr>                       
                    <th class='encabezado_registro' style="text-align:center">Periodo</th>
                    <th class='encabezado_registro' style="text-align:center">N° Resolución</th>
                    <th class='encabezado_registro' style="text-align:center">Fecha Resolución</th>
                    <th class='encabezado_registro' style="text-align:center">Fecha Vigencia Inicio</th>
                    <th class='encabezado_registro' style="text-align:center">Fecha Vigencia Hasta</th>
                    <th class='encabezado_registro' style="text-align:center">Tasa Interes(DTF)</th>
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
                        echo "</tr>";
                    }
                    ?>

            </table>

            <center><div class="holder"></div></center>
            <BR><BR>
        </center>

        <?php
    }

    function formularioDTF() {

        $this->formulario = "formDTF";

        
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
                    dateFormat: 'dd/mm/yy',
                    onChangeMonthYear: function(year, month, inst) {
                        $('#' + inst.id).datepicker("setDate", month + '/1/' + year);
                    }
                 
                });

                $("#fecvig_hasta").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    yearRange: '1940:c',
                    dateFormat: 'dd/mm/yy',
               
                });

                $("#fec_reso").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    yearRange: '1940:c',
                    dateFormat: 'dd/mm/yy',
                    maxDate: "+0D",
                    onSelect: function(dateValue, inst) {
                        $("#fecvig_desde").datepicker("option", "minDate", dateValue)
                    },
                    onChangeMonthYear: function(year, month, inst) {
                        $('#' + inst.id).datepicker("setDate", month + '/1/' + year)
                    }
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
                var r = confirm("¿Revisó bien el formulario? Si es así, Aceptar. Si desea corregir, Cancelar");
                if (r == true) {
                    return true;
                } else {
                    return false;
                }
            }
        </script>

        <form id="form" method="post" action="index.php" name='<?php echo $this->formulario; ?>' autocomplete='Off'>
            <h1>Formulario de Registro de Tasa de Interés</h1> 

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
                        <div class="staticcontrol"><span class="wordwrap"><span class="pspan arial" style="text-align: left; font-size:14px;"><span class="ispan" style="color:#000099" xml:space="preserve">REGISTRO DE TASA<span class="ispan" style="color:#EE3D23" xml:space="preserve"> </span></span></span></div>
                        <div class="null"></div>
                    </div>
                    <div class="null"></div>
                </div>


                <div class="formrow f1 ">
                    <div id="p1f10" class="field n1">
                        <div class="caption capleft alignleft">
                            <label class="fieldlabel" for="año_registrar"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF">Año a Registrar<a STYLE="color: red" >*</a></span></span></span></label>
                            <div class="null"></div>
                        </div>
                        <div class="control capleft">
                            <div>
                                <select id="año_registrar" name="año_registrar" autocomplete="off" onchange="validaranio()" required='required'>

                                    <?php
                                    $var = "<option selected value=''>" . "Seleccione Año" . "</option>";
                                    $i = 1920;
                                    $año = date("Y");
                                    for ($i = 1980; $i <= $año; $i++) {
                                        $var.= "<option>" . $i . "</option>";
                                    }
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

                <div class="formrow f1">
                    <div id="p1f6" class="field n1">
                        <div class="caption capleft alignleft">
                            <label class="fieldlabel" for="n_resolucion"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve">N° de la  Resolución <a STYLE="color: red" >*</a></span></span></span></label>*Superintendencia<br> Financiera
                            <div class="null"></div>
                        </div>
                        <div class="control capleft">
                            <div>
                                <input type="text" id="n_resolucion" name="n_resolucion" class="fieldcontent" required='required'  maxLength="10" pattern=".{2,10}"  autocomplete="off" onKeyPress='return acceptNum2(event)' onpaste="return false">
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
                            <label class="fieldlabel" for="fec_reso"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" >Fecha Resolución <a STYLE="color: red" >*</a></span></span></span></label>
                            <div class="null"></div>
                        </div>
                        <div class="control capleft">
                            <div>
                                <input type="text" id="fec_reso" name="fec_reso" placeholder="dd/mm/aaaa" required='required'  maxLength="10"  pattern="(0[1-9]|[12][0-9]|3[01])[/](0[1-9]|1[012])[/](19|20)\d\d" onpaste="return false">
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
                            <label class="fieldlabel" for="fecvig_desde"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" >Fecha Vigencia<br>Inicio<a STYLE="color: red" >*</a> </span></span></span></label>
                            <div class="null"></div>
                        </div>
                        <div class="control capleft">
                            <div>
                                <input type="text" id="fecvig_desde" name="fecvig_desde" placeholder="dd/mm/aaaa" required='required'  maxLength="10"  pattern="(0[1-9]|[12][0-9]|3[01])[/](0[1-9]|1[012])[/](19|20)\d\d" onpaste="return false" >
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
                            <label class="fieldlabel" for="fecvig_hasta"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" >Fecha Vigencia<br>Hasta<a STYLE="color: red" >*</a> </span></span></span></label>
                            <div class="null"></div>
                        </div>
                        <div class="control capleft">
                            <div>
                                <input type="text" id="fecvig_hasta" name="fecvig_hasta" placeholder="dd/mm/aaaa" required='required'  maxLength="10" pattern="(0[1-9]|[12][0-9]|3[01])[/](0[1-9]|1[012])[/](19|20)\d\d" onpaste="return false">
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
                            <label class="fieldlabel" for="indice_dtf"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve">Tasa de Interés<br>(DTF)<a STYLE="color: red" >*</a></span></span></span></label>
                            <div class="null"></div>
                        </div>
                        <div class="control capleft">
                            <div>
                                <input type="text" id="indice_dtf" name="indice_dtf"  placeholder="0.0000"  maxlength="6" pattern="[0]+([\.|,][0-9]+[0-9])?" onpaste="return false" step="0.0000" title="Ingrese indice en numeros decimales." class="fieldcontent"  required='required'  onKeyPress='return acceptNum(event)' >&nbsp;*Ingrese formato decimal. Ejemplo: 0.25  
                            </div>
                            <div class="null"></div>
                        </div>
                        <div class="null"></div>

                    </div>
                    <div class="null"></div>
                </div>


                <div class="null"></div>
                <center> <input id="registrarBoton" type="submit" class="navbtn"  value="Registrar"  onClick='return confirmarEnvio();'></center>

                <input type='hidden' name='opcion' value='insertarDTF'>
                <input type='hidden' name='action' value='<? echo $this->formulario; ?>'>

                </form>


                <?
            }

        }
        