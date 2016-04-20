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
  | 01/11/2013 | Violet Sosa             | 0.0.0.3     |                                 |
  ---------------------------------------------------------------------------------------
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
                    perPage: 4,
                    delay: 20
                });
            });</script>

        <center>     
            <h2><br><br>Reporte Índices de Precios al Consumidor Registrados<br><br></h2>

            <table width="90%" class='bordered' >
                <tr>
                    <th colspan="4" class='encabezado_registro'>INDICES (IPC)</th>
                    <td class='texto_elegante<? echo '' ?> estilo_td' ></td>
                </tr>
                <tr>
                    <th class='encabezado_registro' style="text-align:center">Fecha</th>
                    <th class='encabezado_registro' style="text-align:center">Indice</th>
                    <th class='encabezado_registro' style="text-align:center">Sumas Fijas</th>
                    <th class='encabezado_registro' style="text-align:center">Modificar</th>
                </tr>
                <tbody id="itemContainer">
                    <tr>
                        <?php
                        if (is_array($datos)) {
                            foreach ($datos as $key => $values) {
                                echo "<tr>";
                                echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $datos[$key][0] . "</td>";
                                echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $datos[$key][1] . "</td>";
                                echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . $datos[$key][2] . "</td>";
                                echo "<td class='texto_elegante estilo_td' style='text-align:center;'>"
                                . " <a href='";
                                $variable = 'pagina=formularioIPC';
                                $variable.='&opcion=modificarIPC';
                                $variable.='&datos_ipc=' . serialize($datos[$key]);
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
                            echo "</tr>";
                        }
                        ?>
            </table>
            <center><div class="holder" style="-moz-user-select: none;"></div></center>
        </center>
        <?php
    }

    function formularioIPC($anio_fin) {

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
            function acceptNum(e) {
                key = e.keyCode || e.which;
                tecla = String.fromCharCode(key).toLowerCase();
                letras = "01234567890.-";
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


        <form id="form" method="post" action="index.php" name='<?php echo $this->formulario; ?>'  autocomplete='Off'>

            <h1>Índice de Precios al Consumidor (IPC)</h1> 
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

                                    $año = date("Y");
                                    for ($i = 1950; $i <= $año; $i++) {
                                        switch ($i) {
                                            case "1984":
                                                $var.= "<option>" . $i . "-1</option>";
                                                $var.= "<option>" . $i . "-2</option>";
                                                $var.= "<option>" . $i . "-3</option>";
                                                break;
                                            case "1985":
                                                $var.= "<option>" . $i . "-1</option>";
                                                $var.= "<option>" . $i . "-2</option>";
                                                $var.= "<option>" . $i . "-3</option>";
                                                break;
                                            case "1986":
                                                $var.= "<option>" . $i . "-1</option>";
                                                $var.= "<option>" . $i . "-2</option>";
                                                $var.= "<option>" . $i . "-3</option>";
                                                break;
                                            case "1987":
                                                $var.= "<option>" . $i . "-1</option>";
                                                $var.= "<option>" . $i . "-2</option>";
                                                $var.= "<option>" . $i . "-3</option>";
                                                break;
                                            case "1988":
                                                $var.= "<option>" . $i . "-1</option>";
                                                $var.= "<option>" . $i . "-2</option>";
                                                $var.= "<option>" . $i . "-3</option>";
                                                break;
                                            default:
                                                $var.= "<option>" . $i . "</option>";
                                                break;
                                        }
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
                            <label class="fieldlabel" for="p1f6c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve"><a STYLE="color: red" >* </a>Indice (IPC)</span></span></span></label>
                            <div class="null"></div>
                        </div>
                        <div class="control capleft">
                            <div>
                                <input type="text" id="indice_Ipc" onpaste="return false"  title="*Campo Obligatorio" name="indice_Ipc" placeholder="0.0000"  step="0.0000"  pattern="^[0]{1}(\.[0-9]{1,9})?$" maxlength="11" class="fieldcontent" required='required' onpaste="return false" autocomplete="off" onKeyPress='return acceptNum(event)' >Ejemplo: 0.2222
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
                            <label class="fieldlabel" for="p1f6c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve"><a STYLE="color: red" >* </a>Sumas Fijas</span></span></span></label>
                            <div class="null"></div>
                        </div>
                        <div class="control capleft">
                            <div>
                                <input type="text" id="sum_fj" onpaste="return false" title="*Campo Obligatorio en el caso correspondiente." placeholder="00000000.00" name="sum_fj" pattern="\d{3,11}\.?\d{0,2}" class="fieldcontent" maxlength='11' required='required'  onKeyPress='return acceptNum(event)' onpaste="return false" > **Diligenciar entre los años 1976 y 1988 <br> **Mínimo 3 cifras decimales
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

                <input type='hidden' name='opcion' value='insertarIPC'>
                <input type='hidden' name='action' value='<? echo $this->formulario; ?>'>

                </form>

                <?
            }

            function formularioModificar_IPC($anio_fin, $datos_ipc) {

                $estado = '';

                $anio = (int) $datos_ipc['ipc_fecha'];
                if ($anio < 1976) {
                    $estado = 'disabled="true"';
                } elseif ($anio > 1988) {
                    $estado = 'disabled="true"';
                }

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
                    function acceptNum(e) {
                        key = e.keyCode || e.which;
                        tecla = String.fromCharCode(key).toLowerCase();
                        letras = "01234567890.-";
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


                <form id="form" method="post" action="index.php" name='<?php echo $this->formulario; ?>'  autocomplete='Off' onload="return validaranio();">

                    <h1>Índice de Precios al Consumidor (IPC)</h1> 
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

                        <div class="formrow f1 ">
                            <div id="p1f10" class="field n1">
                                <div class="caption capleft alignleft">
                                    <label class="fieldlabel" for="año_registrar"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF"><a STYLE="color: red" >* </a>Año a Registrar</span></span></span></label>
                                    <div class="null"></div>
                                </div>
                                <div class="control capleft">
                                    <div>
                                        <input type="text" id="año_registrar" value='<? echo $datos_ipc['ipc_fecha'] ?>' readonly name="año_registrar" autocomplete="off" required='required' title="*Campo Obligatorio" class="fieldcontent">
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
                                    <label class="fieldlabel" for="p1f6c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve"><a STYLE="color: red" >* </a>Indice (IPC)</span></span></span></label>
                                    <div class="null"></div>
                                </div>
                                <div class="control capleft">
                                    <div>
                                        <input type="text" id="indice_Ipc" onpaste="return false"  value='<? echo$datos_ipc['ipc_indiceipc'] ?>' title="*Campo Obligatorio" name="indice_Ipc" placeholder="0.0000"  step="0.0000"  pattern="^[0]{1}(\.[0-9]{1,9})?$" maxlength="11" class="fieldcontent" required='required' onpaste="return false" autocomplete="off" onKeyPress='return acceptNum(event)' >Ejemplo: 0.2222
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
                                    <label class="fieldlabel" for="p1f6c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve"><a STYLE="color: red" >* </a>Sumas Fijas</span></span></span></label>
                                    <div class="null"></div>
                                </div>
                                <div class="control capleft">
                                    <div>
                                        <input type="text" id="sum_fj" <? echo $estado ?> onpaste="return false" value='<? echo$datos_ipc['ipc_sumas_fijas'] ?>' title="*Campo Obligatorio en el caso correspondiente." placeholder="00000000.00" name="sum_fj" pattern="\d{3,11}\.?\d{0,2}" class="fieldcontent" maxlength='11' required='required'  onKeyPress='return acceptNum(event)' onpaste="return false" > **Diligenciar entre los años 1976 y 1988 <br> **Mínimo 3 cifras decimales
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

                        <input type='hidden' name='opcion' value='actualizarIPC'>
                        <input type='hidden' name='serial' value='<? echo $datos_ipc['ipc_serial'] ?>'>
                        <input type='hidden' name='action' value='<? echo $this->formulario; ?>'>

                        </form>

                        <?
                    }

                }
                