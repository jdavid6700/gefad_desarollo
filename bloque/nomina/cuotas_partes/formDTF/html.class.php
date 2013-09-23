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
        <h1>Formulario de Registro Indice Precios Consumidor (DTF)</h1> 

        <center>     
            <table width="35%" class='bordered' align="center">
                <tr>
                    <th colspan="11" class='encabezado_registro'>TABLA DTF (DTF)</th>
                    <td class='texto_elegante<? echo '' ?> estilo_td' ></td>
                </tr>

                <tr>                       
                    <th class='encabezado_registro' style="text-align:center">Periodo</th>
                    <th class='encabezado_registro' style="text-align:center">N° Resolución</th>
                    <th class='encabezado_registro' style="text-align:center">Fecha Resolución</th>
                    <th class='encabezado_registro' style="text-align:center">Fecha Vigencia Inicio</th>
                    <th class='encabezado_registro' style="text-align:center">Fecha Vigencia Hasta</th>
                    <th class='encabezado_registro' style="text-align:center">Tasa Interes(DTF)</th>
                </tr>

                <?php
                if (is_array($datos))
                    foreach ($datos as $key => $values) {
                        echo "<tr>";
                        echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $datos[$key][0] . "</td>";
                        echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $datos[$key][1] . "</td>";
                        echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $datos[$key][2] . "</td>";
                        echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $datos[$key][3] . "</td>";
                        echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $datos[$key][4] . "</td>";
                        echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $datos[$key][5] . "</td>";
                        echo "</tr>";
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
            <BR><BR>
        </center>

        <?php
    }

    function formularioDTF() {

        $mes = date('m');
        $anio = date('Y');
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
                    },
                    maxDate: "+0D",
                    onSelect: function(dateValue, inst) {
                        $("#fecvig_hasta").datepicker("option", "minDate", dateValue)
                    }
                });

                $("#fecvig_hasta").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    yearRange: '1940:c',
                    dateFormat: 'dd/mm/yy',
                    maxDate: "+0D",
                    onChangeMonthYear: function(year, month, inst) {
                        $('#' + inst.id).datepicker("setDate", month + '/1/' + year);
                    }
                });

                $("#fec_reso").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    yearRange: '1940:c',
                    dateFormat: 'dd/mm/yy',
                    maxDate:"+0D",
                    onChangeMonthYear: function(year, month, inst) {
                        $('#' + inst.id).datepicker("setDate", month + '/1/' + year);
                    }

                });

            });</script>

        <script>
            function acceptNum(e) {
                key = e.keyCode || e.which;
                tecla = String.fromCharCode(key).toLowerCase();
                letras = "01234567890.";
                especiales = [8, 39, 9, 37];
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
                    document.getElementById("tri_mestre").value = "Seleccione Trimestre";
                    document.getElementById("tri_mestre").disabled = true;
                } else {
                    document.getElementById("indice_dtf").value = "";
                    document.getElementById("indice_dtf").disabled = false;
                    document.getElementById("fecvig_hasta").disabled = false;
                    document.getElementById("fecvig_desde").disabled = false;
                    document.getElementById("n_resolucion").disabled = false;
                    document.getElementById("fec_reso").disabled = false;
                    document.getElementById("tri_mestre").value = "Seleccione Trimestre";
                    document.getElementById("tri_mestre").disabled = false;
                }
            }
        </script>

        <script>
            function  validarperiodo() {
                var anio = document.getElementById("año_registrar").value;
                var periodo = document.getElementById("tri_mestre").value;
                if (anio == 2006) {
                    if (periodo < 3) {
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
            }

        </script>

        <form id="form" method="post" action="index.php" name='<?php echo $this->formulario; ?>' autocomplete='Off'>
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
                        <div class="staticcontrol"><span class="wordwrap"><span class="pspan arial" style="text-align: left; font-size:14px;"><span class="ispan" style="color:#000099" xml:space="preserve">REGISTRO DE INDICES<span class="ispan" style="color:#EE3D23" xml:space="preserve"> </span></span></span></div>
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
                                <select id="año_registrar" name="año_registrar" autocomplete="off" onchange="validaranio()" >
                                    <?php
                                    $var = "<option selected>" . "Seleccione Año" . "</option>";
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
                            <label class="fieldlabel" for="tri_mestre"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve">Trimestre a Registrar<a STYLE="color: red" >*</a></span></span></span></label>
                            <div class="null"></div>
                        </div>
                        <div class="control capleft">
                            <div>
                                <select id="tri_mestre" name="tri_mestre"  autocomplete="off" onchange="validarperiodo()" >
                                    <?php
                                    $var = "<option selected>" . "Seleccione Trimestre" . "</option>";
                                    ;

                                    for ($i = 1; $i <= 4; $i++) {

                                        $var.= "<option>" . $i . "</option>";
                                    }
                                    echo $var;
                                    ?>   

                                </select>**Se debe seleccionar  primero el año.
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
                                <input type="text" id="n_resolucion" name="n_resolucion" class="fieldcontent" required='required'  maxLength="10"  autocomplete="off" onKeyPress='return acceptNum(event)'>
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
                                <input type="text" id="fec_reso" name="fec_reso" required='required' readonly>
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
                                <input type="text" id="fecvig_desde" name="fecvig_desde" required='required' readonly>
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
                                <input type="text" id="fecvig_hasta" name="fecvig_hasta" required='required' readonly>
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
                                <input type="text" id="indice_dtf" name="indice_dtf" title="Ingrese indice en numeros decimales." class="fieldcontent"  maxlength='6'required='required'  autocomplete="off" onKeyPress='return acceptNum(event)' value="0." >&nbsp;*Ingrese formato decimal. Ejemplo: 0.25  
                            </div>
                            <div class="null"></div>
                        </div>
                        <div class="null"></div>
                  
                    </div>
                    <div class="null"></div>
                </div>


                <div class="null"></div>
                <center> <input id="registrarBoton" type="submit" class="navbtn"  value="Registrar"></center>

                <input type='hidden' name='opcion' value='insertarDTF'>
                <input type='hidden' name='action' value='<? echo $this->formulario; ?>'>

                </form>


                <?
            }

        }

        