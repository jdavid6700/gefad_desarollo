<?php 
/*
 ############################################################################
#    UNIVERSIDAD DISTRITAL Francisco Jose de Caldas                        #
#    Copyright: Vea el archivo EULA.txt que viene con la distribucion      #
############################################################################
*/

if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;
}


class html_adminCumplido {

	public $configuracion;
	public $cripto;
	public $indice;

	function __construct($configuracion) {

		$this->configuracion = $configuracion;
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
		$indice=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
		$this->cripto=new encriptar();
		$this->indice=$configuracion["host"].$configuracion["site"]."/index.php?";
		$this->html = new html();

	}


	/**
	 * Funcion que muestra el formulario de solicitud donde estan los contratos y los periodos para pago
	 * @param <array> $contratos
	 * @param <array> $meses
	 * @param String $tema
	 * @param String $estilo
	 */
	function form_solicitud($contratos, $meses, $tema='',$estilo='')
	{
		$indice=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
		/*****************************************************************************************************/
		include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/html.class.php");
		$_REQUEST['mes_cumplido']=(isset($_REQUEST['mes_cumplido'])?$_REQUEST['mes_cumplido']:'');
		$_REQUEST['codigo_contrato']=(isset($_REQUEST['codigo_contrato'])?$_REQUEST['codigo_contrato']:'');
		$tab = 1;
		$this->formulario = "nom_admin_cumplido";
		$this->verificar .= "seleccion_valida(".$this->formulario.",'mes_cumplido')";

		$lista_contratos=$this->html->cuadro_lista($contratos,'codigo_contrato',$this->configuracion,0,$_REQUEST['codigo_contrato'],FALSE,$tab++,'codigo_contrato');
		if($meses){
			$lista_meses=$this->html->cuadro_lista($meses,'mes_cumplido',$this->configuracion,-1,$_REQUEST['mes_cumplido'],FALSE,$tab++,'mes_cumplido');
		}else{
			$lista_meses="Faltan fechas del contrato para definir meses de cumplido.";
		}

		?>
<script
	src="<? echo $this->configuracion["host"].$this->configuracion["site"].$this->configuracion["javascript"]  ?>/funciones.js"
	type="text/javascript" language="javascript"></script>
<script
	src="<? echo $this->configuracion['host'].$this->configuracion['site'].$this->configuracion['javascript']  ?>/cargaFormulario.js"
	type="text/javascript" language="javascript"></script>
<link
	rel='stylesheet' type='text/css' media='all'
	href='<? echo $this->configuracion['host'].$this->configuracion['site'].$this->configuracion['estilo']."/calendario/calendar-blue2.css"?>'
	title="win2k-cold-1" />
<script
	type='text/javascript'
	src=<? echo $this->configuracion['host'].$this->configuracion['site'].$this->configuracion['estilo']."/calendario/calendar.js"?>></script>
<script
	type='text/javascript'
	src=<? echo $this->configuracion['host'].$this->configuracion['site'].$this->configuracion['estilo']."/calendario/calendar-es.js"?>></script>
<script
	type='text/javascript'
	src=<? echo $this->configuracion['host'].$this->configuracion['site'].$this->configuracion['estilo']."/calendario/calendar-setup.js"?>></script>
<body topmargin="0" leftmargin="0">

	<form enctype='multipart/form-data' method='POST' action='index.php'
		name='<? echo $this->formulario;?>'>

		<center>
			<table width='80%' class="bordered">
				<?
				echo "<th colspan='2'>Solicitud de cumplido</th>";
				?>
				<tr>
					<td width='25%' class="texto_elegante estilo_td"><?
					$texto_ayuda = "<b>Contrato.</b><br>Seleccione una opción de la lista. ";
					?><font color="red">*</font>&nbsp;<span
						onmouseover="return escape('<? echo $texto_ayuda?>')">No.
							Contrato:</span>
					</td>
					<td class="texto_elegante estilo_td"><?
					echo $lista_contratos;
					?>
					</td>
				</tr>
				<tr>
					<td width='25%' class="texto_elegante estilo_td"><?
					$texto_ayuda = "<b>Mes de cumplido.</b><br>Seleccione una opción de la lista. ";
					?><font color="red">*</font>&nbsp;<span
						onmouseover="return escape('<? echo $texto_ayuda?>')">Mes de
							cumplido:</span>
					</td>
					<td class="texto_elegante estilo_td"><?
					echo $lista_meses;
					?>
					</td>
				</tr>

				<tr>
					<td colspan="2">
						<table width='90%' height="45">
							<tr class="texto_elegante estilo_td">
								<td colspan="5"><br>Todos los campos marcados con ( * ) son
									obligatorios. <br></td>
							</tr>
						</table>
					</td>
				</tr>

				<tr align='center'>
					<td colspan='2' rowspan='1'><br> <input type='hidden'
						name='vigencia_contrato' value='<? ?>'> <!--<input type='hidden' name='action' value='nom_admin_cumplido'>-->
						<input type='hidden' name='pagina' value='nom_adminCumplido'> <input
						type='hidden' name='opcion' value='verificar'> <input
						value="Solicitar" name="aceptar" tabindex="<?= $tab++ ?>"
						type="button"
						onclick="if(<?= $this->verificar; ?>){document.forms['<? echo $this->formulario?>'].submit()}else{false}">
					</td>
				</tr>


			</table>
		</center>

	</form>
	<br>
	<?php
	} // fin function


	/**
	 * Funcion que muestra el formulario para enviar la solicitud
	 * @param String $contrato
	 * @param String $mes_cumplido
	 * @param <array> $cuentas
	 * @param String $tema
	 * @param String $estilo
	 */
	function form_envio_solicitud($contrato, $mes_cumplido, $cuentas, $tema='',$estilo='')
	{

		$indice=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
		$cod_contrato=(isset($contrato[0]['NUM_CONTRATO'])?$contrato[0]['NUM_CONTRATO']:'');
		$vigencia_contrato=(isset($contrato[0]['VIGENCIA'])?$contrato[0]['VIGENCIA']:'');
		/*****************************************************************************************************/
		include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/html.class.php");

		$tab = 1;
		$this->formulario = "nom_admin_cumplido";
		if(is_array($cuentas)){
                    $this->verificar = "seleccion_valida(".$this->formulario.",'cta_id')";
                    $lista_cuentas = $this->html->cuadro_lista($cuentas,'cta_id',$this->configuracion,-1,0,FALSE,$tab++,'cta_id');
                } else{
                    $this->verificar = true;
                    $cod_cta = $cuentas;
                }

                ?>
	<script
		src="<? echo $this->configuracion["host"].$this->configuracion["site"].$this->configuracion["javascript"]  ?>/funciones.js"
		type="text/javascript" language="javascript"></script>
	<script
		src="<? echo $this->configuracion['host'].$this->configuracion['site'].$this->configuracion['javascript']  ?>/cargaFormulario.js"
		type="text/javascript" language="javascript"></script>
	<link rel='stylesheet' type='text/css' media='all'
		href='<? echo $this->configuracion['host'].$this->configuracion['site'].$this->configuracion['estilo']."/calendario/calendar-blue2.css"?>'
		title="win2k-cold-1" />
	<script type='text/javascript'
		src=<? echo $this->configuracion['host'].$this->configuracion['site'].$this->configuracion['estilo']."/calendario/calendar.js"?>></script>
	<script type='text/javascript'
		src=<? echo $this->configuracion['host'].$this->configuracion['site'].$this->configuracion['estilo']."/calendario/calendar-es.js"?>></script>
	<script type='text/javascript'
		src=<? echo $this->configuracion['host'].$this->configuracion['site'].$this->configuracion['estilo']."/calendario/calendar-setup.js"?>></script>
<body topmargin="0" leftmargin="0">

	<form enctype='multipart/form-data' method='POST' action='index.php'
		name='<? echo $this->formulario;?>'>
		<table width='90%' class="bordered">
			<tr>
				<td align="center"><b> <? echo "Solicitar Cumplido";?>
				</b>
				</td>
			</tr>
			<tr>
				<td><? 
				echo "<br><b>Vigencia contrato:</b> ".$vigencia_contrato;
				echo "<br><b> Periodo pago (AAAA-MM):</b> ".substr($mes_cumplido,0,4)." - ".substr($mes_cumplido,4,2);
				?></td>
			</tr>

			<tr>
				<td class='texto_elegante estilo_td'><br> <?
				if($cuentas){
                                echo "  Seleccione la cuenta a la cual le deben consignar el pago, y para elaborar el cumplido.<br><br>";
                                echo $lista_cuentas;
                            }else{
                                echo "<input type='hidden' name='cta_id' value='".$cod_cta."'>";

                            }
                            ?></td>
			</tr>
			<tr align='center'>
				<td colspan='2' rowspan='1'><br> <input type='hidden'
					name='cod_contrato' value='<? echo $cod_contrato?>'> <input
					type='hidden' name='mes_cumplido' value='<? echo $mes_cumplido?>'>
					<input type='hidden' name='vigencia_contrato'
					value='<? echo $vigencia_contrato?>'> <input type='hidden'
					name='action' value='nom_admin_cumplido'> <input type='hidden'
					name='opcion' value='nuevo'> <input value="Registrar Solicitud"
					name="aceptar" tabindex="<?= $tab++ ?>" type="button"
					onclick="if(<?= $this->verificar; ?>){document.forms['<? echo $this->formulario?>'].submit()}else{false}">
				</td>
			</tr>
		</table>
	</form>
	<br>
	<?php
	} // fin function

	/**
	 * Funcion que muestra los dayos del contrato
	 * @param <array> $datos_contrato
	 * @param String $tipo_contrato
	 * @param date $fecha_contrato
	 */
	function mostrarDatosContrato($datos_contrato,$tipo_contrato,$fecha_contrato){
            $unidad_ejecutora=(isset($datos_contrato[0]['CODIGO_UNIDAD_EJECUTORA'])?$datos_contrato[0]['CODIGO_UNIDAD_EJECUTORA']:'');
            $tipo_identificacion=(isset($datos_contrato[0]['TIPO_IDENTIFICACION'])?$datos_contrato[0]['TIPO_IDENTIFICACION']:'');
            $identificacion=(isset($datos_contrato[0]['NUM_IDENTIFICACION'])?$datos_contrato[0]['NUM_IDENTIFICACION']:'');
            $nombre=(isset($datos_contrato[0]['RAZON_SOCIAL'])?$datos_contrato[0]['RAZON_SOCIAL']:'');
            $num_contrato=(isset($datos_contrato[0]['NUM_CONTRATO'])?$datos_contrato[0]['NUM_CONTRATO']:'');
            $fecha_inicio=(isset($datos_contrato[0]['FECHA_INICIO'])?$datos_contrato[0]['FECHA_INICIO']:'');
            $fecha_fin=(isset($datos_contrato[0]['FECHA_FINAL'])?$datos_contrato[0]['FECHA_FINAL']:'');
            $valor_contrato=(isset($datos_contrato[0]['CUANTIA'])?$datos_contrato[0]['CUANTIA']:'');
            $duracion = (isset($datos_contrato[0]['PLAZO_EJECUCION'])?$datos_contrato[0]['PLAZO_EJECUCION']:'');
            $objeto= (isset($datos_contrato[0]['OBJETO'])?$datos_contrato[0]['OBJETO']:'');
            $tipo_contrato= (isset($tipo_contrato)?$tipo_contrato:'');

            ?>

	<table class='bordered'>
		<tr>
			<th colspan="6" class="estilo_th">DATOS CONTRATO</th>
		</tr>
		<tr>
			<td class='texto_elegante estilo_td'>N&uacute;mero del contrato:</td>
			<td class='texto_elegante estilo_td'><? echo  $num_contrato?>
			</td>
			<td class='texto_elegante estilo_td'>Tipo de contrato:</td>
			<td class='texto_elegante estilo_td' colspan="3"><? echo  $tipo_contrato?>
			</td>
		</tr>
		<tr>
			<td class='texto_elegante estilo_td'>Fecha contrato:</td>
			<td class='texto_elegante estilo_td'><? echo  $fecha_contrato?>
			</td>
			<td class='texto_elegante estilo_td'>Fecha inicio del contrato:</td>
			<td class='texto_elegante estilo_td'><? echo  $fecha_inicio?>
			</td>
			<td class='texto_elegante estilo_td'>Fecha final del contrato :</td>
			<td class='texto_elegante estilo_td'><? echo  $fecha_fin?>
			</td>
		</tr>
		<tr>
			<td class='texto_elegante estilo_td'>Valor del Contrato:</td>
			<td class='texto_elegante estilo_td'><? echo  "$ ".number_format($valor_contrato)?>
			</td>
			<td class='texto_elegante estilo_td'>Plazo de ejecuci&oacute;n:</td>
			<td class='texto_elegante estilo_td'><? echo  $duracion?>
			</td>
			<td class='texto_elegante estilo_td'>Unidad Ejecutora:</td>
			<td class='texto_elegante estilo_td'><? echo  $unidad_ejecutora?>
			</td>

		</tr>
		<tr>
			<td class='texto_elegante estilo_td'>Objeto:</td>
			<td class='texto_elegante estilo_td' colspan="5"><? echo  $objeto?>
			</td>

		</tr>

	</table>
	<?
    }

    /**
     * Funcion que muestra los datos de las cuentas bancarias relacionadas al contratista
     * @param <array> $datos_cuenta
     */
    function mostrarDatosCuentaBanco($datos_cuenta){

                echo "<table class='bordered' width='100%'>";
                echo "    <tr>";
                echo "        <th colspan='3' class='estilo_th'>DATOS CUENTA(S) BANCARIA(S)</th>";
                echo "    </tr>";
                echo "    <tr>";
                echo "        <td class='texto_elegante estilo_td' >Nombre Banco</td>";
                echo "        <td class='texto_elegante estilo_td' >No. cuenta</td>";
                echo "        <td class='texto_elegante estilo_td' >Tipo Cuenta</td>";
                echo "    </tr>";

                if(is_array($datos_cuenta)){
                        foreach ($datos_cuenta as $key => $value)
                        {
                        	$banco=(isset($datos_cuenta[$key]['BANCO'])?$datos_cuenta[$key]['BANCO']:'');
                        	$tipo_cta=(isset($datos_cuenta[$key]['TIPO_CTA'])?$datos_cuenta[$key]['TIPO_CTA']:'');
                        	$num_cta=(isset($datos_cuenta[$key]['NRO_CTA'])?$datos_cuenta[$key]['NRO_CTA']:'');

                        	echo "<tr>";
                        	echo "    <td class='texto_elegante estilo_td'>".$banco."</td>";
                        	echo "    <td class='texto_elegante estilo_td'>".$num_cta."</td>";
                        	echo "    <td class='texto_elegante estilo_td'>".$tipo_cta."</td>";
                        	echo "</tr>";
                        }
                    }else{
                            echo "<tr>";
                            echo "  <td class='texto_elegante estilo_td' colspan='3'> NO EXISTEN DATOS DE CUENTAS RELACIONADAS</td>";
                            echo "</tr>";
                    }
                    echo "</table>";

    }

    /**
     * Funcion que muestra los dat os de la disponibilidad relacionada a un contrato
     * @param type $datos_disponibilidad
     */
    function mostrarDatosDisponibilidad($datos_disponibilidad){
            $nro_cdp=(isset($datos_disponibilidad[0]['NUMERO_DISPONIBILIDAD'])?$datos_disponibilidad[0]['NUMERO_DISPONIBILIDAD']:'');
            $fecha_cdp=(isset($datos_disponibilidad[0]['FECHA_DISPONIBILIDAD'])?$datos_disponibilidad[0]['FECHA_DISPONIBILIDAD']:'');
            $valor_cdp=(isset($datos_disponibilidad[0]['VALOR'])?$datos_disponibilidad[0]['VALOR']:'');

            ?>

	<table class='bordered' width='100%'>
		<tr>
			<th colspan="6" class="estilo_th">CERTIFICADO DE DISPONIBILIDAD
				PRESUPUESTAL</th>
		</tr>
		<tr>
			<td class='texto_elegante estilo_td'>Certificado de Disponibilidad
				Presupuestal No:</td>
			<td class='texto_elegante estilo_td'><? echo  $nro_cdp?>
			</td>
			<td class='texto_elegante estilo_td'>Fecha:</td>
			<td class='texto_elegante estilo_td'><? echo  $fecha_cdp?>
			</td>
			<td class='texto_elegante estilo_td'>Valor:</td>
			<td class='texto_elegante estilo_td'><? echo "$ ".number_format($valor_cdp)?>
			</td>
		</tr>

	</table>
	<?
    }

    /**
     * Funcion que muestra los datos de los certificados presupuestales
     * @param <array> $datos_registro
     */
    function mostrarDatosRegistroPresupuestal($datos_registro){
            $nro_crp=(isset($datos_registro[0]['NUMERO_REGISTRO'])?$datos_registro[0]['NUMERO_REGISTRO']:'');
            $fecha_crp=(isset($datos_registro[0]['FECHA_REGISTRO'])?$datos_registro[0]['FECHA_REGISTRO']:'');
            $valor_crp=(isset($datos_registro[0]['VALOR'])?$datos_registro[0]['VALOR']:'');

            ?>

	<table class='bordered' width='100%'>
		<tr>
			<th colspan="6" class="estilo_th">CERTIFICADO DE REGISTRO
				PRESUPUESTAL</th>
		</tr>
		<tr>
			<td class='texto_elegante estilo_td'>Certificado de Registro
				Presupuestal No:</td>
			<td class='texto_elegante estilo_td'><? echo  $nro_crp?>
			</td>
			<td class='texto_elegante estilo_td'>Fecha:</td>
			<td class='texto_elegante estilo_td'><? echo  $fecha_crp?>
			</td>
			<td class='texto_elegante estilo_td'>Valor:</td>
			<td class='texto_elegante estilo_td'><? echo "$ ".number_format($valor_crp)?>
			</td>
		</tr>

	</table>
	<?
    }

    /**
     * Funcion para mostrar datos del contratista
     * @param <array> $datos_contratista
     */
    function mostrarDatosContratista($datos_contratista){
            $tipo_identificacion=(isset($datos_contratista[0]['TIPO_DOCUMENTO'])?$datos_contratista[0]['TIPO_DOCUMENTO']:'');
            $identificacion=(isset($datos_contratista[0]['NUMERO_DOCUMENTO'])?$datos_contratista[0]['NUMERO_DOCUMENTO']:'');
            $primer_nombre=(isset($datos_contratista[0]['PRIMER_NOMBRE'])?$datos_contratista[0]['PRIMER_NOMBRE']:'');
            $segundo_nombre=(isset($datos_contratista[0]['SEGUNDO_NOMBRE'])?$datos_contratista[0]['SEGUNDO_NOMBRE']:'');
            $primer_apellido=(isset($datos_contratista[0]['PRIMER_APELLIDO'])?$datos_contratista[0]['PRIMER_APELLIDO']:'');
            $segundo_apellido=(isset($datos_contratista[0]['SEGUNDO_APELLIDO'])?$datos_contratista[0]['SEGUNDO_APELLIDO']:'');
            $direccion=(isset($datos_contratista[0]['DIRECCION'])?$datos_contratista[0]['DIRECCION']:'');
            $telefono=(isset($datos_contratista[0]['TELEFONO'])?$datos_contratista[0]['TELEFONO']:'');

            ?>

	<table class='bordered' width='100%'>

		<tr>
			<th colspan="4" class="estilo_th">CONTRATISTA</th>
		</tr>
		<tr>
			<td class='texto_elegante estilo_td'>Identificaci&oacute;n:</td>
			<td class='texto_elegante estilo_td' colspan="3"><? echo  $tipo_identificacion." No. ".$identificacion?>
			</td>

		</tr>
		<tr>
			<td class='texto_elegante estilo_td'>Primer Nombre:</td>
			<td class='texto_elegante estilo_td'><? echo  $primer_nombre?>
			</td>
			<td class='texto_elegante estilo_td'>Segundo Nombre:</td>
			<td class='texto_elegante estilo_td'><? echo  $segundo_nombre?>
			</td>
		</tr>
		<tr>
			<td class='texto_elegante estilo_td'>Primer Apellido:</td>
			<td class='texto_elegante estilo_td'><? echo  $primer_apellido?>
			</td>
			<td class='texto_elegante estilo_td'>Segundo Apellido:</td>
			<td class='texto_elegante estilo_td'><? echo  $segundo_apellido?>
			</td>
		</tr>
		<tr>
			<td class='texto_elegante estilo_td'>Direcci&oacute;n:</td>
			<td class='texto_elegante estilo_td'><? echo  $direccion?>
			</td>
			<td class='texto_elegante estilo_td'>Tel&eacute;fono:</td>
			<td class='texto_elegante estilo_td'><? echo  $telefono?>
			</td>

		</tr>

	</table>
	<?
    }

    /**
     * Funcion que muestra los datos de ordenes de pago
     */
    function mostrarDatosOrdenPago($datos_orden){

                echo "<table class='bordered' width='100%'>";
                echo "    <tr>";
                echo "          <th colspan='3' class='estilo_th'>ORDEN(ES) DE PAGO</th>";
                echo "    </tr>";

                echo "    <tr>";
                echo "        <td class='texto_elegante estilo_td' >No.</td>";
                echo "        <td class='texto_elegante estilo_td' >Fecha</td>";
                echo "        <td class='texto_elegante estilo_td' >Valor</td>";
                echo "    </tr>";

                if(is_array($datos_orden)){
                        foreach ($datos_orden as $key => $value)
                        {
                        	$num_orden=(isset($datos_orden[$key]['NUMERO_ORDEN'])?$datos_orden[$key]['NUMERO_ORDEN']:'');
                        	$fecha=(isset($datos_orden[$key]['FECHA_ORDEN'])?$datos_orden[$key]['FECHA_ORDEN']:'');
                        	$valor=(isset($datos_orden[$key]['VALOR_OP'])?$datos_orden[$key]['VALOR_OP']:'');

                        	echo "<tr>";
                        	echo "    <td class='texto_elegante estilo_td'>".$num_orden."</td>";
                        	echo "    <td class='texto_elegante estilo_td'>".$fecha."</td>";
                        	echo "    <td class='texto_elegante estilo_td'>"."$ ".number_format($valor,2)."</td>";
                        	echo "</tr>";
                        }
                    }else{
                            echo "<tr>";
                            echo "  <td class='texto_elegante estilo_td' colspan='3'> NO EXISTEN DATOS DE ORDENES DE PAGO RELACIONADAS</td>";
                            echo "</tr>";
                    }
                    echo "</table>";

    }

     
    /**
     * Funcion que muestra los datos de las novedades
     * @param <array> $datos_novedad
     */
    function mostrarNovedades($datos_novedad){
           //var_dump($datos_novedad);
                echo "<table class='bordered' width='100%'>";
                echo "    <tr>";
                echo "          <th colspan='7' class='estilo_th'>NOVEDADES</th>";
                echo "    </tr>";
                echo "    <tr>";
                echo "        <td class='texto_elegante estilo_td' width='5%'>No.</td>";
                echo "        <td class='texto_elegante estilo_td' width='15%'>Tipo</td>";
                echo "        <td class='texto_elegante estilo_td' width='12%'>Fecha</td>";
                echo "        <td class='texto_elegante estilo_td' width='12%'>Fecha Inicial</td>";
                echo "        <td class='texto_elegante estilo_td' width='12%'>Fecha Final</td>";
                echo "        <td class='texto_elegante estilo_td' width='39%'>Descripcion</td>";
                echo "        <td class='texto_elegante estilo_td' width='5%'>Estado</td>";
                echo "    </tr>";

                if(is_array($datos_novedad)){
                        foreach ($datos_novedad as $key => $value)
                        {
                        	$num_nov=(isset($datos_novedad[$key]['id_nov'])?$datos_novedad[$key]['id_nov']:'');
                        	$tipo=(isset($datos_novedad[$key]['tipo_nov'])?$datos_novedad[$key]['tipo_nov']:'');
                        	$fecha_nov=(isset($datos_novedad[$key]['fecha_nov'])?$datos_novedad[$key]['fecha_nov']:'');
                        	$fecha_ini=(isset($datos_novedad[$key]['fecha_inicio'])?$datos_novedad[$key]['fecha_inicio']:'');
                        	$fecha_fin=(isset($datos_novedad[$key]['fecha_fin'])?$datos_novedad[$key]['fecha_fin']:'');
                        	$estado=(isset($datos_novedad[$key]['estado_nov'])?$datos_novedad[$key]['estado_nov']:'');
                        	$valor = (isset($datos_novedad[$key]['valor_nov'])?$datos_novedad[$key]['valor_nov']:'');
                        	switch ($datos_novedad[$key]['cod_tipo_nov']){
                        		case 1:
                        			$descripcion=(isset($datos_novedad[$key]['descripcion_nov'])?$datos_novedad[$key]['descripcion_nov']:'');
                        			$descripcion.="<br>BANCO: ".(isset($datos_novedad[$key]['banco'])?$datos_novedad[$key]['banco']:'')."<br>";
                        			$descripcion.="<br>No. CUENTA: ".(isset($datos_novedad[$key]['num_cta'])?$datos_novedad[$key]['num_cta']:'')."<br>";
                        			$descripcion.="<br>TIPO CUENTA: ".(isset($datos_novedad[$key]['tipo_cta'])?$datos_novedad[$key]['tipo_cta']:'')."<br>";
                        			break;
                        		default:
                        			$descripcion=(isset($datos_novedad[$key]['descripcion_nov'])?$datos_novedad[$key]['descripcion_nov']:'');
                        			if($valor){
                                                $descripcion.="<br>VALOR: $".number_format($valor,2);
                                            }
                                            break;
                                    }
                                    echo "<tr>";
                                    echo "    <td class='texto_elegante estilo_td'>".$num_nov."</td>";
                                    echo "    <td class='texto_elegante estilo_td'>".$tipo."</td>";
                                    echo "    <td class='texto_elegante estilo_td'>".$fecha_nov."</td>";
                                    echo "    <td class='texto_elegante estilo_td'>".$fecha_ini."</td>";
                                    echo "    <td class='texto_elegante estilo_td'>".$fecha_fin."</td>";
                                    echo "    <td class='texto_elegante estilo_td'>".$descripcion."</td>";
                                    echo "    <td class='texto_elegante estilo_td'>".$estado."</td>";
                                    echo "</tr>";
                        }
                    }else{
                            echo "<tr>";
                            echo "  <td class='texto_elegante estilo_td' colspan='6'> NO EXISTEN DATOS DE NOVEDADES RELACIONADAS</td>";
                            echo "</tr>";
                    }
                    echo "</table>";

    }



    /**
     * Funcion que muestra los datos de las solicitudes de cumplido realizadas
     * @param <array> $configuracion
     * @param <array> $registro
     * @param int $total
     * @param <array> $variable
     */
    function multiplesCumplidos($configuracion,$registro, $total, $variable)
    {
    	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
    	$indice = $configuracion["host"].$configuracion["site"]."/index.php?";
    	$cripto = new encriptar();
    	if((isset($registro[0]['VIGENCIA'])?$registro[0]['VIGENCIA']:0)>0){
                    $vigencia = "Vigencia ".$registro[0]['VIGENCIA'];
                }else{
                    $vigencia = "";
                }
                ?>
	<table width="80%" align="center" border="0" cellpadding="10"
		cellspacing="0">
		<tbody>
			<tr>
				<td>
					<table class="bordered" width="100%" border="0" align="center"
						cellpadding="5 px" cellspacing="1px">
						<tr class="texto_subtitulo">
							<th>Cumplido(s)</th>
						</tr>
						<tr>
							<td>
								<table class="bordered" width="100%">
									<tr class='cuadro_color'>
										<th width="5%">Id.</th>
										<th width="10%">Vigencia</th>
										<th width="10%">N&uacute;mero de Contrato</th>
										<th width="20%">Mes</th>
										<th width="20%">Fecha</th>
										<th width="20%">Estado</th>
										<th width="5%">Impresiones</th>

									</tr>
									<?

									foreach ($registro as $key => $value)
									{
										$id = (isset($registro[$key]['id'])?$registro[$key]['id']:'');
										$vigencia = (isset($registro[$key]['vigencia'])?$registro[$key]['vigencia']:'');
										$num_contrato = (isset($registro[$key]['num_contrato'])?$registro[$key]['num_contrato']:'');
										$mes = (isset($registro[$key]['mes'])?$registro[$key]['mes']:'');
										$anio= (isset($registro[$key]['anio'])?$registro[$key]['anio']:'');
										$fecha = (isset($registro[$key]['fecha'])?$registro[$key]['fecha']:'');
										$estado= (isset($registro[$key]['estado'])?$registro[$key]['estado']:'');
										$num_impresiones= (isset($registro[$key]['num_impresiones'])?$registro[$key]['num_impresiones']:'');
											
											
										echo "	<tr>
				<td class='texto_elegante estilo_td'>".$id."</td>
				<td class='texto_elegante estilo_td'>".$vigencia."</td>
				<td class='texto_elegante estilo_td'>".$num_contrato."</td>
				<td class='texto_elegante estilo_td'>".$anio." - ".$mes."</td>
	 		<td class='texto_elegante estilo_td'>".$fecha."</td>
	 		<td class='texto_elegante estilo_td'>".$estado."</td>
	 		<td class='texto_elegante estilo_td'>".$num_impresiones."</td>

			</tr>";
											
									}//fin for
									?>
								</table>
							</td>
						</tr>
					</table>
				</td>
			</tr>

		</tbody>
	</table>
	<?
    }//fin funcion multiples cumplidos

    /**
     * Funcion que muestra el formulario para revisar las solicitudes de cumplido
     * @param <array> $configuracion
     * @param <array> $registro
     * @param int $total
     * @param <array> $variable
     */
    function form_revisar_solicitud($configuracion,$registro)
    {
    	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
    	$indice = $configuracion["host"].$configuracion["site"]."/index.php?";
    	$cripto = new encriptar();
    	$tab=0;
    	$total_registros = count($registro);
    	if((isset($registro[0]['VIGENCIA'])?$registro[0]['VIGENCIA']:0)>0){
                    $vigencia = "Vigencia ".$registro[0]['VIGENCIA'];
                }else{
                    $vigencia = "";
                }
                ?>
	<link rel="stylesheet"
		href="<? echo $configuracion["host"].$configuracion["site"].$configuracion["plugins"];?>/jPages-master/css/jPages.css">

	<script type="text/javascript"
		src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["plugins"];?>/jPages-master/js/jquery-1.8.2.min.js"></script>
	<script
		src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["plugins"];?>/jPages-master/js/jPages.js"></script>
	<!-- Script para paginar el listado -->
	<script>
                            
                        $(function (){
                            $("div.holder").jPages({
                            containerID : "contratistas",
                            previous : "←",
                            next : "→",
                            perPage : <? echo $configuracion["registro"]?>,
                            delay : 20
                            });
                        });
                        </script>
	<!-- Script para seleccionar todos los ckechbox-->
	<script type="text/javascript">
                            $(document).ready(function(){

                                    //Checkbox
                                    $("input[name=checktodos]").change(function(){
                                            $('input[type=checkbox]').each( function() {			
                                                    if($("input[name=checktodos]:checked").length == 1){
                                                            this.checked = true;
                                                    } else {
                                                            this.checked = false;
                                                    }
                                            });
                                    });

                            });
                            </script>
	<?

                $this->formulario = "nom_adminCumplido"; ?>
	<form enctype='multipart/form-data' method='POST' action='index.php'
		name='<? echo $this->formulario;?>'>


		<table width="80%" align="center" border="0" cellpadding="10"
			cellspacing="0">
			<tbody>
				<tr>
					<td>
						<table class="bordered" width="100%" border="0" align="center"
							cellpadding="5 px" cellspacing="1px">
							<tr class="texto_subtitulo">
								<th>Cumplidos Solicitados</th>
							</tr>
							<tr>
								<td>
									<table class="bordered" width="100%">
										<tr class='cuadro_color'>
											<th width="5%">Id.</th>
											<th width="10%">Vigencia</th>
											<th width="10%">N&uacute;mero de Contrato</th>
											<th width="20%">Mes</th>
											<th width="20%">Fecha</th>
											<th width="20%">Estado</th>
											<th width="10%">Valor Pago ($)</th>
											<th><input name="checktodos" type="checkbox" />
											</th>

										</tr>
										<tbody id="contratistas">
											<?
                                          
											foreach ($registro as $key => $value)
											{
												$id = (isset($registro[$key]['id'])?$registro[$key]['id']:'');
												$vigencia = (isset($registro[$key]['vigencia'])?$registro[$key]['vigencia']:'');
												$num_contrato = (isset($registro[$key]['num_contrato'])?$registro[$key]['num_contrato']:'');
												$mes = (isset($registro[$key]['mes'])?$registro[$key]['mes']:'');
												$anio= (isset($registro[$key]['anio'])?$registro[$key]['anio']:'');
												$fecha = (isset($registro[$key]['fecha'])?$registro[$key]['fecha']:'');
												$estado= (isset($registro[$key]['estado'])?$registro[$key]['estado']:'');
												$num_impresiones= (isset($registro[$key]['num_impresiones'])?$registro[$key]['num_impresiones']:'');
												$valor_temporal= (isset($registro[$key]['valor'])?$registro[$key]['valor']:'');


												echo "	<tr>
				<td class='texto_elegante estilo_td'>".$id."</td>
				<td class='texto_elegante estilo_td'>".$vigencia."</td>
	 		<td class='texto_elegante estilo_td'>".$num_contrato."</td>
	 		<td class='texto_elegante estilo_td'>".$anio." - ".$mes."</td>
	 		<td class='texto_elegante estilo_td'>".$fecha."</td>
			<td class='texto_elegante estilo_td'>".$estado."</td>
			<td class='texto_elegante estilo_td'><input name='valor_solicitud_".$key."' type='text' size='8' maxlength='9'  onKeyPress='return solo_numero_sin_slash(event)' value='".$valor_temporal."'/></td>
				<td class='texto_elegante estilo_td'><input value='".$id."' name='id_solicitud_".$key."' type='checkbox' /></td>

				</tr>";

											}//fin for
											?>
										</tbody>

									</table>
									<center>
										<div class="holder"></div>
									</center>
								</td>
							</tr>
							<tr align='center'>
								<td colspan='2' rowspan='1'><br> <input type='hidden'
									name='pagina' value='nom_adminCumplido'> <input type='hidden'
									name='opcion' value='aprobar'> <input type='hidden'
									name='total_registros' value='<? echo $total_registros;?>'> <input
									value="Aprobar" name="aceptar" tabindex="<?= $tab++ ?>"
									type="button"
									onclick="document.forms['<? echo $this->formulario?>'].submit()">
								</td>
							</tr>
						</table>

					</td>
				</tr>

			</tbody>
		</table>

	</form>

	<?php

	 

    }//fin funcion


  
}
?>