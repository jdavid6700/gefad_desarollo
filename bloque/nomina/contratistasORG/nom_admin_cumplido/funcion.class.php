<?php
/*--------------------------------------------------------------------------------------------------------------------------
 @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/
/*---------------------------------------------------------------------------------------
 |				Control Versiones				    	|
----------------------------------------------------------------------------------------
| fecha      |        Autor            | version     |              Detalle            |
----------------------------------------------------------------------------------------
| 14/02/2013 | Maritza Callejas C.  	| 0.0.0.1     |                                 |
----------------------------------------------------------------------------------------
*/


if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/navegacion.class.php");
include_once("html.class.php");
class funciones_adminCumplido extends funcionGeneral
{

	function __construct($configuracion, $sql)
	{
		//[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
		//include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
		include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/log.class.php");

		$this->cripto = new encriptar();
		$this->log_us = new log();
		$this->tema = $tema;
		$this->sql = $sql;

		//Conexion General
		$this->acceso_db = $this->conectarDB($configuracion,"mysqlFrame");

		//Conexion SICAPITAL
		$this->acceso_sic = $this->conectarDB($configuracion,"oracleSIC");

		//Conexion NOMINA
		$this->acceso_nomina = $this->conectarDB($configuracion,"nominapg");
		 
		//Datos de sesion

		$this->usuario = $this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
		$this->identificacion = $this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");

		$this->configuracion = $configuracion;

		$this->htmlCumplido = new html_adminCumplido($configuracion);

	}


	function nuevoRegistro($configuracion,$tema,$acceso_db)
	{
		$registro = (isset($registro)?$registro:'');
		$this->form_usuario($configuracion,$registro,$this->tema,"");

	}

	function editarRegistro($configuracion,$tema,$id,$acceso_db,$formulario)
	{
		$this->cadena_sql = $this->sql->cadena_sql($configuracion,$this->acceso_db,"usuario",$id);

		$registro = $this->acceso_db->ejecutarAcceso($this->cadena_sql,"busqueda");
		if ($_REQUEST['opcion'] == 'cambiar_clave')
		{
			$this->formContrasena($configuracion,$registro,$this->tema,'');
		}
		else
		{
			$this->form_usuario($configuracion,$registro,$this->tema,'');
		}
	}

	function corregirRegistro()
	{
	}

	function listaRegistro($configuracion,$id_registro)

	{
	}


	function mostrarRegistro($configuracion,$registro, $totalRegistros, $opcion, $variable)
	{
		switch($opcion)
		{
			case "multiplesCumplidos":
				$this->htmlCumplido->multiplesCumplidos($configuracion,$registro, $totalRegistros, $variable);
				break;

		}

	}


	/*__________________________________________________________________________________________________

	Metodos especificos
	__________________________________________________________________________________________________*/

	/**
	 * Funcion que llama el formulario para solicitar un cumplido
	 */
	function solicitarCumplido(){
		$contratos = $this->consultarContratos($this->identificacion);
		$meses = $this->mesesContrato($contratos);
		//var_dump($contratos);exit;
		foreach ($contratos as $key => $contrato) {
			$contrato['NUM_CONTRATO']=(isset($contrato['NUM_CONTRATO'])?$contrato['NUM_CONTRATO']:'');
			$datos_contrato[$key][0]=$contrato['VIGENCIA']."-".$contrato['INTERNO_OC']."-".$contrato['NUM_CONTRATO'];
			$datos_contrato[$key][1]=$contrato['VIGENCIA']." - No.".$contrato['NUM_CONTRATO'];
		}
		$this->htmlCumplido->form_solicitud($datos_contrato,$meses,"","");
		$this->mostrarListadoSolicitudes($contratos);

	}

	/**
	 * Funcion que verifica los datos de una solicitud para insertarla o no
	 */
	function verificarSolicitud(){
		$variables =explode("-", $_REQUEST['codigo_contrato']);
		$vigencia= $variables[0];
		$interno_oc= $variables[1];
		$cod_contrato = $variables[2];
		$mes_cumplido=(isset($_REQUEST['mes_cumplido'])?$_REQUEST['mes_cumplido']:'');
		$anio = substr($mes_cumplido, 0,4);
		$mes = substr($mes_cumplido, 4,2);
		$datos_solicitud =$this->consultarExisteSolicitud($vigencia, $cod_contrato,$anio,$mes);
		if(!is_array($datos_solicitud) ){

			$contrato = $this->consultarDatosContrato($interno_oc,$vigencia);
			$cuenta = $this->consultarDatosCta($contrato[0]['INTERNO_PROVEEDOR']);

			$contratista = $this->consultarDatosContratista($contrato[0]['NUM_IDENTIFICACION']);
			$disponibilidad = $this->consultarDatosDisponibilidad($contrato[0]['INTERNO_MC'],$contrato[0]['CODIGO_UNIDAD_EJECUTORA'],$vigencia);
			$nro_cdp = $disponibilidad[0]['NUMERO_DISPONIBILIDAD'];
			$registroPresupuestal = $this->consultarDatosRegistroPresupuestal($nro_cdp,$contrato[0]['CODIGO_UNIDAD_EJECUTORA'],$vigencia);
			$ordenPago = $this->consultarDatosOrdenPago($contrato[0]['NUM_IDENTIFICACION'],$nro_cdp,$vigencia);
			$cod_contratista = $contrato[0]['NUM_IDENTIFICACION'];
			$tipo_id_contratista = $contrato[0]['TIPO_IDENTIFICACION'];
			$tipo_contrato = $this->consultarTipoContrato($vigencia,$contrato[0]['CODIGO_UNIDAD_EJECUTORA'],$registroPresupuestal[0]['NUMERO_REGISTRO']);

			$valida_contratista = $this->validaContratista($contratista);
			if($valida_contratista!='ok'){
				echo "<br>Falta información de contratista ";
			}

			$valida_contrato = $this->validaContrato($contrato,$tipo_contrato);
			if($valida_contrato!='ok'){
				echo "<br>Falta información de contrato ";
			}

			$valida_certificados= $this->validaCertificados($disponibilidad,$registroPresupuestal);
			if($valida_certificados!='ok'){
				echo "<br>Falta información de registros presupuestales ";
			}

			if($valida_contratista=='ok' && $valida_contrato=='ok' && $valida_certificados=='ok' ){
				$this->mostrarMensajeVerificacion();
			}
			$this->mostrarInformacionContratista($interno_oc,$cod_contrato,$vigencia);

			if($valida_contratista=='ok' && $valida_contrato=='ok' && $valida_certificados=='ok' ){
				$this->revisarCuentasBancarias($cod_contratista,$tipo_id_contratista,$cuenta);

				if(count($cuenta)>1){
					$info_cuentas = $this->consultarCuentas($cod_contratista,$tipo_id_contratista);
					if(is_array($info_cuentas)){
						foreach ($info_cuentas as $key => $value) {
							$cuentas[$key][0]=$info_cuentas[$key]['id'];
							$cuentas[$key][1]=$info_cuentas[$key]['nombre'];
						}
					}else{
						$cuentas = array(0=>'0');

					}
					$this->htmlCumplido->form_envio_solicitud($contrato,$mes_cumplido,$cuentas,"","");

				}else{
					$cod_banco = $this->consultarCodigoBanco($cuenta[0]['CODIGO']);
					$num_cta=$cuenta[0]['NRO_CTA'];
					$tipo=$cuenta[0]['TIPO_CTA'];

					$cod_relacion = $this->consultarCodigoRelacionCuentas($cod_contratista,$tipo_id_contratista,$cod_banco,$num_cta,$tipo);
					$this->htmlCumplido->form_envio_solicitud($contrato,$mes_cumplido,$cod_relacion,"","");
				}
			}
		}else{

			$mensaje = "Ya existe una solicitud de cumplido de ese mes";
			$pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
			$variable="pagina=nom_adminCumplido";
			$variable.="&opcion=solicitar";
			$variable.="&cod_contrato=".$cod_contrato;
			$variable.="&mes_cumplido=".$mes_cumplido;

			$variable=$this->cripto->codificar_url($variable,$this->configuracion);
			$this->retornar($pagina,$variable,$mensaje);

		}

	}


	/**
	 * Funcion que consulta la informacion del contratista y contrato para mostrarla
	 * @param int $interno_co
	 * @param int $cod_contrato
	 * @param int $vigencia
	 */
	function mostrarInformacionContratista($interno_co,$cod_contrato,$vigencia){
		$contrato = $this->consultarDatosContrato($interno_co,$vigencia);
		$cuenta = $this->consultarDatosCta($contrato[0]['INTERNO_PROVEEDOR']);
		$contratista = $this->consultarDatosContratista($contrato[0]['NUM_IDENTIFICACION']);
		$disponibilidad = $this->consultarDatosDisponibilidad($contrato[0]['INTERNO_MC'],$contrato[0]['CODIGO_UNIDAD_EJECUTORA'],$vigencia);
		$nro_cdp = $disponibilidad[0]['NUMERO_DISPONIBILIDAD'];
		$registroPresupuestal = $this->consultarDatosRegistroPresupuestal($nro_cdp,$contrato[0]['CODIGO_UNIDAD_EJECUTORA'],$vigencia);
		$ordenPago = $this->consultarDatosOrdenPago($contrato[0]['NUM_IDENTIFICACION'],$nro_cdp,$vigencia);
		$tipo_contrato = $this->consultarTipoContrato($vigencia,$contrato[0]['CODIGO_UNIDAD_EJECUTORA'],$registroPresupuestal[0]['NUMERO_REGISTRO']);
		$fecha_contrato= (isset($registroPresupuestal[0]['FECHA_REGISTRO'])?$registroPresupuestal[0]['FECHA_REGISTRO']:'');
		if($cod_contrato){
			$novedades = $this->consultarDatosNovedades( $cod_contrato,$vigencia);
		}else{
			$novedades ="";
		}
		//Obtener el total de registros
		$totalRoles = $this->totalRegistros($this->configuracion, $this->acceso_db);
		?>
<table width="90%" align="center" border="0" cellpadding="10"
	cellspacing="0">
	<tbody>
		<tr>
			<td>
				<table width="100%" border="0" align="center" cellpadding="5 px"
					cellspacing="1px">


					<tr>
						<td><?
						$this->htmlCumplido->mostrarDatosContratista($contratista);
						?>
						</td>
					</tr>
					<tr>
						<td><?
						$this->htmlCumplido->mostrarDatosContrato($contrato,$tipo_contrato,$fecha_contrato);
						?>
						</td>
					</tr>
					<tr>
						<td><?
						$this->htmlCumplido->mostrarDatosCuentaBanco($cuenta);
						?>
						</td>
					</tr>
					<tr>
						<td><?
						$this->htmlCumplido->mostrarDatosDisponibilidad($disponibilidad);
						?>
						</td>
					</tr>
					<tr>
						<td><?
						$this->htmlCumplido->mostrarDatosRegistroPresupuestal($registroPresupuestal);
						?>
						</td>
					</tr>
					<tr>
						<td><?
						$this->htmlCumplido->mostrarDatosOrdenPago($ordenPago);
						?>
						</td>
					</tr>
					<tr>
						<td><?
						$this->htmlCumplido->mostrarNovedades($novedades);
						?>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td>
	
	</tbody>
</table>
<?				

	}


	/**
	 * Funcion que consulta en la base de datos informacion del contrato
	 * @param int $cod_contrato
	 * @param int $vigencia
	 */
	function consultarDatosContrato($cod_contrato,$vigencia){
		//busca si existen registro de datos de usuarios en la base de datos
		$datos = array('vigencia'=>$vigencia,
				'cod_contrato'=>$cod_contrato);
		$cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_sic,"datos_contrato",$datos);
		//echo "<br>cadena ".$cadena_sql;
		return $datos_contrato = $this->ejecutarSQL($this->configuracion, $this->acceso_sic, $cadena_sql, "busqueda");
			
	}

	/**
	 * Funcion que consulta en la base de datos informacion de las cuentas bancarias
	 * @param int $cod_interno_proveedor
	 */
	function consultarDatosCta($cod_interno_proveedor){

		$cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_sic,"datos_cuenta",$cod_interno_proveedor);
		//echo $cadena_sql;
		return $datos_cuenta = $this->ejecutarSQL($this->configuracion, $this->acceso_sic, $cadena_sql, "busqueda");
	}

	/**
	 * Funcion que consulta en la base de datos informacion de la disponibilidad de un contrato
	 * @param int $cod_interno_minuta_contrato
	 * @param int $cod_unidad_ejecutora
	 * @param int $vigencia
	 */
	function consultarDatosDisponibilidad($cod_interno_minuta_contrato, $cod_unidad_ejecutora, $vigencia){
		$datos = array('vigencia'=>$vigencia,
				'cod_unidad_ejecutora'=>$cod_unidad_ejecutora,
				'cod_minuta_contrato'=>$cod_interno_minuta_contrato);

		$cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_sic,"datos_disponibilidad",$datos);
		return $datos_disponibilidad = $this->ejecutarSQL($this->configuracion, $this->acceso_sic, $cadena_sql, "busqueda");

	}

	/**
	 * Funcion que consulta en la base de datos informacion del registro presupuestal de un contrato
	 * @param int $nro_cdp
	 * @param int $cod_unidad_ejecutora
	 * @param int $vigencia
	 */
	function consultarDatosRegistroPresupuestal($nro_cdp, $cod_unidad_ejecutora, $vigencia){
		$datos = array( 'nro_cdp'=>$nro_cdp,
				'cod_unidad_ejecutora'=>$cod_unidad_ejecutora,
				'vigencia'=>$vigencia
		);

		$cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_sic,"datos_registro",$datos);
		return $datos_disponibilidad = $this->ejecutarSQL($this->configuracion, $this->acceso_sic, $cadena_sql, "busqueda");

	}

	/**
	 * Funcion que consulta en la base de datos informacion del contratista
	 * @param int $identificacion
	 */
	function consultarDatosContratista($identificacion){

		$cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_sic,"datos_contratista",$identificacion);
		return $datos_disponibilidad = $this->ejecutarSQL($this->configuracion, $this->acceso_sic, $cadena_sql, "busqueda");
		 
	}

	/**
	 * Funcion que consulta en la base de datos informacion de ordenes de pago
	 * @param int $identificacion
	 * @param int $num_disponibilidad
	 * @param int $vigencia
	 */
	function consultarDatosOrdenPago($identificacion,$num_disponibilidad,$vigencia){
		$datos = array( 'identificacion'=>$identificacion,
				'num_disponibilidad'=>$num_disponibilidad,
				'vigencia'=>$vigencia
		);

		$cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_sic,"datos_orden_pago",$datos);
		//echo "cadena ".$cadena_sql;exit;
		return $datos_disponibilidad = $this->ejecutarSQL($this->configuracion, $this->acceso_sic, $cadena_sql, "busqueda");
		 
	}

	/**
	 * Funcion que consulta en la base de datos informacion de las novedades registradas
	 * @param int $interno_contrato
	 */
	function consultarDatosNovedades($cod_contrato,$vigencia){
		$datos= array(  'vigencia'=>$vigencia,
				'cod_contrato'=>$cod_contrato);
		$cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_nomina,"datos_novedades",$datos);
		//echo "cadena ".$cadena_sql;exit;
		return $datos_novedad = $this->ejecutarSQL($this->configuracion, $this->acceso_nomina, $cadena_sql, "busqueda");

	}


	/**
	 * Funcion que toma los datos para registrar una solicitud
	 */
	function registrarSolicitud(){
		$insertado=0;
		$id=$this->obtenerNumeroSolicitud();
		$cod_contrato=(isset($_REQUEST['cod_contrato'])?$_REQUEST['cod_contrato']:'');
		$mes_cumplido=(isset($_REQUEST['mes_cumplido'])?$_REQUEST['mes_cumplido']:'');
		$vigencia=(isset($_REQUEST['vigencia_contrato'])?$_REQUEST['vigencia_contrato']:'');

		$annio = substr($mes_cumplido, 0, 4);
		$mes = substr($mes_cumplido, 4, 2);
		$num_dias = 0;
		$procesado='N';
		$estado = 'SOLICITADO';
		$fecha=date('Y-m-d');
		$estado_reg="A";
		$num_impresion=0;
		$valor=0;
		$cta_id=(isset($_REQUEST['cta_id'])?$_REQUEST['cta_id']:'');

		$datos_solicitud =$this->consultarExisteSolicitud($vigencia, $cod_contrato,$annio,$mes);
		if(!is_array($datos_solicitud) && $vigencia && $cod_contrato && $annio && $mes && $cta_id){
			$insertado = $this->insertarSolicitud($id,$vigencia,$cod_contrato,$annio,$mes,$num_dias,$procesado, $estado ,$fecha,$estado_reg, $num_impresion, $valor,$cta_id);
		}

		if ($insertado>0){
			$mensaje = "Solicitud registrada con exito";
			$pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
			$variable="pagina=nom_adminCumplido";
			$variable.="&opcion=solicitar";
			$variable.="&cod_contrato=".$cod_contrato;
			$variable.="&mes_cumplido=".$mes_cumplido;

			$variable=$this->cripto->codificar_url($variable,$this->configuracion);
			$this->retornar($pagina,$variable,$mensaje);

		}else{
			$mensaje = "Error al registrar Solicitud";
			$pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
			$variable="pagina=nom_adminCumplido";
			$variable.="&opcion=solicitar";
			$variable.="&cod_contrato=".$cod_contrato;
			$variable.="&mes_cumplido=".$mes_cumplido;

			$variable=$this->cripto->codificar_url($variable,$this->configuracion);
			$this->retornar($pagina,$variable,$mensaje);

		}

	}


	/**
	 * Funcion que inserta una solcicitud de cumplido
	 * @param int $id
	 * @param int $vigencia
	 * @param int $cod_contrato
	 * @param int $annio
	 * @param int $mes
	 * @param int $num_dias
	 * @param String $procesado
	 * @param String $estado
	 * @param date $fecha
	 * @param String $estado_reg
	 * @param int $num_impresion
	 * @param double $valor
	 * @param int $cta_id
	 * @return int
	 */
	function insertarSolicitud($id,$vigencia,$cod_contrato,$annio,$mes,$num_dias,$procesado, $estado ,$fecha,$estado_reg, $num_impresion, $valor,$cta_id){
		$datos_novedad = array('id'=>$id,
				'vigencia'=>$vigencia,
				'cod_contrato'=>$cod_contrato,
				'annio'=>$annio,
				'mes'=>$mes,
				'num_dias'=>$num_dias,
				'procesado'=>$procesado,
				'estado'=>$estado,
				'fecha'=>$fecha,
				'estado_reg'=>$estado_reg,
				'num_impresion'=>$num_impresion,
				'valor'=>$valor,
				'cta_id'=>$cta_id);
		$cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_nomina,"insertar_solicitud",$datos_novedad);
		//echo "cadena ".$cadena_sql;exit;
		$this->ejecutarSQL($this->configuracion, $this->acceso_nomina, $cadena_sql, "");
		return $this->totalAfectados($this->configuracion, $this->acceso_nomina);
	}

	/**
	 * Funcion para obtener un numero consecutivo para la solicitud
	 * @return int
	 */
	function obtenerNumeroSolicitud(){
		$numero = $this->consultarUltimoNumeroSolicitud();
		$numero++;
		return $numero;
	}

	/**
	 * Funcion que consulta el último número de solicitud de la novedad
	 * @return int
	 */
	function consultarUltimoNumeroSolicitud(){

		$cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_nomina,"ultimo_numero_solicitud","");
		//echo "cadena ".$cadena_sql;exit;
		$datos_novedad = $this->ejecutarSQL($this->configuracion, $this->acceso_nomina, $cadena_sql, "busqueda");
		return $datos_novedad[0][0];
	}

	/**
	 * Funcion que valida que existan los datos de un contratista requeridos para la elaboración del cumplido
	 * @param <array> $contratista
	 * @return String/array
	 */
	function validaContratista($contratista){
		$indice = 1;
		if(!$contratista[0]['TIPO_DOCUMENTO']){
			$valido[$indice]="Tipo documento";
			$indice++;
		}
		if(!$contratista[0]['NUMERO_DOCUMENTO']){
			$valido[$indice]="Número documento";
			$indice++;
		}
		if(!$contratista[0]['PRIMER_NOMBRE']){
			$valido[$indice]="Primer Nombre";
			$indice++;
		}
		if(!$contratista[0]['PRIMER_APELLIDO']){
			$valido[$indice]="Primer Apellido";
			$indice++;
		}
		$valido=(isset($valido)?$valido:'ok');
		return $valido;
	}


	/**
	 * Funcion que valida que existan los datos de un contrato requeridos para la elaboración del cumplido
	 * @param type $contrato
	 * @param type $tipo_contrato
	 * @return type
	 */
	function validaContrato($contrato,$tipo_contrato){
		$indice = 1;
		$contrato[0]['NUM_CONTRATO']=(isset( $contrato[0]['NUM_CONTRATO'])? $contrato[0]['NUM_CONTRATO']:'');
		$contrato[0]['FECHA_INICIO']=(isset( $contrato[0]['FECHA_INICIO'])? $contrato[0]['FECHA_INICIO']:'');
		$contrato[0]['FECHA_FINAL']=(isset( $contrato[0]['FECHA_FINAL'])? $contrato[0]['FECHA_FINAL']:'');
		if(!$contrato[0]['NUM_CONTRATO']){
			$valido[$indice]="Tipo documento";
			$indice++;
		}
		if(!$contrato[0]['FECHA_INICIO']){
			$valido[$indice]="Fecha de inicio";
			$indice++;
		}

		if(!$contrato[0]['FECHA_FINAL']){
			$valido[$indice]="Fecha final";
			$indice++;
		}
		if(!$tipo_contrato){
			$valido[$indice]="Tipo de contrato";
			$indice++;
		}

		$valido=(isset($valido)?$valido:'ok');
		return $valido;
	}

	/**
	 * Funcion que valida que existan los datos de cretificados de disponibilidad y registro presupuestal requeridos para la elaboración del cumplido
	 * @param <array> $disponibilidad
	 * @param <array> $registroPresupuestal
	 * @return type
	 */
	function validaCertificados($disponibilidad,$registroPresupuestal){
		$indice = 1;
		if(!$disponibilidad[0]['NUMERO_DISPONIBILIDAD']){
			$valido[$indice]="Número de disponibilidad";
			$indice++;
		}
		if(!$disponibilidad[0]['FECHA_DISPONIBILIDAD']){
			$valido[$indice]="Fecha de disponibilidad";
			$indice++;
		}
		if(!$disponibilidad[0]['VALOR']){
			$valido[$indice]="Valor disponibilidad";
			$indice++;
		}
		if(!$registroPresupuestal[0]['NUMERO_REGISTRO']){
			$valido[$indice]="Número de registro";
			$indice++;
		}
		if(!$registroPresupuestal[0]['FECHA_REGISTRO']){
			$valido[$indice]="Fecha de registro";
			$indice++;
		}
		if(!$registroPresupuestal[0]['VALOR']){
			$valido[$indice]="Valor REGISTRO";
			$indice++;
		}

		$valido=(isset($valido)?$valido:'ok');
		return $valido;
	}

	/**
	 *
	 * @param int $vigencia
	 * @param int $cod_contrato
	 * @param int $anio
	 * @param int $mes
	 * @return <array>
	 */
	function consultarExisteSolicitud($vigencia, $cod_contrato,$anio,$mes){
		$datos = array('vigencia'=>$vigencia,
				'cod_contrato'=>$cod_contrato,
				'anio'=>$anio,
				'mes'=>$mes);
		$cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_nomina,"existe_cumplido",$datos);
		return $datos = $this->ejecutarSQL($this->configuracion, $this->acceso_nomina, $cadena_sql, "busqueda");

	}

	/**
	 * Funcion que revisa si existe una cuenta bancaria relacionada a un contratista, de lo contrario realiza el llamado al
	 * metodo correspondiente para insertar el registro
	 * @param int $cod_contratista
	 * @param String $tipo_id_contratista
	 * @param <array> $cuentas
	 */
	function revisarCuentasBancarias($cod_contratista,$tipo_id_contratista,$cuentas){

		foreach ($cuentas as $key => $cuenta) {
			$cod_banco = $this->consultarCodigoBanco($cuenta['CODIGO']);
			$num_cta=$cuenta['NRO_CTA'];
			$tipo=$cuenta['TIPO_CTA'];
			$cod_relacion = $this->consultarCodigoRelacionCuentas($cod_contratista,$tipo_id_contratista,$cod_banco,$num_cta,$tipo);
			if(!$cod_relacion){
				$relacionado = $this->relacionarCuentaBancariaAContratista($cod_contratista,$tipo_id_contratista,$cod_banco,$num_cta,$tipo);
			}

		}
	}

	/**
	 * Funcion para consultar en la base de datos el codigo para nomina de un banco
	 * @param type $codigo_sic
	 * @return type
	 */
	function consultarCodigoBanco($codigo_sic){
		$cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_nomina,"codigo_banco",$codigo_sic);
		//echo $cadena_sql ;exit;
		$datos = $this->ejecutarSQL($this->configuracion, $this->acceso_nomina, $cadena_sql, "busqueda");
		return $datos[0][0];
	}

	/**
	 * Funcion para consultar en la base de datos el codigo de la relación de una cuenta con un contratista
	 * @param type $cod_contratista
	 * @param String $tipo_id_contratista
	 * @param int $cod_banco
	 * @param int $num_cta
	 * @param String $tipo
	 * @return int
	 */
	function consultarCodigoRelacionCuentas($cod_contratista,$tipo_id_contratista,$cod_banco,$num_cta,$tipo){
		$datos = array('cod_contratista'=>$cod_contratista,
				'tipo_id'=>$tipo_id_contratista,
				'id_banco'=>$cod_banco,
				'num_cta'=>$num_cta,
				'tipo'=>$tipo);
		$cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_nomina,"codigo_cuenta_banco",$datos);
		//echo $cadena_sql ;exit;
		$resultado = $this->ejecutarSQL($this->configuracion, $this->acceso_nomina, $cadena_sql, "busqueda");
		return $resultado[0][0];
	}

	/**
	 * Funcion que busca el numero de una cuenta de banco y llama el metodo para hacer el registro de la cuenta con el contratista
	 * @param String $cod_contratista
	 * @param String $tipo_id_contratista
	 * @param int $id_banco
	 * @param int $num_cta
	 * @param String $tipo
	 * @return int
	 */
	function relacionarCuentaBancariaAContratista($cod_contratista,$tipo_id_contratista,$id_banco,$num_cta,$tipo){
		$numero = $this->obtenerNumeroCuentaBanco();
		$insertado=$this->insertarCuentaBanco($numero,$cod_contratista,$tipo_id_contratista,$id_banco,$num_cta,$tipo);
		return $insertado;
	}

	/**
	 * Funcino que retorna el numero consecutivo a partir del ultimo numero de relacion de cuenta banco registrado
	 * @return type
	 */
	function obtenerNumeroCuentaBanco(){
		$numero = $this->consultarUltimoNumeroCuentaBanco();
		$numero++;
		return $numero;
	}

	 
	/**
	 * Funcion que consulta en la base de datos el ultimo numero de relacion de cuenta banco
	 * @return int
	 */
	function consultarUltimoNumeroCuentaBanco(){

		$cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_nomina,"ultimo_numero_cuenta_banco","");
		//echo "cadena ".$cadena_sql;exit;
		$datos = $this->ejecutarSQL($this->configuracion, $this->acceso_nomina, $cadena_sql, "busqueda");
		return $datos[0][0];
	}

	/**
	 * Funcion que inserta un registro de cuenta bancaria de un contratista en la base de datos
	 * @param int $id
	 * @param int $cod_contratista
	 * @param String $tipo_id_contratista
	 * @param type $id_banco
	 * @param type $num_cta
	 * @param type $tipo
	 * @return int
	 */

	function insertarCuentaBanco($id,$cod_contratista,$tipo_id_contratista,$id_banco,$num_cta,$tipo){
		$datos = array('id'=>$id,
				'cod_contratista'=>$cod_contratista,
				'tipo_id'=>$tipo_id_contratista,
				'id_banco'=>$id_banco,
				'num_cta'=>$num_cta,
				'tipo'=>$tipo,
				'estado'=>"A");
		$cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_nomina,"insertar_relacion_cuenta_banco",$datos);
		//echo "cadena ".$cadena_sql;exit;
		$this->ejecutarSQL($this->configuracion, $this->acceso_nomina, $cadena_sql, "");
		if ($this->totalAfectados($this->configuracion, $this->acceso_nomina)>0){
			return $id;
		}else{
			return 0;
		}

	}


	/**
	 * Funcion que consulta las cuentas bancarias relacionadas a un contratista
	 * @param int $identificacion
	 * @param String $tipo_id
	 * @return <array>
	 */
	function consultarCuentas($identificacion, $tipo_id){
		$datos = array('tipo_id'=>$tipo_id,
				'num_id'=>$identificacion);
		$cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_nomina,"lista_cuentas",$datos);
		return $resultado = $this->ejecutarSQL($this->configuracion, $this->acceso_nomina, $cadena_sql, "busqueda");

	}

	/**
	 * Funcion que muestra un mensaje
	 */
	function mostrarMensajeVerificacion(){
		echo "<p class='fondoImportante'>Por favor verifique los datos que se encuentran registrados en el sistema, tenga en cuenta que estos son los que se van a tener en cuenta para elaborar el cumplido y realizar la solicitud de pago.</p>";
	}


	/**
	 * Funcion que consulta el tipo de contrato de un contrato
	 * @param int $vigencia
	 * @param int $unidad_ejecutora
	 * @param int $numero_registro
	 * @return type
	 */
	function consultarTipoContrato($vigencia,$unidad_ejecutora,$numero_registro){
		$datos = array('vigencia'=>$vigencia,
				'unidad_ejec'=>$unidad_ejecutora,
				'num_registro'=>$numero_registro);
		$cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_sic,"tipo_contrato",$datos);
		$datos_contrato = $this->ejecutarSQL($this->configuracion, $this->acceso_sic, $cadena_sql, "busqueda");
		return $datos_contrato[0][1];
	}

	/**
	 * Funcion que consulta los registro de contratos relacionados a un contratista
	 * @param int $identificacion
	 * @return <array>
	 */
	function consultarContratos($identificacion){
		$cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_sic,"contratos",$identificacion);
		//echo "<br>cadena ".$cadena_sql;
		$datos_contrato = $this->ejecutarSQL($this->configuracion, $this->acceso_sic, $cadena_sql, "busqueda");
		return $datos_contrato;
	}

	/**
	 * Funcion que retorna un arreglo con los meses que posee un contrato con el respectivo año
	 * @param type $contratos
	 * @return type
	 */
	function mesesContrato($contratos){
		$indice=0;
		foreach ($contratos as $key => $contrato) {
			$contrato['FECHA_INICIO']=(isset($contrato['FECHA_INICIO'])?$contrato['FECHA_INICIO']:'');
			$contrato['FECHA_FINAL']=(isset($contrato['FECHA_FINAL'])?$contrato['FECHA_FINAL']:'');

			if($contrato['FECHA_INICIO'] && $contrato['FECHA_FINAL']){
				$anio_inicial=substr($contrato['FECHA_INICIO'], 0,4);
				$anio_final=substr($contrato['FECHA_FINAL'], 0,4);
				$mes_inicial=substr($contrato['FECHA_INICIO'], 5,2);
				$mes_final=substr($contrato['FECHA_FINAL'], 5,2);
				$fecha_inicial=$anio_inicial.$mes_inicial;
				$fecha_final=$anio_final.$mes_final;

				for($i=$anio_inicial;$i<=$anio_final;$i++){
					for($mes=1;$mes<=12;$mes++){

						if($mes<10)
							$mes="0".$mes;
						$fecha=$i.$mes;
						if($fecha>=$fecha_inicial && $fecha<=$fecha_final){
							$meses[$indice][0]=$i.$mes;
							$meses[$indice][1]=$i."- ".$this->nombreMes($mes);
							$indice++;
						}
					}
				}

			}
		}
		$meses=(isset($meses)?$meses:'');
		if($meses){
            $meses = $this->ordenarMeses($meses);
        }
        return $meses;
	}

	/**
	 * Funcion que ordena descendentemente un arreglo de meses
	 * @param <array> $meses
	 * @return <array>
	 */
	function ordenarMeses($meses){
            foreach ($meses as $key => $fila) {
                    $ids[$key]  = $fila[0]; // columna de identificador
                    $nombres[$key] = $fila[1]; //columna de nombre
                }
                //ordenamos descendente por la columna elegida
                array_multisort($ids, SORT_DESC, $meses);
                return $meses;
    }

    /**
     * Funcion que retorna el nombre de un mes de acuerdo a un número de mes
     * @param String $numero_mes
     * @return string
     */
    function nombreMes($numero_mes){
        switch ($numero_mes) {
        	case '01':
        		$mes="ENERO";
        		break;

        	case '02':
        		$mes="FEBRERO";
        		break;

        	case '03':
        		$mes="MARZO";
        		break;

        	case '04':
        		$mes="ABRIL";
        		break;

        	case '05':
        		$mes="MAYO";
        		break;

        	case '06':
        		$mes="JUNIO";
        		break;

        	case '07':
        		$mes="JULIO";
        		break;

        	case '08':
        		$mes="AGOSTO";
        		break;

        	case '09':
        		$mes="SEPTIEMBRE";
        		break;

        	case '10':
        		$mes="OCTUBRE";
        		break;

        	case '11':
        		$mes="NOVIEMBRE";
        		break;

        	case '12':
        		$mes="DICIEMBRE";
        		break;

        	default:
        		$mes="";
        		break;
        }
        return $mes;

    }

    /**
     * Funcion que llama al metodo de consultar las solicitudes y al metodo de mostrar la información relacionada
     * @param <array> $contratos
     */
    function mostrarListadoSolicitudes($contratos){
        //var_dump($contratos);exit;
        foreach ($contratos as $key => $contrato) {
            $vigencia=$contrato['VIGENCIA'];
            $cod_contrato=$contrato['NUM_CONTRATO'];

            $resultado = $this->consultarSolicitudes($vigencia,$cod_contrato);
            if($resultado){
                $registro[] = $resultado;
            }

        }
         
        if($registro){
            $indice=0;
            foreach ($registro as $key => $value) {
                $arreglos=$value;
                foreach ($arreglos as $key2 => $arreglo) {
                        $cumplidos[$indice]['id'] = $arreglo['id'];
                        $cumplidos[$indice]['vigencia'] =$arreglo['vigencia'];
                        $cumplidos[$indice]['num_contrato'] =$arreglo['num_contrato'];
                        $cumplidos[$indice]['anio'] =$arreglo['anio'];
                        $cumplidos[$indice]['mes'] =$arreglo['mes'];
                        $cumplidos[$indice]['procesado'] =$arreglo['procesado'];
                        $cumplidos[$indice]['estado'] =$arreglo['estado'];
                        $cumplidos[$indice]['estado_reg'] =$arreglo['estado_reg'];
                        $cumplidos[$indice]['fecha'] =$arreglo['fecha'];
                        $cumplidos[$indice]['num_impresiones'] =$arreglo['num_impresiones'];
                        $cumplidos[$indice]['valor'] =$arreglo['valor'];
                        $cumplidos[$indice]['id_cta'] =$arreglo['id_cta'];
                        $indice++;

                }
            }

            $this->mostrarRegistro($this->configuracion,$cumplidos, $this->configuracion['registro'], "multiplesCumplidos", "");

        }

         
    }

    /**
     * Funcion que consulta los registros de las solicitudes de cumplido de un contratista
     * @param int $vigencia
     * @param int $cod_contrato
     * @return <array>
     */
    function consultarSolicitudes($vigencia,$cod_contrato){
            $datos=array('vigencia'=>$vigencia,
                            'cod_contrato'=>$cod_contrato);
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_nomina,"solicitudes_cumplido",$datos);
            //echo "<br>cadena ".$cadena_sql;
            $resultado = $this->ejecutarSQL($this->configuracion, $this->acceso_nomina, $cadena_sql, "busqueda");
            return $resultado;

    }


    /**
     * Funcion que muestra un mensaje de alerta y retorna a una pagina
     * @param type $pagina
     * @param type $variable
     * @param type $mensaje
     */
    function retornar($pagina,$variable,$mensaje=""){
        if($mensaje=="")
        {

        }
        else
        {
        	echo "<script>alert ('".$mensaje."');</script>";
        }
        $this->enlaceParaRetornar($pagina, $variable);
    }

    /**
     * Funcion que retorna a una pagina
     * @param <string> $pagina
     * @param <string> $variable
     */
    function enlaceParaRetornar($pagina,$variable) {
        echo "<script>location.replace('".$pagina.$variable."')</script>";
        exit;
    }

    function revisarSolicitudCumplido($configuracion){
            $solicitudes = $this->consultarTodasSolicitudesCumplido();
            $solicitudes = $this->asignarValorCumplido($solicitudes);
            //var_dump($solicitudes);exit;
            $this->htmlCumplido->form_revisar_solicitud($configuracion, $solicitudes);

        }
         
        /**
         * Funcion que consulta los registros de todas las solicitudes de cumplido
         * @param int $vigencia
         * @param int $cod_contrato
         * @return <array>
         */
        function consultarTodasSolicitudesCumplido(){
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_nomina,"todas_solicitudes_cumplido","");
            //echo "<br>cadena ".$cadena_sql;
            $resultado = $this->ejecutarSQL($this->configuracion, $this->acceso_nomina, $cadena_sql, "busqueda");
            return $resultado;

    }

    function revisarAprobacion(){
        var_dump($_REQUEST);//exit;
        $aprobados=0;
        $total=(isset($_REQUEST['total_registros'])?$_REQUEST['total_registros']:0);
        if($total){
                for($i=0;$i<$total;$i++){
                    $modificado=0;
                    $nombre = "id_solicitud_".$i;
                    $nombre_valor = "valor_solicitud_".$i;
                    $_REQUEST[$nombre]=(isset($_REQUEST[$nombre])?$_REQUEST[$nombre]:'');
                    if($_REQUEST[$nombre]){
                        //echo "<br>".$nombre." seleccionado , id=".$_REQUEST[$nombre];
                        $modificado = $this->aprobarSolicitud($_REQUEST[$nombre],$_REQUEST[$nombre_valor]);
                        if($modificado>0){
                            $aprobados++;
                        }
                    }
                }
                if($aprobados>0){
                        $mensaje = $aprobados. " solicitudes aprobadas con exito";
                }else{
                        $mensaje = " No se aprobó ninguna solicitud";
                }
                $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                $variable="pagina=nom_adminCumplido";
                $variable.="&opcion=revisar_solicitud";

                $variable=$this->cripto->codificar_url($variable,$this->configuracion);
                $this->retornar($pagina,$variable,$mensaje);

        }
    }

    function aprobarSolicitud($id_solicitud,$valor_solicitud){

            $aprobado=$this->actualizarSolicitud($id_solicitud,$valor_solicitud);
            return $aprobado;
    }

    function actualizarSolicitud($id_solicitud,$valor_solicitud){
            $datos=array('id'=>$id_solicitud,
                            'valor'=>$valor_solicitud,
                            'estado'=>'APROBADO');
            $cadena_sql = $this->sql->cadena_sql($this->configuracion,$this->acceso_nomina,"aprobar_solicitud",$datos);
            // echo "cadena ".$cadena_sql;//exit;
            $this->ejecutarSQL($this->configuracion, $this->acceso_nomina, $cadena_sql, "");
            return $this->totalAfectados($this->configuracion, $this->acceso_nomina);
    }

    function asignarValorCumplido($solicitudes){
        //foreach ($solicitudes as $key => $solicitud) {
            $fecha_inicial='2009-02-16';
            $fecha_final='2010-02-15';
            $valor_contrato=30000000;
            $tiempo_contrato_dias=360;
            $cumplido = $this->calcularDiasCumplido($fecha_inicial,$fecha_final,$valor_contrato,$tiempo_contrato_dias,$mes_cumplido,$anio_cumplido);
            $dias_cumplido = $cumplido['dias'];
            $valor_dia = $this->calcularValorDia($valor_contrato,$tiempo_contrato_dias);
            $valor_cumplido = $dias_cumplido*$valor_dia;
            echo "<br>valor ".$valor_cumplido;
            //}
            return $solicitudes;
    }

    function calcularDiasCumplido($fecha_inicial,$fecha_final,$valor_contrato,$tiempo_contrato_dias,$mes_cumplido,$anio_cumplido){
            $dia_inicio_contrato= substr($fecha_inicial, 6,2);
            $mes_inicio_contrato= substr($fecha_inicial, 4,2);
            $anio_inicio_contrato= substr($fecha_inicial, 0,4);

            if($mes_cumplido==$mes_inicio_contrato && $anio_cumplido==$anio_inicio_contrato){
        
            }
            $minuendo=30;
            $sustraendo=1;
            $dias_cumplido = ($minuendo-$sustraendo)+1;
            $finicial_cumplido='2009-05-01';
            $ffinal_cumplido='2009-05-30';
            $resultado=array('dias'=>$dias_cumplido,
                            'finicial'=>$finicial_cumplido,
                            'ffinal'=>$ffinal_cumplido
            );

            return $resultado;
    }

    function calcularValorDia($valor_contrato,$tiempo_contrato_dias){
            $valor_dia = $valor_contrato/$tiempo_contrato_dias;
            return $valor_dia;
    }

} // fin de la clase


?>



