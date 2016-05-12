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

    function form_valor() {

        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/dbms.class.php");
        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/sesion.class.php");
        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
        $this->formulario = "formConcurrencia";
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

            <h2>Ingrese la cédula para realizar <br> Registro de Concurrencia Aceptada: </h2><br><br>

            <input type="text" name="cedula_emp" required='required' onKeyPress='return acceptNum(event)' title="*Campo Obligatorio">
            <br><br>
            <center> <input id="registrarBoton" type="submit" class="navbtn"  value="Consultar" ></center>

            <input type='hidden' name='pagina' value='formularioConcurrencia'>
            <input type='hidden' name='opcion' value='historiaConcurrencia'>
            <br><br><br>
            <h3><a STYLE="color: red" >* ¡Atención! La CONCURRENCIA registrada para una Entidad sólo puede ser diligenciada UNA VEZ</a></h3>
            <br>
        </form>
        <?
    }

    function datosPrevisora($cedula, $datos_en) {

        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/dbms.class.php");
        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/sesion.class.php");
        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");

        $this->formulario = "formRecaudo";
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
        <!referencias a estilos y plugins>
        <link href = "<? echo $this->configuracion["host"] . $this->configuracion["site"] . $this->configuracion["bloques"] ?>/nomina/cuotas_partes/cuentaCobro/cuentaC.css" rel = "stylesheet" type = "text/css" />
        <link href = "http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel = "stylesheet" type = "text/css"/>
        <script type = "text/javascript" src = "http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
        <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>

        <form id="form" method="post" action='index.php' name='<? echo $this->formulario; ?>' autocomplete='off'>
            <h2>Seleccione Entidad Previsora:</h2>
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
            </div>

            <div class="formrow f1">
                <div id="p1f7" class="field n1">
                    <div class="caption capleft alignleft">
                        <label class="fieldlabel" for="p1f7c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve">Cédula Empleado</span></span></span></label>
                        <div class="null"></div>
                    </div>
                    <div class="control capleft">
                        <div>
                            <input type="text" onpaste="return false" id="p1f7c" title="*Campo Obligatorio" name="cedula_emp" readonly class="fieldcontent" required='required'  onKeyPress='return acceptNum(event)' value="<?php echo $cedula ?>">
                        </div>
                        <div class="null"></div>
                    </div>
                    <div class="null"></div>
                </div>
                <div class="null"></div>
            </div>

            <div id="p1f103" class="field n1">
                <div class="caption capleft alignleft">
                    <label class="fieldlabel" for="entidades"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" >Entidad a cobrar:</span></span></span></label>
                    <div class="null"></div>
                </div>
            </div>

            <div class="control capleft">

                <div class="dropdown" required='required' title="*Campo Obligatorio" required='required'>

                    <select name='prev_nit' required>

                        <?
                        foreach ($datos_en as $key => $value) {
                            ?>
                            <option id='prev_nit' name='prev_nit' value ="<?php echo $datos_en[$key]['prev_nit']; ?>"><?php echo$datos_en[$key]['prev_nombre']; ?></option>
                            <?
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div>

                <br><br><br>
                <input id="generarBoton" type="submit" class="navbtn"  value="Registrar">
                <input type='hidden' name='pagina' value='formularioConcurrencia'>
                <input type='hidden' name='opcion' value='formulario'>
            </div>
        </form>

        <?
    }

    function formularioConcurrencia($datos_historia, $datos_empleador, $datos_previsora, $datos_concurrencia) {

        $minDate = date('d/m/Y', strtotime("" . $datos_historia[0]['hlab_fingreso'] . "+1 day"));
        $maxDate = date('d/m/Y', strtotime("" . $datos_historia[0]['hlab_fretiro'] . " + 1 day"));

        $f_fecha_anio = date('Y', (strtotime(str_replace('/', '-', $datos_historia[0]['hlab_fretiro']))));
        $f_fecha_dia = date('d', (strtotime(str_replace('/', '-', $datos_historia[0]['hlab_fretiro']) . "+1 day")));
        $f_fecha_mes = date('m', (strtotime("" . str_replace('/', '-', $datos_historia[0]['hlab_fretiro']))));

        if ($datos_concurrencia !== 0) {
            $valor_mesada = $datos_concurrencia[0]['dcp_valor_mesada'];
            $resol_pension = $datos_concurrencia[0]['dcp_resol_pension'];
            $resol_pension_fecha = date('d/m/Y', strtotime("" . $datos_concurrencia[0]['dcp_resol_pension_fecha']));
            $fecha_pension = $datos_concurrencia[0]['dcp_fecha_pension'];
            $fecha_concurrencia = $datos_concurrencia[0]['dcp_fecha_concurrencia'];
            $lectura = "readonly";
        } else {
            $valor_mesada = "";
            $resol_pension = "";
            $resol_pension_fecha = date('d/m/Y', strtotime("" . $datos_historia[0]['hlab_fretiro']));
            $fecha_pension = date('d/m/Y', strtotime("" . $datos_historia[0]['hlab_fretiro']));
            $fecha_concurrencia = "";
            $lectura = " ";
        }

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
                    maxDate: "+2M",
                    dateFormat: 'dd/mm/yy',
                    defaultDate: '20/12/2013'
                });
                $("#fecha_con").datepicker('setDate', '<? echo $fecha_concurrencia ?>');
                $("#fecha_con").datepicker('option', 'minDate', '<?php echo $fecha_pension ?>');

            });

            $(document).ready(function() {
                $("#fecha_acto_adm").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    yearRange: '1980:c',
                    maxDate: "+2M",
                    dateFormat: 'dd/mm/yy'
                });
                $("#fecha_acto_adm").datepicker('option', 'minDate', '<?php echo $maxDate ?>');
            });

            $(document).ready(function() {
                $("#fecha_res_pension").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    yearRange: '1940:c',
                    maxDate: "+2M",
                    dateFormat: 'dd/mm/yy'
                });
                $("#fecha_res_pension").datepicker('setDate', '<? echo $resol_pension_fecha ?>');
            });

            $(document).ready(function() {
                $("#fecha_pension").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    yearRange: '1940:c',
                    maxDate: "+2M",
                    dateFormat: 'dd/mm/yy',
                });
                $("#fecha_pension").datepicker('setDate', '<? echo $fecha_pension ?>');
            });
        </script>

        <script language = "Javascript">
            //Éste script valida si las fechas ingresadas en el formulario no son menores a la fecha de retiro de la entidad
            function echeck(str) {

                var min = new Date('<? echo $f_fecha_anio ?>,<? echo $f_fecha_mes ?>,<? echo $f_fecha_dia ?>,');
                var y = str.substring(6);
                var m3 = str.substring(3, 5);
                var m2 = m3 - 1;
                var m = '0' + m2;
                var d = str.substring(0, 2);

                var cadena = new Date(y, m, d);

                if (cadena < min) {
                    alert('Ingrese una fecha válida' + min)
                    return false
                }

                return true
            }

            function minDate() {

                var fechaID = document.formConcurrencia.fecha_con

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


                var fechaID = document.formConcurrencia.fecha_acto_adm

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


                var fechaID = document.formConcurrencia.fecha_pension

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

                var fechaID = document.formConcurrencia.fecha_res_pension

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
                var r = confirm("Confirmar envío de formulario.");
                if (r == true) {
                    return true;
                } else {
                    return false;
                }
            }
        </script>

        <form id="form" method="post" action="index.php" name='<? echo $this->formulario; ?>' onSubmit="return minDate();" autocomplete='Off'>
            <h1>Concurrencias Aceptadas</h1>

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
                        <label class="fieldlabel" for="p1f12c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve"><a STYLE="color: red" >* </a>Cédula Pensionado</span></span></span></label>
                        <div class="null"></div>
                    </div>
                    <div class="control capleft">
                        <div>
                            <input type="text" id="p1f12c" name="cedula" title="*Campo Obligatorio" class="fieldcontent" readonly required='required' value="<? echo $datos_historia[0]['hlab_nro_identificacion'] ?>" onpaste="return false">
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
                        <label class="fieldlabel" for="p1f6c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve"><a STYLE="color: red" >* </a>Nombre Empleador</span></span></span></label>
                        <div class="null"></div>
                    </div>
                    <div class="control capleft">
                        <div class="control capleft">
                            <div class="dropdown" required='required' title="*Campo Obligatorio">

                                <select name='entidad_empleadora' required>

                                    <?
                                    foreach ($datos_empleador as $key => $value) {
                                        ?>
                                        <option id='prev_nit' name='entidad_empleadora' value ="<?php echo $datos_empleador[$key]['prev_nit']; ?>"><?php echo $datos_empleador[$key]['prev_nombre']; ?></option>
                                        <?
                                    }
                                    ?>
                                </select>

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
                            <div class="dropdown" required='required' title="*Campo Obligatorio" >
                                <select name='entidad_previsora' required>

                                    <?
                                    foreach ($datos_previsora as $key => $value) {
                                        ?>
                                        <option id='prev_nit' name='entidad_previsora' value ="<?php echo $datos_previsora[$key]['prev_nit']; ?>"><?php echo $datos_previsora[$key]['prev_nombre']; ?></option>
                                        <?
                                    }
                                    ?>
                                </select>
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
                        <label class="fieldlabel" for="fecha_con"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" ><a STYLE="color: red" >* </a>Fecha Inicio <br>  Concurrencia</span></span></span></label>
                        <div class="null"></div>
                    </div>
                    <div class="control capleft">
                        <div>
                            <input type="text" id="fecha_con" title="*Campo Obligatorio" <? echo " " . $lectura . " " ?> name="fecha_concurrencia" maxlenght="10" placeholder="dd/mm/aaaa" required='required' pattern="(0[1-9]|[12][0-9]|3[01])[/](0[1-9]|1[012])[/](19|20)\d\d" onpaste="return false">
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
                        <label class="fieldlabel" for="fecha_pension"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" ><a STYLE="color: red" >* </a>Fecha Pensión</span></span></span></label>
                        <div class="null"></div>
                    </div>
                    <div class="control capleft">
                        <div>
                            <input type="text" id="fecha_pension" title="*Campo Obligatorio" <? echo " " . $lectura . " " ?>  name="fecha_pension"  maxlenght="10" placeholder="dd/mm/aaaa" required='required' pattern="(0[1-9]|[12][0-9]|3[01])[/](0[1-9]|1[012])[/](19|20)\d\d" onpaste="return false" value="<? echo $fecha_pension ?>">
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
                        <label class="fieldlabel" for="resolucion_pension"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF"><a STYLE="color: red" >* </a>Resolución Pensión</span></span></span></label>
                        <div class="null"></div>
                    </div>
                    <div class="control capleft">
                        <div>
                            <input type="text" id="res_pensión" title="*Campo Obligatorio" <? echo " " . $lectura . " " ?>  name="resolucion_pension" required='required' onKeyPress='return acceptNumLetter(event)' maxlength="12" pattern=".{1,12}." onpaste="return false" value="<? echo $resol_pension ?>">
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
                        <label class="fieldlabel" for="fecha_res_pension"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" ><a STYLE="color: red" >* </a>Fecha Resolución<br>   Pensión</span></span></span></label>
                        <div class="null"></div>
                    </div>
                    <div class="control capleft">
                        <div>
                            <input type="text" id="fecha_res_pension" title="*Campo Obligatorio" <? echo " " . $lectura . " " ?>  name="fecha_res_pension" maxlenght="10" placeholder="dd/mm/aaaa" required='required' pattern="(0[1-9]|[12][0-9]|3[01])[/](0[1-9]|1[012])[/](19|20)\d\d" onpaste="return false" value="<? echo $resol_pension_fecha ?>">
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
                        <label class="fieldlabel" for="p1f12c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve"><a STYLE="color: red" >* </a>Mesada Inicial</span></span></span></label>
                        <div class="null"></div>
                    </div>
                    <div class="control capleft">
                        <div>
                            <input type="text" id="p1f12c" name="mesada" <? echo " " . $lectura . " " ?>  title="*Campo Obligatorio" class="fieldcontent" required='required' onKeyPress='return acceptNum2(event)' maxlength="11" placeholder="00000000.00" pattern="^[0-9]\d{4,9}(\.\d{1,2})?%?$" onpaste="return false" value="<? echo $valor_mesada ?>">Mínimo 4 caracteres
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
                        <label class="fieldlabel" for="p1f6c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve"><a STYLE="color: red" >* </a>Cuota Aceptada</span></span></span></label>
                        <div class="null"></div>
                    </div>
                    <div class="control capleft">
                        <div>
                            <input type="text" id="p1f6c" name="cp_aceptada" title="*Campo Obligatorio" class="fieldcontent" required='required' onKeyPress='return acceptNum2(event)' placeholder="00000000.00" pattern="^[0-9]\d{3,9}(\.\d{1,2})?%?$" maxlength="11"  onpaste="return false">Mínimo 4 caracteres
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
                        <label class="fieldlabel" for="p1f6c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve"><a STYLE="color: red" >* </a>Porcentaje Aceptado</span></span></span></label>
                        <div class="null"></div>
                    </div>
                    <div class="control capleft">
                        <div>
                            <input type="text" id="p1f6c" name="porc_aceptado" title="*Campo Obligatorio" placeholder="0.0000" class="fieldcontent" required='required' onKeyPress='return acceptNum2(event)' maxlength="6" pattern="^[0]{1}(\.[0-9]{2,4})?$" step="0.00" onpaste="return false">Decimal en formato: 0.9999, mín. dos decimales
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
                        <label class="fieldlabel" for="p1f7c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve"><a STYLE="color: red" >* </a>Tipo Acto <br>   Administrativo</span></span></span></label>
                        <div class="null"></div>
                    </div>
                    <div class="control capleft">
                        <div>
                            <div class="dropdown"> 
                                <select id="p1f13c" name="tipo_acto" title="*Campo Obligatorio" class="fieldcontent"><option selected="selected "value="Silencio Administrativo">Silencio Administrativo</option><option value="Resolución">Resolución</option><option value="Oficio">Oficio</option></select>
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
                        <label class="fieldlabel" for="p1f7c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve"><a STYLE="color: red" >* </a>Número/Referencia<br>   Acto Administrativo</span></span></span></label>
                        <div class="null"></div>
                    </div>
                    <div class="control capleft">
                        <div>
                            <input type="text" id="p1f7c" name="acto_adm" title="*Campo Obligatorio" class="fieldcontent" onKeyPress='return acceptNumLetter(event)' maxlength="15" placeholder="Número o Referencia de Acto Administrativo" onpaste="return false">
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
                        <label class="fieldlabel" for="fecha_acto_adm"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" ><a STYLE="color: red" >* </a>Fecha Acto<br>  Administrativo</span></span></span></label>
                        <div class="null"></div>
                    </div>
                    <div class="control capleft">
                        <div>
                            <input type="text" id="fecha_acto_adm" title="*Campo Obligatorio" name="fecha_acto_adm"  maxlenght="10" placeholder="dd/mm/aaaa" required='required' pattern="(0[1-9]|[12][0-9]|3[01])[/](0[1-9]|1[012])[/](19|20)\d\d" onpaste="return false" >
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
                        <label class="fieldlabel" for="observacion"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" > Observaciones</span></label>
                                    <div class="null"></div>
                                    </div>
                                    <div class="control capleft">
                                        <div>
                                            <textarea id="observacion" rows="4" cols="50" title="*Campo Obligatorio" name="observacion"  maxlenght="10" onpaste="return false" ></textarea>
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

                                    <input type='hidden' name='opcion' value='registrarConcurrencia'>
                                    <input type='hidden' name='action' value='<? echo $this->formulario; ?>'>

                                    </form>
                                    <?
                                }

                                function formularioConcurrenciaModificar($datos_historia, $datos_empleador, $datos_previsora, $datos_concurrencia) {

                                    $minDate = date('d/m/Y', strtotime("" . $datos_historia[0]['hlab_fingreso'] . "+1 day"));
                                    $maxDate = date('d/m/Y', strtotime("" . $datos_historia[0]['hlab_fretiro'] . " + 1 day"));

                                    $f_fecha_anio = date('Y', (strtotime(str_replace('/', '-', $datos_historia[0]['hlab_fretiro']))));
                                    $f_fecha_dia = date('d', (strtotime(str_replace('/', '-', $datos_historia[0]['hlab_fretiro']) . "+1 day")));
                                    $f_fecha_mes = date('m', (strtotime("" . str_replace('/', '-', $datos_historia[0]['hlab_fretiro']))));

                                    $valor_mesada = $datos_concurrencia['dcp_valor_mesada'];
                                    $valor_cuota = $datos_concurrencia['dcp_valor_cuota'];
                                    $porcentaje = $datos_concurrencia['dcp_porcen_cuota'];
                                    $resol_pension = $datos_concurrencia['dcp_resol_pension'];
                                    $resol_pension_fecha = date('d/m/Y', strtotime("" . $datos_concurrencia['dcp_resol_pension_fecha']));
                                    $fecha_pension = date('d/m/Y', strtotime("" . $datos_concurrencia['dcp_fecha_pension']));
                                    $fecha_concurrencia = date('d/m/Y', strtotime("" . $datos_concurrencia['dcp_fecha_concurrencia']));
                                    $actoadm = $datos_concurrencia['dcp_actoadmin'];
                                    $observacion = $datos_concurrencia['dcp_observacion'];
                                    $factoadm = date('d/m/Y', strtotime("" . $datos_concurrencia['dcp_factoadmin']));
                                    //$lectura = "readonly";


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
                                                maxDate: "+2M",
                                                dateFormat: 'dd/mm/yy',
                                            });
                                            $("#fecha_con").datepicker('setDate', '<? echo $fecha_concurrencia ?>');
                                            $("#fecha_con").datepicker('option', 'minDate', '<?php echo $fecha_pension ?>');

                                        });

                                        $(document).ready(function() {
                                            $("#fecha_acto_adm").datepicker({
                                                changeMonth: true,
                                                changeYear: true,
                                                yearRange: '1980:c',
                                                maxDate: "+2M",
                                                dateFormat: 'dd/mm/yy'
                                            });
                                            $("#fecha_acto_adm").datepicker('setDate', '<? echo $factoadm ?>');
                                        });

                                        $(document).ready(function() {
                                            $("#fecha_res_pension").datepicker({
                                                changeMonth: true,
                                                changeYear: true,
                                                yearRange: '1940:c',
                                                maxDate: "+2M",
                                                dateFormat: 'dd/mm/yy'
                                            });
                                            $("#fecha_res_pension").datepicker('setDate', '<? echo $resol_pension_fecha ?>');
                                        });

                                        $(document).ready(function() {
                                            $("#fecha_pension").datepicker({
                                                changeMonth: true,
                                                changeYear: true,
                                                yearRange: '1940:c',
                                                maxDate: "+2M",
                                                dateFormat: 'dd/mm/yy',
                                            });
                                            $("#fecha_pension").datepicker('setDate', '<? echo $fecha_pension ?>');
                                        });
                                    </script>

                                    <script language = "Javascript">
                                        //Éste script valida si las fechas ingresadas en el formulario no son menores a la fecha de retiro de la entidad
                                        function echeck(str) {

                                            var min = new Date('<? echo $f_fecha_anio ?>,<? echo $f_fecha_mes ?>,<? echo $f_fecha_dia ?>,');
                                            var y = str.substring(6);
                                            var m3 = str.substring(3, 5);
                                            var m2 = m3 - 1;
                                            var m = '0' + m2;
                                            var d = str.substring(0, 2);
                                            var cadena = new Date(y, m, d);

                                            if (cadena < min) {
                                                alert('Ingrese una fecha válida ' + str)
                                                return false
                                            }

                                            return true
                                        }

                                        function minDate() {

                                            var fechaID = document.formConcurrencia.fecha_con

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


                                            var fechaID = document.formConcurrencia.fecha_acto_adm

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


                                            var fechaID = document.formConcurrencia.fecha_pension

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

                                            var fechaID = document.formConcurrencia.fecha_res_pension

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
                                            var r = confirm("Confirmar envío de formulario.");
                                            if (r == true) {
                                                return true;
                                            } else {
                                                return false;
                                            }
                                        }
                                    </script>

                                    <form id="form" method="post" action="index.php" name='<? echo $this->formulario; ?>' onSubmit="return minDate();" autocomplete='Off'>
                                        <h1>Concurrencias Aceptadas</h1>

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
                                                    <label class="fieldlabel" for="p1f12c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve"><a STYLE="color: red" >* </a>Cédula Pensionado</span></span></span></label>
                                                    <div class="null"></div>
                                                </div>
                                                <div class="control capleft">
                                                    <div>
                                                        <input type="text" id="p1f12c" name="cedula" title="*Campo Obligatorio" class="fieldcontent" readonly required='required' value="<? echo $datos_historia[0]['hlab_nro_identificacion'] ?>" onpaste="return false">
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
                                                    <label class="fieldlabel" for="p1f6c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve"><a STYLE="color: red" >* </a>Nombre Empleador</span></span></span></label>
                                                    <div class="null"></div>
                                                </div>
                                                <div class="control capleft">
                                                    <div class="control capleft">
                                                        <div class="dropdown" required='required' title="*Campo Obligatorio">

                                                            <select name='entidad_empleadora' required>

                                                                <?
                                                                foreach ($datos_empleador as $key => $value) {
                                                                    ?>
                                                                    <option id='prev_nit' name='entidad_empleadora' value ="<?php echo $datos_empleador[$key]['prev_nit']; ?>"><?php echo $datos_empleador[$key]['prev_nombre']; ?></option>
                                                                    <?
                                                                }
                                                                ?>
                                                            </select>

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
                                                        <div class="dropdown" required='required' title="*Campo Obligatorio" >
                                                            <select name='entidad_previsora' required>
                                                                <option id='prev_nit' name='entidad_previsora' value ="<?php echo $datos_previsora[0]['prev_nit']; ?>"><?php echo $datos_previsora[0]['prev_nombre']; ?></option>
                                                            </select>
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
                                                    <label class="fieldlabel" for="fecha_con"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" ><a STYLE="color: red" >* </a>Fecha Inicio <br>  Concurrencia</span></span></span></label>
                                                    <div class="null"></div>
                                                </div>
                                                <div class="control capleft">
                                                    <div>
                                                        <input type="text" id="fecha_con" title="*Campo Obligatorio" name="fecha_concurrencia" maxlenght="10" placeholder="dd/mm/aaaa" required='required' pattern="(0[1-9]|[12][0-9]|3[01])[/](0[1-9]|1[012])[/](19|20)\d\d" onpaste="return false">
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
                                                    <label class="fieldlabel" for="fecha_pension"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" ><a STYLE="color: red" >* </a>Fecha Pensión</span></span></span></label>
                                                    <div class="null"></div>
                                                </div>
                                                <div class="control capleft">
                                                    <div>
                                                        <input type="text" id="fecha_pension" title="*Campo Obligatorio"  name="fecha_pension"  maxlenght="10" placeholder="dd/mm/aaaa" required='required' pattern="(0[1-9]|[12][0-9]|3[01])[/](0[1-9]|1[012])[/](19|20)\d\d" onpaste="return false" value="<? echo $fecha_pension ?>">
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
                                                    <label class="fieldlabel" for="resolucion_pension"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF"><a STYLE="color: red" >* </a>Resolución Pensión</span></span></span></label>
                                                    <div class="null"></div>
                                                </div>
                                                <div class="control capleft">
                                                    <div>
                                                        <input type="text" id="res_pensión" title="*Campo Obligatorio" value="<? echo $resol_pension ?>"   name="resolucion_pension" required='required' onKeyPress='return acceptNumLetter(event)' maxlength="12" pattern=".{1,12}." onpaste="return false" value="<? echo $resol_pension ?>">
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
                                                    <label class="fieldlabel" for="fecha_res_pension"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" ><a STYLE="color: red" >* </a>Fecha Resolución<br>   Pensión</span></span></span></label>
                                                    <div class="null"></div>
                                                </div>
                                                <div class="control capleft">
                                                    <div>
                                                        <input type="text" id="fecha_res_pension" title="*Campo Obligatorio"  name="fecha_res_pension" maxlenght="10" placeholder="dd/mm/aaaa" required='required' pattern="(0[1-9]|[12][0-9]|3[01])[/](0[1-9]|1[012])[/](19|20)\d\d" onpaste="return false" value="<? echo $resol_pension_fecha ?>">
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
                                                    <label class="fieldlabel" for="p1f12c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve"><a STYLE="color: red" >* </a>Mesada Inicial</span></span></span></label>
                                                    <div class="null"></div>
                                                </div>
                                                <div class="control capleft">
                                                    <div>
                                                        <input type="text" id="p1f12c" name="mesada" value="<? echo $valor_mesada ?>"   title="*Campo Obligatorio" class="fieldcontent" required='required' onKeyPress='return acceptNum2(event)' maxlength="11" placeholder="00000000.00" pattern="^[0-9]\d{4,9}(\.\d{1,2})?%?$" onpaste="return false" value="<? echo $valor_mesada ?>">Mínimo 4 caracteres
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
                                                    <label class="fieldlabel" for="p1f6c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve"><a STYLE="color: red" >* </a>Cuota Aceptada</span></span></span></label>
                                                    <div class="null"></div>
                                                </div>
                                                <div class="control capleft">
                                                    <div>
                                                        <input type="text" id="p1f6c" name="cp_aceptada" value="<? echo $valor_cuota ?>" title="*Campo Obligatorio" class="fieldcontent" required='required' onKeyPress='return acceptNum2(event)' placeholder="00000000.00" pattern="^[0-9]\d{3,9}(\.\d{1,8})?%?$" maxlength="11"  onpaste="return false">Mínimo 4 caracteres
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
                                                    <label class="fieldlabel" for="p1f6c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve"><a STYLE="color: red" >* </a>Porcentaje Aceptado</span></span></span></label>
                                                    <div class="null"></div>
                                                </div>
                                                <div class="control capleft">
                                                    <div>
                                                        <input type="text" id="p1f6c" name="porc_aceptado" value="<? echo $porcentaje ?>" title="*Campo Obligatorio" placeholder="0.0000" class="fieldcontent" required='required' onKeyPress='return acceptNum2(event)' maxlength="6" pattern="^[0]{1}(\.[0-9]{2,4})?$" step="0.00" onpaste="return false">Decimal en formato: 0.9999, mín. dos decimales
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
                                                    <label class="fieldlabel" for="p1f7c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve"><a STYLE="color: red" >* </a>Tipo Acto <br>   Administrativo</span></span></span></label>
                                                    <div class="null"></div>
                                                </div>
                                                <div class="control capleft">
                                                    <div>
                                                        <div class="dropdown"> 
                                                            <select id="p1f13c" name="tipo_acto" title="*Campo Obligatorio" class="fieldcontent"><option selected="selected "value="Silencio Administrativo">Silencio Administrativo</option><option value="Resolución">Resolución</option><option value="Oficio">Oficio</option></select>
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
                                                    <label class="fieldlabel" for="p1f7c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve"><a STYLE="color: red" >* </a>Número/Referencia<br>   Acto Administrativo</span></span></span></label>
                                                    <div class="null"></div>
                                                </div>
                                                <div class="control capleft">
                                                    <div>
                                                        <input type="text" id="p1f7c" name="acto_adm" value="<?php echo $actoadm ?>" title="*Campo Obligatorio" class="fieldcontent" onKeyPress='return acceptNumLetter(event)' maxlength="15" placeholder="Número o Referencia de Acto Administrativo" onpaste="return false">
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
                                                    <label class="fieldlabel" for="fecha_acto_adm"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" ><a STYLE="color: red" >* </a>Fecha Acto<br>  Administrativo</span></span></span></label>
                                                    <div class="null"></div>
                                                </div>
                                                <div class="control capleft">
                                                    <div>
                                                        <input type="text" id="fecha_acto_adm" value="<?php echo $factoadm ?>" title="*Campo Obligatorio" name="fecha_acto_adm"  maxlenght="10" placeholder="dd/mm/aaaa" required='required' pattern="(0[1-9]|[12][0-9]|3[01])[/](0[1-9]|1[012])[/](19|20)\d\d" onpaste="return false" >
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
                                                    <label class="fieldlabel" for="observacion"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" > Observaciones</span></label>
                                                                <div class="null"></div>
                                                                </div>
                                                                <div class="control capleft">
                                                                    <div>
                                                                        <textarea id="observacion" rows="4" cols="50" title="*Campo Obligatorio" name="observacion"  maxlenght="10" onpaste="return false" ><? echo $observacion ?></textarea>
                                                                    </div>
                                                                    <div class="null"></div>
                                                                </div>
                                                                <div class="null"></div>
                                                                </div>
                                                                <div class="null"></div>
                                                                </div>

                                                                <div align="left"><a STYLE="color: red" ><br><br>* Campo obligatorio</a></div>

                                                                <div class="null"></div>
                                                                <center> <input id="registrarBoton" type="submit" class="navbtn"  onClick='return confirmarEnvio();' value="Actualizar Datos"></center>

                                                                <input type='hidden' name='opcion' value='actualizarConcurrencia'>
                                                                <input type='hidden' name='action' value='<? echo $this->formulario; ?>'>

                                                                </form>
                                                                <?
                                                            }

                                                        }
                                                        