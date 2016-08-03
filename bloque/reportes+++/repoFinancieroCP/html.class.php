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

class html_reporteFinanciero {

    public $configuracion;
    public $cripto;
    public $indice;

    function __construct($configuracion) {

        $this->configuracion = $configuracion;
        include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
        include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/html.class.php");
        $indice = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
        $this->cripto = new encriptar();
        $this->html = new html();
    }

    function bienvenida() {
        echo "<br>Bienvenido al Módulo de Reportes del Sistema de Gestión Financiera";
    }
    
    

    /**
     * Funcion que muestra el formulario para seleccionar los parametros del reporte
     * @param <array> $vigencias
     */
    function form_muestra_parametros($parametrosHtml) {
        $tab = 0;
        $this->formulario = "repoFinanciero";
        ?>

  <link href="<? echo $this->configuracion["host"] . $this->configuracion["site"] . $this->configuracion["bloques"]; ?>/reportes/repoFinanciero/estilo_repoFinanciero.css"
            rel="stylesheet" type="text/css" />


        <table width="100%" align="center" border="0" cellpadding="10" cellspacing="0">
            <tr class="texto_subtitulo">
                <td><? echo $parametrosHtml[0]['titulo']; ?><br>
                    <hr class="hr_subtitulo">
                </td>
            </tr>       
            <tbody> 
                <tr>
                    <td>


                        <form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario; ?>'>
                            <table align="center"  border="0" width="98%" >

        <?
        foreach ($parametrosHtml as $key => $value) {
            ?><tr> 
                                        <td class='texto_elegante estilo_td' valign='top' width="120px" >
            <? echo ucfirst($parametrosHtml[$key]['nombre']) . ": "; ?>   
                                        </td> 
                                        <td class='texto_elegante estilo_td' valign='top' >
                                    <?
                                    //verifica que tipo de caja debe armar
                                    switch ($parametrosHtml[$key]['caja_html']) {
                                        case 'combo':
                                            unset($combo);
                                            //prepara los datos como se deben mostrar en el combo
                                            $combo[0][0] = '0';
                                            $combo[0][1] = 'Todos';
                                            foreach ($parametrosHtml[$key]['datos'] as $cmb => $values) {
                                                $combo[$cmb + 1][0] = isset($parametrosHtml[$key]['datos'][$cmb][0]) ? $parametrosHtml[$key]['datos'][$cmb][0] : 0;
                                                $combo[$cmb + 1][1] = isset($parametrosHtml[$key]['datos'][$cmb][1]) ? $parametrosHtml[$key]['datos'][$cmb][1] : '';
                                            }
                                            if (isset($_REQUEST[$parametrosHtml[$key]['nombre']])) {
                                                $lista_combo = $this->html->cuadro_lista($combo, $parametrosHtml[$key]['nombre'], $this->configuracion, $_REQUEST[$parametrosHtml[$key]['nombre']], 0, FALSE, $tab++, $parametrosHtml[$key]['nombre']);
                                            } else {
                                                $lista_combo = $this->html->cuadro_lista($combo, $parametrosHtml[$key]['nombre'], $this->configuracion, 0, 0, FALSE, $tab++, $parametrosHtml[$key]['nombre']);
                                            }
                                            echo $lista_combo;

                                            break;

                                        default:
                                            echo $this->html->cuadro_texto($parametrosHtml[$key]['nombre'], $this->configuracion, $parametrosHtml[$key]['datos'], '', 0, '', 20, 25, "");
                                            break;
                                    }
                                    ?></td>
                                    </tr> 
                                        <? } ?>
                                <tr>

                                    <td class='estilo_td' align='center' colspan ="2"><br> 
                                        <input type='hidden' name='action' value='repoFinanciero'>
                                        <input type='hidden' name='opcion' value='generar'> 
                                        <input type='hidden' name='reporte' value='<? echo $parametrosHtml[0]['reporte']; ?>'> 
                                        <input type='hidden' name='pagina' value='<? echo $parametrosHtml[0]['pagina']; ?>'> 
                                        <input value="Generar" name="generar_reporte" tabindex="<?= $tab++ ?>" type="button" onclick="document.forms['<? echo $this->formulario ?>'].submit()">
                                    </td>
                                </tr>
                            </table>
                        </form>
                    </td>
                </tr>
            </tbody>
        </table>
        <?
    }

    /**
     * funcion que muestra la información del reporte
     */
    function mostrarReportes($configuracion, $registro, $nombre, $titulo) {
        include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/reporteadorHtml.class.php");
        $reporte = new reporteador();
        $reporte->mostrarReporte($configuracion, $registro, $nombre, $titulo);
    }

    /**
     * funcion que muestra la información del reporte
     */
    function sinDatos($configuracion, $titulo) {
        include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/alerta.class.php");
        $cadena = ".::" . $titulo . "::.";
        $cadena = htmlentities($cadena, ENT_COMPAT, "UTF-8");
        alerta::sin_registro($configuracion, $cadena);
    }

}
?>