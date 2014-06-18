<?php

/* --------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
  --------------------------------------------------------------------------------------------------------------------------- */
/* ---------------------------------------------------------------------------------------
  |				Control Versiones				    	|
  ----------------------------------------------------------------------------------------
  | fecha      |        Autor            | version     |              Detalle            |
  ----------------------------------------------------------------------------------------
  | 09/07/2013 | Violeta Sosa            | 0.0.0.1     |                                 |
  ----------------------------------------------------------------------------------------
  | 00/03/2014 | Violeta Sosa            | 0.0.0.2     |                                 |
  ----------------------------------------------------------------------------------------
 */


if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/funcionGeneral.class.php");
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/navegacion.class.php");
include_once("html.class.php");

class funciones_liquidador extends funcionGeneral {

    function __construct($configuracion, $sql) {
//[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo		
//include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
        include ($configuracion["raiz_documento"] . $configuracion["estilo"] . "/basico/tema.php");
        include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
        include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/log.class.php");
        include_once($configuracion["raiz_documento"] . $configuracion["plugins"] . "/html2pdf/html2pdf.class.php");


        $this->cripto = new encriptar();
        $this->log_us = new log();
        $this->sql = $sql;

//Conexion General
        $this->acceso_db = $this->conectarDB($configuracion, "mysqlFrame");
//Conexión a Postgres
        $this->acceso_pg = $this->conectarDB($configuracion, "cuotas_partes");
//Conexión a Oracle
        $this->acceso_Oracle = $this->conectarDB($configuracion, "cuotasP");

//Datos de sesion
        $this->usuario = $this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion = $this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
        $this->configuracion = $configuracion;

        $this->html_liquidador = new html_liquidador($configuracion);
    }

    /* __________________________________________________________________________________________________

      Metodos específicos
      __________________________________________________________________________________________________ */

//datos basicos para liquidar
    function datosIniciales() {
        $this->html_liquidador->formularioDatos();
    }

    function generarPDF_Cuenta($datos_basicos, $totales_liquidacion, $enletras, $consecutivo, $jefeRecursos, $jefeTesoreria) {

        ob_start();
        $direccion = $this->configuracion['host'] . $this->configuracion['site'] . $this->configuracion['bloques'];

        $dias = array('Domingo, ', 'Lunes, ', 'Martes, ', 'Miercoles, ', 'Jueves, ', 'Viernes, ', 'Sábado, ');
        $meses = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
        $fecha_cc = $dias[date('w')] . ' ' . date('d') . ' de ' . $meses[date('n') - 1] . ' del ' . date('Y');


        $ContenidoPdf = "
<style type=\"text/css\">
    table { 
        color:#333; /* Lighten up font color */
        font-family:Helvetica, Arial, sans-serif; /* Nicer font */

        border-collapse:collapse; border-spacing: 3px; 
    }

    table.page_header {width: 100%; border: none; background-color: #DDDDFF; border-bottom: solid 1mm #AAAADD; padding: 2mm }
    table.page_footer {width: 100%; border: none; background-color: #DDDDFF; border-top: solid 1mm #AAAADD; padding: 2mm}

    td, th { 
        border: 1px solid #CCC; 
        height: 13px;
    } /* Make cells a bit taller */

    th {
        background: #F3F3F3; /* Light grey background */
        font-weight: bold; /* Make sure they're bold */
        text-align: center;
        font-size:10px
    }

    td {
        background: #FAFAFA; /* Lighter grey background */
        text-align: left;
        font-size:10px
    }
</style>
<page backtop='60mm' backbottom='20mm' backleft='3mm' backright='3mm' pagegroup='new'>
<page_header>
    <table align='center'>
        <thead>
            <tr>
                <th style=\"width:10px;\" colspan=\"1\">
                    <img alt=\"Imagen\" src=" . $direccion . "/nomina/cuotas_partes/Images/escudo1.png\" />
                </th>
                <th style=\"width:520px;font-size:13px;\" colspan=\"1\">
                    <br>UNIVERSIDAD DISTRITAL FRANCISCO JOSÉ DE CALDAS
                    <br> NIT 899999230-7<br>
                    <br> DIVISIÓN DE RECURSOS HUMANOS<br><br>
                </th>
                <th style=\"width:130px;font-size:10px;\" colspan=\"1\">
                    <br>CUENTA DE COBRO
                    <br>No." . $consecutivo . "<br>
                    <br>" . $fecha_cc . "<br><br>
                </th>
            </tr>
        </thead>               <tr>
                        <td>Entidad Concurrente:</td>
                        <td colspan='2'>" . '&nbsp;&nbsp;' . $datos_basicos['entidad_nombre'] . "</td>
                    </tr>
                    <tr> 
                        <td>NIT:</td>
                        <td   colspan='2'>" . '&nbsp;&nbsp;' . $datos_basicos['entidad'] . "</td>
                    </tr>
                    <tr> 
                        <td>Fecha Vencimiento Cuenta:</td>
                        <td   colspan=\"2\">&nbsp;&nbsp; 30 días calendario a partir de la fecha de recibido</td>
                    </tr>
    </table>  
    <br>
     <table align='center'>
                    <tr>
                        <td>Nombre Pensionado:</td>
                        <td style=\"width:280px;\">" . '&nbsp;&nbsp;' . $datos_basicos['nombre_emp'] . "</td>
                        <td>Documento Pensionado:</td>
                        <td style=\"width:280px;\">" . '&nbsp;&nbsp;' . $datos_basicos['cedula'] . "</td>
                    </tr>
                    <tr>
                        <td>Nombre Sustituto:</td>
                        <td style=\"width:280px;\"></td>
                        <td>Documento Sustituto:</td>
                        <td style=\"width:280px;\" ></td>
                    </tr>
                </table>
</page_header>

<page_footer>
    <table align='center' width='100%'>
        <tr>
            <td align='center' style=\"width: 750px;\">
                Universidad Distrital Francisco José de Caldas
                <br>
                Todos los derechos reservados.
                <br>
                Carrera 8 N. 40-78 Piso 1 / PBX 3238400 - 3239300, Ext. 1618 - 1603
                <br>

            </td>
        </tr>
    </table>
     <p style=\"font-size:7px\">Diseño forma: JUAN D. CALDERON MARTIN</p>
        <p style='text-align: right; font-size:10px;'>[[page_cu]]/[[page_nb]]</p>
</page_footer> 
                   <table align='center'>
                    <tr>
                        <th>Item</th>
                        <th>Descripción</th>
                        <th>DESDE</th>
                        <th>HASTA</th>
                        <th>Valor</th>
                    </tr>
                    <tr>
                        <td style=\"text-align:center;\">1</td>
                        <td>Cuotas Partes Pensionales (mesadas ordinarias y adicionales)</td>
                        <td style=\"text-align:center;\">" . '&nbsp;&nbsp;' . $totales_liquidacion[0]['liq_fdesde'] . "</td>
                        <td style=\"text-align:center;\">" . '&nbsp;&nbsp;' . $totales_liquidacion[0]['liq_fhasta'] . "</td>
                        <td>" . '&nbsp;$&nbsp;' . number_format($totales_liquidacion[0]['liq_cuotap'] + $totales_liquidacion[0]['liq_mesada_ad']) . "</td>
                    </tr>
                    <tr>
                        <td style=\"text-align:center;\">2</td>
                        <td>Incremento en Cotización Salud</td>
                        <td style=\"text-align:center;\">" . '&nbsp;&nbsp;' . $totales_liquidacion[0]['liq_fdesde'] . "</td>
                        <td style=\"text-align:center;\">" . '&nbsp;&nbsp;' . $totales_liquidacion[0]['liq_fhasta'] . "</td>
                        <td>" . '&nbsp;$&nbsp;' . number_format($totales_liquidacion[0]['liq_incremento']) . "</td>
                    </tr>
                    <tr>
                        <td style=\"text-align:center;\">3</td>
                        <td>Valor Intereses Ley 68/1923</td>
                        <td style=\"text-align:center;\">" . '&nbsp;&nbsp;' . $totales_liquidacion[0]['liq_fdesde'] . "</td>
                        <td style=\"text-align:center;\">" . '&nbsp;&nbsp;' . $totales_liquidacion[0]['liq_fhasta'] . "</td>
                        <td>" . '&nbsp;$&nbsp;' . number_format($totales_liquidacion[0]['liq_interes_a2006']) . "</td>
                    </tr>
                     <tr>
                        <td style=\"text-align:center;\">4</td>
                        <td>Valor Intereses Ley 1066/2006</td>
                        <td style=\"text-align:center;\">" . '&nbsp;&nbsp;' . $totales_liquidacion[0]['liq_fdesde'] . "</td>
                        <td style=\"text-align:center;\">" . '&nbsp;&nbsp;' . $totales_liquidacion[0]['liq_fhasta'] . "</td>
                        <td>" . '&nbsp;$&nbsp;' . number_format($totales_liquidacion[0]['liq_interes_d2006']) . "</td>
                    </tr>
          
             <tr>
                        <th   style=\"text-align:right;\" colspan=\"4\">TOTAL&nbsp;&nbsp;</th>
                        <td >" . '&nbsp;$&nbsp;' . number_format($totales_liquidacion[0]['liq_total']) . "</td>
            </tr>
            <tr>
            <td style=\"width:750px;text-align:center;\" colspan=\"45\">SON&nbsp;" . $enletras . "</td>
            </tr>
            </table><br>             
            <table align='center'>
                    <tr>
                        <td   align:justify style=\"font-size:12px;width:650px;\" colspan=\"2\">
                            El (La) Jefe de la División de Recursos Humanos y la (el) Tesorero (a) de 
                            la UNIVERSIDAD DISTRITAL FRANCISCO JOSE DE CALDAS, certifican que  la persona 
                            por quien se realiza este cobro se encuentra incluida en nomina  de pensionados y se le ha pagado las mesadas cobradas.
                            La supervivencia fue verificada de conformidad con el articulo 21 del Decreto 19 de 2012.</td>
                    </tr>
                    <tr>
                        <td   align=justify style=\"font-size:12px;width:650px;text-align:justify;\" colspan=\"2\">
                            La suma adeudada debe ser consignada (en efectivo, cheque de gerencia o transferencia electronica) en la Cuenta 
                            de Ahorros No 251–80660–0 del Banco de Occcidente, a nombre del FONDO DE PENSIONES UNIVERSIDAD DISTRITAL y remitir 
                            copia de la misma a la carrera 7 Nº 40-53, piso 6, Division de Recursos Humanos y al correo electronico rechumanos@udistrital.edu.co.
                            <br><br>
                            En caso de haber pagado parcial o totalmente esta cuenta, favor descontar el valor de dicho abono (s) del presente 
                            cobro y remitir el (los) comprobante (s) del (los) pago (s) realizado (s).</td>
                    </tr>
                    <tr>
                        <td   align=justify style=\"font-size:12px;\" >
                            <br><br><br><br>
                        </td>
                        <td   align=justify style=\"font-size:12px;\">
                            <br><br><br><br>
                        </td>
                    </tr>
                    <tr>
                        <td   align=center style=\"width:375px;text-align:center;\">
        " . $jefeTesoreria . "
                            <br>Tesorero(a)
                        </td>
                        <td   align=center style=\"width:375px;text-align:center;\">
        " . $jefeRecursos . "
                            <br>Jefe(a) División de Recursos Humanos
                        </td>
                    </tr>
                </table>

</page>
              

";

        $PDF = new HTML2PDF('P', 'Letter', 'es', true, 'UTF-8', 3);
        $PDF->pdf->SetDisplayMode('fullpage');
        $PDF->writeHTML($ContenidoPdf);
        clearstatcache();
        $PDF->Output("CuentadeCobro_" . $datos_basicos['cedula'] . "_" . $datos_basicos['entidad_nombre'] . ".pdf", "D");


        $this->guardar_cuenta($datos_basicos, $consecutivo, $totales_liquidacion);
    }

    function generarPDF_Resumen($datos_basicos, $consecutivo, $datos_concurrencia, $datos_pensionado, $liquidacion_anual, $dias_cargo, $jefeRecursos, $total_dias) {

        ob_start();
        $direccion = $this->configuracion['host'] . $this->configuracion['site'] . $this->configuracion['bloques'];

        $dias = array('Domingo, ', 'Lunes, ', 'Martes, ', 'Miercoles, ', 'Jueves, ', 'Viernes, ', 'Sábado, ');
        $meses = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
        $fecha_cc = $dias[date('w')] . ' ' . date('d') . ' de ' . $meses[date('n') - 1] . ' del ' . date('Y');

        $total = 0;
        $contenido = '';
        foreach ($liquidacion_anual as $key => $values) {
            $contenido.= " <tr> ";
            $contenido.= "<td   colspan='1'>" . $liquidacion_anual[$key]['vigencia'] . "</td> ";
            $contenido.= "<td  >&nbsp;$&nbsp;" . number_format($liquidacion_anual[$key]['mesada']) . "</td> ";
            $contenido.= "<td  >&nbsp;$&nbsp;" . number_format($liquidacion_anual[$key]['cuota_parte']) . "</td> ";
            $contenido.= "<td  >&nbsp;$&nbsp;" . number_format($liquidacion_anual[$key]['mesada_adc']) . "</td> ";
            $contenido.= "<td  >&nbsp;$&nbsp;" . number_format($liquidacion_anual[$key]['incremento']) . "</td> ";
            $contenido.= "<td  >&nbsp;$&nbsp;" . number_format($liquidacion_anual[$key]['interes_a2006']) . "</td> ";
            $contenido.= "<td  >&nbsp;$&nbsp;" . number_format($liquidacion_anual[$key]['interes_d2006']) . "</td> ";
            $contenido.= "<td  >&nbsp;$&nbsp;" . number_format($liquidacion_anual[$key]['total']) . "</td> ";
            $contenido.= "</tr> ";
            $total = $liquidacion_anual[$key]['total'] + $total;
        }



        $ContenidoPdf = "
<style type=\"text/css\">
    table { 
        color:#333; /* Lighten up font color */
        font-family:Helvetica, Arial, sans-serif; /* Nicer font */

        border-collapse:collapse; border-spacing: 3px; 
    }

    table.page_header {width: 100%; border: none; background-color: #DDDDFF; border-bottom: solid 1mm #AAAADD; padding: 2mm }
    table.page_footer {width: 100%; border: none; background-color: #DDDDFF; border-top: solid 1mm #AAAADD; padding: 2mm}

    td, th { 
        border: 1px solid #CCC; 
        height: 13px;
    } /* Make cells a bit taller */

    th {
        background: #F3F3F3; /* Light grey background */
        font-weight: bold; /* Make sure they're bold */
        text-align: center;
        font-size:10px
    }

    td {
        background: #FAFAFA; /* Lighter grey background */
        text-align: left;
        font-size:10px
    }
</style>
<page backtop='95mm' backbottom='20mm' backleft='3mm' backright='3mm' pagegroup='new'>
<page_header>
    <table align='center'>
        <thead>
            <tr>
                <th style=\"width:60px;\" colspan=\"1\">
                    <img alt=\"Imagen\" src=" . $direccion . "/nomina/cuotas_partes/Images/escudo1.png\" />
                </th>
                <th style=\"width:490px;font-size:13px;\" colspan=\"1\">
                    <br>UNIVERSIDAD DISTRITAL FRANCISCO JOSÉ DE CALDAS
                    <br> NIT 899999230-7<br>
                    <br> DIVISIÓN DE RECURSOS HUMANOS<br><br>
                </th>
                <th style=\"width:160px;font-size:10px;\" colspan=\"1\">
                    <br>RESUMEN CUENTA DE COBRO
                    <br>No." . $consecutivo . "<br>
                    <br>" . $fecha_cc . "<br><br>
                </th>
            </tr>
        </thead>      
        <tr>
            <td >Entidad Concurrente:</td>
            <td colspan='2'>" . $datos_basicos['entidad_nombre'] . "</td>
        </tr>
        <tr> 
            <td >NIT:</td>
            <td colspan='2'>" . $datos_basicos['entidad'] . "</td>
        </tr>
    </table>  
    <br>
    <table align='center' style=\"width: 750px;\">
    <tr>
        <th colspan=\"8\" style=\"width: 750px;\">DATOS PENSIONADO - PENSIÓN</th>
    </tr>
    <tr>
        <td colspan='3'>Nombres y Apellidos del Titular:</td>
        <td colspan='5'>" . $datos_basicos['nombre_emp'] . "</td>
    </tr>
    <tr>
        <td colspan='3'>Documento:</td>
        <td colspan='5'>" . $datos_basicos['cedula'] . "</td>
    </tr>
    <tr>
        <td colspan='3'>Fecha de Nacimiento:</td>
        <td colspan='5'>" . $datos_pensionado[0]['FECHA_NAC'] . "</td>
    </tr>
    <tr>
        <td colspan='3'>Resolución Reconocimiento Concurrencia:</td>
        <td colspan='5'>" . $datos_concurrencia[0]['dcp_actoadmin'] . "</td>
    </tr>
    <tr>
        <td colspan='3'>Fecha de Efectividad:</td>
        <td colspan='5'>" . date('d/m/Y', strtotime(str_replace('/', '-', $datos_concurrencia[0]['dcp_factoadmin']))) . "</td>
    </tr>
    <tr>
        <td colspan='3'>Fecha Inicio de Concurrencia:</td>
        <td colspan='5'>" . date('d/m/Y', strtotime(str_replace('/', '-', $datos_concurrencia[0]['dcp_fecha_concurrencia']))) . "</td>
    </tr>
    <tr>
        <td colspan='1'>Días a Cargo:</td>
        <td colspan='2'>" . $dias_cargo . "</td>
        <td colspan='1'>Total Días</td>
        <td colspan='2'>" . $total_dias . "</td>
        <td colspan='1'>Porcentaje Ingresado:</td>
        <td colspan='1'>" . (($datos_concurrencia[0]['dcp_porcen_cuota']) * 100) . '&nbsp;%' . "</td>
        " . /* <td colspan='1'>Porcentaje Calculado:</td>
                  <td colspan='1'>" . \round(((($dias_cargo / $total_dias)) * 100), 3) . '&nbsp;%' . "</td> */"
    </tr>
    <tr>
        <td colspan='2'>Mesada Inicial:</td>
        <td colspan='1'>" . number_format($datos_concurrencia[0]['dcp_valor_mesada']) . "</td>
        <td colspan='2'>Cuota Parte:</td>
        <td colspan='1'>" . number_format($datos_concurrencia[0]['dcp_valor_cuota']) . "</td>
        <td colspan='2'></td>
    </tr>
    <tr>
        <td colspan='3'>Resolución que modifica o reliquida:</td>
        <td colspan='5'></td>
    </tr>
</table>
</page_header>
<page_footer>
    <table align='center' width='100%'>
        <tr>
            <td align='center' style=\"width: 750px;\">
                Universidad Distrital Francisco José de Caldas
                <br>
                Todos los derechos reservados.
                <br>
                Carrera 8 N. 40-78 Piso 1 / PBX 3238400 - 3239300, Ext. 1618 - 1603
                <br>
            </td>
        </tr>
    </table>
     <p style=\"font-size:7px\">Diseño forma: JUAN D. CALDERON MARTIN</p>
        <p style='text-align: right; font-size:10px;'>[[page_cu]]/[[page_nb]]</p>
</page_footer>

<br>
<table align='center' >
    <tr>
        <th colspan=\"1\" width=\"5%\">PERIODO</th>
        <th rowspan=\"2\" width=\"13.5%\">MONTO DE MESADA</th>
        <th rowspan='2' width=\"13.5%\">CUOTA MENSUAL</th>
        <th rowspan='2' width=\"13.5%\">MESADA ADICIONAL</th>
        <th rowspan='2' width=\"13.5%\">INCREMENTO SALUD (7%)</th>
        <th rowspan='2' width=\"13.5%\">INTERÉS LEY 68/1923</th>
        <th rowspan='2' width=\"13.5%\">INTERÉS LEY 1066/2006</th>
        <th rowspan='2' width=\"13.5%\">TOTAL AÑO</th>
    </tr>
    <tr>
        <th colspan=\"1\">AÑO</th>
    </tr>
    " . $contenido . "
    <tr>
        <th  style=\"text-align:right;\" colspan=\"6\">Valor liquidado a la fecha de corte&nbsp;&nbsp;</th>
        <td colspan=\"3\">&nbsp;$&nbsp;" . number_format($total) . "</td>
    </tr>
    <tr>
        <th  style=\"text-align:right;\" colspan=\"6\">Ajuste al peso&nbsp;&nbsp;</th>
        <td colspan=\"3\">&nbsp;$&nbsp;" . number_format($total) . "</td>
    </tr>
    <tr>
        <th  style=\"text-align:right;\" colspan=\"6\">VALOR A COBRAR&nbsp;&nbsp;</th>
        <td colspan=\"3\">&nbsp;$&nbsp;" . number_format($total) . "</td>
    </tr>
</table>
<br>
<table align='center'>
    <tr>
        <td style=\"text-align:center; width: 750px;\"><br><br><br><br>
        </td>
    </tr>
    <tr>  
        <td align='center' style=\"text-align:center; width: 750px;\" >
            " . $jefeRecursos . "
            <br>
            Jefe(a) División de Recursos Humanos
            <br>

        </td>
    </tr>
</table>
<br><br>
</page>
 ";

        $PDF = new HTML2PDF('P', 'Letter', 'es', true, 'UTF-8', 3);
        $PDF->pdf->SetDisplayMode('fullpage');
        $PDF->writeHTML($ContenidoPdf);
        clearstatcache();
        $PDF->Output("CuentaResumen_" . $datos_basicos['cedula'] . "_" . $datos_basicos['entidad_nombre'] . ".pdf", "D");
    }

    function generarPDF_Detalle($datos_basicos, $liquidacion, $totales_liquidacion, $consecu_cc, $detalle_indice, $fecha_cobro, $jefeRecursos) {

        ob_start();
        $direccion = $this->configuracion['host'] . $this->configuracion['site'] . $this->configuracion['bloques'];

        $dias = array('Domingo, ', 'Lunes, ', 'Martes, ', 'Miercoles, ', 'Jueves, ', 'Viernes, ', 'Sábado, ');
        $meses = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
        $fecha_cc = $dias[date('w')] . ' ' . date('d') . ' de ' . $meses[date('n') - 1] . ' del ' . date('Y');

        $total = 0;
        $contenido = '';
        if (is_array($liquidacion)) {
            foreach ($liquidacion as $key => $value) {
                $contenido.="<tr>";
                $contenido.="<td style='text-align:center;'>" . date('Y-m', strtotime(str_replace('/', '-', $liquidacion[$key]['fecha']))) . "</td>";
                $contenido.="<td style='text-align:center;'>$" . number_format($liquidacion[$key]['mesada']) . "</td>";
                $contenido.="<td style='text-align:center;'>$" . number_format($liquidacion[$key]['cuota_parte']) . "</td>";
//$contenido.="<td style='text-align:center;' > " . $liquidacion[$key]['ajuste_pension'] . "</td>";
                $contenido.="<td style='text-align:center;'>" . number_format($liquidacion[$key]['mesada_adc']) . "</td>";
                $contenido.="<td style='text-align:center;'>" . number_format($liquidacion[$key]['incremento']) . "</td>";
                $contenido.="<td style='text-align:center;'>$" . number_format($liquidacion[$key]['interes_a2006']) . "</td>";
                $contenido.="<td style='text-align:center;'>$" . number_format($liquidacion[$key]['interes_d2006']) . "</td>";
                $contenido.="<td style='text-align:center;'>$" . number_format($liquidacion[$key]['total']) . "</td>";
                $contenido.="</tr>";
            }
        } else {
            $contenido.="<tr>";
            $contenido.="<td style='text-align:center;'></td>";
//$contenido.="<td style='text-align:center;'></td>";
            $contenido.="<td style='text-align:center;'></td>";
            $contenido.="<td style='text-align:center;'></td>";
            $contenido.="<td style='text-align:center;'></td>";
            $contenido.="<td style='text-align:center;'></td>";
            $contenido.="<td style='text-align:center;'></td>";
            $contenido.="<td style='text-align:center;'></td>";
            $contenido.="</tr>";
        }

        $total2 = 0;

        $contenido2 = '';
        if (is_array($totales_liquidacion)) {

            foreach ($totales_liquidacion as $key => $values) {
                $contenido2.="<tr>";
                $contenido2.="<td style='text-align:center;'>$ " . number_format($totales_liquidacion[$key]['liq_cuotap']) . "</td>";
//$contenido2.="<td style='text-align:center;'>$ " . number_format($totales_liquidacion[$key]['liq_ajustepen']) . "</td>";
                $contenido2.="<td style='text-align:center;'>$ " . number_format($totales_liquidacion[$key]['liq_mesada_ad']) . "</td>";
                $contenido2.="<td style='text-align:center;'>$ " . number_format($totales_liquidacion[$key]['liq_incremento']) . "</td>";
                $contenido2.="<td style='text-align:center;'>$ " . number_format($totales_liquidacion[$key]['liq_interes_a2006']) . "</td>";
                $contenido2.="<td style='text-align:center;'>$ " . number_format($totales_liquidacion[$key]['liq_interes_d2006']) . "</td>";
                $contenido2.="<td style='text-align:center;'>$ " . number_format($totales_liquidacion[$key]['liq_total']) . "</td>";
                $contenido2.="</tr>";
                $total2[$key] = $totales_liquidacion[$key][12];
            }
        } else {
            $contenido2.="<tr>";
            $contenido2.="<td style='text-align:center;'></td>";
// $contenido2.="<td style='text-align:center;'></td>";
            $contenido2.="<td style='text-align:center;'></td>";
            $contenido2.="<td style='text-align:center;'></td>";
            $contenido2.="<td style='text-align:center;'></td>";
            $contenido2.="<td style='text-align:center;'></td>";
            $contenido2.="<td style='text-align:center;'></td>";
            $contenido2.="</tr>";
        }

        $contenido3 = '';
        foreach ($detalle_indice as $key => $values) {
            $contenido3.="<tr>";
            $contenido3.="<td style='text-align:center;'>" . $detalle_indice[$key]['vigencia'] . "</td> ";
            $contenido3.="<td style='text-align:center;'>" . $detalle_indice[$key]['ipc'] . "</td> ";
            $contenido3.="<td style='text-align:center;'>" . $detalle_indice[$key]['suma_fija'] . "</td> ";
            $contenido3.="</tr>";
        }

        $ContenidoPdf = "
 <style type=\"text/css\">
        table { 
            color:#333; /* Lighten up font color */
            font-family:Helvetica, Arial, sans-serif; /* Nicer font */
            border-collapse:collapse; border-spacing: 3px; 
        }

              td, th { 
            border: 1px solid #CCC; 
            height: 13px;
        } /* Make cells a bit taller */

        th {
            background: #F3F3F3; /* Light grey background */
            font-weight: bold; /* Make sure they're bold */
            text-align: center;
            font-size:10px
        }

        td {
            background: #FAFAFA; /* Lighter grey background */
            text-align: left;
            font-size:10px
        }
        
        div.niveau
    
{ padding-left: 5mm; }
    </style>

<page backtop='70mm' backbottom='20mm' backleft='3mm' backright='3mm' pagegroup='new'>
<page_header>
    <table align='center'>
        <thead>
            <tr>
                <th style=\"width:10px;\" rowspan=\"2\">
                    <img alt=\"Imagen\" src=" . $direccion . "/nomina/cuotas_partes/Images/escudo1.png\" />
                </th>
                <th style=\"width:668px;font-size:12px;\" colspan=\"1\">
                    <br>UNIVERSIDAD DISTRITAL FRANCISCO JOSÉ DE CALDAS
                    <br> NIT 899999230-7<br>
                    <br> DIVISIÓN DE RECURSOS HUMANOS<br>
                    <br> Detalle Cuenta de Cobro 
                <br>" . $consecu_cc . "<br><br>
            </th>
        </tr>
        <tr>
            <th colspan=\"1\" style=\"font-size:10px; width:668px;\">" . $fecha_cc . "
            </th>
        </tr>
    </thead>      

    <tr>
        <td>Entidad Concurrente:</td>
        <td colspan='1'>" . $datos_basicos['entidad_nombre'] . "</td>
    </tr>
    <tr> 
        <td>NIT:</td>
        <td colspan='1'>" . $datos_basicos['entidad'] . "</td>
    </tr>
    <tr> 
        <td>Fecha Corte Cuenta:</td>
        <td colspan=\"2\">" . $fecha_cobro . "</td>
    </tr>
</table>
<br>
  <table align='center'>
        <tr>
            <td>Nombre Pensionado:</td>
            <td style=\"width:273px;\" colspan='1'>" . $datos_basicos['nombre_emp'] . "</td>
            <td>Documento Pensionado:</td>
            <td style=\"width:273px;\" colspan='1'>" . $datos_basicos['cedula'] . "</td>
        </tr>
        <tr>
            <td>Nombre Sustituto:</td>
            <td style=\"width:273px;\" colspan='1'></td>
            <td>Documento Sustituto:</td>
            <td style=\"width:273px;\" colspan='1'></td>
        </tr>
    </table>
<br>
</page_header>

<page_footer>

        <table align='center'>
        <tr>
        <td align = 'center' style=\"width: 762px; text-align:center\">
        Universidad Distrital Francisco José de Caldas
        <br>
        Todos los derechos reservados.
        <br>
        Carrera 8 N. 40-78 Piso 1 / PBX 3238400 - 3239300, Ext. 1618 - 1603
        <br>
        </td>
        </tr>
        </table>
        <p style=\"font-size:7px\">Diseño forma: JUAN D. CALDERON MARTIN</p>
        <p style='text-align: right; font-size:10px;'>[[page_cu]]/[[page_nb]]</p>
        </page_footer>

  <table>
   <thead>
        <tr>
            <th colspan=\"8\" style=\"width:767px; font-size:12px;\">DETALLE DE LA LIQUIDACIÓN</th>
        </tr>
        <tr>
            <th>CICLO</th>
            <th>MESADA</th>
            <th>VALOR CUOTA</th>
            <th>MESADA AD.</th>
            <th>INCREMENTO SALUD</th>
            <th>INTERÉS L_68/1923</th>
            <th>INTERÉS L_1066/2006</th>
            <th>TOTAL MES</th>
        </tr>
         </thead>
         <tbody>
            " . $contenido . "
                </tbody>
                
    </table>
<br>

<table>
  <thead>
        <tr>
            <th colspan=\"9\" style=\"width:767px; font-size:11px; border-collapse: collapse\">PARCIALES LIQUIDACIÓN</th>
        </tr>
        <tr>
            <th rowspan=\"2\">TOTAL</th>
            <th>VALOR CUOTA</th>
            <th>MESADA AD.</th>
            <th>INCREMENTO SALUD</th>
            <th>INTERES LEY 68/1923</th>
            <th>INTERES LEY 1066/2006</th>
            <th>ACUMULADO</th>
        </tr>
        </thead>
        <tbody>
        " . $contenido2 . "
            </tbody>
            
<tr>
        <th colspan = \"1\">TOTAL&nbsp;&nbsp;</th>
            <td  colspan=\"8\" style='text-align:center'>$" . number_format($total2) . "</td>
        </tr>
        </table>
        <br>
    <table align='center'>
        <tr>
        <th colspan=\"3\" style=\"width:320px; font-size:12px;\">AJUSTES ANUALES PENSIÓN APLICADOS (Ley 4a/76, Ley 71/88 y Ley 100 de 1993)</th>
        </tr>
        <tr>
            <th>VIGENCIA</th>
            <th>PORCENTAJE (IPC)</th>
            <th>SUMAFIJA</th>
        </tr>
       " . $contenido3 . "
        </table>
        <br><br><br><br>
        <table align='center'>
    <tr>
        <td style=\"text-align:center; width: 740px;\"><br><br><br><br>
        </td>
    </tr>
    <tr>  
        <td align='center' style=\"text-align:center; width: 750px;\" >
            " . $jefeRecursos . "
            <br>
            Jefe(a) División de Recursos Humanos
            <br>

        </td>
    </tr>
</table>
</page>
        ";

        //$ContenidoPdf = ob_get_clean();
        $PDF = new HTML2PDF('P', 'Letter', 'es', true, 'UTF-8', 3);
        $PDF->pdf->SetDisplayMode('fullpage');
        $PDF->writeHTML($ContenidoPdf);
        clearstatcache();
        $PDF->Output("FormatoCuentaDetalle_" . $datos_basicos['cedula'] . "_" . $datos_basicos['entidad_nombre'] . ".pdf", "D");
    }

    function datosEntidad() {
        $parametros = array(
            'cedula' => (isset($_REQUEST['cedula']) ? $_REQUEST['cedula'] : ''));

        $cedula = $_REQUEST['cedula'];
        $datos_entidad = $this->consultarEntidades($parametros);

        if (!is_array($datos_entidad)) {
            echo "<script type = \"text/javascript\">" .
            "alert('No existe detalle de la Concurrencia Aceptada para la cedula " . $cedula . ".');" .
            "</script> ";
            error_log('\n');
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = 'pagina=formularioConcurrencia';
            $variable.='&opcion=';
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
            exit;
        }

        $temp_array = array();

        foreach ($datos_entidad as $v) {

            if (!isset($temp_array[$v['prev_nit']])) {
                $temp_array[$v['prev_nit']] = $v;
            }
        }


        $datos_eunicos = array_values($temp_array);

        $this->html_liquidador->formularioEntidad($cedula, $datos_eunicos);
    }

    function datosInicialesReporte() {
        $this->html_liquidador->formularioDatosReporte();
    }

    function datosEntidadReporte() {
        $parametros = array(
            'cedula' => (isset($_REQUEST['cedula']) ? $_REQUEST['cedula'] : ''));

        $cedula = $_REQUEST['cedula'];
        $datos_entidad = $this->consultarEntidades($parametros);

        if (!is_array($datos_entidad)) {
            echo "<script type=\"text/javascript\">" .
            "alert('No existe detalle de la Concurrencia Aceptada para la cedula " . $cedula . ".');" .
            "</script> ";
            error_log('\n');
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = 'pagina=formularioConcurrencia';
            $variable.='&opcion=';
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
            exit;
        }

//$datos_eunicos = array_intersect_key($datos_entidad, array_unique(array_map('serialize', $datos_entidad)));

        $temp_array = array();

        foreach ($datos_entidad as $v) {

            if (!isset($temp_array[$v['prev_nit']])) {
                $temp_array[$v['prev_nit']] = $v;
            }
        }

        $datos_eunicos = array_values($temp_array);

        $this->html_liquidador->formularioEntidadReporte($cedula, $datos_eunicos);
    }

//recuperar entidad a liquidar
//NUEVAS FUNCIONALIDADES A MARZO 2014
    function cadenaLiquidacion() {
        $periodos_liquidacion = array();

        for ($i = 1; $i < 12; $i++) {
            $periodos_liquidacion[$i]['tipo_mesada_1'] = 'mesada_ordinaria';
        }

        $periodos_liquidacion[5]['tipo_mesada_2'] = 'mesada_adicionaljun';
        $periodos_liquidacion[12]['tipo_mesada_2'] = 'mesada_adicionaldic';

        return $periodos_liquidacion;
    }

    function periodoLiquidar($datos_liquidar) {

        $parametros = array(
            'cedula' => $datos_liquidar['cedula'],
            'entidad' => $datos_liquidar['prev_nit']);

        $consultar_fechas = $this->consultarRecaudosLiq($parametros);
        $consultar_pension = $this->datosConcurrencia($parametros);


        if (is_array($consultar_pension)) {
            if (is_array($consultar_fechas)) {
                $fecha_inicial = date('d/m/Y', strtotime(str_replace('/', '-', $consultar_fechas[0]['recta_fechahasta'])));
            } else {
                $fecha_inicial = date('d/m/Y', strtotime(str_replace('/', '-', $consultar_pension[0]['dcp_fecha_pension'])));
            }
        } else {
            echo "<script type=\"text/javascript\">" .
            "alert('No existe detalle de la Concurrencia Aceptada para la entidad.');" .
            "</script> ";
            error_log('\n');
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = 'pagina=formularioConcurrencia';
            $variable.='&opcion=';
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
            exit;
        }

        $this->html_liquidador->formularioPeriodo($parametros, $fecha_inicial);
    }

//consultar
    function consultarJefeRecursos($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_Oracle, "jefeRecursosH", $parametros);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_Oracle, $cadena_sql, "busqueda");
        return $datos;
    }

    function consultarJefeTesoreria($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_Oracle, "jefeTesoreria", $parametros);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_Oracle, $cadena_sql, "busqueda");
        return $datos;
    }

    function consultarEntidades($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "consultarEntidades", $parametros);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos;
    }

    function nombreEntidad($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "nombreEntidad", $parametros);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos;
    }

    function consultarDatosHistoria($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "datosHistoria", $parametros);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos;
    }

    function consultarDatosHistoriaTotal($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "datosHistoriaTotales", $parametros);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos;
    }

    function datosPensionado($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_Oracle, "datos_pensionado", $parametros);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_Oracle, $cadena_sql, "busqueda");
        return $datos;
    }

    function datosConcurrencia($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "datos_concurrencia", $parametros);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos;
    }

//--
    function consultarRecaudos($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "recaudos", $parametros);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos;
    }

    function consultarRecaudosLiq($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "recaudos_fechaliq", $parametros);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos;
    }

    function obtenerIPC($parametro) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "valor_ipc", $parametro);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos;
    }

    function obtenerSumafija($parametro) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "valor_sumafija", $parametro);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos;
    }

    function obtenerIndices($parametro) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "detalle_indices", $parametro);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos;
    }

    function obtenerDTF($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "valor_dtf", $parametros);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos;
    }

    function mesadaInicial($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "valor_mesada_inicial", $parametros);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos;
    }

    function consecutivo($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "consecutivo", $parametros);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos;
    }

    function consecutivoCC($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "consecutivoCC", $parametros);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos;
    }

    function consultarCC($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "consultarCC", $parametros);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos;
    }

    function guardarLiqui($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "guardarLiquidacion", $parametros);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "registro");
        return $datos;
    }

    function consultarLiqui($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "consultarLiquidacion", $parametros);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos;
    }

    function consultarLiquiFija($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "consultarLiquidacionConsecutivo", $parametros);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos;
    }

    function RescatarDTF($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "valor_dtf", $parametros);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos;
    }

// Reportes Liquidación
    function reportes($datos_basicos) {

        $parametros = array(
            'cedula' => (isset($datos_basicos['cedula']) ? $datos_basicos['cedula'] : ''),
            'entidad' => (isset($datos_basicos['entidad']) ? $datos_basicos['entidad'] : ''),
        );

        if (!isset($datos_basicos['entidad_nombre'])) {

            $nombre_entidad = $this->nombreEntidad($datos_basicos);
            $nombre_empleado = $this->datosPensionado($datos_basicos);
            $datos_basicos = array(
                'cedula' => $datos_basicos['cedula'],
                'nombre_emp' => $nombre_empleado[0]['NOMBRE'],
                'entidad_nombre' => $nombre_entidad[0]['prev_nombre'],
                'entidad' => $datos_basicos['entidad']);
        }

        $totales_liq = $this->consultarLiqui($parametros);

        if (is_array($totales_liq)) {
            $this->html_liquidador->generarReportes($datos_basicos, $totales_liq);
        } else {
            echo "<script type=\"text/javascript\">" .
            "alert('No existen Liquidaciones Generadas para la Entidad.');" .
            "</script> ";
            error_log('\n');
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = 'pagina=liquidadorCP';
            $variable.='&opcion=';
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
            exit;
        }
    }

    function reporteCuenta($datos_basicos, $consecutivo) {
//recuperar información del sustituto
//recuperar nombre jefe recursos humanos y tesorero
        $parametros = array(
            'cedula' => $datos_basicos['cedula'],
            'entidad' => $datos_basicos['entidad'],
            'liq_consecutivo' => $consecutivo
        );

        $total_liquidacion = $this->consultarLiquiFija($parametros);
        $enletras = strtoupper($this->num2letras($total_liquidacion[0]['liq_total']));
//definir consecutivo cuenta de cobro
        $consecutivo = $this->generarConsecutivo();

        $a = array();
        $jefe_recursos = $this->consultarJefeRecursos($a);
        $jefe_tesoreria = $this->consultarJefeTesoreria($a);

        $this->html_liquidador->reporteCuenta($datos_basicos, $total_liquidacion, $enletras, $consecutivo, $jefe_recursos, $jefe_tesoreria);
    }

    function reporteResumen($datos_basicos, $consecutivo) {
//definir consecutivo cuenta de cobro
        $parametros = array(
            'id_liq' => $consecutivo,
            'cedula' => $datos_basicos['cedula'],
            'entidad' => $datos_basicos['entidad']
        );

        $existe_cc = $this->consultarCC($parametros);

        if ($existe_cc == true) {
            $conse_cc = $existe_cc[0]['cob_consecu_cta'];
        } else {
            echo "<script type=\"text/javascript\">" .
            "alert('¡Debe crear primero una Cuenta de Cobro!');" .
            "</script> ";
            error_log('\n');
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = 'pagina=liquidadorCP';
            $variable.='&opcion=';
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
            exit;
        }

//recuperar datos de la concurrencia
        $datos_concurrencia = $this->datosConcurrencia($parametros);
//recuperar datos del pensionado
        $datos_pensionado = $this->datosPensionado($parametros);

//recuperar y organizar año a año liquidación detallada
        $parametros_l = array(
            'cedula' => $datos_basicos['cedula'],
            'entidad' => $datos_basicos['entidad'],
            'liq_consecutivo' => $consecutivo
        );

        $total_liquidacion = $this->consultarLiquiFija($parametros_l);

        $datos_liquidacion = array(
            'cedula' => $total_liquidacion[0]['liq_cedula'],
            'entidad' => $total_liquidacion[0]['liq_nitprev'],
            'liquidar_desde' => $total_liquidacion[0]['liq_fdesde'],
            'liquidar_hasta' => $total_liquidacion[0]['liq_fhasta']
        );

        $liquidacion = $this->liquidacion($datos_liquidacion);
        $liquidacion_anual = $this->detalleLiquidacion($liquidacion);

//recuperar detalle de días según historia laboral

        $dias_cargo = $this->calculoDias($datos_basicos);

//recuperar información del sustituto
//recuperar nombre jefe recursos humanos
        $a = array();
        $jefe_recursos = $this->consultarJefeRecursos($a);


        $this->html_liquidador->reporteResumen($datos_basicos, $conse_cc, $datos_concurrencia, $datos_pensionado, $liquidacion_anual, $dias_cargo, $jefe_recursos);
    }

    function reportesDetalle($datos_basicos, $consecutivo) {
//definir consecutivo cuenta de cobro
        $parametros = array(
            'id_liq' => $consecutivo,
            'cedula' => $datos_basicos['cedula'],
            'entidad' => $datos_basicos['entidad']
        );

        $existe_cc = $this->consultarCC($parametros);

        if ($existe_cc == true) {
            $conse_cc = $existe_cc[0]['cob_consecu_cta'];
        } else {
            echo "<script type=\"text/javascript\">" .
            "alert('¡Debe crear primero una Cuenta de Cobro!');" .
            "</script> ";
            error_log('\n');
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = 'pagina=liquidadorCP';
            $variable.='&opcion=';
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
            exit;
        }
//recuperar y organizar año a año liquidación detallada
        $parametros = array(
            'cedula' => $datos_basicos['cedula'],
            'entidad' => $datos_basicos['entidad'],
            'liq_consecutivo' => $consecutivo
        );

        $total_liquidacion = $this->consultarLiquiFija($parametros);

        $datos_liquidacion = array(
            'cedula' => $total_liquidacion[0]['liq_cedula'],
            'entidad' => $total_liquidacion[0]['liq_nitprev'],
            'liquidar_desde' => $total_liquidacion[0]['liq_fdesde'],
            'liquidar_hasta' => $total_liquidacion[0]['liq_fhasta']
        );

        $liquidacion = $this->liquidacion($datos_liquidacion);
        $detalle_indice = $this->detalleIndices($liquidacion);

        foreach ($liquidacion as $key => $value) {
            $fecha_cobro = $liquidacion[$key]['fecha'];
        }
//recuperar información del sustituto
//recuperar nombre jefe recursos humanos
        $a = array();
        $jefe_recursos = $this->consultarJefeRecursos($a);

        $this->html_liquidador->reporteDetalle($datos_basicos, $liquidacion, $total_liquidacion, $conse_cc, $detalle_indice, $fecha_cobro, $jefe_recursos);
    }

    function detalleIndices($liquidacion) {

        $indice = array();
        $indice_f = array();
        $a = 0;

        foreach ($liquidacion as $key => $values) {
            $indice['vigencia'][$key] = date('Y', strtotime(str_replace('/', '-', $liquidacion[$key]['fecha']))) + 1;
        }

        $indice_k = array_unique($indice['vigencia'], SORT_REGULAR);

        foreach ($indice_k as $key => $values) {
            $detalle_indice = $this->obtenerIndices($indice_k[$key]);
            foreach ($detalle_indice as $key => $values) {
                $indice_f[$a]['vigencia'] = $detalle_indice[$key]['ipc_fecha'];
                $indice_f[$a]['ipc'] = $detalle_indice[$key]['ipc_indiceipc'];
                $indice_f[$a]['suma_fija'] = $detalle_indice[$key]['ipc_sumas_fijas'];
                $a++;
            }
        }
        return $indice_f;
    }

    function detalleLiquidacion($liquidacion) {

        $indice = array();

        foreach ($liquidacion as $key => $values) {
            $indice['vigencia'][$key] = date('Y', strtotime(str_replace('/', '-', $liquidacion[$key]['fecha'])));
        }

        $indice_k = array_unique($indice['vigencia'], SORT_REGULAR);
        $año_liquidacion = array();
        $mesada = 0;
        $ajuste_pen = 0;
        $mesada_adc = 0;
        $incremento = 0;
        $cuota_parte = 0;
        $interes = 0;
        $total = 0;


        foreach ($indice_k as $key => $values) {
            foreach ($liquidacion as $cont => $values) {
                $año = date('Y', strtotime(str_replace('/', '-', $liquidacion[$cont]['fecha'])));
                $fecha_a2006 = strtotime(str_replace('/', '-', '29/07/2006'));
                $fecha_li = strtotime(str_replace('/', '-', $liquidacion[$cont]['fecha']));
                $año_k = $indice_k[$key];

                if ($año == $año_k) {

                    $mesada = $liquidacion[$cont]['mesada'] + $mesada;
                    $ajuste_pen = $liquidacion[$cont]['ajuste_pension'] + $ajuste_pen;
                    $mesada_adc = $liquidacion[$cont]['mesada_adc'] + $mesada_adc;
                    $incremento = $liquidacion[$cont]['incremento'] + $incremento;
                    $cuota_parte = $liquidacion[$cont]['cuota_parte'] + $cuota_parte;
                    $interes_a2006 = $liquidacion[$cont]['interes_a2006'] + $interes_a2006;
                    $interes_d2006 = $liquidacion[$cont]['interes_d2006'] + $interes_d2006;
                    $interes = $liquidacion[$cont]['interes'] + $interes6;
                    $total = $liquidacion[$cont]['total'] + $total;

                    $año_liquidacion[$key]['vigencia'] = $año_k;
                    $año_liquidacion[$key]['mesada'] = $mesada;
                    $año_liquidacion[$key]['ajuste_pen'] = $ajuste_pen;
                    $año_liquidacion[$key]['mesada_adc'] = $mesada_adc;
                    $año_liquidacion[$key]['incremento'] = $incremento;
                    $año_liquidacion[$key]['cuota_parte'] = $cuota_parte;
                    $año_liquidacion[$key]['interes'] = $interes_a2006;
                    $año_liquidacion[$key]['interes_a2006'] = $interes_a2006;
                    $año_liquidacion[$key]['interes_d2006'] = $interes_d2006;
                    $año_liquidacion[$key]['total'] = $total;
                }
            }

            $mesada = 0;
            $ajuste_pen = 0;
            $mesada_adc = 0;
            $incremento = 0;
            $cuota_parte = 0;
            $interes = 0;
            $interes_a2006 = 0;
            $interes_d2006 = 0;
            $total = 0;
        }

        return $año_liquidacion;
    }

    function guardar_cuenta($datos_basicos, $consecutivocc, $totales_liq) {

        $parametros_x = array();
        $consecutivo = $this->consecutivoCC($parametros_x);
        $consecutivo_cc = $this->generarConsecutivo($parametros_x);

        $cons = intval($consecutivo[0][0]) + 1;

        $subtotal = $totales_liq[0]['liq_cuotap'] + $totales_liq[0]['liq_mesada_ad'];
        $t_s_interes = $subtotal + $totales_liq[0]['liq_incremento'] + $totales_liq[0]['liq_ajustepen'];

        $parametros = array(
            'id_liq' => $totales_liq[0]['liq_consecutivo'],
            'id_cuentac' => $cons,
            'fecha_generacion' => $totales_liq[0]['liq_fgenerado'],
            'cedula' => $totales_liq[0]['liq_cedula'],
            'previsor' => $totales_liq[0]['liq_nitprev'],
            'consecutivo_cc' => $consecutivo_cc,
            'saldo_fecha' => $totales_liq[0]['liq_total'],
            'fecha_inicial' => $totales_liq[0]['liq_fdesde'],
            'fecha_final' => $totales_liq[0]['liq_fhasta'],
            'mesada_ordinaria' => $totales_liq[0]['liq_cuotap'],
            'mesada_adc' => $totales_liq[0]['liq_mesada_ad'],
            'subtotal' => $subtotal,
            'incremento' => $totales_liq[0]['liq_incremento'],
            'ajuste_pension' => $totales_liq[0]['liq_ajustepen'],
            't_sin_interes' => $t_s_interes,
            'interes' => $totales_liq[0]['liq_interes'],
            't_con_interes' => $totales_liq[0]['liq_total'],
            'total' => $totales_liq[0]['liq_total'],
            'fecha_recibido' => '',
            'estado_cuenta' => 'ACTIVA',
            'estado' => $totales_liq[0]['liq_estado'],
            'fecha_registro' => $totales_liq[0]['liq_fecha_registro']
        );

        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "guardar_cuentac", $parametros);
        $datos_registrados = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "registrar");

        if ($datos_registrados == true) {
            $registro[0] = "GUARDAR";
            $registro[1] = $parametros['cedula'] . '|' . $parametros['previsor']; //
            $registro[2] = "CUOTAS_PARTES-CuentaCobroSistema";
            $registro[3] = $parametros['consecutivo_cc'] . '|' . $parametros['fecha_inicial'] . '|' . $parametros['fecha_final'] . '|' . $parametros['mesada_ordinaria']
                    . '|' . $parametros['mesada_adc'] . '|' . $parametros['subtotal'] . '|' . $parametros['incremento'] . '|' . $parametros['t_sin_interes'] . '|' . $parametros['interes']
                    . '|' . $parametros['t_con_interes'] . '|' . $parametros['saldo_fecha'] . '|' . $parametros['fecha_recibido']; //
            $registro[4] = time();
            $registro[5] = "Registra datos cuenta de cobro del sistema del pensionado con ";
            $registro[5] .= " identificacion =" . $parametros['cedula'];
            $this->log_us->log_usuario($registro, $this->configuracion);

            echo "<script type=\"text/javascript\">" .
            "alert('Datos Registrados');" .
            "</script> ";
        } else {
            echo "<script type=\"text/javascript\">" .
            "alert('Esta Cuenta de Cobro ya Existe!. ERROR en el REGISTRO');" .
            "</script> ";
        }

        $parametros_z = array();
        $consecutivo_recta = $this->consultarConseRecta($parametros_z);

        if ($consecutivo_recta == null) {
            $rectaid = 1;
        } else {
            $rectaid = $consecutivo_recta[0][0] + 1;
        }

        $parametros_saldo = array(
            'id_registro' => $rectaid,
            'cedula' => $totales_liq[0]['liq_cedula'],
            'previsor' => $totales_liq[0]['liq_nitprev'],
            'consecutivo_cc' => $consecutivo_cc,
            'recaudo' => 0,
            'consecu_rec' => '',
            'capital' => $t_s_interes,
            'interes' => $totales_liq[0]['liq_interes'],
            't_con_interes' => $totales_liq[0]['liq_total'],
            'saldo_fecha' => $totales_liq[0]['liq_total']
        );

        $cadena_sql_saldo = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "insertarRecta", $parametros_saldo);
        $registro_saldo = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql_saldo, "registrar");

        if ($registro_saldo == true) {
            $registro[0] = "GUARDAR";
            $registro[1] = $parametros['cedula'] . '|' . $parametros['previsor']; //
            $registro[2] = "CUOTAS_PARTES-CuentaCobroManualSaldo";
            $registro[3] = $parametros['consecutivo_cc'] . '|' . $parametros['fecha_inicial'] . '|' . $parametros['fecha_final'] . '|' . $parametros['mesada']
                    . '|' . $parametros['mesada_adc'] . '|' . $parametros['subtotal'] . '|' . $parametros['incremento'] . '|' . $parametros['t_sin_interes'] . '|' . $parametros['interes']
                    . '|' . $parametros['t_con_interes'] . '|' . $parametros['saldo_fecha'] . '|' . $parametros['fecha_recibido']; //
            $registro[4] = time();
            $registro[5] = "Registra datos cuenta de cobro manual del pensionado en la tabla de saldos, con ";
            $registro[5] .= " identificacion =" . $parametros['cedula'];
            $this->log_us->log_usuario($registro, $this->configuracion);

            echo "<script type=\"text/javascript\">" .
            "alert('Datos Registrados');" .
            "</script> ";
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = "pagina=reportesCuotas";
            $variable .= "&opcion=";
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
            exit;
        } else {
            echo "<script type=\"text/javascript\">" .
            "alert('Datos NO Registrados Correctamente. ERROR en el REGISTRO');" .
            "</script> ";
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = "pagina=formularioCManual";
            $variable .= "&opcion=manual";
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
            exit;
        }
    }

    function generarConsecutivo() {
        $parametros = array();
        $consecutivo = $this->consecutivoCC($parametros);
        $cons = $consecutivo[0]['cob_idcob'] + 1;
        $annio = date("Y");

        if ($cons <= 9) {
            $cons_cuenta = "CP-000" . $cons . "-" . $annio;
        } elseif ($cons <= 99) {
            $cons_cuenta = "CP-00" . $cons . "-" . $annio;
        } elseif ($cons <= 999) {
            $cons_cuenta = "CP-0" . $cons . "-" . $annio;
        } else {
            $cons_cuenta = "CP-" . $cons . "-" . $annio;
        }

        return $cons_cuenta;
    }

    function consultarConseRecta($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "consecutivoRecta", $parametros);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos;
    }

//liquidacion
    function calculoTotales($liquidacion_periodo) {

        $calculo_totales = array(
            'mesada' => 0,
            'ajuste_pension' => 0,
            'mesada_adc' => 0,
            'incremento' => 0,
            'cuota_parte' => 0,
            'interes_a2006' => 0,
            'interes_d2006' => 0,
            'interes' => 0,
            'total' => 0,
        );

        foreach ($liquidacion_periodo as $key => $value) {
            $calculo_totales['mesada'] = $liquidacion_periodo[$key]['mesada'] + $calculo_totales['mesada'];
            $calculo_totales['ajuste_pension'] = $liquidacion_periodo[$key]['ajuste_pension'] + $calculo_totales['ajuste_pension'];
            $calculo_totales['mesada_adc'] = $liquidacion_periodo[$key]['mesada_adc'] + $calculo_totales['mesada_adc'];
            $calculo_totales['incremento'] = $liquidacion_periodo[$key]['incremento'] + $calculo_totales['incremento'];
            $calculo_totales['cuota_parte'] = $liquidacion_periodo[$key]['cuota_parte'] + $calculo_totales['cuota_parte'];
            $calculo_totales['interes_a2006'] = $liquidacion_periodo[$key]['interes_a2006'] + $calculo_totales['interes_a2006'];
            $calculo_totales['interes_d2006'] = $liquidacion_periodo[$key]['interes_d2006'] + $calculo_totales['interes_d2006'];
            $calculo_totales['interes'] = $liquidacion_periodo[$key]['interes'] + $calculo_totales['interes'];
            $calculo_totales['total'] = $liquidacion_periodo[$key]['total'] + $calculo_totales['total'];
        }

        return $calculo_totales;
    }

    function liquidacion($datos_liquidar) {

        $periodo_liquidar = $this->cadenaLiquidacion();

        $parametros = array(
            'cedula' => (isset($datos_liquidar['cedula']) ? $datos_liquidar['cedula'] : ''),
            'entidad' => (isset($datos_liquidar['entidad']) ? $datos_liquidar['entidad'] : ''));

        $datos_concurrencia = $this->datosConcurrencia($parametros);

        $f_pension = date('d/m/Y', strtotime(str_replace('/', '-', $datos_liquidar['liquidar_desde'])));
        $f_actual = date('d/m/Y', strtotime(str_replace('/', '-', $datos_liquidar['liquidar_hasta'])));
        $porcentaje_cuota = $datos_concurrencia[0]['dcp_porcen_cuota'];
        $mesada = $datos_concurrencia[0]['dcp_valor_mesada'];

        list ($FECHAS) = $fechas = $this->GenerarFechas($f_pension, $f_actual);
        $TOTAL = 0;

        $liquidacion_cp = array();

        foreach ($FECHAS as $key => $value) {

//Cadena del periodo liquidar
            $annio = date('Y', strtotime(str_replace('/', '-', $FECHAS[$key]))) + 1;

//Valor Indices Básicos
            $sumafija = $this->obtenerSumafija($annio);
            $INDICE = $this->obtenerIPC($annio);
            $MESADA = $this->MesadaFecha(($FECHAS[$key]), $mesada, $sumafija[0][0]);

//Determinar Cuota Parte
            $CUOTAPARTE = $this->CuotaParte($MESADA, $porcentaje_cuota);

//Valor Ajustes Adicionales
            $AJUSTEPENSIONAL = $this->AjustePensional(($FECHAS[$key]), $sumafija[0][0]);
            $MESADAADICIONAL = $this->MesadaAdicional(($FECHAS[$key]), $CUOTAPARTE);
            $INCREMENTOSALUD = $this->IncrementoSalud(($FECHAS[$key]), $CUOTAPARTE);

            $valor_cuota = $CUOTAPARTE + $MESADAADICIONAL + $INCREMENTOSALUD + $AJUSTEPENSIONAL;

            $INTERESES_A2006 = $this->Intereses_a2006($FECHAS[$key], $valor_cuota);
            $INTERESES_D2006 = $this->Intereses_d2006($FECHAS[$key], $valor_cuota, $f_actual);
            $INTERESES = $this->Intereses($FECHAS[$key], $valor_cuota);

//$INTERESES = 0;
//Valor Total Mes liquidado
            $TOTAL = $MESADAADICIONAL + $INCREMENTOSALUD + $CUOTAPARTE + $INTERESES;
            $TOTAL = round($TOTAL, 0);

//**************SALIDA FINAL****************//

            $liquidacion_cp[$key]['fecha'] = $FECHAS[$key];
            $liquidacion_cp[$key]['ipc'] = $INDICE[0][0];
            $liquidacion_cp[$key]['mesada'] = $MESADA;
            $liquidacion_cp[$key]['ajuste_pension'] = 0;
            $liquidacion_cp[$key]['mesada_adc'] = $MESADAADICIONAL;
            $liquidacion_cp[$key]['incremento'] = $INCREMENTOSALUD;
            $liquidacion_cp[$key]['cuota_parte'] = $CUOTAPARTE;
            $liquidacion_cp[$key]['interes_a2006'] = $INTERESES_A2006;
            $liquidacion_cp[$key]['interes_d2006'] = $INTERESES_D2006;
            $liquidacion_cp[$key]['interes'] = $INTERESES;
            $liquidacion_cp[$key]['total'] = $TOTAL;
            $mesada = $MESADA;
        }

        return $liquidacion_cp;
    }

    function calculoDias($datos_basicos) {

        $historia_laboral = $this->consultarDatosHistoriaTotal($datos_basicos);

        foreach ($historia_laboral as $key => $values) {
            $desde = strtotime(str_replace('/', '-', $historia_laboral[$key]['hlab_fingreso']));
            $hasta = strtotime(str_replace('/', '-', $historia_laboral[$key]['hlab_fretiro']));
            $dias_entre[$key]['dias'] = floor(abs(($desde - $hasta) / 86400));
            $dias_entre[$key]['entidad'] = $historia_laboral[$key]['hlab_nitprev'];
        }

        foreach ($dias_entre as $key => $values) {
            $array[$dias_entre[$key]['entidad']][$key] = $dias_entre[$key]['dias'];
        }

        $diasTotales = 0;
        foreach ($array as $k => $i) {
            $diasEntidad = 0;
            foreach ($array[$k] as $r) {
//acumulados
                $diasEntidad += $r;
                $diasTotales +=$r;
            }
            $array[$k]['total_dia'] = $diasEntidad;
        }

        $array['Total'] = $diasTotales;
        return $array;
    }

    function calcularPeriodoLiq($datos_liquidar) {

//Datos del Periodo a Liquidar
        $fecha_inicial = date('m/Y', strtotime(str_replace('/', '-', $datos_liquidar['liquidar_desde'])));
        $fecha_final = date('m/Y', strtotime(str_replace('/', '-', $datos_liquidar['liquidar_hasta'])));

        $liquidacion = $this->liquidacion($datos_liquidar);

//Generación Arreglo para el periodo especificado
        if (is_array($liquidacion)) {
            $inicio = 0;
            $fin = 0;

            foreach ($liquidacion as $key => $values) {
                $fecha_liq = date('m/Y', strtotime(str_replace('/', '-', $liquidacion[$key]['fecha'])));

                if ($fecha_inicial == $fecha_liq) {
                    $inicio = $key;
                }

                if ($fecha_final == $fecha_liq) {
                    $fin = $key;
                }
            }

            $periodo_calculado = array();

            for ($i = $inicio; $i <= $fin; $i++) {
                $periodo_calculado[$i] = $liquidacion[$i];
            }

            return $periodo_calculado;
        } else {
            echo "<script type=\"text/javascript\">" .
            "alert('Error recuperando la liquidación. Reinicie el proceso.');" .
            "</script> ";
            error_log('\n');
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = 'pagina=liquidadorCP';
            $variable.='&opcion=';
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
            exit;
        }
    }

    function mostrarLiquidacion($datos_liquidar) {
//recuperar periodo calculado
//Datos Básicos del Detalle de Liquidación
        $nombre_entidad = $this->nombreEntidad($datos_liquidar);
        $nombre_empleado = $this->datosPensionado($datos_liquidar);

        $datos_basicos = array(
            'cedula' => $datos_liquidar['cedula'],
            'nombre_emp' => $nombre_empleado[0]['NOMBRE'],
            'entidad_nombre' => $nombre_entidad[0]['prev_nombre'],
            'entidad' => $datos_liquidar['entidad'],
            'liquidar_desde' => $datos_liquidar['liquidar_desde'],
            'liquidar_hasta' => $datos_liquidar['liquidar_hasta']);

        $periodo_calculado = $this->calcularPeriodoLiq($datos_liquidar);
// Calculo de Totales
        $total_calculado = $this->calculoTotales($periodo_calculado);

        $this->html_liquidador->liquidador($periodo_calculado, $datos_basicos, $total_calculado);
    }

    function guardarLiquidacion($datos_basicos, $totales_liquidacion) {
//Generar consecutivo liquidación
        $parametro = array();
        $consecutivo = $this->consecutivo($parametro);

        if ($consecutivo == null) {
            $consecutivo[0][0] = 0;
        }

        $consecutivo_real = intval($consecutivo[0][0]) + 1;

//Guardar datos Liquidación
        $parametros = array(
            'liq_consecutivo' => (isset($consecutivo_real) ? $consecutivo_real : ''),
            'liq_fgenerado' => date('Y-m-d'),
            'liq_cedula' => (isset($datos_basicos['cedula']) ? $datos_basicos['cedula'] : ''),
            'liq_nitprev' => (isset($datos_basicos['entidad']) ? $datos_basicos['entidad'] : ''),
            'liq_fdesde' => (isset($datos_basicos['liquidar_desde']) ? $datos_basicos['liquidar_desde'] : ''),
            'liq_fhasta' => (isset($datos_basicos['liquidar_hasta']) ? $datos_basicos['liquidar_hasta'] : ''),
            'liq_mesada' => (isset($totales_liquidacion['mesada']) ? $totales_liquidacion['mesada'] : ''),
            'liq_ajustepen' => (isset($totales_liquidacion['ajuste_pension']) ? $totales_liquidacion['ajuste_pension'] : ''),
            'liq_mesada_ad' => (isset($totales_liquidacion['mesada_adc']) ? $totales_liquidacion['mesada_adc'] : ''),
            'liq_incremento' => (isset($totales_liquidacion['incremento']) ? $totales_liquidacion['incremento'] : ''),
            'liq_interes_a2006' => (isset($totales_liquidacion['interes_a2006']) ? $totales_liquidacion['interes_a2006'] : ''),
            'liq_interes_d2006' => (isset($totales_liquidacion['interes_d2006']) ? $totales_liquidacion['interes_d2006'] : ''),
            'liq_interes' => (isset($totales_liquidacion['interes']) ? $totales_liquidacion['interes'] : ''),
            'liq_cuotap' => (isset($totales_liquidacion['cuota_parte']) ? $totales_liquidacion['cuota_parte'] : ''),
            'liq_total' => (isset($totales_liquidacion['total']) ? $totales_liquidacion['total'] : ''),
            'liq_estado_cc' => 'ACTIVO',
            'liq_fecha_estado_cc' => null,
            'liq_estado_ccdetalle' => 'ACTIVO',
            'liq_fecha_estado_ccdetalle' => null,
            'liq_estado_ccresumen' => 'ACTIVO',
            'liq_fecha_estado_ccresumen' => null,
            'liq_estado' => 'ACTIVO',
            'liq_fecha_registro' => date('Y-m-d')
        );

        $datos_registrados = $this->guardarLiqui($parametros);

        if ($datos_registrados == true) {
            $registro[0] = "GUARDAR";
            $registro[1] = $parametros['liq_cedula'] . '|' . $parametros['liq_nitprev']; //
            $registro[2] = "CUOTAS_PARTES-LiquidacionGenerada";
            $registro[3] = $parametros['liq_consecutivo'] . '|' . $parametros['liq_fdesde'] . '|' . $parametros['liq_fhasta'] . '|' . $parametros['liq_mesada']
                    . '|' . $parametros['liq_total'] . '|' . $parametros['liq_estado'] . '|' . $parametros['liq_fecha_registro'];
            $registro[4] = time();
            $registro[5] = "Registra datos liquidacion generada para el pensionado con ";
            $registro[5] .= " identificacion =" . $parametros['liq_cedula'];
            $this->log_us->log_usuario($registro, $this->configuracion);

            echo "<script type=\"text/javascript\">" .
            "alert('Gestor de Reportes');" .
            "</script> ";
        }
    }

// operar

    function GenerarFechas($Fecha_pension, $Fecha_actual) {
        $Anio_p = date('Y', strtotime(str_replace('/', '-', $Fecha_pension)));
        $Mes_p = date("m", strtotime(str_replace('/', '-', $Fecha_pension)));
        $Dia_p = date("d", strtotime(str_replace('/', '-', $Fecha_pension)));

        $Anio_a = date("Y", strtotime(str_replace('/', '-', $Fecha_actual)));
        $Mes_a = date("m", strtotime(str_replace('/', '-', $Fecha_actual)));
        $Dia_a = date("d", strtotime(str_replace('/', '-', $Fecha_actual)));

        settype($Anio_p, "integer");
        settype($Mes_p, "integer");
        settype($Dia_p, "integer");

        settype($Anio_a, "integer");
        settype($Mes_a, "integer");

        $Dia = $Dia_p;

        for ($Anio_p; $Anio_p <= $Anio_a; $Anio_p++) {
            for ($Mes_p; $Mes_p <= 12; $Mes_p++) {
//echo $Mes_p;
                if ($Anio_p != $Anio_a) {
                    $fecha[] = $Dia . "/" . $Mes_p . "/" . $Anio_p;
                    $Dia = mktime(0, 0, 0, $Mes_p + 2, 0, $Anio_p);
                    $Dia = date("d", $Dia);
                    settype($Dia, "integer");
                    settype($Dia, "integer");
                } elseif ($Mes_p <= $Mes_a) {
                    $fecha[] = $Dia . "/" . $Mes_p . "/" . $Anio_p;
                    $Dia = mktime(0, 0, 0, $Mes_p + 2, 0, $Anio_p);
                    $Dia = date("d", $Dia);
                    settype($Dia, "integer");
                    settype($Dia, "integer");
                }
//echo $Anio;
//echo $Anio_p;	
            }
            $Mes_p = 1;
        }
        return array($fecha);
    }

    function AjustePensional($FECHA, $sumafija) {

        $Anio = substr(date("Y", strtotime(str_replace('/', '-', $FECHA))), 0, 4);
        $Mes = substr(date("m", strtotime(str_replace('/', '-', $FECHA))), 0, 2);
        $Dia = substr(date("d", strtotime(str_replace('/', '-', $FECHA))), 0, 2);

        settype($Anio, "integer");
        settype($Mes, "integer");
        settype($Dia, "integer");

        if ($Anio <= 1988) {
            if ($Mes == 1) {
                $AjustePensional = $sumafija[0][0];
            } else {
                $AjustePensional = 0;
            }
        } else {
            $AjustePensional = 0;
        }
        $AjustePensional = round($AjustePensional, 2);
        return ($AjustePensional);
    }

    function CuotaParte($Mesada, $porcentaje) {
        $Mesadacp = round($Mesada);
        $porcentajecp = round($porcentaje, 6);

//Cuota Parte	
        $Cuotaparte = $porcentajecp * $Mesadacp;
        $Cuotaparte2 = round($Cuotaparte);

        return($Cuotaparte2);
    }

    function IncrementoSalud($fecha, $cuota_calculada) {

        $Anio = substr(date("Y", strtotime(str_replace('/', '-', $fecha))), 0, 4);

        if ($Anio >= '1994') {
            $Incr_Salud = $cuota_calculada * 0.07;
        } else {
            $Incr_Salud = 0;
        }

        $Incr_S = round($Incr_Salud, 2);
        return ($Incr_S);
    }

    function MesadaFecha($FECHA, $Mesada, $sumafija) {
        $Anio = substr(date("Y", strtotime(str_replace('/', '-', $FECHA))), 0, 4) + 1;
        $Mes = substr(date("m", strtotime(str_replace('/', '-', $FECHA))), 0, 2);

        settype($Anio, "integer");
        settype($Mes, "integer");

        $Mesada = round($Mesada);
        $INDICE = $this->obtenerIPC($Anio);

//Ajuste Pensional

        if ($Mes == 1) {
            if ($Anio == 1979) {
                if ($Mesada <= 12900) {
                    $Mesada_Fecha = ($Mesada * $INDICE[0][0]) + $Mesada;
                } else {
                    $Mesada_Fecha = ($Mesada * $INDICE[0][1]) + $Mesada + $sumafija;
                }
            } elseif ($Anio == 1984) {
                if ($Mesada >= 36872.5 && $Mesada <= 46305) {
                    $Mesada_Fecha = ($Mesada * $INDICE[0][0]) + $Mesada;
                } else {
                    $Mesada_Fecha = ($Mesada * $INDICE[0][1]) + $Mesada + $sumafija;
                }
            } elseif ($Anio == 1985) {
                if ($Mesada >= 25462.5 && $Mesada <= 56490) {
                    $Mesada_Fecha = ($Mesada * $INDICE[0][0]) + $Mesada;
                } else {
                    $Mesada_Fecha = ($Mesada * $INDICE[0][1]) + $Mesada + $sumafija;
                }
            } elseif ($Anio == 1986) {
                if ($Mesada >= 22590 && $Mesada <= 67788) {
                    $Mesada_Fecha = ($Mesada * $INDICE[0][0]) + $Mesada;
                } else {
                    $Mesada_Fecha = ($Mesada * $INDICE[0][1]) + $Mesada + $sumafija;
                }
            } elseif ($Anio == 1987) {
                if ($Mesada >= 54231.15 && $Mesada <= 84057) {
                    $Mesada_Fecha = ($Mesada * $INDICE[0][0]) + $Mesada;
                } else {
                    $Mesada_Fecha = ($Mesada * $INDICE[0][1]) + $Mesada + $sumafija;
                }
            } elseif ($Anio == 1988) {
                if ($Mesada >= 46230 && $Mesada <= 102549) {
                    $Mesada_Fecha = ($Mesada * $INDICE[0][0]) + $Mesada;
                } else {
                    $Mesada_Fecha = ($Mesada * $INDICE[0][1]) + $Mesada + $sumafija;
                }
            }
            $Mesada_Fecha = ($Mesada * $INDICE[0][0]) + $Mesada;
        } else {
            $Mesada_Fecha = ($Mesada);
        }

        $Mesada_Fecha = round($Mesada_Fecha, 2);
        return ($Mesada_Fecha);
    }

//MesadaAdicional
//$cuota_calculada Cuota Parte Calculada
    function MesadaAdicional($FECHA, $cuota_calculada) {
//Rescatando Año , Mes y Dia
        $Anio = substr(date("Y", strtotime(str_replace('/', '-', $FECHA))), 0, 4);
        $Mes = substr(date("m", strtotime(str_replace('/', '-', $FECHA))), 0, 2);

        settype($Anio, "integer");
        settype($Mes, "integer");

        if ($Anio >= 1994) {
            if ($Mes == 6) {
                $MesaAD = $cuota_calculada;
            } elseif ($Mes == 12) {
                $MesaAD = $cuota_calculada;
            } else {
                $MesaAD = 0;
            }
        } elseif ($Mes == 12) {
            $MesaAD = $cuota_calculada;
        } else {
            $MesaAD = 0;
        }

        $MesaAD = round($MesaAD, 2);
        return ($MesaAD);
    }

//Rescatando DTF
    function Intereses($FECHA, $valor_cuota, $fecha_hasta) {
//Determinar el dtf para la fecha de liquidación
        $Fecha_ = (strtotime(str_replace('/', '-', $FECHA)));
        $f_hasta = (strtotime(str_replace('/', '-', $fecha_hasta)));

        $parametros = array();
        $historia_dtf = $this->RescatarDtf($parametros);
        $dtf_periodo = '';

        foreach ($historia_dtf as $key => $values) {

            $inicio = strtotime(str_replace('/', '-', $historia_dtf[$key]['dtf_fe_desde']));
            $fin = strtotime(str_replace('/', '-', $historia_dtf[$key]['dtf_fe_hasta']));

            if ($Fecha_ >= $inicio && $$Fecha_ <= $fin) {
                $dtf_periodo = $historia_dtf[$key]['dtf_indi_ce'];
                $cont = $key;
            }
        }

        $ley_2006 = strtotime(str_replace('/', '-', '2006-07-28'));

        if ($Fecha_ < $ley_2006) {
            $valor_interes = 0;
        } else {
//Si es mayor al 28/07/2006
            $valor_acumulado = 1;

            foreach ($historia_dtf as $cont => $values) {
                $inicio = strtotime(str_replace('/', '-', $historia_dtf[$cont]['dtf_fe_desde']));
                $fin = strtotime(str_replace('/', '-', $historia_dtf[$cont]['dtf_fe_hasta']));
                while ($f_hasta >= $fin) {
                    $dias_vigencia = floor(abs(($fin - $inicio) / 86400));
                    $valor_dtf = ((1 + pow(floatval($historia_dtf[$cont]['dtf_indi_ce']), ($dias_vigencia / 365))) - 1);
                    $valor_acumulado = $valor_acumulado * $valor_dtf;
                    $valor_interes = ($valor_cuota * $valor_acumulado);
                }
            }

            return $valor_interes;
        }
    }

    function Intereses_a2006($FECHA, $valor_cuota) {
//Determinar el dtf para la fecha de liquidación
        $Fecha_ = (strtotime(str_replace('/', '-', $FECHA)));

        $parametros = array();
        $historia_dtf = $this->RescatarDtf($parametros);
        $dtf_periodo = '';

        foreach ($historia_dtf as $key => $values) {

            $inicio = strtotime(str_replace('/', '-', $historia_dtf[$key]['dtf_fe_desde']));
            $fin = strtotime(str_replace('/', '-', $historia_dtf[$key]['dtf_fe_hasta']));
//
//            if ($Fecha_ >= $inicio && $$Fecha_ <= $fin) {
//                $dtf_periodo = $historia_dtf[$key]['dtf_indi_ce'];
//                $cont = $key;
//            }
            
             if ($Fecha_ >= $inicio) {
                $dtf_periodo = $historia_dtf[$key]['dtf_indi_ce'];
                $cont = $key;
            }
        }

        $ley_2006 = strtotime(str_replace('/', '-', '2006-07-28'));

        if ($Fecha_ <= $ley_2006) {
//Si es menor al 28/07/2006=> interes=(C*n*t)/365
//C= cuota, n=numero dias, t=tasa mora
            $valor_interes = ((floatval($valor_cuota) * 30 * floatval($dtf_periodo)) / 365);
        } else {
            $valor_interes = 0;
        }

        return $valor_interes;
    }

    function Intereses_d2006($FECHA, $valor_cuota, $fecha_hasta) {
//Determinar el dtf para la fecha de liquidación
        $Fecha_ = (strtotime(str_replace('/', '-', $FECHA)));
        $f_hasta = (strtotime(str_replace('/', '-', $fecha_hasta)));
        $valor_interes = 0;

        $parametros = array();
        $historia_dtf = $this->RescatarDtf($parametros);
        $dtf_periodo = '';

        foreach ($historia_dtf as $key => $values) {

            $inicio = strtotime(str_replace('/', '-', $historia_dtf[$key]['dtf_fe_desde']));
            $final = strtotime(str_replace('/', '-', $historia_dtf[$key]['dtf_fe_hasta']));

//            if ($Fecha_ >= $inicio && $$Fecha_ <= $final) {
//                $dtf_periodo = $historia_dtf[$key]['dtf_indi_ce'];
//                $cont = $key;
//            }
            
               if ($Fecha_ >= $inicio) {
                $dtf_periodo = $historia_dtf[$key]['dtf_indi_ce'];
                $cont = $key;
            }
        }

        $ley_2006 = strtotime(str_replace('/', '-', '2006-07-28'));

        if ($Fecha_ <= $ley_2006) {
            $valor_interes = 0;
        } else {
//Si es mayor al 28/07/2006
            $valor_acumulado = 1;

            foreach ($historia_dtf as $cont => $values) {
                $inicio = strtotime(str_replace('/', '-', $historia_dtf[$cont]['dtf_fe_desde']));
                $fin = strtotime(str_replace('/', '-', $historia_dtf[$cont]['dtf_fe_hasta']));
                while ($f_hasta >= $fin) {
                    $dias_vigencia = floor(abs(($fin - $inicio) / 86400));
                    $valor_dtf = ((1 + pow(floatval($historia_dtf[$cont]['dtf_indi_ce']), ($dias_vigencia / 365))) - 1);
                    $valor_acumulado = $valor_acumulado * $valor_dtf;
                    echo $valor_interes = ($valor_cuota * $valor_acumulado);
                }
            }

            
            return $valor_interes;
        }
    }

    function cambiafecha_format($fecha) {
        ereg("([0-9]{1,2})/([0-9]{1,2})/([0-9]{2,4})", $fecha, $mifecha);
        $fechana = $mifecha[3] . "-" . $mifecha[2] . "-" . $mifecha[1];
        return $fechana;
    }

    /* ! 
      @function num2letras ()
      @abstract Dado un n?mero lo devuelve escrito.
      @param $num number - N?mero a convertir.
      @param $fem bool - Forma femenina (true) o no (false).
      @param $dec bool - Con decimales (true) o no (false).
      @result string - Devuelve el n?mero escrito en letra.

     */

    function num2letras($num, $fem = false, $dec = true) {
        $matuni[2] = "dos";
        $matuni[3] = "tres";
        $matuni[4] = "cuatro";
        $matuni[5] = "cinco";
        $matuni[6] = "seis";
        $matuni[7] = "siete";
        $matuni[8] = "ocho";
        $matuni[9] = "nueve";
        $matuni[10] = "diez";
        $matuni[11] = "once";
        $matuni[12] = "doce";
        $matuni[13] = "trece";
        $matuni[14] = "catorce";
        $matuni[15] = "quince";
        $matuni[16] = "dieciseis";
        $matuni[17] = "diecisiete";
        $matuni[18] = "dieciocho";
        $matuni[19] = "diecinueve";
        $matuni[20] = "veinte";
        $matunisub[2] = "dos";
        $matunisub[3] = "tres";
        $matunisub[4] = "cuatro";
        $matunisub[5] = "quin";
        $matunisub[6] = "seis";
        $matunisub[7] = "sete";
        $matunisub[8] = "ocho";
        $matunisub[9] = "nove";

        $matdec[2] = "veint";
        $matdec[3] = "treinta";
        $matdec[4] = "cuarenta";
        $matdec[5] = "cincuenta";
        $matdec[6] = "sesenta";
        $matdec[7] = "setenta";
        $matdec[8] = "ochenta";
        $matdec[9] = "noventa";
        $matsub[3] = 'mill';
        $matsub[5] = 'bill';
        $matsub[7] = 'mill';
        $matsub[9] = 'trill';
        $matsub[11] = 'mill';
        $matsub[13] = 'bill';
        $matsub[15] = 'mill';
        $matmil[4] = 'millones';
        $matmil[6] = 'billones';
        $matmil[7] = 'de billones';
        $matmil[8] = 'millones de billones';
        $matmil[10] = 'trillones';
        $matmil[11] = 'de trillones';
        $matmil[12] = 'millones de trillones';
        $matmil[13] = 'de trillones';
        $matmil[14] = 'billones de trillones';
        $matmil[15] = 'de billones de trillones';
        $matmil[16] = 'millones de billones de trillones';

//Zi hack
        $float = explode('.', $num);
        $num = $float[0];

        $num = trim((string) @$num);
        if ($num[0] == '-') {
            $neg = 'menos ';
            $num = substr($num, 1);
        } else {
            $neg = '';
        }
        while ($num[0] == '0') {
            $num = substr($num, 1);
        }
        if ($num[0] < '1' or $num[0] > 9) {
            $num = '0' . $num;
        }
        $zeros = true;
        $punt = false;
        $ent = '';
        $fra = '';
        for ($c = 0; $c < strlen($num); $c++) {
            $n = $num[$c];
            if (!(strpos(".,'''", $n) === false)) {
                if ($punt) {
                    break;
                } else {
                    $punt = true;
                    continue;
                }
            } elseif (!(strpos('0123456789', $n) === false)) {
                if ($punt) {
                    if ($n != '0') {
                        $zeros = false;
                    }
                    $fra .= $n;
                } else {
                    $ent .= $n;
                }
            } else {
                break;
            }
        }
        $ent = '     ' . $ent;
        if ($dec and $fra and !$zeros) {
            $fin = ' coma';
            for ($n = 0; $n < strlen($fra); $n++) {
                if (($s = $fra[$n]) == '0') {
                    $fin .= ' cero';
                } elseif ($s == '1') {
                    $fin .= $fem ? ' una' : ' un';
                } else {
                    $fin .= ' ' . $matuni[$s];
                }
            }
        } else {
            $fin = '';
        }
        if ((int) $ent === 0) {
            return 'Cero ' . $fin;
        }
        $tex = '';
        $sub = 0;
        $mils = 0;
        $neutro = false;
        while (($num = substr($ent, -3)) != '   ') {
            $ent = substr($ent, 0, -3);
            if (++$sub < 3 and $fem) {
                $matuni[1] = 'una';
                $subcent = 'as';
            } else {
                $matuni[1] = $neutro ? 'un' : 'uno';
                $subcent = 'os';
            }
            $t = '';
            $n2 = substr($num, 1);
            if ($n2 == '00') {
                
            } elseif ($n2 < 21) {
                $t = ' ' . $matuni[(int) $n2];
            } elseif ($n2 < 30) {
                $n3 = $num[2];
                if ($n3 != 0) {
                    $t = 'i' . $matuni[$n3];
                }
                $n2 = $num[1];
                $t = ' ' . $matdec[$n2] . $t;
            } else {
                $n3 = $num[2];
                if ($n3 != 0) {
                    $t = ' y ' . $matuni[$n3];
                }
                $n2 = $num[1];
                $t = ' ' . $matdec[$n2] . $t;
            }
            $n = $num[0];
            if ($n == 1) {
                $t = ' ciento' . $t;
            } elseif ($n == 5) {
                $t = ' ' . $matunisub[$n] . 'ient' . $subcent . $t;
            } elseif ($n != 0) {
                $t = ' ' . $matunisub[$n] . 'cient' . $subcent . $t;
            }
            if ($sub == 1) {
                
            } elseif (!isset($matsub[$sub])) {
                if ($num == 1) {
                    $t = ' mil';
                } elseif ($num > 1) {
                    $t .= ' mil';
                }
            } elseif ($num == 1) {
                $t .= ' ' . $matsub[$sub] . '?n';
            } elseif ($num > 1) {
                $t .= ' ' . $matsub[$sub] . 'ones';
            }
            if ($num == '000')
                $mils ++;
            elseif ($mils != 0) {
                if (isset($matmil[$sub]))
                    $t .= ' ' . $matmil[$sub];
                $mils = 0;
            }
            $neutro = true;
            $tex = $t . $tex;
        }
        $tex = $neg . substr($tex, 1) . $fin;
//Zi hack --> return ucfirst($tex);
        $end_num = ucfirst($tex) . ' PESOS M/CTE';
        return $end_num;
    }

}

// fin de la clase








