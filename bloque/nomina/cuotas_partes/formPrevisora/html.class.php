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
  | 11/06/2013 | Violeta Sosa             | 0.0.0.1     |                                 |
  ----------------------------------------------------------------------------------------
  | 02/08/2013 | Violeta Sosa             | 0.0.0.2     |                                 |
  ----------------------------------------------------------------------------------------
 */

if (!isset($GLOBALS["autorizado"])) {
//include("../index.php");
    exit;
}

class html_formPrevisora {

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
        $this->formulario = "formPrevisora";
    }

    function mostrarRegistros($registros) {
        ?>
        <link	href="<? echo $this->configuracion["host"] . $this->configuracion["site"] . $this->configuracion["bloques"] ?>/nomina/cuotas_partes/formPrevisora/form_estilo.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="<? echo $this->configuracion["host"] . $this->configuracion["site"] . $this->configuracion["plugins"]; ?>/jPages-master/css/jPages.css">
        <script src="<? echo $this->configuracion["host"] . $this->configuracion["site"] . $this->configuracion["plugins"]; ?>/jPages-master/js/jPages.js"></script>
        <!-- permite la paginacion-->  

        <!--script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script-->
        <script type="text/javascript" src="<? echo $this->configuracion["host"] . $this->configuracion["site"] . $this->configuracion["plugins"]; ?>/jFilter/multifilter.js"></script>

        <script>

            $(document).ready(function() {
                $('.filter').multifilter()
            })</script>
        <h1>Entidades Previsoras y Empleadoras</h1>

        <a href="
        <?
        $variable = 'pagina=formularioPrevisora';
        $variable.='&opcion=formularioPrevisora';
        $variable = $this->cripto->codificar_url($variable, $this->configuracion);
        echo $this->indice . $variable;
        ?>">
            <center><button id="registrarBoton"  class="navbtn">Registrar Nueva Entidad</button><br><br><br></center>
        </a>

        <br>
        <div class="formrow f1">
            <div id="p1f2" class="field n1">
                <div class="caption capleft alignleft">
                    <label class="fieldlabel" for="p1f2c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve"><a STYLE="color: red" >* </a>Filtro Nit Entidad:</span></span></span></label>
                    <div class="null"></div>
                </div>
                <div>
                    <input type="text" id="p1f2c" class="fieldcontent filter" autocomplete='off' name='NIT' placeholder='NIT' data-col='NIT'>
                </div>
            </div>
        </div>


        <div class="formrow f1">
            <div id="p1f2" class="field n1">
                <div class="caption capleft alignleft">
                    <label class="fieldlabel" for="p1f2c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve"><a STYLE="color: red" >* </a>Filtro Nombre<br>   Entidad:</span></span></span></label>
                    <div class="null"></div>
                </div>
                <div>
                    <input type="text" id="p1f2c" class="fieldcontent filter" autocomplete='off' name='NOMBRE' placeholder='NOMBRE' data-col='NOMBRE'>
                </div>
            </div>
        </div>

        <br><br><br><br><br><br>

        <table class='bordered' width='80%' align='center'>
            <thead>
            <th class = 'texto_elegante2 estilo_th NIT' name='NIT' width='30px' align = center>NIT</th>
            <th class = 'texto_elegante2 estilo_th ' align = center>NOMBRE</th>
            <th class = 'texto_elegante2 estilo_th ' align = center>ESTADO</th>
            <!--th class = 'texto_elegante2 estilo_th 'align = center>OBSERV.</th-->
            <!--th class = 'texto_elegante2 estilo_th' align = center>DIRECCION<!--/th-->
            <th class = 'texto_elegante2 estilo_th ' align = center>DEPARTAMENTO</th>
            <th class = 'texto_elegante2 estilo_th ' align = center>CIUDAD</th>
            <th class = 'texto_elegante2 estilo_th' align = center>TELEFONO</th>
            <!--th class = 'texto_elegante2 estilo_th' align = center>RESPONSABLE</th>
            <th class = 'texto_elegante2 estilo_th' align = center>CARGO</th>
            <!--th class = 'texto_elegante2 estilo_th' align = center>OTRO CONTACTO<!--/th-->
            <!--th class = 'texto_elegante2 estilo_th' align = center>CARGO<!--/th-->
            <th class = 'texto_elegante2 estilo_th' align = center>CORREO</th>
            <th class = 'texto_elegante2 estilo_th' align = center>MODIFICAR</th>
            <!--th class = 'texto_elegante2 estilo_th' align = center>CORREO 2<!--/th-->
        </thead>
        <tbody id="itemContainer">
            <tr>
                <?
                if (is_array($registros)) {
                    foreach ($registros as $key => $value) {
                        echo "<tr>";
                        echo "<td class='texto_elegante estilo_td entidad-nit' >" . $registros[$key][0] . "</td>";
                        echo "<td class='texto_elegante estilo_td entidad-nombre' >" . $registros[$key][1] . "</td>";
                        echo "<td class='texto_elegante estilo_td entidad-estado' >" . $registros[$key][2] . "</td>";
                        // echo "<td class='texto_elegante estilo_td' >" . $registros[$key][3] . "</td>";
                        //echo "<td class='texto_elegante estilo_td' >" . $registros[$key][4] . "</td>";
                        echo "<td class='texto_elegante estilo_td entidad-dep' >" . $registros[$key][5] . "</td>";
                        echo "<td class='texto_elegante estilo_td entidad-ciudad' >" . $registros[$key][6] . "</td>";
                        echo "<td class='texto_elegante estilo_td' >" . $registros[$key][7] . "</td>";
                        //echo "<td class='texto_elegante estilo_td' >" . $registros[$key][8] . "</td>";
                        //echo "<td class='texto_elegante estilo_td' >" . $registros[$key][9] . "</td>";
                        //  echo "<td class='texto_elegante estilo_td' >" . $registros[$key][10] . "</td>";
                        // echo "<td class='texto_elegante estilo_td' >" . $registros[$key][11] . "</td>";
                        echo "<td class='texto_elegante estilo_td' >" . $registros[$key][12] . "</td>";
                        echo "<td class='texto_elegante estilo_td' style='text-align:center;'>"
                        . " <a href='";
                        $variable = 'pagina=formularioPrevisora';
                        $variable.='&opcion=modificar';
                        $variable.='&datos_entidad=' . serialize($registros[$key]);
                        $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                        echo " " . $this->indice . $variable . "'>
                            <img alt='Imagen' width='20px' src='" . $this->configuracion["host"] . $this->configuracion["site"] . $this->configuracion["bloques"] . "/nomina/cuotas_partes/liquidador/icons/cuentacobro.png'/></td>";

                        // echo "<td class='texto_elegante estilo_td' >" . $registros[$key][13] . "</td>";
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
                    echo "<td class='texto_elegante estilo_td' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                    echo "</tr>";
                }
                ?>
        </table >     
        <center><div class="holder" style="-moz-user-select: none;"></div></center>
        <?
    }

    function formularioPrevisora($depto, $mun) {

        $this->formulario = "formPrevisora";

        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/dbms.class.php");
        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/sesion.class.php");
        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
        ?>
        <!referencias a estilos y plugins>
        <script type="text/javascript" src="<? echo $this->configuracion["host"] . $this->configuracion["site"] . $this->configuracion["plugins"]; ?>/datepicker/js/datepicker.js"></script>
        <link	href="<? echo $this->configuracion["host"] . $this->configuracion["site"] . $this->configuracion["bloques"] ?>/nomina/cuotas_partes/formPrevisora/form_estilo.css"	rel="stylesheet" type="text/css" />
        <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
        <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>

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

        <script language = "Javascript">

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

                var emailID = document.formPrevisora.txtEmail

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

                return true
            }

        </script>

        <script>
            function confirmarEnvio()
            {
                var r = confirm("Confirmar envío del formulario.");
                if (r == true) {
                    return true;
                } else {
                    return false;
                }
            }
        </script>

        <script>
            function acceptNum3(e) {
                key = e.keyCode || e.which;
                tecla = String.fromCharCode(key).toLowerCase();
                letras = "01234567890-/()eExt.";
                especiales = [8, 9, 32];
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
                letras = "abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZ";
                especiales = [8, 9, 32];
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
                letras = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789.-@_ñÑ";
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

        <script type = text/javascript>
            function ComponerLista(depto) {
                document.formPrevisora.departamentos.disabled = true;
                document.formPrevisora.municipios.innerHTML = ""
                SeleccionarEmpleados(depto);
                document.formPrevisora.departamentos.disabled = false;
            }

            function SeleccionarEmpleados(depto) {
                var o;
                document.formPrevisora.municipios.disabled = true;

        <?php
        foreach ($mun as $key => $value) {
            ?>
                    if (depto === "<?php echo $mun[$key]['departamento']; ?>") {
                        o = document.createElement("OPTION");
                        o.text = "<?php echo $mun[$key]['municipio'] ?>";
                        o.value = "<?php echo $mun[$key]['municipio'] ?>";
                        document.formPrevisora.municipios.options.add(o);
                    }

        <?php } ?>
                if (depto === "") {
                    o = document.createElement("OPTION");
                    o.text = "Seleccione un Municipio";
                    o.value = "0";
                    document.formPrevisora.municipios.options.add(o);
                }

                document.formPrevisora.municipios.disabled = false;
            }
        </script>




        <form id="form" method="post" action="index.php" name='<? echo $this->formulario; ?>' onSubmit="return  ValidateForm();" autocomplete='Off'>
            <h1>Entidades Previsoras y Empleadoras</h1>

            <div class="formrow f1">
                <div id="p1f5" class="field n1">
                    <div class="staticcontrol"><span class="wordwrap"><span class="pspan arial" style="text-align: left; font-size:14px;"><span class="ispan" style="color:#000099" xml:space="preserve">DETALLES DE LA ENTIDAD<span class="ispan" style="color:#EE3D23" xml:space="preserve"> </span></span></span></div>
                    <div class="null"></div>
                </div>
                <div class="null"></div>
            </div>

            <div class="formrow f1">
                <div id="p1f6" class="field n1">
                    <div class="caption capleft alignleft">
                        <label class="fieldlabel" for="p1f6c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve"><a STYLE="color: red" >* </a>Nit Entidad</span></span></span></label>
                        <div class="null"></div>
                    </div>
                    <div class="control capleft">
                        <div>
                            <input type="text" id="p1f6c" name="nit_previsora" class="fieldcontent" required='required' onKeyPress='return acceptNum(event)' maxlength='15' onpaste="return false">
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
                        <label class="fieldlabel" for="p1f7c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve"><a STYLE="color: red" >* </a>Nombre Entidad</span></span></span></label>
                        <div class="null"></div>
                    </div>
                    <div class="control capleft">
                        <div>
                            <input type="text" id="p1f7c" name="nombre_previsora" class="fieldcontent" required='required' maxlength='50' onKeyPress='return acceptNumLetter(event)' onpaste="return false">
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
                        <label class="fieldlabel" for="p1f7c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve"><a STYLE="color: red" >* </a>Estado</span></span></span></label>
                        <div class="null"></div>
                    </div>
                    <div class="control capleft">
                        <div>
                            <div class="dropdown">
                                <select id="p1f13c" name="estado" required='required' class="fieldcontent"><option value="ACTIVA">ACTIVA</option><option value="INACTIVA">INACTIVA</option></select>
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
                        <label class="fieldlabel" for="p1f7c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve">   Observación</span></span></span></label>
                        <div class="null"></div>
                    </div>
                    <div class="control capleft">
                        <div>
                            <input type="text" id="p1f7c" name="observacion" class="fieldcontent" maxlength='150' onKeyPress='return acceptNumLetter(event)'  onpaste="return false">
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
                        <label class="fieldlabel" for="p1f7c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve"><a STYLE="color: red" >* </a>Dirección</span></span></span></label>
                        <div class="null"></div>
                    </div>
                    <div class="control capleft">
                        <div>
                            <input type="text" id="p1f7c" name="direccion" class="fieldcontent" required='required' maxlength='50' onKeyPress='return acceptNumLetter(event)' onpaste="return false">
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
                        <label class="fieldlabel" for="p1f6c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve"><a STYLE="color: red" >* </a>Departamento</span></span></span></label>
                        <div class="null"></div>
                    </div>
                    <div class="control capleft">
                        <div>
                            <select name='departamentos' onChange='ComponerLista(this.value)' title="*Campo Obligatorio" autocomplete="off" required='required'>
                                <?php
                                $var = "<option selected value=''>" . "Seleccione un Departamento" . "</option>";
                                foreach ($depto as $key => $value) {
                                    $var.= "<option value='" . $depto[$key]['departamento'] . "'>" . $depto[$key]['departamento'] . "</option>";
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
                        <label class="fieldlabel" for="p1f6c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve"><a STYLE="color: red" >* </a>Municipio</span></span></span></label>
                        <div class="null"></div>
                    </div>
                    <div class="control capleft">

                        <select id="municipios" name="municipios" required='required' autocomplete="off" title="*Campo Obligatorio">
                            <option value='' >Seleccione un Municipio</option>
                        </select>

                        <div class="null"></div>
                    </div>
                    <div class="null"></div>
                </div>
                <div class="null"></div>
            </div>

            <div class="formrow f1">
                <div id="p1f6" class="field n1">
                    <div class="caption capleft alignleft">
                        <label class="fieldlabel" for="p1f6c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve">   Teléfono</span></span></span></label>
                        <div class="null"></div>
                    </div>
                    <div class="control capleft">
                        <div>
                            <input type="text" id="p1f7c" name="telefono" class="fieldcontent" placeholder="7777777 - (057) 7777777 Ext.000/001" onKeyPress='return acceptNum3(event)' maxlength='50' onpaste="return false">
                        </div>
                        <div class="null"></div>
                    </div>
                    <div class="null"></div>
                </div>
                <div class="null"></div>
            </div>
            <div class="formrow f1">
                <div id="p1f5" class="field n1">
                    <div class="staticcontrol"><span class="wordwrap"><span class="pspan arial" style="text-align: left; font-size:14px;"><span class="ispan" style="color:#000099" xml:space="preserve">DATOS DE CONTACTO<span class="ispan" style="color:#EE3D23" xml:space="preserve"> </span></span></span></div>
                    <div class="null"></div>
                </div>
                <div class="null"></div>
            </div>

            <div class="formrow f1">
                <div id="p1f7" class="field n1">
                    <div class="caption capleft alignleft">
                        <label class="fieldlabel" for="p1f7c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve">   Responsable</span></span></span></label>
                        <div class="null"></div>
                    </div>
                    <div class="control capleft">
                        <div>
                            <input type="text" id="p1f7c" name="responsable" class="fieldcontent"  maxlength='50' onKeyPress='return acceptLetter(event)' onpaste="return false" >
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
                        <label class="fieldlabel" for="p1f6c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve">   Cargo</span></span></span></label>
                        <div class="null"></div>
                    </div>
                    <div class="control capleft">
                        <div>
                            <input type="text" id="p1f7c" name="cargo" class="fieldcontent" maxlength='50' onKeyPress='return acceptLetter(event)' onpaste="return false">
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
                        <label class="fieldlabel" for="p1f12c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve">   Otro Contacto</span></span></span></label>
                        <div class="null"></div>
                    </div>
                    <div class="control capleft">
                        <div>
                            <input type="text" id="p1f7c" name="otro_contacto" class="fieldcontent" maxlength='50' onKeyPress='return acceptLetter(event)' onpaste="return false">
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
                        <label class="fieldlabel" for="p1f6c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve">   Cargo</span></span></span></label>
                        <div class="null"></div>
                    </div>
                    <div class="control capleft">
                        <div>
                            <input type="text" id="p1f7c" name="otro_cargo" class="fieldcontent" maxlength='50' onKeyPress='return acceptLetter(event)' onpaste="return false">
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
                        <label class="fieldlabel" for="p1f12c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve"><a STYLE="color: red" >* </a>Correo Electrónico</span></span></span></label>
                        <div class="null"></div>
                    </div>
                    <div class="control capleft">
                        <div>
                            <input type="text" id="email" name="txtEmail" class="fieldcontent" maxlength='50' placeholder="correo@dominio.com" onKeyPress='return acceptNumLetter(event)' onpaste="return false">
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

            <input type='hidden' name='opcion' value='registrarPrevisora'>
            <input type='hidden' name='action' value='<? echo $this->formulario; ?>'>

        </form>
        <?
    }

    function modificarPrevisora($depto, $mun, $datos_entidad) {

        $this->formulario = "formPrevisora";

        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/dbms.class.php");
        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/sesion.class.php");
        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
        ?>
        <!referencias a estilos y plugins>
        <script type="text/javascript" src="<? echo $this->configuracion["host"] . $this->configuracion["site"] . $this->configuracion["plugins"]; ?>/datepicker/js/datepicker.js"></script>
        <link	href="<? echo $this->configuracion["host"] . $this->configuracion["site"] . $this->configuracion["bloques"] ?>/nomina/cuotas_partes/formPrevisora/form_estilo.css"	rel="stylesheet" type="text/css" />
        <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
        <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>

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

        <script language = "Javascript">

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

                var emailID = document.formPrevisora.txtEmail

                if ((emailID.value == null) || (emailID.value == "")) {
                    alert("Ingrese un correo electrónico!")
                    emailID.focus()
                    return false
                }

                if (echeck(emailID.value) == false) {
                    emailID.focus()
                    return false
                }

                return true
            }

        </script>

        <script>
            function confirmarEnvio()
            {
                var r = confirm("Confirmar envío del formulario.");
                if (r == true) {
                    return true;
                } else {
                    return false;
                }
            }
        </script>

        <script>
            function acceptNum3(e) {
                key = e.keyCode || e.which;
                tecla = String.fromCharCode(key).toLowerCase();
                letras = "01234567890-/()eExt.";
                especiales = [8, 9, 32];
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
                letras = "abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZ";
                especiales = [8, 9, 32];
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
                letras = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789.-@_ñÑ";
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

        <script type = text/javascript>
            function ComponerLista(depto) {
                document.formPrevisora.departamentos.disabled = true;
                document.formPrevisora.municipios.innerHTML = ""
                SeleccionarEmpleados(depto);
                document.formPrevisora.departamentos.disabled = false;
            }

            function SeleccionarEmpleados(depto) {
                var o;
                document.formPrevisora.municipios.disabled = true;

        <?php
        foreach ($mun as $key => $value) {
            ?>
                    if (depto === "<?php echo $mun[$key]['departamento']; ?>") {
                        o = document.createElement("OPTION");
                        o.text = "<?php echo $mun[$key]['municipio'] ?>";
                        o.value = "<?php echo $mun[$key]['municipio'] ?>";
                        document.formPrevisora.municipios.options.add(o);
                    }

        <?php } ?>
                if (depto === "") {
                    o = document.createElement("OPTION");
                    o.text = "Seleccione un Municipio";
                    o.value = "0";
                    document.formPrevisora.municipios.options.add(o);
                }

                document.formPrevisora.municipios.disabled = false;
            }
        </script>

        <form id="form" method="post" action="index.php" name='<? echo $this->formulario; ?>' onSubmit="return  ValidateForm();" autocomplete='Off'>
            <h1>Entidades Previsoras y Empleadoras</h1>

            <div class="formrow f1">
                <div id="p1f5" class="field n1">
                    <div class="staticcontrol"><span class="wordwrap"><span class="pspan arial" style="text-align: left; font-size:14px;"><span class="ispan" style="color:#000099" xml:space="preserve">DETALLES DE LA ENTIDAD<span class="ispan" style="color:#EE3D23" xml:space="preserve"> </span></span></span></div>
                    <div class="null"></div>
                </div>
                <div class="null"></div>
            </div>

            <div class="formrow f1">
                <div id="p1f6" class="field n1">
                    <div class="caption capleft alignleft">
                        <label class="fieldlabel" for="p1f6c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve"><a STYLE="color: red" >* </a>Nit Entidad</span></span></span></label>
                        <div class="null"></div>
                    </div>
                    <div class="control capleft">
                        <div>
                            <input type="text" id="p1f6c" name="nit_previsora" class="fieldcontent" required='required' onKeyPress='return acceptNum(event)' maxlength='15' onpaste="return false" value="<?php echo $datos_entidad["prev_nit"] ?>" >
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
                        <label class="fieldlabel" for="p1f7c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve"><a STYLE="color: red" >* </a>Nombre Entidad</span></span></span></label>
                        <div class="null"></div>
                    </div>
                    <div class="control capleft">
                        <div>
                            <input type="text" id="p1f7c" name="nombre_previsora" class="fieldcontent" required='required' maxlength='50' onKeyPress='return acceptNumLetter(event)' onpaste="return false" value="<?php echo $datos_entidad["prev_nombre"] ?>">
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
                        <label class="fieldlabel" for="p1f7c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve"><a STYLE="color: red" >* </a>Estado</span></span></span></label>
                        <div class="null"></div>
                    </div>
                    <div class="control capleft">
                        <div>
                            <div class="dropdown">
                                <select id="p1f13c" name="estado" required='required' class="fieldcontent" value="<?php echo $datos_entidad["prev_nit"] ?>"><option value="ACTIVA">ACTIVA</option><option value="INACTIVA">INACTIVA</option></select>
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
                        <label class="fieldlabel" for="p1f7c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve">   Observación</span></span></span></label>
                        <div class="null"></div>
                    </div>
                    <div class="control capleft">
                        <div>
                            <input type="text" id="p1f7c" name="observacion" class="fieldcontent" maxlength='150' onKeyPress='return acceptNumLetter(event)'  onpaste="return false"  value="<?php echo $datos_entidad["prev_observacion"] ?>">
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
                        <label class="fieldlabel" for="p1f7c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve"><a STYLE="color: red" >* </a>Dirección</span></span></span></label>
                        <div class="null"></div>
                    </div>
                    <div class="control capleft">
                        <div>
                            <input type="text" id="p1f7c" name="direccion" class="fieldcontent" required='required' maxlength='50' onKeyPress='return acceptNumLetter(event)' onpaste="return false" value="<?php echo $datos_entidad["prev_direccion"] ?>"> 
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
                        <label class="fieldlabel" for="p1f6c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve"><a STYLE="color: red" >* </a>Departamento</span></span></span></label>
                        <div class="null"></div>
                    </div>
                    <div class="control capleft">
                        <div>
                            <select name='departamentos' onChange='ComponerLista(this.value)' title="*Campo Obligatorio" autocomplete="off" required='required'>
                                <?php
                                $var = "<option selected value='" . $datos_entidad['prev_departamento'] . "'> " . $datos_entidad['prev_departamento'] . " </option>";
                                foreach ($depto as $key => $value) {
                                    if ($depto[$key]['departamento'] !== $datos_entidad['prev_departamento']) {
                                        $var.= "<option value='" . $depto[$key]['departamento'] . "'>" . $depto[$key]['departamento'] . "</option>";
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
                        <label class="fieldlabel" for="p1f6c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve"><a STYLE="color: red" >* </a>Municipio</span></span></span></label>
                        <div class="null"></div>
                    </div>
                    <div class="control capleft">

                        <select id="municipios" name="municipios" required='required' autocomplete="off" title="*Campo Obligatorio">
                            <?php
                            $var = "<option selected value='" . $datos_entidad['prev_ciudad'] . "'> " . $datos_entidad['prev_ciudad'] . " </option>";
                            echo $var;
                            ?>   

                        </select>

                        <div class="null"></div>
                    </div>
                    <div class="null"></div>
                </div>
                <div class="null"></div>
            </div>

            <div class="formrow f1">
                <div id="p1f6" class="field n1">
                    <div class="caption capleft alignleft">
                        <label class="fieldlabel" for="p1f6c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve">   Teléfono</span></span></span></label>
                        <div class="null"></div>
                    </div>
                    <div class="control capleft">
                        <div>
                            <input type="text" id="p1f7c" name="telefono" class="fieldcontent" placeholder="7777777 - (057) 7777777 Ext.000/001" onKeyPress='return acceptNum3(event)' maxlength='50' onpaste="return false" value="<?php echo $datos_entidad["prev_telefono"] ?>">
                        </div>
                        <div class="null"></div>
                    </div>
                    <div class="null"></div>
                </div>
                <div class="null"></div>
            </div>
            <div class="formrow f1">
                <div id="p1f5" class="field n1">
                    <div class="staticcontrol"><span class="wordwrap"><span class="pspan arial" style="text-align: left; font-size:14px;"><span class="ispan" style="color:#000099" xml:space="preserve">DATOS DE CONTACTO<span class="ispan" style="color:#EE3D23" xml:space="preserve"> </span></span></span></div>
                    <div class="null"></div>
                </div>
                <div class="null"></div>
            </div>

            <div class="formrow f1">
                <div id="p1f7" class="field n1">
                    <div class="caption capleft alignleft">
                        <label class="fieldlabel" for="p1f7c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve">   Responsable</span></span></span></label>
                        <div class="null"></div>
                    </div>
                    <div class="control capleft">
                        <div>
                            <input type="text" id="p1f7c" name="responsable" class="fieldcontent"  maxlength='50' onKeyPress='return acceptLetter(event)' onpaste="return false" value="<?php echo $datos_entidad["prev_responsable"] ?>">
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
                        <label class="fieldlabel" for="p1f6c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve">   Cargo</span></span></span></label>
                        <div class="null"></div>
                    </div>
                    <div class="control capleft">
                        <div>
                            <input type="text" id="p1f7c" name="cargo" class="fieldcontent" maxlength='50' onKeyPress='return acceptLetter(event)' onpaste="return false" value="<?php echo $datos_entidad["prev_cargo"] ?>">
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
                        <label class="fieldlabel" for="p1f12c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve">   Otro Contacto</span></span></span></label>
                        <div class="null"></div>
                    </div>
                    <div class="control capleft">
                        <div>
                            <input type="text" id="p1f7c" name="otro_contacto" class="fieldcontent" maxlength='50' onKeyPress='return acceptLetter(event)' onpaste="return false" value="<?php echo $datos_entidad["prev_otroc"] ?>">
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
                        <label class="fieldlabel" for="p1f6c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve">   Cargo</span></span></span></label>
                        <div class="null"></div>
                    </div>
                    <div class="control capleft">
                        <div>
                            <input type="text" id="p1f7c" name="otro_cargo" class="fieldcontent" maxlength='50' onKeyPress='return acceptLetter(event)' onpaste="return false" value="<?php echo $datos_entidad["prev_cargooc"] ?>">
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
                        <label class="fieldlabel" for="p1f12c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve"><a STYLE="color: red" >* </a>Correo Electrónico</span></span></span></label>
                        <div class="null"></div>
                    </div>
                    <div class="control capleft">
                        <div>
                            <input type="text" id="email" name="txtEmail" class="fieldcontent" maxlength='50' placeholder="correo@dominio.com" onKeyPress='return acceptNumLetter(event)' onpaste="return false" value="<?php echo $datos_entidad["prev_correo1"] ?>">
                        </div>
                        <div class="null"></div>
                    </div>
                    <div class="null"></div>
                </div>
                <div class="null"></div>
            </div>

            <div align="left"><a STYLE="color: red" ><br><br>* Campo obligatorio</a></div>

            <div class="null"></div>
            <center> <input id="registrarBoton" type="submit" class="navbtn"  value="Modificar Registro" onClick='return confirmarEnvio();'></center>

            <input type='hidden' name='opcion' value='actualizarPrevisora'>
            <input type='hidden' name='serial' value='<? echo $datos_entidad['prev_serial'] ?>'>
            <input type='hidden' name='action' value='<? echo $this->formulario; ?>'>


        </form>
        <?
    }

}
