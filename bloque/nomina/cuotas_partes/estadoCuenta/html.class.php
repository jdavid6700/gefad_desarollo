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
  | 30/12/2013 | Violet Sosa             | 0.0.0.1     |                                 |
  ----------------------------------------------------------------------------------------
 */

if (!isset($GLOBALS["autorizado"])) {
//include("../index.php");
    exit;
}

class html_estadoCuenta {

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
        $this->formulario = "estadoCuenta";
    }

    function mostrarRegistros($registros) {
        ?>
        <link	href="<? echo $this->configuracion["host"] . $this->configuracion["site"] . $this->configuracion["bloques"] ?>/nomina/cuotas_partes/estadoCuenta/form_estilo.css"	rel="stylesheet" type="text/css" />
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
            });
        </script>

        <h1>Entidades Previsoras y Empleadoras</h1>

        <a href="
        <?
        $variable = 'pagina=formularioPrevisora';
        $variable.='&opcion=formularioPrevisora';
        $variable = $this->cripto->codificar_url($variable, $this->configuracion);
        echo $this->indice . $variable;
        ?>">
            <center><button id="registrarBoton"  class="navbtn">Registrar Nueva Entidad</button></center>
        </a>

        <center><div class="holder"></div></center>

        <table class = 'bordered' width = "100%" >
            <tr>
                <th colspan = "14" class = 'encabezado_registro'>ENTIDADES PREVISORAS Y EMPLEADORAS</th>
                <td class = 'texto_elegante<? echo '' ?> estilo_td' ></td>
            </tr>
            <tr>
                <td class = 'texto_elegante2 estilo_td' align = center>NIT</td>
                <td class = 'texto_elegante2 estilo_td' align = center>NOMBRE</td>
                <td class = 'texto_elegante2 estilo_td' align = center>ESTADO</td>
                <td class = 'texto_elegante2 estilo_td' align = center>OBSERVACION</td>
                <!--td class = 'texto_elegante2 estilo_td' align = center>DIRECCION<!--/td-->
                <!--td class = 'texto_elegante2 estilo_td' align = center>DEPARTAMENTO<!--/td-->
                <td class = 'texto_elegante2 estilo_td' align = center>CIUDAD</td>
                <td class = 'texto_elegante2 estilo_td' align = center>TELEFONO</td>
                <td class = 'texto_elegante2 estilo_td' align = center>RESPONSABLE</td>
                <td class = 'texto_elegante2 estilo_td' align = center>CARGO</td>
                <!--td class = 'texto_elegante2 estilo_td' align = center>OTRO CONTACTO<!--/td-->
                <!--td class = 'texto_elegante2 estilo_td' align = center>CARGO<!--/td-->
                <td class = 'texto_elegante2 estilo_td' align = center>CORREO 1</td>
                <!--td class = 'texto_elegante2 estilo_td' align = center>CORREO 2<!--/td-->
            </tr>
            <tbody id="itemContainer">
                <tr>
                    <?
                    if (is_array($registros)) {
                        foreach ($registros as $key => $value) {
                            echo "<tr>";
                            echo "<td class='texto_elegante estilo_td' >" . $registros[$key][0] . "</td>";
                            echo "<td class='texto_elegante estilo_td' >" . $registros[$key][1] . "</td>";
                            echo "<td class='texto_elegante estilo_td' >" . $registros[$key][2] . "</td>";
                            echo "<td class='texto_elegante estilo_td' >" . $registros[$key][3] . "</td>";
                            //echo "<td class='texto_elegante estilo_td' >" . $registros[$key][4] . "</td>";
                            //echo "<td class='texto_elegante estilo_td' >" . $registros[$key][5] . "</td>";
                            echo "<td class='texto_elegante estilo_td' >" . $registros[$key][6] . "</td>";
                            echo "<td class='texto_elegante estilo_td' >" . $registros[$key][7] . "</td>";
                            echo "<td class='texto_elegante estilo_td' >" . $registros[$key][8] . "</td>";
                            echo "<td class='texto_elegante estilo_td' >" . $registros[$key][9] . "</td>";
                            //  echo "<td class='texto_elegante estilo_td' >" . $registros[$key][10] . "</td>";
                            // echo "<td class='texto_elegante estilo_td' >" . $registros[$key][11] . "</td>";
                            echo "<td class='texto_elegante estilo_td' >" . $registros[$key][12] . "</td>";
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
                        echo "<td class='texto_elegante estilo_td' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                        echo "</tr>";
                    }
                    ?>
        </table >     
        <center><div class="holder" style="-moz-user-select: none;"></div></center>

        <?
    }

    function formularioPrevisora() {

        $this->formulario = "estadoCuenta";

        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/dbms.class.php");
        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/sesion.class.php");
        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
        ?>



        <!referencias a estilos y plugins>
        <script type="text/javascript" src="<? echo $this->configuracion["host"] . $this->configuracion["site"] . $this->configuracion["plugins"]; ?>/datepicker/js/datepicker.js"></script>
        <link	href="<? echo $this->configuracion["host"] . $this->configuracion["site"] . $this->configuracion["bloques"] ?>/nomina/cuotas_partes/estadoCuenta/form_estilo.css"	rel="stylesheet" type="text/css" />
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



        <form id="form" method="post" action="index.php" name='<? echo $this->formulario; ?>' onSubmit="return  ValidateForm();" autocomplete='Off'>
            <h1>Formulario de Registro Entidades Previsoras y Empleadoras</h1>

            <div class="formrow f1">
                <div id="p1f6" class="field n1">
                    <div class="caption capleft alignleft">
                        <label class="fieldlabel" for="p1f6c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve">Nit Entidad<a STYLE="color: red" >*</a></span></span></span></label>
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
                        <label class="fieldlabel" for="p1f7c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve">Nombre Entidad<a STYLE="color: red" >*</a></span></span></span></label>
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
                        <label class="fieldlabel" for="p1f7c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve">Estado<a STYLE="color: red" >*</a></span></span></span></label>
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
                        <label class="fieldlabel" for="p1f7c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve">Observación</span></span></span></label>
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
                        <label class="fieldlabel" for="p1f7c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve">Dirección<a STYLE="color: red" >*</a></span></span></span></label>
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
                        <label class="fieldlabel" for="p1f6c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve">Ciudad<a STYLE="color: red" >*</a></span></span></span></label>
                        <div class="null"></div>
                    </div>
                    <div class="control capleft">
                        <div>
                            <input type="text" id="p1f7c" name="ciudad" class="fieldcontent" required='required' maxlength='35' onKeyPress='return acceptLetter(event)' onpaste="return false">
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
                        <label class="fieldlabel" for="p1f6c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve">Departamento<a STYLE="color: red" >*</a></span></span></span></label>
                        <div class="null"></div>
                    </div>
                    <div class="control capleft">
                        <div>
                            <input type="text" id="p1f7c" name="departamento" class="fieldcontent" required='required' maxlength='35' onKeyPress='return acceptLetter(event)'  onpaste="return false">
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
                        <label class="fieldlabel" for="p1f6c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve">Teléfono</span></span></span></label>
                        <div class="null"></div>
                    </div>
                    <div class="control capleft">
                        <div>
                            <input type="text" id="p1f7c" name="telefono" class="fieldcontent" onKeyPress='return acceptNum3(event)' maxlength='50' onpaste="return false">
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
                        <label class="fieldlabel" for="p1f7c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve">Responsable</span></span></span></label>
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
                        <label class="fieldlabel" for="p1f6c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve">Cargo</span></span></span></label>
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
                        <label class="fieldlabel" for="p1f12c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve">Otro Contacto</span></span></span></label>
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
                        <label class="fieldlabel" for="p1f6c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve">Cargo</span></span></span></label>
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
                        <label class="fieldlabel" for="p1f12c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve">Correo 1</span></span></span></label>
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

            <div class="formrow f1">
                <div id="p1f12" class="field n1">
                    <div class="caption capleft alignleft">
                        <label class="fieldlabel" for="p1f12c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve">Correo 2</span></span></span></label>
                        <div class="null"></div>
                    </div>
                    <div class="control capleft">
                        <div>
                            <input type="text" id="email" name="txtEmail2" class="fieldcontent" placeholder="correo@dominio.com" maxlength='50' onKeyPress='return acceptNumLetter(event)' onpaste="return false">
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

}
