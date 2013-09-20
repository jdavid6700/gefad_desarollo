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
}

class html_infoTributario {

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

    function encuesta_info_tributario($datos_pre) {


        //include_once($this->configuracion["host"] . $this->configuracion["site"] . $this->configuracion["plugins"] . "/pdf/dompdf/dompdf_config_inic.php");


        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/dbms.class.php");
        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/sesion.class.php");
        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
        $this->formulario = "admin_DocumentosVinculacion";

        //Datos traídos desde la tabla datos básicos

        $vigencia = $_REQUEST['vigencia'];
        $vinculacion = $_REQUEST['vinculacion'];
        $estado = $_REQUEST['estado'];
        $contrato = $_REQUEST['contrato'];
        $nombre = $_REQUEST['nombre'];
        $nombre2 = $_REQUEST['nombre2'];
        $apellido = $_REQUEST['apellido'];
        $ap2 = $_REQUEST['ap2'];
        $id = $_REQUEST['identificacion'];
        $id_tipo = $_REQUEST['id_tipo'];

        //Datos traidos desde postgres
        ?>

        <link href = "<? echo $this->configuracion["host"] . $this->configuracion["site"] . $this->configuracion["bloques"] ?>/tributario/infoTributario/cuentaC.css" rel = "stylesheet" type = "text/css" />

        <center> <table class='bordered'  width ="85%">

                <thead>
                    <tr>
                        <th  class='encabezado_registro' colspan="1" ><img alt="Imagen" src="<? echo $this->configuracion["host"] . $this->configuracion["site"] . $this->configuracion["bloques"] ?>/tributario/infoTributario/escudo.png" /></th>
                        <th  class='encabezado_registro2'colspan="3" >
                            <br>UNIVERSIDAD DISTRITAL FRANCISCO JOSÉ DE CALDAS
                            <br>NIT 899999230-7 <br><br>
                        </th>
                    </tr>

                    <tr>
                        <td colspan='4' class='estilo_td2 texto_elegante2' align=center >

                            <br>FORMATO PERSONAS NATURALES 
                            <br>CERTIFICACIÓN DECRETO 1070 DE 2013 PARA PERSONAS NATURALES 
                            <br>VINCULADAS CONTRACTUALMENTE CON LA UNIVERSIDAD DISTRITAL

                        </td>
                    </tr>
                    <tr>
                        <td colspan='4' class='estilo_td2 texto_elegante2'  >
                            <br><br>
                            <?
                            $dias = array("Domingo, ", "Lunes, ", "Martes, ", "Miercoles, ", "Jueves, ", "Viernes, ", "Sábado, ");
                            $meses = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
                            echo 'Bogotá D.C. ' . $dias[date('w')] . " " . date('d') . " de " . $meses[date('n') - 1] . " del " . date('Y');

                            echo '<br>';
                            ?>
                            <br>
                            <br>Señores
                            <br>UNIVERSIDAD DISTRITAL FRANCISCO JOSÉ DE CALDAS
                            <br>Ciudad
                            <br><br><br>

                            Yo <? echo $nombre . ' ' . $nombre2 . ' ' . $apellido . ' ' . $ap2 ?>, por medio de la presente y para dar cumplimiento al artículo 1° 
                            del decreto 1070 de 2013, me permito informar bajo la gravedad de juramento lo siguiente:
                            <br><br>
                        </td>
                    </tr>
                </thead>
            </table>


            <form method="post" action="index.php" name='<? echo $this->formulario; ?>'>
                <table class='bordered'  width ="85%"  >

                    <tr>
                        <th class='texto_elegante estilo_th' >ID</th>
                        <th class='texto_elegante estilo_th' >Pregunta</th>
                        <th class='texto_elegante estilo_th' >SI</th>
                        <th class='texto_elegante estilo_th' >NO</th>

                    </tr>

                    <?
                    foreach ($datos_pre as $key => $dato) {
                        $id_pregunta = (isset($dato['preg_id']) ? $dato['preg_id'] : '');
                        $pregunta = (isset($dato['preg_nombre']) ? $dato['preg_nombre'] : '');
                        $id_encuesta = (isset($dato['form_enc_id']) ? $dato['form_enc_id'] : '');


                        echo "<tr>";
                        echo "<td class='texto_elegante2 estilo_td' align=center width=30 >" . $id_pregunta . "</td>";
                        echo "<td class='texto_elegante estilo_td' width=500>" . $pregunta . "</td>";
                        echo "
                        <td class='texto_elegante estilo_td' width=50 style='text-align: center; vertical-align: middle;'>
                        <input type='hidden' name='id_pregunta" . $id_pregunta . "' value='$id_pregunta'>
                        <input type='radio' name='respuesta_" . $id_pregunta . "' value='SI' checked>
                        </td>";


                        echo "
                        <td class='texto_elegante estilo_td' width=50 style='text-align: center; vertical-align: middle;'>
                        <input type='hidden' name='id_pregunta" . $id_pregunta . "' value='$id_pregunta'>
                        <input type='radio' name='respuesta_" . $id_pregunta . "' value='NO' checked> 
                        </td>";

                        echo "</tr>";
                    }
                    ?>

                    <tr>
                        <td class='estilo_td2 texto_elegante2' colspan='4'>
        <?
        echo '<br>';
        echo 'Nombre: ' . $nombre . ' ' . $nombre2 . ' ' . $apellido . ' ' . $ap2;
        echo '<br>';
        echo 'Identificación: ' . $id;
        if ($tipo_id = 1)
            echo ' CC';
        ?>

                        </td>
                    </tr>

                    <tr>
                        <td class='estilo_td2 texto_elegante' colspan='4'>

                    <center>
                        <input id="registrarBoton" type="submit" class="navbtn"  value="Guardar" > 
                    </center>

                    <input type='hidden' name='opcion' value='guardarDatos'>
                    <input type='hidden' name='action' value='<? echo $this->formulario; ?>'>


                    <input type='hidden' name='func_documento' value='<? echo $id ?>'>
                    <input type='hidden' name='tipo_documento' value='<? echo $id_tipo ?>'>
                    <input type='hidden' name='annio' value='<? echo $vigencia ?>'>
                    <input type='hidden' name='contrato' value='<? echo $contrato ?>'>
                    <input type='hidden' name='fecha_registro' value='<? echo date('d/m/Y') ?>'>
                    <input type='hidden' name='id_encuesta' value='<? echo $id_encuesta ?>'>

                    <br>
                    </td>
                    </tr>


                </table>
            </form>       </center>


        <?
    }

    function respuesta_info_tributario($datos_pre, $datos_respu) {

        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/dbms.class.php");
        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/sesion.class.php");
        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
        $this->formulario = "admin_DocumentosVinculacion";

        //Datos traídos desde la tabla datos básicos

        $vigencia = $_REQUEST['vigencia'];
        $vinculacion = $_REQUEST['vinculacion'];
        $estado = $_REQUEST['estado'];
        $contrato = $_REQUEST['contrato'];
        $nombre = $_REQUEST['nombre'];
        $nombre2 = $_REQUEST['nombre2'];
        $apellido = $_REQUEST['apellido'];
        $ap2 = $_REQUEST['ap2'];
        $id = $_REQUEST['identificacion'];
        $id_tipo = $_REQUEST['id_tipo'];

        $fecha_registro = strtotime($datos_respu[0]['resp_fec_registro']);

        //Datos traidos desde postgres
        ?>

        <link href = "<? echo $this->configuracion["host"] . $this->configuracion["site"] . $this->configuracion["bloques"] ?>/tributario/infoTributario/cuentaC.css" rel = "stylesheet" type = "text/css" />


        <center> <table class='bordered'  width ="85%">

                <thead>
                    <tr>
                        <th  class='encabezado_registro' colspan="1" ><img alt="Imagen" src="<? echo $this->configuracion["host"] . $this->configuracion["site"] . $this->configuracion["bloques"] ?>/tributario/infoTributario/escudo.png" /></th>
                        <th  class='encabezado_registro2'colspan="3" >
                            <br>UNIVERSIDAD DISTRITAL FRANCISCO JOSÉ DE CALDAS
                            <br>NIT 899999230-7 <br><br>
                        </th>
                    </tr>

                    <tr>
                        <td colspan='4' class='estilo_td2 texto_elegante2' align=center >

                            <br>FORMATO PERSONAS NATURALES 
                            <br>CERTIFICACIÓN DECRETO 1070 DE 2013 PARA PERSONAS NATURALES 
                            <br>VINCULADAS CONTRACTUALMENTE CON LA UNIVERSIDAD DISTRITAL

                        </td>
                    </tr>
                    <tr>
                        <td colspan='4' class='estilo_td2 texto_elegante2'  >
                            <br><br>
        <?
        $dias = array("Domingo, ", "Lunes, ", "Martes, ", "Miercoles, ", "Jueves, ", "Viernes, ", "Sábado, ");
        $meses = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
        echo 'Bogotá D.C. ' . $dias[date('w', $fecha_registro)] . " " . date('d', $fecha_registro) . " de " . $meses[date('n', $fecha_registro) - 1] . " del " . date('Y', $fecha_registro);

        echo '<br>';
        ?>
                            <br>
                            <br>Señores
                            <br>UNIVERSIDAD DISTRITAL FRANCISCO JOSÉ DE CALDAS
                            <br>Ciudad
                            <br><br><br>

                            Yo <? echo $nombre . ' ' . $nombre2 . ' ' . $apellido . ' ' . $ap2 ?>, por medio de la presente y para dar cumplimiento al artículo 1° 
                            del decreto 1070 de 2013, me permito informar bajo la gravedad de juramento lo siguiente:
                            <br><br>
                        </td>
                    </tr>
                </thead>
            </table>


            <form method="post" action="index.php" name='<? echo $this->formulario; ?>'>
                <table class='bordered'  width ="85%"  >

                    <tr>
                        <th class='texto_elegante estilo_th' >ID</th>
                        <th class='texto_elegante estilo_th' >Pregunta</th>
                        <th class='texto_elegante estilo_th' >SI</th>
                        <th class='texto_elegante estilo_th' >NO</th>

                    </tr>

        <?
        foreach ($datos_pre as $key => $dato) {
            $id_pregunta = (isset($dato['preg_id']) ? $dato['preg_id'] : '');
            $pregunta = (isset($dato['preg_nombre']) ? $dato['preg_nombre'] : '');
            $id_encuesta = (isset($dato['form_enc_id']) ? $dato['form_enc_id'] : '');

            echo "<tr>";
            echo "<td class='texto_elegante2 estilo_td' align=center width=30 >" . $id_pregunta . "</td>";
            echo "<td class='texto_elegante estilo_td' width=500>" . $pregunta . "</td>";
            echo "
                        <td class='texto_elegante estilo_td' width=50 style='text-align: center; vertical-align: middle;'>
                        <input type='hidden' name='id_pregunta" . $id_pregunta . "' value='$id_pregunta'>
                        <input type='radio' name='respuesta_" . $id_pregunta . "' value='SI'";

            if ($datos_respu[$key]['resp_respuesta'] == 'SI') {
                echo "checked>";
            }


            echo "            </td>";


            echo "
                        <td class='texto_elegante estilo_td' width=50 style='text-align: center; vertical-align: middle;'>
                        <input type='hidden' name='id_pregunta" . $id_pregunta . "' value='$id_pregunta'>
                        <input type='radio' name='respuesta_" . $id_pregunta . "' value='NO'";

            if ($datos_respu[$key]['resp_respuesta'] == 'NO') {
                echo "checked>";
            }


            echo "            </td>";

            echo "</tr>";
        }
        ?>

                    <tr>
                        <td class='estilo_td2 texto_elegante2' colspan='4'>
        <?
        echo '<br>';
        echo 'Nombre: ' . $nombre . ' ' . $nombre2 . ' ' . $apellido . ' ' . $ap2;
        echo '<br>';
        echo 'Identificación: ' . $id;
        if ($tipo_id = 1)
            echo ' CC';
        ?>

                        </td>
                    </tr>

                    <tr>
                        <td class='estilo_td2 texto_elegante' colspan='4'>



                    <center>
                        <input id="registrarBoton" type="submit" class="navbtn"  value="Actualizar" > 
                    </center>

                    <input type='hidden' name='opcion' value='actualizarRespuestas'>
                    <input type='hidden' name='action' value='<? echo $this->formulario; ?>'>


                    <input type='hidden' name='func_documento' value='<? echo $id ?>'>
                    <input type='hidden' name='tipo_documento' value='<? echo $id_tipo ?>'>
                    <input type='hidden' name='annio' value='<? echo $vigencia ?>'>
                    <input type='hidden' name='contrato' value='<? echo $contrato ?>'>
                    <input type='hidden' name='fecha_registro' value='<? echo date('d/m/Y') ?>'>
                    <input type='hidden' name='id_encuesta' value='<? echo $id_encuesta ?>'>

                    <br>

                    <br>



        <? /* Para deshabilitar cambiar las respuestas, añadir disable a radio buttons
         * comentariar mètodos de envio de datos y descomentariar envio de formulario


          <center>


          <a href="<?
          $variable = 'pagina=asistenteTributario';
          $variable.='&opcion=';
          $variable = $this->cripto->codificar_url($variable, $this->configuracion);
          echo $this->indice . $variable;
          ?>" >
          <img alt="Ir a Formulario" src="<? echo $this->configuracion["host"] . $this->configuracion["site"] . $this->configuracion["bloques"] ?>/tributario/IconSummary-blue.jpg" /></a>
          </center>

          <br>


         */ ?>


                    </td>
                    </tr>


                </table>
            </form>       </center><?
        }

    }
?>