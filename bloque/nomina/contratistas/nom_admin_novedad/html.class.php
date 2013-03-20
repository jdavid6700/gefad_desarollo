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


class html_adminNovedad {

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

	// funcion que muestra los datos de varios Contratistas

	function multiplesNovedades($configuracion,$registro, $total, $variable)
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

<link
	rel="stylesheet"
	href="<? echo $configuracion["host"].$configuracion["site"].$configuracion["plugins"];?>/jPages-master/css/jPages.css">

<script
	type="text/javascript"
	src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["plugins"];?>/jPages-master/js/jquery-1.8.2.min.js"></script>
<script
	src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["plugins"];?>/jPages-master/js/jPages.js"></script>

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

<table width="80%" align="center" border="0" cellpadding="10"
	cellspacing="0">
	<tbody>
		<tr>
			<td>
				<table width="100%" border="0" align="center" cellpadding="5 px"
					cellspacing="1px">

					<tr class="texto_subtitulo">
						<td>Contratistas <? echo $vigencia;?><br>
							<hr class="hr_subtitulo">
						</td>
					</tr>
					<tr>
						<td>
							<table class="bordered">
								<tr class='cuadro_color'>
									<th>Tipo Id.</th>
									<th>No. Identificaci&oacute;n</th>
									<th>Nombre</th>
									<th>N&uacute;mero de Contrato</th>
									<th>Opciones</th>
								</tr>
								<tbody id="contratistas">

									<?

									foreach ($registro as $key => $value)
									{
										$tipo = (isset($registro[$key]['TIPO_IDENTIFICACION'])?$registro[$key]['TIPO_IDENTIFICACION']:'');
										$identificacion = (isset($registro[$key]['NUM_IDENTIFICACION'])?$registro[$key]['NUM_IDENTIFICACION']:'');
										$nombre = (isset($registro[$key]['RAZON_SOCIAL'])?$registro[$key]['RAZON_SOCIAL']:'');
										$num_contrato = (isset($registro[$key]['NUM_CONTRATO'])?$registro[$key]['NUM_CONTRATO']:'');
										$interno_oc = (isset($registro[$key]['INTERNO_OC'])?$registro[$key]['INTERNO_OC']:'');
										$vigencia = (isset($registro[$key]['VIGENCIA'])?$registro[$key]['VIGENCIA']:'');
										$interno_prov = (isset($registro[$key]['INTERNO_PROVEEDOR'])?$registro[$key]['INTERNO_PROVEEDOR']:'');
										$unidad_ejec= (isset($registro[$key]['CODIGO_UNIDAD_EJECUTORA'])?$registro[$key]['CODIGO_UNIDAD_EJECUTORA']:'');

										//Con enlace a la busqueda
										$parametro = "pagina=nom_adminNovedad";
										$parametro .= "&hoja=1";
										$parametro .= "&opcion=consultar";
										$parametro .= "&accion=consultar";
										$parametro .= "&vigencia=".$vigencia;
										$parametro .= "&cod_contrato=".$num_contrato;
										$parametro .= "&cod_contratista=".$identificacion;
										$parametro .= "&tipo_id=".$tipo;
										$parametro .= "&interno_oc=".$interno_oc;
										$parametro .= "&unidad_ejec=".$unidad_ejec;
										$parametro = $cripto->codificar_url($parametro,$this->configuracion);
										$ruta="pagina=nom_adminNovedad";
										$ruta.="&opcion=crearNovedad";
										$ruta.="&cod_contrato=".$num_contrato;
										$ruta.= "&cod_contratista=".$identificacion;
										$ruta.= "&tipo_id=".$tipo;
										$ruta.="&vigencia=".$vigencia;
										$ruta.="&interno_prov=".$interno_prov;
										$ruta.= "&interno_oc=".$interno_oc;
										$ruta.= "&unidad_ejec=".$unidad_ejec;
										//$rutaCrear=$ruta;
										$rutaCrear=$cripto->codificar_url($ruta,$this->configuracion);


										echo "	<tr>
                            		<td class='texto_elegante estilo_td'>".$tipo."</td>
                            		<td class='texto_elegante estilo_td'>".$identificacion."</td>
                            		<td class='texto_elegante estilo_td'><a href='".$indice.$parametro."'>".$nombre."</a></td>
                            		<td class='texto_elegante estilo_td'>".$num_contrato."</td>
		<td class='texto_elegante estilo_td'><a href='".$indice.$rutaCrear."'><span>:: Registrar Novedad</span></a></td>
				</tr>";
											
									}//fin for
									?>
							
							</table>
							<center>
								<div class="holder"></div>
							</center>
						</td>
					</tr>
					</tbody>
				</table>

			</td>
		</tr>
		<tr>
			<td class='cuadro_plano cuadro_brown'>
				<p class="textoNivel0">Por favor realice click sobre el nombre del
					contratista que desea consultar.</p>
			</td>
		</tr>
	</tbody>
</table>

<?
	}//fin funcion multiples usuarios



	/**
	 * Funcion que muestra los datos del contrato
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

<table class="bordered" width="100%" align="center">
	<tr>
		<th colspan="6" class="estilo_th">DATOS CONTRATO</th>
	</tr>
	<tr>
		<td class='texto_elegante estilo_td'>N&uacute;mero del contrato:</td>
		<td class='texto_elegante estilo_td'><? echo  $num_contrato?></td>
		<td class='texto_elegante estilo_td centrar ancho10'>Tipo de contrato:</td>
		<td class='texto_elegante estilo_td' colspan="3"><? echo  $tipo_contrato?>
		</td>
	</tr>
	<tr>
		<td class='texto_elegante estilo_td'>Fecha contrato:</td>
		<td class='texto_elegante estilo_td'><? echo  $fecha_contrato?></td>
		<td class='texto_elegante estilo_td'>Fecha inicio del contrato:</td>
		<td class='texto_elegante estilo_td'><? echo  $fecha_inicio?></td>
		<td class='texto_elegante estilo_td'>Fecha final del contrato :</td>
		<td class='texto_elegante estilo_td'><? echo  $fecha_fin?></td>
	</tr>
	<tr>
		<td class='texto_elegante estilo_td'>Valor del Contrato:</td>
		<td class='texto_elegante estilo_td'><? echo  "$ ".number_format($valor_contrato)?>
		</td>
		<td class='texto_elegante estilo_td'>Plazo de ejecuci&oacute;n:</td>
		<td class='texto_elegante estilo_td'><? echo  $duracion?></td>
		<td class='texto_elegante estilo_td'>Unidad Ejecutora:</td>
		<td class='texto_elegante estilo_td'><? echo  $unidad_ejecutora?></td>

	</tr>

	<tr>
		<td class='texto_elegante estilo_td'>Objeto:</td>
		<td class='texto_elegante estilo_td' colspan="5"><? echo  $objeto?></td>

	</tr>

</table>
<?
    }

    /**
     * Funcion para mostrat datos de las cuentas bancarias
     * @param <array> $datos_cuenta
     */
    function mostrarDatosCuentaBanco($datos_cuenta){

                echo "<table class='bordered'  width ='100%' >";
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
     * Funcion que muestra los datos de la disponibilidad de un contrtao
     * @param <array> $datos_disponibilidad
     */
    function mostrarDatosDisponibilidad($datos_disponibilidad){
            $nro_cdp=(isset($datos_disponibilidad[0]['NUMERO_DISPONIBILIDAD'])?$datos_disponibilidad[0]['NUMERO_DISPONIBILIDAD']:'');
            $fecha_cdp=(isset($datos_disponibilidad[0]['FECHA_DISPONIBILIDAD'])?$datos_disponibilidad[0]['FECHA_DISPONIBILIDAD']:'');
            $valor_cdp=(isset($datos_disponibilidad[0]['VALOR'])?$datos_disponibilidad[0]['VALOR']:'');

            ?>

<table class='bordered' width="100%">
	<tr>
		<th colspan="6" class="estilo_th">CERTIFICADO DE DISPONIBILIDAD
			PRESUPUESTAL</th>
	</tr>
	<tr>
		<td class='texto_elegante estilo_td'>Certificado de Disponibilidad
			Presupuestal No:</td>
		<td class='texto_elegante estilo_td'><? echo  $nro_cdp?></td>
		<td class='texto_elegante estilo_td'>Fecha:</td>
		<td class='texto_elegante estilo_td'><? echo  $fecha_cdp?></td>
		<td class='texto_elegante estilo_td'>Valor:</td>
		<td class='texto_elegante estilo_td'><? echo "$ ".number_format($valor_cdp)?>
		</td>
	</tr>

</table>
<?
    }

    /**
     * Funcion que muestra los datos de registros presupuestales
     * @param <array> $datos_registro
     */
    function mostrarDatosRegistroPresupuestal($datos_registro){
            $nro_crp=(isset($datos_registro[0]['NUMERO_REGISTRO'])?$datos_registro[0]['NUMERO_REGISTRO']:'');
            $fecha_crp=(isset($datos_registro[0]['FECHA_REGISTRO'])?$datos_registro[0]['FECHA_REGISTRO']:'');
            $valor_crp=(isset($datos_registro[0]['VALOR'])?$datos_registro[0]['VALOR']:'');

            ?>

<table class='bordered' width="100%">
	<tr>
		<th colspan="6" class="estilo_th">CERTIFICADO DE REGISTRO PRESUPUESTAL</th>
	</tr>
	<tr>
		<td class='texto_elegante estilo_td'>Certificado de Registro
			Presupuestal No:</td>
		<td class='texto_elegante estilo_td'><? echo  $nro_crp?></td>
		<td class='texto_elegante estilo_td'>Fecha:</td>
		<td class='texto_elegante estilo_td'><? echo  $fecha_crp?></td>
		<td class='texto_elegante estilo_td'>Valor:</td>
		<td class='texto_elegante estilo_td'><? echo "$ ".number_format($valor_crp)?>
		</td>
	</tr>

</table>
<?
    }

     
    /**
     * Funcion que muestra los datos del contratista
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

<table class='bordered' width="100%">

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
		<td class='texto_elegante estilo_td'><? echo  $primer_nombre?></td>
		<td class='texto_elegante estilo_td'>Segundo Nombre:</td>
		<td class='texto_elegante estilo_td'><? echo  $segundo_nombre?></td>
	</tr>
	<tr>
		<td class='texto_elegante estilo_td'>Primer Apellido:</td>
		<td class='texto_elegante estilo_td'><? echo  $primer_apellido?></td>
		<td class='texto_elegante estilo_td'>Segundo Apellido:</td>
		<td class='texto_elegante estilo_td'><? echo  $segundo_apellido?></td>
	</tr>
	<tr>
		<td class='texto_elegante estilo_td'>Direcci&oacute;n:</td>
		<td class='texto_elegante estilo_td'><? echo  $direccion?></td>
		<td class='texto_elegante estilo_td'>Tel&eacute;fono:</td>
		<td class='texto_elegante estilo_td'><? echo  $telefono?></td>

	</tr>

</table>
<?
    }

    /**
     * Funcion que muestra los datos de las ordenes de pago
     * @param <array> $datos_orden
     */
    function mostrarDatosOrdenPago($datos_orden){

                echo "<table class='bordered'  width ='100%' >";
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
                echo "<table class='bordered'>";
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
     * Funcion que muestra el formulario para registrar una novedad
     * @param int $tipo
     * @param <array> $cuentas
     * @param <array> $bancos
     * @param String $tipo_cta
     * @param int $cod_contrato
     * @param int $vigencia
     * @param int $interno_prov
     * @param int $cod_contratista
     * @param String $tipo_id_contratista
     * @param String $unidad_ejec
     * @param int $interno_oc
     * @param String $tema
     * @param String $estilo
     */
    function form_novedad($tipo,$cuentas,$bancos,$tipo_cta,$cod_contrato,$vigencia,$interno_prov,$cod_contratista,$tipo_id_contratista,$unidad_ejec,$interno_oc,$tema='',$estilo='')
    {
    	$indice=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";

    	/*****************************************************************************************************/
    	include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/html.class.php");

    	$tab = 1;
    	$this->formulario = "nom_admin_novedad";
    	$this->verificar .= "seleccion_valida(".$this->formulario.",'id_tipo')";
    	$this->verificar .= "&&control_vacio(".$this->formulario.",'descripcion')";

    	$id_tipo=(isset($_REQUEST['id_tipo'])?$_REQUEST['id_tipo']:'');
    	$finicial=(isset($_REQUEST['finicial'])?$_REQUEST['finicial']:'');
    	$ffinal=(isset($_REQUEST['ffinal'])?$_REQUEST['ffinal']:'');
    	$valor=(isset($_REQUEST['valor'])?$_REQUEST['valor']:'');
    	$num_cta=(isset($_REQUEST['num_cta'])?$_REQUEST['num_cta']:'');
    	$descripcion=(isset($_REQUEST['descripcion'])?$_REQUEST['descripcion']:'');
    	$cod_contrato=(isset($cod_contrato)?$cod_contrato:'');
    	$cod_contratista=(isset($cod_contratista)?$cod_contratista:'');
    	$tipo_id_contratista = (isset($tipo_id_contratista)?$tipo_id_contratista:'');

    	$lista_tipo_novedad=$this->html->cuadro_lista($tipo,'id_tipo',$this->configuracion,-1,0,FALSE,$tab++,'id_tipo');
    	$lista_bancos=$this->html->cuadro_lista($bancos,'id_banco',$this->configuracion,-1,0,FALSE,$tab++,'id_banco');
    	$lista_tipo_cta=$this->html->cuadro_lista($tipo_cta,'id_cta_tipo',$this->configuracion,-1,0,FALSE,$tab++,'id_cta_tipo');
    	$lista_cuentas = $this->html->cuadro_lista($cuentas,'cta_id',$this->configuracion,-1,0,FALSE,$tab++,'cta_id');

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


		<table width='90%' class="bordered">
			<?
			echo "<th colspan='2'>Registrar Novedad</th>";
			?>
			<tr>
				<td width='25%' class="texto_elegante estilo_td"><?
				$texto_ayuda = "<b>Tipo de novedad.</b><br>Seleccione una opción de la lista. ";
				?><font color="red">*</font>&nbsp;<span
					onmouseover="return escape('<? echo $texto_ayuda?>')">Tipo de
						Novedad:</span>
				</td>
				<td><?
				echo $lista_tipo_novedad;
				?>
				</td>
			</tr>
			<tr>
				<td width='25%' class="texto_elegante estilo_td"><?
				$texto_ayuda = "<b>Fecha Inicio.</b><br>Fecha inicial del periodo- formato AAAA-MM-DD. ";
				?>&nbsp;<span onmouseover="return escape('<? echo $texto_ayuda?>')">Fecha
						Inicio:</span>
				</td>
				<td colspan="2"><input name="finicial" type="text" id="finicial"
					size="15" value="" readonly="readonly" /> <input align="center"
					type=image
					src="<? echo $this->configuracion['site'] . $this->configuracion['grafico']; ?>/cal.png"
					name="btnFecha" id="btnFecha"> <script type="text/javascript">  
                                        Calendar.setup({
                                            inputField    : "finicial",
                                            button        : "btnFecha",
                                            align         : "Tr"
                                        });
                                    </script>
				</td>

			</tr>
			<tr>
				<td width='25%' class="texto_elegante estilo_td"><?
				$texto_ayuda = "<b>Fecha Final.</b><br>Fecha final del periodo- formato AAAA-MM-DD. ";
				?>&nbsp;<span onmouseover="return escape('<? echo $texto_ayuda?>')">Fecha
						Final:</span>
				</td>
				<td colspan="2"><input name="ffinal" type="text" id="ffinal"
					size="15" value="" readonly="readonly" /> <input align="center"
					type=image
					src="<? echo $this->configuracion['site'] . $this->configuracion['grafico']; ?>/cal.png"
					name="btnFechaFin" id="btnFechaFin"> <script type="text/javascript">  
                                        Calendar.setup({
                                            inputField    : "ffinal",
                                            button        : "btnFechaFin",
                                            align         : "Tr"
                                        });
                                    </script>
				</td>
			</tr>
			<tr>
				<td width='25%' class="texto_elegante estilo_td"><?
				$texto_ayuda = "<b>Cuenta Bancaria.</b><br>Seleccione una opción de la lista de cuentas existentes, si no existe ingrese los datos para crearla. ";
				?>&nbsp;<span onmouseover="return escape('<? echo $texto_ayuda?>')">Cuenta
						Bancaria:</span>
				</td>
				<td>
					<table class='bordered'>
						<tr>
							<td>Cuenta existente</td>
							<td><?
							echo $lista_cuentas;
							?>
							</td>
						</tr>
						<tr>
							<td colspan="2" align="center">Crear Nueva Cuenta</td>
						</tr>

						<tr>
							<td width='20%' class="texto_elegante estilo_td"><?
							$texto_ayuda = "<b>Banco.</b><br>Seleccione una opción de la lista. ";
							?>&nbsp;<span
								onmouseover="return escape('<? echo $texto_ayuda?>')">Banco:</span>
							</td>
							<td><?
							echo $lista_bancos;
							?>
							</td>
						</tr>
						<tr>
							<td width='20%' class="texto_elegante estilo_td"><?
							$texto_ayuda = "<b>Numero de Cuenta.</b><br>Seleccione una opción de la lista. ";
							?>&nbsp;<span
								onmouseover="return escape('<? echo $texto_ayuda?>')">No.
									Cuenta:</span>
							</td>
							<td><input type='text' name='num_cta'
								value='<?if($num_cta) echo $num_cta; ?>' size='25'
								maxlength='15' tabindex='<? echo $tab++ ?>'
								oncontextmenu='return false;'
								onKeyPress="return solo_numero_sin_slash(event)">
							</td>
						</tr>
						<tr>
							<td width='20%' class="texto_elegante estilo_td"><?
							$texto_ayuda = "<b>Tipo Cuenta.</b><br>Seleccione una opción de la lista. ";
							?>&nbsp;<span
								onmouseover="return escape('<? echo $texto_ayuda?>')">Tipo
									Cuenta:</span>
							</td>
							<td><?
							echo $lista_tipo_cta;
							?>
							</td>
						</tr>
					</table>
				</td>
			</tr>


			<tr>
				<td width='25%' class="texto_elegante estilo_td"><?
				$texto_ayuda = "<b>Valor.Valor en pesos</b><br> ";
				?>&nbsp;<span onmouseover="return escape('<? echo $texto_ayuda?>')">Valor:</span>
				</td>
				<td><input type='text' name='valor'
					value='<?if($valor) echo $valor; ?>' size='25' maxlength='15'
					tabindex='<? echo $tab++ ?>' oncontextmenu='return false;'
					onKeyPress="return solo_numero_sin_slash(event)">
				</td>
			</tr>
			<tr>
				<td width='25%' class="texto_elegante estilo_td"><?
				$texto_ayuda = "<b>Descripcion.</b><br>Descripcion detallada de la novedad.";
				?><font color="red">*</font>&nbsp;<span
					onmouseover="return escape('<? echo $texto_ayuda?>')">Descripcion:</span>
				</td>
				<td><TEXTAREA name="descripcion" rows="5" cols="60"><?if($descripcion) echo $descripcion; ?></TEXTAREA>

				</td>
			</tr>
			<tr>
				<td colspan="2">
					<table width='90%' height="45">
						<tr class="tabla_alerta">
							<td colspan="5"><br>Todos los campos marcados con ( * ) son
								obligatorios. <br></td>
						</tr>
					</table>
				</td>
			</tr>

			<tr align='center'>
				<td colspan='2' rowspan='1'><br> <input type='hidden'
					name='cod_contrato' value='<? echo $cod_contrato?>'> <input
					type='hidden' name='interno_prov' value='<? echo $interno_prov?>'>
					<input type='hidden' name='interno_oc'
					value='<? echo $interno_oc?>'> <input type='hidden'
					name='cod_contratista' value='<? echo $cod_contratista?>'> <input
					type='hidden' name='tipo_id' value='<? echo $tipo_id_contratista?>'>
					<input type='hidden' name='vigencia' value='<? echo $vigencia?>'> <input
					type='hidden' name='unidad_ejec' value='<? echo $unidad_ejec?>'> <input
					type='hidden' name='action' value='nom_admin_novedad'> <input
					type='hidden' name='opcion' value='nuevo'> <input value="Guardar"
					name="aceptar" tabindex="<?= $tab++ ?>" type="button"
					onclick="if(<?= $this->verificar; ?>){document.forms['<? echo $this->formulario?>'].submit()}else{false}">
					<input name='cancelar' value='Cancelar' type='submit'
					tabindex='<?= $tab++ ?>'>
				</td>
			</tr>


		</table>


	</form>
	<br>
	<?php
    } // fin function

    /**
     * Funcion que muestra el formulario para seleccionar una vigencia
     * @param <array> $vigencias
     */
    function form_seleccionar_vigencia($vigencias){
            $tab=0;
            $this->formulario = "nom_admin_novedad";
            $_REQUEST['vigencia']=(isset($_REQUEST['vigencia'])?$_REQUEST['vigencia']:'');
            $lista_vigencias = $this->html->cuadro_lista($vigencias,'vigencia',$this->configuracion,$_REQUEST['vigencia'],0,FALSE,$tab++,'vigencia');
             
            ?>
	<form enctype='multipart/form-data' method='POST' action='index.php'
		name='<? echo $this->formulario;?>'>
		<table align="center">
			<tr>
				<td width='30%' class="texto_elegante estilo_td"><?
				$texto_ayuda = "<b>Vigencia.</b><br>Seleccione una opción de la lista. ";
				?>&nbsp;<span onmouseover="return escape('<? echo $texto_ayuda?>')">Vigencia:</span>
				</td>
				<td><?
				echo $lista_vigencias;
				?>
				</td>
				<td colspan='2' rowspan='1' align="center"><br> <input type='hidden'
					name='action' value='nom_admin_novedad'> <input type='hidden'
					name='opcion' value=''> <input value="Buscar"
					name="buscar_vigencia" tabindex="<?= $tab++ ?>" type="button"
					onclick="document.forms['<? echo $this->formulario?>'].submit()">
				</td>
			</tr>
		</table>
	</form>
	<?
        }

}
?>