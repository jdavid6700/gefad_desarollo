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

class html_formIPC {

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

    function tablaIPC($datos) {
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
                    perPage: 5,
                    delay: 20
                });
            });</script>

        <h1>Formulario de Registro Indice Precios Consumidor (IPC)</h1> 

        <center>     
            <center><div class="holder"></div></center>
            <table width="35%" class='bordered' >
                <tr>
                    <th colspan="11" class='encabezado_registro'>TABLA INDICES (IPC)</th>
                    <td class='texto_elegante<? echo '' ?> estilo_td' ></td>
                </tr>
                <tr>
                    <th class='encabezado_registro' style="text-align:center">Fecha</th>
                    <th class='encabezado_registro' style="text-align:center">Indice</th>
                    <th class='encabezado_registro' style="text-align:center">Sumas Fijas</th>
                </tr>
                <tbody id="itemContainer">
                    <tr>
                        <?php
                        if (is_array($datos))
                            foreach ($datos as $key => $values) {
                                echo "<tr>";
                                echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $datos[$key][0] . "</td>";
                                echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $datos[$key][1] . "</td>";
                                echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $datos[$key][2] . "</td>";
                                echo "</tr>";
                            } else {
                            echo "<tr>";
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

    function formularioIPC() {

        $mes = date('m');
        $anio = date('Y');
        $this->formulario = "formIPC";

        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/dbms.class.php");
        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/sesion.class.php");
        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
        ?>


        <style>                    h3{text-align: left}                </style>

        <!referencias a estilos y plugins>
        <script type="text/javascript" src="<? echo $this->configuracion["host"] . $this->configuracion["site"] . $this->configuracion["plugins"]; ?>/datepicker/js/datepicker.js"></script>
        <link	href="<? echo $this->configuracion["host"] . $this->configuracion["site"] . $this->configuracion["bloques"] ?>/nomina/cuotas_partes/formIPC/form_estilo.css"	rel="stylesheet" type="text/css" />
        <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
        <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>

        <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
        <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
        <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
        <link rel="stylesheet" href="/resources/demos/style.css" />

        <script>
            $(function() {
                $(".p1f6c").tooltip("Ingresar indice en decimales.");
            });</script>

        <script>
            function acceptNum(e) {
                key = e.keyCode || e.which;
                tecla = String.fromCharCode(key).toLowerCase();
                letras = "01234567890.";
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

            function  validaranio() {
                var anio = document.getElementById("año_registrar").value;
                
                if (anio < 1976) {
                    document.getElementById("sum_fj").disabled = true;
                } else {
                    document.getElementById("sum_fj").disabled = false;
                    if (anio > 1988) {
                        document.getElementById("sum_fj").disabled = true;
                    }
                }
            }
        </script>

        <script>
            function mensaje() {
                document.write('Ingreso del indice en numeros decimales.');
            }
        </script>

        <form id="form" method="post" action="index.php" name='<?php echo $this->formulario; ?>'  autocomplete='Off'>
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
                                    for ($i = 1940; $i <= $año; $i++) {

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
                            <label class="fieldlabel" for="p1f6c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve">Indice   (IPC)<a STYLE="color: red" >*</a></span></span></span></label>
                            <div class="null"></div>
                        </div>
                        <div class="control capleft">
                            <div>
                                <input type="text" id="indice_Ipc" name="indice_Ipc" class="fieldcontent" required='required' title="Ingrese indice en numeros decimales." maxlength='5'  autocomplete="off" onKeyPress='return acceptNum(event)' value='0.' >

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
                            <label class="fieldlabel" for="p1f6c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve">Sumas Fijas<a STYLE="color: red" >*</a></span></span></span></label>
                            <div class="null"></div>
                        </div>
                        <div class="control capleft">
                            <div>
                                <input type="text" id="sum_fj" name="sum_fj" class="fieldcontent" maxlength='6' required='required'  onKeyPress='return acceptNum(event)'  > **Solo se debe diligenciar entre los años 1976 y 1988

                            </div>
                            <div class="null"></div>
                        </div>
                        <div class="null"></div>
                    </div>
                    <div class="null"></div>
                </div>

                <div class="null"></div>
                <center> <input id="registrarBoton" type="submit" class="navbtn"  value="Registrar"></center>

                <input type='hidden' name='opcion' value='insertarIPC'>
                <input type='hidden' name='action' value='<? echo $this->formulario; ?>'>

                </form>

                <?
            }

        }

        