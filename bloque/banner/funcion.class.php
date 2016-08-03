
<?php
/* --------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
  --------------------------------------------------------------------------------------------------------------------------- */

if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/funcionGeneral.class.php");

//@ Esta clase presenta el horario registrado para el estudiante y los enlaces para realizar inscripcion por busqeda
//@ Tambien se puede realizar cambio de grupo y cancelacion si hay permisos para inscripciones

class funcion_bannerFlotante extends funcionGeneral {

    private $configuracion;

    //@ Método costructor que crea el objeto sql de la clase sql_noticia
    function __construct($configuracion) {
        //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
        //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
        //include ($configuracion["raiz_documento"] . $configuracion["estilo"] . "/basico/tema.php");
        $this->configuracion = $configuracion;
    }

    /**
     * Esta funcion presenta el horario del estudiante
     * Utiliza los metodos datosEstudiante, validar_fechas_estudiante_coordinador, validarEstadoEstudiante, registroAgil,
     *  horarioEstudianteConsulta, calcularCreditos, adicionar, finTabla
     * @param <array> $this->configuracion
     * @param <array> $_REQUEST (pagina, opcion, codProyecto, planEstudio, codProyectoEstudiante, planEstudioEstudiante, nombreProyecto, codEstudiante, xajax, xajax_file)
     */
    function mostrarbannerFlotante() {
        ?>

        <link href="<? echo $this->configuracion["host"] . $this->configuracion["site"] . $this->configuracion["bloques"]; ?>/banner/css/estiloBanner.css"
            rel="stylesheet" type="text/css" />
        <script  type="text/javascript" src="<? echo $this->configuracion["host"] . $this->configuracion["site"] . $this->configuracion["bloques"]; ?>/banner/js/codigo.js"></script>
        <script>

            function ajaxFunction() {
                var xmlHttp;
                try {
                    // Firefox, Opera 8.0+, Safari
                    xmlHttp = new XMLHttpRequest();
                    return xmlHttp;
                } catch (e) {
                    // Internet Explorer
                    try {
                        xmlHttp = new ActiveXObject("Msxml2.XMLHTTP");
                        return xmlHttp;
                    } catch (e) {
                        try {
                            xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
                            return xmlHttp;
                        } catch (e) {
                            alert("Tu navegador no soporta AJAX!");
                            return false;
                        }
                    }
                }
            }
        </script>
        <div id="contenedorb">
            <div id="contenedor_A">
            </div>
            <div id="contenedor_B">
            </div>
        </div>
        <?
    }

}
?>
