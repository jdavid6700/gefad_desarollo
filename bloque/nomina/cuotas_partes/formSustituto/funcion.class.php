
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
  |				Control Versiones				       	 |
  ----------------------------------------------------------------------------------------
  | fecha      |        Autor            | version     |              Detalle            |
  ----------------------------------------------------------------------------------------
  | 01/05/2014 | Violet Sosa             | 0.0.0.1     |                                 |
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

class funciones_formSustituto extends funcionGeneral {

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

        $this->html_formSustituto = new html_formSustituto($configuracion);
    }

    function inicio() {
        $this->html_formSustituto->form_valor();
    }

    function mostrarFormulario($cedula) {

        $parametros = array(
            'cedula' => $cedula);

        $datos_sustituto = $this->consultarSustituto($parametros);
        $datos_pensionado = $this->consultarPensionado($parametros);
        $datos_pensionado_pg = $this->consultarPensionadoPG($parametros);
        $defuncion = array(
            'fecha_defuncion' => 0,
            'defuncion_certificado' => 0,
            'fecha_defuncioncertificado' => 0,
        );

        if (is_array($datos_sustituto)) {

            $defuncion = array(
                'fecha_defuncion' => $datos_sustituto[0]['sus_fdefuncion'],
                'defuncion_certificado' => $datos_sustituto[0]['sus_certificado_defuncion'],
                'fecha_defuncioncertificado' => $datos_sustituto[0]['sus_fcertificado_defuncion'],);
        }
        if (is_array($datos_pensionado)) {
            $this->html_formSustituto->formularioSustituto($cedula, $datos_pensionado, $defuncion);
        } else {
            if (is_array($datos_pensionado_pg)) {
                $this->html_formSustituto->formularioSustituto($cedula, $datos_pensionado_pg, $defuncion);
            } else {
                echo "<script type=\"text/javascript\">" .
                "alert('No existe registro de fecha de pensión para la cédula " . $cedula . "');" .
                "</script> ";
                $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                $variable = 'pagina=formularioSustituto';
                $variable.='&opcion=';
                $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                exit;
            }
        }
    }

    function registrarSustituto($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "insertarSustituto", $parametros);
        $datos_DescripcionCP = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "insertar");
        return $datos_DescripcionCP;
    }

    function consultarSustituto($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "reporteSustituto", $parametros);
        $datos_registro = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos_registro;
    }

    function reporteSustitutos($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "listadoSustitutos", $parametros);
        $datos_registro = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos_registro;
    }

    function consultarPensionado($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_Oracle, "datos_pensionado", $parametros);
        $datos_registro = $this->ejecutarSQL($this->configuracion, $this->acceso_Oracle, $cadena_sql, "busqueda");
        return $datos_registro;
    }

    function consultarPensionadoPG($parametros) {
        $cadena_sql = $this->sql->cadena_sql($this->configuracion, $this->acceso_pg, "datos_pensionado_pg", $parametros);
        $datos_registro = $this->ejecutarSQL($this->configuracion, $this->acceso_pg, $cadena_sql, "busqueda");
        return $datos_registro;
    }

    function procesarFormulario($datos) {

        $fecha_registro = date('d/m/Y');
        $estado_registro = 1;

        foreach ($datos as $key => $value) {

            if ($datos[$key] == "") {
                echo "<script type=\"text/javascript\">" .
                "alert('Formulario NO diligenciado correctamente');" .
                "</script> ";
                $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                $variable = 'pagina=formularioSustituto';
                $variable.='&opcion=';
                $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                exit;
            }
        }

        if (!preg_match("/([0-9]{2})\/([0-9]{2})\/([0-9]{4})/", $datos['fecha_nacsustituto'])) {
            echo "<script type=\"text/javascript\">" .
            "alert('Formato fecha nacimiento diligenciado incorrectamente');" .
            "</script> ";
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = 'pagina=formularioSustituto';
            $variable.='&opcion=';
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace(' " . $pagina . $variable . "')</script>";
            exit;
        }

        if (!preg_match("/([0-9]{2})\/([0-9]{2})\/([0-9]{4})/", $datos['fecha_muerte'])) {
            echo "<script type=\"text/javascript\">" .
            "alert('Formato fecha defunción diligenciado incorrectamente');" .
            "</script> ";
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = 'pagina=formularioSustituto';
            $variable.='&opcion=';
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace(' " . $pagina . $variable . "')</script>";
            exit;
        }

        if (!preg_match("/([0-9]{2})\/([0-9]{2})\/([0-9]{4})/", $datos['fecha_res_sustitucion'])) {
            echo "<script type=\"text/javascript\">" .
            "alert('Formato fecha sustitución diligenciado incorrectamente');" .
            "</script> ";
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = 'pagina=formularioSustituto';
            $variable.='&opcion=';
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace(' " . $pagina . $variable . "')</script>";
            exit;
        }

        $defuncion = strtotime(str_replace('/', '-', $datos['fecha_muerte']));
        $defuncion_c = strtotime(str_replace('/', '-', $datos['fecha_certificadod']));
        $defuncion_sus = strtotime(str_replace('/', '-', $datos['fecha_res_sustitucion']));

        if ($defuncion_c < $defuncion) {
            echo "<script type=\"text/javascript\">" .
            "alert('Fecha de certificado defunción no válida.');" .
            "</script> ";
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = 'pagina=formularioSustituto';
            $variable.='&opcion=';
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace(' " . $pagina . $variable . "')</script>";
            exit;
        }

        if ($defuncion_sus < $defuncion || $defuncion_sus < $defuncion_c) {
            echo "<script type=\"text/javascript\">" .
            "alert('Fecha de certificado de sustitución no válida');" .
            "</script> ";
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = 'pagina=formularioSustituto';
            $variable.='&opcion=';
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace(' " . $pagina . $variable . "')</script>";
            exit;
        }


        $parametros = array(
            'cedula_pen' => (isset($datos['cedula_pen']) ? $datos['cedula_pen'] : ''),
            'cedula_sustituto' => (isset($datos['cedula_sustituto']) ? $datos['cedula_sustituto'] : ''),
            'nombre_sustituto' => (isset($datos['nombre_sustituto']) ? $datos['nombre_sustituto'] : ''),
            'fecha_nacsustituto' => (isset($datos['fecha_nacsustituto']) ? $datos['fecha_nacsustituto'] : ''),
            'fecha_muerte' => (isset($datos['fecha_muerte']) ? $datos['fecha_muerte'] : ''),
            'fecha_certificadod' => (isset($datos['fecha_certificadod']) ? $datos['fecha_certificadod'] : ''),
            'certificado_defuncion' => (isset($datos['certificado_defuncion']) ? $datos['certificado_defuncion'] : ''),
            'fecha_res_sustitucion' => (isset($datos['fecha_res_sustitucion']) ? $datos['fecha_res_sustitucion'] : ''),
            'res_sustitucion' => (isset($datos['resolucion_sustitucion']) ? $datos['resolucion_sustitucion'] : ''),
            'estado' => $estado_registro,
            'registro' => $fecha_registro,
            'parentezco' => (isset($datos['parentezco']) ? $datos['parentezco'] : ''),
            'tutor' => (isset($datos['tutor']) ? $datos['tutor'] : ''),
            'cedula_tercero' => (isset($datos['cedula_tercero']) ? $datos['cedula_tercero'] : ''),
            'nombre_tercero' => (isset($datos['nombre_tercero']) ? $datos['nombre_tercero'] : ''),
            'tercero_sentencia' => (isset($datos['tercero_sentencia']) ? $datos['tercero_sentencia'] : ''),
            'fecha_tersentencia' => (isset($datos['fecha_tersentencia']) ? $datos['fecha_tersentencia'] : ''),
            'observacion' => (isset($datos['observacion']) ? $datos['observacion'] : ''),
            'genero' => (isset($datos['genero']) ? $datos['genero'] : ''),
        );

        $registro_sustituto = $this->registrarSustituto($parametros);


        if ($registro_sustituto == true) {
            $registroD[0] = "GUARDAR";
            $registroD[1] = $parametros['cedula_pen'] . '|' . $parametros['cedula_sustituto']; //
            $registroD[2] = "CUOTAS_PARTES";
            $registroD[3] = $parametros['fecha_muerte'] . '|' . $parametros['res_sustitucion'] . '|' . $parametros['fecha_res_sustitucion']; //
            $registroD[4] = time();
            $registroD[5] = "Registra datos sustituto del pensionado con cedula ";
            $registroD[5] .= " identificacion =" . $parametros['cedula_pen'];
            $this->log_us->log_usuario($registroD, $this->configuracion);

            echo "<script type=\"text/javascript\">" .
            "alert('Datos Registrados');" .
            "</script> ";

            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = "pagina=reportesCuotas";
            $variable .= "&opcion=";
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
        } else {
            echo "<script type=\"text/javascript\">" .
            "alert('Datos de Interrupción No Registrados Correctamente. Puede deberse a que el registro ya existe');" .
            "</script> ";

            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = "pagina=formularioSustituto";
            $variable .= "&opcion=";
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
        }
    }

    function reporteSustituto() {

        $parametros = array();
        $datos_sustitutos = $this->reporteSustitutos($parametros);

        if (is_array($datos_sustitutos)) {
            $this->html_formSustituto->reporteSustituto($datos_sustitutos);
        } else {
            echo "<script type=\"text/javascript\">" .
            "alert('No existen datos para el reporte.');" .
            "</script> ";
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = 'pagina=formularioSustituto';
            $variable.='&opcion=';
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
            exit;
        }
    }

    function generarPDF_sustituto($datos_sustitutos) {

        ob_start();
        $direccion = $this->configuracion['host'] . $this->configuracion['site'] . $this->configuracion['bloques'];

        // $parametros='';
        //$datos_sustitutos=  $this->reporteSustitutos($parametros);//

        $dias = array('Domingo, ', 'Lunes, ', 'Martes, ', 'Miercoles, ', 'Jueves, ', 'Viernes, ', 'Sábado, ');
        $meses = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
        $fecha_cc = $dias[date('w')] . ' ' . date('d') . ' de ' . $meses[date('n') - 1] . ' del ' . date('Y');

        $contenido = '';
        if (is_array($datos_sustitutos)) {
            foreach ($datos_sustitutos as $key => $value) {
                $contenido.="<tr>";
                $contenido.= "<td>" . $datos_sustitutos[$key]['sus_cedulapen'] . "</td>";
                $contenido.= "<td>" . $datos_sustitutos[$key]['sus_fdefuncion'] . "</td>";
                $contenido.= "<td>" . $datos_sustitutos[$key]['sus_certificado_defuncion'] . "</td>";
                $contenido.= "<td>" . $datos_sustitutos[$key]['sus_fcertificado_defuncion'] . "</td>";
                $contenido.= "<td>" . $datos_sustitutos[$key]['sus_cedulasus'] . "</td>";
                $contenido.= "<td  >" . wordwrap($datos_sustitutos[$key]['sus_nombresus'],15,"<br>") . "</td>";
                $contenido.= "<td>" . $datos_sustitutos[$key]['sus_fnac_sustituto'] . "</td>";
                $contenido.= "<td>" . $datos_sustitutos[$key]['sus_resol_sustitucion'] . "</td>";
                $contenido.= "<td>" . $datos_sustitutos[$key]['sus_fresol_sustitucion'] . "</td>";
                $contenido.= "<td>" . $datos_sustitutos[$key]['sus_parentezcosus'] . "</td>";
               $contenido.= "<td  >" . $datos_sustitutos[$key]['sus_generosus'] . "</td>";
                $contenido.= "<td>" . $datos_sustitutos[$key]['sus_tutor'] . "</td>";
                $contenido.= "<td>" . $datos_sustitutos[$key]['sus_cedula_tercero'] . "</td>";
                $contenido.= "<td  >" . wordwrap($datos_sustitutos[$key]['sus_nombre_tercero'],15,"<br>") . "</td>";
                $contenido.= "<td>" . wordwrap($datos_sustitutos[$key]['sus_tercero_sentencia'],15,"<br>") . "</td>";
                $contenido.= "<td>" . $datos_sustitutos[$key]['sus_fecha_tersentencia'] . "</td>";
                $contenido.= "<td>" . wordwrap($datos_sustitutos[$key]['sus_observacion'],17,"<br>") . "</td>";
                $contenido.= "</tr>";
            }
        } else {
            $contenido.= "<tr>";
            $contenido.="<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
            $contenido.="<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
            $contenido.="<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
            $contenido.="<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
            $contenido.="<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
            $contenido.="<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
            $contenido.="<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
            $contenido.="<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
            $contenido.="<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
            $contenido.="<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
            $contenido.="<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
            $contenido.="<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
            $contenido.="<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
            $contenido.="<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
            $contenido.="<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
            $contenido.="<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
            $contenido.="</tr>";
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
            height: 12px;
        } /* Make cells a bit taller */

        th {
            background: #F3F3F3; /* Light grey background */
            font-weight: bold; /* Make sure they're bold */
            text-align: center;
            font-size:8px
        }

        td {
            background: #FAFAFA; /* Lighter grey background */
            text-align: left;
            font-size:8px
        }
        
        div.niveau
    
{ padding-left: 5mm; }
    </style>

<page backtop='30mm' backbottom='20mm' backleft='9mm' backright='5mm' pagegroup='new'>
<page_header>
    <table align='right'>
                <tr>
                <th style=\"width:10px;\" colspan=\"1\">
                    <img alt=\"Imagen\" src=" . $direccion . "/nomina/cuotas_partes/Images/escudo1.png\" />
                </th>
                <th style=\"width:775px;font-size:13px;\" colspan=\"1\">
                    <br>UNIVERSIDAD DISTRITAL FRANCISCO JOSÉ DE CALDAS
                    <br> NIT 899999230-7<br>
                    <br> DIVISIÓN DE RECURSOS HUMANOS<br><br>
                </th>
                <th style=\"width:135px;font-size:10px;\" colspan=\"1\">
                    <br>REPORTE SUSTITUTOS REGISTRADOS<BR>
                    <br>" . $fecha_cc . "<br><br>
                </th>
            </tr>
    </table>  
    <br>
</page_header>

<page_footer>
        <table align='right'>
        <tr>
        <td align = 'center' style=\"width: 995px; text-align:center\">
        Universidad Distrital Francisco José de Caldas
        <br>
        Todos los derechos reservados.
        <br>
        Carrera 8 N. 40-78 Piso 1 / PBX 3238400 - 3239300, Ext. 1618 - 1603
        <br>
        </td>
        </tr>
        </table>
        <p style=\"font-size:7px\">Diseño forma: JUAN CALDERON -División de Recursos Humanos</p>
        <p style='text-align: right; font-size:10px;'>[[page_cu]]/[[page_nb]]</p>
</page_footer>

  <table  align='center'>
   <thead>
        <tr>
            <th colspan=\"17\" style=\"width:995px; font-size:12px;\">DETALLE DE SUSTITUTOS</th>
        </tr>
        <tr>
                            <th>CÉDULA<br>PENSIONADO</th>
                                                        <th>FECHA<br>DEFUNCION</th>
                                                        <th>CERTIFICADO<br>DEFUNCIÓN</th>
                                                        <th>FECHA C. D.</th>
                                                        <th>CÉDULA<br>SUSTITUTO</th>
                                                        <th>NOMBRE<br>SUSTITUTO</th>
                                                        <th>FECHA NAC.<br>SUSTITUTO</th>
                                                        <th>RES.<br>SUSTITUCIÓN</th>
                                                        <th>FECHA RES.<br>SUSTITUCIÓN</th>
                                                        <th>PARENTEZCO</th>
                                                        <th>GÉNERO</th>
                                                        <th>TUTOR</th>
                                                        <th>CÉDULA<br>TERCERO </th>
                                                        <th>NOMBRE<br>TERCERO</th>
                                                        <th>SENTENCIA</th>
                                                        <th>FECHA<br>SENTENCIA</th>
                                                        <th>OBSERVACIONES </th>
        </tr>
         </thead>
         <tbody>
            " . $contenido . "
                </tbody>
                
    </table>
</page>
        ";

        //$ContenidoPdf = ob_get_clean();
        $PDF = new HTML2PDF('L', 'Letter', 'es', true, 'UTF-8', 3);
        $PDF->pdf->SetDisplayMode('fullpage');
        $PDF->writeHTML($ContenidoPdf);
        clearstatcache();
        $PDF->Output("ReporteSustitutos.pdf", "D");
    }

}
