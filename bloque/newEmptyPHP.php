"
<style type = \"text/css\">
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
                    <br>DETALLE ESTADO DE CUENTA<br>
                    <br>" . $fecha_cc . "<br><br>
                </th>
            </tr>
        </thead>
    </table>  
</page_header>

<br><br><br><br><br><br><br><br><br><br><br><br>
<br>

<table  width =\"77%\">

    <tr>
        <th colspan=\"4\" style=\"font-size:12px;\" >
            DATOS BÁSICOS
        </th>
    </tr>
    <tr>
        <td  >Nombre Pensionado:</td>
        <td  colspan='1'>" . $datos_basicos['nombre_emp'] . "</td>
        <td  >Documento del Pensionado:</td>
        <td  colspan='1'>" . $datos_basicos['cedula'] . "</td>
    </tr>
    <tr>
        <td  >Entidad Concurrente:</td>
        <td  colspan='1'>" . $datos_basicos['entidad_nombre'] . "</td>
        <td  >NIT:</td>
        <td  colspan='1'>" . $datos_basicos['entidad'] . "</td>
    </tr>
    <tr>
        <th colspan=\"4\" style=\"font-size:12px;\" >
            DATOS DE LA CONCURRENCIA
        </th>
    </tr>
    <tr>
        <td  >Fecha Pensión:</td>
        <td  colspan='1'>" . date('d/m/Y', strtotime(str_replace('/', '-', $datos_concurrencia[0]['dcp_fecha_pension']))) . "</td>
        <td  >Fecha Inicio Concurrencia:</td>
        <td  colspan='1'>" . date('d/m/Y', strtotime(str_replace('/', '-', $datos_concurrencia[0]['dcp_fecha_concurrencia']))) . "</td>
    </tr>
    <tr>
        <td  >Acto Administrativo:</td>
        <td  colspan='1'>" . $datos_concurrencia[0]['dcp_actoadmin'] . "</td>
        <td  >Fecha Acto Adminsitrativo:</td>
        <td  colspan='1'>" . date('d/m/Y', strtotime(str_replace('/', '-', $datos_concurrencia[0]['dcp_factoadmin']))) . "</td>
    </tr>
    <tr>
        <td  >Mesada Inicial:</td>
        <td  colspan='3'>" . number_format($datos_concurrencia[0]['dcp_valor_mesada']) . "</td>
    </tr>
    <tr>
        <td  >Porcentaje Cuota Aceptada:</td>
        <td  colspan='1'>" . (($datos_concurrencia[0]['dcp_porcen_cuota']) * 100) . "%" . "</td>
        <td  >Valor de la Cuota Aceptada:</td>
        <td  colspan='1'>" . number_format($datos_concurrencia[0]['dcp_valor_cuota']) . "</td>
    </tr>
    <tr>
        <td  >Mesada a la Fecha:</td>
        <td  colspan='1'>" . number_format($datos_concurrencia[0]['dcp_valor_mesada']) . "</td>
        <td  >Valor de la Cuota a la Fecha:</td>
        <td  colspan='1'>" . number_format($datos_concurrencia[0]['dcp_valor_mesada']) . "</td>
    </tr>
</table>
<br>

<table  width =\"77%\" align=\"center\">
    <tr>
        <th colspan=\"8\"  style=\"font-size:12px;\" >CUENTAS COBRO REGISTRADAS</th>
    </tr>
    <tr>
        <td rowspan=\"2\"  style=\"font-size:10px;\" align=center>CONSECUTIVO CUENTA COBRO</td>
        <td rowspan=\"2\"  style=\"font-size:10px;\" align=center>IE_CORRESPONDENCIA</td>
        <td rowspan=\"2\"  style=\"font-size:10px;\" align=center>FECHA GENERACIÓN</td>
        <td colspan=\"2\"  style=\"font-size:10px;\" align=center>PERIODO DE COBRO</td>
        <td rowspan=\"2\"  style=\"font-size:10px;\" align=center>VALOR SIN INTERÉS</td>
        <td rowspan=\"2\"  style=\"font-size:10px;\" align=center>INTERÉS</td>
        <td rowspan=\"2\"  style=\"font-size:10px;\" align=center>TOTAL</td>
    </tr>
    <tr>
        <td  style=\"text-align:center ;font-size:10px;\" >INICIO</td>
        <td  style=\"text-align:center;font-size:10px;\">FIN</td>
    </tr>
    <tr>
        " . $contenido_cobros . "
</table >
<br>
<table  width =\"77%\" align=\"center\">
    <tr>
        <th colspan=\"10\"  style=\"font-size:12px;\" >RECAUDOS REGISTRADOS</th>
    </tr>
    <tr>
        <td rowspan=\"2\"  style=\"font-size:10px;\" align=center>CONSECUTIVO RECAUDO</td>
        <td rowspan=\"2\"  style=\"font-size:10px;\" align=center>RES. OP</td>
        <td rowspan=\"2\"  style=\"font-size:10px;\" align=center>FECHA RES. OP</td>
        <td rowspan=\"2\"  style=\"font-size:10px;\" align=center>FECHA PAGO</td>
        <td colspan=\"2\"  style=\"font-size:10px;\" align=center>PERIODO PAGO</td>
        <td rowspan=\"2\"  style=\"font-size:10px;\" align=center>VALOR A CAPITAL</td>
        <td rowspan=\"2\"  style=\"font-size:10px;\" align=center>VALOR A INTERESES</td>
        <td rowspan=\"2\"  style=\"font-size:10px;\" align=center>TOTAL</td>
    </tr>
    <tr>
        <td  style=\"text-align:center ;font-size:10px;\" >INICIO</td>
        <td  style=\"text-align:center;font-size:10px;\">FIN</td>
    </tr>
    <tr>
        .".$contenido_recaudos."
</table >

<br>

<table width = \"77%\" align=\"center\">
    <tr>
        <th colspan=\"11\"  style=\"font-size:12px;\" >RELACIÓN DE SALDOS</th>
    </tr>
    <tr>
        <td   style=\"font-size:10px;\" align=center>CONSECUTIVO CUENTA COBRO</td>
        <td   style=\"font-size:10px;\" align=center>&nbsp;CONSECUTIVO RECAUDO&nbsp;</td>
        <td   style=\"font-size:10px;\" align=center>VALOR TOTAL COBRO</td>
        <td   style=\"font-size:10px;\" align=center>VALOR TOTAL RECAUDO</td>
        <td   style=\"font-size:10px;\" align=center>SALDO</td>
    </tr>
    <tr>
        ".$contenido_saldo."
</table >
<br>

<page_footer>
    <table align = 'center' width = '100%'>
        <tr>
            <td align = 'center' style = \"width: 750px;\">
                Universidad Distrital Francisco José de Caldas
                <br>
                Todos los derechos reservados.
                <br>
                Carrera 8 N. 40-78 Piso 1 / PBX 3238400 - 3239300, Ext. 1618 - 1603
                <br>

            </td>
        </tr>
    </table>
    <p style=\"font-size:8px\">Diseño forma: JUAN D. CALDERON MARTIN</p>
</page_footer> 


";