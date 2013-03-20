<?

/* --------------------------------------------------------------------------------------------------------------------------
 @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
--------------------------------------------------------------------------------------------------------------------------- */

if (!isset($GLOBALS["autorizado"])) {
	include("../index.php");
	exit;
}
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/bloque.class.php");
include_once("funcion.class.php");
include_once("sql.class.php");

//Clase
class bloque_bannerFlotante extends bloque {

	private $configuracion;

	public function __construct($configuracion) {
		$this->configuracion = $configuracion;
		//include ($configuracion["raiz_documento"] . $configuracion["estilo"] . "/basico/tema.php");
		//$this->tema = $tema;
		$this->funcion = new funcion_bannerFlotante($configuracion);
		$this->sql = new sql_bannerFlotante();
	}

	function html() {
		//$this->acceso_db=$this->funcion->conectarDB($configuracion);
		// @ Crear un objeto de la clase funcion


		if (!isset($_REQUEST['opcion'])) {
			$_REQUEST['opcion'] = "nuevo";
		}

		switch ($_REQUEST['opcion']) {

			case "mostrarConsulta":
				$this->funcion->mostrarbannerFlotante();
				break;

			default:
				$this->funcion->mostrarbannerFlotante();
				break;
		}
	}

	function action() {

	}

}

// @ Crear un objeto bloque especifico

$esteBloque = new bloque_bannerFlotante($configuracion);

if (!isset($_REQUEST['action'])) {
	$esteBloque->html();
} else {
	$esteBloque->action();
}
?>