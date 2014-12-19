
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
  | 18/05/2013 | Violet Sosa             | 0.0.0.1     |                                 |
  ----------------------------------------------------------------------------------------
  | 02/08/2013 | Violet Sosa             | 0.0.0.2     |                                 |
  ----------------------------------------------------------------------------------------
 */

/* --------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
  --------------------------------------------------------------------------------------------------------------------------- */


if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/funcionGeneral.class.php");
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/navegacion.class.php");
include_once("html.class.php");

class funciones_formRecaudo extends funcionGeneral {

    function __construct($configuracion, $sql) {
//[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo		
//include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
        include ($configuracion["raiz_documento"] . $configuracion["estilo"] . "/basico/tema.php");
        include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
        include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/log.class.php");
        include_once($configuracion["raiz_documento"] . $configuracion["plugins"] . "/html2pdf/html2pdf.class.php");

        $this->cripto = new encriptar();
        $this->log_us = new log();
        $this->tema = $tema;
        $this->sql = $sql;

//Conexion General
        $this->acceso_db = $this->conectarDB($configuracion, "mysqlFrame");

//Conexión a Postgres 
        $this->acceso_pg = $this->conectarDB($configuracion, "cuotas_partes");
//Conexión Oracle        
        $this->acceso_Oracle = $this->conectarDB($configuracion, "cuotasP");

//Datos de sesion
        $this->usuario = $this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion = $this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
        $this->configuracion = $configuracion;
        $this->html_formRecaudo = new html_formRecaudo($configuracion);
    }

    function inicio() {
        $this->html_formRecaudo->form_valor();
    }

    function inicio_cp() {
        $this->html_formRecaudo->form_valor_cp();
    }

//Consultas del Sistema
    function actualizarEstadoCobro($parametro) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "actualizarCobro", $parametro);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "actualizar");
        return $datos;
    }

    function actualizarEstadoRecta($parametro) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "actualizarRecta", $parametro);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "actualizar");
        return $datos;
    }

    function consultarConsecPago() {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "consultaPagoConsecutivo", "");
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos;
    }

    function consultar_cuentac($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "consultar_cc", $parametros);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos;
    }

    function consultarConseRecta($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "consecutivoRecta", $parametros);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos;
    }

    function consultarEntidades($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "consultarEntidades", $parametros);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos;
    }

    function consultarEntidadesRecaudo($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "consultarEntidadesRecaudo", $parametros);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos;
    }

    function consultarCobroPago($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "consultarCobrosPagos", $parametros);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos;
    }

    function consultarRecaudos($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "consultarRecaudos", $parametros);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos;
    }

    function consultarRecaudoCompleto($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "consultarRecaudoCompleto", $parametros);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos;
    }

    function consultarRecaudoUnico($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "consultarRecaudoUnico", $parametros);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos;
    }

    function consultarSaldoAnterior($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "consultarSaldoAnterior", $parametros);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos;
    }

    function consultarConsecutivo($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "consultarConsecutivo", $parametros);
        $datos_historia = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos_historia;
    }

    function consultarCobros($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "consultarCobros", $parametros);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos;
    }

    function consultarCobrosEstado($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "consultarCobrosEstado", $parametros);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos;
    }

    function consultarCargue($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "consultarCargue", $parametros);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos;
    }

    function datosConcurrencia($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "datos_concurrencia", $parametros);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos;
    }

    function datosPensionado($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_Oracle, "datos_pensionado", $parametros);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_Oracle, $cadena_sql, "busqueda");
        return $datos;
    }

    function datosSaldos($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "datos_saldos", $parametros);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos;
    }

    function datosSaldosHistoria($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "datos_saldosHistoria", $parametros);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos;
    }

    function nombreEntidad($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "nombreEntidad", $parametros);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos;
    }

    function registrarPago($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "registrarPago", $parametros);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "registrar");
        return $datos;
    }

    function registrarPagoCobro($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "registrarPagoCobro", $parametros);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "registrar");
        return $datos;
    }

    function registrarSaldo($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "registrarSaldo", $parametros);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "registrar");
        return $datos;
    }

    function consultarJefeRecursos($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_Oracle, "jefeRecursosH", $parametros);
        $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_Oracle, $cadena_sql, "busqueda");
        return $datos;
    }

//PDF

    function generarPDF_Estado($datos_basicos, $datos_recaudos, $cobros, $datos_concurrencia, $datos_saldo) {

        $a = array();
        $jefeRecursos = $this->consultarJefeRecursos($a);

        ob_start();
        $direccion = $this->configuracion['host'] . $this->configuracion['site'] . $this->configuracion['bloques'];

        $dias = array('Domingo, ', 'Lunes, ', 'Martes, ', 'Miercoles, ', 'Jueves, ', 'Viernes, ', 'Sábado, ');
        $meses = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
        $fecha_cc = $dias[date('w')] . ' ' . date('d') . ' de ' . $meses[date('n') - 1] . ' del ' . date('Y');

        $contenido_cobros = '';
        foreach ($cobros as $key => $value) {
            $contenido_cobros.= "<tr> <td  style='text-align:center;'>" . $cobros[$key]['cob_consecu_cta'] . "</td>";
            $contenido_cobros.= "<td  style='text-align:center;'>" . $cobros[$key]['cob_ie_correspondencia'] . "</td>";
            $contenido_cobros.= "<td style = 'text-align:center;'>" . date('d/m/Y', strtotime(str_replace('/', '-', $cobros[$key]['cob_fgenerado']))) . "</td>";
            $contenido_cobros.= "<td style = 'text-align:center;'>" . date('d/m/Y', strtotime(str_replace('/', '-', $cobros[$key]['cob_finicial']))) . "</td>";
            $contenido_cobros.= "<td style = 'text-align:center;'>" . date('d/m/Y', strtotime(str_replace('/', '-', $cobros[$key]['cob_ffinal']))) . "</td>";
            $contenido_cobros.= "<td style = 'text-align:center;'>$&nbsp;" . number_format($cobros[$key]['cob_ts_interes']) . "</td>";
            $contenido_cobros.= "<td style = 'text-align:center;'>$&nbsp;" . number_format($cobros[$key]['cob_interes']) . "</td>";
            $contenido_cobros.= "<td style = 'text-align:center;'>$&nbsp;" . number_format($cobros[$key]['cob_tc_interes']) . "</td>";
            $contenido_cobros.= "</tr>";
        }

        $contenido_recaudos = '';
        foreach ($datos_recaudos as $key => $value) {
            $contenido_recaudos.="<tr>";
            $contenido_recaudos.="<td  style='text-align:center;'>" . $datos_recaudos[$key]['recta_consecu_rec'] . "</td>";
            $contenido_recaudos.="<td  style='text-align:center;'>" . $datos_recaudos[$key]['rec_resolucionop'] . "</td>";
            $contenido_recaudos.="<td  style='text-align:center;'>" . $datos_recaudos[$key]['rec_fecha_resolucion'] . "</td>";
            $contenido_recaudos.="<td  style='text-align:center;'>" . $datos_recaudos[$key]['recta_fechapago'] . "</td>";
            $contenido_recaudos.="<td  style='text-align:center;'>" . $datos_recaudos[$key]['recta_fechadesde'] . "</td>";
            $contenido_recaudos.="<td  style='text-align:center;'>" . $datos_recaudos[$key]['recta_fechahasta'] . "</td>";
            $contenido_recaudos.="<td  style='text-align:center;'>$" . number_format($datos_recaudos[$key]['rec_pago_capital']) . "</td>";
            $contenido_recaudos.="<td  style='text-align:center;'>$" . number_format($datos_recaudos[$key]['rec_pago_interes']) . "</td>";
            $contenido_recaudos.="<td  style='text-align:center;'>$" . number_format($datos_recaudos[$key]['rec_total_recaudo']) . "</td>";
            $contenido_recaudos.="</tr>";
        }

        $contenido_saldo = '';
        foreach ($datos_saldo as $key => $value) {
            $contenido_saldo.="<tr>";
            $contenido_saldo.="<td  style='text-align:center;'>" . $datos_saldo[$key]['recta_consecu_cta'] . "</td>";
            $contenido_saldo.= "<td  style='text-align:center;'>" . $datos_saldo[$key]['recta_consecu_rec'] . "</td>";
            $contenido_saldo.= "<td  style='text-align:center;'>&nbsp;$&nbsp;&nbsp;" . number_format($datos_saldo[$key]['recta_valor_cobro']) . "</td>";
            $contenido_saldo.= "<td  style='text-align:center;'>&nbsp;$&nbsp;&nbsp;" . number_format($datos_saldo[$key]['recta_valor_recaudo']) . "</td>";
            $contenido_saldo.= "<td  style='text-align:center;'>&nbsp;$&nbsp;&nbsp;" . number_format($datos_saldo[$key]['recta_saldototal']) . "</td>";
            $contenido_saldo.= "</tr>";
        }

        $ContenidoPdf = "
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
                <th style=\"width:60px;\" colspan=\"1\">
                    <img alt=\"Imagen\" src=" . $direccion . "/nomina/cuotas_partes/Images/escudo1.png\" />
                </th>
                <th style=\"width:490px;font-size:13px;\" colspan=\"1\">
                    <br>UNIVERSIDAD DISTRITAL FRANCISCO JOSÉ DE CALDAS
                    <br> NIT 899999230-7<br>
                    <br> DIVISIÓN DE RECURSOS HUMANOS<br><br>
                </th>
                <th style=\"width:160px;font-size:10px;\" colspan=\"1\">
                    <br>Reporte Estado de Cuenta<br><br>
                    <br>" . $fecha_cc . "<br><br>
                </th>
            </tr>
        </thead>      

        <tr>
        </tr>
    </table>  
</page_header>

<br><br><br><br><br><br><br><br>

<table align='center'>

    <tr>
        <th colspan=\"4\" style=\"font-size:12px;width:735px;\" >
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
        <th colspan=\"4\" style=\"font-size:12px;width:735px;\">
            DATOS DE LA CONCURRENCIA
        </th>
    </tr>
    <tr>
        <td>Fecha Pensión:</td>
        <td  colspan='1'>" . date('d/m/Y', strtotime(str_replace('/', '-', $datos_concurrencia[0]['dcp_fecha_pension']))) . "</td>
        <td>Fecha Inicio Concurrencia:</td>
        <td  colspan='1'>" . date('d/m/Y', strtotime(str_replace('/', '-', $datos_concurrencia[0]['dcp_fecha_concurrencia']))) . "</td>
    </tr>
    <tr>
        <td>Acto Administrativo:</td>
        <td  colspan='1'>" . $datos_concurrencia[0]['dcp_actoadmin'] . "</td>
        <td>Fecha Acto Adminsitrativo:</td>
        <td  colspan='1'>" . date('d/m/Y', strtotime(str_replace('/', '-', $datos_concurrencia[0]['dcp_factoadmin']))) . "</td>
    </tr>
    <tr>
        <td>Mesada Inicial:</td>
        <td  colspan='3'>" . number_format($datos_concurrencia[0]['dcp_valor_mesada']) . "</td>
    </tr>
    <tr>
        <td>Porcentaje Cuota Aceptada:</td>
        <td colspan='1'>" . (($datos_concurrencia[0]['dcp_porcen_cuota']) * 100) . "%" . "</td>
        <td>Valor de la Cuota Aceptada:</td>
        <td colspan='1'>" . number_format($datos_concurrencia[0]['dcp_valor_cuota']) . "</td>
    </tr>
    <tr>
        <td>Mesada a la Fecha:</td>
        <td colspan='1'>" . number_format($datos_concurrencia[0]['dcp_valor_mesada']) . "</td>
        <td>Valor de la Cuota a la Fecha:</td>
        <td colspan='1'>" . number_format($datos_concurrencia[0]['dcp_valor_mesada']) . "</td>
    </tr>
</table>
<br>

<table align=\"center\">
    <tr>
        <th colspan=\"8\"  style=\"font-size:12px;width:735px;\" >CUENTAS COBRO REGISTRADAS</th>
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
          " . $contenido_cobros . "
</table >
<br>
<table  align=\"center\">
    <tr>
        <th colspan=\"10\" style=\"font-size:12px;width:735px;\">RECAUDOS REGISTRADOS</th>
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
        ." . $contenido_recaudos . "
</table >

<br>

<table align=\"center\">
    <tr>
        <th colspan=\"11\" style=\"font-size:12px;width:735px;\">RELACIÓN DE SALDOS</th>
    </tr>
    <tr>
        <td   style=\"font-size:10px;\" align=center>CONSECUTIVO CUENTA COBRO</td>
        <td   style=\"font-size:10px;\" align=center>&nbsp;CONSECUTIVO RECAUDO&nbsp;</td>
        <td   style=\"font-size:10px;\" align=center>VALOR TOTAL COBRO</td>
        <td   style=\"font-size:10px;\" align=center>VALOR TOTAL RECAUDO</td>
        <td   style=\"font-size:10px;\" align=center>SALDO</td>
    </tr>
        " . $contenido_saldo . "
</table >
<br>
<table align='center'>
    <tr>
        <td style=\"text-align:center; width: 733px;\"><br><br><br><br>
        </td>
    </tr>
    <tr>  
        <td align='center' style=\"text-align:center; width: 733px;\" >
            " . $jefeRecursos[0][0] . "
            <br>
            Jefe(a) División de Recursos Humanos
            <br>

        </td>
    </tr>
</table>
<page_footer>
    <table align=\"center\">
        <tr>
            <td align=\"center\" style = \"width: 750px;\">
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
        $PDF = new HTML2PDF('P', 'LETTER', 'es');
        $PDF->writeHTML($ContenidoPdf);
        $PDF->Output("EstadoCuenta.pdf", "D");
    }

//Movimiento a Formularios
    function mostrarRecaudos() {
        $cedula = array('cedula' => (isset($_REQUEST['cedula_emp']) ? $_REQUEST['cedula_emp'] : ''));

        if (!preg_match("^\d+$^", $cedula['cedula'])) {
            echo "<script type=\"text/javascript\">" .
            "alert('La cédula posee un formato inválido');" .
            "</script> ";
            error_log('\n');
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = 'pagina=formularioRecaudo';
            $variable.='&opcion=';
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
            exit;
        } else {
            $datos_entidad = $this->consultarEntidadesRecaudo($cedula);

            if (is_array($datos_entidad)) {
                $this->html_formRecaudo->datosRecaudos($cedula, $datos_entidad);
            } else {
                echo "<script type=\"text/javascript\">" .
                "alert('No existen Cuentas de Cobro registradas con cédula " . $cedula['cedula'] . ". Por lo tanto, no hay pagos a registrar.');" .
                "</script> ";
                $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                $variable = 'pagina=cuentaCobro';
                $variable.='&opcion=manual';
                $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                exit;
            }
        }
    }

    function mostrarRecaudos_cp() {
        $cedula = array('cedula' => (isset($_REQUEST['cedula_emp']) ? $_REQUEST['cedula_emp'] : ''));

        if (!preg_match("^\d+$^", $cedula['cedula'])) {
            echo "<script type=\"text/javascript\">" .
            "alert('La cédula posee un formato inválido');" .
            "</script> ";
            error_log('\n');
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = 'pagina=formularioRecaudo';
            $variable.='&opcion=';
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
            exit;
        } else {

            $datos_entidad = $this->consultarEntidadesRecaudo($cedula);

            if (is_array($datos_entidad)) {
                $this->html_formRecaudo->datosRecaudos_cp($cedula, $datos_entidad);
            } else {
                echo "<script type=\"text/javascript\">" .
                "alert('No existen Cuentas de Cobro registradas con cédula " . $cedula['cedula'] . ". Por lo tanto, no hay datos a consultar.');" .
                "</script> ";
                $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                $variable = 'pagina=cuentaCobro';
                $variable.='&opcion=manual';
                $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                exit;
            }
        }
    }

    function historiaRecaudos($datos_consulta, $saldo_cuenta) {
        $parametros = array(
            'cedula_emp' => $datos_consulta['cedula_emp'],
            'nit_previsional' => $datos_consulta['hlab_nitprev']);

        $parametros2 = array(
            'cedula' => $datos_consulta['cedula_emp'],
            'entidad' => $datos_consulta['hlab_nitprev']);

        $saldo_cc = $saldo_cuenta;
        $datos_recaudos = $this->consultarRecaudos($parametros);
        $datos_cobros = $this->consultarCobros($parametros);
        $datos_saldo = $this->datosSaldos($parametros2);

        if (is_array($datos_cobros)) {
            $this->html_formRecaudo->historiaRecaudos($datos_recaudos, $datos_cobros, $saldo_cc);
        } else {
            echo "<script type=\"text/javascript\">" .
            "alert('No existen Cuentas de Cobro registradas con cédula " . $parametros['cedula'] . " para la Entidad " . $parametros['entidad'] . ". Por lo tanto, no hay pagos a registrar.');" .
            "</script> ";
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = 'pagina=reportesCuotas';
            $variable.='&opcion=';
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
            exit;
        }
    }

    function historiaRecaudos_cp($datos_consulta) {
        $parametros = array('cedula' => $datos_consulta['cedula_emp'], 'entidad' => $datos_consulta['hlab_nitprev']);
        $parametros2 = array('cedula_emp' => $datos_consulta['cedula_emp'], 'nit_previsional' => $datos_consulta['hlab_nitprev']);

        $datos_recaudos = $this->consultarRecaudoCompleto($parametros2);
        $datos_cobros = $this->consultarCobrosEstado($parametros);
        $datos_concurrencia = $this->datosConcurrencia($parametros);
        $datos_saldo = $this->datosSaldosHistoria($parametros);

        $nombre_entidad = $this->nombreEntidad($parametros);
        $nombre_empleado = $this->datosPensionado($parametros);

        $datos_basicos = array(
            'cedula' => $parametros['cedula'],
            'nombre_emp' => $nombre_empleado[0]['NOMBRE'],
            'entidad_nombre' => $nombre_entidad[0]['prev_nombre'],
            'entidad' => $parametros['entidad']);

        if (is_array($datos_cobros)) {
            $this->html_formRecaudo->estadoCuenta($datos_basicos, $datos_recaudos, $datos_cobros, $datos_concurrencia, $datos_saldo);
        } else {
            echo "<script type=\"text/javascript\">" .
            "alert('No existen Cuentas de Cobro registradas con cédula " . $parametros['cedula'] . " para la Entidad " . $parametros['entidad'] . ". Por lo tanto, no hay pagos a registrar.');" .
            "</script> ";
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = 'pagina=reportesCuotas';
            $variable.='&opcion=';
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
            exit;
        }
    }

    function mostrarFormulario($cuentas_pago) {

        foreach ($cuentas_pago as $key => $value) {
            $fecha_cuenta[$key] = date('d/m/Y', strtotime(str_replace('/', '-', $value['fecha_cuenta'])));
        }

        rsort($fecha_cuenta);

        $fecha_minima_datepicker = $fecha_cuenta[0];

        $this->html_formRecaudo->formularioRecaudos($cuentas_pago, $fecha_minima_datepicker);
    }

    function actualizarSaldo($parametros) {

//Verificar la cuenta de cobro y recaudo
        $consultar_ccobro = $this->consultar_cuentac($parametros);
        $consultar_recaudo = $this->consultarRecaudoUnico($parametros);

        if ($consultar_ccobro == null) {
            echo "<script type=\"text/javascript\">" .
            "alert('No registra Cuenta de Cobro Válida');" .
            "</script> ";
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = 'pagina=formularioRecaudo';
            $variable.='&opcion=';
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
            exit;
        }

        if ($consultar_recaudo == null) {
            echo "<script type=\"text/javascript\">" .
            "alert('No registra Recaudo (Pago) Válido');" .
            "</script> ";
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = 'pagina=formularioRecaudo';
            $variable.='&opcion=';
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
            exit;
        }

        $consultar_saldo_anterior = $this->consultarSaldoAnterior($parametros);


        if ($consultar_saldo_anterior !== null) {
//No existen pagos anteriores registrados
//Revisión Datos de Recta
            $deuda_capital = $consultar_saldo_anterior[0]['recta_saldocapital'];
            $deuda_interes = $consultar_saldo_anterior[0]['recta_saldointeres'];
            $total_deuda = $consultar_saldo_anterior[0]['recta_saldototal'];

//Revisión datos del pago registrado
            $deuda_cuentac = $parametros['total_cobro'];
            $pago_capital = $parametros['valor_pagado_capital'];
            $pago_interes = $parametros['valor_pagado_interes'];
            $total_pago_calc = $pago_capital + $pago_interes;
            $total_pago_bd = $parametros['total_recaudo'];

//Cálculos de la deuda
            $saldo_capital = floatval($deuda_capital) - floatval($pago_capital);
            $saldo_interes = floatval($deuda_interes) - floatval($pago_interes);
            $saldo_total = $saldo_capital + $saldo_interes;

            if ($saldo_total == 0) {
//Si el saldo es 0, actualizar saldo e inactivar cuenta de cobro para cobros y actualizar registro de pago en recta
                $inactivar_cobro = $this->actualizarEstadoCobro($parametros['consecutivo_cc']);
                $inactivar_recta = $this->actualizarEstadoRecta($consultar_saldo_anterior[0]['recta_id']);

                $parametros_z = array();
                $consecutivo_recta = $this->consultarConseRecta($parametros_z);

                if ($consecutivo_recta == null) {
                    $rectaid = 1;
                } else {
                    $rectaid = $consecutivo_recta[0][0] + 1;
                }

                $para_saldo = array(
                    'recta_id' => $rectaid,
                    'recta_consecu_cta' => $parametros['consecutivo_cc'],
                    'recta_consecu_rec' => $parametros['consecutivo_rec'],
                    'recta_cedula' => $parametros['cedula_emp'],
                    'recta_nitprev' => $parametros['nit_previsional'],
                    'recta_valor_cobro' => $parametros['total_cobro'],
                    'recta_valor_recaudo' => $total_pago_bd,
                    'recta_saldocapital' => $saldo_capital,
                    'recta_saldointeres' => $saldo_interes,
                    'recta_saldototal' => $saldo_total,
                    'recta_fechapago' => $parametros['fecha_pago'],
                    'recta_fechadesde' => $parametros['fecha_pdesde'],
                    'recta_fechahasta' => $parametros['fecha_phasta'],
                    'recta_estado' => 'ACTIVO',
                    'recta_fecha_registro' => date('Y-m-d')
                );

                $registro_actualizado = $this->registrarSaldo($para_saldo);

                if ($registro_actualizado) {
                    echo "<script type=\"text/javascript\">" .
                    "alert('Cuenta de Cobro " . $parametros['consecutivo_cc'] . " con saldo igual a cero.');" .
                    "</script> ";
                    $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                    $variable = 'pagina=formularioRecaudo';
                    $variable.='&opcion=consulta_cp';
                    $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                    echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                    exit;
                } else {
                    echo "<script type=\"text/javascript\">" .
                    "alert('No se realizó el cambio de estado de la cuenta " . $parametros['consecutivo_cc'] . " con éxito3.');" .
                    "</script> ";
                    $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                    $variable = 'pagina=formularioRecaudo';
                    $variable.='&opcion=';
                    $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                    echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                    exit;
                }
            } else {
//Si el saldo es diferente de 0, inactivar registro anterior, ingresar nuevo registro de recta con valor actualizado
                $inactivar_recta = $this->actualizarEstadoRecta($consultar_saldo_anterior[0]['recta_id']);

                $parametros_z = array();
                $consecutivo_recta = $this->consultarConseRecta($parametros_z);

                if ($consecutivo_recta == null) {
                    $rectaid = 1;
                } else {
                    $rectaid = $consecutivo_recta[0][0] + 1;
                }

                $para_saldo = array(
                    'recta_id' => $rectaid,
                    'recta_consecu_cta' => $parametros['consecutivo_cc'],
                    'recta_consecu_rec' => $parametros['consecutivo_rec'],
                    'recta_cedula' => $parametros['cedula_emp'],
                    'recta_nitprev' => $parametros['nit_previsional'],
                    'recta_valor_cobro' => $parametros['total_cobro'],
                    'recta_valor_recaudo' => $total_pago_bd,
                    'recta_saldocapital' => $saldo_capital,
                    'recta_saldointeres' => $saldo_interes,
                    'recta_saldototal' => $saldo_total,
                    'recta_fechapago' => $parametros['fecha_pago'],
                    'recta_fechadesde' => $parametros['fecha_pdesde'],
                    'recta_fechahasta' => $parametros['fecha_phasta'],
                    'recta_estado' => 'ACTIVO',
                    'recta_fecha_registro' => date('Y-m-d')
                );

                $registro_actualizado = $this->registrarSaldo($para_saldo);

                if ($registro_actualizado) {
                    echo "<script type=\"text/javascript\">" .
                    "alert('Cuenta de Cobro " . $parametros['consecutivo_cc'] . " con saldo igual a " . number_format($saldo_total) . ".');" .
                    "</script> ";
                    $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                    $variable = 'pagina=formularioRecaudo';
                    $variable.='&opcion=consulta_cp';
                    $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                    echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                    exit;
                } else {
                    echo "<script type=\"text/javascript\">" .
                    "alert('No se realizó el cambio de estado de la cuenta " . $parametros['consecutivo_cc'] . " con éxito3.');" .
                    "</script> ";
                    $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                    $variable = 'pagina=formularioRecaudo';
                    $variable.='&opcion=';
                    $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                    echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                    exit;
                }
            }
        } else {
//NO existen registros de pagos anteriores, lo cual debe ser IMPOSIBLE
            echo "<script type=\"text/javascript\">" .
            "alert('Error Fatal. No se pudo recuperar los datos para actualizar el saldo.');" .
            "</script> ";
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = 'pagina=formularioRecaudo';
            $variable.='&opcion=consulta_cp';
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
            exit;
        }
    }

    function procesarFormulario($datos, $cuentas_pago) {

        foreach ($datos as $key => $value) {

            if ($datos[$key]=="") {
                echo "<script type=\"text/javascript\">" .
                "alert('Formulario NO diligenciado correctamente');" .
                "</script> ";
                $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                $variable = 'pagina=formularioRecaudo';
                $variable.='&opcion=';
                $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                exit;
            }
        }

        if (!preg_match("/([0-9]{2})\/([0-9]{2})\/([0-9]{4})/", $datos['fecha_resolucion'])) {
            echo "<script type=\"text/javascript\">" .
            "alert('Formato fecha resolución diligenciado incorrectamente');" .
            "</script> ";
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = 'pagina=formularioRecaudo';
            $variable.='&opcion=';
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace(' " . $pagina . $variable . "')</script>";
            exit;
        }

        if (!preg_match("/([0-9]{2})\/([0-9]{2})\/([0-9]{4})/", $datos['fecha_pago_cuenta'])) {
            echo "<script type=\"text/javascript\">" .
            "alert('Formato fecha pago cuenta diligenciado incorrectamente');" .
            "</script> ";
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = 'pagina=formularioRecaudo';
            $variable.='&opcion=';
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace(' " . $pagina . $variable . "')</script>";
            exit;
        }

        foreach ($datos as $key => $value) {
            if (strstr($key, 'valor_pagado_capital')) {
                $valor = substr($key, strlen('valor_pagado_capital'));

                if ($datos['valor_pagado_capital' . $valor] == 0) {
                    echo "<script type=\"text/javascript\">" .
                    "alert('Valor Pagado NO Válido');" .
                    "</script> ";
                    $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                    $variable = 'pagina=formularioRecaudo';
                    $variable.='&opcion=';
                    $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                    echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                    exit;
                }
            }
        }

        $validacion = array();
        
        foreach ($cuentas_pago as $key => $values) {
            $validacion[$key]['saldo_capital'] = doubleval($cuentas_pago[$key]['saldo_capital']);
            $validacion[$key]['saldo_interes'] = doubleval($cuentas_pago[$key]['saldo_interes']);
        }

        foreach ($datos as $key => $values) {
            if (strstr($key, 'valor_pagado_capital')) {
                $valor = substr($key, strlen('valor_pagado_capital'));
                $validacion[$valor]['valor_pagado_capital'] = doubleval($datos['valor_pagado_capital' . $valor]);
            }

            if (strstr($key, 'valor_pagado_interes')) {
                $valor = substr($key, strlen('valor_pagado_interes'));
                $validacion[$valor]['valor_pagado_interes'] = doubleval($datos['valor_pagado_interes' . $valor]);
            }
        }


        foreach ($validacion as $key => $values) {
            if ($validacion[$key]['valor_pagado_capital'] > $validacion[$key]['saldo_capital']) {
                echo "<script type=\"text/javascript\">" .
                "alert('Valor Pagado NO Válido');" .
                "</script> ";
                $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                $variable = 'pagina=formularioRecaudo';
                $variable.='&opcion=';
                $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                exit;
            }
        }

        foreach ($validacion as $key => $values) {
            if ($validacion[$key]['valor_pagado_interes'] > $validacion[$key]['saldo_interes']) {
                echo "<script type=\"text/javascript\">" .
                "alert('Valor Pagado NO Válido');" .
                "</script> ";
                $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                $variable = 'pagina=formularioRecaudo';
                $variable.='&opcion=';
                $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                exit;
            }
        }


        $total_capital = 0;
        $total_interes = 0;

        foreach ($datos as $key => $value) {
            if (strstr($key, 'valor_pagado_capital')) {
                $valor = substr($key, strlen('valor_pagado_capital'));
                $total_capital = intval($datos['valor_pagado_capital' . $valor]) + $total_capital;
            }

            if (strstr($key, 'valor_pagado_interes')) {
                $valor = substr($key, strlen('valor_pagado_interes'));
                $total_interes = intval($datos['valor_pagado_interes' . $valor]) + $total_interes;
            }
        }

        $total_pagado = intval($total_capital) + intval($total_interes);

        if (intval($datos['total_recaudo']) !== intval($total_pagado)) {
            echo "<script type=\"text/javascript\">" .
            "alert('Valor Total Pagado no corresponde a la Suma de los valores correspondientes!');" .
            "</script> ";
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = 'pagina=formularioRecaudo';
            $variable.='&opcion=';
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
            exit;
        }

        /** Validar el traslape de las fechas de cobro y pago* */
        $parametros_rec = array(
            'cedula_emp' => $datos['cedula_emp'],
            'nit_previsional' => $datos['nit_previsional']
        );


        foreach ($datos as $key => $value) {
            if (strstr($key, 'fecha_cinicio')) {
                $valor = substr($key, strlen('fecha_cinicio'));
                $rango[$valor]['inicio'] = $datos['fecha_cinicio' . $valor];
            }

            if (strstr($key, 'fecha_cfin')) {
                $valor = substr($key, strlen('fecha_cfin'));
                $rango[$valor]['fin'] = $datos['fecha_cfin' . $valor];
            }

            if (strstr($key, 'fecha_pinicio')) {
                $valor = substr($key, strlen('fecha_pinicio'));
                $rango[$valor]['desde'] = $datos['fecha_pinicio' . $valor];
            }

            if (strstr($key, 'fecha_pfin')) {
                $valor = substr($key, strlen('fecha_pfin'));
                $rango[$valor]['hasta'] = $datos['fecha_pfin' . $valor];
            }
        }


        /*  foreach ($rango as $key => $values) {
          $antes = strtotime(str_replace('/', '-', $rango[$key]['desde']));
          $despues = strtotime(str_replace('/', '-', $rango[$key]['hasta']));

          foreach ($rango as $key => $values) {
          $inicio = strtotime(str_replace('/', '-', $rango[$key]['inicio']));
          $fin = strtotime(str_replace('/', '-', $rango[$key]['fin']));

          if ($antes > $inicio && $antes < $fin) {
          echo "<script type=\"text/javascript\">" .
          "alert('El intervalo de pago no es válido');" .
          "</script> ";
          error_log('\n');
          $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
          $variable = 'pagina=formHistoria';
          $variable.='&opcion=';
          $variable = $this->cripto->codificar_url($variable, $this->configuracion);
          echo "<script>location.replace('" . $pagina . $variable . "')</script>";
          exit;
          }

          if ($despues > $inicio && $despues < $fin) {
          echo "<script type=\"text/javascript\">" .
          "alert('El intervalo de pago no es válido');" .
          "</script> ";
          error_log('\n');
          $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
          $variable = 'pagina=formHistoria';
          $variable.='&opcion=';
          $variable = $this->cripto->codificar_url($variable, $this->configuracion);
          echo "<script>location.replace('" . $pagina . $variable . "')</script>";
          exit;
          }
          }
          }

          $recaudos = $this->consultarRecaudos($parametros_rec);

          if (is_array($recaudos)) {
          foreach ($rango as $key => $values) {
          $antes = strtotime(str_replace('/', '-', $rango[$key]['desde']));
          $despues = strtotime(str_replace('/', '-', $rango[$key]['hasta']));

          foreach ($recaudos as $key => $values) {
          $inicio = strtotime(str_replace('/', '-', $recaudos[$key]['recta_fechadesde']));
          $fin = strtotime(str_replace('/', '-', $recaudos[$key]['recta_fechahasta']));

          if ($antes >= $inicio && $antes <= $fin) {
          echo "<script type=\"text/javascript\">" .
          "alert('El intervalo de pago no es válido');" .
          "</script> ";
          error_log('\n');
          $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
          $variable = 'pagina=formHistoria';
          $variable.='&opcion=';
          $variable = $this->cripto->codificar_url($variable, $this->configuracion);
          echo "<script>location.replace('" . $pagina . $variable . "')</script>";
          exit;
          }

          if ($despues >= $inicio && $despues <= $fin) {
          echo "<script type=\"text/javascript\">" .
          "alert('El intervalo de pago no es válido');" .
          "</script> ";
          error_log('\n');
          $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
          $variable = 'pagina=formHistoria';
          $variable.='&opcion=';
          $variable = $this->cripto->codificar_url($variable, $this->configuracion);
          echo "<script>location.replace('" . $pagina . $variable . "')</script>";
          exit;
          }
          }
          }
          }
          /*         * Fin de las validaciones de los datos */

        $consecutivo = $this->consultarConsecPago();
        $cons = $consecutivo[0]['rec_id'] + 1;
        $annio = date("Y");

        if ($cons <= 9) {
            $cons_recaudo = "RC-000" . $cons . "-" . $annio;
        } elseif ($cons <= 99) {
            $cons_recaudo = "RC-00" . $cons . "-" . $annio;
        } elseif ($cons <= 999) {
            $cons_recaudo = "RC-0" . $cons . "-" . $annio;
        } else {
            $cons_recaudo = "RC-" . $cons . "-" . $annio;
        }

        $parametros_recaudo = array(
            'rec_id' => $cons,
            'consecutivo_rec' => $cons_recaudo,
            'nit_previsional' => (isset($datos['nit_previsional']) ? $datos['nit_previsional'] : ''),
            'cedula_emp' => (isset($datos['cedula_emp']) ? $datos['cedula_emp'] : ''),
            'actoadmin' => '',
            'factoadmin' => '',
            'resolucion_OP' => (isset($datos['resolucion_OP']) ? $datos['resolucion_OP'] : ''),
            'fecha_resolucion' => (isset($datos['fecha_resolucion']) ? $datos['fecha_resolucion'] : ''),
            'fecha_pago_cuenta' => (isset($datos['fecha_pago_cuenta']) ? $datos['fecha_pago_cuenta'] : ''),
            'medio_pago' => (isset($datos['medio_pago']) ? $datos['medio_pago'] : ''),
            'valor_pagado_capital' => (isset($total_capital) ? $total_capital : ''),
            'valor_pagado_interes' => (isset($total_interes) ? $total_interes : ''),
            'fecha_registro' => date('Y-m-d'),
            'total_recaudo' => (isset($datos['total_recaudo']) ? $datos['total_recaudo'] : ''));

        $datos_recaudo = $this->registrarPago($parametros_recaudo);

        if ($datos_recaudo == 1) {

            foreach ($datos as $key => $value) {

                if (strstr($key, 'consec')) {
                    $valor = substr($key, strlen('consec_cc'));

                    $total_Recaudo = intval($datos['valor_pagado_interes' . $valor]) + intval($datos['valor_pagado_capital' . $valor]);

                    $parametros = array(
                        'consecutivo_cc' => $datos['consec_cc' . $valor],
                        'consecutivo_rec' => $cons_recaudo,
                        'cedula_emp' => $datos['cedula_emp'],
                        'nit_previsional' => $datos['nit_previsional'],
                        'valor_pagado_capital' => $datos['valor_pagado_capital' . $valor],
                        'valor_pagado_interes' => $datos['valor_pagado_interes' . $valor],
                        'total_recaudo' => $total_Recaudo,
                        'total_cobro' => $datos['valor_cobro_' . $valor],
                        'fecha_pago' => $datos['fecha_pago_cuenta'],
                        'fecha_pdesde' => $datos['fecha_pinicio' . $valor],
                        'fecha_phasta' => $datos['fecha_pfin' . $valor]);

                    $revisar_saldo = $this->actualizarSaldo($parametros);

                    /* Las siguientes funciones se realizan en actualizar Saldo
                     * $datos_recaudo_cobro = $this->registrarPagoCobro($parametros);

                      if ($datos_recaudo_cobro == 1) {
                      $registroL[0] = "REGISTRAR";
                      $registroL[1] = $parametros['cedula_emp'] . '|' . $parametros['nit_previsional']; //
                      $registroL[2] = "CUOTAS_PARTES";
                      $registroL[3] = $parametros['consecutivo_cc'] . '|' . $parametros['consecutivo_rec'] . '|' . $parametros['total_recaudo'] . '|' . $parametros['fecha_pdesde'] . '|' . $parametros['fecha_phasta']; //
                      $registroL[4] = time();
                      $registroL[5] = "Registra el pago_cobro para el pensionado con ";
                      $registroL[5] .= " identificacion =" . $parametros['cedula_emp'];
                      $this->log_us->log_usuario($registroL, $this->configuracion);
                      } else {
                      echo "<script type=\"text/javascript\">" .
                      "alert('Datos de Recaudo-Cobro NO Registrados Correctamente. ERROR en el REGISTRO');" .
                      "</script> ";

                      $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                      $variable = 'pagina=formularioRecaudo';
                      $variable .= "&opcion=";
                      $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                      echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                      break;
                      }

                      $actualizar_cobro = $this->actualizarEstadoCobro($parametros['consecutivo_cc']);

                      if ($actualizar_cobro == false) {
                      echo "<script type=\"text/javascript\">" .
                      "alert('Datos de Actualización de Cobro NO Registrados Correctamente. ERROR en el REGISTRO');" .
                      "</script> ";

                      $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                      $variable = 'pagina=formularioRecaudo';
                      $variable .= "&opcion=";
                      $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                      echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                      break;
                      } else {
                      $registroL[0] = "ACTUALIZAR";
                      $registroL[1] = $parametros['cedula_emp'] . '|' . $parametros['nit_previsional']; //
                      $registroL[2] = "CUOTAS_PARTES";
                      $registroL[3] = $parametros['consecutivo_cc'] . '|' . $parametros['consecutivo_rec'] . '|' . $parametros['total_recaudo'] . '|' . $parametros['fecha_pdesde'] . '|' . $parametros['fecha_phasta']; //
                      $registroL[4] = time();
                      $registroL[5] = "Actualiza el cobro para el pensionado con ";
                      $registroL[5] .= " identificacion =" . $parametros['cedula_emp'];
                      $this->log_us->log_usuario($registroL, $this->configuracion);
                      } */
                }
            }

            $registroL[0] = "REGISTRAR";
            $registroL[1] = $parametros['cedula_emp'] . '|' . $parametros['nit_previsional']; //
            $registroL[2] = "CUOTAS_PARTES";
            $registroL[3] = $parametros['consecutivo_cc'] . '|' . $parametros['consecutivo_rec'] . '|' . $parametros['total_recaudo'] . '|' . $parametros['fecha_pdesde'] . '|' . $parametros['fecha_phasta']; //
            $registroL[4] = time();
            $registroL[5] = "Registra el pago para el pensionado con ";
            $registroL[5] .= " identificacion =" . $parametros['cedula_emp'];
            $this->log_us->log_usuario($registroL, $this->configuracion);

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
            "alert('Datos de Recaudos NO Registrados Correctamente. ERROR en el REGISTRO');" .
            "</script> ";

            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = 'pagina=formularioRecaudo';
            $variable .= "&opcion=";
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
            exit;
        }
    }

//**************************************** Para cargues Masivos ********************************************//
    function actualizarSaldo_masivo($parametros) {

//Verificar la cuenta de cobro y recaudo
        $consultar_ccobro = $this->consultar_cuentac($parametros);
        $consultar_recaudo = $this->consultarRecaudoUnico($parametros);

        if ($consultar_ccobro == null) {
            echo "<script type=\"text/javascript\">" .
            "alert('No registra Cuenta de Cobro Válida');" .
            "</script> ";
        }

        if ($consultar_recaudo == null) {
            echo "<script type=\"text/javascript\">" .
            "alert('No registra Recaudo (Pago) Válido');" .
            "</script> ";
        }

        $consultar_saldo_anterior = $this->consultarSaldoAnterior($parametros);

        if ($consultar_saldo_anterior !== null) {
//No existen pagos anteriores registrados
//Revisión Datos de Recta
            $deuda_capital = $consultar_saldo_anterior[0]['recta_saldocapital'];
            $deuda_interes = $consultar_saldo_anterior[0]['recta_saldointeres'];
            $total_deuda = $consultar_saldo_anterior[0]['recta_saldototal'];

//Revisión datos del pago registrado
            $deuda_cuentac = $parametros['total_cobro'];
            $pago_capital = $parametros['valor_pagado_capital'];
            $pago_interes = $parametros['valor_pagado_interes'];
            $total_pago_calc = $pago_capital + $pago_interes;
            $total_pago_bd = $parametros['total_recaudo'];

//Cálculos de la deuda
            $saldo_capital = floatval($deuda_capital) - floatval($pago_capital);
            $saldo_interes = floatval($deuda_interes) - floatval($pago_interes);
            $saldo_total = $saldo_capital + $saldo_interes;

            if ($saldo_total == 0) {
//Si el saldo es 0, actualizar saldo e inactivar cuenta de cobro para cobros y actualizar registro de pago en recta
                $inactivar_cobro = $this->actualizarEstadoCobro($parametros['consecutivo_cc']);
                $inactivar_recta = $this->actualizarEstadoRecta($consultar_saldo_anterior[0]['recta_id']);

                $parametros_z = array();
                $consecutivo_recta = $this->consultarConseRecta($parametros_z);

                if ($consecutivo_recta == null) {
                    $rectaid = 1;
                } else {
                    $rectaid = $consecutivo_recta[0][0] + 1;
                }

                $para_saldo = array(
                    'recta_id' => $rectaid,
                    'recta_consecu_cta' => $parametros['consecutivo_cc'],
                    'recta_consecu_rec' => $parametros['consecutivo_rec'],
                    'recta_cedula' => $parametros['cedula_emp'],
                    'recta_nitprev' => $parametros['nit_previsional'],
                    'recta_valor_cobro' => $parametros['total_cobro'],
                    'recta_valor_recaudo' => $total_pago_bd,
                    'recta_saldocapital' => $saldo_capital,
                    'recta_saldointeres' => $saldo_interes,
                    'recta_saldototal' => $saldo_total,
                    'recta_fechapago' => $parametros['fecha_pago'],
                    'recta_fechadesde' => $parametros['fecha_pdesde'],
                    'recta_fechahasta' => $parametros['fecha_phasta'],
                    'recta_estado' => 'ACTIVO',
                    'recta_fecha_registro' => date('Y-m-d')
                );

                $registro_actualizado = $this->registrarSaldo($para_saldo);

                if ($registro_actualizado) {
                    echo "<script type=\"text/javascript\">" .
                    "alert('Cuenta de Cobro " . $parametros['consecutivo_cc'] . " con saldo igual a cero.');" .
                    "</script> ";
                } else {
                    echo "<script type=\"text/javascript\">" .
                    "alert('No se realizó el cambio de estado de la cuenta " . $parametros['consecutivo_cc'] . " con éxito3.');" .
                    "</script> ";
                }
            } else {
//Si el saldo es diferente de 0, inactivar registro anterior, ingresar nuevo registro de recta con valor actualizado
                $inactivar_recta = $this->actualizarEstadoRecta($consultar_saldo_anterior[0]['recta_id']);

                $parametros_z = array();
                $consecutivo_recta = $this->consultarConseRecta($parametros_z);

                if ($consecutivo_recta == null) {
                    $rectaid = 1;
                } else {
                    $rectaid = $consecutivo_recta[0][0] + 1;
                }

                $para_saldo = array(
                    'recta_id' => $rectaid,
                    'recta_consecu_cta' => $parametros['consecutivo_cc'],
                    'recta_consecu_rec' => $parametros['consecutivo_rec'],
                    'recta_cedula' => $parametros['cedula_emp'],
                    'recta_nitprev' => $parametros['nit_previsional'],
                    'recta_valor_cobro' => $parametros['total_cobro'],
                    'recta_valor_recaudo' => $total_pago_bd,
                    'recta_saldocapital' => $saldo_capital,
                    'recta_saldointeres' => $saldo_interes,
                    'recta_saldototal' => $saldo_total,
                    'recta_fechapago' => $parametros['fecha_pago'],
                    'recta_fechadesde' => $parametros['fecha_pdesde'],
                    'recta_fechahasta' => $parametros['fecha_phasta'],
                    'recta_estado' => 'ACTIVO',
                    'recta_fecha_registro' => date('Y-m-d')
                );

                $registro_actualizado = $this->registrarSaldo($para_saldo);

                if ($registro_actualizado) {
                    echo "<script type=\"text/javascript\">" .
                    "alert('Cuenta de Cobro " . $parametros['consecutivo_cc'] . " con saldo igual a " . number_format($saldo_total) . ".');" .
                    "</script> ";
                } else {
                    echo "<script type=\"text/javascript\">" .
                    "alert('No se realizó el cambio de estado de la cuenta " . $parametros['consecutivo_cc'] . " con éxito3.');" .
                    "</script> ";
                }
            }
        } else {
//NO existen registros de pagos anteriores, lo cual debe ser IMPOSIBLE
            echo "<script type=\"text/javascript\">" .
            "alert('Error Fatal. No se pudo recuperar los datos para actualizar el saldo.');" .
            "</script> ";
        }
    }

    function procesarFormulario_Masivo() {

        $count = 0;
        /* llamar datos de la BD para los recaudos masivos */
        $consultaParametro = array();
        $datos_masivos = $this->consultarCargue($consultaParametro);

        foreach ($datos_masivos as $key => $values) {
            $parametros = array(
                'cedula_emp' => $datos_masivos[$key]['cedula'],
                'nit_previsional' => $datos_masivos[$key]['nit_previsora']);

            $datos_cobros = $this->consultarCobros($parametros);

            $datos = array(
                'cedula_emp' => $datos_masivos[$key]['cedula'],
                'nit_previsional' => $datos_masivos[$key]['nit_previsora'],
                'resolucion_OP' => $datos_masivos[$key]['resolucion_op'],
                'fecha_resolucion' => $datos_masivos[$key]['fecha_resoop'],
                'consec_cc' => $datos_cobros[0]['cob_consecu_cta'],
                'valor_cobro' => $datos_cobros[0]['cob_tc_interes'],
                'valor_saldo' => $datos_cobros[0]['recta_saldototal'],
                'valor_pagado_capital' => round($datos_masivos[$key]['capital']),
                'valor_pagado_interes' => round($datos_masivos[$key]['interes']),
                'total_recaudo' => round($datos_masivos[$key]['total']),
                'fecha_pago_cuenta' => $datos_masivos[$key]['fechapago'],
                'fecha_pinicio' => $datos_masivos[$key]['fpdesde'],
                'fecha_pfin' => $datos_masivos[$key]['fphasta'],
                'medio_pago' => $datos_masivos[$key]['observacion'],
                'fecha_cinicio' => $datos_cobros[0]['cob_finicial'],
                'fecha_cfin' => $datos_cobros[0]['cob_ffinal'],
            );


            $total_capital = 0;
            $total_interes = 0;

            $total_capital = intval($datos['valor_pagado_capital']);
            $total_interes = intval($datos['valor_pagado_interes']);

            $total_pagado = intval($total_capital) + intval($total_interes);

            if (intval($datos['total_recaudo']) !== intval($total_pagado)) {
                echo "<script type=\"text/javascript\">" .
                "alert('Valor Total Pagado no corresponde a la Suma de los valores correspondientes!');" .
                "</script> ";
            }

            $consecutivo = $this->consultarConsecPago();
            $cons = intval($consecutivo[0]['rec_id']) + 1;
            $annio = date("Y");

            if ($cons <= 9) {
                $cons_recaudo = "RC-000" . $cons . "-" . $annio;
            } elseif ($cons <= 99) {
                $cons_recaudo = "RC-00" . $cons . "-" . $annio;
            } elseif ($cons <= 999) {
                $cons_recaudo = "RC-0" . $cons . "-" . $annio;
            } else {
                $cons_recaudo = "RC-" . $cons . "-" . $annio;
            }

            $parametros_recaudo = array(
                'rec_id' => $cons,
                'consecutivo_rec' => $cons_recaudo,
                'nit_previsional' => (isset($datos['nit_previsional']) ? $datos['nit_previsional'] : ''),
                'cedula_emp' => (isset($datos['cedula_emp']) ? $datos['cedula_emp'] : ''),
                'actoadmin' => '',
                'factoadmin' => '',
                'resolucion_OP' => (isset($datos['resolucion_OP']) ? $datos['resolucion_OP'] : ''),
                'fecha_resolucion' => (isset($datos['fecha_resolucion']) ? $datos['fecha_resolucion'] : ''),
                'fecha_pago_cuenta' => (isset($datos['fecha_pago_cuenta']) ? $datos['fecha_pago_cuenta'] : ''),
                'medio_pago' => (isset($datos['medio_pago']) ? $datos['medio_pago'] : ''),
                'valor_pagado_capital' => (isset($total_capital) ? $total_capital : ''),
                'valor_pagado_interes' => (isset($total_interes) ? $total_interes : ''),
                'fecha_registro' => date('Y-m-d'),
                'total_recaudo' => (isset($datos['total_recaudo']) ? $datos['total_recaudo'] : ''));

            $datos_recaudo = $this->registrarPago($parametros_recaudo);

            if ($datos_recaudo == 1) {


                $total_Recaudo = $total_pagado;

                $parametros = array(
                    'consecutivo_cc' => $datos['consec_cc'],
                    'consecutivo_rec' => $cons_recaudo,
                    'cedula_emp' => $datos['cedula_emp'],
                    'nit_previsional' => $datos['nit_previsional'],
                    'valor_pagado_capital' => $datos['valor_pagado_capital'],
                    'valor_pagado_interes' => $datos['valor_pagado_interes'],
                    'total_recaudo' => $total_Recaudo,
                    'total_cobro' => $datos['valor_cobro'],
                    'fecha_pago' => $datos['fecha_pago_cuenta'],
                    'fecha_pdesde' => $datos['fecha_pinicio'],
                    'fecha_phasta' => $datos['fecha_pfin']
                );

                $revisar_saldo = $this->actualizarSaldo_masivo($parametros);

                /* $datos_recaudo_cobro = $this->registrarPagoCobro($parametros);

                  if ($datos_recaudo_cobro == 1) {
                  echo "pago-cobro registrado";
                  } else {
                  echo "<script type=\"text/javascript\">" .
                  "alert('Datos de Recaudo-Cobro NO Registrados Correctamente. ERROR en el REGISTRO');" .
                  "</script> ";
                  }

                  $actualizar_cobro = $this->actualizarEstadoCobro($parametros['consecutivo_cc']);

                  if ($actualizar_cobro == false) {
                  echo "<script type=\"text/javascript\">" .
                  "alert('Datos de Actualización de Cobro NO Registrados Correctamente. ERROR en el REGISTRO');" .
                  "</script> ";
                  } else {
                  echo "cobro actualizado";
                  } */
            } else {

                echo "<script type=\"text/javascript\">" .
                "alert('Datos de Recaudos NO Registrados Correctamente. ERROR en el REGISTRO');" .
                "</script> ";
            }
            $count++;
        }
        echo "Se realizó con éxito el registro de " . $count . " recaudos. ";
    }

}
