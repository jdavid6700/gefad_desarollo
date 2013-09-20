<?php
/*
  ############################################################################
  #    UNIVERSIDAD DISTRITAL Francisco Jose de Caldas                        #
  #    Copyright: Vea el archivo EULA.txt que viene con la distribucion      #
  ############################################################################
 */

if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;

    include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/dbms.class.php");
    include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/sesion.class.php");
    include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
}

class html_liquidador {

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

    function formularioDatos() {
        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/dbms.class.php");
        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/sesion.class.php");
        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
        $this->formulario = "liquidador";

//Datos traídos desde la tabla datos básicos
        ?>
        <link href = "<? echo $this->configuracion["host"] . $this->configuracion["site"] . $this->configuracion["bloques"] ?>/nomina/cuotas_partes/cuentaCobro/cuentaC.css" rel = "stylesheet" type = "text/css" />
        <form method='POST' action='index.php' name='<? echo $this->formulario; ?>'>
            <h2>Ingrese la cédula a liquidar:</h2>
            <br>
            <input type="text" name="cedula_emp" required='required'>
            <br>  <br>
            <center> <input id="registrarBoton" type="submit" class="navbtn"  value="Enviar" ></center>
            <input type='hidden' name='pagina' value='liquidadorCP'>
            <input type='hidden' name='opcion' value='recuperar'>
            <br>
        </form>
        <?
    }

    function formularioEntidad($cedula_em, $datos_en) {
        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/dbms.class.php");
        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/sesion.class.php");
        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");

        $this->formulario = "liquidador";
        ?>
        <!referencias a estilos y plugins>
        <script type = "text/javascript" src = "<? echo $this->configuracion["host"] . $this->configuracion["site"] . $this->configuracion["plugins"]; ?>/datepicker/js/datepicker.js"></script>
        <link href = "<? echo $this->configuracion["host"] . $this->configuracion["site"] . $this->configuracion["bloques"] ?>/nomina/cuotas_partes/cuentaCobro/cuentaC.css" rel = "stylesheet" type = "text/css" />
        <link href = "http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel = "stylesheet" type = "text/css"/>
        <script type = "text/javascript" src = "http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
        <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>

        <script>
            $(document).ready(function() {
                $("#fecha_inicial").datepicker(
                        {
                            changeMonth: true,
                            changeYear: true,
                            yearRange: '1920:2013',
                            dateFormat: 'dd/mm/yy'
                        }
                );

            });

            $(document).ready(function() {
                $("#fecha_final").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    yearRange: '1920:2013',
                    dateFormat: 'dd/mm/yy'
                });
            });
        </script>    

        <!--p>Inicio de Declaraciòn del Formulario</p-->

        <form id="form" method="post" action='index.php' name='<? echo $this->formulario; ?>'>

            <p>Seleccione la entidad a liquidar:</p>
            <div class="formrow f1">
                <div class="formrow f1">
                    <div id="p1f4" class="field n1">
                        <div class="staticcontrol">
                            <div class="hrcenter px1"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="formrow f1">
                <div id="p1f7" class="field n1">
                    <div class="caption capleft alignleft">
                        <label class="fieldlabel" for="p1f7c"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" xml:space="preserve">Cédula Empleado</span></span></span></label>
                    </div>
                    <div class="control capleft">
                        <div>
                            <input type="text" id="p1f7c" name="cedula_emp" class="fieldcontent" value="<?php echo $cedula_em ?>">
                        </div>
                    </div>
                </div>
            </div>



            <div id="p1f103" class="field n1">

                <div class="caption capleft alignleft">
                    <label class="fieldlabel" for="entidades"><span><span class="pspan arial" style="text-align:left;font-size:14px;"><span class="ispan" style="color:#9393FF" >Entidad a cobrar:</span></span></span></label>
                    <div class="null"></div>
                </div>
            </div>

            <div class="control capleft">
                <div class="dropdown">
                    <?
                    //var_dump($datos_en);exit;
                    unset($combo);
                    //prepara los datos como se deben mostrar en el combo
                    $combo[0][0] = '0';
                    $combo[0][1] = 'Todos';
                    foreach ($datos_en as $cmb => $values) {
                        $combo[$cmb + 1][0] = isset($datos_en[$cmb]['nombre_entidad']) ? $datos_en[$cmb]['nombre_entidad'] : 0;
                        $combo[$cmb + 1][1] = isset($datos_en[$cmb]['nombre_entidad']) ? $datos_en[$cmb]['nombre_entidad'] : '';
                    }

                    // echo$combo;
                    if (isset($_REQUEST['entidad2'])) {
                        $lista_combo = $this->html->cuadro_lista($combo, 'entidad2', $this->configuracion, $_REQUEST['entidad2'], 0, FALSE, 0, 'entidad2');
                    } else {
                        $lista_combo = $this->html->cuadro_lista($combo, 'entidad2', $this->configuracion, 0, 0, FALSE, 0, 'entidad2');
                    }
                    echo $lista_combo;
                    ?> 
                </div>
            </div>

            <div>
                <div class="null"></div>
                <input id="generarBoton" type="submit" class="navbtn"  value="Liquidar">
                <input type='hidden' name='pagina' value='liquidadorCP'>
                <input type='hidden' name='opcion' value='liquidar'>
            </div>
        </form>

        <?
    }

    function liquidador($liquidacion, $datos_entidad) {
        $entidad = $datos_entidad[0][1];
        ?>
        <h1>Liquidación Cuota Parte para la Entidad <? echo $entidad . '<br><br>' ?> </h1>
        <table class='bordered'  width ="95%" >
            <tr>
                <th colspan="8" class="estilo_th">VALORES CALCULADOS DE CUOTAS MES A MES</th>
            </tr>
            <tr>
                <th class='subtitulo_th centrar'>FECHA PAGO</th>
                <th class='subtitulo_th centrar'>MESADA</th>
                <th class='subtitulo_th centrar'>AJUSTE PENSION</th>
                <th class='subtitulo_th centrar'>MESADA AD.</th>
                <th class='subtitulo_th centrar'>INCREMENTO SALUD</th>
                <th class='subtitulo_th centrar'>INTERESES</th>
                <th class='subtitulo_th centrar'>VALOR CUOTA</th>
                <th class='subtitulo_th centrar'>TOTAL MES</th>
            </tr>
            <?
            foreach ($liquidacion as $key => $value) {
                echo "<tr>";
                echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . date("d/m/Y", strtotime($liquidacion[$key][0])) . "</td>";
                echo "<td class='texto_elegante estilo_td' style='text-align:center;'>$ " . number_format($liquidacion[$key][2]) . "</td>";
                echo "<td class='texto_elegante estilo_td' style='text-align:center;' >" . $liquidacion[$key][3] . "</td>";
                echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . number_format($liquidacion[$key][4]) . "</td>";
                echo "<td class='texto_elegante estilo_td' style='text-align:center;'>" . number_format($liquidacion[$key][5]) . "</td>";
                echo "<td class='texto_elegante estilo_td' style='text-align:center;'>$ " .number_format($liquidacion[$key][7]) . "</td>";
                echo "<td class='texto_elegante estilo_td' style='text-align:center;'>$ " . number_format($liquidacion[$key][6]) . "</td>";
                echo "<td class='texto_elegante estilo_td' style='text-align:center;'>$ " . number_format($liquidacion[$key][8]) . "</td>";
                echo "</tr>";
            }
            ?>

        </table>

        <?
    }

}
?>