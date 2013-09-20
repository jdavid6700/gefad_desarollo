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

class html_adminDocumentosVinculacion_1 {

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

    function mostrarDatosBasicos($datosVinculacion, $datosContrato, $datosusuario, $datos_dir, $datos_tel) {
        
        echo 'aqui estamos!';
        
        ?>
        <script src="<? echo $this->configuracion["host"] . $this->configuracion["site"] . $this->configuracion["javascript"] ?>/jquery.js" type="text/javascript" language="javascript"></script>

        <table class="bordered" width="100%" border="0" align="center" cellpadding="4 px" cellspacing="1px" >



            <th class='titulo_th' colspan ="5" >FUNCIONARIO </th>

            <tbody>
                <tr>
                    <td align="center"  class='cuadro_plano '>
                        <table class="bordered"  width="100%">
                            <tr>
                                <td class='texto_elegante estilo_td2' width='20%' height="30">
                                    Tipo de documento:
                                </td>
                                <td class='texto_elegante estilo_td2' width='20%' align="left">
                                    <?
                                    if ($datosusuario[0]['PLA_TIPO_IDEN'] = 1)
                                        echo 'CC';
                                    ?>
                                </td>
                                <td class='texto_elegante estilo_td2' width='20%'>
                                    Identificaci&oacute;n:
                                </td>
                                <td class='texto_elegante estilo_td2' width='20%' align="left">
                                    <? echo $datosusuario[0]['PLA_NRO_IDEN']; ?>
                                </td>

                            </tr>

                            <tr>
                                <td class='texto_elegante estilo_td2' width='15%' height="30">
                                    Nombre:
                                </td>
                                <td class='texto_elegante estilo_td2' width='15%' align="left">
                                    <? echo $datosusuario[0]['PLA_NOMBRE1']; ?>
                                </td>
                                <td class='texto_elegante estilo_td2'  width=15%' height="30">
                                    Apellido:
                                </td>
                                <td class='texto_elegante estilo_td2' width='15%' align="left">
                                    <? echo $datosusuario[0]['PLA_APELLIDO1'] . ' ' . $datosusuario[0]['PLA_APELLIDO2']; ?>
                                </td>

                            </tr>
                            <tr>
                                <td class='texto_elegante estilo_td2' width='15%' height="30">
                                    Email:
                                </td>
                                <td class='texto_elegante estilo_td2' width='15%' align="left">
                                    <? echo $datosusuario[0]['PLA_EMAIL'] . '<br><br>'; ?>
                                </td>
                            </tr>
                            <tr>
                                <td class='texto_elegante estilo_td2' width='15%' height="30">
                                    Dirección:
                                </td>
                                <td class='texto_elegante estilo_td2' width='15%' align="left">
                                    <?
                                    if ($datosusuario[0]['PLA_DIRECC'] == "") {
                                        echo $datos_dir[0]['DATO_B_V_DIR'] . '<br><br>';
                                    } else {

                                        echo $datosusuario[0]['PLA_DIRECC'] . '<br><br>';
                                    }
                                    ?>
                                </td>
                                <td class='texto_elegante estilo_td2' width='15%' height="30">
                                    Teléfono:
                                </td>
                                <td class='texto_elegante estilo_td2' width='15%' align="left">
                                    <?
                                    if ($datosusuario[0]['PLA_TELE'] == "") {
                                        echo $datos_tel[0]['DATO_B_V_TEL'] . '<br><br>';
                                    } else {

                                        echo $datosusuario[0]['PLA_TELE'] . '<br><br>';
                                    }
                                    ?>
                                </td>
                            </tr>

                        </table>
                    </td>
                </tr>
            </tbody>

        </tr>
        </table>
        <div id="div_mensaje1" align="center" class="ab_name">
        </div>
        <?
    }

    function mostrarHistorialVinculacion($datosVinculacion, $datosContrato, $datosusuario,$datos_consulta) {
        ?>
        <table class="bordered" width="100%" >

            <th class='espacios_proyecto' colspan ="5"><? echo "<br>HISTORIAL DE VINCULACIONES - " . $datosusuario[0]['PLA_APELLIDO1'] . ' ' . $datosusuario[0]['PLA_APELLIDO2'] . ' ' . $datosusuario[0]['PLA_NOMBRE1'] . ' ' . $datosusuario[0]['PLA_NOMBRE2'] . '<br>    <br>'; ?></th>
            <tr>    <th class = 'subtitulo_th centrar' > Periodo </th>
                <th class = 'subtitulo_th centrar' > Tipo Vinculación</th>
                <th class = 'subtitulo_th centrar' > Estado</th>
                <th class = 'subtitulo_th centrar' > Resoluci&oacute;n/Contrato</th>
                <th class = 'subtitulo_th centrar' > Registrar Información Tributaria</th>
            </tr>
            <?
            //Impresión de vinculaciones como Funcionario de Planta

            if ($datosusuario[0]['PLA_ESTADO'] != " ") {
                ?>

                <tr >
                    <td width="10%" class='texto_elegante estilo_td' style="text-align:center;">
                        <? echo $anio = date('Y'); ?>
                    </td>

                    <td width="20%" class='texto_elegante estilo_td'>
                        <? echo $datosusuario[0]['VINCULACION']; ?>
                    </td>

                    <td width="15%" class='texto_elegante estilo_td' style="text-align:center;">
                        <?
                        echo $datosusuario[0]['PLA_ESTADO'];
                        ?>
                    </td>

                    <td width="20%" class='texto_elegante estilo_td'  style="text-align:center;">
                        <? echo $datosusuario[0]['PLA_RES']; ?>
                    </td>

                    <td width="20%" class='texto_elegante estilo_td' style="text-align:center;">
                        <?
                        if ($anio == date('Y')) {

                            if ($datos_consulta[0]['resp_annio'] == $anio) {
                                ?>   
                                <a href="
                                <?
                                $variable = 'pagina=encuestaTributario';
                                $variable.='&opcion=consultar';

                                $variable.='&vigencia=' . $anio;
                                $variable.='&vinculacion=' . $datosusuario[0]['VINCULACION'];
                                $variable.='&estado=' . $datosusuario[0]['PLA_ESTADO'];
                                $variable.='&contrato=' . $datosusuario[0]['PLA_RES'];

                                $variable.='&nombre=' . $datosusuario[0]['PLA_NOMBRE1'];
                                $variable.='&apellido=' . $datosusuario[0]['PLA_APELLIDO1'];
                                $variable.='&ap2=' . $datosusuario[0]['PLA_APELLIDO2'];
                                $variable.='&identificacion=' . $datosusuario[0]['PLA_NRO_IDEN'];
                                $variable.='&id_tipo=' . $datosusuario[0]['PLA_TIPO_IDEN'];

                                $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                                echo $this->indice . $variable;
                                ?>" >
                                    <img alt="Ir a Formulario" src="<? echo $this->configuracion["host"] . $this->configuracion["site"] . $this->configuracion["bloques"] ?>/tributario/magi_form.jpg" /></a> 
                            <? } else {
                                ?>   
                                <a href="
                                <?
                                $variable = 'pagina=encuestaTributario';
                                $variable.='&opcion=';

                                $variable.='&vigencia=' . $anio;
                                $variable.='&vinculacion=' . $datosusuario[0]['VINCULACION'];
                                $variable.='&estado=' . $datosusuario[0]['PLA_ESTADO'];
                                $variable.='&contrato=' . $datosusuario[0]['PLA_RES'];

                                $variable.='&nombre=' . $datosusuario[0]['PLA_NOMBRE1'];
                                $variable.='&apellido=' . $datosusuario[0]['PLA_APELLIDO1'];
                                $variable.='&ap2=' . $datosusuario[0]['PLA_APELLIDO2'];
                                $variable.='&identificacion=' . $datosusuario[0]['PLA_NRO_IDEN'];
                                $variable.='&id_tipo=' . $datosusuario[0]['PLA_TIPO_IDEN'];

                                $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                                echo $this->indice . $variable;
                                ?>" >
                                    <img alt="Ir a Formulario" src="<? echo $this->configuracion["host"] . $this->configuracion["site"] . $this->configuracion["bloques"] ?>/tributario/icon_form_1.jpg" /></a> 
                                <?
                            }
                            ?> 
                        </td>

                    </tr>

                    <?
                }
            }

            //Impresión de vinculaciones como Contratista
            if (is_array($datosContrato)) {
                foreach ($datosContrato as $key => $value) {
                    ?> <tr >
                        <td width="10%" class='texto_elegante estilo_td' style="text-align:center;">
                            <? echo $datosContrato[$key]['VIGENCIA'];
                            ?>
                        </td>

                        <td width="20%" class='texto_elegante estilo_td'>
                            <? echo $datosContrato[$key]['TIPO_CONTRATO']; ?>
                        </td>

                        <td width="15%" class='texto_elegante estilo_td'>
                            <?
                            echo '';
                            ?>
                        </td>

                        <td width="20%" class='texto_elegante estilo_td' style="text-align:center;">
                            <? echo $datosContrato[$key]['NUM_CONTRATO'] ?>
                        </td>

                        <td width="20%" height="26" class='texto_elegante estilo_td' style="text-align:center;">
                            <?
                            if ($datosContrato[$key]['VIGENCIA'] == date('Y')) {
                                if ($datos_consulta[$key]['resp_annio'] == $datosContrato[$key]['VIGENCIA']) {
                                    ?>
                                    <a href="
                                    <?
                                    $variable = 'pagina=encuestaTributario';
                                    $variable.='&opcion=consultar';

                                    $variable.='&vigencia=' . $datosContrato[$key]['VIGENCIA'];
                                    $variable.='&vinculacion=' . $datosContrato[$key]['TIPO_CONTRATO'];
                                    //$variable.='$estadoC=' .
                                    $variable.='&contrato=' . $datosContrato[$key]['NUM_CONTRATO'];

                                    $variable.='&nombre=' . $datosusuario[0]['PLA_NOMBRE1'];
                                    $variable.='&apellido=' . $datosusuario[0]['PLA_APELLIDO1'];
                                    $variable.='&ap2=' . $datosusuario[0]['PLA_APELLIDO2'];
                                    $variable.='&identificacion=' . $datosusuario[0]['PLA_NRO_IDEN'];
                                    $variable.='&id_tipo=' . $datosusuario[0]['PLA_TIPO_IDEN'];
                                    $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                                    echo $this->indice . $variable;
                                    ?>"> 
                                        <img alt="Ir a Formulario" src="<? echo $this->configuracion["host"] . $this->configuracion["site"] . $this->configuracion["bloques"] ?>/tributario/magi_form.jpg" /></a> 
                                    <?
                                } else {
                                    ?>
                                    <a href="
                                    <?
                                    $variable = 'pagina=encuestaTributario';
                                    $variable.='&opcion=';

                                    $variable.='&vigencia=' . $datosContrato[$key]['VIGENCIA'];
                                    $variable.='&vinculacion=' . $datosContrato[$key]['TIPO_CONTRATO'];
                                    //$variable.='$estadoC=' .
                                    $variable.='&contrato=' . $datosContrato[$key]['NUM_CONTRATO'];

                                    $variable.='&nombre=' . $datosusuario[0]['PLA_NOMBRE1'];
                                    $variable.='&apellido=' . $datosusuario[0]['PLA_APELLIDO1'];
                                    $variable.='&ap2=' . $datosusuario[0]['PLA_APELLIDO2'];
                                    $variable.='&identificacion=' . $datosusuario[0]['PLA_NRO_IDEN'];
                                    $variable.='&id_tipo=' . $datosusuario[0]['PLA_TIPO_IDEN'];
                                    $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                                    echo $this->indice . $variable;
                                    ?>"> 
                                        <img alt="Ir a Formulario" src="<? echo $this->configuracion["host"] . $this->configuracion["site"] . $this->configuracion["bloques"] ?>/tributario/icon_form_1.jpg" /></a> 
                                    <?
                                }
                                ?> 
                            </td>

                        </tr>

                        <?
                    }
                }
            }

            //Impresión de vinculaciones como Docente
            if (is_array($datosVinculacion)) {

                foreach ($datosVinculacion as $key => $value) {
                    ?> <tr >
                        <td width="10%" class='texto_elegante estilo_td' style="text-align:center;">
                            <? echo $datosVinculacion[$key]['VIN_ANIO']; ?>
                        </td>

                        <td width="20%" class='texto_elegante estilo_td'>
                            <? echo $datosVinculacion[$key]['VIN_NOMBRE']; ?>
                        </td>

                        <td width="5%" class='texto_elegante estilo_td' style="text-align:center;">
                            <? echo $datosVinculacion[$key]['VIN_ESTADO']; ?>
                        </td>

                        <td width="15%" class='texto_elegante estilo_td'  style="text-align:center;">
                            <?
                            if ($datosVinculacion[$key]['VIN_COD'] == 1 || $datosVinculacion[$key]['VIN_COD'] == 8 || $datosVinculacion[$key]['VIN_COD'] == 6)
                                echo $datosusuario[0]['PLA_RES'];
                            ?>
                        </td>

                        <td width="20%" height="26" class='texto_elegante estilo_td' style="text-align:center;">
                            <?
                            if ($datosVinculacion[$key]['VIN_ANIO'] == date('Y')) {
                                if ($datos_consulta[0]['resp_annio'] == $datosVinculacion[$key]['VIN_ANIO']) {
                                    ?>   
                                    <a href="<?
                        $variable = 'pagina=encuestaTributario';
                        $variable.='&opcion=consultar';

                        $variable.='&vigencia=' . $datosVinculacion[$key]['VIN_ANIO'];
                        $variable.='&vinculacion=' . $datosVinculacion[$key]['VIN_NOMBRE'];
                        $variable.='&estado=' . $datosVinculacion[$key]['VIN_ESTADO'];
                        $variable.='&contrato=' . $datosusuario[0]['PLA_RES'];

                        $variable.='&nombre=' . $datosusuario[0]['PLA_NOMBRE1'];
                        $variable.='&apellido=' . $datosusuario[0]['PLA_APELLIDO1'];
                        $variable.='&ap2=' . $datosusuario[0]['PLA_APELLIDO2'];
                        $variable.='&identificacion=' . $datosusuario[0]['PLA_NRO_IDEN'];
                        $variable.='&id_tipo=' . $datosusuario[0]['PLA_TIPO_IDEN'];

                        $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                        echo $this->indice . $variable;
                                    ?>" >    
                                        <img alt="Ir a Formulario" src="<? echo $this->configuracion["host"] . $this->configuracion["site"] . $this->configuracion["bloques"] ?>/tributario/magi_form.jpg" /></a> 
                                </td>

                            </tr>

                            <?
                        } else {
                            ?>   
                            <a href="<?
                        $variable = 'pagina=encuestaTributario';
                        $variable.='&opcion=';

                        $variable.='&vigencia=' . $datosVinculacion[$key]['VIN_ANIO'];
                        $variable.='&vinculacion=' . $datosVinculacion[$key]['VIN_NOMBRE'];
                        $variable.='&estado=' . $datosVinculacion[$key]['VIN_ESTADO'];
                        $variable.='&contrato=' . $datosusuario[0]['PLA_RES'];

                        $variable.='&nombre=' . $datosusuario[0]['PLA_NOMBRE1'];
                        $variable.='&apellido=' . $datosusuario[0]['PLA_APELLIDO1'];
                        $variable.='&ap2=' . $datosusuario[0]['PLA_APELLIDO2'];
                        $variable.='&identificacion=' . $datosusuario[0]['PLA_NRO_IDEN'];
                        $variable.='&id_tipo=' . $datosusuario[0]['PLA_TIPO_IDEN'];

                        $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                        echo $this->indice . $variable;
                            ?>" >    
                                <img alt="Ir a Formulario" src="<? echo $this->configuracion["host"] . $this->configuracion["site"] . $this->configuracion["bloques"] ?>/tributario/icon_form_1.jpg" /></a> 
                        </td>

                        </tr>

                        <?
                    }
                }
            }
        }
        echo '<br>';
        ?>

        </table>
        <?
    }

}
?>
